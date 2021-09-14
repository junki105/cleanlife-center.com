<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Help;
use ILJ\Core\Options as Options;
use ILJ\Core\Options\MultipleKeywords;

/**
 * Option: Links per page
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class LinksPerPage extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'links_per_page';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return (int) 0;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Maximum amount of links per post', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('For an unlimited number of links, set this value to <code>0</code> .', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function renderField($value)
    {
        $multiple_keywords = Options::getOption(MultipleKeywords::getKey());
        echo '<input type="number" name="' . self::getKey() . '" id="' . self::getKey() . '" value="' . $value . '"' . ($multiple_keywords ? ' disabled="disabled"' : '') . ' /> ' . Help::getOptionsLink('link-countings/', 'links-per-post-amount', 'links per post');
    }

    /**
     * @inheritdoc
     */
    public function isValidValue($value)
    {
        return is_numeric($value);
    }
}
