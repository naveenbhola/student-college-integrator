<?php
class HomePageServer extends MX_Controller {
    function index(){
        //load XML RPC Libs
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->helper('url');
        //Define the web services method
        $config['functions']['sGetHomeMainCategoryPage'] = array('function' => 'HomePageServer.sGetHomeMainCategoryPage');
        $config['functions']['sGetHomeMainCountryPage'] = array('function' => 'HomePageServer.sGetHomeMainCountryPage');
        $config['functions']['sGetHomeMainTestPrepPage'] = array('function' => 'HomePageServer.sGetHomeMainTestPrepPage');
        $config['functions']['sGetProductsCountForHomePages'] = array('function' => 'HomePageServer.sGetProductsCountForHomePages');
        $args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
    }

    function sGetHomeMainCategoryPage($request){
        $this->load->model('CategoryListModel');
        $this->load->model('ArticleModel');
        $this->load->database();

        $catTree= $this->CategoryListModel->getCategoryTree($this->db);
        $snippet = $this->ArticleModel->getArticlesWithImageForCriteria($this->db, array('categoryId'=> 1), 'category');

        foreach($catTree as $categoryId => $categoryStuff) {
            $catTree[$categoryId]['snippets'][] = $snippet[$categoryId];
        }
        $data['categoryTree'] = $catTree;
        $response = json_encode($data);
        return $this->xmlrpc->send_response($response);
    }

    function sGetHomeMainCountryPage($request){
        $this->load->model('CategoryListModel');
        $this->load->model('ArticleModel');
        $this->load->database();
        $countryMap= $this->CategoryListModel->getCountryList($this->db);
        $snippet = $this->ArticleModel->getArticlesWithImageForCriteria($this->db, array('countryId'=> 1), 'country');
        foreach($countryMap as $countryId => $countryStuff) {
            $countryMap[$countryId]['snippets'][] = $snippet[$countryId];
        }
        $data['countryMap'] = $countryMap;
        $response = json_encode($data);
        return $this->xmlrpc->send_response($response);
    }

    function sGetHomeMainTestPrepPage($request){
        $this->load->model('CategoryListModel');
        $this->load->model('ArticleModel');
        $this->load->database();
        $testPrepExams= array();
        $snippets = $this->ArticleModel->getArticlesWithImageForCriteria($this->db, array('type'=>'exam'), 'exam');
        $examsList = $this->ArticleModel->getExamsForProducts($this->db);
        /*
           foreach($snippets as $snippetId => $snippet) {
           $testPrepExams[$snippetId]['snippet'] = $snippet[0];
           }
         */
        foreach($examsList  as $exam){
            if($exam['parentId'] == 0) {
                $testPrepExams[$exam['blogId']]['snippet'] = $snippets[$exam['blogId']][0];
            }else {
                $testPrepExams[$exam['parentId']]['examList'][] = $exam;
            }
        }
        $data['testPrepExams'] = $testPrepExams;
        $response = json_encode($data);
        return $this->xmlrpc->send_response($response);
    }

    function sGetProductsCountForHomePages($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $criteria=$parameters['1'];
        $this->load->database();
        $this->load->model('EventModel');
        $this->load->model('ArticleModel');
        $this->load->model('QnAModel');
        $this->load->model('ListingModel');
        $productsCount = array();
        $dbHandle = $this->db;
        try{
            $productsCount['totalEventCount'] = $this->EventModel->getTotalEventCountForCriteria($dbHandle, $criteria);
        } catch(Exception $error){ 
            $productsCount['totalEventCount'] = array();
            error_log("COUNT WIDGET Events::". $error->getMessage()); 
        }
        try{
            $productsCount['totalArticleCount'] = $this->ArticleModel->getTotalArticlesCountForCriteria($dbHandle, $criteria);
        } catch(Exception $error){
            $productsCount['totalArticleCount'] = array();
            error_log("COUNT WIDGET Articles::". $error->getMessage()); 
        }
        try{
            $listingCriteria = $criteria;
            $listingCriteria['listingType'] = 'institute';
            $tempTotalInsCount = $this->ListingModel->getTotalListingCountForCriteria($dbHandle, $listingCriteria);
            $productsCount['totalInstituteCount'] = $tempTotalInsCount['numInstitutes'];
        } catch(Exception $error){ 
            $productsCount['totalInstituteCount'] = array();
            error_log("COUNT WIDGET Institutes::". $error->getMessage()); 
        }
        try{
            $listingCriteria['listingType'] = 'course';
            $tempTotalInsCount = $this->ListingModel->getTotalListingCountForCriteria($dbHandle, $listingCriteria);
            $productsCount['totalCourseCount'] = $tempTotalInsCount['numCourses'];
        } catch(Exception $error){ 
            $productsCount['totalCourseCount'] =array();
            error_log("COUNT WIDGET Courses::". $error->getMessage()); 
        }
        try{
            if(!isset($listingCriteria['exam'])){
                $listingCriteria['listingType'] = 'scholarship';
                $tempTotalInsCount = $this->ListingModel->getTotalListingCountForCriteria($dbHandle, $listingCriteria);
                $productsCount['totalScholarshipCount'] = $tempTotalInsCount['numScholarships'];
                error_log("ASHISH:: OLA OLA OLA");
            } else {
                $productsCount['totalScholarshipCount'] = array();
            }
        } catch(Exception $error){ 
            $productsCount['totalScholarshipCount'] = array();
            error_log("COUNT WIDGET ScholarShips::". $error->getMessage()); 
        }
        try{
            $productsCount['totalQnACount'] = $this->QnAModel->getTotalQnACountForCriteria($dbhandle, $criteria);
        } catch(Exception $error){ 
            $productsCount['totalQnACount'] =array();
            error_log("COUNT WIDGET QNA::". $error->getMessage()); 
        }
        $response = json_encode($productsCount);
        return $this->xmlrpc->send_response($response);
    }
}
?>
