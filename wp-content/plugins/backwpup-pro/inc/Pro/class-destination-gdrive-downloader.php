<?php
/**
 * Google Drive Downloader
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup
 */

/**
 * Class BackWPup_Pro_Destination_Gdrive_Downloader
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup
 */
final class BackWPup_Pro_Destination_Gdrive_Downloader implements BackWPup_Destination_Downloader_Interface {

	const OPTION_REFRESH_TOKEN = 'gdriverefreshtoken';
	const GOOGLE_API_URL = 'https://www.googleapis.com/auth/drive';

	/**
	 * @var \BackWpUp_Destination_Downloader_Data
	 */
	private $data;

	/**
	 * @var resource
	 */
	private $local_file_handler;

	/**
	 * @var Google_Client
	 */
	private $google_api = null;

	/**
	 * BackWPup_Pro_Destination_Gdrive_Downloader constructor
	 *
	 * @param \BackWpUp_Destination_Downloader_Data $data
	 */
	public function __construct( BackWpUp_Destination_Downloader_Data $data ) {

		$this->data = $data;

		$this->google_api();
	}

	/**
	 * Clean up things
	 */
	public function __destruct() {

		fclose( $this->local_file_handler );
	}

	/**
	 * @inheritdoc
	 */
	public function download_chunk( $start_byte, $end_byte ) {

		$this->local_file_handler( $start_byte );

		// Get the file
		$file    = $this->google_api->files->get( $this->data->source_file_path() );
		$url     = $file->downloadUrl;
		$request = new Google_Http_Request( $url, 'GET', null, null );
		$request->setRequestHeaders( array(
			'Range' => "bytes={$start_byte}-{$end_byte}",
		) );

		$httpRequest = $this->google_api->getClient()->getAuth()->authenticatedRequest( $request );

		$bytes = (int) fwrite( $this->local_file_handler, $httpRequest->getResponseBody() );
		if ( $bytes === 0 ) {
			throw new \RuntimeException( __( 'Could not write data to file.', 'backwpup' ) );
		}
	}

	/**
	 * @inheritdoc
	 */
	public function calculate_size() {

		$file = $this->google_api->files->get( $this->data->source_file_path() );

		return (int) $file->fileSize;
	}

	/**
	 * Set the handler resource for the local file
	 *
	 * @param int $start_byte
	 */
	private function local_file_handler( $start_byte ) {

		if ( is_resource( $this->local_file_handler ) ) {
			return;
		}

		$this->local_file_handler = fopen( $this->data->local_file_path(), $start_byte == 0 ? 'wb' : 'ab' );

		if ( ! is_resource( $this->local_file_handler ) ) {
			throw new \RuntimeException( __( 'File could not be opened for writing.', 'backwpup' ) );
		}
	}

	/**
	 * Set the google api instance
	 *
	 * @return void
	 */
	private function google_api() {

		if ( $this->google_api ) {
			return;
		}

		try {
			$client = new Google_Client();

			$client
				->getIo()
				->setOptions( array( CURLOPT_SSL_VERIFYPEER => false ) );

			if ( BackWPup::get_plugin_data( 'cacert' ) ) {
				$client
					->getIo()
					->setOptions( array( CURLOPT_CAINFO => BackWPup::get_plugin_data( 'cacert' ) ) );
			}

			$client->setApplicationName( BackWPup::get_plugin_data( 'name' ) );
			$client->setClientId( get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_ID ) );
			$client->setClientSecret( BackWPup_Encryption::decrypt(
				get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_SECRET )
			) );

			$client->setScopes( array( self::GOOGLE_API_URL ) );
			$client->setAccessType( 'offline' );

			$refresh_token = BackWPup_Encryption::decrypt(
				BackWPup_Option::get( $this->data->job_id(), self::OPTION_REFRESH_TOKEN )
			);

			$client->refreshToken( $refresh_token );

			$this->google_api = new Google_Service_Drive( $client );
		} catch ( Exception $e ) {
			BackWPup_Admin::message( 'Google Drive: ' . $e->getMessage() );
		}
	}
}
