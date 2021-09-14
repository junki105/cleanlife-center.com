<?php
namespace ILJ\Backend\MenuPage\Tour;

use ILJ\Backend\MenuPage\Tour\Step;

/**
 * Step: Settings
 *
 * Gives a short introduction to possible settings
 *
 * @package ILJ\Backend\Tour
 * @since   1.1.0
 */
class Settings extends Step
{
    /**
     * @inheritdoc
     */
    public function renderContent()
    {
        echo '<h1>' . __('Brief introduction to the most important settings', 'internal-links') . '</h1>';

        $data_container = [
            [
                'title'       => __('Blacklist: Exclude any content from linking', 'internal-links'),

                'description' => '<p>' .
                __('To <strong>prevent unwanted linking</strong>, you can take advantage of the blacklist feature. This feature excludes specific content from link building and gives you full control over link behavior at all times.', 'internal-links') .
                '</p><p>' .
                __('You can find the blacklist in the Internal Link Juicer settings under the "Content" tab.', 'internal-links') .
                '</p><p>' .
                __('The input field works like a keyword search. To <strong>exclude one or more posts from linking</strong>, follow this procedure:', 'internal-links') .
                '</p><ol><li>' .
                __('Find the desired post (or page).', 'internal-links') .
                '</li><li>' .
                __('Add it by clicking on the blacklist.', 'internal-links') .
                '</li><li>' .
                __('Save', 'internal-links') .
                '</li></ol><p>' .
                __('As a result, this article will no longer display auto-generated internal links.', 'internal-links') .
                '</p><p>' .
                __('In the video, you can see how the blacklist is configured and what effects this setting has on the link.', 'internal-links') .
                '</p>',

                'video'       => 'QR92w3wpbN4'
            ], [
                'title'       => __('Whitelist: Define content types that should always be linked', 'internal-links'),

                'description' => '<p>' .
                __('With the whitelist, you can <strong>include post and page types</strong> that you always want <strong>to be linked</strong>. This allows you to exclude complete post types from linking beyond the blacklist.', 'internal-links') .
                '</p><p>' .
                __('You can find the whitelist in the Internal Link Juicer settings under the “Content” tab. The whitelist setting is right under the blacklist setting.', 'internal-links') .
                '</p><p>' .
                __('The input field for the whitelist opens a list with all available post and page types. Select all types that you want to allow linking for. After saving, the setting will be active.', 'internal-links') .
                '</p><p>' .
                __('In the video, you can see a model configuration of the whitelist and its effect on linking behavior.', 'internal-links') .
                '</p>',

                'video'       => 'cVgFPXW9WjU'
            ], [
                'title'       => __('Determine the order of configured keywords for linking', 'internal-links'),
                'description' => '<p>' .
                __('Configure the order in which you want to use your configured keywords for linking. This gives you even more influence on <strong>whether longer or shorter phrases</strong> are linked.', 'internal-links') .
                '</p><p>' .
                __('You can find the order settings in the Internal Link Juicer under the "Content" tab, just below the whitelist.', 'internal-links') .
                '</p><p>' .
                __('There are a total of 3 different settings available:', 'internal-links') .
                '</p><ol><li>' .
                __('<strong>First configured keyword links first</strong>: Keywords are used in the order you entered them into the Keyword Editor.', 'internal-links') .
                '</li><li>' .
                __('<strong>Highest word count first</strong>: Phrases with the highest word count are preferred for linking.', 'internal-links') .
                '</li><li>' .
                __('<strong>Lowest word count first</strong>: Phrases with the lowest word count are preferred for linking.', 'internal-links') .
                '</li></ol><p>' .
                __('In the video, you can see how the individual settings impact linking behavior.', 'internal-links') .
                '</p>',

                'video'       => 'UVk0XaovXDE'
            ]
        ];

        foreach ($data_container as $data) {
            $this->renderFeatureRow($data);
        }
    }
}
