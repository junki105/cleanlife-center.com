<?php

namespace ILJ\Backend;

use  ILJ\Database\Postmeta ;
/**
 * Listview columns
 *
 * Responsible for adding information to listview columns (posts, taxonomies)
 *
 * @package ILJ\Backend
 *
 * @since 1.1.3
 */
class Column
{
    const  ILJ_COLUMN_CONFIGURED_LINKS = 'ilj_column_configured_links' ;
    /**
     * Returns the title for the configured links column in the frontend
     *
     * @since 1.1.3
     *
     * @return string
     */
    protected static function getConfiguredLinksColumnTitle()
    {
        return __( 'Configured keywords for internal linking', 'internal-links' );
    }
    
    /**
     * Generates and adds all possible configured links columns
     *
     * @since 1.1.3
     *
     * @return void
     */
    public static function addConfiguredLinksColumn()
    {
        $types = get_post_types( [
            'public' => true,
        ] );
        foreach ( $types as $type ) {
            add_action(
                'manage_' . $type . '_posts_custom_column',
                [ '\\ILJ\\Backend\\Column', 'addConfiguredLinksColumnContent' ],
                10,
                2
            );
            add_filter( 'manage_' . $type . '_posts_columns', [ '\\ILJ\\Backend\\Column', 'addConfiguredLinksColumnHeader' ] );
            add_filter( 'manage_edit-' . $type . '_sortable_columns', [ '\\ILJ\\Backend\\Column', 'addConfiguredLinksColumnSorter' ] );
            add_filter( 'wp', [ '\\ILJ\\Backend\\Column', 'sortConfiguredLinksColumn' ] );
        }
    }
    
    /**
     * Adds the configured links column header
     *
     * @since 1.1.3
     * @param array $columns All columns header
     *
     * @return array
     */
    public static function addConfiguredLinksColumnHeader( $columns )
    {
        wp_enqueue_style( 'ilj_ui', ILJ_URL . 'admin/css/ilj_ui.css' );
        $columns[self::ILJ_COLUMN_CONFIGURED_LINKS] = '<span class="icon icon-ilj" title="' . self::getConfiguredLinksColumnTitle() . '"></span><span class="screen-reader-text">' . self::getConfiguredLinksColumnTitle() . '</span>';
        return $columns;
    }
    
    /**
     * Outputs the content of the configured links column
     *
     * @since 1.1.3
     * @param string $column  The current column
     * @param int    $post_id Post ID
     *
     * @return void
     */
    public static function addConfiguredLinksColumnContent( $column, $post_id )
    {
        
        if ( $column === self::ILJ_COLUMN_CONFIGURED_LINKS ) {
            $data = get_post_meta( $post_id, Postmeta::ILJ_META_KEY_LINKDEFINITION );
            echo  ( count( $data ) ? count( $data[0] ) : '0' ) ;
        }
    
    }
    
    /**
     * Adds the sorter to the configured links column
     *
     * @since 1.1.3
     * @param array $columns All sortable columns
     *
     * @return array
     */
    public static function addConfiguredLinksColumnSorter( $columns )
    {
        $columns[self::ILJ_COLUMN_CONFIGURED_LINKS] = self::ILJ_COLUMN_CONFIGURED_LINKS;
        return $columns;
    }
    
    /**
     * Adds the post sorting logic for configured links column
     *
     * @since 1.1.3
     *
     * @return void
     */
    public static function sortConfiguredLinksColumn()
    {
        global  $wp_query ;
        if ( !is_admin() ) {
            return;
        }
        $orderby = $wp_query->get( 'orderby' );
        if ( self::ILJ_COLUMN_CONFIGURED_LINKS != $orderby ) {
            return;
        }
        $page_offset = ( $wp_query->query_vars['paged'] ? $wp_query->query_vars['paged'] : 1 );
        $posts_per_page = $wp_query->query_vars['posts_per_page'];
        $order = ( isset( $wp_query->query_vars ) && isset( $wp_query->query_vars['order'] ) && strcasecmp( $wp_query->query_vars['order'], 'desc' ) == 0 ? 'DESC' : 'ASC' );
        $args = $wp_query->query;
        $args['posts_per_page'] = -1;
        $new_query = new \WP_Query( $args );
        $posts = $new_query->posts;
        usort( $posts, function ( $a, $b ) use( $order ) {
            $keywords_a = get_post_meta( $a->ID, Postmeta::ILJ_META_KEY_LINKDEFINITION );
            $keywords_b = get_post_meta( $b->ID, Postmeta::ILJ_META_KEY_LINKDEFINITION );
            $count_a = ( count( $keywords_a ) ? count( $keywords_a[0] ) : 0 );
            $count_b = ( count( $keywords_b ) ? count( $keywords_b[0] ) : 0 );
            $sorting_value = ( ( $order == "DESC" ? $count_a > $count_b : $count_a < $count_b ) ? -1 : (( $count_a == $count_b ? 0 : 1 )) );
            return $sorting_value;
        } );
        $sliced = array_slice( $posts, ($page_offset - 1) * $posts_per_page, $posts_per_page );
        $wp_query->posts = $sliced;
    }

}