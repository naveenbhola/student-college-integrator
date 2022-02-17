<?php
class HamburgerLib {

    	function __construct()
    	{
    		$this->CI = & get_instance();
    	}		

    	// get all stream ,substream and specialization (spec will be directly mapped to steam only)
    	function getTabsContentByStream(){
                return $this->nonZeroTabContent();
    	}

        //Desc - get all base courses which having >0 listings, using for hamburger Find college by course section
        //Param - streamIds in array
        //return - type array, Example - array([streamId]=>array('baseCourseId'=>courseName))
        //@uthor - akhter
        function getBaseCourseByStream($resetData=false){

                $this->CI->load->library('Common/cacheLib');
                $this->cacheLib = new cacheLib();
                $cacheHtmlKey = '_hmbaseCoursebyStream_json';

                if($this->cacheLib->get($cacheHtmlKey) == 'ERROR_READING_CACHE' || $resetData){
                    
                    error_log('Mobile Hamburger :: update baseCourse');
                    
                    $this->CI->load->library('search/Solr/AutoSuggestorSolrClient');
                    $autoSuggestorSolrClient = new AutoSuggestorSolrClient;

                    $this->CI->load->builder('ListingBaseBuilder','listingBase');
                    $builder = new ListingBaseBuilder();
                    $obj = $builder->getHierarchyRepository();
                    $streamObj = $builder->getBaseCourseRepository();
                    $streamArr = $obj->getAllStreams();
                    //unset($streamArr[19]);//remove Government Exams stream Id - 20

                    $data['requestType'] = 'hamburger_filters';
                    $data['facetCriterion'] = array('type' => 'stream');
                    
                    $result = array();
                    foreach ($streamArr as $key => $value) {
                        $data['filters']['stream'] = array($value['id']);
                        $tempStreamArr = $streamObj->getBaseCoursesByBaseEntities($value['id'], 'any', 'any',1);
                        $filterResult  = $autoSuggestorSolrClient->getAdvancedFilterOnEntitySelection($data);
                        foreach ($filterResult['advancedFilters']['base_course'] as $key => $row) {
                            if(array_key_exists($row['id'], $tempStreamArr)){
                                $result[$value['id']][$row['id']]  = $row['name'];
                            }
                        }
                        unset($filterResult['advancedFilters']['base_course']);
                    }
                    $this->cacheLib->store($cacheHtmlKey,json_encode($result), 30*24*3600);
                }else{
                    $result = json_decode($this->cacheLib->get($cacheHtmlKey),true);
                }
                return $result; // all base courses which have > 0 listings
        }

        public function getCampusRepProgramData($streamId = 0){
                $this->CI->load->config('CA/CAPrograms');
                $caProgToStreamMap = $this->CI->config->item('programToStreamMap');
                $ccmodel = $this->CI->load->model('CA/CampusConnectModel');
                $ccProgs = $ccmodel->getAllCCPrograms();
                $ccData = array();
                $programIdArr = array();
                if(empty($streamId)){
                    $ccData = $ccProgs;
                }else{
                    $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
                    $listingBaseBuilder = new ListingBaseBuilder();
                    $subStremRepoObj      = $listingBaseBuilder->getSubstreamRepository();
                    $filterCCProgs = array();
                    foreach ($ccProgs as $key => $value) {
                        if($value['entityType'] == 'substream'){
                            $subStremObj = $subStremRepoObj->find($value['entityId']);
                            if($subStremObj->getPrimaryStreamId() == $streamId){
                                $filterCCProgs[] = $value;
                            }
                        }else if($value['entityType'] == 'baseCourse'){
                            if($caProgToStreamMap[$value['programId']] == $streamId)
                            {
                                $filterCCProgs[] = $value;
                            }
                        }else{
                            if($value['entityId'] == $streamId){
                                $filterCCProgs[] = $value;
                            }
                        }
                    }
                    $ccData = $filterCCProgs;
                }
                foreach ($ccData as $val) {
                    $programIdArr[] = $val['programId'];
                }
                //get url from api and add them to output array
                $ccBaseLib = $this->CI->load->library('CA/CcBaseLib');
                $progDetails = $ccBaseLib->getProgramIdMappingDetails($programIdArr);
                foreach ($ccData as &$progData) {
                    $progData['ccUrl'] = $progDetails[$progData['programId']]['url'];
                }
                return $ccData;
        }

        //Desc - get all stream, substream and specialization which having >0 listings, using for hamburger Find college by spec
        //return - array
        //@uthor - akhter

        function nonZeroTabContent($resetData=false){

                $this->CI->load->library('Common/cacheLib');
                $this->cacheLib = new cacheLib();
                $cacheHtmlKey = '_hmMainTbCnt_json';

                if($this->cacheLib->get($cacheHtmlKey) == 'ERROR_READING_CACHE' || $resetData){
                        
                        error_log('Mobile Hamburger :: update content from cron/url');

                        $this->CI->load->library('search/Solr/AutoSuggestorSolrClient');
                        $autoSuggestorSolrClient = new AutoSuggestorSolrClient;
                
                        $this->CI->load->builder('ListingBaseBuilder','listingBase');
                        $builder = new ListingBaseBuilder();
                        $obj = $builder->getHierarchyRepository();
                        $streamArr = $obj->getAllStreams();
                        //unset($streamArr[19]);//remove Government Exams stream Id - 20

                        $data['requestType'] = 'hamburger_filters';
                        $data['facetCriterion'] = array('type' => 'stream');

                        foreach ($streamArr as $key => $value) {
                                $data['filters']['stream'] = array($value['id']);
                                $filterResult  = $autoSuggestorSolrClient->getAdvancedFilterOnEntitySelection($data);           
                                $result[$value['id']]  = array('id'=>$value['id'],'name'=>$value['name']);
                        
                                $streamSpec = array();
                                $subStream  = array();
                                foreach ($filterResult['advancedFilters']['sub_spec'] as $key => $row) {
                        
                                        if($row['type'] == 'substream'){
                                                //stream=>substream
                                                $subStream[$row['id']] = array('id'=>$row['id'],'name'=>$row['name']);
                                                
                                                $subSpec = array();
                                                //substream=>specialization
                                                foreach ($row['specialization'] as $key => $spec) {
                                                        $subSpec[$spec['id']] = array('id'=>$spec['id'],'name'=>$spec['name']);
                                                }
                                                if(!empty($subSpec)){
                                                    $subStream[$row['id']]['specializations'] = $subSpec;
                                                }
                                        }                               

                                        //stream=>specialization
                                        if($row['type'] == 'specialization'){
                                                $streamSpec[$row['id']] = array('id'=>$row['id'],'name'=>$row['name']);
                                        }
                                }
                        unset($filterResult['advancedFilters']['base_course'],$filterResult['advancedFilters']['sub_spec']);
                                
                                if(!empty( $subStream)){
                                    $result[$value['id']]['substreams'] = $subStream;
                                }
                                if(!empty($streamSpec)){
                                    $result[$value['id']]['specializations'] = $streamSpec;
                                }
                        }

                        /*$temp1=$result[21];
                        for ($x=1; $x <=21; $x++) { 
                            if ($x==3) {
                                $temp2=$result[3];
                                $result[3]=$temp1;
                                # code...
                            }
                            elseif ($x>3) {
                                $temp1=$result[$x];
                                $result[$x]=$temp2;
                                $temp2=$temp1;
                            }
                        }
                        unset($result[21]);*/
                        
                    $this->cacheLib->store($cacheHtmlKey,json_encode($result), 30*24*3600);   
                }else{
                    $result = json_decode($this->cacheLib->get($cacheHtmlKey),true);
                }
            return $result;
        }
}
?>
