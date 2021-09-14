<?php

namespace Inpsyde\Restore\Api\Module\Manifest;

use Symfony\Component\Translation\Translator;
use Inpsyde\Restore\Api\Module\Manifest\Exception\ManifestFileException;
use Inpsyde\Restore\Api\Module\Registry;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * ManifestFile Class for handling operations regarding manifest.json in backups
 *
 * @package Inpsyde\Restore\Api\Module\Manifest
 */
class ManifestFile
{
    /**
     * @var array content of manifest.json decoded from json into php array
     */
    private $manifest;

    /**
     * @var Registry object holding general information for the app
     */
    private $registry;

    /**
     * @var Translator|null
     */
    private $translation;

    /**
     * ManifestFile constructor.
     *
     * @param Registry $registry Object holding general information for the app.
     * @param TranslatorInterface $translation
     */
    public function __construct(Registry $registry, TranslatorInterface $translation)
    {
        $this->registry = $registry;
        $this->translation = $translation;
    }

    /**
     * Setter method to set manifest file from extracted backup
     *
     * @param string $file path to manifest file.
     *
     * @return void
     * @throws ManifestFileException If file is not readable.
     *
     */
    public function set_manifest_file($file)
    {
        if (!is_readable($file)) {
            throw new ManifestFileException($this->translation->trans('Manifest file not readable'));
        }

        $this->manifest = json_decode(file_get_contents($file));
    }

    /**
     * Fetch dumpfile from manifest file
     *
     * @return string The file name or empty string
     * @throws ManifestFileException If the manifest is not a valid object.
     *
     */
    public function get_dump_file()
    {
        if (!isset($this->manifest)) {
            throw new ManifestFileException(
                $this->translation->trans(
                    'Manifest file not found. Please check the file exists within the backup and extraction folder.'
                )
            );
        }

        // Some job settings may use an invalid dump extension such as "mysqldump".
        // So, if we found the `sql` substring in the database dump type we assue the extension is `.sql`.
        $dump_ext = $this->manifest->job_settings->dbdumptype;
        if (false !== strpos($this->manifest->job_settings->dbdumptype, 'sql')) {
            $dump_ext = 'sql';
        }

        $dump_name = $this->manifest->job_settings->dbdumpfile;
        $dump_comp = $this->manifest->job_settings->dbdumpfilecompression;

        if ($dump_ext === 'xml') {
            return '';
        }

        $dump_file = $dump_name . '.' . $dump_ext . $dump_comp;

        return $dump_file;
    }

    /**
     * Helper method for finding charset, which is used in Manifest
     *
     * @throws ManifestFileException If it's not possible to retrieve the database dump file.
     *
     * @return string DB Charset from job_settings or empty string if not set
     */
    public function get_charset()
    {
        // Firstly look if charset is set in manifest.json.
        // If that fails try to find the charset in SQL dump file.
        // Each MySQL dump should contain a comment, which states the charset.
        if (!empty($this->manifest->job_settings->dbdumpdbcharset)) {
            return $this->manifest->job_settings->dbdumpdbcharset;
        }

        $file = $this->registry->dbdumpfile;

        if (empty($file)) {
            throw new ManifestFileException($this->translation->trans('No DB Dump File found in Registry.'));
        }

        // Fetch first 1000 chars of sql dump and look for 'SET NAMES'
        $content = file_get_contents($file, null, null, 0, 1000); // phpcs:ignore
        $start = strpos($content, 'SET NAMES');
        $charset_comment = substr($content, $start, 20);
        $charset_comment_array = array();

        if (false !== $charset_comment) {
            $charset_comment_array = explode(' ', $charset_comment);
        }

        if (!isset($charset_comment_array[2])) {
            return '';
        }

        return $charset_comment_array[2];
    }
}
