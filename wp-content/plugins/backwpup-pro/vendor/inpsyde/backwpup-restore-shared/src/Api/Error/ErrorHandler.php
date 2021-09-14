<?php

namespace Inpsyde\Restore\Api\Error;

use Inpsyde\Restore\Api\Module\Registry;
use Psr\Log\LoggerInterface;

/**
 * Class ErrorHandler
 *
 * @author  Hans-Helge Buerger
 * @package Inpsyde\Restore\Api\Error
 */
class ErrorHandler
{

    /**
     * Holder var for Monolog logger instance
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * ErrorHandler constructor.
     * @param LoggerInterface $logger
     * @param Registry $registry
     */
    public function __construct(LoggerInterface $logger, Registry $registry)
    {
        $this->logger = $logger;
        $this->registry = $registry;
    }

    /**
     * Register the error handler as default
     */
    public function register()
    {
        set_error_handler(array($this, 'handle_error'));
    }

    /**
     * Restore the default error handler
     */
    public function unregister()
    {
        restore_error_handler();
    }

    /**
     * Log every PHP error and don't pass it to user
     *
     * @param int $error_type Number of error
     * @param string $error_text Error message
     * @param string $error_file File in which error raise
     * @param int $error_line Line in which error raised
     *
     * @return bool true; for suppressing PHP Internal error handling
     */
    public function handle_error($error_type, $error_text, $error_file, $error_line)
    {
        $this->registry->reset_registry();

        $msg = '[' . $error_type . '] ' . $error_text . ' ';
        $msg .= '(' . $error_file . ' on Line ' . $error_line . ') ';
        $msg .= '| PHP ' . PHP_VERSION . ' (' . PHP_OS . ')';

        /* Log message according to its error type */
        switch ($error_type) {
            case E_USER_ERROR:
                $this->logger->error($msg);
                break;

            case E_USER_NOTICE:
                $this->logger->notice($msg);
                break;

            case E_USER_WARNING:
            default:
                $this->logger->warning($msg);
                break;
        }

        /* Return true to suppress PHP internal error handling */

        return true;
    }
}
