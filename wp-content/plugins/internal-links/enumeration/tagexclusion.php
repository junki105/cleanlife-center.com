<?php

namespace ILJ\Enumeration;

/**
 * Enum for TagExclusion
 *
 * @package ILJ\Enumerations
 * @since   1.1.1
 */
final class TagExclusion
{
    const  HEADLINE = 'tag_headlines' ;
    const  STRONG = 'tag_strong' ;
    const  DIV = 'tag_div' ;
    const  TABLE = 'tag_table' ;
    const  CAPTION = 'tag_caption' ;
    const  ORDERED_LIST = 'tag_ordered_list' ;
    const  UNORDERED_LIST = 'tag_unordered_list' ;
    const  BLOCKQUOTE = 'tag_blockquote' ;
    const  ITALIC = 'tag_italic' ;
    const  CITE = 'tag_cite' ;
    const  CODE = 'tag_code' ;
    /**
     * Returns all enumeration values
     *
     * @since  1.1.1
     * @return array
     */
    public static function getValues()
    {
        $reflectionClass = new \ReflectionClass( static::class );
        return $reflectionClass->getConstants();
    }
    
    /**
     * Translate enum to natural language
     *
     * @since  1.1.1
     * @param  string $value The enum value
     * @return string
     */
    public static function translate( $value )
    {
        switch ( $value ) {
            case self::HEADLINE:
                return htmlentities( __( 'Headlines', 'internal-links' ) . ' (<h1-6>)' );
            case self::STRONG:
                return htmlentities( __( 'Strong text', 'internal-links' ) . ' (<strong>, <b>)' );
            case self::DIV:
                return htmlentities( __( 'Div container', 'internal-links' ) . ' (<div>)' );
            case self::TABLE:
                return htmlentities( __( 'Tables', 'internal-links' ) . ' (<table>)' );
            case self::CAPTION:
                return htmlentities( __( 'Image captions', 'internal-links' ) . ' (<figcaption>)' );
            case self::ORDERED_LIST:
                return htmlentities( __( 'Ordered lists', 'internal-links' ) . ' (<ol>)' );
            case self::UNORDERED_LIST:
                return htmlentities( __( 'Unordered lists', 'internal-links' ) . ' (<ul>)' );
            case self::BLOCKQUOTE:
                return htmlentities( __( 'Blockquotes', 'internal-links' ) . ' (<blockquote>)' );
            case self::ITALIC:
                return htmlentities( __( 'Italic text', 'internal-links' ) . ' (<em>, <i>)' );
            case self::CITE:
                return htmlentities( __( 'Inline quotes', 'internal-links' ) . ' (<cite>)' );
            case self::CODE:
                return htmlentities( __( 'Sourcecode', 'internal-links' ) . ' (<code>)' );
        }
        return 'N/A';
    }
    
    /**
     * Returns the regex for the exclusion
     *
     * @since  1.1.1
     * @param  string $deputy The name of the html area
     * @return string|bool
     */
    public static function getRegex( $deputy )
    {
        switch ( $deputy ) {
            case self::HEADLINE:
                return '/(?<parts><h[1-6].*>.*<\\/h[1-6]>)/sU';
            case self::STRONG:
                return '/(?<parts><strong.*>.*<\\/strong>|<b.*>.*<\\/b>)/sU';
        }
        return false;
    }

}