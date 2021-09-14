<?php
/**
 * DownloadLog
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */

namespace Inpsyde\BackWPup\Pro\Restore\LogDownloader;

/**
 * Class DownloadLog
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */
class View {

	const NONCE_NAME = 'backwpup_restore_log_download_action';
	const NONCE_ACTION = 'download_restore_log';

	/**
	 * Capability
	 *
	 * @since 3.5.0
	 *
	 * @var string
	 */
	private static $capability = 'backwpup_restore';

	/**
	 * Label
	 *
	 * @since 3.5.0
	 *
	 * @var string The label for the link
	 */
	private $label;

	/**
	 * Url
	 *
	 * @since 3.5.0
	 *
	 * @var string The url where point the action
	 */
	private $url;

	/**
	 * Files
	 *
	 * @since 3.5.0
	 *
	 * @var array The list of the files to download
	 */
	private $files;

	/**
	 * DownloadLogView constructor
	 *
	 * @since 3.5.0
	 *
	 * @param string $label The label for the link.
	 * @param string $url The url where point the action.
	 */
	public function __construct( $label, $url, array $files ) {

		if ( ! $label || ! is_string( $label ) ) {
			throw new \InvalidArgumentException(
				sprintf( 'Invalid label for %s', __CLASS__ )
			);
		}
		if ( ! $url || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			throw new \InvalidArgumentException(
				sprintf( 'Invalid label for %s', __CLASS__ )
			);
		}

		$this->label = $label;
		$this->url = $url;
		$this->files = $files;
	}

	/**
	 * View
	 *
	 * @since 3.5.0
	 *
	 * Print the anchor link.
	 *
	 * @return void
	 */
	public function view() {

		if ( ! current_user_can( self::$capability ) ) {
			return;
		}

		backwpup_template(
			(object) array(
				'link' => $this->link(),
				'label' => $this->label,
			),
			'/pro/restore/download-log.php'
		);
	}

	/**
	 * Build the link
	 *
	 * @since 3.5.0
	 *
	 * @return string The link url
	 */
	private function link() {

		return add_query_arg(
			array(
				self::NONCE_NAME => wp_create_nonce( self::NONCE_NAME ),
				'action' => self::NONCE_ACTION,
			),
			$this->url
		);
	}
}
