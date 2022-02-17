<?php
	/**
	 * CodeIgniter Facebook Connect Graph API Login Controller 
	 * 
	 * Author: Graham McCarthy (graham@hitsend.ca) HitSend inc. (http://hitsend.ca)
	 * 
	 * VERSION: 1.0 (2010-09-30)
	 * LICENSE: GNU GENERAL PUBLIC LICENSE - Version 2, June 1991
	 * 
	 **/
class Facebook_server extends MX_Controller {
	
	function index(){
		ini_set('max_execution_time', '1800000');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('facebookconfig');
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->helper('shikshaUtility');

	        $this->load->library('dbLibCommon');
        	$this->dbLibObj = DbLibCommon::getInstance('Facebook');

		//$config['functions']['getDataForFacebook'] = array('function' => 'Facebook_server.getDataForFacebook');
		$config['functions']['editFbPostSettings'] = array('function' => 'Facebook_server.editFbPostSettings');
		$config['functions']['getFbPostSettings'] = array('function' => 'Facebook_server.getFbPostSettings');
		$config['functions']['saveAccessToken'] = array('function' => 'Facebook_server.saveAccessToken');
		$config['functions']['getAccessToken'] = array('function' => 'Facebook_server.getAccessToken');
		$config['functions']['saveFacebookFollowInfo'] = array('function' => 'Facebook_server.saveFacebookFollowInfo');
		$config['functions']['deleteAccessToken'] = array('function' => 'Facebook_server.deleteAccessToken');
		$config['functions']['getUserDetailsForEventUpdates'] = array('function' => 'Facebook_server.getUserDetailsForEventUpdates');
		$config['functions']['getUserDetailsForArticlesUpdates'] = array('function' => 'Facebook_server.getUserDetailsForArticlesUpdates');
		$config['functions']['checkThresholdValue'] = array('function' => 'Facebook_server.checkThresholdValue');
		$config['functions']['updateFacebookFlag'] = array('function' => 'Facebook_server.updateFacebookFlag');
		$config['functions']['checkLinkArticle'] = array('function' => 'Facebook_server.checkLinkArticle');
		
		//pranjul starts
                
        $config['functions']['getAccountSetttingInfo'] = array('function' => 'Facebook_server.getAccountSetttingInfo');
		$config['functions']['saveAccessToken_AnA'] = array('function' => 'Facebook_server.saveAccessToken_AnA');
		$config['functions']['getAccessToken_AnA'] = array('function' => 'Facebook_server.getAccessToken_AnA');
		$config['functions']['getDataForFacebook'] = array('function' => 'Facebook_server.getDataForFacebook');
                $config['functions']['donShowAgain'] = array('function' => 'Facebook_server.donShowAgain');
		//pranjul ends
                $args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

	private function loadDatabaseHandle($operation='read'){
                if($operation=='read'){
                        $dbHandle = $this->dbLibObj->getReadHandle();
                }
                else{
                        $dbHandle = $this->dbLibObj->getWriteHandle();
                }

                if($dbHandle == ''){
                        error_log_shiksha('ERROR:: FACEBOOK SERVER CAN NOT CREATE DB HANDLE','qna');
        		return false;
                }
	        return $dbHandle;
    	}
	
	 function getDataForFacebook($request)
	{ 
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$type=$parameters['2'];
		$parentId=$parameters['3'];

		if($type=='bestanswer'){
                    $userId=$parameters['1'];
                    $threadId=$parameters['3'];
		}

		$mainAnswerId=$parameters['4'];
		$instituteId = $parameters['5'];
		$courseId = $parameters['6'];//In case of Request-E-Brochure
		
		if($type == 'followArticle'){
		$blogId = $parentId;
		}

		$dbHandle = $this->loadDatabaseHandle();
		if($type=="Post"){ //This could be for a question, discussion or announcement
		    $queryCmd = "select fromOthers from messageTable where userId = ? and parentId = 0 and status IN ('live','closed') and fromOthers IN ('user','discussion','announcement') order by creationDate desc limit 1";
		    $query = $dbHandle->query($queryCmd, array($userId));
		    $fromOthers = '';
		    foreach ($query->result() as $row){
			$fromOthers=$row->fromOthers;
		    }
		    if($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers == 'review'){
			$queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,mct.categoryId,ct.name,'post' type, ifnull((select md.description from messageDiscussion md where m1.msgId=md.threadId),'') postDesc from messageTable m1,messageCategoryTable mct, categoryBoardTable ct where m1.parentId=m1.threadId and m1.status IN ('live','closed') and m1.userId=? and m1.fromOthers IN ('discussion','announcement') and mct.threadId = m1.threadId and ct.boardId = mct.categoryId and ct.parentId = 1 order by m1.creationDate desc limit 1";
		    }
		    else if($fromOthers == 'user'){
			$queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,mct.categoryId,ct.name,'question' type from messageTable m1,messageCategoryTable mct, categoryBoardTable ct where m1.parentId=0 and m1.status IN ('live','closed') and m1.userId=? and m1.fromOthers = 'user' and mct.threadId = m1.threadId and ct.boardId = mct.categoryId and ct.parentId = 1 order by m1.creationDate desc limit 1";
		    }
		    $query = $dbHandle->query($queryCmd, array($userId));
		}
		else if($type=="Answer"){
		    $queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,mct.categoryId,ct.name,'answer' type, (select msgTxt from messageTable m2 where m2.msgId = m1.threadId and status IN ('live','closed')) questionText, (SELECT Level FROM userPointLevelByModule upl, userpointsystembymodule ups WHERE upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = ? and ups.userPointValueByModule > upl.minLimit LIMIT 1) level from messageTable m1,messageCategoryTable mct, categoryBoardTable ct where m1.parentId=m1.threadId and m1.parentId = ? and m1.status IN ('live','closed') and m1.userId=? and m1.fromOthers IN ('user','discussion','announcement') and mct.threadId = m1.threadId and ct.boardId = mct.categoryId and ct.parentId = 1  order by m1.creationDate desc limit 1";
		    $query = $dbHandle->query($queryCmd, array($userId, $parentId, $userId));
		}
		else if($type=="Comment"){ //This could be a comment on Answer, discussion comment or an announcement comment
		    $queryCmd = "select fromOthers from messageTable where userId = ? and mainAnswerId = ? and status IN ('live','closed') and fromOthers IN ('user','discussion','announcement')  order by creationDate desc limit 1";
		    $query = $dbHandle->query($queryCmd, array($userId,$mainAnswerId));
		    $fromOthers = '';
		    foreach ($query->result() as $row){
			$fromOthers=$row->fromOthers;
		    }
		    $catIdQuery = " ,(select categoryId from messageCategoryTable mct LEFT JOIN categoryBoardTable cbt1 ON cbt1.boardId = mct.categoryId where mct.threadId = m1.threadId and cbt1.parentId = 1 ) categoryId ";
		    $catNameQuery = " ,(select name from categoryBoardTable ct, messageCategoryTable mct where mct.threadId = m1.threadId and ct.parentId = 1 and ct.boardId = mct.categoryId) name ";
		    if($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers == 'review'){
			$queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,'postcomment' type, (select msgTxt from messageTable m2 where m2.msgId = m1.mainAnswerId) postText $catIdQuery  $catNameQuery from messageTable m1 where m1.mainAnswerId=? and m1.status IN ('live','closed') and m1.userId=? and m1.fromOthers IN ('discussion','announcement') order by m1.creationDate desc limit 1";
		    }
		    else if($fromOthers == 'user'){
			$queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,'comment' type, (select msgTxt from messageTable m2 where m2.msgId = m1.threadId) questionText $catIdQuery  $catNameQuery from messageTable m1 where m1.mainAnswerId=? and m1.status IN ('live','closed') and m1.userId=? and m1.fromOthers = 'user' order by m1.creationDate desc limit 1";
		    }
		    $query = $dbHandle->query($queryCmd, array($mainAnswerId, $userId));
		}
		else if($type == 'level'){
                    $queryCmd = "select t1.email, t1.displayname,'level' type,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid=?";
		    $query = $dbHandle->query($queryCmd, array($userId));
                }
                else if($type == 'bestanswer'){
                    $queryCmd = "select m1.userId, m1.msgTxt,m1.parentId,m1.threadId,m1.msgId,'bestanswer' type,mct.categoryId,ct.name,(SELECT Level FROM userPointLevelByModule upl, userpointsystembymodule ups WHERE upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = ? and ups.userPointValueByModule > upl.minLimit LIMIT 1) level,t1.firstname,t1.displayname,t1.email from messageTable m1,messageCategoryTable mct, categoryBoardTable ct, tuser t1 where m1.threadId=? and (m1.msgId=? or m1.parentId=0) and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.userId=t1.userid and mct.threadId = m1.threadId and ct.parentId = 1 and ct.boardId = mct.categoryId";
		    $query = $dbHandle->query($queryCmd, array($userId, $threadId, $mainAnswerId));
                }
		elseif(($type == 'followInstitute')||($type == 'request-E-Brochure')){
		    $fbData = array();
		    $queryCmd = "SELECT listing_title title,listing_seo_url listingUrl from listings_main where status ='live' AND listing_type='institute' AND listing_type_id = ?";
		    $query = $dbHandle->query($queryCmd, array($instituteId));
		    $titleArray = array();
		    foreach($query->result() as $row){
			$titleArray[] = array(array(
				      'title' => array($row->title,'string'),
				      'listingUrl' => array($row->listingUrl,'string')
				),'struct');
			}
			array_push($fbData,array($titleArray,'struct'));
		    
		    $queryCmd = "SELECT ilt.locality_name locality_name ,cct.city_name from institute_location_table ilt INNER JOIN countryCityTable cct ON (ilt.city_id = cct.city_id) where ilt.institute_id=? and ilt.status = 'live' LIMIT 1";
		    $query = $dbHandle->query($queryCmd, array($instituteId));
		    $locationArray = array();
		    foreach($query->result() as $row){
			$locationArray[] = array(array(
				      'city' => array($row->city_name,'string'),
				      'locality' => array($row->locality_name,'string')
				),'struct');
			}
			array_push($fbData,array($locationArray,'struct'));
			
		    $queryCmd = "select cbt.name name,cbt.urlName url from categoryBoardTable cbt where cbt.boardId In (SELECT parentId from categoryBoardTable where boardId in (select category_id from listing_category_table where listing_type='institute' and listing_type_id= ?) group by parentId order by 1 desc) limit 1";
		    $query = $dbHandle->query($queryCmd, array($instituteId));
		    $categoryArray = array();
		    foreach($query->result() as $row){
			$categoryArray[] = array(
				array(
				      'categoryName' => array($row->name,'string'),
				      'categoryUrl' => array($row->url,'string')
				),'struct');
		    }
			array_push($fbData,array($categoryArray,'struct'));
		    
		    
		    if($courseId == 0){
			$queryCmd = "SELECT course_id ,fees_value,fees_unit from course_details where institute_id =? and status = 'live' order by course_order limit 1";
			
			$query = $dbHandle->query($queryCmd, array($instituteId));
			$courseDetailsArray = array();
			foreach($query->result() as $row){
				$courseDetailsArray[] = array(
				array(
				      'courseId' => array($row->course_id,'string'),
				      'fees_value' => array($row->fees_value,'string'),
				      'fees_unit' => array($row->fees_unit,'string')
				),'struct');
			}
			array_push($fbData,array($courseDetailsArray,'struct'));
		    }else{
			$queryCmd = "SELECT course_id ,fees_value,fees_unit from course_details where course_id = ? and status = 'live' order by course_order limit 1";
			$query = $dbHandle->query($queryCmd, array($courseId));
			$courseDetailsArray = array();
			foreach($query->result() as $row){
				$courseDetailsArray[] = array(
				array(
				      'courseId' => array($row->course_id,'string'),
				      'fees_value' => array($row->fees_value,'string'),
				      'fees_unit' => array($row->fees_unit,'string')
				),'struct');
			}
			array_push($fbData,array($courseDetailsArray,'struct'));
			
		    }
		    
		    $queryCmd = "select sf.feature_name featureName,sf.value featureValue from course_features cf JOIN salient_features sf on cf.salient_feature_id = sf.feature_id where cf.listing_id = ? and cf.status = 'live' and sf.status = 'live'";
		    $query = $dbHandle->query($queryCmd, array($courseId));
		    $salientFeatureArray = array();
		    foreach($query->result() as $row){
			$salientFeatureArray[] =  array(
				array(
                                    'salientFeatureName'=>array($row->featureName,'string'),
			            'salientFeatureValue'=>array($row->featureValue,'string')
				),'struct');
		    }
		    array_push($fbData,array($salientFeatureArray,'struct'));
		    
		    $queryCmd = "Select attribute,value from course_attributes WHERE course_id = ? and status = 'live'";
		    
		    $query = $dbHandle->query($queryCmd, array($courseId));
		    
		    $courseAttributesArray = array();
		    foreach($query->result() as $row){
			$courseAttributesArray[] = array(
				array(
                                    'attributeName'=>array($row->attribute,'string'),
			            'attributeValue'=>array($row->value,'string')
				),'struct');
          		
		    }
		    
		    array_push($fbData,array($courseAttributesArray,'struct'));
		    
		    $queryCmd = "SELECT thumb_url as img_src from header_image where listing_id = ? and status = 'live' order by header_order limit 1";
		    $query = $dbHandle->query($queryCmd, array($instituteId));
		    $imgArray = array();
		    foreach($query->result() as $row){
			$imgArray[] = array(
				array(
				      'image' => array($row->img_src,'string'),
				      
				),'struct');
			}
		   array_push($fbData,array($imgArray,'struct'));
		   
		   $queryCmd = "SELECT access_token from facebook_access_token where userId = ?";
		   $query = $dbHandle->query($queryCmd, array($userId));
		   foreach($query->result() as $row){
			array_push($fbData,array(
			array(
			      'access_token' => array($row->access_token ,'string'),
			      
			),'struct'));
		}
		   $queryCmd = "SELECT details from institute_join_reason where institute_id = ? and status = 'live'";
		   $query = $dbHandle->query($queryCmd, array($instituteId));
		   foreach($query->result() as $row){
			array_push($fbData,array(
			array(
			      'whyJoin' => array($row->details ,'string'),
			      
			),'struct'));
			
		   }
		    
		}
		elseif($type == 'followArticle'){
		$fbData = array();
            $queryCmd = "SELECT bt.boardId,bt.blogTitle,bt.url,bi.ImageUrl from blogTable bt LEFT OUTER JOIN blogImages bi ON (bt.blogId = bi.blogId) where bt.blogId  = ? and bt.status = 'live' limit 1";            
			$query = $dbHandle->query($queryCmd, array($blogId));
			$blogDetails = array();
			foreach($query->result() as $row){
				$blogDetails[] = array(
					array(
					      'blogId'	=> array($blogId,'string'),
					      'boardId' => array($row->boardId,'string'),
					      'blogTitle'=> array($row->blogTitle,'string'),
					      'url'=>array($row->url,'string'),
					      'imageUrl'=>array($row->ImageUrl,'string')
					      
					),'struct');
			}
		array_push($fbData,array($blogDetails,'struct'));	
		   $queryCmd = "SELECT access_token from facebook_access_token where userId = ?";
		   $query = $dbHandle->query($queryCmd, array($userId));
		   foreach($query->result() as $row){
			array_push($fbData,array(
			array(
			      'access_token' => array($row->access_token ,'string'),
			      
			),'struct'));
			
			
		}
		}
		
		if(($type != 'request-E-Brochure')&&($type != 'followInstitute')&&($type!= 'followArticle')){
		$fbData = array();
 		foreach ($query->result_array() as $rowTemp)
 			array_push($fbData,array($rowTemp,'struct'));
		}
		
		$response = array($fbData,'struct');
		return $this->xmlrpc->send_response($response);
	}

	
	function editFbPostSettings($request){
		
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$columnName = $parameters['1'];
		$columnValue = $parameters['2'];
		//error_log(print_r($columnValue,true),3,'/home/aakash/Desktop/error.log');
		$appId = 12;
		$dbHandle = $this->loadDatabaseHandle('write');	
		$queryCmd = "UPDATE tuser set $columnName = $columnValue where userid = ?";
		$query = $dbHandle->query($queryCmd, array($userId));
		$resp = array('result'=>"SUCCESS",'struct');
		$response = array($resp,'struct');
		return $this->xmlrpc->send_response($response);
	}
	
	function getFbPostSettings($request){
		
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$columnName = $parameters['1'];
		$settingValue = '';
		$dbHandle = $this->loadDatabaseHandle();
		
		$queryCmd = "SELECT $columnName from tuser where userid = ?";
		$query = $dbHandle->query($queryCmd, array($userId));
		$row = $query->row();
		$settingValue = $row->$columnName;
		$response = array($settingValue,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function saveAccessToken($request){
		$appId = '12';
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$access_token = $parameters['1'];
		
		$dbHandle = $this->loadDatabaseHandle('write');
		$queryCmd = "SELECT count(*) as total from facebook_access_token where userId = ?";
		$query = $dbHandle->query($queryCmd, array($userId));
		$total = 0;
		foreach($query->result_array() as $row){
			$total = $row['total'];
		}
		error_log("user access_token = $access_token");
                $access_token = $this->getExtendedAccessToken($access_token);
		error_log("fb exchanged user access_token = $access_token");
		if($total==0){
		$queryCmd = "INSERT INTO `facebook_access_token` (`userId`,`access_token`,`status`,`creationTime`) VALUES (?,?,'live',NOW())";
                $query = $dbHandle->query($queryCmd, array($userId,$access_token));
		$resp = array('result'=>"SUCCESS",'struct');
		}else{
                $queryCmd = "UPDATE facebook_access_token SET access_token=? ,status = 'live',creationTime=NOW() where userId = ?";
                $query = $dbHandle->query($queryCmd, array($access_token,$userId));
		$resp = array('result'=>"SUCCESS",'struct');			
		//$resp = array('result'=>"access_token already exists",'struct');
		
		}
		$response = array($resp,'struct');
		return $this->xmlrpc->send_response($response);
	}
	
	function getAccessToken($request){
		$appId = '1';
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$dbHandle = $this->loadDatabaseHandle();
		$queryCmd = "SELECT * from facebook_access_token WHERE userId = ? and status ='live'";
		$query = $dbHandle->query($queryCmd, array($userId));
		$data = array();
		
                $row = $query->row();
                
		$response = array($data,'string');

                $msgArray = array();
		if(!empty($row)){
                array_push($msgArray,array(
                        array(
                            'userid'=>array($row->userId),
                            'access_token'=>array($row->access_token)
                            ),'struct')
                    );
		}
                $response = array($msgArray,'struct');
                return $this->xmlrpc->send_response($response);
	}


	
	
	function saveFacebookFollowInfo($request){
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$actionId = $parameters['1'];
		$action = $parameters['2'];
		$dbHandle = $this->loadDatabaseHandle('write');
		$articleFlag = 0;
		if($action == 'followArticle'){
			$queryCmd = "SELECT parentId from categoryBoardTable where boardId = ?";
			$query = $dbHandle->query($queryCmd, array($actionId));
			foreach($query->result() as $row){
				$actionId = $row->parentId;
			}
			$queryCmd = "SELECT count(*) as total from facebook_follow_table where userId = ? and action = 'followArticle' and actionId = ?";			
			$query = $dbHandle->query($queryCmd, array($userId, $actionId));
			foreach($query->result() as $row){
				$articleFlag = $row->total;
			}
		}
		
		
		$date = date("Y-m-d h:i:s");
		if($articleFlag == 0){
		$queryCmd = "INSERT INTO facebook_follow_table VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE userId = ? ";
		$query = $dbHandle->query($queryCmd, array($userId,$actionId,$action,$date,$userId));
		}
		
		if($articleFlag == 0){
		$resp = array('result'=>"SUCCESS",'struct');
		}else{
		$resp = array('result'=>"alreadySubscribedArticle",'struct');	
		}
		$response = array($resp,'struct');
		error_log(print_r($response,true),3,'/home/aakash/Desktop/error.log');
		return $this->xmlrpc->send_response($response);
	}
	
	function deleteAccessToken($request){
	$parameters = $request->output_parameters();
	$userId = $parameters['0'];
	$appId ='1';
	$dbHandle = $this->loadDatabaseHandle('write');
	$queryCmd = "UPDATE facebook_access_token SET status='deleted' where userId = ?";
        $query = $dbHandle->query($queryCmd, array($userId));
	$resp = array('result'=>"SUCCESS",'struct');
	$response = array($resp,'struct');
	return $this->xmlrpc->send_response($response);
	}
	
	function getUserDetailsForEventUpdates($request){
	$parameters = $request->output_parameters();
	$appId = $parameters['0'];
	$dbHandle = $this->loadDatabaseHandle();
	$this->load->library('facebookconfig');
		$detailArray = array();
		$date = date('Y-m-d 00:00:00');
		$timeStampHalfHour = date('Y-m-d h:i:s',strtotime(CRON_TIME_VAR."hour"));
		$queryEvent = "SELECT e.event_id,e.event_title,e.venue_id,e.listing_type_id,ed.start_date,ed.end_date,ev.venue_name,ev.Address_Line1 from event e ,event_date ed , event_venue ev where e.listingType = 'course'  and e.event_id = ed.event_id and e.venue_id = ev.venue_id and ed.end_date>=? and e.creationDate>'$timeStampHalfHour'";
		error_log($queryEvent);
		$query = $dbHandle->query($queryEvent, array($date));
		foreach($query->result() as $row){
		$courseId = $row->listing_type_id;
		$eventId = $row->event_id;
		$eventTitle = $row->event_title;
		$eventVenue = 	$row->venue_name;
		$eventAddress = $row->Address_Line1;
		$startDate = $row->start_date;
		$endDate = $row->end_date;
			$queryInstitute  = "select institute_id from institute_courses_mapping_table where course_id = ?";
			$queryIns = $dbHandle->query($queryInstitute, array($courseId));
			foreach($queryIns->result() as $rowIns){
			$instituteId = $rowIns->institute_id;
			
				$queryUser = "SELECT tu.userid,fat.access_token from tuser tu,facebook_access_token fat,facebook_follow_table fft where fft.action = 'followInstitute' AND fft.actionId = ? and tu.publishInstituteFollowing =1 and tu.userid = fft.userId and fat.userId = fft.userId";
			
				$queryU = $dbHandle->query($queryUser, array($instituteId));
				foreach($queryU->result() as $rowU){
					$userId = $rowU->userid;
					$access_token = $rowU->access_token;
					$query1 = "SELECT lm.listing_title,ilt.locality_name locality_name ,cct.city_name from institute_location_table ilt INNER JOIN countryCityTable cct ON (ilt.city_id = cct.city_id) INNER JOIN listings_main lm ON (lm.listing_type_id = ilt.institute_id) where ilt.institute_id=? and ilt.status = 'live' and lm.status= 'live' and lm.listing_type ='institute' LIMIT 1";
					$query1 = $dbHandle->query($query1, array($instituteId));
						foreach($query1->result() as $row1){
						$city = $row1->city_name;
						$locality = $row1->locality_name;
						$instituteName = $row1->listing_title;
						$query2 = "SELECT thumb_url as img_src from header_image where listing_id = ? and status = 'live'order by header_order limit 1";
							$query2 = $dbHandle->query($query2, array($instituteId));
							$imgSrc='';
							foreach($query2->result() as $row2){
							$imgSrc = $row2->img_src;
							}
					}
					array_push($detailArray,array(
					array(
					      'userId' => $userId,
					      'access_token'=> $access_token,
					      'event_id' => $eventId,	
					      'event_title' => $eventTitle,
					      'start_date' => $startDate,
					      'end_date' => $endDate,
					      'event_venue'=>$eventVenue,
					      'event_address'=>$eventAddress,
					      'instituteId'=>$instituteId,
					      'instituteName'=>$instituteName,
					      'imgScr'=>$imgSrc,
					      'city'=>$city,
					      'locality'=>$locality
					      ),'struct'      
					      ));
					}
	
					
				}
			
		}
		
		$response = array($detailArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getUserDetailsForArticlesUpdates($request){
	$parameters = $request->output_parameters();
	$appId = $parameters['0'];
		$this->load->library('facebookconfig');
		$dbHandle = $this->loadDatabaseHandle();
		$detailArray = array();
		//$timeStampHalfHour = date('Y-m-d h:i:s',strtotime(CRON_TIME_VAR."hour"));
          $timeStampHalfHour = date('Y-m-d H:i:s');
		//$queryArticle = "SELECT bt.blogId ,bt.blogTitle,bt.url ,bt.boardId,cbt.parentId,(select urlName from categoryBoardTable  where boardId = cbt.parentId )urlName,(select name from categoryBoardTable where boardId = cbt.parentId)Categoryname from blogTable bt,categoryBoardTable cbt where bt.creationDate>='$timeStampHalfHour' and bt.boardId = cbt.boardId";
		$queryArticle = "SELECT DISTINCT bt.creationDate, bt.blogId, bt.blogTitle, bt.url, bt.boardId, cbt.parentId, urlName, cbt.name Categoryname  FROM blogTable bt, categoryBoardTable cbt WHERE  TIMESTAMPDIFF(SECOND , bt.creationDate, '$timeStampHalfHour' ) <= 3600 AND bt.boardId = cbt.boardId and bt.status != 'draft' ";
		$queryArticle = $dbHandle->query($queryArticle);
		foreach($queryArticle->result() as $row){
		$parentId = $row->parentId;
		$blogId = $row->blogId;
		$queryAI = "SELECT imageUrl from blogImages where blogId = ? limit 1";
		$queryAI = $dbHandle->query($queryAI, array($blogId));
		$articleImage = '';
		foreach($queryAI->result() as $aI){
		$articleImage = $aI->imageUrl;
		}
		$queryCmd = "SELECT tu.userid,fat.access_token from tuser tu,facebook_access_token fat,facebook_follow_table fft where fft.action = 'followArticle' and tu.publishArticleFollowing =1 and fft.actionId = ? and tu.userid = fft.userId and fat.userId = fft.userId";		
		error_log($queryCmd);
		$query1 = $dbHandle->query($queryCmd, array($parentId));
		foreach($query1->result() as $row1){
			array_push($detailArray,array(
					array(
					      'userId' => $row1->userid,
					      'access_token'=> $row1->access_token,
					      'blog_id' => $row->blogId,	
					      'blog_title' => $row->blogTitle,
					      'categoryId' => $row->parentId,
					      'categoryName' => $row->Categoryname,
					      'categoryUrl' => $row->urlName,
					      'blogUrl' => $row->url,
					      'articleImage' => $articleImage,
					      ),'struct'      
					      ));
		}
		}		
		$response = array($detailArray,'struct');
		return $this->xmlrpc->send_response($response);
	
	}
	
	function checkThresholdValue($request){
	$parameters = $request->output_parameters();
	$userId = $parameters['0'];
	$appId ='1';
	$dbHandle = $this->loadDatabaseHandle();
		$date = date('Y-m-d 00:00:00');
	$queryCmd = "SELECT count(*) as value from messageTableFacebookLog where userId = ? and creationDate>'$date'";
	$count =0;
        $query = $dbHandle->query($queryCmd, array($userId));
	foreach($query->result() as $row){
		$count = $row->value;
	}
	$resp = array('count'=>$count,'struct');
	$response = array($resp,'struct');
	return $this->xmlrpc->send_response($response);	
	}
	
	function updateFacebookFlag($request){
	$parameters = $request->output_parameters();
	$action = $parameters['0'];
	$actionId = $parameters['1'];
		$dbHandle = $this->loadDatabaseHandle('write');
		if($action == 'followArticle'){
		$queryCmdUpdate = "update blogTable set facebookFlag =1 where blogId = ?";
		$dbHandle->query($queryCmdUpdate, array($actionId));
		}
		
		if($action == 'followInstitute'){
		$queryCmd = "SELECT course_id from institute_courses_mapping_table where institute_id = ?";
		
		$query = $dbHandle->query($queryCmd, array($actionId));
		$courseIds = array();
		foreach($query->result() as $row){
		$courseIds[] = $row->course_id;
		}
		$courseIds = implode(",",$courseIds);
		
		$date = date("Y-m-d h:i:s");
		$queryCmd = "SELECT e.event_id from event e INNER JOIN event_date ed ON (e.event_id = ed.event_id) where listing_type_id IN ($courseIds) and listingType = 'course' and ed.end_date>?";
		
		$query = $dbHandle->query($queryCmd, array($date));
		$eventIds = array();
		foreach($query->result() as $row){
		$eventIds[] = $row->event_id;
		}
		$eventIds = implode(",",$eventIds);
		
		$queryCmdUpdate = "update event set facebookFlag =1 where event_id IN ($eventIds)";
		$dbHandle->query($queryCmdUpdate);
		
		}
             $resp = array('result'=>"Success",'struct');
	     $response = array($resp,'struct');
             return $this->xmlrpc->send_response($response);
		
	}
	
	function checkLinkArticle($request){
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$categoryId = $parameters['1'];
		$dbHandle = $this->loadDatabaseHandle();
		
		$queryCmd = "SELECT actionId from facebook_follow_table where action = 'followArticle' and userId = ?";
		$query  = $dbHandle->query($queryCmd, array($userId));
		$actionId= array();
		$flag=0;
		foreach($query->result() as $row){
			$flag=1;	
			$actionId[] = $row->actionId;
		}
		
		$queryCmd = "SELECT parentId from categoryBoardTable where boardId = ?";
		$query  = $dbHandle->query($queryCmd, array($categoryId));
		$parentId='';
		$flag=0;
		foreach($query->result() as $row){
			$flag=1;	
			$parentId = $row->parentId;
		}		
		if(!empty($actionId)){
			if(in_array($parentId,$actionId)){
			$flag =2;	
			}		
		}
		
		if($flag == 0){
		$resp = "FollowButton";	
		}
		if($flag == 1){
		$resp = "FollowLink";	
		}
		if($flag == 2){
		$resp = "noFButton";	
		}
		
	     $resp = array('result'=>$resp,'struct');
	     $response = array($resp,'struct');
	     return $this->xmlrpc->send_response($response);
	}
	//pranjul starts
	function donShowAgain($request){ 
            $appId = '12';
	    $parameters = $request->output_parameters();
            $userId = $parameters['0'];
            $cookieAuto = $parameters['1'];
   	    $dbHandle = $this->loadDatabaseHandle('write');
             $queryCmdUpdate = "update tuser set $cookieAuto = '2' where userId = ?";
             $queryUpdate = $dbHandle->query($queryCmdUpdate, array($userId));
             $resp = array('result'=>"Success",'struct');
	     $response = array($resp,'struct');
             return $this->xmlrpc->send_response($response);
            
        }

        function getAccountSetttingInfo($request){ error_log("i m inside getAccountSettingInfo today server ");
                $appId = '1';
		$parameters = $request->output_parameters();error_log("i m inside getAccountSettingInfo today server ".print_r($parameters,true));
		$action = $parameters['0'];
                if($action=='user')
                    $action = 'publishQuestionOnFB';
                $userId = $parameters['1'];
		$dbHandle = $this->loadDatabaseHandle();
		//$queryCmd = "SELECT publishAnaActivity from tuser WHERE userId = '".$userId."'";
                /*$queryCmd =  "SELECT publishAnaActivity from tuser as tu ,facebook_access_token as fat WHERE fat.userId = tu.userid and fat.status='live' and fat.userId ='".$userId."'";
		$res= $dbHandle->query($queryCmd);
                $result = $res->row();error_log("i m inside getAccountSettingInfo today server result".print_r($result,true));*/
                $resp = array();
                //$queryCmd1 = "SELECT tu.$action ,fat.access_token from tuser as tu,facebook_access_token as fat WHERE fat.userId = tu.userid and fat.userId = '".$userId."' and status ='live'";
		$queryCmd1 = "SELECT tu.$action ,fat.access_token from tuser as tu LEFT JOIN facebook_access_token as fat ON (fat.userId = tu.userid and fat.status='live') WHERE tu.userid = ?";
                $res1= $dbHandle->query($queryCmd1, array($userId));
                $result1 = $res1->row();
                if($result1){
                //if($result->publishAnaActivity==1){
                  //  $resp = array('result'=>"DoNotShowAll###".$result1->access_token,'struct');
		 //}else{
                     
                     if($result1->$action==1){
                         $resp = array('result'=>'DoNotShow###'.$result1->access_token,'struct');
                     }else if($result1->$action==2){
                         $resp = array('result'=>'NeverShow###'.$result1->access_token,'struct');
                     }else{
                         $resp = array('result'=>$action.'###'.$result1->access_token,'struct');
                     }

                    
                //}
                }else{
                    //$resp = array('result'=>'NeverShow###','struct');
                    //$queryCmd2 = "SELECT $action ,access_token from facebook_access_token WHERE userId = '".$userId."'";
                    //$queryCmd2 = "SELECT tu.$action ,fat.access_token from tuser as tu,facebook_access_token as fat WHERE fat.userId = tu.userid and fat.userId = '".$userId."'";
		    $queryCmd1 = "SELECT tu.$action ,fat.access_token from tuser as tu LEFT JOIN facebook_access_token as fat ON (fat.userId = tu.userid) WHERE tu.userid = ?";
                    $res2= $dbHandle->query($queryCmd2, array($userId));
                    $result2 = $res2->row();
                    

                    if($result2->$action==1){
                        
                         $resp = array('result'=>$action.'###','struct');
                     }else if($result2->$action==2){
                         
                         $resp = array('result'=>'NeverShow###','struct');
                     }else{
                         
                         $resp = array('result'=>$action.'###','struct');
                    }
                }
                $response = array($resp,'struct');
                return $this->xmlrpc->send_response($response);
        	}
	
	        function getUserInfo($email){
		$dbHandle = $this->loadDatabaseHandle();
		$queryCmd = "select userid from tuser where email = ?";
		$res = $dbHandle->query($queryCmd, array($email));
                $result = $res->row();
                $numOfRows = $res->num_rows();
                if($numOfRows){
                    return $result->userid;
                }else{
                    return 'noResult';
                }
            
        }
	
	function saveAccessToken_AnA($request){ error_log("i m inside saveAccessToken done");
		$appId = '12';
		$parameters = $request->output_parameters();
		$access_token = $parameters['1'];
                $email = $parameters['2'];
                $automaticFShare = $parameters['3'];
                $cookieAuto = $parameters['4'];
                if($parameters[0]!='0'){
                   $userId = $parameters['0'];
                }
                else
                {
                   $userId = $this->getUserInfo($email);
                }
		$dbHandle = $this->loadDatabaseHandle('write');
                if($automaticFShare!= -1){
                    //$queryCmdUpdate = "update facebook_access_token set $cookieAuto = $automaticFShare,`status`='live' where userId = '".$userId."'";
                    $queryCmdUpdate = "update tuser set $cookieAuto = $automaticFShare where userId = ?";
                    $queryUpdate = $dbHandle->query($queryCmdUpdate, array($userId));
                }
		$queryCmd = "SELECT count(*) as total from facebook_access_token where userId = ?";
		$query = $dbHandle->query($queryCmd, array($userId));
		$total = 0;
		foreach($query->result_array() as $row){
			$total = $row['total'];
		}
		$access_token = $this->getExtendedAccessToken($access_token);
		if($total==0){ 
                    $queryCmd = "INSERT INTO `facebook_access_token` (`userId`,`access_token`,`status`,`creationTime`) VALUES (?,?,'live',NOW())";
                    $query = $dbHandle->query($queryCmd, array($userId,$access_token));
                    $resp = array('result'=>"SUCCESS",'struct');
		}else{
		    $queryCmd = "update `facebook_access_token` set `access_token` = ? ,status = 'live',creationTime=NOW() where `userId`= ?";
                    $query = $dbHandle->query($queryCmd, array($access_token,$userId));
                    $resp = array('result'=>"access_token already exists",'struct');
		}
		$response = array($resp,'struct');
		return $this->xmlrpc->send_response($response);
	}
	
	function getAccessToken_AnA($request){
		$appId = '1';
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$dbHandle = $this->loadDatabaseHandle();
		$queryCmd = "SELECT * from facebook_access_token WHERE userId = ?";
		$query = $dbHandle->query($queryCmd, array($userId));
		$data = array();

                $row = $query->row();
                $numOfRows = $query->num_rows();
		//$response = array($data,'string');
                 $msgArray = array();
                if($numOfRows){

                array_push($msgArray,array(
                        array(
                            //'userid'=>array($row->userid),
                            'access_token'=>array($row->access_token)
                            ),'struct')
                    );
                }else{

                        $msgArray = array(array(array('access_token'=>"noresult"),'struct'),'struct');
                }
                $response = array($msgArray,'struct');
                return $this->xmlrpc->send_response($response);
	}
	
	//pranjul ends
	
	function saveUserFullInfo(){
		return false;
		try{
			$this->load->library('dbLibCommon');
			$this->dbLibObj = DbLibCommon::getInstance('Facebook');
			$dbHandle = $this->dbLibObj->getWriteHandle();
			$sql = "select userId, access_token from facebook_access_token where creationTime > now() - interval 5 minute and status = 'live'";
			$query = $dbHandle->query($sql);
			$this->load->library("facebook");
			$FB = new Facebook();
			
			foreach($query->result_array() as $row){
				$User = $FB->api("/me",array("access_token" => $row['access_token']));
				$sql = "replace into facebookInfo(userid,facebookUserid,facebookName,lastUpdateTime,locationText)
					values(".$row['userId'].",".$User['id'].",'".mysql_escape_string($User['name'])."','".$User['updated_time']."','".$User['location']['name']."')";
				$dbHandle->query($sql);
				
				$sql = "replace into facebookEducationHistory(facebookUserId,facebookSchoolId,facebookSchoolName,type,year) values";
				$values = array();
				foreach($User['education'] as $edu){
					$values[] = "(".$User['id'].",".$edu["school"]["id"].",'".mysql_escape_string($edu["school"]["name"])."','".$edu["type"]."','".$edu["year"]["name"]."')";
				}
				$sql .= implode(", ",$values);
				$dbHandle->query($sql);
				
				$sql = "replace into facebookWorkHistory(facebookUserId,facebookEmployerId,facebookEmployerName,start_date,end_date) values";
				$values = array();
				foreach($User['work'] as $emp){
					$values[] = "(".$User['id'].",".$emp["employer"]["id"].",'".mysql_escape_string($emp["employer"]["name"])."','".$emp["start_date"]."-01','".$emp["end_date"]."-01')";
				}
				if(count($values)>0) {
					$sql .= implode(", ",$values);
					$dbHandle->query($sql);
				}
				
				$FriendsJson = $FB->api("/me/friends",array("access_token" => $row['access_token']));
				$sql = "replace into facebookFriends values";
				$values = array();
				$FriendIds = array();
				foreach($FriendsJson["data"] as $friend) {
					$FriendIds[] = $friend['id'];
					$values[] = "(".$row['userId'].",'".$User['id']."','".mysql_escape_string($User['name'])."','".$friend['id']."','".mysql_escape_string($friend['name'])."')";
				}
				if(count($values)>0) {
					$sql .= implode(", ",$values);
					$dbHandle->query($sql);
				}
				
				foreach($FriendIds as $friend){
					$User = $FB->api("/$friend",array("access_token" => $row['access_token']));
					$sql = "replace into facebookEducationHistory(facebookUserId,facebookSchoolId,facebookSchoolName,type,year) values";
					$values = array();
					foreach($User['education'] as $edu){
						$values[] = "(".$User['id'].",".$edu["school"]["id"].",'".mysql_escape_string($edu["school"]["name"])."','".$edu["type"]."','".$edu["year"]["name"]."')";
					}
					if(count($values)>0) {
						$sql .= implode(", ",$values);
						$dbHandle->query($sql);
					}
					
					$sql = "replace into facebookWorkHistory(facebookUserId,facebookEmployerId,facebookEmployerName,start_date,end_date) values";
					$values = array();
					foreach($User['work'] as $emp){
						$values[] = "(".$User['id'].",".$emp["employer"]["id"].",'".mysql_escape_string($emp["employer"]["name"])."','".$emp["start_date"]."-01','".$emp["end_date"]."-01')";
					}
					if(count($values)>0) {
						$sql .= implode(", ",$values);
						$dbHandle->query($sql);
					}
				}
			}
		}
		catch(Exception $e) {
			error_log("Exception while storing full facebook info: ".$e->getMessage());
		}
	}
	
	function getExtendedAccessToken($access_token){
		$this->load->library("facebook");
		$FB = new Facebook(array("appId" => FACEBOOK_API_ID, "secret" => FACEBOOK_SECRET_KEY));
		try {
			return $FB->getExtendedAccessToken($access_token);
		}
		catch(Exception $e){
			error_log("Exception : ".$e->getMessage());
		}
		return false;
	}
}
