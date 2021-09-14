<?php

namespace Inpsyde\Restore\Api\Module\Upload;

use Inpsyde\Restore\Api\Exception\FileSystemException;
use Inpsyde\Restore\Api\Module\Registry;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Upload. Responsible for retrieving a file and save it into upload folder
 *
 * @author  Hans-Helge Buerger
 * @package Inpsyde\Restore\Api\Module\Upload
 * @since   1.0.0
 */
final class BackupUpload implements FileUploadInterface
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
     * @var string file name of uploaded backup archive
     */
    private $file_name;

    /**
     * @var string absolute path to upload folder
     */
    private $upload_folder = null;

    /**
     * @var int number of current chunk, which is uploaded
     */
    private $current_chunk;

    /**
     * @var int total number of chunks
     */
    private $total_chunks;

    /**
     * @var \Inpsyde\Restore\Api\Module\Registry
     */
    private $registry = null;

    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * BackupUpload constructor
     *
     * @param Registry $registry
     * @param TranslatorInterface $translator
     */
    public function __construct(Registry $registry, TranslatorInterface $translator)
    {
        $this->registry = $registry;
        $this->translator = $translator;
    }

    /**
     * Method checks if file available in request
     *
     * @return bool
     */
    public function files_var_exists()
    {
        // phpcs:disable WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
        if (empty($_FILES)
            || !isset($_FILES['file'])
            || (isset($_FILES['file']['error']) && $_FILES['file']['error'])
        ) {
            // phpcs:enable
            return false;
        }

        return true;
    }

    /**
     * This method is called to start an upload of a backup file
     *
     * @throws \Exception In case isn't possible to upload the file.
     *
     * @return bool true on success.
     */
    public function run()
    {
        // phpcs:ignore
        $tmp_file_name = $_FILES['file']['tmp_name'];
        if (!is_uploaded_file($tmp_file_name)) {
            die;
        }

        if (!$this->files_var_exists()) {
            throw new UploadException($this->translator->trans('Failed to move uploaded file.'));
        }

        // phpcs:disable
        $chunk = isset($_REQUEST["chunk"])
            ? filter_var($_REQUEST["chunk"], FILTER_SANITIZE_NUMBER_INT)
            : 0;
        $chunks = isset($_REQUEST["chunks"])
            ? filter_var($_REQUEST["chunks"], FILTER_SANITIZE_NUMBER_INT)
            : 0;
        // phpcs:enable

        $this->set_current_chunk((int)$chunk);
        $this->set_total_chunks((int)$chunks);

        // phpcs:disable
        $file_name = isset($_REQUEST["name"])
            ? $_REQUEST["name"]
            : (isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '');
        // phpcs:enable
        $file_name = filter_var($file_name, FILTER_SANITIZE_STRING);

        if (!$file_name) {
            throw new UploadException(
                $this->translator->trans('No File Name Found. Cannot upload.')
            );
        }

        $this->set_file_name($file_name);

        $file_path = $this->get_abs_file_path();

        // Open temp file
        $out = $this->open_file($file_path);
        if (!$out) {
            throw new FileSystemException(
                $this->translator->trans('Failed to open output stream during upload.')
            );
        }

        // Read binary input stream and append it to temp file
        $in = @fopen($tmp_file_name, "rb");
        if (!$in) {
            throw new FileSystemException(
                $this->translator->trans('Failed to open input stream during upload.')
            );
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($in);
        @fclose($out);

        @unlink($tmp_file_name);

        // Check if file has been uploaded
        if ($this->upload_finished()) {
            // Strip the temp .part suffix off
            rename("{$file_path}.part", $file_path);
        }

        return true;
    }

    /**
     * Helper function which opens the file stream for uploading.
     *
     * This helper function has only one purpose up to now: Testing.
     * Excluding this part from $this->run() makes it easier to test.
     *
     * @param string $file_path path of file to upload.
     *
     * @return resource Returns a file pointer resource on success, or FALSE on error.
     */
    public function open_file($file_path)
    {
        return fopen("{$file_path}.part", $this->get_current_chunk() === 0 ? "wb" : "ab");
    }

    /**
     * Method to check if current upload was the last one and upload is finish
     *
     * @return bool
     */
    protected function upload_finished()
    {
        if ($this->get_total_chunks() === 0 || $this->get_current_chunk() === $this->get_total_chunks() - 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method return absolute path to upload file
     *
     * @return string absolute path to upload file
     */
    public function get_abs_file_path()
    {
        return $this->get_upload_folder() . '/' . $this->get_file_name();
    }

    /**
     * @return string
     */
    public function get_upload_folder()
    {
        if (is_null($this->upload_folder)) {
            $this->upload_folder = $this->registry->uploads_folder;

            // Delete if it's a file.
            if (is_file($this->upload_folder)) {
                unlink($this->upload_folder);
            }

            if (!file_exists($this->upload_folder) && !is_dir($this->upload_folder)) {
                mkdir($this->upload_folder);
            }
        }

        return $this->upload_folder;
    }

    /**
     * @return mixed
     */
    public function get_current_chunk()
    {
        return $this->current_chunk;
    }

    /**
     * @return mixed
     */
    public function get_total_chunks()
    {
        return $this->total_chunks;
    }

    /**
     * @param string $upload_folder
     */
    public function set_upload_folder($upload_folder)
    {
        $this->upload_folder = $upload_folder;
    }

    /**
     * @param mixed $current_chunk
     */
    public function set_current_chunk($current_chunk)
    {
        $this->current_chunk = intval($current_chunk);
    }

    /**
     * @param mixed $total_chunks
     */
    public function set_total_chunks($total_chunks)
    {
        $this->total_chunks = intval($total_chunks);
    }

    /**
     * @return mixed
     */
    public function get_file_name()
    {
        return $this->file_name;
    }

    /**
     * @param mixed $file_name
     */
    public function set_file_name($file_name)
    {
        $this->file_name = $this->sanitize_file_path($file_name);
    }

    /**
     * Helper function to check if uploaded file is an archive
     *
     * @param string $path Absolute path to uploaded file.
     *
     * @return bool
     */
    public static function upload_is_archive($path)
    {
        return self::upload_is_type(self::$supported_archives, pathinfo($path));
    }

    /**
     * Helper function to check if uploaded file is a sql file
     *
     * @param string $path Absolute path to uploaded file.
     *
     * @return bool
     */
    public static function upload_is_sql($path)
    {
        return self::upload_is_type(array('sql'), pathinfo($path));
    }

    /**
     * Sanitize File Path
     *
     * @param string $path The file path.
     *
     * @return string The sanitized filename.
     */
    private function sanitize_file_path($path)
    {
        $filename = basename($path);
        $path = str_replace($filename, '', $path);

        // Trailing slash it.
        if ($path) {
            $path = rtrim($path, '\\/') . DIRECTORY_SEPARATOR;
        }

        // Clean the filename.
        $filename = trim(preg_replace('/[^a-zA-Z0-9\/\-\_\.]+/', '', $filename));
        while (false !== strpos($filename, '..')) {
            $filename = str_replace('..', '', $filename);
        }
        $filename = ('/' !== $filename) ? $filename : '';

        $path .= $filename;

        return $path;
    }

    /**
     * Check if uploaded file extension is in provided array and hence of type.
     *
     * @param array $extensions file types to check for
     * @param array $path_parts pathinfos
     *
     * @return bool
     */
    private static function upload_is_type($extensions, $path_parts)
    {
        if (!isset($path_parts['extension'])) {
            return false;
        }

        return in_array($path_parts['extension'], $extensions);
    }
}
