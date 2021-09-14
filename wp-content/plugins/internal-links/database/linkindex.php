<?php

namespace ILJ\Database;

/**
 * Database wrapper for the linkindex table
 *
 * @package ILJ\Database
 * @since   1.0.0
 */
class Linkindex
{
    const  ILJ_DATABASE_TABLE_LINKINDEX = "ilj_linkindex" ;
    /**
     * Cleans the whole index table
     *
     * @since  1.0.0
     * @return void
     */
    public static function flush()
    {
        global  $wpdb ;
        $wpdb->query( "TRUNCATE TABLE " . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX );
    }
    
    /**
     * Returns all post outlinks from linkindex table
     *
     * @since  1.0.1
     * @param  int $id The post ID where outlinks should be retrieved
     * @return array
     */
    public static function getRules( $id, $type )
    {
        if ( !is_numeric( $id ) ) {
            return [];
        }
        global  $wpdb ;
        $query = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX . " linkindex WHERE linkindex.link_from = %d AND linkindex.type_from = %s", $id, $type );
        return $wpdb->get_results( $query );
    }
    
    /**
     * Adds a post rule to the linkindex table
     *
     * @since  1.0.1
     * @param  int    $link_from Post ID which gives the link
     * @param  int    $link_to   Post ID where the link should point to
     * @param  string $anchor    The anchor text which gets used for linking
     * @param  string $type_from The type of asset which gives the link
     * @param  string $type_to   The type of asset which receives the link
     * @return void
     */
    public static function addRule(
        $link_from,
        $link_to,
        $anchor,
        $type_from,
        $type_to
    )
    {
        if ( !is_integer( (int) $link_from ) || !is_integer( (int) $link_to ) || !is_string( (string) $anchor ) ) {
            return;
        }
        global  $wpdb ;
        $wpdb->insert( $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX, [
            'link_from' => $link_from,
            'link_to'   => $link_to,
            'anchor'    => $anchor,
            'type_from' => $type_from,
            'type_to'   => $type_to,
        ], [
            '%d',
            '%d',
            '%s',
            '%s',
            '%s'
        ] );
    }
    
    /**
     * Aggregates and counts entries for a given column
     *
     * @since  1.0.0
     * @param  string $column The column in the linkindex table
     * @return array
     */
    public static function getGroupedCount( $column )
    {
        $allowed_columns = [ 'link_from', 'link_to', 'anchor' ];
        if ( !in_array( $column, $allowed_columns ) ) {
            return null;
        }
        $type_mapping = [
            'link_from' => 'type_from',
            'link_to'   => 'type_to',
        ];
        $type = ( in_array( $column, array_keys( $type_mapping ) ) ? ', ' . $type_mapping[$column] . ' AS type ' : '' );
        global  $wpdb ;
        $query = sprintf(
            'SELECT  %1$s, COUNT(*) AS elements%2$s FROM %3$s linkindex GROUP BY %1$s ORDER BY elements DESC',
            $column,
            $type,
            $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX
        );
        return $wpdb->get_results( $query );
    }
    
    /**
     * Returns all link data grouped by in- and outlink countings
     *
     * @since  1.1.0
     * @param  string $column The column in the linkindex table
     * @param  int    $limit  Count of selected results
     * @param  int    $offset Offset of selected results
     * @return array
     */
    public static function getGroupedCountFull( $order, $limit, $offset )
    {
        $allowed_orders = [ 'elements_from', 'elements_to' ];
        if ( !in_array( $order, $allowed_orders ) ) {
            $order = 'elements_from';
        }
        $limit = ( $limit > 0 ? sprintf( ' LIMIT %1$d OFFSET %2$d', $limit, $offset ) : '' );
        global  $wpdb ;
        $query = sprintf( '
            SELECT asset_id, elements_from, elements_to, asset_type
            FROM(
                (
                    SELECT link_from AS asset_id, COUNT(*) AS elements_from, type_from AS asset_type
                    FROM ' . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX . ' GROUP BY asset_id,asset_type) outlinks
                    LEFT JOIN
                    (
                        SELECT link_to AS asset_id_, COUNT(*) AS elements_to, type_to AS asset_type_
                        FROM ' . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX . ' GROUP BY asset_id_,asset_type_
                    ) inlinks
                    ON
                    (outlinks.asset_id = inlinks.asset_id_)
                    AND
                    (outlinks.asset_type = inlinks.asset_type_)
                )
                UNION
                SELECT asset_id, elements_from, elements_to, asset_type
                FROM(
                    (SELECT link_from AS asset_id_, COUNT(*) AS elements_from, type_from AS asset_type_ FROM ' . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX . ' a GROUP BY asset_id_,asset_type_) outlinks
                    RIGHT JOIN
                    (SELECT link_to AS asset_id, COUNT(*) AS elements_to, type_to AS asset_type FROM ' . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX . ' GROUP BY asset_id,asset_type) inlinks
                    ON
                    (outlinks.asset_id_ = inlinks.asset_id)
                    AND
                    (outlinks.asset_type_ = inlinks.asset_type)
                )
            ORDER BY %1$s DESC' . $limit, $order );
        return $wpdb->get_results( $query );
    }
    
    /**
     * Returns all links, pointing to or from a single ressource
     *
     * @since  1.2.5
     * @param  int    $id
     * @param  string $type
     * @param  string $direction
     * @return array
     */
    public static function getDirectiveLinks( $id, $type, $direction )
    {
        global  $wpdb ;
        if ( !is_numeric( $id ) ) {
            return;
        }
        $select = "SELECT * FROM " . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX . " linkindex";
        
        if ( $direction == 'from' ) {
            $query = $wpdb->prepare( $select . " WHERE linkindex.link_from = %d AND linkindex.type_from = %s", $id, $type );
        } elseif ( $direction == 'to' ) {
            $query = $wpdb->prepare( $select . " WHERE linkindex.link_to = %d AND linkindex.type_to = %s", $id, $type );
        } else {
            return null;
        }
        
        return $wpdb->get_results( $query );
    }
    
    /**
     * Returns all anchor texts with their frequency
     *
     * @since  1.1.0
     * @return array
     */
    public static function getAnchorCountFull()
    {
        global  $wpdb ;
        $query = "SELECT *, count(anchor) as frequency FROM  " . $wpdb->prefix . self::ILJ_DATABASE_TABLE_LINKINDEX . "  GROUP BY anchor ORDER BY frequency DESC";
        return $wpdb->get_results( $query );
    }

}