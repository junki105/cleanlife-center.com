<?php
/**
 * Page for displaying Wizard
 *
 * @internal $wizards[$_SESSION['backwpup_wizard']['wizard']['id']] BackWPup_Pro_Wizards
 */
class BackWPup_Pro_Page_Wizard {

	/**
	 * @var array
	 */
	public static $wiz_data = array();

	/**
	 * @var int
	 */
	public static $wiz_data_id = 0;

	/**
	 * Load
	 *
	 * @return void
	 */
	public static function load() {

		global $_wp_using_ext_object_cache;

		//disable caching for site transient
		$_wp_using_ext_object_cache = false;

		//generate id if not existing
		if ( ! empty ( $_COOKIE['BackWPup_Wizard_ID'] ) ) {
			self::$wiz_data_id = $_COOKIE['BackWPup_Wizard_ID'];
		}
		if ( empty( self::$wiz_data_id ) && ! empty( $_REQUEST['BackWPup_Wizard_ID'] ) ) {
			self::$wiz_data_id = $_REQUEST['BackWPup_Wizard_ID'];
		}
		if ( empty( self::$wiz_data_id ) ) {
			self::$wiz_data_id = get_current_user_id() . time();
		}

		//set cookie and get wizard data
		setcookie( 'BackWPup_Wizard_ID', self::$wiz_data_id, time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
		self::$wiz_data = get_site_transient( 'BackWPup_Wiz_' . self::$wiz_data_id );

		//add save data method
		add_action( 'shutdown', array( __CLASS__, 'save_wiz_data' ) );

		// get wizards
		$wizards = BackWPup::get_wizards();

		//if set wizard_start restart wizard with this
		if ( ! empty ( $_GET['wizard_start'] ) ) {
			//empty existing wizard
			self::$wiz_data = array();
			//set wizard id
			self::$wiz_data['wizard']['id'] = strtoupper( $_GET['wizard_start'] );
			//get referrer
			self::$wiz_data['wizard']['referrer'] = wp_get_referer();
			// add empty message
			self::$wiz_data['wizard']['message'] = '';
			//add pre configuration
			if ( ! empty( $_GET['pre_conf'] ) ) {
				self::$wiz_data['wizard']['pre_config'] = $wizards[ self::$wiz_data['wizard']['id'] ]->get_pre_configurations( $_GET['pre_conf'] );
			} else {
				self::$wiz_data['wizard']['pre_config'] = false;
			}
			//get wizard start settings
			self::$wiz_data = $wizards[ self::$wiz_data['wizard']['id'] ]->initiate( self::$wiz_data );
		}

		//go to wizard selection if no one (comes up on empty settings)
		if ( empty( self::$wiz_data['wizard']['id'] ) ) {
			// only on wizard set this
			if ( 1 == count( $wizards ) ) {
				//stet wizard to start directly
				self::$wiz_data['wizard']['id'] = (int) array_keys( $wizards );
				//get referrer
				self::$wiz_data['wizard']['referrer'] = wp_get_referer();
			} else {
				self::$wiz_data = array();

				return;
			}
		}

		//check permissions
		if ( ! current_user_can( $wizards[ self::$wiz_data['wizard']['id'] ]->info['cap'] ) ) {
			self::$wiz_data = array();
			wp_die( 'No rights!', 'backwpup' );
		}

		// get steps of that wizard
		$wizard_steps = $wizards[ self::$wiz_data['wizard']['id'] ]->get_steps( self::$wiz_data );

		//on empty wizard step get the first
		if ( empty( self::$wiz_data['wizard']['step'] ) ) {
			if ( isset( $wizard_steps[0] ) ) {
				self::$wiz_data['wizard']['step'] = $wizard_steps[0]['id'];
			} else {
				self::$wiz_data['wizard']['step'] = 'last';
			}
		}

		//if special step submitted
		if ( ! empty( $_GET['step'] ) ) {
			foreach ( $wizard_steps as $step ) {
				if ( $step['id'] == $_GET['step'] ) {
					self::$wiz_data['wizard']['step'] = $_GET['step'];
					break;
				}
			}
			if ( $_GET['step'] == 'last' ) {
				self::$wiz_data['wizard']['step'] = 'last';
			}
		}

	}

	/**
	 * Save Post Form
	 *
	 * @uses wp_save_redirect() To redirect the user.
	 *
	 * @return void
	 */
	public static function save_post_form() {

		global $_wp_using_ext_object_cache;

		check_admin_referer( 'wizard' );

		//disable caching for site transient
		$_wp_using_ext_object_cache = false;

		// get wizards
		$wizards = BackWPup::get_wizards();

		if ( ! empty( $_COOKIE['BackWPup_Wizard_ID'] ) ) {
			self::$wiz_data_id = $_COOKIE['BackWPup_Wizard_ID'];
		}
		if ( empty( self::$wiz_data_id ) && ! empty( $_POST['BackWPup_Wizard_ID'] ) ) {
			self::$wiz_data_id = $_POST['BackWPup_Wizard_ID'];
		}

		//start using sessions
		self::$wiz_data = get_site_transient( 'BackWPup_Wiz_' . self::$wiz_data_id );

		if ( empty( self::$wiz_data['wizard']['id'] ) ) {
			wp_die( __( 'No BackWPup Wizard Session found!', 'backwpup' ) );
		}

		//add save data method
		add_action( 'shutdown', array( __CLASS__, 'save_wiz_data' ) );

		//check permissions
		if ( ! current_user_can( $wizards[ self::$wiz_data['wizard']['id'] ]->info['cap'] ) ) {
			self::$wiz_data = array();
			wp_die( 'No rights!', 'backwpup' );
		}

		//if cancel button hit do nothing else
		if ( isset( $_POST['wizard_button'] ) && $_POST['wizard_button'] == __( 'Cancel', 'backwpup' ) ) {
			$wizards[ self::$wiz_data['wizard']['id'] ]->cancel( self::$wiz_data );
			$referrer       = self::$wiz_data['wizard']['referrer'];
			self::$wiz_data = array();
			if ( ! empty( $referrer ) ) {
				wp_safe_redirect( $referrer );
			} else {
				wp_safe_redirect( network_admin_url( 'admin.php' ) . '?page=backwpupwizard&BackWPup_Wizard_ID=' . self::$wiz_data_id );
			}

			return;
		}

		$current_step = self::$wiz_data['wizard']['step'];

		//call save method for saving settings to wizard session form other
		self::$wiz_data = $wizards[ self::$wiz_data['wizard']['id'] ]->save( self::$wiz_data );

		// get steps of that wizard
		$wizard_steps = $wizards[ self::$wiz_data['wizard']['id'] ]->get_steps( self::$wiz_data );

		//is jQuery submit
		if ( isset( $_POST['nextstep'] ) && $_POST['nextstep'] != self::$wiz_data['wizard']['step'] ) {
			foreach ( $wizard_steps as $step ) {
				if ( $step['id'] === $_POST['nextstep'] ) {
					self::$wiz_data['wizard']['step'] = $_POST['nextstep'];
					break;
				}
			}
			if ( $_POST['nextstep'] == 'last' ) {
				self::$wiz_data['wizard']['step'] = 'last';
			}
		}

		//if Button pressed
		if ( isset( $_POST['wizard_button'] ) ) {
			//get next step on next
			if ( $_POST['wizard_button'] === __( 'Next ›', 'backwpup' ) ) {
				$key_now = 0;
				foreach ( $wizard_steps as $key => $step ) {
					if ( $step['id'] == self::$wiz_data['wizard']['step'] ) {
						$key_now = $key;
						break;
					}
				}
				$key_new                          = $key_now + 1;
				self::$wiz_data['wizard']['step'] = $wizard_steps[ $key_new ]['id'];
				//while on step if message
				$message = BackWPup_Admin::get_messages();
				if ( ! empty( $message ) ) {
					self::$wiz_data['wizard']['step'] = $current_step;
				}
			} //get previous step on previous
			elseif ( $_POST['wizard_button'] === __( '‹ Previous', 'backwpup' ) ) {
				$key_now = 0;
				foreach ( $wizard_steps as $key => $step ) {
					if ( $step['id'] == self::$wiz_data['wizard']['step'] ) {
						$key_now = $key;
						break;
					}
				}
				$key_new                          = $key_now - 1;
				self::$wiz_data['wizard']['step'] = $wizard_steps[ $key_new ]['id'];
			}

			//if last button hit
			if ( $_POST['wizard_button'] === $wizards[ self::$wiz_data['wizard']['id'] ]->get_last_button_name( self::$wiz_data ) ) {
				self::$wiz_data['wizard']['step'] = 'last';
			}
		}

		//Back to wizard
		wp_safe_redirect( network_admin_url( 'admin.php' ) . '?page=backwpupwizard&BackWPup_Wizard_ID=' . self::$wiz_data_id . '&step=' . esc_attr( self::$wiz_data['wizard']['step'] ) );
	}

	/**
	 * Save Data
	 *
	 * @return void
	 */
	public static function save_wiz_data() {

		if ( empty( self::$wiz_data_id ) ) {
			return;
		}

		if ( ! empty( self::$wiz_data ) ) {
			set_site_transient( 'BackWPup_Wiz_' . self::$wiz_data_id, self::$wiz_data, HOUR_IN_SECONDS );
		} else {
			delete_site_transient( 'BackWPup_Wiz_' . self::$wiz_data_id );
		}
	}

	/**
	 * Print Styles for the current wizard
	 *
	 * @return void
	 */
	public static function admin_print_styles() {

		// get wizards
		$wizards = BackWPup::get_wizards();

		//call wizard class admin_print_styles
		if ( isset( self::$wiz_data['wizard']['id'] ) ) {
			$wizards[ self::$wiz_data['wizard']['id'] ]->admin_print_styles( self::$wiz_data );
		}

	}

	/**
	 * Print Scripts for the current wizard
	 *
	 * @return void
	 */
	public static function admin_print_scripts() {

		// get wizards
		$wizards = BackWPup::get_wizards();

		wp_enqueue_script( 'backwpupgeneral' );

		//call wizard class admin_print_styles
		if ( isset( self::$wiz_data['wizard']['id'] ) ) {
			$wizards[ self::$wiz_data['wizard']['id'] ]->admin_print_scripts( self::$wiz_data );
		}

	}

	/**
	 * Page Rendering Callback
	 *
	 * @return void
	 */
	public static function page() {

		// get wizards
		$wizards = BackWPup::get_wizards();

		//show wizard selection
		if ( empty( self::$wiz_data ) ) {

			?>
			<div class="wrap" id="backwpup-page">
				<h1><?php echo esc_html( sprintf( _x( '%s &rsaquo; Wizards', 'Plugin Name', 'backwpup' ), BackWPup::get_plugin_data( 'name' ) ) ); ?></h1>
				<?php
				foreach ( $wizards as $wizard_class ) {
					//check permissions
					if ( ! current_user_can( $wizard_class->info['cap'] ) ) {
						continue;
					}
					//get info of wizard
					echo '<div class="wizardbox post-box backwpup-floated-postbox" id="wizard-' . strtolower( $wizard_class->info['ID'] ) . '"><form method="get" action="' . esc_url( network_admin_url( 'admin.php' ) ) . '">';
					echo '<h3 class="wizardbox_name">' . esc_html( $wizard_class->info['name'] ) . '</h3>';
					echo '<p class="wizardbox_description">' . esc_html( $wizard_class->info['description'] ) . '</p>';
					$conf_names = $wizard_class->get_pre_configurations();
					if ( ! empty ( $conf_names ) ) {
						echo '<select id="wizardbox_pre_conf" name="pre_conf" size="1">';
						foreach ( $conf_names as $conf_key => $conf_name ) {
							echo '<option value="' . esc_attr( $conf_key ) . '">' . esc_attr( $conf_name ) . '</option>';
						}
						echo '</select>';
					} else {
						echo '<input type="hidden" name="pre_conf" value="" />';
					}
					wp_nonce_field( 'wizard' );
					echo '<input type="hidden" name="page" value="backwpupwizard" />';
					echo '<input type="hidden" name="wizard_start" value="' . esc_attr( $wizard_class->info['ID'] ) . '" />';
					echo '<div class="wizardbox_start"><input type="submit" name="submit" class="button button-primary button-primary-bwp" value="' . esc_attr__( 'Start wizard', 'backwpup' ) . '" /></div>';
					echo '</form></div>';
				}
				?>
			</div>
			<?php
		} //execute wizard
		else {

			//get steps of wizard
			$wizard_steps = $wizards[ self::$wiz_data['wizard']['id'] ]->get_steps( self::$wiz_data );

			?>
			<div class="wrap" id="backwpup-page">
				<h2 id="wizard_name"><span
						id="backwpup-page-icon">&nbsp;</span><?php echo esc_html( sprintf( _x( '%s Wizard:', 'Plugin Name', 'backwpup' ), BackWPup::get_plugin_data( 'name' ) ) ) . ' ' . esc_html( $wizards[ self::$wiz_data['wizard']['id'] ]->info['name'] ); ?>
				</h2>
				<?php BackWPup_Admin::display_messages(); ?>
				<div id="wizard_description">
					<?php if ( isset ( self::$wiz_data['wizard']['pre_config']['name'] ) ) { ?>
						<h3><?php echo esc_html( self::$wiz_data['wizard']['pre_config']['description'] ); ?></h3>
					<?php } else { ?>
						<h3><?php echo esc_html( $wizards[ self::$wiz_data['wizard']['id'] ]->info['description'] ); ?></h3>
					<?php } ?>
				</div>
				<form method="post" id="wizard_form" enctype="multipart/form-data"
				      action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="backwpup_wizard"/>
					<input type="hidden" name="BackWPup_Wizard_ID"
					       value="<?php echo esc_attr( self::$wiz_data_id ); ?>"/>
					<input type="hidden" name="nextstep"
					       value="<?php echo esc_html( self::$wiz_data['wizard']['step'] ); ?>"/>
					<?php
					wp_nonce_field( 'wizard' );
					wp_nonce_field( 'backwpup_ajax_nonce', 'backwpupajaxnonce', false );
					?>
					<div id="wizard">
						<div id="wizard_navigation">
							<ul>
								<?php
								foreach ( $wizard_steps as $step ) {
									$current_class = '';
									if ( self::$wiz_data['wizard']['step'] == $step['id'] ) {
										$current_class = 'current';
									}
									echo '<li class="' . esc_attr( $current_class ) . '">';
									if ( self::$wiz_data['wizard']['step'] != 'last' && ! $wizards[ self::$wiz_data['wizard']['id'] ]->is_step_by_step( self::$wiz_data ) ) {
										echo '<a class="wizardsteplink" href="' . esc_url( network_admin_url( 'admin.php' ) . '?page=backwpupwizard&BackWPup_Wizard_ID=' . self::$wiz_data_id . '&step=' . $step['id'] ) . '" data-nextstep="' . esc_attr( $step['id'] ) . '">';
									}
									echo esc_html( $step['name'] );
									if ( self::$wiz_data['wizard']['step'] != 'last' && ! $wizards[ self::$wiz_data['wizard']['id'] ]->is_step_by_step( self::$wiz_data ) ) {
										echo '</a>';
									}
									echo '</li>';
								}
								if ( self::$wiz_data['wizard']['step'] == 'last' ) {
									echo '<li class="execute current">' . esc_attr( $wizards[ self::$wiz_data['wizard']['id'] ]->get_last_button_name( self::$wiz_data ) ) . '</li>';
								} else {
									if ( $wizards[ self::$wiz_data['wizard']['id'] ]->is_step_by_step( self::$wiz_data ) ) {
										echo '<li class="execute">' . esc_attr( $wizards[ self::$wiz_data['wizard']['id'] ]->get_last_button_name( self::$wiz_data ) ) . '</li>';
									} else {
										echo '<li class="execute"><a class="wizardsteplink" href="' . esc_url( network_admin_url( 'admin.php' ) . '?page=backwpupwizard&BackWPup_Wizard_ID=' . self::$wiz_data_id . '&step=last' ) . '" data-nextstep="last">' . esc_html( $wizards[ self::$wiz_data['wizard']['id'] ]->get_last_button_name( self::$wiz_data ) ) . '</a></li>';
									}
								}
								?>
							</ul>
						</div>
						<div id="wizard_settings">
							<div id="wizard_settings_area">
								<?php
								//call Wizard step Method
								if ( self::$wiz_data['wizard']['step'] != 'last' ) {
									$wizards[ self::$wiz_data['wizard']['id'] ]->page( self::$wiz_data );
								} else {
									$wizards[ self::$wiz_data['wizard']['id'] ]->execute( self::$wiz_data );
								}
								?>
							</div>
						</div>
						<div id="wizard_navbuttons">
							<?php
							if ( isset( self::$wiz_data['wizard']['step'] ) && self::$wiz_data['wizard']['step'] != 'last' ) {
								if ( $wizard_steps[0]['id'] != self::$wiz_data['wizard']['step'] ) {
									echo '<input type="submit" id="wizard-button-previous" name="wizard_button" class="button button-bwp button-previous-bwp" value="' . esc_html__( '‹ Previous', 'backwpup' ) . '" tabindex="2" accesskey="b" />&nbsp;';
								}
								$highest_step_key = count( $wizard_steps ) - 1;
								if ( $wizard_steps[ $highest_step_key ]['id'] != self::$wiz_data['wizard']['step'] || ( isset( $wizard_steps[ $highest_step_key ]['create'] ) && ! $wizard_steps[ $highest_step_key ]['create'] ) ) {
									echo '<input type="submit" id="wizard-button-next" name="wizard_button" class="button button-bwp button-primary-bwp button-next-bwp" value="' . esc_html__( 'Next ›', 'backwpup' ) . '" tabindex="1" accesskey="n" />&nbsp;';
								}
								if ( $wizard_steps[ $highest_step_key ]['id'] == self::$wiz_data['wizard']['step'] && ! ( isset( $wizard_steps[ $highest_step_key ]['create'] ) && ! $wizard_steps[ $highest_step_key ]['create'] ) ) {
									echo '<input type="submit" id="wizard-button-create" name="wizard_button" class="button button-primary-bwp button-submit-bwp" value="' . esc_attr( $wizards[ self::$wiz_data['wizard']['id'] ]->get_last_button_name( self::$wiz_data ) ) . '" tabindex="3" accesskey="f" />&nbsp;';
								}
								echo '<input type="submit" id="wizard-button-chancel" name="wizard_button" class="button button-bwp button-cancel-bwp" value="' . esc_html__( 'Cancel', 'backwpup' ) . '" tabindex="3" />';
							} else {
								if ( ! empty( self::$wiz_data['wizard']['referrer'] ) ) {
									echo '<a class="button button-bwp" id="wizard-button-back" href="' . self::$wiz_data['wizard']['referrer'] . '">' . __( 'Back to overview', 'backwpup' ) . '</a>';
								}
							}
							?>
						</div>
					</div>

				</form>
			</div>

			<script type="text/javascript">
				jQuery( document ).ready( function ( $ ) {
					var changed = false;
					$( 'input, textarea, select' ).change( function () {
						changed = true;
					} );
					$( '.wizardsteplink' ).click( function () {
						if ( changed ) {
							$( 'input[name="nextstep"]' ).val( $( this ).data( "nextstep" ) );
							$( '#wizard_form' ).submit();
							return false;
						}
					} );
					$( 'body' ).keypress( function ( e ) {
						if ( e.which == 13 ) {
							$( '<input type="hidden" name="wizard_button" value="<?php _e( 'Next ›', 'backwpup' ); ?>" />' ).appendTo( '#wizard_form' );
							$( '#wizard_form' ).submit();
						}
					} );
					$( 'input[name="destinations[]"]' ).change( function () {
						if ( $( 'input[name="destinations[]"]:checked' ).val() ) {
							$( '#wizard-button-next' ).show();
						} else {
							$( '#wizard-button-next' ).hide();
						}
					} );
					if ( $( 'input[name="destinations[]"]' ).val() ) {
						if ( $( 'input[name="destinations[]"]:checked' ).val() ) {
							$( '#wizard-button-next' ).show();
						} else {
							$( '#wizard-button-next' ).hide();
						}
					}
				} );
			</script>
			<?php
			//add inline js
			$wizards[ self::$wiz_data['wizard']['id'] ]->inline_js( self::$wiz_data );

			//delete setting on execute
			if ( self::$wiz_data['wizard']['step'] == 'last' ) {
				self::$wiz_data = array();
			}
		}

	}
}
