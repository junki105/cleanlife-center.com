<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_IDs
 * @subpackage Catch_IDs/admin/partials
 */

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Catch IDs', 'catch-ids' ); ?></h1>
    <div id="plugin-description">
        <p><?php esc_html_e( 'Catch IDs is a simple and light weight plugin to show the Post ID, Page ID, Media ID, Links ID, Category ID, Tag ID and User ID in the Admin Section Table.', 'catch-ids' ); ?></p>
    </div>
    <div class="catchp-content-wrapper">
        <div class="catchp_widget_settings">

            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active" id="dashboard-tab" href="#dashboard"><?php esc_html_e( 'Dashboard', 'catch-ids' ); ?></a>
                <a class="nav-tab" id="features-tab" href="#features">Features</a>
            </h2>

            <div id="dashboard" class="wpcatchtab  nosave active">

                <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/display-dashboard.php'; ?>

                <div id="ctp-switch" class="content-wrapper col-3 catchids-main">
                    <div class="header">
                        <h2><?php esc_html_e( 'Catch Themes & Catch Plugins Tabs', 'catch-ids' ); ?></h2>
                    </div> <!-- .Header -->

                    <div class="content">

                        <p><?php echo esc_html__( 'If you want to turn off Catch Themes & Catch Plugins tabs option in Add Themes and Add Plugins page, please uncheck the following option.', 'catch-ids' ); ?>
                        </p>
                        <table>
                            <tr>
                                <td>
                                    <?php echo esc_html__( 'Turn On Catch Themes & Catch Plugin tabs', 'catch-ids' );  ?>
                                </td>
                                <td>
                                    <div class="module-header <?php echo $options['theme_plugin_tabs'] ? 'active' : 'inactive'; ?>">
                                        <div class="switch">
                                            <input type="checkbox" id="catchids_options[theme_plugin_tabs]" class="ctp-switch" rel="theme_plugin_tabs" <?php checked( true, $options['theme_plugin_tabs'] ); ?> >
                                            <label for="catchids_options[theme_plugin_tabs]"></label>
                                        </div>
                                        <div class="loader"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                    </div>
                </div><!-- .content-wrapper -->

            </div><!-- .dashboard -->

            <div id="features" class="wpcatchtab save">
                <div class="content-wrapper col-3">
                    <div class="header">
                        <h3><?php esc_html_e( 'Features', 'catch-ids' ); ?></h3>

                    </div><!-- .header -->
                    <div class="content">
                        <ul class="catchp-lists">
                            <li>
                                <strong><?php esc_html_e( 'Supports all themes on WordPress', 'catch-ids' ); ?></strong>
                                <p><?php esc_html_e( 'You don’t have to worry if you have a slightly different or complicated theme installed on your website. It supports all the themes on WordPress and makes your website more striking and playful.', 'catch-ids' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Lightweight', 'catch-ids' ); ?></strong>
                                <p><?php esc_html_e( 'It is extremely lightweight. You do not need to worry about it affecting the space and speed of your website.', 'catch-ids' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Responsive Design', 'catch-ids' ); ?></strong>
                                <p><?php esc_html_e( 'One of the key features of our plugins is that your website will magically respond and adapt to different screen sizes delivering an optimized design for iPhones, iPads, and other mobile devices. No longer will you need to zoom and scroll around when browsing on your mobile phone.', 'catch-ids' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Incredible Support', 'catch-ids' ); ?></strong>
                                <p><?php esc_html_e( 'We have a great line of support team and support documentation. You do not need to worry about how to use the plugins we provide, just refer to our Tech Support Forum. Further, if you need to do advanced customization to your website, you can always hire our theme customizer!', 'catch-ids' ); ?></p>
                            </li>
                        </ul>
                    </div><!-- .content -->
                </div><!-- content-wrapper -->
            </div> <!-- Featured -->

        </div><!-- .catchp_widget_settings -->


        <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/sidebar.php'; ?>
    </div> <!-- .catchp-content-wrapper -->

    <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/footer.php'; ?>
</div><!-- .wrap -->
