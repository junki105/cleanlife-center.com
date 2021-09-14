<?php

namespace Inpsyde\Restore\Api\Module;

use Exception;
use Inpsyde\Restore\Api\Exception\FileSystemException;
use Inpsyde\Restore\Utils\SanitizePath;
use InvalidArgumentException;

/**
 * Class Registry
 *
 * Persistent Registry. Saves Data in an PHP serialized file.
 *
 * @author  ap
 * @package Inpsyde\Restore\Api\Module
 * @since   1.0.0
 *
 * @property string $dbhost             Database connection host
 * @property string $dbuser             Database connection user
 * @property string $dbpassword         Database connection user
 * @property string $dbname             Database name
 * @property string $dbcharset          Database connection charset
 * @property string $dbdumpfile         File to import to Database
 * @property array $dbdumppos          Position where the import at moment
 * @property string $locale             Language locale, e.g. de_DE
 * @property string $lang_dir           Path to language directory
 * @property int $migration_progress migration_progress
 * @property string $restore_strategy   states what strategy is chosen (DB only restore, complete restore)
 * @property string $project_root       absolute path to project root
 * @property string $project_temp       absolute path to project temp folder
 * @property string $uploaded_file      file name of upload
 * @property string $upload_dir         absolute path to upload directory
 * @property string $extract_folder     absolute path to decompressed backup directory
 * @property string $manifest_file      path to manifest.json
 * @property array $extra_files        array holds files to ignore during file restore
 * @property array $restore_list       list of directories seen so far to restore.
 * @property array $restore_finished   array where jobs can mark themselves as finished
 * @property string $uploads_folder     The uploads folder within the restore dir.
 */
class Registry
{
    /**
     * File Path
     *
     * @var string
     */
    private $filePath;

    /**
     * Registry
     *
     * @var array Internal registry
     */
    private $registry = [];

    /**
     * Registry construct
     *
     * @param string $path The Path to the save file.
     * @throws InvalidArgumentException
     */
    public function __construct($path)
    {
        $sanitizedPath = SanitizePath::sanitize($path);

        if ($sanitizedPath !== $path) {
            throw new InvalidArgumentException(
                'Given Path seems corrupted when construct the registry instance.'
            );
        }

        $this->filePath = $path;
    }

    /**
     * Init
     *
     * Perform the basic tasks to make the registry works properly
     *
     * @return void
     * @throws FileSystemException
     */
    public function init()
    {
        if (file_exists($this->filePath)) {
            $data = file_get_contents($this->filePath);
            $unserializedData = unserialize($data);
            $this->registry = is_array($unserializedData) ? $unserializedData : [];

            return;
        }

        if (!defined('FS_CHMOD_DIR')) {
            define('FS_CHMOD_DIR', 0775);
        }

        if (!file_exists(dirname($this->filePath))) {
            // Attempt to create the directories if not exists.
            mkdir(dirname($this->filePath), FS_CHMOD_DIR, true);
        }

        // If file doesn't exists let's try to create it.
        $handle = fopen($this->filePath, 'a+');

        // If file cannot be created log it for support.
        if (!$handle) {
            throw new FileSystemException(
                'Cannot possible to create the registry file. Restore will not work properly.'
            );
        }

        // Release the resource.
        fclose($handle);
    }

    /**
     * Save the registry to the save file.
     *
     * @throws FileSystemException In case the registry cannot be saved.
     *
     * return void
     */
    public function save()
    {
        $data = serialize($this->registry); // phpcs:ignore

        // When writing get an exclusive lock on the file to avoid conflicts
        if (false === file_put_contents($this->filePath, $data, LOCK_EX)) { // phpcs:ignore
            throw new FileSystemException(
                sprintf(
                    'Could not write Registry file to %s',
                    $this->filePath
                )
            );
        }
    }

    /**
     * Get a value from the registry
     *
     * @param string $key The registry key.
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->registry)) {
            return $this->registry[$key];
        }

        return null;
    }

    /**
     * Add/Update a value in the registry.
     *
     * @param string $key The registry key.
     * @param mixed $value Value associated with the key.
     *
     * @return void
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function __set($key, $value)
    {
        $this->registry[$key] = $value;

        $this->save();
    }

    /**
     * Check if value is set in registry.
     *
     * @param string $name The registry key.
     *
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->registry);
    }

    /**
     * Has key
     *
     * Check if registry has a specific key set.
     *
     * @param string $key name of registry key.
     *
     * @return bool
     */
    public function has($key)
    {
        return (bool)$this->__isset($key);
    }

    /**
     * Delete key
     *
     * Remove a entry from the registry
     *
     * @param string $key The registry key.
     *
     * @return void
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function delete($key)
    {
        unset($this->registry[$key]);

        $this->save();
    }

    /**
     * Add file to blacklist
     *
     * Helper function to add file names to a blacklist for file restore
     *
     * @param string $file_name The file name to exclude.
     *
     * @return void
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function add_to_blacklist($file_name)
    {
        if (!isset($this->registry['extra_files'])) {
            $this->registry['extra_files'] = [];
        }

        array_push($this->registry['extra_files'], $file_name);

        $this->save();
    }

    /**
     * Store the finished jobs
     *
     * @param string $job_name The job to store.
     *
     * @return void
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function finish_job($job_name)
    {
        if (!isset($this->registry['restore_finished'])
            || !is_array($this->registry['restore_finished'])
        ) {
            $this->registry['restore_finished'] = [];
        }

        $this->registry['restore_finished'][$job_name] = 'finished';

        $this->save();
    }

    /**
     * Helper function to add dirs to restore_list
     *
     * @param string $dir The dir to store in the registry.
     *
     * @return void
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function add_to_restore_list($dir)
    {
        if (!isset($this->registry['restore_list']) || !is_array($this->registry['restore_list'])) {
            $this->registry['restore_list'] = [];
        }

        array_push($this->registry['restore_list'], $dir);

        $this->save();
    }

    /**
     * Helper function to remove first element in restore_list
     *
     * @return string The directory to restore
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function next_dir_in_restore_list()
    {
        if (!isset($this->registry['restore_list']) || !is_array($this->registry['restore_list'])) {
            return '';
        }

        $current = array_shift($this->registry['restore_list']);

        $this->save();

        return $current;
    }

    /**
     * Update the migration progress.
     *
     * @param int $percent The progress percentage.
     *
     * @return void
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function update_progress($percent)
    {
        $this->registry['migration_progress'] = $percent;

        $this->save();
    }

    /**
     * Reset registry to start a new restore process.
     *
     * A hard and soft reset is possible. A hard one will delete the complete registry and the app will
     * start from scratch. A soft reset will delete only information regarding the last restore. I.e.
     * information about language translation, etc. are kept b/c this does not influence the restore itself.
     *
     * @return object Registry for concatenation
     * @throws Exception In case the registry cannot be saved.
     *
     */
    public function reset_registry()
    {
        @copy($this->filePath, "{$this->filePath}.bkp");

        $this->registry = [];
        $this->save();

        return $this;
    }

    /**
     * Is Restore Finished?
     *
     * @return bool True on success, false on error
     */
    public function is_restore_finished()
    {
        if (!isset($this->registry['restore_strategy'], $this->registry['restore_finished'])) {
            return false;
        }

        $jobs = 1;
        if ('complete restore' === $this->registry['restore_strategy']) {
            $jobs = 2;
        }

        return !($jobs !== count($this->registry['restore_finished']));
    }
}
