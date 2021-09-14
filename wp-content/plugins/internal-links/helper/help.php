<?php

namespace ILJ\Helper;

/**
 * Documentation toolset
 *
 * Methods for generation of help and documentation ressources
 *
 * @since   1.1.3
 * @package ILJ\Helper
 */
class Help
{
    /**
     * @since 1.1.3
     * @var   string
     */
    public static  $doc_url = "https://internallinkjuicer.com/docs/%s?utm_source=%s&utm_medium=%s&utm_campaign=%s" ;
    /**
     * Returns the plain url for manual pages
     *
     * @since 1.1.3
     * @param string $docpath The path on the docs archive
     * @param string $anchor  The anchored link within the doc page to jump to
     * @param string $medium  The tracking medium
     * @param string $source  The tracking source
     *
     * @return string
     */
    public static function getLinkUrl(
        $docpath,
        $anchor,
        $medium,
        $source
    )
    {
        $campaign = "plugin";
        $url = sprintf(
            self::$doc_url,
            $docpath,
            urlencode( $source ),
            urlencode( $medium ),
            urlencode( $campaign )
        );
        return ( $anchor && $anchor != '' ? $url . '#' . $anchor : $url );
    }
    
    /**
     * Generates and returns the help link for manual pages
     *
     * @since  1.1.3
     * @param  string $docpath The path on the docs archive
     * @param  string $anchor  The anchored link within the doc page to jump to
     * @param  string $medium  The tracking medium
     * @return string
     */
    public static function getOptionsLink( $docpath, $anchor, $medium )
    {
        $url = self::getLinkUrl(
            $docpath,
            $anchor,
            $medium,
            'settings'
        );
        return '<a href="' . $url . '" class="help tip" rel="noopener" target="_blank" title="' . __( 'Get help', 'internal-links' ) . '"><span class="dashicons dashicons-editor-help"></span></a>';
    }

}