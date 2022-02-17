<?php
class SiteMap extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url','search'));
		$this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client','alerts_client','keyPagesClient'));
	}

    function makeUrl()
    {
        $this->init();
        $appId = 1;
//        $ListingClientObj = new Listing_client();
        $id = $this->uri->segment(3);
        $title = $this->uri->segment(4);
        $type = $this->uri->segment(5);
        if($type == "user") {
            $type = "question";                                               
        } 
        if($type == "group") {
            $url = "https://groups.shiksha.com/network/Network/collegeNetwork/".$id."/".seo_url($title)."/0";
        }else {
            $url = createListingUrlSearch($id,$type,$title);
        }
        echo $url;
    }

function updateTopicForListing(){
		$this->init();
		$appId = 1;

		$ListingClientObj = new Listing_client();
		$type_id = $this->uri->segment(3);
		$listing_type = $this->uri->segment(4);
		error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
		$listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);

		$details = $listingDetails[0];
		echo "<pre>";
//		print_r($listingDetails);
		echo "</pre>";
		$countryList = array();
		$cityList = array();
        $fromOthers = 'group';
		$toBeIndex = 1;
		$topicCat = 1;
        $userId = 1;
		$msgbrdClient = new message_board_client();
		if($listing_type == "institute"){
			$topicResult = $msgbrdClient->addTopic($appId,$userId,$details['title'],$topicCat,'127.0.0.1',$fromOthers,$type_id, $listing_type, $toBeIndex);
			$newthreadId= $topicResult['ThreadID'];
		}
        echo "threadid".$newthreadId;
        if($newthreadId > 0){
            $response = $ListingClientObj->updateThreadId(1,$type_id,$listing_type,$newthreadId);
            echo "<pre>";
            print_r($response);
            echo "<pre/>";
        }else {
            echo "ERROR:threadId:".$newthreadId;
        }
    }

	function indexListing(){
		$appId = 1;
		$this->init();
		$displayData = array();
		$ListingClientObj = new Listing_client();
		$type_id = $this->uri->segment(3);
		$listing_type = $this->uri->segment(4);
		error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
		$listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);
		switch($listing_type){
			case "institute":
				$luceneArr = $this->indexInstitute($listingDetails[0]);
			break;
			case "course":
				$luceneArr = $this->indexCourse($listingDetails[0]);
			break;
			case "scholarship":
				$luceneArr = $this->indexScholarship($listingDetails[0]);
			break;
			case "notification":
				$luceneArr = $this->indexNotification($listingDetails[0]);
			break;
		}
		echo "<pre>";
		print_r($luceneArr);
		echo "</pre>";
		$luceneResponse = $ListingClientObj->indexListing(1,$luceneArr); 
		echo "<pre>";
		print_r($luceneResponse);
		echo "</pre>";
	}

	function indexInstitute($details){
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];

			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];

			}
		}
/*		echo "<pre>";
		print_r($details);
        echo "</pre>";*/
		$countryList = array();
		$cityList = array();
		for($i = 0; $i < count($details['locations']) ; $i++)
		{
			$countryList[$i] = $details['locations'][$i]['country_id'];
			$cityList[$i] = $details['locations'][$i]['city_id'];
		}

		$scholarships = array();
		$courses = array();
		$courseTitle = array();
		$notifications = array();
		$c = 0;
		for($i = 0; $i < count($details['sublisting']); $i++){
			//echo $details['sublisting'][$i]['sublistingType'];
			switch ($details['sublisting'][$i]['sublistingType']) {

				case "notification":

					array_push($notifications,array(                                                                                                     
								'sublistingId'=>$details['sublisting'][$i]['sublistingId'],                                                                  
								'title'=>$details['sublisting'][$i]['title'],                                                                                
								));			
				break;
				case "scholarship":
					array_push($scholarships,array(                                                                                                     
								'sublistingId'=>$details['sublisting'][$i]['sublistingId'],                                                                  
								'title'=>$details['sublisting'][$i]['title'],                                                                                
								));			
				break;
				case "course":

					array_push($courses,array(                                                                                                     
								'title'=>$details['sublisting'][$i]['title'],                                                                                
								));			
				$courseTitle[$c] = $details['sublisting'][$i]['title']; 
				$c++;
				break;
			}	
		}


		$courseTitles =implode(":::",$courseTitle); 
        $luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled'; 
        }
        else{
            $luceneData['hack'] = ''; 
        }

        $luceneData['title'] = $details['title']; 
        $luceneData['content'] = $details['short_desc']; 
        $luceneData['packtype'] = $details['packType']; 
        $luceneData['type'] = 'institute'; 
        $luceneData['Id'] = $details['institute_id']; 
        $luceneData['imageCount'] = count($details['photos']); 
        $luceneData['videoCount'] =  count($details['videos']);; 
        $luceneData['categoryList'] = $details['category_id']; 
		$luceneData['countryList'] = implode(',',$countryList); 
		$luceneData['cityList'] = implode(',',$cityList); 
		$luceneData['imageUrl'] = $details['institute_logo'];
		$luceneData['courseTitle'] = $courseTitles;
		$luceneData['timestamp'] = $details['timestamp'];
		$luceneData['tags'] = $details['tags'];
		$luceneData['userId'] = $details['userId'];
		return $luceneData;
	}

	function indexCourse($details){
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];

			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];

			}
		}
		$luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled'; 
        }
        else{
            $luceneData['hack'] = ''; 
        }


		$luceneData['title'] = $details['title']; 
		$luceneData['content'] = $details['overview']; 
		$luceneData['courseContent'] = $details['contents']; 
		$luceneData['courseType'] = $details['course_type']; 
		$luceneData['packtype'] = $details['packType']; 
		$luceneData['type'] = 'course'; 
		$luceneData['Id'] = $details['course_id']; 
		$luceneData['imageCount'] = count($details['photos']); 
		$luceneData['videoCount'] = count($details['videos']); 
		$luceneData['categoryList'] = $details['category_id']; 
		$countryList = array();
                $cityList = array();
                for($i = 0; $i < count($details['locations']) ; $i++)
                {
                        $countryList[$i] = $details['locations'][$i]['country_id'];
                        $cityList[$i] = $details['locations'][$i]['city_id'];
                }
		$luceneData['countryList'] = implode(',',$countryList); 
		$luceneData['cityList'] = implode(',',$cityList); 
		$luceneData['instituteId'] = $details['institute_id']; 
		$luceneData['fees'] = $details['fees'];
		$luceneData['duration'] = $details['duration'];
		$luceneData['timestamp'] = $details['timestamp'];
		$luceneData['tags'] = $details['tags'];
		$luceneData['userId'] = $details['userId'];
		return $luceneData;
	}

	function indexScholarship($details){
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];
			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];
			}
		}
        if(isset($details['eligibilityArr'][0]))
        {
            $details['eligibility']="";
            foreach($details['eligibilityArr'] as $row)
            {
                foreach($row as $value)
                {
                    if($i==0)
                    {
                        $details['eligibility'].=$value." ";
                        $i=1;
                    }
                    else
                    {
                        $details['eligibility'].=$value.":";
                        $i=0;
                    }
                }
            }
        }
//        print_r($details);
		$luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled'; 
        }
        else{
            $luceneData['hack'] = ''; 
        }


		$luceneData['title'] = $details['title']; 
		$luceneData['content'] = $details['desc'];
		$luceneData['levels'] = $details['levels'];
		$luceneData['packtype'] = $details['packType'];
		$luceneData['type'] = 'scholarship';
		$luceneData['Id'] = $details['scholarship_id'];
		$luceneData['categoryList'] = $details['category_id'];
		$luceneData['countryList'] = $details['country_id'];
		$luceneData['cityList'] = $details['city_id'];
		$luceneData['instituteId'] = $details['institute_id'];
		//$luceneData['address'] = $details['address_line1']."  " . $details['address_line2'];
		//$luceneData['addressCountry'] = $details['country_id'];
		//$luceneData['addressCity'] = $details['city_id'];
		$luceneData['institutedBy'] = $details['institution'];
		$luceneData['value'] = $details['value'];
		$luceneData['number'] = $details['num_of_schols'];
		$luceneData['eligibility'] = $details['eligibility'];
		$luceneData['timestamp'] = $details['timestamp'];
		$luceneData['userId'] = $details['userId'];
		return $luceneData;
	}
	function indexNotification($details)
    {
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];
			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];
			}
		}
        $instituteList=array();
        $countryList=array();
        $cityList=array();
        foreach($details['instituteArr'] as $row)
        {
            $ListingClientObj = new Listing_client();
            $instituteDetails = $ListingClientObj->getListingDetails($appId,$row['institute_id'],'institute');
            $instituteList[count($instituteList)]=$instituteDetails[0]['institute_id'];
            for($i = count($countryList), $j=0; $i < count($instituteDetails[0]['locations']) ; $i++,$j++)
            {
                $countryList[$i] = $instituteDetails[0]['locations'][$j]['country_id'];
                $cityList[$i] = $instituteDetails[0]['locations'][$j]['city_id'];
            }
        }
/*        echo "<pre>";
        print_r($details);
        echo "</pre>";*/
        $luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled'; 
        }
        else{
            $luceneData['hack'] = ''; 
        }


		$luceneData['title'] = $details['title']; 
		$luceneData['content'] = $details['desc'];
		$luceneData['admissionYear'] = $details['admission_year'];
		$luceneData['packtype'] = $details['packType'];
		$luceneData['type'] = 'notification';
		$luceneData['Id'] = $details['admission_notification_id'];
		$luceneData['categoryList'] = $details['category_id'];
		$luceneData['countryList'] = implode(',',$countryList);
		$luceneData['cityList'] = implode(',',$cityList);
		$luceneData['instituteId'] = implode(',',$instituteList);
		$luceneData['endDate'] = $details['application_end_date'];
		$luceneData['userId'] = $details['userId'];
		return $luceneData;
	}
}
?>
