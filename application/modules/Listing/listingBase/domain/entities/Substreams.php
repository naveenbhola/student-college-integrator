<?php

/**
 * Substreams
 */
class Substreams
{
    /**
     * @var integer
     */
    private $substream_id;

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
     * @var integer
     */
    private $primary_stream_id;
    /**
     * @var int
     */
    private $display_order;


    /**
     * Set substreamId
     *
     * @param integer $substreamId
     * @return Substreams
     */
    public function setSubstreamId($substreamId)
    {
        $this->substream_id = $substreamId;

        return $this;
    }

    /**
     * Get substreamId
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->substream_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Substreams
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
     * @return Substreams
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
     * @return Substreams
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
     * @return Substreams
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
     * @return Substreams
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

    /**
     * Gets the value of display_order.
     *
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->display_order;
    }

    /**
     * Sets the value of display_order.
     *
     * @param int $display_order the display order
     *
     * @return self
     */
    public function setDisplayOrder($display_order)
    {
        $this->display_order = $display_order;

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
