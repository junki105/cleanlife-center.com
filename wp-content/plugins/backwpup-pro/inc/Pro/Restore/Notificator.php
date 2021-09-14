<?php
/**
 * Restore Notifications
 */

namespace Inpsyde\BackWPup\Pro\Restore;

use Inpsyde\Restore\Api\Module\Session\NotificableStorableSessionInterface;
use Inpsyde\Restore\Api\Module\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Notifications
 *
 * @since
 * @package Inpsyde\BackWPup\Pro\Restore
 */
class Notificator {

	/**
	 * Session
	 *
	 * @var Session The session to use retrieve the messages
	 */
	private $session;

	/**
	 * Translator
	 *
	 * @var TranslatorInterface The translator for strings
	 */
	private $translator;

	/**
	 * Notifications list
	 *
	 * @var array The container for the notifications message
	 */
	private $notifications_list = array();

	/**
	 * Notificator constructor
	 *
	 * @param NotificableStorableSessionInterface $session    The session to use retrieve the messages.
	 * @param TranslatorInterface                 $translator The translator for strings.
	 */
	public function __construct( NotificableStorableSessionInterface $session, TranslatorInterface $translator ) {

		$this->session    = $session;
		$this->translator = $translator;
	}

	/**
	 * Load
	 *
	 * Set the hooks
	 *
	 * @return void
	 */
	public function load() {

		add_action(
			( is_network_admin() ? 'network_admin_notices' : 'admin_notices' ),
			array( $this, 'notify' )
		);
	}

	/**
	 * Notify
	 *
	 * Load the template and show the notifications
	 *
	 * @return void
	 */
	public function notify() {

		$this->set_notification();

		$this->notifications_list and backwpup_template( // phpcs:ignore
			(object) array(
				'notifies' => $this->notifications_list,
			),
			'/pro/restore/notifications.php'
		);
	}

	/**
	 * Set Notifications
	 *
	 * @return void
	 */
	private function set_notification() {

		foreach ( $this->session->notifications() as $note ) {
			// Create a new item if not exists.
			if ( ! isset( $this->notifications_list[ $note['level'] ] ) ) {
				$this->notifications_list[ $note['level'] ] = array();
			}

			// Set the message and translate it.
			// Don't use WordPress functions here because the text messages comes from the shared library.
			$this->notifications_list[ $note['level'] ][] = $this->translator->trans( $note['msg'] );
		}

		// Clean the session.
		$this->session->delete( 'notifications' );
	}
}
