<?php

namespace ILJ\Backend\MenuPage;

use  ILJ\Backend\MenuPage\Includes\Postbox ;
use  ILJ\Core\Options ;
use  ILJ\Enumeration\IndexMode ;
use  ILJ\Helper\Help ;
use  ILJ\Helper\Statistic ;
use  ILJ\Backend\AdminMenu ;
use  ILJ\Helper\IndexAsset ;
use  ILJ\Backend\Environment ;
use  ILJ\Backend\MenuPage\Includes\Sidebar ;
use  ILJ\Backend\MenuPage\Includes\Headline ;
use  RankMath\Helper ;
/**
 * The dashboard page
 *
 * Responsible for displaying the dashboard
 *
 * @package ILJ\Backend\Menupage
 * @since   1.0.0
 */
class Dashboard extends AbstractMenuPage
{
    const  ILJ_STATISTIC_HANDLE = 'ilj_statistic_pro' ;
    public function __construct()
    {
        $this->page_title = __( 'Dashboard', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->addSubMenuPage( true );
        $assets_css = [
            'tipso'             => ILJ_URL . 'admin/js/tipso.js',
            'jquery.dataTables' => ILJ_URL . 'admin/js/jquery.dataTables.min.js',
        ];
        if ( !\ILJ\ilj_fs()->can_use_premium_code() ) {
            $assets_css['ilj_promo'] = ILJ_URL . 'admin/js/ilj_promo.js';
        }
        $this->addAssets( $assets_css, [
            'tipso'                    => ILJ_URL . 'admin/css/tipso.css',
            'ilj_ui'                   => ILJ_URL . 'admin/css/ilj_ui.css',
            'ilj_grid'                 => ILJ_URL . 'admin/css/ilj_grid.css',
            'jquery.dataTables'        => ILJ_URL . 'admin/css/jquery.dataTables.min.css',
            self::ILJ_STATISTIC_HANDLE => ILJ_URL . 'admin/css/ilj_statistic.css',
        ] );
        add_action( 'admin_enqueue_scripts', function ( $hook ) {
            $screen = get_current_screen();
            
            if ( $screen->base === $this->page_hook ) {
                wp_register_script(
                    self::ILJ_STATISTIC_HANDLE,
                    ILJ_URL . 'admin/js/ilj_statistic.js',
                    [],
                    ILJ_VERSION
                );
                wp_localize_script( self::ILJ_STATISTIC_HANDLE, 'ilj_statistic_translation', Dashboard::getTranslation() );
                wp_enqueue_script( self::ILJ_STATISTIC_HANDLE );
            }
        
        } );
    }
    
    /**
     * @inheritdoc
     */
    public function render()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
        echo  '<div class="wrap ilj-menu-dashboard">' ;
        $this->renderHeadline( __( 'Dashboard', 'internal-links' ) );
        echo  '<div class="ilj-row">' ;
        echo  '<div class="col-9">' ;
        $related = '<p><strong>' . __( 'Installed version', 'internal-links' ) . ':</strong> ' . $this->getVersion() . '</p>';
        $related .= $this->getHelpRessources();
        $this->renderPostbox( [
            'title'   => __( 'Plugin related', 'internal-links' ),
            'content' => $related,
        ] );
        $this->renderPostbox( [
            'title'   => __( 'Linkindex info', 'internal-links' ),
            'content' => $this->getIndexMeta(),
            'class'   => 'ilj-indexinfo',
        ] );
        $this->renderPostbox( [
            'title'   => __( 'Statistics', 'internal-links' ),
            'content' => $this->getStatistics(),
            'class'   => 'ilj-statistic-wrap',
            'help'    => Help::getLinkUrl(
            'statistics/',
            null,
            'statistics',
            'dashboard'
        ),
        ] );
        echo  '</div>' ;
        echo  '<div class="col-3">' ;
        $this->renderSidebar();
        echo  '</div>' ;
        echo  '</div>' ;
        echo  '</div>' ;
    }
    
    /**
     * Generates the help links
     *
     * @since  1.2.0
     * @return string
     */
    protected function getHelpRessources()
    {
        $output = '';
        $url_manual = Help::getLinkUrl(
            null,
            null,
            'docs',
            'dashboard'
        );
        $url_tour = add_query_arg( [
            'page' => AdminMenu::ILJ_MENUPAGE_SLUG . '-' . Tour::ILJ_MENUPAGE_TOUR_SLUG,
        ], admin_url( 'admin.php' ) );
        $url_plugins_forum = 'https://wordpress.org/support/plugin/internal-links/';
        $output .= '<ul class="ilj-ressources divide">';
        $output .= '<li>';
        $output .= '<span class="dashicons dashicons-book-alt"></span>';
        $output .= '<a href="' . $url_manual . '" target="_blank" rel="noopener"><strong>' . __( 'Docs & How To', 'internal-links' ) . '</strong><span>' . __( 'Learn how to use the plugin', 'internal-links' ) . '</span></a>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<span class="dashicons dashicons-welcome-learn-more"></span>';
        $output .= '<a href="' . $url_tour . '"><strong>' . __( 'Interactive Tour', 'internal-links' ) . '</strong><span>' . __( 'A quick guided tutorial', 'internal-links' ) . '</span></a>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<span class="dashicons dashicons-testimonial"></span>';
        $output .= '<a href="' . $url_plugins_forum . '" target="_blank" rel="noopener"><strong>' . __( 'Request support', 'internal-links' ) . '</strong><span>' . __( 'Get help through our forum', 'internal-links' ) . '</span></a>';
        $output .= '</li>';
        $output .= '</ul>';
        return $output;
    }
    
    /**
     * Generates the statistic section
     *
     * @since  1.2.0
     * @return string
     */
    public function getStatistics()
    {
        $output = '';
        $output .= '<div class="ilj-statistic-cover"><div class="spinner is-active"></div></div>';
        $output .= '<div class="ilj-row ilj-statistic">';
        $output .= '<div class="col-12 no-top-padding">';
        $output .= '<h2 class="nav-tab-wrapper">';
        $output .= '<a data-target="statistic-links" class="nav-tab nav-tab-active">' . __( 'Link statistics', 'internal-links' ) . '</a>';
        $output .= '<a data-target="statistic-anchors" class="nav-tab">' . __( 'Anchor text statistics', 'internal-links' ) . '</a>';
        $output .= '</h2>';
        $output .= '<div id="statistic-links" class="tab-content active">';
        $output .= '<div class="spinner is-active"></div>';
        $output .= '</div>';
        $output .= '<div id="statistic-anchors" class="tab-content">';
        $output .= '<div class="spinner is-active"></div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="ilj-row"></div>';
        $output .= '</div>';
        return $output;
    }
    
    /**
     * Generates all index related meta data
     *
     * @since  1.2.0
     * @return string
     */
    private function getIndexMeta()
    {
        $output = '';
        $linkindex_info = Environment::get( 'linkindex' );
        
        if ( $linkindex_info['last_update']['entries'] == "" ) {
            $help_url = Help::getLinkUrl(
                'editor/',
                null,
                'editor onboarding',
                'dashboard'
            );
            $output .= '<p>' . __( 'Index has no entries yet', 'internal-links' ) . '.</p>';
            $output .= '<p class="divide"><span class="dashicons dashicons-arrow-right-alt"></span> <strong>' . __( 'Start to set some keywords to your posts', 'internal-links' ) . ' - <a href="' . $help_url . '" target="_blank" rel="noopener">' . __( 'learn how it works', 'internal-links' ) . '</a></strong></p>';
            return $output;
        }
        
        $hours = (int) get_option( 'gmt_offset' );
        $minutes = ($hours - floor( $hours )) * 60;
        $date = $linkindex_info['last_update']['date']->setTimezone( new \DateTimeZone( sprintf( '%+03d:%02d', $hours, $minutes ) ) );
        $output .= '<ul>';
        $output .= '<li class="ilj-row"><div class="col-4"><strong>' . __( 'Amount of links in the index', 'internal-links' ) . '</strong>:</div><div class="col-6">' . number_format( $linkindex_info['last_update']['entries'] ) . '</div><div class="clear"></div></li>';
        $output .= '<li class="ilj-row"><div class="col-4"><strong>' . __( 'Amount of configured keywords', 'internal-links' ) . '</strong>:</div><div class="col-6">' . number_format( Statistic::getConfiguredKeywordsCount() ) . '</div><div class="clear"></div></li>';
        $output .= '<li class="ilj-row"><div class="col-4"><strong>' . __( 'Last built', 'internal-links' ) . '</strong>:</div><div class="col-6">' . $date->format( get_option( 'date_format' ) ) . ' ' . __( 'at', 'internal-links' ) . ' ' . $date->format( get_option( 'time_format' ) ) . '</div><div class="clear"></div></li>';
        $output .= '<li class="ilj-row"><div class="col-4"><strong>' . __( 'Duration for construction', 'internal-links' ) . '</strong>:</div><div class="col-6">' . $linkindex_info['last_update']['duration'] . ' ' . __( 'seconds', 'internal-links' ) . $this->getIndexModeHint( $linkindex_info['last_update']['duration'] ) . '</div><div class="clear"></div></li>';
        $output .= '</ul>';
        return $output;
    }
    
    /**
     * Returns a recommendation for changing the indexmode when building time takes to long
     *
     * @since  1.2.5
     * @param  float $building_duration The duration the index took to build itself
     * @return string
     */
    private function getIndexModeHint( $building_duration )
    {
        if ( !is_float( $building_duration ) ) {
            return '';
        }
        $threshold_automatic_mode = 3.5;
        //Threshold in seconds
        $threshold_manual_mode = 15.5;
        //Threshold in seconds
        $message = $class = '';
        $message_index_size_warning = __( 'The size of your link index is growing. This means that more time is needed to build the index.', 'internal-links' );
        $message_documentation_link = sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', Help::getLinkUrl(
            'index-generation-mode/',
            null,
            'index size warning',
            'dashboard'
        ), __( 'Read more in our documentation', 'internal-links' ) );
        $message_manual_mode = '<p>' . $message_index_size_warning . __( 'We recommend a switch to the manual index mode.', 'internal-links' ) . '</p><p>📘 ' . $message_documentation_link . '</p>';
        $message_cli_mode = '<p>' . $message_index_size_warning . __( 'We recommend a switch to the WP-CLI mode through a cronjob.', 'internal-links' ) . '</p><p>📘 ' . $message_documentation_link . '</p>';
        $current_index_mode = Options::getOption( Options\IndexGeneration::getKey() );
        
        if ( $current_index_mode == IndexMode::AUTOMATIC || !\ILJ\ilj_fs()->can_use_premium_code() ) {
            if ( $building_duration <= $threshold_automatic_mode ) {
                return '';
            }
            
            if ( $building_duration > $threshold_manual_mode ) {
                $message = $message_cli_mode;
                $class = 'danger';
            } else {
                $message = $message_manual_mode;
            }
            
            $message .= '<hr><p>' . __( 'The additional index modes are part of our Pro version', 'internal-links' ) . '</p><p>🚀 <a href="' . get_admin_url( null, 'admin.php?page=' . AdminMenu::ILJ_MENUPAGE_SLUG . '-pricing' ) . '">' . __( 'Upgrade Now!', 'internal-links' ) . '</a></p>';
        }
        
        if ( $message == '' ) {
            return '';
        }
        $hint = sprintf( '<span class="warning-tip dashicons dashicons-warning%s"><span class="the-tip">%s</span></span></a>', ( $class != '' ? ' ' . $class : '' ), $message );
        return $hint;
    }
    
    /**
     * Returns the version including the subscription type
     *
     * @since  1.1.0
     * @return string
     */
    protected function getVersion()
    {
        return ILJ_VERSION . ' <span class="badge basic">Basic</span>';
    }
    
    /**
     * Returns the frontend translation
     *
     * @since 1.2.5
     *
     * @return array
     */
    public static function getTranslation()
    {
        $translation = [
            'incoming_links'                 => __( 'Incoming links to', 'internal-links' ),
            'outgoing_links'                 => __( 'Outgoing links from', 'internal-links' ),
            'anchor_text'                    => __( 'Anchor text', 'internal-links' ),
            'datatables_aria_sortAscending'  => __( ': activate to sort column ascending', 'internal-links' ),
            'datatables_aria_sortDescending' => __( ': activate to sort column descending', 'internal-links' ),
            'datatables_paginate_first'      => __( 'First', 'internal-links' ),
            'datatables_paginate_last'       => __( 'Last', 'internal-links' ),
            'datatables_paginate_next'       => __( 'Next', 'internal-links' ),
            'datatables_paginate_previous'   => __( 'Previous', 'internal-links' ),
            'datatables_empty_table'         => __( 'No data available in table', 'internal-links' ),
            'datatables_info'                => __( 'Showing _START_ to _END_ of _TOTAL_ entries', 'internal-links' ),
            'datatables_info_empty'          => __( 'Showing 0 to 0 of 0 entries', 'internal-links' ),
            'datatables_info_filtered'       => __( '(filtered from _MAX_ total entries)', 'internal-links' ),
            'datatables_length_menu'         => __( 'Show _MENU_ entries', 'internal-links' ),
            'datatables_loading_records'     => __( 'Loading...', 'internal-links' ),
            'datatables_processing'          => __( 'Processing...', 'internal-links' ),
            'datatables_search'              => __( 'Search:', 'internal-links' ),
            'datatables_zero_records'        => __( 'No matching records found', 'internal-links' ),
            'filter_type'                    => __( 'Filter type', 'internal-links' ),
            'filter_section_posts_pages'     => __( 'Posts and Pages', 'internal-links' ),
            'filter_section_taxonomies'      => __( 'Taxonomies', 'internal-links' ),
            'filter_section_custom_links'    => __( 'Custom Links', 'internal-links' ),
        ];
        return $translation;
    }
    
    /**
     * Generates a list of post ids as post links
     *
     * @deprecated
     * @since      1.2.0
     * @param      array $data          Bag of objects
     * @param      int   $asset_id_node The name of the post id property in single object
     * @return     string
     */
    private function getLinkList( array $data, $asset_id_node )
    {
        $render_header = [ __( 'Page', 'internal-links' ), __( 'Count', 'internal-links' ), __( 'Action', 'internal-links' ) ];
        $render_data = [];
        if ( !isset( $data[0] ) || !property_exists( $data[0], $asset_id_node ) ) {
            return '';
        }
        foreach ( $data as $row ) {
            $asset_id = (int) $row->{$asset_id_node};
            if ( $asset_id < 1 || $row->type != 'post' ) {
                continue;
            }
            $asset_data = IndexAsset::getMeta( $asset_id, 'post' );
            if ( !$asset_data ) {
                continue;
            }
            $edit_link = sprintf( '<a href="%s" title="' . __( 'Edit', 'internal-links' ) . '" class="tip">%s</a>', $asset_data->url_edit, '<span class="dashicons dashicons-edit"></span>' );
            $post_link = sprintf( '<a href="%s" title="' . __( 'Open', 'internal-links' ) . '" class="tip" target="_blank" rel="noopener">%s</a>', $asset_data->url, '<span class="dashicons dashicons-external"></span>' );
            $render_data[] = [ $asset_data->title, $row->elements, $post_link . $edit_link ];
        }
        return $this->getList( $render_header, $render_data );
    }
    
    /**
     * Generates a list of keywords
     *
     * @deprecated
     * @since      1.2.0
     * @param      array  $data         Bag of objects
     * @param      string $keyword_node The name of the keyword property in single object
     * @return     string
     */
    private function getKeywordList( array $data, $keyword_node )
    {
        $render_header = [ __( 'Keyword', 'internal-links' ), __( 'Count', 'internal-links' ) ];
        $render_data = [];
        if ( !isset( $data[0] ) || !property_exists( $data[0], $keyword_node ) ) {
            return '';
        }
        foreach ( $data as $row ) {
            $keyword = $row->{$keyword_node};
            $render_data[] = [ $keyword, $row->elements ];
        }
        return $this->getList( $render_header, $render_data );
    }
    
    /**
     * Generic method for generating a list
     *
     * @deprecated
     * @since      1.2.0
     * @param      array $header
     * @param      array $data
     * @return     string
     */
    private function getList( array $header, array $data )
    {
        $output = '';
        $output .= '<table class="wp-list-table widefat striped ilj-statistic-table">';
        $output .= '<thead>';
        $output .= '<tr>';
        foreach ( $header as $title ) {
            $output .= '<th scope="col">' . $title . '</th>';
        }
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach ( $data as $row ) {
            $output .= '<tr>';
            foreach ( $row as $col ) {
                $output .= '<td>' . $col . '</td>';
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }

}