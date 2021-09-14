<?php

namespace ILJ\Database;

/**
 * Postmeta wrapper for the inlink postmeta
 *
 * @package ILJ\Database
 * @since   1.0.0
 */
class Postmeta
{
    const  ILJ_META_KEY_LINKDEFINITION = 'ilj_linkdefinition' ;
    /**
     * Returns all Linkdefinitions from postmeta table
     *
     * @since  1.0.0
     * @return array
     */
    public static function getAllLinkDefinitions()
    {
        global  $wpdb ;
        $meta_key = self::ILJ_META_KEY_LINKDEFINITION;
        $public_post_types = array_keys( get_post_types( [
            'public' => true,
        ] ) );
        $public_post_types = array_map( 'esc_sql', $public_post_types );
        $public_post_types_list = "'" . implode( "','", $public_post_types ) . "'";
        $query = "\n            SELECT postmeta.*\n            FROM {$wpdb->postmeta} postmeta\n            LEFT JOIN {$wpdb->posts} posts ON postmeta.post_id = posts.ID\n            WHERE postmeta.meta_key = '{$meta_key}'\n            AND posts.post_status = 'publish'\n            AND posts.post_type IN ({$public_post_types_list})\n        ";
        return $wpdb->get_results( $query );
    }
    
    /**
     * Removes all link definitions from postmeta table
     *
     * @since  1.1.3
     * @return int
     */
    public static function removeAllLinkDefinitions()
    {
        global  $wpdb ;
        $meta_key = self::ILJ_META_KEY_LINKDEFINITION;
        return $wpdb->delete( $wpdb->postmeta, array(
            'meta_key' => $meta_key,
        ) );
    }

}