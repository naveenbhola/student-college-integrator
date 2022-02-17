<?php

class AbroadExamPageRepository extends EntityRepository
{
    private $abroadabroadExamPageDao;
    //private $caching;
    
    function __construct($dao/*, $cache*/) {
	parent::__construct($dao/*, $cache*/);
        $this->abroadExamPageDao = $dao;
        
        //load required entities
        $this->CI->load->entities(array('AbroadExamPage'), 'abroadExamPages');
        
        //set caching
        //$this->caching = false;
    }
    
    /*
     * Find exam page using examId /contentId ,section (optional : 1 by default - will pick the first one)
     */
    public function find($examPageId, $section = 1) {
	if(empty($examPageId)) {
	    return false;
	}
        //check in cache
        //if($this->caching && $cachedAbroadExamPage = $this->cache->getAbroadExamPage($examName)) {
	//    return $cachedAbroadExamPage;
    	//}
        //else, get data from model
	$examPageResults = $this->abroadExamPageDao->getAbroadExamPageData($examPageId, $section);
	// create object
        $examPage = $this->_load($examPageResults[$examPageId]);
        //if($this->caching) {
        //    $this->cache->storeExamPage($examPage);
        //}
        
	return $examPage;
    }
    /*
     * Create abroadexampage object 
     */
    private function _load($result) {
	if($result){
	    $examPage = new AbroadExamPage;
	    $this->fillObjectWithData($examPage, $result);
	    return $examPage;
	}
	else{
	    return false;
	}
    }
}