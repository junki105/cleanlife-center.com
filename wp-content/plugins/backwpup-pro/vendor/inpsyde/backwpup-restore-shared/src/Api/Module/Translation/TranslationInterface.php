<?php

namespace Inpsyde\Restore\Api\Module\Translation;

/**
 * Interface TranslationInterface
 *
 * @package Inpsyde\Api\Module\Translation
 */
interface TranslationInterface
{

    /**
     * Get locale to use by the translator object
     *
     * @return string
     */
    public function get_locale();

    /**
     * Get language dir where to search for language files
     *
     * @return string
     */
    public function get_lang_dir();

    /**
     * Setup the translator object ready for the trans() call
     *
     * @param string $translator_class The class name used to create the
     *                                                                             instance.
     * @param \Symfony\Component\Translation\Loader\PoFileLoader $file_loader The class used to load the po.
     * @param string $ext The extension of the file.
     *
     * @return object
     */
    public function get_translator($translator_class, $file_loader, $ext);
}
