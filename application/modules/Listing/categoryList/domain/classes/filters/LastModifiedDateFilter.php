<?php

class LastModifiedDateFilter extends AbstractFilter
{
	private $request;
	
    function __construct($request)
    {
		$this->request = $request;
        parent::__construct();
    }
    
	public function getFilteredValues()
	{
		return $this->values;
	}

	public function extractValue(Institute $institute,Course $course)
	{
		$lastModifiedDate = $course->getLastUpdatedDate();
		return $lastModifiedDate;
	}

	public function addValue(Institute $institute, Course $course)
	{
		$this->extractValue($institute, $course);
		$lastModifiedDate = $this->extractValue($institute, $course);
		$this->values[$lastModifiedDate] = $lastModifiedDate;
	}
}
