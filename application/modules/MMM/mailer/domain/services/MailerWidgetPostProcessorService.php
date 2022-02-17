<?php

class MailerWidgetPostProcessorService
{
    function __construct()
    {
        
    }
    
    function doPostProcessing($userWidgetData)
    {

        if(!is_array($userWidgetData) || empty($userWidgetData)) { 
            return $userWidgetData;
        }
        
        foreach($userWidgetData as $userId => $widgetData) {
            $widgetData = $this->_setTitleArticle($widgetData); 
            $userWidgetData[$userId] = $widgetData;
        }
        unset($widgetData);
        return $userWidgetData;
    }
    
    /**
     * TITLE ARTICLE
     * i.e. article to be shown in subject of MContent mailer
     * First preference to must read title article
     * if there is no must read widget, the pick title article of latest news widget
     */
    private function _setTitleArticle($widgetData)
    {
        if($widgetData['mustread_titleArticle']) {
            $widgetData['titleArticle'] = $widgetData['mustread_titleArticle'];
            
        }
        else if($widgetData['article_titleArticle']) {
            $widgetData['titleArticle'] = $widgetData['article_titleArticle'];
        }
        
        unset($widgetData['mustread_titleArticle']);
        unset($widgetData['article_titleArticle']);
        
        return $widgetData;
    }
}