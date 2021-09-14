<?php

namespace ILJ\Helper;

/**
 * Encoding toolset
 *
 * Methods for encoding / decoding of strings for the application
 *
 * @package ILJ\Helper
 * @since   1.0.0
 */
class Encoding
{
    /**
     * Masks (back-)slashes for saving into the postmeta table through WordPress sanitizing methods
     *
     * @since  1.0.0
     * @param  string $regex_string The full regex pattern
     * @return string
     */
    public static function maskSlashes($regex_string)
    {
        return str_replace("\\", "|", $regex_string);
    }

    /**
     * Unmasks sanitized (back-)slashes for retreiving a pattern from the postmeta table
     *
     * @since  1.0.0
     * @param  string $masked_string The masked regex pattern
     * @return string
     */
    public static function unmaskSlashes($masked_string)
    {
        return str_replace("|", "\\", $masked_string);
    }

    /**
     * Translates a pseudo selection rule to its regex pattern
     *
     * @since  1.0.0
     * @param  string $pseudo The given pseudo pattern
     * @return string
     */
    public static function translatePseudoToRegex($pseudo)
    {
        $word_pattern = '(?:\b\w+\b\s*)';
        $regex        = preg_replace('/\s*{(\d+)}\s*/', ' ' . $word_pattern . '{\1} ', $pseudo);
        $regex        = preg_replace('/\s*{\+(\d+)}\s*/', ' ' . $word_pattern . '{\1,} ', $regex);
        $regex        = preg_replace('/\s*{\-(\d+)}\s*/', ' ' . $word_pattern . '{1,\1} ', $regex);
        $regex        = preg_replace('/^\s*(.+?)\s*$/', '\1', $regex);
        return $regex;
    }

    /**
     * Translates a regex pattern to its equivalent pseudo pattern
     *
     * @since  1.0.0
     * @param  string $regex The given regex pattern
     * @return string
     */
    public static function translateRegexToPseudo($regex)
    {
        $pseudo = preg_replace('/\(\?\:\\\b\\\w\+\\\b\\\s\*\)\{(\d+)\}/', '{\1}', $regex);
        $pseudo = preg_replace('/\(\?\:\\\b\\\w\+\\\b\\\s\*\){(\d+),}/', '{+\1}', $pseudo);
        $pseudo = preg_replace('/\(\?\:\\\b\\\w\+\\\b\\\s\*\){(\d+),(\d+)}/', '{-\2}', $pseudo);
        return $pseudo;
    }

    /**
     * Decorates and manipulates a given pattern for matching optimization
     *
     * @since  1.1.5
     * @param  $pattern
     * @return string
     */
    public static function maskPattern($pattern)
    {
        $phrase = '(?<phrase>%2$s%1$s%3$s)';

        $boundary_start = $boundary_end = '\b';

		//has dot:
	    if (strpos($pattern, '.') !== false) $boundary_start = $boundary_end = '';

	    //starting/ending with special char:
	    if ($boundary_start != '' && !preg_match('/^[a-z0-9àâçéèêëîïôûùüÿñæœ]/', strtolower($pattern))) $boundary_start = '';
	    if ($boundary_end != '' && !preg_match('/[a-z0-9àâçéèêëîïôûùüÿñæœ]$/', strtolower($pattern))) $boundary_end = '';

        $masked_pattern = sprintf($phrase, wptexturize($pattern), $boundary_start, $boundary_end);

        return $masked_pattern;
    }

    /**
     * Decodes a JSON string to an array and returns false if not parseable
     *
     * @since 1.2.0
     * @param null $data
     *
     * @return array|bool
     */
    public static function jsonToArray($data)
    {
        $json = @json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return $json;
    }
}
