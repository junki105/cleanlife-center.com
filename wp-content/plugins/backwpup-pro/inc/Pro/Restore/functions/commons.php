<?php
/**
 * BackWPup Restore functions
 */

namespace Inpsyde\BackWPup\Pro\Restore\Functions;

use Inpsyde\BackWPup\Archiver\Extractor;
use Inpsyde\BackWPup\Archiver\Factory;
use Inpsyde\Restore\AjaxFileLocker;
use Inpsyde\Restore\AjaxHandler;
use Inpsyde\Restore\Api\Controller\DecryptController;
use Inpsyde\Restore\Api\Controller\LanguageController;
use Inpsyde\Restore\Api\Controller\JobController;
use Inpsyde\Restore\Api\Exception\ExceptionHandler;
use Inpsyde\Restore\Api\Module\Decryption\Decrypter;
use Inpsyde\Restore\Api\Module\Registry;
use Inpsyde\Restore\Api\Module\Decompress;
use Inpsyde\Restore\Api\Module\Translation;
use Inpsyde\Restore\Api\Module\Database;
use Inpsyde\Restore\Api\Module\Upload;
use Inpsyde\Restore\Api\Error\ErrorHandler;
use Inpsyde\Restore\Api\Module\Restore;
use Inpsyde\Restore\Api\Module\Manifest\ManifestFile;
use Inpsyde\Restore\Api\Module\Session\Session;
use Inpsyde\Restore\EventSource;
use Inpsyde\Restore\Log\LevelExtractorFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Translation\Loader\PoFileLoader;
use \Pimple\Container;
use phpseclib\Crypt\AES;
use phpseclib\Crypt\RSA;

/**
 * Container
 *
 * @param string|null $name The name of the data to retrieve or null to retrieve the whole container.
 *
 * @return mixed|\Pimple\Container
 */
function restore_container( $name ) {

	static $container;

	// Clean up the name.
	$name = sanitize_key( $name );

	if ( ! $container ) {
		// Upload Dir.
		$upload_dir = wp_upload_dir( null, true, false );

		// DI Container.
		$container = new Container();

		// Project Paths, it's the root WordPress installation.
		$container['project_root'] = ABSPATH;

		// Create the restore directory to use to store the temporary data for restoring.
		$container['project_temp'] = untrailingslashit( $upload_dir['basedir'] ) . '/backwpup-restore';

		// Logger
		$container['log_file'] = $container['project_temp'] . '/restore.log';
		$container['logger'] = function ( Container $container ) {

			$logger = new Logger( 'restore' );
			$logger->pushHandler( new StreamHandler( $container['log_file'], Logger::INFO ) );

			return $logger;
		};

		// Registry.
		$container['registry'] = function ( Container $container ) {

			$registry = new Registry( $container['project_temp'] . '/restore.dat');
			$registry->init();

			return $registry;
		};

		// Translation.
		$container['pofileloader'] = function () {

			return new PoFileLoader();
		};
		$container['translation'] = function ( Container $container ) {

			$translation = new Translation\RestoreTranslation(
				$container['registry'],
				$container['logger'],
				\BackWPup::get_plugin_data(
					'plugindir'
				) . '/vendor/inpsyde/backwpup-restore-shared/resources/languages'
			);
			$translation->set_browser_lang( 'po' );
			$translator = $translation->get_translator(
				'\Symfony\Component\Translation\Translator',
				$container['pofileloader'],
				'po'
			);

			return $translator;
		};

        // Decompressor.
        $container['decompress_state'] = function (Container $container) {
            return new Decompress\State($container['registry']);
        };
        $container['decompress_state_updater'] = function (Container $container) {
            return new Decompress\StateUpdater($container['registry']);
        };
        $container['decompress'] = function (Container $container) {
            return new Decompress\Decompressor(
                $container['registry'],
                $container['logger'],
                $container['translation'],
                $container['extractor_extractor'],
                $container['decompress_state'],
                $container['decompress_state_updater']
            );
        };

		// Error.
		$container['error_handler'] = function ( Container $container ) {

			$logger = new Logger( 'errors' );
			$logger->pushHandler(
				new StreamHandler(
					$container['project_temp'] . '/debug.log',
					Logger::ERROR
				)
			);

			return new ErrorHandler($logger, $container['registry']);
		};

		// Exception Handler.
		$container['exception_handler'] = function ( Container $container ) {

			$logger = new Logger( 'exceptions' );
			$logger->pushHandler(
				new StreamHandler(
					$container['project_temp'] . '/debug.log',
					Logger::WARNING
				)
			);

			return new ExceptionHandler(
				$logger,
				$container['session'],
				$container['translation'],
				$container['registry']
			);
		};

		// Controller.
		$container['job_controller'] = function ( Container $container ) {

			$controller = new JobController(
				$container['registry'],
				$container['translation'],
				$container['logger'],
				$container['decompress'],
				$container['manifest'],
				$container['session'],
				$container['backup_upload'],
				$container['database_factory'],
				$container['database_import'],
				$container['restore_files'],
				$container['decrypter']
			);

			return $controller;
		};
		$container['language_controller'] = function ( Container $container ) {

			return new LanguageController( $container['registry'] );
		};
		$container['decrypt_controller'] = function ( Container $container ) {

			return new DecryptController(
				$container['decrypter'],
				$container['translation']
			);
		};

		// Upload.
		$container['backup_upload'] = function ( Container $container ) {

			return new Upload\BackupUpload( $container['registry'], $container['translation'] );
		};

		// Database.
		$container['database_factory'] = function ( Container $container ) {

			$types = array(
				'mysqli' => '\Inpsyde\Restore\Api\Module\Database\MysqliDatabaseType',
				'mysql'  => '\Inpsyde\Restore\Api\Module\Database\MysqlDatabaseType',
			);
			$db_factory = new Database\DatabaseTypeFactory( $types, $container['registry'], $container['translation'] );
			$db_factory->set_logger( $container['logger'] );

			return $db_factory;
		};
		$container['database_import_file_factory'] = function ( Container $container ) {

			$types = array(
				'sql' => '\Inpsyde\Restore\Api\Module\Database\SqlFileImport',
			);

			return new Database\ImportFileFactory( $types, $container['translation'] );
		};
		$container['database_import'] = function ( Container $container ) {

			return new Database\ImportModel(
				$container['database_factory'],
				$container['database_import_file_factory'],
				$container['registry'],
				$container['logger'],
				$container['translation']
			);
		};

		// Restore.
		$container['restore_files'] = function ( Container $container ) {

			return new Restore\RestoreFiles( $container['registry'], $container['logger'], $container['translation'] );
		};

		// Manifest File.
		$container['manifest'] = function ( Container $container ) {

			return new ManifestFile( $container['registry'], $container['translation'] );
		};

		// Notification.
		$container['session'] = function () {

			return new Session( $_SESSION ); // phpcs:ignore
		};

		// Decrypt
		$container['decrypter'] = function ( Container $container ) {

			return new Decrypter(
				new AES( AES::MODE_CBC ),
				new RSA(),
				$container['translation'],
                $container['archivefileoperator_factory']
			);
		};

		// Ajax
		$container['event_source'] = function () {
			return new EventSource();
		};
		$container['ajax_handler'] = function (Container $container) {
			return new AjaxHandler(
				$container['job_controller'],
				$container['language_controller'],
				$container['decrypt_controller'],
				$container['registry'],
				$container['translation'],
				$container['logger'],
				$container['event_source'],
				$container['log_file']
			);
		};

        // Extractor
        $container['archivefileoperator_factory'] = function () {
            return new Factory();
        };
        $container['extractor_extractor'] = function (Container $container) {
            return new Extractor(
                $container['logger'],
                $container['archivefileoperator_factory']
            );
        };

        // Log
        $container['level_extractor_factory'] = function() {
            return new LevelExtractorFactory();
        };
	}

	if ( '' === $name ) {
		return $container;
	}

	if ( ! isset( $container[ $name ] ) ) {
		throw new \OutOfBoundsException(
			sprintf(
				'Invalid data request for container. %s doesn\'t exists in the container',
				$name
			)
		);
	}

	return $container[ $name ];
}

/**
 * Registry
 *
 * @todo Move the creation of this object within the container, so we'll pass the values directly to the construct as
 *       an array of arguments.
 *
 * @internal
 *
 * @return \Inpsyde\Restore\Api\Module\Registry The instance with additional properties.
 */
function restore_registry() {

	static $registry;

	if ( ! $registry ) {
		$container = restore_container( null );
		$registry = $container['registry'];

		// Save Project Root in Registry.
		$registry->project_root = $container['project_root'];
		$registry->project_temp = $container['project_temp'];
		$registry->extract_folder = untrailingslashit( $container['project_temp'] ) . '/extract';
		$registry->uploads_folder = untrailingslashit( $container['project_temp'] ) . '/uploads';

		// Create the uploads directory if not exists.
		// In some cases me need to create the uploaded file from third party services and the directory must exists
		// prior to save the file.
		if ( ! file_exists( $registry->uploads_folder ) ) {
			backwpup_wpfilesystem()->mkdir( $registry->uploads_folder );
		}
	}

	return $registry;
}

/**
 * Error Handler Register
 *
 * @param \Pimple\Container $containerontainer The container from which retrieve the error handler instance.
 */
function error_handler_register( $container ) {

	$error_handler = $container['error_handler'];
	$error_handler->register();
}

/**
 * Exception Handler Register
 *
 * @param \Pimple\Container $containerontainer The container from which retrieve the error handler instance.
 */
function exception_handler_register( $container ) {

	$exception_handler = $container['exception_handler'];
	$exception_handler->register();
}

/**
 * Create Project temporary directory
 *
 * @param \Pimple\Container $containerontainer The container of the services.
 *
 * @throws \Exception In case the temporary project directory isn't writable.
 */
function create_project_temp_dir( $container ) {

	$response = \BackWPup_File::check_folder( $container['project_temp'], true );

	if ( $response ) {
		throw new \Exception( $response );
	}
}

/**
 * Restore Boot
 *
 * @return void
 */
function restore_boot() {

	// Session is needed if we want to use notifications.
	session_start(); // phpcs:ignore

	$container = restore_container( null );

	create_project_temp_dir( $container );
	error_handler_register( $container );
	exception_handler_register( $container );

	restore_registry();
}
