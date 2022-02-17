<?php

class NationalUSP{
	private $description;
	private $description_type;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

        function getDescription(){
                return $this->description;
        }

        function getDescriptionType(){
                return $this->description_type;
        }

}
?>
