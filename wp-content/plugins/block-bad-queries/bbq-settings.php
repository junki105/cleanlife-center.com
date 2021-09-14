<?php // BBQ - Settings

if (!defined('ABSPATH')) exit;

function bbq_languages() {
	
	load_plugin_textdomain('block-bad-queries', false, BBQ_DIR .'languages/');
	
}
add_action('plugins_loaded', 'bbq_languages');

function bbq_options() {
	
	$bbq_options = array(
		
		'version' => BBQ_VERSION,
		
	);
	
	return $bbq_options;
}

function bbq_check_plugin() {
	
	if (class_exists('BBQ_Pro')) {
		
		if (is_plugin_active('block-bad-queries/block-bad-queries.php')) {
			
			$msg  = '<strong>'. esc_html__('Warning:', 'block-bad-queries') .'</strong> ';
			$msg .= esc_html__('Free and Pro versions of BBQ cannot be activated at the same time. ', 'block-bad-queries');
			$msg .= esc_html__('Please return to the ', 'block-bad-queries');
			$msg .= '<a href="'. admin_url('plugins.php') .'">'. esc_html__('WordPress Admin Area', 'block-bad-queries') .'</a> ';
			$msg .= esc_html__('and try again.', 'block-bad-queries');
			
			deactivate_plugins(BBQ_FILE);
			
			wp_die($msg);
			
		}
		
	}
	
}
add_action('admin_init', 'bbq_check_plugin');

function bbq_register_settings() {
	
	// register_setting( $option_group, $option_name, $sanitize_callback );
	register_setting('bbq_options_free', 'bbq_options_free', 'bbq_validate_options');
	
	// add_settings_section( $id, $title, $callback, $page ); 
	add_settings_section('general', esc_html__('Plugin Information', 'block-bad-queries'), 'bbq_settings_section_general', 'bbq_options_free');
	
	// add_settings_field( $id, $title, $callback, $page, $section, $args );
	add_settings_field('version', esc_html__('BBQ Version', 'block-bad-queries'), 'bbq_callback_version', 'bbq_options_free', 'general', array('id' => 'version', 'label' => ''));
	
	add_settings_section('upgrade', esc_html__('Upgrade to BBQ Pro', 'block-bad-queries'), 'bbq_settings_section_upgrade', 'bbq_options_free');
	
}
add_action('admin_init', 'bbq_register_settings');

function bbq_validate_options($input) {
	
	if (!isset($input['version'])) $input['version'] = null;
	
	return $input;
	
}

function bbq_settings_section_general() {
	
	echo '<p>'. esc_html__('Thanks for using the free version of ', 'block-bad-queries');
	echo '<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/block-bad-queries/">'. esc_html__('BBQ Firewall', 'block-bad-queries') .'</a>.</p>';
	echo '<p>'. esc_html__('The free version is completely plug-&amp;-play, protecting your site automatically with no settings required.', 'block-bad-queries') .'</p>';
	
}

function bbq_settings_section_upgrade() {
	
	$url  = esc_url('https://plugin-planet.com/bbq-pro/');
	$text = esc_html__('Upgrade your site security with advanced protection and complete control. ', 'block-bad-queries');
	$alt  = esc_attr__('BBQ Pro: Advanced WordPress Firewall', 'block-bad-queries');
	$src  = esc_url(BBQ_URL .'assets/bbq-pro-960x250.jpg');
	
	$upgrade  = '<p>';
	$upgrade .= $text;
	$upgrade .= '<a target="_blank" rel="noopener noreferrer" href="'. $url .'">'. esc_html__('Get BBQ Pro &raquo;', 'block-bad-queries') .'</a>';
	$upgrade .= '</p>';
	
	$upgrade .= '<p class="bbq-pro">';
	$upgrade .= '<a target="_blank" rel="noopener noreferrer" href="'. $url .'" title="'. $text .'"><img src="'. $src .'" width="480" height="125" alt="'. $alt .'"></a>';
	$upgrade .= '</p>';
	
	echo $upgrade;
	
}

function bbq_callback_version($args) {
	
	$bbq_options = get_option('bbq_options_free', bbq_options());
	
	$id = isset($args['id']) ? $args['id'] : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$version = isset($bbq_options[$id]) ? esc_html($bbq_options[$id]) : BBQ_VERSION;
	
	echo $version;
	
}

function bbq_action_links($links, $file) {
	
	if ($file == BBQ_FILE && current_user_can('manage_options')) {
		
		$settings_url   = admin_url('options-general.php?page=bbq_settings');
		$settings_title = esc_attr__('Visit the BBQ plugin page', 'block-bad-queries');
		$settings_text  = esc_html__('Settings', 'block-bad-queries');
		
		$settings_link  = '<a href="'. $settings_url .'" title="'. $settings_title .'">'. $settings_text .'</a>';
		
		array_unshift($links, $settings_link);
		
	}
	
	if ($file == BBQ_FILE) {
		
		$pro_url   = esc_url('https://plugin-planet.com/bbq-pro/');
		$pro_title = esc_attr__('Get BBQ Pro for advanced protection', 'block-bad-queries');
		$pro_text  = esc_html__('Go&nbsp;Pro', 'block-bad-queries');
		$pro_style = esc_attr('font-weight:bold;');
		
		$pro_link  = '<a target="_blank" rel="noopener noreferrer" href="'. $pro_url .'" title="'. $pro_title .'" style="'. $pro_style .'">'. $pro_text .'</a>';
		
		array_unshift($links, $pro_link);
		
	}
	
	return $links;
	
}
add_filter('plugin_action_links', 'bbq_action_links', 10, 2);

function bbq_meta_links($links, $file) {
	
	if ($file == BBQ_FILE) {
		
		$home_href  = 'https://perishablepress.com/block-bad-queries/';
		$home_title = esc_attr__('Plugin Homepage', 'block-bad-queries');
		$home_text  = esc_html__('Homepage', 'block-bad-queries');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		
		$rate_url   = esc_url('https://wordpress.org/support/plugin/block-bad-queries/reviews/?rate=5#new-post');
		$rate_title = esc_attr__('Click here to rate and review this plugin at WordPress.org', 'block-bad-queries');
		$rate_text  = esc_html__('Rate this plugin&nbsp;&raquo;', 'block-bad-queries');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_url .'" title="'. $rate_title .'">'. $rate_text .'</a>';
		
	}
	
	return $links;
	
}
add_filter('plugin_row_meta', 'bbq_meta_links', 10, 2);

function bbq_menu_page() {
	
	$title = esc_html__('BBQ Firewall', 'block-bad-queries');
	
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page($title, $title, 'manage_options', 'bbq_settings', 'bbq_display_settings');
	
}
add_action('admin_menu', 'bbq_menu_page');

function bbq_display_settings() { ?>
	
	<div class="wrap">
		<h1><?php esc_html_e('BBQ Firewall', 'block-bad-queries'); ?></h1>
		<form method="post" action="options.php">
			<?php 
				settings_fields('bbq_options_free');
				do_settings_sections('bbq_options_free');
				// submit_button();
			?>
		</form>
	</div>
	
<?php }

function bbq_enqueue_resources_admin() {
	
	$screen = get_current_screen();
	
	if ($screen->id === 'settings_page_bbq_settings') {
		
		// wp_enqueue_style ( $handle, $src, $deps, $ver, $media )
		wp_enqueue_style('bbq_admin', BBQ_URL .'assets/admin-styles.css');
		
	}
	
}
add_action('admin_enqueue_scripts', 'bbq_enqueue_resources_admin');
