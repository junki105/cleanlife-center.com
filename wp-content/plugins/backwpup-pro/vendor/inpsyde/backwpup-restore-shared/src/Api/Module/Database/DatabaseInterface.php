<?php

namespace Inpsyde\Restore\Api\Module\Database;

use Inpsyde\Restore\Api\Module\Database\Exception\DatabaseConnectionException;
use Inpsyde\Restore\Api\Module\Database\Exception\DatabaseQueryException;
use Psr\Log\LoggerInterface;

/**
 * Interface DatabaseInterface
 *
 * @package Inpsyde\Api\Module\Database
 */
interface DatabaseInterface
{

    /**
     * Connect to Database
     *
     * @return bool successful
     * @throws DatabaseConnectionException
     */
    public function connect();

    /**
     * Do an SQL Query
     *
     * @param string $query The SQl Query.
     *
     * @return int affected/queried rows
     *
     * @throws DatabaseQueryException In case the query cannot be performed
     */
    public function query($query);

    /**
     * Disconnect from Database
     *
     * @return bool successful
     */
    public function disconnect();

    /**
     * Can this Database type be used
     *
     * @return bool
     */
    public function can_use();

    /**
     * Setter method to add a logger
     *
     * @param LoggerInterface $logger The logger instance, e.g. Monolog.
     */
    public function set_logger(LoggerInterface $logger);
}
