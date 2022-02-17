<?php

interface SpecificationInterface
{
    function isSatisfiedBy($item);
    function and_(SpecificationInterface $other_specification);
    function or_(SpecificationInterface $other_specification);
}

abstract class CompositeSpecification implements SpecificationInterface
{
    protected $filterValues;
    
    public function setFilterValues($filterValues)
    {
        /*
         * Copy array values into keys
         * So that we can use isset for matching
         */ 
        $this->filterValues = array_combine($filterValues,$filterValues);
    }
    
    function and_(SpecificationInterface $other_specification)
    {
        return new AndSpecification($this,$other_specification);
    }
    
    function or_(SpecificationInterface $other_specification)
    {
        return new OrSpecification($this,$other_specification);
    }
}

class AndSpecification extends CompositeSpecification
{
    private $_first_specification;
    private $_second_specification;
    
    function __construct(SpecificationInterface $first_specification,SpecificationInterface $second_specification)
    {
        $this->_first_specification = $first_specification;
        $this->_second_specification = $second_specification;
    }
    
    function isSatisfiedBy($item)
    {
        return ($this->_first_specification->isSatisfiedBy($item) && $this->_second_specification->isSatisfiedBy($item));
    }
}

class OrSpecification extends CompositeSpecification
{
    private $_first_specification;
    private $_second_specification;
    
    function __construct(SpecificationInterface $first_specification,SpecificationInterface $second_specification)
    {
        $this->_first_specification = $first_specification;
        $this->_second_specification = $second_specification;
    }
    
    function isSatisfiedBy($item)
    {
        return ($this->_first_specification->isSatisfiedBy($item) || $this->_second_specification->isSatisfiedBy($item));
    }
}