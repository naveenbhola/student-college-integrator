<?php

class ListingViewCount
{
	private $viewCount;
	private $id;

	function __construct()
	{

	}

	public function getCount()
	{
		return $this->viewCount;
	}

	function getId()
	{
		return $this->id;
	}


	function __set($property,$value)
	{
		$this->$property = $value;
	}
}