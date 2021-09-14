<?php
/**
 *
 */
class BackWPup_Pro_JobType_DBCheck extends BackWPup_JobType_DBCheck {


	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {
		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title"><?php esc_html_e( 'Settings for database check', 'backwpup' ) ?></h3>
					<p></p>
					<fieldset>
						<label for="iddbcheckwponly">
							<input
								class="checkbox"
								value="1"
								type="checkbox"
								<?php checked( $job_settings[ 'dbcheckwponly' ], TRUE ); ?>
								name="dbcheckwponly"
								id="iddbcheckwponly" />
							<?php esc_html_e( 'Check only WordPress Database tables', 'backwpup' ); ?>
						</label><br />

						<label for="iddbcheckrepair">
							<input
								class="checkbox"
								value="1"
								id="iddbcheckrepair"
								type="checkbox"
								<?php checked( $job_settings[ 'dbcheckrepair' ], TRUE ); ?>
							   name="dbcheckrepair" />
							<?php esc_html_e( 'Try to repair defect table', 'backwpup' ); ?>
						</label><br />
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * @param $job_settings
	 *
	 * @return array
	 */
	public function wizard_save( array $job_settings ) {
		$job_settings[ 'dbcheckwponly' ] = ! empty( $_POST[ 'dbcheckwponly' ] );
		$job_settings[ 'dbcheckrepair' ] = ! empty( $_POST[ 'dbcheckrepair' ] );

		return $job_settings;
	}

}
