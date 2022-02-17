<?php

class articleWidgets extends MX_Controller
{
    private $Blog_client;
    
    function index($widgetType, $categoryID, $subCatgoryID = 1, $countryID, $regionID)
    {        
        //echo "Total Recs: ".$widgetType; echo ", cat id: ".$categoryID; echo ", subcatid: ".$subCatgoryID; echo ", countryid: ".$countryID;

        switch($widgetType) {
            case 1:
                    $widget_type_value = 'quick_links';
                    $viewFile = 'articleWidgets/quickLinks';
                break;

            case 2:
                    $widget_type_value = 'latest_news';
                    $viewFile = 'articleWidgets/latestNews';
                    
                break;

            default:
                    $widget_type_value = 'quick_links';
                    $viewFile = 'articleWidgets/quickLinks';
                break;
        }

        /*
        if($subCatgoryID == 0)
            $operatedCategoryID = $categoryID;
        else
            $operatedCategoryID = $subCatgoryID;
         * 
         */
        
        $this->Blog_client = $this->load->library('Blog_client');
        // $latestNews = $this->Blog_client->getLatestNewsWidgetsData($operatedCategoryID);
        $articleWidgetsData = $this->Blog_client->getArticleWidgetsData($widget_type_value, $categoryID, $subCatgoryID, $countryID, $regionID);
        // $latestNews = json_decode(base64_decode($latestNews));
        // echo "Total Recs: ".count($articleWidgetsData)."<pre>";print_r($articleWidgetsData); die;
        
        if(count($articleWidgetsData) && is_array($articleWidgetsData))
        {
            $data['articleWidgetsData'] = $articleWidgetsData;
            $data['countryID'] = $countryID;
            $data['categoryID'] = $categoryID;
	    $this->load->builder('CategoryBuilder','categoryList');
	    $categoryBuilder = new CategoryBuilder;
            $categoryBuilderRepository = $categoryBuilder->getCategoryRepository();
	    
	    
	    $categoryName = $categoryBuilderRepository->find($categoryID)->getName();
	    if($categoryBuilderRepository->find($categoryID)->isTestPrep()) {
		if($subCatgoryID == 1) {
		    $categoryName = ' Entrance Exams';
		}
		else if($subCatgoryID != 1) {
		    $categoryName = $categoryBuilderRepository->find($subCatgoryID)->getName();
		    $categoryName = str_replace('Management', 'MBA', $categoryName);
		    $categoryName = str_replace('Exams', '', $categoryName);
		    $categoryName .= ' Exams';
		}
	    }
            $data['categoryName'] = $categoryName;
	    
            echo $this->load->view($viewFile, $data,TRUE);
        }
    }
    
    function getStudyAbroadStepsWidget($categoryId, $regionId, $countryId, $pageName,$categoryName){
        $this->Blog_client = $this->load->library('Blog_client');
        if($countryId>2){
            $location_id = $countryId;
            $location_type = 'country';
        }else{
            $location_id = $regionId;
            $location_type = 'region';
        }
        $data = $this->Blog_client->getStudyAbroadStepsWidget($categoryId, $location_id, $location_type);
        $displayData['data'] = $data;
        $displayData['pageName'] = $pageName;
        $displayData['categoryName'] = $categoryName;
        if(count($data) && is_array($data)){
             $this->load->view('articleWidgets/stepsWidget',$displayData);
        }
    }
    function getStudyAbroadSnippetWidget($categoryId, $regionId, $countryId){
        $this->load->helper('shikshautility_helper');
        $this->Blog_client = $this->load->library('Blog_client');
        if($countryId>2){
            $location_id = $countryId;
            $location_type = 'country';
        }else{
            $location_id = $regionId;
            $location_type = 'region';
        }
        $data = $this->Blog_client->getStudyAbroadSnippetWidget($categoryId, $location_id, $location_type);
        $displayData['data'] = $data;
        $this->load->model('enterprise/studyabroadwidgetmodel');
	$studyabroadwidgetmodel = new studyabroadwidgetmodel();
        $help_array = json_decode($studyabroadwidgetmodel->renderCarouselDeatils(),true);
        //_p($help_array);
        if(count($data) && is_array($data)){
            //_p($displayData);
            $displayData['showArticle'] = 'YES';
        } else {
	    $displayData['showArticle'] = 'NO';	
	}
       if(is_array($help_array) && !empty($help_array[0])) {
	    $displayData['help_image'] = $help_array[0]['registrationBannerURL'];
            $displayData['registrationLayerTitle'] = $help_array[0]['registrationLayerTitle'];
            $displayData['registrationLayerMsg'] = $help_array[0]['registrationLayerMsg']; 
       } else {
	    $displayData['help_image'] = "/public/images/help-img.jpg";	
       }
       $validity = $this->checkUserValidation();
       $displayData['user_logged_in'] = 'FALSE';
       if($validity != 'false') {
	    $displayData['user_logged_in'] = 'TRUE';
            $displayData['help_image'] = "/public/images/ask-expert-banner.jpg";		
       }
       $this->load->view('articleWidgets/snippetsWidget',$displayData);
    }
}
