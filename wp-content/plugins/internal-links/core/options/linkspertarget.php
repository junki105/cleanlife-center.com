<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Help;
use ILJ\Core\Options as Options;
use ILJ\Core\Options\MultipleKeywords;

/**
 * Option: Links per target
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class LinksPerTarget extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'links_per_target';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return (int) 1;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Maximum frequency of how often a post gets linked within another one', 'internal-links');
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
        echo '<input type="number" name="' . self::getKey() . '" id="' . self::getKey() . '" value="' . $value . '"' . ($multiple_keywords ? ' disabled="disabled"' : '') . ' /> ' . Help::getOptionsLink('link-countings/', 'post-frequency', 'post frequency');
    }

    /**
     * @inheritdoc
     */
    public function isValidValue($value)
    {
        return is_numeric($value);
    }
}
