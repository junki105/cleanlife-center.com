<?php
namespace ILJ\Backend\MenuPage\Includes;

use ILJ\Backend\User;
use ILJ\Backend\AdminMenu;
use ILJ\Backend\Menupage\Settings;

/**
 * Backend Sidebar
 *
 * Responsible for displaying the sidebar
 *
 * @package ILJ\Backend\Menupage
 * @since   1.0.1
 */
trait Sidebar
{
    /**
     * Renders the sidebar
     *
     * @since  1.0.1
     * @return void
     */
    protected function renderSidebar()
    {
        $current_page = isset($_GET['page']) ? $_GET['page'] : '';

        if (\ILJ\ilj_fs()->is_free_plan() && !User::get('hide_promo') && $current_page != AdminMenu::ILJ_MENUPAGE_SLUG . '-' . Settings::ILJ_MENUPAGE_SETTINGS_SLUG) {
            $this->renderPromo();
        }

        echo '<div class="postbox ilj-postbox info">';
        echo '<h3 class="title">' . __('Support us', 'internal-links') . '</h3>';
        echo '<div class="inside">';
        echo '<p>';
        echo __('Do you like the plugin? Then <strong>please rate us</strong> or <strong>tell your friends</strong> about the Internal Link Juicer.', 'internal-links');
        echo '</p>';
        echo '<p><a href="https://wordpress.org/support/plugin/internal-links/reviews/" class="button button-primary" target="_blank" rel="noopener">&raquo; ' . __('Give us your review', 'internal-links') . '</a></p>';
        echo '<p class="divide">';
        echo __('Are you looking for a <strong>new feature</strong> or have <strong>suggestions for improvement</strong>? Have you <strong>found a bug</strong>? Please tell us about.', 'internal-links');
        echo  '</p>';
        echo '<p><a href="' . get_admin_url(null, 'admin.php?page=' . AdminMenu::ILJ_MENUPAGE_SLUG . '-contact') . '" class="button">&raquo; ' . __('Get in touch with us', 'internal-links') . '</a></p>';
        echo '<p class="divide"><strong>' . __('Thank you for using the Internal Link Juicer!', 'internal-links') . '</strong></p>';
        echo '<p class="character"><img src="' . ILJ_URL . '/admin/img/character.png" alt="The Internal Link Juicer" /></p>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Renders informational promotion for pro version of ILJ
     *
     * @since  1.1.1
     * @return void
     */
    protected function renderPromo()
    {
        echo '<div class="postbox ilj-postbox promo">';
        echo '<button type="button" class="handlediv" aria-expanded="true"><span class="close" title="' . __('Hide this information forever', 'internal-links') . '" aria-hidden="true"></span></button>';
        echo '<h3 class="title">' . __('Activate pro version', 'internal-links') . '</h3>';
        echo '<div class="inside">';
        echo '<h4>' . __('Achieve even more with Internal Link Juicer Pro:', 'internal-links') . '</h4>';
        echo '<ul>';
        echo '<li><span>' . __('Individual Links', 'internal-links') . '</span>: ' . __('More flexible linking with your own <strong>individual URLs</strong> as link targets.', 'internal-links') . '</li>';
        echo '<li><span>' . __('Maximum control', 'internal-links') . '</span>: ' . __('<strong>Shortcode</strong> and <strong>settings</strong> to <strong>exclude</strong> specific areas of content <strong>from link creation</strong>.', 'internal-links') . '</li>';
        echo '<li><span>' . __('Focused optimization', 'internal-links') . '</span>: ' . __('Maximize your results with the comprehensive <strong>Statistics Dashboard</strong>.', 'internal-links') . '</li>';
        echo '<li><span>' . __('Activate taxonomies', 'internal-links') . '</span>: ' . __('Unlimited possibilities by linking even from and to <strong>categories and tags</strong>.', 'internal-links') . '</li>';
        echo '</ul>';
        echo '<p><a href="' . get_admin_url(null, 'admin.php?page=' . AdminMenu::ILJ_MENUPAGE_SLUG . '-pricing') . '" class="button button-primary">&raquo; ' . __('Upgrade Now!', 'internal-links') . '</a></p>';
        echo '</div>';
        echo '</div>';
    }
}
