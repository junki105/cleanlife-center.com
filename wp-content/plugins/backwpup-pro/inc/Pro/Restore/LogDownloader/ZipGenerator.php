<?php
/**
 * Restore Log Zip Generator
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */

namespace Inpsyde\BackWPup\Pro\Restore\LogDownloader;

/**
 * Class RestoreLogZipGenerator
 *
 * @since   3.5.0
 * @package Inpsyde\BackWPup\Pro\Restore
 */
class ZipGenerator {

	/**
	 * File
	 *
	 * @since 3.5.0
	 *
	 * @var string The zip file path
	 */
	private $filePath;

	/**
	 * Files
	 *
	 * @since 3.5.0
	 *
	 * @var array The files list to zip
	 */
	private $files;

	/**
	 * Zip
	 *
	 * @since 3.5.0
	 *
	 * @var \PclZip The zip instance
	 */
	private $zip;

    /**
     * ZipGenerator constructor
     *
     * @since 3.5.0
     *
     * @param \PclZip $zip The zip instance.
     * @param string $filePath The path of the zip file.
     * @param array $files The files list to zip.
     *
     * @throws \InvalidArgumentException in case one of the parameter isn't valid
     */
    public function __construct(\PclZip $zip, $filePath, array $files)
    {
        if (!$filePath || !is_string($filePath)) {
            throw new \InvalidArgumentException('Wrong value for file path.');
        }

        $this->zip = $zip;
        $this->files = $files;
        $this->filePath = $filePath;
    }

    /**
     * Zip
     *
     * @since 3.5.0
     *
     * @throws \RuntimeException In case the zip file cannot be opened
     *
     * @return void
     */
    public function zip()
    {
        foreach ($this->files as $file) {
            file_exists($file) and $this->zip->add($file, PCLZIP_OPT_REMOVE_ALL_PATH);
        }
    }

	/**
	 * Zip File Path
	 *
	 * @since 3.5.0
	 *
	 * @return string The file path of the zip
	 */
	public function path() {

		return $this->filePath;
	}
}
