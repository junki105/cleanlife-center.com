<?php

namespace Inpsyde\Restore\Api\Module\Session;

/**
 * Interface Notification
 */
interface NotificationInterface
{

    /**
     * Wrapper method to add an info.
     *
     * @param string $message information for user.
     *
     * @return NotificationInterface $this for concatenation.
     */
    public function info($message);

    /**
     * Wrapper method to add an warning message.
     *
     * @param string $message warning for user.
     *
     * @return NotificationInterface $this for concatenation.
     */
    public function warning($message);

    /**
     * Wrapper method to add an success message.
     *
     * @param string $message success message for user.
     *
     * @return NotificationInterface $this for concatenation.
     */
    public function success($message);

    /**
     * Wrapper method to add an error message.
     *
     * @param string $message error message for user.
     *
     * @return NotificationInterface $this for concatenation.
     */
    public function error($message);

    /**
     * Notifications
     *
     * Wrapper for getter method to retrieve notifications.
     *
     * @return array
     */
    public function notifications();

    /**
     * Clean all notifications
     *
     * @return void
     */
    public function clean();
}
