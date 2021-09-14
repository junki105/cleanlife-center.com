<?php
/*
 * This file is part of the Inpsyde BackWpUp package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Controller;

use Inpsyde\Restore\Api\Module\Decryption\Exception\DecryptException;
use Inpsyde\Restore\Api\Module\Decryption\Decrypter;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DecryptController
 */
class DecryptController
{

    const STATE_DECRYPTION_FAILED = 'decryption_failed';
    const STATE_DECRYPTION_SUCCESS = 'decryption_success';
    const STATE_NEED_DECRYPTION_KEY = 'need_decryption_key';

    /**
     * @var Decrypter
     */
    private $decrypter;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * DecryptController constructor.
     *
     * @param Decrypter $decrypter
     * @param TranslatorInterface $translator
     */
    public function __construct(Decrypter $decrypter, TranslatorInterface $translator)
    {
        $this->decrypter = $decrypter;
        $this->translator = $translator;
    }

    /**
     * @param $key
     * @param $encrypted_file
     *
     * @return bool
     * @throws DecryptException
     */
    public function decrypt($key, $encrypted_file)
    {
        $decrypted = false;
        $maybe_decrypted = $this->decrypter->maybe_decrypted($encrypted_file);

        if ($maybe_decrypted) {
            $decrypted = $this->decrypter->decrypt($key, $encrypted_file);
        }
        if (!$decrypted && $maybe_decrypted) {
            throw new DecryptException(
                $this->translator->trans(
                    'Decryption Failed. Probably the key you provided is not correct. Try again with a different key.'
                )
            );
        }

        return true;
    }
}
