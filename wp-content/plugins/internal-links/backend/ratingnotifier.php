<?php

namespace ILJ\Backend;

use  ILJ\Core\Options ;
use  ILJ\Helper\Help ;
/**
 * Rating notifier
 *
 * Responsible for the rating notification on the backend
 *
 * @package ILJ\Backend
 *
 * @since 1.2.0
 */
class RatingNotifier
{
    /**
     * Initializes the rating notifier
     *
     * @return void
     * @since  1.2.0
     */
    public static function init()
    {
        $rating_notification_base = User::getRatingNotificationBaseDate();
        if ( $rating_notification_base > new \DateTime( 'now' ) || !User::canShowRatingNotification() ) {
            return;
        }
        add_action( 'admin_notices', [ '\\ILJ\\Backend\\RatingNotifier', 'registerNotifier' ] );
        add_action( 'admin_enqueue_scripts', [ '\\ILJ\\Backend\\RatingNotifier', 'registerAssets' ] );
    }
    
    /**
     * Registers all assets for the frontend rating notification
     *
     * @return void
     * @since  1.2.0
     */
    public static function registerAssets()
    {
        wp_enqueue_script(
            'ilj_index_rating_notification',
            ILJ_URL . 'admin/js/ilj_rating_notification.js',
            [],
            ILJ_VERSION
        );
    }
    
    /**
     * Responsible for the notifier screen in the admin dashboard
     *
     * @since 1.2.0
     *
     * @return void
     */
    public static function registerNotifier()
    {
        $notification_template = '<div class="%1$s"><p><strong>%2$s</strong></p><p>%3$s</p>%4$s</div>';
        $class = esc_attr( 'notice notice-info is-dismissible' );
        $message = '<p>' . __( 'Hey', 'internal-links' ) . ' ' . User::getName() . ', ' . __( 'you have been using the Internal Link Juicer for a while now - that\'s great!', 'internal-links' ) . '</p><p>' . __( 'Could you do us a big favor and <strong>give us your review on WordPress.org</strong>? This will help us to increase our visibility and to develop even <strong>more features for you</strong>.', 'internal-links' ) . '</p><p>' . __( 'Thanks!', 'internal-links' ) . '</p>';
        $buttons = '<div style="margin-bottom: 15px;">' . sprintf( '<a class="button button-primary" style="margin-right: 15px;" href="%s" target="_blank" rel="noopener">%s</a>', 'https://wordpress.org/support/plugin/internal-links/reviews/#new-post', '<span class="dashicons dashicons-thumbs-up" style="line-height:28px;"></span> ' . __( 'Of course, you deserve it', 'internal-links' ) ) . sprintf( '<a class="ilj-rating-notification-add button" style="background:none;margin-right: 15px;" href="#" data-add="%d">%s</a>', 5, '<span class="dashicons dashicons-backup" style="line-height:28px;"></span> ' . __( 'Please remind me later', 'internal-links' ) ) . sprintf( '<a class="ilj-rating-notification-add button" style="background:none;" href="#" data-add="%d">%s</a>', -1, __( 'Hide this information forever', 'internal-links' ) ) . '</div>';
        printf(
            $notification_template,
            $class,
            'Internal Link Juicer:',
            $message,
            $buttons
        );
        return;
    }

}