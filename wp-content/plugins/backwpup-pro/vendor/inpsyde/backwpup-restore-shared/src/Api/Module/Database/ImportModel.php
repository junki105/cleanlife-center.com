<?php
/**
 * Import Model
 *
 * @since   1.0.0
 * @package Inpsyde\Restore\Api\Module\Database
 */

namespace Inpsyde\Restore\Api\Module\Database;

use Exception;
use Inpsyde\Restore\AjaxHandler;
use Inpsyde\Restore\Api\Module\ImportInterface;
use Inpsyde\Restore\Api\Module\Registry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ImportModel
 *
 * @since   1.0.0
 * @package Inpsyde\Restore\Api\Module\Database
 */
final class ImportModel implements ImportInterface
{

    /**
     * Factory Database Type
     *
     * @var DatabaseTypeFactory
     */
    private $db_connection;

    /**
     * Factory Importer File
     *
     * @var ImportFileFactory
     */
    private $file_import;

    /**
     * Registry
     *
     * @var Registry
     */
    private $registry;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Translator
     *
     * @var TranslatorInterface The translator to use to translate the strings.
     */
    private $translator;

    /**
     * Context
     *
     * The context in which the instance operate. Default is `event_source` means
     * the instance is used in a EventSource request.
     *
     * @var string The context in which the instance operate.
     */
    private $context = AjaxHandler::EVENT_SOURCE_CONTEXT;

    /**
     * Import_Model constructor.
     *
     * @param DatabaseTypeFactory $db_connection
     * @param ImportFileFactory $file_import
     * @param Registry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(
        DatabaseTypeFactory $db_connection,
        ImportFileFactory $file_import,
        Registry $registry,
        LoggerInterface $logger,
        TranslatorInterface $translator
    ) {

        $this->db_connection = $db_connection;
        $this->file_import = $file_import;
        $this->registry = $registry;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function import()
    {
        $errors = 0;
        $database = $this->db_connection->database_type();
        $database->connect();

        // Set sql_mode to prevent strict error on zero date
        $database->query("SET sql_mode = ''");
        // Prevent issues when dropping a table that has a foreign key.
        $database->query('SET FOREIGN_KEY_CHECKS = 0');

        $file = $this->file_import->import_file('sql');
        $file->set_import_file($this->registry->dbdumpfile);

        // Save file size to calculate percentage of file process for output
        $this->registry->dbdumpsize = $file->get_file_size();

        if (isset($this->registry->dbdumppos)) {
            $file->set_position($this->registry->dbdumppos);
        } else {
            $this->registry->dbdumppos = array();
        }

        $query = $file->get_query();

        while (!empty($query)) {
            try {
                $response = $database->query($query);
            } catch (Exception $exc) {
                $this->logger->error($exc->getMessage());
                ++$errors;
            }

            // Only for event source calls and only when rows are stored into the db.
            if ($response && AjaxHandler::EVENT_SOURCE_CONTEXT === $this->context) {
                preg_match('/`(\w+)`/i', $query, $match);
                $this->echo_event_data(
                    'message',
                    array(
                        'message' => isset($match[1]) ? $match[1] : '',
                        'state' => 'progress',
                    )
                );
            }

            // Update the restoring progress.
            $this->registry->dbdumppos = $file->get_position();
            $this->save_progress();

            $query = $file->get_query();
        }

        $database->query('SET FOREIGN_KEY_CHECKS = 1');

        // After we have finished to import, let's update one last time the progress.
        // We cannot get the 100% in the log because may be the latest query return a value valuable as false,
        // and the progress is not saved the last time.
        $this->registry->dbdumppos = $file->get_position();
        $this->registry->finish_job('db_restore');
        $this->save_progress();

        // Clean.
        unset(
            $import_file,
            $database,
            $query
        );

        if (AjaxHandler::EVENT_SOURCE_CONTEXT === $this->context) {
            $message = $errors
                ? $this->translator->trans('Database restored with errors.')
                : $this->translator->trans('Database restored successfully.');

            $this->echo_event_data(
                'message',
                array(
                    'message' => $message,
                    'state' => 'done',
                )
            );
        }
    }

    /**
     * Save Restore Progress
     *
     * Helper method for calculating the progress of DB file restore and saving it to registry.
     */
    private function save_progress()
    {
        $progress = (int)floor(($this->registry->dbdumppos['pos'] / $this->registry->dbdumpsize) * 100);

        // Log valid progress and not log the same value multiple times.
        if (0 < $progress && $progress > $this->registry->migration_progress) {
            $this->registry->update_progress($progress);
            $this->logger->info(
                sprintf(
                    'SQL File restore: dbdumpos [%d], dbdumpsize [%d], progress [%d]',
                    $this->registry->dbdumppos['pos'],
                    $this->registry->dbdumpsize,
                    $progress
                )
            );
        }
    }

    /**
     * Event Source Output
     *
     * @param string $event The type of the message. Message, Error, Log...
     * @param array $data The data to output.
     */
    private function echo_event_data($event, array $data)
    {
        echo "event: {$event}\n"; // phpcs:ignore
        echo 'data: ' . wp_json_encode($data) . "\n\n";
        flush();
    }
}
