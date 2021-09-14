<?php

namespace Inpsyde\Restore\Api\Module\Database;

use Symfony\Component\Translation\Translator;

class ImportFileFactory
{

    /**
     * @var Translator|null
     */
    private $translation = null;

    /**
     * @var array
     */
    private $types = array();

    /**
     * ImportFileFactory constructor.
     *
     * @param array $types
     * @param Translator $translation
     */
    public function __construct(array $types, Translator $translation)
    {
        $this->types = $types;
        $this->translation = $translation;
    }

    /**
     *
     * @param string $type
     *
     * @return ImportFileInterface|null
     */
    public function import_file($type = 'sql')
    {
        if (!empty($type) && isset($this->types[$type])) {
            return new $this->types[$type]($this->translation);
        }

        return null;
    }
}
