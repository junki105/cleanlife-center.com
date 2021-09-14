<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              catchplugins.com
 * @since             1.0.0
 * @package           Catch_IDs
 *
 * @wordpress-plugin
 * Plugin Name:       Catch IDs
 * Plugin URI:        https://catchplugins.com/plugins/catch-ids/
 * Description:       Catch IDs is a simple and light weight plugin to show the Post ID, Page ID, Media ID, Links ID, Category ID, Tag ID and User ID in the Admin Section Table. This plugin was initially develop to support our themes features slider. Then we thought that this will be helpful to all the WordPress Admin Users. Just activate and catch IDs in your page, post, category, tag and media pages.
 * Version:           2.2
 * Author:            Catch Plugins
 * Author URI:        catchplugins.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       catch-ids
 * Tags: catch-ids, simple, admin, wp-admin, show, ids, post, page, category, media, links, tag, user, id, post id, page id, category id
 * Domain Path:       /languages
 */

/*
Copyright (C) 2018 Catch Plugins, (info@catchplugins.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Make plugin available for translation
 * Translations can be filed in the /languages/ directory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Define Version
define( 'CATCH_IDS_VERSION', '2.2' );

// The URL of the directory that contains the plugin
if ( ! defined( 'CATCH_IDS_URL' ) ) {
	define( 'CATCH_IDS_URL', plugin_dir_url( __FILE__ ) );
}


// The absolute path of the directory that contains the file
if ( ! defined( 'CATCH_IDS_PATH' ) ) {
	define( 'CATCH_IDS_PATH', plugin_dir_path( __FILE__ ) );
}


// Gets the path to a plugin file or directory, relative to the plugins directory, without the leading and trailing slashes.
if ( ! defined( 'CATCH_IDS_BASENAME' ) ) {
	define( 'CATCH_IDS_BASENAME', plugin_basename( __FILE__ ) );
}

function catchids_load_textdomain() {
	load_plugin_textdomain( 'catch-ids', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'catchids_load_textdomain' );


/**
 * @package Catch Plugins
 * @subpackage Catch IDs
 * @since Catch IDs 1.0
 */

if ( ! function_exists( 'catchids_column' ) ) :
	/**
	 * Prepend the new column to the columns array
	 */
	function catchids_column( $cols ) {
		$column_id = array( 'catchids' => __( 'ID', 'catch-ids' ) );
		$cols      = array_slice( $cols, 0, 1, true ) + $column_id + array_slice( $cols, 1, null, true );
		return $cols;
	}

endif; // catchids_column

if ( ! function_exists( 'catchids_value' ) ) :
	/**
	 * Echo the ID for the new column
	 */
	function catchids_value( $column_name, $id ) {
		if ( 'catchids' == $column_name ) {
			echo $id;
		}
	}
endif; // catchids_value


if ( ! function_exists( 'catchids_return_value' ) ) :
	function catchids_return_value( $value, $column_name, $id ) {
		if ( 'catchids' == $column_name ) {
			$value .= $id;
		}
		return $value;
	}
endif; // catchids_return_value


if ( ! function_exists( 'catchids_css' ) ) :
	/**
	 * Output CSS for width of new column
	 */
	function catchids_css() {
		?>
<style type="text/css">
	#catchids { width: 80px; }
	@media screen and (max-width: 782px) {
		.wp-list-table #catchids, .wp-list-table #the-list .catchids { display: none; }
		.wp-list-table #the-list .is-expanded .catchids {
			padding-left: 30px;
		}
	}
</style>
		<?php
	}
endif; // catchids_css
add_action( 'admin_head', 'catchids_css' );


if ( ! function_exists( 'catchids_add' ) ) :
	/**
	 * Actions/Filters for various tables and the css output
	 */
	function catchids_add() {
		$options = catchids_get_options();
		// For Media Management
		if ( is_array( $options ) && array_key_exists( 'media', $options ) && ( 1 == $options['media'] ) ) {
			add_action( 'manage_media_columns', 'catchids_column' );
			add_filter( 'manage_media_custom_column', 'catchids_value', 10, 3 );
		}

		// For Link Management
		add_action( 'manage_link_custom_column', 'catchids_value', 10, 2 );
		add_filter( 'manage_link-manager_columns', 'catchids_column' );

		// For Category Management
		add_action( 'manage_edit-link-categories_columns', 'catchids_column' );
		add_filter( 'manage_link_categories_custom_column', 'catchids_return_value', 10, 3 );

		// For Category, Tags and other custom taxonomies Management
		foreach ( get_taxonomies() as $taxonomy ) {
			if ( is_array( $options ) && array_key_exists( 'category', $options ) && ( 1 == $options['category'] ) ) {
				add_action( "manage_edit-${taxonomy}_columns", 'catchids_column' );
				add_filter( "manage_${taxonomy}_custom_column", 'catchids_return_value', 10, 3 );
				if ( version_compare( $GLOBALS['wp_version'], '3.0.999', '>' ) ) {
					add_filter( "manage_edit-${taxonomy}_sortable_columns", 'catchids_column' );
				}
			}
		}

		foreach ( get_post_types() as $ptype ) {
			if ( is_array( $options ) && array_key_exists( $ptype, $options ) && ( 1 == $options[ $ptype ] ) ) {
				add_action( "manage_edit-${ptype}_columns", 'catchids_column' );
				add_filter( "manage_${ptype}_posts_custom_column", 'catchids_value', 10, 3 );
				if ( version_compare( $GLOBALS['wp_version'], '3.0.999', '>' ) ) {
					add_filter( "manage_edit-${ptype}_sortable_columns", 'catchids_column' );
				}
			}
		}

		// For User Management
		if ( is_array( $options ) && array_key_exists( 'user', $options ) && ( 1 == $options['user'] ) ) {
			add_action( 'manage_users_columns', 'catchids_column' );
			add_filter( 'manage_users_custom_column', 'catchids_return_value', 10, 3 );
			if ( version_compare( $GLOBALS['wp_version'], '3.0.999', '>' ) ) {
				add_filter( 'manage_users_sortable_columns', 'catchids_column' );
			}
		}

		// For Comment Management
		if ( is_array( $options ) && array_key_exists( 'comment', $options ) && ( 1 == $options['comment'] ) ) {
			add_action( 'manage_edit-comments_columns', 'catchids_column' );
			add_action( 'manage_comments_custom_column', 'catchids_value', 10, 2 );
			if ( version_compare( $GLOBALS['wp_version'], '3.0.999', '>' ) ) {
				add_filter( 'manage_edit-comments_sortable_columns', 'catchids_column' );
			}
		}
	}
endif; // catchids_add
add_action( 'admin_init', 'catchids_add' );


if ( ! function_exists( 'catchids_get_all_post_types' ) ) :
	function catchids_get_all_post_types() {
		$post_types     = get_post_types( array( 'public' => true ) );
		$post_type_list = array();
		foreach ( $post_types as $key => $value ) {
			if ( 'attachment' != $key ) {
				$data                   = str_replace( '-', ' ', $value );
				$data                   = str_replace( '_', ' ', $data );
				$post_type_list[ $key ] = ucwords( $data );
			}
		}
		return $post_type_list;
	}
endif; // catchids_get_all_post_types


if ( ! function_exists( 'catchids_get_options' ) ) :
	function catchids_get_options() {
		$defaults = catchids_default_options();
		$options  = get_option( 'catchids_options', $defaults );

		return wp_parse_args( $options, $defaults );
	}
endif; // catchids_get_options


if ( ! function_exists( 'catchids_default_options' ) ) :
	/**
	 * Return array of default options
	 *
	 * @since     1.0
	 * @return    array    default options.
	 */
	function catchids_default_options( $option = null ) {
		$types = catchids_get_all_post_types();
		foreach ( $types as $key => $value ) {
			$default_options[ $key ] = 1;
		}
		$default_options['category']          = 1;
		$default_options['media']             = 1;
		$default_options['user']              = 1;
		$default_options['comment']           = 1;
		$default_options['theme_plugin_tabs'] = 1;

		if ( null == $option ) {
			return apply_filters( 'catchids_options', $default_options );
		} else {
			return $default_options[ $option ];
		}
	}
endif; // catchids_default_options


if ( ! function_exists( 'catchids_add_plugin_settings_menu' ) ) :
	function catchids_add_plugin_settings_menu() {
		add_menu_page(
			esc_html__( 'Catch IDs', 'catch-ids' ), //page title
			esc_html__( 'Catch IDs', 'catch-ids' ), //menu title
			'edit_posts', //capability needed
			'catch-ids', //menu slug (and page query url)
			'catchids_settings',
			'dashicons-editor-ol',
			'99.01564'
		);
	}
endif; // catchids_add_plugin_settings_menu
add_action( 'admin_menu', 'catchids_add_plugin_settings_menu' );


if ( ! function_exists( 'catchids_settings' ) ) :
	function catchids_settings() {
		$child_theme = false;
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'catch-ids' ) );
		}

		require_once plugin_dir_path( __FILE__ ) . 'partials/catch-ids-display.php';
	}
endif; // catchids_settings


if ( ! function_exists( 'catchids_register_settings' ) ) :
	/**
	 * Catch IDs: register_settings
	 * Catch IDs Register Settings
	 */
	function catchids_register_settings() {
		register_setting(
			'catchids-group',
			'catchids_options',
			'catchids_sanitize_callback'
		);
	}
endif; // catchids_register_settings
add_action( 'admin_init', 'catchids_register_settings' );


if ( ! function_exists( 'catchids_action_links' ) ) :
	/**
	 * Catch_IDs: catchids_action_links
	 * Catch_IDs Settings Link function callback
	 *
	 * @param arrray $links Link url.
	 *
	 * @param arrray $file File name.
	 */
	function catchids_action_links( $links, $file ) {
		if ( $file === 'catch-ids/catch-ids.php' ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=catch-ids' ) ) . '">' . esc_html__( 'Settings', 'catch-ids' ) . '</a>';

			array_unshift( $links, $settings_link );
		}
		return $links;
	}
endif; // catchids_action_links
add_filter( 'plugin_action_links', 'catchids_action_links', 10, 2 );


if ( ! function_exists( 'catchids_enqueue_styles' ) ) :
	function catchids_enqueue_styles() {
		if ( isset( $_GET['page'] ) && 'catch-ids' == $_GET['page'] ) {
			wp_enqueue_style( 'catchids-styles', plugin_dir_url( __FILE__ ) . 'css/catch-ids.css', array(), '1.0', 'all' );
			wp_enqueue_style( 'catch-ids-dashboard-tabs', plugin_dir_url( __FILE__ ) . 'css/admin-dashboard.css', array(), '1.0', 'all' );
		}
	}
endif; // catchids_enqueue_styles
add_action( 'admin_enqueue_scripts', 'catchids_enqueue_styles' );


if ( ! function_exists( 'catchids_enqueue_scripts' ) ) :
	function catchids_enqueue_scripts() {
		if ( isset( $_GET['page'] ) && 'catch-ids' == $_GET['page'] ) {
			wp_enqueue_script( 'catch-ids-match-height', plugin_dir_url( __FILE__ ) . 'js/jquery.matchHeight.min.js', array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'catchids-scripts', plugin_dir_url( __FILE__ ) . 'js/catch-ids.js', array( 'jquery', 'catch-ids-match-height' ), '1.0', false );
		}

	}
endif; // catchids_enqueue_scripts
add_action( 'admin_enqueue_scripts', 'catchids_enqueue_scripts' );


add_action( 'wp_ajax_catchids_switch', 'catchids_switch' );
if ( ! function_exists( 'catchids_switch' ) ) :
	function catchids_switch() {
		$value = ( 'true' == $_POST['value'] ) ? 1 : 0;

		$option_name = $_POST['option_name'];

		$option_value = catchids_get_options( 'catchids_options' );

		$option_value[ $option_name ] = $value;

		if ( update_option( 'catchids_options', $option_value ) ) {
			echo $value;
		} else {
			esc_html_e( 'Connection Error. Please try again.', 'catch-ids' );
		}

		wp_die(); // this is required to terminate immediately and return a proper response
	}
endif; // catchids_switch

add_action( 'wp_ajax_ctp_switch', 'ctp_switch' );
if ( ! function_exists( 'ctp_switch' ) ) :
	function ctp_switch() {
		$value = ( 'true' == $_POST['value'] ) ? 1 : 0;

		$option_name = $_POST['option_name'];

		$option_value = catchids_get_options( 'catchids_options' );

		$option_value[ $option_name ] = $value;

		if ( update_option( 'catchids_options', $option_value ) ) {
			echo $value;
		} else {
			esc_html_e( 'Connection Error. Please try again.', 'catch-ids' );
		}

		wp_die(); // this is required to terminate immediately and return a proper response
	}
endif; // catchids_switch

$options = catchids_get_options();
//print_r($options); die();
if ( 1 == $options['theme_plugin_tabs'] ) {
	/* Adds Catch Themes tab in Add theme page and Themes by Catch Themes in Customizer's change theme option. */
	if ( ! class_exists( 'CatchThemesThemePlugin' ) && ! function_exists( 'add_our_plugins_tab' ) ) {
		require plugin_dir_path( __FILE__ ) . 'includes/CatchThemesThemePlugin.php';
	}
}

/* Adds support link and review link in plugin page */
// Only visible if the plugin is active
add_filter( 'plugin_row_meta', 'catchids_add_plugin_meta_links', 10, 2 );
function catchids_add_plugin_meta_links( $meta_fields, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {

		$meta_fields[] = "<a href='https://catchplugins.com/support-forum/forum/catch-ids/' target='_blank'>Support Forum</a>";
		$meta_fields[] = "<a href='https://wordpress.org/support/plugin/catch-ids/reviews#new-post' target='_blank' title='Rate'>
		        <i class='ct-rate-stars'>"
		  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
		  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
		  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
		  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
		  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
		  . '</i></a>';

		$stars_color = '#ffb900';

		echo '<style>'
			. '.ct-rate-stars{display:inline-block;color:' . $stars_color . ';position:relative;top:3px;}'
			. '.ct-rate-stars svg{fill:' . $stars_color . ';}'
			. '.ct-rate-stars svg:hover{fill:' . $stars_color . '}'
			. '.ct-rate-stars svg:hover ~ svg{fill:none;}'
			. '</style>';
	}

	return $meta_fields;
}
