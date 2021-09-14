<?php
/*
 * No direct access to this file
 */
if ( ! isset( $data, $criticalCssConfig ) ) {
	exit;
}

$locationKey = 'custom_pages';
?>
<div class="wpacu-wrap <?php if ($data['wpacu_settings']['input_style'] !== 'standard') { ?>wpacu-switch-enhanced<?php } else { ?>wpacu-switch-standard<?php } ?>">
    <div class="wpacu-alert alert-warning"><p>At this time, if you want to implement critical CSS to a custom page, you need to use the "wpacu_critical_css" hook which requires editing functions.php from your theme (ideally the Child theme if you have one available) or create a custom plugin containing the code and activate it. <span class="dashicons dashicons-welcome-learn-more"></span> <a target="_blank" href="https://www.assetcleanup.com/docs/?p=608#wpacu-how-to-use-hook">Click here for a tutorial</a> explaining how to do the implementation.</p></div>
    <p>Here's an example of the hook in action! Here, critical CSS is loading if the visited page has the ID equal with 10. To find out the ID of the page for which you want to implement custom critical CSS, you can get the ID from the request URI (anytime you want to edit the post/page) which is something like: <code>/wp-admin/post.php?post=<strong>PAGE_ID_HERE</strong>&action=edit</code></p>

    <?php
    $exampleCustomPage = 'add_filter(\'wpacu_critical_css\', static function($args) {
    global $post;

    // Assuming the post ID is 10; If it\'s not, do not continue!
    // It could be a special landing page for which you have a Google AdWords campaign
    if ( ! (isset($post->ID) && $post->ID == 10) ) {
        return array();
    }

    $cssContent = \'/* CSS rules here */\';

    $args[\'content\'] = $cssContent;
    $args[\'minify\']  = false; // if possible, have it already minified to save resources

    return $args;
}, 11);';

    ?>
    <textarea readonly="readonly" name="<?php echo WPACU_PLUGIN_ID . '_critical_css'; ?>[content]" id="wpacu-css-editor-textarea"><?php echo $exampleCustomPage; ?></textarea>
</div>