<?php
namespace SafeRegex;

use CleanRegex\Internal\Arguments;
use SafeRegex\Constants\PregConstants;
use SafeRegex\Guard\GuardedExecution;

class preg
{
    public static function match($pattern, $subject, array &$matches = null, $flags = 0, $offset = 0)
    {
        return GuardedExecution::invoke('preg_match', function () use ($offset, $flags, &$matches, $subject, $pattern) {
            return @preg_match($pattern, $subject, $matches, $flags, $offset);
        });
    }

    public static function match_all($pattern, $subject, array &$matches = null, $flags = PREG_PATTERN_ORDER, $offset = 0)
    {
        return GuardedExecution::invoke('preg_match_all', function () use ($offset, $flags, &$matches, $subject, $pattern) {
            return @preg_match_all($pattern, $subject, $matches, $flags, $offset);
        });
    }

    public static function replace($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        return GuardedExecution::invoke('preg_replace', function () use ($limit, $subject, $replacement, $pattern, &$count) {
            return @preg_replace($pattern, $replacement, $subject, $limit, $count);
        });
    }

    public static function replace_callback($pattern, callable $callback, $subject, $limit = -1, &$count = null)
    {
        return GuardedExecution::invoke('preg_replace_callback', function () use ($pattern, $limit, $subject, $callback, &$count) {
            return @preg_replace_callback($pattern, $callback, $subject, $limit, $count);
        });
    }

    public static function replace_callback_array($patterns_and_callbacks, $subject, $limit = -1, &$count)
    {
        return GuardedExecution::invoke('preg_replace_callback_array', function () use ($patterns_and_callbacks, $subject, $limit, &$count) {
            return @preg_replace_callback_array($patterns_and_callbacks, $subject, $limit, $count);
        });
    }

    public static function filter($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        return GuardedExecution::invoke('preg_filter', function () use ($pattern, $replacement, $subject, $limit, &$count) {
            return @preg_filter($pattern, $replacement, $subject, $limit, $count);
        });
    }

    public static function split($pattern, $subject, $limit = -1, $flags = 0)
    {
        return GuardedExecution::invoke('preg_split', function () use ($pattern, $subject, $limit, $flags) {
            return @preg_split($pattern, $subject, $limit, $flags);
        });
    }

    public static function grep($pattern, array $input, $flags = 0)
    {
        return GuardedExecution::invoke('preg_grep', function () use ($flags, $input, $pattern) {
            return @preg_grep($pattern, $input, $flags);
        });
    }

    public static function quote($string, $delimiter = null)
    {
        return GuardedExecution::invoke('preg_quote', function () use ($delimiter, $string) {
            return @preg_quote($string, $delimiter);
        });
    }

    /**
     * @return int
     */
    public static function last_error()
    {
        return preg_last_error();
    }

    /**
     * @return string
     */
    public static function last_error_constant()
    {
        return (new PregConstants())->getConstant(preg_last_error());
    }

    /**
     * @param int $error
     * @return string
     */
    public static function error_constant($error)
    {
        Arguments::integer($error);

        return (new PregConstants())->getConstant($error);
    }
}
