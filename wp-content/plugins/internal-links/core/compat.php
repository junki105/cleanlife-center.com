<?php

namespace ILJ\Core;

use  ILJ\Backend\MenuPage\Dashboard ;
use  ILJ\Backend\MenuPage\Tools ;
use  ILJ\Core\IndexStrategy\PolylangStrategy ;
use  ILJ\Core\IndexStrategy\WPMLStrategy ;
use  ILJ\Helper\Ajax ;
use  ILJ\Helper\IndexAsset ;
use  ILJ\Type\KeywordList ;
/**
 * Compatibility handler
 *
 * Responsible for managing compatibility with other 3rd party plugins
 *
 * @package ILJ\Core
 *
 * @since 1.2.0
 */
class Compat
{
    /**
     * Initializes the Compat module
     *
     * @static
     * @since  1.2.0
     *
     * @return void
     */
    public static function init()
    {
        self::enableWpml();
        self::enableYoast();
        self::enableRankMath();
        self::enablePolylang();
    }
    
    /**
     * Responsible for handling Polylang integration
     *
     * @static
     * @since  1.2.2
     *
     * @return void
     */
    public static function enablePolylang()
    {
        if ( !defined( 'POLYLANG_BASENAME' ) ) {
            return;
        }
        add_filter( IndexBuilder::ILJ_FILTER_INDEX_STRATEGY, function ( $strategy ) {
            return new PolylangStrategy();
        } );
        add_filter(
            Ajax::ILJ_FILTER_AJAX_SEARCH_POSTS,
            function ( $data, $args ) {
            for ( $i = 0 ;  $i < count( $data ) ;  $i++ ) {
                $data[$i]['text'] = $data[$i]['text'] . ' (' . pll_get_post_language( $data[$i]['id'] ) . ')';
            }
            return $data;
        },
            10,
            2
        );
        add_filter(
            IndexAsset::ILJ_FILTER_INDEX_ASSET,
            function ( $meta_data, $type, $id ) {
            $asset_language = '';
            $language_container = [];
            $asset_language = ( $asset_language == '' ? pll_get_post_language( $id ) : $asset_language );
            if ( !$asset_language || $asset_language == '' ) {
                return $meta_data;
            }
            if ( !isset( $language_container[$asset_language] ) ) {
                $language_container[$asset_language] = PLL()->model->get_language( $asset_language );
            }
            $flag_url = $language_container[$asset_language]->flag_url;
            $flag_img = sprintf( '<img class="tip" src="%s" title="%s" />', $flag_url, $language_container[$asset_language]->name );
            $meta_data->title = $flag_img . ' ' . $meta_data->title;
            return $meta_data;
        },
            10,
            3
        );
    }
    
    /**
     * Responsible for handling WPML integration
     *
     * @static
     * @since  1.2.0
     *
     * @return void
     */
    protected static function enableWpml()
    {
        if ( !function_exists( 'icl_object_id' ) || defined( 'POLYLANG_BASENAME' ) ) {
            return;
        }
        add_filter( IndexBuilder::ILJ_FILTER_INDEX_STRATEGY, function ( $strategy ) {
            return new WPMLStrategy();
        } );
        add_filter(
            Ajax::ILJ_FILTER_AJAX_SEARCH_POSTS,
            function ( $data, $args ) {
            global  $sitepress ;
            $languages = WPMLStrategy::getLanguages();
            $current_language = $sitepress->get_current_language();
            for ( $i = 0 ;  $i < count( $data ) ;  $i++ ) {
                $data[$i]['text'] = $data[$i]['text'] . ' (' . $current_language . ')';
            }
            foreach ( $languages as $language ) {
                if ( $language == $current_language ) {
                    continue;
                }
                $sitepress->switch_lang( $language, true );
                $query = new \WP_Query( $args );
                foreach ( $query->posts as $post ) {
                    $data[] = [
                        "id"   => $post->ID,
                        "text" => $post->post_title . ' (' . $language . ')',
                    ];
                }
                $sitepress->switch_lang( $current_language, true );
            }
            return $data;
        },
            10,
            2
        );
        add_filter(
            IndexAsset::ILJ_FILTER_INDEX_ASSET,
            function ( $meta_data, $type, $id ) {
            global  $sitepress ;
            $language_info = ( !isset( $language_info ) ? wpml_get_language_information( null, (int) $id ) : $language_info );
            if ( !$language_info ) {
                return $meta_data;
            }
            $flag_url = $sitepress->get_flag_url( $language_info['language_code'] );
            $flag_img = sprintf( '<img class="tip" src="%s" title="%s" />', $flag_url, $language_info['display_name'] );
            $meta_data->title = $flag_img . ' ' . $meta_data->title;
            return $meta_data;
        },
            10,
            3
        );
    }
    
    /**
     * Responsible for handling Yoast-SEO integration
     *
     * @static
     * @since  1.2.0
     *
     * @return void
     */
    protected static function enableYoast()
    {
        if ( !defined( 'WPSEO_VERSION' ) ) {
            return;
        }
        add_filter( Tools::ILJ_FILTER_MENUPAGE_TOOLS_KEYWORD_IMPORT_POST, function ( $keyword_import_source ) {
            $import_source = [
                'title' => __( 'Yoast focus keywords', 'internal-links' ),
                'class' => 'yoast-seo',
            ];
            $keyword_import_source[] = $import_source;
            return $keyword_import_source;
        } );
        add_filter( Tools::ILJ_FILTER_MENUPAGE_TOOLS_KEYWORD_IMPORT_TERM, function ( $keyword_import_source ) {
            $import_source = [
                'title' => __( 'Yoast focus keywords', 'internal-links' ),
                'class' => 'yoast-seo',
            ];
            $keyword_import_source[] = $import_source;
            return $keyword_import_source;
        } );
    }
    
    /**
     * Responsible for handling RankMath integration
     *
     * @static
     * @since  1.2.0
     *
     * @return void
     */
    protected static function enableRankMath()
    {
        if ( !class_exists( 'RankMath' ) ) {
            return;
        }
        add_filter( Tools::ILJ_FILTER_MENUPAGE_TOOLS_KEYWORD_IMPORT_POST, function ( $keyword_import_source ) {
            $import_source = [
                'title' => __( 'RankMath focus keywords', 'internal-links' ),
                'class' => 'rankmath',
            ];
            $keyword_import_source[] = $import_source;
            return $keyword_import_source;
        } );
        add_filter( Tools::ILJ_FILTER_MENUPAGE_TOOLS_KEYWORD_IMPORT_TERM, function ( $keyword_import_source ) {
            $import_source = [
                'title' => __( 'RankMath focus keywords', 'internal-links' ),
                'class' => 'rankmath',
            ];
            $keyword_import_source[] = $import_source;
            return $keyword_import_source;
        } );
    }

}