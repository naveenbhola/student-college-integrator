<?php 
 class ArticleUtilityLib {
 	private $CI;

 	function __construct(){
 		$this->CI =& get_instance();
 	}

	public function getLimitForPagination($currentPage,$pageSize=20){
		if(empty($currentPage) || !is_numeric($currentPage) || $currentPage < 1){
			$currentPage = 1;
		}
		$limit['lowerLimit'] = ($currentPage-1)*$pageSize;
		$limit['pageSize'] = $pageSize;
		return $limit;
	}

 	function getHierarchyId($stream,$subStream='any',$specialization='any'){
 		if(empty($stream) && empty($subStream) && empty($specialization)){
 			return;
 		}
 		$this->CI->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();
        $hierarchyRepo = $listingBase->getHierarchyRepository();
        $hierarchyIds = $hierarchyRepo->getHierarchyIdByBaseEntities($stream,$subStream,$specialization,'array');
        return implode(',',$hierarchyIds);
 	}

 	public function getTotalArticlesBasedOnHierarchy($hierarchyIds,$articleIds,&$articleModel){
		if(empty($articleModel)){
			$articleModel = $this->CI->load->model('article/articlenewmodel');
		}
		$res = $articleModel->getTotalArticlesBasedOnHierarchy($hierarchyIds,$articleIds);
		return $res[0]['totalArticles'];
	}

	public function getTotalArticlesBasedOnCourse($popularCourseId,$articleIds,&$articleModel){
		if(empty($articleModel)){
			$articleModel = $this->CI->load->model('article/articlenewmodel');
		}
		$res = $articleModel->getTotalArticlesBasedOnCourse($popularCourseId,$articleIds);
		return $res[0]['totalArticles'];
	}

	public function getTotalArticles(&$articleModel){
		if(empty($articleModel)){
			$articleModel = $this->CI->load->model('article/articlenewmodel');
		}
		$res = $articleModel->getTotalArticles();
		return $res[0]['totalArticles'];
	}

	public function getCommentCountForArticles($discussionTopicIds,&$articleModel){
		if(empty($articleModel)){
			$articleModel = $this->CI->load->model('article/articlenewmodel');
		}
		$comments = $articleModel->getCommentCountForArticles($discussionTopicIds);
		foreach ($comments as $key => $value) {
            $returnArray[$value['msgId']] = $value['commentCount'];
        }
		return $returnArray;
	}

	public function getSEOUrls($currentPage,$totalArticles,$pageSize,$paginationURL){
		if($currentPage < ceil($totalArticles/$pageSize) && $totalArticles>0){
			$returnArray['nextURL'] = str_replace("@pageno@", $currentPage+1, $paginationURL);
		}elseif($currentPage > ceil($totalArticles/$pageSize) && $currentPage > 1){
			redirect(str_replace("-@pageno@", '', $paginationURL),'location','301');
		}
		if($currentPage > 1){
			$returnArray['prevURL'] = str_replace("@pageno@", $currentPage-1, $paginationURL);
		}
		$returnArray['canonicalURL'] = rtrim(str_replace("@pageno@", '', $paginationURL),'-');
		return $returnArray;
	}

	public function blogRedirectRules(&$displayData){
		$redirectFlag = true;
		$this->CI->config->load('article/articleConfig');
		switch ($displayData['blogId']) {
			case 154:
				$newUrl = SHIKSHA_HOME.'/careers/hotel-manager-62';
				break;
			case 232: case 233: case 284: case 2115:
				$newUrl = SHIKSHA_HOME.'/careers/army-officer-14';
				break;
			case 7825:
				$newUrl = SHIKSHA_HOME.'/jee-main-college-predictor-find-college-branch-based-on-your-rank-article-10276-1';
				break;
			case 7826:
				$newUrl = SHIKSHA_HOME.'/jee-main-college-predictor-know-college-cut-offs-article-10277-1';
				break;
			case 7827:
				$newUrl = SHIKSHA_HOME.'/jee-main-college-predictor-find-college-for-a-branch-article-10279-1';
				break;
			case 7828:
				$newUrl = SHIKSHA_HOME.'/shiksha-com-launches-jee-main-college-predictor-tool-article-10275-1';
				break;
			default:
				$this->CI->config->load('article/articleConfig');
				$articlesToAdmissionPageMap = $this->CI->config->item("articlesToAdmissionPageMap");
				if(!empty($articlesToAdmissionPageMap[$displayData['blogId']])){
				 	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
			    	$instituteBuilder = new InstituteBuilder();
					$instituteRepo = $instituteBuilder->getInstituteRepository();
				    $instituteObj = $instituteRepo->find($articlesToAdmissionPageMap[$displayData['blogId']]);
				    $newUrl = $instituteObj->getAllContentPageUrl('admission');
				    break;
				}
				if ($displayData['pageNum'] <= 0){
					$this->CI->load->library('common/Seo_client');
					$Seo_client = new Seo_client();
					$dbURL = $Seo_client->getURLFromDB($displayData['blogId'],'blog');
					if($displayData['ampViewFlag']) {
						$dbURL['URL'] = getAmpPageURL('blog', $dbURL['URL']);
                                                if(strpos($dbURL['URL'],'/boards') === 0){
                                                        $dbURL['URL'] = getAmpPageURL('boards', $dbURL['URL']);
                                                }
                                                if(strpos($dbURL['URL'],'/courses-after-12th') === 0){
                                                        $dbURL['URL'] = getAmpPageURL('coursesAfter12th', $dbURL['URL']);
                                                }
					}
					$enteredURL = getCurrentPageURLWithoutQueryParams();
					$dbURL['URL'] = SHIKSHA_HOME.$dbURL['URL'];
					if($dbURL['URL']!='' && $dbURL['URL']!=$enteredURL)	{
						$QUERY_STRING = $this->CI->input->server('QUERY_STRING');
						$newUrl = $dbURL['URL'];
		                if($QUERY_STRING!='' && $QUERY_STRING!=NULL) {
		                	$newUrl = $newUrl."?".$QUERY_STRING;
		                }
					}else{
						$redirectFlag = false;
					}
				}else{
					$redirectFlag = false;
				}
		}
		if($redirectFlag){
			redirect($newUrl, 'location', 301);
			exit();
		}
	}

	public function blogShow404Rules(&$displayData){
		if($displayData['blogId']=='' || !is_numeric($displayData['blogId']) || $displayData['blogId'] <= 0){
			show_404();
			exit();
        }
		$this->CI->config->load('article/articleConfig');
		$kumkumArticlesToCareerPageMap = $this->CI->config->item("kumkumArticlesToCareerPageMap");
		$kumkumArticlesOnCareer = array_keys($kumkumArticlesToCareerPageMap);
		if(in_array($displayData['blogId'], $kumkumArticlesOnCareer)){
			show_404();
			exit();
		}
	}

	function createUrlForPopularCourseBasedArticleListingPage(&$data){
		$this->getQueryParam($data);
		$data['url'] = SHIKSHA_HOME."/".strtolower(seo_url($data['result']['popularCourseName'])).'/articles-pc-'.$data['result']['base_course_id'].$data['queryParam'];
	}

	function createUrlForSubstreamBasedArticleListingPage(&$data){
		$this->getQueryParam($data);
		$data['url'] = SHIKSHA_HOME."/".strtolower(seo_url($data['result']['streamName']))."/".strtolower(seo_url($data['result']['subStreamName'])).'/articles-sb-'.$data['result']['stream_id']."-".$data['result']['substream_id'].$data['queryParam'];
	}

	function createUrlForStreamBasedArticleListingPage(&$data){
		$this->getQueryParam($data);
		$data['url'] = SHIKSHA_HOME."/".strtolower(seo_url($data['result']['streamName'])).'/articles-st-'.$data['result']['stream_id'].$data['queryParam'];
	}

	function getQueryParam(&$data){
		if(!empty($data['result']['delivery_method'])){
			$data['queryParam'] = '?dm='.$data['result']['delivery_method'];
			$data['filters']['delivery_method'] = $data['result']['delivery_method'];
		}
		if(!empty($data['result']['education_type'])){
			if(!empty($data['queryParam'])){
				$data['queryParam'] .= '&';
			}else{
				$data['queryParam'] .= '?';
			}
			$data['queryParam'] .= 'et='.$data['result']['education_type'];
			$data['filters']['education_type'] = $data['result']['education_type'];
		}
		if(!empty($data['result']['base_course_id']) && $data['case'] != 'popularCourse'){
			if(!empty($data['queryParam'])){
				$data['queryParam'] .= '&';
			}else{
				$data['queryParam'] .= '?';
			}
			$data['queryParam'] .= 'bc='.$data['result']['base_course_id'];
			$data['filters']['base_course_id'] = $data['result']['base_course_id'];
		}
	}

	function getFilteredArticles($filters,&$articleModel){
		if(empty($articleModel)){
			$articleModel = $this->CI->load->model('article/articlenewmodel');
		}
		$otherAttributeIds = $filters['delivery_method'];
		if(!empty($otherAttributeIds) && !empty($filters['education_type'])){
			$otherAttributeIds .= ',';
		}
		$otherAttributeIds .= $filters['education_type'];
		$articleId = $articleModel->getIdsFromOtherAttributes($otherAttributeIds);
		$articleIds = $this->getArticleIds($articleId);
		$articleId = $articleModel->getIdsFromCourse($filters['base_course_id'],$articleIds);
		$articleIds2 = $this->getArticleIds($articleId);
		if(!empty($articleIds) && !empty($articleIds2)){
			$articleIds .= ',';
		}
		$articleIds .= $articleIds2;
		return $this->removeDuplicateIds(explode(',',$articleIds));
	}

	function getArticleIds($articleId){
		foreach ($articleId as $key => $value) {
			if($key != 0){
				$articleIds .= ',';
			}
			$articleIds .= $value['articleId'];
		}
		return $articleIds;
	}

	function removeDuplicateIds($articleIdArray){
		return implode(',',array_unique($articleIdArray));
	}

	function getFormattedDateDiff($dateDiffinDays) {
	    if($dateDiffinDays > 30) {
			$dateDiffinMonths = floor($dateDiffinDays / 30);
			$dateDiff = 'Added '.$dateDiffinMonths.($dateDiffinMonths > 1 ? ' months': ' month'). ' ago';
	    } else {
			$dateDiff = 'Added '.$dateDiffinDays.($dateDiffinDays > 1 ? ' days': ' day'). ' ago';
	    }    
	    return $dateDiff;
	}

	function urlExists($fileUrl) {
		$AgetHeaders = @get_headers($fileUrl);
		if (preg_match("|200|", $AgetHeaders[0])) {	// file exists
			return true;
		} else {					// file doesn't exists
			return false;
		}
	}

	function parseInputForWidget($value){
		$input['stream_id'] = $value['streamId'];
		$input['substream_id'] = $value['substreamId'];
		$input['base_course_id'] = $value['baseCourseId'];
		$input['education_type'] = $value['educationType'];
		$input['delivery_method'] = $value['deliveryMethod'];
		$input['courseHomePageName'] = $value['Name'];
		$input['courseHomePageId'] = $value['courseHomeId'];
		$input['courseIds'] = array($value['baseCourseId']);
		return $input;
	}

	function parseFilters($paramArray){
		foreach ($paramArray as $key => $value) {
			$this->validateInput($value);
		}
		if(!empty($paramArray['bc'])){
			$filter['base_course_id'] = $paramArray['bc'];
		}
		if(!empty($paramArray['et'])){
			$filter['education_type'] = $paramArray['et'];
		}
		if(!empty($paramArray['dm'])){
			$filter['delivery_method'] = $paramArray['dm'];
		}
		return $filter;
	}

	function validateInput($variable){
		if(!empty($variable) && !preg_match('/^\d+$/',$variable) || $variable < 0){
			show_404();
		}
	}

	

	function getQuickLinksData($quickLinkData){
		$instituteIds = $examIds = $careerIds = $finalInstArr = $quickLinkArr = $finalExamArr = $finalCareerArr = array();
	 	foreach ($quickLinkData as $key => $value) {
	 		if($value['entityType'] == 'college' || $value['entityType'] == 'university' || $value['entityType'] == 'group' ){
	 			if(!empty($value['entityId']))
	 			{
	 				$instituteIds[] = $value['entityId'];
	 			}
	 		}
	 		if($value['entityType'] == 'exam'){
	 			$examIds[] = $value['entityId'];
	 		}
	 		if($value['entityType'] == 'career'){
	 			$careerIds[] = $value['entityId'];
	 		}
	 	}
	 	if(!empty($instituteIds)){	
		 	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    	$instituteBuilder = new InstituteBuilder();
			$instituteRepo = $instituteBuilder->getInstituteRepository();
		    $instituteObjs = $instituteRepo->findMultiple($instituteIds);
		    $finalInstArr = array();
		    foreach ($instituteObjs as $key => $value) {
		    	$finalInstArr[$key]['shortName'] = $value->getShortName();
		    	$finalInstArr[$key]['fullName'] = $value->getName();
		    	$finalInstArr[$key]['url'] = $value->getURL();
		    }
		}
	 	$instCount = count($instituteIds);
	 	$maxQuicklinks = 8;
	 	if($instCount >= $maxQuicklinks){
	 		shuffle($finalInstArr);
	 		$finalInstArr = array_slice($finalInstArr,0, $maxQuicklinks);
	 		$quickLinkArr['instData'] = $finalInstArr;
	 	}else {
	        
	        $this->examPostingLib = $this->CI->load->library('examPages/ExamPostingLib');
            $finalExamArr = $this->examPostingLib->getExamNameByExamId($examIds);
			$examCount = count($finalExamArr);
			if(($examCount + $instCount) >= $maxQuicklinks){
				$quickLinkArr['instData'] = $finalInstArr;
				$remainingLinks = $maxQuicklinks - $instCount;
		 		shuffle($finalExamArr);
		 		$finalExamArr = array_slice($finalExamArr,0, $remainingLinks);
				$quickLinkArr['examData'] = $finalExamArr;
			}
			else {
				$this->CI->load->model('Careers/careermodel');
				$this->careerModel = new Careermodel();	
				$finalCareerArr = $this->careerModel->getCareerDataForQuickLinks(implode(',', $careerIds));
		 		$quickLinkArr['instData'] = $finalInstArr;
				$quickLinkArr['examData'] = $finalExamArr;
				$remainingLinks = $maxQuicklinks - $instCount - $examCount;
				shuffle($finalCareerArr);
		 		$finalCareerArr = array_slice($finalCareerArr,0, $remainingLinks);
				$quickLinkArr['careerData'] = $finalCareerArr;
			}	
	 	}
		return $quickLinkArr;
	}

	function migrateOrDeleteArticleMapping($listingIds,$newListingId){
		if(empty($listingIds) || (!is_array($listingIds))|| (!(count($listingIds) > 0))){
   			return 'Old Listing is Not Valid,not able to Migrate / Delete Article Mapping.';
   		}

   		$this->articleModel = $this->CI->load->model('article/articlenewmodel');
   		$result = $this->articleModel->checkIfArticleMappingExist($listingIds);
   		if($result == true){
   			if(empty($newListingId)){
	            $responses = $this->articleModel->deleteArticleListingMapping($listingIds);
	        }else{
	            if(!($newListingId > 0)){
	                return 'New listing is invalid,not able to Migrate / Delete Article Mapping.';
	            }else{
	                $responses = $this->articleModel->migrateArticleListingMapping($listingIds,$newListingId);
	            }
	        }
			
	    	if($responses){
	    		return 'Article Mapping is migrated / deleted.';
	    	}else{
	    		return 'Article Mapping is not migrated / deleted.';
	    	}
   		}else{
   			return 'Article migration not applicable.';
   		}
	   		
	}
	
	function getArticleMappingForRecommendedArticles($blogIds = array()){
		if(empty($blogIds)){
			return array();
		}
		$articleModel = $this->CI->load->model('article/articlenewmodel');
		$articleHier = $articleModel->getArticleHierarchyForRecommendedArticles($blogIds);

		if(!empty($articleHier[0])){
   			$mappingData = Modules::run('common/commonHierarchyForm/getBaseEntityIdsByHierarchyId',$articleHier[0],1);	
    	}
    	if(!empty($mappingData)){
	    	foreach ($mappingData as $hierId => $entityValue) {
	    		if(!empty($entityValue['stream'])){
	    			$returnArr['stream_id'] = $entityValue['stream']['id'];
	    		}if(!empty($entityValue['substream'])){
	    			$returnArr['substream_id'] = $entityValue['substream']['id'];	
	    		}if(!empty($entityValue['specialization'])){
	    			$returnArr['spec_id'] = $entityValue['specialization']['id'];
	    		}
	    	}
	    }

		return $returnArr;		
	}

	function getYearBuckets(){
		$yearFrom = 2008;
		$yearTo = (int)date('Y') - 1;
		$yearBuckets = array();
		for($i=$yearFrom;$i<=$yearTo;$i++)
		{
		    $yearBuckets[] = $i;
		}
		return $yearBuckets;
	}

	function isYearContains($resultArr){
		$yearBuckets = $this->getYearBuckets();
		foreach ($resultArr as $key => $value) {
			$string = $value->nl_entity_name;
			$outPut = findMultipleValues($string);
			$matchResult = array_intersect($yearBuckets, $outPut);
			if(count($matchResult)){
				$unsetKeys[] = $key;
			}
		}
		foreach ($unsetKeys as $key => $value) {
			unset($resultArr[$value]);
		}
		unset($unsetKeys);
		return $resultArr;
	}

	function showRelatedArticles($articleId){
		$data = $this->getArticleMappingForRecommendedArticles(array($articleId));
		$streamId = $data['stream_id'];
		if(empty($streamId)){
			$logFile = "/tmp/relatedArticleLog".date('Y-m-d');
			error_log($articleId."\n", 3, $logFile);
		}
		$subStreamId = $data['substream_id'];
		$specId = $data['spec_id'];
		$finalData = array();
		$secondLevelArr = array();
		$thirdLevelArr = array();
     	$this->CI->load->builder('SearchBuilder','search');
  	    $solrServer = SearchBuilder::getSearchServer();
        $solrBaseURL = $solrServer->getSolrURL('mlt','select');
		
		$url1 = $solrBaseURL."q=nl_entity_id:".$articleId."&facetype:ugc&nl_entity_type:article&wt=json&indent=true&mlt.mindf=1&mlt.mintf=1&mlt.fl=nl_entity_name&fl=nl_entity_id,%20nl_entity_name,nl_entity_url&fq=nl_entity_stream:".$streamId."&fq=nl_entity_substream:".$subStreamId."&fq=nl_entity_specialization:".$specId;
		$url2 = $solrBaseURL."q=nl_entity_id:".$articleId."&facetype:ugc&nl_entity_type:article&wt=json&indent=true&mlt.mindf=1&mlt.mintf=1&mlt.fl=nl_entity_name&fl=nl_entity_id,%20nl_entity_name,nl_entity_url&fq=nl_entity_stream:".$streamId."&fq=nl_entity_substream:".$subStreamId;
		$url3 = $solrBaseURL."q=nl_entity_id:".$articleId."&facetype:ugc&nl_entity_type:article&wt=json&indent=true&mlt.mindf=1&mlt.mintf=1&mlt.fl=nl_entity_name&fl=nl_entity_id,%20nl_entity_name,nl_entity_url&fq=nl_entity_stream:".$streamId;
		
		$ch = curl_init();
		
		if($streamId != '' && $subStreamId != '' && $specId != ''){
			curl_setopt($ch, CURLOPT_URL, $url1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
        	$decodeData = json_decode($response);
        	$resultLevel1 = $this->isYearContains($decodeData->response->docs);
        }
        
		if(count($resultLevel1) < 4){
			if($streamId != '' && $subStreamId != ''){
	        	curl_setopt($ch, CURLOPT_URL, $url2);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$responseLevel2 = curl_exec($ch);
	        	$decodeDataLevel2 = json_decode($responseLevel2);
	        	$resultLevel2 = $this->isYearContains($decodeDataLevel2->response->docs);
	        }
	        if(!empty($resultLevel2) && !empty($resultLevel1)){
	        	foreach ($resultLevel2 as $key1 => $value1) {
	        		foreach ($resultLevel1 as $key2 => $value2) {
	        			if($value2->nl_entity_id == $value1->nl_entity_id){
	        				unset($resultLevel2[$key1]); 
	        			}
	        			
	        		}
	        	}
	        }
	        $noOfSecondLevelVal = 4 - (count($resultLevel1));
        	if($noOfSecondLevelVal > count($resultLevel2)){
        		$noOfSecondLevelVal = count($resultLevel2);		
        	}
        	if($noOfSecondLevelVal == 1){
        		if(count($resultLevel2) == 1){
	        		$secondLevelArr = $resultLevel2;        			
        		}else{
        			$randomNo = array_rand($resultLevel2, 1);
        			$secondLevelArr[$randomNo] = $resultLevel2[$randomNo];
        		}
        	}else {
        		$secondLevelValues = array_rand($resultLevel2, $noOfSecondLevelVal);
        		foreach ($secondLevelValues as $ind => $val) {
					$secondLevelArr[] = $resultLevel2[$val];
				}
			}
			if(empty($secondLevelArr) && empty($resultLevel1)){
				$finalData = array();
			}
			else if(empty($secondLevelArr)){
				$finalData = $resultLevel1;
			}
			else if(empty($resultLevel1)){
				$finalData = $secondLevelArr;
			}else {
	        	$finalData = array_merge($resultLevel1, $secondLevelArr);	
			}
        }else{
        	$finalDataValues = array_rand($resultLevel1, 4);
        	foreach ($finalDataValues as $ind => $val) {
				$finalData[] = $resultLevel1[$val];
			}
        }

     	if(count($finalData) < 4 && $streamId != ''){
     		curl_setopt($ch, CURLOPT_URL, $url3);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$responseLevel3 = curl_exec($ch);
        	$decodeDataLevel3 = json_decode($responseLevel3);

        	$resultLevel3 = $this->isYearContains($decodeDataLevel3->response->docs);
        	
        	$noOfThirdLevelVal = 4 - (count($resultLevel1) + count($resultLevel2));
        	
        	if(!empty($resultLevel3) && !empty($finalData)){
	        	foreach ($resultLevel3 as $key1 => $value1) {
	        		foreach ($finalData as $key2 => $value2) {
	        			if($value2->nl_entity_id == $value1->nl_entity_id){
	        				unset($resultLevel3[$key1]); 
	        			}
	        			
	        		}
	        	}
	        }
        	if($noOfThirdLevelVal > count($resultLevel3)){
        		$noOfThirdLevelVal = count($resultLevel3);		
        	}
        	if($noOfThirdLevelVal == 1){
        		if(count($resultLevel3) == 1){
	        		$thirdLevelArr = $resultLevel3;        			
        		}else{
        			$randomNo = array_rand($resultLevel3, 1);
        			$thirdLevelArr[$randomNo] = $resultLevel3[$randomNo];
        		}
        	}else {
	     	    $thirdLevelValues = array_rand($resultLevel3, $noOfThirdLevelVal);
				foreach ($thirdLevelValues as $ind => $val) {
					$thirdLevelArr[] = $resultLevel3[$val];
				}
			}
			if(empty($thirdLevelArr) && empty($finalData)){
				$finalData = array();
			}
			if(!empty($thirdLevelArr)) {
	        	$finalData = array_merge($finalData, $thirdLevelArr);	
			}
        }

		if(empty($finalData))
		{
			return;
		}
        curl_close ($ch);
		$relatedArticles = array();
		foreach ($finalData as $key => $value) {
			$relatedArticles[$key]['blogTitle'] = $value->nl_entity_name;
			$relatedArticles[$key]['blogId'] = $value->nl_entity_id;
			//$relatedArticles[$key]['blogImageURL'] = $value->article_image_url;
			$relatedArticles[$key]['url'] = $value->nl_entity_url;
			$blogIdArray[$key] = $value->nl_entity_id;
		}
		$qnamodel      = $this->CI->load->model("messageBoard/qnamodel");
		$result = $qnamodel->getCommentAndViewCount($blogIdArray);
		foreach ($relatedArticles as $key => $value) {
			$relatedArticles[$key]['viewCount'] = $result[$value['blogId']]['blogView'];
			$relatedArticles[$key]['commentCount'] = $result[$value['blogId']]['msgCount'];
		}
		return $relatedArticles;
	}

	function getCityAndStateMappedToArticle($blogIds = array()){
		if(empty($blogIds)){ return array();}
		$articleModel = $this->CI->load->model('article/articlenewmodel');
		$result = $articleModel->getCityAndStateMappedToArticle($blogIds);
		foreach ($result as $city => $data) {
	 		if($data['entityType'] == 'college' || $data['entityType'] =='university'){
	 			$cityStateClgArray['clgUnivIds'][] = $data['entityId'];
	 		}
	 		else if($data['entityType'] == 'city'){
	 			$cityStateClgArray['cityIds'][] = $data['entityId'];
	 		}
	 		else if($data['entityType'] == 'state'){
	 			$cityStateClgArray['stateIds'][] = $data['entityId'];
	 		}
	 		else if($data['entityType'] == 'exam'){
	 			$cityStateClgArray['examIds'][] = $data['entityId'];
	 		}
	 	}
		return $cityStateClgArray;
	}	

	function getGTMArray($beaconTrackData , $cityStateClgArray){
		foreach ($beaconTrackData['extraData']['hierarchy'] as $key => $value) {
			if($value['streamId'] != ''){
				$stream[] = $value['streamId'];	
			}
			if($value['substreamId'] != ''){
				$substream[] = $value['substreamId'];	
			}
			if($value['specializationId'] != ''){
				$specialization[] = $value['specializationId'];	
			}
		}
		if(is_array($beaconTrackData['extraData']['baseCourseId'])){
			$baseCourseId = implode(',', $beaconTrackData['extraData']['baseCourseId']);
		}else{
			$baseCourseId = $beaconTrackData['extraData']['baseCourseId'];			
		}
		if(is_array($beaconTrackData['extraData']['educationType'])){	
			$educationType = implode(',', $beaconTrackData['extraData']['educationType']);	
		}else{
			$educationType = $beaconTrackData['extraData']['baseCourseId'];			
		}
		if(is_array($beaconTrackData['extraData']['deliveryMethod'])){	
			$deliveryMethod = implode(',', $beaconTrackData['extraData']['deliveryMethod']);	
		}else{
			$deliveryMethod = $beaconTrackData['extraData']['baseCourseId'];			
		}
		if(is_array($cityStateClgArray['examIds'])){	
			$examIds = implode(',', $cityStateClgArray['examIds']);	
		}else{
			$examIds = $cityStateClgArray['examIds'];			
		}
		if(is_array($cityStateClgArray['clgUnivIds'])){	
			$clgUnivIds = implode(',', $cityStateClgArray['clgUnivIds']);	
		}else{
			$clgUnivIds = $cityStateClgArray['clgUnivIds'];			
		}
		if(is_array($cityStateClgArray['cityIds'])){	
			$cityIds = implode(',', $cityStateClgArray['cityIds']);	
		}else{
			$cityIds = $cityStateClgArray['cityIds'];			
		}
		if(is_array($cityStateClgArray['stateIds'])){	
			$stateIds = implode(',', $cityStateClgArray['stateIds']);	
		}else{
			$stateIds = $cityStateClgArray['stateIds'];			
		}
		
	    $gtmParams = array(
        "pageType" => $beaconTrackData['pageIdentifier'],
     	"stream"=>implode(',',array_unique($stream)),
	 	"substream"=>implode(',',array_unique($substream)),
	 	"specialization"=>implode(',',array_unique($specialization)),
	 	"instituteId"=>$clgUnivIds,
	 	"baseCourseId"=>$baseCourseId,
	 	"educationType"=>$educationType,
	 	"deliveryMethod"=>$deliveryMethod,
	 	"exam"=> $examIds,
     	"cityId" => $cityIds,
        "stateId" => $stateIds,
        "countryId"=> 2
        );
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $gtmParams['workExperience'] = $userStatus[0]['experience'];
	    }
        
        return $gtmParams;
	}

	public function getEntityCountsForRightRegnWidget(){
		$this->CI->load->helper('mAnA5/ana');
		$cacheLib = $this->CI->load->library('cacheLib');
		$cntKey = md5('nationalHomepageCounters_json');
        $data = $cacheLib->get($cntKey);
        $result = array();
        if($data != 'ERROR_READING_CACHE'){
        	$data = json_decode($data, true);
            $result['collegeCount'] = formatNumber($data['national']['instCount']);
            $result['examCount']    = formatNumber($data['national']['examCount']);
            $result['reviewCount']  = formatNumber($data['national']['reviewsCount']);
            $result['answerCount']  = formatNumber($data['national']['questionsAnsweredCount']);
        }
        return $result;
	}

	function getArticleEntitiesFromArticleId($articleMappingObj) {
        $entityIds = array();
        foreach ($articleMappingObj as $key => $value) {
        	$entId = $value->getEntityId();
        	$entType = $value->getEntityType();
        	if(!empty($entId) && !in_array($entId, $entityIds[$entType]) ) {
            	$entityIds[$value->getEntityType()][] = $value->getEntityId();
        	}
        }
        
	    $primaryHierarchyId = $entityIds['primaryHierarchy'][0];
        if(!empty($primaryHierarchyId)) {
	        $this->CI->load->builder('listingBase/ListingBaseBuilder');
	        $listingBase = new ListingBaseBuilder();
	        $hierarchyRepo = $listingBase->getHierarchyRepository();

	        $hierarchyData = $hierarchyRepo->getBaseEntitiesByHierarchyId($primaryHierarchyId);

	        unset($entityIds['primaryHierarchy']);

	    	$entityIds['primaryHierarchy']['stream'] = $hierarchyData[$primaryHierarchyId]['stream_id'];
	    	if(!empty($hierarchyData[$primaryHierarchyId]['substream_id'])) {
	    		$entityIds['primaryHierarchy']['substream'] = $hierarchyData[$primaryHierarchyId]['substream_id'];
	    	}
	    	else {
	    		$entityIds['primaryHierarchy']['substream'] = 0;
	    	}
	    	if(!empty($hierarchyData[$primaryHierarchyId]['specialization_id'])) {
	    		$entityIds['primaryHierarchy']['specialization'] = $hierarchyData[$primaryHierarchyId]['specialization_id'];
	    	} else {
	    		$entityIds['primaryHierarchy']['specialization'] = 0;
	    	}
	    }

	    if(!empty($entityIds['otherAttribute'])) {
	    	$this->baseAttributeLibrary = $this->CI->load->library('listingBase/BaseAttributeLibrary');
	    	$attributes = $this->baseAttributeLibrary->getAttributeNameByValueId($entityIds['otherAttribute']);

	    	foreach ($attributes as $valueId => $attributeName) {
	            if($attributeName == 'Education Type') {
	                $entityIds['education_type'][] = $valueId;
	            }
	            if($attributeName == 'Medium/Delivery Method') {
	                $entityIds['delivery_method'][] = $valueId;
	            }
	        }
	    }

        return $entityIds;
    }

	function getAllArticlePageUrlAndCount($type, $params){
        if(empty($type) || empty($params)){
                return 'ERROR_IN_PASSED_PARAMETERS';
        }
        $articleLib      = $this->CI->load->library('common/UrlLib');
        $articleModel    = $this->CI->load->model('article/articlenewmodel');
        $validArticleIds = array();
        $articleUrlAndCount['url']              = $articleLib->getAllPageUrl($params,false);

        $articleCache = $this->CI->load->library('article/cache/articleCache');
        $articleCount = $articleCache->getArticleCountByHierarchy($params['stream_id'],$params['substream_id'],$params['course']);

        if(!empty($articleCount))
        {
        	$articleUrlAndCount['count'] = $articleCount;
        }
        else
        {
        	if($type=='stream'){
	                $hierarchyIds = $this->getHierarchyId($params['stream_id']);
	                $articleUrlAndCount['count'] = $this->getTotalArticlesBasedOnHierarchy($hierarchyIds,$validArticleIds,$articleModel);
	        }else if($type=='substream'){
	                $hierarchyIds = $this->getHierarchyId($params['stream_id'],$params['substream_id']);
	                $articleUrlAndCount['count'] = $this->getTotalArticlesBasedOnHierarchy($hierarchyIds,$validArticleIds,$articleModel);
	        }else if($type=='course'){
	            	$articleUrlAndCount['count'] = $this->getTotalArticlesBasedOnCourse($params['course'],$validArticleIds,$articleModel);
	        }
	        if(!empty($articleUrlAndCount['count']))
	        {
	        	$articleCache->storeArticleCountByHierarchy($params['stream_id'],$params['substream_id'],$params['course'],$articleUrlAndCount['count']);
	        }
        }

        if(empty($articleUrlAndCount['count'])){
                $articleUrlAndCount =  array();
        }
        return  $articleUrlAndCount;
    }

    function getArticleBasedOnEntity($data, $dataLimit=3){
	$entityType       = $data['entityType'];
	$streamId         = $data['streamId'];
	$substreamId      = $data['substreamId'];
	$popularCourseId  = $data['popularCourseId'];
	$specializationId = $data['specializationId'];
	$limit            = array('pageSize'=>$dataLimit,'lowerLimit'=>'0');
	$validArticleIds  = array(); 
	$lib              = $this->CI->load->library('article/ArticleUtilityLib');
	$articleModel     = $this->CI->load->model('article/articlenewmodel');
	$this->CI->load->builder('ArticleBuilder','article');
	$this->articleBuilder    = new ArticleBuilder;
	$this->articleRepository = $this->articleBuilder->getArticleRepository();
	if($entityType=='stream'){
		$hierarchyIds = $lib->getHierarchyId($streamId);
		$articleList  = $this->articleRepository->getArticleListBasedOnHierarchy($hierarchyIds,$limit,'',$validArticleIds);
		$returnArray['totalArticles'] = $lib->getTotalArticlesBasedOnHierarchy($hierarchyIds,$validArticleIds,$articleModel);
	}else if($entityType=='substream'){
		$hierarchyIds = $lib->getHierarchyId($streamId,$substreamId);
		$articleList = $this->articleRepository->getArticleListBasedOnHierarchy($hierarchyIds,$limit,'',$validArticleIds);
		$returnArray['totalArticles'] = $lib->getTotalArticlesBasedOnCourse($popularCourseId,$validArticleIds,$articleModel);
	}else if($entityType=='specialization'){
		$hierarchyIds = $lib->getHierarchyId($streamId,$substreamId,$specializationId);
		$articleList = $this->articleRepository->getArticleListBasedOnHierarchy($hierarchyIds,$limit,'',$validArticleIds);
		$returnArray['totalArticles'] = $lib->getTotalArticlesBasedOnCourse($popularCourseId,$validArticleIds,$articleModel);
	}else if($entityType=='popularCourse'){
        	$articleList   = $this->articleRepository->getArticleListBasedOnPopularCourse($popularCourseId,$limit,'',$validArticleIds);
        	$returnArray['totalArticles'] = $lib->getTotalArticlesBasedOnHierarchy($hierarchyIds,$validArticleIds,$articleModel);
        }
    else if($entityType=='institute' || $entityType=='university' || $entityType=='college' || $entityType=='exam'){
    	$articleList   = $this->articleRepository->getArticlesBasedOnEntity($popularCourseId,$entityType, $limit,'',$validArticleIds);
        	$returnArray['totalArticles'] = $lib->getTotalArticlesBasedOnHierarchy($hierarchyIds,$validArticleIds,$articleModel);
    }
    else{
        	$articleList = $this->articleRepository->getAllArticleList($limit);
        	$returnArray['totalArticles'] = $lib->getTotalArticles($articleModel);
        }
        foreach($articleList as $articleId=>$info){
        	$returnArray['articleDetail'][$articleId]['title'] = $info->getTitle();
        	$returnArray['articleDetail'][$articleId]['url']   = SHIKSHA_HOME.$info->getUrl();
        }
        return $returnArray;
    }

    function parseArticlePageInternalCss($blogObj) {
    	$descriptionObj = $blogObj->getDescription();
    	$blogType = $blogObj->getBlogLayout();
    	$articleInternalCss = '';
    	foreach ($descriptionObj as $key => $description) {
    		switch ($blogType) {
    			case 'qna':
	    			$articleInternalCss .= $description->getQuestionCss();
	    			$articleInternalCss .= $description->getAnswerCss();
    				break;
    			
    			default:
	    			$articleInternalCss .= $description->getDescriptionCss();
    				break;
    		}
    	}
    	return $articleInternalCss;
    }

    function parseImageData($blogObj){
    	$descriptionObj = $blogObj->getDescription();
    	$imageData = array();
    	foreach ($descriptionObj as $key => $description) {
    		$data = $description->getImageData();
    		if(!empty($data)){
    			$imageData = array_merge($imageData,json_decode($description->getImageData(),true));
    		}
    	}
    	return $imageData;
    }

   function getFooterCustomizedLinks(){
      $articleModel    = $this->CI->load->model('article/articlenewmodel');
      $articleCache = $this->CI->load->library('article/cache/articleCache');
      $footerLinks = $articleCache->getFooterLinksCache();

      if(empty($footerLinks)){
          $footerLinks = $articleModel->getFooterLinks();
              if(!empty($footerLinks) && count($footerLinks)>0)
              {
                      $articleCache->storeFooterLinksCache($footerLinks);
              }
      }
    
      return $footerLinks;
   }

   	function getHierarchyBasedRecentArticles($payload, $limit = 5) {
   		$recentArticles = array();
   		$data = array();
   		if(!empty($payload['streamId']) && $payload['streamId'] > 0) {
			$payload['limit']    = $limit;
   			$this->CI->load->config('chp/chpAPIs');
			$recentArticlesURL   = $this->CI->config->item('GET_RECENT_ARTICLES');
			$this->curlClient    = $this->CI->load->library('chp/ChpClient');
			$queryParam          = $this->curlClient->build_http_query($payload);
			$recentArticlesURL   = $recentArticlesURL.'?'.$queryParam;
			$result              = $this->curlClient->makeCURLCall('GET', $recentArticlesURL);
			$result              = json_decode($result,true);
			$chpInterLinking     = $result['data']['relatedCHP'];		
			$result              = $result['data']['recentArticles'];
	        $articlesIdsArray    = array();
	        foreach ($result['articleDetails'] as $key => $articleDetail) {
				$articlesIdsArray[]                = $articleDetail['blogId'];
				$recentArticles[$key]['blogTitle'] = $articleDetail['blogTitle'];
				$recentArticles[$key]['blogId']    = $articleDetail['blogId'];
				$recentArticles[$key]['url']       = $articleDetail['url'];
	        }

	        if(isset($articlesIdsArray) && is_array($articlesIdsArray) && count($articlesIdsArray) > 1){
		        $qnamodel = $this->CI->load->model("messageBoard/qnamodel");
				$blogData = $qnamodel->getCommentAndViewCount($articlesIdsArray);
				foreach ($recentArticles as $key => $value) {
					$recentArticles[$key]['viewCount']    = $blogData[$value['blogId']]['blogView'];
					$recentArticles[$key]['commentCount'] = $blogData[$value['blogId']]['msgCount'];
				}
			}

   		}
   		$data['recentArticles'] = $recentArticles;
   		$data['chpInterLinking'] = $chpInterLinking;
   		return $data;
	}
}
?>
