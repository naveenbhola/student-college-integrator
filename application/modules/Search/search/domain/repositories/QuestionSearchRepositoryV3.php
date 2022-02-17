<?php

class QuestionSearchRepositoryV3 extends EntityRepository {

    public function __construct($request){
        $this->CI = & get_instance();

        $this->request = $request;
        $this->solrClient = $this->CI->load->library('search/Solr/SolrClient');

        $this->CI->load->config("nationalCategoryList/nationalConfig");
        $this->AnALibrary = $this->CI->load->library("messageBoard/AnALibrary");

        $this->CI->load->helper('ana');
    }

    public function getRawSearchData($tab) {
        //get search keyword
        $keyword = $this->request->getSearchKeyword();
        
        if(empty($keyword)) {
            return false;
        }
        
        $solrRequestData = array();
        $solrRequestData['keyword'] = $keyword;

        //get page details
        $solrRequestData['pageLimit'] = $this->request->getPageLimit();
        $solrRequestData['pageNum'] = $this->request->getCurrentPageNum();

        //set filter by
        $solrRequestData['filterBy'] = $this->request->getFilterBy();
        $solrRequestData['currentTab'] = $tab;

        $solrResults = $this->solrClient->getQuestionsAndTags($solrRequestData);

        if(empty($solrResults['data'])) {
            return false;
        } else {
            // If tab is not known yet, set 1st tab, based on data availability
            // OR
            // If tab in url does not have data, redirect url to 1st tab, based on data availability
            if(empty($tab) || empty($solrResults['data']['questions_'.$tab])) {
                if(!empty($solrResults['data']['questions_answered'])) {
                    $tab = 'answered';
                } else if (!empty($solrResults['data']['questions_topics'])) {
                    $tab = 'topics';
                } else if (!empty($solrResults['data']['questions_unanswered'])) {
                    $tab = 'unanswered';
                }

                $result['updateTabInURL'] = $this->request->getTabURL(array('tab' => $tab));
            }
            
            $this->request->setCurrentTab($tab);
            
            //load current tab's data
            $result['tupleData'] = $this->loadTupleData($solrResults);
            
            //get all tab's URL, if they have data
            if(!empty($solrResults['data']['questions_answered'])) {
                $result['tabURL']['answered'] = $this->request->getTabURL(array('tab' => 'answered'));
            }
            if(!empty($solrResults['data']['questions_unanswered'])) {
                $result['tabURL']['unanswered'] = $this->request->getTabURL(array('tab' => 'unanswered'));
            }
            if(!empty($solrResults['data']['questions_topics'])) {
                $result['tabURL']['topics'] = $this->request->getTabURL(array('tab' => 'topics'));
            }
        }
        
        return $result;
    }

    private function loadTupleData($solrResults) {
        $tab = $this->request->getCurrentTab();

        switch ($tab) {
            case 'answered':
                $result = $this->loadAnsdQuestionData($solrResults['data']['questions_answered']);
                break;
            
            case 'unanswered':
                $result = $this->loadUnansdQuestionData($solrResults['data']['questions_unanswered']);
                break;

            case 'topics':
                $result = $this->loadTagData($solrResults['data']['questions_topics']);
                break;
        }

        return $result;
    }

    private function loadAnsdQuestionData($questions) {
        $questionIds = array_keys($questions['result']);
        $questionData = $this->AnALibrary->getDetailsForMultipleQuestion($questionIds);

        $userId = $this->request->getUserId();
        if($userId != -1) {
            $followedQuestns = $this->AnALibrary->checkWhetherEntitiesFollowedByUser($questionIds, 'question', $userId);
            foreach ($followedQuestns as $key => $value) {
                $followedQuestnsArr[$value] = $value;
            }
        }

        foreach ($questionIds as $key => $questionId) {
            if(empty($questionData[$questionId]['answer']['ansTxt'])) {
                unset($questions['result'][$questionId]);
                continue;
            }
            $questions['result'][$questionId] = $questionData[$questionId];
            
            if(strlen($questionData[$questionId]['answer']['ansTxt']) > 650) {
                $questions['result'][$questionId]['answer']['ansTxt'] = substr(sanitizeAnAMessageText($questionData[$questionId]['answer']['ansTxt'], 'answer'), 0, 647).'...';
                $questions['result'][$questionId]['answer']['isLongAns'] = 1;
            } else {
                $questions['result'][$questionId]['answer']['ansTxt'] = sanitizeAnAMessageText($questionData[$questionId]['answer']['ansTxt'], 'answer');
            }

            if(strlen($questionData[$questionId]['answer']['userData']['aboutMe']) > 35) {
                $questions['result'][$questionId]['answer']['userData']['aboutMe'] = substr($questionData[$questionId]['answer']['userData']['aboutMe'], 0, 32).'...';
            }

            if(strlen($questionData[$questionId]['answer']['userData']['levelName']) > 35) {
                $questions['result'][$questionId]['answer']['userData']['levelName'] = substr($questionData[$questionId]['answer']['userData']['levelName'], 0, 32).'...';
            }

            if(empty($questionData[$questionId]['answer']['userData']['picUrl'])) {
                $questions['result'][$questionId]['answer']['userData']['initialLetter'] = ucfirst(substr(trim($questionData[$questionId]['answer']['userData']['firstname']), 0, 1));
            }

            if($followedQuestnsArr[$questionId]) {
                $questions['result'][$questionId]['follow'] = 1;
            }
        }

        //_p($questions); die;
        return $questions;
    }

    private function loadUnansdQuestionData($questions) {
        uasort($questions['result'], array('QuestionSearchRepositoryV3', 'sortByCreationDate'));
        
        $questionIds = array_keys($questions['result']);
        
        $userId = $this->request->getUserId();
        if($userId != -1) {
            $followedQuestns = $this->AnALibrary->checkWhetherEntitiesFollowedByUser($questionIds, 'question', $userId);
            foreach ($followedQuestns as $key => $value) {
                $followedQuestnsArr[$value] = $value;
            }
        }
        
        $questionData = $this->AnALibrary->getDetailsForMultipleUnansweredQues($questionIds);

        foreach ($questionIds as $key => $questionId) {
            if(!empty($questionData[$questionId])) {

                $questions['result'][$questionId] = $questionData[$questionId];

                if($followedQuestnsArr[$questionId]) {
                    $questions['result'][$questionId]['follow'] = 1;
                }
            } else {
                unset($questions['result'][$questionId]);
            }
        }

        //_p($questions); die;
        return $questions;
    }

    function sortByCreationDate($a, $b) {
        if($a['creationDate'] < $b['creationDate']) {
            return 1;
        } else {
            return -1;
        }
    }

    private function loadTagData($tags) {
        $tagIds = array_keys($tags['result']);

        $userId = $this->request->getUserId();
        if($userId != -1) {
            $followedTags = $this->AnALibrary->checkWhetherEntitiesFollowedByUser($tagIds, 'tag', $userId);
            foreach ($followedTags as $key => $value) {
                $followedTagsArr[$value] = $value;
            }
        }

        $tagData = $this->AnALibrary->getDetailsForMultipleTag($tagIds);

        if(!isMobileRequest()) {
            $tagAnsdQuesData = $this->AnALibrary->getTop2QuestionBasedOnQualityScore($tagIds);
            //$tagQuesCount = $this->AnALibrary->getQuestionCountForTags($tagIds);
        }
        
        foreach ($tagIds as $key => $tagId) {
            if(!empty($tagData[$tagId])) {
                $tags['result'][$tagId] = $tagData[$tagId];

                // if(!empty($tagQuesCount[$tagId])) {
                //     $tags['result'][$tagId]['questionCount'] = $tagQuesCount[$tagId];
                // }

                if(!empty($tagAnsdQuesData[$tagId])) {
                    $tags['result'][$tagId]['ansdQuesData'] = $tagAnsdQuesData[$tagId];
                }

                if($followedTagsArr[$tagId]) {
                    $tags['result'][$tagId]['follow'] = 1;
                }
            } else {
                unset($tags['result'][$tagId]);
            }
        }

        //_p($tags); die;
        return $tags;
    }
}