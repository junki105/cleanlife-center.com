<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$baseNamePageType = str_replace('.php', '', basename(__FILE__));
$baseNamePageType = trim($baseNamePageType, '_');

$lockedForPro = str_replace('[wpacu_chosen_page_type]', $baseNamePageType, $data['locked_for_pro']);
?>
<div style="margin: 25px 0 0;">
    <p><?php echo $lockedForPro; ?></p>
    <hr />

    <p>Default Taxonomy (they are found in "Posts" &#187; "Categories", accessing a category link reveals all the posts from that category) &#10230; <a target="_blank" href="https://wordpress.org/support/article/posts-categories-screen/"><?php _e('read more', 'wp-asset-clean-up'); ?></a></p>

    <strong>How to retrieve the loaded styles &amp; scripts?</strong>

    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes" style="color: green;"></span> If "Manage in the Dashboard?" is enabled:</p>
    <p style="margin-top: 0;">Go to <a target="_blank" href="<?php echo admin_url('edit-tags.php?taxonomy=category'); ?>">"Posts" &#187; "Categories"</a> &#187; [Choose the category you want to manage the assets for and click on its name] -&gt; Scroll to "<?php echo WPACU_PLUGIN_TITLE; ?>" area where you will see the loaded CSS &amp; JavaScript files.</p>
    <hr />
    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes" style="color: green;"></span> If "Manage in the Front-end?" is enabled and you're logged in:</p>
    <p style="margin-top: 0;">Go to the category's page permalink ("View" link under its name in the Dashboard list) such as <code>/www.yoursite.com/category/my-category-title/</code> where you want to manage the files and scroll to the bottom of the page where you will see the list.</p>
</div>
