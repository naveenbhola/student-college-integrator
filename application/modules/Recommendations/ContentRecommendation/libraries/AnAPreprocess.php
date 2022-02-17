<?php
/**
 * AnAPreprocess Library Class
 *
 *
 * @package     ContentRecommendation
 * @subpackage  Libraries
 *
 */

class AnAPreprocess {

    private $_CI;
    private $anarecommendmodel;
    
    private $debugFlag = false;
    private $debugData = array();

    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->anarecommendmodel = $this->_CI->load->model('ContentRecommendation/anarecommendationmodel');
        $this->_CI->load->helper('ContentRecommendation/recommend');
    }

    public function updateInstituteAnARecommendation($timeWindow){

        list($startTime,$endTime) = explode(';', $timeWindow);

        $deleteQueryFlag = $this->_deleteInstituteAnARecommendation($startTime,$endTime);
        
        $queue = array();
        $queue = $this->_computeAnAQueue($startTime,$endTime);
        
        // fetch details
        $data = $this->prepareAnADataForInstitute($queue);
        
        $updateQueryFlag = $this->anarecommendmodel->updateAnARecommendation($data);

        return ($deleteQueryFlag && $updateQueryFlag);
    }
    
    private function _deleteInstituteAnARecommendation($startTime,$endTime){
        $ret = $this->anarecommendmodel->deleteInstituteAnARecommendation($startTime,$endTime);
        return $ret;
    }
    
    private function _computeAnAQueue($startTime,$endTime){
        
        $contentId = $this->anarecommendmodel->getNewlyTagged($startTime,$endTime);
        $msgId = $this->anarecommendmodel->getNewlyAnswered($startTime,$endTime);
        $threadId = $this->anarecommendmodel->getUpdatedThreads($startTime,$endTime);

        $queue = array();
        $queue = array_unique(array_merge($contentId,$msgId,$threadId));

        return $queue;
    }

    public function prepareAnADataForInstitute($msgIdQueue){
        
        if(count($msgIdQueue) <= 0 || !is_array($msgIdQueue)){
            return array();
        }

        $contentIdTagType = $this->anarecommendmodel->getInstituteAndTagType($msgIdQueue);
        
        $allIds = array_keys($contentIdTagType);   

        $contentId = $this->anarecommendmodel->filterContentByStatus($allIds);
        
        $msgIds = array_keys($contentId);   

        $threadQuality = $this->anarecommendmodel->getThreadQuality($msgIds);
        
        $questionTime = $this->anarecommendmodel->getTimeOfRecentContentOnThreads($msgIds);
        
        $threadTagTypeCount = $this->anarecommendmodel->getThreadTagTypeCount($msgIds);

        $threadCAFlag = $this->anarecommendmodel->getThreadCAFlag($msgIds);
        
        $threadDetails = array();
        
        foreach ($msgIds as $value) {
            
            $threadDetails[$value]['msgId'] = $value;
            $threadDetails[$value]['threadQualityScore'] = $threadQuality[$value];
            $threadDetails[$value]['threadRecentTime'] = $questionTime[$value];
            $threadDetails[$value]['tagCount'] = array_sum($threadTagTypeCount[$value]);
            
            if($contentId[$value]['contentType']=='user'){
                $threadDetails[$value]['contentType'] = 'question';
                $threadDetails[$value]['CA'] = $threadCAFlag[$value];
            }
            else{
                $threadDetails[$value]['contentType'] = 'discussion';
                $threadDetails[$value]['CA'] = 0;  // discussion
            }
        }

        $dbUpdate = array();
        foreach ($threadDetails as $key => $value) {
            
            foreach ($contentIdTagType[$key] as $mId=>$instituteTag) {
                $value['instituteId'] = $mId;
                $value['tagType'] = $instituteTag;
                $dbUpdate[] = $value;
            }
        }

        return $dbUpdate;
    }

    function insertCustomTaggedANA($queue){
        $data = $this->prepareAnADataForInstitute($queue); // $queue is the array of msgId
        $updateQueryFlag = $this->anarecommendmodel->updateAnARecommendation($data);
        return $updateQueryFlag;
    }

}

?>


    