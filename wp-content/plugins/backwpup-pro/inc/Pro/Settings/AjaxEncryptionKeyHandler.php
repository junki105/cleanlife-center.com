<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\Settings;

use phpseclib\Crypt\RSA;

/**
 * Class AjaxEncryptKeyGenerator
 */
class AjaxEncryptionKeyHandler {

	const TASK_GENERATE_SYMMETRIC_KEY = 'generate_symmetric_key';
	const TASK_GENERATE_ASYMMETRIC_KEY_PAIR = 'generate_asymmetric_key_pair';
	const TASK_VALIDATE_ASYMMETRIC_KEY_PAIR = 'validate_asymmetric_key_pair';

	/**
	 * @var array
	 */
	private static $all_tasks = array(
		self::TASK_GENERATE_SYMMETRIC_KEY,
		self::TASK_GENERATE_ASYMMETRIC_KEY_PAIR,
		self::TASK_VALIDATE_ASYMMETRIC_KEY_PAIR,
	);

	/**
	 * @var \phpseclib\Crypt\RSA
	 */
	private $rsa;

	/**
	 * AjaxEncryptionKeyHandler constructor
	 *
	 * @param \phpseclib\Crypt\RSA $rsa
	 */
	public function __construct( RSA $rsa ) {

		$this->rsa = $rsa;
	}

	/**
	 * @see generate_symmetric_key()
	 * @see generate_asymmetric_key_pair()
	 * @see validate_asymmetric_key_pair()
	 */
	public function handle() {

		if ( ! $this->verify_request() ) {
			exit;
		}

		$task = (string) filter_input( INPUT_POST, 'task', FILTER_SANITIZE_STRING );

		if ( ! in_array( $task, self::$all_tasks, true )
		     || ! method_exists( $this, $task )
		) {
			exit;
		}

		$this->{$task}();
	}

	/**
	 * Verify the request
	 *
	 * @return bool
	 */
	private function verify_request() {

		return check_ajax_referer( 'backwpup_ajax_nonce' ) && current_user_can( 'backwpup_settings' );
	}

	/**
	 * Generate the Symmetric Encryption Key
	 */
	private function generate_symmetric_key() {

		if ( ! \BackWPup::is_pro() ) {
			exit;
		}

		// Generate 256-bit string
		$hex = unpack( 'H*', \phpseclib\Crypt\Random::string( 32 ) );

		if ( isset( $hex[1] ) && $hex[1] ) {
			wp_send_json_success(
				array(
					'key' => $hex[1],
					'message' => esc_html__(
						'Your Symmetric key has been successful generated.',
						'backwpup'
					),
				)
			);
		}

		wp_send_json_error( array(
			'message' => esc_html__(
				'There was a problem during the generation of symmetric key.',
				'backwpup'
			),
		) );
	}

	/**
	 * Generate the Asymmetric Encryption Key Public and Private
	 */
	private function generate_asymmetric_key_pair() {

		$partial = array();
		do {
			$keys = $this->rsa->createKey( 1024, false, $partial );
			$partial = $keys['partialkey'];
		} while ( $partial !== false );

		if ( ! isset( $keys['publickey'] ) || ! isset( $keys['privatekey'] ) ) {
			wp_send_json_error( array(
				'message' => esc_html__(
					'There was a problem during the generation of asymmetric keys.',
					'backwpup'
				),
			) );
		}

		$data = array(
			'publicKey' => $keys['publickey'],
			'privateKey' => $keys['privatekey'],
		);

		wp_send_json_success(
			array(
				'keys' => $data,
				'message' => esc_html__(
					'Your Asymmetric key have been successful generated.',
					'backwpup'
				),
			)
		);
	}

	/**
	 * Validate the Public and Private keys
	 */
	private function validate_asymmetric_key_pair() {

		$public_key = filter_input( INPUT_POST, 'publickey', FILTER_SANITIZE_STRING );
		$private_key = filter_input( INPUT_POST, 'privatekey', FILTER_SANITIZE_STRING );

		if ( ! $public_key || ! $private_key ) {
			wp_send_json_error( array(
				'valid' => false,
				'message' => esc_html__(
					'No public or private key provided. Cannot validate the encryption keys.',
					'backwpup'
				),
			) );
		}

		$this->rsa->loadKey( $public_key );
		$data = $this->rsa->encrypt( 'test' );

		// Decrypt
		$this->rsa->loadKey( $private_key );
		$result = $this->rsa->decrypt( $data );

		if ( $result !== 'test' ) {
			wp_send_json_error( array(
				'valid' => false,
				'message' => esc_html__( 'Validate asymmetric keys failed.', 'backwpup' ),
			) );
		}

		wp_send_json_success( array(
			'valid' => true,
			'message' => esc_html__( 'Validate asymmetric keys passed.', 'backwpup' ),
		) );
	}
}
