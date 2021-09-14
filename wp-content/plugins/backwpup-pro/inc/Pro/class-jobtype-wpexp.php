<?php
/**
 *
 */
class BackWPup_Pro_JobType_WPEXP extends BackWPup_JobType_WPEXP {


	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {

		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title"><?php esc_html_e( 'Items to export:', 'backwpup' ) ?></h3>
					<p></p>
					<fieldset>
						<label for="idwpexportcontent-all">
							<input
								type="radio"
								name="wpexportcontent"
								id="idwpexportcontent-all"
								value="all"
								<?php checked( $job_settings[ 'wpexportcontent' ], 'all' ); ?>
							/>
							<?php esc_html_e( 'All content', 'backwpup' ); ?>
						</label><br />
						<label for="idwpexportcontent-post">
							<input
								type="radio"
								name="wpexportcontent"
								id="idwpexportcontent-post"
								value="post"
								<?php checked( $job_settings[ 'wpexportcontent' ], 'post' ); ?>
							/>
							<?php esc_html_e( 'Posts', 'backwpup' ); ?>
						</label><br />
						<label for="idwpexportcontent-page">
							<input
								type="radio"
								name="wpexportcontent"
								id="idwpexportcontent-page"
								value="page"
								<?php checked( $job_settings[ 'wpexportcontent' ], 'page' ); ?>
							/>
							<?php esc_html_e( 'Pages', 'backwpup' ); ?>
						</label><br />
						<?php
						foreach ( get_post_types( array( '_builtin' => false, 'can_export' => true ), 'objects' ) as $post_type ) {
							?>
							<label for="idwpexportcontent-<?php echo esc_attr( $post_type->name ); ?>">
								<input
									type="radio"
									name="wpexportcontent"
									id="idwpexportcontent-<?php echo esc_attr( $post_type->name ); ?>"
									value="<?php echo esc_attr( $post_type->name ); ?>"
									<?php checked( $job_settings[ 'wpexportcontent' ], esc_attr( $post_type->name ) ); ?> />
								<?php echo esc_html( $post_type->label ); ?>
							</label><br />
						<?php
						}
						?>
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

		$job_settings[ 'wpexportcontent' ] = $_POST[ 'wpexportcontent' ] ;
		$job_settings[ 'wpexportfile' ] = sanitize_file_name( get_bloginfo( 'name' ) ) . '.wordpress.%Y-%m-%d';
		$job_settings[ 'wpexportfilecompression' ] = '';

		return $job_settings;
	}

}
