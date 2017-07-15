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
        if (! in_array(ConstraintInterface::class, class_implements($constraint))) {
            throw new \Exception("$constraint does not implement ConstraintInterface");
        }
        
        if (is_string($constraint)) {
            $constraint = new $constraint;
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
    
    public function searchOn(Page $chunks)
    {
        $result = $chunks->getNotebook()->newPage();
        
        foreach ($this->constraints as $constraint) {
            $constraint->setPage($chunks);
        }
        
        foreach ($chunks->getEntries() as $chunk) {
            foreach ($this->constraints as $class => $constraint) {
                if ($constraint->passesTest($chunk)) {
                    $result->addEntry($chunk);
                }
            }
        }
        
        return $result;
    }
}