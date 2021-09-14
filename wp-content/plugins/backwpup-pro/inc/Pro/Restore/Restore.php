<?php
/**
 * Restore
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */

namespace Inpsyde\BackWPup\Pro\Restore;

use BackWPup_Download_File;
use Inpsyde\BackWPup\Pro\Restore\LogDownloader\DownloaderFactory;
use Inpsyde\BackWPup\Pro\Restore\LogDownloader\LogDownloaderFactory;
use Inpsyde\BackWPup\Pro\Restore\LogDownloader\View;
use Inpsyde\BackWPup\Pro\Restore\Functions;

/**
 * Class Restore
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */
class Restore {

	/**
	 * Booted Correctly
	 *
	 * The control variable is needed because much code depends on parts that works with filesystem, if for
	 * some reason some file or directory isn't created correctly or isn't writable we may encountered issues in the
	 * booting fase.
	 *
	 * @var bool True if the restore has been booted correctly, false otherwise.
	 */
	private $booted_correctly = false;

	/**
	 * Set Hooks
	 *
	 * @since 3.5.0
	 *
	 * @return \Inpsyde\BackWPup\Pro\Restore\Restore For concatenation.
	 */
	public function set_hooks() {

		add_action( 'admin_init', array( $this, 'ajax_handler' ) );
		add_action( 'admin_head', array( $this, 'localize_scripts' ) );
		add_action( 'backwpup_page_pro_restore', array( $this, 'boot' ) );
		add_action( 'backwpup_page_pro_restore', array( $this, 'handle_restore_log_download_request' ) );

		return $this;
	}

	/**
	 * Initialize
	 *
	 * @return \Inpsyde\BackWPup\Pro\Restore\Restore For concatenation.
	 */
	public function init() {

		$this->requires();

		return $this;
	}

	/**
	 * Booting the Restore
	 *
	 * @since 3.5.0
	 *
	 * @return void
	 */
	public function boot() {

		try {
			Functions\restore_boot();

			$container = Functions\restore_container( null );

			if ( $container['session']->notifications() ) {
				// Notifications.
				$notificator = new \Inpsyde\BackWPup\Pro\Restore\Notificator(
					$container['session'],
					$container['translation']
				);
				$notificator->load();
			}

			// Mark as booted correctly.
			$this->booted_correctly = true;
		} catch ( \Exception $e ) {
			\BackWPup_Admin::message( $e->getMessage(), true );
			\BackWPup_Admin::display_messages();
		}
	}

    /**
     * Handle ajax request
     *
     * @since 3.5.0
     *
     * @return void
     */
    public function ajax_handler()
    {
        if (defined('DOING_AJAX') && DOING_AJAX && defined('WP_ADMIN') && WP_ADMIN) {
            $ajaxHandler = Functions\restore_container('ajax_handler');
            $ajaxHandler->load();
        }
    }

	/**
	 * Handle Restore Log Download Request
	 *
	 * @since 3.5.0
	 *
	 * @return void
	 */
	public function handle_restore_log_download_request() {

		// phpcs:ignore
		$request = isset( $_GET['action'] ) ? filter_var( $_GET['action'], FILTER_SANITIZE_STRING ) : '';
		if ( 'download_restore_log' !== $request ) {
			return;
		}

		$capability = 'backwpup_restore';

        $downloader_factory = new DownloaderFactory();
        try {
            $log_downloader = $downloader_factory->create();
        } catch (\RuntimeException $exc) {
            return;
        }

		// Compress the log file.
		$log_downloader->zip();

		// phpcs:ignore
		$download_handler = new \BackWpup_Download_Handler(
			new BackWPup_Download_File(
				$log_downloader->path(),
				function ( \BackWPup_Download_File_Interface $obj ) use ( $log_downloader ) {

					$obj->clean_ob()
					    ->headers();

					// phpcs:ignore
					echo backwpup_wpfilesystem()->get_contents( $obj->filepath() );

					$log_downloader->clean();
					die();
				},
				$capability
			),
			View::NONCE_NAME,
			$capability,
			View::NONCE_ACTION
		);

		$download_handler->handle();
	}

	/**
	 * Localize Scripts
	 *
	 * @since 3.5.0
	 *
	 * @return void
	 */
	public function localize_scripts() {

		if ( ! $this->booted_correctly ) {
			return;
		}

		// Retrieve the list of the text to translate.
		$list = include \BackWPup::get_plugin_data(
				'plugindir'
			) . '/vendor/inpsyde/backwpup-restore-shared/inc/localize-restore-api.php';

		$localizer = new \Inpsyde\Restore\LocalizeScripts(
			Functions\restore_container( 'translation' ),
			$list
		);

		$localizer
			->localize()
			->output();
	}

	/**
	 * Requirements
	 *
	 * @since 3.5.0
	 *
	 * @return void
	 */
	private function requires() {

		$file = untrailingslashit( \BackWPup::get_plugin_data( 'plugindir' ) )
		        . '/inc/Pro/Restore/functions/commons.php';

		if ( $file ) {
			require_once $file;
		}
	}
}
