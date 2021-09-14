<?php
namespace ILJ\Backend\MenuPage\Includes;

/**
 * Backend Headline
 *
 * Responsible for displaying the headline on backend pages
 *
 * @package ILJ\Backend\Menupage
 * @since   1.1.0
 */
trait Headline
{
    /**
     * Renders the headline
     *
     * @since  1.1.0
     * @param  string $page_title The title for the headline
     * @return void
     */
    protected function renderHeadline($page_title)
    {
        echo '<hr class="wp-header-end" />';
        $this->renderPostbox(
            [
            'class' => 'admin-headline',
            'title' => 'Internal Link Juicer',
            'title_span' => 'vendor',
            'content' => sprintf("<h1>%s</h1>", $page_title),
            'help' => ''
            ]
        );
    }
}
