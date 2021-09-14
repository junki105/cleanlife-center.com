<?php

namespace ILJ\Helper;

use  ILJ\Type\KeywordList ;
/**
 * Export toolset
 *
 * Methods for data export
 *
 * @since   1.2.0
 * @package ILJ\Helper
 */
class Export
{
    const  ILJ_EXPORT_CSV_FORMAT_HEADLINE = '"%1$s";"%2$s";"%3$s";"%4$s";"%5$s"' ;
    const  ILJ_EXPORT_CSV_FORMAT_LINE = '"%1$d";"%2$s";"%3$s";"%4$s";"%5$s"' ;
    /**
     * Prints the headline for keyword export as CSV
     *
     * @since  1.2.0
     * @param  bool $verbose Permits echo of headline output if true
     * @return string
     */
    public static function printCsvHeadline( $verbose = false )
    {
        $headline = sprintf(
            self::ILJ_EXPORT_CSV_FORMAT_HEADLINE,
            "ID",
            "Type",
            "Keywords (ILJ)",
            "Title",
            "Url"
        );
        if ( !$verbose ) {
            echo  $headline ;
        }
        return $headline;
    }
    
    /**
     * Converts all index relevant posts to CSV data
     *
     * @since  1.2.0
     * @param  bool $empty   Flag for output of empty entries
     * @param  bool $verbose Permits echo of CSV output if true
     * @return string
     */
    public static function printCsvPosts( $empty, $verbose = false )
    {
        $csv = '';
        $posts = IndexAsset::getPosts();
        foreach ( $posts as $post ) {
            $keyword_list = KeywordList::fromMeta( $post->ID, 'post' );
            if ( $empty && !$keyword_list->getCount() ) {
                continue;
            }
            $csv_curr = PHP_EOL;
            $csv_curr .= sprintf(
                self::ILJ_EXPORT_CSV_FORMAT_LINE,
                $post->ID,
                'post',
                $keyword_list->encoded( false ),
                $post->post_title,
                get_permalink( $post->ID )
            );
            if ( !$verbose ) {
                echo  $csv_curr ;
            }
            $csv .= $csv_curr;
        }
        return $csv;
    }

}