<?php

namespace ColbyGatte\Chunky;

/**
 * When running a search, a SearchReport is generated for each entry using @see Entry::newSearchReport()
 *
 * @package ColbyGatte\Chunky
 */
class SearchReport
{
    /**
     * Array of information about the passed constraints.
     *
     * @var array
     */
    protected $passed = [];

    /**
     * Array of information about the failed constraints.
     *
     * @var array
     */
    protected $failed = [];

    /**
     * Log information about a constraint.
     *
     * @param \ColbyGatte\Chunky\ConstraintInterface $constraint
     * @param                                        $passed
     */
    public function logConstraint(ConstraintInterface $constraint, $passed)
    {
        $result = ['shortName' => $constraint->shortName(), 'constraint' => get_class($constraint)];

        if ($passed) {
            $this->passed[] = $result;
        } else {
            $this->failed[] = $result;
        }
    }

    /**
     * Returns true if all tests were passed, false if not.
     *
     * @return bool
     */
    public function passesAll()
    {
        return empty($this->failed);
    }

    /**
     *
     * @return array
     */
    public function getPassedConstraints()
    {
        return $this->passed;
    }

    /**
     * @return array
     */
    public function getFailedConstraints()
    {
        return $this->failed;
    }
}