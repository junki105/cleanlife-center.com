<?php
namespace ILJ\Database\WPML;

use ILJ\Database\Postmeta;

/**
 * Database wrapper wpml table "icl_translations"
 *
 * @package ILJ\Database
 * @since   1.2.0
 */
class Translations
{
    const ILJ_DATABASE_TABLE_WPML_TRANSLATIONS = "icl_translations";

    /**
     * Retrieves all translation relations by type
     *
     * @since 1.2.0
     * @param string $type_prefix The prefix of the type (post, tax)
     *
     * @return array
     */
    public static function getByElementType($type_prefix)
    {
        global $wpdb;

        $query    = sprintf(
            '
            SELECT *
            FROM %s translations
            WHERE element_type LIKE "%s%%"', $wpdb->prefix . self::ILJ_DATABASE_TABLE_WPML_TRANSLATIONS, $type_prefix . '_'
        );

        return $wpdb->get_results($query);
    }
}
