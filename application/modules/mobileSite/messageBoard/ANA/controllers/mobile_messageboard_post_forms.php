<?php
/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2012 Ravi Raj <[Your email]>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category   [ Category ]
 * @package    [ Package ]
 * @subpackage [ Subpackage ]
 * @author     Ravi Raj <[Your email]>
 * @copyright  2012 Ravi Raj.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    [ Version ]
 * @link       http://[ Your website ]
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


// ==================================================================
//
// [ Section description goes here ... ]
//
// ------------------------------------------------------------------

class mobile_messageboard_post_forms extends ShikshaMobileWebSite_Controller {

	private $userStatus;

	private $login_client;

	private $register_client;

	private $alerts_client;

	private $messageBoardProxy;

	private $message_board_client;

	public $wurfl_obj;

	public $flag_UnansweredTopics = 3;

	public $start = 0;

	public $rows = 20; 

	public $myqnaTab='answer';

	public $actionDone='default';

	function __construct() 
	{
		parent::__construct();
		$this->load->library(array(
			'miscelleneous',
			'category_list_client',
			'listing_client',
			'register_client',
			'alerts_client',
			'lmsLib',
			'Login_client',
			'messageBoardProxy',
			'form_validation',
			'message_board_client',
			'RelatedClient'
		));

		$this->load->helper(array('url','image','shikshautility'));

		$this->load->config('mcommon/mobi_config');

		$this->userStatus = $this->checkUserValidation();
		$this->login_client = new Login_client();
		$this->register_client = new Register_client();
		$this->alerts_client = new Alerts_client();
		$this->message_board_client = new message_board_client();
		$this->wurfl_obj = $this->ci_mobile_capbilities;
		$this->messageBoardProxy  = new messageBoardProxy($this->wurfl_obj);

		$this->load->library('form_validation');
		// Need Hack to work form_validation Lib with HMVC
		$this->form_validation->CI =& $this;

	}

	function strip_tags_string( $string = '')
	{
        		$search = array(
		    "'<head[^>]*?>.*?</head>'si",
		    "'<script[^>]*?>.*?</script>'si",
		    "'<style[^>]*?>.*?</style>'si",
		);
		$replace = array("","",""); 
		$string = strip_tags(preg_replace($search, $replace, $string));
        		return $string;
    	}

	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	function _register_user_ana($user_name, $last_name,$user_mobile, $user_email)
	{
		try{
			$this->load->library("muser/User_Utility");
			$register_obj = new User_Utility;
			$result = $register_obj->registerUser($user_name,$last_name, $user_mobile, $user_email);
			$signedInUser = $this->register_client->getinfoifexists(1, $user_email, 'email');
			$result = array_merge($result,$signedInUser);
			return $result;
		}catch(Exception $e){
			$this->messageBoardProxy->reportStatus($e,'Unable to register User.');
			//throw new Exception($e);
		}
	}
	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	function submit_topic_detail()
	{
		$fromOthers = 'user';
		$mainAnsId 	= '0';
		$appId = 1;
		$threadId = $this->input->post('qid');
		$immediateParentId = $threadId;
		$text = $this->strip_tags_string($this->input->post('ans'));
		if((!is_array($this->userStatus)) && ($this->userStatus == "false")){

			$hash = $this->input->post('hashtxt');
			$human_typing_time = $this->config->item('human_typing_time');
			if(secureForm($human_typing_time,$hash) == false )
			{
				echo "An error occured, please try again";
				exit;
			}

			$user_name = $this->input->post('name');
			$user_last_name = $this->input->post('userlastname');
			$user_mobile = $this->input->post('phone');
			$user_email = $this->input->post('email');

			if (validateMobilePhone($user_mobile,"mobile") !== TRUE)
			{
				echo validateMobilePhone($user_mobile,"mobile") ;
				exit;
			}
			if (validateMobileUsername($user_name,"First name") !== TRUE)
			{
				echo validateMobileUsername($user_name,"First name");
				exit;
			}
			if (validateMobileUsername($user_last_name,"Last name") !== TRUE)
			{
				echo validateMobileUsername($user_last_name,"Last name");
				exit;
			}
			if(validateMobileEmailField($user_email,$caption) !== TRUE)
			{
				echo validateMobileEmailField($user_email,"email") ;
				exit;
			}

			$result = $this->_register_user_ana($user_name, $user_last_name,$user_mobile, $user_email);

			if ($result['user_exit_in_db'] == 'true')
			{
				echo "user_exit";
				exit;
			}
			if ($result['user_register'] == 'true')
			{
				$userId = $result[0]['userid'];            
				$displayName = $result[0]['displayname'];  
			}
		} else {
			$userId = $this->userStatus[0]['userid'];
			$displayName = $this->userStatus[0]['displayname'];
		}

		$requestIp = S_REMOTE_ADDR;
		$msgbrdClient = new Message_board_client();
		$res = json_decode($msgbrdClient->getUserReputationPoints($appId,$userId));
		if($res[0]->reputationPoints<=0 && $res[0]->reputationPoints!='9999999'){
			echo 'NOREP';
			exit;
		}
		try{
			$returnValue = $msgbrdClient->postReply($appId,$userId,$text,$threadId,$immediateParentId,$requestIp,$fromOthers,$displayName,$mainAnsId);

			if ($returnValue ==  "MTOA")
			{
				echo "MTOA";
				exit;
			}

			if ($returnValue ==  "SUQ")
			{
				echo "SUQ";
				exit;
			}
			$activity_type = "answer_post";
			$activity_type_value = $returnValue;

		             modules::run('mcommon/MobileBeacon/logdataFrmbackend', $activity_type,$activity_type_value,$userId);
			
			$this->_clearCacheForUser();
			echo  "success";
			exit;
		}catch(Exception $e){
			$this->messageBoardProxy->reportStatus($e,'Ans posting failed on mobile');
			throw new Exception($e);
		}
	}
	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	function _clearCacheForUser()
	{
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$coookie=isset($_COOKIE['user'])?$_COOKIE['user']:'';
		$key = md5('validateuser'.$coookie.'on');
		$cacheLib->clearCacheForKey($key);
		return;
	}
	/**
	 * [Description]
	 *
	 * @param [type] $new[ Prop name ] [description]
	 */
	function submit_question_for_post()
	{
		$question_text_for_post = preg_replace("/[\r\n]+/", " ", $this->strip_tags_string($this->input->post('question_text_for_post')));
		$catselect = $this->input->post('catselect');
		$subcatselect = $this->input->post('subcatselect');
		$countrydd = $this->input->post('countrydd');
		$appId=12;
		$msgbrdClient = new Message_board_client();
		$selectedCategoryCsv = $catselect . ",".$subcatselect;
		$requestIp = S_REMOTE_ADDR;
		$fromOthers = 'user';
		$listingTypeId = '0';
		$listingType = '';
		$countryCsv = $countrydd;

		if((!is_array($this->userStatus)) && ($this->userStatus == "false")){

			$hash = $this->input->post('hashtxt');
			$human_typing_time = $this->config->item('human_typing_time');
			if(secureForm($human_typing_time,$hash) == false )
			{
				echo "An error occured, please try again";
				exit;
			}

			$qname = $this->input->post('qname');
			$qlname = $this->input->post('qlname');
			$qemail = $this->input->post('qemail');
			$qmobile = $this->input->post('qmobile');

			if (validateMobilePhone($qmobile,"mobile") !== TRUE)
			{
				echo validateMobilePhone($qmobile,"mobile") ;
				exit;
			}
			if (validateMobileUsername($qname,"First name") !== TRUE)
			{
				echo validateMobileUsername($qname,"First name");
				exit;
			}
			if (validateMobileUsername($qlname,"Last name") !== TRUE)
			{
				echo validateMobileUsername($qlname,"Last name");
				exit;
			}
			if(validateMobileEmailField($qemail,$caption) !== TRUE)
			{
				echo validateMobileEmailField($qemail,"email") ;
				exit;
			}

			$result = $this->_register_user_ana($qname, $qlname,$qmobile, $qemail);

			if ($result['user_exit_in_db'] == 'true')
			{
				echo "user_exit";
				exit;
			}
			if ($result['user_register'] == 'true')
			{
				$userId = $result[0]['userid'];            
				$displayName = $result[0]['displayname'];  
			}
		} else {
			$userId = $this->userStatus[0]['userid'];
			$displayName = $this->userStatus[0]['displayname'];
		}
		$requestIp = S_REMOTE_ADDR;
		$result = array();
		try{
			$topicResult = $msgbrdClient->addTopic($appId,$userId,$question_text_for_post,$selectedCategoryCsv,$requestIp,$fromOthers,$listingTypeId,$listingType,1,$displayname,$countryCsv,$otherParamCsv);
			
			$activity_type = "question_post";
			$activity_type_value = $topicResult['ThreadID'];
		             modules::run('mcommon/MobileBeacon/logdataFrmbackend', $activity_type,$activity_type_value,$userId);
		             
		}catch(Exception $e){
			$this->messageBoardProxy->reportStatus($e,'question posting failed on mobile');
			throw new Exception($e);
		}
		$this->_clearCacheForUser();
		$pageurl = SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome/" . $catselect . "/" . $this->flag_UnansweredTopics . "/" . $countrydd . "/" . $this->myqnaTab . '/' .  $this->actionDone .  "/" . $this->start . "/" . $thsi->rows;
		echo($pageurl);
	}

}
