<?php class Discussionsearchcontent {
	
	private $_ci;
	private $searchWrapper;
	private $searchCommon ;
	private $searchRepository;
    private $searchServer;

	public function __construct()
	{
		$this->_ci = & get_instance();
		$this->_ci->load->helper('search/SearchUtility');
		$this->_ci->config->load('search_config');
		$this->_ci->load->builder('SearchBuilder', 'search');
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->searchCommon 	= SearchBuilder::getSearchCommon();
		$this->searchWrapper = SearchBuilder::getSearchWrapper();
		$this->searchRepository = SearchBuilder::getSearchRepository();
        $this->searchServer = SearchBuilder::getSearchServer($this->_ci->config->item('search_server'));
	}


	public function getQuestionDocuments()
	{
		$params = $this->searchCommon->readSearchParams('SEARCH');
		$results = $this->searchRepository->searchQuestions($params);
		return $results;
	}

	public function getDiscussionDocuments()
	{
		$params = $this->searchCommon->readSearchParams('SEARCH');
		$results = $this->searchRepository->searchDiscussions($params);
		return $results;
	}

	public function getQuestionsByText($questionText,$count=20, $excludeQuestionIds = array())
	{
		$params = $this->searchCommon->readQuestionSearchParameters($questionText,$count, $excludeQuestionIds);
		$results = $this->searchRepository->searchQuestions($params);
		return $results;
	}

    public function getQuestionCategory($questionText)
    {
        $category_predicted;
        $qerUrl = $this->_ci->config->item('qer_url_new');
        $qerUrl = $qerUrl."?inkeyword=".urlencode($questionText)."&output=xmlcand&action=Submit&doObjectiveDirection=false";
        $qerOutput = $this->searchServer->curl(sanitizeUrl($qerUrl));
        $xml1 = json_decode(json_encode(simplexml_load_string($qerOutput, null, LIBXML_NOCDATA)), true);
        if(array_key_exists("question_category_ids", $xml1))
        {
            $category_predicted = explode("::",$xml1['question_category_ids']);
            $category_predicted = $category_predicted[0];
        }
        else
        {
            error_log("qer failed to retrieve category.");
        }
        return $category_predicted;
    }

}
?>
