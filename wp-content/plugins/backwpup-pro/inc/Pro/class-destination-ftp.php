<?php
/**
 * Ftp Pro Destination
 *
 * @package \Inpsyde\BackWpup
 */

use \Inpsyde\BackWPup\Pro\Restore;

/**
 * Class BackWPup_Pro_Destination_Ftp
 *
 * @package \Inpsyde\BackWpup
 */
class BackWPup_Pro_Destination_Ftp extends BackWPup_Destination_Ftp {

	/**
	 * Page Markup
	 *
	 * @param array $job_settings The job settings.
	 */
	public function wizard_page( array $job_settings ) {

		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
						<label for="idftphost"><strong><?php esc_html_e( 'Hostname:', 'backwpup' ); ?></strong><br />
							<input name="ftphost" id="idftphost" type="text" value="<?php echo esc_attr(
								$job_settings['ftphost']
							); ?>"
								class="large-text" autocomplete="off" /></label>
						<br />
						<label for="idftphostport"><strong><?php esc_html_e( 'Port:', 'backwpup' ); ?></strong><br />
							<input name="ftphostport" type="number" step="1" min="1" value="<?php echo esc_attr(
								$job_settings['ftphostport']
							); ?>"
								class="small-text" id="idftphostport" /></label>
						<br />
						<label id="idftpuser"><strong><?php esc_html_e( 'Username:', 'backwpup' ); ?></strong><br />
							<input name="ftpuser" type="text" value="<?php echo esc_attr(
								$job_settings['ftpuser']
							); ?>"
								class="user large-text" autocomplete="off" id="idftpuser" /></label>
						<br />
						<label for="idftppass"><strong><?php esc_html_e( 'Password:', 'backwpup' ); ?></strong><br />
							<input name="ftppass" type="password" value="<?php echo esc_attr(
								BackWPup_Encryption::decrypt( $job_settings['ftppass'] )
							); ?>"
								class="password large-text" autocomplete="off" id="idftppass" /></label>
						<br />
						<label for="idftpdir"><strong><?php esc_html_e(
									'Folder on server:',
									'backwpup'
								); ?></strong><br />
							<input name="ftpdir" id="idftpdir" type="text" value="<?php echo esc_attr(
								$job_settings['ftpdir']
							); ?>" class="large-text" /></label>
						<br />

						<?php
						if ( $job_settings['backuptype'] == 'archive' ) {

						?>
						<label for="idftpmaxbackups"><input name="ftpmaxbackups" id="idftpmaxbackups" class="small-text" type="number" step="3" step="1" min="0" value="<?php echo esc_attr(
								$job_settings['ftpmaxbackups']
							); ?>" />
							<?php
							esc_html_e( 'Maximum number of backup files to keep in folder:', 'backwpup' );
							} else { ?>
								<label for="idftpsyncnodelete"><input class="checkbox" value="1" type="checkbox" <?php checked(
										$job_settings['ftpsyncnodelete'],
										true
									); ?> name="ftpsyncnodelete" id="idftpsyncnodelete" /> <?php esc_html_e(
										'Do not delete files while syncing to destination!',
										'backwpup'
									); ?></label>
							<?php } ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save Wizard
	 *
	 * @param array $job_settings The job settings.
	 *
	 * @return array The edited job settings
	 */
	public function wizard_save( array $job_settings ) {

		$_POST['ftphost']        = str_replace(
			array( 'http://', 'ftp://' ),
			'',
			sanitize_text_field( $_POST['ftphost'] )
		);
		$job_settings['ftphost'] = isset( $_POST['ftphost'] ) ? $_POST['ftphost'] : '';

		$job_settings['ftphostport'] = ! empty( $_POST['ftphostport'] ) ? absint( $_POST['ftphostport'] ) : 21;
		$job_settings['ftpuser']     = isset( $_POST['ftpuser'] ) ? sanitize_text_field( $_POST['ftpuser'] ) : '';
		$job_settings['ftppass']     = isset( $_POST['ftppass'] ) ? BackWPup_Encryption::encrypt(
			(string) $_POST['ftppass']
		) : '';

		if ( ! empty( $_POST['ftpdir'] ) ) {
			$_POST['ftpdir'] = trailingslashit(
				str_replace( '//', '/', str_replace( '\\', '/', trim( stripslashes( $_POST['ftpdir'] ) ) ) )
			);
		}
		$job_settings['ftpdir'] = sanitize_text_field( $_POST['ftpdir'] );

		if ( isset( $_POST['ftpmaxbackups'] ) ) {
			$job_settings['ftpmaxbackups'] = ! empty( $_POST['ftpmaxbackups'] ) ? absint( $_POST['ftpmaxbackups'] ) : 0;
		}

		$job_settings['ftpssl']     = false;
		$job_settings['ftppasv']    = true;
		$job_settings['ftptimeout'] = 90;

		$job_settings['ftpsyncnodelete'] = ! empty( $_POST['ftpsyncnodelete'] );

		return $job_settings;
	}

	/**
	 * @inheritdoc
	 */
	public function file_get_list( $jobdest ) {

		$jobid = intval( substr( $jobdest, 0, strpos( $jobdest, '_', 1 ) ) );

		if ( ! $jobid ) {
			return array();
		}

		$registry = Restore\Functions\restore_registry();
		$files    = parent::file_get_list( $jobdest );

		// Include the Restore link.
		foreach ( $files as $index => &$file ) {
			$file['restoreurl'] = add_query_arg(
				array(
					'page'         => 'backwpuprestore',
					'action'       => 'restore-destination_ftp',
					'file'         => $files[ $index ]['file'],
					'restore_file' => $registry->uploads_folder . '/' . basename( $files[ $index ]['file'] ),
					'jobid'        => $jobid,
					'service'      => 'Ftp',
				),
				network_admin_url( 'admin.php' )
			);
		}

		return $files;
	}
}
