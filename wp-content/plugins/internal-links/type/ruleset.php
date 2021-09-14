<?php

namespace ILJ\Type;

/**
 * Ruleset Datatype
 *
 * Provides an iterable container for every ruleset datatype
 *
 * @package ILJ\Type
 * @since   1.0.0
 */
class Ruleset
{
    /**
     * @var   int $ruleset
     * @since 1.0.0
     */
    private $ruleset = array();

    /**
     * @var   int $rule_pointer
     * @since 1.0.0
     */
    private $ruleset_pointer = 0;

    /**
     * Adds a new rule entry to the ruleset container.
     *
     * @since  1.0.0
     * @param  string $pattern The condition for applying the rule
     * @param  string $value   The value that gets applied
     * @param  string $type    Type for the rule (optional)
     * @return bool
     */
    public function addRule($pattern, $value, $type = '')
    {
        if ($pattern != '' && $value != '') {
            $rule            = new \stdClass();
            $rule->pattern   = $pattern;
            $rule->value     = $value;
            $rule->type      = $type;
            $this->ruleset[] = $rule;
            return true;
        }
        return false;
    }

    /**
     * Checks if the container has elements left to iterate.
     *
     * @since  1.0.0
     * @return bool
     */
    public function hasRule()
    {
        return isset($this->ruleset[$this->ruleset_pointer]);
    }

    /**
     * Returns the rule entry from a speficic index within the ruleset container.
     *
     * @since  1.0.0
     * @param  int $index The index of ruleset bag to retrieve a specific ruleset (optional)
     * @return null|object
     */
    public function getRule($index = -1)
    {
        if (!is_numeric($index)) {
            return null;
        }
        $index = ($index >= 0) ? $index : $this->ruleset_pointer;
        if (isset($this->ruleset[$index])) {
            return $this->ruleset[$index];
        }
        return null;
    }

    /**
     * Increments the position of the ruleset_pointer
     *
     * @since  1.0.0
     * @return void
     */
    public function nextRule()
    {
        $this->ruleset_pointer++;
    }

    /**
     * Returns the count of entries in ruleset
     *
     * @since  1.0.0
     * @return int
     */
    public function getRuleCount()
    {
        return count($this->ruleset);
    }

    /**
     * Resets the ruleset pointer
     *
     * @since  1.0.0
     * @return void
     */
    public function reset()
    {
        $this->ruleset_pointer = 0;
    }
}
