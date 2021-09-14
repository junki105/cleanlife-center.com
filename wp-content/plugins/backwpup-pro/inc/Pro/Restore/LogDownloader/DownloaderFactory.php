<?php
/**
 * Log Downloader Factory
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\pro\restore
 */

namespace Inpsyde\BackWPup\Pro\Restore\LogDownloader;

/**
 * Class DownloderFactory
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\pro\restore
 */
class DownloaderFactory
{
    /**
     * Files List
     *
     * @since 3.5.0
     *
     * @var array The files to compress
     */
    private static $files = array(
        'debug.log',
        'restore.log',
        'restore.dat.bkp',
        'restore.dat',
    );

    /**
     * Container
     *
     * @since 3.5.0
     *
     * @var \Pimple\Container The container instance
     */
    private $container;

    /**
     * DownloaderFactory constructor
     *
     * @since 3.5.0
     */
    public function __construct()
    {
        $this->container = \Inpsyde\BackWPup\Pro\Restore\Functions\restore_container(null);
    }

    /**
     * Create the Downloader instance
     *
     * @since 3.5.0
     *
     * @throws \RuntimeException in case the function `gzopen` doesn't exists
     * @throws \RuntimeException in case temporary directory isn't readable or writable
     * @return Downloader A instance of the Downloader class
     */
    public function create()
    {
        if (!function_exists('gzopen')) {
            throw new \RuntimeException(
                'gzopen function doesn\'t exist, cannot create a Downloader instance.'
            );
        }

        $dir = isset($this->container['project_temp']) ? $this->container['project_temp'] : '';
        if (!$dir || !is_readable($dir) || !is_writable($dir)) {
            throw new \RuntimeException(
                'Project temporary directory doesn\'t exists or is not readable/writable'
            );
        }

        $this->ensurePclZip();
        $this->createFilesPath();

        $filePath = trailingslashit($dir) . 'log.zip';

        $view = new View(
            esc_html__('Download Log', 'backwpup'),
            self_admin_url('admin.php?page=backwpuprestore'),
            self::$files
        );
        $zipGenerator = new ZipGenerator(
            new \PclZip($filePath),
            $filePath,
            self::$files
        );

        return new Downloader($view, $zipGenerator, backwpup_wpfilesystem(), self::$files);
    }

    /**
     * Generate the absolute files path
     *
     * @since 3.5.0
     *
     * @return void
     */
    private function createFilesPath()
    {
        foreach (self::$files as &$file) {
            $file = trailingslashit($this->container['project_temp']) . $file;
        }
    }

    /**
     * Require PclZip if needed
     *
     * @return void
     */
    private function ensurePclZip()
    {
        if (!class_exists('PclZip')) {
            require_once ABSPATH . '/wp-admin/includes/class-pclzip.php';
        }
    }
}
