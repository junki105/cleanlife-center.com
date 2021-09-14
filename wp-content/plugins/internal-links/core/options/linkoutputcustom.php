<?php

namespace ILJ\Core\Options;

use  ILJ\Helper\Options as OptionsHelper ;
/**
 * Option: Link template for custom links
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class LinkOutputCustom extends AbstractOption
{
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
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'link_output_custom';
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
    public static function getDefault()
    {
        return esc_html( '<a href="{{url}}">{{anchor}}</a>' );
    }
    
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __( 'Template for the link output (custom links)', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __( 'You can use the placeholders <code>{{url}}</code> for the target and <code>{{anchor}}</code> for the generated anchor text.', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function renderField( $value )
    {
        if ( !\ILJ\ilj_fs()->can_use_premium_code() ) {
            $value = esc_html( self::getDefault() );
        }
        echo  '<input type="text" name="' . self::getKey() . '" id="' . self::getKey() . '" value="' . $value . '" ' . OptionsHelper::getDisabler( $this ) . '/>' ;
    }
    
    /**
     * @inheritdoc
     */
    public function isValidValue( $value )
    {
        return false;
    }

}