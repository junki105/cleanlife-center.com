<?php

namespace Inpsyde\Restore\Api\Module\Decryption;

use Inpsyde\BackWPup\Archiver\Factory as ArchiveFileOperatorFactory;
use Inpsyde\BackWPupShared\File\MimeTypeExtractor;
use Inpsyde\Restore\Api\Module\Decryption\Exception\DecryptException;
use Symfony\Component\Translation\Translator;
use phpseclib\Crypt\AES;
use phpseclib\Crypt\RSA;

/**
 * Decrypter
 *
 * Decrypt backup archives using AES or RSA.
 *
 * @package Inpsyde\BackWPup
 */
class Decrypter
{
    const DECRYPT_PADDING_LENGHT = 16;
    const ENCRYPTED_FILE_SUB_EXTENSION = '.decrypted';

    /**
     * @var AES
     */
    private $aes;

    /**
     * @var RSA
     */
    private $rsa;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var ArchiveFileOperatorFactory
     */
    private $archiveFileOperatorFactory;

    /**
     * Decrypter constructor.
     *
     * @param AES $aes
     * @param RSA $rsa
     * @param Translator $translator
     * @param ArchiveFileOperatorFactory $archiveFileOperatorFactory
     */
    public function __construct(
        AES $aes,
        RSA $rsa,
        Translator $translator,
        ArchiveFileOperatorFactory $archiveFileOperatorFactory
    ) {

        $this->aes = $aes;
        $this->rsa = $rsa;
        $this->translator = $translator;
        $this->archiveFileOperatorFactory = $archiveFileOperatorFactory;
    }

    /**
     * @param string $backup_file
     *
     * @return bool
     */
    public function maybe_decrypted($backup_file)
    {
        $source_file_handler = fopen($backup_file, 'rb');

        // Read first byte to know what encryption method was used
        // 0 == Symmetric, 1 == Asymmetric
        $type = fread($source_file_handler, 1);

        return $type === chr(0) ?: $type === chr(1);
    }

    /**
     * @param string $key
     * @param string $backup_file
     *
     * @return bool
     * @throws DecryptException
     */
    public function decrypt($key, $backup_file)
    {
        $decrypter_backup_file = $backup_file . self::ENCRYPTED_FILE_SUB_EXTENSION;

        if (!$key) {
            throw new DecryptException($this->translator->trans('Private key must be provided.'));
        }

        $source_file_handler = fopen($backup_file, 'rb');
        if (!is_resource($source_file_handler)) {
            throw new DecryptException($this->translator->trans('Cannot open the archive for reading.'));
        }

        $decrypter_key = $this->decrypter_key($key, $source_file_handler);
        if ($decrypter_key === '') {
            return false;
        }

        if (file_exists($backup_file . self::ENCRYPTED_FILE_SUB_EXTENSION)) {
            unlink($backup_file . self::ENCRYPTED_FILE_SUB_EXTENSION);
        }

        $target_file_handler = fopen($decrypter_backup_file, 'a+b');
        if (!is_resource($target_file_handler)) {
            throw new DecryptException($this->translator->trans('Cannot write the decrypted archive.'));
        }

        $this->aes->setKey($decrypter_key);
        $this->aes->enableContinuousBuffer();
        $this->aes->disablePadding();

        $this->decrypt_data($source_file_handler, $target_file_handler);
        fclose($source_file_handler);
        fclose($target_file_handler);

        if (filesize($decrypter_backup_file) === 0) {
            return false;
        }
        if (!$this->test($decrypter_backup_file)) {
            return false;
        }

        unlink($backup_file);
        rename($decrypter_backup_file, $backup_file);

        return true;
    }

    /**
     * Key is in the first byte
     *
     * 0 = Symmetric, 1 = Asymmetric
     *
     * @param string $key
     * @param resource $source_file_handler
     *
     * @return string
     */
    private function decrypter_key($key, $source_file_handler)
    {
        $type = fread($source_file_handler, 1);
        switch ($type) {
            case chr(0):
                if (!ctype_xdigit($key)) {
                    return '';
                }
                $decrypter_key = pack('H*', $key);
                break;
            case chr(1):
                $decrypter_key = $this->rsa_decrypted_key($key, $source_file_handler);
                break;
            default:
                $decrypter_key = '';
                break;
        }

        return $decrypter_key;
    }

    /**
     * @param string $key
     * @param resource $source_file_handler
     *
     * @return string
     */
    private function rsa_decrypted_key($key, $source_file_handler)
    {
        $this->rsa->loadKey($key);

        $length = unpack('H*', fread($source_file_handler, 1));
        $length = hexdec($length[1]);
        $key = fread($source_file_handler, $length);

        return $this->rsa->decrypt($key);
    }

    /**
     * @param resource $source_file_handler
     * @param resource $target_file_handler
     */
    private function decrypt_data($source_file_handler, $target_file_handler)
    {
        $block_size = 128 * 1024;

        while (!feof($source_file_handler)) {
            $data = fread($source_file_handler, $block_size);
            $packet = $this->aes->decrypt($data);

            if (feof($source_file_handler)) {
                $padding_length = ord($packet[strlen($packet) - 1]);
                if ($padding_length <= self::DECRYPT_PADDING_LENGHT) {
                    $packet = substr($packet, 0, -$padding_length);
                }
            }

            fwrite($target_file_handler, $packet);
        }
    }

    /**
     * @param $decrypter_backup_file
     *
     * @return bool
     */
    private function test($decrypter_backup_file)
    {
        $valid = false;
        $mime_type = MimeTypeExtractor::fromFilePath($decrypter_backup_file);

        switch ($mime_type) {
            case 'application/zip':
                $operator = $this->archiveFileOperatorFactory->create($decrypter_backup_file);
                $valid = $operator->isValid();
                break;

            case 'application/x-tar':
            case 'application/x-gzip':
            case 'application/x-bzip2':
                $tar = new \Archive_Tar($decrypter_backup_file);
                $valid = $tar->listContent();
                break;
        }

        return (bool)$valid;
    }
}
