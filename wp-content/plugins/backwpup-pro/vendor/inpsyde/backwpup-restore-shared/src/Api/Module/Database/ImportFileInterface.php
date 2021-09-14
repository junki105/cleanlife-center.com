<?php

namespace Inpsyde\Restore\Api\Module\Database;

/**
 * Interface ImportFileInterface
 *
 * @package Inpsyde\Api\Module\Database
 */
interface ImportFileInterface
{

    /**
     * File to import
     *
     * @param string $file The file to read.
     *
     * @return bool
     * @throws \Inpsyde\Restore\Api\Module\Database\Exception\DatabaseFileException If problems to read the file.
     *
     */
    public function set_import_file($file);

    /**
     * Get Position
     *
     * Get Position on import file for store and later going on.
     *
     * @return array An associative array containing the 'pos' value
     * @throws \Inpsyde\Restore\Api\Module\Database\Exception\DatabaseFileException If the current position within
     *                                                                                the file cannot be read.
     *
     */
    public function get_position();

    /**
     * Set position on import file
     *
     * @param array $position The position information to set the pointer for the file.
     *
     * @return bool
     * @throws \Inpsyde\Restore\Api\Module\Database\Exception\DatabaseFileException If something went wrong reading
     *                                                                                the file.
     *
     */
    public function set_position(array $position);

    /**
     * Get query to import
     *
     * @return string The query string
     */
    public function get_query();

    /**
     * Get File Size
     *
     * @return int The size of the file
     */
    public function get_file_size();
}
