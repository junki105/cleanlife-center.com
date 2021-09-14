<?php
namespace ILJ\Backend;

/**
 * User Environment
 *
 * Singleton, that has information about the current users (meta) data regarding the plugin
 *
 * @package ILJ\Backend
 * @since   1.1.2
 */
class User
{
    const ILJ_META_USER = 'ilj_user';

    /**
     * @var   User
     * @since 1.1.2
     */
    private static $instance;

    /**
     * @var   int
     * @since 1.1.2
     */
    private $user_id;

    /**
     * @var   array
     * @since 1.1.2
     */
    private $user_data;

    protected function __construct()
    {
        $user_data_default = [
            'hide_promo' => false,
            'last_trigger' => null
        ];

        $user_id   = get_current_user_id();
        $user_data = get_user_meta($user_id, self::ILJ_META_USER, true);

        $this->user_id   = $user_id;
        $this->user_data = wp_parse_args($user_data, $user_data_default);
    }

    /**
     * Get data
     *
     * @since  1.1.2
     * @param  string $key The key
     * @return string|bool
     */
    public static function get($key)
    {
        self::init();

        $user_data = self::$instance->user_data;
        if (array_key_exists($key, $user_data)) {
            return $user_data[$key];
        }
        return false;
    }

    /**
     * Update data
     *
     * @since  1.1.2
     * @param  string $key   The key
     * @param  mixed  $value The value
     * @return void
     */
    public static function update($key, $value)
    {
        self::init();
        self::$instance->user_data[$key] = $value;
        update_user_meta(self::$instance->user_id, self::ILJ_META_USER, self::$instance->user_data);
    }

    /**
     * Init User class
     *
     * @since  1.1.2
     * @return void
     */
    private static function init()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
    }

    /**
     * Retrieves the base date for the rating notification
     *
     * @since  1.2.0
     * @return \DateTime
     */
    public static function getRatingNotificationBaseDate()
    {
        $rating_notification = self::get('rating_notification');

        if (!$rating_notification) {
            $rating_notification = [];
        }

        if (!array_key_exists('base_date', $rating_notification)) {
            $base_date = new \DateTime('now');
            $base_date->modify('+14 day');
            $rating_notification['base_date'] = $base_date;

            self::update('rating_notification', $rating_notification);
        }

        return $rating_notification['base_date'];
    }

    /**
     * Indicates if the rating notification can be shown
     *
     * @since  1.2.2
     * @return bool
     */
    public static function canShowRatingNotification()
    {
        $rating_notification = self::get('rating_notification');

        if (!$rating_notification) {
            $rating_notification = [];
        }

        if (!array_key_exists('show', $rating_notification)) {
            $rating_notification['show'] = true;
            self::update('rating_notification', $rating_notification);
        }

        return $rating_notification['show'];
    }

    /**
     * Sets a new base date for rating notifier
     *
     * @since  1.2.0
     * @param  \DateTime $date The new date
     * @return void
     */
    public static function setRatingNotificationBaseDate(\DateTime $date)
    {
        $rating_notification = self::get('rating_notification');
        $rating_notification['base_date'] = $date;
        self::update('rating_notification', $rating_notification);
    }

    /**
     * Unsets the rating notification
     *
     * @since  1.2.2
     * @return void
     */
    public static function unsetRatingNotification()
    {
        $rating_notification = self::get('rating_notification');
        $rating_notification['show'] = false;
        self::update('rating_notification', $rating_notification);
    }

    /**
     * Returns the name of the current user
     *
     * @since  1.2.0
     * @return string
     */
    public static function getName()
    {
        $user = wp_get_current_user();
        return $user->data->display_name;
    }
}
