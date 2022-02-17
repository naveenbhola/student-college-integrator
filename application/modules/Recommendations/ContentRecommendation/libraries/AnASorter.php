<?php

class AnASorter
{
    private static $sortFactors = array(
        'additive' => array(
            'THREAD_QUALITY',
            'INSTITUTE_TAG_TYPE',
            'INSTITUTE_TAG_SPECIFICITY',
            'ANSWERED_BY_CA'    
        ),
        'multiplicative' => array(
            'RECENCY'
        )
    );
    
    private $_CI;
    private $items;
    
    private $recencyScorer;
    private $threadQualityScorer;

    private $sortWeights;
    
    function __construct()
    {
        $this->_CI = & get_instance();
        
        $this->_CI->config->load('ContentRecommendation/AnARecommendation_config');
        $this->sortWeights = $this->_CI->config->item('sortWeights');
        
        $this->_CI->load->library('ContentRecommendation/RecencyScorer');
        $this->recencyscorer = new RecencyScorer($this->sortWeights['halfLifeForThreads']);
        
        $this->_CI->load->library('ContentRecommendation/ThreadQualityScorer');
        $this->threadqualityscorer = new ThreadQualityScorer();
    }
    
    public function sort($items,$factor = 'RELEVANCY')
    {
        if(count($items) <= 0 || !is_array($items)){
            return array();
        }

        $this->items = $items;
        if($factor==null || $factor=='RELEVANCY' || $factor=='THREAD_QUALITY'){
            $threadQualityList = array();
            foreach ($this->items as $i) {
                $threadQualityList[] = $i['threadQualityScore'];
            }
            $this->threadqualityscorer->setThreadQualityList($threadQualityList);
        }
        $itemScores = array();

        if($factor==null || $factor=='RELEVANCY') {
        foreach($items as $itemId => $item) {
            $itemScores[$itemId] = $this->computeItemScore($item);
        }
        }
        else{
            foreach($items as $itemId => $item) {
                $itemScores[$itemId] = $this->computeItemScoreForFactor($item, $factor);
            }
        }

        arsort($itemScores,SORT_NUMERIC);

        return array_keys($itemScores);
    }
    
    private function computeItemScore($item)
    {

        $score = 0;
        foreach(self::$sortFactors['additive'] as $factor) {
            $score += $this->computeItemScoreForFactor($item, $factor);
        }
        foreach(self::$sortFactors['multiplicative'] as $factor) {
            $score *= $this->computeItemScoreForFactor($item, $factor);
        }
        return $score;
    }
    
    private function computeItemScoreForFactor($item, $factor)
    {
        switch($factor) {
            case 'RECENCY':
                return $this->computeRecencyScore($item);
            case 'THREAD_QUALITY':
                return $this->computeThreadQualityScore($item);
            case 'INSTITUTE_TAG_TYPE':
                return $this->computeInstituteTagTypeScore($item);
            case 'INSTITUTE_TAG_SPECIFICITY':
                return $this->computeInstituteTagSpecificityScore($item);
            case 'ANSWERED_BY_CA':
                return $this->computeAnsweredByCAScore($item);    
        }
    }
    
    private function computeRecencyScore($item)
    {
       return $this->recencyscorer->score($item['threadRecentTime'])*$this->sortWeights['recency'];
    }
    
    private function computeThreadQualityScore($item)
    {
        
        return $this->threadqualityscorer->score($item['threadQualityScore'])*$this->sortWeights['threadQuality'];
    }
    
    private function computeInstituteTagTypeScore($item)
    {
        $score = 0;
        
        $tagAsParent = strpos($item['tagType'], 'parent') !== false ? true : false;
        $tagAsBackground = strpos( $item['tagType'], 'background') !== false ? true : false;  
        
        if(!$tagAsParent) {
            $score += $this->sortWeights['asParent'] * 1;
        }
        if(!$tagAsBackground) {
            $score += $this->sortWeights['asBackground'] * 1;
        }
        return $score;
    }
    
    private function computeInstituteTagSpecificityScore($item)
    {
        $numTagsAttached = $item['tagCount'];
        $tagSpecificity = $numTagsAttached == 0 ? 0 : (1/$numTagsAttached);
        $score = $this->sortWeights['tagSpecificity'] * $tagSpecificity;
        return $score;
    }
    
    function computeAnsweredByCAScore($item)
    {
        $answeredByCA = $item['CA'] == 1 ? true : false;
        $score = $answeredByCA ? $this->sortWeights['CA'] * 1 : 0;
        return $score;
    }
}