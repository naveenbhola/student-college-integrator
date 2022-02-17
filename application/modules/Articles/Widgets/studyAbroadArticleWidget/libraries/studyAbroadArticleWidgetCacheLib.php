<?php

class studyAbroadArticleWidgetCacheLib extends Cache{
    
    private $ttl = 14400;
    public function getListingWidgetsDataOnArticles($blogId){
        if(empty($blogId)){
            return;
        }
        $data = $this->get('blog',$blogId);
        $data = json_decode($data,true);
        return $data;
    }
    public function saveListingWidgetsDataOnArticles($blogId,$data){
        $data = json_encode($data);
        $this->store('blog',$blogId,$data,$this->ttl);        
    }
}
