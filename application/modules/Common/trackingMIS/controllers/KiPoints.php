<?php

class KiPoints extends MX_Controller
{
	private $trackingLib;
	

	public function __construct()
	{
		parent::__construct();
        $this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
        reset($this->trackingLib->checkValidUser());
		$this->load->model('trackingMIS/kipoints_model');
        $this->trackingKiModel = new kipoints_model();
    }

    private function _init(){
    }

    function dashboard(){
    	$trackingKeyArray = $this->trackingKiModel->getTrackingKeys();
		foreach ($trackingKeyArray as $key => $value) {
    		$trackingIdsArray[$value['id']] = array(
    										'key' => $value['id'],
    										'keyName' => $value['keyName'],
    										'page' => $value['page'],
    										'widget' => $value['widget'],
    										'conversionType' => $value['conversionType'],
    										'site' => $value['site'],
    										'siteSource' => $value['siteSource'],
                                            'pageGroup' => $value['pageGroup'],
                                            'siteSourceType' => $value['siteSourceType']
    										);
    		$id[$value['id']]=0;
    		$keyName[$value['keyName']]=0;
    		$page[$value['page']]=0;
    		$widget[$value['widget']]=0;
    		$conversionType[$value['conversionType']]=0;
    		$site[$value['site']]=0;
    		$siteSource[$value['siteSource']]=0;
    	}
    	$data['trackingIdsArray'] = $trackingIdsArray;
    	$data['id'] = array_keys($id);
    	$data['keyName'] = array_keys($keyName);
    	$data['page'] = array_keys($page);
    	$data['widget'] = array_keys($widget);
    	$data['conversionType'] = array_keys($conversionType);
    	$data['site'] = array_keys($site);
    	$data['siteSource'] = array_keys($siteSource);	
    	$data['date'] = date('d M Y');
    	$this->load->view('kiPoints',$data);
    }

    function generateKey(){
    	$row = $this->input->post('row');
    	$localIP = $this->input->post('localIP');
    	$name = $this->input->post('name');
    	$array = $this->trackingKiModel->insertRow($row);
		$key = $array['id'];
		$query = explode('(',$array['sql']);
		$query = $query[0].' ( id, '.$query[1].'( '.$key.' , '.$query[2];
		$subject="Ki Points : New Key Added (Key Id : ".$key.")";
		$message = "Hey,<br/>A new key has been added to the system. Please find the query to incorporate this change into your local system below:<br/><br/><b>".$query.";</b><br/><br/>This change has been made by : $name<br/><br/>Regards,<br/>Ki Points";
		$this->trackingKeyMailer($subject,$message);
		$this->trackingKiModel->insertDataForuserTracking($name,$localIP,'insert',$key);
        echo json_encode(array('errorFlag'=>0,'query'=>$query));
    }

    function deleteRow($key){
    	// Rewrite this logic!
    }

    public function trackingKeyMailer($subject="",$message=""){
        /*$emailId = 'shiksha-dev@infoedge.com';
        $cc = 'shikshaqa@infoedge.com';
        $bcc = 'satech@shiksha.com';*/
        $emailId = 'shikshaqa@Infoedge.com';
        // $cc = 'kalva.nithishredyy@99acres.com';
        $bcc = '';
        $alertsClient = $this->load->library('alerts_client');
        $alertsClient->externalQueueAdd("12",SA_ADMIN_EMAIL,$emailId,$subject,$message,"html",'','n',array(),$cc,$bcc);
 	}

 	public function checkIfRowAlreadyExists(){
 		$data = $this->input->post("row");
 		$key = $this->trackingKiModel->checkIfRowAlreadyExists($data);
 		if($key > 0){
 			echo $key;
 			return true;
 		}
 		echo "-1";
 	}
}
