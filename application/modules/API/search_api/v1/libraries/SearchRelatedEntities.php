<?php
/**
 * Search RelatedEntities Class
 * This is the class for fetching out related items
 * @date    2015-09-07
 * @author  Romil Goel
 * @todo    none
*/
class SearchRelatedEntities {

    private $CI;

    function __construct() {

        $this->CI = &get_instance();

        // boosting constants
        define("THREAD_BOOST_LEVEL_1", 100);
        define("THREAD_BOOST_LEVEL_2", 50);

        $this->CI->load->builder('SearchBuilder', 'search');
        $this->CI->load->model('search/SearchModel', '', true);
        $this->CI->config->load('search_config');
        $this->CI->load->builder('SearchBuilder', 'search');

        $this->config = $this->CI->config;
        $this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));
        $this->searchModel = new SearchModel();
    }
    
    function getRelatedEntity($id, $type, $excludeEntities = array(), $limit = 10, $params = array()){

          if(in_array($type, array("question", "discussion"))){
            $relatedData = array();

            if($params['algorithm'] == 1){
                $relatedData = $this->getRelatedThreads($id, $type, $excludeEntities, $limit);
            }
            else{
                $params['excludeEntities'] = array_merge((array)$excludeEntities, array($id));  
                $relatedData = $this->getRelatedQuestionDiscussion($id, $type, $params, $limit);
            }
            
            return $relatedData;
          }
          else if($type == "tag"){
            return $this->getRelatedTags($id);
          }
          else{
            return false;
          }
    }

    function getRelatedThreads($threadId, $threadType, $excludeEntities = array(), $limit = 10){
        $s = microtime(true);
        $threadTags = $this->searchModel->getContentMappedTags($threadId, $threadType);
        if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 1.1 :: ".$threadType."_".$threadId." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');
        $s = microtime(true);

        $this->CI->load->library("common_api/APICommonCacheLib");
        $apiCommonCacheLib = new APICommonCacheLib();
        $highLevelTags = $apiCommonCacheLib->getHighLevelTags();
        if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 1.2 :: ".$threadType."_".$threadId." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');
        $s = microtime(true);

        $commonHighLevelTags = array();
        $commonHighLevelTags = array_intersect($highLevelTags, (array)$threadTags['objective']);
        $commonHighLevelTags = array_merge($commonHighLevelTags, (array)array_intersect($highLevelTags, (array)$threadTags['manual']));

        $commonHighLevelParentTags = array();
        $commonHighLevelParentTags = array_intersect($highLevelTags, (array)$threadTags['objective_parent']);
        $commonHighLevelParentTags = array_merge($commonHighLevelParentTags, (array)array_intersect($highLevelTags, (array)$threadTags['manual_parent']));
        
        $boostParams                                    = array();
        $boostParams['objective_tags']                  = array_diff((array)$threadTags['objective'], $commonHighLevelTags);
        $boostParams['manual_tags']                     = array_diff((array)$threadTags['manual'], $commonHighLevelTags);
        $boostParams['highlevel_objective_manual_tags'] = $commonHighLevelTags;
        $boostParams['background_tags']                 = $threadTags['background'];
        $boostParams['objective_parent']                = array_diff((array)$threadTags['objective_parent'], $commonHighLevelParentTags);
        $boostParams['manual_parent']                   = array_diff((array)$threadTags['manual_parent'], $commonHighLevelParentTags);
        $boostParams['highlevel_objective_manual_parent_tags'] = $commonHighLevelParentTags;
        $boostParams['excludeThreads']                  = $excludeEntities;

        $solrUrl = $this->prepareRelatedThreadSolrQuery($threadId, $threadType, $boostParams, $limit);

        $solrContent = unserialize($this->searchServer->curl($solrUrl));
        $response = $this->getDocuments($solrContent);

        if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 2 :: ".$threadType."_".$threadId." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');
        
        if($solrContent['response']['numFound'] < $limit){

            $s = microtime(true);
            foreach ($response as $threadObj) {
              $boostParams['excludeThreads'][] = $threadObj->getThreadId();
            }

            $otherLimit 				   = $limit - $solrContent['response']['numFound'];
            $otherRelatedQuestions         = $this->getOtherRelatedThreads($threadId, $threadType, $boostParams, $otherLimit);  
            if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 3 :: ".$threadType."_".$threadId." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');

            if(is_array($otherRelatedQuestions) && !empty($otherRelatedQuestions))
              $response = array_merge((array)$response, (array)$otherRelatedQuestions);

        }

        $s = microtime(true);
        // set the view count details from DB
        $response = $this->setViewCountDetail($response, $threadType);
        if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 4 :: ".$threadType."_".$threadId." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');

        $s = microtime(true);
        $response = $this->formatAutosuggestorResponse($response, $threadType);
        if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 5 :: ".$threadType."_".$threadId." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');

        $response = array_slice($response, 0, $limit);
        
        return $response;
    }

    private function getOtherRelatedThreads($threadId, $threadType, $boostParams, $limit = 10){

        $solrUrl = $this->getOtherRelatedThreadSolrQuery($threadId, $threadType, $boostParams, $limit);
        
        $solrContent = unserialize($this->searchServer->curl($solrUrl));

        $response = $this->getDocuments($solrContent);

        return $response;
    }

    private function prepareRelatedThreadSolrQuery($threadId, $threadType, $boostParams, $limit = 10){

        $params   = array();

        $objectiveTagsStr = "0";
        $objectiveTags    = array_merge((array)$boostParams['objective_tags'],(array)$boostParams['manual_tags'], (array)$boostParams['highlevel_objective_manual_tags']);
        if($objectiveTags)
          $objectiveTagsStr = implode(" ",$objectiveTags);

        // objective manual tags for boosting
        $objectiveTagsBoostingStr = "0";
        $objectiveBoostingTags    = array_merge((array)$boostParams['objective_tags'],(array)$boostParams['manual_tags']);
        if($objectiveBoostingTags)
          $objectiveTagsBoostingStr = implode(" ",$objectiveBoostingTags);

        //http://172.16.3.247:8984/solr_new/shiksha?q=*&start=0&rows=200&fq=facetype:question&wt=xml&qt=shiksha&&qt=shiksha&fq=thread_tags_objective:(307%20279)&bq=thread_tags_objective:(307%20279%20145043%20327)^100&bq=thread_tags_objectiveparent:(7%20144631)^5&fl=question_id,thread_tags_objective,thread_tags_objectiveparent,score
        // tag types to be given boost

        // add query text
        $params[] = 'q=*:*';
        $params[] = 'start=0';
        $params[] = 'rows='.$limit;
        $params[] = 'fq=facetype:'.$threadType;
        $params[] = 'wt=phps';
        $params[] = 'qt=shiksha';

        if($threadType == 'question')
          $params[] = 'fq=question_created_time_date:['.FILTER_OLD_QUESTION_DATE.'%20TO%20NOW]';
        else if($threadType == 'discussion')
          $params[] = 'fq=discussion_created_time:['.FILTER_OLD_QUESTION_DATE.'%20TO%20NOW]';

        if($threadType == 'question')
        {
            $params[] = 'fq=-question_thread_id:'.$threadId;
            $params[] = 'fq=question_answers_count:'.urlencode('[1 TO *]');
            $params[] = 'fq=thread_tags_objective:('.urlencode($objectiveTagsStr).')%20OR%20thread_tags_manual:('.urlencode($objectiveTagsStr).')';
            if($boostParams['excludeThreads'])
                $params[] = 'fq=-question_thread_id:('.urlencode(implode(" ", $boostParams['excludeThreads'])).")";

            // boosting params
            if($objectiveTagsBoostingStr){
              $params[] = 'bq=thread_tags_objective:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
              $params[] = 'bq=thread_tags_manual:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
            }
            
            if($boostParams['background_tags']){
              $params[] = 'bq=thread_tags_background:('.urlencode(implode(" ",$boostParams['background_tags'])).')^'.THREAD_BOOST_LEVEL_2;
            }

            if($boostParams['highlevel_objective_manual_tags']){
              $params[] = 'bq=thread_tags_objective:('.urlencode(implode(" ",$boostParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
              $params[] = 'bq=thread_tags_manual:('.urlencode(implode(" ",$boostParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
            }
            
            $params[] = 'bf=ord(question_quality_score)^'.THREAD_BOOST_LEVEL_2;
            $params[] = 'bf=recip(rord(question_created_time),1,100,100)^10';
            $params[] = 'fl=*';
        }
        else
        {
            $params[] = 'fq=-discussion_thread_id:'.$threadId;
            $params[] = 'fq=discussion_comment_0:*';
            $params[] = 'fq=thread_tags_objective:('.urlencode($objectiveTagsStr).')%20OR%20thread_tags_manual:('.urlencode($objectiveTagsStr).')';
            if(!empty($boostParams['excludeThreads']))
                $params[] = 'fq=-discussion_thread_id:('.urlencode(implode(" ", $boostParams['excludeThreads'])).")";

            // boosting params
            if($boostParams['objective_tags']){
              $params[] = 'bq=thread_tags_objective:('.urlencode(implode(" ",$boostParams['objective_tags'])).')^'.THREAD_BOOST_LEVEL_1;
            }
            if($boostParams['manual_tags']){
              $params[] = 'bq=thread_tags_manual:('.urlencode(implode(" ",$boostParams['manual_tags'])).')^'.THREAD_BOOST_LEVEL_1;
            }

            if($boostParams['background_tags']){
              $params[] = 'bq=thread_tags_background:('.urlencode(implode(" ",$boostParams['background_tags'])).')^'.THREAD_BOOST_LEVEL_2;
            }

            if($boostParams['highlevel_objective_manual_tags']){
              $params[] = 'bq=thread_tags_objective:('.urlencode(implode(" ",$boostParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
              $params[] = 'bq=thread_tags_manual:('.urlencode(implode(" ",$boostParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
            }

            $params[] = 'bf=ord(discussion_quality_score)^'.THREAD_BOOST_LEVEL_2;
            $params[] = 'bf=recip(ms(NOW,discussion_created_time),3.16e-11,1,1)^10';
            $params[] = 'fl=*';
        }

        // prepare the final solr url
        $solrUrl = SOLR_NEW_INSTI_SELECT_URL_BASE.implode("&", $params);

        return $solrUrl;
  }

  private function getOtherRelatedThreadSolrQuery($threadId, $threadType, $boostParams, $limit = 10){

        $params   = array();

        $objectiveTagsStr = "0";
        $objectiveTags    = array_merge((array)$boostParams['objective_parent'],(array)$boostParams['manual_parent'], (array)$boostParams['highlevel_objective_manual_parent_tags']);
        if($objectiveTags)
          $objectiveTagsStr = implode(" ",$objectiveTags);

        $objectiveTagsBoostingStr = "0";
        $objectiveBoostingTags    = array_merge((array)$boostParams['objective_parent'],(array)$boostParams['manual_parent']);
        if($objectiveBoostingTags)
          $objectiveTagsBoostingStr = implode(" ",$objectiveBoostingTags);

        $highLevelTagsStr = "0";
        if($boostParams['highlevel_objective_manual_parent_tags'])
          $highLevelTagsStr = implode(" ",$boostParams['highlevel_objective_manual_parent_tags']);

        // highlevel_objective_manual_parent_tags
        
        // add query text
        $params[] = 'q=*:*';
        $params[] = 'start=0';
        $params[] = 'rows='.$limit;
        $params[] = 'wt=phps';
        $params[] = 'qt=shiksha';

        if($threadType == 'question')
          $params[] = 'fq=question_created_time_date:['.FILTER_OLD_QUESTION_DATE.'%20TO%20NOW]';
        else if($threadType == 'discussion')
          $params[] = 'fq=discussion_created_time:['.FILTER_OLD_QUESTION_DATE.'%20TO%20NOW]';

        if($threadType == 'question'){
            $params[] = 'fq=facetype:question';
            // add query response type : phps will give the output in serialized version of php array
            
            $params[] = 'fq=-question_thread_id:'.$threadId;
            $params[] = 'fq=question_answers_count:'.urlencode('[1 TO *]');
            if(!empty($boostParams['excludeThreads']))
                $params[] = 'fq=-question_thread_id:('.urlencode(implode(" ", $boostParams['excludeThreads'])).")";
            $params[] = 'fq=thread_tags_objectiveparent:('.urlencode($objectiveTagsStr).')%20OR%20thread_tags_manualparent:('.urlencode($objectiveTagsStr).')';

            // boosting
            $params[] = 'bq=thread_tags_objectiveparent:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
            $params[] = 'bq=thread_tags_manualparent:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
            $params[] = 'bq=thread_tags_objectiveparent:('.urlencode($highLevelTagsStr).')^'.THREAD_BOOST_LEVEL_2;
            $params[] = 'bq=thread_tags_manualparent:('.urlencode($highLevelTagsStr).')^'.THREAD_BOOST_LEVEL_2;

            $params[] = 'bf=ord(question_quality_score)^50';
            $params[] = 'bf=recip(rord(question_created_time),1,100,100)^10';
            $params[] = 'fl=*';
        }
        else{
            $params[] = 'fq=facetype:discussion';
            $params[] = 'fq=-discussion_thread_id:'.$threadId;
            $params[] = 'fq=discussion_comment_0:*';
            if(!empty($boostParams['excludeThreads']))
                $params[] = 'fq=-discussion_thread_id:('.urlencode(implode(" ", $boostParams['excludeThreads'])).")";
            $params[] = 'fq=thread_tags_objectiveparent:('.urlencode($objectiveTagsStr).')%20OR%20thread_tags_manualparent:('.urlencode($objectiveTagsStr).')';

            // boosting
            $params[] = 'bq=thread_tags_objectiveparent:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
            $params[] = 'bq=thread_tags_manualparent:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
            $params[] = 'bq=thread_tags_objectiveparent:('.urlencode($highLevelTagsStr).')^'.THREAD_BOOST_LEVEL_2;
            $params[] = 'bq=thread_tags_manualparent:('.urlencode($highLevelTagsStr).')^'.THREAD_BOOST_LEVEL_2;

            $params[] = 'bf=ord(discussion_quality_score)^50';
            $params[] = 'bf=recip(ms(NOW,discussion_created_time),3.16e-11,1,1)^10';
            $params[] = 'fl=*';
        }
        
        // prepare the final solr url
        $solrUrl = SOLR_NEW_INSTI_SELECT_URL_BASE.implode("&", $params);

        return $solrUrl;
    }

    function getTagDetails($tagIds){

        if(empty($tagIds))
            return false;

        //http://172.16.3.247:8983/solr/collection1/select?q=*&wt=xml&indent=true&fq=facetype:tag&fq=tag_id:(301%20306)

        $params   = array();

        // add query text
        $params[] = 'q=*';
        $params[] = 'rows=100';
        $params[] = 'wt=phps';
        $params[] = 'fq=facetype:tag';
        $params[] = 'fq=tag_id:('.urlencode(implode(" ", $tagIds)).')';

        // prepare the final solr url
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode("&", $params);

        $solrContent = unserialize($this->searchServer->curl($solrUrl));

        return $solrContent;
    }

  /**
   * Method to get the related tags for a given tag
   * Logic : The logic behind getting related tags is 
   * 1. fetch all parents, siblings and children of the given tag(order by quality score) 
   * 2. Merge 1/3rd part of each list and then shuffle it for visibility of all tags
   * 3. merge the remaining shuffled list into the tail of the result list
   * @author Romil Goel <romil.goel@shiksha.com>
   * @date   2015-09-16
   * @param  [type]     $tagId            [description]
   * @param  integer    $relatedTagsCount [description]
   * @return [type]                       [description]
   */
  function getRelatedTags($tagId, $relatedTagsCount = 10){

      if(empty($tagId))
          return false;

      // check if it is present in redis
      $SearchAPICacheLib = $this->CI->load->library("v1/SearchAPICacheLib");
      $relatedTags = $SearchAPICacheLib->getRelatedTags($tagId);
      if($relatedTags){
        shuffle($relatedTags);
        return $relatedTags;
      }

      // get parents
      //$this->searchModel = new SearchModel();
      $perBucketCount = floor($relatedTagsCount/3);
      
      $tagParents  = $this->searchModel->getTagParents($tagId, 10);
      $tagChildren = $this->searchModel->getTagChildren($tagId, 10);
      $tagSiblings = $this->searchModel->getTagSiblings($tagId, $tagParents, 10);
      $tagParentSubArr   = array_slice($tagParents, 0, $perBucketCount);
      $tagChildrenSubArr = array_slice($tagChildren, 0, $perBucketCount);
      $tagSiblingsSubArr = array_slice($tagSiblings, 0, $perBucketCount);
      
      $relatedTags = array_merge((array)$tagParentSubArr , (array)$tagChildrenSubArr , (array)$tagSiblingsSubArr);

      // shuffle the list for randomization
      shuffle($relatedTags);

      $remainingParents = (array)array_slice($tagParents, $perBucketCount);
      $remainingChildren = (array)array_slice($tagChildren, $perBucketCount);
      $remainingSiblings = (array)array_slice($tagSiblings, $perBucketCount);
      $remainingTags = (array)array_merge($remainingParents, $remainingChildren, $remainingSiblings);

      shuffle($remainingTags);

      $relatedTags = array_merge((array)$relatedTags , $remainingTags);
      $relatedTags = array_unique($relatedTags);

      $relatedTags = array_slice($relatedTags, 0, $relatedTagsCount);

      $relatedTagsData = $this->searchModel->getTagDetails($relatedTags);

      $finalResult = array();
      foreach($relatedTags as $relatedtagId){
        $finalResult[] = $relatedTagsData[$relatedtagId];
      }

      // store in cache
      if($finalResult)
        $SearchAPICacheLib->storeRelatedTags($tagId, $finalResult);

      return $finalResult;
  }

  private function getDocuments($solrContent){

    if($solrContent['response']['numFound'] <= 0){
      return false;
    }

    $contentResults['data'] = $solrContent['response']['docs'];
    $contentSearchResultsRepository = SearchBuilder::getContentSearchResultRepository();
    $contentDocumentList = $contentSearchResultsRepository->getContent($contentResults);

    return $contentDocumentList;
  }

  private function formatAutosuggestorResponse($objectsList, $type){
    $this->CI->load->helper('messageBoard/ana');

    $formattedArr = array();
    switch ($type) {
      case 'tag':
        $i = 0;
        foreach($objectsList as $obj){
          $formattedArr[$i]['id'] = $obj->getId();
          $formattedArr[$i]['name'] = $obj->getName();
          $formattedArr[$i]['description'] = $obj->getDescription();
          $i++;
        }
        break;
      
      case 'question':
        $i = 0;
        global $isMobileApp;
        foreach($objectsList as $obj){
          if($obj){
            $formattedArr[$i]['msgId']       = $obj->getId();
            $formattedArr[$i]['threadId']    = $obj->getThreadId();
            if($isMobileApp){
                $formattedArr[$i]['title']   = sanitizeAnAMessageText(strip_tags(html_entity_decode($obj->getFullTitle())),'question');
            }else{
                $formattedArr[$i]['title']   = strip_tags(html_entity_decode($obj->getFullTitle()));
            }
            $formattedArr[$i]['viewCount']   = $obj->getViewCount();
            $formattedArr[$i]['childCount']  = $obj->getAnswersCount();
            $formattedArr[$i]['creationDate']  = date('Y-m-d',strtotime($obj->getCreatedTime()));
            
            $i++;
          }
        }
        break;

      case 'discussion':
        $i = 0;
        global $isMobileApp;
        foreach($objectsList as $obj){
          if($obj){
            $formattedArr[$i]['msgId']       = $obj->getId();
            $formattedArr[$i]['threadId']    = $obj->getThreadId();
            if($isMobileApp){
                $formattedArr[$i]['title']   = sanitizeAnAMessageText(strip_tags(html_entity_decode($obj->getFullTitle())),'discussion');
            }else{
                $formattedArr[$i]['title']   = strip_tags(html_entity_decode($obj->getFullTitle()));
            }
            
            $formattedArr[$i]['viewCount']   = $obj->getViewCount();
            $formattedArr[$i]['childCount']  = $obj->getCommentCount();
            $formattedArr[$i]['creationDate']  = date('Y-m-d',strtotime($obj->getCreatedTime()));
            $i++;
          }
        }
        break;
    }

    return $formattedArr;
   
  }

  function setViewCountDetail($response, $threadType){

      $threadIds = array();
      foreach($response as $doc){
        if($doc)
          $threadIds[] = $doc->getThreadId();
      }

      $viewCounts = $this->searchModel->getThreadsViewCount($threadIds);

      $commentCount = array();
      if($threadType == 'discussion')
        $commentCount = $this->searchModel->getDiscussionsCommentsCount($threadIds);

      foreach($response as $key=>$doc){
        if($doc){
          $response[$key]->setViewCount($viewCounts[$doc->getThreadId()]['viewCount']);
	  if($threadType != 'discussion'){
		  $response[$key]->__set('title',$viewCounts[$doc->getThreadId()]['msgTxt']);
	  }
          $cnt = $commentCount[$doc->getThreadId()] ? $commentCount[$doc->getThreadId()] : 0;
          $response[$key]->setCommentCount($cnt);
        }
      }

      return $response;
  }

  function getRelatedThreadByText($text, $params=array(), $limit = 10){

        $this->CI->load->entities(array('Category'),'categoryList');
        $solrUrl = $this->getRelatedThreadByTextSolrQuery($text, $params, $limit);
        
        $solrContent = unserialize($this->searchServer->curl($solrUrl));

        $searchModel = $this->CI->load->model("search/SearchModel");
        foreach ($solrContent['response']['docs'] as &$value) {
            $questionDetails = $searchModel->getQuestionDisplayInformation($value['question_id']);
            $value['question_comment_count'] = $questionDetails['commentCount'];
            $value['question_answer_count']  = $questionDetails['answerCount'];
            $value['question_view_count']    = $questionDetails['viewCount'];
        }
        $response = $this->getDocuments($solrContent);

        $finalResponse                        = array();
        $finalResponse['content']             = $response;
        $finalResponse['general']['start']    = $solrContent['response']['start'];
        $finalResponse['general']['numfound'] = $solrContent['response']['numFound'];
        return $finalResponse;
    }

    function getRelatedQuestionDiscussion($id, $type, $inputParams=array(), $limit = 10){

        $this->CI->load->entities(array('Category'),'categoryList');

        $threadTags = $this->searchModel->getContentMappedTags($id, $type);

        $this->CI->load->library("common_api/APICommonCacheLib");
        $apiCommonCacheLib = new APICommonCacheLib();
        $highLevelTags     = $apiCommonCacheLib->getHighLevelTags();

        $commonHighLevelTags = array();
        $commonHighLevelTags = array_intersect($highLevelTags, (array)$threadTags['objective']);
        $commonHighLevelTags = array_merge($commonHighLevelTags, (array)array_intersect($highLevelTags, (array)$threadTags['manual']));

        $commonHighLevelParentTags = array();
        $commonHighLevelParentTags = array_intersect($highLevelTags, (array)$threadTags['objective_parent']);
        $commonHighLevelParentTags = array_merge($commonHighLevelParentTags, (array)array_intersect($highLevelTags, (array)$threadTags['manual_parent']));
        
        $params                                                    = array();
        $params['boost']['objective_tags']                         = array_diff((array)$threadTags['objective'], $commonHighLevelTags);
        $params['boost']['manual_tags']                            = array_diff((array)$threadTags['manual'], $commonHighLevelTags);
        $params['boost']['highlevel_objective_manual_tags']        = $commonHighLevelTags;
        $params['boost']['background_tags']                        = $threadTags['background'];
        $params['boost']['objective_parent']                       = array_diff((array)$threadTags['objective_parent'], $commonHighLevelParentTags);
        $params['boost']['manual_parent']                          = array_diff((array)$threadTags['manual_parent'], $commonHighLevelParentTags);
        $params['boost']['highlevel_objective_manual_parent_tags'] = $commonHighLevelParentTags;
        $params['excludeThreads']                                  = $inputParams['excludeEntities'];
        $params['threadType']                                      = $type;

        $text = "";
        if(empty($inputParams['text'])){
            $qnamodel      = $this->CI->load->model("messageBoard/qnamodel");
            $this->CI->benchmark->mark('get_related_threadDetails_start');
            $threadDetails = $qnamodel->getThreadDetails($id, $type);
            $this->CI->benchmark->mark('get_related_threadDetails_end');
            $text          = $threadDetails[0]['msgTxt'];
        }
        else{
            $text = $inputParams['text'];
        }

        $this->CI->benchmark->mark('get_related_thread_solr_start');
        $solrUrl = $this->getRelatedThreadByTextSolrQuery($text, $params, $limit);
        
        $solrContent = unserialize($this->searchServer->curl($solrUrl));
        $response = $this->getDocuments($solrContent);
         $this->CI->benchmark->mark('get_related_thread_solr_end');

        // set the view count details from DB
        $response = $this->setViewCountDetail($response, $type);
        
        $this->CI->benchmark->mark('format_related_thread_solr_start');
        $response = $this->formatAutosuggestorResponse($response, $type);
         $this->CI->benchmark->mark('format_related_thread_solr_end');
        
        $response = array_slice($response, 0, $limit);
        
        return $response;
    }

    function getRelatedThreadByTextSolrQuery($text, $inputParams=array(), $limit = 10){

        $params   = array();

        $searchByQuestionId = false;
        if(is_numeric($text)){
            $searchByQuestionId = true;
        }

        if(!$searchByQuestionId && empty($inputParams['boost'])){
            $this->CI->load->library("Tagging/TaggingLib");
            $taggingLib= new TaggingLib();
            $tags = $taggingLib->showTagSuggestions(array($text));
            $inputParams['boost']['objective_tags'] = array_filter($tags['objective']);
        }

        // objective manual tags for boosting
        $objectiveTagsBoostingStr = "0";
        $objectiveBoostingTags    = array_merge((array)$inputParams['boost']['objective_tags'],(array)$inputParams['boost']['manual_tags']);
        if($objectiveBoostingTags)
          $objectiveTagsBoostingStr = implode(" ",$objectiveBoostingTags);

        $text = $this->escapeSolrValue($text);
        $threadType = "question";
        if($inputParams['threadType'] == 'discussion'){
            $threadType = "discussion";
        }

        if($threadType == "question"){
                if(!$searchByQuestionId){
                    $params[] = 'q='.$text;
                }else{
                    $params[] = 'q=*:*';
                    $params[] = 'fq=question_id:'.$text;
                }
                
                $params[] = 'start=0';
                $params[] = 'rows='.$limit;
                $params[] = 'fq=facetype:'.$threadType;
                $params[] = 'wt=phps';
                $params[] = 'qt=shiksha';

                $params[] = 'fq=question_created_time_date:['.FILTER_OLD_QUESTION_DATE.'%20TO%20NOW]';

                $params[] = 'fq=question_answers_count:'.urlencode('[1 TO *]');
                if($inputParams['excludeThreads'])
                    $params[] = 'fq=-question_thread_id:('.urlencode(implode(" ", $inputParams['excludeThreads'])).")";

                // boosting params
                if($objectiveTagsBoostingStr){
                  $params[] = 'bq=thread_tags_objective:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
                  $params[] = 'bq=thread_tags_manual:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
                }
                
                if($inputParams['background_tags']){
                  $params[] = 'bq=thread_tags_background:('.urlencode(implode(" ",$inputParams['background_tags'])).')^'.THREAD_BOOST_LEVEL_2;
                }

                if($inputParams['highlevel_objective_manual_tags']){
                  $params[] = 'bq=thread_tags_objective:('.urlencode(implode(" ",$inputParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
                  $params[] = 'bq=thread_tags_manual:('.urlencode(implode(" ",$inputParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
                }
                
                $params[] = 'bf=ord(question_quality_score)^0.1';
                $params[] = 'bf=recip(rord(question_created_time_date),1,100,100)^10';
                $params[] = 'fl=*';
        }
        else if($threadType == "discussion"){

                if(!$searchByQuestionId){
                    $params[] = 'q='.$text;
                }else{
                    $params[] = 'q=*:*';
                    $params[] = 'fq=discussion_thread_id:'.$text;
                }
                
                $params[] = 'start=0';
                $params[] = 'rows='.$limit;
                $params[] = 'fq=facetype:'.$threadType;
                $params[] = 'wt=phps';
                $params[] = 'qt=shiksha';

                $params[] = 'fq=discussion_comment_count:'.urlencode('[1 TO *]');
                if($inputParams['excludeThreads'])
                    $params[] = 'fq=-discussion_thread_id:('.urlencode(implode(" ", $inputParams['excludeThreads'])).")";

                // boosting params
                if($objectiveTagsBoostingStr){
                  $params[] = 'bq=thread_tags_objective:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
                  $params[] = 'bq=thread_tags_manual:('.urlencode($objectiveTagsBoostingStr).')^'.THREAD_BOOST_LEVEL_1;
                }
                
                if($inputParams['background_tags']){
                  $params[] = 'bq=thread_tags_background:('.urlencode(implode(" ",$inputParams['background_tags'])).')^'.THREAD_BOOST_LEVEL_2;
                }

                if($inputParams['highlevel_objective_manual_tags']){
                  $params[] = 'bq=thread_tags_objective:('.urlencode(implode(" ",$inputParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
                  $params[] = 'bq=thread_tags_manual:('.urlencode(implode(" ",$inputParams['highlevel_objective_manual_tags'])).')^'.THREAD_BOOST_LEVEL_2;
                }
                
                $params[] = 'bf=ord(discussion_quality_score)^'.THREAD_BOOST_LEVEL_2;
                $params[] = 'bf=recip(rord(discussion_created_time),1,100,100)^100';
                $params[] = 'fl=*';
        }

        // prepare the final solr url
        $solrUrl = SOLR_NEW_INSTI_SELECT_URL_BASE.implode("&", $params);

        return $solrUrl;
    }

    function escapeSolrValue($string)
    {
        $match = array('and');
        $replace = array('');
        $string = str_ireplace($match, $replace, $string);
        $string = urlencode($string);

        return $string;
    }

}
?>
