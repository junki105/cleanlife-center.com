<?php
/**
 * AddQuicktag - Settings for enhanced code buttons
 *
 * @license    GPLv3
 * @package    AddQuicktag
 * @subpackage AddQuicktag Settings
 * @author     Frank Bueltge <frank@bueltge.de>
 * @since      01/26/2014
 * @version    2015-12-23
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

/**
 * Class Add_Quicktag_Code_Quicktags
 */
class Add_Quicktag_Code_Quicktags extends Add_Quicktag_Settings {

	/**
	 * Post types for the settings
	 *
	 * @var
	 */
	private static $code_quicktags = array(
		'enhanced_code' => 'pre',
		'en_de_coding'  => 'htmlentities',
	);

	/**
	 * Handler for the action 'init'. Instantiates this class.
	 *
	 * @access  public
	 * @since   2.0.0
	 * @return \Add_Quicktag|\Add_Quicktag_Code_Quicktags|\Add_Quicktag_Settings $instance
	 */
	public static function get_object() {
		static $instance;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Constructor, init on defined hooks of WP and include second class.
	 *
	 * @access  public
	 * @since   0.0.2
	 * @uses    register_activation_hook, register_uninstall_hook, add_action
	 */
	private function __construct() {
		add_action( 'addquicktag_settings_form_page', array( $this, 'get_code_quicktag_area' ) );
	}

	/**
	 * Add settings area.
	 *
	 * @param array $options Store options content.
	 */
	public function get_code_quicktag_area( $options ) {
		if ( ! array_key_exists( 'code_buttons', $options ) ) {
			$options['code_buttons'] = array();
		}
		?>
		<h3><?php esc_html_e( 'Enhanced Code Quicktag buttons', 'addquicktag' ); ?></h3>
		<p><?php esc_html_e( 'Select the checkbox below to add enhanced code buttons.', 'addquicktag' ); ?></p>
		<h4><?php esc_html_e( 'pre: Enhanced Code buttons', 'addquicktag' ); ?></h4>
		<p><?php esc_html_e( 'Enhanced the default Code buttons. Add a pull down menu for different languages before the default code button and include this as class inside the code tag. Also add a pre button for preformatted text.', 'addquicktag' ); ?></p>
		<h4><?php esc_html_e( 'htmlentities: HTML Entities, HTML Decode', 'addquicktag' ); ?></h4>
		<p><?php esc_html_e( 'Add buttons to do the inconvient HTML encoding/decoding, like &lt; to &amp;lt; and back.', 'addquicktag' ); ?></p>

		<?php
		// Loop about the post types, create html an values for title in table.
		$pt_title    = '';
		$pt_colgroup = '';
		foreach ( $this->get_post_types_for_js() as $post_type ) {
			$pt_title    .= '<th class="row-title rotate" title="Post Type"><span><code>' . $post_type . '</code></span></th>' . "\n";
			$pt_colgroup .= '<colgroup></colgroup>' . "\n";
		}
		?>

		<table class="widefat form-table rmnlCodeQuicktagSettings">
			<colgroup></colgroup>
			<?php echo $pt_colgroup; ?>
			<colgroup></colgroup>

			<thead>
			<tr>
				<th class="row-title"><?php esc_html_e( 'Button', 'addquicktag' ); ?></th>
				<?php echo $pt_title; ?>
				<th class="row-title num" style="width:3%;">&#x2714;</th>
			</tr>
			</thead>

			<tbody>
			<?php
			// Loop over items to remove and unset them from the buttons.
			$i = 9999;
			foreach ( self::$code_quicktags as $key => $value ) {
				echo '<tr id="rmqtb' . $i . '">' . "\n";
				echo '<td><input type="button" class="ed_button" title="" value="' . $value . '"></td>';

				// Loop about the post types, create html an values.
				$pt_checkboxes = '';
				foreach ( $this->get_post_types_for_js() as $post_type ) {
					$pt_checked = '';
					if ( isset( $options['code_buttons'][ $value ][ $post_type ] )
						&& 1 === (int) $options['code_buttons'][ $value ][ $post_type ]
					) {
						$pt_checked = ' checked="checked"';
					}

					$pt_checkboxes .= '<td class="num"><input type="checkbox" name="' .
									parent::get_option_string() . '[code_buttons][' .
									$value . '][' . $post_type . ']" value="1"' .
									$pt_checked . '/></td>' . "\n";
				}
				echo $pt_checkboxes;

				echo '<td class="num"><input type="checkbox" class="toggle" id="select_all_' . $i . '" value="' . $i . '" /></td>' . "\n";

				echo '</tr>' . "\n";
				$i ++;
			}
			?>
			</tbody>
		</table>
		<?php
	}

} // end class

$add_quicktag_code_quicktags = Add_Quicktag_Code_Quicktags::get_object();
