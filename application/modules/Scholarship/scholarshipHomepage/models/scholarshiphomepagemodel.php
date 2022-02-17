<?php
class scholarshiphomepagemodel extends MY_Model {
    private $dbHandle = '';
   
    function __construct() {
        parent::__construct('ShikshaApply');
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){ 
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }		
    }

    public function getScholarshipsByCountry($scholarshipCategoryPageCountries){
        $scholorshipsCategory = array();
        $scholorshipsCategory = $this->categorizeScholarshipsByType();
        $externalScholarshipsByCountry = array();
        $internalScholarshipsByCountry = array();
        $externalScholarshipsByCountry = $this->getExternalScholarshipsByCountry(array_unique($scholorshipsCategory['external']),$scholarshipCategoryPageCountries);
        $internalScholarshipsByCountry = $this->getInternalScholarshipsByCountry(array_unique($scholorshipsCategory['internal']),$scholarshipCategoryPageCountries);
        return array_merge($externalScholarshipsByCountry,$internalScholarshipsByCountry);
    }

    private function categorizeScholarshipsByType(){
        $scholorshipsCategory = array();
        $this->initiateModel('read');
        $this->dbHandle->select('scholarshipId,category');
        $this->dbHandle->from('scholarshipBaseTable');
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $scholorshipsCategory[$value['category']][] = $value['scholarshipId'];
        }
        return $scholorshipsCategory;
    }

    private function getInternalScholarshipsByCountry($scholarshipIds,$scholarshipCategoryPageCountries){
        $this->initiateModel('read');
        $this->dbHandle->select('country_id as countryId,scholarshipId');
        $this->dbHandle->from('scholarshipAttributesMapping sam');
        $this->dbHandle->join('abroadCategoryPageData acpd','acpd.university_id=sam.attributeValue','inner');
        $this->dbHandle->where('attributeType','university');
        $this->dbHandle->where('acpd.status','live');
        $this->dbHandle->where('sam.status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $this->dbHandle->where_in('country_id',$scholarshipCategoryPageCountries);
        $this->dbHandle->group_by('scholarshipId');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    private function getExternalScholarshipsByCountry($scholarshipIds,$scholarshipCategoryPageCountries){
        $this->initiateModel('read');
        $this->dbHandle->select('countryId,scholarshipId');
        $this->dbHandle->from('scholarshipApplicableCountries');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('countryId',$scholarshipCategoryPageCountries);
        $this->dbHandle->where_in('scholarshipId',$scholarshipIds);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getScholarshipsAmountAndAwards($scholarshipId, $exchangeRate){
        $scholarshipDetails = array();
        $result = array();
        $this->initiateModel('read');
        $this->dbHandle->select('scholarshipId,totalAmountPayout,amountCurrency');
        $this->dbHandle->from('scholarshipBaseTable');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipId);
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $scholarshipDetails[$value['scholarshipId']]['totalAmountPayout'] = 0;
            if($value['totalAmountPayout']>0){
                if($value['amountCurrency']==1){
                    $scholarshipDetails[$value['scholarshipId']]['totalAmountPayout'] =  $value['totalAmountPayout'];
                }else{
                    $scholarshipDetails[$value['scholarshipId']]['totalAmountPayout'] =  $value['totalAmountPayout']*$exchangeRate[$value['amountCurrency']][1]['factor'];
                }
            }
        }

        $result = array();
        $this->dbHandle->select('scholarshipId,numAwards');
        $this->dbHandle->from('scholarshipDeadlineBaseTable');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('scholarshipId',$scholarshipId);
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            if($value['numAwards']>0){
                $scholarshipDetails[$value['scholarshipId']]['totalAmount'] = $value['numAwards'] * $scholarshipDetails[$value['scholarshipId']]['totalAmountPayout'];
            }
            else {
                $scholarshipDetails[$value['scholarshipId']]['totalAmount'] = 1 * $scholarshipDetails[$value['scholarshipId']]['totalAmountPayout'];
            }
            unset($scholarshipDetails[$value['scholarshipId']]['totalAmountPayout']);
        }
        return $scholarshipDetails;
    }
    /*
     * function that finds content_ids that have "scholarship & loans" tag
     */
    public function getContentIdsWithScholarshipTags()
    {
        $this->initiateModel('read');
        $this->dbHandle->select('content_id');
        $this->dbHandle->from('sa_content_tags_mapping');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('tag_id',4); // scholarship & loans (refer sa_content_tags)
        $result = $this->dbHandle->get()->result_array();
        return array_map(function($a){ return $a['content_id']; }, $result);
    }
    /*
     * function to get popular content by ids
     * @param :
     * $sortBy - popularity for popular, recency for recent
     * $contentIds - array of contentIds that are tagged
     */
    public function getSortedScholarshipContent($sortBy, $contentIds = array(), $contentCount = 5)
    {
        $this->initiateModel('read');
        $this->dbHandle->select('content_id, type, title as strip_title, content_url as contentURL, view_count as viewCount, comment_count as commentCount, download_link,is_downloadable');
        $this->dbHandle->from('sa_content');
        $this->dbHandle->where('status','live');
        $type = array('article','guide');
        $this->dbHandle->where_in('type' ,$type);
        if(count($contentIds)>0)
        {
            $this->dbHandle->where_in('content_id' ,$contentIds);
        }
        if($sortBy == "popularity")
        {
            $this->dbHandle->select('summary, content_image_url as contentImageURL');
            $this->dbHandle->order_by('popularity_count','desc');
        }else{ // recency
            $this->dbHandle->order_by('created_on','desc');
        }
        $this->dbHandle->limit($contentCount);
        $result = $this->dbHandle->get()->result_array();
        foreach($result as $key=>$row){
            $result[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$result[$key]['contentURL'];
            $result[$key]['strip_title'] = html_entity_decode(strip_tags($result[$key]['strip_title']),ENT_NOQUOTES, 'UTF-8');
            if($sortBy == "popularity")
            {
                $result[$key]['contentImageURL'] = MEDIAHOSTURL.$result[$key]['contentImageURL'];
            }
            //$result[$key]['download_link'] = MEDIAHOSTURL.$result[$key]['download_link'];
        }
        return $result;
    }
}
?>