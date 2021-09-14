<?php

namespace ILJ\Core;

use  ILJ\Core\Options ;
use  ILJ\Helper\Encoding ;
use  ILJ\Helper\Regex ;
use  ILJ\Type\Ruleset ;
use  ILJ\Database\Linkindex ;
use  ILJ\Helper\Replacement ;
/**
 * The main LinkBuilder class
 *
 * Is responsible for editing a piece of content and setting links within by a given Ruleset
 *
 * @package ILJ\Core
 * @since   1.0.0
 */
class LinkBuilder
{
    /**
     * @var   int
     * @since 1.0.0
     */
    private  $id = null ;
    /**
     * @var   string
     * @since 1.0.1
     */
    private  $type = null ;
    /**
     * @var   Ruleset
     * @since 1.0.0
     */
    private  $link_ruleset = null ;
    /**
     * @var   Ruleset
     * @since 1.0.0
     */
    private  $replace_ruleset = null ;
    /**
     * @var   string
     * @since 1.0.0
     */
    private  $content = '' ;
    /**
     * Constructor of ILJ_LinkBuilder
     *
     * @since  1.0.1
     * @param  int    $id   The ID of the current subject
     * @param  string $type The type of the current subject
     * @return void
     */
    public function __construct( $id, $type )
    {
        $this->id = $id;
        $this->type = $type;
        $this->replace_ruleset = new Ruleset();
        $this->setupInLinks();
    }
    
    /**
     * Loads all ingoing links to current content from linkindex table and sets a new Ruleset type with it
     *
     * @since  1.0.0
     * @return void
     */
    private function setupInLinks()
    {
        $this->link_ruleset = new Ruleset();
        $post_rules = Linkindex::getRules( $this->id, $this->type );
        foreach ( $post_rules as $post_rule ) {
            $this->link_ruleset->addRule( Regex::escapeDot( $post_rule->anchor ), $post_rule->link_to, $post_rule->type_to );
        }
        return;
    }
    
    /**
     * Applies the link rules to a given piece of content
     *
     * @since  1.0.0
     * @param  string $content The content of the post, where the rules get applied
     * @return string
     */
    public function linkContent( $content )
    {
        $this->content = $content;
        $this->replace_ruleset = Replacement::mask( $this->content );
        $this->maskLinkRules();
        $this->applyReplaceRules();
        return $this->content;
    }
    
    /**
     * Applies the given LinkRuleset for the document through masking within the content.
     *
     * @since  1.0.0
     * @return void
     */
    private function maskLinkRules()
    {
        $multi_keyword_mode = (bool) Options::getOption( \ILJ\Core\Options\MultipleKeywords::getKey() );
        $links_per_page = ( $multi_keyword_mode === false ? Options::getOption( \ILJ\Core\Options\LinksPerPage::getKey() ) : 0 );
        $links_per_target = ( $multi_keyword_mode === false ? Options::getOption( \ILJ\Core\Options\LinksPerTarget::getKey() ) : 0 );
        $link_page_count = 0;
        $link_target_count = [];
        $used_pattern = [];
        while ( $this->link_ruleset->hasRule() ) {
            $link_rule = $this->link_ruleset->getRule();
            if ( $links_per_page > 0 && $link_page_count == $links_per_page ) {
                break;
            }
            
            if ( $links_per_target > 0 && array_key_exists( $link_rule->value, $link_target_count ) && $link_target_count[$link_rule->value] >= $links_per_target || !$multi_keyword_mode && in_array( $link_rule->pattern, $used_pattern ) ) {
                $this->link_ruleset->nextRule();
                continue;
            }
            
            $pattern = wptexturize( $link_rule->pattern );
            preg_match_all( '/' . Encoding::maskPattern( $pattern ) . '/ui', $this->content, $rule_match );
            
            if ( !isset( $rule_match['phrase'] ) || !count( $rule_match['phrase'] ) ) {
                $this->link_ruleset->nextRule();
                continue;
            }
            
            $phrases = array_unique( $rule_match['phrase'] );
            foreach ( $phrases as $index => $rule ) {
                
                if ( $links_per_target > 0 && array_key_exists( $link_rule->value, $link_target_count ) && $link_target_count[$link_rule->value] == $links_per_target || !$multi_keyword_mode && in_array( $link_rule->pattern, $used_pattern ) ) {
                    $this->link_ruleset->nextRule();
                    continue 2;
                }
                
                $rule_id = 'ilj_' . uniqid( '', true );
                $link = $this->generateLink( $link_rule, esc_html( $rule ) );
                
                if ( !$link ) {
                    $this->link_ruleset->nextRule();
                    continue;
                }
                
                $this->content = preg_replace(
                    '/' . Encoding::maskPattern( $rule ) . '/u',
                    $rule_id,
                    $this->content,
                    ( $multi_keyword_mode ? -1 : 1 )
                );
                $this->replace_ruleset->addRule( $rule_id, $link );
                if ( !array_key_exists( $link_rule->value, $link_target_count ) ) {
                    $link_target_count[$link_rule->value] = 0;
                }
                $used_pattern[] = $link_rule->pattern;
                $link_target_count[$link_rule->value]++;
                $link_page_count++;
            }
            $this->link_ruleset->nextRule();
        }
    }
    
    /**
     * Applies the configured masks and replaces the placeholders with the generated links.
     *
     * @since  1.0.0
     * @return void
     */
    private function applyReplaceRules()
    {
        while ( $this->replace_ruleset->hasRule() ) {
            $replace_rule = $this->replace_ruleset->getRule();
            $this->content = str_replace( $replace_rule->pattern, $replace_rule->value, $this->content );
            $this->replace_ruleset->nextRule();
        }
        
        if ( preg_match( "/ilj\\_[a-z0-9]{14}\\.[0-9]{8}/", $this->content ) ) {
            $this->replace_ruleset->reset();
            $this->applyReplaceRules();
        }
        
        return;
    }
    
    /**
     * Returns the template for link output
     *
     * @since  1.0.0
     * @return string
     */
    private function getLinkTemplate()
    {
        $default_template = \ILJ\Core\Options\LinkOutputInternal::getDefault();
        $template = Options::getOption( \ILJ\Core\Options\LinkOutputInternal::getKey() );
        if ( $template == "" ) {
            return $default_template;
        }
        return wp_specialchars_decode( $template, \ENT_QUOTES );
    }
    
    /**
     * Generates the link markup
     *
     * @since  1.0.0
     * @param  string $post_id The post where the link should point to
     * @param  string $anchor  The anchortext for the link
     * @return bool|string
     */
    private function generateLink( $link_rule, $anchor )
    {
        $template = $this->getLinkTemplate();
        $nofollow = (bool) Options::getOption( \ILJ\Core\Options\InternalNofollow::getKey() );
        
        if ( $link_rule->type == 'post' ) {
            if ( get_post_status( $link_rule->value ) != 'publish' ) {
                return false;
            }
            $url = get_the_permalink( $link_rule->value );
        }
        
        $link = str_replace( '{{url}}', ( isset( $url ) ? $url : '#' ), $template );
        $link = str_replace( '{{anchor}}', $anchor, $link );
        $check_nofollow = true;
        if ( $check_nofollow && $nofollow ) {
            $link = str_replace( '<a ', '<a rel="nofollow" ', $link );
        }
        return $link;
    }

}