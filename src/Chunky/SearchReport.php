<?php

namespace ColbyGatte\Chunky;

/**
 * When running a search, a SearchReport is generated for each entry using @see Entry::newSearchReport()
 *
 * @package ColbyGatte\Chunky
 */
class SearchReport
{
    protected $passed = [];
    
    protected $failed = [];
    
    public function logConstraint(ConstraintInterface $constraint, $passed)
    {
        $result = ['shortName' => $constraint->shortName(), 'constraint' => get_class($constraint)];
        
        if ($passed) {
            $this->passed[] = $result;
        } else {
            $this->failed[] = $result;
        }
    }
    
    public function passesAll()
    {
        return empty($this->failed);
    }
    
    public function getPassedConstraints()
    {
        return $this->passed;
    }
    
    public function getFailedConstraints()
    {
        return $this->failed;
    }
}