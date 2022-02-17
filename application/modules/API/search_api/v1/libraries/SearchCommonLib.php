<?php
/**
 * Search Common Lib Class
 * This is the class for all the Common functionalities related to search like. getting tag search data, discussion/question search results
 * @date    2015-09-04
 * @author  Romil Goel
 * @todo    none
*/
class SearchCommonLib {

    private $CI;

    function __construct() {
        $this->CI = &get_instance();

		$this->CI->load->helper("messageBoard/ana");
        $this->CI->load->library('messageBoard/Discussionhomesearchcontent');
        $this->CI->load->builder('SearchBuilder', 'search');
        $this->CI->load->model('search/SearchModel', '', true);
        $this->searchRepository = SearchBuilder::getSearchRepository();
        $this->searchModel = new SearchModel();
    }
    
    /**
     * Method to get the search results for tag/question/discussion
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-09-04
     * @param  [string]     $type        [type of the search - tag/question/discussion]
     * @param  [string]     $text        [user typed text]
     * @param  array        $otherParams [this list contains all other params like startoffset, rows to be fetched]
     * @return [array]                   [list of all searched results]
     */
    function getSearchResults($type, $text, $otherParams = array()){

        $data = array();

        switch ($type) {
            case 'tag':

                $taggingLib   = $this->CI->load->library("Tagging/TaggingLib");
                $tags         = $taggingLib->showTagSuggestions(array($text));
                $tagsFromText = array_merge((array)$tags['objective'], (array)$tags['background']);
                $tagsFromText = array_filter($tagsFromText);
   
                $params = array("keyword" => $text, "optionalParams" => array("fields" =>"All", "start" => $otherParams['start'], "numRows" => $otherParams['rows'], "boostingIds" => $tagsFromText));
                $data   = $this->searchRepository->searchTags($params);

                $tagIds = array();
                foreach($data['content'] as $docs){
                    $tagIds[] = $docs->getId();
                }

                if($tagIds){
                    $this->CI->load->model("v1/tagsmodel");
                    $tagsmodel = new tagsmodel();
                    $tagFollowerCount = $tagsmodel->entityFollowerCount($tagIds, 'tag');
                    $tagFollowFlag = $tagsmodel->isUserFollowingTag($otherParams['userId'], $tagIds);
                }

                foreach($data['content'] as $key=>$docs){
                    $id = $docs->getId();
                    $data['content'][$key]->setFollowCount($tagFollowerCount[$id]);
                    $data['content'][$key]->setTagFollowFlag($tagFollowFlag[$id]);
                }

                $data   = $this->formatTagSearchData($data['content']);
                break;
            
            case 'discussion':
                $this->setContentParams($type, $text, $otherParams);
                $discussionhomesearchcontent = new Discussionhomesearchcontent();
                $data                        = $discussionhomesearchcontent->getDiscussionDocuments();
                $otherDetails                = $this->getThreadOtherDetails($data['content'], 'discussion');
                $data                        = $this->formatDiscussionSearchData($data['content'], $otherDetails);
                break;

            case 'question':
                $this->setContentParams($type, $text, $otherParams);
                $discussionhomesearchcontent = new Discussionhomesearchcontent();
                $data                        = $discussionhomesearchcontent->getQuestionDocuments();
                $otherDetails                = $this->getThreadOtherDetails($data['content'], 'question');
                $data                        = $this->formatQuestionSearchData($data['content'], $otherDetails);
                break;
        }

        return $data;
    }	

    function formatTagSearchData($data){

        $formattedData = array();
        $i = 0;
        foreach($data as $tagObject){

            $formattedData[$i]['id']          = $tagObject->getId();
            $formattedData[$i]['name']        = strip_tags(html_entity_decode($tagObject->getName()));
            $formattedData[$i]['followCount'] = $tagObject->getFollowCount();
            $formattedData[$i]['isFollowing'] = $tagObject->getTagFollowFlag();

            $i++;
        }

        return $formattedData;
    }

    function formatDiscussionSearchData($data, $otherDetails){
        
        $formattedData = array();
        $i = 0;
        global $isMobileApp;
        foreach($data as $discussionObject){

            $msgCount                       = $otherDetails['discussionsCommentCount'][$discussionObject->getThreadId()];
            $formattedData[$i]['id']        = $discussionObject->getThreadId();
            if($isMobileApp){
                $formattedData[$i]['title'] = sanitizeAnAMessageText(strip_tags(html_entity_decode($discussionObject->getTitle())),'discussion');
            }else{
                $formattedData[$i]['title'] = strip_tags(html_entity_decode($discussionObject->getTitle()));
            }
            $formattedData[$i]['viewCount'] = $otherDetails[$discussionObject->getThreadId()]['viewCount'];
            $formattedData[$i]['msgCount']  = $msgCount ? $msgCount : 0;
            
            $i++;
        }

        return $formattedData;
    }

    function formatQuestionSearchData($data, $otherDetails){

        $formattedData = array();
        $i = 0;
        global $isMobileApp;
        foreach($data as $questionObject){

            $formattedData[$i]['id']          = $questionObject->getThreadId();
            if($isMobileApp){
                $formattedData[$i]['title']       = sanitizeAnAMessageText(strip_tags(html_entity_decode($questionObject->getTitle())),'question');
            }else{
                $formattedData[$i]['title']       = strip_tags(html_entity_decode($questionObject->getTitle()));
            }
            $formattedData[$i]['viewCount']   = $otherDetails[$questionObject->getThreadId()]['viewCount'];
            $formattedData[$i]['msgCount']    = $otherDetails[$questionObject->getThreadId()]['msgCount'];
            
            $i++;
        }

        return $formattedData;
    }

    function setContentParams($type, $text, $otherParams){

        $start = $otherParams['start'] ? $otherParams['start'] : 0;
        $rows = $otherParams['rows'] ? $otherParams['rows'] : 10;

        $commonData = array("keyword" => $text, "start" => $start, "institute_rows" => 0, "content_rows"=> $rows);
        $requestData = $commonData + array("search_data_type" => $type);

        // fetch discussion data
        foreach($requestData as $key=>$val){
            $_REQUEST[$key] = $val;
        }
    }

    function getThreadOtherDetails($documents, $type){

        $threadIds = array();
        foreach ($documents as $doc) {
            $threadIds[] = $doc->getThreadId();
        }

        $data = $this->searchModel->getThreadsDetails($threadIds);

        if($type == 'discussion'){
            $commentCounts = $this->searchModel->getDiscussionsCommentCount($threadIds);
        }
        $data['discussionsCommentCount'] = $commentCounts;

        return $data;
    }
}
?>
