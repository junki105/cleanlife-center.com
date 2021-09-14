<?php
/**
 * Restore Pro Page
 */

use \Inpsyde\BackWPup\Pro\Restore\Functions;
use \Inpsyde\BackWPup\Pro\Restore\TemplateLoader;

/**
 * Class for BackWPup restore page
 */
class BackWPup_Pro_Page_Restore {

	/**
	 * Enqueue JS
	 *
	 * @return void
	 */
    public static function admin_print_scripts()
    {
        $url = untrailingslashit(BackWPup::get_plugin_data('url'));
        $dir = untrailingslashit(BackWPup::get_plugin_data('plugindir'));
        $path_js = "{$url}/assets/js";
        $dir_js = "{$dir}/assets/js";
        $shared_scripts_path = "{$url}/vendor/inpsyde/backwpup-shared/resources/js";
        $shared_scripts_dir = "{$dir}/vendor/inpsyde/backwpup-shared/resources/js";
        $restore_scripts_path = "{$url}/vendor/inpsyde/backwpup-restore-shared/resources/js";
        $restore_scripts_dir = "{$dir}/vendor/inpsyde/backwpup-restore-shared/resources/js";
        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        // Vendor
        wp_register_script('js-url', "{$path_js}/vendor/url.min.js", array('jquery'), '', true);

        wp_register_script(
            'backwpup_functions',
            "{$shared_scripts_path}/functions{$suffix}.js",
            array(
                'underscore',
                'jquery',
            ),
            filemtime("{$shared_scripts_dir}/functions{$suffix}.js"),
            true
        );
        wp_register_script(
            'backwpup_states',
            "{$shared_scripts_path}/states{$suffix}.js",
            array(
                'backwpup_functions',
            ),
            filemtime("{$shared_scripts_dir}/states{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_functions',
            "{$restore_scripts_path}/restore-functions{$suffix}.js",
            array(
                'underscore',
                'jquery',
            ),
            filemtime("{$restore_scripts_dir}/restore-functions{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_decompress',
            "{$restore_scripts_path}/decompress{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
                'decrypter',
            ),
            filemtime("{$restore_scripts_dir}/decompress{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_download',
            "{$restore_scripts_path}/download{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
                'backwpup_states',
            ),
            filemtime("{$restore_scripts_dir}/download{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_strategy',
            "{$restore_scripts_path}/strategy{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
            ),
            filemtime("{$restore_scripts_dir}/strategy{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_database',
            "{$restore_scripts_path}/database{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
            ),
            filemtime("{$restore_scripts_dir}/database{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_database_restore',
            "{$restore_scripts_path}/database-restore{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
            ),
            filemtime("{$restore_scripts_dir}/database-restore{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_files_restore',
            "{$restore_scripts_path}/files-restore{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
            ),
            filemtime("{$restore_scripts_dir}/files-restore{$suffix}.js"),
            true
        );
        wp_register_script(
            'restore_controller',
            "{$restore_scripts_path}/controller{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
            ),
            filemtime("{$restore_scripts_dir}/controller{$suffix}.js"),
            true
        );
        wp_register_script(
            'decrypter',
            "{$restore_scripts_path}/decrypter{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'backwpup_functions',
                'restore_functions',
            ),
            filemtime("{$restore_scripts_dir}/controller{$suffix}.js"),
            true
        );

        wp_enqueue_script('backwpupgeneral', array(), '', true);
        wp_enqueue_script(
            'restore_restore',
            "{$path_js}/pro/restore{$suffix}.js",
            array(
                'underscore',
                'jquery',
                'plupload',
                'js-url',
                'backwpup_functions',
                'restore_functions',
                'restore_decompress',
                'restore_download',
                'restore_strategy',
                'restore_database',
                'restore_database_restore',
                'restore_files_restore',
                'restore_controller',
                'decrypter',
            ),
            filemtime("{$dir_js}/pro/restore{$suffix}.js"),
            true
        );
    }

	/**
	 * The Content of the page
	 *
	 * @return void
	 */
	public function content() {

		$template = new TemplateLoader( Functions\restore_container( null ) );
		$template->load();

		backwpup_template( null, '/pro/restore/index.php' );
	}

	/**
	 * Page Title
	 *
	 * @return void
	 */
	public function title() {

		echo esc_html(
			sanitize_text_field(
				sprintf(
				/* Translators: $1 is the name of the plugin */
					esc_html__( '%s &rsaquo; Restore', 'backwpup' ),
					BackWPup::get_plugin_data( 'name' )
				)
			)
		);
	}

	/**
	 * Load
	 *
	 * Load the basic for the page and also, perform stuffs before render the content.
	 *
	 * @return void
	 */
	public static function load() {

		do_action( 'backwpup_page_pro_restore' );
	}

	/**
	 * Entry method to display WordPress page.
	 */
	public static function page() {

		$restore_page = new self();

		?>
		<div class="wrap" id="backwpup-page">
			<h1>
				<?php $restore_page->title(); ?>
			</h1>

			<?php $restore_page->content(); ?>
		</div>
		<?php
	}
}
