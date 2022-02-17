<?php

class ChildPageExists{

	private $aboutCollege;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

	function getAboutCollege() {
		return $this->aboutCollege;
	}

}
