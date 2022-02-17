<?php
/**
 * File representing a rule in custom rules applied on form fields
 */ 
namespace registration\libraries\CustomLogic;

/**
 * Class representing a rule in custom rules applied on form fields
 */ 
class Rule
{
    /**
     * Variable for condition in the rule
     * @var array 
     */ 
    private $conditions;
    
    /**
     * actions in the rule
     * @var array 
     */
    private $actions;
    
    /**
     * condition logic (AND/OR)
     * @var string 
     */
    private $logic;
    
    /**
     * Constructor
     */
    function __construct()
    {
        $this->conditions = array();
        $this->actions = array();
    }
    
    /**
     * Add a new condition to the rule
     *
     * @param object Condition
     */ 
    public function addCondition(Condition $condition)
    {
        $this->conditions[] = $condition;
    }
    
    /**
     * Get conditions in the rule
     *
     * @return array
     */ 
    public function getConditions()
    {
        return $this->conditions;
    }
    
    /**
     * Add a new action to the rule
     *
     * @param object Action
     */ 
    public function addAction(Action $action)
    {
        $this->actions[] = $action;
    }
    
    /**
     * Get actions in the rule
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }
    
    /**
     * Set condition logic
     *
     * @param string $logic
     */
    public function setLogic($logic)
    {
        $this->logic = $logic;
    }
    
    /**
     * Get condition logic
     *
     * @return string
     */
    public function getLogic()
    {
        return $this->logic;
    }
}