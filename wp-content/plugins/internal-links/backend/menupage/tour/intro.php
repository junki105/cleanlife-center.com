<?php
namespace ILJ\Backend\MenuPage\Tour;

use ILJ\Backend\MenuPage\Tour\Step;

/**
 * Step: Intro
 *
 * Gives a brief description of the tour
 *
 * @package ILJ\Backend\Tour
 * @since   1.1.0
 */
class Intro extends Step
{
    /**
     * @inheritdoc
     */
    public function renderContent()
    {
        echo '<div class="intro">';
        echo '<div class="banner">';
        echo '<img src="' . ILJ_URL . '/admin/img/character-onboarding.png" />';
        echo '</div>';
        echo '<div class="content">';
        echo '<h2>' . __('Start the tour through the plugin', 'internal-links') . '</h2>';
        echo '<p>' . __('We show you the most important functions of the Internal Link Juicer in a few minutes. With that you are able to start immediately and get the maximum out of your internal links.', 'internal-links') . '</p>';
        echo '</div>';
        echo '</div>';
    }
}
