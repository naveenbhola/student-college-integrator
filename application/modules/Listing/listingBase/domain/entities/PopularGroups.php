<?php

/**
 * PopularGroups
 */
class PopularGroups{
    /**
     * @var int
     */
    private $popular_group_id;
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
     * Gets the value of popular_group_id.
     *
     * @return int
     */
    public function getPopularGroupId()
    {
        return $this->popular_group_id;
    }

    /**
     * Sets the value of popular_group_id.
     *
     * @param int $popular_group_id the popular group id
     *
     * @return self
     */
    public function setPopularGroupId($popular_group_id)
    {
        $this->popular_group_id = $popular_group_id;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Sets the value of alias.
     *
     * @param string $alias the alias
     *
     * @return self
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Gets the value of synonym.
     *
     * @return string
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * Sets the value of synonym.
     *
     * @param string $synonym the synonym
     *
     * @return self
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;

        return $this;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }

    /**
     * internal
     * @return array
     */
    function getObjectAsArray() {
        return get_object_vars($this);
    }
}