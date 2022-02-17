<?php

class NationalResearchProject{
	private $description;
	private $description_type;

	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
?>