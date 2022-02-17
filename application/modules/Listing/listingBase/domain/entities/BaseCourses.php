<?php

/**
 * BaseCourses
 */
class BaseCourses
{
    /**
     * @var integer
     */
    private $base_course_id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $level;

    /**
     * @var boolean
     */
    private $is_popular;

    /**
     * @var boolean
     */
    private $is_hyperlocal;

    /**
     * @var boolean
     */
    private $is_executive;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $synonym;

    /**
     * @var integer
     */
    private $credential;

    private $is_dummy;

    /**
     * Set baseCourseId
     *
     * @param integer $baseCourseId
     * @return BaseCourses
     */
    public function setBaseCourseId($baseCourseId)
    {
        $this->base_course_id = $baseCourseId;

        return $this;
    }

    /**
     * Get baseCourseId
     *
     * @return integer
     */
    public function getId()
    {
        return $this->base_course_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BaseCourses
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return ucfirst($this->name);
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return BaseCourses
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set isPopular
     *
     * @param boolean $isPopular
     * @return BaseCourses
     */
    public function setIsPopular($isPopular)
    {
        $this->is_popular = $isPopular;

        return $this;
    }

    /**
     * Get isPopular
     *
     * @return boolean 
     */
    public function getIsPopular()
    {
        return $this->is_popular;
    }

    /**
     * Set isHyperlocal
     *
     * @param boolean $isHyperlocal
     * @return BaseCourses
     */
    public function setIsHyperlocal($isHyperlocal)
    {
        $this->is_hyperlocal = $isHyperlocal;

        return $this;
    }

    /**
     * Get isHyperlocal
     *
     * @return boolean 
     */
    public function getIsHyperlocal()
    {
        return $this->is_hyperlocal;
    }

    /**
     * Set isExecutive
     *
     * @param boolean $isExecutive
     * @return BaseCourses
     */
    public function setIsExecutive($isExecutive)
    {
        $this->is_executive = $isExecutive;

        return $this;
    }

    /**
     * Get isExecutive
     *
     * @return boolean 
     */
    public function getIsExecutive()
    {
        return $this->is_executive;
    }

    /**
     * Magic method to set some property with some value.
     *
     * @param string $property The property in the class to set
     * @param mixed $value The corresponding value
     */
    function __set($property,$value)
    {
        $this->$property = $value;
    }

    function getObjectAsArray() {
        return get_object_vars($this);
    }

    /**
     * Get a base course alias
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set an alias
     *
     * @param string $alias The alias to set
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Get a synonym
     *
     * @return string The base course synonym
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * Set a synonym for a base course
     * @param string $synonym The synonym to be set
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;
    }

    /**
     * Get a credential id for a base course
     *
     * @return int The credential id
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * Set a credential id for a base course
     *
     * @param int $credential The credential id
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
    }


    function getUrlName($getSanitizedUrl = 0) {
        $urlName = $this->getAlias();
        if(empty($urlName)) {
            $urlName = $this->getName();
        }

        if($getSanitizedUrl) {
            $urlName = seo_url($urlName);
        }
        
        return $urlName;
    }

    function isDummy() {
        return $this->is_dummy;
    }
}
