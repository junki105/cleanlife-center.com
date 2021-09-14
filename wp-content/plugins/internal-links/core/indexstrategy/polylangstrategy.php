<?php

namespace ILJ\Core\IndexStrategy;

use  ILJ\Core\Options ;
use  ILJ\Database\Linkindex ;
use  ILJ\Database\Postmeta ;
use  ILJ\Helper\Encoding ;
use  ILJ\Helper\IndexAsset ;
use  ILJ\Helper\Regex ;
use  ILJ\Helper\Replacement ;
use  ILJ\Helper\Url ;
use  ILJ\Type\Ruleset ;
/**
 * Polylang compatible indexbuilder
 *
 * Takes care of interlinking only pages from the same language domain
 *
 * @package ILJ\Core\Indexbuilder
 *
 * @since 1.2.2
 */
class PolylangStrategy extends DefaultStrategy
{
    /**
     * @var   array
     * @since 1.2.2
     */
    protected  $link_rules = array() ;
    /**
     * @var   array
     * @since 1.2.2
     */
    protected  $languages = array() ;
    public function __construct()
    {
        $this->languages = self::getLanguages();
    }
    
    /**
     * Get all active Polylang languages
     *
     * @static
     * @since  1.2.2
     *
     * @return array
     */
    public static function getLanguages()
    {
        $languages = [];
        $languagesData = ( function_exists( 'icl_get_languages' ) ? icl_get_languages( 'skip_missing=0&orderby=code' ) : [] );
        if ( !count( $languagesData ) ) {
            return $languages;
        }
        foreach ( $languagesData as $language ) {
            $languages[] = $language['language_code'];
        }
        return array_unique( $languages );
    }
    
    /**
     * @inheritdoc
     */
    public function setIndices()
    {
        $index_count = 0;
        $this->loadLinkConfigurations();
        $posts = IndexAsset::getPosts();
        $this->writeToIndex(
            $posts,
            'post',
            [
            'id'      => 'ID',
            'content' => 'post_content',
        ],
            $index_count
        );
        return $index_count;
    }
    
    /**
     * Picks up all meta definitions for configured keywords by language and adds them to internal ruleset
     *
     * @since 1.2.2
     *
     * @return void
     */
    protected function loadLinkConfigurations()
    {
        $post_definitions = Postmeta::getAllLinkDefinitions();
        foreach ( $this->languages as $language ) {
            $this->link_rules[$language] = new Ruleset();
            foreach ( $post_definitions as $definition ) {
                if ( $this->getDataLanguage( $definition->post_id, 'post' ) != $language ) {
                    continue;
                }
                $type = 'post';
                $anchors = unserialize( $definition->meta_value );
                if ( !$anchors ) {
                    continue;
                }
                $anchors = $this->applyKeywordOrder( $anchors );
                $this->addAnchorsToLinkRules( $anchors, [
                    'id'       => $definition->post_id,
                    'type'     => $type,
                    'language' => $language,
                ] );
            }
        }
        return;
    }
    
    /**
     * Writes a set of data to the linkindex
     *
     * @since 1.2.2
     *
     * @param  array  $data      The data container
     * @param  string $data_type Type of the data inside the container
     * @param  array  $fields    Field settings for the container objects
     * @param  int    &$counter  Counts the written operations
     * @return void
     */
    protected function writeToIndex(
        $data,
        $data_type,
        array $fields,
        &$counter
    )
    {
        if ( !is_array( $data ) || !count( $data ) ) {
            return;
        }
        $multi_keyword_mode = $this->link_options['multi_keyword_mode'];
        $links_per_page = $this->link_options['links_per_page'];
        $links_per_target = $this->link_options['links_per_target'];
        $fields = wp_parse_args( $fields, [
            'id'      => '',
            'content' => '',
        ] );
        foreach ( $this->languages as $language ) {
            $data_filtered = $this->filterDataByLanguage( $data, $language, $data_type );
            foreach ( $data_filtered as $item ) {
                $linked_urls = [];
                $linked_anchors = [];
                $post_outlinks_count = 0;
                if ( !property_exists( $item, $fields['content'] ) || !property_exists( $item, $fields['id'] ) ) {
                    continue;
                }
                $content = $item->{$fields['content']};
                if ( $data_type == 'post' ) {
                    $this->filterTheContentWithoutTexturize( $content );
                }
                Replacement::mask( $content );
                while ( $this->link_rules[$language]->hasRule() ) {
                    $link_rule = $this->link_rules[$language]->getRule();
                    if ( !isset( $linked_urls[$link_rule->value] ) ) {
                        $linked_urls[$link_rule->value] = 0;
                    }
                    
                    if ( !$multi_keyword_mode && ($links_per_page > 0 && $post_outlinks_count >= $links_per_page || $links_per_target > 0 && $linked_urls[$link_rule->value] >= $links_per_target) ) {
                        $this->link_rules[$language]->nextRule();
                        continue;
                    }
                    
                    
                    if ( $link_rule->value != $item->{$fields['id']} ) {
                        preg_match( '/' . Encoding::maskPattern( $link_rule->pattern ) . '/ui', $content, $rule_match );
                        
                        if ( isset( $rule_match['phrase'] ) ) {
                            $phrase = trim( $rule_match['phrase'] );
                            
                            if ( !$multi_keyword_mode && in_array( $phrase, $linked_anchors ) ) {
                                $this->link_rules[$language]->nextRule();
                                continue;
                            }
                            
                            Linkindex::addRule(
                                $item->{$fields['id']},
                                $link_rule->value,
                                $phrase,
                                $data_type,
                                $link_rule->type
                            );
                            $counter++;
                            $post_outlinks_count++;
                            $linked_urls[$link_rule->value]++;
                            $linked_anchors[] = $phrase;
                        }
                    
                    }
                    
                    $this->link_rules[$language]->nextRule();
                }
                $this->link_rules[$language]->reset();
            }
        }
    }
    
    /**
     * @inheritDoc
     */
    protected function addAnchorsToLinkRules( array $anchors, array $params )
    {
        foreach ( $anchors as $anchor ) {
            $anchor = Encoding::unmaskSlashes( $anchor );
            if ( !Regex::isValid( $anchor ) ) {
                continue;
            }
            $pattern = Regex::escapeDot( $anchor );
            $this->link_rules[$params['language']]->addRule( $pattern, $params['id'], $params['type'] );
        }
        return;
    }
    
    /**
     * Get the language of any asset data (post, tax)
     *
     * @since 1.2.2
     * @param int    $data_id   The id of the asset
     * @param string $data_type The type of the asset (post, tax)
     *
     * @return string
     */
    protected function getDataLanguage( $data_id, $data_type )
    {
        return pll_get_post_language( $data_id );
    }
    
    /**
     * Filters a collection of data (posts, taxes) by a given language
     *
     * @since 1.2.2
     * @param array  $data      The data collection
     * @param string $language  The language code
     * @param string $data_type The type of the collection items
     *
     * @return array
     */
    protected function filterDataByLanguage( $data, $language, $data_type )
    {
        $data_filtered = [];
        foreach ( $data as $current ) {
            $data_id = ( isset( $data_id ) ? $data_id : $current->ID );
            $data_language = $this->getDataLanguage( $data_id, $data_type );
            if ( $data_language == $language ) {
                $data_filtered[] = $current;
            }
            unset( $data_id );
        }
        return $data_filtered;
    }

}