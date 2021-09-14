<?php

namespace ILJ\Helper;

use  ILJ\Backend\Editor ;
use  ILJ\Database\Linkindex ;
use  ILJ\Database\Postmeta ;
use  ILJ\Database\Termmeta ;
/**
 * Statistics toolset
 *
 * Methods for providing statistics
 *
 * @package ILJ\Helper
 * @since   1.0.0
 */
class Statistic
{
    /**
     * Returns the amount of configured keywords
     *
     * @since  1.1.3
     * @return int
     */
    public static function getConfiguredKeywordsCount()
    {
        $configuredKeywords = [];
        $postmeta = Postmeta::getAllLinkDefinitions();
        foreach ( $postmeta as $meta ) {
            $keywords = get_post_meta( $meta->post_id, Postmeta::ILJ_META_KEY_LINKDEFINITION, true );
            if ( is_array( $keywords ) ) {
                $configuredKeywords = array_merge( $configuredKeywords, $keywords );
            }
        }
        return count( $configuredKeywords );
    }
    
    /**
     * Returns the count of configured keywords by a given asset type
     *
     * @since 1.2.5
     * @param int    $asset_id   The Id of the asset
     * @param string $asset_type The type of the asset
     *
     * @return int
     */
    public static function getConfiguredKeywordsCountForAsset( $asset_id, $asset_type )
    {
        $allowed_asset_types = [ 'post' ];
        if ( !in_array( $asset_type, $allowed_asset_types ) ) {
            return 0;
        }
        $data = get_post_meta( $asset_id, Postmeta::ILJ_META_KEY_LINKDEFINITION );
        return ( count( $data ) ? count( $data[0] ) : '0' );
    }
    
    /**
     * Returns the statistics for links
     *
     * @since  1.1.0
     * @param  int $results Number of results to display
     * @param  int $page    Number of page to display
     * @return array
     */
    public static function getLinkStatistics( $results = -1, $page = 0 )
    {
        $page = ( $page > 0 ? $page : 1 );
        $limit = (int) $results;
        $offset = (int) ($page - 1) * $results;
        $links = Linkindex::getGroupedCountFull( 'elements_to', $limit, $offset );
        return $links;
    }
    
    /**
     * Returns the statistics for anchor texts
     *
     * @since  1.1.0
     * @param  int $results
     * @param  int $page
     * @return array
     */
    public static function getAnchorStatistics( $results = -1, $page = 0 )
    {
        $page = ( $page > 0 ? $page : 1 );
        $limit = (int) $results;
        $offset = (int) ($page - 1) * $results;
        $anchors = Linkindex::getAnchorCountFull();
        return $anchors;
    }
    
    /**
     * A configureable wrapper for the aggregation of columns of the linkindex
     *
     * @deprecated
     * @since      1.0.0
     * @param      array $args Configuration of the selection
     * @return     array
     */
    public static function getAggregatedCount( $args = array() )
    {
        $defaults = [
            "type"  => "link_from",
            "limit" => 10,
        ];
        $args = wp_parse_args( $args, $defaults );
        extract( $args );
        if ( !is_numeric( $limit ) ) {
            $limit = $defaults['limit'];
        }
        $inlinks = Linkindex::getGroupedCount( $type );
        return array_slice( $inlinks, 0, $limit );
    }

}