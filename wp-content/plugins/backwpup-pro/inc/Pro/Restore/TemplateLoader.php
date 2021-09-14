<?php
/**
 * Template
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @package   backwpup-pro
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace Inpsyde\BackWPup\Pro\Restore;

use Inpsyde\BackWPup\Pro\Restore\LogDownloader\DownloaderFactory;
use Inpsyde\Restore\Log\LevelExtractor;
use SplFileObject;

/**
 * Class Template
 *
 * @package Inpsyde\Restore
 */
class TemplateLoader {

	/**
	 * Step
	 *
	 * @var int The current step to load
	 */
	private $step = 1;

	/**
	 * Skip
	 *
	 * If the current step must be skipped
	 *
	 * @var bool True to skip, false otherwise
	 */
	private $skip = false;

	/**
	 * Default step
	 *
	 * @var string The default step if none can be set
	 */
	private $default_step_view = 'step1';

	/**
	 * Step List
	 *
	 * @var array Step's list
	 */
	private $list = array();

	/**
	 * Container
	 *
	 * @var \Pimple\Container The container of the instances
	 */
	private $container;

	/**
	 * TemplateLoader constructor
	 *
	 * @param \Pimple\Container $container The container of the instances.
	 */
	public function __construct( $container ) {

		$this->container = $container;

		// Default to one. The upload step.
		// phpcs:disable
		$this->step = (int) ( isset( $_GET['step'] )
			? filter_var( $_GET['step'], FILTER_SANITIZE_NUMBER_INT )
			: $this->step
		);
		// phpcs:enable
	}

	/**
	 * Load
	 *
	 * @return $this
	 */
	public function load() {

		$self = $this;

		add_action( 'backwpup_restore_upload_content', array( $this, 'template' ) );
		add_action(
			'backwpup_restore_before_upload_content',
			function () use ( $self ) {

				$self->template( 'dashboard' );
			}
		);
		add_action(
			'backwpup_restore_before_main_content',
			function () use ( $self ) {

				$self->template( 'top' );
			}
		);
		add_action(
			'backwpup_restore_main_content',
			function () use ( $self ) {

				$self->template( 'action' );
			}
		);

		return $this;
	}

	/**
	 * Template
	 *
	 * @param string $which Which Template to load.
	 *
	 * @return TemplateLoader $this The instance for concatenation
	 */
	public function template( $which = '' ) {

		$this->set_context();

		// Retrieve the item for which load the template.
		$item = $this->item( $which );

		// Prevent infinite loop or max call stack.
		if ( $which && ! $item ) {
			return $this;
		}

		// If item found, load it.
		if ( $item ) {
			backwpup_template( $item['bind'], $item['view'] );

			return $this;
		}

		backwpup_template( $this, '/pro/restore/main.php' );

		return $this;
	}

	/**
	 * Container
	 *
	 * @param string $what What container to retrieve.
	 *
	 * @return mixed|null The instance request or empty if the instance doesn't exists
	 */
	public function container( $what = '' ) {

		if ( ! $what ) {
			return $this->container;
		}

		return isset( $this->container[ $what ] ) ? $this->container[ $what ] : null;
	}

	/**
	 * Set Context for step
	 *
	 * @return void
	 */
	private function set_context() {

		// Get the base views path.
		$path = untrailingslashit( \BackWPup::get_plugin_data( 'plugindir' ) ) . '/views/pro/restore';

		// Top template.
		$this->list['top'] = $this->set_step( 'top', $path );
		// Action Template.
		$this->list['action'] = $this->set_step( 'action', $path );
		// Dashboard Template.
		$this->list['dashboard'] = $this->set_dashboard();
	}

	/**
	 * Set Step
	 *
	 * @param string $portion The portion of the view for which set the data.
	 * @param string $path    The base path where looking for the template view.
	 *
	 * @return array The data needed by the view
	 */
	private function set_step( $portion, $path ) {

		// Action Template
		$item = array(
			'bind' => $this,
			'view' => "{$path}/steps/step{$this->default_step_view}_{$portion}.php",
		);

		if ( ! $this->skip && file_exists( "{$path}/steps/step{$this->step}_{$portion}.php" ) ) {
			$item['bind'] = $this->create_bind_from_step( $this->step );
			$item['view'] = "/pro/restore/steps/step{$this->step}_{$portion}.php";
		}

		return $item;
	}

    /**
     * Set Dashboard
     *
     * @return array The data needed by the view
     */
    private function set_dashboard()
    {
        // Download Url view.
        $downloaderFactory = new DownloaderFactory();

        try {
            $downloader = $downloaderFactory->create();
        } catch (\RuntimeException $exc) {
            return array();
        }

        return array(
            'bind' => (object)array(
                'downloader' => $downloader,
            ),
            'view' => '/pro/restore/dashboard.php',
        );
    }

	/**
	 * Create bind object for the view
	 *
	 * @param int $step The step for which create the bind object.
	 *
	 * @return null|object The bind object or null if none of the step value match
	 */
	private function create_bind_from_step( $step ) {

		$bind = array();

		switch ( $step ) {
			case 2 :
				$backup_upload = $this->container( 'backup_upload' );
				$bind          = array(
					'backup_upload'     => $backup_upload,
					'upload_is_archive' => $backup_upload::upload_is_archive(
						$this->container( 'registry' )->uploaded_file
					),
					'upload_is_sql'     => $backup_upload::upload_is_sql(
						$this->container( 'registry' )->uploaded_file
					),
				);
				break;
            case 5:
                /** @var LevelExtractor $levelExtractor */
                $levelExtractor = $this->container('level_extractor_factory')->create();
                $logFile = new SplFileObject($this->container('log_file'));
                $bind['errors'] = $levelExtractor->extractError($logFile);
                break;
			default:
				break;
		}

		// Set nonce to use within the template.
		$bind['nonce'] = wp_create_nonce( 'backwpup_action_nonce' );

		return (object) $bind;
	}

	/**
	 * Retrieve the item from the list
	 *
	 * @param string $item The item to retrieve from the list.
	 *
	 * @return array The item found or empty array if the requested item doesn't exists
	 */
	private function item( $item ) {

		return isset( $this->list[ $item ] ) ? $this->list[ $item ] : array();
	}
}
