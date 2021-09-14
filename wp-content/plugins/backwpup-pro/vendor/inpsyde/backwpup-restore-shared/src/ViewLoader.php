<?php
/*
 * This file is part of the Inpsyde BackWpUp package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ViewLoader
 *
 * @package Inpsyde\Restore
 */
class ViewLoader
{

    const DECRYPT_KEY_INPUT = 'decrypt-key-input.php';

    /**
     * @var string
     */
    private $view_directory;

    /**
     * @var array
     */
    private $cache = array();

    private $translator;

    /**
     * ViewLoader constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->view_directory = dirname(__DIR__) . '/views';
    }

    /**
     * Load the private key input view
     */
    public function decrypt_key_input()
    {
        $this->load(self::DECRYPT_KEY_INPUT);
    }

    /**
     * @param string $view
     */
    private function load($view)
    {
        if (isset($this->cache[$view])) {
            /** @noinspection PhpIncludeInspection */
            include $this->cache[$view];

            return;
        }

        $file_path = $this->view_directory . "/{$view}";
        if (!file_exists($file_path)) {
            return;
        }

        $this->cache[$view] = $file_path;

        /** @noinspection PhpIncludeInspection */
        include $file_path;
    }
}
