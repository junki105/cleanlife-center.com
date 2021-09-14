<?php
/*
 * This file is part of the Inpsyde BackWpUp package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Exception;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ExceptionLinkHelper
 *
 * @internal
 * @package Inpsyde\Restore
 */
class ExceptionLinkHelper
{
    /**
     * @param TranslatorInterface $translation
     * @param string $message
     * @param string $message_links
     *
     * @return string
     */
    public static function translateWithAppropiatedLink(
        TranslatorInterface $translation,
        $message,
        $message_links
    ) {

        $locale = self::region($translation);
        $links_for_messages = self::links_for_messages();

        if (!isset($links_for_messages[$message_links][$locale])) {
            return $message;
        }

        return $message
            . ' ' . $translation->trans('see the') . ' '
            . self::link_markup(
                $links_for_messages[$message_links][$locale],
                $translation->trans('documentation')
            );
    }

    /**
     * @param TranslatorInterface $translation
     *
     * @return string
     */
    private static function region(TranslatorInterface $translation)
    {
        $locale = $translation->getLocale();
        $canonicalized_locale = str_replace('-', '_', $locale);
        $primary_languageIndex = strpos(
            $canonicalized_locale,
            '_'
        ) ?: strlen($canonicalized_locale);

        $region = substr($canonicalized_locale, 0, $primary_languageIndex);

        return $region;
    }

    /**
     * @param $link
     * @param $label
     *
     * @return string
     */
    private static function link_markup($link, $label)
    {
        return '<a href="' . htmlentities($link) . '" target="_blank" rel="noopener noreferer">' . $label . '</a>';
    }

    /**
     * @return array|null
     */
    private static function links_for_messages()
    {
        static $message_links = null;

        if (null === $message_links) {
            $message_links = array(
                'DIR_CANNOT_BE_CREATED' => array(
                    'en' => 'https://backwpup.com/docs/restore-directory-cannot-be-created/',
                    'de' => 'https://backwpup.de/doku/restore-dekomprimierungsverzeichnis-kann-nicht-erstellt-werden/',
                ),
                'ARCHIVE_RESTORE_PATH_CANNOT_BE_SET' => array(
                    'en' => 'https://backwpup.com/docs/archive-path-restore-path-not-set/',
                    'de' => 'https://backwpup.de/doku/archivpfad-und-oder-restorepfad-ist-nicht-festgelegt/',
                ),
                'DATABASE_CONNECTION_PROBLEMS' => array(
                    'en' => 'https://backwpup.com/docs/restore-cannot-connect-mysql-database/',
                    'de' => 'https://backwpup.de/doku/verbindung-zur-mysql-datenbank-nicht-moeglich-1045-zugriff-verweigert-fuer-benutzer-localhost-mit-passwort-nein/',
                ),
                'BZIP2_CANNOT_BE_DECOMPRESSED' => array(
                    'en' => 'https://backwpup.com/docs/convert-bzip2-file-zip/',
                    'de' => 'https://backwpup.de/doku/bzip2-nach-zip-konvertieren/',
                ),
            );
        }

        return $message_links;
    }
}
