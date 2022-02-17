<?php
//
include('Sums_Mail_Events.php');
/**
 * Controller class for Sums Common functions like user validations
 * 
 */
class Sums_Common extends MX_Controller
{
	function sumsUserValidation($aclId=0,$prodId='')
		{
		$validity = $this->checkUserValidation();
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
			/*$thisURI = $_SERVER['REQUEST_URI'];
			 $thisUrl = 'https://'.THIS_CLIENT_IP.$thisURI;
			 $sendUrl = base64_encode($thisUrl);
			 error_log_shiksha("SEND URL: ".$sendUrl);
			 header('location:/sums/Manage/login/'.$sendUrl); */
			header("location:/sums/Manage/login");
			exit();
		}else {
			$logged = "Yes";
			$userid = $validity[0]['userid'];
			$usergroup = $validity[0]['usergroup'];
			if ($usergroup!="sums") {
				header('location:/sums/Manage/login');
				exit;
			}
		}
		$this->load->library('enterprise_client');
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
		$returnArray['validity'] = $validity;
		$returnArray['userid'] = $userid;
		$returnArray['usergroup'] = $usergroup;
		$returnArray['headerTabs'] = $headerTabs;
		$this->load->library('sums_manage_client');
		$mngObj = new Sums_Manage_client();
		$sumsuserinfo = $mngObj->getSumsUserInfo(1,$userid);
		$sumsuseracl = $mngObj->getSumsUserACL(1,$userid);
		error_log_shiksha("shivam ".print_r($sumsuseracl,true));
		$returnArray['sumsuserinfo']=$sumsuserinfo;
		$returnArray['sumsuseracl']=$sumsuseracl;
		if($aclId!=0)
		{
			if(array_key_exists($aclId,$sumsuseracl))
			{
				return $returnArray;
			}
			else
			{
				$data=array();
				$data['sumsUserInfo']=$returnArray;
				$data['prodId'] = $prodId;
				header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
				exit();
			}
		}
		return $returnArray;
		}
	
	function permissionDenied($prodId)
		{
		$this->load->helper('url');
		$data=array();
		$data['sumsUserInfo']=$this->sumsUserValidation();
		$data['prodId'] = $prodId;
		$this->load->view('/sums/permissionDenied',$data);
		}
}

?>
