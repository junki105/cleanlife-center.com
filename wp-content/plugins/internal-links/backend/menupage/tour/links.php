<?php
namespace ILJ\Backend\MenuPage\Tour;

use ILJ\Backend\MenuPage\Tour\Step;

/**
 * Step: Links
 *
 * Shows the result and output of generated links
 *
 * @package ILJ\Backend\Tour
 * @since   1.1.0
 */
class Links extends Step
{
    /**
     * @inheritdoc
     */
    public function renderContent()
    {
        echo '<h1>' . __('Learn how to influence the linking behavior', 'internal-links') . '</h1>';

        $data_container = [
            [
                'title'       => __('Auto-generate link outputs', 'internal-links'),

                'description' =>
                '<p>' .
                __('As soon as you save keywords for your content, the internal links are activated.', 'internal-links') .
                '</p><p>' .
                __('The plugin works with its <strong>own index</strong> for internal links. It <strong>updates automatically</strong> after you edit content or keywords. This won’t affect your website’s <strong>quick loading times</strong>. This aspect makes the Internal Link Juicer <strong>distinct from other plugins</strong> with similar functionality.', 'internal-links') .
                '</p><p>' .
                __('The video shows an example of two different posts getting linked.', 'internal-links') .
                '</p>',

                'video'       => 'lVh1EzALiJs'
            ], [
                'title'       => __('Easily customize outputs using templates', 'internal-links'),

                'description' => '<p>' .
                __('With the help of the template feature, you can <strong>change and individualize the output</strong> of internal links.', 'internal-links') .
                '</p><p>' .
                __('You can find the link template settings within the plugin settings under the "Links" tab.', 'internal-links') .
                '</p><p>' .
                __('There you can update the output of a link using your own HTML code. The template tags <code>{{url}}</code> and <code>{{anchor}}</code> are available. In your content, these placeholders will later be replaced by the corresponding parameters (link target and anchor text).', 'internal-links') .
                '</p><p>' .
                __('The video shows you one example of how to mask the built links using JavaScript.', 'internal-links') .
                '</p>',

                'video'       => '-qoxE49bVBw'
            ], [
                'title'       => __('Individually control link frequencies according to your needs', 'internal-links'),
                'description' => '<p>' . __('With this feature, you can actively control link frequencies. You can limit the maximum number of links built, or allow multiple linking of the same destination URL from the same content.', 'internal-links') . '</p><p>' . __('These settings can be found in the Internal Link Juicer settings under the "Content" tab.', 'internal-links') . '</p><p>' . __('Overall, you have the following options available, which cover all use cases:', 'internal-links') . '</p><ul><li>' . __('Set the “<strong>maximum number of links per post</strong>” within a single content. With the value "0," you link all possible keywords.', 'internal-links') . '</li><li>' . __('Set the “<strong>maximum frequency per post</strong>” of how much a post can link to a single destination URL. Here, the value "0" also allows linking the maximum destination URLs, even if they are identical.', 'internal-links') . '</li><li>' . __('Check the “<strong>link as often as possible</strong>” box. If you activate this setting, there will be no consideration for the maximum number of links or maximum frequency of identical target URLs. Wherever a link is possible, this setting will create a link.', 'internal-links') . '</li></ul><p>' . __('Watch the video and you will see this feature demonstrated in depth.', 'internal-links') . '</p>',
                'video'       => 'rZfMjA8IhVg'
            ]
        ];

        foreach ($data_container as $data) {
            $this->renderFeatureRow($data);
        }
    }
}
