<?php

/**
 *
 */
class BackWPup_Pro_Settings_APIKeys {

	const OPTION_GOOGLE_CLIENT_ID = 'backwpup_cfg_googleclientid';
	const OPTION_GOOGLE_CLIENT_SECRET = 'backwpup_cfg_googleclientsecret';

	/**
	 * @var BackWPup_Pro_Settings_APIKeys
	 */
	private static $instance = null;

	private function __construct() {

		add_action( 'backwpup_page_settings_tab_apikey', array( $this, 'backwpup_hash' ) );

		$destinations = BackWPup::get_registered_destinations();
		if ( isset( $destinations['DROPBOX'] ) ) {
			add_action( 'backwpup_page_settings_tab_apikey', array( $this, 'dropbox_keys_form' ) );
		}
		if ( isset( $destinations['SUGARSYNC'] ) ) {
			add_action( 'backwpup_page_settings_tab_apikey', array( $this, 'sugarsync_keys_form' ) );
		}
		if ( isset( $destinations['GDRIVE'] ) ) {
			add_action( 'backwpup_page_settings_tab_apikey', array( $this, 'google_keys_form' ) );
		}

		//save settings
		add_action( 'backwpup_page_settings_save', array( $this, 'save_form' ) );
	}

	/**
	 * @return BackWPup_Pro_Settings_APIKeys
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Prints form fields for hash key
	 */
	public function backwpup_hash() {

		?>
		<h3><?php esc_html_e( 'Hash key', 'backwpup' ); ?></h3>
		<p><?php esc_html_e( 'Hash Key for BackWPup. It will be used to have hashes in folder and file names. It must at least 6 chars long.',
				'backwpup' ); ?></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="hashid"><?php esc_html_e( 'Hash key:', 'backwpup' ); ?></label></th>
				<td>
					<input
						name="hash"
						type="text"
						id="hashid"
						size="12"
						value="<?php echo esc_attr( get_site_option( 'backwpup_cfg_hash' ) ); ?>"
						class="code"
						autocomplete="off"
						maxlength="12"/>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Prints form fields for Dropbox keys
	 */
	public function dropbox_keys_form() {

		$secret = BackWPup_Encryption::decrypt( get_site_option( 'backwpup_cfg_dropboxappsecret' ) );
		$sandbox_secret = BackWPup_Encryption::decrypt( get_site_option( 'backwpup_cfg_dropboxsandboxappsecret' ) );
		?>
		<h3><?php esc_html_e( 'Dropbox API Keys', 'backwpup' ); ?></h3>
		<p><?php esc_html_e( 'If you want to set your own Dropbox API Keys, you can do it here. Leave empty for default.',
				'backwpup' ); ?></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="dropboxappkey"><?php esc_html_e( 'Full Dropbox App key:',
							'backwpup' ); ?></label></th>
				<td>
					<input
						name="dropboxappkey"
						type="text" id="dropboxappkey"
						value="<?php echo esc_attr( get_site_option( 'backwpup_cfg_dropboxappkey' ) ); ?>"
						class="regular-text"
						autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="dropboxappsecret"><?php esc_html_e( 'Full Dropbox App secret:',
							'backwpup' ); ?></label></th>
				<td>
					<input
						name="dropboxappsecret"
						type="password"
						id="dropboxappsecret"
						value="<?php echo esc_attr( $secret ); ?>"
						class="regular-text"
						autocomplete="off"/>
			</tr>

			<tr>
				<th scope="row"><label for="dropboxsandboxappkey"><?php esc_html_e( 'Sandbox App key:',
							'backwpup' ); ?></label></th>
				<td>
					<input
						name="dropboxsandboxappkey"
						type="text"
						id="dropboxsandboxappkey"
						value="<?php echo esc_attr( get_site_option( 'backwpup_cfg_dropboxsandboxappkey' ) ); ?>"
						class="regular-text" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="dropboxsandboxappsecret"><?php esc_html_e( 'Sandbox App secret:',
							'backwpup' ); ?></label></th>
				<td>
					<input
						name="dropboxsandboxappsecret"
						type="password"
						id="dropboxsandboxappsecret"
						value="<?php echo esc_attr( $sandbox_secret ); ?>"
						class="regular-text"
						autocomplete="off"/>
			</tr>
		</table>
		<?php
	}

	/**
	 * Prints form fields for SugarSync keys
	 */
	public function sugarsync_keys_form() {

		$secret = BackWPup_Encryption::decrypt( get_site_option( 'backwpup_cfg_sugarsyncsecret' ) );
		?>
		<h3><?php esc_html_e( 'SugarSync API Keys', 'backwpup' ); ?></h3>
		<p><?php esc_html_e( 'If you want to set your own SugarSync API keys you can do that here. Leave empty for default.',
				'backwpup' ); ?></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="sugarsynckey"><?php esc_html_e( 'Access Key ID:', 'backwpup' ); ?></label>
				</th>
				<td>
					<input name="sugarsynckey" type="text" id="sugarsynckey"
					       value="<?php echo esc_attr( get_site_option( 'backwpup_cfg_sugarsynckey' ) ); ?>"
					       class="regular-text" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="sugarsyncsecret"><?php esc_html_e( 'Private Access Key:',
							'backwpup' ); ?></label></th>
				<td>
					<input
						name="sugarsyncsecret"
						type="password"
						id="sugarsyncsecret"
						value="<?php echo esc_attr( $secret ); ?>"
						class="regular-text"
						autocomplete="off"/>
			</tr>
			<tr>
				<th scope="row"><label for="sugarsyncappid"><?php esc_html_e( 'App ID:', 'backwpup' ); ?></label></th>
				<td>
					<input
						name="sugarsyncappid"
						type="text"
						id="sugarsyncappid"
						value="<?php echo esc_attr( get_site_option( 'backwpup_cfg_sugarsyncappid' ) ); ?>"
						class="regular-text"
						autocomplete="off"/>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Prints form fields for Google Drive keys
	 */
	public function google_keys_form() {

		$secret = BackWPup_Encryption::decrypt( get_site_option( self::OPTION_GOOGLE_CLIENT_SECRET ) );
		?>
		<h3><?php esc_html_e( 'Google API Keys', 'backwpup' ); ?></h3>
		<p><a href="https://console.developers.google.com">https://console.developers.google.com</a></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="googleclientid"><?php esc_html_e( 'Client ID:', 'backwpup' ); ?></label>
				</th>
				<td>
					<input
						name="googleclientid"
						type="text"
						id="googleclientid"
						value="<?php echo esc_attr( get_site_option( self::OPTION_GOOGLE_CLIENT_ID ) ); ?>"
						class="regular-text"
						autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="googleclientsecret"><?php esc_html_e( 'Client secret:',
							'backwpup' ); ?></label></th>
				<td>
					<input
						name="googleclientsecret"
						type="password"
						id="googleclientsecret"
						value="<?php echo esc_attr( $secret ); ?>"
						class="regular-text"
						autocomplete="off"/>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Redirect URIs:', 'backwpup' ); ?></th>
				<td>
					<span
						class="code"><?php echo admin_url( 'admin-ajax.php' ) . '?action=backwpup_dest_gdrive'; ?></span>
					<br/>
					<span class="description"><?php esc_html_e( 'Add this URI in a new line to the field.',
							'backwpup' ); ?></span>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save form fields
	 */
	public function save_form() {

		if ( ! empty( $_POST['hash'] ) && strlen( $_POST['hash'] ) >= 6 ) {
			update_site_option( 'backwpup_cfg_hash', sanitize_text_field( $_POST['hash'] ) );
		} else {
			delete_site_option( 'backwpup_cfg_hash' );
		}

		if ( $_POST['dropboxappkey'] ) {
			update_site_option( 'backwpup_cfg_dropboxappkey', sanitize_text_field( $_POST['dropboxappkey'] ) );
		} else {
			delete_site_option( 'backwpup_cfg_dropboxappkey' );
		}

		if ( $_POST['dropboxappsecret'] ) {
			update_site_option(
				'backwpup_cfg_dropboxappsecret',
				BackWPup_Encryption::encrypt( sanitize_text_field( $_POST['dropboxappsecret'] ) )
			);
		} else {
			delete_site_option( 'backwpup_cfg_dropboxappsecret' );
		}

		if ( $_POST['dropboxsandboxappkey'] ) {
			update_site_option( 'backwpup_cfg_dropboxsandboxappkey',
				sanitize_text_field( $_POST['dropboxsandboxappkey'] ) );
		} else {
			delete_site_option( 'backwpup_cfg_dropboxsandboxappkey' );
		}

		if ( $_POST['dropboxsandboxappsecret'] ) {
			update_site_option(
				'backwpup_cfg_dropboxsandboxappsecret',
				BackWPup_Encryption::encrypt( sanitize_text_field( $_POST['dropboxsandboxappsecret'] ) )
			);
		} else {
			delete_site_option( 'backwpup_cfg_dropboxsandboxappsecret' );
		}

		if ( $_POST['sugarsynckey'] ) {
			update_site_option( 'backwpup_cfg_sugarsynckey', sanitize_text_field( $_POST['sugarsynckey'] ) );
		} else {
			delete_site_option( 'backwpup_cfg_sugarsynckey' );
		}

		if ( $_POST['sugarsyncsecret'] ) {
			update_site_option(
				'backwpup_cfg_sugarsyncsecret',
				BackWPup_Encryption::encrypt( sanitize_text_field( $_POST['sugarsyncsecret'] ) )
			);
		} else {
			delete_site_option( 'backwpup_cfg_sugarsyncsecret' );
		}

		if ( $_POST['sugarsyncappid'] ) {
			update_site_option( 'backwpup_cfg_sugarsyncappid', sanitize_text_field( $_POST['sugarsyncappid'] ) );
		} else {
			delete_site_option( 'backwpup_cfg_sugarsyncappid' );
		}

		if ( $_POST['googleclientsecret'] ) {
			update_site_option(
				self::OPTION_GOOGLE_CLIENT_SECRET,
				BackWPup_Encryption::encrypt( sanitize_text_field( $_POST['googleclientsecret'] ) )
			);
		} else {
			delete_site_option( self::OPTION_GOOGLE_CLIENT_SECRET );
		}

		if ( $_POST['googleclientid'] ) {
			update_site_option( self::OPTION_GOOGLE_CLIENT_ID, sanitize_text_field( $_POST['googleclientid'] ) );
		} else {
			delete_site_option( self::OPTION_GOOGLE_CLIENT_ID );
		}

	}

}
