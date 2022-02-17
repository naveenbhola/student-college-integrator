<?php
/*

$Id: listing_server.php,v 1.332 2010/08/18 12:06:49 build Exp $:

*/
class Listing_server extends MX_Controller {
	public static $gotRelatedDataFlag = "0";
	public static $totalRelatedData = array();
        var $cacheLib;
	function index(){
		ini_set('max_execution_time', '1800000');

		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('listingconfig');
        $this->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
		$this->dbLibObj = DbLibCommon::getInstance('Listing');
		$this->db = $this->_loadDatabaseHandle();
		$this->load->model('ListingModel');
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->helper('shikshaUtility');
		$config['functions']['getdeletedListingDetails'] = array('function' => 'Listing_server.getdeletedListingDetails');
        $config['functions']['getCountDataForAnAWidget'] = array('function' => 'Listing_server.getCountDataForAnAWidget');
        $config['functions']['getSeoTagsForNewListing'] = array('function' => 'Listing_server.getSeoTagsForNewListing');
		$config['functions']['checkIfUserExistsForListingAnA'] = array('function' => 'Listing_server.checkIfUserExistsForListingAnA');
		$config['functions']['getCategoryIdsForEachCourse'] = array('function' => 'Listing_server.getCategoryIdsForEachCourse');
		$config['functions']['getCategoryIdsForCourseIds'] = array('function' => 'Listing_server.getCategoryIdsForCourseIds');
		$config['functions']['saveCourseOrder'] = array('function' => 'Listing_server.saveCourseOrder');
		$config['functions']['getCourseOrder'] = array('function' => 'Listing_server.getCourseOrder');
		$config['functions']['getWikiDescriptionCaption'] = array('function' => 'Listing_server.getWikiDescriptionCaption');
		$config['functions']['getReasonToJoinInstitute'] = array('function' => 'Listing_server.getReasonToJoinInstitute');
		$config['functions']['getUrlForOverviewTab'] = array('function' => 'Listing_server.getUrlForOverviewTab');
		$config['functions']['getUrlForCompaniesLogo'] = array('function' => 'Listing_server.getUrlForCompaniesLogo');
		$config['functions']['getUrlForHeaderImages'] = array('function' => 'Listing_server.getUrlForHeaderImages');
		$config['functions']['getWikiForListing'] = array('function' => 'Listing_server.getWikiForListing');
		$config['functions']['getCourseIdForInstituteId'] = array('function' => 'Listing_server.getCourseIdForInstituteId');
		$config['functions']['getSalaryStats'] = array('function' => 'Listing_server.getSalaryStats');
		$config['functions']['sgetCountForResponseForm'] = array('function' => 'Listing_server.sgetCountForResponseForm');
		$config['functions']['sdelete_listing'] = array('function' => 'Listing_server.sdelete_listing');
		$config['functions']['reportAbuse'] = array('function' => 'Listing_server.reportAbuse');
		$config['functions']['get_testprep_listings'] = array('function' => 'Listing_server.get_testprep_listings');
		$config['functions']['get_institute_name'] = array('function' => 'Listing_server.get_institute_name');
		$config['functions']['get_course_name'] = array('function' => 'Listing_server.get_course_name');
		$config['functions']['get_exam_categories'] = array('function' => 'Listing_server.get_exam_categories');
		$config['functions']['get_online_test_banner'] = array('function' => 'Listing_server.get_online_test_banner');

		/******* NEW WS CODE*******/
		$config['functions']['sUploadCmsBanners'] = array('function' => 'Listing_server.sUploadCmsBanners');
		$config['functions']['sUpdateCmsBanners'] = array('function' => 'Listing_server.sUpdateCmsBanners');
		$config['functions']['sUploadCmsCountries'] = array('function' => 'Listing_server.sUploadCmsCountries');
		$config['functions']['sgetCityList'] = array('function' => 'Listing_server.sgetCityList');
		$config['functions']['sgetCountryForCity'] = array('function' => 'Listing_server.sgetCountryForCity');
		$config['functions']['sgetInstituteList'] = array('function' => 'Listing_server.sgetInstituteList');
		$config['functions']['sgetInstituteListInCountry'] = array('function' => 'Listing_server.sgetInstituteListInCountry');
		$config['functions']['sgetCourseList'] = array('function' => 'Listing_server.sgetCourseList');
		$config['functions']['sGetListingsByFilters'] = array('function' => 'Listing_server.sGetListingsByFilters');
		$config['functions']['sgetCountryCityTree'] = array('function' => 'Listing_server.sgetCountryCityTree');
		$config['functions']['supdate_institute'] = array('function' => 'Listing_server.supdate_institute');
		$config['functions']['supdateMediaContent'] = array('function' => 'Listing_server.supdateMediaContent');
		$config['functions']['sadd_scholarship'] = array('function' => 'Listing_server.sadd_scholarship');
		$config['functions']['newAddInstitute'] = array('function' => 'Listing_server.newAddInstitute');
		$config['functions']['sgetFullPath'] = array('function' => 'Listing_server.sgetFullPath');
		$config['functions']['sadd_admission'] = array('function' => 'Listing_server.sadd_admission');
		$config['functions']['sgetCountries'] = array('function' => 'Listing_server.sgetCountries');
		$config['functions']['sgetCountriesForProduct'] = array('function' => 'Listing_server.sgetCountriesForProduct');
		$config['functions']['sgetSubListings'] = array('function' => 'Listing_server.sgetSubListings');
		$config['functions']['sgetListingDetails'] = array('function' => 'Listing_server.getLiveListings');
		$config['functions']['sgetParentCategoriesForListing'] = array('function' => 'Listing_server.sgetParentCategoriesForListing');
		$config['functions']['sgetListingsList'] = array('function' => 'Listing_server.sgetListingsList');
		$config['functions']['updateViewCount'] = array('function' => 'Listing_server.updateViewCount');
		$config['functions']['updateViewCountforAbroadListing'] = array('function' => 'Listing_server.updateViewCountforAbroadListing');
		$config['functions']['updateThreadId'] = array('function' => 'Listing_server.updateThreadId');
		$config['functions']['sGetCitiesWithCollege'] = array('function' => 'Listing_server.sGetCitiesWithCollege');
		$config['functions']['sGetCountriesWithCollegeInCategory'] = array('function' => 'Listing_server.sGetCountriesWithCollegeInCategory');
		$config['functions']['sGetCitiesWithCollegeInCategory'] = array('function' => 'Listing_server.sGetCitiesWithCollegeInCategory');
		$config['functions']['seditCategFormOpen'] = array('function' => 'Listing_server.seditCategFormOpen');
		$config['functions']['sgetEntranceExams'] = array('function' => 'Listing_server.sgetEntranceExams');
		/******* NEW WS CODE*******/

		$config['functions']['sgetJoinGroupInfo'] = array('function' => 'Listing_server.sgetJoinGroupInfo');
		$config['functions']['sGetUserReqInfoStatus'] = array('function' => 'Listing_server.sGetUserReqInfoStatus');
		$config['functions']['sInsertReqInfo'] = array('function' => 'Listing_server.sInsertReqInfo');
		$config['functions']['sInsertCity']= array('function' =>'Listing_server.sInsertCity');
		$config['functions']['getFeaturedPanelLogo']= array('function' =>'Listing_server.getFeaturedPanelLogo');

		$config['functions']['getInstitutesForHomePageS'] = array('function' => 'Listing_server.getInstitutesForHomePageS');
		$config['functions']['getNumberOfMainInstiS'] = array('function' => 'Listing_server.getNumberOfMainInstiS');
		$config['functions']['getScholarshipsForHomePageS'] = array('function' => 'Listing_server.getScholarshipsForHomePageS');
		$config['functions']['getCoursesForHomePageS'] = array('function' => 'Listing_server.getCoursesForHomePageS');
		$config['functions']['updateApcForSearch'] = array('function' => 'Listing_server.updateApcForSearch');

		$config['functions']['reportChanges'] = array('function' => 'Listing_server.reportChanges');
		$config['functions']['sgetListingDetailForSms'] = array('function' => 'Listing_server.sgetListingDetailForSms');

		$config['functions']['getListingsByClient'] = array('function' => 'Listing_server.getListingsByClient');
		$config['functions']['getListingsByClientForType'] = array('function' => 'Listing_server.getListingsByClientForType');
		$config['functions']['getInstituteForCourse'] = array('function' => 'Listing_server.getInstituteForCourse');
		$config['functions']['getInstituteIdForCourseId'] = array('function' => 'Listing_server.getInstituteIdForCourseId');
		$config['functions']['getInstitutesForExam'] = array('function' => 'Listing_server.getInstitutesForExam');

		$config['functions']['getListingsCount'] = array('function' => 'Listing_server.getListingsCount');

		/**** SUMS INTEGRATION POINTS *****/
		$config['functions']['consumeProduct'] = array('function' => 'Listing_server.consumeProduct');
		$config['functions']['cancelSubscription'] = array('function' => 'Listing_server.cancelSubscription');
		$config['functions']['cancelListing'] = array('function' => 'Listing_server.cancelListing');
		$config['functions']['sextendExpiryDate'] = array('function' => 'Listing_server.sextendExpiryDate');
		$config['functions']['schangeListingDates'] = array('function' => 'Listing_server.schangeListingDates');
		$config['functions']['getCoursesForExam'] = array('function' => 'Listing_server.getCoursesForExam');
		/**** SUMS INTEGRATION POINTS *****/

		$config['functions']['addOtherExam'] = array('function' => 'Listing_server.addOtherExam');
		$config['functions']['getInterestedInstitutes'] = array('function' => 'Listing_server.getInterestedInstitutes');
		$config['functions']['getWikiFields'] = array('function' => 'Listing_server.getWikiFields');
		$config['functions']['getLiveListings'] = array('function' => 'Listing_server.getLiveListings');
		$config['functions']['getContactInfo'] = array('function' => 'Listing_server.getContactInfo');
		$config['functions']['editInstitute'] = array('function' => 'Listing_server.editInstitute');
		$config['functions']['editCourse'] = array('function' => 'Listing_server.editCourse');
		$config['functions']['checkInstituteDuplicacy'] = array('function' => 'Listing_server.checkInstituteDuplicacy');
		$config['functions']['checkCourseDuplicacy'] = array('function' => 'Listing_server.checkCourseDuplicacy');
		$config['functions']['getListingForEdit'] = array('function' => 'Listing_server.getListingForEdit');
		$config['functions']['getDraftAndLiveInstiContactIds'] = array('function' => 'Listing_server.getDraftAndLiveInstiContactIds');
		$config['functions']['getModerationList'] = array('function' => 'Listing_server.getModerationList');
		$config['functions']['sGetDistinctLogo'] = array('function' => 'Listing_server.sGetDistinctLogo');
		$config['functions']['sMapCourseCompany'] = array('function' => 'Listing_server.sMapCourseCompany');
		$config['functions']['sMapHeader'] = array('function' => 'Listing_server.sMapHeader');
		$config['functions']['sMapCourseHeader'] = array('function' => 'Listing_server.sMapCourseHeader');
		$config['functions']['sGetDistinctHeader'] = array('function' => 'Listing_server.sGetDistinctHeader');

		//Media page functions
		$config['functions']['sMapMediaContentWithListing'] = array('function' => 'Listing_server.sMapMediaContentWithListing');
		$config['functions']['sUpdateListingMediaAttributes'] = array('function' => 'Listing_server.sUpdateListingMediaAttributes');
		$config['functions']['sAssociateMedia'] = array('function' => 'Listing_server.sAssociateMedia');
		$config['functions']['sRemoveMediaContent'] = array('function' => 'Listing_server.sRemoveMediaContent');
		$config['functions']['sGetMediaDetailsForListing'] = array('function' => 'Listing_server.sGetMediaDetailsForListing');
		$config['functions']['getMetaInfo'] = array('function' => 'Listing_server.getMetaInfo');
		$config['functions']['makeListingsLive'] = array('function' => 'Listing_server.makeListingsLive');
		$config['functions']['deleteDraftOrQueued'] = array('function' => 'Listing_server.deleteDraftOrQueued');
		$config['functions']['getCurrentStatusVersions'] = array('function' => 'Listing_server.getCurrentStatusVersions');
		$config['functions']['disapproveQueuedListings'] = array('function' => 'Listing_server.disapproveQueuedListings');
		$config['functions']['getListingsForNaukriShiksha'] = array('function' => 'Listing_server.getListingsForNaukriShiksha');
                $config['functions']['getCoursesInformation'] = array('function' => 'Listing_server.getCoursesInformation');
		$config['functions']['getMetaInfoForInstitutes'] = array('function' => 'Listing_server.getMetaInfoForInstitutes');
		$config['functions']['getCmsTopInstitutes'] = array('function' => 'Listing_server.getCmsTopInstitutes');
		$config['functions']['getTopInstitutes'] = array('function' => 'Listing_server.getTopInstitutes');
		$config['functions']['getCategoryContentParams'] = array('function' => 'Listing_server.getCategoryContentParams');
                $config['functions']['saveTopOption'] = array('function' => 'Listing_server.saveTopOption');
		$config['functions']['publishAll'] = array('function' => 'Listing_server.publishAll');
		$config['functions']['sgetListingAutoComplete'] = array('function' => 'Listing_server.sgetListingAutoComplete');

		$config['functions']['sGetEntitiesForPriorityLeads'] = array('function' => 'Listing_server.sGetEntitiesForPriorityLeads');
		$config['functions']['sAddEntityForPriorityLeads'] = array('function' => 'Listing_server.sAddEntityForPriorityLeads');
		$config['functions']['sDeleteEntityFromPriorityLeads'] = array('function' => 'Listing_server.sDeleteEntityFromPriorityLeads');
		$config['functions']['getOptimumCategory'] = array('function' => 'Listing_server.sgetOptimumCategory');
		$config['functions']['sGetDataForCountryPage'] = array('function' => 'Listing_server.sGetDataForCountryPage');
		$config['functions']['publishCountrySelection'] = array('function' => 'Listing_server.publishCountrySelection');
		$config['functions']['saveCountryOption'] = array('function' => 'Listing_server.saveCountryOption');
		$config['functions']['getCmsCountryOptions'] = array('function' => 'Listing_server.getCmsCountryOptions');
		$config['functions']['makeCopyListingMapEntry'] = array('function' => 'Listing_server.makeCopyListingMapEntry');
		$config['functions']['sgetInstituteDataDetails'] = array('function' => 'Listing_server.sgetInstituteDataDetails');
		$config['functions']['sgetInstitutesForMultipleCourses'] = array('function' => 'Listing_server.sgetInstitutesForMultipleCourses');
		$config['functions']['sgetShoshkeleDetails'] = array('function' => 'Listing_server.sgetShoshkeleDetails');
		$config['functions']['sinsertbannerdetails'] = array('function' => 'Listing_server.sinsertbannerdetails');
		$config['functions']['supdatebannerdetails'] = array('function' => 'Listing_server.supdatebannerdetails');
		$config['functions']['sselectnduseshoshkele'] = array('function' => 'Listing_server.sselectnduseshoshkele');
		$config['functions']['sgetListingSponsorDetails'] = array('function' => 'Listing_server.sgetListingSponsorDetails');
		$config['functions']['sgetListingndBannersForCoupling'] = array('function' => 'Listing_server.sgetListingndBannersForCoupling');
		$config['functions']['schangeCouplingStatus'] = array('function' => 'Listing_server.schangeCouplingStatus');
		$config['functions']['scmsremoveshoshkele'] = array('function' => 'Listing_server.scmsremoveshoshkele');
		$config['functions']['scmsaddstickylisting'] = array('function' => 'Listing_server.scmsaddstickylisting');
		$config['functions']['scmsgetlistingdetails'] = array('function' => 'Listing_server.scmsgetlistingdetails');
		$config['functions']['getCorrectSeoURL'] = array('function' => 'Listing_server.getCorrectSeoURL');
		$config['functions']['getBasicDataForListing'] = array('function' => 'Listing_server.getBasicDataForListing');
		$config['functions']['checkListingQuestions'] = array('function' => 'Listing_server.checkListingQuestions');
		$config['functions']['updateListingQuestions'] = array('function' => 'Listing_server.updateListingQuestions');
		$config['functions']['setListingCacheValue'] = array('function' => 'Listing_server.setListingCacheValue');
		$config['functions']['getListingCacheValue'] = array('function' => 'Listing_server.getListingCacheValue');
		$config['functions']['getUploadedBrochure'] = array('function' => 'Listing_server.getUploadedBrochure');
		
		// Tracking
		$config['functions']['serverTrackAutoSuggestStats'] = array('function' => 'Listing_server.serverTrackAutoSuggestStats');
		
		$config['functions']['getInformationForCourses'] = array('function' => 'Listing_server.getInformationForCourses');
		$config['functions']['getInformationForInstitutes'] = array('function' => 'Listing_server.getInformationForInstitutes');
		$config['functions']['getInstituteTitle'] = array('function' => 'Listing_server.getInstituteTitle');
		
		$config['functions']['sgetLDBIdForCourseId'] = array('function' => 'Listing_server.sgetLDBIdForCourseId');
		$config['functions']['sgetLdbCourseDetailsForLdbId'] = array('function' => 'Listing_server.sgetLdbCourseDetailsForLdbId');
		
		$config['functions']['getEstablishYearAndSeats'] = array('function' => 'Listing_server.getEstablishYearAndSeats');
		
		$config['functions']['sgetInstituteLocationDetails'] = array('function' => 'Listing_server.sgetInstituteLocationDetails');
	       $config['functions']['checkIfUserIsResponse'] = array('function' => 'Listing_server.checkIfUserIsResponse');
               $config['functions']['updateBulkListingsContactDetails'] = array('function' => 'Listing_server.updateBulkListingsContactDetails');
               $config['functions']['upgradeCourse'] = array('function' => 'Listing_server.upgradeCourse');
		
		$this->makeApcCountryMap();
		$this->makeApcCityMap();
		$this->makeApcCityCountryMap();
		$this->makeApcCategoryMap();
		
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : '.$method);
		error_log('Code Usability Check:listing_server: '.$method, 3, '/tmp/listing_server.log'); 
		return $this->$method($args[1]);
	}

	function getInformationForCourses($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$course_ids = $parameters['1'];
		$info_types = $parameters['2'];
		$this->load->library('course_info');
		
		$course_info = $this->course_info->getInfo($course_ids,$info_types);
		return $this->xmlrpc->send_response(utility_encodeXmlRpcResponse($course_info));
	}
	
	function getInformationForInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$institute_ids = $parameters['1'];
		$info_types = $parameters['2'];
		
		$this->load->library('institute_info');
		
		$institute_info = $this->institute_info->getInfo($institute_ids,$info_types);	
		return $this->xmlrpc->send_response(utility_encodeXmlRpcResponse($institute_info));
	}
	
	function sgetOptimumCategory($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$instituteId=$parameters['1'];
		$pageName=$parameters['2'];
		$categoryIdsByPriority = array(3,2,10,4,12,5,6,7,11,9,8);
		$queryCmd="select category_id,parentId,count(*) noOfCourseForCategory from listing_category_table lct INNER JOIN institute_courses_mapping_table icmt ON (lct.listing_type_id = icmt.course_id and lct.listing_type = 'course') INNER JOIN categoryBoardTable cbt ON (lct.category_id = cbt.boardId) where icmt.institute_id = ? group by category_id  order by noOfCourseForCategory desc";
		$query =  $this->db->query($queryCmd,$instituteId);

		$previousCategoryIdCount = 0;
		$previousCategoryId = 0;
		$conflict=0;
		$i=0;
		$categoryId = 0;
		$catArr = array();
		foreach ($query->result() as $row){
			if(($i>1) && ($conflict!=1)){
				break;
			}
			$categoryId = $row->category_id;
			$parentId = $row->parentId;
			if($previousCategoryIdCount == 0){
				$catArr[$categoryId] = $parentId;
				$previousCategoryIdCount = $row->noOfCourseForCategory;
				$previousCategoryId = $categoryId;
			}elseif($previousCategoryIdCount == $row->noOfCourseForCategory){
				$catArr[$categoryId] = $parentId;
				$conflict=1;
			}
			$i++;
		}
		$parentCatId = 0;
		if($conflict == 0){
			$categoryId = $previousCategoryId;
		}else{
			foreach($categoryIdsByPriority as $parentCategoryId){
				if(in_array($parentCategoryId,$catArr)){
					$parentCatId = $parentCategoryId;
					break;
				}
			}
			$finalSubCatArray = array_keys(array_intersect($catArr,array($parentCatId)));
			$randomIndex = array_rand($finalSubCatArray);
			$categoryId = $finalSubCatArray[$randomIndex];
		}
		$response = array($categoryId,'string');
		return $this->xmlrpc->send_response($response);
	}
	function getdeletedListingDetails($request){
		 $parameters = $request->output_parameters();
		 $type_id = $parameters['0'];
		 $identifier = $parameters['1'];
		 $appId = '12';
		 //Note : in the following query status= 'deleted ' is not used because on deleting the listing from cms side, status is not getting updated in listing_category_table.Moreover we just need the category of institute being deleted.
		 $queryCmd = "SELECT cbt2.name name,cbt2.urlName urlName from  categoryBoardTable cbt1 INNER JOIN categoryBoardTable cbt2 ON cbt1.parentId = cbt2.boardId where cbt1.boardId IN (SELECT category_id from listing_category_table where listing_type = '?' AND listing_type_id = ? ) limit 1";
		 
		 
		 
		 if($this->db == ''){
		  log_message('error','can not create db handle');
		}
		$query = $this->db->query($queryCmd,array($identifier,$type_id));
		$categoryDetails =array();		
		foreach($query->result_array() as $row){
		array_push($categoryDetails,array('name' => $row['name'],'urlName'=> $row['urlName']));
		}
		
		$categoryDetails = json_encode($categoryDetails);
		$response = array($categoryDetails,'string');
		return $this->xmlrpc->send_response($response);
	}
	function sMapHeader($request){
				$this->db = $this->_loadDatabaseHandle('write');
                $parameters = $request->output_parameters();
                $lType= $parameters[0];
                $listingType= explode(",",$lType);
                $lId=$parameters[1];
                $listingId=explode(",",$lId);
                $lorder= $parameters[2];
                $order= explode(",",$lorder);
                $tURL= $parameters[3];
                $thumbURL= explode(",",$tURL);
                $lURL= $parameters[4];
                $largeURL= explode(",",$lURL);
                $nm= $parameters[5];
                $name= explode(",",$nm);
                $iId= $parameters[6];
                $instituteId= explode(",",$iId);

                $listingTypeId= $listingId[0];
                $noHeader = $parameters[7];

                if( $noHeader == 1){
                        $topHeader= array();
                        $topHeader[0]= $listingType;
                        $topHeader[1]= $listingId;
                        $topHeader[2]= $order;
                        $topHeader[3]= $thumbURL;
                        $topHeader[4]= $largeURL;
                        $topHeader[5]= $name;
                        $topHeader[6]= $instituteId;
                        $topHeader[7] = "yes";

                        
                        
                       
                        $updateVersion =1;
                        if( $updateVersion == 1)
                        {

                            $draftVersion = 0;
                            $liveVersion = 0;
                            if($data['dataFromCMS'] == '1')
                            {

                                    $draftVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'draft','history');
                                    $queuedVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'queued','history');
                                    $status = 'draft';
                            }
                            else
                            {
                                    $draftVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'draft','history');
                                    $queuedVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'queued','history');
                                    $status = 'draft';

                            }

                            $queryCmd = 'select max(version) as version from listings_main where listing_type = "institute" and listing_type_id= ?';
                            $query =  $this->db->query($queryCmd,$listingTypeId);
                            foreach ($query->result() as $row)
                            { $version = $row->version;}
                            $new_version =  $version+1;

                            if($draftVersion > 0 || $liveVersion > 0){
                            $old_version = $draftVersion>$liveVersion?$draftVersion:$liveVersion;}

                            else{

                                $queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "institute" and listing_type_id= ?';
                                $query =  $this->db->query($queryCmd,$listingTypeId);
                                foreach ($query->result() as $row){
                                    $liveVersion = $row->version;}

                                if($liveVersion > 0){
                                    $old_version = $liveVersion;}
                                else{
                                $old_version = 1;}
                                }

                            $data= array();
                            $data['header_details'] = $topHeader;
                            $this->ListingModel->getLocationAndContactInfoForListing($this->db,$listingTypeId,'institute',$old_version,$data); 
                            $response = $this->replicateInstitute($this->db,$listingTypeId,$old_version,$new_version,$status,$data,'media');
                         }
                        $setcompanylogoid=1;
                        return $this->xmlrpc->send_response($setcompanylogoid);

                }
                if( $noHeader ==0)
                {
                            $topHeader= array();
                            $topHeader[7] = "no";

                            $listingTypeId=$instituteId[0];


                            
                            
                        
                            $updateVersion =1;
                            if( $updateVersion == 1)
                            {

                                $draftVersion = 0;
                                $liveVersion = 0;
                                if($data['dataFromCMS'] == '1')
                                {

                                        $draftVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'draft','history');
                                        $queuedVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'queued','history');
                                        $status = 'draft';
                                }
                                else
                                {
                                        $draftVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'draft','history');
                                        $queuedVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'queued','history');
                                        $status = 'draft';

                                }

                                $queryCmd = 'select max(version) as version from listings_main where listing_type = "institute" and listing_type_id= ?';
                                $query =  $this->db->query($queryCmd,$listingTypeId);
                                foreach ($query->result() as $row)
                                { $version = $row->version;}
                                $new_version =  $version+1;

                                if($draftVersion > 0 || $liveVersion > 0){
                                $old_version = $draftVersion>$liveVersion?$draftVersion:$liveVersion;}

                                else{

                                    $queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "institute" and listing_type_id= ?';
                                    $query =  $this->db->query($queryCmd,$listingTypeId);
                                    foreach ($query->result() as $row){
                                    $liveVersion = $row->version;}

                                    if($liveVersion > 0){
                                    $old_version = $liveVersion;}
                                    else{
                                    $old_version = 1;}
                                    }

                                $data= array();
                                $data['header_details'] = $topHeader;
                                $this->ListingModel->getLocationAndContactInfoForListing($this->db,$listingTypeId,'institute',$old_version,$data); 
                                $response = $this->replicateInstitute($this->db,$listingTypeId,$old_version,$new_version,$status,$data,'media');
                            }


                }





}


	function sMapCourseCompany($request){

		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$lId= $parameters[0];
		$logoId= explode(",",$lId);
		$lType=$parameters[1];
		$listingType=explode(",",$lType);
		$liId= $parameters[2];
		$listingId= explode(",",$liId);
		$originalListingId= explode(",",$liId);
		$lorder= $parameters[3];
		$order= explode(",",$lorder);
		$iId= $parameters[4];
		$instituteId= explode(",",$iId);
		$listingId= array_unique($listingId);
		$listingId= array_values($listingId);
		$noCompany = $parameters[5];



		if($noCompany == 1){


			for( $b=0; $b < count($listingId); $b++)
			{
				$topCompany= array();
				$tempLogoId= array();
				$tempListingType= array();
				$tempListingId= array();
				$tempOrder= array();
				$tempInstituteId= array();
				$tempv=0;
				for( $c=0; $c< count($originalListingId); $c++)
				{


					if( $listingId[$b] == $originalListingId[$c])
					{

						$tempLogoId[$tempv]= $logoId[$c];
						$tempListingType[$tempv]= $listingType[$c];
						$tempListingId[$tempv]= $originalListingId[$c];
						$tempOrder[$tempv]=$order[$c];
						$tempInstituteId[$tempv]= $instituteId[$c];
						$tempv++;

					}


				}

				$topCompany[0]= $tempLogoId;
				$topCompany[1]= $tempListingType;
				$topCompany[2]= $tempListingId;
				$topCompany[3]= $tempOrder;
				$topCompany[4]= $tempInstituteId;

				if ( $noCompany == 1)
				$topCompany[5] = "yes";
				else
				$topCompany[5]= "no";



				$courseId= $tempListingId[0];
				
				
				

				$draftVersion = 0;
				$liveVersion = 0;
				if($data['dataFromCMS'] == '1'){
					$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
					$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
					$status = 'draft';
				}
				else{
					$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
					$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
					$status = 'draft';
				}
				$queryCmd = 'select max(version) as version from listings_main where listing_type = "course" and listing_type_id= ?';
				$query =  $this->db->query($queryCmd,$courseId);
				foreach ($query->result() as $row){
					$version = $row->version;
				}
				$new_version =  $version+1;
				if($draftVersion > 0 || $queuedVersion > 0){
					$old_version = $draftVersion>$queuedVersion?$draftVersion:$queuedVersion;
				}
				else{
					$queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "course" and listing_type_id= ?';
					$query =  $this->db->query($queryCmd,$courseId);
					foreach ($query->result() as $row){
						$liveVersion = $row->version;
					}

					if($liveVersion > 0){
						$old_version = $liveVersion;
					}

					else{
						$old_version = 1;
					}
				}

				$data= array();
				$data['company_details'] = $topCompany;
                                $data['relicate_features'] = "YES";
                                $this->ListingModel->getLocationAndContactInfoForListing($this->db,$courseId,'course',$old_version,$data);
                                //error_log('aditya_in_gandh1');
				$response = $this->replicateCourse($this->db,$courseId,$old_version,$new_version,$status,$data);


			}

		}
		if($noCompany == 0)
		{

			$iId= $parameters[4];
			$courseId= array();
			$courseList= array();

			
			
			
			if($this->db == ''){
				log_message('error','can not create db handle');}

				$queryCmd = "select listing_id from company_logo_mapping where listing_type='course' and institute_id= ? and ( status= 'draft' or status= 'live') ";
				$query = $this->db->query($queryCmd,$iId);

				foreach ($query->result() as $row){
					array_push($courseList,$row->listing_id);}
					$courseId= array_unique($courseList);
					$courseId= array_values($courseId);

					$topCompany = array();
					$topCompany[5]= "no";

					foreach( $courseId as $key => $val)
					{
						$courseId= $val;
						
						
						

						$draftVersion = 0;
						$liveVersion = 0;
						if($data['dataFromCMS'] == '1'){
							$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
							$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
							$status = 'draft';
						}
						else{
							$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
							$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
							$status = 'draft';
						}
						$queryCmd = 'select max(version) as version from listings_main where listing_type = "course" and listing_type_id= ?';
						$query =  $this->db->query($queryCmd,$courseId);
						foreach ($query->result() as $row){
							$version = $row->version;
						}
						$new_version =  $version+1;
						if($draftVersion > 0 || $queuedVersion > 0){
							$old_version = $draftVersion>$queuedVersion?$draftVersion:$queuedVersion;
						}
						else{
							$queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "course" and listing_type_id= ?';
							$query =  $this->db->query($queryCmd,$courseId);
							foreach ($query->result() as $row){
								$liveVersion = $row->version;
							}

							if($liveVersion > 0){
								$old_version = $liveVersion;
							}

							else{
								$old_version = 1;
							}
						}

						$data= array();
						$data['company_details'] = $topCompany;
                                                $data['relicate_features'] = "YES";
                                                $this->ListingModel->getLocationAndContactInfoForListing($this->db,$courseId,'course',$old_version,$data);
						$response = $this->replicateCourse($this->db,$courseId,$old_version,$new_version,$status,$data);
					}

		}


	}

	function sMapCourseHeader($request){

		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$lType= $parameters[0];
		$listingType= explode(",",$lType);
		$lId=$parameters[1];
		$listingId=explode(",",$lId);
		$originalListingId= explode(",",$lId);
		$lorder= $parameters[2];
		$order= explode(",",$lorder);
		$tURL= $parameters[3];
		$thumbURL= explode(",",$tURL);
		$lURL= $parameters[4];
		$largeURL= explode(",",$lURL);
		$nm= $parameters[5];
		$name= explode(",",$nm);
		$iId= $parameters[6];
		$instituteId= explode(",",$iId);
		$listingId= array_unique($listingId);
		$listingId= array_values($listingId);

		for( $b=0; $b < count($listingId); $b++)
		{
			$topHeader= array();
			$tempThumbURL= array();
			$tempLargeURL= array();
			$tempName= array();
			$tempListingType= array();
			$tempListingId= array();
			$tempOrder= array();
			$tempInstituteId= array();
			$tempv=0;
			for( $c=0; $c< count($originalListingId); $c++)
			{


				if( $listingId[$b] == $originalListingId[$c])
				{

					$tempThumbURL[$tempv]= $thumbURL[$c];
					$tempLargeURL[$tempv]= $largeURL[$c];
					$tempName[$tempv]= $name[$c];
					$tempListingType[$tempv]= $listingType[$c];
					$tempListingId[$tempv]= $listingId[$c];
					$tempOrder[$tempv]= $order[$c];
					$tempInstituteId[$tempv]= $instituteId[$c];
					$tempv++;


				}


			}

			$topHeader[0]= $tempListingType;
			$topHeader[1]= $tempListingId;
			$topHeader[2]= $tempOrder;
			$topHeader[3]= $tempThumbURL;
			$topHeader[4]= $tempLargeURL;
			$topHeader[5]= $tempName;
			$topHeader[6]= $tempInstituteId;
			$courseId= $tempListingId[0];

			
			
			

			$draftVersion = 0;
			$liveVersion = 0;
			if($data['dataFromCMS'] == '1'){
				$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
				$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
				$status = 'draft';
			}
			else{
				$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
				$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
				$status = 'draft';
			}
			$queryCmd = 'select max(version) as version from listings_main where listing_type = "course" and listing_type_id= ?';
			$query =  $this->db->query($queryCmd,$courseId);
			foreach ($query->result() as $row){
				$version = $row->version;
			}
			$new_version =  $version+1;
			if($draftVersion > 0 || $queuedVersion > 0){
				$old_version = $draftVersion>$queuedVersion?$draftVersion:$queuedVersion;
			}
			else{
				$queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "course" and listing_type_id= ?';
				$query =  $this->db->query($queryCmd,$courseId);
				foreach ($query->result() as $row){
					$liveVersion = $row->version;
				}

				if($liveVersion > 0){
					$old_version = $liveVersion;
				}

				else{
					$old_version = 1;
				}
			}

			$data= array();
			$data['header_details'] = $topHeader;
                        $this->ListingModel->getLocationAndContactInfoForListing($this->db,$courseId,'course',$old_version,$data);
			$response = $this->replicateCourse($this->db,$courseId,$old_version,$new_version,$status,$data);


		}


	}


	function sGetDistinctLogo($request){

		$parameters = $request->output_parameters();
		
		
		$institute_id= $parameters[0];
                $getLiveVersion = $parameters[1];
		//$queryCmd = 'select logo_id,company_name,logo_url,company_order, group_concat(listing_id) as listing_ids, group_concat(listing_type) as listing_types from company_logo_mapping a inner join company_logos on a.logo_id=id where institute_id= "'.$institute_id.'" and a.linked= "yes" and version = (select max(b.version) from company_logo_mapping b where b.listing_type=a.listing_type and b.listing_id= a.listing_id and (b.status ="live" or b.status="draft" or b.status="queued")) group by logo_id order by company_order';

         $queryCmd = 'SELECT logo_id, company_name, logo_url, company_order, group_concat( listing_id ) AS listing_ids, group_concat( listing_type ) AS listing_types
                FROM company_logo_mapping a, company_logos b
                WHERE institute_id = ? 
                AND a.logo_id = b.id
                AND b.status = "live"
                AND (
                a.STATUS = "live"
                OR a.STATUS = "draft"
                OR a.STATUS = "queued"
                )
                GROUP BY logo_id
                ORDER BY `a`.`company_order` ASC';
                
       if(!empty($getLiveVersion) && $getLiveVersion == 'getLiveVersion') {
			$queryCmd = 'select logo_id,company_name,logo_url,company_order, group_concat(listing_id) as listing_ids, group_concat(listing_type) as listing_types from company_logo_mapping a inner join company_logos on a.logo_id=id where institute_id= ? and a.linked= "yes" and version = (select max(b.version) from company_logo_mapping b where b.listing_type=a.listing_type and b.listing_id= a.listing_id and (b.status ="live")) group by logo_id order by company_order';
		}

		$query = $this->db->query($queryCmd,$institute_id);
		$resArray= array();
		foreach ($query->result() as $row){

			array_push($resArray,array(
			array(
                                                                                'logo_id'=>$row->logo_id,
                                                                                'company_name'=>$row->company_name,
                                                                                'company_order'=>$row->company_order,
                                                                                'listing_ids'=>$row->listing_ids,
                                                                                'logo_url'=>$row->logo_url,
                                                                                'listing_types'=>$row->listing_types,
			),'struct')
			);//close array_push
		}

		$response = array($resArray,'struct');
		return $this->xmlrpc->send_response($response);

	}


	function sGetDistinctHeader($request){

		$parameters = $request->output_parameters();
		
		
		$institute_id= $parameters[0];

                $queryCmd = 'select name, header_order, full_url, thumb_url, listing_id as listing_ids, listing_type as listing_types from header_image a where institute_id= ? and a.linked="yes" and status ="draft" order by header_order';
		$query = $this->db->query($queryCmd,$institute_id);
                if($query->num_rows() <= 0){
                    $queryCmd = 'select name, header_order, full_url, thumb_url, listing_id as listing_ids, listing_type as listing_types from header_image a where institute_id= ? and a.linked="yes" and status ="live" order by header_order';
                    $query = $this->db->query($queryCmd,$institute_id);
                }

		$resArray= array();
		foreach ($query->result() as $row){

			array_push($resArray,array(
			array(
                                                                                'name'=>$row->name,
                                                                                'large_url'=>$row->full_url,
                                                                                'thumb_url'=>$row->thumb_url,
                                                                                'header_order'=>$row->header_order,
                                                                                'listing_ids'=>$row->listing_ids,
                                                                                'listing_types'=>$row->listing_types,
			),'struct')
			);//close array_push
		}

		$response = array($resArray,'struct');
		return $this->xmlrpc->send_response($response);

	}


       function getCategoryContentParams($request){


                
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

                $parameters = $request->output_parameters();
		$virtualSubCatId=$parameters['0'];
                $virtualCourseLevel=$parameters['1'];
                $virtualMode= $parameters['2'];


                $queryCmd= "SELECT course_category_page_id from course_category_url_mapping where subcategory_id= ? and course_level= '?' and mode= '?' and status='live' ";
                //error_log(print_r($queryCmd,true),3,"/home/naukri/Desktop/qry.log");
                $query= $this->db->query($queryCmd,array($virtualSubCatId,$virtualCourseLevel,$virtualMode));
                foreach($query->result_array() as $row){
			$ccPageId = $row['course_category_page_id'];
		}
                if($ccPageId== NULL)
                {
                    $queryCmd= "SELECT keyword, course_type_search, course_level_search from course_category_url_mapping where subcategory_id= ? and course_level= '?' and mode= '?' and status='live'";
                	$query= $this->db->query($queryCmd,array($virtualSubCatId,$virtualCourseLevel,$virtualMode));
                    $resArray= array();
                    foreach ($query->result() as $row){

			array_push($resArray,array(
                                                array(
                                                        'redirected'=>1,
                                                        'keyword'=>$row->keyword,
                                                        'course_type_search'=>$row->course_type_search,
                                                        'course_level_search'=>$row->course_level_search,
                                                     ),'struct')
                                  );
                    }
                    $response = array($resArray,'struct');
                    return $this->xmlrpc->send_response($response);
                }
                else
                {
                    $queryCmd= "SELECT snippet_type,course_key_id, also_see, search_combination_categories,search_combination_course_level, search_combination_course_type, show_subcategory, show_level, show_mode,course_page_heading,course_page_title from course_category_url_mapping , category_course_list where subcategory_id= ? and course_level= '?' and mode= '?' and course_category_page_id= course_key_id and category_course_list.status='active' and course_category_url_mapping.status='live'";
                    $query= $this->db->query($queryCmd,array($virtualSubCatId,$virtualCourseLevel,$virtualMode));
                    $resArray= array();
                    foreach ($query->result() as $row){

			array_push($resArray,array(
                                                array(
                                                        'redirected'=>0,
                                                        'course_key_id'=>$row->course_key_id,
                                                        'also_see'=>$row->also_see,
                                                        'snippet_type'=>$row->snippet_type,
                                                        'search_combination_categories'=>$row->search_combination_categories,
                                                        'search_combination_course_level'=>$row->search_combination_course_level,
                                                        'search_combination_course_type'=> str_replace("elearning", "E-learning", $row->search_combination_course_type),
                                                        'show_subcategory'=>$row->show_subcategory,
                                                        'show_level'=>$row->show_level,
                                                        'show_mode'=>$row->show_mode,
                                                        'course_page_heading'=>$row->course_page_heading,
														'course_page_title'=>$row->course_page_title
                                                     ),'struct')
                                  );
                    }
                    $response = array($resArray,'struct');
                    return $this->xmlrpc->send_response($response);
                }

        }

	function disapproveQueuedListings($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listings = unserialize(base64_decode($parameters['1']));
		$audit = unserialize(base64_decode($parameters['2']));
		
		
		$this->db = $this->_loadDatabaseHandle('write');
		$resultSet = array();
		$numListings = count($listings);
		for($i = 0; $i < $numListings ; $i++){
			switch($listings[$i]['type']){
				case 'institute':
					$new_live = $this->updateInstituteStatus($this->db,$listings[$i]['typeId'],'queued','draft',-1,$audit);
					break;
				case 'course':
					$new_live = $this->updateCourseStatus($this->db,$listings[$i]['typeId'],'queued','draft',-1,$audit);
					break;
			}
			$resultSet[$i]['version'] = $new_live;
			$resultSet[$i]['type'] = $listings[$i]['type'];
			$resultSet[$i]['typeId'] = $listings[$i]['typeId'];
		}
		$response = array(base64_encode(serialize($resultSet)),'string');
		return $this->xmlrpc->send_response($response);
	}

	function getUrlForOverviewTab($request){

		$parameters = $request->output_parameters();
		$id = $parameters['0'];
		$type = $parameters['1'];
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$this->load->config('nationalInstitute/instituteSectionConfig');
		$listingMainStatus= $this->config->item("listingMainStatus");
		$status = $listingMainStatus['live'];

		$queryCmd = "SELECT listing_seo_url FROM listings_main WHERE listing_type_id = ? AND listing_type = ? AND status = ?";
		$query = $this->db->query($queryCmd,array($id,$type,$status));
		$url=0;

		foreach($query->result_array() as $row){
			$url = $row['listing_seo_url'];
		}
		$response = array($url, 'string');
		return $this->xmlrpc->send_response($response);

	}

	
	function getCategoryIdsForEachCourse($request){

		$parameters = $request->output_parameters();
		$courseIds = $parameters['0'];

		$queryCmd = "SELECT GROUP_CONCAT(distinct cbt.parentId) as parentId,lct.listing_type_id as course_id FROM categoryBoardTable cbt INNER JOIN listing_category_table lct on cbt.boardId = lct.category_id WHERE lct.listing_type = 'course' AND lct.status = 'live' AND listing_type_id IN (?) GROUP BY listing_type_id;";
		$query = $this->db->query($queryCmd,array($courseIds));
		$categoryIds = array();
		foreach($query->result_array() as $row){
			array_push($categoryIds,array(array(
            'category_id' => $row['parentId'],
            'course_id'   => $row['course_id']),'struct'));

		}

		$response = array($categoryIds,'struct');

		return $this->xmlrpc->send_response($response);
	}

	function getCategoryIdsForCourseIds($request){
		
		$parameters = $request->output_parameters();
		$courseIds = $parameters['0'];

		$queryCmd = "SELECT DISTINCT parentId FROM categoryBoardTable where boardID IN(SELECT category_id FROM listing_category_table WHERE listing_type = 'course' AND listing_type_id IN (?) and status = 'live' )";
		//error_log(print_r($queryCmd,true),3,'/home/aakash/Desktop/aakash.log');
		$query = $this->db->query($queryCmd,array($courseIds));
		$categoryIds = array();
		foreach($query->result_array() as $row){
			$categoryIds[] = $row['parentId'];
		}
		$response = array($categoryIds,'struct');
		return $this->xmlrpc->send_response($response);
	}

        function getCountDataForAnAWidget($request){
                $parameters = $request->output_parameters();
		  $instituteId = $parameters['0'];

                $queryCmd = "SELECT sum(if(mainAnswerId=0,1,0)) AnswerCount ,sum(if(mainAnswerId>0,1,0)) CommentCount  FROM messageTable where listingTypeId = ? and listingType='institute' and parentId!=0 and mainAnswerId>=0 and status IN ('live','closed')";
                $query = $this->db->query($queryCmd,$instituteId);
                $count = array();
                foreach($query->result_array() as $row){
		    $queryCmdQ = "SELECT count(*) QuestionCount FROM messageTable where listingTypeId = ? and listingType = 'institute' and parentId=0 and status IN ('live','closed') and fromOthers='user'";
		    $queryQ = $this->db->query($queryCmdQ,$instituteId);
		    $rowQ = $queryQ->row();
		    $row['QuestionCount'] = $rowQ->QuestionCount;

                    array_push($count,array(array(
                        'questionCount'=> $row['QuestionCount'],
                        'answerCount' => $row['AnswerCount'],
                        'commentCount'=> $row['CommentCount']
                    ),'struct'));

                }
                $response = array($count,'struct');
                return $this->xmlrpc->send_response($response);
        }

	function getReasonToJoinInstitute($request){
		$parameters = $request->output_parameters();
		$instituteId = $parameters['0'];

		
		$queryCmd = "SELECT * FROM institute_join_reason WHERE institute_id = ? AND status ='live'";

		$query = $this->db->query($queryCmd,$instituteId);
		$joinInstitute = array();
		//error_log(print_r($query->result_array(),true),3,'/home/aakash/Desktop/aakash.log');
		foreach($query->result_array() as $row){
			$data = array(
                    'photoTitle'=>$row['photo_title'],
                    'photoUrl'=>$row['photo_url'],
                    'details'=>$row['details']
			);
			array_push($joinInstitute,array($data,'struct'));
		}
		//error_log(print_r("aakash",true),3,'/home/aakash/Desktop/aakash.log');

		$joinInstitute = base64_encode(serialize($joinInstitute));
		//error_log(print_r($joinInstitute,true),3,'/home/aakash/Desktop/aakash.log');
		$response = array($joinInstitute,'string');
		//error_log(print_r($response,true),3,'/home/aakash/Desktop/aakash.log');
		return $this->xmlrpc->send_response($response);
	}


	function makeListingsLive($request){

		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listings = unserialize(base64_decode($parameters['1']));
		$audit = unserialize(base64_decode($parameters['2']));
		$status = $parameters['3'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		if($status == 'live') {
			$resultSet = array();
			$numListings = count($listings);

			for($i = 0; $i < $numListings ; $i++){
				switch($listings[$i]['type']){
					case 'institute':
						$new_live = $this->updateInstituteStatus($this->db,$listings[$i]['typeId'],'draft','live',-1,$audit);
						if($new_live > 0) {
							$live = $this->updateInstituteStatus($this->db,$listings[$i]['typeId'],'live','history',$new_live,$audit);
						}
						else{
							$new_live = $this->updateInstituteStatus($this->db,$listings[$i]['typeId'],'queued','live',-1,$audit);
							if($new_live > 0) {
							}
						}
						//Amit Singhal : Updating the Category Page Data for an institute.
						// $this->updateCategoryPageDataInstitute($this->db,$listings[$i]['typeId']);
                                                // error_log("\n\n In makeListingsLive, going to call updateCategoryPageDataInstitute  ",3,'/home/infoedge/Desktop/log.txt');
						//Amit Singhal : Updating the Media Count Data
						$this->updateMediaCountData($this->db, $listings[$i]['typeId']);
						
						//Amit Singhal : Clear SEO url for listing
						$key = 'getUrlForOverviewTab'.$type_id.$identifier;
						break;
				}
				$resultSet[$i]['version'] = $new_live;
				if($new_live > 0){
					$this->indexListing($listings[$i]['type'],$listings[$i]['typeId']);
					if($listings[$i]['type'] == 'course'){
						$queryCmd = 'select institute_id from institute_courses_mapping_table where course_id= ?';
						$query =  $this->db->query($queryCmd,$listings[$i]['typeId']);
						foreach ($query->result() as $row){
							$instituteId = $row->institute_id;
							$this->indexListing('institute',$instituteId);
						}
					}
				}
				$resultSet[$i]['type'] = $listings[$i]['type'];
				$resultSet[$i]['typeId'] = $listings[$i]['typeId'];
			}
                        
			for($i = 0; $i < $numListings ; $i++){
				switch($listings[$i]['type']){
					case 'course':
						$new_live = $this->updateCourseStatus($this->db,$listings[$i]['typeId'],'draft','live',-1,$audit);
						error_log("$new_live new live version");
						if($new_live > 0) {
							$live = $this->updateCourseStatus($this->db,$listings[$i]['typeId'],'live','history',$new_live,$audit);
							error_log("$live new live version");
						}
						else{
							$new_live = $this->updateCourseStatus($this->db,$listings[$i]['typeId'],'queued','live',-1,$audit);
							error_log("$new_live new live version");
							if($new_live > 0) {
								$live = $this->updateCourseStatus($this->db,$listings[$i]['typeId'],'live','history',$new_live,$audit);
							}
						}
						//Amit Singhal : Updating the Category Page Data for a course.
						$this->updateCategoryPageDataCourse($this->db,$listings[$i]['typeId']);
						break;
				}
				$resultSet[$i]['version'] = $new_live;
				if($new_live > 0){
					$this->indexListing($listings[$i]['type'],$listings[$i]['typeId']);
					$this->buildListingCache($listings[$i]['type'],$listings[$i]['typeId']);
					if($listings[$i]['type'] == 'course'){
						$queryCmd = 'select DISTINCT institute_id from institute_courses_mapping_table where course_id= ?';
						$query =  $this->db->query($queryCmd,$listings[$i]['typeId']);
						foreach ($query->result() as $row){
							$instituteId = $row->institute_id;
							$this->indexListing('institute',$instituteId);
							$this->buildListingCache('institute',$instituteId);
						}
					}
				}
				$resultSet[$i]['type'] = $listings[$i]['type'];
				$resultSet[$i]['typeId'] = $listings[$i]['typeId'];

			}
		}
		else{
			$resultSet = $this->makeListingsQueued($this->db,$listings,$audit);
		}
                
                // error_log("\n\n In makeListingsLive, Done : ".print_r($resultSet, true),3,'/home/infoedge/Desktop/log.txt');
                 
		$response = array(base64_encode(serialize($resultSet)),'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function buildListingCache($type, $typeId){
		$GLOBALS['forceListingWriteHandle'] = 1;
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		if($type != "course"){
			$listingRepository = $listingBuilder->getInstituteRepository();
		}else{
			$listingRepository = $listingBuilder->getCourseRepository();
		}
		$listingRepository->disableCaching();
		$listing = $listingRepository->find($typeId);
		
	}

	function makeListingsQueued($db, $listings,$audit){
		$this->db = $this->_loadDatabaseHandle('write');
		$resultSet = array();
		$numListings = count($listings);
		for($i = 0; $i < $numListings ; $i++){
			switch($listings[$i]['type']){
				case 'institute':
					$new_live = $this->updateInstituteStatus($this->db,$listings[$i]['typeId'],'draft','queued',-1,$audit);
					break;
				case 'course':
					$new_live = $this->updateCourseStatus($this->db,$listings[$i]['typeId'],'draft','queued',-1,$audit);
					error_log("$new_live new live version");
					break;
			}
			$resultSet[$i]['version'] = $new_live;
			$resultSet[$i]['type'] = $listings[$i]['type'];
			$resultSet[$i]['typeId'] = $listings[$i]['typeId'];
		}
		return $resultSet;
	}


	function getWikiForListing($request){

		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingId = $parameters['1'];
		$listingType = $parameters['2'];
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$queryCmd = 'select max(version) as version from listings_main where listing_type = ? and listing_type_id= ? and status = "live" ';

		$query =  $this->db->query($queryCmd,array($listingType,$listingId));

		foreach ($query->result() as $row){
			$version = $row->version;
		}

		$wikiInfo = base64_encode(serialize($this->ListingModel->getWikiDetailsForListing($this->db,$listingType,$listingId,$version)));
		$response = array($wikiInfo,'string');
		return $this->xmlrpc->send_response($response);

	}


	//Returns the url for the header images on the institute page
	function getUrlForHeaderImages($request){
		$parameters = $request->output_parameters();
		$institute_id = $parameters['0'];

		$queryCmd = "SELECT full_url,thumb_url FROM header_image where institute_id = ? AND status ='live' AND linked='yes' ";


		$query = $this->db->query($queryCmd,$institute_id);

		$imageArray = array();
		foreach($query->result_array() as $row){

			array_push($imageArray,array(array(
                'bigImage'=> urlencode($row['full_url']),
                'smallImage'=> urlencode($row['thumb_url']) ),'struct'));
		}
		//error_log(print_r("aakash",true),3,'/home/aakash/Desktop/aakash.log');
		//$resultSet = json_encode($imageArray);
		$response = array($imageArray,'struct');
		//error_log(print_r($response,true),3,'/home/aakash/Desktop/aakash.log');
		return $this->xmlrpc->send_response($response);

	}

	function getUrlForCompaniesLogo($request){
		$parameters = $request->output_parameters();
		$course_id = $parameters['0'];
		$institute_id = $parameters['1'];
		
	
		// Getting the MAX version...
		 $versionQuery = "select max(version) as version from company_logo_mapping where listing_id = ? and status = 'live'";
                $versionRs = $this->db->query($versionQuery,$course_id);
                foreach ($versionRs->result() as $versionRow){
		    $liveVersion = $versionRow->version;
                }

		// $queryCmd = "SELECT cl.logo_url as url FROM company_logos as cl ,company_logo_mapping as clm where clm.listing_id = ".$course_id." AND clm.institute_id = ".$institute_id." AND clm.listing_type = 'course' AND cl.status = 'live' AND clm.status = 'live' AND clm.linked = 'yes' AND clm.logo_id = cl.id ORDER BY clm.company_order";

		$queryCmd = "SELECT cl.logo_url as url FROM company_logos as cl ,company_logo_mapping as clm where clm.listing_id = ? AND clm.institute_id = ? AND clm.listing_type = 'course' AND cl.status = 'live' AND clm.status = 'live' AND clm.linked = 'yes' AND clm.logo_id = cl.id and clm.version = ? ORDER BY clm.company_order";

		$query = $this->db->query($queryCmd,array($course_id,$institute_id,$liveVersion));

		$imageArray = array();
		foreach($query->result_array() as $row){

			$imageArray[] = urlencode($row['url']);
		}

		$response = array($imageArray,'struct');
		return $this->xmlrpc->send_response($response);

	}


	function getMetaInfoForInstitutes($request)
	{

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$institutes = $parameters['1'];
		$categoryid = $parameters['2'];
		
		$resultSet = $this->ListingModel->getMetaInfoForInstitutes($this->db,$institutes,$categoryid);
		$response = array($resultSet,'string');
		return $this->xmlrpc->send_response($response);
	}

	function getCmsCountryOptions($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$countryid = $parameters['1'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$resultSet = $this->ListingModel->getCmsCountryOptions($this->db,$countryid);
		$response = array(json_encode($resultSet),'string');
		return $this->xmlrpc->send_response($response);
	}

	function getCmsTopInstitutes($request)
	{

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$categoryid = $parameters['1'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$resultSet = $this->ListingModel->getCmsTopInstitutes($this->db,$categoryid);
		$response = array(json_encode($resultSet),'string');
		return $this->xmlrpc->send_response($response);
	}

	function getMetaInfo($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listings = unserialize(base64_decode($parameters['1']));
		$status  = $parameters['2'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$resultSet = $this->ListingModel->getMetaInfo($this->db,$listings,$status);
		$response = array(base64_encode(serialize($resultSet)),'string');
		return $this->xmlrpc->send_response($response);
	}

	function sUpdateListingMediaAttributes($request) { //SQL Injection Update

		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingType = $parameters['1'];
		$listingId = $parameters['2'];
		$mediaType = $parameters['3'];
		$mediaId = $parameters['4'];
		$mediaAttributeName = $parameters['5'];
		$mediaAttributeValue= $parameters['6'];
		$mediaType = $this->getMediaType($mediaType);
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$updateMediaListingMappingQuery = 'UPDATE institute_uploaded_media SET '.$mediaAttributeName.' = "'. $mediaAttributeValue .'" WHERE media_id="'. $mediaId .'" AND media_type="'. $mediaType .'" AND listing_type = "'. $listingType .'" AND listing_type_id = "'. $listingId .'"';
		error_log("MEDIAL::". $updateMediaListingMappingQuery);
		$queryResult = $this->db->query($updateMediaListingMappingQuery);
		$response = array(array('status'=>'success'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sMapMediaContentWithListing($request) { //SQL Injection insert
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingId = $parameters['1'];
		$listingType = $parameters['2'];
		$mediaType = $parameters['3'];
		$mediaType = $this->getMediaType($mediaType);
		$mediaDetails = json_decode(base64_decode($parameters['4']), true);

		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$mediaId = $mediaDetails['mediaId'];
		$mediaName = $mediaDetails['mediaName'];
		$mediaUrl = $mediaDetails['mediaUrl'];
		$mediaThumbUrl = $mediaDetails['mediaThumbUrl'];
		$institute_location_id = 0;
		if(!empty($mediaDetails['institute_location_id'])) {
			$institute_location_id = $mediaDetails['institute_location_id'];
		}
		$insertMediaMappingQuery = 'INSERT INTO institute_uploaded_media SET institute_location_id=? ,listing_type=? , listing_type_id=? , media_type = ?, media_id = ?, name = ?, url=?, thumburl = ?, uploadeddate = NOW()';
		$queryResult = $this->db->query($insertMediaMappingQuery,array($institute_location_id, $listingType, $listingId, $mediaType, $mediaId, $mediaName, $mediaUrl, $mediaThumbUrl));
		//error_log("MEDIAL::". $this->db->last_query());
		$response = array(array('status'=>'success'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sAssociateMedia($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$parentListingType = $parameters['1'];
		$parentListingId = $parameters['2'];
		$mediaAssociation = json_decode(base64_decode($parameters['3']), true);
		$metaData = json_decode(base64_decode($parameters['4']), true);
		$commentData = json_decode(base64_decode($parameters['5']), true);

		
		$this->db = $this->_loadDatabaseHandle('write');
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		foreach($mediaAssociation as $listingType => $listingTypeAssociations) {
			foreach($listingTypeAssociations as $listingTypeId => $listingTypeIdAssociations) {
				$existingMediaForListing = $this->ListingModel->getCurentMediaForListing($this->db,$listingType,$listingTypeId);
				$media_diff1 = array_diff_assoc_recursive($existingMediaForListing,$listingTypeIdAssociations);

				$media_diff2 = array_diff_assoc_recursive($listingTypeIdAssociations,$existingMediaForListing);
				error_log("hurray123 $listingType $listingTypeId ".print_r($media_diff1,true));
				error_log("hurray123 $listingType $listingTypeId ".print_r($media_diff2,true));
				if($media_diff1 != 0 || $media_diff2 != 0){
					$new_version = $this->updateListingMedia($this->db,$listingType,$listingTypeId,$metaData,$listingTypeIdAssociations);
				}
			}
		}
		$response = array(array('status'=>$new_version),'struct');

		//save mandatory comments
		$commentResponse  = $this->saveMandatoryComments(array($commentData['cmsTrackUserId'],$commentData['cmsTrackListingId'],$commentData['cmsTrackTabUpdated'],$commentData['mandatory_comments']));

		return $this->xmlrpc->send_response($response);
	}

	function updateListingMedia($db,$listingType,$listingTypeId,$data,$listingTypeIdAssociations){
		$this->db = $this->_loadDatabaseHandle('write');
		error_log("in updateListingMedia");
		$draftVersion = 0;
		$liveVersion = 0;
		switch($listingType){
			case 'institute':                   				
				if($data['dataFromCMS'] == '1'){
					$draftVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'draft','history');
					$queuedVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'queued','history');
					//                $liveVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'live','history');
					//                $status = 'live';
					$status = 'draft';
				}
				else{
					$draftVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'draft','history');
					$queuedVersion = $this->updateInstituteStatus($this->db,$listingTypeId,'queued','history');
					$status = 'draft';
				}
                                $queryCmd = 'select max(version) as version from listings_main where listing_type = "institute" and listing_type_id= ?';
				$query =  $this->db->query($queryCmd,$listingTypeId);
                
				foreach ($query->result() as $row){
					$version = $row->version;
				}
				$new_version =  $version+1;
				if($draftVersion > 0 || $liveVersion > 0){
					$old_version = $draftVersion>$liveVersion?$draftVersion:$liveVersion;
				}
				else{
					$queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "institute" and listing_type_id= ?';
					$query =  $this->db->query($queryCmd,$listingTypeId);
					foreach ($query->result() as $row){
						$liveVersion = $row->version;
					}
					if($liveVersion > 0){
						$old_version = $liveVersion;
					}
					else{
						$old_version = 1;
					}
				}
				$data['media_details'] = $listingTypeIdAssociations;
                                $this->ListingModel->getLocationAndContactInfoForListing($this->db,$listingTypeId,'institute',$old_version,$data);  
				$response = $this->replicateInstitute($this->db,$listingTypeId,$old_version,$new_version,$status,$data,'media');
				break;
			case 'course':                                
				if($data['dataFromCMS'] == '1'){
					$draftVersion = $this->updateCourseStatus($this->db,$listingTypeId,'draft','history');
					$queuedVersion = $this->updateCourseStatus($this->db,$listingTypeId,'queued','history');
					//                $liveVersion = $this->updateCourseStatus($this->db,$listingTypeId,'live','history');
					//                $status = 'live';
					$status = 'draft';
				}
				else{
					$draftVersion = $this->updateCourseStatus($this->db,$listingTypeId,'draft','history');
					$queuedVersion = $this->updateCourseStatus($this->db,$listingTypeId,'queued','history');
					$status = 'draft';
				}
                                $queryCmd = 'select max(version) as version from listings_main where listing_type = "course" and listing_type_id= ?';
				$query =  $this->db->query($queryCmd,$listingTypeId);
				foreach ($query->result() as $row){
					$version = $row->version;
				}	 			
				$new_version =  $version+1;
				if($draftVersion > 0){
					$old_version = $draftVersion;
				}
				else{
					$queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "course" and listing_type_id= ?';
					$query =  $this->db->query($queryCmd,$listingTypeId);
					foreach ($query->result() as $row){
						$liveVersion = $row->version;
					}
					if($liveVersion > 0){
						$old_version = $liveVersion;
					}
					else{
						$old_version = 1;
					}
				}
				$data['media_details'] = $listingTypeIdAssociations;
				$response = $this->replicateCourse($this->db,$listingTypeId,$old_version,$new_version,$status,$data,'media');
				break;
		}
		return $response;
	}

	function getMediaType($mediaType) {
		switch($mediaType) {
			case 'documents' : $mediaType = 'doc'; break;
			case 'photos' : $mediaType = 'photo'; break;
			case 'videos' : $mediaType = 'video'; break;
		}
		return $mediaType;
	}

	function sRemoveMediaContent($request) { //SQL Injection Update
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingType = $parameters['1'];
		$listingId = $parameters['2'];
		$mediaType = $parameters['3'];
		$mediaId = $parameters['4'];
		$mediaType = $this->getMediaType($mediaType);

		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$updateMediaListingMappingQuery = 'UPDATE institute_uploaded_media SET status = "deleted" WHERE media_id="'. $mediaId .'" AND media_type="'. $mediaType .'" AND listing_type = "'. $listingType .'" AND listing_type_id = "'. $listingId .'"';
		error_log("MEDIAL::". $updateMediaListingMappingQuery);
		$queryResult = $this->db->query($updateMediaListingMappingQuery);
		$response = array(array('status'=>'success'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sGetMediaDetailsForListing($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingType = $parameters['1'];
		$listingId = $parameters['2'];

		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
               
		$result_array = $this->ListingModel->getListingMaxVersionInfo($listingId, $listingType);

 	        $maxVersion = $result_array[0]['version'];
                if($maxVersion == NULL || $maxVersion == "") {
                    $response = array(base64_encode(json_encode("")));
                    return $this->xmlrpc->send_response($response);
                }
		
 	        $getMediaDetailsQuery = 'SELECT *, a.media_type, a.media_id FROM institute_uploaded_media a left join  listing_media_table b on a.media_id = b.media_id AND a.media_type=b.media_type and b.version = ? where a.status!="deleted" AND a.listing_type = ? AND a.listing_type_id= ?  ORDER BY a.media_type, a.media_id';
                
		$queryResult = $this->db->query($getMediaDetailsQuery,array($maxVersion,$listingType,$listingId));

		$msgArray = array();
		foreach ($queryResult->result() as $row){
			$mediaType = $row->media_type;
			$mediaId = $row->media_id;
			$mediaName = $row->name;
			$mediaUrl = $row->url;
			$mediaThumbUrl = $row->thumburl;
			$mediaUploadDate = $row->uploaddate;
			$mediaAssociationDate = $row->associationdate;
			$type = $row->type;
			$typeId = $row->type_id;
			switch($mediaType) {
				case 'doc' : $mediaType = 'documents'; break;
				case 'photo' : $mediaType = 'photos'; break;
				case 'video' : $mediaType = 'videos'; break;
			}

			$msgArray[$mediaType][$mediaId]['mediaCaption'] = $mediaName;
			$msgArray[$mediaType][$mediaId]['mediaUrl'] = $mediaUrl;
			$msgArray[$mediaType][$mediaId]['mediaThumbUrl'] = $mediaThumbUrl;
			$msgArray[$mediaType][$mediaId]['mediaUploadDate'] = $mediaUploadDate;
			$msgArray[$mediaType][$mediaId]['mediaAssociationDate'] = $mediaAssociationDate;
			$msgArray[$mediaType][$mediaId]['mediaAssociation'][] = array($type => $typeId);
		}
		
		$response = array(base64_encode(json_encode($msgArray)));
		return $this->xmlrpc->send_response($response);
	}


	function checkCourseDuplicacy($request)
	{
		
		$parameters = $request->output_parameters();
		//error_log(print_r($parameters[1],true));
		$formVal = unserialize(base64_decode($parameters['1']));
		error_log(print_r($formVal,true));
		$appId = $parameters['0']['0'];
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = "select course_id, courseTitle, institute_name from course_details, institute where institute.status in ('live','draft') and institute.institute_id=course_details.institute_id and institute.institute_id = ? and courseTitle = ? and course_type= ? and course_id != ? and course_details.status in ('live','draft','queued') ";

		//error_log("RRR COURSE DUP :: ".$queryCmd);
		$queryCheckDup = $this->db->query($queryCmd,array($formVal['institute_id'],$formVal['courseTitle'],$formVal['courseType'],$formVal['course_id']));
		$duplicateCourses = array();
		if($queryCheckDup->num_rows() > 0){
			foreach($queryCheckDup->result_array() as $duplicateRow){
				array_push($duplicateCourses,$duplicateRow);
			}
		}
		$response = array(base64_encode(serialize($duplicateCourses)),'string');
		return $this->xmlrpc->send_response($response);
	}


	function checkInstituteDuplicacy($request)
	{
		
		$parameters = $request->output_parameters();
		error_log(print_r($parameters[1],true));
		$formVal = unserialize(base64_decode($parameters['1']));
		error_log(print_r($formVal,true));
		$appId = $parameters['0']['0'];
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$queryCmd = "select institute.institute_id, institute_name from institute,institute_location_table where institute.institute_id = institute_location_table.institute_id and pincode = ? and institute.institute_id != ? and  institute_name = ? and institute.status in ('live','draft','queued') and institute.status=institute_location_table.status";
		
		$queryCheckDup = $this->db->query($queryCmd,array(trim($formVal['pincode']),$formVal['institute_id'],$formVal['institute_name']));
		$duplicateInstitutes = array();
		if($queryCheckDup->num_rows() > 0){
			foreach($queryCheckDup->result_array() as $duplicateRow){
				array_push($duplicateInstitutes,$duplicateRow);
			}
		}
	
		$response = array(base64_encode(serialize($duplicateInstitutes)),'string');
		return $this->xmlrpc->send_response($response);
	}

	function getContactInfo($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = 'select contact_details_id  from listings_main where listing_type = ? and listing_type_id= ?  and status = "live" ';
		$query =  $this->db->query($queryCmd,array($listing_type,$type_id));
		foreach ($query->result() as $row){
			$contact_details_id = $row->contact_details_id;
		}
		$contactInfo = $this->ListingModel->getContactDetails($this->db,$contact_details_id);
		$response = array(base64_encode(serialize($contactInfo)),'string');
		return $this->xmlrpc->send_response($response);
	}

	//This function gives the Flagship Course Id for the given the InstituteId
	function getCourseIdForInstituteId($request){
		
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$instituteId = $parameters['1'];			
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		// $queryCmd = "SELECT course_id FROM course_details WHERE status= 'live' AND institute_id = $instituteId AND course_order IS NOT NULL ORDER BY course_order  limit 0,1";
                $queryCmd = "SELECT cd.course_id FROM course_details cd WHERE cd.status= 'live' AND cd.institute_id = ? AND cd.course_order IS NOT NULL ORDER BY cd.course_order  limit 0,1";
		$query = $this->db->query($queryCmd,$instituteId);
		if(!($query->result_array())){
			  $queryCmd = "SELECT min(course_id)as course_id FROM `course_details` cd, listings_main lm WHERE lm.listing_type_id = cd.course_id and lm.status = 'live' and lm.listing_type='course' and cd.status='live' AND cd.institute_id = ?";
			$query = $this->db->query($queryCmd,$instituteId);
		}
		$courseId = 0;

		foreach($query->result_array()as $row){
			$courseId = $row['course_id'];
		}

		$response = array($courseId,'int');

		return $this->xmlrpc->send_response($response);
	}

	function getWikiDescriptionCaption($request){
		$parameters = $request->output_parameters();
		$instituteId = $parameters['0'];
		$courseId = $parameters['1'];

		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$instituteCaption = array();
		$courseCaption = array();

		$queryCmd  = "SELECT caption FROM listing_attributes_table WHERE listing_type_id = ? AND status = 'live' AND listing_type = 'institute'";
		$query = $this->db->query($queryCmd,$instituteId);
		foreach($query->result_array() as $caption ){
			$instituteCaption[] = array($caption,'struct');
		}

		$queryCmd  = "SELECT caption FROM listing_attributes_table WHERE listing_type_id = ? AND status = 'live' AND listing_type = 'course' ";
		$query = $this->db->query($queryCmd,$courseId);
		foreach($query->result_array() as $caption ){
			$courseCaption[] = array($caption,'struct');
		}
		$caption = array(array($instituteCaption,'struct'),array($courseCaption,'struct'));
		$response = array($caption,'struct');
		return $this->xmlrpc->send_response($response);

	}



	function getLiveListings($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$otherInstitutesCategory = $parameters['3'];
		$allDataFlag = $parameters['4'];
                $isRequestedfromSearch = $parameters['5'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = 'select max(version) as version from listings_main where listing_type = ? and listing_type_id= ? and status = "live" ';
		
		$query =  $this->db->query($queryCmd,array($listing_type,$type_id));
		foreach ($query->result() as $row){
			$version = $row->version;
		}
		if($version >= 0){
			switch($listing_type){
				case 'course':
					$institute_status ="'live'";
                                        $courseEditFlag = 0;
					$dataArr = $this->getCourseDetailsByVersion($this->db, $type_id,$version, $courseEditFlag, $institute_status,$otherInstitutesCategory,$allDataFlag, $isRequestedfromSearch);
					break;
				case 'institute':
					$courseStatus ="'live'";
					$dataArr = $this->getInstituteDetailsByVersion($this->db,$type_id,$version,$courseStatus,$otherInstitutesCategory,$allDataFlag, $isRequestedfromSearch);					break;
				case 'scholarship':
					// $dataArr = $this->getAllScholarshipDetails($type_id);
					break;
				case 'notification':
					// $dataArr = $this->getAllNotificationDetails($type_id);
					break;

			}
			$response = $dataArr;
		    return $this->xmlrpc->send_response($response);
		}
		else{
			$dataArr  =array('failure','string');
			return $this->xmlrpc->send_response($dataArr);
		}
	}

	function getListingForEdit($request){
		
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$getLiveVersion = $parameters['3'];
                $isInstituteEditCase = $parameters['4'];
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$queryCmd = 'select max(version) as version from listings_main where listing_type = ? and listing_type_id= ? and status in ("live","draft","queued") ';

                if(!empty($getLiveVersion) && $getLiveVersion =='getLiveVersion') {
			$queryCmd = 'select version from listings_main where listing_type = ? and listing_type_id= ? and status = "live"';
                }
		$query =  $this->db->query($queryCmd,array($listing_type,$type_id));
		foreach ($query->result() as $row){
			$version = $row->version;
		}
                // If no version found for this listing then show the 404 error page..
		if($version == "") {
                    return $this->xmlrpc->send_response('NO_SUCH_LISTING_FOUND_IN_DB');
                }

		switch($listing_type){
			case 'course':
				$institute_status = "'live','draft','queued'";
                                if(!empty($getLiveVersion) && $getLiveVersion =='getLiveVersion') {
					$institute_status = "'live'";
                                }
                                $courseEditFlag = 1;
				$dataArr = $this->getCourseDetailsByVersion($this->db, $type_id, $version, $courseEditFlag, $institute_status);
                                // error_log("getCourseDetailsByVersion: ".print_r($dataArr,true),3,'/home/infoedge/Desktop/log.txt');
				break;
			case 'institute':
				$courseStatus = "'live','draft','queued'";
                                if(!empty($getLiveVersion) && $getLiveVersion =='getLiveVersion') {
					$courseStatus = "'live'";
                                }
				$dataArr = $this->getInstituteDetailsByVersion($this->db, $type_id, $version, $courseStatus, '', 0, 0, $isInstituteEditCase);
				break;
		}

                // error_log("editResponse: ".print_r($dataArr,true),3,'/home/infoedge/Desktop/log.txt');
		return $this->xmlrpc->send_response($dataArr);
	}


	function updateCourseStatus($db, $course_id, $old_status, $new_status,$notThisVersion = -1,$extraFields=array()){ ////SQL Injection Update 
		$this->db = $this->_loadDatabaseHandle('write');
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : updateCourseStatus');
                // error_log("\n\n In updateCourseStatus for courseid : $course_id with old_status = $old_status, new_status = $new_status, notThisVersion : $notThisVersion",3,'/home/infoedge/Desktop/log.txt');
            
		$queryCmd = 'select version from listings_main where listing_type="course" and listing_type_id = ? and  version != ? and status = ? ';
		$query = $this->db->query($queryCmd,array($course_id,$notThisVersion,$old_status));
		foreach($query->result() as $row){
			$version =  $row->version;
		}
		if(isset($version) && $version>0){
			if($extraFields !=array()){
				$extraClause = ' ,comments="'.$extraFields['comments'].'" ,approvedBy="'.$extraFields['approvedBy'].'"';
			}else{
				$extraClause = '';
			}
			
			if(!empty($this->user_id) && is_numeric($this->user_id))
				$extraClause .= ' ,editedBy = '.$this->user_id;
				
			//Update listings_main table
			$queryCmd = 'update listings_main set status="'.$new_status.'",	last_modify_date = NOW()  '.$extraClause.' where  version = "'.$version.'" and  listing_type="course" and listing_type_id ='.$course_id.' and status = "'.$old_status.'"';
			$query = $this->db->query($queryCmd);
			//Update course table
			$queryCmd = 'update course_details set status="'.$new_status.'" where version = "'.$version.'" and course_id="'.$course_id.'"' ;
			$query = $this->db->query($queryCmd);
			//Update listing_attributes_table table
			$queryCmd = 'update listing_attributes_table set status="'.$new_status.'" where listing_type="course" and version = "'.$version.'" and listing_type_id="'.$course_id.'"';
			$query = $this->db->query($queryCmd);
			//Update listing_media_table table
			$queryCmd = 'update listing_media_table set status="'.$new_status.'" where type="course" and version = "'.$version.'" and type_id="'.$course_id.'"';
			$query = $this->db->query($queryCmd);
			//Update listingExamMap table
			$queryCmd = 'update listingExamMap set status="'.$new_status.'" where type="course" and version = "'.$version.'" and typeId="'.$course_id.'"';
			$query = $this->db->query($queryCmd);
			//Update othersExamTable table
			$queryCmd = 'update othersExamTable set status="'.$new_status.'" where listingType="course" and version = "'.$version.'" and listingTypeId="'.$course_id.'"';
			$query = $this->db->query($queryCmd);
			//Update course_mapping_table table
			//$queryCmd = 'update course_mapping_table set status="'.$new_status.'" where version = "'.$version.'" and course_id ="'.$course_id.'"';
			//$query = $this->db->query($queryCmd);
						
			$queryCmd = 'update clientCourseToLDBCourseMapping set status="'.$new_status.'" where version = "'.$version.'" and clientCourseID ="'.$course_id.'"';
			$query = $this->db->query($queryCmd);
			
			//Update course_attributes table
			$queryCmd = 'update course_attributes set status="'.$new_status.'" where version = "'.$version.'" and course_id ="'.$course_id.'"';
			$query = $this->db->query($queryCmd);
			//Update course_features table
			$queryCmd = 'update course_features set status="'.$new_status.'" where version = "'.$version.'" and listing_id ="'.$course_id.'"';
			$query = $this->db->query($queryCmd);

			// Update comapny_logo_mapping table
			$queryCmd = 'update company_logo_mapping set status="'.$new_status.'" where listing_type="course" and version = "'.$version.'" and listing_id="'.$course_id.'"';
			$query = $this->db->query($queryCmd);
			//Update header_image table
			$queryCmd = 'update header_image set status="'.$new_status.'" where listing_type="course" and version = "'.$version.'" and listing_id="'.$course_id.'"';
			$query = $this->db->query($queryCmd);


                        // Updating Course's Location Info and Location Attributes info..
                        $this->updateCourseLocationContactInfo($this->db, $new_status, $old_status, $version, $course_id, $notThisVersion);

                        // $queryCmd = "update listing_contact_details set status = '".$new_status."' where institute_location_id in (".$institute_location_ids.") AND listing_type = 'course' AND listing_type_id = '".$course_id."'";
                        // $query = $this->db->query($queryCmd);


			return $version;
		}
		else{
			return 0;
		}
	}
	

	function editInstitute($request){
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$institute_id = $parameters['1'];
		//        $data = $parameters['2'];
		$data = unserialize(base64_decode($parameters['2']));
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		//error_log($institute_id);
		//error_log(print_r($data,true));

		$draftVersion = 0;
		$queuedVersion = 0;
		$liveVersion = 0;
		if($data['dataFromCMS'] == '1'){
			$draftVersion = $this->updateInstituteStatus($this->db,$institute_id,'draft','history');
			$queuedVersion= $this->updateInstituteStatus($this->db,$institute_id,'queued','history');
			$status = 'draft';
		}
		else{
			$draftVersion = $this->updateInstituteStatus($this->db,$institute_id,'draft','history');
			$queuedVersion= $this->updateInstituteStatus($this->db,$institute_id,'queued','history');
			$status = 'draft';
		}
		//error_log("live $liveVersion");
		//error_log("draft $draftVersion");
				
		$queryCmd = 'select max(version) as version from listings_main where listing_type = "institute" and listing_type_id= ?';
		$query =  $this->db->query($queryCmd,$institute_id);
		foreach ($query->result() as $row){
			$version = $row->version;
		}
		$new_version =  $version+1;
		if($draftVersion > 0 || $queuedVersion > 0){
			$old_version = $draftVersion>$queuedVersion?$draftVersion:$queuedVersion;
		}
		else{
			$queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "institute" and listing_type_id= ?';
			$query =  $this->db->query($queryCmd,$institute_id);
			foreach ($query->result() as $row){
				$liveVersion = $row->version;
			}
			if($liveVersion > 0){
				$old_version = $liveVersion;
			}
			else{
				$old_version = 1;
			}
		}
		//error_log($new_version);
		//error_log($old_version);
		$response = $this->replicateInstitute($this->db,$institute_id,$old_version,$new_version,$status,$data);
		//error_log($old_version);
		
		//save mandatory comments
		$commentResponse  = $this->saveMandatoryComments(array($data['cmsTrackUserId'],$data['cmsTrackListingId'],$data['cmsTrackTabUpdated'],$data['mandatory_comments']));

		$response =array($response,'int');
		return $this->xmlrpc->send_response($response);
	}

	function saveMandatoryComments($commentData){
		$this->db = $this->_loadDatabaseHandle('write');
		
		$userid=$commentData['0'];
		$listing_id = $commentData['1'];
		$tab= $commentData['2'];
		$comments= $commentData['3'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$queryCmd = "insert into listingCMSUserTracking (userId,listingId,tabUpdated,comments) values (?,?,?,?)";
		
		$query =  $this->db->query($queryCmd,array($userid,$listing_id,$tab,$comments));
		
		return $this->xmlrpc->send_response(array(1,'int'));
	}

	function replicateInstitute($db,$institute_id,$old_version,$new_version,$status,$formVal,$pageName="")
	{ //SQL Injection insert
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : replicateInstitute');
		$this->db = $this->_loadDatabaseHandle('write');
		//error_log("LINDEX".print_r($formVal,true));
		if(isset($formVal['contact_details_id']) && $formVal['contact_details_id'] >0){
			$cid = $formVal['contact_details_id'];
		}
		else{
			if ($pageName != "media") {
				// $cid = $this->ListingModel->addContactDetails($this->db,$formVal);
			}
		}

		$queryCmd = "select * from listings_main where listing_type='institute' and listing_type_id = ? and version = ?";
		$query = $this->db->query($queryCmd,array($institute_id,$old_version));
		foreach($query->result_array() as $row){
			$data =array();
			$format = 'DATE_ATOM';
			$data = array(
	                'listing_id'=>'',
	                'listing_type'=>'institute',
	                'listing_type_id'=>$institute_id,
	                'listing_title'=>isset($formVal['institute_name'])?$formVal['institute_name']:$row['listing_title'],
	                'hiddenTags'=>isset($formVal['hiddenTags'])?$formVal['hiddenTags']:$row['hiddenTags'],
	                'last_modify_date'=>standard_date($format,time()),
	                'requestIP'=>isset($formVal['requestIP'])?$formVal['requestIP']:$row['requestIP'],
	                'contact_details_id' => isset($cid)?$cid:$row['contact_details_id'],
			'listing_seo_url'=>(!empty($formVal['listing_seo_url']))?$formVal['listing_seo_url']:$row['listing_seo_url'],
			'listing_seo_title'=>(!empty($formVal['listing_seo_title']))?$formVal['listing_seo_title']:$row['listing_seo_title'],	'listing_seo_description'=>(!empty($formVal['listing_seo_description']))?$formVal['listing_seo_description']:$row['listing_seo_description'],
			'listing_seo_keywords'=>(!empty($formVal['listing_seo_keywords']))?$formVal['listing_seo_keywords']:$row['listing_seo_keywords'],
	                'moderation_flag' => $moderated,
	                'status'=>$status,
	                'version'=>$new_version,
	                'editedBy'=>isset($formVal['editedBy'])?$formVal['editedBy']:$row['editedBy'],
			'username'=>$row['username'],
			'subscriptionId'=>(isset($formVal['subscriptionId'])&& $formVal['subscriptionId']!=0 && $pageName!="media")?$formVal['subscriptionId']:$row['subscriptionId'],
			'pack_type'=>(isset($formVal['packType']) && $pageName!='media' && $formVal['packType']!=0 && isset($formVal['subscriptionId'])&& $formVal['subscriptionId']!=0 && $pageName!="media")?$formVal['packType']:$row['packType']
				);
			if((isset($formVal['packType']) && $pageName!='media' && $formVal['packType']!=0 ) && !(isset($formVal['subscriptionId'])&& $formVal['subscriptionId']!=0 && $pageName!="media"))
			{
				error_log("Listing Pack_TYPE & SUBS MISMATCH ".$formVal['subscriptionId']." ".$formVal['packType']);
			}
			$data = superImpose($row,$data);
			$queryCmd = $this->db->insert_string('listings_main',$data);
			$query = $this->db->query($queryCmd);
		}
		$queryCmd = "select * from institute where institute_id = ? and version = ?";
		$query = $this->db->query($queryCmd,array($institute_id,$old_version));
                $institute_request_brochure_link = trim($formVal['institute_request_brochure_link']);
		$institute_request_brochure_link_year = trim($formVal['institute_request_brochure_link_year']);					
		foreach($query->result_array() as $row){
			$logo_link = isset($formVal['logoArr']['thumburl'])?$formVal['logoArr']['thumburl']:$row['logo_link'];
			$featured_panel_link = isset($formVal['panelArr']['thumburl'])?$formVal['panelArr']['thumburl']:$row['featured_panel_link'];

			$data =array();
			$data = array(
                    'id'=>'',
                    'institute_id'=>$institute_id,
                    'logo_link'=>$logo_link,
                    'featured_panel_link'=>$featured_panel_link,
                    'institute_name'=>isset($formVal['institute_name'])?$formVal['institute_name']:$row['institute_name'],
                    'establish_year'=>isset($formVal['establish_year'])?$formVal['establish_year']:$row['establish_year'],
            		'abbreviation'=>isset($formVal['abbreviation'])?$formVal['abbreviation']:$row['abbreviation'],
            		'aima_rating'=>isset($formVal['aima_rating'])?$formVal['aima_rating']:$row['aima_rating'],
            		'usp'=>isset($formVal['usp'])?$formVal['usp']:$row['usp'],
					'admission_counseling'=>isset($formVal['admission_counseling'])?$formVal['admission_counseling']:$row['admission_counseling'],
                    'visa_assistance'=>isset($formVal['visa_assistance'])?$formVal['visa_assistance']:$row['visa_assistance'],
                    'certification'=>isset($formVal['affiliated_to'])?$formVal['affiliated_to']:$row['certification'],
                    'contact_details_id' => isset($cid)?$cid:$row['contact_details_id'],
            		'admission_counseling' => $formVal['admission_counseling'],
            		'visa_assistance' => $formVal['visa_assistance'],
                    'status'=>$status,
                    'version'=>$new_version,
                    'institute_request_brochure_link'=>!empty($institute_request_brochure_link)?$institute_request_brochure_link:$row['institute_request_brochure_link'],
		    'institute_request_brochure_year'=>!empty($institute_request_brochure_link_year)?$institute_request_brochure_link_year:$row['institute_request_brochure_year'],	
                    'source_type'=>array_key_exists('source_type',$formVal)?$formVal['source_type']:$row['source_type'],
                    'source_name'=>array_key_exists('source_name',$formVal)?$formVal['source_name']:$row['source_name']
			);
			$data = superImpose($row,$data);
			if($formVal['request_brochure_link_delete'] == 'delete') {
				$data['institute_request_brochure_link'] = ""; 
				$data['institute_request_brochure_year'] = 0; 
			}	
			$queryCmd = $this->db->insert_string('institute',$data);
			$query = $this->db->query($queryCmd);
		}
                $this->ListingModel->replicateInstituteLocationContactData($this->db, $institute_id, $old_version, $new_version, $status, $formVal);

		//Replicate institute join table
		$queryCmd = "select * from institute_join_reason where institute_id = ? and version = ?";
		$query = $this->db->query($queryCmd,array($institute_id,$old_version));
		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$photo_title = $row['photo_title'];
				$photo_url = $row['photo_url'];
				$details = $row['details'];
			}
		}
		if($query->num_rows() > 0 || ($formVal['details']!='')){
			$data = array(
		                'institute_id'=>$institute_id,
//		                'photo_title'=>$formVal['photo_title']!=''?$formVal['photo_title']:$photo_title,
		                'photo_url'=>$formVal['photoArr']['url']!=''?$formVal['photoArr']['url']:$photo_url,
		                'details'=>$formVal['details']!=''?$formVal['details']:$details,
		                'status'=>$status,
		                'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('institute_join_reason',$data);
			$query = $this->db->query($queryCmd);
		}
		//FIXME: wiki replication - first fetch ; construct new array and add this to addWikiSections
		$currentWikiSections = $this->ListingModel->getWikiDetailsForListing($this->db,'institute',$institute_id,$old_version);
		$newWikiData =  $this->getEditedWikiData($currentWikiSections,$formVal);

		$this->ListingModel->addWikiSections($this->db,$institute_id,'institute',$newWikiData,$new_version,$status);
		//error_log("came till here ".print_r($queryCmd,true),3,'/home/naukri/Desktop/log.txt');

		//MEDIA Replication OR Updation
		$currentMedia = $this->ListingModel->getMediaForListingVersion($this->db,'institute',$institute_id,$old_version);

		if(isset($formVal['media_details'])){
			foreach($formVal['media_details'] as $mediaType=>$mediaElem){
				foreach($mediaElem as $mediaId=>$action){
					if($action == 'removal'){
						unset($currentMedia[$mediaType][$mediaId]);
					}
					if($action == 'addition'){
						$currentMedia[$mediaType][$mediaId] ='addition';
					}
				}
			}
		}
		$this->ListingModel->addMediaElements($this->db,'institute',$institute_id,$currentMedia,$new_version,$status);

		//Company_Mapping Replication OR Updation
		if( isset($formVal['company_details']))
		{$this->ListingModel->addTopCompany($this->db,'institute',$institute_id,$formVal['company_details'],$old_version,$new_version,$status,1);}
		else
		{$this->ListingModel->addTopCompany($this->db,'institute',$institute_id,0,$old_version,$new_version,$status,0);}


		//Header_Image Replication OR Updation
		if( isset($formVal['header_details']))
		{$this->ListingModel->addTopHeader($this->db,'institute',$institute_id,$formVal['header_details'],$old_version,$new_version,$status,1);}
		else
		{$this->ListingModel->addTopHeader($this->db,'institute',$institute_id,0,$old_version,$new_version,$status,0);}

		return 1;
	}

        
	function getEditedWikiData($currentWikiSections,$formVal){
		$newWikiData = array();
		$segregatedFields = systemAndUserFieldsDataSegregation($currentWikiSections);
		foreach($segregatedFields['systemFieldsArr'] as $key=>$val){
			$wiki_check_key = trim($formVal['wiki'][$val['key_name']]);

			if(!empty($wiki_check_key)){
                		if($formVal['wiki'][$val['key_name']] == "<br>") {
                			unset($formVal['wiki'][$val['key_name']]);
                			continue;
                 	}
				//add new data instead of previous one
				$newWikiData[$val['key_name']] = $formVal['wiki'][$val['key_name']];
				unset($formVal['wiki'][$val['key_name']]);
			}
			else{
				//copy old data for this system field
                		$newWikiData[$val['key_name']] = $val['attributeValue'];
			}
		}
		foreach($formVal['wiki'] as $key=>$val){
			if($key != 'user_fields'){
				$newWikiData[$key] = $val;
				unset($formVal['wiki'][$key]);
			}
		}
		if(isset($formVal['wiki']['user_fields'])){
			//replace with new user created fields
			$newWikiData['user_fields']= $formVal['wiki']['user_fields'];
		}
		else{
			//copy old data for user field
			$i= 0;
			foreach($segregatedFields['userFieldsArr'] as $oneField){
				$newWikiData['user_fields'][$i]['caption'] =$oneField['caption'];
				$newWikiData['user_fields'][$i]['value'] =$oneField['attributeValue'];
				$i++;
			}
		}
		error_log(print_r($newWikiData,true));
		return $newWikiData;
	}

	function editCourse($request){
		//SQL Injection Code Not in use
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$courseId = $parameters['1'];
		//        $data = $parameters['2'];
		$data = unserialize(base64_decode($parameters['2']));
		
		$data['courseTitle'] = html_entity_decode($data['courseTitle']);
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		//        error_log("in editCourse ".print_r($data,true),3,'/home/naukri/Desktop/log.txt');

		$draftVersion = 0;
		$liveVersion = 0;
		if($data['dataFromCMS'] == '1'){
			$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
			$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
			//            $liveVersion = $this->updateCourseStatus($this->db,$courseId,'live','history');
			//            $status = 'live';
			$status = 'draft';
		}
		else{
			$draftVersion = $this->updateCourseStatus($this->db,$courseId,'draft','history');
			$queuedVersion = $this->updateCourseStatus($this->db,$courseId,'queued','history');
			$status = 'draft';
		}
		//        error_log("live $liveVersion");
		//        error_log("draft $draftVersion");
		$queryCmd = 'select max(version) as version from listings_main where listing_type = "course" and listing_type_id= ?';
		$query =  $this->db->query($queryCmd,$courseId);
		foreach ($query->result() as $row){
			$version = $row->version;
		}
		$new_version =  $version+1;
		if($draftVersion > 0 || $queuedVersion > 0){
			$old_version = $draftVersion>$queuedVersion?$draftVersion:$queuedVersion;
		}
		else{
			$queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "course" and listing_type_id= ?';
			$query =  $this->db->query($queryCmd,$courseId);
			foreach ($query->result() as $row){
				$liveVersion = $row->version;
			}
			if($liveVersion > 0){
				$old_version = $liveVersion;
			}
			else{
				$old_version = 1;
			}
		}
		$response = $this->replicateCourse($this->db,$courseId,$old_version,$new_version,$status,$data);

		//save mandatory comments
		$commentResponse  = $this->saveMandatoryComments(array($data['cmsTrackUserId'],$data['cmsTrackListingId'],$data['cmsTrackTabUpdated'],$data['mandatory_comments']));

		$response =array($response,'int');
		return $this->xmlrpc->send_response($response);
	}

		function replicateCourse($db,$course_id,$old_version,$new_version,$status,$formVal,$pageName='')
	{ //SQL Injection Code Not in use
                //error_log('aditya_in_gandh2');
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : replicateCourse');
		
		$this->db = $this->_loadDatabaseHandle('write');
		$calledFromMediaPage=0;//to check if this fucntion has been called during media page associations
                // Please increment this variable in case more media associations are added in future

                //error_log("LINDEX".print_r($formVal,true));
		$tests = $formVal['tests'];
				if( ($formVal['contact_name'] != '') ||
                    ($formVal['contact_main_phone'] != '') ||
                    ($formVal['contact_cell'] != '') ||
                    ($formVal['contact_email'] != '') || array_key_exists('relicate_features',$formVal)){                                    
                        $cid = $this->ListingModel->addContactDetails($this->db, $formVal, 'course', $course_id, $status, $new_version,0,$old_version); // Global Contact Details as Course Level.
                }else{
                    $cid = $formVal['contact_details_id'];
                }


		$queryCmd = "select * from listings_main where listing_type='course' and listing_type_id =? and version = ?";
		$query = $this->db->query($queryCmd,array($course_id,$old_version));
		foreach($query->result_array() as $row){
			$data =array();
			$format = 'DATE_ATOM';
			$data = array(
                'listing_id' => '',
                'listing_type'=>'course',
                'listing_type_id'=>$course_id,
                'listing_title'=>isset($formVal['courseTitle'])?$formVal['courseTitle']:$row['listing_title'],
                'hiddenTags'=>isset($formVal['hiddenTags'])?$formVal['hiddenTags']:$row['hiddenTags'],
                'tags'=>isset($formVal['courseType'])?$formVal['courseType']:$row['tags'],
                'last_modify_date'=>standard_date($format,time()),
                'moderation_flag' => $moderated,
                'requestIP'=>isset($formVal['requestIP'])?$formVal['requestIP']:$row['requestIP'],
                'contact_details_id' => isset($cid)?$cid:$row['contact_details_id'],
                'status'=>$status,
                'version'=>$new_version,
	        'listing_seo_url'=>(!empty($formVal['listing_seo_url']))?$formVal['listing_seo_url']:$row['listing_seo_url'],
		'listing_seo_title'=>(!empty($formVal['listing_seo_title']))?$formVal['listing_seo_title']:$row['listing_seo_title'],	'listing_seo_description'=>(!empty($formVal['listing_seo_description']))?$formVal['listing_seo_description']:$row['listing_seo_description'],
		'listing_seo_keywords'=>(!empty($formVal['listing_seo_keywords']))?$formVal['listing_seo_keywords']:$row['listing_seo_keywords'],
                'editedBy'=>isset($formVal['editedBy'])?$formVal['editedBy']:$row['editedBy'],
                'subscriptionId'=>(isset($formVal['subscriptionId']) && $formVal['subscriptionId']!=0 && $pageName!='media')?$formVal['subscriptionId']:$row['subscriptionId'],
                'username'=>$row['clientId'],
                'pack_type'=>(isset($formVal['packType']) && $pageName!='media' && $formVal['packType']!=0 && isset($formVal['subscriptionId'])&& $formVal['subscriptionId']!=0 && $pageName!="media")?$formVal['packType']:$row['packType']
			);
			if((isset($formVal['packType']) && $pageName!='media' && $formVal['packType']!=0 ) && !(isset($formVal['subscriptionId'])&& $formVal['subscriptionId']!=0 && $pageName!="media"))
			{
//				error_log("Listing Pack_TYPE & SUBS MISMATCH ".$formVal['subscriptionId']." ".$formVal['packType']);
			}

			$data = superImpose($row,$data);
			$queryCmd = $this->db->insert_string('listings_main',$data);
			$query = $this->db->query($queryCmd);
		}

		$queryCmd = "select * from course_details where course_id = ? and version = ?";
		$query = $this->db->query($queryCmd,array($course_id,$old_version));
		foreach($query->result_array() as $row){
			if(isset($formVal['form_upload'])){
				if($formVal['form_upload'] == 'upload'){
					$is_application_uploaded ='yes';
					$application_form_url = isset($formVal['ApplicationDocArr']['url'])?$formVal['ApplicationDocArr']['url']:$row['application_form_url'];
				}
				else{
					$is_application_uploaded ='no';
					$application_form_url = isset($formVal['ApplicationDocArr']['url'])?$formVal['ApplicationDocArr']['url']:$row['application_form_url'];
				}
			}
			else{
				$is_application_uploaded =$row['is_application_uploaded'];
				$application_form_url = $row['application_form_url'];
			}

			if(isset($formVal['duration'])){
				//Intermediate Course Duration
				$tempDuration = preg_replace('/[^A-Za-z0-9\-\/\.]/', ' ', $formVal['duration']);
				$intermediateDuration = exec("./duration.sh '".$tempDuration."'");
				if(strlen($intermediateDuration) <= 0){
					$intermediateDuration = $formVal['duration'];
				}
			}
			$data =array();
			$data = array(
                'id'=>'',
                'courseTitle'=>isset($formVal['courseTitle'])?$formVal['courseTitle']:$row['courseTitle'],
                'duration'=>isset($formVal['duration'])?$formVal['duration']:$row['duration'],
                'duration_value'=>isset($formVal['duration_value'])?$formVal['duration_value']:$row['duration_value'],
                'duration_unit'=>isset($formVal['duration_unit'])?$formVal['duration_unit']:$row['duration_unit'],
                'fees'=>isset($formVal['fees'])?$formVal['fees']:$row['fees'],
                'fees_value'=>isset($formVal['fees_value'])?$formVal['fees_value']:$row['fees_value'],
                'fees_unit'=>isset($formVal['fees_unit'])?$formVal['fees_unit']:$row['fees_unit'],
                'course_type'=>isset($formVal['courseType'])?$formVal['courseType']:$row['course_type'],
                'course_level'=>isset($formVal['courseLevel'])?$formVal['courseLevel']:$row['course_level'],
                'course_level_1'=>isset($formVal['courseLevel_1'])?$formVal['courseLevel_1']:$row['course_level_1'],
                'course_level_2'=>isset($formVal['courseLevel_2'])?$formVal['courseLevel_2']:$row['course_level_2'],
			//'approvedBy'=>isset($formVal['approvedBy'])?$formVal['approvedBy']:$row['approvedBy'],
                'is_application_uploaded'=>$is_application_uploaded,
                'application_form_url'=>$application_form_url,
            	'seats_total'=>isset($formVal['seats_total'])?$formVal['seats_total']:$row['seats_total'],
                'seats_general'=>isset($formVal['seats_general'])?$formVal['seats_general']:$row['seats_general'],
                'seats_reserved'=>isset($formVal['seats_reserved'])?$formVal['seats_reserved']:$row['seats_reserved'],
                'seats_management'=>isset($formVal['seats_management'])?$formVal['seats_management']:$row['seats_management'],
                'date_form_submission'=>isset($formVal['date_form_submission'])?date('Y-m-d',strtotime($formVal['date_form_submission'])):$row['date_form_submission'],
                'date_result_declaration'=>isset($formVal['date_result_declaration'])?date('Y-m-d',strtotime($formVal['date_result_declaration'])):$row['date_result_declaration'],
                'date_course_comencement'=>isset($formVal['date_course_comencement'])?date('Y-m-d',strtotime($formVal['date_course_comencement'])):$row['date_course_comencement'],
                'contact_details_id' => isset($cid)?$cid:$row['contact_details_id'],
            	'course_order' => $row['course_order'],
                'status'=>$status,
                'version'=>$new_version,
                'course_request_brochure_link'=>!empty($formVal['course_request_brochure_link'])?$formVal['course_request_brochure_link']:$row['course_request_brochure_link'],
		'course_request_brochure_year'=>!empty($formVal['course_request_brochure_link_year'])?$formVal['course_request_brochure_link_year']:$row['course_request_brochure_year'],
                'source_type'=>array_key_exists('source_type',$formVal)?$formVal['source_type']:$row['source_type'],
                'source_name'=>array_key_exists('source_name',$formVal)?$formVal['source_name']:$row['source_name'],
		'feestypes'=>array_key_exists('feestypes',$formVal)?$formVal['feestypes']:$row['feestypes']
			);
			
			$data = superImpose($row,$data);
			if(stripos($data['date_form_submission'],'1970') === false) {
			}else {
				$data['date_form_submission'] = "0000-00-00 00:00:00";
			}

			if(stripos($data['date_result_declaration'],'1970') === false) {
			}else {
				$data['date_result_declaration'] = "0000-00-00 00:00:00";
			}

			if(stripos($data['date_course_comencement'],'1970') === false) {
			}else {
				$data['date_course_comencement'] = "0000-00-00 00:00:00";
			}

                        if($formVal['request_brochure_link_delete'] == 'delete') {
				$data['course_request_brochure_link'] = "";
				$data['course_request_brochure_year'] = 0;
			}
	
			$queryCmd = $this->db->insert_string('course_details',$data);
			$query = $this->db->query($queryCmd);
		}

                // Inserting in course_location_attribute table..
                $this->ListingModel->addCourseLocationAttributes($this->db, $course_id, $formVal['institute_location_ids'], $formVal['locationFeeInfo'], $formVal['head_ofc_location_id'], $new_version,$formVal['important_date_info_location'],$formVal['course_contact_details_locationwise']);

		// add wiki wala funda from institute
		$currentWikiSections = $this->ListingModel->getWikiDetailsForListing($this->db,'course',$course_id,$old_version);
		$newWikiData =  $this->getEditedWikiData($currentWikiSections,$formVal);
		$this->ListingModel->addWikiSections($this->db,$course_id,'course',$newWikiData,$new_version,$status);


		//MEDIA Replication OR Updation
		$currentMedia = $this->ListingModel->getMediaForListingVersion($this->db,'course',$course_id,$old_version);

		if(isset($formVal['media_details'])){
			$calledFromMediaPage++;
                        foreach($formVal['media_details'] as $mediaType=>$mediaElem){
				foreach($mediaElem as $mediaId=>$action){
					if($action == 'removal'){
						unset($currentMedia[$mediaType][$mediaId]);
					}
					if($action == 'addition'){
						$currentMedia[$mediaType][$mediaId] ='addition';
					}
				}
			}
		}
		$this->ListingModel->addMediaElements($this->db,'course',$course_id,$currentMedia,$new_version,$status);

		//Company_Mapping Replication OR Updation
		if( isset($formVal['company_details']))
		{
                    $calledFromMediaPage++;
                    $this->ListingModel->addTopCompany($this->db,'course',$course_id,$formVal['company_details'],$old_version,$new_version,$status,1);
                }
                else
		$this->ListingModel->addTopCompany($this->db,'course',$course_id,0,$old_version,$new_version,$status,0);


                // Annie redemption By Bhuvnesh & Aakash (course_mapping_table) funda
                $mapCheck= array(0,0,0,0,0);
                                               
	 	for($j=1;$j<=LDB_COURSE_MAPPING_LIMIT;$j++)
        {
        	$mapCheck[$j-1]= $formVal['courseMap_'.$j];
        	
        	if($mapCheck[$j-1])
        	{
        		$insert_data = array(
        								'LDBCourseID' => $mapCheck[$j-1],
        								'clientCourseID' => $course_id,
        								'status' => $status,
        								'version' => $new_version	
        							);
        		$this->db->insert('clientCourseToLDBCourseMapping',$insert_data);					
        	}
        }
        
		if ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal))
        {
				
				$queryCmd = "SELECT * 
							 FROM clientCourseToLDBCourseMapping 
							 WHERE version = ? 
							 AND clientCourseID =?";
				$query = $this->db->query($queryCmd,array($old_version,$course_id));
				
				foreach ($query->result() as $row)
				{
					$insert_data = array(
									'clientCourseID'=>$row->clientCourseID,
									'LDBCourseID'=>$row->LDBCourseID,
									'version'=>$new_version,
									'status'=>$status
								);
					 $this->db->insert('clientCourseToLDBCourseMapping',$insert_data);
				 }
         }                

                if($formVal['accreditedBy']!=''){
			$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AccreditedBy','".$formVal['accreditedBy']."','$status',$new_version)";
			$this->db->query($queryCmd);
		}
		
		elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){

                        $queryCmd = "select * from course_attributes where version = ? and course_id = ? and attribute= 'AccreditedBy' ";
                        $query = $this->db->query($queryCmd,array($old_version,$course_id,));
                        foreach ($query->result() as $row){

                          $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);                        
                        $queryy = $this->db->query($queryC);

                        }
                }
   //Affiliated To
		if($formVal['affiliatedTo']!=''){
			$affiliatedTo = array();
			$affiliatedTo = explode(',',$formVal['affiliatedTo']);
			if(in_array('INUNI',$affiliatedTo)){
				$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AffiliatedToIndianUni','yes','$status',$new_version)";
				$this->db->query($queryCmd);
			}
			if(in_array('FOUNI',$affiliatedTo)){
				$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AffiliatedToForeignUni','yes','$status',$new_version)";
				$this->db->query($queryCmd);
			}
			if(in_array('DEUNI',$affiliatedTo)){
				$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AffiliatedToDeemedUni','yes','$status',$new_version)";
				$this->db->query($queryCmd);
			}
			if(in_array('AUTO',$affiliatedTo)){
				$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AffiliatedToAutonomous','yes','$status',$new_version)";
				$this->db->query($queryCmd);
			}
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){ // no new value for affiliated replication funda

                        $queryCmd = "select * from course_attributes where version = ? and course_id = ? and attribute In ('AffiliatedToIndianUni','AffiliatedToForeignUni','AffiliatedToDeemedUni','AffiliatedToAutonomous')";
                        $query = $this->db->query($queryCmd,array($old_version,$course_id));
                        foreach ($query->result() as $row){

                            $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);
                        }
                   }

		if($formVal['affiliatedToIndianUniName']!='' && in_array('INUNI',$affiliatedTo)){
			$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AffiliatedToIndianUniName','".$formVal['affiliatedToIndianUniName']."','$status',$new_version)";
			$this->db->query($queryCmd);
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){

                        $queryCmd = "select * from course_attributes where version = ? and course_id =? and attribute= 'AffiliatedToIndianUniName'";
                        $query = $this->db->query($queryCmd,array($old_version,$course_id));
                        foreach ($query->result() as $row){

                            $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);
                        }


                }

		if($formVal['affiliatedToForeignUniName']!='' && in_array('FOUNI',$affiliatedTo)){
			$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AffiliatedToForeignUniName','".$formVal['affiliatedToForeignUniName']."','$status',$new_version)";
			$this->db->query($queryCmd);
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){
                        $queryCmd = "select * from course_attributes where version = ? and course_id =? and attribute= 'AffiliatedToForeignUniName'";
                        $query = $this->db->query($queryCmd,array($old_version,$course_id));
                        foreach ($query->result() as $row){

                            $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);
                        }

                }

		if($formVal['otherEligibilityCriteria']!=''){
			$formVal['otherEligibilityCriteria'] = addslashes($formVal['otherEligibilityCriteria']);
			$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'otherEligibilityCriteria','".$formVal['otherEligibilityCriteria']."','$status',$new_version)";
			$this->db->query($queryCmd);
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){

                        $queryCmd = "select * from course_attributes where version = ? and course_id = ? and attribute= 'otherEligibilityCriteria'";
                        $query = $this->db->query($queryCmd,array($old_version,$course_id));
                        foreach ($query->result() as $row){

                            $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);
                        }

                }

		//New changes
		if($formVal['approvedBy']!=''){
			$approvedBy = array();
			$approvedBy = explode(',',$formVal['approvedBy']);
			if(in_array('AICTE',$approvedBy)){
				$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'AICTEStatus','yes','$status',$new_version)";
				$this->db->query($queryCmd);
			}
			if(in_array('UGC',$approvedBy)){
				$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'UGCStatus','yes','$status',$new_version)";
				$this->db->query($queryCmd);
			}
			if(in_array('DEC',$approvedBy)){
				$queryCmd = "INSERT INTO course_attributes(course_id,attribute,value,status,version) VALUES($course_id,'DECStatus','yes','$status',$new_version)";
				$this->db->query($queryCmd);
			}
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){
                        $queryCmd = "select * from course_attributes where version = ? and course_id = ? and attribute In ('AICTEStatus','UGCStatus','DECStatus') ";
                        $query = $this->db->query($queryCmd,array($old_version,$course_id));
                        foreach ($query->result() as $row){

                          $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);

                        }


                }
		//Salient Features
		$query = "SELECT feature_id,feature_name,value FROM salient_features";
		$queryResult = $this->db->query($query);
		$salientFeatures = array();
		foreach($queryResult->result_array() as $row){
			$salientFeatures[$row['feature_name']][$row['value']] = $row['feature_id'];
		}
		//Query Command for Inserting salient features
		$courseSalientFeatures = array();
		if($formVal['c_freeLaptop']!=''){
			array_push($courseSalientFeatures, $salientFeatures['freeLaptop'][$formVal['c_freeLaptop']]);
		}
		if($formVal['c_foreignStudy']!=''){
			array_push($courseSalientFeatures, $salientFeatures['foreignStudy'][$formVal['c_foreignStudy']]);
		}
		if($formVal['c_studyExchange']!=''){
			array_push($courseSalientFeatures, $salientFeatures['studyExchange'][$formVal['c_studyExchange']]);
		}
		if($formVal['c_jobAssurance']!=''){
			array_push($courseSalientFeatures, $salientFeatures['jobAssurance'][$formVal['c_jobAssurance']]);
		}
		if($formVal['c_dualDegree']!=''){
			array_push($courseSalientFeatures, $salientFeatures['dualDegree'][$formVal['c_dualDegree']]);
		}
		if($formVal['c_hostel']!=''){
			array_push($courseSalientFeatures, $salientFeatures['hostel'][$formVal['c_hostel']]);
		}
		if($formVal['c_transport']!=''){
			array_push($courseSalientFeatures, $salientFeatures['transport'][$formVal['c_transport']]);
		}
		if($formVal['c_freeTraining']!=''){
			array_push($courseSalientFeatures, $salientFeatures['freeTraining'][$formVal['c_freeTraining']]);
		}
		if($formVal['c_wifi']!=''){
			array_push($courseSalientFeatures, $salientFeatures['wifi'][$formVal['c_wifi']]);
		}
		if($formVal['c_acCampus']!=''){
			array_push($courseSalientFeatures, $salientFeatures['acCampus'][$formVal['c_acCampus']]);
		}
                if(array_key_exists('relicate_features',$formVal) && $formVal['relicate_features'] == 'YES') {
			$to_get_silent_exisitng = "select salient_feature_id from course_features where ".
                	"listing_id="."'".$course_id."'"." AND version=$old_version";
                	$query = $this->db->query($to_get_silent_exisitng);
                        foreach($query->result() as $row) {
				$courseSalientFeatures[] = $row->salient_feature_id;
                       }
                       //error_log('aditya'.print_r($courseSalientFeatures,true));
                }
		if(sizeof($courseSalientFeatures)){
			$queryCmd = "INSERT INTO course_features(listing_id,salient_feature_id,status,version) VALUES";
			foreach($courseSalientFeatures AS $feature){
				$queryCmd .= "($course_id,$feature,'$status',$new_version),";
			}
			$queryCmd = substr($queryCmd, 0, -1);
			$this->db->query($queryCmd);
		}

	$typeQuery = "SELECT institute_type FROM institute i, institute_courses_mapping_table icmt WHERE i.institute_id =  icmt.institute_id and icmt.course_id = $course_id order by i.version desc limit 1";
        $query = $this->db->query($typeQuery);
        $result = $query->result_array();
        foreach($result as $row){
        if(trim($row['institute_type'])=='Academic_Institute')
        $formVal['cType']='academic';
        if(trim($row['institute_type'])=='Test_Preparatory_Institute')
        $formVal['cType']='testPrep';
        }
        //error_log('aditya_in_gandh3'.$typeQuery);
	if($formVal['cType']=='testPrep'){
        //error_log('aditya_in_gandh4');
			//Query Command to insert the exams prepared for
			$newCheck = array(0,0,0,0,0);
                        if($formVal['entranceExam1']!=''){
				$newCheck[0]= $formVal['entranceExam1'];
                                $data =array(
					'type' => 'course',
					'typeId' => $course_id,
					'examId' => $formVal['entranceExam1'],
					'typeOfMap' => 'testprep',
					'valueIfAny' => $formVal['practiceTestsOffered1'],
					'status' => $status,
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('listingExamMap',$data);
//				error_log($queryCmd,3,'/home/naukri/Desktop/log.txt');
				$query = $this->db->query($queryCmd);
			}
			if($formVal['entranceExam2']!=''){
				$newCheck[1]= $formVal['entranceExam2'];
                                $data =array(
					'type' => 'course',
					'typeId' => $course_id,
					'examId' => $formVal['entranceExam2'],
					'typeOfMap' => 'testprep',
					'valueIfAny' => $formVal['practiceTestsOffered2'],
					'status' => $status,
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('listingExamMap',$data);
				$query = $this->db->query($queryCmd);
			}
			if($formVal['entranceExam3']!=''){
				$newCheck[2]= $formVal['entranceExam3'];
                                $data =array(
					'type' => 'course',
					'typeId' => $course_id,
					'examId' => $formVal['entranceExam3'],
					'typeOfMap' => 'testprep',
					'valueIfAny' => $formVal['practiceTestsOffered3'],
					'status' => $status,
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('listingExamMap',$data);
				$query = $this->db->query($queryCmd);
			}
			if($formVal['entranceExam4']!=''){
				$newCheck[3]= $formVal['entranceExam4'];
                                $data =array(
					'type' => 'course',
					'typeId' => $course_id,
					'examId' => $formVal['entranceExam4'],
					'typeOfMap' => 'testprep',
					'valueIfAny' => $formVal['practiceTestsOffered4'],
					'status' => $status,
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('listingExamMap',$data);
				$query = $this->db->query($queryCmd);
			}
			if($formVal['entranceExam5']!=''){
				$newCheck[4]= $formVal['entranceExam5'];
                                $data =array(
					'type' => 'course',
					'typeId' => $course_id,
					'examId' => $formVal['entranceExam5'],
					'typeOfMap' => 'testprep',
					'valueIfAny' => $formVal['practiceTestsOffered5'],
					'status' => $status,
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('listingExamMap',$data);
				$query = $this->db->query($queryCmd);
			}
                        if ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){
                        //error_log('aditya_in_gandh5');
                        $mapCheckVar= implode(',',$newCheck);
                        $queryCmd = "select * from listingExamMap where version = $old_version and type='course' and typeId ='$course_id' and examId NOT IN ( $mapCheckVar)";
                        //error_log('aditya_in_gandh'.$queryCmd);
                        $query = $this->db->query($queryCmd);

                        foreach ($query->result() as $row){

                              $data = array(

                                'type'=>$row->type,
                                'typeId'=>$row->typeId,
                                'examId'=>$row->examId,
                                'typeOfMap'=>$row->typeOfMap,
                                'valueIfAny'=>$row->valueIfAny,
                                'version'=>$new_version,
                                'status'=>$status
                                );
                        $queryC = $this->db->insert_string('listingExamMap',$data);
                        $queryy = $this->db->query($queryC);
                        }
                       }



			//Query Comand to insert additional test prep details
			if($formVal['morningClasses']!=''){
				$data =array(
					'course_id' => $course_id,
					'attribute' => 'morningClasses',
					'status' => $status,
					'value' => $formVal['morningClasses'],
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('course_attributes',$data);
				$query = $this->db->query($queryCmd);
			}
			elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){
                
                        $queryCmd = "select * from course_attributes where version = $old_version and course_id ='$course_id' and attribute= 'morningClasses'";
                        $query = $this->db->query($queryCmd);
                        foreach ($query->result() as $row){

                            $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);
                        }
 
                       }

                        if($formVal['eveningClasses']!=''){
				$data =array(
					'course_id' => $course_id,
					'attribute' => 'eveningClasses',
					'status' => $status,
					'value' => $formVal['eveningClasses'],
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('course_attributes',$data);
				$query = $this->db->query($queryCmd);
			}
			elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){
                        $queryCmd = "select * from course_attributes where version = $old_version and course_id ='$course_id' and attribute= 'eveningClasses'";
                        $query = $this->db->query($queryCmd);
                        foreach ($query->result() as $row){

                            $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);
                        }
 
                       }



                        if($formVal['weekendClasses']!=''){
				$data =array(
					'course_id' => $course_id,
					'attribute' => 'weekendClasses',
					'status' => $status,
					'value' => $formVal['weekendClasses'],
					'version'=>$new_version
				);
				$queryCmd = $this->db->insert_string('course_attributes',$data);
				$query = $this->db->query($queryCmd);
			}
                        elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){
                        $queryCmd = "select * from course_attributes where version = $old_version and course_id ='$course_id' and attribute= 'weekendClasses'";
                        $query = $this->db->query($queryCmd);
                        foreach ($query->result() as $row){

                            $data = array(

                            'course_id'=>$row->course_id,
                            'course_type'=>'0',
                            'attribute'=>$row->attribute,
                            'value'=>$row->value,
                            'version'=>$new_version,
                            'status'=>$status
                            );
                        $queryC = $this->db->insert_string('course_attributes',$data);
                        $queryy = $this->db->query($queryC);
                          }

                        }



		}

	if($formVal['cType']=='academic'){
		//Query Commad to insert the entrance exam required data
		$newCheck = array(0,0,0,0,0);
                if($formVal['entranceExam1']!=''){
			$newCheck[0]= $formVal['entranceExam1'];
                        $data =array(
					'type' => 'course',
					'typeid' => $course_id,
					'examId' => $formVal['entranceExam1'],
					'typeOfMap' => 'required',
					'marks' => $formVal['entranceExamMarks1'],
					'marks_type' => $formVal['entranceExamMarksType1'],
					'status' => $status,
					'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('listingExamMap',$data);
			$query = $this->db->query($queryCmd);
		}

		if($formVal['entranceExam2']!=''){
			$newCheck[1]= $formVal['entranceExam2'];
                        $data =array(
				'type' => 'course',
				'typeid' => $course_id,
				'examId' => $formVal['entranceExam2'],
				'typeOfMap' => 'required',
				'marks' => $formVal['entranceExamMarks2'],
				'marks_type' => $formVal['entranceExamMarksType2'],
				'status' => $status,
				'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('listingExamMap',$data);
			$query = $this->db->query($queryCmd);
		}


		if($formVal['entranceExam3']!=''){
			$newCheck[2]= $formVal['entranceExam3'];
                        $data =array(
					'type' => 'course',
					'typeid' => $course_id,
					'examId' => $formVal['entranceExam3'],
					'typeOfMap' => 'required',
					'marks' => $formVal['entranceExamMarks3'],
					'marks_type' => $formVal['entranceExamMarksType3'],
					'status' => $status,
					'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('listingExamMap',$data);
			$query = $this->db->query($queryCmd);
		}

		if($formVal['entranceExam4']!=''){
			$newCheck[3]= $formVal['entranceExam4'];
                        $data =array(
				'type' => 'course',
				'typeid' => $course_id,
				'examId' => $formVal['entranceExam4'],
				'typeOfMap' => 'required',
				'marks' => $formVal['entranceExamMarks4'],
				'marks_type' => $formVal['entranceExamMarksType4'],
				'status' => $status,
				'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('listingExamMap',$data);
			$query = $this->db->query($queryCmd);
		}

		if($formVal['entranceExam5']!=''){
			$newCheck[4]= $formVal['entranceExam5'];
                        $data =array(
				'type' => 'course',
				'typeid' => $course_id,
				'examId' => $formVal['entranceExam5'],
				'typeOfMap' => 'required',
				'marks' => $formVal['entranceExamMarks5'],
				'marks_type' => $formVal['entranceExamMarksType5'],
				'status' => $status,
				'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('listingExamMap',$data);
//			error_log($queryCmd,3,'/home/naukri/Desktop/log.txt');
			$query = $this->db->query($queryCmd);
		}

                        if ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){
                        $mapCheckVar= implode(',',$newCheck);
                        $queryCmd = "select * from listingExamMap where version = $old_version and type='course' and typeId ='$course_id' and examId NOT IN ( $mapCheckVar)";
                        $query = $this->db->query($queryCmd);

                        foreach ($query->result() as $row){

                              $data = array(

                                'type'=>$row->type,
                                'typeId'=>$row->typeId,
                                'examId'=>$row->examId,
                                'typeOfMap'=>$row->typeOfMap,
                                'valueIfAny'=>$row->valueIfAny,
                                'marks'=>$row->marks,
                                'marks_type'=>$row->marks_type,
                                'version'=>$new_version,
                                'status'=>$status
                                );
                        $queryC = $this->db->insert_string('listingExamMap',$data);
                        $queryy = $this->db->query($queryC);
                        }
                       }

		//Query Command for inserting salary
		if($formVal['maxSalary']!=''){
			$data =array(
					'course_id' => $course_id,
//					'course_type' => 'course',
					'attribute' => 'SalaryMax',
					'status' => $status,
					'value' => $formVal['maxSalary'],
					'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('course_attributes',$data);
			$query = $this->db->query($queryCmd);
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){

                                $queryCmd = "select * from course_attributes where version = $old_version and course_id ='$course_id' and attribute ='SalaryMax' ";
                                $query = $this->db->query($queryCmd);
                                foreach ($query->result() as $row){

                                    $data = array(

                                        'course_id'=>$row->course_id,
                                        'course_type'=>'0',
                                        'attribute'=>$row->attribute,
                                        'value'=>$row->value,
                                        'version'=>$new_version,
                                        'status'=>$status
                                        );
                                    $queryC = $this->db->insert_string('course_attributes',$data);
                                    $queryy = $this->db->query($queryC);
                                }

                        }


		if($formVal['avgSalary']!=''){
			$data =array(
					'course_id' => $course_id,
//					'course_type' => 'course',
					'attribute' => 'SalaryAvg',
					'status' => $status,
					'value' => $formVal['avgSalary'],
					'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('course_attributes',$data);
			$query = $this->db->query($queryCmd);
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){

                                $queryCmd = "select * from course_attributes where version = $old_version and course_id ='$course_id' and attribute ='SalaryAvg' ";
                                
                                $query = $this->db->query($queryCmd);
                                foreach ($query->result() as $row){

                                        $data = array(

                                            'course_id'=>$row->course_id,
                                            'course_type'=>'0',
                                            'attribute'=>$row->attribute,
                                            'value'=>$row->value,
                                            'version'=>$new_version,
                                            'status'=>$status
                                        );
                                 $queryC = $this->db->insert_string('course_attributes',$data);
                                 
                                 $queryy = $this->db->query($queryC);
                                 }
                        }




		if($formVal['minSalary']!=''){
			$data =array(
					'course_id' => $course_id,
//					'course_type' => 'course',
					'attribute' => 'SalaryMin',
					'status' => $status,
					'value' => $formVal['minSalary'],
					'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('course_attributes',$data);
			$query = $this->db->query($queryCmd);
		}
                 elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){

                                $queryCmd = "select * from course_attributes where version = $old_version and course_id ='$course_id' and attribute ='SalaryMin' ";
                                
                                $query = $this->db->query($queryCmd);
                                foreach ($query->result() as $row){

                                    $data = array(

                                        'course_id'=>$row->course_id,
                                        'course_type'=>'0',
                                        'attribute'=>$row->attribute,
                                        'value'=>$row->value,
                                        'version'=>$new_version,
                                        'status'=>$status
                                    );
                                $queryC = $this->db->insert_string('course_attributes',$data);
                                
                                $queryy = $this->db->query($queryC);

                                }

                        }

		if($formVal['SalaryCurrency']!=''){
			$data =array(
					'course_id' => $course_id,
//					'course_type' => 'course',
					'attribute' => 'SalaryCurrency',
					'status' => $status,
					'value' => $formVal['SalaryCurrency'],
					'version'=>$new_version
			);
			$queryCmd = $this->db->insert_string('course_attributes',$data);
			$query = $this->db->query($queryCmd);
		}
                elseif ($calledFromMediaPage > 0 || array_key_exists('relicate_features',$formVal)){

                                $queryCmd = "select * from course_attributes where version = $old_version and course_id ='$course_id' and attribute ='SalaryCurrency' ";
                                
                                $query = $this->db->query($queryCmd);
                                foreach ($query->result() as $row){

                                    $data = array(

                                        'course_id'=>$row->course_id,
                                        'course_type'=>'0',
                                        'attribute'=>$row->attribute,
                                        'value'=>$row->value,
                                        'version'=>$new_version,
                                        'status'=>$status
                                    );
                                $queryC = $this->db->insert_string('course_attributes',$data);
                                
                                $queryy = $this->db->query($queryC);

                                }

                        }
	}
		//Header Image Replication OR Updation
		if( isset($formVal['header_details']))
		$this->ListingModel->addTopHeader($this->db,'course',$course_id,$formVal['header_details'],$old_version,$new_version,$status,1);
		else
		$this->ListingModel->addTopHeader($this->db,'course',$course_id,0,$old_version,$new_version,$status,0);

		return $new_version;
	}


	function updateInstituteStatus($db, $institute_id, $old_status, $new_status,$notThisVersion = -1, $extraFields=array()){
		//SQL Injection Code Not in use
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : updateInstituteStatus');
		$this->db = $this->_loadDatabaseHandle('write');
                // error_log("\n\n In updateInstituteStatus for institute_id : $institute_id with old_status = $old_status, new_status = $new_status, notThisVersion : $notThisVersion",3,'/home/infoedge/Desktop/log.txt');

		$queryCmd = 'select version from listings_main where listing_type="institute" and listing_type_id ='.$institute_id.' and version != '.$notThisVersion.' and status = "'.$old_status.'"';
                error_log('aditya'.$queryCmd);
		$query = $this->db->query($queryCmd);
		foreach($query->result() as $row){
			$version =  $row->version;
		}
		//        error_log("version to be changed".$version);
		if(isset($version) && $version > 0){
			if($extraFields !=array()){
				$extraClause = ' ,comments="'.$extraFields['comments'].'" ,approvedBy="'.$extraFields['approvedBy'].'"';
			}else{
				$extraClause = '';
			}

			if(!empty($this->user_id) && is_numeric($this->user_id))
				$extraClause .= ' ,editedBy = '.$this->user_id;

			//Update listings_main table
			$queryCmd = 'update listings_main set status="'.$new_status.'",	last_modify_date = NOW()  '.$extraClause.' where  version = "'.$version.'" and listing_type="institute" and listing_type_id ='.$institute_id.' and status = "'.$old_status.'"';
			$query = $this->db->query($queryCmd);
			//Update institute table
			$queryCmd = 'update institute set status="'.$new_status.'" where version = "'.$version.'" and institute_id="'.$institute_id.'"' ;
			$query = $this->db->query($queryCmd);
                        
                        // error_log("\n\n In updateInstituteStatus, going to call updateInstituteLocationContactInfo  ",3,'/home/infoedge/Desktop/log.txt');
                        $this->updateInstituteLocationContactInfo($this->db, $new_status, $version, $institute_id);

			//Update listing_attributes_table table
			$queryCmd = 'update listing_attributes_table set status="'.$new_status.'" where listing_type="institute" and version = "'.$version.'" and listing_type_id="'.$institute_id.'"';
			$query = $this->db->query($queryCmd);
			//Update listing_media_table table
			try{
				$queryCmd = 'update listing_media_table set status="'.$new_status.'" where type="institute" and version = "'.$version.'" and type_id="'.$institute_id.'"';
				//	            error_log(" error updating tables ".print_r($queryCmd,true),3,'/home/naukri/Desktop/log.txt');
				$query = $this->db->query($queryCmd);
				//Update company_logo_mapping table
				$queryCmd = 'update company_logo_mapping set status="'.$new_status.'" where listing_type="institute" and version = "'.$version.'" and listing_id="'.$institute_id.'"';
				//	            error_log(" error updating tables ".print_r($queryCmd,true),3,'/home/naukri/Desktop/log.txt');
				$query = $this->db->query($queryCmd);

				//Update header_image table
				$queryCmd = 'update header_image set status="'.$new_status.'" where listing_type="institute" and version = "'.$version.'" and listing_id="'.$institute_id.'"';
				//	            error_log(" error updating tables ".print_r($queryCmd,true),3,'/home/naukri/Desktop/log.txt');
				$query = $this->db->query($queryCmd);

				//Update institute_join_reason table
				$queryCmd = "update institute_join_reason set status='$new_status' where institute_id = $institute_id and version = '$version'" ;
				//	            error_log(" error updating tables ".print_r($queryCmd,true),3,'/home/naukri/Desktop/log.txt');
				$query = $this->db->query($queryCmd);
                                
			}catch (Exception $ex){
				//            	error_log(" error updating tables ".print_r($ex,true),3,'/home/naukri/Desktop/log.txt');
			}
			return $version;
		}
		else{
			return 0;
		}
	}

        function updateInstituteLocationContactInfo($db, $new_status, $version, $institute_id){
            //SQL Injection Code Not in use
            error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : updateInstituteLocationContactInfo');
            //Update institute_location_table table
            $queryCmd = 'update institute_location_table set status="'.$new_status.'" where version = "'.$version.'" and institute_id="'.$institute_id.'"' ;
            $query = $db->query($queryCmd);
            // error_log("\n\n updateInstituteLocationContactInfo query: ".$queryCmd,3,'/home/infoedge/Desktop/log.txt');

            // $queryCmd = "update listing_contact_details set status = '".$new_status."' where institute_location_id in (".$institute_location_ids.") AND listing_type = 'institute' AND listing_type_id = '".$institute_id."'";
            $queryCmd = "update listing_contact_details set status = '".$new_status."' where version = '".$version."'  AND listing_type = 'institute' AND listing_type_id = '".$institute_id."'";
            // error_log("\n\n contact query: ".$queryCmd,3,'/home/infoedge/Desktop/log.txt');
            $query = $db->query($queryCmd);            
        }

        function updateCourseLocationContactInfo($db, $new_status, $old_status, $version, $course_id, $notThisVersion = -1){
        	 //SQL Injection Code Not in use
        	error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : updateCourseLocationContactInfo');
            // $queryCmd = "update listing_contact_details set status = '".$new_status."' where institute_location_id in (".$institute_location_ids.") AND listing_type = 'course' AND listing_type_id = '".$course_id."'";
            $queryCmd = "update listing_contact_details set status = '".$new_status."' where version = ".$version." AND listing_type = 'course' AND listing_type_id = '".$course_id."'";
            // error_log("\n\n contact query: ".$queryCmd,3,'/home/infoedge/Desktop/log.txt');
            $query = $db->query($queryCmd);

            //Update course_location_attribute table
            // $queryCmd = 'update course_location_attribute set status="'.$new_status.'" where  institute_location_id in ('.$institute_location_ids.') and course_id = "'.$course_id.'" and status = "'.$old_status.'" and version != '.$notThisVersion;
            $queryCmd = 'update course_location_attribute set status="'.$new_status.'" where version = "'.$version.'" and course_id = '.$course_id;
            $query = $db->query($queryCmd);
            // error_log("\n\n course_location_attribute query: ".$queryCmd,3,'/home/infoedge/Desktop/log.txt');
        }
        
        
     //Amit Singhal : Updating the Category Page Data for an institute.
    function updateCategoryPageDataInstitute($db, $institute_id){
    	//SQL Injection Code Not in use
		$this->db = $this->_loadDatabaseHandle('write');
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : updateCategoryPageDataInstitute');

		$updateFlag = 0;
		$queryCmd = "select city_id,country_id,pack_type from categoryPageData where institute_id = ".$institute_id." and status = 'live'";
		$query = $this->db->query($queryCmd);
		foreach($query->result_array() as $result1){
				$queryCmd = "select city_id,country_id from institute_location_table where institute_id = ".$institute_id." and status = 'live' limit 1";	
				$query = $this->db->query($queryCmd);
				foreach($query->result_array() as $result2){
						if(($result2['city_id'] != $result1['city_id']) || ($result2['country_id'] != $result1['country_id']))
							$updateFlag = 1;
				}
				if($updateFlag != 1){
					$queryCmd = "select pack_type from listings_main where listing_type_id = ".$institute_id." and listing_type = 'institute' and status = 'live' limit 1";
					$query = $this->db->query($queryCmd);
					foreach($query->result_array() as $result3){
						if($result3['pack_type'] != $result1['pack_type'])
						$updateFlag = 1;
					}
				}
		}
		//error_log(" \nupdateFlag =".print_r($updateFlag,true),3,'/home/amit/Desktop/log.txt');
		if($updateFlag == 1){
				try{
				$queryCmd = "update categoryPageData set status = 'history' where institute_id = ".$institute_id." and status = 'live'";
				$query = $this->db->query($queryCmd);
				$queryCmd = "INSERT INTO `categoryPageData` (
								`course_id` ,
								`category_id` ,
								`ldb_course_id` ,
								`institute_id` ,
								`pack_type` ,
								`institute_pack_type` ,
								`city_id` ,
								`state_id` ,
								`country_id` ,
								`status`
						)SELECT DISTINCT cd.course_id,lsm.categoryID,clm.LDBCourseID,cd.institute_id, lm.pack_type, lm2.pack_type, ilt.city_id, cct.state_id, ilt.country_id, cd.status
						FROM course_details cd
						JOIN listings_main lm ON ( lm.listing_type_id = cd.course_id
									AND lm.listing_type = 'course'
									AND lm.status = 'live' )
						JOIN listings_main lm2 ON ( lm2.listing_type_id = cd.institute_id
									AND lm2.listing_type = 'institute'
									AND lm2.status = 'live' )
						JOIN institute i ON ( i.institute_id = cd.institute_id
									AND i.status = 'live' )
						JOIN institute_location_table ilt ON ( ilt.institute_id = cd.institute_id
									AND ilt.status = 'live' )
						JOIN countryCityTable cct ON ( cct.city_id = ilt.city_id )
						JOIN clientCourseToLDBCourseMapping clm ON ( clm.clientCourseID = cd.course_id
								    AND clm.status='live')
						JOIN LDBCoursesToSubcategoryMapping lsm ON ( lsm.ldbCourseID = clm.LDBCourseID
								    AND lsm.status = 'live')
						WHERE cd.status = 'live'
						AND cd.institute_id = ".$institute_id."";
				$query = $this->db->query($queryCmd);
				
				}catch(Exception $ex){
					error_log("Error in Updating the Category Page Data for a course".$queryCmd);							
				}
		}

		$queryCmd = "UPDATE `categoryPageData` c ".
						"SET `institute_pack_type` = (SELECT `pack_type` FROM `listings_main` lm ".
						"WHERE lm.listing_type_id = c.institute_id AND lm.listing_type = 'institute' ".
						"AND c.status='live' ".
						"AND lm.status = 'live') WHERE c.institute_id = ".$institute_id;
		$query = $this->db->query($queryCmd);

		$queryCmd = "update  listing_category_table lct set lct.status = 'history'
						where
						listing_type = 'institute' and listing_type_id = ".$institute_id." and  lct.status = 'live'";
		$query = $this->db->query($queryCmd);

		$queryCmd = "INSERT INTO `listing_category_table` (
						`listing_category_id` ,
						`listing_type` ,
						`listing_type_id` ,
						`category_id` ,
						`version` ,
						`status`
						)
						SELECT DISTINCT NULL , 'institute', cpd.institute_id, cpd.category_id, i.version, i.status
						FROM categoryPageData cpd
						JOIN institute i ON ( i.institute_id = cpd.institute_id
								AND i.status = 'live')
						WHERE cpd.status = 'live'
								AND cpd.category_id >0
								AND cpd.institute_id = ".$institute_id."
						";
		$query = $this->db->query($queryCmd);
	}
	
	//Amit Singhal : Updating the Category Page Data for a course.
        function updateCategoryPageDataCourse($db, $course_id){
        	//SQL Injection Code Not in use
				$this->db = $this->_loadDatabaseHandle('write');
				error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : updateCategoryPageDataCourse');
				try{
				$queryCmd = "update categoryPageData set status = 'history' where course_id = ".$course_id." and status = 'live'";
				$query = $this->db->query($queryCmd);
				$queryCmd = "INSERT INTO `categoryPageData` (
								`course_id` ,
								`category_id` ,
								`ldb_course_id` ,
								`institute_id` ,
								`pack_type` ,
								`institute_pack_type` ,
								`city_id` ,
								`state_id` ,
								`country_id` ,
								`status`
						) SELECT DISTINCT cd.course_id, lsm.categoryID,	clm.LDBCourseID, cd.institute_id, lm.pack_type, lm2.pack_type, ilt.city_id, cct.state_id, ilt.country_id, cd.status

						FROM course_details cd

						JOIN listings_main lm ON ( lm.listing_type_id = cd.course_id
									AND lm.listing_type = 'course'
									AND lm.status = 'live' )

						JOIN listings_main lm2 ON ( lm2.listing_type_id = cd.institute_id
									AND lm2.listing_type = 'institute'
									AND lm2.status = 'live' )

						JOIN institute i ON ( i.institute_id = cd.institute_id
									AND i.status = 'live' )

						JOIN  course_location_attribute cla ON (cla.course_id = cd.course_id
									AND cla.attribute_type = 'Head Office' AND cla.status = 'live' )

						JOIN institute_location_table ilt ON (ilt.institute_location_id = cla.institute_location_id AND ilt.status = 'live')

						JOIN countryCityTable cct ON ( cct.city_id = ilt.city_id )

						JOIN clientCourseToLDBCourseMapping clm ON ( clm.clientCourseID = cd.course_id
								    AND clm.status='live')

						JOIN LDBCoursesToSubcategoryMapping lsm ON ( lsm.ldbCourseID = clm.LDBCourseID
								    AND lsm.status = 'live')

						WHERE cd.status = 'live'
						AND cd.course_id = ".$course_id." ";
                                
				error_log($queryCmd);
				$query = $this->db->query($queryCmd);
				$queryCmd = "update listing_category_table set status = 'history' where listing_type = 'course' and listing_type_id = ".$course_id." and status = 'live'";
				$query = $this->db->query($queryCmd);
				$queryCmd = "update  listing_category_table lct,course_details cd set lct.status = 'history'
								where
								listing_type = 'institute' and listing_type_id = cd.institute_id
								and cd.course_id = ".$course_id." and lct.status = 'live' and cd.status='live'";
				$query = $this->db->query($queryCmd);
				$queryCmd = "INSERT INTO `listing_category_table` (
								`listing_category_id` ,
								`listing_type` ,
								`listing_type_id` ,
								`category_id` ,
								`version` ,
								`status`
								)
								SELECT DISTINCT NULL , 'course', cpd.course_id, cpd.category_id, cd.version, cd.status
								FROM categoryPageData cpd
								JOIN course_details cd ON ( cd.course_id = cpd.course_id
										AND cd.status = 'live' )
								WHERE cpd.status = 'live'
								AND cpd.category_id >0
								AND cpd.course_id = ".$course_id."
								";
				$query = $this->db->query($queryCmd);
				$queryCmd = "INSERT INTO `listing_category_table` (
								`listing_category_id` ,
								`listing_type` ,
								`listing_type_id` ,
								`category_id` ,
								`version` ,
								`status`
								)
								SELECT DISTINCT NULL , 'institute', cpd.institute_id, cpd.category_id, i.version, i.status
								FROM categoryPageData cpd
								JOIN institute i ON ( i.institute_id = cpd.institute_id
										AND i.status = 'live' )
								WHERE cpd.status = 'live'
                                AND cpd.institute_id = (select cd.institute_id from course_details cd where cd.status = 'live' AND cd.course_id = ".$course_id.")
                                AND cpd.category_id >0
								";
				$query = $this->db->query($queryCmd);
				}catch(Exception $ex){
					error_log("Error in Updating the Category Page Data for a course".$queryCmd);	
				}
	}
	
	//Amit Singhal : Deleting the Category Page Data for an institute.
	function deleteCategoryPageData($db,$type_id,$listing_type){
		//SQL Injection Code Not in use
		$this->db = $this->_loadDatabaseHandle('write');
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : deleteCategoryPageData');
		//error_log(" \nupdateFlag2 =".print_r($type_id.$listing_type,true),3,'/home/amit/Desktop/log.txt');
		if($listing_type == 'institute'){
				$queryCmd = "update categoryPageData set status = 'history' where institute_id = ".$type_id." and status = 'live'";
				//error_log(" \nupdateFlag =".print_r($queryCmd,true),3,'/home/amit/Desktop/log.txt');
				$query = $this->db->query($queryCmd);
		}
		if($listing_type == 'course'){
				$queryCmd = "update categoryPageData set status = 'history' where course_id = ".$type_id." and status = 'live'";
				//error_log(" \nupdateFlag =".print_r($queryCmd,true),3,'/home/amit/Desktop/log.txt');
				$query = $this->db->query($queryCmd);
		}
		$queryCmd = "update listing_category_table set status = 'history' where listing_type = '".$listing_type."' and listing_type_id = ".$type_id."";
		$query = $this->db->query($queryCmd);
	}
	
	function deleteListingEbrochureInfo($db,$type_id,$listing_type){
		//SQL Injection Code Not in use
		$this->db = $this->_loadDatabaseHandle('write');	
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : deleteListingEbrochureInfo');	
		$queryCmd = "update listings_ebrochures set status = 'deleted' where status = 'live' AND listingType = '".$listing_type."' and listingTypeId = ".$type_id."";
		$query = $this->db->query($queryCmd);
	}
	

	
	//Amit Singhal : Updating the Media Count Data
	function updateMediaCountData($db, $institute_id){
		//SQL Injection Code Not in use
		        $this->db = $this->_loadDatabaseHandle('write');
				$queryCmd = "select photo_count,video_count from institute_mediacount_rating_info where institute_id = ".$institute_id."";
				$query = $this->db->query($queryCmd);
				$rows1 = $query->num_rows();
				if($rows1 <= 0){
						$insert = 1;
				}else{
						$result = $query->row();
				}
				//error_log(" \nupdateFlag =".print_r($result,true),3,'/home/amit/Desktop/log.txt');

                $locQueryCmd = "SELECT (
								SELECT count( * )
								FROM listing_media_table lmt, institute_uploaded_media ium
								WHERE lmt.media_id = ium.media_id
								AND lmt.type_id = ium.listing_type_id
								AND ium.listing_type = 'institute'
								AND ium.media_type = 'photo'
								AND ium.status <> 'deleted'
								AND lmt.media_type = 'photo'
								AND lmt.status = 'live'
								AND lmt.type = 'institute'
								AND lmt.type_id = i.institute_id
								GROUP BY lmt.type_id
								) AS photo, (
								
								SELECT count( * )
								FROM listing_media_table lmt, institute_uploaded_media ium
								WHERE lmt.media_id = ium.media_id
								AND lmt.type_id = ium.listing_type_id
								AND ium.listing_type = 'institute'
								AND ium.media_type = 'video'
								AND ium.status <> 'deleted'
								AND lmt.media_type = 'video'
								AND lmt.status = 'live'
								AND lmt.type = 'institute'
								AND lmt.type_id = i.institute_id
								GROUP BY lmt.type_id
								) AS video
								FROM institute i
								WHERE i.status = 'live'
								AND i.institute_id = ?";
                $queryTemp = $this->db->query($locQueryCmd,$institute_id);
                $retArr = array();
                foreach ($queryTemp->result() as $rowTemp) {
						$retArr['photo'] = $rowTemp->photo;
						$retArr['video'] = $rowTemp->video;
				}
				if(!$retArr['photo']){
					$retArr['photo'] = 0;	
				}
				if(!$retArr['video']){
					$retArr['video'] = 0;	
				}
				if(!$result->photo_count){
						$result->photo_count = 0;
				}
				if(!$result->video_count){
						$result->video_count = 0;
				}
				if($insert == 1){
						$queryCmd = "INSERT INTO `institute_mediacount_rating_info` (`institute_id` ,`photo_count`, `video_count`)VALUES(".$institute_id.", ".$retArr['photo'].", ".$retArr['video'].")";
						$query = $this->db->query($queryCmd);
				}else{
						if(($retArr['photo'] != $result->photo_count)||(($retArr['video'] != $result->video_count))){
								$queryCmd = "update institute_mediacount_rating_info set photo_count = ".$retArr['photo']." , video_count = ".$retArr['video']." where institute_id = ".$institute_id."";
								$query = $this->db->query($queryCmd);
						}
				}
    
	}

	function getSalaryStats($request){
		//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$courseId = $parameters['1'];

		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		$queryCmd = "SELECT * FROM course_attributes WHERE attribute IN('SalaryMin','SalaryMax','SalaryAvg', 'SalaryCurrency') AND course_id = ?";

		$query = $this->db->query($queryCmd,array($courseId));
		$salaryStats = array();
		foreach($query->result_array() as $row){
			array_push($salaryStats,array(
            'attribute' => $row['attribute'],
             'amount'=> $row['value']));
		}
		$response = array($salaryStats,'struct');
		return $response;
	}

/*  not in use
function sanitizeCourseContactDetails($db, $institute_id, $status){ 
    //SQL Injection Code Not in use 
    $this->db = $this->_loadDatabaseHandle('write'); 
     

    if($status == 'live'){ 
            $queryCmd = 'select contact_details_id from institute where status="live" and institute_id = ?'; 
            $query = $this->db->query($queryCmd,$institute_id); 
            foreach($query->result_array() as $row){ 
                    $cid = $row['contact_details_id']; 
            } 
            $queryCmd = 'update course_details set contact_details_id = ? where institute_id= ? and status in ("live","draft","queued") and course_details.contact_details_id in (select contact_details_id from institute where institute_id = ?)'; 
            $query = $this->db->query($queryCmd,array($cid,$institute_id,$institute_id)); 

            //Modified by Ankur for Performance modificatons  
            $queryCmd = "select contact_details_id from institute where institute_id = ?";  
            $query = $this->db->query($queryCmd,$institute_id); 
            $contactIds = '';  
            foreach($query->result_array() as $row){  
                $contactIds .= (trim($contactIds) == "")?$row['contact_details_id']:(",".$row['contact_details_id']);  
            }  
            if($contactIds!=''){  
                $queryCmd = 'update listings_main set contact_details_id = '.$cid.' where listing_type="course" and status in ("live","draft","queued") and listings_main.contact_details_id in ('.$contactIds.')';  
                $query = $this->db->query($queryCmd);  
            }  
    } 
}
*/	

	function getInstituteDetailsByVersion($db, $institute_id, $version=1, $courseStatus = "", $otherInstitutesCategory='', $allDataFlag = 0, $isRequestedfromSearch = 0, $isInstituteEditCase = 0){
		//SQL Injection Code Not in use
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : '.$method);
		$appId =1;
		$queryCmd = 'select * from  institute, listings_main  where listings_main.listing_type_id =institute.institute_id and listings_main.listing_type = "institute" and institute_id = '.$institute_id.' and institute.version='."'".$version."'".' and listings_main.version = '."'".$version."'".' limit 1';
		$query = $this->db->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row){
			//TODO: get media content for this version in separate arrays
			$wikiInfo = base64_encode(serialize($this->ListingModel->getWikiDetailsForListing($this->db,'institute',$institute_id,$version)));
			//error_log(print_r(unserialize(base64_decode($wikiInfo)),true),3,'/home/aakash/Desktop/aakash.log');
			$mediaInfo = base64_encode(serialize($this->ListingModel->getMediaDetailsForListings($this->db,'institute',$institute_id,$version)));
			$courseList = base64_encode(serialize($this->ListingModel->getCoursesForListing($this->db,'institute',$institute_id,$version,$courseStatus)));

			if($isRequestedfromSearch == 1) {
                            $course_id_query = 'select course_id from course_details cd where status = "live" and cd.institute_id = '.$institute_id.' order by course_order limit 1'; 
                            $course_temp = $this->db->query($course_id_query);
                            $result_set_new = $course_temp->result();
                            $course_id = $result_set_new[0]->course_id;	
                            // $queryCmd = 'select ilt.*, cla.course_id from institute_location_table ilt, course_location_attribute cla where ilt.version='.$version.' and ilt.institute_id='.$institute_id.' and cla.institute_location_id = ilt.institute_location_id and cla.attribute_type = "Head Office" and attribute_value = "TRUE" and cla.course_id ='."'".$course_id."'".' and cla.status="live" limit 1';
                            $queryCmd = 'select ilt.institute_location_id, ilt.institute_id, ilt.city_id, ilt.country_id, ilt.pincode, ilt.address_1, ilt.address_2, ilt.zone, ilt.locality_id, lcm.localityName as locality_name, cct.city_name as city_name, cla.course_id from course_location_attribute cla, institute_location_table ilt left join countryCityTable cct on ilt.city_id = cct.city_id left join localityCityMapping lcm on lcm.localityId = ilt.locality_id where ilt.version='.$version.' and ilt.institute_id='.$institute_id.' and cla.institute_location_id = ilt.institute_location_id and cla.attribute_type = "Head Office" and cla.attribute_value = "TRUE" and cla.course_id ='."'".$course_id."'".' and cla.status="live" and ilt.status="live" limit 1';
                        } else {
                            // $queryCmd = 'select * from institute_location_table where version='.$version.' and institute_id='.$institute_id.' order by city_name, locality_name ';
                            $queryCmd = 'select ilt.institute_location_id, ilt.institute_id, ilt.city_id, ilt.country_id, ilt.pincode, ilt.address_1, ilt.address_2, ilt.zone, ilt.locality_id, lcm.localityName as locality_name, cct.city_name as city_name, ilt.locality_name as alternate_locality
                                from institute_location_table ilt left join countryCityTable cct on ilt.city_id = cct.city_id left join localityCityMapping lcm on  lcm.localityId = ilt.locality_id
                                where version='.$version.' and institute_id='.$institute_id.' order by city_name, locality_name ';
                        }
                        
                       //     error_log($queryCmd,3,'/home/infoedge/Desktop/log.txt');		       

			$queryTemp = $this->db->query($queryCmd);
			$randomLocation = rand(100)%($queryTemp->num_rows());
			$locationArrayTemp = array(); $ijk = 0;
			foreach ($queryTemp->result() as $rowTemp) {
                                if($ijk++ == 0) {
                                    $institute_location_ids = $rowTemp->institute_location_id;
                                } else {
                                    $institute_location_ids .= ", ".$rowTemp->institute_location_id;
                                }
                                
				if($randomLocation == 0){
					$otherInstitutesCity = $rowTemp->city_id;
					$otherInstitutesCountry = $rowTemp->country_id;
				}
				$randomLocation--;

                                if($rowTemp->locality_id == 0 || $rowTemp->locality_id == NULL) {
                                    $locality_name = $rowTemp->alternate_locality;
                                } else {
                                    $locality_name = $rowTemp->locality_name;
                                }

				array_push($locationArrayTemp,array(
				array(
                                'institute_location_id'=>array($rowTemp->institute_location_id,'string'),
                                'institute_id'=>array($rowTemp->institute_id,'string'),
                                'city_id'=>array($rowTemp->city_id,'string'),
                                'country_id'=>array($rowTemp->country_id,'string'),
                                'city_name'=>array((($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE") ? $rowTemp->city_name : $this->cacheLib->get("city_".$rowTemp->city_id)),'string'),                                
                                'country_name'=>array((($this->cacheLib->get("country_".$rowTemp->country_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("country_".$rowTemp->country_id)),'string'),
                                'pincode'=>array($rowTemp->pincode,'string'),
                            	//'address1'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_1)),'string'),
                            	'address1'=>array(htmlspecialchars_decode(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_1)),'string'),
                            	//'address2'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_2)),'string'),
                            	'address2'=>array(htmlspecialchars_decode(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_2)),'string'),
                            	'zone'=>array(htmlspecialchars($rowTemp->zone),'string'),
                            	'localityId'=>array(htmlspecialchars($rowTemp->locality_id),'string'),
                            	'locality'=> array(htmlspecialchars_decode($locality_name),'string'),
                            	'city'=>array(htmlspecialchars($rowTemp->city_name),'string'),
				//                                'address'=>array(htmlspecialchars($rowTemp->address),'string')
				),'struct')
				);//close array_push
			}


                        // error_log("\n\n For institute_id -".$institute_id.", institute_location_ids = ".print_r($locationArrayTemp,true),3,'/home/infoedge/Desktop/log.txt');

                        $contactInfo = $this->ListingModel->getLocationWiseContactDetails($this->db, $institute_location_ids, 'institute', $institute_id, $version);
                       
                        // If edit Institute case then collect all the Locations assigned to its Courses ('live', 'draft' both) so that user can't delete them..
                        if($isInstituteEditCase == 1) {
                            $locationOnCourses = $this->ListingModel->getLocationOnCourses($this->db, $institute_id);
                        } else {
                            $locationOnCourses = "";
                        }

			//Get institute category
			$queryCmd = 'select * from listing_category_table where listing_type="institute" and listing_type_id='.$institute_id.' and status="live"';
			$queryTemp = $this->db->query($queryCmd);
			$randomCategoryIndex = rand(100)%($queryTemp->num_rows());
			$catArrayTemp = array();
			$catIdsString ='';
			$randomCategoryId = 2;
			foreach ($queryTemp->result() as $rowTemp) {
				if($randomCategoryIndex == 0){
					$randomCategoryId = $rowTemp->category_id;
				}
				$randomCategoryIndex--;
				$tempArray = array(
				array(
                                'category_id'=>array($rowTemp->category_id,'string'),
                                'category_path'=>array($this->cacheLib->get("cat_".$rowTemp->category_id),'string')
				),'struct');
				$catArrayTemp[$rowTemp->category_id] =  $tempArray;
				
				if(count($catIdsString)>0){
					$catIdsString .= ",".$rowTemp->category_id;
				}else{
					$catIdsString = $rowTemp->category_id;
				}
			}
			$catArrayTemp = array_values($catArrayTemp);

			$catIdsString = ltrim($catIdsString,",");
			$parentCategoriesForMB = $this->getParentCategories($catIdsString);

			$msgArray = array();
			$searchStats = $this->getSearchStats($row->institute_id,'institute');
			$solrDate=$this->dateFormater($row->submit_date);
			$lastModifyDate=$this->dateFormater($row->last_modify_date);
			if(isset($row->sourceURL) && (strlen($row->sourceURL) > 5)){
				$outLink = $row->sourceURL;
			}
			else{
				$outLink = $row->url;
			}

			//Related data is not needed in indexing listings
			if($allDataFlag == 1 )
			{
				
				if($otherInstitutesCategory == '' || $otherInstitutesCategory == 1){
					//fetch random institute category
					$otherInstitutesCategory = $randomCategoryId;
				}

				$relatedListings = $this->ListingModel->getInterestedInstitutes($this->db, $otherInstitutesCategory, $otherInstitutesCategory, $otherInstitutesCountry, $otherInstitutesCity, 0, 6);

			}

			if($allDataFlag != 1){
				$queryCmdFetchCourse = 'select course_details.courseTitle as title, course_details.course_id as sublistingId,"course" as sublistingType , group_concat(category_id) as categoryIds, course_type, course_level, viewCount,  concat(duration_value," ",duration_unit) duration , listing_seo_url  from institute_courses_mapping_table,course_details , listings_main , listing_category_table where  listing_category_table.listing_type="course" and listing_category_table.listing_type_id=course_details.course_id  and listing_category_table.status="live" and listings_main.listing_type="course" and listings_main.listing_type_id=course_details.course_id  and listings_main.status="live" and institute_courses_mapping_table.course_id = course_details.course_id and institute_courses_mapping_table.course_id= listing_category_table.listing_type_id and institute_courses_mapping_table.institute_id= '.$institute_id.' group by course_details.course_id order by viewCount';
				//                log_message('debug', 'getAllCourses query cmd is ' . $queryCmdFetchCourse);
				//                error_log_shiksha($queryCmdFetchCourse);
				$queryTemp = $this->db->query($queryCmdFetchCourse);
				foreach ($queryTemp->result() as $rowTemp) {
					array_push($courselistingArrayTemp,array(
					array(
                            'courseId'=>array($rowTemp->sublistingId,'string'),
                            'courseTitle'=>array(htmlspecialchars($rowTemp->title),'string'),
                            'sublistingType'=>array($rowTemp->sublistingType,'string'),
                            'categoryIds'=>array($rowTemp->categoryIds,'string'),
                            'courselevel'=>array($rowTemp->course_level,'string'),
                            'courseType'=>array($rowTemp->course_type,'string'),
                            'seoListingUrl'=>array($rowTemp->listing_seo_url,'string'),
                            'duration'=>array($rowTemp->duration,'string'),
					),'struct'));//close array_push
				}
			}
			$instituteType = $row->institute_type;
			switch($instituteType){
				case 'Test_Preparatory_Institute':
					$instituteType=2;
					break;
				case 'Academic_Institute':
				default:
					$instituteType=1;
					break;
			}

			$queryCmd = "select * from institute_join_reason where institute_id = $institute_id and version = $version";

			$instituteJoinReasonArrayTemp = array();
			$queryTemp = $this->db->query($queryCmd);
			foreach ($queryTemp->result() as $rowTemp) {
				array_push($instituteJoinReasonArrayTemp,array(
				array(
                            'photoTitle'=>array($rowTemp->photo_title,'string'),
                            'photoUrl'=>array(htmlspecialchars($rowTemp->photo_url),'string'),
                        'details'=>array($rowTemp->details,'string'),
				),'struct'));//close array_push
			}

			array_push($msgArray,array(
			array(
                            'institute_id'=>array($row->institute_id,'string'),
							'instituteType'=>array($instituteType,'string'),
                            'title'=>array(htmlspecialchars($row->institute_name),'string'),
                            'institute_request_brochure_link'=>array($row->institute_request_brochure_link,'string'),
			    'institute_request_brochure_year'=>array($row->institute_request_brochure_year,'string'),				 	
                            'establish_year'=>array(htmlspecialchars($row->establish_year),'string'),
                            'hiddenTags'=>array(htmlspecialchars($row->hiddenTags),'string'),
                            'certification'=>array(htmlspecialchars($row->certification),'string'),
                            'institute_logo'=>array(htmlspecialchars($row->logo_link),'string'),
                        	'institute_type'=>array(htmlspecialchars($row->institute_type),'string'),
                        	'abbreviation'=>array(htmlspecialchars($row->abbreviation),'string'),
                        	'aima_rating'=>array(htmlspecialchars($row->aima_rating),'string'),
                        	'usp'=>array(htmlspecialchars($row->usp),'string'),
			'admission_counseling'=>array($row->admission_counseling,'string'),
			'visa_assistance'=>array($row->visa_assistance,'string'),
                            'featured_panel'=>array(htmlspecialchars($row->featured_panel_link),'string'),
                            'threadId'=>array($row->threadId,'string'),
                            'packType'=>array($row->pack_type,'string'),
                            'crawled'=>array($row->crawled,'string'),
                            'moderation'=>array($row->moderation_flag,'string'),
                            'status'=>array($row->status,'string'),
                            'tags'=>array($row->tags,'string'),
                            'url'=>array($contactInfo['website'],'string'),
                            'outLink'=>array($outLink,'string'),
                            'userId'=>array($row->username,'stri            ng'),
                            'timestamp'=>array($solrDate,'string'),
                            'lastModifyDate'=>array($lastModifyDate,'string'),
                            'locations'=>array($locationArrayTemp,'struct'),
                            'contactInfo'=>array($contactInfo,'struct'),
                            'locationIdsAssociatedWithCourses'=>array($locationOnCourses,'struct'),
                            'wikiFields'=>array($wikiInfo,'string'),
                            'mediaInfo'=>array($mediaInfo,'string'),
                            'parentCatsForMB'=>array(htmlspecialchars($parentCategoriesForMB),'string'),
                            'viewCount'=>array($row->viewCount,'string'),
                            'summaryCount'=>array($searchStats[0]['count'],'string'),
                            'categoryArr'=>array($catArrayTemp,'struct'),
			//'relatedQuestions'=>array($relatedQuestions,'string'),
                            'relatedListings'=>$relatedListings,
                            'courselisting'=>array($courselistingArrayTemp,'struct'),
                        	'joinreason'=>array($instituteJoinReasonArrayTemp,'struct'),
                            'courseList'=>array($courseList,'string'),
                            'showWiki'=>array($row->showWiki,'string'),
                            'showMedia'=>array($row->showMedia,'string'),
                        	'seoListingUrl'=>array($row->listing_seo_url,'string'),
							'seoListingTitle'=>array($row->listing_seo_title,'string'),
							'listingSeoDescription'=>array($row->listing_seo_description,'string'),
							'listingSeoKeywords'=>array($row->listing_seo_keywords,'string'),
							'source_type'=>array(htmlspecialchars($row->source_type),'string'),
                        	'source_name'=>array(htmlspecialchars($row->source_name),'string'),

			),'struct')
			);//close array_push

			break;
		}
		$response = array($msgArray,'struct');
		return $response;
	}

	function getCourseDetailsByVersion($db, $course_id, $version=1, $courseEditFlag=0, $institute_status ='"live","draft","queued"', $otherInstitutesCategory='', $allDataFlag = 0, $isRequestedfromSearch = 0 ){
		//connect DB
		//SQL Injection Code Not in use
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : getCourseDetailsByVersion');

		$appId =1;
		$queryCmd = 'select *,course_details.contact_details_id as cid, course_details.source_name as cd_sn,course_details.source_type as cd_st, course_details.approvedBy as approvedBy from course_details, institute, listings_main where listings_main.listing_type_id = course_details.course_id and listings_main.listing_type = "course" and course_details.course_id = '.$course_id.' and course_details.institute_id = institute.institute_id and course_details.version = '."'".$version."'".' and listings_main.version = '."'".$version."'".' and institute.status in ('.$institute_status.') order by institute.version asc limit 1 ';
		$query = $this->db->query($queryCmd); //TODO only if live is not there, draft to be used
		
		$msgArray = array();
		foreach ($query->result() as $row){
			
			//TODO: get media content for this version in separate arrays
			$contactInfo = $this->ListingModel->getContactDetails($this->db,$row->cid);
			$wikiInfo = base64_encode(serialize($this->ListingModel->getWikiDetailsForListing($this->db,'course',$course_id,$version)));
			$mediaInfo = base64_encode(serialize($this->ListingModel->getMediaDetailsForListings($this->db,'course',$course_id,$version)));
			$courseList = base64_encode(serialize($this->ListingModel->getCoursesForListing($this->db,'institute',$row->institute_id,$version,$institute_status))); // FIXME Version need not be passed

			$queryCmd = 'select * from listing_category_table where version="'.$version.'" and listing_type="course" and listing_type_id='.$course_id;
			$queryTemp = $this->db->query($queryCmd);
			$catArrayTemp = array();
			$randomCategoryIndex = rand(100)%($queryTemp->num_rows());
			$catIdsString ='';
			foreach ($queryTemp->result() as $rowTemp) {
				if($randomCategoryIndex == 0){
					$randomCategoryId = $rowTemp->category_id;
				}
				$randomCategoryIndex--;
				array_push($catArrayTemp,array(
				array(
                                'category_id'=>array($rowTemp->category_id,'string'),
                                'category_path'=>array($this->cacheLib->get("cat_".$rowTemp->category_id),'string')
				),'struct')
				);//close array_push
				if(count($catIdsString)>0){
					$catIdsString .= ",".$rowTemp->category_id;
				}else{
					$catIdsString = $rowTemp->category_id;
				}
			}

			$catIdsString = ltrim($catIdsString,",");
			$parentCategoriesForMB = $this->getParentCategories($catIdsString);
			//Institute Type
			$queryCmd = 'select institute_type from institute where institute_id='.$row->institute_id.' order by version desc limit 1';
                        // error_log("\ninstitute type query $queryCmd",3,'/home/infoedge/Desktop/log.txt');
			$queryTemp = $this->db->query($queryCmd);
			if($queryTemp->num_rows() > 0){
				foreach ($queryTemp->result() as $rowTemp) {
					$instituteType = $rowTemp->institute_type;
				}
			}
			switch($instituteType){
				case 'Test_Preparatory_Institute':
					$instituteType=2;
					break;
				case 'Academic_Institute':
				default:
					$instituteType=1;
					break;
			}
			//FIXME draft??
                        if($courseEditFlag == 1 ) { // Take the heighest version, can be of Draft Status entry..
                            // $locQueryCmd = 'select * from institute_location_table where institute_id='.$row->institute_id.' and version = (select max(version) from institute_location_table where institute_id='.$row->institute_id.' and status in ('.$institute_status.')) order by city_name, locality_name';
                            $locQueryCmd = 'select ilt.institute_location_id, ilt.version, ilt.institute_id, ilt.city_id, ilt.country_id, ilt.pincode, ilt.address_1, ilt.address_2, ilt.zone, ilt.locality_id, lcm.localityName as locality_name, cct.city_name as city_name, ilt.locality_name as alternate_locality
                                from institute_location_table ilt left join countryCityTable cct on ilt.city_id = cct.city_id left join localityCityMapping lcm on  lcm.localityId = ilt.locality_id where institute_id='.$row->institute_id.' and version = (select max(version) from institute_location_table where institute_id='.$row->institute_id.' and status in ('.$institute_status.')) order by city_name, locality_name';
                        } else {
                            if($isRequestedfromSearch == 1)
                                // $locQueryCmd = 'select ilt.* from institute_location_table ilt, course_location_attribute cla where cla.course_id = '.$course_id.' and cla.version='.$version.' and ilt.institute_id='.$row->institute_id.' and cla.institute_location_id = ilt.institute_location_id and cla.attribute_type = "Head Office" and attribute_value = "TRUE" AND cla.status="live" limit 1';
                                $locQueryCmd = 'select ilt.institute_location_id, ilt.version, ilt.institute_id, ilt.city_id, ilt.country_id, ilt.pincode, ilt.address_1, ilt.address_2, ilt.zone, ilt.locality_id, lcm.localityName as locality_name, cct.city_name as city_name, ilt.locality_name as alternate_locality
                                    from course_location_attribute cla, institute_location_table ilt left join countryCityTable cct on ilt.city_id = cct.city_id left join localityCityMapping lcm on  lcm.localityId = ilt.locality_id where cla.course_id = '.$course_id.' and cla.version='.$version.' and ilt.institute_id='.$row->institute_id.' and cla.institute_location_id = ilt.institute_location_id and cla.attribute_type = "Head Office" and cla.attribute_value = "TRUE" AND cla.status="live" and ilt.status = "live" limit 1';
                            else
                                // $locQueryCmd = 'select * from institute_location_table where institute_id='.$row->institute_id.' and status="live" order by city_name, locality_name';
                            $locQueryCmd = 'select ilt.institute_location_id, ilt.version, ilt.institute_id, ilt.city_id, ilt.country_id, ilt.pincode, ilt.address_1, ilt.address_2, ilt.zone, ilt.locality_id, lcm.localityName as locality_name, cct.city_name as city_name, ilt.locality_name as alternate_locality
                                from institute_location_table ilt left join countryCityTable cct on ilt.city_id = cct.city_id left join localityCityMapping lcm on  lcm.localityId = ilt.locality_id where institute_id='.$row->institute_id.' and ilt.status="live" order by city_name, locality_name';
                        }

                        // error_log("\n locQueryCmd query $locQueryCmd",3,'/home/infoedge/Desktop/log.txt');

			$queryTemp = $this->db->query($locQueryCmd);
			$randomLocation = rand(100)%($queryTemp->num_rows());
			$locationArrayTemp = array();
                        $institute_location_ids = "";
			if($queryTemp->num_rows() > 0){
				foreach ($queryTemp->result() as $rowTemp) {
					if($randomLocation == 0){
						$otherInstitutesCity = $rowTemp->city_id;
						$otherInstitutesCountry = $rowTemp->country_id;
					}
					$randomLocation--;
					
					if($rowTemp->locality_id == 0 || $rowTemp->locality_id == NULL) {
					    $locality_name = $rowTemp->alternate_locality;
					} else {
					    $locality_name = $rowTemp->locality_name;
					}
			
					  array_push($locationArrayTemp,array(
							array(
							'institute_location_id'=>array($rowTemp->institute_location_id,'string'),
							'institute_id'=>array($rowTemp->institute_id,'string'),
							'city_id'=>array($rowTemp->city_id,'string'),
							'country_id'=>array($rowTemp->country_id,'string'),
							'city_name'=>array((($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE") ? $rowTemp->city_name : $this->cacheLib->get("city_".$rowTemp->city_id)),'string'),
							'country_name'=>array((($this->cacheLib->get("country_".$rowTemp->country_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("country_".$rowTemp->country_id)),'string'),
							'pincode'=>array($rowTemp->pincode,'string'),
							'address1'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_1)),'string'),
							'address2'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_2)),'string'),
							'zone'=>array(htmlspecialchars($rowTemp->zone),'string'),
							'localityId'=>array(htmlspecialchars($rowTemp->locality_id),'string'),
							'locality'=>array(htmlspecialchars($locality_name),'string'),
							'city'=>array(htmlspecialchars($rowTemp->city_name),'string')
							),'struct')
							);//close array_push

					$optionalArgs['location'][$l]  = (($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$rowTemp->city_id))."-".$this->cacheLib->get("country_".$rowTemp->country_id);
					$l++;
				}

                                $instituteVersion = $rowTemp->version;

                                // Getting Contact info for Course agianst the institute locations ids associated with this course..
                               // $contactInfoForCourseLocations = $this->ListingModel->getLocationWiseContactDetails($this->db, $institute_location_ids, 'course', $course_id);

			}
			else{                            
				$locQueryCmd = 'select * from institute_location_table where institute_id='.$row->institute_id.' and status in ("draft","queued") order by institute_location_id asc ';
				$queryTemp = $this->db->query($locQueryCmd);
				$locationArrayTemp = array();
				foreach ($queryTemp->result() as $rowTemp) {
					if($randomLocation == 0){
						$otherInstitutesCity = $rowTemp->city_id;
						$otherInstitutesCountry = $rowTemp->country_id;
					}
					$randomLocation--;

                                        array_push($locationArrayTemp,array(
                                            array(
                                            'institute_location_id'=>array($rowTemp->institute_location_id,'string'),
                                            'institute_id'=>array($rowTemp->institute_id,'string'),
                                            'city_id'=>array($rowTemp->city_id,'string'),
                                            'country_id'=>array($rowTemp->country_id,'string'),
                                            'city_name'=>array((($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE") ? $rowTemp->city_name : $this->cacheLib->get("city_".$rowTemp->city_id)),'string'),
                                            'country_name'=>array((($this->cacheLib->get("country_".$rowTemp->country_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("country_".$rowTemp->country_id)),'string'),
                                            'pincode'=>array($rowTemp->pincode,'string'),
                                            'address1'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_1)),'string'),
                                            'address2'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_2)),'string'),
                                            'zone'=>array(htmlspecialchars($rowTemp->zone),'string'),
                                            'localityId'=>array(htmlspecialchars($rowTemp->locality_id),'string'),
                                            'locality'=>array(htmlspecialchars($rowTemp->locality_name),'string'),
                                            'city'=>array(htmlspecialchars($rowTemp->city_name),'string')
                                            ),'struct')
                                            );//close array_push

					$optionalArgs['location'][$l]  = (($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$rowTemp->city_id))."-".$this->cacheLib->get("country_".$rowTemp->country_id);
					$l++;
				}

				$instituteVersion = $rowTemp->version;
			}

                        // Now getting the Location and Contact info Attributes assigned to this particular Course..
                        $locationsAttributes = $this->getLocationsContactsAttributesForThisCourse($this->db, $course_id, $version, $instituteVersion);


			if(isset($row->sourceURL) && (strlen($row->sourceURL) > 5)){
				$outLink = $rowTemp->sourceURL;
			}
			else{
				$outLink = $rowTemp->url;
			}

			$contact_name = $contactInfo['contact_name'];
			$contact_email = $contactInfo['contact_email'];
			$contact_cell = $contactInfo['contact_cell'];
			$contact_fax = $contactInfo['contact_fax'];
			if(strlen($outLink) < 5){
				if(isset($rowTemp->sourceURL) && (strlen($rowTemp->sourceURL) > 5)){
					$outLink = $rowTemp->sourceURL;
				}
				else{
					$outLink = $rowTemp->url;
				}
			}

			$solrDate=$this->dateFormater($row->submit_date);
			$lastModifyDate=$this->dateFormater($row->last_modify_date);
			if(isset($row->intermediateDuration) && strlen($row->intermediateDuration)>0){
				$intermediateDuration = $row->intermediateDuration;
			}
			else{
				$intermediateDuration = $row->duration;
			}

			$this->load->model('ExamModel','',$dbConfig);
			$tests_required = $this->ExamModel->getExamsForEntity($course_id,'course','required',$version);
			$tests_required_other = $this->ExamModel->getOtherExams($course_id,'course','required',$version);
			$tests_preparation = $this->ExamModel->getExamsForEntity($course_id,'course','testprep',$version);
			$tests_preparation_other = $this->ExamModel->getOtherExams($course_id,'course','testprep',$version);

			$searchStats = $this->getSearchStats($row->course_id,'course');
			//Related data is not needed in indexing listings
			if($allDataFlag == 1)
			{
				
				if($otherInstitutesCategory == '' || $otherInstitutesCategory == 1){
					//fetch random institute category
					$otherInstitutesCategory = $randomCategoryId;
				}
				$relatedListings = $this->ListingModel->getInterestedInstitutes($this->db, $otherInstitutesCategory, $otherInstitutesCategory, $otherInstitutesCountry, $otherInstitutesCity, 0, 6);

			}

			$query = "SELECT id,course_id,shiksha_course_id FROM course_mapping_table WHERE course_id = $course_id and version = $version";
			//error_log("\nquery ".$query,3,'/home/naukri/Desktop/log.txt');
			$queryTemp = $this->db->query($query);
			$courseMapArray = array();
			foreach ($queryTemp->result() as $rowTemp) {
				array_push($courseMapArray,array(
				array(
                                    'id'=>array($rowTemp->id,'string'),
                                    'course_id'=>array($rowTemp->course_id,'string'),
                                    'shiksha_course_id'=>array($rowTemp->shiksha_course_id,'string')
				),'struct')
				);//close array_push
			}

			$query = "SELECT course_id,attribute,value FROM course_attributes WHERE course_id = $course_id and version = $version";
			//			error_log("\nquery ".$query,3,'/home/naukri/Desktop/log.txt');
			$queryTemp = $this->db->query($query);
			$courseAttributeArray = array();
			foreach ($queryTemp->result() as $rowTemp) {
				array_push($courseAttributeArray,array(
				array(
                                    'course_id'=>array($rowTemp->course_id,'string'),
						 			'attribute'=>array($rowTemp->attribute,'string'),
                                    'value'=>array($rowTemp->value,'string')
				),'struct')
				);//close array_push
			}

			$query = "select id,examId,marks,marks_type,l.valueIfAny,b.blogTitle,b.acronym
                        from listingExamMap l
                        join blogTable b
                        on l.examId = b.blogId
                        where type = 'course'
                        and typeId = $course_id and version = $version and b.status = 'live' ";
			//			error_log("\nquery ".$query,3,'/home/naukri/Desktop/log.txt');
			$queryTemp = $this->db->query($query);
			$courseExamArray = array();
			foreach ($queryTemp->result() as $rowTemp) {
				array_push($courseExamArray,array(
				array(
                                    'id'=>array($rowTemp->id,'string'),
						 			'examId'=>array($rowTemp->examId,'string'),
									'courseId'=>array($rowTemp->typeId,'string'),
                                    'marks'=>array($rowTemp->marks,'string'),
									'marks_type'=>array($rowTemp->marks_type,'string'),
									'practiceTestsOffered'=>array($rowTemp->valueIfAny,'string'),
                                    'exam_name'=>array($rowTemp->blogTitle,'string'),
                                    'acronym'=>array($rowTemp->acronym,'string')
				),'struct')
				);//close array_push
			}

			$query = "select cf.listing_id,cf.salient_feature_id,sf.feature_name,sf.value from course_features cf JOIN salient_features sf
            			on cf.salient_feature_id = sf.feature_id where cf.listing_id = $course_id and version = $version";
			//			error_log("\nquery ".$query,3,'/home/naukri/Desktop/log.txt');
			$queryTemp = $this->db->query($query);
			$courseFeatureArray = array();
			foreach ($queryTemp->result() as $rowTemp) {
				array_push($courseFeatureArray,array(
				array(
                                    'course_id'=>array($rowTemp->listing_id,'string'),
                                    'salient_feature_id'=>array($rowTemp->salient_feature_id,'string'),
                                    'feature_name'=>array($rowTemp->feature_name,'string'),
			            'value'=>array($rowTemp->value,'string')
				),'struct')
				);//close array_push
			}

			//Check if this course has an Online form available on SHiksha
			$queryCmd = "select * from OF_InstituteDetails where courseId = $course_id and status = 'live'";
			$queryOF = $this->db->query($queryCmd);
			$numRow = $queryOF->num_rows();
			$displayOnlineFormButton = 'false';
			if($numRow>0){
				$displayOnlineFormButton = 'true';
			}


			array_push($msgArray,array(
			array(
                            'course_id'=>array($row->course_id,'string'),
                            'title'=>array(htmlspecialchars($row->courseTitle),'string'),
                            'course_request_brochure_link'=>array(htmlspecialchars($row->course_request_brochure_link),'string'),
			    'course_request_brochure_year'=>array($row->course_request_brochure_year,'string'),	
                            'duration_value'=>array($row->duration_value,'string'),
                            'duration_unit'=>array($row->duration_unit,'string'),
                            'form_upload'=>array($row->is_application_uploaded,'string'),
                            'application_form_url'=>array($row->application_form_url,'string'),
                            'fees_value'=>array($row->fees_value,'string'),
                            'fees_unit'=>array($row->fees_unit,'string'),
                            'start_date'=>array($row->start_date,'string'),
                            'hiddenTags'=>array(htmlspecialchars($row->hiddenTags),'string'),
                            'end_date'=>array($row->end_date,'string'),
                            'threadId'=>array($row->threadId,'string'),
                            'viewCount'=>array($row->viewCount,'string'),
                            'summaryCount'=>array($searchStats[0]['count'],'string'),
                            'crawled'=>array($row->crawled,'string'),
                            'packType'=>array($row->pack_type,'string'),
                            'moderation'=>array($row->moderation_flag,'string'),
                            'status'=>array($row->status,'string'),
                            'tags'=>array($row->tags,'string'),
                            'userId'=>array($row->username,'string'),
                            'parentCatsForMB'=>array(htmlspecialchars($parentCategoriesForMB),'string'),
                            'course_type'=>array(htmlspecialchars($row->course_type),'string'),
                            'course_level'=>array(htmlspecialchars($row->course_level),'string'),
                            'course_level_1'=>array(htmlspecialchars($row->course_level_1),'string'),
                            'course_level_2'=>array(htmlspecialchars($row->course_level_2),'string'),

                            'available_locations_of_courses'=>array($locationsAttributes['locationsInfoForCourse'],'struct'),
                            'courseFeeLocationWise'=>array($locationsAttributes['courseFeeLocationWise'],'struct'),
                            'courseFeeUnitLocationWise'=>array($locationsAttributes['courseFeeUnitLocationWise'],'struct'),
							'showFeesDisclaimerLocationWise'=>array($locationsAttributes['showFeesDisclaimerLocationWise'],'struct'), // LF-4327
                            'headOfcLocationIDForCourse'=>array($locationsAttributes['headOfcLocationIDForCourse'],'string'),
                            'course_contact_details_locationwise_list'=>array($locationsAttributes['course_contact_details_locationwise_list'],'string'),
                            'contactInfoForAvailableCourseLocations'=>array($locationsAttributes['contactInfoForCourseLocations'],'struct'),
                            'submissiondatesArray'=>array($locationsAttributes['submissiondatesArray'],'struct'),
                            'institute_id'=>array($row->institute_id,'int'),
                            'institute_name'=>array(htmlspecialchars($row->institute_name),'string'),
                            'instituteType'=>array($instituteType,'string'),
                            'seoListingUrl'=>array($row->listing_seo_url,'string'),
                            'seoListingTitle'=>array($row->listing_seo_title,'string'),
                            'listingSeoDescription'=>array($row->listing_seo_description,'string'),
                            'listingSeoKeywords'=>array($row->listing_seo_keywords,'string'),
                            'timestamp'=>array($solrDate,'string'),
                            'lastModifyDate'=>array($lastModifyDate,'string'),
                            'categoryArr'=>array($catArrayTemp,'struct'),
                            'locations'=>array($locationArrayTemp,'struct'),
                            'contact_details_id'=>array($row->cid,'string'),
                            'contactInfoForCourseLocations'=>array($contactInfoForCourseLocations,'struct'),
                            'contact_name'=>array($contactInfo['contact_person'],'string'),
                            'contact_email'=>array($contactInfo['contact_email'],'string'),
                            'contact_main_phone'=>array($contactInfo['contact_main_phone'],'string'),
                            'contact_cell'=>array($contactInfo['contact_cell'],'string'),
                            'contact_alternate_phone'=>array($contactInfo['contact_alternate_phone'],'string'),
                            'contact_fax'=>array($contactInfo['contact_fax'],'string'),
                            'url'=>array($contactInfo['website'],'string'),
                            'outLink'=>array($outLink,'string'),
                            'approvedBy'=>array($row->approvedBy,'string'),
                            'seats_total'=>array($row->seats_total,'string'),
                            'seats_general'=>array($row->seats_general,'string'),
                            'seats_management'=>array($row->seats_management,'string'),
                            'seats_reserved'=>array($row->seats_reserved,'string'),
                            'date_form_submission'=>array($row->date_form_submission,'string'),
                            'date_result_declaration'=>array($row->date_result_declaration,'string'),
                            'date_course_comencement'=>array($row->date_course_comencement,'string'),
                            'tests_required'=>array(htmlspecialchars($tests_required),'string'),
                            'tests_required_other'=>array(htmlspecialchars($tests_required_other),'string'),
                            'tests_preparation'=>array(htmlspecialchars($tests_preparation),'string'),
                            'tests_preparation_other'=>array(htmlspecialchars($tests_preparation_other),'string'),
                            'establish_year'=>array(htmlspecialchars($row->establish_year),'string'),
                            'institute_logo'=>array(htmlspecialchars($row->logo_link),'string'),
			//'relatedQuestions'=>array($relatedQuestions,'string'),
                            'relatedListings'=>$relatedListings,
                            'wikiFields'=>array($wikiInfo,'string'),
                            'mediaInfo'=>array($mediaInfo,'string'),
                            'courseList'=>array($courseList,'string'),
                            'showWiki'=>array($row->showWiki,'string'),
                            'showMedia'=>array($row->showMedia,'string'),
                        	'courseMap'=>array($courseMapArray,'struct'),
                        	'courseAttributes'=>array($courseAttributeArray,'struct'),
                        	'courseExams'=>array($courseExamArray,'struct'),
                        	'courseFeatures'=>array($courseFeatureArray,'struct'),
			    'displayOnlineFormButton'=>array($displayOnlineFormButton,'string'),
			    'source_type'=>array(htmlspecialchars($row->cd_st),'string'),
			    'source_name'=>array(htmlspecialchars($row->cd_sn),'string'),
			    'feestypes'=>array($row->feestypes,'string')	
			),'struct')
			);//close array_push
			
		}
		$response = array($msgArray,'struct');
		return $response;
	}

        function getLocationsContactsAttributesForThisCourse($db, $course_id, $version, $instituteVersion) {
        	error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : getLocationsContactsAttributesForThisCourse ');

        	//SQL Injection Code Not in use
                $locQueryCmd = 'select ilt.* from course_location_attribute cla, institute_location_table ilt where
                    cla.course_id='.$course_id.' and cla.version= '.$version.' and ilt.version = '.$instituteVersion.' and ilt.institute_location_id = cla.institute_location_id group by institute_location_id order by city_name, locality_name';

                $queryTemp = $this->db->query($locQueryCmd);
                $locationArrayTemp = array();
                $institute_location_ids = "";
                // error_log("\n\n locQueryCmd: ".$locQueryCmd."\n data: ".print_r($queryTemp->result(), true),3,'/home/infoedge/Desktop/log.txt');
                if($queryTemp->num_rows() > 0){

                        $instituteLocationIdsArray = array();

                        foreach ($queryTemp->result() as $rowTemp) {

                            $instituteLocationIdsArray[] = $rowTemp->institute_location_id;
                            
                            array_push($locationArrayTemp,array(
                                array(
                                    'institute_location_id'=>array($rowTemp->institute_location_id,'string'),
                                    'institute_id'=>array($rowTemp->institute_id,'string'),
                                    'city_id'=>array($rowTemp->city_id,'string'),
                                    'country_id'=>array($rowTemp->country_id,'string'),
                                    'city_name'=>array((($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE") ? $rowTemp->city_name : $this->cacheLib->get("city_".$rowTemp->city_id)),'string'),
                                    'country_name'=>array((($this->cacheLib->get("country_".$rowTemp->country_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("country_".$rowTemp->country_id)),'string'),
                                    'pincode'=>array($rowTemp->pincode,'string'),
                                    'address1'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_1)),'string'),
                                    'address2'=>array(htmlspecialchars(str_replace(array("\n", "\r","\r\n"), " ", $rowTemp->address_2)),'string'),
                                    'zone'=>array(htmlspecialchars($rowTemp->zone),'string'),
                                    'localityId'=>array(htmlspecialchars($rowTemp->locality_id),'string'),
                                    'locality'=>array(htmlspecialchars($rowTemp->locality_name),'string'),
                                    'city'=>array(htmlspecialchars($rowTemp->city_name),'string')
                                    ),'struct')
                                );//close array_push

                        }   // End of foreach ($queryTemp->result() as $rowTemp).


                        // Getting Head Ofc, Fee Value, Fee Unit related location wise information..
                        $locAttributesQueryCmd = 'select cla.* from course_location_attribute cla, institute_location_table ilt where
                            cla.course_id='.$course_id.' and cla.version= '.$version.' and ilt.institute_location_id = cla.institute_location_id and cla.attribute_value != "FALSE" order by institute_location_id asc';

                        $querylocAttributesTemp = $this->db->query($locAttributesQueryCmd);
                        // error_log("\n\n locAttributesQueryCmd: ".$locAttributesQueryCmd."\n data: ".print_r($querylocAttributesTemp->result(), true),3,'/home/infoedge/Desktop/log.txt');
                        if($querylocAttributesTemp->num_rows() > 0){                            
                            $courseFeeArray = array();
                            $courseFeeUnitArray = array();
							$showDisclaimers = array(); // LF-4327
                            
                            $headOfcLocationIDForCourse = "";

                            foreach ($querylocAttributesTemp->result() as $rowlocAttributesTemp) {

                                // Getting Head Ofc for this Course for this location id..
                                if($rowlocAttributesTemp->attribute_type == "Head Office" && $rowlocAttributesTemp->attribute_value == "TRUE") {
                                    $headOfcLocationIDForCourse = $rowlocAttributesTemp->institute_location_id;
                                }

                                // Getting Course Fee for this Course for this location id..
                                if($rowlocAttributesTemp->attribute_type == "Course Fee Value") {
                                    $courseFeeArray[$rowlocAttributesTemp->institute_location_id] = $rowlocAttributesTemp->attribute_value;
                                }

                                // Getting Course Fee Unit for this Course for this location id..
                                if($rowlocAttributesTemp->attribute_type == "Course Fee Unit") {
                                    $courseFeeUnitArray[$rowlocAttributesTemp->institute_location_id] = $rowlocAttributesTemp->attribute_value;
                                }
                               // get different dates values
                               if(in_array($rowlocAttributesTemp->attribute_type,
                                array ('date_form_submission','date_result_declaration','date_course_comencement'))) {
			       	   $submissiondatesArray[$rowlocAttributesTemp->institute_location_id][$rowlocAttributesTemp->attribute_type] =
                                   $rowlocAttributesTemp->attribute_value;
                               }

								// Getting Fees Disclaimer flag
								if($rowlocAttributesTemp->attribute_type == "Show Fee Disclaimer") { // LF-4327
									$showDisclaimers[$rowlocAttributesTemp->institute_location_id] = $rowlocAttributesTemp->attribute_value;
								}

                            }   // End of foreach ($querylocAttributesTemp->result() as $rowlocAttributesTemp).
                            

                            // Getting Contact info for Course agianst the institute locations ids associated with this course..
                            $contactInfoForCourseLocations = $this->ListingModel->getLocationWiseContactDetails($this->db, implode(", ", $instituteLocationIdsArray), 'course', $course_id, $version);

                        }   // End of if($querylocAttributesTemp->num_rows() > 0).                    

                }   // End of if($queryTemp->num_rows() > 0).
                
                // get locationwise contact details info
                        $locContactsQueryCmd = 'select lcd.* from listing_contact_details lcd, institute_location_table ilt where
                            lcd.listing_type ="course" AND lcd.listing_type_id='.$course_id.' and lcd.version= '.$version.' and ilt.institute_location_id = lcd.institute_location_id order by lcd.institute_location_id asc';
                $locContactsQueryCmdTemp = $this->db->query($locContactsQueryCmd);
                $course_contact_details_locationwise = array();
                if($locContactsQueryCmdTemp->num_rows() > 0){
			 foreach ($locContactsQueryCmdTemp->result() as $rowlocAttributesTemp) {
                        	$course_contact_details_locationwise[$rowlocAttributesTemp->institute_location_id]['contact_name_location'] =
                                $rowlocAttributesTemp->contact_person; 
                                $course_contact_details_locationwise[$rowlocAttributesTemp->institute_location_id]['contact_phone_location'] =
                                $rowlocAttributesTemp->contact_main_phone; 
                                $course_contact_details_locationwise[$rowlocAttributesTemp->institute_location_id]['contact_mobile_location'] =
                                $rowlocAttributesTemp->contact_cell; 
                                $course_contact_details_locationwise[$rowlocAttributesTemp->institute_location_id]['contact_email_location'] =
                                $rowlocAttributesTemp->contact_email; 
                         }

                }
                $finalReturnArray['locationsInfoForCourse'] = $locationArrayTemp;
                $finalReturnArray['headOfcLocationIDForCourse'] = $headOfcLocationIDForCourse;
                $finalReturnArray['courseFeeLocationWise'] = $courseFeeArray;
                $finalReturnArray['courseFeeUnitLocationWise'] = $courseFeeUnitArray;
                $finalReturnArray['showFeesDisclaimerLocationWise'] = $showDisclaimers;
                $finalReturnArray['contactInfoForCourseLocations'] = $contactInfoForCourseLocations;
                $finalReturnArray['submissiondatesArray'] = $submissiondatesArray;
                $finalReturnArray['course_contact_details_locationwise_list'] = $course_contact_details_locationwise;
               
                // error_log("\n\n FINAL RETURNED ARRAY : ".print_r($finalReturnArray, true),3,'/home/infoedge/Desktop/log.txt');

                return $finalReturnArray;
        }

        
	 function checkIfUserExistsForListingAnA($request){
	 	//SQL Injection Code Not in use
	 $parameters = $request->output_parameters();
	 $email = $parameters['0'];
	 
	 
	 
	 if($this->db == ''){
	 log_message('error','can not create db handle');
	 }
	 $queryCmd = "SELECT userid from tuser where email = ?";
	 $query = $this->db->query($queryCmd,array($email));
	 $result = '';
         foreach($query->result() as $row){
	 $result = $row->userid;
	 }

	 $response = array($result,'string');
         return $this->xmlrpc->send_response($response);
	 }

	function sgetCountForResponseForm($request){
		//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$institute_id = $parameters['0'];
					
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		//$queryCmd = 'SELECT count(listing_type_id) as  total FROM tempLmsRequest WHERE listing_type = "course" AND listing_type_id IN(SELECT DISTINCT course_id FROM course_details WHERE institute_id = '.$institute_id.')' ;
		$course_ids = '';
		$queryCmd = "SELECT DISTINCT course_id FROM course_details WHERE institute_id = ? and status ='live'";
                
        $query = $this->db->query($queryCmd,array($institute_id));
		foreach($query->result() as $row){
			$course_ids .= ($course_ids=='')?$row->course_id:",".$row->course_id;
		}
                
		$total = 0;
		if($course_ids!=''){
			//$queryCmd = "SELECT count(listing_type_id) as  total FROM tempLmsRequest WHERE listing_type = 'course' AND listing_type_id IN ($course_ids)";
			//$queryCmd = "SELECT count( DISTINCT userId ) total FROM tempLMSTable WHERE (listing_type = 'course' AND listing_type_id IN($course_ids)) OR (listing_type = 'institute' AND listing_type_id IN ($institute_id)) AND action In('Request_E-Brochure','GetFreeAlert') ";
			//Modified by Ankur on 18 April to modify the query so as to improve its execution time
			// $queryCmd = "select userId FROM tempLMSTable WHERE listing_type = 'course' AND listing_type_id IN ($course_ids) AND action In ('Request_E-Brochure','GetFreeAlert')";
			// $queryCmd = "select userId FROM tempLMSTable WHERE listing_type = 'course' AND listing_type_id IN ($course_ids)";
			$queryCmd = "select userId FROM tempLMSTable WHERE  listing_subscription_type='paid' and listing_type = 'course' AND listing_type_id IN ($course_ids) AND action != 'marketingPage'";
                        $query = $this->db->query($queryCmd);
			$userIds = array();
			foreach($query->result_array() as $row){
				array_push($userIds,$row['userId']);
			}
			// $queryCmd = "select userId FROM tempLMSTable WHERE  listing_type = 'institute' AND listing_type_id IN ($institute_id) AND action In ('Request_E-Brochure','GetFreeAlert')";
			// $queryCmd = "SELECT userId FROM tempLMSTable WHERE listing_type = 'institute' AND listing_type_id = '".$institute_id."' AND ( ACTION = 'Request_E-Brochure' OR ACTION = 'GetFreeAlert' )";
                        
			/* $queryCmd = "(SELECT userId FROM tempLMSTable WHERE listing_type = 'institute' AND listing_type_id ='".$institute_id."' AND ACTION = 'Request_E-Brochure')
                        UNION
			 (SELECT userId FROM tempLMSTable WHERE listing_type = 'institute' AND listing_type_id ='".$institute_id."' AND ACTION = 'GetFreeAlert')" ;
                         */
                        // $queryCmd = "SELECT userId FROM tempLMSTable WHERE listing_type = 'institute' AND listing_type_id ='".$institute_id."'";
			$queryCmd = "SELECT userId FROM tempLMSTable WHERE listing_subscription_type='paid' and listing_type = 'institute' AND listing_type_id ='".$institute_id."' AND action != 'marketingPage'";

                        $query = $this->db->query($queryCmd);
			foreach($query->result_array() as $row){
				array_push($userIds,$row['userId']);
			}
			$uniqueUserIds=array_unique($userIds); 
			$total = count($uniqueUserIds);
		}
		$response = array($total,'string');
		return $this->xmlrpc->send_response($response);

	}

	function newAddInstitute($request)
	{   //SQL Injection Code Not in use
		$this->db = $this->_loadDatabaseHandle('write');
		
		$parameters = $request->output_parameters();
		$formVal = unserialize(base64_decode($parameters['1']));
		// error_log(print_r($formVal,true),3,'/home/infoedge/Desktop/log.txt');
		$appId = $parameters['0']['0'];
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$crawled = "noncrawled";
		if ($formVal['dataFromCMS']=="1") {
			$moderated = "moderated";
			//            $status = 'live';
			$status = 'draft';
		} else {
			$moderated = "unmoderated";
			$status = 'draft';
		}

		if (isset($formVal['packType'])) {
			$packType =$formVal['packType'];
		} else {
			$packType = '-10';
		}
		$version = 1;

		$showWiki =  isset($formVal['showWiki'])?$formVal['showWiki']:'yes';
		$showMedia =  isset($formVal['showMedia'])?$formVal['showMedia']:'yes';

		if (!isset($formVal['sourceUrl']) || strlen($formVal['sourceUrl'])<=5) {
			$formVal['sourceUrl'] = $formVal['url'];
		}

		$queryCmd = "select institute.institute_id from institute,institute_location_table where institute.institute_id = institute_location_table.institute_id and pincode = '".mysql_escape_string(trim($formVal['locationInfo'][0]['pin_code']))."' and institute_name ='".mysql_escape_string($formVal['institute_name'])."' and institute.status in ('live','draft','queued') and institute.status=institute_location_table.status";
		$queryCheckDup = $this->db->query($queryCmd);
		if($queryCheckDup->num_rows() <= 0){
		
			/*
			$queryCmd = "select max(institute.institute_id) as institute_id from institute";
			$queryMaxId = $this->db->query($queryCmd);
			foreach($queryMaxId->result_array() as $maxIdRow){
				$maxId = $maxIdRow['institute_id'];
			}
			$maxId++;
			*/
			
			$maxId = Modules::run('common/IDGenerator/generateId','institute');

			$logo_link = $formVal['logoArr']['thumburl'];
			$featured_panel_link = $formVal['panelArr']['thumburl'];
			switch($formVal['insituteType']){
				case 1:
					$instituteType = 'Academic_Institute';
					break;
				case 2:
					$instituteType = 'Test_Preparatory_Institute';
					break;
			}
			$data =array();
			$data = array(
					'institute_type'=>$instituteType,
                                        'institute_id'=>$maxId,
                                        'institute_name'=>$formVal['institute_name'],
                                            'abbreviation'=>$formVal['abbreviation'],
                                            'usp'=>$formVal['usp'],
                                            'aima_rating'=>$formVal['aima_rating'],
                                        'logo_link'=>$logo_link,
                                        'featured_panel_link'=>$featured_panel_link,
                                        'establish_year'=>$formVal['establish_year'],
                                        'certification'=>$formVal['affiliated_to'],
                                        'contact_details_id' => $cid,
                                            'admission_counseling' => $formVal['admission_counseling'],
                                            'visa_assistance' => $formVal['visa_assistance'],
                                        'status'=>$status,
                                        'version'=>$version,
                                        'institute_request_brochure_link'=>$formVal['institute_request_brochure_link'],
					'institute_request_brochure_year'=>$formVal['institute_request_brochure_link_year'],
										'source_type'=>$formVal['source_type'],
										'source_name'=>$formVal['source_name']
        			);
			$queryCmd = $this->db->insert_string('institute',$data);
			$query = $this->db->query($queryCmd);
			$id = $this->db->insert_id();
			$institute_id = $maxId;
			$data =array();
			$format = 'DATE_ATOM';
			$data = array(
                                'listing_type_id'=>$institute_id,
                                'listing_title'=>$formVal['institute_name'],
                                'username'=>$formVal['username'],
                                'threadId'=>$formVal['threadId'],
                                'hiddenTags'=>$formVal['hiddenTags'],
                                'listing_type'=>'institute',
                                'last_modify_date'=>standard_date($format,time()),
                                'moderation_flag' => $moderated,
                                'requestIP'=>$formVal['requestIP'],
                                'crawled' => $crawled,
                                'pack_type' => $packType,
                                'contact_details_id' => $cid,
                                'status'=>$status,
                                'version'=>$version,
                                'editedBy'=>$formVal['editedBy'],
                                'showWiki'=>$showWiki,
                                'showMedia'=>$showMedia,
                                'listing_seo_url'=>$formVal['listing_seo_url'],
                                'listing_seo_title'=>$formVal['listing_seo_title'],
                                'listing_seo_description'=>$formVal['listing_seo_description'],
                                'listing_seo_keywords'=>$formVal['listing_seo_keywords']
                            );
			$queryCmd = $this->db->insert_string('listings_main',$data);
			$query = $this->db->query($queryCmd);
			$listing_id = $this->db->insert_id();
			/* SEO START */
                        $queryCmd2 = "SELECT listing_type_id FROM listings_main WHERE listing_id = $listing_id";
                                $query = $this->db->query($queryCmd2);
                                $course_id = '';
                                foreach ($query->result_array() as $row){
                                    $institute_id = $row['listing_type_id'];
                                }
			if(empty($formVal['listing_seo_url']))
			{
				if (!empty($formVal['abbreviation'])) {
					$title = seo_url(implode("-", array($formVal['abbreviation'],$formVal['institute_name'])), "-", 30);
				} else {
					$title = seo_url($formVal['institute_name'],"-",30);
				}
                                //While adding a Single-location listing (institute / course), this will work as it is and we will have the location in the listing URL.
                                if(count($formVal['locationInfo']) == 1) {
				if (!empty($formVal['locationInfo']['0']['locality_name']))
				{
					$location = seo_url(implode("-", array($formVal['locationInfo']['0']['locality_name'], $formVal['locationInfo']['0']['city_name'])), "-", 10);
				} else {
					$location = seo_url($formVal['locationInfo']['0']['city_name'], "-", 10);
				}
	                        	$url = SHIKSHA_HOME_URL."/".$title . "-" . $location."-institute-college-listingoverviewtab-".$institute_id;
                                } else {
					$url = SHIKSHA_HOME_URL."/".$title ."-institute-college-listingoverviewtab-".$institute_id;
                                }
			} else {
			        $url = SHIKSHA_HOME_URL."/".seo_url($formVal['listing_seo_url'],"-",30)."-listingoverviewtab-".$institute_id;
			}
			$sql_seo = "update listings_main set listing_seo_url = '$url'";
			if (!empty($formVal['listing_seo_title']))
			{
				$seo_title = $formVal['listing_seo_title'];
				$sql_seo .= " ,listing_seo_title='$seo_title'";
			}
			if (!empty($formVal['listing_seo_description']))
			{
				$seo_desc = $formVal['listing_seo_description'];
				$sql_seo .= " ,listing_seo_description='$seo_desc'";
			}
			if (!empty($formVal['listing_seo_keywords']))
			{
				$seo_key = $formVal['listing_seo_keywords'];
				$sql_seo .= " ,listing_seo_keywords='$seo_key'";
			}
			$sql_seo .= " WHERE listing_type_id ='$institute_id' AND listing_type='institute'";
			
			$this->db->query($sql_seo);
			/* SEO END */
			$response = array(array
                                ('QueryStatus'=>$query,
                             'institute_id'=>$institute_id,
                             'listing_id'=>$listing_id,
                             'type_id'=>$institute_id,
                             'listing_type'=>"institute",
                                ), 'struct'
                                );
                    //update User point system
                    $queryCmd = 'update userPointSystem set userPointValue=userPointValue+20 where userId='.$formVal['username'];
                    if(!$this->db->query($queryCmd)){
                    	error_log_shiksha("ADD LISTING INSTITUTE : Updating User Point System Failed");
                    }

                    // By Amit K for Multilocationing project..
                    $this->ListingModel->addLocationContactInfoForInstitute($this->db, $formVal['locationInfo'], $formVal['contactInfo'], $institute_id, $version);                    

                    $this->ListingModel->addWikiSections($this->db,$institute_id,'institute',$formVal['wiki'],$version,$status);

                    if($formVal['details'] != ''){
                    	$data = array(
	            			'institute_id'=>$institute_id,
	//            			'photo_title'=>$formVal['photo_title'],
	            			'photo_url'=>$formVal['photoArr']['url'],
	            			'details'=>$formVal['details'],
	            			'status'=>$status,
							'version'=>$version
                    	);
                    	$queryCmd = $this->db->insert_string('institute_join_reason',$data);
//                    	error_log($queryCmd.'  add photo etc.',3,'/home/naukri/Desktop/log.txt');
                    	$query = $this->db->query($queryCmd);
                    }
		}
		else{
			foreach ($queryCheckDup->result() as $row){
				$institute_id = $row->institute_id;
			}
			$response = array(array
			('QueryStatus'=>1,
                     'institute_id'=>$institute_id,
                     'listing_id'=>-1,
                     'type_id'=>$institute_id,
                     'listing_type'=>"institute",
                     'duplicate'=>1,
			),
                    'struct'
                    );
		}
		//save mandatory comments
		$commentResponse  = $this->saveMandatoryComments(array($formVal['cmsTrackUserId'],$institute_id,$formVal['cmsTrackTabUpdated'],$formVal['mandatory_comments']));

		return $this->xmlrpc->send_response($response);
	}


	function getInterestedInstitutes($request)
	{	//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$catId = $parameters['1'];
		$cityId = $parameters['2'];
		$start = $parameters['3'];
		$count = $parameters['4'];

		
		//connect DB
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
			error_log("no db handle");
		}

		$listings = $this->ListingModel->getInterestedInstitutes($this->db, $catId, $catId, "2", $cityId, $start, $count);
		return $this->xmlrpc->send_response($listings);
	}


	function getListingsCount($request)
	{	//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$criteria = $parameters['1'];
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
			error_log("no db handle");
		}
		$countData = $this->ListingModel->getTotalListingCountForCriteria($this->db, unserialize($criteria));
		$response = array(array('countInfo'=>json_encode($countData)),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function addOtherExam($request)
	{	//SQL Injection Code Not in use
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$type_id = $parameters['0'];
		$listing_type = $parameters['1'];
		$examData = $parameters['2'];
		$examData['listingType'] = $listing_type;
		$examData['listingTypeId'] = $type_id;
		$this->load->model('ExamModel','',$dbConfig);
		$newExamId = $this->ExamModel->insertOtherExam($examData);
		$response = array(array('otherExam'=>$newExamId,'int'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	function cancelListing($request)
	{	//SQL Injection Code Not in use
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$response = $this->updateListingStatus($type_id,$listing_type,'cancelled');
		return $this->xmlrpc->send_response($response);
	}

	function cancelSubscription($request)
	{
		//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$subscriptionId = $parameters['1'];
		$extraParams = $parameters['2'];
		//connect DB
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmd = 'select listing_type, listing_type_id from listings_main where subscriptionId = ? ';
		$query = $this->db->query($queryCmd,array($subscriptionId));
		foreach ($query->result() as $row){
			$response = $this->updateListingStatus($row->listing_type_id,$row->listing_type,'cancelled');
		}
		return $this->xmlrpc->send_response($response);
	}



	function consumeProduct($request){
		//SQL Injection Code 
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$subscriptionId=$parameters['1'];
		$listing_type = $parameters['2'];
		$type_id = $parameters['3'];
		$features = $parameters['4'];
		//connect DB
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}

		if($features['expiry_date']!=''){
			/*if($listing_type == 'university'){
				$queryCmd = 'update listings_main set expiry_date="'.$features['expiry_date'].'" where listing_type="'.$listing_type.'" and listing_type_id='.$type_id.' and status="'.ENT_SA_PRE_LIVE_STATUS.'"';
			}else{*/
				$queryCmd = 'update listings_main set expiry_date=? where listing_type=? and listing_type_id=? and status="live"';
			/* } */
			$this->db->query($queryCmd,array($features['expiry_date'], $listing_type, $type_id));
		}
		/*
                if($features['subscriptionEndDate'] != ''){
                    $expiryDateClause = ", expiry_date = '".$features['subscriptionEndDate']."' ";
                } else {
		    $expiryDateClause = "";
		}
		*/

		if($features['pack_type']!=''){
			$queryCmd = 'update listings_main set pack_type=? where listing_type=? and listing_type_id=? and status in ("draft","queued")';
			$this->db->query($queryCmd,array($features['pack_type'], $listing_type, $type_id));
		}

		$queryCmd = 'update listings_main set subscriptionId=? where listing_type=? and listing_type_id=? and status in ("draft","queued")';
		if(!$this->db->query($queryCmd,array($subscriptionId, $listing_type, $type_id))){
			$response = array(array('false','struct'));
			return $this->xmlrpc->send_response($response);
		}

		$response = array('true','struct');
		return $this->xmlrpc->send_response($response);
	}

	function extendProduct($request){
		//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$subscriptionId=$parameters['1'];
		$features = $parameters['2'];
		//connect DB
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmd = 'update listings_main set expiry_date="'.$features['expiry_date'].'" where  subscriptionId='.$subscriptionId;
		if(!$this->db->query($queryCmd)){
			$response = array(array('false','struct'));
			return $this->xmlrpc->send_response($response);
		}
		$response = array('true','struct');
		return $this->xmlrpc->send_response($response);
	}


	function makeApcCityCountryMap(){
		
		$appId = 12;
		if($this->cacheLib->get("city2Country_flag") != "1"){
			
			
			if($this->db == ''){
				log_message('error','getCountryTable can not create db handle');
			}
			$queryCmd = 'select name,city_name,city_id from countryTable, countryCityTable where countryCityTable.countryId= countryTable.countryId and countryCityTable.city_name is not null and countryCityTable.city_name !="" order by name';
			$query = $this->db->query($queryCmd);
			$counter = 0;
			$msgArray = array();
			foreach ($query->result() as $row){
				$key = "city2Country_".$row->city_id;
				$val = $row->city_name."-".$row->name;
				// apc_store($key,$val);
                                $this->cacheLib->store($key,$val,1800,'misc',1);
			}
			// apc_store("city2Country_flag","1");
                        $this->cacheLib->store("city2Country_flag","1",1800,'misc',1);
		}
	}

	function makeApcCityMap(){
		
		$appId = 12;
		if($this->cacheLib->get("city_flag") != "1"){
			
			
			if($this->db == ''){
				log_message('error','getCountryTable can not create db handle');
			}
			$queryCmd = 'select * from countryCityTable  where countryCityTable.city_name is not null and countryCityTable.city_name !="" ';
			$query = $this->db->query($queryCmd);
			$counter = 0;
			$msgArray = array();
			foreach ($query->result() as $row){
				$key = "city_".$row->city_id;
				$val = $row->city_name;
				// apc_store($key,$val);
                                $this->cacheLib->store($key,$val,1800,'misc',1);
			}
			// apc_store("city_flag","1");
                        $this->cacheLib->store("city_flag","1",1800,'misc',1);
		}
	}

	function makeApcCountryMap(){
		
		$appId = 12;
		if($this->cacheLib->get("country_flag") != "1"){
			
			
			if($this->db == ''){
				log_message('error','getCountryTable can not create db handle');
			}
			$queryCmd = 'select * from countryTable';
			$query = $this->db->query($queryCmd);
			$counter = 0;
			$msgArray = array();
			foreach ($query->result() as $row){
				$key = "country_".$row->countryId;
				$val = $row->name;
				// apc_store($key,$val);
                                $this->cacheLib->store($key,$val,1800,'misc',1);
			}
			// apc_store("country_flag","1");
                        $this->cacheLib->store("country_flag","1",1800,'misc',1);
		}
	}

	function getParentCategories($catIds){
		//SQL Injection Code Not in use :AA
		//connect DB
		$appId = 1;
		
		$catIds = explode(',',$catIds);
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}


		foreach($catIds as $catId) {
          $catIdsEscaped[] = $this->db->escape($catId);
		}
		$catIds = implode(',', $catIdsEscaped);

		$parentIdString='';
		if(strlen($catIds) == 0){
			return 1;
		}else{
			$queryCmd = " select count(*) as cnt ,parentId from categoryBoardTable where boardId in ($catIds) group by parentId order by 1 desc ";
			$query = $this->db->query($queryCmd);
			foreach ($query->result() as $row){
				if(strlen($parentIdString) > 0){
					$parentIdString .=",".$row->parentId;
				}else{
					$parentIdString = $row->parentId;
				}
			}
			$parentIdString = ltrim($parentIdString,",");
			return $parentIdString;
		}
	}

        function getSeoTagsForNewListing($request){
        	//SQL Injection Code Not in use
            $parameters = $request->output_parameters();
            $listingId = $parameters['0'];
            $identifier = $parameters['1'];
            
	    
            
	    if($this->db == ''){
	    log_message('error','can not create db handle');
            }

            $queryCmd = "SELECT listing_seo_title as title,listing_seo_description as description,listing_seo_keywords as keywords FROM listings_main WHERE status = 'live' AND listing_type = ? AND listing_type_id = ?";
            $query= $this->db->query($queryCmd,array($identifier,$listingId));
            $seoTags = array();
            foreach($query->result_array() as $row){
                array_push($seoTags,array(array('Title' => $row['title'],
                'Keywords' => $row['keywords'],
                'Description' => $row['description']),'struct'));
            }
            $response = array($seoTags,'struct');
            return $this->xmlrpc->send_response($response);

        }

	function getInstituteIdForCourseId($request){
		//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$courseId = $parameters['1'];
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = "SELECT institute_id FROM course_details WHERE status = 'live' AND course_id = ?";

		$query = $this->db->query($queryCmd,array($courseId));
		$instituteId = 0;

		foreach($query->result_array()as $row){
			$instituteId = $row['institute_id'];
		}

		$response = array($instituteId,'int');

		return $this->xmlrpc->send_response($response);

	}

	function saveCourseOrder($request){
		//SQL Injection Code Not in use : Update
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$instituteId = $parameters['1'];
		$courseIds = $parameters['2'];
		$courseOrders = $parameters['3'];
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		foreach ($courseIds AS $key => $courseId){
			$query = "UPDATE course_details SET course_order = ".$courseOrders[$key]." WHERE course_id = $courseId AND institute_id = $instituteId";
			$query = $this->db->query($query);
			//Amit Singhal : Updating the Category Page Data for a course.
			//$this->updateCategoryPageDataCourse($this->db, $courseId);
		}
	}

	function getCourseOrder($request){
		//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$instituteId = $parameters['1'];
		// $query = "select distinct(course_id) AS course_id,courseTitle,ifnull(course_order,1) as course_order from course_details where institute_id = ".$instituteId;
		// This query has been updated by Amit Kuksal on 12th Jan 2010 against bug id: 41698 (added the status 'live')
		$query = "select distinct(course_id) AS course_id,courseTitle,ifnull(course_order,1) as course_order from course_details where institute_id = ? AND status = 'live'";

		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$msgArray = array();
		$query = $this->db->query($query,$instituteId);
		foreach($query->result_array() as $row){
			array_push($msgArray,array(
			array(
                            'courseId'=>array($row['course_id'],'string'),
				            'courseTitle'=>array($row['courseTitle'],'string'),
                        	'courseOrder'=>array($row['course_order'],'string')
			),'struct'));//close array_push
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getInstituteForCourse($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$courseId = $parameters['1'];
		//connect DB
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = 'select listing_title as institute_name, listing_type_id as institute_id, tags from listings_main inner join  institute_courses_mapping_table on institute_courses_mapping_table.institute_id  = listing_type_id where institute_courses_mapping_table.course_id = ? and status ="live" asc limit 1';
		$query = $this->db->query($queryCmd,$courseId);
		$counter = 0;
		$crsArray = array();
		foreach ($query->result() as $row){
			$institute_id = $row->institute_id;
			$institute_name = $row->institute_name;
			$tags = $row->tags;
		}
		
		$queryCmd = '';
		$queryCmd = 'SELECT city_id, country_id from institute_location_table where institute_id = ? and status ="live" order by institute_location_id asc ';
		$query = $this->db->query($queryCmd,$institute_id);
		$counter = 0;
		$l = 0;
		$locArray = array();
		foreach ($query->result() as $row){
			$optionalArgs['location'][$l]  = (($this->cacheLib->get("city_".$row->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$row->city_id))."-".$this->cacheLib->get("country_".$row->country_id);
			$l++;
		}
		$detailurl  = getSeoUrl($institute_id,"institute",$institute_name,$optionalArgs);
		$examPrepFlag = 0;
		if(stripos(trim($tags),"Exam Preparation") !== FALSE){
			$examPrepFlag = 1;
		}
		$msgArray = array();
		array_push($msgArray,array(
		array(
                        'instituteName'=>array($institute_name,'string'),
                        'instituteId'=>array($institute_id,'string'),
                        'detailurl'=>array($detailurl,'string'),
                        'examprepflag'=>array($examPrepFlag,'string')
		),'struct')
		);//close array_push
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sgetJoinGroupInfo($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$institute_id = $parameters['1'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = 'SELECT course_details.course_id, course_details.courseTitle from course_details, listings_main where listings_main.listing_type="course" and listing_type_id = course_id and listings_main.status!="deleted" and listings_main.version = course_details.version and course_details.institute_id = ?';
		$query = $this->db->query($queryCmd,$institute_id);
		$counter = 0;
		$crsArray = array();
		foreach ($query->result() as $row){
			array_push($crsArray,array(
			array(
                            'courseId'=>array($row->course_id,'string'),
                            'courseTitle'=>array($row->courseTitle,'string')
			),'struct'));//close array_push

		}
		$queryCmd = 'SELECT * from institute_location_table where institute_id = ? and status = "live" order by institute_location_id asc ';
		$query = $this->db->query($queryCmd,$institute_id);
		$counter = 0;
		$l = 0;
		$locArray = array();
		foreach ($query->result() as $row){
			$optionalArgs['location'][$l]  = (($this->cacheLib->get("city_".$row->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$row->city_id))."-".$this->cacheLib->get("country_".$row->country_id);
			$l++;
			array_push($locArray,array(
			array(
                            'iltId'=>array($row->institute_location_id,'string'),
                            'cityName'=>array((($this->cacheLib->get("city_".$row->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$row->city_id)),'string'),
                            'cityId'=>array($row->city_id,'string'),
                            'countryName'=>array($this->cacheLib->get("country_".$row->country_id),'string'),
                            'countryId'=>array($row->country_id,'string'),
                            'address'=>array($row->address,'string')
			),'struct'));//close array_push

		}

		//$queryCmd = 'SELECT institute_name,short_desc,url,logo_link as thumburl, status from institute left join institute_photos_table on institute.institute_id = institute_photos_table.institute_id where institute.institute_id = '.$institute_id;
		//        $queryCmd = 'SELECT institute_name,short_desc,institute_uploaded_media.url as url ,logo_link as thumburl, institute.status from institute left join listing_media_table inner join institute_uploaded_media on institute.institute_id = listing_media_table.type_id and type="institute" and institute_uploaded_media.listing_type="institute" and institute_uploaded_media.listing_type_id=listing_media_table.type_id and institute_uploaded_media.media_id=listing_media_table.media_id and listing_media_table.media_type="photo" and listing_media_table.media_type=institute_uploaded_media.media_type where institute.institute_id = '.$institute_id;
		$queryCmd="select institute_name , logo_link , institute_uploaded_media.url from institute
		left join  institute_uploaded_media on institute.institute_id = institute_uploaded_media.listing_type_id
		and institute_uploaded_media.listing_type='institute' and institute_uploaded_media.media_type ='photo'
		where institute.institute_id = ? and institute.status ='live' limit 1";
		$query = $this->db->query($queryCmd,$institute_id);
		foreach ($query->result() as $row){
			$instituteName = $row->institute_name;
			$status = $row->status;
			$shortDesc = $row->short_desc;
			$url = $row->url;
			$thumbUrl = $row->thumburl;
		}
		$queryCmd = "select group_concat(category_id) as categories from listing_category_table  where listing_type='institute'  and listing_type_id = ? group by listing_type_id";
		$query = $this->db->query($queryCmd,$institute_id);
		$row1 = $query->row();
		$categories = $row1->categories;
		$detailurl  = getSeoUrl($institute_id,"institute",$instituteName,$optionalArgs);
		$relatedQuestions = $this->getRelatedProducts($institute_id,'institute');
		//        $relatedQuestionsSameListing = $this->getRelatedProducts($institute_id,'institute','quesSameListing');
		$oldArr = json_decode($relatedQuestions,true);
		//        $newArr = json_decode($relatedQuestionsSameListing,true);
		$newArr =  array();

		$finalArr = array_merge((array)$newArr,(array)$oldArr);
		$relatedQuestions = json_encode($finalArr);
		$relatedListings = $this->getRelatedProducts($institute_id,'institute','listing');
		$relatedEvents = $this->getRelatedProducts($institute_id,'institute','event');
		$msgArray = array();
		array_push($msgArray,array(
		array(
                            'courses'=>array($crsArray,'struct'),
                            'locations'=>array($locArray,'struct'),
                            'instituteName'=>array($instituteName,'string'),
                            'shortDesc'=>array($shortDesc,'string'),
                            'url'=>array($url,'string'),
                            'status'=>array($status,'string'),
                            'detailurl'=>array($detailurl,'string'),
                            'categories'=>array($categories,'string'),
                            'relatedQuestions'=>array($relatedQuestions,'string'),
                            'relatedListings'=>array($relatedListings,'string'),
                            'relatedEvents'=>array($relatedEvents,'string'),
                            'thumbUrl'=>array($thumbUrl,'string')
		),'struct')
		);//close array_push


		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
	/*
	 *	Get the details for listing
	 */
	function sgetParentCategoriesForListing($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		error_log_shiksha("$appId,   $type_id,   $listing_type");
		switch($listing_type){
			case 'course':
				$queryCmd = 'select * from listing_category_table where listing_type="course" and status="live" and listing_type_id= ?';
				$queryTemp = $this->db->query($queryCmd,$type_id);
				$catArrayTemp = array();
				$catIdsString ='';
				foreach ($queryTemp->result() as $rowTemp) {
					if(count($catIdsString)>0){
						$catIdsString .= ",".$rowTemp->category_id;
					}else{
						$catIdsString = $rowTemp->category_id;
					}
				}
				$catIdsString = ltrim($catIdsString,",");
				$parentCategoriesForMB = $this->getParentCategories($catIdsString);
				break;
			case 'scholarship':
				$queryCmd = 'select * from scholarship_category_table where scholarship_id= ?';
				$queryTemp = $this->db->query($queryCmd,$type_id);
				$catArrayTemp = array();
				$catIdsString ='';
				foreach ($queryTemp->result() as $rowTemp) {
					if(count($catIdsString)>0){
						$catIdsString .= ",".$rowTemp->category_id;
					}else{
						$catIdsString = $rowTemp->category_id;
					}
				}
				$catIdsString = ltrim($catIdsString,",");
				$parentCategoriesForMB = $this->getParentCategories($catIdsString);
				break;
			case 'notification':
				$queryCmd = 'select * from admission_notification_category_table where admission_notification_id= ?';
				$queryTemp = $this->db->query($queryCmd,$type_id);
				$catArrayTemp = array();
				$catIdsString ='';
				foreach ($queryTemp->result() as $rowTemp) {
					if(count($catIdsString)>0){
						$catIdsString .= ",".$rowTemp->category_id;
					}else{
						$catIdsString = $rowTemp->category_id;
					}
				}
				$catIdsString = ltrim($catIdsString,",");
				$parentCategoriesForMB = $this->getParentCategories($catIdsString);
				break;
			case 'institute':
				$queryCmd = 'select * from listing_category_table where listing_type = "institute" and status="live" and listing_type_id= ?';
				$queryTemp = $this->db->query($queryCmd,$type_id);
				$catArrayTemp = array();
				$catIdsString ='';
				foreach ($queryTemp->result() as $rowTemp) {
					if(count($catIdsString)>0){
						$catIdsString .= ",".$rowTemp->category_id;
					}else{
						$catIdsString = $rowTemp->category_id;
					}
				}
				$catIdsString = ltrim($catIdsString,",");
				$parentCategoriesForMB = $this->getParentCategories($catIdsString);
				break;
		}

		$response = array(array('catList'=>$parentCategoriesForMB,'struct'),'struct');
		error_log_shiksha("PCRESPONSE".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}

	function makeApcCategoryMap(){
		$boardId = 1;
		//connect DB
		$appId = 1;
		if($this->cacheLib->get("cat_flag") != "1"){
			
			
			
			if($this->db == ''){
				log_message('error','can not create db handle');
			}
			$boardIdArray = array();
			$boardIdString='';

			$queryCmd = ' SELECT t1.boardId AS lev1,t1.name as name1, t2.boardId as lev2, t2.name as name2,
                t3.boardId as lev3, t3.name as name3, t4.boardId as lev4, t4.name as name4 FROM categoryBoardTable AS t1 '.
                    'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
                    'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
                    'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = \''.$boardId.'\'';
			$query = $this->db->query($queryCmd);
			$catArray = array();
			foreach ($query->result() as $row){
				$key = "cat_".$row->lev3;
				$val = $row->name1." / ".$row->name2." / ".$row->name3 ;
				// apc_store($key,$val);
                                $this->cacheLib->store($key,$val,1800,'misc',1);
			}
			// apc_store("cat_flag","1");
                        $this->cacheLib->store("cat_flag","1",1800,'misc',1);
		}
	}

	function alertMobileFeeder($listing_type,$listing_type_id,$title,$category,$country){
		//SQL Injection Code Not in use

		$this->db = $this->_loadDatabaseHandle('write');
		$appId = 1;
		
		
		if($this->db == ''){
			error_log_shiksha("ADD LISTING : cannot create db handle");
		}

		switch($listing_type){
			case 'course':
			case 'institute':
				$product_id = 1;
				break;
			case 'scholarship':
				$product_id = 2;
				break;
			case 'notification':
				$product_id = 3;
				break;
		}

		//Query Command for Insert in the Listing Main Table
		//	    $queryCmd ="INSERT INTO AlertMobileMainTable (type_id,product_id,title,product_name) VALUES ($listing_type_id,$product_id,'$title','$listing_type')";
		$data =array();
		$data = array(
                'type_id'=>$listing_type_id,
                'product_id'=>$product_id,
                'title'=>$title,
                'product_name'=>$listing_type
		);
		$queryCmd = $this->db->insert_string('AlertMobileMainTable',$data);
		$query = $this->db->query($queryCmd);
		$recent_id = $this->db->insert_id();

		for($i = 0; $i < count($category); $i++){
			$thisCat = $category[$i];
			$queryCmd ="INSERT INTO AlertMobileCategoryTable (product_id,type_id,category_id,product_name) VALUES ($product_id,$listing_type_id,$thisCat,'$listing_type')";
			$query = $this->db->query($queryCmd);
		}
		if (isset($country)) {
			foreach ($country as $thisCountry){
				$queryCmd ="INSERT INTO AlertMobileCountryTable (product_id,type_id,country_id,product_name) VALUES ($product_id,$listing_type_id,$thisCountry,'$listing_type')";
				$query = $this->db->query($queryCmd);
			}
		}
		return true;
	}

	/* get List of specific type of listings*/
	function sgetListingsList($request){
		//SQL Injection Code Not in use
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listing_type = $parameters['1'];
		$start=$parameters['2'];
		$count=$parameters['3'];
		$searchCriteria1=trim($parameters['4']);
		$searchCriteria2=trim($parameters['5']);
		$searchCriteria3=trim($parameters['6']);
		$filter1=trim($parameters['7']);
		$filter2=trim($parameters['8']);
		$filter3=trim($parameters['9']);
		$filter4=trim($parameters['10']);
		$usergroup=trim($parameters['11']);
		$userid=trim($parameters['12']);
		$filter5=trim($parameters['13']);
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','sgetListingsList can not create db handle');
		}

		if($filter1!=''){
			$addFilter1 = 'AND L.moderation_flag = '.$filter1.'';
		}else{
			$addFilter1 = '';
		}

		if($filter2!=''){
			$addFilter2 = 'AND L.crawled = '.$filter2.'';
		}else{
			$addFilter2 = '';
		}

		if($filter3!=''){
			$addFilter3 = 'AND L.status = "'.$filter3.'"';
		}else{
			$addFilter3 = '';
		}

		if($filter4!=''){
			$addFilter4 = ' AND L.abuseCount > '.$filter4.' ';
		}else{
			$addFilter4 = '';
		}
		if($filter5!=''){
			if($filter5!='All')
			$addFilter5 = ' AND I.institute_type = "'.$filter5.'" ';
		}else{
			$addFilter5 = '';
		}


		switch($listing_type){
			case 'scholarship' :

				if($searchCriteria1!=''){
					$addSearch1 = 'AND S.scholarship_name LIKE "%'.$searchCriteria1.'%"';
				}else{
					$addSearch1 = '';
				}

				if($searchCriteria2!=''){
					$addSearch2 = 'AND S.institution LIKE "%'.$searchCriteria2.'%"';
				}else{
					$addSearch2 = '';
				}

				if($usergroup=='cms'){
					$addUserid = '';
				}else if($usergroup=='enterprise'){
					$addUserid = 'AND S.username = '.$userid.' ';
				}

				$queryCmd = 'SELECT SQL_CALC_FOUND_ROWS S.scholarship_name,S.levels,S.contact_email,S.scholarship_id,S.institution FROM scholarship S, listings_main L WHERE L.listing_type_id = S.scholarship_id AND L.listing_type = "scholarship"  '.$addSearch1.' '.$addSearch2.' '.$addFilter1.' '.$addFilter2.' '.$addFilter3.' '.$addFilter4.' '.$addUserid.' GROUP BY scholarship_id ORDER BY L.last_modify_date DESC LIMIT '.$start.','.$count.'';
				break;
			case 'notification' :

				if($searchCriteria1!=''){
					$addSearch1 = 'AND A.admission_notification_name LIKE "%'.$searchCriteria1.'%"';
				}else{
					$addSearch1 = '';
				}

				if($searchCriteria2!=''){
					$addSearch2 = 'AND A.admission_year LIKE "%'.$searchCriteria2.'%"';
				}else{
					$addSearch2 = '';
				}

				if($usergroup=='cms'){
					$addUserid = '';
				}else if($usergroup=='enterprise'){
					$addUserid = 'AND A.username = '.$userid.' ';
				}

				$queryCmd = 'SELECT SQL_CALC_FOUND_ROWS A.admission_notification_id,A.admission_notification_name,A.admission_year,A.application_brochure_start_date,A.application_end_date FROM admission_notification A, listings_main L WHERE L.listing_type_id = A.admission_notification_id AND L.listing_type = "notification" '.$addSearch1.' '.$addSearch2.' '.$addFilter1.' '.$addFilter2.' '.$addFilter3.' '.$addFilter4.' '.$addUserid.' GROUP BY admission_notification_id ORDER BY L.last_modify_date DESC LIMIT '.$start.','.$count.'';
				break;
			case 'course' :

				if($searchCriteria1!=''){
					$addSearch1 = 'AND C.courseTitle LIKE "%'.$searchCriteria1.'%"';
				}else{
					$addSearch1 = '';
				}

				if($searchCriteria2!=''){
					$addSearch2 = 'AND I.institute_name LIKE "%'.$searchCriteria2.'%"';
				}else{
					$addSearch2 = '';
				}
				/*
				 if($searchCriteria3!=''){
				 $addSearch3 = 'AND S.city_name LIKE "%'.$searchCriteria3.'%"';
				 }else{
				 $addSearch3 = '';
				 }
				 */
				if($usergroup=='cms'){
					$addUserid = '';
				}else if($usergroup=='enterprise'){
					$addUserid = 'AND L.username = '.$userid.' ';
				}

				$queryCmd = 'SELECT SQL_CALC_FOUND_ROWS C.course_id,C.courseTitle,I.institute_name,T.displayname,L.version FROM course_details C, listings_main L, institute I,tuser T WHERE  L.version=C.version and L.listing_type_id = C.course_id AND L.listing_type = "course" AND I.institute_id=C.institute_id AND I.version = (select min(version) from institute where institute_id=C.institute_id and institute.status in ("live","draft","queued")) AND L.username=T.userid '.$addSearch1.' '.$addSearch2.' '.$addFilter1.' '.$addFilter2.' '.$addFilter3.' '.$addFilter4.' '.$addUserid.' GROUP BY C.course_id ORDER BY L.last_modify_date DESC LIMIT '.$start.','.$count.' ';
				break;
			case 'institute' :

				if($searchCriteria1!=''){
					$addSearch1 = 'AND I.institute_name LIKE "%'.$searchCriteria1.'%"';
				}else{
					$addSearch1 = '';
				}

				$addFilterStatus = ' ilt.status = "live" ';
				if(trim($filter3) == 'draft'){
					$addFilterStatus = ' ilt.status = "draft" ';
				}
				/*
				 if($searchCriteria2!=''){
				 $addSearch2 = 'AND S.city_name LIKE "%'.$searchCriteria2.'%"';
				 }else{
				 $addSearch2 = '';
				 }

				 if($searchCriteria3!=''){
				 $addSearch3 = 'AND C.name LIKE "%'.$searchCriteria3.'%"';
				 }else{
				 $addSearch3 = '';
				 }
				 */
				if($usergroup=='cms'){
					$addUserid = '';
				}else if($usergroup=='enterprise'){
					$addUserid = 'AND L.username = '.$userid.' ';
				}

//				$queryCmd= 'select SQL_CALC_FOUND_ROWS I.institute_id,I.institute_name,I.institute_type,T.displayname,L.version,ilt.city_id,ilt.country_id,(select city_name from countryCityTable cct where cct.city_id = ilt.city_id) cityName , (select name from countryTable ct where ct.countryId = ilt.country_id) countryName from institute I, listings_main L ,tuser T, institute_location_table ilt where '.$addFilterStatus.' and ilt.institute_id = L.listing_type_id and L.version=I.version and (L.listing_type_id = I.institute_id) AND (L.listing_type = "institute") AND (L.username=T.userid) '.$addSearch1.' '.$addFilter1.' '.$addFilter2.' '.$addFilter3.' '.$addFilter4.' '.$addFilter5.' '.$addUserid.' GROUP BY I.institute_id ORDER BY L.last_modify_date DESC LIMIT '.$start.','.$count.'';
				$queryCmd= 'select SQL_CALC_FOUND_ROWS I.institute_id,I.institute_name,I.institute_type,T.displayname,L.version,ilt.city_id,ilt.country_id,cct.city_name cityName , ct.name countryName from institute I, listings_main L ,tuser T, institute_location_table ilt left join countryCityTable cct on cct.city_id = ilt.city_id left join countryTable ct on ct.countryId = ilt.country_id where '.$addFilterStatus.' and ilt.institute_id = L.listing_type_id and L.version=I.version and (L.listing_type_id = I.institute_id) AND (L.listing_type = "institute") AND (L.username=T.userid) '.$addSearch1.' '.$addFilter1.' '.$addFilter2.' '.$addFilter3.' '.$addFilter4.' '.$addFilter5.' '.$addUserid.' GROUP BY I.institute_id ORDER BY L.last_modify_date DESC LIMIT '.$start.','.$count.'';
				break;
			default:
		}
		error_log("aditya".$queryCmd);
		$query = $this->db->query($queryCmd);

		$msgArray = array();

		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$queryCmdCount = 'SELECT FOUND_ROWS() as totalRows';
		$query = $this->db->query($queryCmdCount);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}

		array_push($msgArray,array(array('totalCount'=>$totalRows),'struct'));

		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function updateViewCount($request){
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$this->load->model('Viewcountmodel');
		$this->Viewcountmodel->updateViewCounts($request,"listings");

		//connect DB
			
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmd = 'update listings_main set viewCount=viewCount+1 where listing_type= ? and status in ("live","draft","queued","stagging","stagging_draft") and listing_type_id= ?';

		if(!$this->db->query($queryCmd, array($listing_type, $type_id))){
			$response = array(array('error'=>'UpdateViewCount for message board Query Failed','struct'));
			return $this->xmlrpc->send_response($response);
		}
		$response = array('added','struct');
		return $this->xmlrpc->send_response($response);
	}

/*******
* Purpose : To update view count for Abroad Listing pages.
* Param : request with listing type and listing id.
* Author : Vinay
*********/
	function updateViewCountforAbroadListing($request){
		
		$clientIP = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
		global $blockedIPsForAbroadListingViewCountTracking;
		if(!in_array($clientIP, $blockedIPsForAbroadListingViewCountTracking))
		{		
		
		$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$this->load->model('Viewcountmodel');
		$this->Viewcountmodel->updateViewCounts($request,"abroadlistings");

		if($this->db == ''){
			log_message('error',' can not create db handle to upadete abroad view count');
		}			
		
		$listing_type  = $listing_type == 'institute' ? 'department' : $listing_type;
		$validListingTypes = array('course','department','university','snapshotcourse');
		if(!in_array($listing_type, $validListingTypes)){
			$response = array(array('error'=>'Invalid Listing Type','struct'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		
		if( (isset($_COOKIE['ci_mobile']) && $_COOKIE['ci_mobile'] == "mobile") && !(isset($_COOKIE['user_force_cookie']) && $_COOKIE['user_force_cookie'] == "YES") ){
			$sql = "INSERT INTO abroadListingViewCountDetails (listingType,listingId,viewDate,viewCount,mobileViewCount) VALUES (?,?,CURDATE(),1,1)
				ON DUPLICATE KEY UPDATE viewCount = viewCount+1, mobileViewCount = mobileViewCount+1";
		} else {
			$sql = "INSERT INTO abroadListingViewCountDetails (listingType,listingId,viewDate,viewCount,mobileViewCount) VALUES (?,?,CURDATE(),1,0)
		ON DUPLICATE KEY UPDATE viewCount = viewCount+1";
		}		
		// error_log("AMIT sql = ".$sql.", cookie = ".$_COOKIE['ci_mobile'].", force cookie = ".$_COOKIE['user_force_cookie']);
		if(!$this->db->query($sql,array($listing_type,$type_id))){
			$response = array(array('error'=>'Query Failed','struct'),'struct');
			return $this->xmlrpc->send_response($response);
		}
		if($listing_type == 'course' || $listing_type == 'institute' || $listing_type == 'university' || $listing_type == 'department') {
		$listing_type  = $listing_type == 'department' ? 'institute' : $listing_type; 
		$queryCmd = 'update listings_main set viewCount=viewCount+1 where listing_type=? and status in ("draft","queued","live") and listing_type_id=? ';
		if(!$this->db->query($queryCmd,array($listing_type,$type_id))){
			$response = array(array('error'=>'Query Failed','struct'),'struct');
			return $this->xmlrpc->send_response($response);
			}
		}
		$response = array('added','struct');
		return $this->xmlrpc->send_response($response);
		}
		$response = array(array('error'=>'blocked ip','struct') ,'struct');
		return $this->xmlrpc->send_response($response);
	}	
	

	function updateThreadId($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$threadId = $parameters['3'];
		//connect DB
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmd = 'update listings_main set threadId='.$threadId.' where listing_type="'.$listing_type.'" and listing_type_id='.$type_id;
		if(!$this->db->query($queryCmd)){
			$response = array(array('error'=>'UpdateThreadId for message board Query Failed','struct'));
			return $this->xmlrpc->send_response($response);
		}
		$response = array('added','struct');
		return $this->xmlrpc->send_response($response);
	}

	function courseTestsMigration($appId){
		$this->load->library('listingconfig');
		
		echo 1;
		//connect DB
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$this->load->model('ExamModel','',$dbConfig);

		$queryCmd = ' select blogId , blogTable.acronym , blogTitle from blogTable where blogType ="exam" and status = "live" group by blogTable.acronym ';
		$query = $this->db->query($queryCmd);
		$examArray = array();
		foreach ($query->result() as $row){
			$examArray[strtolower($row->acronym)] = $row->blogId;
		}

		echo "<pre>";
		print_r($examArray);
		echo "</pre>";

		$queryCmd = ' select course_id, test  ,value from course_tests_required_table where test !="" and test is not null  ';
		$query = $this->db->query($queryCmd);
		$coursesToBeMigrated = array();
		foreach ($query->result() as $row){
			$course_id = $row->course_id;
			$test = $row->test;
			$value = $row->value;
			if(strtolower($test) != "others"){
				if(isset($examArray[strtolower($test)]) && strlen($test) > 0){
					$examsForCourse[0] = $examArray[strtolower($test)];
					$this->ExamModel->makeEntityExamsMapping($course_id, $examsForCourse,'course','required');
				}
				else{
					$examData =array();
					$examData['listingType'] = 'course';
					$examData['listingTypeId'] = $course_id;
					$examData['typeOfMap'] = 'required';
					$examData['exam_name'] = $value;
					$examData['exam_desc'] = $value;
					$examData['numOfCentres'] = 0;
					$newExamId = $this->ExamModel->insertOtherExam($examData);
				}
			}
			else{
				$examsForCourse = array();
				foreach($examArray as $key=>$val){
					if(strpos($value,strtoupper($key)) !== FALSE)
					{
						array_push($examsForCourse,$val);
					}
				}
				if(count($examsForCourse) > 0){
					echo "<pre>";
					echo $course_id;
					print_r($examsForCourse);
					echo "</pre>";
					$this->ExamModel->makeEntityExamsMapping($course_id, $examsForCourse,'course','required',$value);
				}
				else{
					$examData =array();
					$examData['listingType'] = 'course';
					$examData['listingTypeId'] = $course_id;
					$examData['typeOfMap'] = 'required';
					$examData['exam_name'] = $value;
					$examData['exam_desc'] = $value;
					$examData['numOfCentres'] = 0;
					$newExamId = $this->ExamModel->insertOtherExam($examData);
				}
			}
		}
		echo 1;
	}

	/*
	 *	Get the details for listing
	 */
	function sgetListingDetails($request){
		//error_log("============================here==========================================");
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$otherInstitutesCategory = $parameters['3'];
		$allDataFlag = $parameters['4'];
		//        error_log("=========================> $appId,   $type_id,   $listing_type",3,'/home/naukri/Desktop/log.txt');
		switch($listing_type){
			case 'course':
				// $dataArr = $this->getAllCourseDetails($type_id,$otherInstitutesCategory, $allDataFlag);
				break;
			case 'scholarship':
				// $dataArr = $this->getAllScholarshipDetails($type_id);
				break;
			case 'notification':
				// $dataArr = $this->getAllNotificationDetails($type_id);
				break;
			case 'institute':
				// $dataArr = $this->getAllInstituteDetails($type_id,$otherInstitutesCategory, $allDataFlag);
				break;
		}
		return $this->xmlrpc->send_response($dataArr);
	}


	function getRelatedProducts($listing_type_id,$listing_type,$relatedprod = 'ask'){
		$this->load->library('relatedClient');
		$relatedClientObj = new RelatedClient();
		$gotRelatedDataFlag = self::$gotRelatedDataFlag;
		if($gotRelatedDataFlag == "0") {
			$relatedQuestions = $relatedClientObj->getAllRelatedData($appId,$listing_type,$listing_type_id);
			self::$gotRelatedDataFlag = 1;
			self::$totalRelatedData = $relatedQuestions;
		}else {
			$relatedQuestions = self::$totalRelatedData;
		}
		$flagFound = 0;
		for($i = 0 ; $i < count($relatedQuestions); $i++) {
			if(is_array($relatedQuestions) && is_array($relatedQuestions[$i]))
			{
				if($relatedQuestions[$i]['relatedProductName'] == $relatedprod) {
					$relatedQuestions[0] = $relatedQuestions[$i];
					$flagFound = 1;
					break;
				}
			}
		}


		if($flagFound == 1)
		{
			if(is_array($relatedQuestions) && is_array($relatedQuestions[0]) && is_array($relatedQuestions[0])){
				$data = json_decode($relatedQuestions[0]['relatedData'],true);
				$data = $data["resultList"];

				for($j = 0 ; $j < count($data["resultList"]); $j++) {
					if(isset($data[$j]['packtype']) && $data[$j]['packtype'] > 0 && $data[$j]['packtype'] !=7 )
					{
						$data[$j]['isSponsored'] = 1;
					}
				}

				if(isset($relatedQuestions[0]['relatedDataSponsored'])) {
					$dataSpon = json_decode($relatedQuestions[0]['relatedDataSponsored'],true);
					$max = 7;
					$dataSpon = $dataSpon["resultList"];
					if(count($data)< 7) {
						$max = count($data);
					}
					$maxT = 3;
					if(count($dataSpon) < 3) {
						$maxT = count($dataSpon);
					}
					$k =0;
					$totalData = array();
					for($i = 0; $i < $maxT ; $i++) {
						$flagNotToDo = false;
						for($j = 0 ; $j < $max; $j++) {
							if(isset($data[$j]['url']) && isset($dataSpon[$i]['url'])){
								if($data[$j]['url'] == $dataSpon[$i]['url']) {
									$flagNotToDo = true;
								}
							}
						}
						if(!$flagNotToDo) {
							if(isset($dataSpon[$j]['packtype']) && $dataSpon[$j]['packtype'] > 0 && $dataSpon[$j]['packtype'] !=7 )
							{
								$dataSpon[$i]['isSponsored'] = 1;
							}
							$totalData[$k] = $dataSpon[$i];
							$k++;
						}
					}
					$data = array_slice($data,0,7);
					$totalData = array_merge($data,$totalData);
					$data = $totalData;
				}
				return json_encode($data);
			}else{
				return  false;
			}
		}
		else{
			return  false;
		}
	}

	function getSearchStats($type_id, $listing_type){
		$appId =1;
		$this->load->library('listing_client');
		$listingCient = new Listing_client();
		$response = $listingCient->getSearchSnippetCount($appId,$listing_type,$type_id);
		if(count($response) <= 0){
			$response[0]['count'] = 0;
		}
		return $response;
	}

	/**
	 *
	 * Returns the course attributes for the particular course
	 * @param $courseId int
	 * @return array
	 */

	function getCourseAttributes($courseId){
		$query = "SELECT course_id,attribute,value FROM course_attributes WHERE course_id = $courseId AND status = 'live'";
		//error_log("\nattributes ".$query,3,'/home/naukri/Desktop/log.txt');
		$queryTemp = $this->db->query($query,$courseId);
		$attributesArrayTemp = array();
		foreach ($queryTemp->result() as $rowTemp) {
			array_push($attributesArrayTemp,array(
			array(
                	'courseId'=>array($rowTemp->course_id,'string'),
                    'attribute'=>array($rowTemp->attribute,'string'),
                    'value'=>array($rowTemp->value,'string')
			),'struct')
			);//close array_push
		}
		return $attributesArrayTemp;
	}

	/**
	 *
	 * Returns the course eligibility criteria
	 * @param $courseId integer
	 * $return array
	 */

	function getCourseEligibility($courseId){
		$query = "SELECT id,typeId,examId,typeOfMap,marks,marks_type,valueIfAny,blogTitle from listingExamMap lem LEFT JOIN blogTable bt ON lem.examId = bt.blogId WHERE lem.typeId = ? AND lem.status = 'live' and bt.status ='live' ";
		//error_log("\nattributes ".$query,3,'/home/naukri/Desktop/log.txt');
		$queryTemp = $this->db->query($query,$courseId);
		$examsArrayTemp = array();
		foreach ($queryTemp->result() as $rowTemp) {
			array_push($examsArrayTemp,array(
			array(
                	'courseId'=>array($rowTemp->typeId,'string'),
                    'listingExamMapId'=>array($rowTemp->id,'string'),
                    'examId'=>array($rowTemp->examId,'string'),
            		'typeOfMap'=>array($rowTemp->typeOfMap,'string'),
            		'marks'=>array($rowTemp->marks,'string'),
            		'marks_type'=>array($rowTemp->marks_type,'string'),
            		'valueIfAny'=>array($rowTemp->valueIfAny,'string'),
            		'blogTitle'=>array($rowTemp->blogTitle,'string')
			),'struct')
			);//close array_push
		}
		return $examsArrayTemp;
	}

	/**
	 *
	 * Returns the course salient features...
	 * @param $courseId int
	 * @return array
	 */

	function getCourseFeatures($courseId){
		$query = "select cf.id,cf.listing_id,sf.feature_name,sf.value FROM course_features cf JOIN salient_features sf ON cf.salient_feature_id = sf.feature_id WHERE listing_id = ? AND cf.status = 'live'";
		//error_log("\nattributes ".$query,3,'/home/naukri/Desktop/log.txt');
		$queryTemp = $this->db->query($query,$courseId);
		$featuresArrayTemp = array();
		foreach ($queryTemp->result() as $rowTemp) {
			array_push($featuresArrayTemp,array(
			array(
                	'courseId'=>array($rowTemp->listing_id,'string'),
                    'courseFeatureId'=>array($rowTemp->id,'string'),
                    'featureName'=>array($rowTemp->feature_name,'string'),
            		'value'=>array($rowTemp->value,'string')
			),'struct')
			);//close array_push
		}
		return $featuresArrayTemp;
	}

	/*
	 *	Get the list of sublistings
	 */
	function sgetSubListings($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		switch($listing_type){
			case 'course':
				$queryCmd = 'select scholarship.scholarship_name as title, scholarship.scholarship_id as sublistingId,"scholarship" as sublistingType from course_scholarship_mapping_table, scholarship, listings_main  where listings_main.listing_type="scholarship" and listings_main.listing_type_id= scholarship.scholarship_id and listings_main.status="live" and course_scholarship_mapping_table.scholarship_id = scholarship.scholarship_id and course_scholarship_mapping_table.course_id= '.$type_id;
				$queryCmd .= ' UNION select admission_notification_name as title, admission_notification.admission_notification_id as sublistingId,"notification" as sublistingType from course_examinations_mapping_table, admission_notification, listings_main  where  listings_main.listing_type_id=  admission_notification.admission_notification_id and  listings_main.listing_type="notification" and  listings_main.status="live" and course_examinations_mapping_table.admission_notification_id = admission_notification.admission_notification_id and course_examinations_mapping_table.course_id= '.$type_id;
				$query = $this->db->query($queryCmd);
				break;
			case 'institute':
				$queryCmd = 'select scholarship.scholarship_name as title, scholarship.scholarship_id as sublistingId,"scholarship" as sublistingType from institute_scholarship_mapping_table, scholarship ,listings_main  where listings_main.listing_type="scholarship" and listings_main.listing_type_id= scholarship.scholarship_id and listings_main.status="live" and institute_scholarship_mapping_table.scholarship_id = scholarship.scholarship_id and institute_scholarship_mapping_table.institute_id= '.$type_id;
				$queryCmd .= ' UNION select admission_notification_name as title, admission_notification.admission_notification_id as sublistingId,"notification" as sublistingType from institute_examinations_mapping_table, admission_notification, listings_main  where  listings_main.listing_type_id=  admission_notification.admission_notification_id  and  listings_main.listing_type="notification" and  listings_main.status="live" and institute_examinations_mapping_table.admission_notification_id = admission_notification.admission_notification_id and institute_examinations_mapping_table.institute_id= '.$type_id;
				$queryCmd .= ' UNION select course_details.courseTitle as title, course_details.course_id as sublistingId,"course" as sublistingType from institute_courses_mapping_table,course_details, listings_main where listings_main.listing_type="course" and listings_main.listing_type_id =  course_details.course_id and listings_main.status="live" and institute_courses_mapping_table.course_id = course_details.course_id and course_details.version =  listings_main.version and institute_courses_mapping_table.institute_id= '.$type_id;
				$query = $this->db->query($queryCmd);
				break;
		}
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'sublistingId'=>array($row->sublistingId,'string'),
                            'title'=>array($row->title,'string'),
                            'sublistingType'=>array($row->sublistingType,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');

		return $this->xmlrpc->send_response($response);
	}



	/*
	 *	Get the country tree
	 */
	function sgetCountries($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}

		$queryCmd = 'select name,countryId from countryTable';

		$query = $this->db->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'countryName'=>array($row->name,'string'),
                            'countryID'=>array($row->countryId,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');

		return $this->xmlrpc->send_response($response);
	}

	function sgetCountriesForProduct($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$product=$parameters['1'];

		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}

		$queryCmd = "select regionname as name,group_concat(id) as countryId,group_concat(name) as countryName from tregion a inner join tregionmapping b on a.regionid = b.regionid inner join countryTable c on c.countryId = b.id where a.product = '$product' group by a.regionid";
		error_log($queryCmd.'QUERYQUERY');
		$query = $this->db->query($queryCmd);
		$countriesId = '1';
		$ArrayResult = array();
		$i = 0;
		foreach($query->result() as $row)
		{
			$countriesId .= ','.$row->countryId;
			$ArrayResult[$i] = (array)$row;
			$i++;
		}
		/* $ArrayResult[0] = (array)$query->row();
		 $countriesId = $query->row()->countryId; */
		$queryCmd1 = "select name,countryId,name as countryName from countryTable where countryId not in ($countriesId)";
		error_log($queryCmd1.'QUERYQUERY');
		$query = $this->db->query($queryCmd1);
		$ArrayResult = array_merge((array)$ArrayResult,(array)$query->result_array());
		error_log(print_r($ArrayResult,true).'QUERYQUERY');
		$countriesArray = array();
		foreach ($ArrayResult as $row){
			$name = str_replace(" ","",strtolower($row['name']));
			if($row['name'] == "USA")
			{
				$row['name'] = "United States";


			}
			if($row['name'] == "UK")
			{
				$row['name'] = 'United Kingdom';
			}
			$countriesArray[$name] = array('name' => $row['name'],'value' => $row['name'],'id' => $row['countryId'],'countryName' => $row['countryName']);
		}
		error_log(print_r($countriesArray,true).'COUNTRIESARRAY');
		$response = json_encode($countriesArray);
		return $this->xmlrpc->send_response($response);
	}

	function sgetFullPath($request)
	{
		$parameters = $request->output_parameters();
		//$appId = $parameters['0'];
		$boardId = 1;
		//connect DB
		$appId = 1;
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$boardIdArray = array();
		$boardIdString='';
		$queryCmd = ' SELECT t1.boardId AS lev1,t1.name as name1, t2.boardId as lev2, t2.name as name2,
            t3.boardId as lev3, t3.name as name3, t4.boardId as lev4, t4.name as name4 FROM categoryBoardTable AS t1 '.
                'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
                'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
                'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = \''.$boardId.'\'';
		$query = $this->db->query($queryCmd);
		$catArray = array();
		foreach ($query->result() as $row){
			array_push($catArray,array(
			array(
                            '0'=>array($row->lev1,'string'),
                            '1'=>array($row->lev2,'string'),
                            '2'=>array($row->lev3,'string'),
                            'name' => array($row->name1." / ".$row->name2." / ".$row->name3,'string')
			),'struct'));//close array_push


		}

		$response = array($catArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


	/*
	 * common lib method XXX to move to common library
	 */
	function getBoardChilds($boardId){
		//connect DB
		
		$this->reviewconfig->getDbConfig(1,$dbConfig);
		
		$boardIdArray = array();
		$boardIdString='';
		if($this->db == ''){
			log_message('error','getRecentEvent can not create db handle');
		}

		$queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2,
            t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.
                'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId =
                t1.boardId '.
                'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId =
                t2.boardId '.
                'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId =
                t3.boardId WHERE t1.boardId = ?';

		$query = $this->db->query($queryCmd,$boardId);
		foreach ($query->result() as $row){

			if(!array_key_exists($row->lev1,$boardIdArray) &&
			!empty($row->lev1)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev1]=$row->lev1;
				$boardIdString .= $row->lev1;

			}
			if(!array_key_exists($row->lev2,$boardIdArray) &&
			!empty($row->lev2)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev2]=$row->lev2;
				$boardIdString .= $row->lev2;

			}
			if(!array_key_exists($row->lev3,$boardIdArray) &&
			!empty($row->lev3)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev3]=$row->lev3;
				$boardIdString .= $row->lev3;

			}
			if(!array_key_exists($row->lev4,$boardIdArray) &&
			!empty($row->lev4)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev4]=$row->lev4;
				$boardIdString .= $row->lev4;

			}
		}
		if(strlen($boardIdString)==0){
			$boardIdString .= $boardId;
		}
		$response = array(array
		('QueryStatus'=>1,
                 'path'=>$boardIdString
		),
                'struct'
                );
                return $this->xmlrpc->send_response($response);
	}




	function getChildIds($boardId){
		//connect DB
		$appId = 1;
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}



		$queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2,
            t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.
                'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId =
                t1.boardId '.
                'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId =
                t2.boardId '.
                'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId =
                t3.boardId WHERE t1.boardId = ?';

		$query = $this->db->query($queryCmd,$boardId);
		foreach ($query->result() as $row){

			if(!array_key_exists($row->lev1,$boardIdArray) &&
			!empty($row->lev1)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev1]=$row->lev1;
				$boardIdString .= $row->lev1;

			}
			if(!array_key_exists($row->lev2,$boardIdArray) &&
			!empty($row->lev2)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev2]=$row->lev2;
				$boardIdString .= $row->lev2;

			}
			if(!array_key_exists($row->lev3,$boardIdArray) &&
			!empty($row->lev3)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev3]=$row->lev3;
				$boardIdString .= $row->lev3;

			}
			if(!array_key_exists($row->lev4,$boardIdArray) &&
			!empty($row->lev4)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev4]=$row->lev4;
				$boardIdString .= $row->lev4;

			}
		}
		if(strlen($boardIdString)==0){
			$boardIdString .= $boardId;
		}
		return $boardIdString;
	}



	/******* NEW WS CODE*******/
	function getListingsByClient($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$start = $parameters['2'];
		$count = $parameters['3'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = "select listing_type_id, listing_type, listing_title from listings_main where username = $clientId group by listing_type,listing_type_id limit $start, $count";
		$query = $this->db->query($queryCmd);
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'title'=>array($row->listing_title,'string'),
                            'type'=>array($row->listing_type,'string'),
                            'type_id'=>array($row->listing_type_id,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getListingsByClientForType($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$listingType = $parameters['2'];
		$start = $parameters['3'];
		$count = $parameters['4'];
		$tabStatus = $parameters['5'];

		if($tabStatus == 'live') {
			$statusClause = " AND lm.status = 'live' ";
		} else if($tabStatus == 'deleted') {
			$statusClause = " AND lm.status in ( 'live' , 'deleted' ) ";
		}

		$exclusionClause = '';
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		
		//$listingType_string = "";
		//foreach($listingType as $type) {
		//	$listingType_string = $listingType_string.","."'".$type."'";
		//}
		//$listingType_string = trim($listingType_string,",");
		
		if(in_array('university',$listingType)) {
			
			$queryCmd = "SELECT DISTINCT lm.listing_type_id, lm.listing_type, lm.listing_title, ult.city_id, cct.city_name, ult.university_location_id 
				     FROM listings_main lm
				     LEFT JOIN university_location_table ult ON ( ult.university_id = lm.listing_type_id )
				     LEFT JOIN countryCityTable cct ON cct.city_id = ult.city_id
				     WHERE username = ? ".//". $this->db->escape($clientId) ."
				    " AND listing_type in (?)". //$listingType_string.")  
				    " AND lm.status = ult.status $statusClause
				     order by listing_type_id desc limit $start, $count "; //$start, $count
			$paramArr = array($clientId, $listingType);
		}
		else {
			if(in_array('institute',$listingType)) {
				$exclusionClause = 'and lm.listing_type_id not in (select distinct institute_id from institute_university_mapping)';
			}
			
			/*$queryCmd = "(select DISTINCT lm.listing_type_id, lm.listing_type, lm.listing_title, ilt.city_id, ilt.locality_id, cct.city_name, lcm.localityName, ilt.listing_location_id as institute_location_id 
				     from listings_main lm
				     left join shiksha_institutes_locations ilt ON (ilt.listing_id = lm.listing_type_id)
				     left join countryCityTable cct ON cct.city_id = ilt.city_id
				     left join localityCityMapping lcm ON lcm.localityId = ilt.locality_id
				     where username = ". $this->db->escape($clientId) ."
				     and lm.listing_type in (".$listingType_string." )  
				     AND lm.status = ilt.status $statusClause $exclusionClause)
				     UNION  
				     (select DISTINCT lm.listing_type_id, lm.listing_type, lm.listing_title, ilt.city_id, ilt.locality_id, cct.city_name, lcm.localityName, ilt.institute_location_id 
				     from listings_main lm
				     left join institute_location_table ilt ON (ilt.institute_id = lm.listing_type_id)
				     left join countryCityTable cct ON cct.city_id = ilt.city_id
				     left join localityCityMapping lcm ON lcm.localityId = ilt.locality_id
				     where username = ". $this->db->escape($clientId) ."
				     and lm.listing_type in (".$listingType_string.")  
				     AND lm.status = ilt.status $statusClause $exclusionClause)
				     
				     order by listing_type_id desc limit $start, $count";*/
				     
			$queryCmd = "(select DISTINCT lm.listing_type_id, lm.listing_type, lm.listing_title, ilt.city_id, ilt.locality_id, cct.city_name, lcm.localityName, ilt.listing_location_id as institute_location_id 
				     from listings_main lm
				     left join shiksha_institutes_locations ilt ON (ilt.listing_id = lm.listing_type_id)
				     left join countryCityTable cct ON cct.city_id = ilt.city_id
				     left join localityCityMapping lcm ON lcm.localityId = ilt.locality_id
				     where username = ? 
				     and lm.listing_type in (?)
				     AND lm.status = ilt.status $statusClause $exclusionClause)				     				     
				     order by listing_type_id desc limit $start, $count ";
			$paramArr = array($clientId, $listingType);
		}
		
		$query = $this->db->query($queryCmd, $paramArr);
		$response = array($query->result_array(),'struct');
		error_log("####query getListingsByClientForType ".$listingType.$this->db->last_query());
		return $this->xmlrpc->send_response($response);
	}
	
	

	function sgetCityList($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$country_id = $parameters['1'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = 'SELECT * from '.$this->listingconfig->countryCityTable.' C left join stateTable  on C.state_id= stateTable.state_id WHERE  C.countryId = ?  and C.city_name is not null and C.city_name !="" and C.enabled !=1 order by city_name';
		//error_log('asdssssssssssssssssssssssssssssss'.$queryCmd);
		$query = $this->db->query($queryCmd,array($country_id));
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'cityID'=>array($row->city_id,'string'),
                            'cityName'=>array($row->city_name,'string'),
                            'state_id'=>array($row->state_id,'string'),
                            'stateName'=>array($row->state_name,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}





	function sgetInstituteList($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$city_id = $parameters['1'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		if($city_id <=0){
			$queryCmd = 'SELECT * from '.$this->listingconfig->instituteTable.' I, institute_location_table, listings_main WHERE I.institute_id = institute_location_table.institute_id and listings_main.status = "live" and listings_main.listing_type="institute" and listings_main.listing_type_id=I.institute_id and I.version= institute_location_table.version and listings_main.version= I.version group by I.institute_id ORDER BY TRIM(I.institute_name)';
		}
		else{
			$queryCmd = 'SELECT * from '.$this->listingconfig->instituteTable.' I, institute_location_table, listings_main WHERE I.institute_id = institute_location_table.institute_id and institute_location_table.city_id ='.$city_id.' and listings_main.status = "live" and listings_main.listing_type="institute" and listings_main.listing_type_id=I.institute_id and I.version= institute_location_table.version and listings_main.version= I.version ORDER BY TRIM(I.institute_name)';
		}
		$query = $this->db->query($queryCmd);
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'instituteID'=>array($row->institute_id,'string'),
                            'instituteName'=>array($row->institute_name,'string'),
                            'cityId'=>array($row->city_id,'string'),
                            'countryId'=>array($row->country_id,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sgetInstituteListInCountry($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$country_id = $parameters['1'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		if($country_id <=0){
			$queryCmd = 'SELECT * from '.$this->listingconfig->instituteTable.' I, institute_location_table, listings_main WHERE I.institute_id = listings_main.listing_type_id and listings_main.listing_type="institute" and listings_main.status = "live" and I.institute_id = institute_location_table.institute_id and I.version= institute_location_table.version and listings_main.version= I.version  group by I.institute_id';
		}
		else{
			$queryCmd = 'SELECT * from '.$this->listingconfig->instituteTable.' I, institute_location_table, listings_main WHERE  I.institute_id = listings_main.listing_type_id and listings_main.listing_type="institute" and listings_main.status = "live" and I.institute_id = institute_location_table.institute_id and institute_location_table.country_id = '.$country_id.' and I.version= institute_location_table.version and listings_main.version= I.version   group by I.institute_id';
		}
		$query = $this->db->query($queryCmd);
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'instituteID'=>array($row->institute_id,'string'),
                            'instituteName'=>array($row->institute_name,'string'),
                            'cityId'=>array($row->city_id,'string'),
                            'countryId'=>array($row->country_id,'string'),
                            'outLink'=>array($row->sourceURL,'string'),
                            'url'=>array($row->url,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sgetCountryForCity($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$city_id = $parameters['1'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = 'SELECT countryId from '.$this->listingconfig->countryCityTable.' C WHERE C.city_id = ?';
		$query = $this->db->query($queryCmd,$city_id);
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'countryId'=>array($row->countryId,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sgetEntranceExams(){
		$dbNames = array('Exam_CAT','Exam_MAT','Exam_XAT','Exam_FMS','Eam_JMET','Exam_SNAP','Exam_IIT-JEE','Exam_AIEEE',
						 'Exam_BITSAT','Exam_AIPMT','Exam_AIIMS','Exam_GRE','Exam_GMAT','Exam_SAT','Exam_TOEFL','Exam_UPSC');
		$displayNames = array('CAT','MAT','XAT','FMS','JMET','SNAP','IIT JEE','BITSAT','AIPMT','AIIMS','GRE','GMAT','SAT','TOEFL','UPSC');
		$entranceExams = array(
		array(
								'dbNames'=>array($dbNames,'struct'),
							    'displayNames'=>array($displayNames,'struct')
		),
						'struct'
						);
						//		error_log("in exams ".print_r($entranceExams,true),3,'/home/naukri/Desktop/log.txt');
						return $this->xmlrpc->send_response($entranceExams);
	}

	function sgetCourseList($request)
	{
		/* code in use, do not delete*/
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$institute_id = $parameters['1'];
		$status = $parameters['2'];
		$userId = $parameters['3'];
		$use_old_tables = $parameters['4'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$userIdClause = '';
		if(!empty($userId)) {
			
			if($use_old_tables) {
				$queryCmd = 'select *, group_concat(xyz.status) as allStatus from (SELECT C.* from course_details C, listings_main lm WHERE C.status in ("draft","queued","live") and C.institute_id = ? and lm.listing_type="course" and lm.listing_type_id = C.course_id and lm.username= ?  order by C.version desc) as xyz group by xyz.course_id';
			} else {
				$queryCmd = 'select *, group_concat(xyz.status) as allStatus from (SELECT C.* from shiksha_courses C, listings_main lm WHERE C.status in ("draft","queued","live") and C.primary_id = ? and lm.listing_type="course" and lm.listing_type_id = C.course_id and lm.username= ?  order by C.id desc) as xyz group by xyz.course_id';
			}
			
		} else {
			
			if($use_old_tables) {
				$queryCmd = 'select *, group_concat(xyz.status) as allStatus from (SELECT C.* from course_details C WHERE C.status in ("draft","queued","live") and C.institute_id = ? order by C.version desc) as xyz group by xyz.course_id';
			} else {
				$queryCmd = 'select *, group_concat(xyz.status) as allStatus from (SELECT C.* from shiksha_courses C WHERE C.status in ("draft","queued","live") and C.primary_id = ? order by C.id desc) as xyz group by xyz.course_id';
			}
			
		}
		
		$query = $this->db->query($queryCmd, array($institute_id, $userId));
		$counter = 0;
		$msgArray = array();
		
		foreach ($query->result() as $row){
			
			if($use_old_tables) {
				array_push($msgArray,array(
				array(
								'courseID'=>array($row->course_id,'string'),								
								'courseName'=>array($row->courseTitle,'string'),
								'status'=>array($row->allStatus,'string')
				),'struct'));//close array_push

		    } else {
				array_push($msgArray,array(
				array(
								'courseID'=>array($row->course_id,'string'),
								 'courseName'=>array($row->name,'string'),								
								'status'=>array($row->allStatus,'string')
				),'struct'));//close array_push
			}
			
		}
		
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
	
	function getCoursesForHomePageS($request){
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$response = $this->ListingModel->getCoursesForHomePageS($this->db,$parameters);
		return $this->xmlrpc->send_response($response);
	}

	function publishCountrySelection($request){
		error_log('listing_serverHEREHERE');
		$parameters = $request->output_parameters();
		
		//connect DB
		$this->db = $this->_loadDatabaseHandle('write');		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		error_log('SERVER');
		$response = json_encode($this->ListingModel->publishCountrySelection($this->db,$parameters));
		error_log(print_r($response,true).'RESULTSETSERVER');
		return $this->xmlrpc->send_response($response);
	}
	function publishAll($request){
		error_log('listing_serverHEREHERE');
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		$this->db = $this->_loadDatabaseHandle('write');
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		error_log('SERVER');
		$response = json_encode($this->ListingModel->publishAll($this->db,$parameters));
		error_log(print_r($response,true).'RESULTSETSERVER');
		return $this->xmlrpc->send_response($response);
	}

	function saveCountryOption($request){
		error_log('listing_serverHEREHERE');
		$parameters = $request->output_parameters();
		$params = json_decode($parameters[1],true);
		error_log('REQARR'.print_r($parameters,true));
		
		//connect DB
		
		$appId = $parameters['0'];
		
		$this->db = $this->_loadDatabaseHandle('write');
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		error_log('SERVER');
		$response = json_encode($this->ListingModel->saveCountryOption($this->db,$params));
		error_log(print_r($response,true).'RESULTSETSERVER');
		return $this->xmlrpc->send_response($response);
	}
	function saveTopOption($request){
		error_log('listing_serverHEREHERE');
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		error_log('SERVER');
		$response = json_encode($this->ListingModel->saveTopOption($this->db,$parameters));
		error_log(print_r($response,true).'RESULTSETSERVER');
		return $this->xmlrpc->send_response($response);
	}

	function getTopInstitutes($request){
		error_log('listing_serverHEREHERE');
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		error_log('SERVER');
		$response = $this->ListingModel->getTopInstitutes($this->db,$parameters);
		error_log(print_r($response,true).'RESULTSETSERVER');
		return $this->xmlrpc->send_response($response);
	}

	function getListingsForNaukriShiksha($request){
		error_log('listing_server');
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$response = $this->ListingModel->getListingsForNaukriShiksha($this->db,$parameters);
		return $this->xmlrpc->send_response($response);
	}

        function getCoursesInformation($request) {
                $parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$response = $this->ListingModel->getCoursesInformation($this->db,$parameters);
		return $this->xmlrpc->send_response($response);
        }

	function getInstitutesForHomePageS($request){
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$response = $this->ListingModel->getInstitutesForHomePageS($this->db,$parameters);
		return $this->xmlrpc->send_response($response);
	}
	function getNumberOfMainInstiS($request){
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$response = $this->ListingModel->getNumberOfMainInstiS($this->db,$parameters);
		return $this->xmlrpc->send_response($response);
	}



	function getScholarshipsForHomePageS($request){
		$parameters = $request->output_parameters();
		
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$response = $this->ListingModel->getScholarshipsForHomePageS($this->db,$parameters);
		return $this->xmlrpc->send_response($response);
	}

	function sGetListingsByFilters($request)
	{
		$parameters = $request->output_parameters();
		$filters = $parameters['1'];

		//connect DB
		
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		//Query Command for Insert in the Listing Main Table
		$add_where = '';
		if(isset($filters['start_time'] ) && ($filters['start_time'] != '')) {
			$start_time = date('Y-m-d G:i:s',$filters['start_time']);
			$add_where .= 'listings_main.submit_date > "'.$start_time.'"';
			if(isset($filters['end_time'] ) && ($filters['end_time'] != '')) {
				$end_time = date('Y-m-d G:i:s',$filters['end_time']);
				$add_where .= ' and listings_main.submit_date < "'.$end_time.'"';
			}
		}
		if(isset($filters['username'] ) && ($filters['username'] != '')) {
			if(isset($add_where ) && ($add_where != '')) {
				$add_where .= ' and listings_main.username ="'.$filters['username'].'"';
			} else {
				$add_where .= ' listings_main.username ="'.$filters['username'].'"';
			}
		}
		if(isset($filters['category_id'] ) && ($filters['category_id'] != '')) {
			switch($filters['listing_type']) {
				case 'course' :
					$add_category_where = ' and listing_category_table.listing_type="course" and listing_category_table.version =  listings_main.version and listing_category_table.category_id in ('.$filters['category_id'].')';
					break;
				case 'institute' :
					$add_category_where = ' and listing_category_table.listing_type="institute" and listing_category_table.version =  listings_main.version and listing_category_table.category_id in ('.$filters['category_id'].')';
					break;
				case 'scholarship' :
					$add_category_where = ' scholarship_category_table.category_id in ('.$filters['category_id'].')';
					break;
				case 'notification' :
					$add_category_where = ' admission_notification_category_table.category_id in ('.$filters['category_id'].')';
					break;
			}
			if(isset($add_where ) && ($add_where != '')) {
				$add_where .= "and $add_category_where";
			} else {
				$add_where .= " $add_category_where";
			}
		}


		switch($filters['listing_type']) {
			case 'course' :
				// $queryCmd = 'SELECT listings_main.listing_id as listing_id, course_details.course_id as type_id, course_details.courseTitle as title, "" as sh_desc ,listings_main.viewCount,listings_main.submit_date as date from course_details, listings_main,listing_category_table where listings_main.listing_type = "course" and listings_main.listing_type_id = course_details.course_id  and listing_category_table.listing_type_id = course_details.course_id and '.$add_where.' and listings_main.version = course_details.version group by course_details.course_id';
                                $queryCmd = 'SELECT listings_main.listing_id as listing_id, listings_main.listing_type_id as type_id, listing_title as title, "" as sh_desc ,listings_main.viewCount,listings_main.submit_date as date from listings_main, listing_category_table where listings_main.listing_type = "course" and listing_category_table.listing_type_id = listings_main.listing_type_id and '.$add_where.' group by listings_main.listing_type_id ';

				break;
			case 'institute' :
				$queryCmd = 'SELECT listings_main.listing_id as listing_id, institute.institute_id as type_id, institute.institute_name as title, "" as sh_desc ,listings_main.viewCount, listings_main.submit_date as date  from institute, listings_main, listing_category_table  where listings_main.listing_type = "institute" and listings_main.listing_type_id = institute.institute_id  and listing_category_table.listing_type_id = institute.institute_id and '. $add_where.' and listings_main.version = institute.institute_id group by institute.institute_id';
				break;
			case 'scholarship' :
				//$queryCmd = 'SELECT course_details.courseTitle as title, course_details.overview as sh_desc from course_details, listings_main where listings_main.listing_type = "course" and listings_main.listing_type_id = course_details.course_id';
				$queryCmd = 'SELECT listings_main.listing_id as listing_id, scholarship.scholarship_id as type_id,scholarship.scholarship_name as title, scholarship.short_desc as sh_desc ,listings_main.viewCount, listings_main.submit_date as date from scholarship, listings_main, scholarship_category_table where listings_main.listing_type = "scholarship" and listings_main.listing_type_id = scholarship.scholarship_id  and scholarship_category_table.scholarship_id = scholarship.scholarship_id and '. $add_where.' group by scholarship.scholarship_id';
				break;
			case 'notification' :
				$queryCmd = 'SELECT listings_main.listing_id as listing_id, admission_notification.admission_notification_id as type_id , admission_notification.admission_notification_name as title, admission_notification.short_desc as sh_desc ,listings_main.viewCount, listings_main.submit_date as date  from admission_notification, listings_main, admission_notification_category_table where listings_main.listing_type = "notification" and listings_main.listing_type_id = admission_notification.admission_notification_id  and admission_notification_category_table.admission_notification_id = admission_notification.admission_notification_id and '. $add_where;
				break;
			default:
				$queryCmd = 'SELECT listings_main.listing_title as title, listings_main.short_desc as sh_desc from listings_main ';
				break;
		}
		//$queryCmd .= " ORDER BY listings_main.submit_date desc LIMIT ".$filters['start'].','.$filters['number_of_results'];
		$queryCmd .= " ORDER BY listings_main.submit_date desc ";


		if ((isset($filters['saved']) && $filters['saved']!="") && (isset($filters['username']) && $filters['username']!=""))
		{
			$queryCmd = "( $queryCmd )";
			$queryCmd .= " union (SELECT listing_id, listing_type_id as type_id, listing_title as title, '' as sh_desc,viewCount, time_created as date FROM listings_main, saved_product WHERE listings_main.listing_type_id =saved_product.id and saved_product.user_id=".$filters['username']." and listings_main.status='live' and    listing_type='".$filters['listing_type']."' and type='".$filters['listing_type']."' and username<>".$filters['username'].")";

			$queryCmd = "select * from ($queryCmd) as tab order by date";
		}
		$query = $this->db->query($queryCmd);
		$counter = 0;
		$msgArray = array();
		$start = $filters['start'];
		$end = $filters['start'] + $filters['number_of_results'];
		$cnt = 0;
		foreach ($query->result() as $row){
			if($cnt >= $start && $cnt < $end){
				array_push($msgArray,array(
				array(
                                                                'listing_id'=>array($row->listing_id,'string'),
                                                                'type_id'=>array($row->type_id,'string'),
                                                                'Title'=>array($row->title,'string'),
                                                                'listing_type'=>array($filters['listing_type'],'string'),
                                                                'Description'=>array($row->sh_desc,'string'),
                                                                'Views'=>array($row->viewCount,'string')
				),'struct'));//close array_push
			}
			$cnt++;
		}
		$finalResultArray = array();
		array_push($finalResultArray,array(
		array(
                                                        'totalListings'=>array($cnt,'string'),
                                                        'listingsArr'=>array($msgArray,'struct')
		),'struct'));//close array_push
		$response = array($finalResultArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	//Server API to add a admission
	function sadd_admission($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0']['0'];
		$formVal = $parameters['1'];
		$eligibility = $parameters['2'];
		//connect DB
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == ''){
			error_log_shiksha("ADD LISTING Admission : cannot create db handle");
		}
		if ($formVal['dataFromCMS']=="dataFromCMS") {
			$moderated = "moderated";
		} else {
			$moderated = "unmoderated";
		}

		if (isset($formVal['packType'])) {
			$packType =$formVal['packType'];
		} else {
			$packType = '-10';
		}

		//Query Command for Insert in the course details Table
		$data =array();
		$data = array(
                'admission_notification_name'=>$formVal['admission_notification_name'],
                'short_desc'=>$formVal['short_desc'],
                'admission_year'=>$formVal['admission_year'],
                'application_brochure_start_date'=>$formVal['application_brochure_start_date'],
                'application_brochure_end_date'=>$formVal['application_brochure_end_date'],
                'application_end_date'=>$formVal['application_end_date'],
                'application_procedure'=>$formVal['application_procedure'],
                'fees'=>$formVal['fees'],
                'entrance_exam'=>$formVal['entrance_exam'],
                'username'=>$formVal['username'],
                'contact_name'=>$formVal['contact_name'],
                'contact_fax' =>$formVal['contact_fax'],
                'contact_email'=>$formVal['contact_email'],
                'contact_cell'=>$formVal['contact_cell'],
                'address' =>$formVal['contact_address']
		);
		$queryCmd = $this->db->insert_string('admission_notification',$data);
		$query = $this->db->query($queryCmd);
		$admission_not_id = $this->db->insert_id();
		//Query Command for Insert in the Listing Main Table
		$data =array();
		$format = 'DATE_ATOM';
		$data = array(
                'listing_type_id'=>$admission_not_id,
                'listing_title'=>$formVal['admission_notification_name'],
                'short_desc'=>$formVal['short_desc'],
                'username'=>$formVal['username'],
                'threadId'=>$formVal['threadId'],
                'listing_type'=>'notification',
                'username' => $formVal['username'],
                'last_modify_date'=>standard_date($format,time()),
                'requestIP'=>$formVal['requestIP'],
                'moderation_flag' => $moderated,
                'pack_type' => $packType
		);
		$queryCmd = $this->db->insert_string('listings_main',$data);
		$query = $this->db->query($queryCmd);
		$listing_id = $this->db->insert_id();
		$response = array(array
		('QueryStatus'=>$query,
                 'Admission_notification_id'=>$admission_not_id,
                 'type_id'=>$admission_not_id,
                 'listing_type'=>"notification",
                 'Listing_id'=>$listing_id
		),
                'struct'
                );
                //update User point system
                $queryCmd = 'update userPointSystem set userPointValue=userPointValue+20 where userId='.$formVal['username'];
                if(!$this->db->query($queryCmd)){
                	error_log_shiksha("ADD LISTING Admission : update user point failed");
                }
                $catArr = array();
                if(isset($formVal['category_id']) && $formVal['category_id'] != ""){
                	$categories = $formVal['category_id'];
                	$catArr = explode(',',$categories);
                	$numOfCats = count($catArr);
                	for($i = 0; $i < $numOfCats ; $i++){
                		//Query Command for Insert in the course category Table
                		$queryCmd = "INSERT into admission_notification_category_table (admission_notification_id,category_id) values ($admission_not_id,$catArr[$i]) on duplicate key update category_id =".$catArr[$i];
                		$query = $this->db->query($queryCmd);
                	}
                }

                $queryCmd = "INSERT into institute_examinations_mapping_table (institute_id,admission_notification_id) values (".$formVal['institute_id'].",".$admission_not_id.")";
                $query = $this->db->query($queryCmd);
                if($formVal['entrance_exam'] == "yes"){
                	$this->load->model('ExamModel','',$dbConfig);
                	if(isset($formVal['tests_required']) && strlen($formVal['tests_required']) > 0 )
                	{
                		$examsArr = explode(",",$formVal['tests_required']);
                		$this->ExamModel->makeEntityExamsMapping($admission_not_id, $examsArr,'notification','required');
                	}
                	if($formVal['tests_required_other'] == 'true'){
                		$examData =array();
                		$examData['listingType'] = 'notification';
                		$examData['listingTypeId'] = $admission_not_id;
                		$examData['typeOfMap'] = 'required';
                		$examData['exam_name'] = $formVal['exam_name'];
                		$examData['exam_date'] = $formVal['exam_date'];
                		$examData['exam_desc'] = $formVal['exam_desc'];
                		$examData['exam_duration'] = $formVal['exam_duration'];
                		$examData['exam_timings'] = $formVal['exam_timings'];
                		$examData['numOfCentres'] = $formVal['numOfCentres'];
                		for($i = 0 ; $i < $formVal['numOfCentres']; $i++)
                		{
                			$examData['address_line1'.$i] = $formVal['address_line1'.$i];
                			$examData['address_line2'.$i] = $formVal['address_line2'.$i];
                			$examData['city_id'.$i] = $formVal['city_id'.$i];
                			$examData['country_id'.$i] = $formVal['country_id'.$i];
                			$examData['zip'.$i] = $formVal['zip'.$i];
                		}
                		$newExamId = $this->ExamModel->insertOtherExam($examData);
                	}
                }

                if(isset($eligibility) && count($eligibility) > 0){
                	foreach($eligibility as $key=>$val){
                		$data =array();
                		$data = array(
                                'admission_notification_id'=>$admission_not_id,
                                'eligibility_criteria'=>$key,
                                'eligibility_criteria_values'=>$val
                		);
                		$queryCmd = $this->db->insert_string('admission_notification_eligibility_table',$data);
                		$query = $this->db->query($queryCmd);
                	}
                }

                //Alert Mobile integration

                $queryCmd = 'select country_id from institute_location_table where institute_id = '.$formVal['institute_id'].' and status="live" group by country_id';
                $query = $this->db->query($queryCmd);
                $countryArr = array();
                $i =0;
                foreach ($query->result() as $row){
                	$countryArr[$i] = $row->country_id;
                	$i++;
                }
                $alertStatus =  $this->alertMobileFeeder('notification',$admission_not_id,$formVal['admission_notification_name'],$catArr,$countryArr);
                return $this->xmlrpc->send_response($response);
	}

	function sadd_scholarship($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0']['0'];
		$formVal = $parameters['1'];
		$eligibility = $parameters['2'];
		//connect DB
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == ''){
			error_log_shiksha("ADD LISTING Scholarship : cannot create db handle");
		}
		if ($formVal['dataFromCMS']=="dataFromCMS") {
			$moderated = "moderated";
		} else {
			$moderated = "unmoderated";
		}
		if (isset($formVal['packType'])) {
			$packType =$formVal['packType'];
		} else {
			$packType = '-10';
		}
		$data =array();
		$data = array(
                'scholarship_name'=>$formVal['scholarship_name'],
                'short_desc'=>$formVal['short_desc'],
                'num'=>$formVal['num'],
                'levels'=>$formVal['levels'],
                'address_line1'=>$formVal['address_line1'],
                'address_line2'=>$formVal['address_line2'],
                'city_id'=>$formVal['city_id'],
                'country_id'=>$formVal['country_id'],
                'zip'=>$formVal['zip'],
                'application_procedure'=>$formVal['application_procedure'],
                'selection_process'=>$formVal['selection_process'],
                'segment'=>$formVal['segment'],
                'other_segment'=>$formVal['other_segment'],
                'value'=>$formVal['value'],
                'period_of_awards'=>$formVal['period_of_awards'],
                'institution'=>$formVal['institution'],
                'username'=>$formVal['username'],
                'institute_id'=>$formVal['institute_id'],
                'contact_email'=>$formVal['contact_email'],
                'contact_name'=>$formVal['contact_name'],
                'contact_cell'=>$formVal['contact_cell'],
                'contact_fax' => $formVal['contact_fax'],
                'address'=>$formVal['contact_address'],
                'last_date_submission' => $formVal['last_date_submission']
		);
		$queryCmd = $this->db->insert_string('scholarship',$data);
		$query = $this->db->query($queryCmd);
		$scholarship_id = $this->db->insert_id();
		//Query Command for Insert in the Listing Main Table
		$data =array();
		$format = 'DATE_ATOM';
		$data = array(
                'listing_type_id'=>$scholarship_id,
                'listing_title'=>$formVal['scholarship_name'],
                'username'=>$formVal['username'],
                'threadId'=>$formVal['threadId'],
                'listing_type'=>'scholarship',
                'requestIP'=>$formVal['requestIP'],
                'last_modify_date'=>standard_date($format,time()),
                'moderation_flag' => $moderated,
                'pack_type' => $packType
		);
		$queryCmd = $this->db->insert_string('listings_main',$data);
		$query = $this->db->query($queryCmd);
		$listing_id = $this->db->insert_id();
		$response = array(array
		('QueryStatus'=>$query,
                 'scholarship_id'=>$scholarship_id,
                 'Listing_id'=>$listing_id,
                 'type_id'=>$scholarship_id,
                 'listing_type'=>"scholarship",
		),
                'struct'
                );
                //update User point system
                $queryCmd = 'update userPointSystem set userPointValue=userPointValue+20 where userId='.$formVal['username'];
                if(!$this->db->query($queryCmd)){
                	error_log_shiksha("ADD LISTING Scholarship : updating user points system failed");
                }

                $catArr = array();
                if(isset($formVal['category_id']) && $formVal['category_id'] != ""){
                	$categories = $formVal['category_id'];
                	$catArr = explode(',',$categories);
                	$numOfCats = count($catArr);
                	for($i = 0; $i < $numOfCats ; $i++){
                		//Query Command for Insert in the course category Table
                		$queryCmd = "INSERT into scholarship_category_table (scholarship_id,category_id) values ($scholarship_id,$catArr[$i]) on duplicate key update category_id =".$catArr[$i];
                		$query = $this->db->query($queryCmd);
                	}
                }

                if ($formVal['numoflocations']>0) {
                	for ($i = 0;$i<$formVal['numoflocations'];$i++) {
                		$queryCmd = 'INSERT into institute_scholarship_mapping_table (institute_id,scholarship_id) values ('.$formVal['institute_id'.$i].','.$scholarship_id.')  on duplicate key update institute_id= '.$formVal['institute_id'.$i];
                		$query = $this->db->query($queryCmd);
                	}
                }
                if(isset($eligibility) && count($eligibility) > 0){
                	foreach($eligibility as $key=>$val){
                		$data =array();
                		$data = array(
                        'scholarship_id'=>$scholarship_id,
                        'eligibility_criteria'=>$key,
                        'eligibility_criteria_values'=>$val
                		);
                		$queryCmd = $this->db->insert_string('scholarship_eligibility_table',$data);
                		//$queryCmd .= " on duplicate key update eligibility_criteria_values =  '".$val."'";
                		$query = $this->db->query($queryCmd);
                	}
                }
                //Alert Mobile integration
                if ($formVal['numoflocations']>0)
                {
                	$queryCmd = 'select country_id from institute_location_table where institute_id in ( '.$formVal['institute_id'].') and status="live" group by country_id';
                	$query = $this->db->query($queryCmd);
                	$countryArr = array();
                	$i =0;
                	foreach ($query->result() as $row){
                		$countryArr[$i] = $row->country_id;
                		$i++;
                	}
                }
                $alertStatus =  $this->alertMobileFeeder('scholarship',$scholarship_id,$formVal['scholarship_name'],$catArr,$countryArr);
                error_log_shiksha("ADD LISTING Scholarship : RESPONSE _server====>".print_r($response,TRUE));
                return $this->xmlrpc->send_response($response);
	}




	//Server API to add a Course
	function supdateMediaContent($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0']['0'];
		$formVal = $parameters['1'];
		$formVal2 = $parameters['2'];
		//connect DB
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		switch ($formVal['listing_type']) {
			case "course":

				switch($formVal['media_type']){
					case 'photos':
						$queryCmd = 'INSERT INTO course_photos_table (course_id,name,photo_id,url,thumburl) VALUES ('.$formVal['type_id'].',"'.$formVal2['title'].'",'.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'")';
						break;
					case 'videos':
						$queryCmd = 'INSERT INTO course_videos_table (course_id,name,video_id,url,thumburl) VALUES ('.$formVal['type_id'].',"'.$formVal2['title'].'",'.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'")';
						break;
					case 'doc':
						$queryCmd = 'INSERT INTO course_doc_table (course_id,name,doc_id,url,thumburl) VALUES ('.$formVal['type_id'].',"'.$formVal2['title'].'",'.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'")';
						break;
				}
				$query = $this->db->query($queryCmd);
				$recent_id[$i] = $this->db->insert_id();
				break;
			case "institute":
				switch($formVal['media_type']){
					case 'photos':
						$queryCmd = 'INSERT INTO institute_photos_table (institute_id,name , photo_id,url,thumburl) VALUES ('.$formVal['type_id'].',"'.$formVal2['title'].'",'.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'")';
						break;
					case 'videos':
						$queryCmd = 'INSERT INTO institute_videos_table (institute_id,name,video_id,url,thumburl) VALUES ('.$formVal['type_id'].',"'.$formVal2['title'].'",'.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'")';
						break;
					case 'doc':
						$queryCmd = 'INSERT INTO institute_doc_table (institute_id,name,doc_id,url,thumburl) VALUES ('.$formVal['type_id'].',"'.$formVal2['title'].'",'.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'")';
						break;
				}
				$query = $this->db->query($queryCmd);
				$recent_id[$i] = $this->db->insert_id();
				break;
			case "notification" :
				$queryCmd = 'INSERT INTO admission_notification_doc_table  (admission_notification_id,doc_id,url,thumburl,name) VALUES ('.$formVal['type_id'].','.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'","'.$formVal2['title'].'")';

				$query = $this->db->query($queryCmd);
				$recent_id[$i] = $this->db->insert_id();
				break;
			case "scholarship" :
				$queryCmd = 'INSERT INTO scholarship_doc_table  (scholarship_id,doc_id,url,thumburl,name) VALUES ('.$formVal['type_id'].','.$formVal2['mediaid'].',"'.$formVal2['url'].'","'.$formVal2['thumburl'].'","'.$formVal2['title'].'")';

				$query = $this->db->query($queryCmd);
				$recent_id[$i] = $this->db->insert_id();
				break;
		}
		$response = array(array
		(
                 'recent_id'=>$recent_id
		),
                'struct'
                );
                return $this->xmlrpc->send_response($response);
	}

	function sgetCountryCityTree($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$queryCmd = 'SELECT * from '.$this->listingconfig->countryCityTable.' ,countryTable where countryCityTable.countryId = countryTable.countryId  and countryCityTable.city_name is not null and countryCityTable.city_name !="" order by countryTable.countryId';
		$query = $this->db->query($queryCmd);
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
                            'countryId'=>array($row->countryId,'string'),
                            'countryName'=>array($row->name,'string'),
                            'cityID'=>array($row->city_id,'string'),
                            'cityName'=>array($row->city_name,'string')
			),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}




	/******* NEW WS CODE*******/


	function sdelete_listing($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$this->user_id = $parameters['3'];
		$response = $this->updateListingStatus($type_id,$listing_type,'deleted');
		
		return $this->xmlrpc->send_response($response);
	}

	//Server API to get status & versions info about a listing
	function getCurrentStatusVersions($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];

		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmd =  "select status, group_concat(version) as versions from listings_main where listing_type= ? and listing_type_id= ? group by status ";
		$query = $this->db->query($queryCmd,array($listing_type,$type_id));
		$response = array();
		foreach($query->result_array() as $row){
			$response["status_".$row->status] = $row->versions;
			$versionArr = explode(",",$row->versions);
			$numVersion = count($versionArr);
			$status = $row->status;
			for($i = 0; $i < $numVersion; $i++){
				$response["version_".$versionArr[$i]] = $status;
			}
		}
		return $this->xmlrpc->send_response(array(base64_encode(serialize($response)),'string'));
	}

	//Server API to Delete a Listing
	function deleteDraftOrQueued($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$old_status = $parameters['3'];
		$audit = unserialize(base64_decode($parameters['4']));
		
		$this->db = $this->_loadDatabaseHandle('write');
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}

		$resultSet = array();
		switch($listing_type){
			case 'course':
				$deleted_version = $this->updateCourseStatus($this->db,$type_id,$old_status,'deleted',-1,$audit);
				error_log($deleted_version);
				break;
			case 'institute':
				$deleted_version = $this->updateInstituteStatus($this->db,$type_id,$old_status,'deleted',-1,$audit);
				error_log($deleted_version);
				if($deleted_version > 0)
				{
					//check if any draft , live, queued exists;
					$queryCmd = "select version from listings_main where listing_type='institute' and listing_type_id = $type_id and status in ('draft','queued','live') limit 1 ";
					error_log($queryCmd);
					$query = $this->db->query($queryCmd);
					if($query->num_rows() <= 0){
						//delete all corr. courses also
						$queryCmd = " select distinct course_id as course_id from  course_details where institute_id=$type_id ";
						error_log($queryCmd);
						$query = $this->db->query($queryCmd);
						foreach ($query->result() as $row){
							$deleted_version_draft = $this->updateCourseStatus($this->db,$row->course_id,'draft','deleted');
							$deleted_version_queued = $this->updateCourseStatus($this->db,$row->course_id,'queued','deleted');
							if($deleted_version_draft > 0 || $deleted_version_queued > 0)
							{
								$listings = array();
								$listings[0]['type'] = 'course';
								$listings[0]['typeId'] =$row->course_id;
								$listings[0]['version'] = $deleted_version_queued>$deleted_version_draft?$deleted_version_queued:$deleted_version_draft;
								$resultSet = array_merge($resultSet,$this->ListingModel->getMetaInfo($this->db,$listings,""));
							}
						}
					}
				}
				break;
		}
		if($deleted_version > 0){
			//check if any draft , live, queued exists;
			$queryCmd = "select version from listings_main where listing_type='$listing_type' and listing_type_id = $type_id and status in ('draft','queued','live') limit 1 ";
			error_log($queryCmd);
			$query = $this->db->query($queryCmd);
			if($query->num_rows() <= 0){
				$listings = array();
				$listings[0]['type'] = $listing_type;
				$listings[0]['typeId'] = $type_id;
				$listings[0]['version'] = $deleted_version;
				$resultSet = array_merge($resultSet,$this->ListingModel->getMetaInfo($this->db,$listings,""));
			}
			$response['info'] = $resultSet;
			$response['status'] = $deleted_version;
		}
		else{
			$response['status'] = 0;
		}
		$response = array(base64_encode(serialize($response)),'string');
		return $this->xmlrpc->send_response($response);
	}
	//Server API to Delete a Listing
	function updateListingStatus($type_id,$listing_type,$status)
	{
		$appId=1;
		//connect DB
		
		
		$this->db = $this->_loadDatabaseHandle('write');
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		//Amit Singhal : Soft-deleting Category Page Data
		if((($status == 'cancelled') || ($status == 'deleted')) && (($listing_type == 'course')||($listing_type == 'institute'))){
						$this->deleteCategoryPageData($this->db,$type_id,$listing_type);						
						$this->deleteListingEbrochureInfo($this->db,$type_id,$listing_type);
						
		}
		//Query Command for soft-deleting a Listing for a Listing ID
		switch($listing_type){
			case 'notification':
			case 'scholarship':
				$queryCmd = "UPDATE ".$this->listingconfig->listingMainTable." SET status = '$status' WHERE listing_type ='$listing_type' and listing_type_id=$type_id";
				error_log($queryCmd);
				$query = $this->db->query($queryCmd);
				break;
			case 'course':
				$draftVersion = $this->updateCourseStatus($this->db,$type_id,'draft',$status);
				$liveVersion = $this->updateCourseStatus($this->db,$type_id,'live',$status);
				$queuedVersion = $this->updateCourseStatus($this->db,$type_id,'queued',$status);
				if($draftVersion > 0 || $liveVersion > 0 || $queuedVersion > 0)
				{
					$query = 1;
				}
				break;
			case 'institute':
				$draftVersion = $this->updateInstituteStatus($this->db,$type_id,'draft',$status);
				$liveVersion = $this->updateInstituteStatus($this->db,$type_id,'live',$status);
				$queuedVersion = $this->updateInstituteStatus($this->db,$type_id,'queued',$status);
				if($draftVersion > 0 || $liveVersion > 0 || $queuedVersion > 0)
				{
					$query = 1;
				}
				break;
		}

		
		$response = array(array('QueryStatus'=>$query,'int'),'struct');
		//Deleting from search index
		$this->load->library('listing_client');
		$listingCient = new Listing_client();
		$indexResponse = $listingCient->deleteListing($appId,$listing_type,$type_id);

		//delete topic
		$deleteTopic = $this->deleteTopicForListing($appId,$listing_type,$type_id);

		//delete event
		$deleteEvent = $this->deleteEventsForListing($appId,$listing_type,$type_id);

		if($listing_type == "institute") {
			//Query Command for soft-deleting the courses if recently deleted listing is institute
			$queryCmd = "UPDATE institute SET status = '$status' WHERE institute_id=$type_id";
			$query = $this->db->query($queryCmd);
			$queryCmd = "select course_id from  course_details where institute_id=$type_id";
			$query = $this->db->query($queryCmd);
			foreach ($query->result() as $row){
				//Query Command for soft-deleting the courses if recently deleted listing is institute
				//$queryCmd = "UPDATE ".$this->listingconfig->listingMainTable." SET status = '$status' WHERE listing_type ='course' and listing_type_id=".$row->course_id;
				//$query = $this->db->query($queryCmd);
                                $draftVersion = $this->updateCourseStatus($this->db,$row->course_id,'draft',$status);
				$liveVersion = $this->updateCourseStatus($this->db,$row->course_id,'live',$status);
				$queuedVersion = $this->updateCourseStatus($this->db,$row->course_id,'queued',$status);
				$indexResponse = $listingCient->deleteListing($appId,"course",$row->course_id);
			}

			//Query Command for soft-deleting the admission notification if recently deleted listing is institute
			$queryCmd = "select admission_notification_id from institute_examinations_mapping_table where institute_id=$type_id";
			$query = $this->db->query($queryCmd);
			foreach ($query->result() as $row){
				//Query Command for soft-deleting the courses if recently deleted listing is institute
				$queryCmd = "UPDATE ".$this->listingconfig->listingMainTable." SET status = '$status' WHERE listing_type ='notification' and listing_type_id=".$row->admission_notification_id;
				$query = $this->db->query($queryCmd);
				$indexResponse = $listingCient->deleteListing($appId,"notification",$row->admission_notification_id);
				//delete event
				$deleteEvent = $this->deleteEventsForListing($appId,"notification",$row->admission_notification_id);
			}
		}
		if($listing_type == 'course'){
			$this->load->model('ListingModel','',$dbConfig);
			//$this->ListingModel->sanitizeInstituteCategories($this->db,'course',$type_id);

			//Re-index the Institute Listing for Sanity purposes
			$queryCmd = 'select institute_id from institute_courses_mapping_table where course_id='.$type_id;
			$query =  $this->db->query($queryCmd);
			foreach ($query->result() as $row){
				$instituteId = $row->institute_id;
				$this->indexListing('institute',$instituteId);
			}
		}
		return $response;
	}

	function deleteEventsForListing($appId=1, $listing_type,$type_id)
	{
		$this->load->library('event_cal_client');
		$eventClient = new Event_cal_client();
		$indexResponse  = array();
		switch($listing_type){
			case 'notification':
				$indexResponse = $eventClient->deleteListingEvent($appId,$type_id,$listing_type);
				break;
		}
		return $indexResponse;
	}


	function deleteTopicForListing($appId, $listing_type,$type_id)
	{
		//connect DB
		
		
		$this->db = $this->_loadDatabaseHandle('write');

		$this->load->library('message_board_client');
		$mbClient = new message_board_client();
		if($this->db == ''){
			log_message('error','report abuse can not create db handle');
		}
		switch($listing_type){
			case 'institute':
				$query = "select lm.threadId as thread_id,lc.category_id as category_id from listings_main lm INNER JOIN institute i ON(lm.listing_type_id = i.institute_id) INNER JOIN listing_category_table lc ON (lc.listing_type_id = i.institute_id)  where lm.listing_type='institute' and  lc.listing_type = 'institute' and lc.status='live' and i.institute_id = ? limit 0,1";
				break;
			case 'course':
				$query = "select lm.threadId as thread_id,lc.category_id as category_id from listings_main lm INNER JOIN course_details c ON(lm.listing_type_id = c.course_id ) INNER JOIN listing_category_table lc ON(lc.listing_type_id = c.course_id) where lm.listing_type='course' and  lc.listing_type = 'course' and lc.status='live'and c.course_id = ? limit 0,1 ";
				break;
		}
		if(isset($query) && strlen($query) > 10){
			$query = $this->db->query($query,$type_id);
			$indexResponse = '';
			foreach ($query->result() as $row){
				//Query Command for soft-deleting the courses if recently deleted listing is institute
				$indexResponse = $mbClient->deleteTopic($appId,$row->category_id,$row->thread_id);
			}
			return $indexResponse;
		}
		else{
			return array();
		}
	}


	//Server API to Delete a Listing
	function reportAbuse($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		//connect DB
		if(!is_numeric($type_id)) {
			return false;
		}
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','report abuse can not create db handle');
		}
		//Query Command for soft-deleting a Listing for a Listing ID
		$queryCmd = "UPDATE ".$this->listingconfig->listingMainTable." SET abuseCount = abuseCount+1 WHERE listing_type ='$listing_type' and listing_type_id=$type_id and status='live'";
		$query = $this->db->query($queryCmd);

		$response = array(array('QueryStatus'=>$query,'int'),'struct');
		return $this->xmlrpc->send_response($response);
	}




	//TOBE: moved to leads server
	function sGetUserReqInfoStatus($request){
		$parameters = $request->output_parameters();
		$userId=$parameters['1'];
		$listing_type=$parameters['2'];
		$listing_type_id=$parameters['3'];
		$appId=1;
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getBlogsFeeds can not create db handle');
		}
		$queryCmd = "select * from  tReqInfo where userId= ? and listing_type = ?  and listing_type_id = ?";
		$query = $this->db->query($queryCmd,array($userId,$listing_type,$listing_type_id));
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sInsertReqInfo($request)
	{
		$parameters = $request->output_parameters();
		$formVal = $parameters['1'];
		//connect DB
		
		$appId = $parameters['0']['0'];
		
		$this->db = $this->_loadDatabaseHandle('write');
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		//Query Command for Insert in the Listing Main Table
		$data =array();
		$data = array(
                'listing_type_id'=>$formVal['listing_type_id'],
                'listing_type'=>$formVal['listing_type'],
                'userId'=>$formVal['userId'],
                'displayName'=>$formVal['displayName'],
                'message'=>$formVal['message'],
                'email'=>$formVal['contact_email'],
                'action'=>$formVal['action'],
                'contact_cell'=>$formVal['contact_cell']
		);
		$queryCmd = $this->db->insert_string('tReqInfo',$data);
		$query = $this->db->query($queryCmd);
		$recent_id = $this->db->insert_id();
		$response = array(array
		('QueryStatus'=>$query,
                 'leadId'=>$recent_id
		),
                'struct'
                );

                //update User point system
                $queryCmd = 'update userPointSystem set userPointValue=userPointValue+10 where userId='.$formVal['userId'];
                if(!$this->db->query($queryCmd)){
                	error_log_shiksha("REQUEST INFO : updating user points system failed".$formVal['username']." points : 10");
                }
                return $this->xmlrpc->send_response($response);
	}

	function sInsertCity($request)
	{

		$parameters = $request->output_parameters();
		$formVal = $parameters['1'];
		
		$appId = $parameters['0']['0'];
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$chkQuery = "SELECT city_id FROM countryCityTable WHERE city_name = ? AND countryId = ?";
		$exQuery  = $this->db->query($chkQuery, array($formVal['city_name'], $formVal['country_id']));
		if ($this->db->affected_rows()>0) {
			$cityId = $exQuery->first_row()->city_id;
			return $this->xmlrpc->send_response(array($cityId,'int'));
		} else {
			$data = array();
			$data = array (
                    'countryId' =>$formVal['country_id'],
                    'city_name'=>$formVal['city_name'],
                    'enabled' =>"1"
                    );
                    $queryCmd = $this->db->insert_string('countryCityTable',$data);
                    $query = $this->db->query($queryCmd);
                    $cityId= $this->db->insert_id();
		}
		$response = array ($cityId,'int');
		// apc_store("city_flag","0");
		// apc_store("city2Country_flag","0");
                $this->cacheLib->store("city_flag","0");
                $this->cacheLib->store("city2Country_flag","0");
		$this->makeApcCityMap();
		$this->makeApcCityCountryMap();
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client();
		$ListingClientObj->updateApcForSearch();
		return $this->xmlrpc->send_response($response);
	}
	//This function take Mysql date and converts it in Solr Date
	function dateFormater($date)
	{
		$datesp=explode(" ",$date);
		$newdate=$datesp[0]."T".$datesp[1]."Z";
		return $newdate;
	}

	function sGetCitiesWithCollege($request)
	{
		$parameters = $request->output_parameters();
		$formVal = $parameters['1'];
		
		
		
		if($this->db == ''){
			error_log_shiksha('Listing Server : sGetCitiesWithCollege() can not create db handle');
		}

		$queryCmd = "select distinct(institute_location_table.city_id),countryCityTable.city_name from institute_location_table,countryCityTable where country_id = ? and status='live' and institute_location_table.city_id = countryCityTable.city_id order by countryCityTable.city_name";
//		$queryCmd = "select distinct(institute_location_table.city_id),city_name from institute_location_table,countryCityTable where country_id = '".$formVal['countryId']."' and status='live' and institute_location_table.city_id = countryCityTable.city_id order by city_name";
		$query = $this->db->query($queryCmd,array($formVal['countryId']));
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row)
		{
			array_push($msgArray,array(
                            'cityID'=>$row->city_id,
                            'cityName'=>$row->city_name
			));//close array_push

		}
		$response = array(json_encode($msgArray),'string');
		return $this->xmlrpc->send_response($response);
	}

	function sGetCountriesWithCollegeInCategory($request)
	{
		$parameters = $request->output_parameters();
		$formVal = $parameters['1'];
		$examprep = $parameters['2'];

		$dbConfig_test = array( 'hostname' => 'localhost');
		
		
		if($this->db == ''){
			error_log_shiksha('Listing Server : GetCountriesWithCollegeInCategory() can not create db handle');
		}
		$categoryId = $parameters['1'];
		if ($categoryId ==1 )
		{
			$subQuery =  "select boardId from categoryBoardTable where parentId in (select boardId from categoryBoardTable where parentId = 1 )";
		}
		else
		{
			$subQuery = "select boardId from categoryBoardTable where parentId = $categoryId";
		}
		if ($examprep=="true")
		{
			$subExamQuery = " and listings_main.tags like '%exam%' ";
		}

		$queryCmd = "select distinct(institute_location_table.country_id),countryTable.name,countryTable.urlName from countryTable, institute_location_table,listing_category_table ,listings_main where countryTable.countryId = institute_location_table.country_id and institute_location_table.institute_id=listing_category_table.listing_type_id and listings_main.listing_type_id = institute_location_table.institute_id and listings_main.listing_type_id = listing_category_table.listing_type_id and listings_main.listing_type ='institute' and listing_category_table.category_id in ($subQuery) and listing_category_table.listing_type ='institute' and listings_main.version = institute.version and institute.version =  institute_location_table.version and listings_main.status='live' $subExamQuery order by countryTable.name";

		$query = $this->db->query($queryCmd);
		$msgArray = array();
		array_push($msgArray,array(
		array(
                        'countryId'=> array('1','string'),
                        'name' => array('All','string'),
                        'urlName' => array('All','string')
		),'struct'));
		foreach ($query->result() as $row)
		{
			array_push($msgArray,array(
			array(
                            'countryId'=>array($row->country_id,'string'),
                            'name'=>array($row->name,'string'),
                            'urlName'=>array($row->urlName,'string')
			),'struct'));

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sGetCitiesWithCollegeInCategory($request)
	{
		$parameters = $request->output_parameters();
		$categoryId = $parameters['1'];
		$countryId = $parameters['2'];
		$examprep = $parameters['3'];

		$dbConfig_test = array( 'hostname' => 'localhost');
		
		
		if($this->db == ''){
			error_log_shiksha('Listing Server : GetCitiesWithCollegeInCategory() can not create db handle');
		}
		if ($categoryId ==1 )
		{
			$subQuery =  "select boardId from categoryBoardTable where parentId in (select boardId from categoryBoardTable where parentId = 1 )";
		}
		else
		{
			$subQuery = "select boardId from categoryBoardTable where parentId = $categoryId";
		}
		$subQueryCountry = "";
		if ($countryId !=1 )
		{
			$subQueryCountry = "and countryCityTable.countryId= $countryId";
		}
		if ($examprep=="true")
		{
			$subExamQuery = " and listings_main.tags like '%exam%' ";
		}

		$queryCmd = "select distinct(institute_location_table.city_id),countryCityTable.city_name from countryCityTable, institute_location_table,listing_category_table, listings_main where countryCityTable.city_id = institute_location_table.city_id and institute_location_table.institute_id=listing_category_table.listing_type_id and listings_main.listing_type_id = institute_location_table.institute_id and listings_main.listing_type_id=listing_category_table.listing_type_id and listings_main.listing_type ='institute' and listing_category_table.category_id in (".$subQuery.") ". $subQueryCountry." and listings_main.status='live' ".$subExamQuery." and trim(countryCityTable.city_name) <> '' and listing_category_table.listing_type='institute' and listings_main.version= institute.version and institute.version = institute_location_table.version order by countryCityTable.city_name";

		$query = $this->db->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row)
		{
			array_push($msgArray,array(
			array(
                            'cityID'=>array($row->city_id,'string'),
                            'cityName'=>array($row->city_name,'string')
			),'struct'));

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


	function getUploadedBrochure($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		
		$type = $parameters['1'];
		$listing_id = $parameters['2'];
		//connect DB
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		
		
		if($type == 'institute'){
			
				$queryC = 'select institute_name,institute_request_brochure_link from institute where institute_id= ?  and status="live"';
	
				$query = $this->db->query($queryC,$listing_id);
		
				foreach ($query->result() as $row){
					$link = $row->institute_request_brochure_link;
					$title = $row->institute_name;
		
				}
				
			$response = array();
			
			$response = array(
					array(
						'brochureURL'=> $link,
						'instituteName' => $title
						),
					'struct');
			
			return $this->xmlrpc->send_response($response);
		}
		
		$queryCmd = 'select cd.courseTitle,cd.course_request_brochure_link,cd.institute_id,i.institute_name from course_details cd,institute i where i.institute_id = cd.institute_id and cd.course_id= ?  and cd.status="live" and i.status = "live"';
		$query = $this->db->query($queryCmd,$listing_id);
		
		foreach ($query->result() as $row){
			$link = $row->course_request_brochure_link;
			$institute_id = $row->institute_id;
			$institute_name = $row->institute_name;
			$course_name = $row->courseTitle;
		}
	
		if(!trim($link))    
		{
			
			$queryC = 'select institute_request_brochure_link from institute where institute_id= ?  and status="live"';
			
			$query = $this->db->query($queryC,$institute_id);
	
			foreach ($query->result() as $row){
				$link = $row->institute_request_brochure_link;
	
			}
		}
		
		
		$response = array();
		
		$response = array(
				array(
					'brochureURL'=> $link,
					'courseName' => $course_name,
					'instituteName' => $institute_name,
					),
				'struct');
		

		
		return $this->xmlrpc->send_response($response);
	}
	

	function getFeaturedPanelLogo($request)
	{
		$parameters = $request->output_parameters();
		$dbConfig_test = array( 'hostname' => 'localhost');
		
		
		if($this->db == ''){
			error_log_shiksha('Listing Server : getFeaturedPanelLogo() can not create db handle');
		}

		$instituteIds = $parameters['1'];
		$strIds = implode(',',$instituteIds);
		$queryCmd = "select institute_id,featured_panel_link from institute where status='live' and institute_id in ($strIds)";
		$query = $this->db->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row)
		{
			$msgArray[$row->institute_id]=array($row->featured_panel_link,'string');
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function updateApcForSearch($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters[1];
		//apc_store("city_flag","0");
		//apc_store("city2Country_flag","0");
                $this->cacheLib->store("city_flag","0");
                $this->cacheLib->store("city2Country_flag","0");
		$this->makeApcCityMap();
		$this->makeApcCityCountryMap();
		$response = array(
		array(
									'result'=>0,
		),
								'struct');
		return $this->xmlrpc->send_response($response);

	}

	function reportChanges($request)
	{
		$parameters = $request->output_parameters();
		$formVal = $parameters['1'];
		//connect DB
		$this->db = $this->_loadDatabaseHandle('write');
		$appId = $parameters['0']['0'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		//Query Command for Insert in the Listing Main Table
		$data =array();
		$data = array(
                'listing_type_id'=>$formVal['listing_type_id'],
                'listing_type'=>$formVal['listing_type'],
                'userId'=>$formVal['userId'],
                'name'=>$formVal['name'],
                'email'=>$formVal['email'],
                'comment'=>$formVal['comment'],
                'contact_cell'=>$formVal['contact_cell'],
                'status'=>"0"
                );
                $queryCmd = $this->db->insert_string('tReportChanges',$data);
                $query = $this->db->query($queryCmd);
                $recent_id = $this->db->insert_id();
                $response = array(array
                ('QueryStatus'=>$query,
                 'changeId'=>$recent_id
                ),
                'struct'
                );
                return $this->xmlrpc->send_response($response);
	}
	function sgetListingDetailForSms($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];

		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$status = isset($parameters['3'])?$parameters['3']:'live';
		
		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		switch($listing_type){
			case 'institute':
			case 'course':
				$queryCmd = 'select listing_title, contact_details_id from listings_main where status="'.$status.'" and listing_type=\''.$listing_type.'\' and listing_type_id='.$type_id;
				$queryTemp = $this->db->query($queryCmd);
				$msgArray = array();
				foreach($queryTemp->result() as $row)
				{
					$contact_details = $this->ListingModel->getContactDetails($this->db,$row->contact_details_id);
					array_push($msgArray,array(
					array(
                                    'listing_title'=>array($row->listing_title,'string'),
                                    'contact_email'=>array($contact_details['contact_email'],'string'),
                                    'contact_cell'=>array($contact_details['contact_cell'],'string'),
                                    'contact_main_phone'=>array($contact_details['contact_main_phone'],'string'),
                                    'contact_alternate_phone'=>array($contact_details['contact_alternate_phone'],'string')
					),'struct'));
				}
				break;
			case 'scholarship':
			case 'notification':
				$queryCmd = 'select listing_type_id,listing_type,listing_title,contact_email,contact_cell from listings_main where status = "'.$status.'" and listing_type=\''.$listing_type.'\' and listing_type_id='.$type_id;
				error_log_shiksha($queryCmd);
				$queryTemp = $this->db->query($queryCmd);
				$msgArray = array();
				foreach($queryTemp->result() as $row)
				{
					array_push($msgArray,array(
					array(
                                    'listing_title'=>array($row->listing_title,'string'),
                                    'contact_email'=>array($row->contact_email,'string'),
                                    'contact_cell'=>array($row->contact_cell,'string')
					),'struct'));
				}
				break;
		}
		$result_array=array($msgArray,'struct');
		return $this->xmlrpc->send_response($result_array);
	}

	function sextendExpiryDate($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listing_type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		$extendedDate = $parameters['3'];

		//connect DB
		
		
		$this->db = $this->_loadDatabaseHandle('write');
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmd = 'select expiry_date from listings_main where listing_type_id =? and listing_type=?';
		$queryTemp = $this->db->query($queryCmd,array($listing_type_id, $listing_type));
		$expiryDate = $queryTemp->first_row()->expiry_date;

		$status = "No change in Expiry Date!!";
		if($extendedDate > $expiryDate){
			$queryCmdExt = 'update listings_main set expiry_date=? where listing_type_id=? and listing_type=?';
			$queryExt = $this->db->query($queryCmdExt,array($extendedDate, $listing_type_id, $listing_type));
			$status = 'Expiry date changed to '.$extendedDate;
		}

		$response = array(array('ExtensionStatus'=>$status,'string'),'struct');
		return $this->xmlrpc->send_response($response);
	}

	private function makeListingExamsMapping($listingId, $exams, $listing){
		
		
		$this->load->model('ArticleModel','',$dbConfig);
		$this->ArticleModel->makeEntityExamsMapping($listingId, $exams,$listing);

	}

	function getCoursesForExam($request)
	{
		error_log("Vibhu".$request);
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$blogId = $parameters['1'];
		//either 'testprep' or 'required'
		$typeOfInstitutes = $parameters['2'];
		$start=$parameters['3'];
		$count=$parameters['4'];
		$relaxFlag=$parameters['5'];
		$countryId=$parameters['6'];
		$cityId=$parameters['7'];

		
		$response = $this->ListingModel->getCoursesForExams($blogId,$typeOfInstitutes,$start,$count,$relaxFlag,$countryId,$cityId);
		return $this->xmlrpc->send_response($response);
	}




	function getInstitutesForExam($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$blogId = $parameters['1'];
		//either 'testprep' or 'required'
		$typeOfInstitutes = $parameters['2'];
		$start=$parameters['3'];
		$count=$parameters['4'];
		$relaxFlag=$parameters['5'];
		$countryId=$parameters['6'];
		$cityId=$parameters['7'];
		$pageKey =$parameters['8'];
		if(strlen($pageKey) <1){
			$pageKey = 0;
		}

		
		$response = $this->ListingModel->getListingsForExams($blogId,$typeOfInstitutes,$start,$count,$relaxFlag,$countryId,$cityId,$pageKey);
		return $this->xmlrpc->send_response($response);
	}

	private function getExamsForListing($appId, $listingTypeId, $listingType){
		$this->load->model('ArticleModel');
		$examsSelected = $this->ArticleModel->getExamsForEntity($listingTypeId, $listingType);
		return $examsSelected;
	}

	function schangeListingDates($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$params = $parameters['1'];
		$listing_type_id = $params['listing_type_id'];
		$listing_type = $params['listing_type'];
		$new_start_date = $params['new_start_date'];
		$new_end_date = $params['new_end_date'];

		//connect DB
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmdExt = 'update listings_main set expiry_date="'.$new_end_date.'" where listing_type_id='.$listing_type_id.' and listing_type="'.$listing_type.'"';
		$queryExt = $this->db->query($queryCmdExt);

		$resp = array('result'=>"SUCCESS",'struct');
		$response = array($resp,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function seditCategFormOpen($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$instituteId = $parameters['1'];

		//connect DB
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','getCountryTable can not create db handle');
		}
		$queryCmd = "select count(course_id) as courseCount from institute_courses_mapping_table ICM,listings_main LM where ICM.institute_id = ? and ICM.course_id=LM.listing_type_id and LM.listing_type='course' and LM.status='live'";
		$queryTemp = $this->db->query($queryCmd,$instituteId);
		$courseCount = $queryTemp->first_row()->courseCount;

		$resp = array('courseCount'=>$courseCount,'struct');
		$response = array($resp,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * Fetch the list of dafault allowed wiki sections for institut/course form
	 * @param $request : Array having listing type
	 */

	function getWikiFields($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters[0];
		$listing_type = $parameters[1];
		
		

		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$query = "select * from  listing_fields_table where listing_type= ? order by listing_fields_table.formPageOrder";
		$fields = $this->db->query($query,$listing_type);
		$responseArr = array();
		foreach ($fields->result() as $field)
		{
			array_push($responseArr,array(array(
                        'keyId' => $field->keyId,
                        'listing_type'=>$field->listing_type,
                        'caption'=>$field->caption,
                        'key_name'=>$field->key_name,
                        'example'=>$field->help_example,
                        'isPaid'=>$field->isPaid
			),'struct'));
		}
		$response = array($responseArr,'struct');
		return $this->xmlrpc->send_response ($response);
	}

	function getDraftAndLiveInstiContactIds($request)
	{
		$parameters = $request->output_parameters();
		error_log(print_r($parameters[1],true));
		$appId = $parameters['0'];
		$formVal = $parameters['1'];
		error_log(print_r($formVal,true));

		$listing_type=$formVal['listing_type'];
		$listing_type_id=$formVal['listing_type_id'];

		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		if($listing_type=='course'){
			$queryCmd = 'select institute.contact_details_id,institute.status from course_details, institute where course_details.course_id= ? and course_details.institute_id=institute.institute_id and institute.status in ("live","draft","queued")';
		}
		if($listing_type=='institute'){
			$queryCmd = 'select institute.contact_details_id,institute.status from institute where institute.institute_id= ? and institute.status in ("live","draft","queued")';
		}
		error_log($queryCmd);
		$fields = $this->db->query($queryCmd,$listing_type_id);
		$responseArr = array();
		foreach ($fields->result() as $field){
			$contact_details_id = $field->contact_details_id;
			$contactInfo = $this->ListingModel->getContactDetails($this->db,$contact_details_id);

			array_push($responseArr,array(array(
                'contact_details_id' => $contact_details_id,
                'status' => $field->status,
                'contactInfo'=>array($contactInfo,'struct')
			),'struct'));
		}

		$response = array($responseArr,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sUpdateCmsBanners($request) {
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$bannerParams = json_decode($parameters['1'], true);
		$id= $parameters['2'];
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','getModerationList can not create db handle');
		}
		if($bannerParams['logourl'] != null)
		{
			$queryCmd = $this->db->update_string('categoryselector',$bannerParams,'sno in ('. $id.')');
		}
		else
		{
			$queryCmd = "delete from categoryselector where sno in ($id)";
		}
		error_log('ASHISH::'. $queryCmd);
		$query = $this->db->query($queryCmd);

		$recent_id = $this->db->affected_rows();
		$response = array ($recent_id,'int');
		return $this->xmlrpc->send_response($response);
	}

	function sGetDataForCountryPage($request)
	{
		error_log('SQL');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$countryId = $parameters['1'];
		error_log($bannerParams.'BANNERPARAMSSERVER');
		
		
		

		if($this->db == '')
		{
			log_message('error','getModerationList can not create db handle');
		}

		error_log(print_r($bannerParams,true).'BANNERPARAMS');

		//Get FAQ's
		$queryCmd = "select b.blogId,b.blogTitle,b.blogImageURL from tSetIds a inner join blogTable b where a.pagename = 'country' and a.country in ($countryId) and a.itemtype = 'faq' and a.status = 'live' and b.status='live' and a.itemId = b.blogId order by a.priority asc limit 3" ;
		error_log('QUERY1'. $queryCmd);
		$query = $this->db->query($queryCmd);
		$faq = array();
		error_log(print_r($query,true).'ASHISHASHISH');
		error_log(print_r($query->result_array(),true).'ASHISHASHISH');
		error_log(print_r($query->result(),true).'ASHISHASHISH');
		if($this->db->affected_rows() >= 0)
		{
			$i = 0;
			error_log(print_r($query->result(),true).'ROWARRAY');
			$blogImageURL = '';
			foreach ($query->result_array() as $row)
			{
				$row['blogTitle'] = strip_tags($row['blogTitle']);
				$row['blogtext'] = strip_tags($row['blogtext']);
				$faq[$i] = $row;
				if($row['blogImageURL'] != '' && $blogImageURL == '')
				{
					$faq[0]['blogImageURL'] = $row['blogImageURL'];
					$blogImageURL = $row['blogImageURL'];
				}
				$faq[$i]['url'] = getSeoUrl($row['blogId'],'blog',$row['blogTitle']) ;
				error_log(print_r($row,true).'ASHISHASHISH123');
				$i++;
			}
			error_log(print_r($faq,true).'FAQROW');
		}

		//Get Articles
		$queryCmd = "select b.blogId,b.blogTitle,b.blogImageURL,substring(b.summary,1,100) as blogtext,(select count(*) -1 from messageTable where threadId = discussionTopic) as messageCount from tSetIds a inner join blogTable b on a.itemid = b.blogId where a.pagename = 'country' and a.country in ($countryId) and a.itemtype = 'article' and a.status = 'live' and b.status = 'live' order by a.priority asc limit 3";
		error_log('QUERY1'. $queryCmd);
		error_log('ASHISHASHISH::'. $queryCmd);
		$query = $this->db->query($queryCmd);
		$articles = array();
		if($this->db->affected_rows() >=0)
		{
			$i = 0;
			foreach ($query->result_array() as $row)
			{
				$row['blogTitle'] = strip_tags($row['blogTitle']);
				$row['blogtext'] = strip_tags($row['blogtext']);
				$articles[$i] = $row;
				$articles[$i]['url'] = getSeoUrl($row['blogId'],'blog',$row['blogTitle']) ;
				error_log(print_r($row,true).'ASHISHASHISH123');
				$i++;
			}
		}
		error_log('ARTICLES'.print_r($articles,true));
		//Get QnA's

		$queryCmd = "select SQL_CALC_FOUND_ROWS t.userid,t.displayname,m1.viewCount,m1.threadId,m1.msgId,m1.msgTxt,m1.creationDate,m1.status,ifnull((select 1 from messageTable M1 where M1.fromOthers = 'user' and m1.userId = 0 and M1.threadId = m1.threadId and M1.threadId = M1.parentId and M1.status not in('deleted','abused')),0) flagForAnswer,(select count(*) from messageTable M2 where M2.threadId = m1.threadId and M2.threadId = M2.parentId and M2.fromOthers = 'user' and M2.status not in ('deleted','abused')) noOfAnswer from messageTable m1 , tSetIds sd ,tuser t where t.userId = m1.userId and sd.itemid = m1.msgId and sd.itemtype = 'question' and m1.status not in ('deleted','abused') and sd.status = 'live' and sd.country in ($countryId) having noOfAnswer > 0 order by m1.creationDate limit 2";
		error_log($queryCmd.'QUERY123');
		$Result = $this->db->query($queryCmd);
		$csvThreadIds = '';
		$tempArray = array();
		$msgArray = array();
		if($Result->num_rows() > 0)
		{
			foreach ($Result->result_array() as $row)
			{
				$row['msgTxt'] = strip_tags($row['msgTxt']);
				$row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
				$csvThreadIds .= ($csvThreadIds == '')?$row['threadId']:(','.$row['threadId']);
				$tempArray[$row['threadId']] = $row;
			}
			error_log(print_r($tempArray,true).'TEMPARRAY1');
			$this->load->model('QnAModel');
			$tempArray1 = $this->QnAModel->getPopularAnswersForQuestions($this->db,$csvThreadIds,false);
			error_log(print_r($tempArray1,true).'TEMPARRAY');
			$tempArray1 = is_array($tempArray1)?$tempArray1:array();

			foreach($tempArray as $key => $temp)
			{
				$tempMsgArray = array();
				$tempMsgArray['question']=$tempArray[$key];
				if(array_key_exists($key,$tempArray1)){ //to check if the answer for question exists.
					$tempMsgArray['answer']=$tempArray1[$key];
				}else{
					$tempMsgArray['answer']=array(array(),'struct');
				}
				array_push($msgArray,$tempMsgArray);
				//            $msgArray = $tempMsgArray;
			}
		}
		error_log('ARRAYVAL'.print_r($tempMsgArray,true));
		$mainArr = array();
		//$mainArr = $msgArray;
		array_push($mainArr,array('results'=>array($msgArray,'struct'),'struct'));
		//close array_push
		//$response1 = array($mainArr,'struct');
		$response1 = $mainArr;
		error_log(print_r($response1,true).'RESPONSE1');
		$queryCmd = "select b.blogTitle,b.blogId,substring(b.summary,1,200) as blogtext from tSetIds a inner join blogTable b on a.itemId = b.blogId where a.pagename = 'country' and a.country in ($countryId) and a.itemtype = 'englishtestexam' and a.status = 'live'and b.status='live' and b.blogType = 'examstudyabroad' and b.blogTypeValues in ('EnglishTest') limit 3";

		error_log('QUERY1'. $queryCmd);
		error_log('ASHISHASHISH::'. $queryCmd);
		$query = $this->db->query($queryCmd);
		$englishtestexams = array();
		if($this->db->affected_rows() >=0)
		{
			$i = 0;
			foreach ($query->result_array() as $row)
			{
				$row['blogTitle'] = strip_tags($row['blogTitle']);
				$row['blogtext'] = strip_tags($row['blogtext']);
				$englishtestexams[$i] = $row;
				$englishtestexams[$i]['url'] = getSeoUrl($row['blogId'],'blog',$row['blogTitle']) ;
				error_log(print_r($row,true).'ASHISHASHISH123');
				$i++;
			}
		}
		$queryCmd = "select b.blogTitle,b.blogId,substring(b.summary,1,100) as blogtext from tSetIds a inner join blogTable b on a.itemId = b.blogId where a.pagename = 'country' and a.country in ($countryId) and a.itemtype = 'doctoralexam' and a.status = 'live' and b.status = 'live' and b.blogType = 'examstudyabroad' and b.blogTypeValues in ('Doctoral') limit 3";

		error_log('QUERY1'. $queryCmd);
		error_log('ASHISHASHISH::'. $queryCmd);
		$query = $this->db->query($queryCmd);
		$doctoraltestexams = array();
		if($this->db->affected_rows() >=0)
		{
			$i = 0;
			foreach ($query->result_array() as $row)
			{
				$row['blogTitle'] = strip_tags($row['blogTitle']);
				$row['blogtext'] = strip_tags($row['blogtext']);
				$doctoraltestexams[$i] = $row;
				$doctoraltestexams[$i]['url'] = getSeoUrl($row['blogId'],'blog',$row['blogTitle']) ;
				error_log(print_r($row,true).'ASHISHASHISH123');
				$i++;
			}
		}
		$queryCmd = "select b.blogTitle,b.blogId,substring(b.summary,1,100) as blogtext from tSetIds a inner join blogTable b on a.itemId = b.blogId where a.pagename = 'country' and a.country in ($countryId) and a.itemtype = 'ugexam' and a.status = 'live' and b.status = 'live' and b.blogType = 'examstudyabroad' and b.blogTypeValues in ('UG') limit 3";

		error_log('QUERY1'. $queryCmd);
		error_log('ASHISHASHISH::'. $queryCmd);
		$query = $this->db->query($queryCmd);
		$ugtestexams = array();
		if($this->db->affected_rows() >=0)
		{
			$i = 0;
			foreach ($query->result_array() as $row)
			{
				$row['blogTitle'] = strip_tags($row['blogTitle']);
				$row['blogtext'] = strip_tags($row['blogtext']);
				$ugtestexams[$i] = $row;
				$ugtestexams[$i]['url'] = getSeoUrl($row['blogId'],'blog',$row['blogTitle']) ;
				error_log(print_r($row,true).'ASHISHASHISH123');
				$i++;
			}
		}
		$queryCmd = "select b.blogTitle,b.blogId,substring(b.summary,1,100) as blogtext from tSetIds a inner join blogTable b on a.itemId = b.blogId where a.pagename = 'country' and a.country in ($countryId) and a.itemtype = 'pgexam' and a.status = 'live' and b.status = 'live' and  b.blogType = 'examstudyabroad' and b.blogTypeValues in ('PG') limit 3";

		error_log('QUERY1'. $queryCmd);
		error_log('ASHISHASHISH::'. $queryCmd);
		$query = $this->db->query($queryCmd);
		$pgtestexams = array();
		if($this->db->affected_rows() >=0)
		{
			$i = 0;
			foreach ($query->result_array() as $row)
			{
				$row['blogTitle'] = strip_tags($row['blogTitle']);
				$row['blogtext'] = strip_tags($row['blogtext']);
				$pgtestexams[$i] = $row;
				$pgtestexams[$i]['url'] = getSeoUrl($row['blogId'],'blog',$row['blogTitle']) ;
				error_log(print_r($row,true).'ASHISHASHISH123');
				$i++;
			}
		}
		$response = array();
		$response['faq'] = array($faq);
		$response['question'] = $response1;
		$response['englishtestexam'] = array($englishtestexams);
		$response['doctoraltestexam'] = array($doctoraltestexams);
		$response['ugtestexam'] = array($ugtestexams);
		$response['pgtestexam'] = array($pgtestexams);
		$response['article'] = array($articles);
		error_log('RESPONSEQUES'.print_r($response1,true));
		error_log('RESPONSE123'.print_r($response['article'][0],true).'RESPONSE123');
		error_log('ARTICLES'.print_r($articles,true));
		return $this->xmlrpc->send_response(json_encode($response));
	}

	function sUploadCmsCountries($request)
	{
		error_log('SQL');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$bannerParams = json_decode($parameters['1'], true);
		// $bannerParams = $parameters['1'];
		error_log($bannerParams.'BANNERPARAMSSERVER');
		
		
		$this->db = $this->_loadDatabaseHandle('write');

		if($this->db == '')
		{
			log_message('error','getModerationList can not create db handle');
		}

		error_log(print_r($bannerParams,true).'BANNERPARAMS');
		$response = '';
		for($k = 0;$k < count($bannerParams['countryselector']);$k++)
		{
			for($j = 0;$j < count($bannerParams['enterquestionid']); $j++)
			{
				$queryCmd = "select * from messageTable a inner join messageCountryTable b on a.threadId = b.threadId where a.msgId = ".$bannerParams['enterquestionid'][$j] ." and b.countryId in (".$bannerParams['countryselector'][$k].")";
				error_log('ASHISH::'. $queryCmd);
				$query = $this->db->query($queryCmd);
				if ($this->db->affected_rows()<=0) {
					$response .= "The question id ".$bannerParams['enterquestionid'][$j]." is not valid or doesn't belong to the country ".$bannerParams['countryselector'][$k];
				}
				else
				{
					$queryCmd = "select * from tSetIds where country = ".$bannerParams['countryselector'][$k] ." and itemid = ".$bannerParams['enterquestionid'][$j] ." and itemtype = 'question'";
					$query = $this->db->query($queryCmd);
					if ($this->db->affected_rows()>0) {
						$response .= "The question id ".$bannerParams['enterquestionid'][$j]." is already added for the country " .$bannerParams['countryselector'][$k];
					}
					else
					{
						$queryCmd = "insert into tSetIds values('','country','1','0','".$bannerParams['countryselector'][$k]."','0','".$bannerParams['enterquestionid'][$j]."','question')";
						error_log('ASHISH::'. $queryCmd);
						$query = $this->db->query($queryCmd);
					}
				}
			}

			for($j = 0;$j < count($bannerParams['enterarticleid']); $j++)
			{
				$queryCmd = "select * from blogTable a inner join blogCountry b on a.blogId = b.blogId where a.status = 'live' and a.blogId = ".$bannerParams['enterarticleid'][$j] ." and b.countryId in (".$bannerParams['countryselector'][$k].")";
				error_log('ASHISH::'. $queryCmd);
				$query = $this->db->query($queryCmd);
				if ($this->db->affected_rows()<=0) {
					$response .= "The article id ".$bannerParams['enterarticleid'][$j]." is not valid or doesn't belong to the country ".$bannerParams['countryselector'][$k];
				}
				else
				{
					$queryCmd = "select * from tSetIds where country = ".$bannerParams['countryselector'][$k] ." and itemid = ".$bannerParams['enterarticleid'][$j] ." and itemtype = 'article'";
					$query = $this->db->query($queryCmd);
					if ($this->db->affected_rows()>0) {
						$response .= "The article id ".$bannerParams['enterarticleid'][$j]." is already added for the country " .$bannerParams['countryselector'][$k];
					}
					else
					{
						$queryCmd = "insert into tSetIds values('','country','1','0','".$bannerParams['countryselector'][$k]."','0','".$bannerParams['enterarticleid'][$j]."','article')";
						error_log('ASHISH::'. $queryCmd);
						$query = $this->db->query($queryCmd);
					}
				}
			}

			for($j = 0;$j < count($bannerParams['enterfaqid']); $j++)
			{
				$queryCmd = "select * from blogTable a inner join blogCountry b on a.blogId = b.blogId where a.status = 'live' and a.blogId = ".$bannerParams['enterfaqid'][$j] ." and b.countryId in (".$bannerParams['countryselector'][$k].") and a.blogType = 'faq'";
				error_log('ASHISH::'. $queryCmd);
				$query = $this->db->query($queryCmd);
				if ($this->db->affected_rows()<=0) {
					$response .= "The article id ".$bannerParams['enterfaqid'][$j]." is not valid or doesn't belong to the country ".$bannerParams['countryselector'][$k];
				}
				else
				{
					$queryCmd = "select * from tSetIds where country = ".$bannerParams['countryselector'][$k] ." and itemid = ".$bannerParams['enterfaqid'][$j] ." and itemtype = 'faq'";
					$query = $this->db->query($queryCmd);
					if ($this->db->affected_rows()>0) {
						$response .= "The article id ".$bannerParams['enterfaqid'][$j]." is already added for the country " .$bannerParams['countryselector'][$k];
					}
					else
					{
						$queryCmd = "insert into tSetIds values('','country','1','0','".$bannerParams['countryselector'][$k]."','0','".$bannerParams['enterfaqid'][$j]."','faq')";
						error_log('ASHISH::'. $queryCmd);
						$query = $this->db->query($queryCmd);
					}
				}
			}
		}
		return $this->xmlrpc->send_response($response);
	}

	function sUploadCmsBanners($request)
	{
		error_log('SQL');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$bannerParams = json_decode($parameters['1'], true);
		// $bannerParams = $parameters['1'];
		error_log($bannerParams.'BANNERPARAMSSERVER');
		$categoryids = $this->getChildIds($bannerParams['categoryid']);
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($bannerParams['subcategoryid'] == '' || $bannerParams['subcategoryid'] == 'Select')
		$bannerParams['subcategoryid'] = $bannerParams['categoryid'];
		if($this->db == '')
		{
			log_message('error','getModerationList can not create db handle');
		}
		error_log(print_r($bannerParams,true).'BANNERPARAMS');
		$countryId = '';
		for($k = 0;$k < count($bannerParams['countryid']);$k++)
		{
			if($k == 0)
			$countryId = $bannerParams['countryid'];
			else
			$countryId .= ','.$bannerParams['countryid'];
		}
		if($bannerParams['pagename'] == 'category')
		{
			$sql = "select * from categoryselector where categoryid = ".$bannerParams['categoryid'] ." and subcategoryid = ".$bannerParams['subcategoryid'] ." and cityid = ".$bannerParams['cityid'] ." and now() between startdate and enddate and pagename = '".$bannerParams['pagename']."'";
		}
		else
		{
			$sql = "select * from categoryselector where countryid in (".$countryId .") and now() between startdate and enddate and pagename = '".$bannerParams['pagename']."'";
		}
		error_log($sql.'SQL');
		$queryCmd = $this->db->query($sql);
		if($queryCmd->num_rows() > 0)
		{
			$response = 'Institute has already been added for the selection';
			return $this->xmlrpc->send_response($response);
		}
		if($bannerParams['subcategoryid'] != $bannerParams['categoryid'])
		$categoryids = $bannerParams['subcategoryid'];

		if($bannerParams['pagename'] == "category")
		{
			$sql = "select * from institute_location_table a,listing_category_table b,virtualCityMapping c where a.institute_id = b.listing_type_id and b.listing_type = 'institute' and a.status = 'live' and b.status = 'live' and b.category_id in($categoryids) and a.institute_id = ". $bannerParams['instituteid'] ." and a.city_id = c.city_id and c.virtualCityId = ".$bannerParams['cityid'];

		}
		else
		{
			$sql = "select * from institute_location_table where institute_id = ".$bannerParams['instituteid'] ." and status = 'live' and country_id in ($countryId)";
		}
		error_log($sql.'SQL');
		$queryCmd = $this->db->query($sql);
		if($queryCmd->num_rows() == 0)
		{
			$response = "Institute doesn't belong to the selected criteria";
			return $this->xmlrpc->send_response($response);
		}
		for($j = 0;$j < count($bannerParams['countryid']) ; $j++)
		{
			$queryCmd = "insert into categoryselector values('','".$bannerParams['instituteid']."','".$bannerParams['categoryid']."','".$bannerParams['subcategoryid']."','".$bannerParams['countryid']."','".$bannerParams['cityid']."','','".$bannerParams['startdate']."','".$bannerParams['enddate']."','".$bannerParams['pagename']."')";
			$query = $this->db->query($queryCmd);
			if($j == 0)
			$recent_id = $this->db->insert_id();
			else
			$recent_id .= ',' .$this->db->insert_id();
		}
		error_log(strlen($queryCmd));
		$response = $recent_id;
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * Fetch institute and courses which are in moderation queue
	 * @param $request : Array having several search parameters
	 */
	function getModerationList($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$searchParams = $parameters['1'];
		$userid = $searchParams['userid'];
		$usergroup = $searchParams['usergroup'];
		$startFrom = $searchParams['startFrom'];
		$countOffset = $searchParams['countOffset'];
		$instituteName = trim($searchParams['instituteName']);
		$location = trim($searchParams['location']);
		$clientEmail = trim($searchParams['clientEmail']);
		$clientUserid = trim($searchParams['clientUserid']);
		$categoryId = $searchParams['categoryId'];

		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','getModerationList can not create db handle');
		}

		if($instituteName!=''){
			$addSearch1 = 'AND S.scholarship_name LIKE "%'.$searchCriteria1.'%"';
		}else{
			$addSearch1 = '';
		}

		if($location!=''){
			$addSearch2 = 'AND S.institution LIKE "%'.$searchCriteria2.'%"';
		}else{
			$addSearch2 = '';
		}

		if($clientEmail!=''){
			$addUserid = 'AND S.username = '.$userid.' ';
		}else if($usergroup=='enterprise'){
			$addUserid = '';
		}

		if($clientUserid!=''){
			$addSearch2 = 'AND S.institution LIKE "%'.$searchCriteria2.'%"';
		}else{
			$addSearch2 = '';
		}

		if($categoryId!=''){
			$addUserid = 'AND S.username = '.$userid.' ';
		}else if($usergroup=='enterprise'){
			$addUserid = '';
		}

		$query = "select * from (select institute.institute_id as listing_type_id,submit_date,institute_name as listing_title,(select 'institute') as listing_type,institute.status,(select displayname from tuser where userid=listings_main.username) as userName from institute, course_details,listings_main where (institute.status='queued' OR (course_details.status='queued' AND course_details.institute_id=institute.institute_id AND institute.status='live'))AND(institute.institute_id=listings_main.listing_type_id AND listings_main.listing_type='institute')  group by institute.institute_id order by institute.version DESC) as t order by listing_title ASC";
		error_log($query);
		$output = $this->db->query($query);

		$msgArray = array();
		foreach ($output->result_array() as $row){
			array_push($msgArray,array($row,'struct'));

			$queryCmd = 'select distinct(course_id) as listing_type_id,submit_date,courseTitle as listing_title,(select "course") as listing_type,course_details.status,(select displayname from tuser where userid=listings_main.username) as userName from course_details,listings_main where institute_id='.$row["listing_type_id"].' AND course_details.status="queued" AND course_details.course_id=listings_main.listing_type_id AND listings_main.listing_type="course"';
			error_log($queryCmd);
			$Result = $this->db->query($queryCmd);

			if($Result->result_array()!=NULL){
				foreach ($Result->result_array() as $row){
					array_push($msgArray,array($row,'struct'));
				}
			}

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


	function indexListing($listing_type,$type_id){
	
		$indexingType = $listing_type;
		$validIndexTypes = array('course', 'institute');
		if(in_array($indexingType, $validIndexTypes)){
			  modules::run('search/Indexer/addToQueue', $type_id, $indexingType, 'delete'); 
			modules::run('search/Indexer/addToQueue', $type_id, $indexingType);
			
		}
		return true;
		/*
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_URL, "http://".SHIKSHACLIENTIP."/ListingScripts/indexListing/$type_id/$listing_type");
		curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
		$result = curl_exec($ch); // run the whole process
		error_log($result);
		curl_close($ch);
		*/
	}

	function sgetListingAutoComplete($request)
	{
		error_log("asdasdadasda");
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listing_type = $parameters['1'];
		$searchParam = $parameters['2'];
		$start = $parameters['3'];
		$rows = $parameters['4'];
		
		
		
		if($this->db == ''){
			log_message('error','getModerationList can not create db handle');
		}
		error_log("asdasdadasda12121");
		if($listing_type == "institute"){
			$queryCmd = "select SQL_CALC_FOUND_ROWS listings_main.listing_title, listings_main.listing_type_id, listings_main.listing_type, countryCityTable.city_name from listings_main, institute_location_table, countryCityTable where listings_main.listing_type=\"".mysql_escape_string($listing_type)."\" and listings_main.listing_title like \"%".mysql_escape_string($searchParam)."%\" and listings_main.status=\"live\" and listings_main.listing_type_id=institute_location_table.institute_id and institute_location_table.status=\"live\" and institute_location_table.city_id=countryCityTable.city_id limit $start,$rows";
		}
		else
		{
			$queryCmd = "select listings_main.listing_title, listings_main.listing_type_id, listings_main.listing_type from listings_main where listings_main.listing_type=\"".mysql_escape_string($listing_type)."\" and listings_main.listing_title like \"%".mysql_escape_string($searchParam)."%\" and listings_main.status=\"live\" limit $start,$rows";
		}
		error_log("shirish".$queryCmd);
		$output = $this->db->query($queryCmd);

		$msgArray = array();
		foreach ($output->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $this->db->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr = array();
		array_push($mainArr,array(
		array(
                        'results'=>array($msgArray,'struct'),
                        'totalCount'=>array($totalRows,'string'),
		),'struct')
		);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function sGetEntitiesForPriorityLeads($request) {
		/*
		 $parameters = $request->output_parameters();
		 $appId=$parameters['0'];
		 */
		
		
		
		if($this->db == '') {
			log_message('error','sCreateEntitiesForPriorityLeads can not create db handle');
		}
		$queryCmd = 'SELECT tPriorityLeads.*, listings_main.listing_title  FROM tPriorityLeads INNER JOIN listings_main ON listings_main.listing_type_id = tPriorityLeads.listingId WHERE tPriorityLeads.status = "live" AND listings_main.listing_type="institute" AND NOW() <= endDate  GROUP BY listingId ORDER BY submitDate DESC';
		error_log("ashish :: ".$queryCmd);
		$output = $this->db->query($queryCmd);
		$msgArray = array();
		foreach ($output->result_array() as $row) {
			array_push($msgArray,$row);
		}
		$response = json_encode($msgArray);
		return $this->xmlrpc->send_response($response);
	}

	function sAddEntityForPriorityLeads($request) {
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listingId = $parameters['1'];
		$sendDate = $parameters['2'];
		$data = array();
		$data['listingId'] = $listingId;
		$data['endDate'] = $sendDate;

		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','sCreateEntitiesForPriorityLeads can not create db handle');
		}
		$insertQuery = $this->db->insert_string('tPriorityLeads',$data);
		//error_log("ashish".$insertQuery);
		$output = $this->db->query($insertQuery);
		$response = $this->db->insert_id();
		return $this->xmlrpc->send_response($response);
	}

	function sDeleteEntityFromPriorityLeads($request) {
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listingId = $parameters['1'];
		$data = array();
		$data['listingId'] = $listingId;

		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == ''){
			log_message('error','sDeleteEntityFromPriorityLeads can not create db handle');
		}
		$deleteQuery = 'UPDATE tPriorityLeads SET status="deleted" WHERE listingId = '. $this->db->escape($listingId);
		//error_log("ashish".$deleteQuery);
		$output = $this->db->query($deleteQuery);
		return $this->xmlrpc->send_response($response);
	}

	function sgetInstituteDataDetails($request) {
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listingIds= json_decode($parameters['1'], true);
		error_log('ASHISH :: '. implode(',', $listingIds));
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$dataForInstitutes = $this->ListingModel->getMediaDataForMultipleInstitutes($this->db,implode(',', $listingIds));
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function sgetInstitutesForMultipleCourses($request) {
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listingIds= json_decode($parameters['1'], true);
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		$dataForInstitutes = $this->ListingModel->getInstitutesForMultipleCourses($this->db,implode(',', $listingIds));
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}


	function sGetAllGroup($request)
	{

		
		
		
		if($this->db == ''){
			log_message('error','sDeleteEntityFromPriorityLeads can not create db handle');
		}

		$queryCmd='select group_name,group_id from tgroupTable';
		$query = $this->db->query($queryCmd);
		$groupArr = array();
		$i=0;
		foreach($query->result() as $row)
		{
			$groupArr[$row->group_id] = $row->group_name;
		}

		return $this->xmlrpc->send_response(json_encode($groupArr));
	}

	function makeCopyListingMapEntry($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$paramArray = json_decode(gzuncompress(base64_decode($parameters['1'])),true);
		error_log("RRR :: ".print_r($paramArray,true));
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','makeCopyListingMapEntry can not create db handle');
		}
		$queryCmd = "Insert into copyListingMapping (listing_type_id,copy_type_id,listing_type,addedBy) values ";
		foreach($paramArray['copiedListingIds'] as $temp){
			$queryCmd .= "(".$paramArray['originalListingId'].",".$temp.",'".$paramArray['originalListingType']."',".$paramArray['addedBy']."),";
		}
		$queryCmd = substr($queryCmd,0,(strlen($queryCmd)-1));
		error_log("RRR :: QUERY ".$queryCmd);
		$Result = $this->db->query($queryCmd);
		$id = $this->db->insert_id();
		if($id > 0){
			$response = array(array('result' => 'Success'),'struct');
		}else{
			$response = array(array('result' => 'failed'),'struct');
		}
		return $this->xmlrpc->send_response($response);
	}

	function sinsertbannerdetails($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$bannerurl = $parameters['2'];
		$bannername = $parameters['3'];

		

		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		$dataForInstitutes = $this->ListingModel->insertbannerdetails($this->db,$clientId,$bannerurl,$bannername);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function sgetShoshkeleDetails($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$sort = $parameters['2'];
		

		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		$dataForInstitutes = $this->ListingModel->getShoshkeleDetails($this->db,$clientId,$sort);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function supdatebannerdetails($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$bannerId = $parameters['1'];
		$bannerurl = $parameters['2'];
		$bannername = $parameters['3'];
		$clientId = $parameters['4'];
		$keyword = $parameters['5'];

		

		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		$dataForInstitutes = $this->ListingModel->updatebannerdetails($this->db,$clientId,$bannerId,$bannerurl,$bannername,$keyword);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function sselectnduseshoshkele($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$arr = $parameters['1'];

		
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		$dataForInstitutes = $this->ListingModel->selectnduseshoshkele($this->db,$arr);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function sgetListingSponsorDetails($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$sort = $parameters['2'];

		

		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		$dataForInstitutes = $this->ListingModel->getListingSponsorDetails($this->db,$clientId,$sort);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function sgetListingndBannersForCoupling($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$countryId = $parameters['2'];
		$cityId = $parameters['3'];
		$stateId = $parameters['4'];
		$categoryId = $parameters['5'];
		$subcategoryId = $parameters['6'];
		$catType = $parameters['7'];
		$courseLevel = $parameters['8'];

		

		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		error_log('ssdfsf');
		$dataForInstitutes = $this->ListingModel->getListingndBannersForCoupling($this->db,$clientId,$countryId,$cityId,$stateId,$categoryId,$subcategoryId,$catType,$courseLevel);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function scmsremoveshoshkele($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$bannerid = $parameters['1'];
		$tablename = $parameters['2'];

		
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		error_log('ssdfsf');
		$dataForInstitutes = $this->ListingModel->cmsremoveshoshkele($this->db,$bannerid,$tablename);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function schangeCouplingStatus($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$listingsubsid = $parameters['2'];
		$bannerlinkid = $parameters['3'];
		$status = $parameters['4'];

		
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		error_log('ssdfsf');
		$dataForInstitutes = $this->ListingModel->changeCouplingStatus($this->db,$clientId,$listingsubsid,$bannerlinkid,$status);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function scmsaddstickylisting($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$arr = $parameters['1'];

		
		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		$dataForInstitutes = $this->ListingModel->cmsaddstickylisting($this->db,$arr);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function scmsgetlistingdetails($request)
	{
		error_log('werwre');
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$listingid = $parameters['1'];

		$this->db = $this->_loadDatabaseHandle('write');

		
		
		if($this->db == '')
		{
			log_message('error','can not create db handle');

		}
		$dataForInstitutes = $this->ListingModel->cmsgetlistingdetails($this->db,$listingid);
		error_log(print_r($dataForInstitutes,true).'DATAFORINSTITUTES');
		return $this->xmlrpc->send_response(json_encode($dataForInstitutes));
	}

	function get_testprep_listings($request) {
		//          return $this->xmlrpc->send_response("Hii", 'string');
		$this->load->library('util');
		$util = new Util();
		$params = $request->output_parameters();
		$blog_id = $params[0];
		$city_id = $params[1];
		$course_type = $params[2];

		

		$free_listings = $this->ListingModel->get_testprep_free_listing_ids($blog_id, $city_id, $course_type);
		$response['free_listings'] = array($util->to_xmlrpc_array($free_listings), 'array');

		$main_listings = $this->ListingModel->get_testprep_main_listing_ids($blog_id, $city_id, $course_type);
		$response['main_listings'] = array($util->to_xmlrpc_array($main_listings), 'array');

		$paid_listings = $this->ListingModel->get_testprep_paid_listing_ids($blog_id, $city_id, $course_type);
		$response['paid_listings'] = array($util->to_xmlrpc_array($paid_listings), 'array');

		$paid_minus_main_listings = $this->ListingModel->get_testprep_paid_minus_main_listing_ids($blog_id, $city_id, $course_type);
		$response['paid_minus_main_listings'] = array($util->to_xmlrpc_array($paid_minus_main_listings), 'array');

		$cat_sponser_listings = $this->ListingModel->get_testprep_cat_sponser_listing_ids($blog_id, $city_id, $course_type);

		$response['cat_sponser_listings'] = array($util->to_xmlrpc_array($cat_sponser_listings['listings']), 'array');
		$response['cat_sponser_banners'] = array($util->to_xmlrpc_array($cat_sponser_listings['banners']), 'array');

		return $this->xmlrpc->send_response(array($response, 'struct'));
	}

	function get_institute_name($request) {
		
		$params = $request->output_parameters();
		$institute_id = $params[0];
		$name = $this->ListingModel->get_institute_name($institute_id);
		return $this->xmlrpc->send_response(array($name, 'string'));
	}

	function get_course_name($request) {
		
		$params = $request->output_parameters();
		$course_id = $params[0];
		$name = $this->ListingModel->get_course_name($course_id);
		return $this->xmlrpc->send_response(array($name, 'string'));
	}

	/*
	 * This function takes as input the blog id of type exam as input and return parent
	 * blog details plus details of its child blogs.
	 * if the input blog id is of child blog then it returns the info for its parent blog
	 */
	function get_exam_categories($request) {
		
		$this->load->library('util');
		$params = $request->output_parameters();
		$blog_id = $params[0];
		$is_top_level = $this->ListingModel->is_top_level($blog_id);
		$exams = array();
		if ($is_top_level) {
			$parent_blog_id = $blog_id;
			$parent_blog_title = $this->ListingModel->get_blog_title($blog_id);
			$parent_blog_acronym = $this->ListingModel->get_blog_acronym($blog_id);
			$exam_title = "All";
			$exams = $this->ListingModel->get_exams_for_blog($blog_id);
			$exams = $this->util->to_xmlrpc_array($exams);
		} else {
			$parent_blog_id = $this->ListingModel->parent_blog_id($blog_id);
			$parent_blog_title = $this->ListingModel->get_blog_title($parent_blog_id);
			$parent_blog_acronym = $this->ListingModel->get_blog_acronym($parent_blog_id);
			$exam_title = $this->ListingModel->get_blog_acronym($blog_id);
			$exams = $this->ListingModel->get_exams_for_blog($parent_blog_id);
			$exams = $this->util->to_xmlrpc_array($exams);
		}
		$response = array(
		array(
                'parent_blog_id' => $parent_blog_id,
                'parent_blog_title' => $parent_blog_title,
                'parent_blog_acronym' => $parent_blog_acronym,
                'exam_title' => $exam_title,
                'sub_categories' => array($exams, 'array')
		), 'struct');
		error_log("abhi :: 21" . print_r($response, true));
		return $this->xmlrpc->send_response($response);
	}

	function get_online_test_banner($request)
	{
		
		$params = $request->output_parameters();
		$blog_id = $params[0];
		$bannerurl = $this->ListingModel->get_online_test_banner($blog_id);
		return $this->xmlrpc->send_response(array($bannerurl, 'string'));
	}

	//Added by Ankur on 25th Oct to get the correct URL for a course listing
	function getCorrectSeoURL($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listing_id = $parameters['1'];
		$listing_type = $parameters['2'];
		
		
		
		if($this->db == ''){
			log_message('error','getModerationList can not create db handle');
		}
		$url = '';
		if($listing_type=='course'){
			$query = 'select course_details.institute_id,course_details.courseTitle,institute.institute_name,ilt.* from course_details , institute, institute_location_table ilt where course_details.status = "live" and course_id in ('. $listing_id .') and course_details.institute_id = institute.institute_id and institute.status = "live" and ilt.institute_id = course_details.institute_id and ilt.status = "live"';
		}
		else if($listing_type=='institute'){
			$query = 'select institute.institute_id,institute.institute_name,ilt.* from institute, institute_location_table ilt where institute.institute_id = "'.$listing_id.'" and institute.status = "live" and ilt.institute_id = institute.institute_id and ilt.status = "live"';
		}
		$queryTemp = $this->db->query($query);
		$course_details = $queryTemp->result_array();

		foreach($course_details as $row1)
		{
			$locationArrayTemp = array();
			$cityName = array((($this->cacheLib->get("city_".$row1['city_id']) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$row1['city_id'])),'string');
			$countryName = array($this->cacheLib->get("country_".$row1['country_id']),'string');
			$locationArrayTemp[0] = $cityName[0]."-".$countryName[0];
			//In case of no city and country, do not redirect
			if($row1['city_id']==0 && $row1['country_id']==0){
				$url = "DNR";
			}
			else{
				$optionalArgs = array();
				$optionalArgs['location'] = $locationArrayTemp;
				$optionalArgs['institute'] = $row1['institute_name'];
				if($listing_type=='course'){
					$url = getSeoUrl($listing_id,'course',$row1['courseTitle'],$optionalArgs);
				}
				else if($listing_type=='institute'){
					$url = getSeoUrl($listing_id,'institute',$row1['institute_name'],$optionalArgs);
				}
			}
		}
		$response = array($url,'string');
		return $this->xmlrpc->send_response($response);

	}

	function getBasicDataForListing($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];
		
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}
		//First get the institute data
		$queryCmd = 'select max(version) as version from listings_main where listing_type = ? and listing_type_id= ? and status = "live" ';
		$query =  $this->db->query($queryCmd,array($listing_type,$type_id));
		foreach ($query->result() as $row){
			$version = $row->version;
		}

		if($version >= 1){
			switch($listing_type){
				case 'course':
					$institute_status =array('live');
					$dataArr = $this->getBasicCourseDetails($this->db, $type_id,$version,$institute_status);
					break;
				case 'institute':
					$courseStatus ="'live'";
					$dataArr = $this->getBasicInstituteDetails($this->db,$type_id,$version,$courseStatus);
					break;
			}
			return $this->xmlrpc->send_response($dataArr);
		}
		else{
			$dataArr  =array('failure','string');
			return $this->xmlrpc->send_response($dataArr);
		}
	}


	function getBasicInstituteDetails($db, $institute_id,$version=1,$courseStatus = "")
	{
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : getBasicInstituteDetails');
		$appId =1;
		$queryCmd = 'select * from  institute, listings_main  where listings_main.listing_type_id =institute.institute_id and listings_main.listing_type = "institute" and institute_id = ? and institute.version=? and listings_main.version = ? limit 1';
		$query = $this->db->query($queryCmd,array($institute_id,$version,$version));	
		$msgArray = array();
		foreach ($query->result() as $row){

			//Get institute Location array
			$queryCmd = 'select * from institute_location_table where version=? and institute_id=? order by institute_location_id asc ';
			$queryTemp = $this->db->query($queryCmd,array($version,$institute_id));
			$randomLocation = rand(100)%($queryTemp->num_rows());
			$locationArrayTemp = array();
			foreach ($queryTemp->result() as $rowTemp) {
				if($randomLocation == 0){
					$otherInstitutesCity = $rowTemp->city_id;
					$otherInstitutesCountry = $rowTemp->country_id;
				}
				$randomLocation--;

				array_push($locationArrayTemp,array(
				array(
                                'city_id'=>array($rowTemp->city_id,'string'),
                                'country_id'=>array($rowTemp->country_id,'string'),
                                'city_name'=>array((($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$rowTemp->city_id)),'string'),
                                'country_name'=>array($this->cacheLib->get("country_".$rowTemp->country_id),'string'),
                                'pincode'=>array($rowTemp->pincode,'string'),
                            	'address1'=>array(htmlspecialchars(str_replace(array("\r\n", "\r","\n"), " ", $rowTemp->address_1)),'string'),
                            	'address2'=>array(htmlspecialchars(str_replace(array("\r\n", "\r","\n"), " ", $rowTemp->address_2)),'string'),
                            	'zone'=>array(htmlspecialchars($rowTemp->zone),'string'),
                            	'localityId'=>array(htmlspecialchars($rowTemp->locality_id),'string'),
                            	'locality'=>array(htmlspecialchars($rowTemp->locality_name),'string'),
                            	'city'=>array(htmlspecialchars($rowTemp->city_name),'string'),
				//                                'address'=>array(htmlspecialchars($rowTemp->address),'string')
				),'struct')
				);//close array_push
			}


			$msgArray = array();

			//Get institute search stat
			$searchStats = $this->getSearchStats($row->institute_id,'institute');
			if(isset($row->sourceURL) && (strlen($row->sourceURL) > 5)){
				$outLink = $row->sourceURL;
			}
			else{
				$outLink = $row->url;
			}

			//Get institute type
			$instituteType = $row->institute_type;
			switch($instituteType){
				case 'Test_Preparatory_Institute':
					$instituteType=2;
					break;
				case 'Academic_Institute':
				default:
					$instituteType=1;
					break;
			}

			$queryCmd = 'select * from listing_category_table where listing_type="institute" and listing_type_id=? and version=? and status = "live"';
			$queryTemp = $this->db->query($queryCmd, array($institute_id,$version));
			$randomCategoryIndex = rand(100)%($queryTemp->num_rows());
			$catArrayTemp = array();
			$catIdsString ='';
			$randomCategoryId = 2;
			foreach ($queryTemp->result() as $rowTemp) {
				if($randomCategoryIndex == 0){
					$randomCategoryId = $rowTemp->category_id;
				}
				$randomCategoryIndex--;
				array_push($catArrayTemp,array(
				array(
                                'category_id'=>array($rowTemp->category_id,'string'),
                                'category_path'=>array($this->cacheLib->get("cat_".$rowTemp->category_id),'string')
				),'struct')
				);//close array_push
				if(count($catIdsString)>0){
					$catIdsString .= ",".$rowTemp->category_id;
				}else{
					$catIdsString = $rowTemp->category_id;
				}
			}

			array_push($msgArray,array(
			array(
                            'institute_id'=>array($row->institute_id,'string'),
			    'instituteType'=>array($instituteType,'string'),
                            'title'=>array(htmlspecialchars($row->institute_name),'string'),
                            'establish_year'=>array(htmlspecialchars($row->establish_year),'string'),
                            'threadId'=>array($row->threadId,'string'),
                            'moderation'=>array($row->moderation_flag,'string'),
                            'status'=>array($row->status,'string'),
                            'packType'=>array($row->pack_type,'string'),
                            'outLink'=>array($outLink,'string'),
                            'userId'=>array($row->username,'string'),
                            'locations'=>array($locationArrayTemp,'struct'),
                            'viewCount'=>array($row->viewCount,'string'),
                            'summaryCount'=>array($searchStats[0]['count'],'string'),
			    'seoListingUrl'=>array($row->listing_seo_url,'string'),
			    'seoListingTitle'=>array($row->listing_seo_title,'string'),
			    'listingSeoDescription'=>array($row->listing_seo_description,'string'),
                            'categoryArr'=>array($catArrayTemp,'struct'),
			    'listingSeoKeywords'=>array($row->listing_seo_keywords,'string')
			),'struct')
			);//close array_push

			break;
		}
		$response = array($msgArray,'struct');
		return $response;
	}


	function getBasicCourseDetails($db, $course_id, $version=1, $institute_status = array("live","draft","queued"))
	{
		error_log('SQL Injection - Code Usability Check :: Class Name : listing_server :: Func Name : getBasicCourseDetails');
		//connect DB
		$appId =1;
		$queryCmd = 'select *,course_details.contact_details_id as cid, course_details.approvedBy as approvedBy from course_details, institute, listings_main where listings_main.listing_type_id = course_details.course_id and listings_main.listing_type = "course" and course_details.course_id = ? and course_details.institute_id = institute.institute_id and course_details.version = ? and listings_main.version = ? and institute.status in (?) order by institute.version asc limit 1 ';
		$query = $this->db->query($queryCmd,array($course_id,$version,$version,$institute_status)); //TODO only if live is not there, draft to be used
		$msgArray = array();
		foreach ($query->result() as $row){
			//Institute Type
			$queryCmd = 'select institute_type from institute where institute_id= ? order by version asc limit 1';
			//			error_log("\ninstitute type query $queryCmd",3,'/home/naukri/Desktop/log.txt');
			$queryTemp = $this->db->query($queryCmd,array($row->institute_id));
			if($queryTemp->num_rows() > 0){
				foreach ($queryTemp->result() as $rowTemp) {
					$instituteType = $rowTemp->institute_type;
				}
			}
			switch($instituteType){
				case 'Test_Preparatory_Institute':
					$instituteType=2;
					break;
				case 'Academic_Institute':
				default:
					$instituteType=1;
					break;
			}

			$locQueryCmd = 'select * from institute_location_table where institute_id= ? and status="live" order by institute_location_id asc ';
			$queryTemp = $this->db->query($locQueryCmd,array($row->institute_id));
			$randomLocation = rand(100)%($queryTemp->num_rows());
			$locationArrayTemp = array();
			if($queryTemp->num_rows() > 0){
				foreach ($queryTemp->result() as $rowTemp) {
					if($randomLocation == 0){
						$otherInstitutesCity = $rowTemp->city_id;
						$otherInstitutesCountry = $rowTemp->country_id;
					}
					$randomLocation--;

					array_push($locationArrayTemp,array(
					array(
                                    'city_id'=>array($rowTemp->city_id,'string'),
                                    'country_id'=>array($rowTemp->country_id,'string'),
                                    'city_name'=>array((($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$rowTemp->city_id)),'string'),
                                    'country_name'=>array($this->cacheLib->get("country_".$rowTemp->country_id),'string'),
                                    'address'=>array(htmlspecialchars($rowTemp->address),'string')
					),'struct')
					);//close array_push

					$optionalArgs['location'][$l]  = (($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$rowTemp->city_id))."-".$this->cacheLib->get("country_".$rowTemp->country_id);
					$l++;
				}
			}
			else{
				$locQueryCmd = 'select * from institute_location_table where institute_id=? and status in ("draft","queued") order by institute_location_id asc ';
				$queryTemp = $this->db->query($locQueryCmd,array($row->institute_id));
				$locationArrayTemp = array();
				foreach ($queryTemp->result() as $rowTemp) {
					if($randomLocation == 0){
						$otherInstitutesCity = $rowTemp->city_id;
						$otherInstitutesCountry = $rowTemp->country_id;
					}
					$randomLocation--;
					array_push($locationArrayTemp,array(
					array(
                                    'city_id'=>array($rowTemp->city_id,'string'),
                                    'country_id'=>array($rowTemp->country_id,'string'),
                                    'city_name'=>array((($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$rowTemp->city_id)),'string'),
                                    'country_name'=>array($this->cacheLib->get("country_".$rowTemp->country_id),'string'),
                                    'address'=>array(htmlspecialchars($rowTemp->address),'string')
					),'struct')
					);//close array_push

					$optionalArgs['location'][$l]  = (($this->cacheLib->get("city_".$rowTemp->city_id) == "ERROR_READING_CACHE")?"":$this->cacheLib->get("city_".$rowTemp->city_id))."-".$this->cacheLib->get("country_".$rowTemp->country_id);
					$l++;
				}
			}

			if(isset($row->sourceURL) && (strlen($row->sourceURL) > 5)){
				$outLink = $rowTemp->sourceURL;
			}
			else{
				$outLink = $rowTemp->url;
			}

			if(strlen($outLink) < 5){
				if(isset($rowTemp->sourceURL) && (strlen($rowTemp->sourceURL) > 5)){
					$outLink = $rowTemp->sourceURL;
				}
				else{
					$outLink = $rowTemp->url;
				}
			}

			$solrDate=$this->dateFormater($row->submit_date);
			$lastModifyDate=$this->dateFormater($row->last_modify_date);
			if(isset($row->intermediateDuration) && strlen($row->intermediateDuration)>0){
				$intermediateDuration = $row->intermediateDuration;
			}
			else{
				$intermediateDuration = $row->duration;
			}

			$searchStats = $this->getSearchStats($row->course_id,'course');

			$query = "SELECT course_id,attribute,value FROM course_attributes WHERE course_id = ? and version = ?";
	                $queryTemp = $this->db->query($query,array($course_id,$version));
                        $courseAttributeArray = array();
	                foreach ($queryTemp->result() as $rowTemp) {
		            array_push($courseAttributeArray,array(
		            array(
			          'course_id'=>array($rowTemp->course_id,'string'),
							    'attribute'=>array($rowTemp->attribute,'string'),
			          'value'=>array($rowTemp->value,'string')
		            ),'struct')
		        );//close array_push
	                }

			//Check if this course has an Online form available on SHiksha
			$queryCmd = "select * from OF_InstituteDetails where courseId = ? and status = 'live'";
			$queryOF = $this->db->query($queryCmd,array($course_id));
			$numRow = $queryOF->num_rows();
			$displayOnlineFormButton = 'false';
			if($numRow>0){
				$displayOnlineFormButton = 'true';
			}
		    $queryCmd = 'select * from listing_category_table where listing_type_id=? and listing_type = "course" and status = "live"';
			$queryTemp = $this->db->query($queryCmd,array($course_id));
			$catArrayTemp = array();
			$randomCategoryIndex = rand(100)%($queryTemp->num_rows());
			$catIdsString ='';
			foreach ($queryTemp->result() as $rowTemp) {
				if($randomCategoryIndex == 0){
					$randomCategoryId = $rowTemp->category_id;	
				}
				$randomCategoryIndex--;
				array_push($catArrayTemp,array(
				array(
                                'category_id'=>array($rowTemp->category_id,'string'),
                                'category_path'=>array($this->cacheLib->get("cat_".$rowTemp->category_id),'string')
				),'struct')
				);//close array_push
			}
			
			array_push($msgArray,array(
			array(
                            'course_id'=>array($row->course_id,'string'),
                            'title'=>array(htmlspecialchars($row->courseTitle),'string'),
                            'threadId'=>array($row->threadId,'string'),
                            'viewCount'=>array($row->viewCount,'string'),
                            'summaryCount'=>array($searchStats[0]['count'],'string'),
                            'status'=>array($row->status,'string'),
			    'packType'=>array($row->pack_type,'string'),
                            'tags'=>array($row->tags,'string'),
                            'userId'=>array($row->username,'string'),
                            'course_type'=>array(htmlspecialchars($row->course_type),'string'),
                            'course_level'=>array(htmlspecialchars($row->course_level),'string'),
                            'course_level_1'=>array(htmlspecialchars($row->course_level_1),'string'),
                            'course_level_2'=>array(htmlspecialchars($row->course_level_2),'string'),
                            'institute_id'=>array($row->institute_id,'int'),
                            'institute_name'=>array(htmlspecialchars($row->institute_name),'string'),
			    'instituteType'=>array($instituteType,'string'),
			    'seoListingUrl'=>array($row->listing_seo_url,'string'),
			    'seoListingTitle'=>array($row->listing_seo_title,'string'),
			    'listingSeoDescription'=>array($row->listing_seo_description,'string'),
			    'listingSeoKeywords'=>array($row->listing_seo_keywords,'string'),
                            'locations'=>array($locationArrayTemp,'struct'),
                            'contact_details_id'=>array($row->cid,'string'),
                            'outLink'=>array($outLink,'string'),
                            'approvedBy'=>array($row->approvedBy,'string'),
                            'establish_year'=>array(htmlspecialchars($row->establish_year),'string'),
			    'courseAttributes'=>array($courseAttributeArray,'struct'),
                            'institute_logo'=>array(htmlspecialchars($row->logo_link),'string'),
                            'displayOnlineFormButton'=>array($displayOnlineFormButton,'string'),
						    'categoryArr'=>array($catArrayTemp,'struct')
			),'struct')
			);//close array_push

		}
		$response = array($msgArray,'struct');
		return $response;
	}

	//Ankur: Added for Checking Listing questions in the DB
	function checkListingQuestions($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$flagForReSearch = 0;
		$listing_id = $parameters['1'];
		
		
		
		if($this->db == ''){
			log_message('error','checkListingQuestions can not create db handle');
		}
		$yest = date("Y-m-d", strtotime("-0 day"));
		$query = "select questionIds from institute_related_question_table where institute_id = ? and createdTime >= ?";
		$queryTemp = $this->db->query($query,array($listing_id,$yest));
		$quesArray = array();
		foreach ($queryTemp->result_array() as $row){
		    array_push($quesArray,array($row,'struct'));
		}
		
		if(empty($quesArray)){
                $query = "select questionIds from institute_related_question_table where institute_id = ? and createdTime < ?";
                $queryTemp = $this->db->query($query,array($listing_id,$yest));
                $quesArray = array();
                foreach ($queryTemp->result_array() as $row){
                    array_push($quesArray,array($row,'struct'));
                }
                $flagForReSearch = 1;
                }
                //Sanitize the QuestionIds getting returned
                if(isset($quesArray[0][0]['questionIds'])){
                        $questionIds = $quesArray[0][0]['questionIds'];
                        $questionArr = explode(',',$questionIds);
                        $finalList = '';
                        foreach ($questionArr as $questionId){
                                if(is_numeric($questionId)){
                                        $finalList .= ($finalList=='')?$questionId:','.$questionId;
                                }
                        }
                        $quesArray[0][0]['questionIds'] = $finalList;
                }
                $response = array(array(array($quesArray,'struct'),array($flagForReSearch,'int')),'struct');

		//$response = array($quesArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	//Ankur: Added for Storing Listing questions in the DB
	function updateListingQuestions($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$listing_id = $parameters['1'];
		$questionIds = $parameters['2'];
		$All = $parameters['3'];

		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','updateListingQuestions can not create db handle');
		}
		if($All == 'All'){
		    $query = "INSERT INTO institute_related_question_table (institute_id,questionIds) VALUES (?, ?) ON DUPLICATE KEY UPDATE questionIds= ?, createdTime = NOW()";
		    $queryTemp = $this->db->query($query, array($listing_id, $questionIds, $questionIds));
		}
		else if($All == 'RemoveQ'){
		    $queryToGetQuesId = "select questionIds from institute_related_question_table where institute_id = ? ";
		    $ResultL = $this->db->query($queryToGetQuesId,array($listing_id));
		    $rowL = $ResultL->row();
		    $Ids = $rowL->questionIds;
		    //Now, from all the Question Id list, remove the deleted one.
		    if(strlen($Ids)>10)
			$Ids = str_replace($questionIds.',','',$Ids);
		    else
			$Ids = str_replace($questionIds,'',$Ids);
		    if($Ids!=''){
			$query = "UPDATE institute_related_question_table SET questionIds= ?  where institute_id = ? ";
			$queryTemp = $this->db->query($query,array($Ids,$listing_id));
		    }
		}
		else{
		    $query = "INSERT INTO institute_related_question_table (institute_id,questionIds) VALUES (?, ?) ON DUPLICATE KEY UPDATE questionIds=concat(?, questionIds), createdTime = NOW()";
		    $queryTemp = $this->db->query($query, array($listing_id, $questionIds, $questionIds.','));
		}
		$response = array('Success','string');
		return $this->xmlrpc->send_response($response);
	}

	//Ankur: Added for getting Listing page cache
	function getListingCacheValue($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$key = $parameters['1'];
		
		
		
		if($this->db == ''){
			log_message('error','getListingCacheValue can not create db handle');
		}
		$yest = date("Y-m-d", strtotime("-6 day"));
		$query = "select value from listing_pages_cache_settings where cacheKey = ? and createdTime >= ?";
		$queryTemp = $this->db->query($query,array($key,$yest));
		$rowL = $queryTemp->row();
		$val = $rowL->value;
		$response = array($val,'string');
		return $this->xmlrpc->send_response($response);
	}

	//Ankur: Added for Storing Listing questions in the DB
	function setListingCacheValue($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$key = $parameters['1'];
		$value = $parameters['2'];

		$this->db = $this->_loadDatabaseHandle('write');
		
		
		if($this->db == ''){
			log_message('error','setListingCacheValue can not create db handle');
		}
		if($value=='true')
		  $query = "INSERT INTO listing_pages_cache_settings (cacheKey,value) VALUES (?,?) ON DUPLICATE KEY UPDATE value=?, createdTime = NOW()";
		else
		  $query = "INSERT INTO listing_pages_cache_settings (cacheKey,value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value=?";
		$queryTemp = $this->db->query($query, array($key, $value, $value));
		$response = array('Success','string');
		return $this->xmlrpc->send_response($response);
	}
	
	/*
	 Pankaj
	 @name: serverTrackAutoSuggestStats
	 @description: this is for tracking the user behaviour while using the autosuggest feature. server Function
	 @param array $requestArray: requestArray has all the parameters required for the tracking. details can be found in the Listing controller file
	*/
	function serverTrackAutoSuggestStats($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$paramData = $parameters['1'];
		
		$suggestionShown = $paramData['suggestionShown'];
		$actionTakenByUser = $paramData['actionTakenByUser'];
		$userInput = $paramData['userInput'];
		$pickedSuggestion = $paramData['pickedSuggestion'];
		$pickedSuggestionNo = $paramData['pickedSuggestionNo'];
		$navigateInSuggestion = $paramData['navigateInSuggestion'];
		$freeTextSearch = $paramData['freeTextSearch'];
		$sessionId = $paramData['sessionId'];
		
		//connect DB
		
		$this->db = $this->_loadDatabaseHandle('write');
		
		if($this->db == ''){
			log_message('error','can not create db handle in trackAutoSuggest');
		}
		
		if($suggestionShown == 'true'){
			$suggestionShown = 1;
		} else {
			$suggestionShown = -1;
		}
		
		$queryCmd="INSERT INTO shiksha.track_autosuggest(session_id, suggestion_shown, user_action, user_input, suggestion_picked, suggestion_no, navigate, free_text_search) VALUES ('".$sessionId."','".$suggestionShown."', '".$actionTakenByUser."', '".$userInput."', '".$pickedSuggestion."', ".(int)$pickedSuggestionNo." , ".$navigateInSuggestion." , ".$freeTextSearch.")";
		$query=$this->db->query($queryCmd);
		$result = 0;
		if($query){
			$result = 1;
		}
		$response = array(
				array(
					'result'=> $result,
					),
				'struct');
		return $this->xmlrpc->send_response($response);
        }

	function getInstituteTitle($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$type_id = $parameters['1'];
		$listing_type = $parameters['2'];

		//connect DB
		
		
		
		if($this->db == ''){
			log_message('error','can not create db handle in trackAutoSuggest');
		}

		switch($listing_type){
			case 'course':
				$queryCmd="select institute_name from institute i, course_details c where c.institute_id = i.institute_id and c.status = 'live' and c.course_id = ?";
				$query=$this->db->query($queryCmd,$type_id);
				$res = $query->row();
				$dataArr = $res->institute_name;
				break;
			case 'institute':
				$queryCmd="select institute_name from institute i where i.status = 'live' and i.institute_id = ?";
				$query=$this->db->query($queryCmd,$type_id);
				$res = $query->row();
				$dataArr = $res->institute_name;
				break;
			case 'default': 
				$dataArr = ''; 
				break;
		}
		return $this->xmlrpc->send_response($dataArr);
	}
	
	function sgetLDBIdForCourseId($request){
		$parameters = $request->output_parameters();
		$courseId = $parameters[0];
		//connect DB
		$this->listingconfig->getDbConfig(1,$dbConfig);
		
		if($this->db == ''){
			log_message('error','can not create db handle in trackAutoSuggest');
		}
		
		$queryCmd = "select LDBCourseID from clientCourseToLDBCourseMapping where clientCourseID= ? and status='live' order by version asc";
		$LDBID = array();
		$query = $this->db->query($queryCmd,$courseId);
		foreach($query->result() as $row) {
			$id = $row->LDBCourseID;
			if(trim($id)=='')
				$id=-1;
			$LDBID[]=$id;
		}
		if(count($LDBID)==0){
			$LDBID[]=-1;
		}
		return $this->xmlrpc->send_response(utility_encodeXmlRpcResponse($LDBID));
	}
	
	function sgetLdbCourseDetailsForLdbId($request){
		$parameters = $request->output_parameters();
		$ldb_id = $parameters[0];
		$multiple_values = $parameters[1];
		//connect DB
		$this->listingconfig->getDbConfig(1,$dbConfig);
		if($this->db == ''){
			log_message('error','can not create db handle in trackAutoSuggest');
		}
		
		$returnArray = array();
		$queryCmd = "SELECT DISTINCT TCSM.SpecializationName as ldb_specialization_name,
							TCSM.CourseName as ldb_course_name,
							CCLM.LDBCourseID as ldb_id,
							CBT.name as ldb_category_name
							FROM
							clientCourseToLDBCourseMapping CCLM,
							tCourseSpecializationMapping TCSM,
							categoryBoardTable CBT, 
							LDBCoursesToSubcategoryMapping LDBMAPTOSUBCAT 
							WHERE
							TCSM.SpecializationId = CCLM.LDBCourseID 
							AND LDBMAPTOSUBCAT.ldbCourseID = CCLM.LDBCourseID
							AND LDBMAPTOSUBCAT.categoryID = CBT.boardId
							AND LDBMAPTOSUBCAT.status = 'live'
							AND TCSM.status = 'live'
							AND CCLM.status = 'live'
							AND CCLM.LDBCourseID = ?";
		$valuss = intval($ldb_id);					
		$query = $this->db->query($queryCmd,$valuss);
		$results = $query->result();
		if(!empty($results) && is_array($results)) {
				foreach($results as $row){
						if($multiple_values){
								$returnArray["ldb_specialization_name"][$row->ldb_id] = $row->ldb_specialization_name;
								$returnArray["ldb_course_name"][$row->ldb_id] = $row->ldb_course_name;
								$returnArray["ldb_id"][] = $row->ldb_id;
								$returnArray["ldb_category_name"][$row->ldb_id] = $row->ldb_category_name;	
						} else {
								$returnArray["ldb_specialization_name"] = $row->ldb_specialization_name;
								$returnArray["ldb_course_name"] = $row->ldb_course_name;
								$returnArray["ldb_id"] = $row->ldb_id;
								$returnArray["ldb_category_name"] = $row->ldb_category_name;
						}
				}
				
		}
		return $this->xmlrpc->send_response(utility_encodeXmlRpcResponse($returnArray));
	}
	
	
	function getEstablishYearAndSeats($request)
	{
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$institute_id = $parameters['1'];
		$establishYearAndSeats = $this->ListingModel->getEstablishYearAndSeats($this->db,$institute_id,$course_id);
		$bigData=array();
		$j=0;
		for($i=0;$i<count($establishYearAndSeats);$i=$i+3)
		{
			$data['establishedYear'] = $establishYearAndSeats[$i];
			$data['seatsTotal'] = $establishYearAndSeats[$i+1];
			$data['course_id'] = $establishYearAndSeats[$i+2];
			$bigData[$j]=$data;$j++;
			unset($data);
        }
		$response = array($bigData);
		$response = json_encode($response);
		return $this->xmlrpc->send_response($response);
	}
	
	function sgetInstituteLocationDetails($request)
	{
		/* code in use, do not delete*/
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$locationId = $parameters['1'];
		$tabStatus = $parameters['2'];
		
		if($this->db == ''){
			log_message('error','can not create db handle');
		}

		if($tabStatus == 'live')
			$statusClause = " AND ilt.status = 'live' ";
		else if($tabStatus == 'deleted')
			$statusClause = " AND ilt.status in ('live','deleted') ";
		
		$queryCmd = "SELECT locality_id as localityId,ilt.city_id as cityId,ct.countryId,cct.city_name as cityName,localityName,name as countryName
		FROM shiksha_institutes_locations ilt
		LEFT JOIN countryCityTable cct ON cct.city_id = ilt.city_id
		LEFT JOIN countryTable ct ON ct.countryId = cct.countryId
		LEFT JOIN localityCityMapping lcm ON lcm.localityId = ilt.locality_id
		WHERE ilt.listing_location_id = ? $statusClause ";		
		$query = $this->db->query($queryCmd,$locationId);
		$response = json_encode($query->row_array());
		return $this->xmlrpc->send_response($response);
	}

    function checkIfUserIsResponse($request){	
        $parameters = $request->output_parameters();
        $institute_id = $parameters['0'];
        $user_id = $parameters['1'];
        if($this->db == ''){
              log_message('error','can not create db handle');
        }
        $course_ids = array();
        $queryCmd = "SELECT DISTINCT course_id FROM course_details WHERE institute_id = ? and status ='live'";
        $query = $this->db->query($queryCmd,$institute_id);
        foreach($query->result() as $row){
              $course_ids[] = $row->course_id;
        }
        $isResponse = 'false';
        if(!empty($course_ids)){
              $queryCmd = "select * FROM tempLMSTable WHERE  listing_subscription_type='paid' and listing_type = 'course' AND listing_type_id IN (?) AND action In ('Request_E-Brochure','GetFreeAlert') AND userId = ?";
              $query = $this->db->query($queryCmd,array($course_ids,$user_id));
              if($query->num_rows()>0){
                       $isResponse = 'true';
              }
              if($isResponse == 'false'){
                       $queryCmd = "(SELECT userId FROM tempLMSTable WHERE listing_subscription_type='paid' and listing_type = 'institute' AND listing_type_id = ? AND ACTION = 'Request_E-Brochure' AND userId = ?) UNION (SELECT userId FROM tempLMSTable WHERE listing_type = 'institute' AND listing_type_id = ? AND listing_subscription_type='paid' AND ACTION = 'GetFreeAlert' AND userId = ?)" ;
                       $query = $this->db->query($queryCmd,array($institute_id,$user_id,$institute_id,$user_id));
                       if($query->num_rows()>0){
                             $isResponse = 'true';
                       }
              }
        }
        $response = array($isResponse,'string');
        return $this->xmlrpc->send_response($response);
    }


	function updateBulkListingsContactDetails($request){
            
                $parameters = $request->output_parameters();

                /*
                 *  Lets update the contact details for the selected locations of the INSTITUTE first..
                 */                
                 $this->updateContactDetailsForInstitute($parameters);
                 
                /*
                 *  Now update the contact details for the selected COURSES and their respective selected locations..
                 */
                $locationIds_for_courses = $parameters[0]['locationIds_for_courses'];
                if($locationIds_for_courses != "") {
                    $courseValuesArray = explode(", ", $locationIds_for_courses);
                    $courseValuesArrayLen = count($courseValuesArray);
                    // Collect the courses' Ids and their respective institute location ids those need to be updated..
                    for($i = 0; $i < $courseValuesArrayLen; $i++) {
                            $courseInfoToBeUpodatedArray = array();
                            $courseInfoToBeUpodatedArray = explode("||", $courseValuesArray[$i]);
                            $courseId = $courseInfoToBeUpodatedArray[0];
                            $InsLocId = $courseInfoToBeUpodatedArray[1];
                            $courselocationIdsArray[$courseId][] = $InsLocId;
                    }

                    foreach($courselocationIdsArray as $courseId => $InsLocIdsArray) {
                        $this->updateContactDetailsForCourse($parameters, $courseId, $InsLocIdsArray);
                    }
                }
                
                return $this->xmlrpc->send_response(1);
        }

        function updateContactDetailsForInstitute($parameters){
		$contact_name_location = $parameters[0]['contact_name_location'];
                $contact_phone_location = $parameters[0]['contact_phone_location'];
                $contact_mobile_location = $parameters[0]['contact_mobile_location'];
                $contact_email_location = $parameters[0]['contact_email_location'];
                $locationIds_for_institute = $parameters[0]['locationIds_for_institute'];
                $institute_id = $parameters[0]['institute_id'];

                if($locationIds_for_institute == "") {
                    return 0;
                }

                $this->db = $this->_loadDatabaseHandle('write');
                $draftVersion = 0;
                $liveVersion = 0;
                $old_version = 1;
                $draftVersion = $this->updateInstituteStatus($this->db, $institute_id, 'draft', 'history');
                $status = 'draft';

                $versionArray = $this->ListingModel->getListingMaxVersionInfo($institute_id, "institute");
                $version = $versionArray[0]['version'];
                
                $new_version =  $version + 1;

                // Get the Old Version ID for this listing..
                if($draftVersion > 0){
                    $old_version = $draftVersion;
                } else {
                    $queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "institute" and listing_type_id= ?';
                    $query =  $this->db->query($queryCmd,$institute_id);
                    foreach ($query->result() as $row){
                        $liveVersion = $row->version;
                    }

                    if($liveVersion > 0){
                        $old_version = $liveVersion;
                    } else {
                        $old_version = 1;
                    }
                }
                
                $data= array();
                $this->ListingModel->getLocationAndContactInfoForListing($this->db, $institute_id, 'institute', $old_version, $data);

                $locationToBeUpdatedArray = explode(", ", $locationIds_for_institute);
                $arrayLength = count($data['contactInfo']);
                for($i = 0; $i < $arrayLength; $i++) {
                    if(in_array($data['contactInfo'][$i]['institute_location_id'], $locationToBeUpdatedArray)) {
                        $data['contactInfo'][$i]['contact_person_name'] = $contact_name_location;
                        $data['contactInfo'][$i]['main_phone_number'] = $contact_phone_location;
                        $data['contactInfo'][$i]['mobile_number'] = $contact_mobile_location;
                        $data['contactInfo'][$i]['contact_person_email'] = $contact_email_location;
                    }
                }
                
               $response = $this->replicateInstitute($this->db, $institute_id, $old_version, $new_version, $status, $data);
        }

       function updateContactDetailsForCourse($parameters, $courseId, $InsLocIdsArray) {
		$contact_name_location = $parameters[0]['contact_name_location'];
                $contact_phone_location = $parameters[0]['contact_phone_location'];
                $contact_mobile_location = $parameters[0]['contact_mobile_location'];
                $contact_email_location = $parameters[0]['contact_email_location'];
                $institute_id = $parameters[0]['institute_id'];
                $this->db = $this->_loadDatabaseHandle('write');
                $draftVersion = 0;
                $liveVersion = 0;
                $draftVersion = $this->updateCourseStatus($this->db, $courseId, 'draft', 'history');
                $status = 'draft';

                $versionArray = $this->ListingModel->getListingMaxVersionInfo($courseId, "course");
                $version = $versionArray[0]['version'];
                
                $new_version =  $version + 1;

                // Get the Old Version ID for this listing..
                if($draftVersion > 0){
                        $old_version = $draftVersion;
                }
                else{
                        $queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "course" and listing_type_id= ?';
                        $query =  $this->db->query($queryCmd,$courseId);
                        foreach ($query->result() as $row){
                                $liveVersion = $row->version;
                        }

                        if($liveVersion > 0){
                                $old_version = $liveVersion;
                        } else {
                                $old_version = 1;
                        }
                }

                $data= array();
                $data['relicate_features'] = "YES";
                $this->ListingModel->getLocationAndContactInfoForListing($this->db, $courseId, 'course', $old_version, $data);

                // Need to be set only if Global contact details (InsLocId = 0) chkbox is checked to be changed by the user..
                if(in_array(0, $InsLocIdsArray)) {
                    $data['contact_name'] = $contact_name_location;
                    $data['contact_main_phone'] = $contact_phone_location;
                    $data['contact_cell'] = $contact_mobile_location;
                    $data['contact_email'] = $contact_email_location;
                }

                $innerSeparator = "|=#=|";
                $outerSeparator = "||++||";
                $course_contact_details_locationwise = "";
                $courseContactDetailsArray = explode($outerSeparator, $data['course_contact_details_locationwise']);

                $len = count($courseContactDetailsArray);
                for($i = 0 ;$i < $len; $i++) {
                    $courseContactDetailsLocationArray = explode($innerSeparator, $courseContactDetailsArray[$i]);
                    $dbInsLocIdArray[] = $courseContactDetailsLocationArray[0]; // Collecting all ins loc ids that are in DB..
                    // Lets check if we need to change the contact details for this location id..
                    if($courseContactDetailsLocationArray[0] != 0 && in_array($courseContactDetailsLocationArray[0], $InsLocIdsArray)) {
                        $contactDetail = $courseContactDetailsLocationArray[0].$innerSeparator.$contact_name_location.$innerSeparator.$contact_phone_location.$innerSeparator.$contact_mobile_location.$innerSeparator.$contact_email_location;
                        $course_contact_details_locationwise .= ($course_contact_details_locationwise == "" ? $contactDetail : $outerSeparator.$contactDetail);
                    } else {
                        $course_contact_details_locationwise .= ($course_contact_details_locationwise == "" ? $courseContactDetailsArray[$i] : $outerSeparator.$courseContactDetailsArray[$i]);
                    }
                }

                $newContactInsLocIdArray = array_diff($InsLocIdsArray, $dbInsLocIdArray);  // Contact details were not there earlier for these Ins Loc Ids..
                $len = count($newContactInsLocIdArray);
                if(is_array($newContactInsLocIdArray) && $len > 0) {
                       foreach($newContactInsLocIdArray as $key => $insLocIdNew){
                        $contactDetail = $insLocIdNew.$innerSeparator.$contact_name_location.$innerSeparator.$contact_phone_location.$innerSeparator.$contact_mobile_location.$innerSeparator.$contact_email_location;
                        $course_contact_details_locationwise .= ($course_contact_details_locationwise == "" ? $contactDetail : $outerSeparator.$contactDetail);
                    }
                }

                $data['course_contact_details_locationwise'] = $course_contact_details_locationwise;
                
                $response = $this->replicateCourse($this->db, $courseId, $old_version, $new_version, $status, $data);
        }


        function upgradeCourse($request) {
                $appId = 1;
                $parameters = $request->output_parameters();
		$courseId = $parameters[0]['courseId'];
                $expiryDate = $parameters[0]['SubscriptionEndDate'];
                $subscriptionId = $parameters[0]['SubscriptionId'];
                $clientId = $parameters[0]['clientId'];
                $editedBy = $parameters[0]['editedBy'];; // User id of 'edy@shiksha.com' to indicate this update has been done by the Auto Downgrade Listing Script.
                
                // error_log("\n\n DATA = ".print_r($parameters, true),3,'/home/infoedge/Desktop/log.txt');die;
                $this->db = $this->_loadDatabaseHandle('write');
                $draftVersion = 0;
                $liveVersion = 0;
                $draftVersion = $this->updateCourseStatus($this->db, $courseId, 'draft', 'history');
                $status = 'draft';

                $versionArray = $this->ListingModel->getListingMaxVersionInfo($courseId, "course");
                $version = $versionArray[0]['version'];

                $new_version =  $version + 1;

                // Get the Old Version ID for this listing..
                $old_version = $this->getOldVersionOfCourse($courseId, $draftVersion);
                
                $data= array();
                $data['relicate_features'] = "YES";
                $data['editedBy'] = $editedBy;
                // $data['old_version'] = $old_version;
                $this->ListingModel->getLocationAndContactInfoForListing($this->db, $courseId, 'course', $old_version, $data);
                // error_log("\n\n DATA for course id - $courseId = ".print_r($data, true),3,'/home/infoedge/Desktop/log.txt');die;

                // Replicate the Course data now..
                $response = $this->replicateCourse($this->db, $courseId, $old_version, $new_version, $status, $data);
                // error_log("\n------------------------------------------------\n REPLICATION DONE for course id - $courseId = ",3,'/home/infoedge/Desktop/log.txt');

                // Now consume this subscription on SUMS side..
                $this->load->library('Subscription_client');
                $subsObj = new Subscription_client();
                $resp = $subsObj->consumePseudoSubscription($appId, $subscriptionId, '-1', $clientId, $editedBy,'-1', $courseId, 'course', '-1', '-1');                        
        }

        function getOldVersionOfCourse($courseId, $draftVersion) {
                if($draftVersion > 0){
                        $old_version = $draftVersion;
                }else{
                        $queryCmd = 'select max(version) as version from listings_main where status="live" and listing_type = "course" and listing_type_id= ?';
                        $query =  $this->db->query($queryCmd,$courseId);
                        foreach ($query->result() as $row){
                                $liveVersion = $row->version;
                        }

                        if($liveVersion > 0){
                                $old_version = $liveVersion;
                        } else {
                                $old_version = 1;
                        }
                }

                return $old_version;
        }
}
?>
