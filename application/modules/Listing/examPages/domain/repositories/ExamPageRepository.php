<?php

class ExamPageRepository extends EntityRepository
{
    private $examPageDao;
    
    function __construct($dao, $cache) {
		parent::__construct($dao, $cache);
        $this->examPageDao = $dao;
        
        //load required entities
        $this->CI->load->entities(array('ExamPage', 'ExamPageDate', 'Wiki'), 'examPages');
        
        //set caching
        $this->caching = false;
	}
    
    /*
	 * Find exam page using exam name
	 */
	public function find($examName, $preview = 'false') {
		if(empty($examName)) {
            return false;
        }
        
        //check in cache
        if($this->caching && $cachedExamPage = $this->cache->getExamPage($examName)) {
			return $cachedExamPage;
		}
        
        //else, get data from model
		$examPageResults = $this->examPageDao->getData($examName, $preview);
        $examPage = $this->_load($examPageResults);
		if($examPage instanceof ExamPage) {
			//set exam full form in request
			//load exampage builder
			$this->request = $this->CI->load->library('examPages/ExamPageRequest');
			$examFullForm = $examPage->getExamFullForm();
			if(!empty($examFullForm))
				$this->request->setExamFullForm($examPage->getExamFullForm());
	
	
			if($this->caching) {
				$this->cache->storeExamPage($examPage);
			}	
		}
		return $examPage;
	}
    
    /*
     * Returns exampage object (returns array with empty key values if data not found)
     */
    private function _load($result) {
        if(is_array($result) && !empty($result['basic'])) {
            $examPage = $this->_createExamPage($result);
            $this->_loadChildren($examPage, $result);
		}
        return $examPage;
	}
    
    /*
     * Create exampage object filled with basic information
     */
	private function _createExamPage($result) {
		$examPage = new ExamPage;
        $examPageBasicData = (array) $result['basic'];
        $this->fillObjectWithData($examPage, $examPageBasicData);
        return $examPage;
	}
    
    /*
     * Load all the children
     */
	private function _loadChildren($examPage, $result) {
		$children = array(
							'home',
							'syllabus',
							'importantDates',
							'result',
                            'section'
						);
		foreach($children as $child) {
			if(is_array($result[$child]) && count($result[$child]) > 0) {
				foreach($result[$child] as $childResult) {
					$this->_loadChild($child, $examPage, $childResult);
				}
			}
			else {
                //load empty child
				$this->_loadChild($child, $examPage);
			}
		}
	}
    
    /*
     * Load individual child of exampage
     */
	private function _loadChild($child, $examPage, $childResult = NULL)
	{
		switch($child) {
			case 'home':
				$this->_loadHomePageData($examPage, $childResult);
				break;
			case 'syllabus':
				$this->_loadSyllabus($examPage, $childResult);
				break;
			case 'importantDates':
				$this->_loadImportantDates($examPage, $childResult);
				break;
			case 'result':
				$this->_loadResultsData($examPage, $childResult);
				break;
            case 'section':
                $this->_loadSectionsData($examPage, $childResult);
				break;
		}
	}
    
    /*
     * Populate homepage data
     */
	private function _loadHomePageData($examPage, $result)
	{
		$wiki = new Wiki;
		$this->fillObjectWithData($wiki, $result);
        
        //store in exampage object
		$examPage->setHomepageData($wiki);        
	}
    
    /*
     * Populate syllabus data
     */
    private function _loadSyllabus($examPage, $result) {
        $wiki = new Wiki;
        $this->fillObjectWithData($wiki, $result);
        
        //store in exampage object
		$examPage->setSyllabus($wiki);
    }
    
    /*
     * Populate important dates data
     */
    private function _loadImportantDates($examPage, $result) {
        $date = new ExamPageDate;
		$this->fillObjectWithData($date, $result);
        
        //store in exampage object
		$examPage->setImportantDates($date);
    }
    
    /*
     * Populate 'results' data
     */
    private function _loadResultsData($examPage, $result) {
        /*
         * Set ExamPageDate type data
         */
        $resultsDateData = (array) $result['Declaration Date'];
        $date = new ExamPageDate;
        $wiki = new Wiki;
		$this->fillObjectWithData($date, $resultsDateData);
        
        //store in exampage object
		$examPage->setResultsData($wiki, $date, 'Declaration Date');
        
        /*
         * Set wiki fields
         */
        $resultsWikiData = $result['Exam Analysis'];
        $date = new ExamPageDate;
        $wiki = new Wiki;
        $this->fillObjectWithData($wiki, (array) $resultsWikiData);
        //store in exampage object
        $examPage->setResultsData($wiki, $date, 'Exam Analysis');
        
        
        $resultsWikiData = $result['Exam Reaction'];
        $date = new ExamPageDate;
        $wiki = new Wiki;
        $this->fillObjectWithData($wiki, (array) $resultsWikiData);
        //store in exampage object
        $examPage->setResultsData($wiki, $date, 'Exam Reaction');
        
        
        /*
         * Set interviews separately
         */
        $resultsInterviewData = $result['Topper interview'];
        foreach($resultsInterviewData as $data) {
            $wiki = new Wiki;
            $this->fillObjectWithData($wiki, (array) $data);
            
            //store in exampage object
            $examPage->setResultsData($wiki, $date, 'Topper interview');
        }
    }
    
    /*
     * Populate section information
     */
    private function _loadSectionsData($examPage, $result) {
        //store in exampage object
        $examPage->setSectionInfo((array) $result);
    }

    /**
     * fetch article according to tags
     * @param  string $type   
     * @param  string $blogIds  
     * @param  integer $count  
     * @param  integer $offset 
     * @return array        
     */
    function getExamArticles($type,$blogIds,$count=null,$offset=null){
        $examPageArticles = $this->examPageDao->getExamArticles($type,$blogIds,$count,$offset);      
        return $examPageArticles; 
    }

    /**
     * get article info
     * @param  integer $articleId
     * @return array  article info
     */
    function getArticleInfo($articleId){
        if(empty($articleId) && !is_numeric($articleId)){
            return false;
        }

        $articleResult = $this->examPageDao->getArticleInfo($articleId);      
        return $articleResult;
    }
}