<?php
/*
Plugin Name: Mouseflow for Wordpress
Plugin URI: http://mouseflow.com
Description: Integrate Mouseflow analytics on your website. Login to your <a href="admin.php?page=mouseflow-dashboard">Mouseflow dashboard</a>. Create a free account <a href="https://mouseflow.com/sign-up/" target="_blank">here</a>, and paste in your tracking code <a href="admin.php?page=mouseflow-for-wordpress">here</a>.
Author: Mouseflow
Version: 5.0.3
Author URI: http://mouseflow.com
*/

//defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$plugin = plugin_basename(__FILE__);

function mf_admin_scripts() {
  wp_enqueue_style('mouseflow_wp.css', plugins_url('mouseflow-for-wordpress/mouseflow_wp.css'));
}
add_action( 'admin_enqueue_scripts', 'mf_admin_scripts' );

function mouseflow_addOptions()
{
	add_option('mouseflow_script');
}
register_activation_hook(__FILE__, 'mouseflow_addOptions');


function mouseflow_settings_link($links) {
  $settings_link = '<a href="admin.php?page=mouseflow-dashboard">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
add_filter("plugin_action_links_$plugin", 'mouseflow_settings_link' );

function mouseflow_menu() {
  add_menu_page('Mouseflow', 'Mouseflow', 'administrator', 'mouseflow-dashboard', 'mouseflow_options3', plugins_url('mouseflow-for-wordpress/mf_logo_wp.png'));
  add_submenu_page('mouseflow-dashboard', 'Dashboard', 'Dashboard', 'administrator', 'mouseflow-dashboard', 'mouseflow_options3');
  add_submenu_page('mouseflow-dashboard', 'Tracking code', 'Tracking code', 'administrator', 'mouseflow-for-wordpress', 'mouseflow_options');
}
add_action('admin_menu', 'mouseflow_menu');

function mouseflow_options3(){
$output = '';
$output .='<table class="mf-intro">
	<tr>
		<td class="top" colspan="2"><img src="' .plugins_url('mouseflow-for-wordpress/mouseflow_logo.png', dirname(__FILE__) ). '" >

	</tr>
	<tr>
		<td class="top2" colspan="2">
		<h2>Welcome to the Mouseflow dashboard</h2>
		Here you can access everything Mouseflow has to offer - directly from your Wordpress dashboard! Learn more about your visitors by viewing recordings of whole user sessions including mouse movements, clicks, scroll events and key strokes.';

if(get_option('mouseflow_script') == ''){
	$output .='<h3>Get started</h3>
	In order to get started with the Mouseflow Wordpress-plugin you need to register an account on <a href="https://mouseflow.com/sign-up/" target="_blank" title="Mouseflow - live click tracking and websites analytics">mouseflow.com</a> (don&#39;t worry, it&#39;s free of charge). Once you have an account you need to install the <div title="To find your tracking code you must log in to Mouseflow.com and go to your list of websites. Click &#39;edit&#39; at the appropriate site and copy the code from the box in the bottom left corner." class="hover">Mouseflow tracking code</div> in Wordpress - simply go to your account and find your tracking code. Copy this into the box here: <a href="' .get_option('mouseflow-dashboard', 'admin.php?page=mouseflow-for-wordpress'). '">Insert tracking code</a>.';
}
$output .='</td></tr></table>
<table class="mf-square">
	<tr>
		<td class="link"><a href="https://app.mouseflow.com/sign-in" target="_blank">
			<table class="mf-intro2">
				<tr>
					<td><img style="vertical-align:middle" src="' .plugins_url('mouseflow-for-wordpress/account.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Your Mouseflow-account</h3>
Here you can access your Mouseflow-account and get direct access to recordings, heatmaps, analytics and much more. If you don&#39;t already have an account you can create one here as well.
					</td>
				</tr>
			</table>
		</a>
		</td>

		<td class="link"><a href="' .get_option('mouseflow-dashboard', 'admin.php?page=mouseflow-for-wordpress'). '">
			<table class="mf-intro2">
				<tr>';


if(get_option('mouseflow_script') == ''){
	$output .='<td><img src="' .plugins_url('mouseflow-for-wordpress/gear_bad.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Insert tracking code</h3>
		In order to start recording you need to input your <div title="To find your tracking code you must log in to Mouseflow.com and go to your list of websites. Click &#39;edit&#39; at the appropriate site and copy the code from the box in the bottom left corner." class="hover">tracking code</div> here, and it will automatically be inserted into every page of your Wordpress-site - it&#39;s really as easy as that.
					</td>';
}else{
	$output .='<td><img src="' .plugins_url('mouseflow-for-wordpress/gear_good.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Tracking code installed</h3>
		Your tracking code is now installed. If you want to view, change or uninstall the code, click here. The installation is not complete until the first visitor is recorded on your site.
					</td>';
}

$output .='</tr>
			</table>
		</a></td>

	</tr>
	<tr>
		<td class="link"><a href="https://mouseflow.groovehq.com/help_center" target="_blank">
			<table class="mf-intro2">
				<tr>
					<td><img src="' .plugins_url('mouseflow-for-wordpress/support.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Troubleshooting</h3>
		If you have questions or problems using Mouseflow, chances are you will find the answers in our extensive FAQs, walkthroughs, screencasts and other helpful information.
					</td>
				</tr>
			</table>
		</a></td>
		<td class="link"><a href="http://mouseflow.uservoice.com" target="_blank">
			<table class="mf-intro2">
				<tr>
					<td><img src="' .plugins_url('mouseflow-for-wordpress/uservoice.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>What should we do next?</h3>
		We know we can always get better - but how? If you are missing a feature or have a suggestion that could improve our service, then please share your thoughts with us here.
					</td>
				</tr>
			</table>
		</a></td>
	</tr>
</table>
';
echo $output;
}

function mouseflow_options2() {
	$output ='';
	$output .='
	<table class="mf-intro">
		<tr><td class="top2" colspan="2"><h2>Welcome to the Mouseflow dashboard</h2></td></tr>
	</table>';
	$output .= '<iframe src="https://app.mouseflow.com/sign-in" width="1100px" height="1800px"><br></iframe>';
	echo $output;
}

function mouseflow_options() {
	$output ='';
	$output .='
	<table class="mf-intro">
		<tr>
			<td class="top" colspan="2"><img src="' .plugins_url('mouseflow-for-wordpress/mouseflow_logo.png', dirname(__FILE__) ). '" ></td>
		</tr>
		<tr>
			<td class="top2" colspan="2"><h2>Mouseflow tracking code</h2>
	<table class="install"><tr>';

	if(get_option('mouseflow_script') == ''){
		$output .='<td><img src="' .plugins_url('mouseflow-for-wordpress/gear_bad.png', dirname(__FILE__) ). '" ></td>
					<td><h3>Tracking code not installed</h3>
			Your Mouseflow tracking code is not yet installed. You can <a href="https://app.mouseflow.com/sign-in" target="_blank">find the tracking code on your Mouseflow-account</a>. If you don\'t yet have an account, you can easily <a href="https://mouseflow.com/sign-up/" target="_blank">create a Mouseflow-account for free</a>.</td>';
	}else{
		$output .= '<td><img src="'.plugins_url('mouseflow-for-wordpress/gear_good.png', dirname(__FILE__) ). '" ></td><td><h3>Tracking code is installed</h3></td>';
	}
	$output .= '</tr></table>

	<form method="post" action="options.php">'.wp_nonce_field('update-options');

	if(get_option('mouseflow_script') == ''){}else{
		$output .= '<h3>Your current Mouseflow tracking code:</h3><div class="code">';
		$output .= str_replace(">", "&gt;",str_replace("<", "&lt;", get_option('mouseflow_script')));
		$output .= '</div>';
	}
	$output .='
	<h3>Insert tracking code (save empty field to delete)</h3>
	<textarea name="mouseflow_script" style="width:800px;height:200px;" placeholder="<script type=\'text/javascript\'>
window._mfq = window._mfq || [];
(function() {
var mf = document.createElement(\'script\');
mf.type = \'text/javascript\'; mf.async = true;
mf.src = \'//cdn.mouseflow.com/projects/f2a6083d-2ff6-4fcc-a93f-418acf05a709.js\';
document.getElementsByTagName(\'head\')[0].appendChild(mf);
})();
</script>"></textarea>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="mouseflow_script" />
	<p class="submit"><input type="submit" name="update_message" value="'.__("Save Changes").'" /></p></form></td></tr></table>';

	echo $output;
}



function add_mouseflow_script()
{
	if(!is_admin()){
		echo get_option('mouseflow_script');
	}
}
add_action('wp_footer', 'add_mouseflow_script');

?>
