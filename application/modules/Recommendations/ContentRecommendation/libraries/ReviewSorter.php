<?php

class ReviewSorter
{
    private static $sortFactors = array(
        'additive' => array(
            'THREAD_QUALITY',
            'USER_VOTES',
            'ANONYMITY',
            'GRADUATION_YEAR',
            'COURSE_POPULARITY'    
        ),
        'multiplicative' => array(
            'RECENCY'
        )
    );
    
    private $_CI;
    private $items;
    private $sortWeights;
    private $maxCoursePopularity;
    
    private $recencyScorer;
    private $UserVoteScorer;
    
    function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->config->load('ContentRecommendation/ReviewRecommendation_config');
        $this->sortWeights = $this->_CI->config->item('sortWeights');

        $this->_CI->load->library('ContentRecommendation/RecencyScorer');
        $this->recencyscorer = new RecencyScorer($this->sortWeights['halfLifeForThreads']);

        $this->_CI->load->library('ContentRecommendation/UserVoteScorer');
        $this->UserVoteScorer = new UserVoteScorer();
    }
    
    public function sort($items,$factor = 'RELEVANCY')
    {
        if(count($items) <= 0 || !is_array($items)){
            return array();
        }

        $this->items = $items;
        $this->setMaxCoursePopularity();

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
            case 'USER_VOTES':
                return $this->computeUserVotesScore($item);
            case 'ANONYMITY':
                return $this->computeAnonymityScore($item);
            case 'GRADUATION_YEAR':
                return $this->computeGraduationYearScore($item);
            case 'COURSE_POPULARITY':
                return $this->computeCoursePopularityScore($item);    
        }
    }
    
    private function computeRecencyScore($item)
    {
       return $this->recencyscorer->score($item['creationDate'])*$this->sortWeights['recency'];
    }
    
    private function computeThreadQualityScore($item)
    {
        $quality = $item['reviewQuality'];
        $score = 0;
        if($quality=='average'){
            $score = 0;
        }
        else if($quality=='good'){
            $score = 0.5;
        }
        else if($quality=='excellent'){
            $score = 1;
        }
        return ($this->sortWeights['threadQuality']*$score);
    }
    
    private function computeUserVotesScore($item)
    {
        $score = $this->UserVoteScorer->score($item)*$this->sortWeights['helpfulScore'];
        return $score;
    }
    
    private function computeAnonymityScore($item)
    {
        $anonymousFlag = $item['anonymousFlag']=='YES'?1:0;
        return $this->sortWeights['anonymousFlag']*$anonymousFlag;
    }

    private function computeGraduationYearScore($item)
    {
        $yearOfGraduationScore = 0;
        $yearDiff = abs(date('Y',strtotime($item['creationDate']))-$item['yearOfGraduation'])+1;
        
        if($yearDiff!=0 && is_numeric($yearDiff)){
            $yearOfGraduationScore = 1/$yearDiff;
        }
        return $this->sortWeights['yearOfGraduation']*$yearOfGraduationScore;
    }

    private function computeCoursePopularityScore($item)
    {
        $coursePopularityScore = 0;
        if($item['coursePopularity'] && $this->maxCoursePopularity>0){
            $coursePopularityScore = $item['coursePopularity']/$this->maxCoursePopularity;
        }
        return $this->sortWeights['coursePopularity']*$coursePopularityScore;
    }

    private function setMaxCoursePopularity()
    {
       $maxCoursePopularity = max(array_map(function($entity){return $entity['coursePopularity'];}, $this->items));
       $this->maxCoursePopularity = (is_numeric($maxCoursePopularity) && $maxCoursePopularity > 0)?$maxCoursePopularity:0;
    }
}