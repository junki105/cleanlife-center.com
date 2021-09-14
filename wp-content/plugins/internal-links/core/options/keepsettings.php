<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Help;
use ILJ\Helper\Options as OptionsHelper;

/**
 * Option: Keep existing settings and configured keywords
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class KeepSettings extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'keep_settings';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Keep configured keywords and plugin settings after plugin deactivation', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('If activated, all your configured keywords and your plugin settings will remain saved - if not, everything from Internal Link Juicer gets deleted when you deactivate the plugin.', 'internal-links');
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
