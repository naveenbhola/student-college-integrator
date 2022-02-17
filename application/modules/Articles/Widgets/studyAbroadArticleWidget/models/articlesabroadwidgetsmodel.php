<?php

class ArticlesAbroadWidgetsModel extends MY_Model
{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
	    parent::__construct();
    }
    
    private function initiateModel($mode = "write"){
	    if($this->dbHandle && $this->dbHandleMode == 'write')
		return;

	    $this->dbHandleMode = $mode;
	    $this->dbHandle = NULL;
	    if($mode == 'read') {
		    $this->dbHandle = $this->getReadHandle();
	    } else {
		    $this->dbHandle = $this->getWriteHandle();
	    }
    }

    // Different functions for the Article widget on Study Abroad course listing page.
    /*
    * Author : Virender Singh
    * Purpose : To get Articles based on LDB Course and Country.
    */
    public function getArticlesByDesiredCourseAndCountry($desiredCourseId, $countryId, $articlesCount,$includeGuides = false)
    {
	$this->initiateModel('read');
	$res = array();
	if($desiredCourseId!='' && $desiredCourseId>0)
	{
	    if($includeGuides){
		$inCondition = "'article','guide'";
	    }else{
		$inCondition = "'article'";
	    }
	    $sql = "SELECT 
                sac.id,
                sac.content_id,
                sac.type,
                sac.exam_id as exam_type,
                sac.title,
                sac.title as strip_title,
                sac.summary,
                sac.seo_title,
                sac.seo_description,
                sac.seo_keywords,
                sac.is_downloadable,
                sac.download_link,
                sac.content_image_url as contentImageURL,
                sac.content_url as contentURL,
                sac.view_count as viewCount,
                sac.comment_count as commentCount,
                sac.created_on as created,
                sac.updated_on as last_modified,
                sac.status,
                sac.created_by,
                sac.updated_by as last_modified_by,
                sac.related_date as relatedDate,
                sac.published_on as contentUpdatedAt,
                sac.popularity_count as popularityCount,
                sac.is_homepage,
                sac.apply_content_type_id 

		FROM sa_content as sac
		left join sa_content_attribute_mapping as sacrs on (sac.content_id=sacrs.content_id and sacrs.attribute_mapping = 'ldbcourse')
		left join sa_content_attribute_mapping as sacnt on (sac.content_id=sacnt.content_id and sacnt.attribute_mapping = 'country')
		WHERE sac.status = 'live'
		AND sac.type in ($inCondition)
		and sacrs.attribute_id=?
		and sacnt.attribute_id=?
		and sacnt.status='live'
		and sacrs.status='live'
		group by sac.content_id order by sac.view_count desc limit ?";
	    $query = $this->dbHandle->query($sql,array($desiredCourseId,$countryId,$articlesCount));
	    foreach ($query->result_array() as $row) {
            $row['strip_title'] =html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');
		    $res[] = $row;
	    }
	}
	return $res;
    }
    /*
    * Author : Virender Singh
    * Purpose : To get Articles based on Course Level, Parent category, Subcategory and Country.
    */
    public function getArticlesByCourseLevelCategorySubCategoryAndCountry($courseLevel, $catId, $subCatId, $countryId, $articlesCount, $idArr=array(),$includeGuides = false)
    {
	$this->initiateModel('read');
	$res = array();
        
        $queryParams = array();
        
	if($includeGuides){
	    $inCondition = array('article', 'guide');
	}else{
	    $inCondition = array('article');
	}
        
        $queryParams[] = $inCondition;
        
	$sql = "SELECT sac.id,
                sac.content_id,
                sac.type,
                sac.exam_id as exam_type,
                sac.title,
                sac.title as strip_title,
                sac.summary,
                sac.seo_title,
                sac.seo_description,
                sac.seo_keywords,
                sac.is_downloadable,
                sac.download_link,
                sac.content_image_url as contentImageURL,
                sac.content_url as contentURL,
                sac.view_count as viewCount,
                sac.comment_count as commentCount,
                sac.created_on as created,
                sac.updated_on as last_modified,
                sac.status,
                sac.created_by,
                sac.updated_by as last_modified_by,
                sac.related_date as relatedDate,
                sac.published_on as contentUpdatedAt,
                sac.popularity_count as popularityCount,
                sac.is_homepage,
                sac.apply_content_type_id 
    
	    FROM sa_content as sac
	    left join sa_content_course_mapping as sacrs on sac.content_id=sacrs.content_id
	    left join sa_content_attribute_mapping as sacnt on (sac.content_id=sacnt.content_id and attribute_mapping = 'country')
	    WHERE sac.status = 'live'
	    AND sac.type in (?)
	    and sacrs.course_type=?
	    and sacrs.parent_category_id=?
	    and sacrs.subcategory_id= ?
	    and sacnt.attribute_id=?
	    and sacnt.status='live'
	    and sacrs.status='live' ";
        
        $queryParams[] = $courseLevel;
        $queryParams[] = $catId;
        $queryParams[] = $subCatId;
        $queryParams[] = $countryId;

        if(!empty($idArr))
        {
            $sql .= " and sac.content_id NOT IN (?) ";		
            $queryParams[] = $idArr;
        }
        
        $sql .= " group by sac.content_id
        order by sac.view_count desc
        limit ?";

        $queryParams[] = $articlesCount;
        
	$query = $this->dbHandle->query($sql, $queryParams);
                
	foreach ($query->result_array() as $row) {
        $row['strip_title'] = html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');
	    $res[] = $row;
	}
	return $res;
    }
    /*
    * Author : Virender Singh
    * Purpose : To get Articles based on Course Level, Parent category and Country.
    */
    public function getArticlesByCourseLevelCategoryAndCountry($courseLevel, $catId, $countryId, $articlesCount, $idArr=array(),$includeGuides = false)
    {
	$this->initiateModel('read');
	$res = array();
	$excludeIds = '';

        $queryParams = array();
        
	if($includeGuides){
            $queryParams[] = array('article', 'guide');            
        }else{
            $queryParams[] = array('article');            
	}
        
	$sql = "SELECT sac.id,
                sac.content_id,
                sac.type,
                sac.exam_id as exam_type,
                sac.title,
                sac.title as strip_title,
                sac.summary,
                sac.seo_title,
                sac.seo_description,
                sac.seo_keywords,
                sac.is_downloadable,
                sac.download_link,
                sac.content_image_url as contentImageURL,
                sac.content_url as contentURL,
                sac.view_count as viewCount,
                sac.comment_count as commentCount,
                sac.created_on as created,
                sac.updated_on as last_modified,
                sac.status,
                sac.created_by,
                sac.updated_by as last_modified_by,
                sac.related_date as relatedDate,
                sac.published_on as contentUpdatedAt,
                sac.popularity_count as popularityCount,
                sac.is_homepage,
                sac.apply_content_type_id 
    
	    FROM sa_content as sac
	    left join sa_content_course_mapping as sacrs on sac.content_id=sacrs.content_id
	    left join sa_content_attribute_mapping as sacnt on (sac.content_id=sacnt.content_id and attribute_mapping = 'country')
	    WHERE sac.status = 'live'
	    AND sac.type in (?)
	    and sacrs.course_type=?
	    and sacrs.parent_category_id=?
	    and sacnt.attribute_id=?
	    and sacnt.status='live'
	    and sacrs.status='live' ";
    
        
        $queryParams[] = $courseLevel;
        $queryParams[] = $catId;
        $queryParams[] = $countryId;
        
        if(!empty($idArr))
	{
	    $sql .= " and sac.content_id NOT IN (?) ";	
            $queryParams[] = $idArr;
	}
        
        $sql .= " group by sac.content_id
        order by sac.view_count desc
        limit ?";
        
        $queryParams[] = $articlesCount;
	$query = $this->dbHandle->query($sql, $queryParams);
               
	foreach ($query->result_array() as $row) {
        $row['strip_title'] = html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');
	    $res[] = $row;
	}
	return $res;
    }
    /*
    * Author : Virender Singh
    * Purpose : To get Articles based on LDB Course only.
    */
    public function getArticlesByDesiredCourse($desiredCourseId, $articlesCount, $idArr=array(),$includeGuides)
    {
	$this->initiateModel('read');
	$res = array();
	
	if($desiredCourseId!='' && $desiredCourseId>0)
	{
            $queryParams = array();
            
	    if($includeGuides){
                $queryParams[] = array('article', 'guide');
	    }else{
                $queryParams[] = array('article');
	    }
            
	    $sql = "SELECT sac.id,
                sac.content_id,
                sac.type,
                sac.exam_id as exam_type,
                sac.title,
                sac.title as strip_title,
                sac.summary,
                sac.seo_title,
                sac.seo_description,
                sac.seo_keywords,
                sac.is_downloadable,
                sac.download_link,
                sac.content_image_url as contentImageURL,
                sac.content_url as contentURL,
                sac.view_count as viewCount,
                sac.comment_count as commentCount,
                sac.created_on as created,
                sac.updated_on as last_modified,
                sac.status,
                sac.created_by,
                sac.updated_by as last_modified_by,
                sac.related_date as relatedDate,
                sac.published_on as contentUpdatedAt,
                sac.popularity_count as popularityCount,
                sac.is_homepage,
                sac.apply_content_type_id 
                
	    FROM sa_content as sac
	    left join sa_content_attribute_mapping as sacrs on (sac.content_id=sacrs.content_id and attribute_mapping = 'ldbcourse')
	    WHERE sac.status = 'live'
	    AND sac.type in (?)
	    and sacrs.attribute_id=?
	    and sacrs.status='live' ";
            
            $queryParams[] = $desiredCourseId;
                        
            if(!empty($idArr))
            {
                $sql .= " and sac.content_id NOT IN (?) ";
                $queryParams[] = $idArr;
            }
            
	    $sql .= " group by sac.content_id
	    order by sac.view_count desc
	    limit ?";
            
            $queryParams[] = $articlesCount;
            
	    $query = $this->dbHandle->query($sql, $queryParams);
                        
	    foreach ($query->result_array() as $row) {
            $row['strip_title'] = html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');		
            $res[] = $row;
	    }
	}
	return $res;
    }
    /*
    * Author : Virender Singh
    * Purpose : To get Articles based on Course Level, Parent category and Subcategory.
    */
    public function getArticlesByCourseLevelCategoryAndSubCategory($courseLevel, $catId, $subCatId, $articlesCount, $idArr=array(),$includeGuides)
    {
	$this->initiateModel('read');
	$res = array();
        
        $queryParams = array();
	
	if($includeGuides){
            $queryParams[] = array('article', 'guide');
	}else{
            $queryParams[] = array('article');
	}
	$sql = "SELECT sac.id,
            sac.content_id,
            sac.type,
            sac.exam_id as exam_type,
            sac.title,
            sac.title as strip_title,
            sac.summary,
            sac.seo_title,
            sac.seo_description,
            sac.seo_keywords,
            sac.is_downloadable,
            sac.download_link,
            sac.content_image_url as contentImageURL,
            sac.content_url as contentURL,
            sac.view_count as viewCount,
            sac.comment_count as commentCount,
            sac.created_on as created,
            sac.updated_on as last_modified,
            sac.status,
            sac.created_by,
            sac.updated_by as last_modified_by,
            sac.related_date as relatedDate,
            sac.published_on as contentUpdatedAt,
            sac.popularity_count as popularityCount,
            sac.is_homepage,
            sac.apply_content_type_id 
    
	    FROM sa_content as sac
	    left join sa_content_course_mapping as sacrs on sac.content_id=sacrs.content_id
	    WHERE sac.status = 'live'
	    AND sac.type in (?)
	    and sacrs.course_type=?
	    and sacrs.parent_category_id=?
	    and sacrs.subcategory_id= ?
	    and sacrs.status='live' ";

        $queryParams[] = $courseLevel;
        $queryParams[] = $catId;
        $queryParams[] = $subCatId;

        if(!empty($idArr)) {
	    $sql .= " and sac.content_id NOT IN (?) ";
            $queryParams[] = $idArr;
	}

        $sql .= " group by sac.content_id
            order by sac.view_count desc
            limit ? ";
        
        $queryParams[] = $articlesCount;
	$query = $this->dbHandle->query($sql, $queryParams);
        
	foreach ($query->result_array() as $row) {
        $row['strip_title'] = html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');		
	    $res[] = $row;
	}
	return $res;
    }
    /*
    * Author : Virender Singh
    * Purpose : To get Articles based on Course Level and Parent category.
    */
    public function getArticlesByCourseLevelAndCategory($courseLevel, $catId, $articlesCount, $idArr=array(),$includeGuides)
    {
	$this->initiateModel('read');
	$res = array();
        
        $queryParams = array();
        
        if($includeGuides){
            $queryParams[] = array('article', 'guide');
	}else{
            $queryParams[] = array('article');
	}
        
	$sql = "SELECT sac.id,
                sac.content_id,
                sac.type,
                sac.exam_id as exam_type,
                sac.title,
                sac.title as strip_title,
                sac.summary,
                sac.seo_title,
                sac.seo_description,
                sac.seo_keywords,
                sac.is_downloadable,
                sac.download_link,
                sac.content_image_url as contentImageURL,
                sac.content_url as contentURL,
                sac.view_count as viewCount,
                sac.comment_count as commentCount,
                sac.created_on as created,
                sac.updated_on as last_modified,
                sac.status,
                sac.created_by,
                sac.updated_by as last_modified_by,
                sac.related_date as relatedDate,
                sac.published_on as contentUpdatedAt,
                sac.popularity_count as popularityCount,
                sac.is_homepage,
                sac.apply_content_type_id 
    
	    FROM sa_content as sac
	    left join sa_content_course_mapping as sacrs on sac.content_id=sacrs.content_id
	    WHERE sac.status = 'live'
	    AND sac.type in (?)
	    and sacrs.course_type=?
	    and sacrs.parent_category_id=?
	    and sacrs.status='live' ";
        
        $queryParams[] = $courseLevel;
        $queryParams[] = $catId;
        
        if(!empty($idArr)) {
	        $sql .= " and sac.content_id NOT IN (?) ";
            $queryParams[] = $idArr;
	    }
        
        $sql .= " group by sac.content_id
	    order by sac.view_count desc
	    limit ?";
        
        $queryParams[] = $articlesCount;
	$query = $this->dbHandle->query($sql, $queryParams);
        
	foreach ($query->result_array() as $row) {
        $row['strip_title'] = html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');		
	    $res[] = $row;
	}
	return $res;
    }
    /*
    * Author : Virender Singh
    * Purpose : To get Articles based on Country.
    */
    public function getArticlesByCountry($countryId, $articlesCount, $idArr=array(),$includeGuides)
    {
	$this->initiateModel('read');
	$res = array();
        $queryParams = array();
        
	if($includeGuides){
            $queryParams[] = array('article', 'guide');
	}else{
            $queryParams[] = array('article');
	}
	$sql = "SELECT sac.id,
                sac.content_id,
                sac.type,
                sac.exam_id as exam_type,
                sac.title,
                sac.title as strip_title,
                sac.summary,
                sac.seo_title,
                sac.seo_description,
                sac.seo_keywords,
                sac.is_downloadable,
                sac.download_link,
                sac.content_image_url as contentImageURL,
                sac.content_url as contentURL,
                sac.view_count as viewCount,
                sac.comment_count as commentCount,
                sac.created_on as created,
                sac.updated_on as last_modified,
                sac.status,
                sac.created_by,
                sac.updated_by as last_modified_by,
                sac.related_date as relatedDate,
                sac.published_on as contentUpdatedAt,
                sac.popularity_count as popularityCount,
                sac.is_homepage,
                sac.apply_content_type_id 
    
	    FROM sa_content as sac
	    left join sa_content_attribute_mapping as sacnt on (sac.content_id=sacnt.content_id and attribute_mapping ='country')
	    WHERE sac.status = 'live'
	    AND sac.type in (?)
	    and sacnt.attribute_id=?
	    and sacnt.status='live' ";
        
            $queryParams[] = $countryId;
   
        if(!empty($idArr)) {
	    $sql .= " and sac.content_id NOT IN (?) ";
            $queryParams[] = $idArr;
	}
        
        $sql .= " group by sac.content_id
	    order by sac.view_count desc
	    limit ?";
        
        $queryParams[] = $articlesCount;
       
	$query = $this->dbHandle->query($sql, $queryParams);

	foreach ($query->result_array() as $row) {
        $row['strip_title'] = html_entity_decode(strip_tags($row['strip_title']),ENT_NOQUOTES, 'UTF-8');	
	    $res[] = $row;
	}
	return $res;
    }
    
    function getArticleDesiredCourse($blogId)
    {
	$this->initiateModel('read');
	$sql = "SELECT attribute_id as ldb_course_id FROM sa_content_attribute_mapping as sacrs WHERE sacrs.status = 'live' and 
    sacrs.attribute_mapping= 'ldbcourse' and sacrs.content_id= ? limit 1";
	$query = $this->dbHandle->query($sql, array($blogId));
	return $query->result_array();	 
    }
    function getArticleCountry($blogId)
    {
	$this->initiateModel('read');
	$res = array();
	$sql = "SELECT attribute_id as country_id FROM sa_content_attribute_mapping as sacrs WHERE sacrs.status = 'live' and sacrs.content_id= ? and attribute_mapping='country'";
	$query = $this->dbHandle->query($sql, array($blogId));
	foreach ($query->result_array() as $row) {
	    $res[] = $row['country_id'];
	}
	return $res;
    }
    function getArticleCourseMappingData($blogId)
    {
	$this->initiateModel('read');
	$sql = "SELECT course_type, subcategory_id, parent_category_id FROM sa_content_course_mapping as sacrs WHERE sacrs.status = 'live' and sacrs.content_id= ? limit 1";
	$query = $this->dbHandle->query($sql, array($blogId));
	$data = $query->result_array();
	return array($data[0]['course_type'], $data[0]['subcategory_id'], $data[0]['parent_category_id']);
    }
    function getUniversityCountForCountry($countryIds)
    {
		//Get the Number of Universities
        $cacheLibObj = new CacheLib();
        $cacheResults = array();
        $dbResults = array();
        $flagDBCall = $this->checkCacheForUniversityCount($cacheLibObj,$countryIds,$cacheResults);
        if($flagDBCall)
        {
            $this->initiateModel('read');
            $this->dbHandle->select('count(distinct(university_id)) AS universityCount, country_id', NULL, true);
            $this->dbHandle->from('abroadCategoryPageData');
            $this->dbHandle->where('status', 'live');
            $this->dbHandle->where_in('country_id', $countryIds);
            $this->dbHandle->group_by('country_id');
            $univCounts = $this->dbHandle->get()->result_array();
            //echo $this->dbHandle->last_query();die;
            foreach ($univCounts as $key => $value) {
                $dbResults[$value['country_id']] = $value['universityCount'];
            }
            $cacheLibObj->addMembersToHash('universityCount',$dbResults);
        }
        return array_replace($cacheResults,$dbResults);

    }

    function checkCacheForUniversityCount($cacheLibObj,&$countryIds,&$result)
    {
        $dataFromCache = $cacheLibObj->getMembersOfHashByFieldNameWithValue('universityCount',$countryIds);
        $flagDBCall = false;
        if(empty($dataFromCache) || !is_array($dataFromCache))
        {
            $dataFromCache = array();
            $flagDBCall = true;
        }
        else
        {
            foreach ($dataFromCache as $k =>$count)
            {
                if(is_null($count))
                {
                    unset($dataFromCache[$k]);
                    $flagDBCall =true;
                }
                else
                {
                    unset($countryIds[$k]);
                }
            }
        }
        $result = $dataFromCache;
        return $flagDBCall;
    }

    function getCountryNameById($countryId)
    {
		$this->initiateModel('read');
		$sql = "SELECT name FROM countryTable u WHERE u.countryId = ?";
		$query = $this->dbHandle->query($sql, array($countryId));
		$results = $query->row();
		return $results->name;
    }

    function getCountryNameByIds($countryIds)
    {
    	if(empty($countryIds)){
    		return array();
    	}
		$this->initiateModel('read');
		$this->dbHandle->select('name, countryId', NULL, true);
		$this->dbHandle->from('countryTable');
		$this->dbHandle->where_in('countryId', $countryIds);
		$countries = $this->dbHandle->get()->result_array();
		$results = array();
		foreach ($countries as $value) {
			$results[$value['countryId']] = $value['name'];
		}
		return $results;
    }

    public function getUniversityCountryListForCourseCountries(AbroadCategoryPageRequest $categoryPageRequest)
    {
        $queryArray = array();
        if(count($categoryPageRequest->getCountryId()) >0 ){
            if(!in_array(1, $categoryPageRequest->getCountryId())){
                $LocationClause = "AND acp1.country_id IN (?) ";
                $locationParam = $categoryPageRequest->getCountryId();
            }
        }
        $sql = "SELECT country_id,university_id  
					FROM `abroadCategoryPageData` acp1
					where status ='live' ";
        global $courseExpandedLevels;
        if ($categoryPageRequest->isLDBCoursePage()) {
            $sql .= "AND ldb_course_id = ? "
                .$LocationClause;
            $queryArray[] = $categoryPageRequest->getLDBCourseId();
            $queryArray[] = $locationParam;
        }
        else if ($categoryPageRequest->isCategoryCourseLevelPage()) {
            $sql .=" AND category_id = ?";
            $queryArray[] = $categoryPageRequest->getCategoryId();
            if(!empty($courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())]))
            {
                $sql .=  " AND course_level in (?) ";
                $queryArray[] = $courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())];
            }
            else{
                $sql .= " AND course_level = ?";
                $queryArray[] = $categoryPageRequest->getCourseLevel();
            }
            $sql .= $LocationClause;
            $queryArray[] = $locationParam;
        } else if ($categoryPageRequest->isCategorySubCategoryCourseLevelPage()) {
            if(is_array($subcats = $categoryPageRequest->getSubCategoryId())){
                $sql.= " AND sub_category_id IN (?) ";
                $queryArray[] = $subcats;
            }
            else{
                $sql.= " AND sub_category_id = ?";
                $queryArray[] = $categoryPageRequest->getSubCategoryId();
            }
            if(!empty($courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())]))
            {
                $sql .=  " AND course_level in (?) ";
                $queryArray[] = $courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())];
            }
            else{
                $sql .= " AND course_level = ?";
                $queryArray[] = $categoryPageRequest->getCourseLevel();
            }
            $sql .= $LocationClause;
            $queryArray[] = $locationParam;
        }
        else if ($categoryPageRequest->isLDBCourseSubCategoryPage()) {
            if(is_array($subcats = $categoryPageRequest->getSubCategoryId())){
                $sql.= " AND sub_category_id IN (?) ";
                $queryArray[] = $subcats;
            }
            else{
                $sql.= " AND sub_category_id = ?";
                $queryArray[] = $categoryPageRequest->getSubCategoryId();
            }
            $sql .= " AND ldb_course_id = ? "
                .$LocationClause;
            $queryArray[] = $categoryPageRequest->getLDBCourseId();
            $queryArray[] = $locationParam;
        }
        if(!empty($sql)) {
            $sql .= " GROUP BY acp1.university_id,acp1.country_id";
            $data['mainResult'] = $this->db->query($sql,$queryArray)->result_array();
        }
        return $data;
    }
}