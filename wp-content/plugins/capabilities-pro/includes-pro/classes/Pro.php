<?php
namespace PublishPress\Capabilities;

use PublishPress\Capabilities\Factory;

class Pro
{
    // object references
    private static $instance = null;

    public static function instance($args = [])
    {
        if (is_null(self::$instance)) {
            //$defaults = ['load_filters' => true];
            //$args = array_merge($defaults, (array)$args);
            self::$instance = new Pro($args);
            //self::$instance->load();
        }

        return self::$instance;
    }

    private function __construct()
    {
        if (!is_admin()) {
            add_filter('wp_get_nav_menu_items', [$this, 'fltNavMenuPermission'], 99, 3);
            add_action('parse_query', [$this, 'actNavMenuAccess']);
        }

        if (function_exists('bbp_get_version')) {
            require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/classes/bbPress.php');
            new bbPress();
        }
    }

    static function customStatusPermissionsAvailable() {
        return defined('PUBLISHPRESS_VERSION') && class_exists('PP_Custom_Status');
    }

    static function customStatusPostMetaPermissions($post_type = '', $post_status = '') {
        if (!self::customStatusPermissionsAvailable() || !class_exists('PublishPress\Permissions')) {
            return false;
        }

        if ($post_status) {
            if (!$attributes = \PublishPress\Permissions\Statuses::attributes()) {
                return false;
            }

            if (empty($attributes->attributes['post_status']->conditions[$post_status])) {
                return false;
            }

            if ($post_type) {
                if ($status_obj = get_post_status_object($post_status)) {
                    if (!empty($status_obj->post_type) && !in_array($post_type, $status_obj->post_type)) {
                        return false;
                    }
                }
            }
        }

        $pp = \PublishPress\Permissions::instance();
        return $pp->moduleActive('status-control') && $pp->moduleActive('collaboration');
    }

    static function getStatusCaps($cap, $post_type, $post_status) {
        if (!self::customStatusPostMetaPermissions() || !class_exists('PublishPress\Permissions\Statuses')) {
            return [$cap];
        }

        if (!$attributes = \PublishPress\Permissions\Statuses::attributes()) {
            return [$cap];
        }

        if (!isset($attributes->attributes['post_status']->conditions[$post_status])) {
            return [$cap];
        }

        $caps = [];

        if (isset($attributes->condition_metacap_map[$post_type][$cap]['post_status'][$post_status])) {
            $caps = array_merge($caps, (array) $attributes->condition_metacap_map[$post_type][$cap]['post_status'][$post_status]);
        }

        if (!empty($attributes->condition_cap_map[$cap]['post_status'][$post_status])) {
            $caps = array_merge($caps, (array) $attributes->condition_cap_map[$cap]['post_status'][$post_status]);
        }

        return $caps;
    }

    /**
     * @return EDD_SL_Plugin_Updater
     */
    public function load_updater()
    {
		//if ($this->isPro()) {
        	require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/library/Factory.php');
        	$container = \PublishPress\Capabilities\Factory::get_container();
			return $container['edd_container']['update_manager'];
		//}
    }
    
    public function keyStatus($refresh = false)
    {
        //if ($this->isPro()) {
            require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/pro-key.php');
            return _cme_key_status($refresh);
        //} else {
        //   require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes/key.php');
        //    return _presspermit_legacy_key_status($refresh);
        //}
    }

    public function keyActive($refresh = false)
    {
        return in_array($this->keyStatus($refresh), [true, 'valid', 'expired'], true);                
    }


    function NavMenuAccessDenied()
    {
        $forbidden = esc_attr__('You do not have permission to access this page.', 'capabilities-pro');
        wp_die(esc_html($forbidden));
    }

    /**
     * Checks the menu items for their visibility options and
     * removes menu items that are not visible.
     *
     * @return array
     */
    function fltNavMenuPermission($items, $menu, $args)
    {
        //return if it's admin page
        if (is_admin()) {
            return $items;
        }

        $disabled_nav_menu = '';

        $user_roles = (array)wp_get_current_user()->roles;
        $nav_menu_item_option = !empty(get_option('capsman_nav_item_menus')) ? (array)get_option('capsman_nav_item_menus') : [];

        //add loggedin and guest option to role
        if (is_user_logged_in()) {
            $user_roles[] = 'ppc_users';
        } else {
            $user_roles[] = 'ppc_guest';
        }

        /* 
         * PublishPress Permissions: Restrict Nav Menus for a Permission Group
         * (Integrate PublishPress Capabilities Pro functionality).
         *
         * Copy into functions.php, modifying $restriction_role and $permission_group_ids to match your usage.
         *
         * note: Restriction_role can be an extra role that you create just for these menu restrictions.
         *       Configure Capabilities > Nav Menus as desired for that role.
         */
        /*
        add_filter('pp_capabilities_nav_menu_apply_role_restrictions', 
            function($roles) {
                if (function_exists('presspermit')) {
                    $permission_group_ids = [12, 14, 15];   // group IDs to restrict
                    $restriction_role = 'subscriber';       // role that has restrictions defined by Capabilities > Nav Menus

                    if (array_intersect(
                        array_keys(presspermit()->getUser()->groups['pp_group']), 
                        $permission_group_ids
                    )) {
                        $roles []= $restriction_role;
                    }
                }

                return $roles;
            }
        );
        */

        // Support plugin integrations by allowing additional role-based limitations to be applied to user based on external criteria
        $user_roles = apply_filters('pp_capabilities_nav_menu_apply_role_restrictions', $user_roles, compact('menu'));

        //extract disabled menu for roles user belong
        foreach ($user_roles as $role) {
            if (array_key_exists($role, $nav_menu_item_option)) {
                $disabled_nav_menu .= implode(", ", (array)$nav_menu_item_option[$role]) . ', ';
            }
        }

        if ($disabled_nav_menu) {

            //extract only IDS
            $disabled_item_ids = preg_replace('!(0|[1-9][0-9]*)_([a-zA-Z0-9_.-]*),!s', '$1,', $disabled_nav_menu);

            $disabled_nav_menu_array = array_filter(explode(", ", $disabled_item_ids));

            foreach ($items as $key => $item) {

                $item_parent = get_post_meta($item->ID, '_menu_item_menu_item_parent', true);

                if (in_array($item->ID, $disabled_nav_menu_array) || in_array($item_parent, $disabled_nav_menu_array)) {
                    unset($items[$key]);
                }
            }


        }

        return $items;
    }

    /**
     * Checks the menu items for their privacy and remove
     * if user do not have permission to item
     *
     */
    function actNavMenuAccess($query)
    {
        if (!function_exists('wp_get_current_user')) {
            return;
        }

        $nav_menu_item_option = !empty(get_option('capsman_nav_item_menus')) ? (array)get_option('capsman_nav_item_menus') : [];

        if (!$nav_menu_item_option || !function_exists('wp_get_current_user')) {
            return;
        }

        $user_roles = (array)wp_get_current_user()->roles;

        //add loggedin and guest option to role
        $user_roles[] = (is_user_logged_in()) ? 'ppc_users' : 'ppc_guest';

        // Support plugin integrations by allowing additional role-based limitations to be applied to user based on external criteria
        $user_roles = apply_filters('pp_capabilities_nav_menu_apply_role_restrictions', $user_roles, compact('menu'));

        $disabled_nav_menu = '';

        //extract disabled menu for roles user belong
        foreach ($user_roles as $role) {
            if (array_key_exists($role, $nav_menu_item_option)) {
                $disabled_nav_menu .= implode(", ", (array)$nav_menu_item_option[$role]) . ', ';
            }
        }

        if ($disabled_nav_menu) {
            
            //we only need object id and object name e.g, 1_category
            $disabled_object = preg_replace('!(0|[1-9][0-9]*)_([a-zA-Z0-9_.-]*),!s', '$2,', $disabled_nav_menu);
            $disabled_nav_menu_array = array_filter(explode(", ", $disabled_object));

            //category tags and taxonomy page check
            if (is_category() || is_tag() || is_tax()) {
                $taxonomy_id = get_queried_object()->term_id;
                $taxonnomy_type = get_queried_object()->taxonomy;
                foreach ($disabled_nav_menu_array as $item_option) {
                    $option_object = $taxonomy_id . '_' . $taxonnomy_type;
                    if (in_array($option_object, $disabled_nav_menu_array)) {
                        $this->NavMenuAccessDenied();
                    }
                }
            }

            //post, page, cpt check
            if (is_singular()) {
                $post_type = get_post_type();
                $post_id = get_the_ID();
                foreach ($disabled_nav_menu_array as $item_option) {
                    $option_object = $post_id . '_' . $post_type;
                    if (in_array($option_object, $disabled_nav_menu_array)) {
                        $this->NavMenuAccessDenied();
                    }
                }
            }
        }
    }

} // end class
