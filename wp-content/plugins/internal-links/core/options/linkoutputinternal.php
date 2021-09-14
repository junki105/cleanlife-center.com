<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Help;

/**
 * Option: Link template for internal links
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class LinkOutputInternal extends AbstractOption
{

    /**
     * @inheritdoc
     */
    public function register($option_group)
    {
        register_setting(
            $option_group, static::getKey(), [
            'type'              => 'string',
            'sanitize_callback' => 'esc_html'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'link_output_internal';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return esc_html('<a href="{{url}}">{{anchor}}</a>');
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Template for the link output (keyword links)', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('Markup for the output of generated internal links.', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function renderField($value)
    {
        echo '<input type="text" name="' . self::getKey() . '" id="' . self::getKey() . '" value="' . $value . '" /> ' . Help::getOptionsLink('link-templates/', '', 'link templates');
    }

    /**
     * @inheritdoc
     */
    public function getHint()
    {
        return '<p>' . __('You can use the placeholders <code>{{url}}</code> for the target and <code>{{anchor}}</code> for the generated anchor text.', 'internal-links') . '</p>';
    }

    /**
     * @inheritdoc
     */
    public function isValidValue($value)
    {
        return is_string($value);
    }
}
