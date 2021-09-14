<?php

namespace Inpsyde\Restore\Api\Module\Database;

use Inpsyde\Restore\Api\Module\Database\Exception\DatabaseFileException;
use Symfony\Component\Translation\Translator;

final class SqlFileImport implements ImportFileInterface
{

    /**
     * @var null File handle
     */
    private $file_handel = false;

    /**
     * @var Translator
     */
    private $translation;

    /**
     * @var array read line cache
     *
     */
    private $line_cache = array(0 => '');

    /**
     * SqlFileImport constructor.
     *
     * @param Translator $translation
     */
    public function __construct(Translator $translation)
    {
        $this->translation = $translation;
    }

    /**
     * @inheritdoc
     */
    public function set_import_file($file)
    {
        if (!is_file($file)) {
            throw new DatabaseFileException(
                sprintf($this->translation->trans('Sql file %1$s do not exist'), $file)
            );
        }

        if (!is_readable($file)) {
            throw new DatabaseFileException(
                sprintf($this->translation->trans('Sql file %1$s not readable'), $file)
            );
        }

        if (is_resource($this->file_handel)) {
            fclose($this->file_handel); // phpcs:ignore
        }

        if (strstr($file, '.sql.gz') !== false) {
            $this->file_handel = fopen('compress.zlib://' . $file, 'rb'); // phpcs:ignore
        } elseif (strstr($file, '.sql.bz2') !== false) {
            $this->file_handel = fopen('compress.bzip2://' . $file, 'rb'); // phpcs:ignore
        } else {
            $this->file_handel = fopen($file, 'rb'); // phpcs:ignore
        }

        if ($this->file_handel) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function get_file_size()
    {
        $file_handle = fstat($this->file_handel);

        return $file_handle['size'];
    }

    /**
     * @inheritdoc
     */
    public function get_position()
    {
        $pos = ftell($this->file_handel);

        if (!$pos) {
            throw new DatabaseFileException($this->translation->trans('Can not get Sql file position'));
        }

        return array('pos' => $pos, 'line_cache' => $this->line_cache);
    }

    /**
     * @inheritdoc
     */
    public function set_position(array $position)
    {
        if (!isset($position['pos'])) {
            throw new DatabaseFileException($this->translation->trans('Sql file position not set'));
        }

        if (isset($position['line_cache']) && is_array($position['line_cache'])) {
            $this->line_cache = $position['line_cache'];
        } else {
            throw new DatabaseFileException($this->translation->trans('Sql file line cache not set'));
        }

        $result = fseek($this->file_handel, $position['pos']);
        if ($result === -1) {
            throw new DatabaseFileException($this->translation->trans('Can not set Sql file position'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function get_query()
    {
        $query = '';
        $line = '';

        while ($line !== false) {
            $line = $this->get_line_from_file();
            if (!$line) {
                continue;
            }
            if (substr($line, 0, 2) === '--') {
                continue;
            }
            if (substr($line, 0, 1) === '#') {
                continue;
            }
            $query .= $line;
            if (substr($query, -1) === ';') {
                break;
            }
        }

        return $query;
    }

    /**
     * Test is file a sql file
     *
     * @param string $file
     *
     * @return bool
     */
    public function is_sql_file($file)
    {
        if (!is_file($file)) {
            return false;
        }

        $info = pathinfo($file);

        if (isset($info['extension'])) {
            $extension = strtolower($info['extension']);
        } else {
            return false;
        }

        if ($extension !== 'sql' && strstr($file, '.sql.gz') !== false && strstr(
            $file,
            '.sql.bz2'
        )) {
            return false;
        }

        if (strstr($file, '.sql.gz') !== false) {
            $file_handel = fopen('compress.zlib://' . $file, 'r');
        } elseif (strstr($file, '.sql.bz2') !== false) {
            $file_handel = fopen('compress.bzip2://' . $file, 'r');
        } else {
            $file_handel = fopen($file, 'r');
        }

        $content = fread($file_handel, 1048576);

        fclose($file_handel);

        if (stristr($content, 'INSERT') === false || stristr($content, 'CREATE TABLE') === false) {
            return false;
        }

        return true;
    }

    /**
     * Clean up
     */
    public function __destruct()
    {
        if (is_resource($this->file_handel)) {
            fclose($this->file_handel);
        }
    }

    /**
     * Read sql query from file query by query
     *
     * @return string line
     */
    private function get_line_from_file()
    {
        if (!$this->line_cache) {
            return false;
        }

        if (count($this->line_cache) === 1) {
            $file_content = fread($this->file_handel, 8192);
            while ('' !== $file_content && false === strpos($file_content, "\n")) {
                $file_content .= fread($this->file_handel, 8192);
            }

            $file_lines = explode("\n", $file_content);

            if ($this->line_cache) {
                $file_lines[0] = array_shift($this->line_cache) . $file_lines[0];
            }

            $this->line_cache = $file_lines;
        }

        return trim(array_shift($this->line_cache));
    }
}
