<?php
require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/classes/Pro.php');
publishpress_caps_pro();

add_action('init', function(){
    if (!empty($_REQUEST['publishpress_caps_ajax_settings'])) {
        include_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/pro-activation-ajax.php');
    }
});

if (class_exists('BuddyPress')) {
	add_filter(
		'bp_user_can_create_groups', 
		function($can_create, $restricted) {
			return ($restricted) ? current_user_can('bp_create_groups') : $can_create;
		}, 10, 2
	);
}

function publishpress_caps_pro() {
    return \PublishPress\Capabilities\Pro::instance();
}
