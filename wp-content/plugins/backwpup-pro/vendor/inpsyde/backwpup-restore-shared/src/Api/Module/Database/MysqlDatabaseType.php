<?php

namespace Inpsyde\Restore\Api\Module\Database;

use Inpsyde\Restore\Api\Module\Database\Exception as DatabaseException;
use Inpsyde\Restore\Api\Exception\ExceptionLinkHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;
use Inpsyde\Restore\Api\Module\Registry;

final class MysqlDatabaseType implements DatabaseInterface
{
    /**
     * @var string Mysql Link identifier
     */
    private $mysql_connection = false;

    /**
     * @var Translator|null
     */
    private $translation = null;

    /**
     * @var Registry|null
     */
    private $registry = null;

    /**
     * @var \Monolog\Logger
     */
    private $logger = null;

    /**
     * MysqlDatabaseType constructor.
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
        $args = array();

        $this->mysql_connection = mysql_connect(
            $this->registry->dbhost,
            $this->registry->dbuser,
            $this->registry->dbpassword
        );

        if (!$this->mysql_connection) {
            throw new DatabaseException\DatabaseConnectionException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translation,
                    sprintf(
                        $this->translation->trans('Cannot connect to MySQL database %1$d: %2$s'),
                        mysql_errno(),
                        mysql_error()
                    ),
                    'DATABASE_CONNECTION_PROBLEMS'
                )
            );
        }

        // Connect to Database.
        if (!mysql_select_db($args['dbname'], $this->mysql_connection)) {
            throw new DatabaseException\DatabaseConnectionException(
                ExceptionLinkHelper::translateWithAppropiatedLink(
                    $this->translation,
                    sprintf(
                        $this->translation->trans('Cannot use database %1$d'),
                        $this->registry->dbname
                    ),
                    'DATABASE_CONNECTION_PROBLEMS'
                )
            );
        }

        // Set charset.
        if (!empty($this->registry->dbcharset)) {
            $res = mysql_set_charset($this->registry->dbcharset, $this->mysql_connection);
            if (!$res) {
                throw new DatabaseException\DatabaseConnectionException(
                    ExceptionLinkHelper::translateWithAppropiatedLink(
                        $this->translation,
                        sprintf(
                            $this->translation->trans('Cannot set DB charset to %s'),
                            $this->registry->dbcharset
                        ),
                        'DATABASE_CONNECTION_PROBLEMS'
                    )
                );
            }
        }
    }

    /**
     * Do an SQL Query
     *
     * @param $query string the SQl Query
     *
     * @return int affected/queried rows
     * @throws \Exception
     * @since 2015/08/12
     *
     */
    public function query($query)
    {
        $res = mysql_query($query, $this->mysql_connection);
        if (!$res) {
            throw new Exception\DatabaseQueryException(
                sprintf(
                    $this->translation->trans('Database error %1$s for query %2$s'),
                    mysql_error(),
                    $query
                )
            );
        }

        if ($res !== true) {
            return mysql_num_rows($res);
        } elseif ($res !== false) {
            return mysql_affected_rows($this->mysql_connection);
        }

        return 0;
    }

    /**
     * Disconnect from Database
     *
     * @return bool successful
     * @since 2015/08/12
     */
    public function disconnect()
    {
        if ($this->mysql_connection !== null) {
            mysql_close($this->mysql_connection);

            return true;
        }

        return false;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Can this Database type be used
     *
     * @return bool
     */
    public function can_use()
    {
        if (function_exists('mysql_connect')) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function set_logger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
