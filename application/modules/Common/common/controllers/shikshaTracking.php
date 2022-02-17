<?php

class shikshaTracking extends MX_Controller {
	
	private $collection;
	private $userId;
	private $sessionId;
	private $key;
	private $data;
  private $device;

	function __construct(){
        parent::__construct();
    }

  	private function _init(){
  		$this->load->library('session');
  	}

    /* 
     * Make connection to shikshaTracking collection in MongoDb
     */
    function makeConnection(){
        $conn = new MongoClient();

        //MongoConnection->dbName->CollectionName
        $this->collection = $conn->shiksha->shikshaTracking;
    }

    function deviceDetector(){

    }

    /* 
     * Reads the Cookie and sets userId and sessionId
     */
    function setUserDetails(){
    	$this->_init();
    	if(isset($_COOKIE['user'])){
    		$validate = $this->checkUserValidation();
    		if($validate != false){
    			$this->userId = $validate[0]['userid'];
    		}else{
                $this->userId = 0;
            }
    	}else{
            $this->userId = 0;
    	}
        $this->sessionId = $this->session->userdata('session_id');
    }

    /* stores the data to the shikshaTracking Collection
     *
     *  @params : key =>Identifier for tracking
     *            Data => Data associative with tracking
    */

    function tracking($key, $data){
    	$key = isset($_POST['key'])? $this->input->post('key'):$key;  
    	$data = isset($_POST['data'])? $this->input->post('data'):$key;  
    	
    	//Sets userId and sessionId
    	$this->setUserDetails();
      $this->makeConnection();

    }

    function test(){
       $this->makeConnection();

    }


}