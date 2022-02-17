<?php

/**
 * Specializations
 */
class Specializations
{
    /**
     * @var integer
     */
    private $specialization_id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $synonym;
    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $primary_stream_id;

    /**
     * @var integer
     */
    private $primary_substream_id;


    /**
     * Set specializationId
     *
     * @param integer $specializationId
     * @return Specializations
     */
    public function setSpecializationId($specializationId)
    {
        $this->specialization_id = $specializationId;

        return $this;
    }

    /**
     * Get specializationId
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->specialization_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Specializations
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
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Specializations
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set synonym
     *
     * @param string $synonym
     * @return Specializations
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;

        return $this;
    }

    /**
     * Get synonym
     *
     * @return string 
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * Set primaryStreamId
     *
     * @param integer $primaryStreamId
     * @return Specializations
     */
    public function setPrimaryStreamId($primaryStreamId)
    {
        $this->primary_stream_id = $primaryStreamId;

        return $this;
    }

    /**
     * Get primaryStreamId
     *
     * @return integer 
     */
    public function getPrimaryStreamId()
    {
        return $this->primary_stream_id;
    }

    /**
     * Set primarySubStreamId
     *
     * @param integer $primarySubStreamId
     * @return Specializations
     */
    public function setPrimarySubStreamId($primarySubStreamId)
    {
        $this->primary_substream_id = $primarySubStreamId;

        return $this;
    }

    /**
     * Get primarySubStreamId
     *
     * @return integer 
     */
    public function getPrimarySubStreamId()
    {
        return $this->primary_substream_id;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }

    function getObjectAsArray() {
        return get_object_vars($this);
    }

    /**
     * Gets the value of type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param string $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
}
