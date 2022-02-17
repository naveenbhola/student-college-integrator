<?php
class downloadGuideWidgetSnippet{
    private $contentId;
    private $saContentModel;
    private $ci;
    public function __construct($contentId,$saContentModel) {
        $this->contentId = $contentId;
        $this->ci = & get_instance();
        $this->saContentModel = $saContentModel;
        if(empty($this->saContentModel)){
            $this->CI->load->model('blogs/sacontentmodel');
            $this->sacontentmodel = new sacontentmodel();
        }
    }
    
    public function getGuidesForDownloadWidget($noOfGuides=3,$mentionedIds){
        $popularGuidesOfSameCountry      = $this->saContentModel->getPopularContentofSameCountry($this->contentId,$mentionedIds);
        $popularGuidesOfSameLDBCourse    = $this->saContentModel->getPopularContentofSameLDBCourse($this->contentId,$mentionedIds);
        $popularGuidesOfSameContentCourse= $this->saContentModel->getPopularContentofSameContentCourse($this->contentId,$mentionedIds);       
        
        $contentIds = $this->mergeANDSortRecommendations($popularGuidesOfSameCountry,$popularGuidesOfSameLDBCourse,$popularGuidesOfSameContentCourse);
        $contentIds = array_slice($contentIds,0,$noOfGuides);
        if(count($contentIds)<$noOfGuides){
            $popularDownloadableContent = $this->saContentModel->getDownloadablePopularContent();
            $i = 0;
            while(count($contentIds)<$noOfGuides && $i<count($popularDownloadableContent)){
                if(!in_array($popularDownloadableContent[$i]['content_id'],$contentIds) && $popularDownloadableContent[$i]['content_id']!=$this->contentId){
                    $contentIds[] = $popularDownloadableContent[$i]['content_id']; 
                }
                $i++;
            }
        }
        return array_slice($contentIds,0,$noOfGuides);
    }
    public function mergeANDSortRecommendations($a,$b,$c){
        $returnData = array();
        foreach($a as $values){
            if($this->contentId!=$values['content_id']){
                $returnData[$values['content_id']] = $values['popularityCount'];
            }
        }
        foreach($b as $values){
            if($this->contentId!=$values['content_id']){
                $returnData[$values['content_id']] = $values['popularityCount'];
            }
        }
        foreach($c as $values){
            if($this->contentId!=$values['content_id']){
                $returnData[$values['content_id']] = $values['popularityCount'];
            }
        }
        arsort($returnData);
        return array_keys($returnData);
    }
}
