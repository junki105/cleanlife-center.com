<?php
/**
 * Session
 */

namespace Inpsyde\Restore\Api\Module\Session;

/**
 * Class Session
 *
 * Boilerplate for session handling by @dnaber
 *
 * @package Inpsyde\Restore\Api\Module\Session
 */
class Session implements NotificableStorableSessionInterface
{

    /**
     * @var array $session A reference to $_SESSION
     */
    private $session;

    /**
     * Notification constructor.
     *
     * @param array $session Reference to the $_SESSION.
     */
    public function __construct(&$session)
    {
        $this->session = &$session;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->session[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return isset($this->session[$key])
            ? $this->session[$key]
            : null;
    }

    /**
     * @inheritdoc
     */
    public function delete($key)
    {
        if (isset($this->session[$key])) {
            unset($this->session[$key]);
        }
    }

    /**
     * @inheritdoc
     */
    public function notifications()
    {
        $notifications = $this->get('notifications');

        return isset($notifications)
            ? $notifications
            : array();
    }

    /**
     * Wrapper method to add an info message to an array 'notifications' within the session.
     *
     * @param string $message information for user.
     *
     * @return void
     */
    public function info($message)
    {
        $notifications = $this->get('notifications');

        $notifications[] = array(
            'level' => 'info',
            'msg' => $message,
        );

        $this->set('notifications', $notifications);
    }

    /**
     * Wrapper method to add an info message to an array 'notifications' within the session.
     *
     * @param string $message warning for user.
     *
     * @return void
     */
    public function warning($message)
    {
        $notifications = $this->get('notifications');

        $notifications[] = array(
            'level' => 'warning',
            'msg' => $message,
        );

        $this->set('notifications', $notifications);
    }

    /**
     * Wrapper method to add an info message to an array 'notifications' within the session.
     *
     * @param string $message success message for user.
     *
     * @return void
     */
    public function success($message)
    {
        $notifications = $this->get('notifications');

        $notifications[] = array(
            'level' => 'success',
            'msg' => $message,
        );

        $this->set('notifications', $notifications);
    }

    /**
     * Wrapper method to add an info message to an array 'notifications' within the session.
     *
     * @param string $message error message for user.
     *
     * @return void
     */
    public function error($message)
    {
        $notifications = $this->get('notifications');

        $notifications[] = array(
            'level' => 'error',
            'msg' => $message,
        );

        $this->set('notifications', $notifications);
    }

    /**
     * @inheritdoc
     */
    public function clean()
    {
        $this->session = array();
    }
}
