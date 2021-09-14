<?php
namespace ILJ\Helper;

use ILJ\Type\Ruleset;

/**
 * Replacement helper
 *
 * Handles the operations for removing unwanted elements from a content
 *
 * @package ILJ\Helper
 * @since   1.0.0
 */
class Replacement
{
    const ILJ_FILTER_EXCLUDE_TEXT_PARTS = 'ilj_exclude_text_parts';

    /**
     * Masks areas in the document, which should net get used for further linking anymore
     *
     * @since  1.0.0
     * @param  string &$content The content where the rules will get applied on
     * @return Ruleset
     */
    public static function mask(&$content)
    {
        $replace_ruleset = new Ruleset();

        $search_parts = [
            // exclude all sensible html parts:
            '/(?<parts><a.*>.*<\/a>)/sU',
            '/(?<parts><script.*>.*<\/script>)/sU',
            '/(?<parts><style.*>.*<\/style>)/sU'
        ];

        /**
         * Filters all parts of content that dont get used for applying link index
         *
         * @since 1.0.0
         * @param array  $search_parts  All parts as regex that get excluded
         */
        $search_parts = apply_filters(self::ILJ_FILTER_EXCLUDE_TEXT_PARTS, $search_parts);

        if (!is_array($search_parts)) {
            $search_parts = [];
        }

        $search_parts[] = '/(?<parts><.*>)/sU';

        foreach ($search_parts as $search_part) {
            preg_match_all($search_part, $content, $matches);
            if (isset($matches['parts'])) {
                foreach ($matches['parts'] as $part) {
                    $link_id = " " . 'ilj_' . uniqid('', true) . " ";
                    $content = str_replace($part, $link_id, $content);
                    $replace_ruleset->addRule($link_id, $part);
                }
            }
            unset($matches);
        }

        return $replace_ruleset;
    }
}
