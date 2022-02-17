<?php

class userRemindersMigrationScript extends MX_Controller {
	
	function __construct(){
	}
	
	function initDB() {

		$this->load->library("common/DbLibCommon");
		$this->dbLibObj = DbLibCommon::getInstance('ShikshaApply');
		$this->dbHandle = $this->_loadDatabaseHandle("write");
	}
	
	function addUserIdsForRmcUserCourseRatingId(){
		
		// ini_set('max_execution_time', -1);
		// ini_set('memory_limit', '-1');
		$seeker = 0;
		$this->initDB();
		error_log("fetch userids");
		// first get all userIds and courseRatingIds from rmcUserCourseRating for all rmcUserCourseRatingIds in rmcUserReminders
		$sql = "select rmcr.userId , rmcr.rmcUserCourseRatingId from rmcUserCourseRating  rmcr inner join rmcUserReminders rmur 
				on rmcr.rmcUserCourseRatingId =  rmur.rmcUserCourseRatingId ";
			
		$rows = $this->dbHandle->query($sql)->result_array();
		//_p($rows);
		error_log("update sql");
		foreach ($rows  as $k=>$v)
		{
			// now get update all the rows of rmcUserReminders ...
			$sql = 	"UPDATE rmcUserReminders SET userId= ? WHERE rmcUserCourseRatingId=?";			
			$result = $this->dbHandle->query($sql,array($v['userId'],$v['rmcUserCourseRatingId']));
		//	 echo $this->dbHandle->last_query();
		//	 _p($result);
		
			error_log("==shiksha== rmc userId updated : ".$v['userid']."--rmcUserCourseRatingId--".$v['rmcUserCourseRatingId']);
		}
		
		error_log("update successful");

	}
	function rectifyUserStatusInRMCCandidates()
	{
		// ini_set('max_execution_time', -1);
		// ini_set('memory_limit', '-1');
		$seeker = 0;
		$this->initDB();
		error_log("fetch userids");
		// first get all userIds from rmcUserStage where stageID is 10
		$sql = "select userId from rmcUserStage where status='live' and stageId='10'";
			
		$rows = $this->dbHandle->query($sql)->result_array();
		//echo $this->dbHandle->last_query();
		error_log("update users in rmc Candidates from live to dropoff");
		foreach ($rows  as $k=>$v)
		{
			// now get update all the rows of rmcUserReminders ...
			$sql = 	"UPDATE rmcCandidates SET status='dropoff' WHERE userId=?";			
			$result = $this->dbHandle->query($sql,array($v['userId']));
			//echo $this->dbHandle->last_query();		
			error_log("==shiksha== rmc candidate status updated");
		}
		
		error_log("update successful");
	}

	 public function getUserArticleData() {

	 		ini_set('max_execution_time', -1);
			ini_set('memory_limit', '-1');
			$seeker = 0;
			$this->initDB();

            $data = array('timePeriodForDataAnalysis' => '6 month',
                            'urlPattern'                => '-articlepage-');

            $offsetForData 	= 0;
            $dataChunk = 5000;
            // This is pseudo User ID generated for users who are not logged in. There userId in database is -1
            $tempUserIdStart 				= 7000000;
            $uniqueUserCount			 	= 0;
            $sessionMapToPsuedoUserId 		= array();
            $articleIds						= array();
            $userIds						= array();
            $csvArray 						= array();

             // do{
                //error_log('data_fetch from '.$offsetForData.': '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
              
                /*****************************************************************************************/
			        $dateCheck = date('Y-m-d',  strtotime('-'.$data['timePeriodForDataAnalysis']));

			        $this->_initiateModel('write');
			        $this->dbHandle->select('SQL_CALC_FOUND_ROWS sessionId,userId,url',FALSE);
			        $this->dbHandle->from('studyAbroadUserMovementTracking');
			        $this->dbHandle->where('timeStamp >=' , $dateCheck);

			        $this->dbHandle->like('url',$data['urlPattern']);
			        			        
			        $result['data'] = $this->dbHandle->get()->result_array();

			        $sql = 'SELECT FOUND_ROWS() as totalRows';
			        $rowsResult = $this->dbHandle->query($sql)->row_array();
			        $result['totalRows'] = $rowsResult['totalRows'];
			        // _p($result);
			        // die;
                /****************************************************************************************/
                
                foreach ($result['data'] as $data){
                    $userId = $data['userId'];

                    if($userId == -1)
                    {  //incase of logged out user
                        $sessionId = $data['sessionId'];  //consider the session id
                        if($sessionMapToPsuedoUserId[$sessionId]){  //if that sessionId already exixts then we can consider users of same have sessionIds as same                            
                        }else{
                            $tempUserIdStart += 1;			//for each new non logged in user case assign a temp user id
                            $sessionMapToPsuedoUserId[$sessionId] = $tempUserIdStart;   //assign the temp user id
                        }
                        $userId = $sessionMapToPsuedoUserId[$sessionId];   //assing it the temp user id 
                    }
                    //parse the url pattern
                    $url = parse_url($data['url'],PHP_URL_PATH);
                    //explode url to get article id
                    $explodedUrl = explode("-", $url);
                    $len = count($explodedUrl);
                    $articleId  = $explodedUrl[$len-1];
                    
                    $csvArray[]		 =$userId.",".$articleId;
                    
                    // echo "/************************************************/";
                }                
            //   $offsetForData += $dataChunk;
            // }while($offsetForData <= $result['totalRows']);

            $file = fopen("/home/shweta/Desktop/articleUser/userarticlecsv.csv","w");
            foreach ($csvArray as $line)
				{
					$x= fputcsv($file,explode(',',$line));
					 var_dump($x);
				}
			fclose($file);
        }


        public function setUserArticleAnalysisData(){
        	die;
        	ini_set('max_execution_time', -1);
			ini_set('memory_limit', '-1');
			$seeker = 0;
        	$this->initDB();
            $dataArrayForDB = array();
            $dateAdded = date('Y-m-d');
            /********************************************************************************/
            // upload your file  and read the contents tab delimited
            //$file = "/home/shweta/Documents/recommendation algorithms/cooccurence";
            //$file = "/home/shweta/Documents/recommendation algorithms/cosine";
            //$file = "/home/shweta/Documents/recommendation algorithms/loglikelihood";
            $file = "/home/shweta/Documents/recommendation algorithms/tanimoto";
            
            $cols = array();
			ini_set('auto_detect_line_endings', true);

			$fh = fopen($file, 'r');
			//var_dump($fh);
						
			while (($line = fgetcsv($fh, 1000, "\t")) !== false) {
			  $cols[] = $line;
			  }	
			
			/*********************************************************************************************/
			//in the 2D array each sub array 1st index is article1, 2nd index is article 2 and 3rd index is their similarity score
			$dataArrayForDB = array();
			
			foreach ($cols as $value) {
			
				 $dataArrayForDB[] = array(  	'articleId1'  => $value[0],
                                                'articleId2'  => $value[1],
                                                'score'       => $value[2],
                                                'dateUpdated' => $dateAdded,
                                                'status'      => 'live'
                                            );						 
			}
			/*********************************************************************************************/
           	//store in the db
            $this->_initiateModel('write');
	        $this->dbHandle->trans_start();
	        $this->dbHandle->insert_batch('studyAbroadArticleTanimotoAnalysis',$dataArrayForDB);       
	        
	        $this->dbHandle->trans_complete();
			
	        if ($this->dbHandle->trans_status() === FALSE) {
	            throw new Exception('Transaction Failed');
	        }
	        
	       /************************************************************************************************/
        }

        public function recommendationAnalysisData()
        {
        	if(!empty($_POST['articleId']))
        		{
        			$articleId = $_POST['articleId'];
        			echo "ArticleId submitted: ".$articleId;

        			$this->initDB();
        			$this->_initiateModel('read');
        			//fetch cooccurence similarity
        			$this->dbHandle->select('ra.articleId2,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleCooccurenceAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId2 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId1' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');
					
					$result['cooccurence1'] = $this->dbHandle->get()->result_array();
					//_p($result['cooccurence1']);
        			//fetch cosine similarity
			        
			        $this->dbHandle->select('ra.articleId2,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleCosineAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId2 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId1' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');
					
			        $result['cosine1'] = $this->dbHandle->get()->result_array();
			        //_p($result['cosine1']);
			        //fetch loglikelihood similarity
			        $this->dbHandle->select('ra.articleId2,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleLoglikelihoodAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId2 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId1' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');
					
			        $result['loglikelihood1'] = $this->dbHandle->get()->result_array();
			        //_p($result['loglikelihood1']);
			        //fetch tanimoto similarity
			        
			        $this->dbHandle->select('ra.articleId2,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleTanimotoAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId2 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId1' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');
					
			        $result['tanimoto1'] = $this->dbHandle->get()->result_array();
			        //_p($result['tanimoto1']);
			        /**********************************************/
			        //fetch cooccurence similarity
			        $this->dbHandle->select('ra.articleId1,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleCooccurenceAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId1 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId2' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');

					$result['cooccurence2'] = $this->dbHandle->get()->result_array();
					//_p($result['cooccurence2']);
			        $this->dbHandle->select('ra.articleId1,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleCosineAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId1 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId2' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');

			        $result['cosine2'] = $this->dbHandle->get()->result_array();
			        //_p($result['cosine2']);
			        //fetch loglikelihood similarity
			         $this->dbHandle->select('ra.articleId1,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleLoglikelihoodAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId1 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId2' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');

			        $result['loglikelihood2'] = $this->dbHandle->get()->result_array();
			        //_p($result['loglikelihood2']);
			        //fetch tanimoto similarity
			         $this->dbHandle->select('ra.articleId1,score,sac.strip_title,sac.contentURL',FALSE);
			        $this->dbHandle->from('studyAbroadArticleTanimotoAnalysis ra');
			        $this->dbHandle->join('study_abroad_content sac','sac.content_id = ra.articleId1 and sac.status in("live","deleted")','inner');
			        $this->dbHandle->where('ra.articleId2' , $articleId);
			        $this->dbHandle->order_by('ra.score' , 'DESC');
			        $this->dbHandle->limit('5');
			        $result['tanimoto2'] = $this->dbHandle->get()->result_array();
			        //_p($result['tanimoto2']);

			        //sort and merge cooccurence and slice
			       $result['cooccurence']= array_merge($result['cooccurence2'],$result['cooccurence1']);
			       usort($result['cooccurence'], function($a,$b){
			       		if($a['score'] > $b['score'])return -1;
			       		else return 1;
			       });
			       $data['cooccurence'] = array_slice($result['cooccurence'], 0,5);
			       
			        //sort and merge loglikelihood
			       $result['loglikelihood']= array_merge($result['loglikelihood2'],$result['loglikelihood1']);
			       usort($result['loglikelihood'], function($a,$b){
			       		if($a['score'] > $b['score'])return -1;
			       		else return 1;
			       });
			       $data['loglikelihood'] = array_slice($result['loglikelihood'], 0,5);
			       
			        //sort and merge consine
			       $result['cosine']= array_merge($result['cosine2'],$result['cosine1']);
			       usort($result['cosine'], function($a,$b){
			       		if($a['score'] > $b['score'])return -1;
			       		else return 1;
			       });
			       $data['cosine'] = array_slice($result['cosine'], 0,5);
			       
			       
			        //sort and merge tanimoto
			       $result['tanimoto']= array_merge($result['tanimoto2'],$result['tanimoto1']);
			       usort($result['tanimoto'], function($a,$b){
			       		if($a['score'] > $b['score'])return -1;
			       		else return 1;
			       });
			       $data['tanimoto'] = array_slice($result['tanimoto'], 0,5);
			       
			        $displayData['result'] = $data;
        		}
        		
        	$this->load->view("articleRecommendation",$displayData);
        }
}
?>

