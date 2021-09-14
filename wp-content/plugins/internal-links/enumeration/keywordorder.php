<?php

namespace ILJ\Enumeration;

/**
 * Enum for KeywordOrder
 *
 * @package ILJ\Enumerations
 * @since   1.0.4
 */
final class KeywordOrder
{
    const FIFO                 = "keyword_order_fifo";
    const HIGH_WORDCOUNT_FIRST = "keyword_order_high_wordcount_first";
    const LOW_WORDCOUNT_FIRST  = "keyword_order_low_wordcount_first";

    /**
     * Returns all enumeration values
     *
     * @since  1.0.4
     * @return array
     */
    public static function getValues()
    {
        $reflectionClass = new \ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }

    /**
     * Translate enum to natural language
     *
     * @since  1.0.4
     * @param  string $value The enum value
     * @return string
     */
    public static function translate($value)
    {
        switch ($value) {
        case self::FIFO:
            return __('First configured keyword gets linked first', 'internal-links');
        case self::HIGH_WORDCOUNT_FIRST:
            return __('Highest word count gets linked first', 'internal-links');
        case self::LOW_WORDCOUNT_FIRST:
            return __('Lowest word count gets linked first', 'internal-links');
        }
        return 'N/A';
    }
}
