<?php
class Crawler extends MX_Controller {
    function init() {
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client'));
    }

    function dup() {
        $this->init();
        $institute_name = 'kanpur University';
        $pincode = '111111';
        $ListingClientObj = new Listing_client();
        $dupData = $ListingClientObj->checkInstituteDuplicacy('1','-1',$institute_name,$pincode);
        echo "<pre>";
        print_r($dupData);
        echo "</pre>";
    }

    function dup_course() {
        $this->init();
        $courseTitle = 'MCA Course';
        $institute_id = '2';
        $courseType= 'full time';
        $ListingClientObj = new Listing_client();
        $dupData = $ListingClientObj->checkCourseDuplicacy('1','-1',$institute_id,$courseTitle,$courseType);
        echo "<pre>";
        print_r($dupData);
        echo "</pre>";
    }

  function editIns($instituteId = '26269') {
        $this->init();
        $newData['institute_name']="HURRARRARAR NE";
        $newData['editByCMS']="1";
        $ListingClientObj = new Listing_client();
        $wikiData = $ListingClientObj->editInstitute('1',$instituteId,$newData);
        echo "<pre>";
        print_r($wikiData);
        echo "</pre>";
        $wikiData = $ListingClientObj->getLiveListing('1',$instituteId,'institute');
        echo "<pre>";
        print_r($wikiData);
        echo "</pre>";
        echo "<pre>";
        print_r(unserialize(base64_decode($wikiData[0]['wikiFields'])));
        echo "</pre>";
    }

    function getWiki($listing_type = 'institute') {
        $this->init();
        $ListingClientObj = new Listing_client();
        $wikiData = $ListingClientObj->getWikiFields('1',$listing_type);
        echo "<pre>";
        print_r($wikiData);
        echo "</pre>";
    }

  function gc($listing_type = 'course',$listing_type_id = '87529') {
        $this->init();
        $ListingClientObj = new Listing_client();
        $wikiData = $ListingClientObj->getLiveListing('1',$listing_type_id,$listing_type);
        echo "<pre>";
        print_r($wikiData);
        echo "</pre>";
        echo "<pre>";
        print_r(unserialize(base64_decode($wikiData[0]['wikiFields'])));
        echo "</pre>";

    }

  function ge($listing_type = 'institute',$listing_type_id = '26269') {
        $this->init();
        $ListingClientObj = new Listing_client();
        $wikiData = $ListingClientObj->getLiveListing('1',$listing_type_id,$listing_type);
        echo "<pre>";
        print_r($wikiData);
        echo "</pre>";
        echo "<pre>";
        print_r(unserialize(base64_decode($wikiData[0]['wikiFields'])));
        echo "</pre>";

    }


     function addCourse() {
        $this->init();
        $ListingClientObj = new Listing_client();
        $data = array();
        $data['dataFromCMS']='1';
        $data['packType']='0';
        $data['contact_details_id']='8';
        $data['institute_id']='123';
        $data['courseTitle']='HUrray course '.rand(1,1000);
        $data['duration_value']='2';
        $data['duration_unit']='years';
        $data['fee_value']='2000';
        $data['fee_unit']='INR';
        $data['courseType']='part-time';
        $data['courseLevel']='Degree';
        $data['courseLevel_1']='Undergraduate Degree';
        $data['approvedBy']='PUNEET';
        $data['seats_general']='100';
        $data['seats_reserved']='20';
        $data['seats_management']='20';
        $data['date_form_submission']=date();
        $data['date_result_declaration']=date();
        $data['date_course_comencement']=date();
        $data['threadId']='1'.rand(1,1000);
        $data['username']='1'.rand(1,1000);
        $data['hiddenTags']='hu huh uu u humkjbvg ';
        $data['category_id']='70,71,72';
        $data['requestIP']='127.0.0.1';
        $data['wiki']['course_desc']='This is a badhiya course ekdam mast wala';
        $data['wiki']['eligibility']='everyone is eligible for this ***** course';
        $data['wiki']['user_fields'][0]['caption']='Placements';
        $data['wiki']['user_fields'][0]['value']='!00% placements.. all comapnies come for placements';

        echo "<pre>";
        print_r($data);
        echo "</pre>";
        $wikiData = $ListingClientObj->newAddCourse('1',$data,array());
        echo "<pre>";
        print_r($wikiData);
        echo "</pre>";
    }

    function addInstitute() {
        $this->init();
        $ListingClientObj = new Listing_client();
        $data = array();
        $data['dataFromCMS']='1';
        $data['packType']='0';
        $data['institute_name']='Test College '.rand(1,1000);
        $data['contact_name']='Puneet';
        $data['contact_email'] = 'huuh@huw.com';
        $data['contact_main_phone']='989988898998';
        $data['contact_cell']='98898998';
        $data['contact_alternate_phone']='9899888989980';
        $data['website']='http://website.com/';
        $data['establish_year']='1980';
        $data['no_of_students']='190';
        $data['no_of_int_students']='20';
        $data['affiliated_to']='HURRAY UNIV';
        $data['username']='1234567';
        $data['threadId']='1'.rand(1,1000);
        $data['hiddenTags']='hu huh uu u humkjbvg ';
        $data['requestIP']='127.0.0.1';
        $data['locations'][0]['city_id']='2';
        $data['locations'][0]['country_id']='2';
        $data['locations'][0]['address_line']=' 12 , nj   huhu uh uh street';
        $data['locations'][0]['pincode']='12345';
        $data['wiki']['hostel_facility']='This college is a big time sucker in providing college facilities';
        $data['wiki']['placement']='This college is a big time sucker in providing placement facilities';
        $data['wiki']['user_fields'][0]['caption']='Crime Rate';
        $data['wiki']['user_fields'][0]['value']='crime rate is high This college is a big time sucker in providing placement facilities';

        echo "<pre>";
        print_r($data);
        echo "</pre>";
        $wikiData = $ListingClientObj->newAddInstitute('1',$data);
        echo "<pre>";
        print_r($wikiData);
        echo "</pre>";
    }
 

    function addInstituteListing($appId = 1) {

        $countryId  = $this->uri->segment(4);
        $univ_dir  = $this->uri->segment(5);

        $this->load->library('cacheLib');
        $cacheLibObj = new cacheLib();

//        $countryId = 5;
//        $univ_dir = "univ_data";
        $this->init();
        $addInstituteData = array();
        $ListingClientObj = new Listing_client();

        if($cacheLibObj->get("cityList".$countryId) == 'ERROR_READING_CACHE')
        {
            echo "CACHE_MISS city list";
	    $cityList = $ListingClientObj->getCityList($appId,$countryId);
            $cacheLibObj->store('cityList'.$countryId,serialize($cityList));
        }
        else{
            $cityList = unserialize($cacheLibObj->get('cityList'.$countryId));
        }

        $categoryClient = new Category_list_client();	 	
        if($cacheLibObj->get('catListCrawl') == 'ERROR_READING_CACHE')
        {
            echo "CACHE_MISS";
            $categoryList = $categoryClient->getCategoryTree($appId);
            $cacheLibObj->store('catListCrawl',serialize($categoryList));
        }
        else{
            $categoryList = unserialize($cacheLibObj->get('catListCrawl'));
        }

        $categoryIdArr = array();
        for($i = 0; $i < count($categoryList);$i++) {
            $categoryIdArr[$categoryList[$i]['categoryID']]= trim($categoryList[$i]['categoryName']);
        }


        $categoryMapArr = array();
        $categoryMapArr['catfiles/4.crs2'] = "3";
        $categoryMapArr['catfiles/5.crs2'] = "10";
        $categoryMapArr['catfiles/6.crs2'] = "4";
        $categoryMapArr['catfiles/7.crs2'] = "9";
        $categoryMapArr['catfiles/8.crs2'] = "11";
        $categoryMapArr['catfiles/9.crs2'] = "6";
        $categoryMapArr['catfiles/10.crs2'] = "7";
        $categoryMapArr['catfiles/11.crs2'] = "8";
        $categoryMapArr['catfiles/14.crs2'] = "2";
        $categoryMapArr['catfiles/15.crs2'] = "5";
        $categoryMapArr['catfiles/16.crs2'] = "8";
        $categoryMapArr['catfiles/17.crs2'] = "12";
        $categoryMapArr['catfiles/1001.crs2'] = "9";
        $categoryMapArr['catfiles/1002.crs2'] = "8";
        $categoryMapArr['catfiles/dental.crs2'] = "95";


        $fn = $this->uri->segment(3);
        $handle = fopen("$univ_dir/$fn", "r");
        echo $fn;
        echo "handle is".$handle;
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 1000000);
                $tempVar = explode('::',$buffer);
                switch(strtolower(trim($tempVar[0]))){
                    case 'institute_name ':
                    case 'institute_name':
                        $addInstituteData['institute_name'] = $tempVar[1];
                        break;
                    case 'short_desc ':
                    case 'short_desc':
                        $addInstituteData['institute_desc'] .= "<br/> ".$tempVar[1];
                        break;
                    case 'location ':
                    case 'location':
                        $addInstituteData['locationFull'] .= " ". $tempVar[1];
                        break;
                    case 'LOGO':
                    case 'logo':
                        echo $tempVar[1];
                        $wikiLogoLink = $tempVar[1];
                        break;
                    case 'URL':
                    case 'url':
                    case 'URL ':
                        $addInstituteData['url'] = $tempVar[1];
                        break;
                    case 'Category':
                    case 'category':
                        $tempCategory .= "::".trim($tempVar[1]);
			echo $tempCategory;
                        break;
                }
            }
            fclose($handle);
            $flagCity =false;
            for($i = 0; $i < count($cityList); $i++){
                if(stristr($addInstituteData['locationFull'], $cityList[$i]['cityName'])!= FALSE){
                    $addInstituteData['city_id0'] = $cityList[$i]['cityID'];
                    $flagCity =true;
                    break;
                }
            }
            if(!$flagCity){
                echo "ERROR:NOCITYFOUND";
                exit(0);
            }

            $tempCatArr = explode("::",$tempCategory);
//            print_r($tempCatArr);
            $addInstituteData['category_id'] = $this->getRandomCategory(trim($categoryMapArr[trim($tempCatArr[0])]),$categoryList); 
            for($lk = 1; $lk < count($tempCatArr); $lk++) {
                $addInstituteData['category_id'] .= ",".$this->getRandomCategory(trim($categoryMapArr[trim($tempCatArr[$lk])]),$categoryList); 
            }

            $tempCats = explode(",",$addInstituteData['category_id']);
            $addInstituteData['category_id'] = "";
            for($lk = 0; $lk < count($tempCats);$lk++) {
                if($tempCats[$lk] == "") {
                    continue;
                }
                if(!(isset($done[$tempCats[$lk]]))) {
                    $addInstituteData['category_id'] .= ",".$tempCats[$lk];
                    $done[$tempCats[$lk]] = "1";
                }
            }
            $addInstituteData['category_id'] = ltrim($addInstituteData['category_id'],",");


            //Making array which will be sent as parameter to client function	
            $addInstituteData['testimonial_emailids'] = ''; 
            $addInstituteData['no_of_students'] ="";
            $addInstituteData['hostel'] =''; 
            $addInstituteData['username'] = '1';
            $addInstituteData['contact_email'] =  "";
            $addInstituteData['contact_cell'] = "";
            $addInstituteData['contact_name'] =""; 
            $addInstituteData['country_id0'] = $countryId;
            $addInstituteData['crawled'] = 1;

            $addInstituteData['numoflocations'] = 1;
            //Category

            $addInstituteData['islisting'] = "TRUE";
            echo "<pre>";
            print_r($addInstituteData);
            echo "</pre>";

            $addInstituteData['threadId']= '';
            $response = $ListingClientObj->add_institute($appId,$addInstituteData);
            echo "<pre>";
            print_r($response);
            echo "</pre>";
            if(isset($response['institute_id']) && $response['institute_id'] !='' && is_array($response)){
                $response['title'] = $addInstituteData['institute_name'];
                echo $wikiLogoLink;
                echo $response['institute_id'];
                if(isset($wikiLogoLink) && $wikiLogoLink !=''){
                $i_upload_logo = $this->uploadWikiImage($wikiLogoLink,$response['institute_id'], $univ_dir);
                echo "<pre>";
                print_r($i_upload_logo);
                echo "</pre>";
                $k = 0;
                $logoLink = $i_upload_logo[0]['thumburl_m'];
                $updateInstituteData = array();
                $updateInstituteData['institute_id'] = $response['institute_id'];
                $updateInstituteData['logo_link'] = $logoLink;
                print_r($updateInstituteData);
                $status = $ListingClientObj->update_institute("1",$updateInstituteData);

                echo "<pre>";
                print_r($status);
                echo "</pre>";
                }
            }
        }
    }



    function updateLogoFunc(){
        $countryId  = $this->uri->segment(4);
        $univ_dir  = $this->uri->segment(5);

	    //        $countryId = 4;
	    //        $univ_dir = "data_uk1";
	    $this->init();
	    $addInstituteData = array();
	    $ListingClientObj = new Listing_client();
	    $instituteList = $ListingClientObj->getInstituteList($appId,0);
	    $fn = $this->uri->segment(3);
	    $handle = fopen("$univ_dir/$fn.html", "r");
	    echo $fn;
	    echo "handle is".$handle;
	    if ($handle) {
		    while (!feof($handle)) {
			    $buffer = fgets($handle, 1000000);
			    $tempVar = explode('::',$buffer);
			    switch(trim($tempVar[0])){
				    case 'institute_name ':
				    case 'institute_name':
					    $addInstituteData['institute_name'] = $tempVar[1];
					    break;
				    case 'short_desc ':
				    case 'short_desc':
					    $addInstituteData['institute_desc'] = $tempVar[1];
					    break;
				    case 'year_of_establishment ':
				    case 'year_of_establishment':
					    $addInstituteData['establish_year'] = $tempVar[1];
					    break;
				    case 'location ':
				    case 'location':
					    $addInstituteData['locationFull'] = $tempVar[1];
					    break;
				    case 'LOGO':
					    echo $tempVar[1];
					    $wikiLogoLink = $tempVar[1];
					    break;
			    }
            }
            fclose($handle);

            $addInstituteData['institute_name'] = trim($addInstituteData['institute_name']);
            echo $addInstituteData['institute_name'];
            for($i = 0; $i < count($instituteList); $i++){
                if($instituteList[$i]['countryId'] != $countryId){
                    continue;
                }
                if(trim($instituteList[$i]['instituteName']) == $addInstituteData['institute_name']){
                    $instituteId = $instituteList[$i]['instituteID'];
                }
            }

            if($instituteId  == 0){
                echo "INSTITUTE NOT MAPPED:";
                exit(0);
            }
            //Making array which will be sent as parameter to client function	
            if(isset($instituteId) && $instituteId >0){
                echo $wikiLogoLink;
                if(isset($wikiLogoLink) && $wikiLogoLink !=''){
                    $i_upload_logo = $this->uploadWikiImage($wikiLogoLink,$instituteId);
                    echo "<pre>";
                    print_r($i_upload_logo);
                    echo "</pre>";
                    $k = 0;
                    $logoLink = $i_upload_logo[0]['thumburl_m'];
                    $updateInstituteData = array();
                    $updateInstituteData['institute_id'] = $instituteId;
                    $updateInstituteData['logo_link'] = $logoLink;
                    print_r($updateInstituteData);
                    $status = $ListingClientObj->update_institute("1",$updateInstituteData);
                    echo "<pre>";
                    print_r($status);
                    echo "</pre>";
                }
            }
        }
    }

    function uploadWikiImage($link , $id, $univ_dir=''){
        echo $link;
        $post_array = array();
        $iCount = 1 ;
        $fn = $this->uri->segment(3);
        $fname = "/tmp/php_".$univ_dir."_".$fn;
        echo $fname;
        /*$cmd = "curl $link > $fname"; 
        //$post_array['file1'] = "@".$FILES[$mediaArrName]['tmp_name'][$key];
        $image = exec($cmd); 
        echo $image;
*/
        //$image = system("ls > /tmp/lsFile",$retVal); 
        error_log_shiksha($image);
        //$post_array['file1'] ="@".$fname;
        $post_array['file1'] = new CURLFile($fname);
        $post_array['description1']="Logo";
        $iCount = $iCount + 1;
        $post_array['mediatype']='image';
        $post_array['count'] = $iCount;
        $post_array['Id'] = $id;
        $post_array['type'] = 'institute';
        error_log_shiksha("post array is ".print_r($post_array,true));	
		$this->load->library('upload_client');
		$uploadClient = new Upload_client();			

		$uploadFileUrl = MDB_SERVER."Upload.php";
        $output=  $uploadClient->makeCurlCall($post_array,$uploadFileUrl);
		$responseObj = unserialize($output);
		$mediadata = array();
		$ret_array = array();
        	$i = 0;
            if($responseObj["status"] == 1)
            {  	    
                for($i=0;$i < (count($responseObj) - 1); $i++ )
                {
                            array_push($ret_array,array('mediaid' => $responseObj[$i]['mediaid'],'imageurl' => $responseObj[$i]['imageurl'],'title' => $ImageCaptions[$i],'thumburl' => $responseObj[$i]['thumburl'],'thumburl_m' => $responseObj[$i]['thumburl_m']));
                }	
                $ret_array['max'] = $i;
                $ret_array['status'] = 1;
                return $ret_array;	
            }
		else
		{	
			return $responseObj["error_msg"];
		}

        return $ret_array;
    }

	function indexInstitute(){
		$appId = 1;
		$this->init();
		$displayData = array();
		$ListingClientObj = new Listing_client();
		$type_id = $this->uri->segment(3);
		$listing_type = $this->uri->segment(4);
		error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
		$listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);
		$details = $listingDetails[0];
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];

			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];

			}
		}
           echo "<pre>";
           print_r($details);
           echo "</pre>";
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
        $luceneData['title'] = $details['title']; 
        $luceneData['content'] = $details['short_desc']; 
        $luceneData['packtype'] = 'platinum'; 
        $luceneData['type'] = 'institute'; 
        $luceneData['Id'] = $type_id; 
        $luceneData['imageCount'] = count($details['photos']); 
        $luceneData['videoCount'] =  count($details['videos']);; 
        $luceneData['categoryList'] = $details['category_id']; 
        $luceneData['countryList'] = implode(',',$countryList); 
        $luceneData['cityList'] = implode(',',$cityList); 
        $luceneData['imageUrl'] = $details['institute_logo'];
        $luceneData['courseTitle'] = $courseTitles;
        
           echo "<pre>";
           print_r($luceneData);
           echo "</pre>";
        $luceneResponse = $ListingClientObj->indexListing(1,$luceneData); 

        echo "<pre>";
        print_r($luceneResponse);
        echo "</pre>";

	}
	function indexCrawledCourse(){
		$appId = 1;
		$this->init();
		$displayData = array();
		$ListingClientObj = new Listing_client();
		$type_id = $this->uri->segment(3);
		$listing_type = $this->uri->segment(4);
		error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
		$listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);
		$details = $listingDetails[0];
		echo "<pre>";
		print_r($listingDetails);
		echo "</pre>";
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];

			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];

			}
		}
		$luceneData = array();
		$luceneData['title'] = $details['title']; 
		$luceneData['content'] = $details['overview']; 
		$luceneData['courseContent'] = $details['contents']; 
		$luceneData['packtype'] = 'crawl'; 
		$luceneData['type'] = 'course'; 
		$luceneData['Id'] = $details['course_id']; 
		$luceneData['imageCount'] = count($details['photos']); 
		$luceneData['videoCount'] = count($details['videos']); 
		$luceneData['categoryList'] = $details['category_id']; 
		$luceneData['countryList'] = $details['country_id']; 
		$luceneData['cityList'] = $details['city_id']; 
		$luceneData['instituteId'] = $details['institute_id']; 

		echo "<pre>";
		print_r($luceneData);
		echo "</pre>";

		$luceneResponse = $ListingClientObj->indexListing(1,$luceneData); 

		echo "<pre>";
		print_r($luceneResponse);
		echo "</pre>";
	}

	function getRandomCategory($pid,$categoryList){
		$childArray =array();
		$childs = 0;
		for($i = 0; $i < count($categoryList);$i++) {
			if($categoryList[$i]['parentId'] == $pid){
				$childArray[$childs] = $categoryList[$i]['categoryID'];
				$childs++;
			}
		}
		if($childs >0){
			$rnd = rand(0,$childs-1);
			return $childArray[$rnd];
		}else{
			return $pid;
		}
	}

    function addCrawledCourse($appId = 1) {
        $countryId  = $this->uri->segment(4);
        $course_dir  = $this->uri->segment(5);

        $countryId  = $this->uri->segment(4);
        $univ_dir  = $this->uri->segment(5);

        echo $countryId;
        echo $course_dir;
        $this->init();
        global $userid;
        $ListingClientObj = new Listing_client();
        $categoryClient = new Category_list_client();	 	
        if($cacheLibObj->get('instituteList'.$countryId) == 'ERROR_READING_CACHE')
        {
            echo "CACHE_MISS";
            $instituteList = $ListingClientObj->getInstituteListInCountry($appId,$countryId);
            $cacheLibObj->store('instituteList'.$countryId,serialize($instituteList));
        }
        else{
            $instituteList = unserialize($cacheLibObj->get('instituteList'.$countryId));
        }

        if($cacheLibObj->get('catListCrawl') == 'ERROR_READING_CACHE')
        {
            echo "CACHE_MISS";
            $categoryList = $categoryClient->getCategoryTree($appId);
            $cacheLibObj->store('catListCrawl',serialize($categoryList));
        }
        else{
            $categoryList = unserialize($cacheLibObj->get('catListCrawl'));
        }

        $categoryIdArr = array();
        for($i = 0; $i < count($categoryList);$i++) {
            $categoryIdArr[$categoryList[$i]['categoryID']]= trim($categoryList[$i]['categoryName']);
        }
        $stopWordArr = array();

        $stopwordslisttbe = "the university of technology technical institute college post about the home contact and international science art agriculture heart research graduate management";
        $stopwordslisttbeArr = explode(" ",$stopwordslisttbe);
for($i=0; $i < count($stopwordslisttbeArr);$i++){
                $stopWordArr[trim(strtolower($stopwordslisttbeArr[$i]))] = 1;
}


        $categoryMapArr = array();
        $categoryMapArr['catfiles/4.crs2'] = "3";
        $categoryMapArr['catfiles/5.crs2'] = "10";
        $categoryMapArr['catfiles/6.crs2'] = "4";
        $categoryMapArr['catfiles/7.crs2'] = "9";
        $categoryMapArr['catfiles/8.crs2'] = "11";
        $categoryMapArr['catfiles/9.crs2'] = "6";
        $categoryMapArr['catfiles/10.crs2'] = "7";
        $categoryMapArr['catfiles/11.crs2'] = "8";
        $categoryMapArr['catfiles/14.crs2'] = "2";
        $categoryMapArr['catfiles/15.crs2'] = "5";
        $categoryMapArr['catfiles/16.crs2'] = "8";
        $categoryMapArr['catfiles/17.crs2'] = "12";
        $categoryMapArr['catfiles/1001.crs2'] = "9";
        $categoryMapArr['catfiles/1002.crs2'] = "8";
        $categoryMapArr['catfiles/dental.crs2'] = "95";


        $fn = $this->uri->segment(3);
        $addCourseData = array();
        $handle = fopen("$course_dir/$fn", "r");
        echo $handle;
        $flagDone = false;
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 1000000);
                $tempVar = explode(':',$buffer);
                $totStr = $tempVar[1];
                for($i = 2; $i < count($tempVar);$i++) {
                    $totStr .= ":".$tempVar[$i];
                }
                switch(strtolower(trim($tempVar[0]))){
                    case 'College':
                    case 'college':
                        $addCourseData['institute_name'] = $totStr;
                        break;
                    case 'Title':
                    case 'title':
                        $addCourseData['courseTitle'] = $totStr;
                        break;
                    case 'duration':
                        $tempArr = array();
                        $tempArr = explode("wt",$totStr);
                        $addCourseData['duration'] .= $tempArr[0];
                        break;
                    case 'Description':
                    case 'description':
                        $addCourseData['overview'] .= "<br/>".$totStr;
                        break;
                    case 'Category':
                    case 'category':
                        $tempCategory .= "::".$totStr;
                        break;
                    case 'URL':
                    case 'url':
                        $flagDone = true;
                        $addCourseData['url'] = $totStr;
                        break;
                }
            }
            fclose($handle);
            $flagCity =false;
            $addCourseData['institute_name'] = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $addCourseData['institute_name']));
            $tempUnivName = explode(' ',$addCourseData['institute_name']);

            $finalInstituteId ='';
            $finalInstituteName ='';
            $finalCounter = 0;
            $finalCityId = 0;
            $finalCountryId = 0;
            for($i = 0; $i < count($instituteList); $i++){
                if($instituteList[$i]['countryId'] != $countryId){
                    continue;
                }

                $counter = 0;
                $tempInst = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $instituteList[$i]['instituteName']));
                for($j = 0; $j < count($tempUnivName) ; $j++){
                    if(!array_key_exists(trim(strtolower($tempUnivName[$j])),$stopWordArr)){
                        if(stristr($tempInst,$tempUnivName[$j])!= FALSE)
                        {
                            echo "Found = ".$tempUnivName[$j]."<br\>";
                            $counter++;
                        }
                    }
                }
                if($counter > $finalCounter){
                    $finalCounter = $counter;
                    $finalInstituteName =$instituteList[$i]['instituteName'];
                    $finalInstituteId = $instituteList[$i]['instituteID'];
                    $finalCityId = $instituteList[$i]['cityId'];
                    $finalCountryId = $instituteList[$i]['countryId'];
                }
            }
            if($finalInstituteId  == 0){
                echo "INSTITUTE NOT MAPPED:".$$addCourseData['institute_name'];
                exit(0);
            }
            $addCourseData['institute_name'] = $finalInstituteName;
            $addCourseData['crawled'] = 1;
            $addCourseData['objective'] = '';
            $addCourseData['contents'] = '';
            $addCourseData['eligibility'] = '';
            $addCourseData['selection_criteria'] = '';
            $addCourseData['scholarships'] = '';
            $addCourseData['placements'] =''; 
            $addCourseData['hostel_facility'] =''; 
            $addCourseData['fees'] = '';

            $tempCatArr = explode("::",$tempCategory);
            echo $tempCategory;
            $addCourseData['category_id'] = $this->getRandomCategory(trim($categoryMapArr[trim($tempCatArr[0])]),$categoryList); 
            for($lk = 1; $lk < count($tempCatArr); $lk++) {
                $addCourseData['category_id'] .= ",".$this->getRandomCategory(trim($categoryMapArr[trim($tempCatArr[$lk])]),$categoryList); 
            }

            $tempCats = explode(",",$addCourseData['category_id']);
            $addCourseData['category_id'] = "";
            for($lk = 0; $lk < count($tempCats);$lk++) {
                if($tempCats[$lk] == "") {
                    continue;
                }
                if(!(isset($done[$tempCats[$lk]]))) {
                    $addCourseData['category_id'] .= ",".$tempCats[$lk];
                    $done[$tempCats[$lk]] = "1";
                }
            }
            $addCourseData['category_id'] = ltrim($addCourseData['category_id'],",");
            if($addCourseData['category_id']  == ''){
                echo "CATEGORY NOT MAPPED:".$addCourseData['category_id'];
                exit(0);
            }

            $addCourseData['username'] = '1';
            $addCourseData['institute_id'] = $finalInstituteId;
            $addCourseData['country_id'] = $finalCountryId;
            $addCourseData['city_id'] = $finalCityId;
            $addCourseData['media_content'] = '';

            //error_log_shiksha("COURSE request is ::".print_r($_REQUEST,true));
            $addCourseData['contact_name'] = '';
            $addCourseData['contact_cell'] = '';
            $addCourseData['contact_email'] = '';
           // START: Message Board Integration
/*            $msgbrdClient = new message_board_client();
            $selectedCategory = explode(',',$addCourseData['category_id']);
            $countryId = $addCourseData['country_id'];;
            $topicResult = $msgbrdClient->addTopic($appId,$selectedCategory[0],1,"You can discuss about ".$addCourseData['courseTitle'].",".$addCourseData['institute_name'] ."here.",$addCourseData['courseTitle'],$countryId,2);

            $addCourseData['threadId']= $topicResult['ThreadID'];*/

            $addCourseData['threadId']= '';
            echo "<pre>";
            print_r($addCourseData);
            echo "</pre>";
            // END: Message Board Integration
            $response = $ListingClientObj->add_course($appId,$addCourseData);
            print_r($response);
            if(isset($response['Course_id']) && $response['Course_id'] !='' && is_array($response)){
                $response['title'] = $addCourseData['courseTitle'];
                error_log_shiksha("Listing controller RESPONSE.. => ".print_r($response,true));
                $numOfPhotos = 0;
                $numOfVideos = 0;
                //INDEXING
                $luceneData = array();
                $luceneData['title'] = $addCourseData['courseTitle']; 
                $luceneData['content'] = $addCourseData['overview']; 
                $luceneData['courseContent'] = $addCourseData['contents']; 
                $luceneData['packtype'] = 'crawled'; 
                $luceneData['type'] = 'course'; 
                $luceneData['Id'] = $response['Course_id']; 
                $luceneData['imageCount'] = $numOfPhotos; 
                $luceneData['videoCount'] = $numOfVideos; 
                $luceneData['categoryList'] = $addCourseData['category_id']; 
                $luceneData['countryList'] = $addCourseData['country_id']; 
                $luceneData['cityList'] = $addCourseData['city_id']; 
                $luceneData['instituteId'] = $addCourseData['institute_id'];
                $luceneData['fees'] = $addCourseData['fees'];    
                $luceneData['eligibility']=$addCourseData['eligibility'];   
                $luceneData['duration']=$addCourseData['duration'];
            /*    $luceneResponse = $ListingClientObj->indexListing(1,$luceneData); 
                echo "<pre>";
                print_r($luceneResponse);
                echo "</pre>";*/
                $updateInstituteData = array();
                $updateInstituteData['institute_id'] = $addCourseData['institute_id'];
                $updateInstituteData['addCat'] = 1;
                $updateInstituteData['category_id'] = $addCourseData['category_id'];
                print_r($updateInstituteData);
                $status = $ListingClientObj->update_institute("1",$updateInstituteData);
                echo "<pre>";
                print_r($status);
                echo "</pre>";
            }
        }
    }

    function addScholarship($appId = 1) {
        $countryId  = $this->uri->segment(4);
        $scholDir  = $this->uri->segment(5);
        $this->init();
        $addScholarshipData = array();
        $addScholarshipData['packType'] = -5;
        $addScholarshipData['category_id'] = 150;
        $ListingClientObj = new Listing_client();
        $fn = $this->uri->segment(3);
        $handle = fopen("$scholDir/$fn", "r");
        echo $fn;
        echo "handle is".$handle;
        if ($handle) {
            $addScholarshipData['short_desc'] = "<table><tbody>";
            while (!feof($handle)) {
                $buffer = fgets($handle, 1000000);
                $tempVar = explode('::',$buffer);
                switch(strtolower(trim($tempVar[0]))){
                    case 'title':
                        $addScholarshipData['scholarship_name'] = trim($tempVar[1]);
                        break;
                    case 'short_desc ':
                    case 'short_desc':
                    case 'desc':
                        $addScholarshipData['short_desc'] .= trim($tempVar[1]);
                        break;
                    case 'URL':
                    case 'url':
                        $addScholarshipData['sourceUrl'] = trim($tempVar[1]);
                        $addScholarshipData['url'] = trim($tempVar[1]);
                        break;
                }
            }

            $addScholarshipData['short_desc'] .= "</tbody></table>";
            $addScholarshipData['username'] = '1';
            $addScholarshipData['contact_email'] =  "";
            $addScholarshipData['contact_cell'] = "";
            $addScholarshipData['contact_name'] =""; 
            $addScholarshipData['country_id0'] = $countryId;
            $addScholarshipData['crawled'] = 1;
            $addScholarshipData['packType'] = -5;
            $addScholarshipData['last_date_submission'] = "";

            //Category

            echo "<pre>";
            print_r($addScholarshipData);
            echo "</pre>";

            $response = $ListingClientObj->add_scholarship($appId,$addScholarshipData,array());
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }
    }

}
?>
