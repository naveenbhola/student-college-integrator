
<?php
class Discussionhomesearchcontent {
	
	private $_ci;
	private $discussionsearchcontent;
        
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->library("search/Discussionsearchcontent");
		$this->discussionsearchcontent = new Discussionsearchcontent();
	}


    public function getQuestionDocuments()
    {
       try{
            $searchResults = $this->discussionsearchcontent->getQuestionDocuments();
            return $searchResults; 
       }catch(Exception $e)
       {
            error_log("no question doc found");
       }
    }

    public function getDiscussionDocuments()
    {
       try{
            $searchResults = $this->discussionsearchcontent->getDiscussionDocuments();
            return $searchResults; 
       }catch(Exception $e)
       {
            error_log("no question doc found");
       }
    }

    public function getRelatedQuestions($searchText,$count, $excludeQuestionIds = array())
    {
           $searchResults = $this->discussionsearchcontent->getQuestionsByText($searchText,$count, $excludeQuestionIds);
           return $searchResults;
    }

    public function getQuestionCategroy($questionText)
    {
         $category_predicted = $this->discussionsearchcontent->getQuestionCategory($questionText);
         return $category_predicted;
    }
}
?>
