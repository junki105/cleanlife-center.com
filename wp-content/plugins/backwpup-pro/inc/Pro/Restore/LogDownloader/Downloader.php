<?php
/**
 * Log Downloader
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */

namespace Inpsyde\BackWPup\Pro\Restore\LogDownloader;

/**
 * Class Downloader
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */
class Downloader {

	/**
	 * View
	 *
	 * @since 3.5.0
	 *
	 * @var View Instance of view
	 */
	private $view;

	/**
	 * The Zip Generator
	 *
	 * @since 3.5.0
	 *
	 * @var ZipGenerator The instance of ZipGenerator
	 */
	private $zip_generator;

	/**
	 * Files list
	 *
	 * @since 3.5.0
	 *
	 * @var array The files list to zip
	 */
	private $files;

	/**
	 * File System
	 *
	 * @since 3.5.0
	 *
	 * @var \WP_Filesystem_Base A WP filesystem instance
	 */
	private $filesystem;

	/**
	 * Downloader constructor
	 *
	 * @since 3.5.0
	 *
	 * @param View                $view          Instance of view.
	 * @param ZipGenerator        $zip_generator The instance of ZipGenerator.
	 * @param \WP_Filesystem_Base $filesystem    The files list to zip.
	 * @param array               $files         A WP filesystem instance.
	 */
	public function __construct( View $view, ZipGenerator $zip_generator, \WP_Filesystem_Base $filesystem, array $files ) {

		$this->view          = $view;
		$this->zip_generator = $zip_generator;
		$this->files         = $files;
		$this->filesystem    = $filesystem;
	}

	/**
	 * Zip
	 *
	 * @since 3.5.0
	 *
	 * @return void
	 */
	public function zip() {

		$this->zip_generator->zip();
	}

	/**
	 * Zip File Path
	 *
	 * @since 3.5.0
	 *
	 * @return string The file path
	 */
	public function path() {

		return $this->zip_generator->path();
	}

	/**
	 * Clean the Controller
	 *
	 * @since 3.5.0
	 *
	 * @return void
	 */
	public function clean() {

		$this->filesystem->delete(
			$this->zip_generator->path()
		);
	}

	/**
	 * Downloader View
	 *
	 * @since 3.5.0
	 *
	 * @return void
	 */
	public function view() {

		$this->view->view();
	}

	/**
	 * Can be Downloaded
	 *
	 * @since 3.5.0
	 *
	 * @return bool True if the file can be downloaded, false otherwise.
	 */
	public function can_be_downloaded() {

		foreach ( $this->files as $file ) {
			if ( file_exists( $file ) ) {
				return true;
			}
		}

		return false;
	}
}
