<?php

class CourseLocationAttribute
{
	private $course_location_attribute_id;
	private $attribute_type;
	private $attribute_value;

	function __construct()
	{

	}
	
	public function getId()
	{
		return $this->course_location_attribute_id;
	}
	
	public function getName()
	{
		return $this->attribute_type;
	}
	
	public function getValue()
	{
		return $this->attribute_value;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
}