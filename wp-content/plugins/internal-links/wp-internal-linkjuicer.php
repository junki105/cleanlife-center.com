<?php

/**
 * Internal Link Juicer
 *
 * @version 1.2.14
 * @package ILJ
 *
 * @wordpress-plugin
 * Plugin Name: Internal Link Juicer
 * Plugin URI: https://internallinkjuicer.com
 * Version: 1.2.14
 * Description: A performant solution for high class internal linkbuilding automation.
 * Author: Internal Link Juicer
 * Author URI: https://internallinkjuicer.com
 * License: GPL v3
 * Domain Path: /languages/
 * Text Domain: internal-links
 *
 * Internal Link Juicer
 * Copyright (C) 2018, Mark Schatz - dev@internallinkjuicer.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace ILJ;

use  ILJ\Core\App ;

if ( !function_exists( 'add_filter' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

if ( !defined( 'ILJ_VERSION' ) ) {
    define( 'ILJ_VERSION', '1.2.14' );
}
if ( !defined( 'ILJ_FILE' ) ) {
    define( 'ILJ_FILE', __FILE__ );
}
if ( !defined( 'ILJ_PATH' ) ) {
    define( 'ILJ_PATH', plugin_dir_path( ILJ_FILE ) );
}
if ( !defined( 'ILJ_URL' ) ) {
    define( 'ILJ_URL', plugin_dir_url( ILJ_FILE ) );
}
if ( !defined( 'ILJ_NAME' ) ) {
    define( 'ILJ_NAME', plugin_basename( ILJ_FILE ) );
}

if ( function_exists( '\\ILJ\\ilj_fs' ) ) {
    ilj_fs()->set_basename( false, __FILE__ );
    return;
} else {
    function ilj_fs()
    {
        global  $ilj_fs ;
        
        if ( !isset( $ilj_fs ) ) {
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $ilj_fs = fs_dynamic_init( array(
                'id'              => '2610',
                'slug'            => 'internal-links',
                'type'            => 'plugin',
                'public_key'      => 'pk_624b0309eb6afc65f81d070e79fcd',
                'is_premium'      => false,
                'premium_suffix'  => '(Pro)',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'has_affiliation' => 'selected',
                'menu'            => array(
                'slug'        => 'internal_link_juicer',
                'first-path'  => 'admin.php?page=internal_link_juicer-tour',
                'support'     => false,
                'affiliation' => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $ilj_fs;
    }
    
    ilj_fs();
    do_action( 'ilj_fs_loaded' );
    spl_autoload_register( function ( $class_name ) {
        
        if ( false !== strpos( $class_name, 'ILJ\\' ) ) {
            $file = strtolower( str_replace( '\\', '/', substr( $class_name, 4 ) ) ) . '.php';
            if ( file_exists( ILJ_PATH . $file ) ) {
                include_once $file;
            }
        }
    
    } );
    App::init();
}
