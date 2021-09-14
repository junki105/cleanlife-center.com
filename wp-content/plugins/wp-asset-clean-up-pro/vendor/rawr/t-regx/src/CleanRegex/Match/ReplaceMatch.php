<?php
namespace CleanRegex\Match;

use CleanRegex\Internal\Arguments;

class ReplaceMatch extends Match
{
    /** @var int */
    private $offsetModification;

    /**
     * @param string $subject
     * @param int    $index
     * @param array  $matches
     * @param int    $offsetModification
     */
    public function __construct($subject, $index, array $matches, $offsetModification)
    {
        Arguments::string($subject)->integer($index)->integer($offsetModification);

        parent::__construct($subject, $index, $matches);
        $this->offsetModification = $offsetModification;
    }

    /**
     * @return int
     */
    public function modifiedOffset()
    {
        return $this->offset() + $this->offsetModification;
    }
}
