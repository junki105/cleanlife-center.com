<?php
/*
 * This file is part of the Inpsyde BackWpUp package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Exception;

use Inpsyde\Restore\Api\Module\Registry;
use Inpsyde\Restore\Api\Module\Session\Session;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ExceptionHandler
 *
 * @package Inpsyde\Api\Exception
 * @author  ap
 * @since   1.0.0
 */
class ExceptionHandler
{
    /**
     * Holder var for Monolog logger instance
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Inpsyde\Restore\Api\Module\Session\Session
     */
    private $session;

    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * ExceptionHandler constructor.
     * @param LoggerInterface $logger
     * @param Session $session
     * @param TranslatorInterface $translator
     * @param Registry $registry
     */
    public function __construct(
        LoggerInterface $logger,
        Session $session,
        TranslatorInterface $translator,
        Registry $registry
    ) {

        $this->logger = $logger;
        $this->session = $session;
        $this->translator = $translator;
        $this->registry = $registry;
    }

    /**
     * Register the exception handler as default
     */
    public function register()
    {
        set_exception_handler(array($this, 'handle_exception'));
    }

    /**
     * Restore the default exception handler
     */
    public function unregister()
    {
        restore_exception_handler();
    }

    /**
     * Handle Exception
     *
     * @param \Exception $exception An exception Instance.
     */
    public function handle_exception($exception)
    {
        if (!$exception instanceof RestoreExceptionInterface) {
            return;
        }

        $this->registry->reset_registry();

        $msg = get_class($exception)
            . ': '
            . $exception->getMessage()
            . ' ('
            . $exception->getFile()
            . ' on Line '
            . $exception->getLine()
            . ')';

        // Log the error.
        $this->logger->alert($msg);

        // Add a message for the user.
        $this->session->warning(
            $this->translator->trans('We encountered an error. Please check your log file for more information.')
        );

        // Redirect to page and show error message.
        // phpcs:disable WordPress.VIP.SuperGlobalInputUsage.AccessDetected, WordPress.VIP.ValidatedSanitizedInput.InputNotValidated
        $url = (string)filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL);
        $url or (string)filter_var($_SERVER['ORIGIN'], FILTER_VALIDATE_URL);
        // phpcs:enable

        header('Location: ' . $url, true, 307);
        exit();
    }
}
