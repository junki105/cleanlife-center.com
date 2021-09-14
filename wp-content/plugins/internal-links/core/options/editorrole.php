<?php

namespace ILJ\Core\Options;

use  ILJ\Helper\Capabilities ;
use  ILJ\Helper\Options as OptionsHelper ;
/**
 * Option: Editor role
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class EditorRole extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'editor_role';
    }
    
    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return 'administrator';
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
    public function getTitle()
    {
        return __( 'Minimum required user role for editing keywords', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __( 'The minimum required capability to edit keywords.', 'internal-links' );
    }
    
    /**
     * @inheritdoc
     */
    public function renderField( $value )
    {
        if ( !\ILJ\ilj_fs()->is__premium_only() || !\ILJ\ilj_fs()->can_use_premium_code() ) {
            $value = self::getDefault();
        }
        echo  '<select name="' . self::getKey() . '" id="' . self::getKey() . '"' . OptionsHelper::getDisabler( $this ) . '>' ;
        Capabilities::rolesDropdown( $value );
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