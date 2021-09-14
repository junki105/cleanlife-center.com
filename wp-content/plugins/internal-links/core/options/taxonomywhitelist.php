<?php

namespace ILJ\Core\Options;

use  ILJ\Helper\Options as OptionsHelper ;
/**
 * Option: Whitelist for taxonomies
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class TaxonomyWhitelist extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'taxonomy_whitelist';
    }
    
    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return [ 'category', 'post_tag' ];
    }
    
    /**
     * @inheritdoc
     */
    public static function isPro()
    {
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function register( $option_group )
    {
        return;
    }
    
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __( 'Whitelist of taxonomies, that should be used for linking', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __( 'All terms within the allowed taxonomies can link to other posts or terms automatically.', 'internal-links' );
    }
    
    /**
     * Gets all taxonomy types that can be used with the plugin
     *
     * @since  1.2.0
     * @return array
     */
    public static function getTaxonomyTypes()
    {
        $taxonomy_types_default = get_taxonomies( [
            'public'  => true,
            'show_ui' => true,
        ], 'objects', 'and' );
        $taxonomy_types_public = get_taxonomies( [
            'public'   => true,
            '_builtin' => false,
        ], 'objects', 'and' );
        $taxonomies = array_merge( $taxonomy_types_default, $taxonomy_types_public );
        return array_values( $taxonomies );
    }
    
    /**
     * @inheritdoc
     */
    public function renderField( $value )
    {
        if ( $value == "" ) {
            $value = [];
        }
        $taxonomies = $this->getTaxonomyTypes();
        
        if ( count( $taxonomies ) ) {
            echo  '<select name="' . self::getKey() . '[]" id="' . self::getKey() . '" multiple="multiple"' . OptionsHelper::getDisabler( $this ) . '>' ;
            foreach ( $taxonomies as $taxonomy ) {
                echo  '<option value="' . $taxonomy->name . '"' . (( in_array( $taxonomy->name, $value ) ? ' selected' : '' )) . '>' . $taxonomy->label . '</option>' ;
            }
            echo  '</select>' ;
        }
    
    }
    
    /**
     * @inheritdoc
     */
    public function isValidValue( $value )
    {
        return false;
    }

}