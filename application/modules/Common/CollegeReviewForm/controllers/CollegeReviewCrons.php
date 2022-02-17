<?php 

class CollegeReviewCrons extends MX_Controller{
	
	private $crModel;
	private $crLib;

	private function _init(){
		$this->load->model('collegereviewmodel');
		$this->crModel = new CollegeReviewModel;

		$this->load->library('CollegeReviewLib');
		$this->crLib = new CollegeReviewLib;
	}

	/*
     * sends mail with attached CSV file having One day stats of College Reviews
     */

	function sendDailyReport(){
		$this->validateCron();
		$this->_init();
		$date = date("Y-m-d", strtotime("-1 day"));
		$totalCount = $this->crModel->getReviewCountByDate($date);
		$moderatorData = $this->crModel->getModerationDetailsByDate($date);

		$moderationDetails = $this->_makeModeratorDetails($moderatorData);

		$subject = 'College Reviews Daily Report for '.$date;
		$message = 'Here is the daily report of College Reviews for '.$date."<br/><br/>";
		$message .= '&nbsp;1. Total Reviews Sourced: '.$totalCount."<br/>";
		$message .= '&nbsp;2. Total Reviews Moderated: '.count($moderatorData)."<br/>";
		$message .= '&nbsp;&nbsp;&nbsp;2a. Accepted: '.$moderationDetails['acceptedReviews']."<br/>";
		$message .= '&nbsp;&nbsp;&nbsp;2b. Rejected: '.$moderationDetails['rejectedReviews']."<br/>";
		$message .= '&nbsp;&nbsp;&nbsp;2c. Marked Later: '.$moderationDetails['laterReviews']."<br/>";
		$message .= '&nbsp;&nbsp;&nbsp;2d. Published: '.$moderationDetails['publishedReviews']."<br/>";
		$message .= '&nbsp;&nbsp;&nbsp;2e. Edit: '.$moderationDetails['editReviews']."<br/>";
		$message .= '&nbsp;&nbsp;&nbsp;2f. Mapped: '.$moderationDetails['mappedReviews']."<br/><br />";
		$message .= 'The in-depth moderation report for individual moderators is attached with this email.<br /><br />-Thanks,<br />LDB Team';

		$grandTotal = 0;
		$csv = array();
		$csv[] = array('Moderator','Total Moderated', 'Accepted', 'Rejected', 'Marked Later', 'Mapped', 'Published', 'Edit' );
		
		foreach($moderationDetails['moderatorDetails'] as $row=>$col){
			$csv[] = array($row, $col['total'],$col['accepted'], $col['rejected'], $col['later'],$col['Mapped'],$col['published'],$col['Edit']  );
			$grandTotal = $grandTotal + $col['total'];
		}

		$csv[] = array('Grand Total',$grandTotal,$moderationDetails['acceptedReviews'],$moderationDetails['rejectedReviews'],$moderationDetails['laterReviews'],$moderationDetails['mappedReviews'],$moderationDetails['publishedReviews'],$moderationDetails['editReviews']);
       
       // $to = 'karan.chawla@shiksha.com, md.ansari@shiksha.com, n.agarwal@shiksha.com, mehrotra.neha09@gmail.com'; 
       $to = 'pranjul.raizada@shiksha.com, abhinav.pandey@shiksha.com, n.agarwal@shiksha.com, shipra.suman@shiksha.com, sahil.sharma@shiksha.com, abhijit.bhowmick@shiksha.com'; 
	   
       $this->crLib->send_csv_mail($csv,$message, $to, $subject, 'info@shiksha.com');

	}

	/*
     * seperates moderation action of each moderator from the input data (actionType, ModeratorEmail from CollegeReview_ModerationTable)
     * @params:  $moderatorData => array of actionType, ModeratorEmail from CollegeReview_ModerationTable 
     *
     *  @returns => array having count of distinct actionType of the moderator
     */
	private function _makeModeratorDetails($moderatorData){
		
		$moderatorDetails = array();
		$data = array();

		$data['publishedReviews'] = 0;
		$data['acceptedReviews'] = 0;
		$data['rejectedReviews'] = 0;
		$data['laterReviews'] = 0;
		$data['editReviews'] = 0;
		$data['mappedReviews'] = 0;

		foreach($moderatorData as $row=>$col){
			if(empty($moderatorDetails[$col['moderatorEmail']])){
				$moderatorDetails[$col['moderatorEmail']] = array('total' => 0, 'accepted' => 0, 'rejected' => 0, 'later' => 0, 'published' => 0, 'Edit' => 0, 'Mapped' => 0);
			}

			switch ($col['actionType']) {
				case 'published':
					$moderatorDetails[$col['moderatorEmail']]['published']++;
					$data['publishedReviews']++;
					break;

				case 'accepted':
					$moderatorDetails[$col['moderatorEmail']]['accepted']++;
					$data['acceptedReviews']++;
					break;	

				case 'later':
					$moderatorDetails[$col['moderatorEmail']]['later']++;
					$data['laterReviews']++;
					break;

				case 'rejected':
					$moderatorDetails[$col['moderatorEmail']]['rejected']++;
					$data['rejectedReviews']++;
					break;	

				case 'Edit':
					$moderatorDetails[$col['moderatorEmail']]['Edit']++;
					$data['editReviews']++;
					break;

				case 'Mapped':
					$moderatorDetails[$col['moderatorEmail']]['Mapped']++;
					$data['mappedReviews']++;
					break;						
			}
			$moderatorDetails[$col['moderatorEmail']]['total']++;

		}

		$data['moderatorDetails'] = $moderatorDetails;
		return $data;
	}

	/*  IP tracking Cron
	 *	Purpose of this cron is to identify college review posted on a course from same IP address.
	 *	This API generates mail content having trackng data and add it to the mail queue.
	 * 	API process one day of College Reviews
	 */
	public function reviewsIPTracking(){
		$this->validateCron();
		$this->_init();
		$from = date("Y-m-d", strtotime("-1 day")).' 00:00:00';
		$to = date("Y-m-d").' 00:00:00';;

		$collegeReviewSourceData = $this->crModel->collegeReviewSourceData($from, $to);
		// Map IP Address against session IDs in college review source data
		$collegeReviewSourceData = $this->mapSessionIdToClientIPInReviewsData($collegeReviewSourceData);

		if(empty($collegeReviewSourceData)){
			$mailContent .= 'Hi, <br/><br/>No reviews are submitted from same IP address.';
		}else{
			/*Making data in the usable format */
			$formedData = $this->crLib->getReviewIdsFromSourceData($collegeReviewSourceData);
			
			/* Getting institute data */
			$mappedInstituteData = $this->crLib->getCRDataFromMappedInstitute($formedData['reviewIds']);
			$nonMappedInstituteData = $this->crLib->getCRDataFromNonMappedInstitute($formedData['reviewIds']);
			if(empty($mappedInstituteData)){
				$mappedInstituteData = array();
			}
			if(empty($nonMappedInstituteData)){
				$nonMappedInstituteData = array();
			}
			$reviewInstituteData = $mappedInstituteData + $nonMappedInstituteData;
			
			/*now check multiple review from same Ip on a perticular course */ 
			$reviewsFromSameIPOnCourse = $this->crLib->processCRSourceData($formedData, $reviewInstituteData);
			
			/*Getting mail body content */
			$mailContent = $this->crLib->IpTrackingMailContentMaker($formedData, $reviewInstituteData, $reviewsFromSameIPOnCourse);
		}
		error_log("==main==content ===".$mailContent);
		\Modules::run('systemMailer/SystemMailer/addMailToQueue', 'CollegeReviewCron','karan.chawla@shiksha.com',ADMIN_EMAIL,$subject = 'Suspicious activity detected',$mailContent);
	}

	private function mapSessionIdToClientIPInReviewsData(&$collegeReviewSourceData){
	    if (!is_array($collegeReviewSourceData) || empty($collegeReviewSourceData)){
	        return;
        }
	    $sessionIdsToQuery  =   array();
	    foreach ($collegeReviewSourceData as $value){
	        if (null != $value['visitorSessionId'] && "" != $value['visitorSessionId']){
                $sessionIdsToQuery[] = $value['visitorSessionId'];
            }
        }
	    if (empty($sessionIdsToQuery)){
	        return;
        }


	    // Get ES Connection Configuration
	    $this->getESConnectionConfigurations();

	    $countOfSessionIds = count($sessionIdsToQuery);
	    $counter = 0;
	    $chunkSize = 50;
	    $sessionToClientMapping = array();
	    while($counter < $countOfSessionIds){
	        $sessionIdsChunk= array_slice($sessionIdsToQuery, $counter, $chunkSize);
	        $esQuery        = $this->buildESQueryForSessionClientIP(SESSION_INDEX_NAME_REALTIME_SEARCH, "session", $sessionIdsChunk);
	        if (empty($esQuery)){
	            break;
            }
	        $result         = $this->clientConn6->search($esQuery);
	        foreach ($result['hits']['hits'] as $esResultValue){
                $sessionToClientMapping[$esResultValue['_source']['sessionId']] = $esResultValue['_source']['clientIP'];
            }
            $counter += $chunkSize;
        }
        $keysToDeleteFromReviewsArray = array();
	    foreach ($collegeReviewSourceData as $key => &$value){
	        if(key_exists($value['visitorSessionId'], $sessionToClientMapping)){
                $value['clientIP']  = $sessionToClientMapping[$value['visitorSessionId']];
            }else{
                $keysToDeleteFromReviewsArray[] = $key;
            }
        }

	    // unset keys for which we do not have data in Elastic
	    foreach ($keysToDeleteFromReviewsArray as $value){
	        unset($collegeReviewSourceData[$value]);
        }
	    return $collegeReviewSourceData;
    }

    private function getESConnectionConfigurations(){
        $ESConnectionLib    = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientConn   = $ESConnectionLib->getESServerConnection();
        $this->clientConn6  = $ESConnectionLib->getShikshaESServerConnection();
    }

    private function buildESQueryForSessionClientIP($indexName, $type, $sessionIds = array()){
	    $esQueryArray = array();
	    if (!is_array($sessionIds) || empty($sessionIds)){
	        return $esQueryArray;
        }
        $esQueryArray['index']  = $indexName;
        $esQueryArray['type']   = $type;
        $esQueryArray['size']   = count($sessionIds);
        $esQueryBody            = array();
        $esQueryBody['_source'] = array("sessionId","clientIP");
        $esFilterQuery          = array(    'bool'  =>  array(  "must"  =>  array(  "terms" =>  array(  "sessionId" =>  $sessionIds
                                                                                                    )
                                                                                )
                                                            )
                                        );
        $esQueryBody['query']['bool']['filter'] =   $esFilterQuery;
        $esQueryArray['body']   = $esQueryBody;
        return $esQueryArray;
    }

}