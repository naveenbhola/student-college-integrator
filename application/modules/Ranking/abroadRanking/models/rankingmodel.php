<?php
/*
 * Model for study abroad ranking pages
 *
 */

class rankingmodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct(){
        parent::__construct('Ranking');
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
    
    /*
     *
     *  TO DO TASK
     *  Proceed From here
     *  
     */
    
    
    function getRankingData($rankingIds = array()){
        if(empty($rankingIds)){
            return array();
        }
        //get read DB handle
        $this->initiateModel('read');
        
        // query to get ranking page data
        $queryRankingPage = $this->dbHandle->select('ranking_page_id,name,title,type,subcategory_id,ldb_course_id,seo_title,seo_description,seo_keywords,country_id,created,last_modified,last_modified_by,created_by,parentcategory_id')->where(array('status'=>'live'))->where_in('ranking_page_id',$rankingIds)->get('study_abroad_ranking_pages');
        $data = reset($queryRankingPage->result_array());

        // result for ranking pages
        $resultSetRankingPage = $queryRankingPage->result_array('id');
        
        // reset ranking page data :: indexed according to ranking_page_data
        foreach($resultSetRankingPage as $key=>$value){
            $resultSetRankingPage[$value['ranking_page_id']] = $resultSetRankingPage[$key];
            $resultSetRankingPage[$value['ranking_page_id']]['ranking'] = array();
            unset($resultSetRankingPage[$key]);
        }
        
        // query to get rankings
        if($data['type'] == "university"){
            $queryRankings = $this->dbHandle->select('ranking_page_id,listing_id,rank')->where(array('status'=>'live'))->where_in('ranking_page_id',$rankingIds)->get('study_abroad_rankings');
        }
        else
        {
            //$queryRankings = $this->dbHandle->select('study_abroad_rankings.ranking_page_id,study_abroad_rankings.listing_id,study_abroad_rankings.rank,abroadCategoryPageData.sub_category_id')->from('study_abroad_rankings')->join('abroadCategoryPageData','study_abroad_rankings.listing_id = abroadCategoryPageData.course_id','INNER')->where(array('abroadCategoryPageData.status'=>'live','study_abroad_rankings.status'=>'live'))->where_in('study_abroad_rankings.ranking_page_id',$rankingIds)->group_by(array('study_abroad_rankings.id','abroadCategoryPageData.course_id'))->get();
	    $queryRankings = $this->dbHandle->select('study_abroad_rankings.ranking_page_id,study_abroad_rankings.listing_id,study_abroad_rankings.rank,listing_category_table.category_id as sub_category_id')->from('study_abroad_rankings')->join('listing_category_table','study_abroad_rankings.listing_id = listing_category_table.listing_type_id AND listing_type="course"','INNER')->where(array('listing_category_table.status'=>'live','study_abroad_rankings.status'=>'live'))->where_in('study_abroad_rankings.ranking_page_id',$rankingIds)/*->group_by(array('study_abroad_rankings.id','listing_category_table.listing_type_id'))*/->get();
	    /*
             * SELECT `study_abroad_rankings`.`ranking_page_id`, `study_abroad_rankings`.`listing_id`, `study_abroad_rankings`.`rank`, `abroadCategoryPageData`.`sub_category_id` FROM (`study_abroad_rankings`) INNER JOIN `abroadCategoryPageData` ON `study_abroad_rankings`.`listing_id` = `abroadCategoryPageData`.`course_id` WHERE `abroadCategoryPageData`.`status` = 'live' AND `study_abroad_rankings`.`status` = 'live' AND `study_abroad_rankings`.`ranking_page_id` IN ('21') GROUP BY `study_abroad_rankings`.`id`, `abroadCategoryPageData`.`course_id`
             */
        }
        // result for rankings
        $resultSetRankings = $queryRankings->result_array();
        foreach($resultSetRankings as $resultRanking){
           $resultSetRankingPage[$resultRanking['ranking_page_id']]['ranking'][$resultRanking['rank']] = array('listing_id'=>$resultRanking['listing_id'],'sub_category_id'=>$resultRanking['sub_category_id']);
        }
        
        return $resultSetRankingPage;
    }
    
    public function getCurrencyDetailsForRankingPage(){
		$sql = " SELECT cer.conversion_factor,cer.source_currency_id  
				FROM currency_exchange_rates cer 
				INNER JOIN currency c on (cer.destination_currency_id = c.id and c.id = 1 and cer.status = 'live')";
		$result = $this->db->query($sql,array())->result_array();
		return $result;
	}
}

?>