<?php

class NationalAcademic{
	private $name;
	private $type_id;
	private $current_designation;
	private $education_background;
	private $professional_highlights;
	private $display_order;

	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
?>