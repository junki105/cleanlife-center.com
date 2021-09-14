<?php

namespace ILJ\Helper;

use  ILJ\Backend\AdminMenu ;
use  ILJ\Backend\Environment ;
use  ILJ\Backend\MenuPage\Tools ;
use  ILJ\Backend\User ;
use  ILJ\Core\IndexBuilder ;
use  ILJ\Core\Options as CoreOptions ;
use  ILJ\Database\Postmeta ;
use  ILJ\Helper\IndexAsset ;
use  ILJ\Database\Linkindex ;
use  ILJ\Backend\IndexRebuildNotifier ;
use  ILJ\Type\KeywordList ;
/**
 * Ajax toolset
 *
 * Methods for handling AJAX requests
 *
 * @package ILJ\Helper
 *
 * @since 1.0.0
 */
class Ajax
{
    const  ILJ_FILTER_AJAX_SEARCH_POSTS = 'ilj_ajax_search_posts' ;
    const  ILJ_FILTER_AJAX_SEARCH_TERMS = 'ilj_ajax_search_terms' ;
    /**
     * Searches the posts for a given phrase
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function searchPostsAction()
    {
        if ( !isset( $_POST['search'] ) && !isset( $_POST['per_page'] ) && !isset( $_POST['page'] ) ) {
            wp_die();
        }
        $search = sanitize_text_field( $_POST['search'] );
        $per_page = (int) $_POST['per_page'];
        $page = (int) $_POST['page'];
        $args = [
            "s"              => $search,
            "posts_per_page" => $per_page,
            "paged"          => $page,
        ];
        $query = new \WP_Query( $args );
        $data = [];
        foreach ( $query->posts as $post ) {
            $data[] = [
                "id"   => $post->ID,
                "text" => $post->post_title,
            ];
        }
        /**
         * Filters the output of ajax post search
         *
         * @since 1.1.6
         *
         * @param object $data The return data (found posts)
         * @param array  $args The arguments for the post query
         */
        $data = apply_filters( self::ILJ_FILTER_AJAX_SEARCH_POSTS, $data, $args );
        wp_send_json( $data );
        wp_die();
    }
    
    /**
     * Renders the statistics for the links
     *
     * @since 1.2.5
     *
     * @return void
     */
    public static function renderLinksStatisticAction()
    {
        $statistics = Statistic::getLinkStatistics();
        
        if ( !count( $statistics ) ) {
            echo  '<span>' . __( 'There are no statistics to display', 'internal-links' ) . '</span>' ;
            wp_die();
        }
        
        echo  '<table class="ilj-statistic-table-links display">' ;
        echo  '<thead><tr><th>' . __( 'Title', 'internal-links' ) . '</th><th>' . __( 'Configured keywords', 'internal-links' ) . '</th><th class="type">' . __( 'Type', 'internal-links' ) . '</th><th>' . __( 'Incoming links', 'internal-links' ) . '</th><th>' . __( 'Outgoing links', 'internal-links' ) . '</th><th>' . __( 'Action', 'internal-links' ) . '</th></tr></thead>' ;
        echo  '<tbody>' ;
        foreach ( $statistics as $statistic ) {
            $asset_data = IndexAsset::getMeta( $statistic->asset_id, $statistic->asset_type );
            if ( !$asset_data ) {
                continue;
            }
            $edit_link = sprintf( '<a href="%s" class="tip" title="' . __( 'Edit', 'internal-links' ) . '">%s</a>', $asset_data->url_edit, '<span class="dashicons dashicons-edit"></span>' );
            $asset_link = sprintf( '<a href="%s" class="tip" title="' . __( 'Open', 'internal-links' ) . '" target="_blank" rel="noopener">%s</a>', $asset_data->url, '<span class="dashicons dashicons-external"></span>' );
            $elements_to = ( $statistic->elements_to ? '<a title="' . __( 'Show incoming links', 'internal-links' ) . '" class="tip ilj-statistic-detail" data-id="' . $statistic->asset_id . '" data-type="' . $statistic->asset_type . '" data-direction="to">' . $statistic->elements_to . '</a>' : '-' );
            $elements_from = ( $statistic->elements_from ? '<a title="' . __( 'Show outgoing links', 'internal-links' ) . '" class="tip ilj-statistic-detail" data-id="' . $statistic->asset_id . '" data-type="' . $statistic->asset_type . '" data-direction="from">' . $statistic->elements_from . '</a>' : '-' );
            $type = IndexAsset::getDetailedType( $statistic->asset_id, $statistic->asset_type );
            echo  '<tr>' ;
            echo  '<td class="asset-title">' . $asset_data->title . '</td>' ;
            echo  '<td>' . Statistic::getConfiguredKeywordsCountForAsset( $statistic->asset_id, $statistic->asset_type ) . '</td>' ;
            echo  '<td class="type" data-search="' . $statistic->asset_type . ';' . $type . '"><span data-type="' . $statistic->asset_type . '">' . $type . '</span></td>' ;
            echo  '<td>' . $elements_to . '</td>' ;
            echo  '<td>' . $elements_from . '</td>' ;
            echo  '<td>' . $edit_link . ' ' . $asset_link . '</td>' ;
            echo  '</tr>' ;
        }
        echo  '</tbody>' ;
        echo  '</table>' ;
    }
    
    /**
     * Renders the statistics for the anchor texts
     *
     * @since 1.2.5
     *
     * @return void
     */
    public static function renderAnchorsStatistic()
    {
        echo  '<table class="ilj-statistic-table-anchors display">' ;
        echo  '<thead><tr><th>' . __( 'Anchor text', 'internal-links' ) . '</th><th>' . __( 'Character count', 'internal-links' ) . '</th><th>' . __( 'Word count', 'internal-links' ) . '</th><th>' . __( 'Frequency', 'internal-links' ) . '</th></tr></thead>' ;
        echo  '<tbody>' ;
        foreach ( Statistic::getAnchorStatistics() as $statistic ) {
            echo  '<tr>' ;
            echo  '<td>' . $statistic->anchor . '</td>' ;
            echo  '<td>' . strlen( $statistic->anchor ) . '</td>' ;
            echo  '<td>' . count( explode( ' ', $statistic->anchor ) ) . '</td>' ;
            echo  '<td><a title="' . __( 'Show usage', 'internal-links' ) . '" class="tip ilj-statistic-detail" data-anchor="' . $statistic->anchor . '">' . $statistic->frequency . '</a></td>' ;
            echo  '</tr>' ;
        }
        echo  '</tbody>' ;
        echo  '</table>' ;
    }
    
    /**
     * Retrieves link data for a specific asset by a given direction (in- or outgoing)
     *
     * @since 1.1.0
     *
     * @return void
     */
    public static function renderLinkDetailStatisticAction()
    {
        if ( !isset( $_POST['id'] ) || !isset( $_POST['type'] ) || !isset( $_POST['direction'] ) ) {
            wp_die();
        }
        $id = (int) $_POST['id'];
        $type = $_POST['type'];
        $direction = $_POST['direction'];
        $directive_links = Linkindex::getDirectiveLinks( $id, $type, $direction );
        if ( !count( $directive_links ) ) {
            wp_die();
        }
        $direction_header = '';
        
        if ( $direction == 'from' ) {
            $direction_header = __( 'Target', 'internal-links' );
        } elseif ( $direction == 'to' ) {
            $direction_header = __( 'Source', 'internal-links' );
        }
        
        $data = '<table class="ilj-statistic-detail-table display"><thead><tr><th>' . $direction_header . '</th><th>' . __( 'Type', 'internal-links' ) . '</th><th>' . __( 'Anchor text', 'internal-links' ) . '</th></tr></thead>';
        $data .= '<tbody>';
        $row_counter = 0;
        for ( $i = 0 ;  $i < count( $directive_links ) ;  $i++ ) {
            $directive_link = $directive_links[$i];
            if ( !\ILJ\ilj_fs()->can_use_premium_code() && $i >= 3 ) {
                break;
            }
            if ( !property_exists( $directive_link, 'link_' . $direction ) || !property_exists( $directive_link, 'type_' . $direction ) || !property_exists( $directive_link, 'anchor' ) ) {
                continue;
            }
            
            if ( $direction == 'from' ) {
                $reverse_direction = 'to';
            } elseif ( $direction == 'to' ) {
                $reverse_direction = 'from';
            }
            
            $type = IndexAsset::getDetailedType( $directive_link->{'link_' . $reverse_direction}, $directive_link->{'type_' . $reverse_direction} );
            $asset_data = IndexAsset::getMeta( $directive_link->{'link_' . $reverse_direction}, $directive_link->{'type_' . $reverse_direction} );
            if ( !$asset_data ) {
                continue;
            }
            $data .= '<tr class="' . (( $row_counter % 2 === 0 ? 'even' : 'odd' )) . '"><td><a href="' . $asset_data->url . '" rel="noopener" target="_blank">' . $asset_data->title . '</a></td><td class="type"><span data-type="' . $directive_link->{'type_' . $reverse_direction} . '">' . $type . '</span></td><td>' . $directive_link->anchor . '</td></tr>';
            $row_counter++;
        }
        $data .= '</tbody>';
        $data .= '</table>';
        
        if ( !\ILJ\ilj_fs()->can_use_premium_code() && count( $directive_links ) > 3 ) {
            $data .= '<div class="ilj-statistic-detail-hidden spacer">';
            $data .= '	<div class="more"><span class="dashicons dashicons-lock"></span> and ' . (count( $directive_links ) - 3) . ' more</div>';
            $data .= '  <div class="upgrade">';
            $data .= '      <p>' . __( 'With the free basic version you can view the first 3 links in the detail view.', 'internal-links' ) . '</p>';
            $data .= '      <a href="' . get_admin_url( null, 'admin.php?page=' . AdminMenu::ILJ_MENUPAGE_SLUG . '-pricing' ) . '"><span class="dashicons dashicons-unlock"></span> ' . __( 'Upgrade to Pro and view all', 'internal-links' ) . '</a>';
            $data .= '  </div>';
            $data .= '</div>';
        }
        
        echo  $data ;
        wp_die();
    }
    
    /**
     * Renders link details for a specific anchor text
     *
     * @since  1.1.0
     * @return void
     */
    public static function renderAnchorDetailStatisticAction()
    {
        if ( !isset( $_POST['anchor'] ) ) {
            wp_die();
        }
        $data = '';
        
        if ( !\ILJ\ilj_fs()->can_use_premium_code() ) {
            $data = '<div class="ilj-statistic-detail-hidden">';
            $data .= '  <div class="upgrade">';
            $data .= '      <p>' . __( 'The detail view for anchor texts is part of the Pro version.', 'internal-links' ) . '</p>';
            $data .= '      <a href="' . get_admin_url( null, 'admin.php?page=' . AdminMenu::ILJ_MENUPAGE_SLUG . '-pricing' ) . '"><span class="dashicons dashicons-unlock"></span> ' . __( 'Upgrade to Pro and view all', 'internal-links' ) . '</a>';
            $data .= '  </div>';
            $data .= '</div>';
        }
        
        echo  $data ;
        wp_die();
    }
    
    /**
     * Handles interactions with the rating notification
     *
     * @since  1.2.0
     * @return void
     */
    public static function ratingNotificationAdd()
    {
        if ( !isset( $_POST['days'] ) ) {
            wp_die();
        }
        $days = (int) $_POST['days'];
        
        if ( $days === -1 ) {
            User::unsetRatingNotification();
            wp_die();
        }
        
        $days_string = sprintf( '+%d days', $days );
        $notification_base_date = new \DateTime( 'now' );
        $notification_base_date->modify( $days_string );
        User::setRatingNotificationBaseDate( $notification_base_date );
        wp_die();
    }
    
    /**
     * Hides the promo box in the sidebar
     *
     * @since  1.1.2
     * @return void
     */
    public static function hidePromo()
    {
        User::update( 'hide_promo', true );
        wp_die();
    }
    
    /**
     * Handles upload of import files
     *
     * @since  1.2.0
     * @return void
     */
    public static function uploadImport()
    {
        if ( !isset( $_POST['nonce'] ) || !isset( $_POST['file_type'] ) ) {
            wp_send_json_error( null, 400 );
        }
        $nonce = $_POST['nonce'];
        $file_type = $_POST['file_type'];
        if ( !in_array( $file_type, [ 'settings', 'keywords' ] ) ) {
            wp_send_json_error( null, 400 );
        }
        if ( !wp_verify_nonce( $nonce, 'ilj-tools' ) || !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( null, 400 );
        }
        $uploaded_file = $_FILES['file_data'];
        $upload_overrides = [
            'test_form' => false,
            'test_type' => false,
        ];
        if ( $file_type == 'keywords' ) {
            $uploaded_file['name'] = uniqid( rand(), true ) . '.csv';
        }
        $file_upload = wp_handle_upload( $uploaded_file, $upload_overrides );
        if ( !$file_upload || isset( $file_upload['error'] ) ) {
            wp_send_json_error( __( 'Your web host does not allow file uploads. Please fix the problem and try again.', 'internal-links' ), 400 );
        }
        switch ( $file_type ) {
            case 'settings':
                $file_content = file_get_contents( $file_upload['file'] );
                unlink( $file_upload['file'] );
                $file_json = Encoding::jsonToArray( $file_content );
                if ( $file_json === false ) {
                    wp_send_json_error( null, 400 );
                }
                set_transient( 'ilj_upload_settings', $file_json, HOUR_IN_SECONDS * 12 );
                break;
            case 'keywords':
                set_transient( 'ilj_upload_keywords', $file_upload, HOUR_IN_SECONDS * 12 );
                break;
        }
        wp_send_json_success( null, 200 );
    }
    
    /**
     * Initiates the import of already uploaded and prepared files
     *
     * @since  1.2.0
     * @return void
     */
    public static function startImport()
    {
        if ( !isset( $_POST['nonce'] ) || !isset( $_POST['file_type'] ) ) {
            wp_send_json_error( null, 400 );
        }
        $nonce = $_POST['nonce'];
        $file_type = $_POST['file_type'];
        if ( !in_array( $file_type, [ 'settings', 'keywords' ] ) ) {
            wp_send_json_error( null, 400 );
        }
        if ( !wp_verify_nonce( $nonce, 'ilj-tools' ) || !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( null, 400 );
        }
        $upload_transient = get_transient( 'ilj_upload_' . $file_type );
        if ( !$upload_transient ) {
            wp_send_json_error( __( 'Timeout. Please try to upload again.', 'internal-links' ), 400 );
        }
        switch ( $file_type ) {
            case 'settings':
                $import_count = CoreOptions::importOptions( $upload_transient );
                break;
            case 'keywords':
                if ( !isset( $upload_transient['file'] ) || !file_exists( $upload_transient['file'] ) ) {
                    wp_send_json_error( null, 400 );
                }
                $import_count = Keyword::importKeywordsFromFile( $upload_transient['file'] );
                unlink( $upload_transient['file'] );
                break;
        }
        if ( $import_count === 0 ) {
            wp_send_json_error( __( 'Nothing to import or no data for import found.', 'internal-links' ), 400 );
        }
        do_action( IndexBuilder::ILJ_ACTION_TRIGGER_BUILD_INDEX );
        delete_transient( 'ilj_upload_' . $file_type );
        wp_send_json_success( null, 200 );
    }

}