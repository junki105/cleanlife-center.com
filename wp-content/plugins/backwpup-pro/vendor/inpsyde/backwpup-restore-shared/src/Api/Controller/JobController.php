<?php

namespace Inpsyde\Restore\Api\Controller;

use BackWPup_Destination_Downloader_Factory;
use Inpsyde\Restore\Api\Decompress\Exception\DecompressException;
use Inpsyde\Restore\Api\Module\Decryption\Exception\DecryptException;
use Inpsyde\Restore\Api\Module\Database\DatabaseTypeFactory;
use Inpsyde\Restore\Api\Module\Decryption\Decrypter;
use Inpsyde\Restore\Api\Module\Download\Exception\DownloadException;
use Inpsyde\Restore\Api\Module\ImportInterface;
use Inpsyde\Restore\Api\Module\Decompress\Decompressor;
use Inpsyde\Restore\Api\Module\Database\Exception as DatabaseException;
use Inpsyde\Restore\Api\Module\Manifest\Exception\ManifestFileException;
use Inpsyde\Restore\Api\Module\Manifest\ManifestFile;
use Inpsyde\Restore\Api\Module\Registry;
use Inpsyde\Restore\Api\Module\RegistryException;
use Inpsyde\Restore\Api\Module\Restore\Exception\RestorePathException;
use Inpsyde\Restore\Api\Module\Restore\RestoreFiles;
use Inpsyde\Restore\Api\Module\Session\NotificableStorableSessionInterface;
use Inpsyde\Restore\Api\Module\Session\Session;
use Inpsyde\Restore\Api\Module\Upload\BackupUpload;
use Inpsyde\Restore\DestinationFactory;
use Inpsyde\Restore\Api\Exception\ExceptionLinkHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;

/**
 * Class JobController
 *
 * @author  Hans-Helge Buerger
 * @package Inpsyde\Restore\Api\Controller
 */
class JobController
{

    /**
     * Registry
     *
     * @var \Inpsyde\Restore\Api\Module\Registry Registry object
     */
    private $registry;

    /**
     * Translator
     *
     * @var \Symfony\Component\Translation\Translator Translator object
     */
    private $translator;

    /**
     * Backup Upload
     *
     * @var \Inpsyde\Restore\Api\Module\Upload\BackupUpload file upload object
     */
    private $backup_upload;

    /**
     * Database Factory
     *
     * @var \Inpsyde\Restore\Api\Module\Database\DatabaseTypeFactory db factory object
     */
    private $database_factory;

    /**
     * Decompress
     *
     * @var Decompressor Instance used to decompress the archive
     */
    private $decompress;

    /**
     * Manifest
     *
     * @var ManifestFile The instance of the manifest
     */
    private $manifest;

    /**
     * Session
     *
     * @var Session To store info about the actions.
     */
    private $session;

    /**
     * Import Interface
     *
     * @var ImportInterface
     */
    private $database_importer;

    /**
     * Restore Interface
     *
     * @var RestoreFiles
     */
    private $restoreFiles;

    /**
     * @var Decrypter
     */
    private $decrypter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * JobController constructor.
     *
     * @param Registry $registry
     * @param Translator $translator
     * @param LoggerInterface $logger
     * @param Decompressor $decompress
     * @param ManifestFile $manifest
     * @param NotificableStorableSessionInterface $session
     * @param BackupUpload $backup_upload
     * @param DatabaseTypeFactory $database_factory
     * @param ImportInterface $database_importer
     * @param RestoreFiles $restoreFiles
     * @param Decrypter $decrypter
     */
    public function __construct(
        Registry $registry,
        Translator $translator,
        LoggerInterface $logger,
        Decompressor $decompress,
        ManifestFile $manifest,
        NotificableStorableSessionInterface $session,
        BackupUpload $backup_upload,
        DatabaseTypeFactory $database_factory,
        ImportInterface $database_importer,
        RestoreFiles $restoreFiles,
        Decrypter $decrypter
    ) {

        $this->registry = $registry;
        $this->translator = $translator;
        $this->decompress = $decompress;
        $this->manifest = $manifest;
        $this->session = $session;
        $this->backup_upload = $backup_upload;
        $this->database_factory = $database_factory;
        $this->database_importer = $database_importer;
        $this->restoreFiles = $restoreFiles;
        $this->decrypter = $decrypter;
        $this->logger = $logger;
    }

    /**
     * Hello World Method. Used as fallback in ajax.php
     *
     * @return string
     */
    public function index()
    {
        return 'Crafted by Inpsyde';
    }

    /**
     * Upload Action
     *
     * Trigger the upload process and save path to registry.
     *
     * @throws \Exception In case isn't possible to upload the file.
     *
     * @return string The output if the upload process
     */
    public function upload_action()
    {
        $response = $this->backup_upload->run();
        $this->registry->uploaded_file = $this->backup_upload->get_abs_file_path();

        return $response;
    }

    /**
     * Download Archive
     *
     * @param $job_id
     * @param $service_name
     * @param $source_file_path
     * @param $local_file_path
     * @throws DownloadException
     */
    public function download_action($job_id, $service_name, $source_file_path, $local_file_path)
    {
        // The file may be already into the server and we have an absolute file path.
        if (empty($source_file_path) && file_exists($local_file_path)) {
            return;
        }

        if (empty($service_name)) {
            throw new DownloadException($this->translator->trans('Service cannot be empty.'));
        }
        if (empty($job_id)) {
            throw new DownloadException($this->translator->trans('Job ID must not be empty or 0.'));
        }
        if (empty($local_file_path)) {
            throw new DownloadException($this->translator->trans('Local file path cannot be empty.'));
        }

        if (!class_exists('BackWPup_Destination_Downloader_Factory')) {
            throw new DownloadException(
                $this->translator->trans(
                    'Errors occurred while downloading. Destination may not created.'
                )
            );
        }

        // Set service and job_id in registry for future use
        $this->registry->service_name = $service_name;
        $this->registry->job_id = $job_id;

        $factory = new BackWPup_Destination_Downloader_Factory();
        $downloader = $factory->create(
            $service_name,
            $job_id,
            $source_file_path,
            $local_file_path
        );

        $downloader->download_by_chunks();
    }

    /**
     * Decompress Upload Action
     *
     * @throws \RuntimeException In case the file manifest.json doesn't exists.
     * @throws \Exception If something goes wrong with the decompression.
     *
     * @param string $file_path The path of the file to decompress. Optional, default to `uploaded_file` in registry.
     */
    public function decompress_upload_action($file_path = '')
    {
        $this->ensure_uploaded_file($file_path);

        $file_ext = pathinfo($this->registry->uploaded_file, PATHINFO_EXTENSION);

        $this->throw_error_if_bz2($file_ext);

        $may_decrypted = $this->decrypter->maybe_decrypted($this->registry->uploaded_file);
        if ($may_decrypted) {
            throw new DecryptException(DecryptController::STATE_NEED_DECRYPTION_KEY);
        }

        $this->decompress->run();

        if (!$this->is_manifest_readable()) {
            throw new ManifestFileException(
                $this->translator->trans('Sorry but only backups made using BackWPup plugin can be restored.')
            );
        }

        $this->session->success($this->translator->trans('Extraction Successful'));
    }

    /**
     * Check if the manifest json is readable or not
     *
     * @return bool
     */
    private function is_manifest_readable()
    {
        $manifest_file = isset($this->registry->manifest_file) ? $this->registry->manifest_file : '';

        return $manifest_file && is_readable($manifest_file);
    }

    /**
     * Db Test Action
     *
     * @param string $db_settings The database settings in json format.
     *
     * @return true on success. Throws error on failure
     * @throws DatabaseException\DatabaseFileException In case the dump file was not set properly.
     * @throws ManifestFileException If the manifest is not a valid object.
     *
     */
    public function db_test_action($db_settings)
    {
        $this->manifest->set_manifest_file($this->registry->manifest_file);

        $dumpfile = $this->manifest->get_dump_file();

        if (!$dumpfile) {
            throw new DatabaseException\DatabaseFileException(
                $this->translator->trans(sprintf('Sql file %1$s does not exist', $dumpfile))
            );
        }

        $db = json_decode($db_settings);
        $this->registry->dbhost = isset($db->dbhost) ? $db->dbhost : '';
        $this->registry->dbname = isset($db->dbname) ? $db->dbname : '';
        $this->registry->dbuser = isset($db->dbuser) ? $db->dbuser : '';
        $this->registry->dbpassword = isset($db->dbpassword) ? $db->dbpassword : '';

        // Add SQL dump file to $registry->extra_files to ignore it during file restore
        $this->registry->add_to_blacklist($dumpfile);

        // phpcs:disable PSR2.Methods.FunctionCallSignature.Indent
        $this->registry->dbdumpfile = rtrim(
                $this->registry->extract_folder,
                DIRECTORY_SEPARATOR
            ) . DIRECTORY_SEPARATOR . $dumpfile;
        // phpcs:enable

        $this->registry->dbcharset = !empty($db->dbcharset)
            ? $db->dbcharset
            : $this->manifest->get_charset();

        $db = $this->database_factory->database_type();
        $db->connect();

        return true;
    }

    /**
     * Restore Dir
     *
     * @return string The response for the action.
     * @throws RestorePathException If the files cannot be restored because destination and source are not set.
     *
     * @throws \Exception If registry cannot be saved.
     */
    public function restore_dir_action()
    {
        // Restore
        $errors = $this->restoreFiles->restore();

        // Store the state.
        $this->registry->finish_job('file_restore');

        return $errors
            ? $this->translator->trans('Directories restored with errors.')
            : $this->translator->trans('Directories restored successfully.');
    }

    /**
     * Restore the Database
     *
     * @return string
     * @throws DatabaseException\DatabaseFileException
     */
    public function restore_db_action()
    {
        if (!file_exists($this->registry->dbdumpfile)) {
            $message = 'No database dump file found.';
            $this->logger->warning($message);
            return $this->translator->trans($message);
        }

        // Restore the db.
        $this->database_importer->import();

        // Refresh file list
        if ($this->registry->service_name && $this->registry->job_id) {
            $destination_factory = new DestinationFactory($this->registry->service_name);
            $destination = $destination_factory->create();

            if (method_exists($destination, 'file_update_list')) {
                $destination->file_update_list($this->registry->job_id);
            }

            $this->registry->service_name = null;
            $this->registry->job_id = null;
        }

        // After we successfully restored the database we must log the user in again because
        // we have lost the reference to the current user.
        $this->login_user_again();

        return $this->translator->trans('Database restored successfully.');
    }

    /**
     * Save strategy
     *
     * @param string $strategy The restore strategy.
     *
     * @return void
     */
    public function save_strategy_action($strategy)
    {
        $this->registry->restore_strategy = $strategy;
    }

    /**
     * Get restore strategy from registry.
     *
     * @return string
     */
    public function get_strategy_action()
    {
        return $this->registry->restore_strategy;
    }

    /**
     * Login User Programmatically
     *
     * @return void
     */
    private function login_user_again()
    {
        // Nothing to do in standalone version.
        if (!class_exists('\WP')) {
            return;
        }

        $user = wp_get_current_user();

        wp_set_auth_cookie($user->ID, true);

        /**
         * Wp Login
         *
         * @param string $user_login The user login.
         * @param \WP_User $user The user instance.
         * @since 2.0.0
         *
         */
        do_action('wp_login', $user->user_login, $user);
    }

    /**
     * Ensure the uploaded file path is set correctly
     *
     * @param $file_path
     * @throws RegistryException
     */
    private function ensure_uploaded_file($file_path)
    {
        // TODO Find a way to set this once for the entire restore process if possible.
        if (!$this->registry->uploaded_file && $file_path) {
            $this->registry->uploaded_file = $file_path;
            $this->decompress->set_file_path($file_path);
        }

        // Remember about two context, upload file and restore from an existing file.
        if (!$this->registry->uploaded_file) {
            throw new RegistryException(
                $this->translator->trans(
                    'Seems the file you are trying to decompress doesn\'t exists. Please see the log file.'
                )
            );
        }
    }

    /**
     * Throw an exception in case the backup file it's a bzip2
     * Bzip2 isn't supported by the restore feature
     *
     * @param $file_ext
     *
     * @throws DecompressException
     */
    private function throw_error_if_bz2($file_ext)
    {
        if ($file_ext === 'bz2') {
            $this->registry->reset_registry();

            throw new DecompressException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translator,
                    $this->translator->trans(
                        'Sorry but bzip2 backups cannot be restored. You must convert the file to a .zip one in order to able to restore your backup.'
                    ),
                    'BZIP2_CANNOT_BE_DECOMPRESSED'
                )
            );
        }
    }
}
