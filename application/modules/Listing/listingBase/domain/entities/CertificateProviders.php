<?php 
/**
 * CertificateProviders
 */
class CertificateProviders{
	/**
	 * @var int
	 */
	private $certificate_provider_id;
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
     * Gets the value of certificate_provider_id.
     *
     * @return int
     */
    public function getCertificationProviderId()
    {
        return $this->certificate_provider_id;
    }

    /**
     * Sets the value of certificate_provider_id.
     *
     * @param int $certificate_provider_id the certification provider id
     *
     * @return self
     */
    public function setCertificationProviderId($certificate_provider_id)
    {
        $this->certificate_provider_id = $certificate_provider_id;

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
     * Gets the array of hierarchy ids mapped to this provider.
     *
     * @return array
     */
    public function getHierarchyMappings()
    {
        return $this->hierarchyMappings;
    }

    /**
     * Sets the array of hierarchy ids mapped to this provider.
     *
     * @param array $hierarchyMappings the heirarchy mappings
     *
     * @return self
     */
    public function setHierarchyMappings(array $hierarchyMappings)
    {
        $this->hierarchyMappings = $hierarchyMappings;

        return $this;
    }

    /**
     * Gets the array of course ids mapped to this provider.
     *
     * @return [type]
     */
    public function getCourseMappings()
    {
        return $this->courseMappings;
    }

    /**
     * Sets the array of course ids mapped to this provider.
     *
     * @param array $courseMappings the course mappings
     *
     * @return self
     */
    public function setCourseMappings(array $courseMappings)
    {
        $this->courseMappings = $courseMappings;

        return $this;
    }

    /**
     * internal
     * @return array
     */
    function getObjectAsArray() {
        return get_object_vars($this);
    }
}
?>