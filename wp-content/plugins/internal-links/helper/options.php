<?php
namespace ILJ\Helper;

use ILJ\Core\Options\OptionInterface;

/**
 * Options toolset
 *
 * Methods for rendering of options administration form stuff
 *
 * @package ILJ\Helper
 */
class Options
{
    /**
     * Generates the title
     *
     * @since 1.1.3
     * @param OptionInterface $option The option
     *
     * @return string
     */
    public static function getTitle(OptionInterface $option)
    {
        if (!$option->isPro() || (\ILJ\ilj_fs()->is__premium_only() && \ILJ\ilj_fs()->can_use_premium_code())) {
            return $option->getTitle();
        }

        return sprintf('<div class="pro-title"><span class="dashicons dashicons-lock tip" title="' . __('This feature is part of the Pro version', 'internal-links') . '"></span> %s</div>', $option->getTitle());
    }

    /**
     * Decides wheter a form field is visually disabled
     *
     * @since 1.1.3
     * @param OptionInterface $option The option
     *
     * @return string
     */
    public static function getDisabler(OptionInterface $option)
    {
        if (!$option->isPro() || (\ILJ\ilj_fs()->is__premium_only() && \ILJ\ilj_fs()->can_use_premium_code())) {
            return '';
        }

        return ' class="pro-setting" disabled ';
    }

    /**
     * Renders the form field
     *
     * @since 1.1.3
     * @param OptionInterface $option The option
     * @param $value The value
     *
     * @return void
     */
    public static function renderFieldComplete(OptionInterface $option, $value)
    {
        if ($option->isPro() && (!\ILJ\ilj_fs()->is__premium_only() || !\ILJ\ilj_fs()->can_use_premium_code())) {
            echo '<div class="pro-setting">';
        }

        $option->renderField($value);
        echo $option->getDescription() != '' ? '<p class="description">' . $option->getDescription() . '</p>' : '';
        echo $option->getHint() != '' ? $option->getHint() : '';

        if ($option->isPro() && (!\ILJ\ilj_fs()->is__premium_only() || !\ILJ\ilj_fs()->can_use_premium_code())) {
            echo '</div>';
        }
    }

    /**
     * Renders a fancy toggler for checkboxes that follow OptionInterface
     *
     * @since 1.1.3
     * @param OptionInterface $option  The Option
     * @param bool            $checked Active state of the toggler
     *
     * @return void
     */
    public static function renderToggle(OptionInterface $option, $checked)
    {
        $disabled = self::getDisabler($option) ? 'disabled' : '';
        echo self::getToggleField($option::getKey(), $checked, $disabled);
    }

    /**
     * Fundamental method for rendering a toggle output
     *
     * @since 1.2.0
     * @param int    $id       The id/name of the checkbox input field
     * @param bool   $checked  Active state of the toggler
     * @param string $disabled HTML-Part for disabling form field
     *
     * @return void
     */
    public static function getToggleField($id, $checked, $disabled = '')
    {
        $output = '';

        $output .= '<div class="ilj-toggler-wrap">';
        $output .= sprintf('<input class="ilj-toggler-input" type="checkbox" id="%1$s" name="%1$s" value="1" %2$s %3$s />', $id, $checked, $disabled);
        $output .= sprintf('<label class="ilj-toggler-label" for="%s">', $id);
        $output .= '<div class="ilj-toggler-switch" aria-hidden="true">';
        $output .= '<div class="ilj-toggler-option-l" aria-hidden="true">';
        $output .= '<svg class="ilj-toggler-svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="548.9" height="548.9" viewBox="0 0 548.9 548.9" xml:space="preserve"><polygon points="449.3 48 195.5 301.8 99.5 205.9 0 305.4 95.9 401.4 195.5 500.9 295 401.4 548.9 147.5 "/></svg>';
        $output .= '</div>';
        $output .= '<div class="ilj-toggler-option-r" aria-hidden="true">';
        $output .= '<svg class="ilj-toggler-svg" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" viewBox="0 0 28 28" xml:space="preserve"><polygon points="28 22.4 19.6 14 28 5.6 22.4 0 14 8.4 5.6 0 0 5.6 8.4 14 0 22.4 5.6 28 14 19.6 22.4 28 " fill="#030104"/></svg>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</label>';
        $output .= '</div>';

        return $output;
    }
}
