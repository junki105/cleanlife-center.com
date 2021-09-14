<?php
namespace ILJ\Core\Options;

/**
 * Abstract implementation of OptionInterface
 *
 * Provides some generic defaults for option instances
 *
 * @package ILJ\Core\Options
 *
 * @since 1.1.3
 */
abstract class AbstractOption implements OptionInterface
{
    const ILJ_OPTIONS_PREFIX = "ilj_settings_field_";

    /**
     * @inheritdoc
     */
    public static function isPro()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function register($option_group)
    {
        register_setting($option_group, static::getKey());
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getHint()
    {
        return '';
    }
}
