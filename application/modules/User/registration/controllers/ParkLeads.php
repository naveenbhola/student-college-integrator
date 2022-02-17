<?php
/**
 * File for running the script
 */ 

/**
 * This class is used for running a script
 */ 
class ParkLeads extends MX_Controller
{
    /**
     * For changing LDB status
     */ 
    public function run() {
		
		$this->load->model('registration/parkleadsmodel');
		$this->load->model('user/usermodel');
        $parkleadsmodel = new parkleadsmodel;
		$user = new usermodel;
		
		$allUsersIds = $parkleadsmodel->getleads();
		foreach($allUsersIds as $userId) {
			$parkleadsmodel->updateflag($userId);
			$user->addUserToIndexingQueue($userId);
			
			$content = $userId.",";
			$fp = fopen('/tmp/parkleads.txt','a+');
			fwrite($fp, $content);
			fclose($fp);
		}
		//$result = Modules::run('user/UserIndexer/indexMultipleUsers', $allUsersIds);
		echo count($allUsersIds).' users updated';
		
	}
}