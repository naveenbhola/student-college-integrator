<?php

class NationalScholarship{

	private $scholarship_type_id;
	private $scholarship_type_name;
	private $description;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

        function getScholarshipId(){
                return $this->scholarship_type_id;
        }

        function getScholarshipName(){
                return $this->scholarship_type_name;
        }

        function getScholarshipDescription(){
                return $this->description;
        }

        function getCustomizedScholarshipName() {
                switch ($this->scholarship_type_name) {
                        case 'Scholarship':
                        case 'Others':
                        case 'Discount':
                                return 'Scholarship';
                                break;
                        case 'Financial Assistance':
                                return $this->scholarship_type_name;
                                break;
                        default:
                                return $this->scholarship_type_name;
                                break;
                }
        }

}
?>
