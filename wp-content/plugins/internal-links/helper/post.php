<?php

namespace ILJ\Helper;

use  ILJ\Backend\IndexRebuildNotifier ;
use  ILJ\Core\IndexBuilder ;
use  ILJ\Core\Options ;
use  ILJ\Core\Options\OptionInterface ;
/**
 * Post toolset
 *
 * Methods for handling POST requests
 *
 * @package ILJ\Helper
 *
 * @since 1.1.3
 */
class Post
{
    /**
     * Handles the process of resetting options from a given section to default.
     *
     * @since  1.1.3
     * @return bool
     */
    public static function resetOptionsAction()
    {
        if ( !check_admin_referer( Options::KEY ) || !current_user_can( 'manage_options' ) ) {
            return false;
        }
        $url = wp_get_referer();
        if ( !$url ) {
            $url = admin_url();
        }
        
        if ( filter_has_var( INPUT_POST, 'ilj-reset-options' ) && filter_has_var( INPUT_POST, 'section' ) && filter_has_var( INPUT_POST, 'action' ) && $_POST['action'] === Options::KEY ) {
            $section = Options::getSection( $_POST['section'] );
            if ( $section ) {
                foreach ( $section['options'] as $option ) {
                    if ( !$option instanceof OptionInterface ) {
                        continue;
                    }
                    delete_option( $option::getKey() );
                }
            }
            Options::setOptionsDefault();
            do_action( IndexBuilder::ILJ_ACTION_TRIGGER_BUILD_INDEX );
        }
        
        wp_safe_redirect( esc_url_raw( $url ) );
        exit;
    }

}