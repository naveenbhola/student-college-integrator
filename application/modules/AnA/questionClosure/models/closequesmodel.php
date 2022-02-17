<?php
class closequesmodel extends MY_Model {
        
        
        private $dbHandle = '';
	
        function __construct(){
		parent::__construct('AnA');
	}
        
        private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	        $this->dbHandle = $this->getWriteHandle();
		}
        }
	
	private function init(){
		$this->initiateModel('write');	
                $this->load->library(array('alerts_client','messageboardconfig','MailerClient'));
                $this->load->helper(array('url_helper'));
		$this->load->model('UserPointSystemModel');
        }
            
        /**********************************************************************************************          
         this function updates the satus of question to closed which have not a single answer on them for last 2 weeks
         ******************************************************************************/
        public function updateQuestionClosure(){
		
		$this->init();
		$twoWeekBeforeDate = mktime(0,0,0,date("m"),date("d")-14,date("Y"));
                $twoWeekBeforeDate=date("Y-m-d 00:00:00", $twoWeekBeforeDate);
		
		//considering from 1st of november..
		$startDateLimit ='2012-11-01 00:00:00';
		
		//get all the live questions posted before 2 weeks from today with no answer...
		$query="SELECT  m1.msgId,m1.creationDate,m1.status,m1.msgTxt,t1.displayname,t1.email,t1.firstname,t1.userid
				FROM  `messageTable` m1 JOIN tuser t1 ON m1.userId=t1.userid
				WHERE  m1.status =  'live' and m1.`creationDate` >= ?
				and m1.`creationDate` < ? 
				and m1.fromOthers='user' and  m1.mainAnswerId='-1'
				and m1.msgCount=0 and m1.listingTypeId=0";
			
		
		$questionsWithoutAnswer = $this->dbHandle->query($query,array($startDateLimit,$twoWeekBeforeDate));
                $questionsWithoutAnswer = $questionsWithoutAnswer->result_array();
		
		// make a string consisting of all the msgids whose status is to be updated to be closed..
		
		foreach($questionsWithoutAnswer as $questin){
			if($questin['msgId']!=''){
				$string .= ($string=='')?$questin['msgId']:','.$questin['msgId'];
			}
		}
	
		if($string!='')		
				
		$this->_closeQuestion($string);
		
		//sending closure mail to each user whose ques is unanswrd..	
		foreach($questionsWithoutAnswer as $questin){
			$this->_sendClosureMailer($questin);
		}	
			
		
		$this->_selectBestAnswerByAlgoAndClose($startDateLimit);
	}
	
	/**********************************************************************************************          
         this function chooses the best answer with max score(by algo) if the user has not choosen the best
         answer for 5 weeks since the question was posted. and  then closes the question
         ******************************************************************************/
		
	private function _selectBestAnswerByAlgoAndClose($startDateLimit = null){
		
		//get all the live questions posted before  5 weeks from today with  no best answer.
		$this->init();
		
		$FiveWeekBeforeDate = mktime(0,0,0,date("m"),date("d")-35,date("Y"));
                $FiveWeekBeforeDate=date("Y-m-d 00:00:00", $FiveWeekBeforeDate);
		
		//get all questions posted before 5 week from today without best answer and atleast 1 ans .
		$query="SELECT m1.msgId, m1.creationDate, m1.status, m2.threadId, t1.displayname, m1.msgTxt,t1.email,t1.userid
				FROM  `messageTable` m1
				LEFT JOIN messageTableBestAnsMap m2 ON m2.threadId = m1.msgId
				JOIN tuser t1 ON t1.userid = m1.userId
				WHERE m1.status =  'live'
				AND m1.creationDate <  ?
				AND m1.creationDate >= ?
				AND m1.fromOthers =  'user'
				AND m1.mainAnswerId =  '-1'
				AND m1.msgCount >0
				AND m1.listingTypeId =0";	

		$questions = $this->dbHandle->query($query,array($FiveWeekBeforeDate,$startDateLimit));
		$questions = $questions->result_array();
		
	  // loop taking each questns without bestAns 1 by 1 
		foreach($questions as $questin){
			
			//calculate scores for all the answers of a question
			$query="SELECT msgId as ansMsgId,msgTxt as ansTxt,`digUp`,digDown,
				digUp-digDown as score,t1.displayname,
				t1.firstname,t1.email,t1.userid
				FROM  `messageTable` m1
				JOIN tuser t1 ON t1.userid = m1.userId
				WHERE parentId= ? and mainAnswerId=0";
			$result = $this->dbHandle->query($query,array($questin['msgId']));
			$result = $result->result_array();
					

			$resultAns['maxscore']=0;
			//this loop gives the max score and id of the answer with max ans for a particular questn..
			
			foreach($result as $res){
				
				if($res['score'] > $resultAns['maxscore']){
					
					$resultAns['maxscore']=$res['score'];
					$resultAns['maxAnsId']=$res['ansMsgId'];
					$resultAns['maxAnsTxt']=$res['ansTxt'];
					$resultAns['ansOwnerDisplayName']=$res['displayname'];
					$resultAns['ansOwnerFirstName']=$res['firstname'];
					$resultAns['email']=$res['email'];
					$resultAns['maxAnsUserid']=$res['userid'];
				}
			}
		
			
			
			//The answer having the highest score (+1 for thumbs up and -1 for thumbs down) subject to a minimum score of +4. 
			if($resultAns['maxscore']>=4){
				//choose the best answer with max score
				$query="INSERT INTO `messageTableBestAnsMap`(`threadId`, `bestAnsId`, `creation_time`,type)
					VALUES (?,?,?,?)";
				$date = date("Y-m-d H:i:s");  
				$bestAnsSel = $this->dbHandle->query($query,array($questin['msgId'],$resultAns['maxAnsId'],$date,'automated'));
				//this mail will be sent to the user whose answer has been selected as the best answer..
				$type='automated';
				$this->_bestAnsMailer($questin,$resultAns,$type);
				$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$resultAns['maxAnsUserid'],'choosenbestAnswer',$resultAns['maxAnsId']);
				//Question owner will not get anything in this case
				//$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$questin['userid'],'selectBestAnswer',$resultAns['maxAnsId']);

			}	
			
			$this->_closeQuestion($questin['msgId']);
		}
		$this->_askToSelectBestAnswer($startDateLimit);
	}
		
	/**********************************************************************************************          
         this function ask the user to choose bestanswer for questions which were posted 4 weeks back with atleast 1 answer.
         ****************************************************************************************/
	private function _askToSelectBestAnswer($startDateLimit = null){	
		
		$FourWeekBeforeDate = mktime(0,0,0,date("m"),date("d")-28,date("Y"));
                $FourWeekBeforeDate=date("Y-m-d 00:00:00", $FourWeekBeforeDate);
		
		$DayBfreFourWeekDate = mktime(0,0,0,date("m"),date("d")-29,date("Y"));
                $DayBfreFourWeekDate=date("Y-m-d 00:00:00", $DayBfreFourWeekDate);
		
		
		
		//get all questions posted  before 4 weeks with atleast 1 answer and no best answer in 1 day interval.
		
		
		$query="SELECT m1.msgId, m1.creationDate, m1.status, m2.threadId, t1.displayname,t1.firstname, t1.email, m1.msgTxt
				FROM  `messageTable` m1
				LEFT JOIN messageTableBestAnsMap m2 ON m2.threadId = m1.msgId
				JOIN tuser t1 ON t1.userid = m1.userId
				WHERE m1.status =  'live'
				AND m1.creationDate >=  ?
				AND m1.creationDate <  ?
				AND m1.creationDate >=  ?
				AND m1.fromOthers =  'user'
				AND m1.mainAnswerId =  '-1'
				AND m1.msgCount >0
				AND m1.listingTypeId =0";
		

		$questionsWithoutBA = $this->dbHandle->query($query,array($DayBfreFourWeekDate,$FourWeekBeforeDate,$startDateLimit));
                $questionsWithoutBA = $questionsWithoutBA->result_array();
		
		// loop taking each questns without bestAns 1 by 1 
		foreach($questionsWithoutBA as $questionsWithoutBA){
			
			//then send the mail to user to choose a best answer..
			$this->_sendSelectBAMailer($questionsWithoutBA);
			
		}

	}
	
	/***************************************         
         this function closes the question.
         ************************************/
	
	private function _closeQuestion($msgId=null){

		$this->init();
		$query="UPDATE `messageTable` SET status =  'closed'
			WHERE msgId IN ($msgId)";
		$updatedStatus = $this->dbHandle->query($query);
		
		
		
		
	}
	
	/************************************************************         
         this function sends a mail to the user after closing questn.
         *********************************************************/
	
	private function _sendClosureMailer($questin=null){
		          
		$creationDate = strtotime($questin['creationDate']);
		$date = date("Y-m-d H:i:s");
		$current_date = strtotime($date);
		$datediff = $current_date - $creationDate;
		$days = floor($datediff/(60*60*24));
		
		$mailData=array();
		$mailData['days']=$days;
		$NameOfUser = (trim($questin['firstname']) != '')?$questin['firstname']:$questin['displayname'];
		$mailData['userName']=$NameOfUser;
		$mailData['questionText']=$questin['msgTxt'];
		$mailData['type'] = "questionClosureMail";
		$mailData['receiverId'] = $questin['userid'];
		$subject = 'Your question has been closed';
		$mailData['mail_subject'] = $subject;
		$appId=12;
		$fromAddress=SHIKSHA_ADMIN_MAIL;
		$userEmail=$questin['email'];
		$mailData['userId'] = $questin['userid'];
		Modules::run('systemMailer/SystemMailer/questionClosure', $userEmail, $mailData);
	}
	
	/************************************************************         
         this function sends a mail to the user to choose best answer .
         *********************************************************/
	
	private function _sendSelectBAMailer($row=null){
		   
		$mailData=array();
		$mailData['userName']=(trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
		$mailData['questionText']=$row['msgTxt'];
		$mailData['type'] ="selectBestAnswer";
		$url=getSeoUrl($row['msgId'],'question',$row['msgTxt'],'','',$row['creationDate']);
		$MailerClient = new MailerClient();
		$userEmail=$row['email'];
		$mailData['seoUrl']= $MailerClient->generateAutoLoginLink(1,$userEmail,$url);
		$content=$this->load->view("search/searchMail",$mailData,true);
		$subject = 'Select the best answer for your question.';
		$appId=12;
		$fromAddress=SHIKSHA_ADMIN_MAIL;		
		
		$AlertClientObj = new Alerts_client();
		$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
	}
	
	/*****************************************************************************************         
         this function sends a mail to the user whose questn is selctd as bestanswer by automation .
         *****************************************************************************/
	private function _bestAnsMailer($question=null,$answer=null,$type=null){
			
			
		$mailData=array();
		$NameOfUser = (trim($answer['ansOwnerFirstName']) != '')?$answer['ansOwnerFirstName']:$answer['ansOwnerDisplayName'];
		$mailData['type'] = "bestAnsMail";
		$mailData['questionOwnerName']=$question['displayname'];
		$mailData['questionText']=$question['msgTxt'];
		$mailData['NameOfUser']=$NameOfUser;
		$url=getSeoUrl($question['msgId'],'question',$question['msgTxt'],'','',$question['creationDate']);
		$userEmail=$answer['email'];
		$MailerClient = new MailerClient();
		$mailData['seoUrl']= $MailerClient->generateAutoLoginLink(1,$userEmail,$url);
		
		$msgTxt=$answer['maxAnsTxt'];
		$mailData['msgTxt']=strlen($msgTxt)>300?substr($msgTxt,0,297).'...'.'<a href="'.$mailData['seoUrl'].'">more</a>':$msgTxt;
		$mailData['url']=SHIKSHA_ASK_HOME;
		
		$mailData['byuser']=$type; 
		$content=$this->load->view("search/searchMail",$mailData,true);
		$subject = 'Congratulations! Your answer has been selected as the Best Answer.';
		$appId=12;
		$fromAddress=SHIKSHA_ADMIN_MAIL;
		
		$AlertClientObj = new Alerts_client();
		$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
		
	}
}
                
