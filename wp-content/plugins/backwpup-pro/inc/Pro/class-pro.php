<?php

use Inpsyde\BackWPup\Pro\License\Api\PluginInformation;
use Inpsyde\BackWPup\Pro\License\Api\PluginUpdate;

class BackWPup_Pro
{
    /**
     * @var PluginUpdate
     */
    private $pluginUpdate;

    /**
     * @var PluginInformation
     */
    private $pluginInformation;

    /**
     * @param PluginUpdate $pluginUpdate
     * @param PluginInformation $pluginInformation
     */
    public function __construct(
        PluginUpdate $pluginUpdate,
        PluginInformation $pluginInformation
    )
    {
        $this->pluginUpdate = $pluginUpdate;
        $this->pluginInformation = $pluginInformation;

		$restore = new Inpsyde\BackWPup\Pro\Restore\Restore();
        $restore->set_hooks()->init();
    }

    /**
     * Enqueue Styles
     *
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        $isDebug = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG);

        wp_enqueue_style(
            'backwpup_pro',
            BackWPup::get_plugin_data('URL') . '/assets/css/pro.min.css',
            array(),
            ($isDebug ? BackWPup::get_plugin_data('Version') : time()),
            'screen'
        );
    }

	/**
	 * Add extra Destinations or overwrite
	 *
	 * @param array $destinations The destinations array.
	 *
	 * @return array The filtered destination array
	 */
	public function register_destination( $destinations ) {

		// Add/overwrite BackWPup Destinations to folder.
		$destinations['FOLDER']['class'] = 'BackWPup_Pro_Destination_Folder';
		$destinations['FOLDER']['can_sync'] = true;
		// Backup with mail.
		$destinations['EMAIL']['class'] = 'BackWPup_Pro_Destination_Email';
		// Backup to ftp.
		$destinations['FTP']['class'] = 'BackWPup_Pro_Destination_Ftp';
		// Backup to dropbox.
		$destinations['DROPBOX']['class'] = 'BackWPup_Pro_Destination_Dropbox';
		$destinations['DROPBOX']['can_sync'] = true;
		// Backup to S3
		$destinations['S3']['class'] = 'BackWPup_Pro_Destination_S3';
		$destinations['S3']['can_sync'] = true;
		// Backup to MS Azure.
		$destinations['MSAZURE']['class'] = 'BackWPup_Pro_Destination_MSAzure';
		$destinations['MSAZURE']['can_sync'] = true;
		// Backup to Rackspace Cloud.
		$destinations['RSC']['class'] = 'BackWPup_Pro_Destination_RSC';
		$destinations['RSC']['can_sync'] = true;
		// Backup to Sugarsync.
		$destinations['SUGARSYNC']['class'] = 'BackWPup_Pro_Destination_SugarSync';
		// Backup to Amazon Glacier.
		$destinations['GLACIER'] = array(
			'class' => 'BackWPup_Pro_Destination_Glacier',
			'info' => array(
				'ID' => 'GLACIER',
				'name' => __( 'Glacier', 'backwpup' ),
				'description' => __( 'Backup to Amazon Glacier', 'backwpup' ),
			),
			'can_sync' => false,
			'needed' => array(
				'php_version' => '',
				'functions' => array( 'curl_exec' ),
				'classes' => array( 'XMLWriter' ),
			),
		);
		// backup to Google Drive
		$destinations['GDRIVE'] = array(
			'class' => 'BackWPup_Pro_Destination_GDrive',
			'info' => array(
				'ID' => 'GDRIVE',
				'name' => __( 'GDrive', 'backwpup' ),
				'description' => __( 'Backup to Google Drive', 'backwpup' ),
			),
			'can_sync' => true,
			'needed' => array(
				'php_version' => '',
				'functions' => array( 'curl_init', 'json_decode', 'http_build_query' ),
				'classes' => array(),
			),
		);

		return $destinations;
	}

	/**
	 * Add extra Job types or overwrite
	 *
	 * @param $job_types
	 *
	 * @return array
	 */
	public function job_types( $job_types ) {

		if ( class_exists( 'mysqli' ) ) {
			$job_types['DBDUMP'] = new BackWPup_Pro_JobType_DBDump;
		}
		$job_types['FILE'] = new BackWPup_Pro_JobType_File;
		$job_types['WPEXP'] = new BackWPup_Pro_JobType_WPEXP;
		$job_types['WPPLUGIN'] = new BackWPup_Pro_JobType_WPPlugin;
		$job_types['DBCHECK'] = new BackWPup_Pro_JobType_DBCheck;

		return $job_types;
	}

	/**
	 * Add extra Wizards or overwrite
	 *
	 * @param array $wizards The container for the wizards.
	 *
	 * @return array
	 */
	public function wizards( $wizards ) {

		$plugin_version = BackWPup::get_plugin_data( 'Version' );
		$requirements = new BackWPup_System_Requirements();
		$system_test_runner = new BackWPup_System_Tests_Runner(
			$requirements,
			new BackWPup_System_Tests( $requirements ),
			false
		);
		$wizard_system_test = new BackWPup_Pro_Wizard_SystemTest( $plugin_version, $system_test_runner );
		$wizard_pro_job = new BackWPup_Pro_Wizard_Job(
			$plugin_version,
			BackWPup::get_registered_destinations()
		);

		$wizards['SYSTEMTEST'] = $wizard_system_test;
		$wizards['JOB'] = $wizard_pro_job;
		$wizards['JOBIMPORT'] = new BackWPup_Pro_Wizard_JobImport( $plugin_version );

		return $wizards;
	}

	/**
	 * Add wizards Page
	 */
	public function admin_page_wizards( $page_hooks ) {

		$page_hooks['backwpupwizards'] = add_submenu_page(
			'backwpup',
			__( 'Wizards', 'backwpup' ),
			__( 'Wizards', 'backwpup' ),
			'backwpup',
			'backwpupwizard',
			array(
				'BackWPup_Pro_Page_Wizard',
				'page',
			)
		);
		add_action( 'load-' . $page_hooks['backwpupwizards'], array( 'BackWPup_Admin', 'init_general' ) );
		add_action( 'load-' . $page_hooks['backwpupwizards'], array( 'BackWPup_Pro_Page_Wizard', 'load' ) );
		add_action(
			'admin_print_styles-' . $page_hooks['backwpupwizards'],
			array(
				'BackWPup_Pro_Page_Wizard',
				'admin_print_styles',
			)
		);
		add_action(
			'admin_print_scripts-' . $page_hooks['backwpupwizards'],
			array(
				'BackWPup_Pro_Page_Wizard',
				'admin_print_scripts',
			)
		);

		return $page_hooks;
	}

	/**
	 * Add support page
	 */
	public function admin_page_support( $page_hooks ) {

		$page_hooks['backwpupsupport'] = add_submenu_page(
			'backwpup',
			__( 'Support', 'backwpup' ),
			__( 'Contact Support', 'backwpup' ),
			'backwpup',
			'backwpupsupport',
			array(
				'BackWPup_Pro_Page_Support',
				'page',
			)
		);
		add_action( 'load-' . $page_hooks['backwpupsupport'], array( 'BackWPup_Admin', 'init_general' ) );
		add_action(
			'admin_print_scripts-' . $page_hooks['backwpupsupport'],
			array(
				'BackWPup_Pro_Page_Support',
				'admin_print_scripts',
			)
		);

		return $page_hooks;
	}

	/**
	 * Admin Page Restore
	 *
	 * @param array $page_hooks The page hooks list.
	 *
	 * @return array $page_hooks
	 */
	public function admin_page_restore( $page_hooks ) {

		$page_hooks['backwpuprestore'] = add_submenu_page(
			'backwpup',
			esc_html__( 'Restore', 'backwpup' ),
			esc_html__( 'Restore', 'backwpup' ),
			'backwpup_restore',
			'backwpuprestore',
			array(
				'BackWPup_Pro_Page_Restore',
				'page',
			)
		);

		// Register the submenu page (WP take care of capability) but prevent other stuffs to be executed if user
		// doesn't have correct privileges.
		if ( ! current_user_can( 'backwpup_restore' ) ) {
			return $page_hooks;
		}

		add_action( 'load-' . $page_hooks['backwpuprestore'], array( 'BackWPup_Admin', 'init_general' ) );
		add_action( 'load-' . $page_hooks['backwpuprestore'], array( 'BackWPup_Pro_Page_Restore', 'load' ) );
		add_action(
			'admin_print_scripts-' . $page_hooks['backwpuprestore'],
			array(
				'BackWPup_Pro_Page_Restore',
				'admin_print_scripts',
			)
		);

		return $page_hooks;
	}

	/**
	 * Add admin bar menu points for pro
	 */
	public function admin_bar_menu() {

		global $wp_admin_bar;

		if ( ! current_user_can( 'backwpup' ) || ! get_site_option( 'backwpup_cfg_showadminbar' ) ) {
			return;
		}

		/* @var WP_Admin_bar $wp_admin_bar */

		$wizards = BackWPup::get_wizards();

		$wp_admin_bar->add_menu(
			array(
				'id' => 'backwpup_wizard',
				'parent' => 'backwpup',
				'title' => __( 'Wizards', 'backwpup' ),
				'href' => network_admin_url( 'admin.php?page=backwpupwizard' ),
			)
		);

		foreach ( $wizards as $wizard_class ) {
			if ( ! current_user_can( $wizard_class->info['cap'] ) ) {
				continue;
			}
			$wp_admin_bar->add_menu(
				array(
					'id' => 'backwpup_wizard_' . $wizard_class->info['ID'],
					'parent' => 'backwpup_wizard',
					'title' => $wizard_class->info['name'],
					'href' => network_admin_url(
						'admin.php?page=backwpupwizard&wizard_start=' . $wizard_class->info['ID']
					),
				)
			);
		}

	}

	/**
	 * Allow pro users to contact BackWPup directly, sharing debug info.
	 *
	 * @param string $html The default HTML to use.
	 *
	 * @return string The debug HTML
	 */
	public function get_debug_info_text( $html ) {

		ob_start();
		?>
		<p>
			<?php _e(
				'If you are experiencing issues, the debug information shown below can help us to better investigate and solve it for you.',
				'backwpup'
			) ?>
		</p>
		<p>
			<?php _e(
				'If you already have a support ticket open with BackWPup, then you can simply click the copy button below to copy the debug information, and paste it into a response to your ticket.',
				'backwpup'
			) ?>
		</p>
		<p>
			<?php printf(
				__(
					'If you have not yet opened a ticket, you may contact us directly by <a href="%s">clicking here</a>.',
					'backwpup'
				),
				network_admin_url( 'admin.php' ) . '?page=backwpupsupport'
			) ?>
		</p>
		<?php
		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Prevent Cloning
	 */
	public function __clone() {

		wp_die( 'Cheatin&#8217; huh?' );
	}

	/**
	 * Prevent deserialization
	 */
	public function __wakeup() {

		wp_die( 'Cheatin&#8217; huh?' );
	}

    public function init()
    {
        // Register / Enqueue Scripts & Styles.
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        // Add menu page after logs.
        add_filter('backwpup_admin_pages', [$this, 'admin_page_wizards'], 4);
        // Add support page.
        add_filter('backwpup_admin_pages', [$this, 'admin_page_support'], 7);
        // Add or overwrite destinations.
        add_filter('backwpup_register_destination', [$this, 'register_destination'], 5);
        // Add or overwrite job types.
        add_filter('backwpup_job_types', [$this, 'job_types'], 5);
        // Add or overwrite wizards.
        add_filter('backwpup_pro_wizards', [$this, 'wizards'], 5);

        // Add Export Job things.
        add_filter(
            'backwpup_page_jobs_get_bulk_actions',
            [
                'BackWPup_Pro_Export_Jobs',
                'page_jobs_get_bulk_actions',
            ]
        );
        add_filter(
            'backwpup_page_jobs_actions',
            ['BackWPup_Pro_Export_Jobs', 'page_jobs_actions'],
            10,
            3
        );
        add_action('backwpup_page_jobs_load', ['BackWPup_Pro_Export_Jobs', 'page_jobs_load']);
        add_filter('backwpup_admin_pages', [$this, 'admin_page_restore'], 7);

        // Add admin menu points for prp.
        if (!defined('DOING_CRON')) {
            add_action('admin_bar_menu', [$this, 'admin_bar_menu'], 101);
        }

        // Add owen API Keys.
        if (isset($_REQUEST['page']) && $_REQUEST['page'] === 'backwpupsettings') { // phpcs:ignore
            add_action('admin_init', ['BackWPup_Pro_Settings_APIKeys', 'get_instance'], 5);
            add_filter('backwpup_get_debug_info_text', [$this, 'get_debug_info_text']);
        }

        // Check for plugin updates
        add_filter('pre_set_site_transient_update_plugins', [$this->pluginUpdate, 'execute']);

        // Check for plugin information to display on the update details page
        add_filter('plugins_api', [$this->pluginInformation, 'execute'], 10, 3);
    }
}
