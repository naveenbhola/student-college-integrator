<?php
/**
 * Class for exception in case of city list
 */

 /**
 * Class file for exception in case of city list
 */
class CityListException extends Exception {
	/**
	 *error code to error message mapping
	 * @var array
	 */
	private $_error_message_array = array(400=>"Citylist file not found",500=>"City list fie supplied is not redable, please change the access",
	600=>"Data base connection was not established",700=>"Arrays supplied to method getListofCitiesTobeAddedinTheDB are empty",
	800=>"Arrays supplied to method getListofCitiesTobeDeletedFromTheDB are empty",
	900=>"updateCity method has failed",1000=>"Insert city method has failed",
	1100=>"reference table check has failed: method checkReferenceInForeignTablesForDeletedcities",
	1200=>"mapped city is not present in the method getDeletedCitiesMapping",
	1300=>"js files supplied are not writable or readable or file array supplied is empty please check");
	
	/**
	 * Field for storing error code
	 * @var integer
	 */
	private $error_code;
	
	/**
	 * Field for storing error message
	 * @var string
	 */
	private $error_message;
	
	/**
	 * Constructor
	 *
	 * @param string $message
	 * @param integer $connection
	 */
    public function __construct ($message, $code) {
    	$this->error_code = $code;
    	$this->error_message = $message;
    	parent::__construct($message, $code);
    }
	/**
	 * return error message
	 *
	 * @param integer $error_code
	 */
	
	public function getExceptionMessage($error_code) {
		//TODO
		return $this->_error_message_array[$error_code];
	}
	/**
	 * Return error code
	 * @return integer
	 */
	public function getErrorcode() {
		return $this->error_code;
		
	}
}

?>