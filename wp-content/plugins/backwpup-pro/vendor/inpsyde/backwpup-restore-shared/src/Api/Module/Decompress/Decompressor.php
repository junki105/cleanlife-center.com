<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the BackWPup Restore Shared package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Module\Decompress;

use Archive_Tar;
use Exception;
use Inpsyde\BackWPup\Archiver\CurrentExtractInfo;
use Inpsyde\BackWPup\Archiver\Extractor;
use Inpsyde\Restore\AjaxHandler;
use Inpsyde\Restore\Api\Decompress\Exception\DecompressException;
use Inpsyde\Restore\Api\Exception\FileSystemException;
use Inpsyde\Restore\Api\Module\Registry;
use Inpsyde\Restore\Api\Exception\ExceptionLinkHelper;
use InvalidArgumentException;
use OutOfBoundsException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use \Symfony\Component\Translation\TranslatorInterface;
use Monolog\Logger;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use UnexpectedValueException;

/**
 * Class Decompressor
 *
 * @package Inpsyde\Restore\Api\Module
 */
class Decompressor
{
    /**
     * Supported archive extensions
     *
     * @var array The extension of the supported archives
     */
    private static $supported_archives = array(
        'zip',
        'tar',
        'gz',
        'bz2',
    );

    /**
     * Registry
     *
     * @var Registry
     */
    private $registry;

    /**
     * Logger
     *
     * @var Logger
     */
    private $logger;

    /**
     * File Path
     *
     * @var string The path of the file to extract
     */
    private $file_path;

    /**
     * Translation
     *
     * @var \Symfony\Component\Translation\Translator Used to translate strings
     */
    private $translation;

    /**
     * Context
     *
     * The context in which the instance operate. Default is `event_source` means
     * the instance is used in a EventSource request.
     *
     * @var string The context in which the instance operate.
     */
    private $context = AjaxHandler::EVENT_SOURCE_CONTEXT;

    /**
     * @var Extractor
     */
    private $extractor;

    /**
     * @var State
     */
    private $decompressionState;

    /**
     * @var StateUpdater
     */
    private $decompressionStateUpdater;

    /**
     * Decompress Constructor
     *
     * @param Registry $registry Registry instance.
     * @param LoggerInterface $logger Logger instance.
     * @param TranslatorInterface $translation The traslation instance to translate strings.
     *
     * @param Extractor $extractor
     * @param State $decompressionState
     * @param StateUpdater $decompressionStateUpdater
     * @internal param String $file_path The path of the file to extract
     * @internal param String $dir the destination of the extract process
     */
    public function __construct(
        Registry $registry,
        LoggerInterface $logger,
        TranslatorInterface $translation,
        Extractor $extractor,
        State $decompressionState,
        StateUpdater $decompressionStateUpdater
    ) {

        $this->registry = $registry;
        $this->logger = $logger;
        $this->translation = $translation;
        // TODO May be can be retrieved by the Decompress\State. See where this is used.
        $this->file_path = $this->registry->uploaded_file;
        $this->extractor = $extractor;
        $this->decompressionState = $decompressionState;
        $this->decompressionStateUpdater = $decompressionStateUpdater;
    }

    /**
     * Run
     *
     * Checks for the extraction/decompressing requirements
     * and attempts extracting the archive to the destination.
     *
     * @return void
     * @throws DecompressException
     * @throws RuntimeException
     * @throws UnexpectedValueException
     */
    public function run()
    {
        // Check if it's possible to decompress the archive.
        $this->check_if_can_decompressed();

        // Clean previously created files. May be user is trying to perform the decompression again.
        // Only the first time.
        if (!isset($this->registry->decompression_state)) {
            $this->clean_old_decompressed_files();
        }

        $this->extract(
            pathinfo($this->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Set File Path
     *
     * Sets file path of backup archive.
     *
     * @param string $file_path The file path.
     *
     * @return void
     */
    public function set_file_path($file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * Get the file path
     *
     * @return string The file path
     */
    public function get_file_path()
    {
        return $this->file_path;
    }

    /**
     * Get Extract Folder
     *
     * @return string The extract folder path
     */
    public function get_extract_folder()
    {
        return $this->registry->extract_folder;
    }

    /**
     * Clean Old Decompressed Files
     *
     * @return bool True on success, false in case the dir is not readable or not a directory
     * @throws UnexpectedValueException
     */
    private function clean_old_decompressed_files()
    {
        // If current directory is not a directory or empty don't do anything else.
        if (!is_readable($this->registry->extract_folder)
            || !is_dir($this->registry->extract_folder)
        ) {
            return false;
        }

        $it = new RecursiveDirectoryIterator(
            $this->registry->extract_folder,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        // Clean files.
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath()); // phpcs:ignore
                continue;
            }

            unlink($file->getRealPath()); // phpcs:ignore
        }

        return true;
    }

    /**
     * The default extractor that decompresses zip files
     *
     * @throws RuntimeException In case somethings goes wrong during extraction.
     * @throws InvalidArgumentException
     */
    private function zip_extractor()
    {
        $errors = 0;
        $context = $this->context;
        $decompressionState = $this->decompressionState;
        $decompressionStateUpdater = $this->decompressionStateUpdater;
        $currentIndex = $decompressionState->index();

        try {
            $this->extractor->extractByOffset(
                $this->file_path,
                $this->registry->extract_folder,
                ++$currentIndex,
                function (CurrentExtractInfo $info) use ($decompressionStateUpdater, $context) {
                    $decompressionStateUpdater->updateStatus($info);
                    // Only for event source calls.
                    if (AjaxHandler::EVENT_SOURCE_CONTEXT === $context) {
                        echo "event: message\n";
                        echo 'data: ' . wp_json_encode($info) . "\n\n";
                        flush();
                    }
                }
            );
        } catch (Exception $exc) {
            $this->logger->error($exc->getMessage());
            ++$errors;
        }

        $decompressionStateUpdater->clean();

        if ($errors) {
            throw new RuntimeException(
                $this->translation->trans(
                    'Extracted with errors. Please, see the log for more information.'
                )
            );
        }
    }

    /**
     * Extract Tar by index
     *
     * @param Archive_Tar $tar The archive tar instance to use to extract the file.
     * @param array $content The content of the archive.
     * @param int $filesCount The total amount of files within the archive.
     * @param int $index The index of the file to extract.
     *
     * @return CurrentExtractInfo
     * @throws OutOfBoundsException If the index doesn't exists within the tar archive.
     * @throws DecompressException In case the file cannot be decompressed.
     * @throws Exception If the registry cannot be saved.
     */
    private function tar_extractor_by_index($tar, $content, $filesCount, $index)
    {
        if (!isset($content[$index])) {
            throw new OutOfBoundsException(
                sprintf(
                    $this->translation->trans('Impossible to extract file at index %d. Index does not exists'),
                    $index
                )
            );
        }

        // Get the name of the file we want to extract.
        $fileName = $content[$index]['filename'];
        // If it's not possible to extract the file, log the file name.
        if (!$tar->extractList($fileName, $this->registry->extract_folder)) {
            throw new DecompressException(
                sprintf(
                    $this->translation->trans('Decompress %s failed. You need to copy the file manually.'),
                    $fileName
                )
            );
        }

        $data = new CurrentExtractInfo(
            $filesCount,
            $index,
            $fileName,
            $this->registry->extract_folder
        );

        $this->decompressionStateUpdater->updateStatus($data);

        return $data;
    }

    /**
     * Tar Extractor
     *
     * Decompresses tar files with gz and bz compressors
     *
     * @throws RuntimeException If some error occurred.
     */
    private function tar_extractor()
    {
        $errors = 0;
        $tar = new Archive_Tar($this->file_path);
        $content = $tar->listContent();
        $filesCount = count($content);
        $currentIndex = $this->decompressionState->index() + 1;

        for (; $currentIndex < $filesCount; ++$currentIndex) {
            try {
                $data = $this->tar_extractor_by_index($tar, $content, $filesCount, $currentIndex);

                if (AjaxHandler::EVENT_SOURCE_CONTEXT === $this->context) {
                    echo "event: message\n";
                    echo 'data: ' . wp_json_encode($data) . "\n\n";
                    flush();
                }
            } catch (Exception $exc) {
                $this->logger->error($exc->getMessage());
                ++$errors;
            }
        }

        // Clean the registry. So we allow to upload and decompress a new archive.
        $this->decompressionStateUpdater->clean();

        if ($errors) {
            throw new RuntimeException(
                $this->translation->trans(
                    'Extracted with error. Please, see the log for more information.'
                )
            );
        }
    }

    /**
     * Can Decompress
     *
     * Check if the decompression can be performed.
     *
     * @return void
     * @throws RuntimeException In case something went wrong.
     *
     */
    private function check_if_can_decompressed()
    {
        $file_ext = pathinfo($this->file_path, PATHINFO_EXTENSION);

        // Nothing to do if the file extension is not permitted.
        if (!$file_ext || !in_array($file_ext, self::$supported_archives, true)) {
            throw new DecompressException(
                sprintf(
                    $this->translation->trans('File .%s type not supported.'),
                    ltrim($file_ext, '.')
                )
            );
        }

        // If file doesn't exists, we cannot perform any decompression.
        if (!file_exists($this->file_path)) {
            throw new FileSystemException(
                $this->translation->trans('File does not exist or access is denied.')
            );
        }

        $response = $this->create_extract_folder_if_not_exists();
        if ($response) {
            throw new FileSystemException($response);
        }

        // Seems chmod may return a false positive in some situations.
        // @see http://php.net/manual/en/function.chmod.php for more info.
        chmod($this->registry->extract_folder, 0755); // phpcs:ignore

        if (!is_writable($this->registry->extract_folder)) { // phpcs:ignore
            throw new FileSystemException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translation,
                    sprintf(
                        $this->translation->trans(
                            'Destination %s is not writable and is not possible to correct the permissions. Please double check it.'
                        ),
                        $this->registry->extract_folder
                    ),
                    'DIR_CANNOT_BE_CREATED'
                )
            );
        }
    }

    /**
     * Extract
     *
     * Extracts the archive to the destination according to the extension of the archive
     *
     * @param string $ext Extension of archive.
     *
     * @return void
     * @throws RuntimeException If something gone wrong with the decompression.
     * @throws DecompressException In case the backup file is a .bzip one.
     * @throws InvalidArgumentException
     */
    private function extract($ext)
    {
        switch ($ext) {
            case 'tar':
            case 'gz':
                $this->tar_extractor();
                break;

            case 'bz2':
                throw new DecompressException(
                    ExceptionLinkHelper::translateWithAppropiatedLink(
                        $this->translation,
                        $this->translation->trans(
                            'Sorry but bzip2 backups cannot be restored. You must convert the file to a .zip one in order to able to restore your backup.'
                        ),
                        'BZIP2_CANNOT_BE_DECOMPRESSED'
                    )
                );
                break;

            case 'zip':
            default:
                $this->zip_extractor();
                break;
        }

        // Store the manifest json file path.
        $this->registry->manifest_file = $this->registry->extract_folder . '/manifest.json';
    }

    /**
     * Set Error Handler for Decompression directory
     *
     * Try to set the parent directory permissions and recreate the decompress directory.
     * Log if fails.
     *
     * The error handler is removed at the begin of the function to prevent possible loops.
     *
     * @return void
     * @uses set_error_handler() To set the error handler.
     */
    private function set_error_handler_for_decompression_directory()
    {
        // Old php versions.
        $self = $this;
        $logger = $this->logger;
        $translation = $this->translation;

        // `mkdir` emit a `E_WARNING` in case it's not possible to create the directory.
        set_error_handler(
            function () use ($self, $logger, $translation) {

                // Restore the previous handler and return, avoid possible loops.
                restore_error_handler();

                $logger->warning(
                    $translation->trans(
                        'Error during create decompression directory, trying to set permissions for parent directory.'
                    )
                );
                $self->try_set_parent_decompress_dir_permissions();
                $self->create_extract_folder();

                // Try to run the process again since we have successfully created the decompress directory.
                $self->run();
            },
            E_WARNING
        );
    }

    /**
     * Try Set Parent Decompress Directory Permissions
     *
     * @return bool True on success, false otherwise
     * @throws FileSystemException
     * @throws InvalidArgumentException
     */
    public function try_set_parent_decompress_dir_permissions()
    {
        // phpcs:ignore
        $response = chmod(dirname($this->registry->extract_folder), 0755);

        if (!$response) {
            throw new FileSystemException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translation,
                    sprintf(
                        $this->translation->trans('Impossible to set permissions for parent directory %s.'),
                        $this->registry->extract_folder
                    ),
                    'DIR_CANNOT_BE_CREATED'
                )
            );
        }

        return $response;
    }

    /**
     * Create Decompress Directory
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function create_extract_folder()
    {
        $created = mkdir($this->registry->extract_folder, 0755); // phpcs:ignore

        if (!$created) {
            throw new RuntimeException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translation,
                    $this->translation->trans(
                        'Destination directory does not exist and is not possible to create it.'
                    ),
                    'DIR_CANNOT_BE_CREATED'
                )
            );
        }
    }

    /**
     * Create Decompress Directory if not Exists
     *
     * @return string The JSON response if the directory cannot be created for a reason, empty string otherwise.
     * @throws InvalidArgumentException
     * @throws RuntimeException In case isn't possible to create the directory.
     */
    private function create_extract_folder_if_not_exists()
    {
        $msg = '';

        // Not Directory? Try to remove it.
        if (file_exists($this->registry->extract_folder)
            && !is_dir($this->registry->extract_folder)
            && !unlink($this->registry->extract_folder)
        ) {
            $msg = sprintf(
                'Invalid destination %s. Not a valid directory.',
                $this->registry->extract_folder
            );
        }

        // If directory doesn't exists, try to create it.
        if (!file_exists($this->registry->extract_folder)) {
            $this->set_error_handler_for_decompression_directory();
            $this->create_extract_folder();

            // Because we cannot know if the error handler has been called or not.
            restore_error_handler();
        }

        return $msg;
    }
}
