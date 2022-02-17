<?php
class applycontentmodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('ShikshaApply');
    }
    
    private function initiateModel($mode = "read"){
		if($this->dbHandle && $this->dbHandleMode == 'write'){
		    return;
		}
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }
	
	public function getContentData($type,$id){
		$this->initiateModel('read');
		$this->dbHandle->select('sac.content_id as id');
		$this->dbHandle->select('sac.type as type');
		$this->dbHandle->select('sac.title as title');
		$this->dbHandle->select('sac.summary as summary');
		$this->dbHandle->select('sac.seo_title as seo_title');
		$this->dbHandle->select('sac.seo_description as seo_description');
		$this->dbHandle->select('sac.seo_keywords as seo_keywords');
		$this->dbHandle->select('sac.is_downloadable as is_downloadable');
		$this->dbHandle->select('sac.download_link as download_link');
		$this->dbHandle->select('sac.content_image_url as contentImageURL');
		$this->dbHandle->select('sac.content_url as contentURL');
		$this->dbHandle->select('sac.seo_title as seoTitle');
		$this->dbHandle->select('sac.seo_description as seoDescription');
		$this->dbHandle->select('sac.seo_keywords as seoKeywords');
		$this->dbHandle->select('sac.view_count as viewCount');
		$this->dbHandle->select('sac.comment_count as commentCount');
		$this->dbHandle->select('sac.created_on as created');
		$this->dbHandle->select('sac.updated_on as last_modified');
		$this->dbHandle->select('sac.status as status');
		$this->dbHandle->select('sac.created_by as created_by');
		$this->dbHandle->select('sac.updated_by as last_modified_by');
		$this->dbHandle->select('sac.related_date as relatedDate');
		$this->dbHandle->select('sac.published_on as contentUpdatedAt');
		$this->dbHandle->select('sac.popularity_count as popularityCount');
		$this->dbHandle->select('sac.is_homepage as is_homepage');
		
		$this->dbHandle->from('sa_content sac');
		$this->dbHandle->where('sac.apply_content_type_id',$type);
		$this->dbHandle->where('sac.content_id',$id);
		$this->dbHandle->where('sac.status','live');
		$data = $this->dbHandle->get()->result_array();
		
		// get sections
		$this->dbHandle->select('content_id, details');
		$this->dbHandle->from('sa_content_sections');
		$this->dbHandle->where('content_id', $id);
		$this->dbHandle->where('status', 'live');
		$data2 = $this->dbHandle->get()->result_array();
		
		//course mapping
		$this->dbHandle->select('parent_category_id as category_id, subcategory_id as subcategory_id');
		$this->dbHandle->from('sa_content_course_mapping');
		$this->dbHandle->where('content_id', $id);
		$this->dbHandle->where('status', 'live');
		$data3 = $this->dbHandle->get()->result_array();
		
		// attribute mapping
		$this->dbHandle->select('attribute_mapping, attribute_id');
		$this->dbHandle->from('sa_content_attribute_mapping');
		$this->dbHandle->where('content_id', $id);
		$this->dbHandle->where('status', 'live');
		$data4 = $this->dbHandle->get()->result_array();

		foreach($data as $key=>$row){
			$data[$key]['download_link'] = MEDIAHOSTURL.$data[$key]['download_link'];
			$data[$key]['contentImageURL'] = MEDIAHOSTURL.$data[$key]['contentImageURL'];
			$data[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$data[$key]['contentURL'];
			$data[$key]['strip_title'] = html_entity_decode(strip_tags($data[$key]['title']),ENT_NOQUOTES, 'UTF-8');
			$data[$key]['details'] = $data2[0]['details'];
			if($data[$key]['type'] == "applyContent"){
				$data[$key]['description2'] = $data2[1]['details'];
			}
			$data[$key]['category_id'] = $data3[0]['category_id'];
			$data[$key]['subcategory_id'] = $data3[0]['subcategory_id'];
			foreach($data4 as $row)
			{
				switch($row['attribute_mapping'])
				{
					case "country":
						$data[$key]['country_id'] = $row['attribute_id'];break;
					case "ldbcourse":
						$data[$key]['ldb_course_id'] = $row['attribute_id'];break;
				}
			}
		}
		return reset($data);
	}
	

	public function getApplyContentHomePageUrl($dataArray = array())
	{
		$this->initiateModel('read');
		$this->dbHandle->select('sac.content_id as contentId');
		$this->dbHandle->select('sac.content_url as contentURL');
		$this->dbHandle->select('sac.apply_content_type_id as content_type_id');
		$this->dbHandle->from('sa_content sac');
		$this->dbHandle->where('sac.type','applyContent');
		$this->dbHandle->where_in('sac.apply_content_type_id',$dataArray);
		$this->dbHandle->where('sac.status','live');
		$this->dbHandle->where('sac.is_homepage',1);
		$data = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		//_p($data);
		//die;
                foreach($data as $key=>$value){
                    $data[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$data[$key]['contentURL'];
                }
		return $data;
	}
	
	public function getGuideURL($contentId){
		$this->initiateModel('read');
		// so lets first check if this content has downloadable guide
		$this->dbHandle->select('sac.download_link as guideURL');
		$this->dbHandle->select('sac.is_downloadable as flag');
		$this->dbHandle->select('sac.apply_content_type_id as apply_content_type_id');
		$this->dbHandle->from("sa_content sac");
		$this->dbHandle->where('content_id',$contentId);
		$this->dbHandle->where('status','live');
		$res = reset($this->dbHandle->get()->result_array());
		if($res['flag'] == "yes"){
			return MEDIAHOSTURL.$res['guideURL'];
		}
		// didn't find one? no problem use its homepage's content's download link
		$contentTypeId = $res['apply_content_type_id'];
		if(empty($contentTypeId)){ // no luck
			return '';
		}
		
		$this->dbHandle->select('sac.download_link as guideURL');
		$this->dbHandle->from('sa_content sac');
		$this->dbHandle->where('sac.type','applyContent');
		$this->dbHandle->where('sac.status','live');
		$this->dbHandle->where('sac.is_homepage','1');
		$this->dbHandle->where('sac.apply_content_type_id',$contentTypeId);
		$url = MEDIAHOSTURL.reset(reset($this->dbHandle->get()->result_array()));
		
		return $url;
	}
	
	public function trackDownloadGuide($dataArray){
		$this->initiateModel('write');
		$this->dbHandle->insert('applyContentDownloadTracking',$dataArray);
	}

	public function totalGuideDownloded($contentId){
		$this->initiateModel('read');
		$this->dbHandle->select("count(1) as count",false);
		$this->dbHandle->from("applyContentDownloadTracking");
		$this->dbHandle->where("contentId",$contentId);
		$res = $this->dbHandle->get()->result_array();
		return $res[0]['count'];
	}
	
	public function getContentDetails($contentId, $downloadLinkFlag = false){
		$this->initiateModel('read');
		$this->dbHandle->select('title as strip_title, content_url as contentURL');
		if($downloadLinkFlag === true){
			$this->dbHandle->select('download_link');
		}
		$this->dbHandle->from('sa_content');
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('content_id',$contentId);
		$res = reset($this->dbHandle->get()->result_array());
		if(empty($res)){
			return array();
		}
		$res['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$res['contentURL'];
		$res['strip_title'] = html_entity_decode(strip_tags($res['strip_title']),ENT_NOQUOTES, 'UTF-8');
		if($downloadLinkFlag === true){
			$res['download_link'] = MEDIAHOSTURL.$res['download_link'];
		}
		return $res;
	}

	public function getPopularArticlesLastNnoOfDays($contentType,$contentTypeId,$contentId,$noOfDays=30,$noOfarticles=4)
	{		$this->initiateModel('read');
		$sql = "
					SELECT 
						sac.popularity_count as popularityCount,
						sac.content_id,
						sac.title as strip_title,
						sac.content_url as contentUrl,
						sac.view_count as viewCount,
						sac.comment_count as commentCount
					FROM 
						sa_content sac
					
					WHERE 
			            sac.content_id != ?
			        AND sac.type = 'applyContent'
			        AND sac.apply_content_type_id = ?
					AND	sac.status = 'live' 			
					ORDER BY sac.popularity_count desc limit ? ";
		
		$query = $this->dbHandle->query($sql, array($contentId, $contentTypeId, $noOfarticles));
		$result = $query->result_array();
                 
		foreach($result as $key=>$row){
			$result[$key]['strip_title'] = html_entity_decode(strip_tags($result[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$result[$key]['contentUrl'] = SHIKSHA_STUDYABROAD_HOME.$result[$key]['contentUrl'];
		}
		return $result;
	}
	
	public function getRecommendedContents($contentType ,$contentTypeId,$contentId,$noOfcontent)
    {
    	$this->initiateModel('read');
		$sql = "(select 
			    s1.primaryEntityId as recommendedIds,
			    s1.cooccurenceScore,
			    sac1.content_id,
			    sac1.content_url as contentUrl,
			    sac1.title as strip_title,
			    sac1.view_count as viewCount,
			    sac1.comment_count as commentCount 
			from
			    studyAbroadCooccurrenceAnalysis s1 ,
			    sa_content sac1
			  
			where
			    sac1.content_id = s1.primaryEntityId  
			    and s1.secondaryEntityId = ? 
				and s1.entityType = 'applyContent' 
				and sac1.type= 'applyContent'
			    and sac1.apply_content_type_id = ?
			    and s1.status = 'live'
			    and sac1.status = 'live'
			)
			union 

			(select 
			    s2.secondaryEntityId as recommendedIds,
				s2.cooccurenceScore,
				sac2.content_id,
			    sac2.content_url as contentUrl,
			    sac2.title as strip_title,
			    sac2.view_count as viewCount,
			    sac2.comment_count as commentCount 
			from
			    studyAbroadCooccurrenceAnalysis s2,
			    sa_content sac2
			where
			    sac2.content_id = s2.secondaryEntityId 
			    and s2.primaryEntityId = ?  
			    and s2.entityType = 'applyContent'
			    and sac2.type = 'applyContent'     
			    and sac2.apply_content_type_id = ? 
			    and s2.status = 'live' 
			    and  sac2.status = 'live')
			 order by cooccurenceScore desc limit ?";
		
		$query = $this->dbHandle->query($sql,
										array(
												$contentId,$contentTypeId,$contentId,$contentTypeId,$noOfcontent));
		$result = $query->result_array();
		foreach($result as $key=>$row){
			$result[$key]['strip_title'] = html_entity_decode(strip_tags($result[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$result[$key]['contentUrl'] = SHIKSHA_STUDYABROAD_HOME.$result[$key]['contentUrl'];
		}
		return $result;
    }	

    public function getRandomArticleData($contentTypeIds,$numOfArticlesOfEachType)
	{
		$this->initiateModel('read');
		$contentIds = $this->getRandomApplyContentIds($contentTypeIds,$numOfArticlesOfEachType);
		$contentDetails =$this->getApplyContentDetails($contentIds);
		return $contentDetails;
	}

	public function getRandomApplyContentIds($contentTypeIds,$numOfArticlesOfEachType)
	{
		$numofIds = count($contentTypeIds);	
                
		$selectionStatement = " SELECT content_id FROM sa_content ";
                $queryParams = array();

		$sql=" ( ".$selectionStatement." WHERE type='applyContent' and apply_content_type_id = ? AND status='live' ORDER BY RAND() LIMIT ? )";

                $queryParams[] = $contentTypeIds[0];
                $queryParams[] = $numOfArticlesOfEachType;
                
		if($numofIds>1)
		{
			for($i=1;$i<$numofIds;$i++)
			{
				$sql .= " UNION (".$selectionStatement." WHERE apply_content_type_id = ? AND status='live' ORDER BY RAND() LIMIT ? )";
                                $queryParams[] = $contentTypeIds[$i];
                                $queryParams[] = $numOfArticlesOfEachType;                               
                        }
		}
		$query = $this->dbHandle->query($sql, $queryParams);
		$result = $query->result_array();
		$data = array();
  		foreach ($result as $value) {
  			$data[]= $value['content_id'];
  		}
		return $data;
	}

	public function getApplyContentDetails($contentIds)
	{	
		$result = array();	

		if(!empty($contentIds))
		{
			$sql = " SELECT content_id, title as strip_title, content_url as contentUrl, content_image_url as contentImageURL
					FROM sa_content 
					WHERE type = 'applyContent' AND content_id in (?) AND status = 'live'";

			$query = $this->dbHandle->query($sql, array($contentIds));
                        $result = $query->result_array();
		}
		foreach($result as $key=>$row){
			$result[$key]['strip_title'] = html_entity_decode(strip_tags($result[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
			$result[$key]['contentUrl'] = SHIKSHA_STUDYABROAD_HOME.$result[$key]['contentUrl'];
			$result[$key]['contentImageURL'] = MEDIAHOSTURL.$result[$key]['contentImageURL'];
		}
		return $result;
	}
	
	
	public function getApplyContentTypeIdById($contentId)
	{
		$this->initiateModel("read");
		$this->dbHandle->select("apply_content_type_id as content_type_id,content_id");
		$this->dbHandle->from("sa_content");
		$this->dbHandle->where("content_id",$contentId);
		$this->dbHandle->where("type",'applyContent');
		$this->dbHandle->where("status","live");
		$res = $this->dbHandle->get()->result_array();
		return $res;
	}

    public function getPopularApplyContentByApplyContentType($applyContentTypeId,$noOfContents)
    {
        $this->initiateModel('read');
        $this->dbHandle->select("sac.content_id,sac.title as strip_title,sac.content_url as contentUrl,sac.view_count as viewCount,sac.comment_count as commentCount,sac.content_image_url as contentImageURL");
        $this->dbHandle->from("sa_content sac");
        $this->dbHandle->where("sac.apply_content_type_id",$applyContentTypeId);
        $this->dbHandle->where("sac.type",'applyContent');
        $this->dbHandle->where("sac.status","live");
        $this->dbHandle->order_by("sac.popularity_count","desc");
        $this->dbHandle->limit($noOfContents);
	$result = $this->dbHandle->get()->result_array();
	foreach($result as $key=>$row){
		$result[$key]['strip_title'] = html_entity_decode(strip_tags($result[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
	}
        return $result;
    }
}
?>
