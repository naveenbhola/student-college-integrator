<?php

class CommonReviewWidget extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("common/CollegeWidgetModel");
        $this->load->model("CollegeReviewForm/collegereviewmodel");
    }
    
    
    public function getRandomTiles()
    {
       $randomTilesArray = $this->CollegeWidgetModel->getTwoRandomTiles();
       return $randomTilesArray;
    }
    
    public function getTilesByHierarchy($coursePageData)
    {
       $cachekey = "getTilesByHierarchy".$coursePageData['courseHomeId'];
       $cacheLib = $this->load->library('cacheLib');
       $res = $cacheLib->get($cachekey);
       if($res == 'ERROR_READING_CACHE' || empty($res)){
            unset($coursePageData['oldsbCatId']);
            $tilesData = $this->collegereviewmodel->getTilesByHierarchy($coursePageData,6);
            $tilesArray = array();
            foreach($tilesData as $key=>$tile){
                $tilesArray[$key]['seoUrl'] = addingDomainNameToUrl(array('url'=>$tile['seoUrl'],'domainName'=>SHIKSHA_HOME));
                $tilesArray[$key]['title']  = $tile['title'];
            }
            $cacheLib->store($cachekey,$tilesArray,10800);
            return $tilesArray;
       }
       else {
            return $res; 
       }
    }

    public function deleteCacheForCourseHomePageReviewsArticles(){
      $this->coursePageCommonLib = $this->load->library('coursepages/CoursePagesCommonLib');
      $cacheLib = $this->load->library('cacheLib');
      $coursePagesCache = $this->load->library('coursepages/cache/CoursePagesCache');
      $this->courseHomePageList =  $this->coursePageCommonLib->getCourseHomePageDictionary(0);
      $courseHomePageIds = array_keys($this->courseHomePageList);
      foreach ($courseHomePageIds as $id) {
        $cacheLib->clearCacheForKey("getTilesByHierarchy".$id);
        $coursePagesCache->deleteArticlesData($id);
        $coursePagesCache->deleteSlideInfo($id);
        $coursePagesCache->deleteSlideSlotId($id);
        $coursePagesCache->deleteCourseHomePageDictionary();
      }
    }
    
    /**
     * Function to render / load the College Review Widget on Mobile / Desktop
     *
     * @param string $where where to load the widget whether on mobile or on desktop
     *
     */
    public function homePageWidget($where='mobile',$widgetForPage){
        
        $data['widgetForPage'] = $widgetForPage;
        if($where == "mobile"){
           $this->load->view('mcommon5/homePageCollegeReviewWidget',$data);
        }
        
    }
    
}
