<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the backwpup-pro package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\BackWPup\Pro\Settings;

use Inpsyde\BackWPup\Settings\SettingUpdatable;

/**
 * Class EncryptionSettingsUpdater
 */
class EncryptionSettingUpdater implements SettingUpdatable {

	const OPTION_ENCRYPTION_TYPE = 'backwpup_cfg_encryption';
	const OPTION_ASYMMETRIC_PUBLIC_KEY = 'backwpup_cfg_publickey';
	const OPTION_SYMMETRIC_KEY = 'backwpup_cfg_encryptionkey';

	const POST_ENCRYPTION_TYPE_KEY = 'encryption';
	const POST_ASYMMETRIC_PUBLIC_KEY = 'asymmetric_public_key';
	const POST_SYMMETRIC_KEY = 'symmetric_key';

	/**
	 * @var array
	 */
	private static $allowed_types = array(
		'asymmetric',
		'symmetric',
	);

	public function update() {

		if ( ! $this->update_encryption_setting() ) {
			return;
		}

		$this->update_symmetric_key();
		$this->update_asymmetric_public_key();
	}

	public function reset() {

		delete_site_option( self::OPTION_ENCRYPTION_TYPE );
		delete_site_option( self::OPTION_ASYMMETRIC_PUBLIC_KEY );
		delete_site_option( self::OPTION_SYMMETRIC_KEY );
	}

	/**
	 * @return bool
	 */
	private function update_symmetric_key() {

		$encryptionkey = (string) filter_input( INPUT_POST, self::POST_SYMMETRIC_KEY, FILTER_SANITIZE_STRING );

		if ( ! $encryptionkey || ! ctype_xdigit( $encryptionkey ) ) {
			return false;
		}

		$encryptionkey = substr( $encryptionkey, 0, 64 );

		update_site_option( self::OPTION_SYMMETRIC_KEY, $encryptionkey );
	}

	private function update_asymmetric_public_key() {

		$public_key = (string) filter_input( INPUT_POST, self::POST_ASYMMETRIC_PUBLIC_KEY, FILTER_SANITIZE_STRING );

		if ( ! $public_key ) {
			return;
		}

		update_site_option( self::OPTION_ASYMMETRIC_PUBLIC_KEY, $public_key );
	}

	/**
	 * @return bool
	 */
	private function update_encryption_setting() {

		$option = (string) filter_input( INPUT_POST, self::POST_ENCRYPTION_TYPE_KEY, FILTER_SANITIZE_STRING );

		if ( ! $option || ! in_array( $option, self::$allowed_types, true ) ) {
			return false;
		}

		update_site_option( self::OPTION_ENCRYPTION_TYPE, $option );

		return true;
	}
}
