<?php
namespace ILJ\Backend\MenuPage\Includes;

/**
 * Backend Postbox
 *
 * Responsible for activating and generating individual postboxes
 *
 * @package ILJ\Backend\Menupage
 * @since   1.2.0
 */
trait Postbox
{
    /**
     * Renders the postbox
     *
     * @since  1.2.0
     * @param  array $args The arguments for the rendering
     * @return void
     */
    protected function renderPostbox($args)
    {
        $defaults = [
        'class' => '',
        'title' => '',
        'title_span' => '',
        'content' => '',
        'help' => '',
        'before_headline' => ''
        ];

        $args = wp_parse_args($args, $defaults);

        printf('<div class="postbox ilj-postbox%s">', $args['class'] != '' ? ' ' . $args['class'] : '');

        if ($args['help'] != '') {
            $help_link = sprintf('<a class="tip" href="%s" target="_blank" rel="noopener" title="%s"><span class="dashicons dashicons-editor-help"></span></a>', $args['help'],  __('Get help', 'internal-links'));
        }

        $title = esc_html($args['title']);

        if ($args['title_span'] != "") {
            $title = sprintf('<span class="%s">%s</span>', esc_html($args['title_span']), $title);
        }

        printf("%s<h2>%s%s</h2>", $args['before_headline'], $title, isset($help_link) ? $help_link : '');
        echo '      <div class="inside">';
        echo $args['content'];
        echo '      </div>';
        echo '  </div>';
    }
}
