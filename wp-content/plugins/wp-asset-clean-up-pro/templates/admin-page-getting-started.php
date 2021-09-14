<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
    exit;
}
?>
<div class="wpacu-wrap">
    <div class="about-wrap wpacu-about-wrap">
        <h1>Welcome to <?php echo WPACU_PLUGIN_TITLE; ?> <?php echo WPACU_PRO_PLUGIN_VERSION; ?></h1>
        <p class="about-text wpacu-about-text">
            Thank you for installing this premium page speed booster plugin! Prepare to make your WordPress website faster &amp; lighter by removing the useless CSS &amp; JavaScript files from your pages. For maximum performance, <?php echo WPACU_PLUGIN_TITLE; ?> works best when used with either a caching plugin, an in-built hosting caching (e.g. <a style="text-decoration: none; color: #555d66;" href="https://www.gabelivan.com/visit/wp-engine">WPEngine</a>, Kinsta have it) or something like Varnish.
            <img src="<?php echo WPACU_PLUGIN_URL; ?>/assets/images/wpacu-logo-transparent-bg-v1.png" alt="" />
        </p>

        <h2 class="nav-tab-wrapper wp-clearfix">
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=how-it-works'); ?>" class="nav-tab <?php if ($data['for'] === 'how-it-works') { ?>nav-tab-active<?php } ?>">How it works</a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=benefits-fast-pages'); ?>" class="nav-tab <?php if ($data['for'] === 'benefits-fast-pages') { ?>nav-tab-active<?php } ?>">Benefits of a Fast Website</a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=start-optimization'); ?>" class="nav-tab <?php if ($data['for'] === 'start-optimization') { ?>nav-tab-active<?php } ?>">Start Optimization</a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=video-tutorials'); ?>" class="nav-tab <?php if ($data['for'] === 'video-tutorials') { ?>nav-tab-active<?php } ?>"><span class="dashicons dashicons-video-alt3" style="color: #ff0000;"></span> Video Tutorials</a>
        </h2>

        <div class="about-wrap-content">
	        <?php
	        if ($data['for'] === 'how-it-works') {
		        include_once '_admin-page-getting-started-areas/_how-it-works.php';
	        } elseif ($data['for'] === 'benefits-fast-pages') {
		        include_once '_admin-page-getting-started-areas/_benefits-fast-pages.php';
	        } elseif ($data['for'] === 'start-optimization') {
		        include_once '_admin-page-getting-started-areas/_start-optimization.php';
	        } elseif ($data['for'] === 'video-tutorials') {
		        include_once '_admin-page-getting-started-areas/_video-tutorials.php';
	        }
            ?>
        </div>
    </div>
</div>