<?php

namespace Inpsyde\Restore\Api\Module\Database;

use Inpsyde\Restore\Api\Module\Database\Exception as Exception;
use Inpsyde\Restore\Api\Exception\ExceptionLinkHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;
use Inpsyde\Restore\Api\Module\Registry;

/**
 * Class MysqliDatabaseType
 *
 * @package Inpsyde\Restore\Api\Module\Database
 */
final class MysqliDatabaseType implements DatabaseInterface
{
    /**
     * Mysqli
     *
     * @var resource
     */
    private $mysqli = null;

    /**
     * Translator
     *
     * @var Translator|null
     */
    private $translation = null;

    /**
     * Registry
     *
     * @var Registry|null
     */
    private $registry = null;

    /**
     * Logger
     *
     * @var \Monolog\Logger
     */
    private $logger = null;

    /**
     * MysqliDatabaseType constructor.
     *
     * @param Registry $registry
     * @param Translator $translation
     */
    public function __construct(Registry $registry, Translator $translation)
    {
        $this->registry = $registry;
        $this->translation = $translation;
    }

    /**
     * @inheritDoc
     */
    public function connect()
    {
        $this->mysqli = mysqli_init(); // phpcs:ignore

        if (!$this->mysqli) {
            throw new Exception\DatabaseConnectionException(
                $this->translation->trans('Cannot init MySQLi database connection')
            );
        }

        if (!$this->mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) { // phpcs:ignore
            throw new Exception\DatabaseConnectionException(
                $this->translation->trans('Setting of MySQLi connection timeout failed')
            );
        }

        $dbhost = $this->registry->dbhost;
        $dbport = null;
        $dbsocket = null;
        $dbcharset = $this->registry->dbcharset;

        if (strstr($dbhost, ':')) {
            $hostparts = explode(':', $this->registry->dbhost, 2);
            $hostparts[0] = trim($hostparts[0]);
            $hostparts[1] = trim($hostparts[1]);

            if (empty($hostparts[0])) {
                $dbhost = null;
            } else {
                $dbhost = $hostparts[0];
            }

            if (is_numeric($hostparts[1])) {
                $dbport = (int)$hostparts[1];
            } else {
                $dbsocket = $hostparts[1];
            }
        }

        if (!$dbhost) {
            throw new Exception\DatabaseConnectionException(
                $this->translation->trans('No valid connection data. Please check the host is reachable.')
            );
        }

        // Connect to Database.
        $connect = $this->mysqli->real_connect(
            $dbhost,
            $this->registry->dbuser,
            $this->registry->dbpassword,
            $this->registry->dbname,
            $dbport,
            $dbsocket
        );
        if (!$connect) {
            throw new Exception\DatabaseConnectionException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translation,
                    sprintf(
                        $this->translation->trans('Cannot connect to MySQL database %1$d: %2$s'),
                        mysqli_connect_errno(), // phpcs:ignore
                        mysqli_connect_error() // phpcs:ignore
                    ),
                    'DATABASE_CONNECTION_PROBLEMS'
                )
            );
        }

        if (!$this->mysqli->set_charset($dbcharset)) {
            throw new Exception\DatabaseConnectionException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translation,
                    $this->mysqli->error,
                    'DATABASE_CONNECTION_PROBLEMS'
                )
            );
        }

        $this->logger->info(
            sprintf(
                "Current character set: %s\n",
                $this->mysqli->character_set_name()
            )
        );
    }

    /**
     * Do an SQL Query
     *
     * @param string $query The SQl Query.
     *
     * @return int Affected/queried rows.
     * @throws Exception\DatabaseQueryException In case of query error.
     *
     */
    public function query($query)
    {
        $res = $this->mysqli->query($query);
        if ($this->mysqli->error) {
            throw new Exception\DatabaseQueryException(
                sprintf(
                    $this->translation->trans('Database error %1$s for query %2$s'),
                    $this->mysqli->error,
                    $query
                )
            );
        }

        if (isset($res->num_rows) && $res->num_rows > 0) {
            return $res->num_rows;
        } elseif ($this->mysqli->affected_rows > 0) {
            return $this->mysqli->affected_rows;
        }

        return 0;
    }

    /**
     * Disconnect from Database
     *
     * @return bool True on success, false otherwise.
     */
    public function disconnect()
    {
        if ($this->mysqli !== null) {
            $this->mysqli->close();

            return true;
        }

        return false;
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Can this Database type be used
     *
     * @return bool True if mysqli class exists, false otherwise.
     */
    public function can_use()
    {
        if (class_exists('Mysqli')) {
            return true;
        }

        return false;
    }

    /**
     * Setter method to add a logger
     *
     * @param LoggerInterface $logger Logger object, e.g. Monolog.
     *
     * @return void
     */
    public function set_logger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
