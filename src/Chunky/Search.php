<?php

namespace ColbyGatte\Chunky;

class Search
{
    /**
     * @var \ColbyGatte\Chunky\ConstraintInterface[]
     */
    protected $constraints;
    
    /**
     * Search constructor.
     */
    public function __construct()
    {
        $constraintClasses = $this->constraints;
        
        $this->constraints = [];
        
        foreach ($constraintClasses as $constraintClass) {
            $this->addConstraint($constraintClass);
        }
    }
    
    /**
     * @param \ColbyGatte\Chunky\ConstraintInterface $constraint
     *
     * @return $this
     * @throws \Exception
     */
    public function addConstraint($constraint)
    {
        $constraint = is_string($constraint) ? new $constraint : $constraint;
    
        if (! is_subclass_of($constraint, ConstraintInterface::class)) {
            throw new \Exception("$constraint does not implement ConstraintInterface");
        }
        
        $this->constraints[get_class($constraint)] = $constraint;
        
        return $this;
    }
    
    public function removeConstraint($constraint)
    {
        $constraint = is_string($constraint) ? $constraint : get_class($constraint);
        
        unset($this->constraints[$constraint]);
        
        return $this;
    }
    
    /**
     * A Result set of entries is returned as a Page
     *
     * @param \ColbyGatte\Chunky\Page $page
     *
     * @return \ColbyGatte\Chunky\Page
     */
    public function searchOn(Page $page)
    {
        $result = $page->getNotebook()->newPage(false);
        
        foreach ($this->constraints as $constraint) {
            $constraint->setPage($page);
        }
        
        foreach ($page->getEntries() as $entry) {
            if ($this->passesAllConstraints($entry)) {
                $result->addEntry($entry);
            }
        }
        
        return $result;
    }
    
    /**
     * A SearchReport is instantiated from the Entry (which will add the report to itself)
     *
     * Each constraint is assigned the entry and then used to test for pass/fail, and the result is logged on the search report.
     *
     * @param \ColbyGatte\Chunky\Entry $entry
     *
     * @return bool
     */
    public function passesAllConstraints(Entry $entry)
    {
        $searchReport = $entry->newSearchReport();
        
        foreach ($this->constraints as $class => $constraint) {
            $constraint->setEntry($entry);
            $searchReport->logConstraint($constraint, $constraint->passesTest($entry));
        }
        
        // Reset the Entry on each constraint
        foreach ($this->constraints as $class => $constraint) {
            $constraint->setEntry(null);
        }
        
        return $searchReport->passesAll();
    }
}