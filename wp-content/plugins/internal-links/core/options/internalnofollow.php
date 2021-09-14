<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Options as OptionsHelper;

/**
 * Option: Make internal links nofollow
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class InternalNofollow extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'internal_nofollow';
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
        return __('NoFollow for internal keyword links', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('Sets the <code>rel="nofollow"</code> attribute for keyword links (<strong>not recommended</strong>).', 'internal-links');
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
