<?php
class ArticleModel extends MY_Model {
    function __construct(){
        parent::__construct('Blog');
    }

    private function getEntityRelations($entity,$operation='read'){
        $tableName = '';
        $entityFieldName = '';
        $relations = array();
        switch($entity) {
            case 'courses': 
                $this->load->library('listingconfig');
                //$dbConfig = array( 'hostname'=>'localhost');
                //$this->listingconfig->getDbConfig_test($appId,$dbConfig);
                $relations['tableName'] = 'blogCoursesMap'; 
                $relations['entityFieldName'] = 'courseId';
                break;
            case 'notification':
            case 'events': 
                $this->load->library('eventcalconfig');
                //$dbConfig = array( 'hostname'=>'localhost');
                //$this->eventcalconfig->getDbConfig($appId,$dbConfig);
                $relations['tableName'] = 'blogEventsMap'; 
                $relations['entityFieldName'] = 'eventId';
                break;
            default: 
                $this->load->library('eventcalconfig');
                //$dbConfig = array( 'hostname'=>'localhost');
                //$this->eventcalconfig->getDbConfig($appId,$dbConfig);
                break;
	}
        //$this->load->database($dbConfig);
        if($operation=='read'){
                $this->db = $this->getReadHandle();
        }
        else{
                $this->db = $this->getWriteHandle();
        }        
        return $relations;
    }



    function getBlogInfo($blogId,$entity,$pageNum,$status='', $fromWhere)
    {
        $op = 'read';
        if($fromWhere == 'cms'){
            $op = 'write';
        }
        $entityRelations = $this->getEntityRelations($entity, $op);
        if(count($entityRelations) < 0) {
            return false;
        }
        $blogDecriptions = $this->getArticleDescriptions($this->db, $blogId, $pageNum);
		$blogQnA = $this->getBlogQnA($this->db, $blogId);
		$blogSlideShow = $this->getBlogSlideShow($this->db, $blogId, $status);
		$queryCmd = 'select bt.*, concat( t.firstName," ",t.lastNAme) as name, t.displayname, (select levelName from userpointsystembymodule where userId = t.userId and modulename = "AnA" limit 1) level from blogTable bt, tuser t where bt.status in (\'live\',\'draft\') and bt.userId=t.userid and blogId=?';
        $query = $this->db->query($queryCmd, array($blogId));
        $msgArray = array();
		foreach ($query->result_array() as $row){
            $row['blogText'] = $blogDecriptions;
			$row['blogQnA'] = $blogQnA;
			$row['blogSlideShow'] = $blogSlideShow;
            $row['ldbCourses'] = '';
			array_push($msgArray,$row);
		}
        $relatedQues = $this->getRelatedProducts($blogId, 'blogs');
        array_push($msgArray, ($relatedQues));
    	$response = ($msgArray);
        return $response;
    }

    function getRelatedProducts($listing_type_id,$listing_type,$relatedprod = 'ask'){
	$appId =1;
        $this->load->library('relatedClient');
        $relatedClientObj = new RelatedClient();
        $relatedQuestions = $relatedClientObj->getrelatedData($appId,$listing_type,$listing_type_id,$relatedprod);
        if(is_array($relatedQuestions) && is_array($relatedQuestions[0]) && is_array($relatedQuestions[0])){
            $data = json_decode($relatedQuestions[0]['relatedData'],true);
            return json_encode($data['resultList']);
        }else{
            return  false;
        }
    }
	
    function getRelatedBlogs($boardId,$startFrom,$count,$countryId,$entity){
		
        $entityRelations = $this->getEntityRelations($entity);
        if(count($entityRelations) < 0) {
            return false;
        }
		if($countryId>1){
			$queryCmd = 'select *, (select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as stream  ,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as parentCategoryId ,  cbt.name  from blogTable bt inner join categoryBoardTable as
    		 cbt on cbt.boardId = bt.boardId  where bt.status=\'live\' and bt.boardId in('.$boardId.') and bt.blogType NOT IN ("exam", "examstudyabroad") and bt.countryId=? order by bt.creationDate desc  LIMIT ' .
				    $startFrom .',' .$count;
			$query = $this->db->query($queryCmd, array($countryId));
		} else {
			$queryCmd = 'select *, (select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as stream  ,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as parentCategoryId ,  cbt.name  from blogTable bt inner join categoryBoardTable as
    		 cbt on cbt.boardId = bt.boardId  where bt.status=\'live\' and bt.boardId in('.$boardId.') and bt.blogType NOT IN ("exam", "examstudyabroad") order by bt.creationDate desc  LIMIT ' .
				    $startFrom .',' .$count;
			$query = $this->db->query($queryCmd);
		}		
	error_log_shiksha($queryCmd, 'blogs');	

		log_message('debug', 'getRelatedBlogs query cmd is ' . $queryCmd);

		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		$response = json_encode($response);
		return $this->xmlrpc->send_response($response);	
	}
	
	function getArticlesWithImageForCriteria($dbHandle, $criteria = array(), $groupBy = 'blogId', $limit = 20) {
        foreach($criteria as $key => $value) {
            $$key = $value;
        }
        $whereClause = '';
        $appender = ' AND ';
        $join = '';
        $resultKey = '';
        $failSafeWhereClause = '';
        $newCriteria = array();
        switch($groupBy) {
            case 'category':
                $resultKey = ' categoryBoardTable.parentId ';
                $failSafeWhereClause .= $appender .'( blogTable.boardId IN (SELECT  boardId from categoryBoardTable WHERE parentId =(SELECT parentId FROM categoryBoardTable WHERE boardId = '. $categoryId .')))';
                $join = ' INNER JOIN categoryBoardTable  on categoryBoardTable.boardId = blogTable.boardId ';
                $newCriteria = array('categoryId' => $categoryId);
                break;
            case 'country':
                $resultKey = ' blogTable.countryId ';
                $newCriteria = array('countryId' => $countryId);
                break;
            case 'exam':
                $resultKey = ' blogTable.blogId ';
                $failSafeWhereClause .= $appender .'blogType="exam" AND status="live"  AND (blogTable.parentId IN (SELECT bt.blogId FROM blogTable bt WHERE bt.blogId ="'. $exam.'" AND blogType="exam" AND status="live" AND parentId > 0))';
                break;
        }
        if(isset($categoryId)) { 
            if($categoryId != ''){
                if($categoryId != 1) {
                    $whereClause .= $appender .'(blogTable.boardId = '.  $categoryId .' OR blogTable.boardId IN (SELECT  boardId from categoryBoardTable WHERE parentId ="'. $categoryId .'"))';
                } else {
                    $whereClause .= $appender .'  blogTable.boardId IN (SELECT boardId from categoryBoardTable)';
                }
                $appender = ' AND ';
            }
        }
        if(isset($countryId)) {
            if($countryId != '' && $countryId !=1){
                $whereClause .= $appender .'blogTable.countryId = '.$countryId;
                $appender = ' AND ';
            }
        }
        if(isset($exam)) {
            if($exam != '') {
                $whereClause .= $appender .'blogType="exam" AND (blogTable.blogId ="'. $exam.'" OR blogId IN (SELECT bt.blogId FROM blogTable bt WHERE bt.parentId="'. $exam.'" AND blogType="exam"))';
            }else {
                $whereClause .= $appender .' parentId="0" AND blogType="exam"';
            }
        }

        $query = 'SELECT  blogTable.*, '.$resultKey .' as resultKey FROM blogTable '. $join .' WHERE blogTable.blogImageURL != "" AND blogTable.status="live" '. $whereClause ;

        error_log_shiksha($query, 'blogs');
		$resultSet = $dbHandle->query($query);
		$response = array();
		foreach($resultSet->result_array() as $result) {
			$response[$result['resultKey']][] = $result;
		}
        if(count($response) < 1) {
            if(count($criteria)  > 1) {
                $response = $this->getArticlesWithImageForCriteria($dbHandle,$newCriteria, $groupBy, $limit);
            } else {
                $query = 'SELECT  blogTable.*, '.$resultKey .' as resultKey FROM blogTable '. $join .' WHERE blogTable.blogImageURL != "" AND blogTable.status="live" '. $failSafeWhereClause .' LIMIT 1';
                error_log_shiksha("ASHISH JAIN: FAILSAFE::". $query, 'blogs');
                $resultSet = $dbHandle->query($query);
                foreach($resultSet->result_array() as $result) {
                    $response[$result['resultKey']][] = $result;
                }
            }
        }
		return $response;
	}
	
	function getTotalArticlesCountForCriteria($dbHandle, $criteria = array()) {
        foreach($criteria as $key => $value) {
            $$key = $value;
        }
        $whereClause = ' WHERE  blogTable.status = "live" ';
        $appender = ' AND ';
        $join = '';
        if(isset($categoryId)) { 
            if($categoryId != ''){
                if($categoryId != 1) {
                    $whereClause .= $appender .'(blogTable.boardId = '.  $categoryId .' OR blogTable.boardId IN (SELECT  boardId from categoryBoardTable WHERE parentId ="'. $categoryId .'"))';
                } else {
                    $whereClause .= $appender .'  blogTable.boardId IN (SELECT boardId from categoryBoardTable)';
                }
                $appender = ' AND ';
            }
            $join = ' INNER JOIN categoryBoardTable  on categoryBoardTable.boardId = blogTable.boardId ';
        }
        if(isset($countryId)) {
            if($countryId != '' && $countryId !=1){
                $whereClause .= $appender .'blogTable.countryId = '.$countryId;
                $appender = ' AND ';
            }
        }
        if(isset($exam)) {
            if($examId != '' && $examId !=0) {
                $whereClause .= $appender .'blogType="exam" AND (blogTable.parentId="'. $examId .'" OR blogId="'. $examId .'")';
            } else {
                $whereClause .= $appender .'blogType="exam" AND (blogTable.parentId=0 OR parentId IN (SELECT bt.blogId FROM blogTable bt WHERE bt.parentId=0) )';
            }
        }
        switch($groupBy) {
            case 'category' : 
                $resultKey = ' categoryBoardTable.parentId ';
                break;
            case 'exam':
                $resultKey = ' blogTable.parentId';
                break;
            case 'country':
                $resultKey = ' blogTable.countryId ';
                break;
        }
		$query = 'SELECT count(*) AS numArticles , '. $resultKey .' as resultKey FROM blogTable '. $join . $whereClause .' GROUP BY resultKey';
        error_log_shiksha("ASHISH::".$query, 'blogs');
		$resultSet = $dbHandle->query($query);
		$response = array();
		foreach($resultSet->result_array() as $result) {
			$response[$result['resultKey']] = $result['numArticles'];
		}
		return $response;
	}

    function getArticlesForCriteria($dbHandle, $criteria, $orderBy , $startOffset, $countOffset) {

    	$whereClause = '';
        $appender = ' AND ';
        
        $typeCase = 1;
        $typeCase = (!empty($criteria['blogType']) && ( $criteria['blogType'] == 'allArticles' || $criteria['blogType'] == 'popular' )) ? 0 : 1;

	$paramArr = array();

        foreach($criteria as $key => $value) {
	    
	    if($value != 'news_Articles' && $value!= 'ALL_NEWS_ARTICLES'){
		
            if($whereClause != '') { if($typeCase)  $whereClause .= ' AND ';}
	    
            if($key == 'boardId') {
               $whereClause .= 'bt.'. $key .' IN (?)';
	       $val = explode(',',$value);
	       array_push($paramArr, $val);
            }else if($key == 'subcatArr'){
		//$value = implode(',',$value);
		$whereClause .= 'bt.boardId IN (?)';
		array_push($paramArr, $value);
	    }else {
            	if($typeCase)  {
                	$whereClause .= 'bt.'. $key .' = ? ';
			array_push($paramArr, $value);
            	}
            }
        }
	}

        if($whereClause !== '') {
            $whereClause = $appender . $whereClause;
        }
        if(isset($criteria['parentId'])) {
            $whereClause .= ' OR bt.blogId = ? ';
	    array_push($paramArr, $criteria['parentId']);
        }
        if(isset($criteria['tag'])) {
            $whereClause .= ' AND bt.tags like "%'. mysql_escape_string($criteria['tag']) .'%"';
        }
	if($criteria['blogType']!='news' && $criteria['blogType']!='allArticles' && $criteria['blogType']!='ALL_NEWS_ARTICLES' && $criteria['blogType']!='popular' && $criteria['blogType'] !='news_Articles') {
            $whereClause .= ' and bt.blogType != "'.'news'.'"';
    }
    
	    if($criteria['blogType'] == 'popular') {
	    	$popularityCountNum = SHIKSHA_ARTICLE_POPULAR_MAIN_MULTIPLIER."*(".SHIKSHA_ARTICLE_ADDITION_CONSTANT . " + (bt.blogView * ".SHIKSHA_ARTICLE_VIEW_COUNT_WEIGHT
	    						.") +  (mt.msgCount*".SHIKSHA_ARTICLE_COMMENT_COUNT_WEIGHT.") )"; 
	    	$popularityCountDen = " pow("  . SHIKSHA_ARTICLE_DATE_ADD_CONSTANT . "  + DATEDIFF( DATE(NOW()) , DATE(bt.lastModifiedDate) )," . 
	    								SHIKSHA_ARTICLE_EXPONENTIAL .  " ) "; 
	    	$popularityCount = $popularityCountNum  . " / " . $popularityCountDen;
	    	$orderBy = $popularityCount." DESC";
		    
	    }
        if($orderBy =='') {
            $orderBy = 'blogView desc';
        }
        //$query = 'SELECT SQL_CALC_FOUND_ROWS bt.*, mt.msgCount FROM blogTable bt INNER join messageTable mt on mt.threadId = bt.discussionTopic  WHERE bt.status="live" '. $whereClause  .'  GROUP BY bt.blogId ORDER BY '. $orderBy .' LIMIT ' . $startOffset .', '. $countOffset;
    
    
    $query = 'SELECT SQL_CALC_FOUND_ROWS bt.*, mt.msgCount , (select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as stream  ,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as parentCategoryId ,  cbt.name FROM blogTable bt INNER join messageTable mt on mt.msgId = bt.discussionTopic inner join categoryBoardTable as
    		 cbt on cbt.boardId = bt.boardId  WHERE bt.status="live" '. $whereClause  .' AND bt.blogType NOT IN ("exam","examstudyabroad") ORDER BY '.
    		 $orderBy .' LIMIT ' . $startOffset .', '. $countOffset;

        $resultSet = $dbHandle->query($query, $paramArr);
		$response = array();
		foreach($resultSet->result_array() as $result) {
			$response['articles'][] = $result;
		}
        $query = 'SELECT FOUND_ROWS() AS totalArticles';
        $resultSet = $dbHandle->query($query);
        $response = array_merge($response, array_pop($resultSet->result_array()));
		return $response;
    }

    function getExamsForProducts($dbHandle) {
        $queryCmd = 'select blogId, blogTitle, acronym,parentId, url from blogTable where ( parentId in (select blogId from blogTable where blogType="exam" and parentId = 0 AND status="live" ) or parentId = 0 ) and blogType="exam" and status="live" order by parentId,blogId';
        $query = $dbHandle->query($queryCmd);
        return $query->result_array();
    }

    function getArticleDescriptions($dbHandle, $blogId, $pageNum = '') {
        if(empty($blogId) || empty($dbHandle)) {
            return false;
        }
        $limit = '';
        if(!empty($pageNum) && is_numeric($pageNum) && $pageNum > -1) {
            $limit = ' LIMIT  '. $pageNum .' ,1';
            
        }
        $queryCmd = 'SELECT descriptionId, description, descriptionTag FROM blogDescriptions WHERE blogId = ? ORDER BY blogId, descriptionId '. $limit;
        $query = $dbHandle->query($queryCmd, array($blogId));
        return json_encode($query->result_array());
    }
	
	
	function getBlogQnA($dbHandle,$blogId){
		 if(empty($blogId) || empty($dbHandle)) {
            return false;
        }
        $queryCmd = 'SELECT * FROM blogQnA WHERE blogId = ? and status in ("live","draft") ORDER BY sequence';
        $query = $dbHandle->query($queryCmd, array($blogId));
        return json_encode($query->result_array());
	}
	
	function getBlogSlideShow($dbHandle,$blogId,$status){
		 if(empty($blogId) || empty($dbHandle)) {
            return false;
        }
	$status = ($status =='') ? 'live' : $status;
	
        $queryCmd = 'SELECT * FROM blogSlideShow WHERE blogId = ? and status= ? ORDER BY sequence';
        $query = $dbHandle->query($queryCmd, array($blogId,$status));
        return json_encode($query->result_array());
	}

    function getLDBCourses($dbHandle,$blogId,$status){
         if(empty($blogId) || empty($dbHandle)) {
            return false;
         }
	 $status = ($status =='') ? 'live' : $status;
	 
         $queryCmd = 'SELECT ldbCourseId FROM blogLDBCourseMapping WHERE blogId = ? and status= ?';
         $query = $dbHandle->query($queryCmd, array($blogId,$status));
         $ldbCourseCSV = '';
         foreach ($query->result_array() as $row){
            $ldbCourseCSV .= ( $ldbCourseCSV == '')?$row['ldbCourseId']:','.$row['ldbCourseId'];
         }
         return json_encode( $ldbCourseCSV );
    }
	
	function addPoll($polls,$blogId,$status='live'){
		$entityRelations = $this->getEntityRelations($entity,"write");
		$queryCmd = 'UPDATE articlePolls set status="history" where articleId=?';
        $query = $this->db->query($queryCmd,array($blogId));
		
		$queryCmd = "INSERT INTO `articlePolls` (
					`articleId` ,
					`pollTitle` ,
					`pollQuestion` ,
					`pollImage`,
					`status`
					)
					VALUES (
					?, ?, ?, ?, ?
					)";
		$query = $this->db->query($queryCmd,array($blogId,$polls['title'],$polls['question'],$polls['image'],$status));
		$pollId = $this->db->insert_id();
		foreach($polls['options'] as $option){
			if($option['value']){
				$queryCmd = "INSERT INTO `shiksha`.`articlePollsOptions` (
							`pollId` ,
							`optionName` ,
							`status`
							)
							VALUES (
							 ?, ?, ?
							)";
				$query = $this->db->query($queryCmd,array($pollId,$option['value'],$status));
			}
		}
	
	}
	
	
	function getPollsData($blogId,$status,$fromWhere){
		if($blogId <= 1){
			$pollJSON['title'] = "";
			$pollJSON['id'] = 0;
			$pollJSON['question'] = "";
			$pollJSON['image'] = "";
			$pollJSON['options'] = array(
				array(
					"value" => ""  
				)
			);
		}
        $op = 'read';
        if($fromWhere == 'cms'){
            $op = 'write';
        }
		$entityRelations = $this->getEntityRelations($entity,$op);
		$queryCmd = "SELECT distinct *,a.pollId as id,o.optionId as op
					FROM `articlePolls` a
					LEFT JOIN articlePollsOptions o ON ( a.pollId = o.pollId )
					LEFT JOIN articlePollsResponse r ON ( o.pollId = r.pollId and o.optionId = r.optionId)
					WHERE a.articleId = ?
					AND a.status = ?";
					
        $query = $this->db->query($queryCmd,array($blogId,$status));

		
		foreach ($query->result_array() as $row){
			
			$pollJSON['title'] = $row['pollTitle'];
			$pollJSON['question'] = $row['pollQuestion'];
			$pollJSON['id'] = $row['id'];
			$pollJSON['image'] = $row['pollImage'];
			$pollJSON['options'][$row["op"]]["value"] = $row["optionName"];
			$pollJSON['options'][$row["op"]]["id"] = $row["op"];
			if(isset($pollJSON['options'][$row["op"]]["response"])){
				if($row["responseId"]){
					$pollJSON['options'][$row["op"]]["response"]++;
				}
			}else{
				if($row["responseId"]){
					$pollJSON['options'][$row["op"]]["response"] = 1;
				}else{
					$pollJSON['options'][$row["op"]]["response"] = 0;
				}
			}
		}
		$options = $pollJSON['options'];
		$pollJSON['options'] = array();
		foreach($options as $option){
			$pollJSON['options'][] = $option;
		}
		return $pollJSON;
	}
	
	function votePoll($pollId,$option){
		$entityRelations = $this->getEntityRelations($entity,"write");
		$queryCmd = "INSERT INTO `articlePollsResponse` (
					`respondentId` ,
					`optionId` ,
					`pollId` 
					)
					VALUES (
					? , ?, ?
					)";
                    $query = $this->db->query($queryCmd,array(sessionId(),$option,$pollId));
	}
	
	function getReleatedSlideShows($blogId,$boardId,$countryId,$excludeBlogId = 0){
		$entityRelations = $this->getEntityRelations($entity,"read");
		return $this->getReletedArticles($blogId,$boardId,$countryId,'blogSlideShow','blogId',4," and bt.blogLayout='slideshow'",$excludeBlogId);
	}
	
	function getReleatedPolls($blogId,$boardId,$countryId,$excludeBlogId = 0){
		$entityRelations = $this->getEntityRelations($entity,"read");
		return $this->getReletedArticles($blogId,$boardId,$countryId,'articlePolls','articleId',2,'' ,$excludeBlogId);
	}
	
	private function getReletedArticles($blogId,$boardId,$countryId,$table,$blogIdLable,$bucket,$layout,$excludeBlogId = 0){
		if(!$blogId){
			return array();
		}
		
		$excludeQuery = '';
		if($excludeBlogId) {
			$excludeQuery = 'and bt.blogId != ' . $excludeBlogId;
		}		
		
		$queryCmd = "SELECT distinct bt.* ,
		(select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as stream,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as parentCategoryId
		FROM `blogTable` bt,$table bs ,categoryBoardTable as cbt 
		where cbt.boardId = bt.boardId and bt.blogId = bs.$blogIdLable and bt.status='live' and bs.status = 'live' and bt.boardId = ? and bt.blogId not in (?) $layout and bt.lastModifiedDate>=DATE_SUB(NOW(), INTERVAL 3 MONTH) " . $excludeQuery . "  order by bt.lastModifiedDate DESC limit 0,".($bucket);
		
		$query = $this->db->query($queryCmd, array($boardId,$blogId));
		$exclusionList = array($blogId);
		foreach($query->result_array() as $article){
			$articles[$article['blogId']] = $article;
			$exclusionList[] = $article['blogId'];
		}
		$bucket -= count($articles);
		if($bucket > 0){
			$_CI = & get_instance();
			$_CI->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			$subCategory = $categoryRepository->find($boardId);
			if($countryId <= 2){
				$subCategories = $categoryRepository->getSubCategories($subCategory->getParentId(),'national');
			}else{
				$subCategories = $categoryRepository->getSubCategories($subCategory->getParentId(),'abroad');
			}
			$subcatArray = array();
			foreach($subCategories as $subCat){
				$subcatArray[] = $subCat->getId();
			}
    
			
			$queryCmd = "SELECT distinct bt.* ,
					(select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId )  as stream
					,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
					as parentCategoryId
					FROM `blogTable` bt,$table bs ,categoryBoardTable as cbt 
					where cbt.boardId = bt.boardId and bt.blogId = bs.$blogIdLable and bt.status='live' and bs.status = 'live'
					and bt.boardId in (?) and bt.blogId not in ('$blogId') $layout
					and bt.lastModifiedDate>=DATE_SUB(NOW(), INTERVAL 3 MONTH)  " . $excludeQuery . " order by bt.lastModifiedDate DESC limit 0,".($bucket);
		
			//error_log("AMIT".$queryCmd);
			$query = $this->db->query($queryCmd,array($subcatArray));
			$initialCount  = count($articles);
			foreach($query->result_array() as $article){
				$articles[$article['blogId']] = $article;
				$exclusionList[] = $article['blogId'];
			}
			$bucket -= count($articles) - $initialCount;
		}
		if($bucket > 0){
				$queryCmd = "SELECT distinct bt.* ,
					(select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId )  as stream
					,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
					as parentCategoryId
					FROM `blogTable` bt,$table bs ,categoryBoardTable as cbt 
					where cbt.boardId = bt.boardId and bt.blogId = bs.$blogIdLable and bt.status='live' and bs.status = 'live'
					and bt.blogId not in (?) $layout
					and bt.lastModifiedDate>=DATE_SUB(NOW(), INTERVAL 3 MONTH)  " . $excludeQuery . " order by bt.lastModifiedDate DESC limit 0,".($bucket);
			//error_log("AMIT".$queryCmd);
			$query = $this->db->query($queryCmd,array($exclusionList));
			foreach($query->result_array() as $article){
				$articles[$article['blogId']] = $article;
			}
		}
		return $articles;
	}
	
	public function getArticlesData($articleIds,$partial = TRUE)
	{
		$dbHandle = $this->getReadHandle();
		
		if($partial) {
			$sql =  "SELECT bt.blogId,bt.boardId as subCategoryId,bt.countryId,bt.blogTitle,bt.mailerTitle,bt.url,bt.blogImageURL,cbt.parentId as categoryId,blm.ldbCourseId ".
					"FROM blogTable bt ".
					"INNER JOIN categoryBoardTable cbt ON cbt.boardId = bt.boardId ".
					"LEFT JOIN blogLDBCourseMapping blm ON (blm.blogId = bt.blogId AND blm.status = 'live') ".
					"WHERE bt.blogId IN (?) AND bt.status = 'live' ".
					"ORDER BY bt.creationDate DESC";
		}
		else {
			$sql =  "SELECT bt.* ".
					"FROM blogTable bt ".
					"WHERE bt.blogId IN (?) AND bt.status = 'live' ";
		}
		$query = $dbHandle->query($sql,array($articleIds));
		return $query->result_array();
	}
	
	public function getLatestArticles()
	{
		$dbHandle = $this->getReadHandle();
		$sql = "SELECT bt.blogId,bt.boardId as subCategoryId,bt.countryId,bt.blogTitle,bt.mailerTitle,bt.url,bt.blogImageURL,cbt.parentId as categoryId ".
				"FROM blogTable bt ".
				"INNER JOIN categoryBoardTable cbt ON cbt.boardId = bt.boardId ".
				"WHERE bt.status = 'live' ".
				"ORDER BY bt.creationDate DESC";
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		
		$sql =  "select count(msgId) as totalComments, threadId,blogId ".
				"from blogTable bt INNER JOIN messageTable mt ON mt.threadId = bt.discussionTopic ".
				"where mt.parentId = mt.threadId AND mt.status in ('live', 'closed') AND bt.status != 'draft' group by mt.threadId";
		$query = $dbHandle->query($sql);
		$commentResults = $query->result_array();
		$blogComments = array();
		foreach($commentResults as $commentResult) {
			$blogComments[$commentResult['blogId']] = $commentResult['totalComments'];
		}
		
		for($i=0;$i<count($results);$i++) {
			$results[$i]['numComments'] = intval($blogComments[$results[$i]['blogId']]);
		}
		
		return $results;
	}
	
	public function getArticlesList($start,$count, $dateToCheckFor) {
	    $dbHandle = $this->getReadHandle();

        $sql = "SELECT A.`blogId` as articleId , A.`creationDate` , A.blogView as viewCount, ".
            " (SELECT COUNT( * ) -1 FROM messageTable WHERE threadId = B.msgId  AND status in ('live', 'closed')) AS commentCount ".
            " FROM `blogTable` A, messageTable B ".
            " WHERE A.discussionTopic = B.msgId AND B.status IN ('live', 'closed') AND A.status = 'live' ".
            " AND A.blogType NOT IN ('exam', 'examstudyabroad') AND DATE(A.creationDate) > '".$dateToCheckFor."'  Limit $start, $count";

        $query = $dbHandle->query($sql);        
	    return $query->result_array();
	}
	
	public function getArticlesCountForSubcategories($subcat_array = array()) {

		if(count($subcat_array) == 0 ) {
			return array();
		}

		$dbHandle = $this->getReadHandle();
		$sql = "SELECT count(A.`blogId`) as articles_count, A.`boardId` as categoryId ".
		        "FROM `blogTable` A, messageTable B ".
		    	"WHERE A.discussionTopic = B.msgId AND A.boardId IN (?) AND A.status != 'draft' ".
		        "AND B.status IN ('live', 'closed') ".
		    	"AND A.blogType NOT IN ('exam', 'examstudyabroad') group by A.`boardId`";
        
		$queryRes = $dbHandle->query($sql,array($subcat_array));
		$response_array = array();
		foreach ($queryRes->result_array() as $row) {
			$response_array[$row['categoryId']] = $row['articles_count'];
		}

		return $response_array;
	}
	
	function updateArticlePopularity($articleId, $popularityCount) {
	    $dbHandle = $this->getWriteHandle('write');
	    $queryCmd="update blogTable set popularityView = ? where blogId = ?";
	    $dbHandle->query($queryCmd, array($popularityCount,$articleId) );
	}
	
	function getArticlesByPopularity($subCatId, $dateToCheckFor) {

		$dbHandle = $this->getReadHandle();

        $sql    = "SELECT subcategory_id, article_id FROM coursepage_featured_articles WHERE from_date <= CURDATE() AND to_date >= CURDATE() AND status = 'live'";
        $result = $dbHandle->query($sql)->result_array();

        $subcat_to_sticky_array_mapping = array();

        foreach ($result as $value) {
            $subcat_to_sticky_array_mapping[$value['subcategory_id']] = $value['article_id'];
        }
        
        $limit = 5;
        $boostedResx = array();
		$sponsoredBlogId = $subcat_to_sticky_array_mapping[$subCatId];
        if($subcat_to_sticky_array_mapping[$subCatId]) {
			$sql = "SELECT A.`blogId` as articleId, A.blogView as totalViews, DATEDIFF(now(), A.creationDate) as dateDiff, A.blogTitle as artcileTitle, A.summary, ".
    		" A.blogImageURL, A.url, A.popularityView, A.discussionTopic ".
    		" FROM `blogTable` A".
    		" WHERE A.blogId = ? AND A.status != 'draft'";
    		$boostedResx = $dbHandle->query($sql, array($subcat_to_sticky_array_mapping[$subCatId]))->result_array();
        }
	
		$excludeSponsoredArticleSQL = "";
        if(!empty($boostedResx)) {
			$limit = 4;
			$excludeSponsoredArticleSQL = " AND A.blogId NOT IN ($sponsoredBlogId) ";
		}
        
		$sql = "SELECT A.`blogId` as articleId, A.blogView as totalViews, DATEDIFF(now(), A.creationDate) as dateDiff, A.blogTitle as artcileTitle, A.summary, ".
		" A.blogImageURL, A.url, A.popularityView, A.discussionTopic ".
		" FROM `blogTable` A, messageTable B, course_pages_content_exceptions C".
		" WHERE A.blogId = C.contentTypeId and ".
		" A.discussionTopic = B.msgId AND A.boardId = ? AND B.status IN ('live', 'closed') ".
		" AND A.blogType NOT IN ('exam', 'examstudyabroad') AND A.popularityView IS NOT NULL AND DATE(A.creationDate) > ? ".
		" $excludeSponsoredArticleSQL and C.subCategoryId = ? and C.exceptionFlag = 'boost' and C.contentType ='article'".
		" ORDER BY C.modifiedAt desc, A.popularityView desc limit ".$limit;
		
		$boostedResy = $dbHandle->query($sql,array($subCatId,$dateToCheckFor,$subCatId))->result_array();

		$boostedRes = $boostedResx + $boostedResy;

		$records_still_needed = 5 - count($boostedRes);
		if($records_still_needed>0){		
		//global $COURSE_PAGES_EXCLUSION_IDS;
		
		    $COURSE_PAGES_EXCLUSION_IDS = array();
		    $sql = "select contentTypeId from course_pages_content_exceptions
			where contentType='article' and exceptionFlag='noise' and subCategoryId=?";
		    $exclusionRes = $dbHandle->query($sql, array($subCatId))->result_array();
		    foreach($exclusionRes as $exclusionId){
				$COURSE_PAGES_EXCLUSION_IDS['ARTICLES'][] = reset($exclusionId);
		    }
			if(!empty($sponsoredBlogId)){
				$COURSE_PAGES_EXCLUSION_IDS['ARTICLES'][] = $sponsoredBlogId;
			}
			
		    $exclusion_query = "";
		    if(count($COURSE_PAGES_EXCLUSION_IDS['ARTICLES']) >0) {
			    $exclusion_query = " AND A.`blogId` NOT IN (".implode(",",$COURSE_PAGES_EXCLUSION_IDS['ARTICLES']).") ";
		    }
		
		    $sql = "SELECT A.`blogId` as articleId, A.blogView as totalViews, DATEDIFF(now(), A.creationDate) as dateDiff, A.blogTitle as artcileTitle, A.summary, ".
			" A.blogImageURL, A.url, A.popularityView, A.discussionTopic ".
			" FROM `blogTable` A, messageTable B ".
			" WHERE A.discussionTopic = B.msgId AND A.boardId = ? AND B.status IN ('live', 'closed') $exclusion_query ".
			" AND A.blogType NOT IN ('exam', 'examstudyabroad') AND A.popularityView IS NOT NULL AND DATE(A.creationDate) > ? ".
			" ORDER BY A.popularityView desc limit ".$records_still_needed;
		
		    $queryRes = $dbHandle->query($sql, array($subCatId,$dateToCheckFor))->result_array();
		    
		    foreach($queryRes as $secondResult){
			$boostedRes[] = $secondResult;
		    }
		}
		return $boostedRes; //$queryRes->result_array();
	}
	
    function getNoOfViewsForArticles($articleIds) {
	$dbHandle = $this->getReadHandle();	
	$sql = "SELECT blogView, blogId FROM blogTable WHERE blogId in (?)";
	$queryRes = $dbHandle->query($sql,array($articleIds));
	foreach($queryRes->result_array() as $key => $row){
		$articleData[$row['blogId']] = $row['blogView'];
	}
	return $articleData;
    }	
    
    public function getArticlesSubcategories($articles_ids = array()) {

    	if(count($articles_ids) == 0 ) {
    		return array();
    	}
    	
        global $COURSE_PAGES_SUB_CAT_ARRAY; 
    	$dbHandle = $this->getReadHandle();
    	$sql = "SELECT A.`blogId` as articleId, A.`boardId` as categoryId ".
		        "FROM `blogTable` A ".
		    	"WHERE A.`blogId` IN (?) ".		        
		    	"AND A.blogType NOT IN ('exam', 'examstudyabroad') ".
    	        "AND A.`boardId` IN (?)";

    	$queryRes = $dbHandle->query($sql,array($articles_ids, array_keys($COURSE_PAGES_SUB_CAT_ARRAY)));
    	$response_array = array();
    	foreach ($queryRes->result_array() as $row) {
    		$response_array[$row['articleId']] = $row['categoryId'];
    	}
        
    	return $response_array;
    }
    
    public function getEngineeringExams($appId, $examName, $startOffset, $countOffset){
	$dbHandle = $this->getReadHandle();
	$orderBy = ' lastModifiedDate desc ';
	$whereClause = " AND ( ( UPPER(bt.blogTitle) like UPPER('%$examName%') ) OR ( UPPER(bt.tags) like UPPER('%$examName%') ) ) ";
	$query = 'SELECT SQL_CALC_FOUND_ROWS bt.*, mt.msgCount , (select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as stream  ,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as parentCategoryId ,  cbt.name FROM blogTable bt INNER join messageTable mt on mt.msgId = bt.discussionTopic inner join categoryBoardTable as
    		 cbt on cbt.boardId = bt.boardId  WHERE bt.status="live" '. $whereClause  .' AND bt.blogType != "exam" ORDER BY '.
    		 $orderBy .' LIMIT ' . $startOffset .', '. $countOffset;

        $resultSet = $dbHandle->query($query);
	$response = array();
	foreach($resultSet->result_array() as $result) {
		$response['articles'][] = $result;
	}
        $query = 'SELECT FOUND_ROWS() AS totalArticles';
        $resultSet = $dbHandle->query($query);
        $response = array_merge($response, array_pop($resultSet->result_array()));
	return $response;	
    }
    
  
    public function getAuthorInfo($userIds){

    	$dbHandle = $this->getReadHandle();
	$query = "SELECT userId,displayname,firstname,lastname,avtarimageurl from tuser where userId IN (?)";
	$userArr = explode(',',$userIds);
	$resultSet = $dbHandle->query($query,array($userArr));
	$response = array();
	foreach($resultSet->result_array() as $result){
		$response[$result['userId']] = $result;
	}

	$query = "select sum(result.count) as tCount , result.uId from  (
			select count(*) as count , userId as uId from blogTable where userId IN (?) and status = 'live' group by uId
					UNION
			select count(*) as count , created_by  as uId from sa_content where created_by IN  (?) and status = '".ENT_SA_PRE_LIVE_STATUS."' group by uId
		) as result group by result.uId order by tCount desc";
		
	$resultSet = $dbHandle->query($query,array($userArr,$userArr)); 
			
	foreach($resultSet->result_array() as $result){
		$resultantArray[$result['uId']] = $result;
		$newResult = $response[$result['uId']];
		$newResult['count'] = $result['tCount'];
		$finalArray[$result['uId']] = $newResult;
	}

	return $finalArray;	

   }

   function updateArticleURLs($limit = 5000){
        $dbHandle = $this->getWriteHandle();
        $queryCmd = "select blogId from blogTable where boardId IN (SELECT boardId FROM categoryBoardTable WHERE parentId =2 AND flag = 'national') AND blogType!='news' AND status != 'draft' ORDER BY blogId DESC LIMIT $limit";
        $queryRes = $dbHandle->query($queryCmd);
        foreach($queryRes->result_array() as $row){
            $blogIdU = $row['blogId'];
            $queryCmdU = "UPDATE blogTable SET url = REPLACE (url, 'http://www', 'http://engineering') WHERE blogId=?";
            $queryResU = $dbHandle->query($queryCmdU,array($blogIdU));
            echo "Updated BlogId = ".$blogIdU."<br/>";
        }
   }
   
   /**
    * return  data array of the blog 
    * @param  string $type type of article
    * @return Array
    */
   function getArticleResults($type){
  
        $dbHandle = $this->getWriteHandle();
        
        //fetching the query string based on blog type
        switch ($type){
        case article:
        $param            = $this->_queryArticle('article');
        break;
        case slideshow:
        $param            = $this->_queryArticle('slideshow');
        break;
        case qna:
        $param            = $this->_queryArticle('qna');
        break;
        case images:
        $param            = $this->_queryArticle('images');
        break;
        case saimages:
        $param            = $this->_queryArticle('saimages');
        break;
        case sasection:
        $param            = $this->_queryArticle('sasection');
        break;
        }
        
        //executing the query
        $queryRes         = $dbHandle->query($param['query']);
        
        $firstArrayResult = $this->firstArrayResults($queryRes,$param);
        $results          = $this->secondArrayResults($queryRes,$param,$firstArrayResult);
        
        return $results;
   }
   
   /**
    * 
    * @param  Array $queryRes Array after executing the query
    * @param  Array $param
    * @return Array
    */
   function firstArrayResults($queryRes,$param){
        foreach($queryRes->result_array()  as $key=>$row){
            if(count($param['arrayFirst']) == 3){
                $arrayDescription[$row[$param['arraySecond'][0]]][]=array($param['arrayFirst'][0]=>$row[$param['arrayFirst'][0]],$param['arrayFirst'][1]=>$row[$param['arrayFirst'][1]],$param['arrayFirst'][2]=>$row[$param['arrayFirst'][2]]);
            }else{
                $arrayDescription[$row[$param['arraySecond'][0]]][]=array($param['arrayFirst'][0]=>$row[$param['arrayFirst'][0]],$param['arrayFirst'][1]=>$row[$param['arrayFirst'][1]]);
            }
         }
        return $arrayDescription;
    }
   
    /**
     * purifying the result array
     * @param  Array $queryRes Array after executing the query
     * @param  Array $param
     * @param  Array $firstArrayResult
     * @return Array
     */
    function secondArrayResults($queryRes,$param,$firstArrayResult){
        foreach($queryRes->result_array()  as $key=>$row){
            if(count($param['arraySecond'])== 5){
                $results[$row[$param['arraySecond'][0]]]= array(
                                            $param['arraySecond'][0]=> $row[$param['arraySecond'][0]],
                                            $param['arraySecond'][1]=> $row[$param['arraySecond'][1]],
                                            $param['arraySecond'][2]=> $row[$param['arraySecond'][2]],
                                            $param['arraySecond'][3]=> $row[$param['arraySecond'][3]],
                                            $param['arraySecond'][4]=>$firstArrayResult[$row[$param['arraySecond'][0]]]
                                           );
            }else{
                $results[$row[$param['arraySecond'][0]]]= array(
                                            $param['arraySecond'][0]=> $row[$param['arraySecond'][0]],
                                            $param['arraySecond'][1]=> $row[$param['arraySecond'][1]],
                                            $param['arraySecond'][2]=> $row[$param['arraySecond'][2]],
                                            $param['arraySecond'][3]=>$firstArrayResult[$row[$param['arraySecond'][0]]]
                                           );
            } 
          }
        return $results;
    }
   

   /**
    * All query based on type
    * @param  string $type
    * @return Array
    */
   function _queryArticle($type){
        $blogPaserDate = BLOG_PARSER_DATE;
        if($type       == 'article'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL,b.description,b.descriptionId  FROM blogTable a JOIN blogDescriptions b on a.blogId = b.blogId WHERE a.status in ('live','draft') and a.creationDate > '$blogPaserDate' ORDER BY a.blogId DESC";
        $arrayFirst    = array('description','descriptionId');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','description');
        }elseif($type  == 'slideshow'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL, b.id,b.image  FROM blogTable a join blogSlideShow b ON a.blogId = b.blogId WHERE a.status in ('live','draft')   AND a.creationDate > '$blogPaserDate' ORDER BY a.blogId DESC";
        $arrayFirst    = array('id','image');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','slideshowImages');   
        }elseif($type  == 'qna'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL,b.id,b.question,b.answer FROM blogTable a join blogQnA b ON a.blogId = b.blogId WHERE a.status in ('live','draft') AND a.creationDate > '$blogPaserDate' ORDER BY a.blogId DESC";
        $arrayFirst    = array('id','question','answer');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','qna');   
        }elseif($type  == 'images'){
        $query         = "SELECT a.blogId,a.blogTitle,a.blogImageURL,b.blogImageId,b.imageURL FROM blogTable a join blogImages b ON a.blogId = b.blogId WHERE a.status in ('live','draft') AND a.creationDate > '$blogPaserDate'";
        $arrayFirst    = array('blogImageId','imageURL');
        $arraySecond   = array('blogId','blogTitle','blogImageURL','images');  
        }elseif($type  == 'saimages'){
        $query         = "SELECT a.id,a.content_id,a.title,a.content_image_url as contentImageURL,b.content_image_id as saContentimageId,b.image_url as imageURL FROM sa_content a join sa_content_images b ON a.content_id= b.content_id WHERE a.status in ('live','draft') AND b.status in ('live','draft') AND a.created_on > '$blogPaserDate'";
        $arrayFirst    = array('saContentimageId','imageURL');
        $arraySecond   = array('content_id','id','title','contentImageURL','images');  
        }elseif($type  == 'sasection'){
        $query         = "SELECT a.id,a.content_id,a.title as strip_title,a.content_image_url as contentImageURL,b.id,b.details FROM sa_content a join sa_content_sections b ON a.content_id= b.content_id WHERE b.status in ('live','draft') AND a.status in ('live','draft') AND a.created_on > '$blogPaserDate'";
        $arrayFirst    = array('id','details');
        $arraySecond   = array('content_id','id','contentImageURL','strip_title','images');  
        }
        
       return array('query'=>$query,'arrayFirst'=>$arrayFirst,'arraySecond'=>$arraySecond);
   }
   

         
   /**
    * batch update query for update the blogs
    * @param string $insertedString
    * @param Array $tableArray
    */
   function setBlogUpdateQuery($insertedString,$tableArray){
        $tableName   = $tableArray['tableName'];
        $fieldName   = $tableArray['fieldName'];
        $fieldName1  = $tableArray['fieldName1'];
        
        
        $dbHandle    = $this->getWriteHandle();
        if(array_key_exists('fieldName2',$tableArray)){
            $fieldName2  = $tableArray['fieldName2'];
            $queryUpdate = "insert into $tableName ($fieldName,$fieldName1,$fieldName2) VALUES $insertedString ON DUPLICATE KEY UPDATE
            $fieldName1  = VALUES($fieldName1),$fieldName2 = VALUES($fieldName2)";  
        }else{
            $queryUpdate = "insert into $tableName ($fieldName,$fieldName1) VALUES $insertedString ON DUPLICATE KEY UPDATE  $fieldName1 = VALUES($fieldName1)";
        }
        
        $queryDone   = $dbHandle->query($queryUpdate);
        return true;
   }


   /**
     * 
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2015-10-22
     * @return array
     */
    public function getHomePageFeaturedArticles() {
        $this->db = $this->getReadHandle();
        $sql = "select * from HOMEPAGERDGN_carousel_widget order by carousel_order asc";
        $rows = $this->db->query($sql)->result_array();
        return $rows;
    }


    public function getHomePageFeatureAndNewsArticle($blogType,$limit){
        $this->db = $this->getReadHandle();
        if($blogType == 'news'){
             $sql = "SELECT".
               " bt.blogTitle,".
               " bt.url,".
               " bt.summary,".
               " bt.blogImageURL,".
               " bt.blogText1,".
               " bt.creationDate ".
               " FROM blogTable bt".
               " WHERE".
               " bt.status='live'".
               " AND bt.blogType = 'news'".
               " AND bt.blogType NOT IN ('exam','examstudyabroad')".
               " ORDER BY creationDate desc".
               " LIMIT 0, $limit";

        }else{
            $sql = "SELECT".
               " bt.blogTitle,".
               " bt.url,".
               " bt.summary,".
               " bt.blogImageURL,".
               " bt.blogText1,".
               " bt.creationDate,".
               " cbt.name".
               " FROM blogTable bt".
               " INNER JOIN categoryBoardTable as cbt". 
               " ON cbt.boardId = bt.boardId".
               " WHERE".
               " bt.status='live'".
               " AND bt.blogType != 'news'".
               " AND bt.blogType NOT IN ('exam','examstudyabroad')".
               " ORDER BY creationDate desc".
               " LIMIT 0, $limit";

        }
        $rows = $this->db->query($sql)->result_array();
        return $rows;   
    }


//NATIONAL Articles with flag of latest update from cms panel order by last modified date. Excludes exams.
    function getRecentArticles($limit=3){
        $this->db = $this->getReadHandle();
        $sql= "select distinct bt.blogId, bt.blogTitle, bt.url from blogTable bt, PageBlogDb pbd where bt.status ='live' and bt.blogId=pbd.BlogId AND bt.blogType NOT IN ('exam','examstudyabroad') and bt.countryId = '2' order by bt.lastModifiedDate desc limit $limit";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

//National Articles order by last modified date.
    function getLatestUpdatedArticles($limit=3){
        $dbHandle = $this->getReadHandle();
        $sql= "select bt.blogTitle, bt.url from blogTable bt where bt.status ='live' AND bt.blogType NOT IN ('exam','examstudyabroad') AND bt.countryId = '2' order by bt.lastModifiedDate desc limit ?";
        $result = $dbHandle->query($sql,$limit)->result_array();
        return $result;
    }


    function getArticleHierarchyData($blogId,$status){
	if(empty($blogId) || !is_numeric($blogId) || $blogId < 0){
            return;
        }
        $dbHandle = $this->getReadHandle();
        $sql = "select id, entityId, entityType from articleAttributeMapping where status = ? and articleId = ? order by id desc";
        $res = $dbHandle->query($sql, array($status,$blogId));
        return $res->result_array();
    }

    // add article mapping
    // if edit article then mark all exist entry deleted and insert
    // @ akhter
    function mapArticleToAttributesAndHierarchy($data, $blogId, $fromMode){
        $process = true;
        if($fromMode =='edit'){
            $res = $this->getExistMapping($blogId);
            if(!empty($res)){
                $process = $this->updateArticleMapping($blogId);
            }
        }
        if(!empty($data) && $process){
            $dbHandle = $this->getWriteHandle();
            $dbHandle->insert_batch('articleAttributeMapping', $data);
        }
    }

    function getExistMapping($blogId){
        $dbHandle = $this->getReadHandle();
        $sql = "SELECT articleId FROM `articleAttributeMapping` where articleId = ? and status in ('live','draft') limit 1";
        $result = $dbHandle->query($sql, array($blogId))->result_array();
        return $result[0]['articleId'];
    }

    function updateArticleMapping($blogId){
        $dbHandle = $this->getWriteHandle();
        $dbHandle->trans_start();
            $dbHandle->set('status', 'deleted');
            $dbHandle->where('articleId', $blogId);
            $dbHandle->update('articleAttributeMapping');
        $dbHandle->trans_complete();
        return ($dbHandle->trans_status() === FALSE) ? false : true;
    }

    function getPopularCourse($courseIds){
        if(count($courseIds)<=0 || empty($courseIds) || empty($courseIds[0])){
           return; 
        }
        $dbHandle = $this->getReadHandle();
        $courseId = $courseIds; 
        if(is_array($courseIds) && count($courseIds)>0){
            $courseId = implode(',',$courseIds);
        }   
        $sql = "SELECT base_course_id, name, alias FROM `base_courses` where base_course_id in (".$courseId.") and is_popular = 1 and status = 'live'";
        return $dbHandle->query($sql)->result_array();
    }

    //update url only one time even if draft/live
    function updateNewArticleUrl($blogId, $url){
        if(empty($blogId)){return;}
        $dbHandle = $this->getWriteHandle();
        $sql = "UPDATE blogTable set url = ? where blogId = ? and blogType != 'examstudyabroad' and status != 'deleted'";
        $dbHandle->query($sql,array($url,$blogId));
    }

    function getBlogStatus($blogId){
        $dbHandle = $this->getReadHandle();
        $query  = 'SELECT status from blogTable where blogId = ? and status != "deleted"';
        $result = $dbHandle->query($query,array($blogId))->result_array();
        return $result[0]['status'];
    }

    // article migration only
    function getTotalArticle(){
        $dbHandle = $this->getReadHandle();

        // first time execution query
        /*$sql = "select blogId from blogTable where status != 'deleted' and blogType not in ('examstudyabroad')"; */

        // second time execution query for empty url only
        $sql = "select blogId from blogTable where status != 'deleted' and blogType not in ('examstudyabroad') and (url is NULL or url ='') and blogId in (5014,4640,4604,2959,5637,3174,3202,4200,5707)";
        $res = $dbHandle->query($sql)->result_array();
        return $res;
    }
    // article migration only
    function getArticleOldMapping($blogIdArr){
        $dbHandle = $this->getReadHandle();
        if(empty($blogIdArr)){
            return;
        }
        // first time execution query
        $sql = "select distinct(blc.ldbCourseId), blg.blogId, blg.boardId, blg.blogTitle, blg.creationDate, 
blg.lastModifiedDate, blg.status from blogTable blg inner Join blogLDBCourseMapping blc 
on blg.blogId = blc.blogId where blg.status != 'deleted' and blc.status != 'deleted' and blg.blogType not in ('examstudyabroad') and blg.blogId in (?)";

        // second time execution query for empty url only
        /*$sql = "select distinct(blc.ldbCourseId), blg.blogId, blg.boardId, blg.blogTitle, blg.creationDate, 
blg.lastModifiedDate, blg.status from blogTable blg left Join blogLDBCourseMapping blc 
on blg.blogId = blc.blogId where blg.status != 'deleted' and blg.blogType not in ('examstudyabroad') and blg.blogId in ($blogIdStr) and blc.ldbCourseId is NULL and (blg.url is NULL or blg.url = '')";*/
        return $dbHandle->query($sql,array($blogIdArr))->result_array();
    }

    // article migration only
    function getNewMappingFromOldMapping($param){
        $dbHandle = $this->getReadHandle();
        $sql = "select stream_id, substream_id, specialization_id, base_course_id, education_type, delivery_method from base_entity_mapping where oldcategory_id = ? and oldsubcategory_id = ? and oldspecializationid = ? order by id desc limit 1 ";
        return $dbHandle->query($sql,array($param['categoryId'],$param['subCategoryId'],$param['ldbCourseId']))->result_array();   
    }
    // article migration only
    function getArticleHavingTag(){
        $dbHandle = $this->getReadHandle();
        $sql = "select blg.blogId from blogTable blg where tags like '%test prep%' and blg.status in ('live','draft')";
        return $dbHandle->query($sql)->result_array();   
    }
    // article migration only
    function updateArticleType($blogId){
        $dbHandle = $this->getReadHandle();
        $sql = "UPDATE blogTable set blogType = 'testPrep' where blogId in (?)";
        $dbHandle->query($sql,array($blogId));
        return $dbHandle->affected_rows();
    }

    function updateArticleContentURL($articleId = 0){

        ini_set('memory_limit','8000M');
        ini_set("max_execution_time",-1);

        $dbHandle = $this->getWriteHandle();

        // Step 1- get all article descriptions having relative URLS
        if($articleId > 0){
            $blogCheck = " AND blogId = $articleId ";
        }
        $queryCmd = 'Select descriptionId, blogId, description from blogDescriptions where description like \'%href="../../../%\' '.$blogCheck;
        $query = $dbHandle->query($queryCmd);

    	foreach ($query->result_array() as $article) {
            $content = $article['description'];
            $descId = $article['descriptionId'];
            $blogId = $article['blogId'];
            //Step 2 -parse url from content and resolve with Absolute URLs
            $updatedContent = $this->replaceRelativeWithAbsolute($content);
            
            //Step 3 -Update content 
            $queryCmd = "UPDATE blogDescriptions set description = ? where descriptionId = ?";
            $query = $dbHandle->query($queryCmd, array($updatedContent,$descId));
            echo "Content for $blogId has been successfully updated.<br>";
    	}
        

    }

    function replaceRelativeWithAbsolute($content){
        //First find all instances of ../../../../ in the content and replace it with http://www.shiksha.com
        $content = str_replace('href="../../../../','href="http://www.shiksha.com/',$content);
        //Next, find all instances of ../../../ in the content and replace it with http://www.shiksha.com
        $content = str_replace('href="../../../','href="http://www.shiksha.com/',$content);
        //Now, return the content
        return $content;
    }

    // add article board mapping
    // if edit article then mark all exist entry deleted and insert
    // @ akhter
    function articleBoardMapping($data, $blogId, $fromMode){
        $process = true;
        if($fromMode =='edit'){
            $res = $this->getExistBoardMapping($blogId);
            if(!empty($res)){
                $process = $this->updateArticleBoardMapping($blogId);
            }
        }
        if((!empty($data['boardName']) || !empty($data['class'])) && $process){
            $dbHandle = $this->getWriteHandle();
            $dbHandle->insert('blogBoardMapping', $data);
        }
    }

    function updateArticleBoardMapping($blogId){
        if(!empty($blogId)){
            $dbHandle = $this->getWriteHandle();
            $dbHandle->trans_start();
            $dbHandle->set('status', 'deleted');
            $dbHandle->where('blogId', $blogId);
            $dbHandle->update('blogBoardMapping');
            $dbHandle->trans_complete();
            return ($dbHandle->trans_status() === FALSE) ? false : true;
        }
    }

    function getExistBoardMapping($blogId){
        $dbHandle = $this->getReadHandle();
        $sql = "SELECT blogId FROM `blogBoardMapping` where blogId = ? and status in ('live','draft') limit 1";
        $result = $dbHandle->query($sql, array($blogId))->result_array();
        return $result[0]['blogId'];
    }

    function getBoardMappingData($blogId, $fromWhere){
        if($fromWhere == 'cms'){
            $dbHandle = $this->getWriteHandle();
        }else{
            $dbHandle = $this->getReadHandle();
        }
        $sql = "SELECT boardName, class FROM `blogBoardMapping` where blogId = ? and status in ('live','draft') limit 1";
        $result = $dbHandle->query($sql, array($blogId))->result_array();
        return $result[0];   
    }

    function getBlogIdByUrl($url){
        $dbHandle = $this->getReadHandle();
        $sql = "SELECT blogId FROM `blogTable` where url = ? and status in ('live','draft') limit 1";
        $result = $dbHandle->query($sql, array($url))->result_array();
        return $result[0]['blogId'];
    }

    function updateArticleWikiContent($blogId, $wiki){
        if(empty($blogId)){return;}
        $dbHandle = $this->getWriteHandle();
        $insertArr = array();
        foreach ($wiki as $key => $value) {
            if(empty($value['descriptionId'])) {
                $insertArr[] = array('blogId' => $blogId, 'description' => $value['description']);
                unset($wiki[$key]);
            }
        }
        if(!empty($insertArr)) {
            $dbHandle->insert_batch('blogDescriptions',$insertArr);
        }

        if(!empty($wiki)) {
            $dbHandle->where('blogId',$blogId);
            $dbHandle->update_batch('blogDescriptions', $wiki, 'descriptionId');
        }
        // $sql = "UPDATE blogDescriptions set description = ? where blogId = ?";
        // $dbHandle->query($sql,array($content,$blogId));
        return true;
    }

}
