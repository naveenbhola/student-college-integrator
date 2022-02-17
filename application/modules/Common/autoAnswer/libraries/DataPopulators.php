<?php

class DataPopulators{

	public function __construct(){
		$this->CI=& get_instance();
		//$this->CI->load->library('session');
	}


	public function populateRequestObjectFromSession($requestObject){

		if(isset($_SESSION['name'])){
			$requestObject->setName($_SESSION['name']);
		}

		if(isset($_SESSION['phone'])){
			$requestObject->setPhone($_SESSION['phone']);
		}

		if(isset($_SESSION['email'])){
			$requestObject->setEmail($_SESSION['email']);
		}

		if(isset($_SESSION['mainAttrSet'])){
			$requestObject->setMainAttrSet($_SESSION['mainAttrSet']);
		}

		if(isset($_SESSION['mainAttrValue'])){
			$requestObject->setMainAttrValue($_SESSION['mainAttrValue']);
		}

		if(isset($_SESSION['prefLocation'])){
			$requestObject->setPrefLocation($_SESSION['prefLocation']);
		}

		if(isset($_SESSION['instituteObject'])){
			$sessionInstituteObject = json_decode($_SESSION['instituteObject']);
			$oldInstituteObj = $requestObject->getInstituteObject();
			$oldInstituteObj->setValue($sessionInstituteObject->value);
			$oldInstituteObj->setId($sessionInstituteObject->id);
			$oldInstituteObj->setType($sessionInstituteObject->type);
			$oldInstituteObj->setBaseCourse($sessionInstituteObject->baseCourse);
			$oldInstituteObj->setPreferredChoice($sessionInstituteObject->preferredChoice);
			$oldInstituteObj->setSelectedAttrList($sessionInstituteObject->selectedAttrList);
			$oldInstituteObj->setClientCourseArray($sessionInstituteObject->clientCourseArray);
			$requestObject->setInstituteObject($oldInstituteObj);
		}
		
	}

	function json_encode_private($object) {
        $public = [];
        $reflection = new ReflectionClass($object);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $public[$property->getName()] = $property->getValue($object);
        }
        return json_encode($public);
    }
}
?>