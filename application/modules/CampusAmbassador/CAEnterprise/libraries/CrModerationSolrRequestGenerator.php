<?php

class CrModerationSolrRequestGenerator{

	private $CI;
    private $request;
	
	function __construct(){
        $this->CI = & get_instance();
    }

    public function generate(CrModerationPageRequest $request){
        $this->request       = $request;
        
        $queryComponents     = array();
        $queryComponents[]   = "q=*:*";
        $queryComponents[]   = "wt=phps";
		
        $streamId              = $request->getCategoryFilter();
        $statusFilter          = $request->getStatusFilter();
        $moderationReason      = $request->getReasonFilterId();
        $sourceFilter          = $request->getSource();
        $instituteId           = $request->getInstituteId();
        $mobile                = $request->getPhoneNumberFilter();
        $postedDateFrom        = $request->getPostedDateFrom();
        $postedDateTo          = $request->getPostedDateTo();
        $moderatedDateFrom     = $request->getModeratedDateFrom();
        $moderatedDateTo       = $request->getModeratedDateTo();
        $reviewMapping         = $request->getReviewMappingType();
        $moderatorList         = $request->getModeratorsList();
        $filterByEmail         = $request->getFilterByEmail();
        $reviewFilterPostfix   = $request->generateReviewsModeratorMap();
        $reviewerUserEmail     = $request->getReviewerUserEmail();
        $showModeratedByFilter = $request->isShowModeratedByFilter();
        $allRejectRev          = $request->getAllRejectRev();


    	if(isset($streamId)){
    		$queryComponents[] = $this->_getStreamQueryComponents($streamId);
    	}

    	if(isset($statusFilter)){
            $statusData        = $this->_getStatusQueryComponents($statusFilter);
            if($statusData){
    		  $queryComponents[] = $statusData;
            }
            unset($statusData);
    	}

        if(isset($statusFilter) && $statusFilter != 'Pending' && $request->checkForSearchCall != 'email'){
            $moderator_list = array($reviewerUserEmail);

            if(count($moderatorList) > 0 && is_array($moderatorList) ){
                $moderator_list = $moderatorList;  
            }

            if( !is_array($moderatorList) && !($moderatorList) && $showModeratedByFilter){
                //$moderatorListData  = $this->_getModeratedByQueryComponents(array());
            } else {
                $moderatorListData  = $this->_getModeratedByQueryComponents($moderator_list);
            }

            if($moderatorListData){
                $queryComponents[] = $moderatorListData;
            }
            unset($moderatorListData);
        }

    	if(isset($statusFilter) && ($statusFilter == 'Later' || $statusFilter == 'Rejected') && isset($moderationReason)){
            $reasonData          = $this->_getReasonQueryComponents($moderationReason);
            if($reasonData){
    		  $queryComponents[] = $reasonData;
            }
            unset($reasonData);
    	}

    	if(isset($sourceFilter)){
            $sourceData          = $this->_getSourceQueryComponents($sourceFilter);
            if($sourceData){
    		  $queryComponents[] = $sourceData;
            }
    	}

    	if(isset($instituteId)){
    		$instituteData       = $this->_getInstituteQueryComponents($instituteId);
            if($instituteData){
              $queryComponents[] = $instituteData;
            }
            unset($instituteData);
    	}

    	if(isset($mobile)){
            $mobileData        = $this->_getPhoneQueryComponents($mobile);
            if($mobileData){
              $queryComponents[] = $mobileData;
            }
    		unset($mobileData);
    	}

    	if(isset($postedDateFrom) || isset($postedDateTo)){
            $postedDateData    = $this->_getPostedDateQueryComponents($postedDateFrom, $postedDateTo);
            if($postedDateData){
    		  $queryComponents[] = $postedDateData;
            }
            unset($postedDateData);
    	}

    	if(isset($moderatedDateFrom) || isset($moderatedDateTo)){
            $moderatedDateData    = $this->_getModeratedDateQueryComponents($moderatedDateFrom, $moderatedDateTo);
            if($moderatedDateData){
              $queryComponents[] = $moderatedDateData;
            }
            unset($moderatedDateData);
    	}

        if(isset($reviewMapping)){
            $reviewMappingData = $this->_getReviewMappingQueryComponents($reviewMapping);
            if($reviewMappingData){
                $queryComponents[] = $reviewMappingData;
            }
            unset($reviewMappingData);
        }

        if(!empty($reviewFilterPostfix)){
            $reviewFilterPostfixData  = $this->_getReviewFilterPostfixQueryComponents($reviewFilterPostfix);        
            if($reviewFilterPostfixData){
               $queryComponents[] = $reviewFilterPostfixData;
            }
            unset($reviewFilterPostfixData);
        }

        if(!empty($filterByEmail)){
            $filterByEmailData  = $this->_getFilterByEmailComponents($filterByEmail);        
            if($filterByEmailData){
               $queryComponents[] = $filterByEmailData;
            }
            unset($filterByEmailData);
        }

        $queryComponents[] = 'fq=instituteId:[1%20TO%20*]';
        $queryComponents[] = 'fq=courseId:[1%20TO%20*]';

        $queryComponents[] = "fl=reviewId,firstname,lastname,reviewContent,email,isdCode,mobile,isInstituteMapped,courseId,courseName,instituteId,instituteName,creationDate,modificationDate,reviewStatus,ratingParams,recommendCollegeFlag,reviewQuality,yearOfGraduation,socialProfile,isAnonymous,lastModeratedBy,helpfulCount,notHelpfulCount,userId,reviewSource,moderationReason,averageRating,incentiveFlag,lastModeratorEmail,lastModerateDate,reviewPackType,reviewTitle,qualityScore";

        $sortCriteria = $request->getSortCriteria();

        if(isset($sortCriteria) && !empty($sortCriteria)){
            if($sortCriteria == 'New first'){
                $queryComponents[] = "sort=reviewPackType+desc,reviewId+desc";
            }else if($sortCriteria == 'Old first'){
                $queryComponents[] = "sort=reviewPackType+desc,reviewId+asc";
            }else if($sortCriteria == 'Quality_Score_Desc'){
                $queryComponents[] = "sort=qualityScore+desc,creationDate+asc";
            }else if($sortCriteria == 'Quality_Score_Asc'){
                $queryComponents[] = "sort=qualityScore+asc,creationDate+asc";
            }
        } else {
            $queryComponents[] = "sort=reviewPackType+desc,reviewId+desc";
        }

        $resultOffset = $request->getStart();

        if($allRejectRev == 1){
            $queryComponents[] = "start=0";
        }else{
            if(isset($resultOffset) && !empty($resultOffset)){
            $queryComponents[] = "start=".$resultOffset;
            } else {
                $queryComponents[] = "start=0";
            }    
        }

        $resultCount = $request->getResultCount();

        if($allRejectRev == 1){
            $queryComponents[] = "rows=1000";   //for all reject reviews
        }else{
            if(isset($resultCount) && !empty($resultCount)){
                $queryComponents[] = "rows=".$resultCount;
            } else {
                $queryComponents[] = "rows=10";
            }    
        }
        
        
        //facets
        $queryComponents[] = 'facet=true';
        $queryComponents[] = 'facet.limit=-1';
        $queryComponents[] = 'facet.field=utmSource';
        $queryComponents[] = 'facet.pivot={!ex=reviewStatusTag}instituteId,reviewStatus';    
               

        $solrRequest = SOLR_CR_SELECT_URL_BASE."?".implode('&',$queryComponents);        
        return $solrRequest;
    }

    private function _getStreamQueryComponents($streamId){
    	$components = "";
    	if($streamId == 'All'){
			$components = "fq=streamId:*";
		} else {
			$streamId = (int) $streamId;
    		$components = "fq=streamId:".$streamId;
		}
		return $components;
    }

    private function _getStatusQueryComponents($statusFilter){   
    	$components = "";
    	switch ($statusFilter) {
    		case 'All':
    			$components = "(".implode('%20', array('accepted','rejected','published','later','draft','unverified')).")";
    			break;

    		case 'Pending':
    			$components = "draft";
    			break;

    		case 'Unpublished':
    			$components = "accepted";
    			break;

    		case 'Later':
    		case 'Rejected':
    		case 'Published':
    		case 'Unverified':
    			$components = strtolower($statusFilter);
    			break;    		
    		default:
    			$components = "draft";
    			break;
    	}
        if(!empty($components)){
            $components = "fq={!tag=reviewStatusTag}reviewStatus:".$components;   
        }

    	return $components;
    }

    private function _getReasonQueryComponents($reasonId){        
    	$components = "";
    	if($reasonId != 'All'){
			$components = "fq=moderationReason:".$reasonId;
		}
		return $components;
    }

    private function _getSourceQueryComponents($sourceFilter){
    	$components = "";
    	if($sourceFilter != 'All'){
			$components = "fq=utmSource:".$sourceFilter;
		}
		return $components;
    }

    private function _getInstituteQueryComponents($instituteId){
    	$components = "";
    	if($instituteId != ''){
    		$components = "fq=instituteId:".$instituteId;
    	}
    	return $components;
    }

    private function _getPhoneQueryComponents($mobile){
    	$components = "";
    	if($mobile != ''){
    		$components = "fq=mobile:".$mobile;
    	}
    	return $components;
    }

    private function _getPostedDateQueryComponents($postedDateFrom = '', $postedDateTo = ''){
    	$components = "";
        if($postedDateFrom != ''){
            $postedDateFrom = str_replace('/', '-', $postedDateFrom);
    		$formattedFromDate = date("Y-m-d\T00:00:00\Z",strtotime($postedDateFrom));
    	} else {
    		$formattedFromDate = "*";
    	}
    	if($postedDateTo != ''){
            $postedDateTo = str_replace('/', '-', $postedDateTo);
    		$formattedToDate = date("Y-m-d\T23:59:59\Z",strtotime($postedDateTo));
    	} else {
    		$formattedToDate = "*";
    	}
    	$components = "fq=creationDate:[".$formattedFromDate."%20TO%20".$formattedToDate."]";
    	return $components;
    }

    private function _getModeratedDateQueryComponents($moderatedDateFrom = '', $moderatedDateTo = ''){
    	$components = "";
    	if($moderatedDateFrom != ''){
            $moderatedDateFrom = str_replace('/', '-', $moderatedDateFrom);
    		$formattedFromDate = date("Y-m-d\T00:00:00\Z",strtotime($moderatedDateFrom));
    	}
    	if($moderatedDateTo != ''){
            $moderatedDateTo = str_replace('/', '-', $moderatedDateTo);
    		$formattedToDate = date("Y-m-d\T23:59:59\Z",strtotime($moderatedDateTo));
    	} 
        
        if($moderatedDateFrom !=  ''  && $moderatedDateTo != ''){
    	   $components = "fq=lastModerateDate:[".$formattedFromDate."%20TO%20".$formattedToDate."]";
        }

        if($moderatedDateFrom !='' && $moderatedDateTo == ''){
           $components = "fq=lastModerateDate:[".$formattedFromDate."%20TO%20*]";
        }

        if($moderatedDateFrom =='' && $moderatedDateTo != ''){
           $components = "fq=lastModerateDate:[*%20TO%20".$formattedToDate."]";
        }

    	return $components;
    }

    private function _getReviewMappingQueryComponents($type){
        $components = "";
        if($type == 'Mapped-colleges'){
            $components = "fq=isInstituteMapped:true";
        } else if($type == 'UnMapped-colleges'){
            $components = "fq=isInstituteMapped:false";
        }
        return $components;
    }

    private function _getModeratedByQueryComponents($moderatorList = array()){ // array of email ids
        $components = "";
        if(!empty($moderatorList)){
            $components = "fq=lastModeratorEmail:(".implode('%20', $moderatorList).")";
        } else {
            $components = "fq=lastModeratorEmail:(*)";
        }
        return $components;
    }

    private function _getReviewFilterPostfixQueryComponents($reviewFilterPostfix){
        $components = "";
        if(!empty($reviewFilterPostfix)){
                $components = "fq=reviewIdRightDigits:(".implode('%20OR%20', $reviewFilterPostfix).")";                            
        }
        return $components;
    }

    private function _getFilterByEmailComponents($email){
        $components = "";
        //valdaite email
        $pattern = "/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/";

        $emailValidationStatus =  preg_match($pattern, $email) ? true : false;

        if(!empty($email) && $emailValidationStatus){
            $components = "fq=email:".$email;
        }else{
            $components = "fq=mobile:".$email;
        }
        
        return $components;
    }
}