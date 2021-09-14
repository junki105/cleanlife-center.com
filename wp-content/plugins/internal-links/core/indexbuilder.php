<?php
namespace ILJ\Core;

use ILJ\Core\IndexStrategy\DefaultStrategy;
use ILJ\Core\IndexStrategy\StrategyInterface;
use ILJ\Type\Ruleset;
use ILJ\Helper\Keyword;
use ILJ\Helper\Encoding;
use ILJ\Database\Postmeta;
use ILJ\Helper\IndexAsset;
use ILJ\Database\Linkindex;
use ILJ\Helper\Replacement;
use ILJ\Backend\Environment;
use ILJ\Enumeration\KeywordOrder;

/**
 * IndexBuilder
 *
 * This class is responsible for the creation of the links
 *
 * @package ILJ\Core
 *
 * @since 1.0.0
 */
class IndexBuilder
{
    const ILJ_ACTION_TRIGGER_BUILD_INDEX = 'ilj_trigger_build_index';
    const ILJ_ACTION_AFTER_INDEX_BUILT = 'ilj_after_index_built';
    const ILJ_FILTER_INDEX_STRATEGY = 'ilj_index_strategy';

    /**
     * @var   StrategyInterface|null
     * @since 1.2.0
     */
    protected $strategy = null;

    public function __construct()
    {
        $link_options = [];
        $link_options['multi_keyword_mode'] = (bool) Options::getOption(\ILJ\Core\Options\MultipleKeywords::getKey());
        $link_options['links_per_page']     = ($link_options['multi_keyword_mode'] === false) ? Options::getOption(\ILJ\Core\Options\LinksPerPage::getKey()) : 0;
        $link_options['links_per_target']   = ($link_options['multi_keyword_mode'] === false) ? Options::getOption(\ILJ\Core\Options\LinksPerTarget::getKey()) : 0;

        $strategy = new DefaultStrategy();

        /**
         * Filter and change the strategy that gets used to build the index
         *
         * WARNING: Changing this data may throw an exception.
         *
         * @since 1.2.0
         *
         * @param StrategyInterface $strategy
         */
        $strategy = apply_filters(self::ILJ_FILTER_INDEX_STRATEGY, $strategy);

        if (!$strategy instanceof StrategyInterface) {
            throw new \Exception('The filtered strategy must implement StrategyInterface.');
        }

        $strategy->setLinkOptions($link_options);
        $this->setStrategy($strategy);
    }

    /**
     * Executes all processes for building a new index
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function buildIndex()
    {
        if (!$this->strategy) {
            return [];
        }

        $start = microtime(true);

        $this->removeIndices();

        $entries_count = $this->strategy->setIndices();
        $duration      = round((microtime(true) - $start), 2);

        $offset  = get_option('gmt_offset');
        $hours   = (int) $offset;
        $minutes = ($offset - floor($offset)) * 60;

        $feedback = [
            "last_update" => [
                "date"     => new \DateTime('now', new \DateTimeZone(sprintf('%+03d:%02d', $hours, $minutes))),
                "entries"  => $entries_count,
                "duration" => $duration
            ]
        ];

        Environment::update('linkindex', $feedback);

        /**
         * Fires after the index got built.
         *
         * @since 1.0.0
         */
        do_action(self::ILJ_ACTION_AFTER_INDEX_BUILT);

        return $feedback;
    }

    /**
     * Sets the index building strategy
     *
     * @since 1.2.0
     * @param StrategyInterface $strategy The strategy that gets used to build the index
     *
     * @return void
     */
    public function setStrategy(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Flushes the existing linkindex database table
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function removeIndices()
    {
        Linkindex::flush();
    }
}
