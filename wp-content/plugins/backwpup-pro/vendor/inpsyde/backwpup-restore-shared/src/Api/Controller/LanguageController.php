<?php

namespace Inpsyde\Restore\Api\Controller;

use Inpsyde\Restore\Api\Module\Registry;

/**
 * Class LanguageController
 *
 * @package Inpsyde\Restore\Api\Controller
 */
class LanguageController
{
    /**
     * @var Registry Global Registry
     */
    private $registry;

    /**
     * LanguageController construct
     *
     * @param Registry $registry registry object.
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Method to set new locale and saves registry
     *
     * @param string $locale New locale string.
     *
     * @return bool True on success, false if the locale isn't a valid one.
     */
    public function switch_language_action($locale)
    {
        if (!$this->is_valid_locale($locale)) {
            return false;
        }

        $this->registry->locale = $locale;

        return true;
    }

    /**
     * Sanitize locale
     *
     * Props to @ocean90.
     *
     * @link https://core.trac.wordpress.org/ticket/28303#comment:11
     *
     * @param string $locale Provided locale.
     *
     * @return int|false 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred.
     */
    private function is_valid_locale($locale)
    {
        return preg_match('/(?:(.+)-)?([a-z]{2,3}(?:_[A-Z]{2})?(?:_[a-z]+)?)/', $locale);
    }
}
