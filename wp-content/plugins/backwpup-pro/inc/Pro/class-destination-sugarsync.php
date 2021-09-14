<?php
/**
 *
 */
class BackWPup_Pro_Destination_SugarSync extends BackWPup_Destination_SugarSync {

	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {
		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
					<?php if ( ! $job_settings[ 'sugarrefreshtoken' ] ) { ?>
						<label for="sugaremail"><strong><?php esc_html_e( 'Email address:', 'backwpup' ); ?></strong><br/>
						<input id="sugaremail" name="sugaremail" type="text" value="" class="large-text" autocomplete="off" /></label>
						<br/>
						<label for="sugarpass"><strong><?php esc_html_e( 'Password:', 'backwpup' ); ?></strong><br/>
						<input id="sugarpass" name="sugarpass" type="password" value="" class="large-text" autocomplete="off" /></label>
						<br/>
						<br/>
						<input type="submit" name="wizard_button" class="button-primary" value="<?php esc_html_e( 'Sugarsync authenticate!', 'backwpup' ); ?>"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="wizard_button" class="button"
															 value="<?php esc_html_e( 'Create Sugarsync account', 'backwpup' ); ?>"/>
						<br/>
						<?php }
					else { ?>
						<strong><?php esc_html_e( 'Login:', 'backwpup' ); ?></strong>&nbsp;
						<span class="bwu-message-success"><?php esc_html_e( 'Authenticated!', 'backwpup' ); ?></span>
						<input type="submit" name="wizard_button" class="button-primary" value="<?php esc_html_e( 'Delete Sugarsync authentication!', 'backwpup' ); ?>"/>
						<br/>
						<strong><?php esc_html_e( 'Root:', 'backwpup' ); ?></strong>
						<?php
						try {
							$sugarsync   = new BackWPup_Destination_SugarSync_API( $job_settings[ 'sugarrefreshtoken' ] );
							$user        = $sugarsync->user();
							$syncfolders = $sugarsync->get( $user->syncfolders );
							if ( ! is_object( $syncfolders ) )
								echo '<span class="bwu-message-error">' . esc_html__( 'No Syncfolders found!', 'backwpup' ) . '</span>';
						}
						catch ( Exception $e ) {
							echo '<span class="bwu-message-error">' . $e->getMessage() . '</span>';
						}
						if ( isset( $syncfolders ) && is_object( $syncfolders ) ) {
							echo '<select name="sugarroot" id="sugarroot">';
							foreach ( $syncfolders->collection as $roots ) {
								echo '<option ' . selected( strtolower( $job_settings[ 'sugarroot' ] ), strtolower( $roots->ref ), FALSE ) . ' value="' . esc_attr($roots->ref) . '"">' . esc_html($roots->displayName) . '</option>';
							}
							echo '</select>';
						}
						?>
					<?php } ?>
					<br/>
					<label id="idsugardir"><strong><?php esc_html_e( 'Folder:', 'backwpup' ); ?></strong><br/>
					<input name="sugardir" id="idsugardir" type="text" value="<?php echo esc_attr( $job_settings[ 'sugardir' ] );?>" class="large-text" /></label><br/>
					<?php if ( $job_settings[ 'backuptype' ] === 'archive' ) { ?>
						<label for="idsugarmaxbackups"><input name="sugarmaxbackups" id="idsugarmaxbackups" type="text" size="3" value="<?php echo esc_attr($job_settings[ 'sugarmaxbackups' ]);?>" class="small-text" />
						<?php esc_html_e( 'Maximum number of backup files to keep in folder:', 'backwpup' ); ?>
						<br/>
					<?php } else { ?>
						<label for="idsugarsyncnodelete"><input class="checkbox" value="1"
												  type="checkbox" <?php checked( $job_settings[ 'sugarsyncnodelete' ], TRUE ); ?>
												  name="sugarsyncnodelete" id="idsugarsyncnodelete" /> <?php esc_html_e( 'Do not delete files while syncing to destination!', 'backwpup' ); ?></label>
						<br/>
					<?php } ?>
					</fieldset>
				</td>
			</tr>
		</table>
	<?php
	}

	/**
	 * @param $job_settings array
	 *
	 * @return array
	 */
	public function wizard_save( array $job_settings ) {

		if ( ! empty( $_POST[ 'sugaremail' ] ) && ! empty( $_POST[ 'sugarpass' ] ) && $_POST[ 'wizard_button' ] == __( 'Sugarsync authenticate!', 'backwpup' ) ) {
			try {
				$sugarsync     = new BackWPup_Destination_SugarSync_API();
				$refresh_token = $sugarsync->get_Refresh_Token( sanitize_email( $_POST[ 'sugaremail' ] ), sanitize_text_field( $_POST[ 'sugarpass' ] ) );
				if ( ! empty( $refresh_token ) )
					$job_settings[ 'sugarrefreshtoken' ] = $refresh_token ;
			}
			catch ( Exception $e ) {
				BackWPup_Admin::message( 'SUGARSYNC: ' . $e->getMessage(), TRUE );
			}
		}

		if ( isset( $_POST[ 'wizard_button' ] ) && $_POST[ 'wizard_button' ] === __( 'Delete Sugarsync authentication!', 'backwpup' ) ) {
			$job_settings[ 'sugarrefreshtoken' ] = '';
		}

		if ( isset( $_POST[ 'wizard_button' ] ) && $_POST[ 'wizard_button' ] === __( 'Create Sugarsync account', 'backwpup' ) ) {
			try {
				$sugarsync = new BackWPup_Destination_SugarSync_API();
				$sugarsync->create_account( sanitize_email( $_POST[ 'sugaremail' ] ), sanitize_text_field( $_POST[ 'sugarpass' ] ) );
			}
			catch ( Exception $e ) {
				BackWPup_Admin::message( 'SUGARSYNC: ' . $e->getMessage(), TRUE );
			}
		}

		$_POST[ 'sugardir' ] = trailingslashit( str_replace( '//', '/', str_replace( '\\', '/', trim( sanitize_text_field( $_POST[ 'sugardir' ] ) ) ) ) );
		if ( substr( $_POST[ 'sugardir' ], 0, 1 ) == '/' ) {
			$_POST[ 'sugardir' ] = substr( $_POST[ 'sugardir' ], 1 );
		}
		if ( $_POST[ 'sugardir' ] == '/' ) {
			$_POST[ 'sugardir' ] = '';
		}
		$job_settings[ 'sugardir' ] = sanitize_text_field( $_POST[ 'sugardir' ] );

		$job_settings[ 'sugarroot' ] = isset( $_POST[ 'sugarroot' ] ) ? sanitize_text_field( $_POST[ 'sugarroot' ] ) : '';
		$job_settings[ 'sugarmaxbackups' ] = isset( $_POST[ 'sugarmaxbackups' ] ) ? absint( $_POST[ 'sugarmaxbackups' ] ) : 0;
		$job_settings[ 'sugarsyncnodelete' ] = ! empty( $_POST[ 'sugarsyncnodelete' ] );

		return $job_settings;
	}
}
