<?php
namespace PublishPress\Capabilities;

class AdminFiltersPro {
    function __construct() {
        add_action('init', [$this, 'versionInfoRedirect'], 1);
        add_action('admin_init', [$this, 'loadUpdater']);

        add_action('publishpress-caps_manager-load', [$this, 'CapsManagerLoad']);
        add_action('admin_print_styles', array($this, 'adminStyles'));

        add_action('pp-capabilities-settings-ui', [$this, 'settingsUI']);
        add_action('pp-capabilities-update-settings', [$this, 'updateSettings']);

        add_action('publishpress-caps_manager_postcaps_section', [$this, 'capsManagerUI']);

        //add_action('publishpress-caps_sidebar_bottom', [$this, 'sidebarUI']);

        add_action('publishpress-caps_process_update', [$this, 'updateOptions']);

		add_action('pp-capabilities-admin-submenus', [$this, 'actCapabilitiesSubmenus']);
    }

    public function settingsUI() {
        require_once(dirname(__FILE__).'/settings-ui.php');
        new Pro_Settings_UI();
    }

    public function updateSettings() {
        require_once(dirname(__FILE__).'/settings-handler.php');
        new Pro_Settings_Handler();
    }

    function actCapabilitiesSubmenus() {
        $cap_name = (is_multisite() && is_super_admin()) ? 'read' : 'manage_capabilities';
        
        add_submenu_page('pp-capabilities',  __('Admin Menus', 'capsman-enhanced'), __('Admin Menus', 'capsman-enhanced'), $cap_name, 'pp-capabilities-admin-menus', [$this, 'ManageAdminMenus']);
        add_submenu_page('pp-capabilities',  __('Nav Menus', 'capsman-enhanced'), __('Nav Menus', 'capsman-enhanced'), $cap_name, 'pp-capabilities-nav-menus', [$this, 'ManageNavMenus']);
    }

    /**
	 * Manages admin menu permission
	 *
	 * @hook add_management_page
	 * @return void
	 */
	function ManageAdminMenus ()
	{
        global $capsman;

		if ((!is_multisite() || !is_super_admin()) && !current_user_can('administrator') && !current_user_can('manage_capabilities')) {
            // TODO: Implement exceptions.
		    wp_die('<strong>' .__('You do not have permission to manage menu restrictions.', 'capabilities-pro') . '</strong>');
		}

		$capsman->generateNames();
		$roles = array_keys($capsman->roles);

		if ( ! isset($capsman->current) ) {
			if (empty($_POST) && !empty($_REQUEST['role'])) {
				$capsman->current = $_REQUEST['role'];
			}
		}

		if (!isset($capsman->current) || !get_role($capsman->current)) {
			$capsman->current = get_option('default_role');
		}

		if ( ! in_array($capsman->current, $roles) ) {
			$capsman->current = array_shift($roles);
		}

		$ppc_admin_menu_reload = '0';

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['ppc-admin-menu-role']) ) {
			$capsman->current = $_POST['ppc-admin-menu-role'];

			//set role admin menu
			$admin_menu_option = !empty(get_option('capsman_admin_menus')) ? get_option('capsman_admin_menus') : [];
			$admin_menu_option[$_POST['ppc-admin-menu-role']] = isset($_POST['pp_cababilities_disabled_menu']) ? $_POST['pp_cababilities_disabled_menu'] : '';

			//set role admin child menu
			$admin_child_menu_option = !empty(get_option('capsman_admin_child_menus')) ? get_option('capsman_admin_child_menus') : [];
			$admin_child_menu_option[$_POST['ppc-admin-menu-role']] = isset($_POST['pp_cababilities_disabled_child_menu']) ? $_POST['pp_cababilities_disabled_child_menu'] : '';

			update_option('capsman_admin_menus', $admin_menu_option, false);
			update_option('capsman_admin_child_menus', $admin_child_menu_option, false);

			//set reload option for menu reflection if user is updating own role
			if(in_array($_POST['ppc-admin-menu-role'], wp_get_current_user()->roles)){
			$ppc_admin_menu_reload = '1';
			}

            ak_admin_notify(__('Settings updated.', 'capabilities-pro'));
		}

		include ( dirname(__FILE__) . '/admin-menus.php' );
	}

    /**
     * Manages navigation menu permissions
     *
     * @hook add_management_page
     * @return void
     */
    function ManageNavMenus()
    {
        global $capsman;

        if ((!is_multisite() || !is_super_admin()) && !current_user_can('administrator') && !current_user_can('manage_capabilities')) {
            // TODO: Implement exceptions.
            wp_die('<strong>' . __('You do not have permission to manage navigation menus.', 'capabilities-pro') . '</strong>');
        }

        $capsman->generateNames();
        $roles = array_keys($capsman->roles);

        if (!isset($capsman->current)) {
            if (empty($_POST) && !empty($_REQUEST['role'])) {
                $capsman->current = $_REQUEST['role'];
            }
        }

        if (!isset($capsman->current) || !get_role($capsman->current)) {
            $capsman->current = get_option('default_role');
        }

        if (!in_array($capsman->current, $roles)) {
            $capsman->current = array_shift($roles);
        }


        if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['ppc-nav-menu-role'])) {
            $capsman->current = $_POST['ppc-nav-menu-role'];


            //set role nav child menu
            $nav_item_menu_option = !empty(get_option('capsman_nav_item_menus')) ? get_option('capsman_nav_item_menus') : [];
            $nav_item_menu_option[$_POST['ppc-nav-menu-role']] = isset($_POST['pp_cababilities_restricted_items']) ? $_POST['pp_cababilities_restricted_items'] : '';


            update_option('capsman_nav_item_menus', $nav_item_menu_option, false);


            ak_admin_notify(__('Settings updated.', 'capabilities-pro'));
        }

        include(dirname(__FILE__) . '/nav-menus.php');
    }

    function versionInfoRedirect() {
        if (!empty($_REQUEST['publishpress_caps_refresh_updates'])) {
            publishpress_caps_pro()->keyStatus(true);
            set_transient('publishpress-caps-refresh-update-info', true, 86400);

            delete_site_transient('update_plugins');
            delete_option('_site_transient_update_plugins');

            $opt_val = get_option('cme_edd_key');
            if (is_array($opt_val) && !empty($opt_val['license_key'])) {
                $plugin_slug = basename(CME_FILE, '.php'); // 'capabilities-pro';
                $plugin_relpath = basename(dirname(CME_FILE)) . '/' . basename(CME_FILE);  // $_REQUEST['plugin']
                $license_key = $opt_val['license_key'];
                $beta = false;

                delete_option(md5(serialize($plugin_slug . $license_key . $beta)));
                delete_option('edd_api_request_' . md5(serialize($plugin_slug . $license_key . $beta)));
                delete_option(md5('edd_plugin_' . sanitize_key($plugin_relpath) . '_' . $beta . '_version_info'));
            }

            wp_update_plugins();
            //wp_version_check(array(), true);

            if (current_user_can('update_plugins')) {
                $url = remove_query_arg('publishpress_caps_refresh_updates', $_SERVER['REQUEST_URI']);
                $url = add_query_arg('publishpress_caps_refresh_done', 1, $url);
                $url = "//" . $_SERVER['HTTP_HOST'] . $url;
                wp_redirect($url);
                exit;
            }
        }

        if (!empty($_REQUEST['publishpress_caps_refresh_done']) && empty($_POST)) {
            if (current_user_can('activate_plugins')) {
                $url = admin_url('update-core.php');
                wp_redirect($url);
            }
        }
    }

    function CapsManagerLoad() {
        require_once(dirname(__FILE__).'/manager-ui.php');
        new ManagerUI();
    }

    function loadUpdater() {
        require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/library/Factory.php');
        $container = \PublishPress\Capabilities\Factory::get_container();
        return $container['edd_container']['update_manager'];
    }

    function adminStyles() {
        global $plugin_page;

        if (!empty($plugin_page) && (0 == strpos('pp-capabilities', $plugin_page))) {
            wp_enqueue_style('publishpress-caps-pro', plugins_url( '', CME_FILE ) . '/includes-pro/pro.css', [], PUBLISHPRESS_CAPS_VERSION);
            wp_enqueue_style('publishpress-caps-status-caps', plugins_url( '', CME_FILE ) . '/includes-pro/status-caps.css', [], PUBLISHPRESS_CAPS_VERSION);

            add_thickbox();
        }
    }

    function capsManagerUI($args) {
        if (Pro::customStatusPermissionsAvailable() && get_option('cme_custom_status_control')) {
            require_once(dirname(__FILE__).'/admin.php');
            $ui = new CustomStatusCapsUI();
            $ui ->drawUI($args);
        }
    }

    function updateOptions() {
        update_option('cme_custom_status_control', (int) !empty($_REQUEST['cme_custom_status_control']));
        update_option('cme_display_branding', (int) !empty($_REQUEST['cme_display_branding']));
    }
}
