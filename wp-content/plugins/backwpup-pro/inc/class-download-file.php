<?php

use \Inpsyde\BackWPupShared\File\MimeTypeExtractor;

/**
 * Class BackWPup_Download_File
 */
final class BackWPup_Download_File implements BackWPup_Download_File_Interface {

	/**
	 * The file path
	 *
	 * @var string The path of the file to download
	 */
	private $filepath;

	/**
	 * File Name
	 *
	 * @var string The file name
	 */
	private $filename;

	/**
	 * @var string
	 *
	 * @string The encoding type
	 */
	private static $encoding = 'binary';

	/**
	 * File content length
	 *
	 * @var int The length of the file
	 */
	private $length;

	/**
	 * Callback
	 *
	 * @var callable The callback to call that will perform the download action
	 */
	private $callback;

	/**
	 * Capability
	 *
	 * @var @string The capability needed to download the file
	 */
	private $capability;

	/**
	 * BackWPup_Download_File constructor
	 *
	 * @todo move the file stuffs into a specific class to manage only files. Blocked by class-file.php
	 *
	 * @throws \InvalidArgumentException In case the callback is not a valid callback.
	 *
	 * @param string $filepath The path of the file to download.
	 * @param callable $callback The callback to call that will perform the download action.
	 * @param string $capability The capability needed to download the file.
	 */
	public function __construct( $filepath, $callback, $capability ) {

		if ( ! is_callable( $callback ) ) {
			throw new \InvalidArgumentException(
				sprintf( 'Invalid callback passed to %s. Callback parameter must be callable.', __CLASS__ )
			);
		}

		$this->filepath = $filepath;
		$this->filename = basename( $filepath );
		$this->callback = $callback;
		$this->length = file_exists( $filepath ) ? filesize( $filepath ) : 0;
		$this->capability = $capability;
	}

	/**
	 * @inheritdoc
	 */
	public function download() {

		if ( ! current_user_can( $this->capability ) ) {
			wp_die( 'Cheating Uh?' );
		}

		$this->perform_download_callback();
	}

	/**
	 * @inheritdoc
	 */
	public function clean_ob() {

		$level = ob_get_level();
		if ( $level ) {
			for ( $i = 0; $i < $level; $i ++ ) {
				ob_end_clean();
			}
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function filepath() {

		return $this->filepath;
	}

	/**
	 * @inheritdoc
	 */
	public function headers() {

		$mime = MimeTypeExtractor::fromFilePath( $this->filepath );

		$level = ob_get_level();
		if ( $level ) {
			for ( $i = 0; $i < $level; $i ++ ) {
				ob_end_clean();
			}
		}

		// phpcs:ignore
		@set_time_limit( 300 );
		nocache_headers();

		// Set headers.
		header( 'Content-Description: File Transfer' );
		header( "Content-Type: {$mime}" );
		header( "Content-Disposition: attachment; filename={$this->filename}" );
		header( 'Content-Transfer-Encoding: ' . self::$encoding );
		header( "Content-Length: {$this->length}" );

		return $this;
	}

	/**
	 * Perform the Download
	 *
	 * Note: The callback must call `die` it self.
	 *
	 * @return void
	 */
	private function perform_download_callback() {

		call_user_func( $this->callback, $this );
	}
}
