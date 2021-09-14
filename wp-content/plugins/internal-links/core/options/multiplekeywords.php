<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Help;
use ILJ\Helper\Options as OptionsHelper;

/**
 * Option: Multi-keyword mode
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class MultipleKeywords extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'multiple_keywords';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Link as often as possible', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('Allows posts and keywords to get linked as often as possible.', 'internal-links') . Help::getOptionsLink('link-countings/', 'greedy-mode', 'greedy mode') . '<br>' . __('Deactivates all other restrictions', 'internal-links');;
    }

    /**
     * @inheritdoc
     */
    public function renderField($value)
    {
        $checked = checked(1, $value, false);
        OptionsHelper::renderToggle($this, $checked);
    }

    /**
     * @inheritdoc
     */
    public function isValidValue($value)
    {
        return 1 === (int) $value || 0 === (int) $value;
    }
}
