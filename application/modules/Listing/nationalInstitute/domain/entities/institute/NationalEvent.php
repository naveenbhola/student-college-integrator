<?php

class NationalEvent{
	private $event_type_id;
	private $event_id;
	private $event_type_name;
	private $event_name;
	private $description;
	private $position;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

        function getEventId(){
                return $this->event_id;
        }

        function getEventName(){
                return $this->event_name;
        }

        function getEventTypeId(){
                return $this->event_type_id;
        }

        function getEventTypeName(){
                return $this->event_type_name;
        }

        function getEventDescription(){
                return $this->description;
        }

        function getEventPosition(){
                return $this->position;
        }

}
?>
