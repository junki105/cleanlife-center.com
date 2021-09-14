<?php
/**
 * Page for handling support form
 */
class BackWPup_Pro_Page_Support {

	public static function save_post_form() {

		check_admin_referer( 'support' );
		
		$name = sanitize_text_field( $_POST['name'] );
		$email = sanitize_email( $_POST['email'] );
		$subject = sanitize_text_field( $_POST['subject'] );
		$message = $_POST['message'];
		$debug = isset( $_POST['debug'] ) ? true : false;
		$logfiles = isset( $_POST['logfiles'] ) ? $_POST['logfiles'] : array();
		
		if ( $debug ) {
			$information = BackWPup_Page_Settings::get_information();
			$message .= "\n\n--- Debug Information ---\n\n";
			foreach ( $information as $item ) {
				$message .= $item['label'] . ': ' . $item['value'] . "\n";
			}
		}
		
		if ( ! $email ) {
			wp_die( __( 'E-mail address is required.', 'backwpup' ) );
		}
		
		$logfolder = get_site_option( 'backwpup_cfg_logfolder' );
		
		foreach ( $logfiles as $i => $logfile ) {
			if ( validate_file( path_join( $logfolder, $logfile ) ) == 0 ) {
				$logfiles[ $i ] = BackWPup_File::get_absolute_path(
					 path_join( $logfolder, $logfile )
				);
			} else {
				unset( $logfiles[ $i ] );
			}
		}
		
		wp_mail(
			__( 'support@backwpup.com', 'backwpup' ),
			$subject,
			$message,
			"From: \"$name\" <$email>\r\n",
			$logfiles
		);
		
		BackWPup_Admin::message(
			__(
				'Message has been sent to support. We will get back to you shortly.',
				'backwpup'
			)
		);
		
		wp_safe_redirect(
			add_query_arg(
				array( 'page' => 'backwpupsupport' ),
				network_admin_url( 'admin.php' )
			)
		);
		exit;
	}
	
	public static function admin_print_scripts() {
		wp_enqueue_script( 'backwpupgeneral' );
	}

	public static function page() {
		$user = wp_get_current_user();
       ?>            
		<style type="text/css">
			.backwpupsupport_wrapper{
				width: 50%;
				padding-left:14px;
			}

			.backwpupsupport_wrapper input[type=email],.backwpupsupport_wrapper input[type=text], .backwpupsupport_wrapper textarea{
				width: 100%;
			}
		</style>
			<div class="backwpupsupport_wrapper">
				<h1><?php _e( 'Contact BackWPup Support', 'backwpup' ) ?></h1><?php
			BackWPup_Admin::display_messages();
		?><p><?php _e( 'Fill out the form below to contact BackWPup support. We will get back to you as soon as possible.', 'backwpup' ) ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>">
		<?php wp_nonce_field( 'support' ) ?>
		<input type="hidden" name="page" value="backwpupsupport" />
		<input type="hidden" name="action" value="backwpup_support" />
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="name"><?php _e( 'Name:', 'backwpup' ) ?></label>
				</th>
				<td>
					<input type="text" name="name" id="name" size="60"  required="required" value="<?php
						if ( ! empty( $user->user_firstname ) ) {
							echo esc_attr( $user->user_firstname );
							if ( ! empty( $user->user_lastname ) ) {
								echo ' ' . esc_attr( $user->user_lastname );
							}
						} else {
							echo esc_attr( $user->display_name );
						}
						?>" />
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="email"><?php _e( 'E-mail adress:', 'backwpup' ) ?></label>
				</th>
				<td>
					<input type="email" name="email" id="email" size="60" required="required"
						value="<?php echo esc_attr( $user->user_email ) ?>" />
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="subject"><?php _e( 'Subject:', 'backwpup' ) ?></label>
				</th>
				<td>
					<input type="text" name="subject" id="subject" size="60" required="required" />
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="message"><?php _e( 'Message:', 'backwpup' ) ?></label>
				</th>
				<td>
					<textarea name="message" id="message" required="required" cols="60" rows="5" style="padding:2px"></textarea>
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="debug"><?php _e( 'Send debug info:', 'backwpup' ) ?></label>
				</th>
				<td>
					<input type="checkbox" name="debug" id="debug" checked="checked" value="1" />
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="log"><?php _e( 'Select log to attach', 'backwpup' ) ?></label>
				</th>
				<td>
					<fieldset id="log"><?php
						$logfiles = array();
						$logfolder = BackWPup_File::get_absolute_path(
							get_site_option( 'backwpup_cfg_logfolder' )
						);
						if ( is_readable( $logfolder ) ) {
							$dir = new BackWPup_Directory( $logfolder );
							foreach ( $dir as $file ) {
								if ( $file->isFile() && $file->isReadable()
									&& strpos( $file->getFilename(), 'backwpup_log_' ) !== false
									&& strpos( $file->getFilename(), '.html' ) !== false ) {
									$logfiles[] = $file->getFilename();
								}
							}
							
							if ( count( $logfiles ) ) {
								rsort( $logfiles );
								
								// Get only the last 5 logs
								$logfiles = array_slice( $logfiles, 0, 5 );
								
								foreach ( $logfiles as $logfile ) {
									self::print_log_row( $logfile, $logfolder );
								}
							} else {
								printf(
									__( 'No logs could be found. If you want to attach a log, please ' .
										'<a href="%s">run a job first</a>.', 'backwpup' ),
									network_admin_url( 'admin.php?page=backwpupjobs' )
								);
							}
						} else {
							_e( 'Log folder not readable.', 'backwpup' );
						}
						?></fieldset>
				</td>
			</tr>
		</table>
		
		<?php submit_button( __( 'Send Message', 'backwpup' ) ) ?>

		</form>
        </div><!-- .backwpup_wrapper --><?php

	}
	
	private static function print_log_row( $logfile, $logfolder ) {
		$logdata = BackWPup_Job::read_logheader(
			path_join( $logfolder, $logfile )
		);
		
		?><label
			for="logfile-<?php echo esc_attr( $logdata['logtime'] ) ?>">
		<input type="checkbox" name="logfiles[]"
			id="logfile-<?php echo esc_attr( $logdata['logtime'] ) ?>"
			value="<?php echo esc_attr( $logfile ) ?>">
					<?php
				$logname = str_replace(
					array( '.html', '.gz' ),
					'',
					$logfile
				);
			?><a class="thickbox"
				href="<?php echo admin_url( 'admin-ajax.php' ) ?>?action=backwpup_view_log&log=<?php
					echo $logname
					?>&_ajax_nonce=<?php
					echo wp_create_nonce( 'view-log_' . $logname )
					?>&TB_iframe=true&width=640&height=440"
				title="<?php echo esc_attr( $logfile ) . "\n" .
					sprintf( __( 'Job ID: %d', 'backwpup' ), $logdata['jobid'] ) ?>">
				<?php echo esc_html( $logname ) ?>
			</a> (<?php
				if ( $logdata['errors'] || $logdata['warnings'] ) {
					if ( $logdata['errors'] ) {
						printf(
							_n( '1 error', '%d errors', $logdata['errors'], 'backwpup' ),
							$logdata['errors']
						);
					}
					if ( $logdata['warnings'] ) {
						if ( $logdata['errors'] ) {
							echo ' ' . _x( 'and', '1 error and 1 warning', 'backwpup' ) . ' ';
						}
						printf(
							_n( '1 warning', '%d warnings', $logdata['warnings'], 'backwpup' ),
							$logdata['warnings']
						);
					}
				} else {
					_e( 'no errors', 'backwpup' );
				}
			?>)
		</label><br /><?php
		}

}
