<?php
class contentpagemodel extends MY_Model{
    
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct(){
        parent::__construct('Blog');
    }
    
    public function initiateModel($mode = 'read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return;
        }
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($this->dbHandleMode == 'read'){
            $this->dbHandle = $this->getReadHandle();
        }elseif($this->dbHandleMode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }
    }
    
    public function guideDownloadTracking($params){
        $this->initiateModel('write');
        $data = array(
                    'user_id'        => $params['userId'],
                    'session_id'     => sessionId(),
                    'guide_id'       => $params['contentId'],
                    'page_url'       => $params['pageUrl'],
                    'downloaded_from'=> $params['downloadedFrom'],
                    'tracking_key_id' => $params['trackingPageKeyId'],
                    'visitor_session_id' => $params['visitorSessionid']
                    );
        $this->dbHandle->insert('sa_guide_download_tracking',$data);
        return 1;
    }
    /*
     * function to get top articles/guides based on popularity count(descending order)
     * @params: $type array containing different types of content types to be fetched,
     *          $contentCount i.e. num of records required (default 10)
     */
    public function getPopularContent($type = NULL, $contentCount = 10)
    {
        $this->initiateModel('read');
        $this->dbHandle->select('content_id, type, title as strip_title, summary, content_image_url as contentImageURL, content_url as contentURL,view_count as viewCount, comment_count as commentCount,popularity_count as popularityCount, download_link,is_downloadable',FALSE);
        $this->dbHandle->from('sa_content');
        if (!empty($type)) {
            $this->dbHandle->where_in('type' ,$type);
        }
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('is_downloadable','yes');
        $this->dbHandle->order_by('popularity_count','desc');
        $this->dbHandle->limit($contentCount);
        $result = $this->dbHandle->get()->result_array();
        foreach($result as $key=>$row){
            $result[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$result[$key]['contentURL'];
            $result[$key]['contentImageURL'] = MEDIAHOSTURL.$result[$key]['contentImageURL'];
            $result[$key]['download_link'] = MEDIAHOSTURL.$result[$key]['download_link'];
            $result[$key]['strip_title'] = html_entity_decode(strip_tags($result[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
        }
        return $result;
    }
    /*this is to fetch selective but extra data for mis tracking*/
    public function contentPageDetails($contentId)
    {
        $result = array();
        $this->initiateModel('read');
        $this->dbHandle->select('content_id, attribute_id as country_id',FALSE);
        $this->dbHandle->from('sa_content_attribute_mapping');
        $this->dbHandle->where('content_id',$contentId);
        $this->dbHandle->where('attribute_mapping','country');
        $this->dbHandle->where('status','live');
        $result['countryMappingData'] = $this->dbHandle->get()->result_array();
       
        $this->dbHandle->select('content_id,attribute_id as ldb_course_id',FALSE);
        $this->dbHandle->from('sa_content_attribute_mapping');
        $this->dbHandle->where('content_id',$contentId);
        $this->dbHandle->where('attribute_mapping','ldbcourse');
        $this->dbHandle->where('status','live');
        $result['ldbMappingData'] = $this->dbHandle->get()->result_array();
       
        $this->dbHandle->select('content_id, course_type, parent_category_id , subcategory_id',FALSE);
        $this->dbHandle->from('sa_content_course_mapping');
        $this->dbHandle->where('content_id',$contentId);
        $this->dbHandle->where('status','live');
        $result['courseMappingData'] = $this->dbHandle->get()->result_array();

        return $result;
    }
}   