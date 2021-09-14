<?php

namespace ILJ\Core\Options;

use  ILJ\Helper\Options as OptionsHelper ;
/**
 * Option: Blacklist for terms
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class TermBlacklist extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'term_blacklist';
    }
    
    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return [];
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
        return __( 'Blacklist of terms that should not be used for linking', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __( 'Terms that get configured here do not link to others automatically.', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function renderField( $value )
    {
        if ( $value == "" ) {
            $value = [];
        }
        echo  '<select name="' . self::getKey() . '[]" id="' . self::getKey() . '" multiple="multiple"' . OptionsHelper::getDisabler( $this ) . '>' ;
        foreach ( $value as $val ) {
            $term = get_term( $val );
            echo  '<option value="' . $term->term_id . '" selected="selected">' . $term->name . '</option>' ;
        }
        echo  '</select>' ;
    }
    
    /**
     * @inheritdoc
     */
    public function isValidValue( $value )
    {
        return false;
    }

}