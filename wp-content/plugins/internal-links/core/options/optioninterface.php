<?php
namespace ILJ\Core\Options;

/**
 * Option Interface
 *
 * Provides an API for a options instance
 *
 * @package ILJ\Core\Options
 *
 * @since 1.1.3
 */
interface OptionInterface
{
    /**
     * Get the unique identifier for the option
     *
     * @since  1.1.3
     * @return string
     */
    public static function getKey();

    /**
     * Get the default value of the option
     *
     * @since  1.1.3
     * @return mixed
     */
    public static function getDefault();

    /**
     * Identifies if the current option is pro only
     *
     * @since  1.1.3
     * @return bool
     */
    public static function isPro();

    /**
     * Adds the option to an option group
     *
     * @since  1.1.3
     * @param  string $option_group The option group to which the option gets connected
     * @return void
     */
    public function register($option_group);

    /**
     * Get the frontend label for the option
     *
     * @since  1.1.3
     * @return string
     */
    public function getTitle();

    /**
     * Get the frontend description for the option
     *
     * @since  1.1.3
     * @return string
     */
    public function getDescription();

    /**
     * Outputs the options form element for backend administration
     *
     * @since  1.1.3
     * @param  $value
     * @return mixed
     */
    public function renderField($value);

    /**
     * Returns a hint text for the option, if given
     *
     * @since  1.1.3
     * @return string
     */
    public function getHint();

    /**
     * Checks if a value is a valid value for option
     *
     * @since  1.2.0
     * @param  mixed $value The value that gets validated
     * @return bool
     */
    public function isValidValue($value);
}
