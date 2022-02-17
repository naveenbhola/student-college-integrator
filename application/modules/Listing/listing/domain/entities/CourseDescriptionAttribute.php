<?php
class CourseDescriptionAttribute {

	private $attributeId;
	private $attributeName;
	private $attributeValue;
	private $isPaid;
	private $version;

	function __construct()
	{

	}

	public function getId()
	{
		return $this->attributeId;
	}

	public function getName()
	{
		return $this->attributeName;
	}

	public function getValue()
	{
		return $this->attributeValue;
	}

	public function isPaid() {
		if($this->isPaid == 'yes') {
			return true;
		}
		return false;
	}

	public function getVersion() {
		return $this->version;
	}

	function __set($property,$value)
	{
		$this->$property = $value;
	}

}