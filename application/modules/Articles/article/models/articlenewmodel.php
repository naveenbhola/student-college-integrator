<?php 
/**
*  
*/
class articleNewModel extends MY_Model
{
	private $dbHandle = '';
    private $dbHandleMode = '';

    function __construct()
	{ 
		parent::__construct('Blog');
	}

	private function initiateModel($mode = 'write'){
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
		$this->dbHandleMode = $mode;
		//die('dada');
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	public function getArticleData($blogId, $flag=false){
                if(!is_array($blogId) && $blogId != '' && $blogId > 0){
                        $blogId = array($blogId);
                }
                if(is_array($blogId) && count($blogId) > 0){
                        $this->initiateModel('read');
                        $sql = "SELECT bt.*, concat( t.firstName, ' ', t.lastNAme) as name, t.displayname, (select levelName from userpointsystembymodule where userId = t.userId and modulename = 'AnA' limit 1) level from blogTable bt, tuser t where bt.status in ('live') and bt.userId = t.userid and blogId in (".implode(',', $blogId).")";
                        if($flag){
                                $returnArr = array();
                                $result = $this->dbHandle->query($sql)->result_array();
                                foreach($result as $key=>$value){
                                        $returnArr[$value['blogId']] = $value;
                                }
                                return  $returnArr;
                        }else{
                                return $this->dbHandle->query($sql)->result_array();
                        }

            }
            return array();
        }

	public function getUserDataOfBlogUserId($blogUserId){
		if(!empty($blogUserId)){
			$this->initiateModel('read');
			$sql = "SELECT concat( t.firstName, ' ', t.lastNAme) as name, t.displayname, t.userid, (select levelName from userpointsystembymodule where userId = t.userId and modulename = 'AnA' limit 1) level FROM tuser t WHERE t.userid = ?";
	        $data = $this->dbHandle->query($sql, array($blogUserId))->result_array();
	        $finalData['displayname'] = $data[0]['displayname'];
	        $finalData['name']        = $data[0]['name'];
	        $finalData['level']       = $data[0]['level'];
	        $finalData['userId']      = $data[0]['userid'];
	        return $finalData;
		}
		return array();
	}

	public function getArticleDescription($blogId, $flag=false, $ampViewFlag = false){
                if(!empty($blogId)){
                        $this->initiateModel('read');
                        if($ampViewFlag) {
                        	$this->dbHandle->select("blogId, descriptionId, ampDescription as description, ampCss as descriptionCss, descriptionTag, ampImageData as imageData");
                        }
                        else {
                        	$this->dbHandle->select("blogId, descriptionId, description, descriptionTag, ampImageData as imageData ");
                        }
                        $this->dbHandle->where('blogId', $blogId);
                        $this->dbHandle->order_by('blogId, descriptionId');
                        $result = $this->dbHandle->get('blogDescriptions')->result_array();
                        // $sql = "SELECT blogId, descriptionId, description, descriptionTag FROM blogDescriptions WHERE blogId IN (?) ORDER BY blogId, descriptionId";
                        if($flag){
                                $returnArr = array();
                                foreach($result as $key=>$value){
                                        $returnArr[$value['blogId']][] = $value;
                                }
                                return  $returnArr;
                        }else{
                                return $result;
                        }
                }
                return array();
        }

        public function getBlogQnA($blogId , $flag=false, $ampViewFlag = false){
                if(!empty($blogId)){
                        $this->initiateModel('read');
                        if($ampViewFlag) {
                        	$this->dbHandle->select("blogId, id, ampQuestion as question, ampQuestionCss , ampAnswer as answer, ampAnswerCss, sequence, ampImageData as imageData ");
                        }
                        else {
                        	$this->dbHandle->select("blogId, id, question , answer, sequence, ampImageData as imageData ");
                        }

                        $this->dbHandle->where('blogId', $blogId);
                        $this->dbHandle->where_in('status', array('live', 'draft'));
                        $this->dbHandle->order_by('sequence');
                        $result = $this->dbHandle->get('blogQnA')->result_array();


                        $sql = 'SELECT * FROM blogQnA WHERE blogId IN (?) and status in ("live","draft") ORDER BY sequence';
                        if($flag){
                                // $result = $this->dbHandle->query($sql, array($blogId))->result_array();
                                $returnArr = array();
                                foreach($result as $key=>$value){
                                        $returnArr[$value['blogId']][] = $value;
                                }
                                return  $returnArr;
                        }else{
                                return $result;
                        }
                }
                return array();
        }

	public function getBlogSlideShow($blogId, $flag=false){
                if(!empty($blogId)){
                        $this->initiateModel('read');
                        $sql = 'SELECT * FROM blogSlideShow WHERE blogId IN (?) and status in ("live", "draft") ORDER BY sequence';
                        if($flag){
                                $returnArr = array();
                                $result = $this->dbHandle->query($sql, array($blogId))->result_array();
                                foreach($result as $key=>$value){
                                        $returnArr[$value['blogId']][] = $value;
                                }
                                return  $returnArr;
                        }else{
                                return $this->dbHandle->query($sql, array($blogId))->result_array();
                        }
                }
                return array();
        }

	public function getArticleMappingData($blogId){
		if(!empty($blogId)){
			$this->initiateModel('read');
			$sql = 'SELECT id, entityId, entityType, creationDate, modificationDate from articleAttributeMapping where status = "live" and articleId = ?';
	        return $this->dbHandle->query($sql, array($blogId))->result_array();
		}
		return array();
	}

	function getTotalArticlesBasedOnHierarchy($hierarchyIds,$articleIds){
	        if(empty($hierarchyIds)){
        	        return;
	        }
        	$this->initiateModel('read');
	        if(!empty($articleIds)){
        	        $articleCondition = " and articleId in ($articleIds) ";
	        }
		$articleArr = array();
        	$sql = "select distinct(articleId) from articleAttributeMapping where entityType in ('hierarchy','primaryHierarchy') and status='live' and entityId in ($hierarchyIds) $articleCondition";
	        $result = $this->dbHandle->query($sql)->result_array(); 
        	foreach($result as $key=>$article){
               	 $articleArr[] = $article['articleId'];
       	 	}
		if(!empty($articleArr)){
		$query = "SELECT count(distinct(blogId)) as totalArticles FROM blogTable bt WHERE bt.blogType NOT IN ('exam','examstudyabroad') and bt.status='live' and bt.countryId = '2' and bt.blogId in (".implode(',',$articleArr).") and countryId = '2'";
	        	return $this->dbHandle->query($query)->result_array();
		}
		return $articleArr;
	}

	function getArticlesBasedOnHierarchy($hierarchyIds,$limit,$type,$articleIds){
		if(empty($hierarchyIds)){
			return;
		}
		$this->initiateModel('read');
		if(!empty($articleIds)){
			$articleCondition = " and bt.blogId in ($articleIds) ";
		}
		if(empty($limit)){
			$limit = array('pageSize'=>'20','lowerLimit'=>'0');
		}
		$fromCondition = '';
		$whereCondition = '';
		if($type == 'popular'){
			$orderBy = $this->getPopularityCondition();
			$fromCondition = " ,messageTable mt ";
			$whereCondition = " and mt.msgId = bt.discussionTopic ";
		}else{
			$orderBy = "bt.lastModifiedDate desc";
		}

                $articleIdsSelected = $this->getArticlesFromMapping($hierarchyIds,'hierarchy');
                if(empty($articleIdsSelected)){
                        return array();
                }

                $sql = "SELECT distinct(bt.url), bt.discussionTopic, bt.blogTitle, bt.summary, bt.blogType, bt.blogImageURL, bt.blogId FROM blogTable bt $fromCondition WHERE bt.blogType NOT IN ('exam','examstudyabroad') and bt.status='live' and bt.countryId = '2' and bt.blogId IN (?) $articleCondition $whereCondition ORDER BY $orderBy LIMIT ".$limit['lowerLimit']." , ".$limit['pageSize'];
                return $this->dbHandle->query($sql, array($articleIdsSelected))->result_array();
	}

	function getTotalArticlesBasedOnCourse($courseIds,$articleIds){
	        if(empty($courseIds)){
        	        return;
	        }
        	$this->initiateModel('read');
	        if(!empty($articleIds)){
        	        $articleCondition = " and articleId in ($articleIds) ";
	        }
        	$articleArr = array();
	        $sql = "select distinct(articleId) from articleAttributeMapping where entityType in ('course') and status='live' and entityId in ($courseIds) $articleCondition";
        	$result = $this->dbHandle->query($sql)->result_array();
	        foreach($result as $key=>$article){
                	$articleArr[] = $article['articleId'];
        	}
		if(!empty($articleArr)){
	        	$query = "SELECT count(distinct(blogId)) as totalArticles FROM blogTable bt WHERE bt.blogType NOT IN ('exam','examstudyabroad') and bt.status='live' and bt.countryId = '2' and bt.blogId in (".implode(',',$articleArr).") and countryId = '2'";
        		return $this->dbHandle->query($query)->result_array();
		}
		return $articleArr;
	}

	function getArticlesBasedOnCourse($courseIds,$limit,$type,$articleIds){
		if(empty($courseIds)){
			return;
		}
		$this->initiateModel('read');
		if(!empty($articleIds)){
			$articleCondition = " and bt.blogId in ($articleIds) ";
		}
		if(empty($limit)){
			$limit = array('pageSize'=>'20','lowerLimit'=>'0');
		}
		$fromCondition = '';
		$whereCondition = '';
		if($type == 'popular'){
			$orderBy = $this->getPopularityCondition();
			$fromCondition = " ,messageTable mt ";
			$whereCondition = " and mt.msgId = bt.discussionTopic ";
		}else{
			$orderBy = "bt.lastModifiedDate desc";
		}

                $articleIdsSelected = $this->getArticlesFromMapping($courseIds,'course');
                if(empty($articleIdsSelected)){
                        return array();
                }

                $sql = "SELECT distinct(bt.url), bt.discussionTopic, bt.blogTitle, bt.summary, bt.blogType, bt.blogImageURL, bt.blogId FROM blogTable bt $fromCondition WHERE bt.blogType NOT IN ('exam','examstudyabroad') and bt.status='live' and bt.countryId = '2' and bt.blogId IN (?) $articleCondition $whereCondition ORDER BY $orderBy LIMIT ".$limit['lowerLimit']." , ".$limit['pageSize'];
                return $this->dbHandle->query($sql, array($articleIdsSelected))->result_array();
	}

	function getArticlesBasedOnEntity($courseIds,$entityType, $limit,$type,$articleIds){
		if(empty($courseIds)){
			return;
		}
		$this->initiateModel('read');
		if(!empty($articleIds)){
			$articleCondition = " and bt.blogId in ($articleIds) ";
		}
		if(empty($limit)){
			$limit = array('pageSize'=>'20','lowerLimit'=>'0');
		}
		$fromCondition = '';
		$whereCondition = '';
		if($type == 'popular'){
			$orderBy = $this->getPopularityCondition();
			$fromCondition = " ,messageTable mt ";
			$whereCondition = " and mt.msgId = bt.discussionTopic ";
		}else{
			$orderBy = "bt.lastModifiedDate desc";
		}

                $articleIdsSelected = $this->getArticlesFromMapping($courseIds,'entity',$entityType);
                if(empty($articleIdsSelected)){
                        return array();
                }

                $sql = "SELECT distinct(bt.url), bt.discussionTopic, bt.blogTitle, bt.summary, bt.blogType, bt.blogImageURL, bt.blogId FROM blogTable bt $fromCondition WHERE bt.blogType NOT IN ('exam','examstudyabroad') and bt.status='live' and bt.countryId = '2' and bt.blogId IN (?) $articleCondition $whereCondition ORDER BY $orderBy LIMIT ".$limit['lowerLimit']." , ".$limit['pageSize'];
                return $this->dbHandle->query($sql, array($articleIdsSelected))->result_array();
	}


        function getArticlesFromMapping($entityIds,$type,$entityType){
                $this->initiateModel('read');
                if($type == 'hierarchy'){
                        $sql = "SELECT DISTINCT articleId FROM articleAttributeMapping amt USE INDEX (entity_id) WHERE amt.status='live' AND amt.entityType in ('hierarchy','primaryHierarchy') AND amt.entityId in ($entityIds)";
                }
                else if($type == 'course'){
                        $sql = "SELECT DISTINCT articleId FROM articleAttributeMapping amt USE INDEX (entity_id) WHERE amt.status='live' AND amt.entityType in ('course') AND amt.entityId in ($entityIds)";
                }
                else{
                        $sql = "SELECT DISTINCT articleId FROM articleAttributeMapping amt USE INDEX (entity_id) WHERE amt.status='live' AND amt.entityType in ('$entityType') AND amt.entityId in ($entityIds)";
                }
                $blogArray = $this->dbHandle->query($sql, array($entityType))->result_array();
                $finalArray = array();
                foreach ($blogArray as $blog){
                        $finalArray[] = $blog['articleId'];
                }
                return $finalArray;
        }

	function getTotalArticles(){
		$this->initiateModel('read');
		$sql = "SELECT count(distinct(blogId)) as totalArticles FROM blogTable bt WHERE bt.blogType NOT IN ('exam','examstudyabroad') and bt.status='live' and bt.countryId = '2'";
		return $this->dbHandle->query($sql)->result_array();
	}

	function getAllArticles($limit,$type){
		$this->initiateModel('read');
		if(empty($limit)){
			$limit = array('pageSize'=>'20','lowerLimit'=>'0');
		}
		$fromCondition = '';
		$whereCondition = '';
		if($type == 'popular'){
			$orderBy = $this->getPopularityCondition();
			$fromCondition = " ,messageTable mt ";
			$whereCondition = " and mt.msgId = bt.discussionTopic ";
		}else{
			$orderBy = "bt.lastModifiedDate desc";
		}
		$sql = "SELECT distinct(bt.url), bt.discussionTopic, bt.blogTitle, bt.summary, bt.blogType, bt.blogImageURL, bt.blogId FROM blogTable bt $fromCondition WHERE bt.blogType NOT IN ('exam','examstudyabroad') and bt.status='live' and bt.countryId = '2' $whereCondition ORDER BY $orderBy LIMIT ".$limit['lowerLimit']." , ".$limit['pageSize'];
		return $this->dbHandle->query($sql)->result_array();
	}

	function getCommentCountForArticles($discussionTopicIds){
		if(empty($discussionTopicIds)){
			return;
		}
		$this->initiateModel('read');
		$sql = "SELECT mt.msgCount as commentCount, mt.msgId FROM messageTable mt WHERE mt.status='live' and mt.msgId in ($discussionTopicIds)";
		return $this->dbHandle->query($sql)->result_array();
	}

    function getPopularityCondition(){
    	$popularityCountNum = SHIKSHA_ARTICLE_POPULAR_MAIN_MULTIPLIER."*(".SHIKSHA_ARTICLE_ADDITION_CONSTANT . " + (bt.blogView * ".SHIKSHA_ARTICLE_VIEW_COUNT_WEIGHT
	    						.") +  (mt.msgCount*".SHIKSHA_ARTICLE_COMMENT_COUNT_WEIGHT.") )"; 
	    	$popularityCountDen = " pow("  . SHIKSHA_ARTICLE_DATE_ADD_CONSTANT . "  + DATEDIFF( DATE(NOW()) , DATE(bt.lastModifiedDate) )," . 
	    								SHIKSHA_ARTICLE_EXPONENTIAL .  " ) "; 
	    	$popularityCount = $popularityCountNum  . " / " . $popularityCountDen;
	    	return $popularityCount." DESC";
    }

    function getPopularArticlesForCoursePageWidget($courseHomePageId,$limit=5,$entityType,$entityIds,$filteredArticles){
		if(empty($entityIds) || empty($entityType)){
			return;
		}
		$this->initiateModel('read');
		if(!empty($filteredArticles)){
			$articleCondition = "AND bt.blogId in ($filteredArticles)";
		}
		switch ($entityType) {
			case 'popularCourse':
					$mappingCondition = "AND am.entityType in ('course') AND am.entityId in ($entityIds)";
				break;
			
			default:
				$mappingCondition = "AND am.entityType in ('hierarchy','primaryHierarchy') AND am.entityId in ($entityIds)";
				break;
		}
		$dayValue = -365;
		$dateCheck = date("Y-m-d", strtotime("+".$dayValue." days"));
		$noiseSql = "select contentTypeId from course_pages_content_exceptions where contentType='article' AND exceptionFlag='noise' AND courseHomePageId = ?";
	    $noiseExclude = $this->dbHandle->query($noiseSql,array($courseHomePageId))->result_array();
	    if(!empty($noiseExclude[0])){
	    	foreach ($noiseExclude as $key => $value) {	
	    		$excludedArticles[] = $value['contentTypeId'];
	    	}
	    	$excludeCondition = "AND bt.blogId not in (".implode(',',$excludedArticles).")";
	    }
		if(!empty($courseHomePageId)){
			$sql = "SELECT distinct(bt.blogId), bt.blogView, bt.creationDate, bt.blogTitle, bt.summary, bt.blogImageURL, bt.url FROM coursepage_featured_articles cf, blogTable bt WHERE bt.status ='live' AND bt.blogId = cf.article_id AND cf.from_date <= CURDATE() AND cf.to_date >= CURDATE() AND cf.status = 'live' AND cf.courseHomePageId = ? $excludeCondition";
			$stickyArticle = $this->dbHandle->query($sql,array($courseHomePageId))->result_array();
		}
		if(!empty($stickyArticle[0])){
			$limit = $limit-1;
			$excludedArticles[] = $stickyArticle[0]['blogId'];
			$excludeCondition = "AND bt.blogId not in (".implode(',',$excludedArticles).")";
			$foundResults['sticky'] = $stickyArticle;
		}
		$sql = "SELECT distinct(bt.blogId), bt.blogView, bt.creationDate, bt.blogTitle, bt.summary, bt.blogImageURL, bt.url FROM blogTable bt, articleAttributeMapping am, course_pages_content_exceptions cpe,messageTable mt WHERE bt.discussionTopic = mt.msgId AND mt.status in ('live','closed') AND bt.blogId = cpe.contentTypeId AND bt.blogType NOT IN ('exam','examstudyabroad') AND am.status='live' AND am.articleId=bt.blogId $articleCondition AND bt.status='live' AND bt.countryId = '2' AND bt.blogId = am.articleId $mappingCondition $excludeCondition AND cpe.exceptionFlag = 'boost' AND cpe.contentType ='article' AND bt.popularityView IS NOT NULL AND DATE(bt.creationDate) > $dateCheck ORDER BY cpe.modifiedAt desc, bt.popularityView desc LIMIT $limit";
		$foundResults['boost'] = $this->dbHandle->query($sql)->result_array();
		foreach ($foundResults['boost'] as $key => $value) {
			$excludedArticles[] = $value['blogId'];
		}
		$limit = $limit - count($foundResults['boost']);
		if($limit > 0){
		    if(!empty($excludedArticles)){
		    	$excludeCondition = "AND bt.blogId not in (".implode(',',$excludedArticles).")";
		    }
		    $sql = "SELECT distinct(bt.blogId), bt.blogView, bt.creationDate, bt.blogTitle, bt.summary, bt.blogImageURL, bt.url FROM blogTable bt, articleAttributeMapping am, messageTable mt WHERE bt.discussionTopic = mt.msgId AND mt.status in ('live','closed') AND am.status='live' AND am.articleId=bt.blogId $articleCondition $mappingCondition AND bt.blogType NOT IN ('exam', 'examstudyabroad') $excludeCondition AND bt.popularityView IS NOT NULL AND DATE(bt.creationDate) > $dateCheck ORDER BY bt.popularityView desc limit $limit";
			$queryRes = $this->dbHandle->query($sql)->result_array();
			if(!empty($queryRes[0])){
				$foundResults['result'] = $queryRes;
			}
		}
		return $foundResults;
	}


	public function getArticleAttributeMapping(){
		$entityTypeArray = array('course','hierarchy','primaryHierarchy');
    	$this->initiateModel('read');
    	$this->dbHandle->select('entityId,entityType');
    	$this->dbHandle->from('articleAttributeMapping force index (entityType_status)');
    	$this->dbHandle->where_in('entityType',$entityTypeArray);
    	$this->dbHandle->where('status','live');
    	$this->dbHandle->group_by('entityId,entityType');
    	$result = $this->dbHandle->get()->result_array();
    	return $result;
    }

    function getIdsFromOtherAttributes($entityId,$articleId){
    	$this->initiateModel('read');
    	if(empty($entityId)){
    		return;
    	}
    	if(!empty($articleId)){
    		$whereCondition = "AND articleId in ($articleId)";
    	}
    	$sql = "SELECT distinct(articleId) from articleAttributeMapping where entityType='otherAttribute' AND entityId in ($entityId) AND status='live' $whereCondition";
    	return $this->dbHandle->query($sql)->result_array();
    }

    function getIdsFromCourse($entityId,$articleId){
    	$this->initiateModel('read');
    	if(empty($entityId)){
    		return;
    	}
    	if(!empty($articleId)){
    		$whereCondition = "AND articleId in ($articleId)";
    	}
    	$sql = "SELECT distinct(articleId) from articleAttributeMapping where entityType='course' AND entityId in ($entityId) AND status='live' $whereCondition";
    	return $this->dbHandle->query($sql)->result_array();
    }

    function getMappingDataForQuickLinks($blogIds){
        if(empty($blogIds)){return;}
        $dbHandle = $this->getReadHandle();
        $query  = "SELECT entityType, entityId from articleAttributeMapping where articleId in ($blogIds) and status = 'live' and entityType in ('group','university','college','exam','career')";
        $result = $dbHandle->query($query)->result_array();
       return $result;    
    }

    function deleteArticleListingMapping($listingIds){
    	if(empty($listingIds) || (!is_array($listingIds))|| (!(count($listingIds) > 0))){
   			return false;
   		}

   		$fieldsTobeUpdated = array('status' => 'deleted');
   		$listingType = array('university','group','college');
   		$this->initiateModel('write');
   		$this->dbHandle->where('status','live');
   		$this->dbHandle->where_in('entityId',$listingIds);
   		$this->dbHandle->where_in('entityType',$listingType);
   		
   		$response = $this->dbHandle->update('articleAttributeMapping',$fieldsTobeUpdated);
   		return $response;
    }

    function checkIfArticleMappingExist($listingIds){
    	if(empty($listingIds) || (!is_array($listingIds))|| (!(count($listingIds) > 0))){
   			return false;
   		}

   		$listingType = array('university','group','college');
   		$this->initiateModel('read');
   		$this->dbHandle->select('id');
   		$this->dbHandle->from('articleAttributeMapping');
   		$this->dbHandle->where('status','live');
   		$this->dbHandle->where_in('entityId',$listingIds);
   		$this->dbHandle->where_in('entityType',$listingType);
   		$result = $this->dbHandle->get()->result_array();
   		if(count($result) > 0){
   			return true;
   		}else{
   			return false;
   		}
    }

    function migrateArticleListingMapping($listingIds,$newListingId){
    	if(empty($listingIds) || (!is_array($listingIds))|| (!(count($listingIds) > 0))){
   			return false;
   		}

   		if(empty($newListingId) || !($newListingId > 0)){
   			return false;
   		}

   		$response = true;
   		// mark previous entry as deleted and insert new entry
   		$listingType = array('university','group','college');
   		// 1. select all row
   		$dbHandle = $this->getWriteHandle();
   		$dbHandle->select('*');
   		$dbHandle->from('articleAttributeMapping');
   		$dbHandle->where('status','live');
   		$dbHandle->where_in('entityId',$listingIds);
   		$dbHandle->where_in('entityType',$listingType);
   		$result = $dbHandle->get()->result_array();
   		if($result){
   			// mark status as deleted for these mapping
	   		$fieldsTobeUpdated = array('status' => 'deleted');
	   		$dbHandle->where('status','live');
	   		$dbHandle->where_in('entityId',$listingIds);
	   		$dbHandle->where_in('entityType',$listingType);
	   		$response = $dbHandle->update('articleAttributeMapping',$fieldsTobeUpdated);
	   		if($response){
	   			// insert new rows
		   		foreach ($result as $key => $examAttributeMapping) {
		   			$result[$key]['entityId'] = $newListingId;
		   			$result[$key]['modificationDate'] = date('Y-m-d h:i:s');
		   			unset($result[$key]['id']);
					unset($result[$key]['order']);
		   		}
		   		$response = $dbHandle->insert_batch('articleAttributeMapping', $result);
	   		}
   		}
   		return $response;
    }

	function getArticleHierarchyForRecommendedArticles($blogIds){
    	if(empty($blogIds)){return array();}
    	else {
    		$blogIds = implode(",", $blogIds);
    	}
        $dbHandle = $this->getReadHandle();
        $query  = "SELECT entityId from articleAttributeMapping where articleId in ($blogIds) and status = 'live' and entityType in ('primaryHierarchy')";
        $result = $dbHandle->query($query)->result_array();
       	return $result; 	
    }

    function getCityAndStateMappedToArticle($blogIds){
    	if(empty($blogIds)){return array();}
    	else {
    		$blogIds = implode(",", $blogIds);
    	}
        $dbHandle = $this->getReadHandle();
        $query  = "SELECT entityId, entityType from articleAttributeMapping where articleId in ($blogIds) and status = 'live' and entityType in ('university','college','city','state')";
        $result = $dbHandle->query($query)->result_array();
       	return $result;
    }

    function resultDataUploaderVITEEE($tableName, $data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch($tableName, $data); 
		return $this->dbHandle->insert_id();	
	}

	 /**
    * @param : $applNo : application number 
    * @param : $dateOfBirth : date of birth of an applicant
    * @return : result : returning exam result of an applicant
    */
	function checkCorrectDataForCollegeResult($applNo,$dateOfBirth,$examName){
		if(empty($applNo) && empty($dateOfBirth))
    		return;

		$this->initiateModel('read');

		if(empty($examName))
		{
			$examName = 'VITEEE';
		}

		$sql = "SELECT * FROM College_Exam_Result_Table WHERE application_no = ? AND date_of_birth = ? AND exam = ? AND status='live'";

		$result = $this->dbHandle->query($sql,array($applNo,$dateOfBirth,$examName))->result_array();

		return $result;
	}

	    /**
    * @param : $appNum : applciation number 
    * @param : $dob : date of birth of an applicant
    * @return : result : returning exam result of an applicant
    */
    function getResultInfo($appNum,$dob,$examName)
    {
    	if(empty($appNum) && empty($examName))
    		return;
		$this->initiateModel('read');

		if(empty($examName))
		{
			$examName = 'VITEEE';
		}

		$sql = "SELECT candidate_name,rank,gender FROM College_Exam_Result_Table  WHERE application_no = ? AND date_of_birth= ? AND exam = ? AND status = 'live'";

		$result = $this->dbHandle->query($sql,array($appNum,$dob,$examName))->result_array();
		return $result;
    }

    function getAllLiveArticles() {
    	$this->initiateModel('read');
    	$sql = "select distinct blogId from blogTable where blogLayout != 'slideshow' and status = 'live' and blogType not in ('examstudyabroad');";
    	$data = $this->dbHandle->query($sql)->result_array();
    	return $this->getColumnArray($data, 'blogId');
    }

    function getPopularCourse($courseIds){
        if(count($courseIds)<=0 || empty($courseIds) || empty($courseIds[0])){
           return; 
        }
        $this->initiateModel('read');
        $courseId = $courseIds; 
        if(is_array($courseIds) && count($courseIds)>0){
            $courseId = implode(',',$courseIds);
        }   
        $sql = "SELECT base_course_id, name, alias FROM `base_courses` where base_course_id in (".$courseId.") and is_popular = 1 and status = 'live'";
        return $this->dbHandle->query($sql)->result_array();
    }

    function getArticleInterlinkingHTML($articleType){
        $dbHandle = $this->getReadHandle();
        $sql = "SELECT interlinkingHTML,ampHTML,ampCSS FROM `customInterlinkingHTML` WHERE entityName = 'article' AND entityType = ? AND status IN ('live') LIMIT 1";
        $result = $dbHandle->query($sql, array($articleType))->result_array();
        if(isset($result[0]['interlinkingHTML'])){
            return $result[0];
        }
        else{
            return '';
        }
    }

    function insertArticleInterlinkingHTML($data){
        $dbHandle = $this->getWriteHandle();
        if(isset($data['description']) && isset($data['articleType'])){
            $sql = "SELECT interlinkingHTML FROM `customInterlinkingHTML` WHERE entityName = 'article' AND entityType = ? AND status IN ('live') LIMIT 1";
            $result = $dbHandle->query($sql, array($data['articleType']))->result_array();
            if(isset($result[0]['interlinkingHTML'])){
                $queryCmd = "UPDATE customInterlinkingHTML SET interlinkingHTML = ?, ampCSS = ?, ampHTML = ?, lastModifiedDate = now() WHERE entityName = 'article' AND entityType = ? AND status = 'live'";
                $query = $this->db->query($queryCmd,array($data['description'],$data['ampCSS'],$data['ampHTML'],$data['articleType']));
            }
            else{
                $queryCmd = "INSERT INTO `customInterlinkingHTML` ( `entityName` , `entityType` , `interlinkingHTML`, `ampHTML`, `ampCSS` ) VALUES ( ?, ?, ?, ?, ?)";
                $query = $this->db->query($queryCmd,array('article',$data['articleType'],$data['description'],$data['ampHTML'],$data['ampCSS']));
            }
        }
        else{
            return false;
        }
    }

    function getFooterLinks(){
        $dbHandle = $this->getReadHandle();
        $sql = "SELECT id, name, URL FROM `customFooterLinks` WHERE status = 'live' ORDER BY lastModifiedDate DESC";
        return $dbHandle->query($sql)->result_array();
    }

    function updateFooterLinks($id, $name, $URL){
        $dbHandle = $this->getWriteHandle();
        if(!empty($name) && !empty($URL)){
            if($id > 0){        
                $queryCmd = "UPDATE customFooterLinks SET name = ?, URL = ?, lastModifiedDate = now() WHERE id = ?";
                $query = $this->db->query($queryCmd,array($name, $URL, $id));
            }
            else{
                $queryCmd = "INSERT INTO `customFooterLinks` ( `name` , `URL`, `lastModifiedDate` ) VALUES ( ?, ?, now())";
                $query = $this->db->query($queryCmd,array($name, $URL));
            }
        }
        else{
            return false;
        }
    }

    function deleteFooterLinks($id){
        $dbHandle = $this->getWriteHandle();
        if($id > 0){        
            $queryCmd = "UPDATE customFooterLinks SET status = 'deleted', lastModifiedDate = now() WHERE id = ?";
            $query = $this->db->query($queryCmd,array($id));
        }
        else{
            return false;
        }
    }

    function getArticleUrlById($blogIds = array()) {
        if (empty($blogIds))
            return;
        $dbHandle = $this->getWriteHandle();
        $sql = "SELECT blogId,url FROM blogTable WHERE blogId IN (?) AND status = 'live'";
        $result = $dbHandle->query($sql, array($blogIds))->result_array();
        $rs = array();
        foreach ($result as $key => $value) {
            $rs[$value['blogId']] = $value['url'];
        }
        return $rs;
    }

    function checkArticleExist($blogId){
    	$this->initiateModel('read');

    	$sql = "SELECT blogId from blogTable where blogId = ? AND status = 'live'";
    	$result = $this->dbHandle->query($sql,array($blogId))->result_array();
    	return $result[0]['blogId'];
    }
    function getDiscucssionIdsFromBlogTable(){
    	$this->initiateModel("read");
    	$sql = "SELECT blogId,discussionTopic from blogTable where status = 'live'";
    	$result = $this->dbHandle->query($sql)->result_array();
    	$rs = array();
    	foreach ($result as $key => $value) {
    		$rs[$value['blogId']] = $value['discussionTopic'];
    	}
    	return $rs;
    }
}
