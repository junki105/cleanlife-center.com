<?php

namespace Inpsyde\Restore\Api\Module\Database;

use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;
use Inpsyde\Restore\Api\Module\Registry;

/**
 * Class DatabaseTypeFactory
 *
 * @package Inpsyde\Restore\Api\Module\Database
 */
class DatabaseTypeFactory
{

    /**
     * @var Translator|null
     */
    private $translation = null;

    /**
     * @var Registry|null
     */
    private $registry = null;

    /**
     * @var array
     */
    private $types = array();

    /**
     * Monolog Logger for Database Types
     *
     * @var \Monolog\Logger
     */
    private $logger = null;

    /**
     * DatabaseTypeFactory constructor.
     *
     * @param array $types
     * @param Registry $registry
     * @param Translator $translation
     */
    public function __construct(array $types, Registry $registry, Translator $translation)
    {
        $this->types = $types;
        $this->registry = $registry;
        $this->translation = $translation;
    }

    /**
     * Database Type
     *
     * @param string $type mysqli or mysql or none for autodetect.
     *
     * @return DatabaseInterface|null
     */
    public function database_type($type = '')
    {
        if (!empty($type)) {
            if (isset($this->types[$type])) {
                $db = new $this->types[$type]($this->registry, $this->translation);
                $db->set_logger($this->logger);

                return $db;
            } else {
                return null;
            }
        }

        foreach ($this->types as $type) {
            $database = new $type($this->registry, $this->translation);
            /** @var DatabaseInterface $database */
            if ($database->can_use()) {
                $database->set_logger($this->logger);

                return $database;
            }
        }

        return null;
    }

    public function set_logger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
