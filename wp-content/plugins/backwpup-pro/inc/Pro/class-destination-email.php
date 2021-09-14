<?php
/**
 *
 */
class BackWPup_Pro_Destination_Email extends BackWPup_Destination_Email {


	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {
		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title"><?php esc_html_e( 'Email address', 'backwpup' ); ?></h3>
					<fieldset>
						<label for="emailaddress"><strong><?php esc_html_e( 'Email address', 'backwpup' ); ?></strong><br/>

							<input
								name="emailaddress"
								id="emailaddress"
								type="text"
								value="<?php echo esc_attr( $job_settings[ 'emailaddress' ] );?>"
								class="regular-text"
							/>
						</label><br />

						<label for="sendemailtest"><strong><?php esc_html_e( 'Send test email', 'backwpup' ); ?></strong><br/>
							<button id="sendemailtest" class="button secondary"><?php esc_html_e( 'Send test email', 'backwpup' ); ?></button>
						</label>
					</fieldset>
                </td>
            </tr>
		</table>
		<?php
    }


	public function wizard_inline_js() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('#sendemailtest').live('click', function () {
					$('#sendemailtest').after('&nbsp;<img id="emailsendtext" src="<?php echo get_admin_url() . 'images/loading.gif'; ?>" width="16" height="16" />');
					var data = {
						action: 'backwpup_dest_email',
						emailaddress: $('input[name="emailaddress"]').val(),
						emailsndemail: '<?php echo esc_attr( get_bloginfo( 'admin_email' ) ); ?>',
						_ajax_nonce: $('#backwpupajaxnonce').val()
					};
					$.post(ajaxurl, data, function (response) {
						$('#emailsendtext').replaceWith(response);
					});
					return false;
				});
			});
		</script>
	<?php
	}

	/**
	 * @param $job_settings
	 *
	 * @return array
	 */
	public function wizard_save( array $job_settings ) {

		$job_settings[ 'emailaddress' ] = isset( $_POST[ 'emailaddress' ] ) ? sanitize_email( $_POST[ 'emailaddress' ] ) : '';

		$job_settings[ 'emailefilesize' ] = 25;
		$job_settings[ 'emailsndemail' ] = get_bloginfo( 'admin_email' );
		$job_settings[ 'emailsndemailname' ] = 'BackWPup ' . get_bloginfo( 'name' );
		$job_settings[ 'emailmethod' ] = '';

		return $job_settings;
	}

}
