<?php

class HardBounceTracking extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("common/HardBounceTrackingModel");

    }




    public function updateHardBounceStatus($mode = 'daily'){
	
		$this->validateCron();
    	error_log("************bounce***********getting data from server start");
    	$result = $this->_getDataFromServer($mode);
    	error_log("************bounce***********getting data from server end");
    	
    	$inClause = "";
    	if(count($result) > 0)
		{	
			// Copy Data to Tables in Shiksha and Mailer DB
			error_log("************bounce***********putting data to shiksha");
			$this->_copyDataToDB($result,$mode,'shiksha');			
			error_log("************bounce***********putting data to mailer");
			$this->_copyDataToDB($result,$mode,'mailer');

			error_log("************bounce***********generating in clause for email");
			// Generate InClause for fetching userid from tuserflag
			$inClauseEmail = $this->_getInClause($result,'email_id');
			$inClauseIds = "";
	       	unset($result);

	       	error_log("************bounce***********get user id from tuser");
	       	// Get UserID by Email from tuser
	       	$userIdArray = $this->HardBounceTrackingModel->getUserIds($inClauseEmail);
	   		
	       	if(count($userIdArray) > 0){

	       		error_log("************bounce***********generating in clause for ids");
	       		// Generate InClause for tuserflag update
	       		$inClauseIds = $this->_getInClause($userIdArray,'userid');

	       		error_log("************bounce***********updating table tuserflag and tuserdata");
	       		// Update hardBounce in DB
	       		$this->HardBounceTrackingModel->updateUserFlaginDB($inClauseIds);
	       		unset($inClauseIds);

	       		error_log("************bounce***********Indexing for SOLR");

				$extraData = "{'personalInfo:true'}";
				$this->load->model("response/responsemodel");
				$responsemodel = new responsemodel();

	       		// Index the users for SOLR
	       		foreach ($userIdArray as $key=>$row) {

	       			$userIdArray[$key]['userId'] = $row['userid'];
	       			unset($userIdArray[$key]['userid']);
	       			$userIdArray[$key]['queueTime'] = date('Y-m-d H:i:s');
	       			$userIdArray[$key]['status'] = 'queued';

					// add user to elastic indexing queue
	       			$responsemodel->insertInIndexingQueue($row['userid'], '','', $extraData);

	       		}
       			$this->HardBounceTrackingModel->addUserToIndexingQueue($userIdArray);	
	       		
	       		unset($userIdArray);
	       	}
	       	error_log("************bounce***********updating isprocessing of shiksha and mailer");
	       	$this->HardBounceTrackingModel->updateProcessingStatusShiksha($inClauseEmail);
	       	$this->HardBounceTrackingModel->updateProcessingStatusMailer($inClauseEmail);

	       	
	       	$this->HardBounceTrackingModel->updateProcessingStatusBounceLog($inClauseEmail);
	       	unset($inClauseEmail);
		}
    }

    // Function to generate the In Clause with DB Column name and
    private function _getInClause($array,$column_name){
    		$inClause = "(";

	        foreach ($array as $row) {
	            $inClause .= "'".$row[$column_name]."',";
	        }
	        unset($array);
	        $inClause = substr($inClause, 0,-1);
	        $inClause .= ")";
			return $inClause;
    }

    /**
	 * Function to get the HardBounced email data from server(51)
	 * @param string $mode
	 */
    private function _getDataFromServer($mode='daily'){

    	$data = array();
    	if($mode == 'daily'){
    		$data = $this->HardBounceTrackingModel->getHardBounceEmailDaily();
    	} else if($mode == 'forever') {
			$data = $this->HardBounceTrackingModel->getHardBounceEmailForever();
    	}
    	return $data;
    }

    private function _copyDataToDB($data,$mode='daily',$db='shiksha'){
    	
    	if(!empty($data)){
    		$this->HardBounceTrackingModel->copyDataToDB($data,$db,$mode);	
    	}
    	unset($data);
    }
    
    
}