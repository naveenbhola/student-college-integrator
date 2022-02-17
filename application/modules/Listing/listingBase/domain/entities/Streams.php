<?php

/**
 * Streams
 */
class Streams
{
    /**
     * @var integer
     */
    private $stream_id;

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
     * Set streamId
     *
     * @param integer $streamId
     * @return Streams
     */
    public function setStreamId($streamId)
    {
        $this->stream_id = $streamId;

        return $this;
    }

    /**
     * Get streamId
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->stream_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Streams
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
     * @return Streams
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
     * @return Streams
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
     * Get Status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

   /**
     * Set Status
     *
     * @param string $status
     * @return Streams
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }

    function getObjectAsArray() {
        return get_object_vars($this);
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
