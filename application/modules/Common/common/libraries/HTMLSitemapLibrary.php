<?php

/**
 * Class HTMLSitemapLibrary
 * This class handles the logic / algorithm involved in creating Sitemap
 *
 * @see \HTMLSitemap
 */
class HTMLSitemapLibrary
{
    function __construct(){

        $this->CI = & get_instance();
        $this->CI->load->builder('listingBase/ListingBaseBuilder');
        $this->listingBaseBuilder = new ListingBaseBuilder();
        $this->hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
    }

    public function getAllStreamData()
    {   $streamData[]=array();
        $streamObjs = $this->hierarchyRepository->getAllStreams('object');
        foreach ($streamObjs as $streams) {
            $streamUrl = $this->_getUrl($streams->getUrlName(1)); 
            $streamData[] = array("text" => $streams->getName()." Colleges by location >>", "link" => SHIKSHA_HOME."/sitemap/browse-".$streamUrl."-colleges-by-location-s-".$streams->getId());
        }
        return $streamData;
    }
    /**
     * Works on the assumption that either of a stream or a popular course can be mentioned in the sitemap URL
     *
     * @param string $urlText
     * @param \ListingBaseBuilder $listingBaseBuilder
     *
     * @return array Containing the data to be shown on the view
     */
    public function locationSitemap($entityId,$entityType)
    {
        $data               = array();
        switch ($entityType) 
        {
        case 'stream_id':      
            $streamObj                          = $this->hierarchyRepository->findStream($entityId);
            $entityName                         = $streamObj->getName();
            $data['specializationHeading']      = $entityName . " Colleges by Specialization";
            $data['categoryPageCriteria']       =  array('stream_id' => $entityId ,'streamOnlypage'=>1);
        break;

        case 'substream_id':
             $subStreamObj                      = $this->hierarchyRepository->findSubstream($entityId);
             $data['categoryPageCriteria']      = array('stream_id' => $subStreamObj->getPrimaryStreamId(),
                                                   'substream_id'=> $entityId );
             $entityName                        = $subStreamObj->getName();
        break;

        case 'specialization_id':
             $specializationObj                 = $this->hierarchyRepository->findSpecialization($entityId);
             $data['categoryPageCriteria']      =  array('stream_id' => $specializationObj->getPrimaryStreamId(),
                                                  'specialization_id'=> $entityId );
             $entityName                        = $specializationObj->getName();
        break;

        case 'base_course_id':
             $baseCourseReposity                = $this->listingBaseBuilder->getBaseCourseRepository();
             $baseCourseObj                     = $baseCourseReposity->find($entityId);
             $entityName                        = $baseCourseObj->getName();
             $data['categoryPageCriteria']      = array('base_course_id' =>$entityId ,
                                                           'substream_id'=>0,
                                                      'specialization_id'=>0);
             if($entityId==101 || $entityId==10) //MBA || B.tech
             {
                $data['categoryPageCriteria']['education_type']=20; //Fulltime
             }
             if($entityId==86) //M.Tech
             {
                $data['categoryPageCriteria']['stream_id'] =2; //Engineering
             }
        break;

        case 'credential':
             $this->CI->load->library('listingBase/BaseAttributeLibrary');
             $this->baseAttributeLibrary        = new BaseAttributeLibrary();
             $entityNameArray                   = $this->baseAttributeLibrary->getValueNameByValueId($entityId);
             $entityName                        = $entityNameArray[$entityId];
             $data['categoryPageCriteria']      =  array('credential'=>$entityId ,
                                                         'stream_id'=>2,    //Engineering
                                                         'substream_id'=>0,
                                                         'specialization_id'=>0);
        break;

        default:
        break;
        }
        //_p($data['categoryPageCriteria']);die();
        if(!isset($data['categoryPageCriteria']['stream_id']) || empty($data['categoryPageCriteria']['stream_id'])) {
            $data['categoryPageCriteria']['stream_id'] = 0;
        }
        if(!isset($data['categoryPageCriteria']['base_course_id']) || empty($data['categoryPageCriteria']['base_course_id'])) {
            $data['categoryPageCriteria']['base_course_id'] = 0;
        }
        if(!isset($data['categoryPageCriteria']['education_type']) || empty($data['categoryPageCriteria']['education_type'])) {
            $data['categoryPageCriteria']['education_type'] = 0;
        }
        if(!isset($data['categoryPageCriteria']['delivery_method']) || empty($data['categoryPageCriteria']['delivery_method'])) {
            $data['categoryPageCriteria']['delivery_method'] = 0;
        }
        if(!isset($data['categoryPageCriteria']['credential']) || empty($data['categoryPageCriteria']['credential'])) {
            $data['categoryPageCriteria']['credential'] = 0;
        }
        if(!isset($data['categoryPageCriteria']['exam_id']) || empty($data['categoryPageCriteria']['exam_id'])) {
            $data['categoryPageCriteria']['exam_id'] = 0;
        }

        $categoryData                  = $this->findCategoryUrlsByParams($data['categoryPageCriteria']);
        $data['substreams']            = $categoryData['substreams'];
        $data['specializations']       = $categoryData['specializations'];
        $data['categoryText']          = $entityName;
        $data['stateList']             = $categoryData['stateUrls'];
        $data['heading']               = $entityName . " Colleges by Location";
        $data['metroAndPopularCities'] = $categoryData['metroAndPopularCitiesUrl'];
        $data['alphabetWiseCities']    = $categoryData['alphabetWiseCitiesUrl'];
        $data['sitemapPageType']       = 'location';
        $data['seoTitle']              = "Browse $entityName Colleges by Location | Shiksha.com";
        $data['seoDesc']               = "Check out Shiksha's sitemap to find $entityName colleges in various cities and states of India as per your education needs.";
       // _p($data);
        return $data;
    }

    private function findCategoryUrlsByParams($categoryPageCriteria){
        
        $this->CI->load->builder('LocationBuilder', 'location');
        $CategoryPageLib    = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
        $categoryPageLibrary= new NationalCategoryPageLib();
        $locationBuilder    = new LocationBuilder();
        $locationRepository = $locationBuilder->getLocationRepository();        
        $cityList           = $locationRepository->getCitiesByMultipleTiers(array(1, 2, 3), 2);
        $categoryParams     = $categoryPageLibrary->getMultipleUrlByParams($categoryPageCriteria);
        $cityTier1Ids=array();
        foreach ($cityList[1] as $key => $cityObj) {
           $cityTier1Ids[] =$cityObj->getId();
        }
        $topCityIds  = array(30, 55, 912, 63, 67, 87, 95, 106, 109, 127, 138, 139, 156, 158, 161, 171, 713, 192, 838, 2767);
       
        for($j = 0; $j<count($categoryParams); $j++)
        {   
            $substream_id       =$categoryParams[$j]['substream_id'];
            $specialization_id  =$categoryParams[$j]['specialization_id'];
            $city_id            =$categoryParams[$j]['city_id'];
            $state_id           =$categoryParams[$j]['state_id'];
            $categorypageUrl    =$categoryParams[$j]['url'];

            if($substream_id!=0 && $categoryPageCriteria['streamOnlypage']==1 ) //Direct Substream
            {   
                $subStreamObj               = $this->hierarchyRepository->findSubstream($substream_id);
                $subStreamAlias             = $this->_getUrl($subStreamObj->getUrlName(1));
                $substreams[$substream_id]  = array("text" => $subStreamObj->getName(), "link" => SHIKSHA_HOME . "/sitemap/browse-" . $subStreamAlias . "-colleges-by-location-sb-".$substream_id);
            }
            elseif($substream_id==0 && $specialization_id!=0 && $categoryPageCriteria['streamOnlypage']==1) //Direct Specialization
            {     
                $specializationObj                   = $this->hierarchyRepository->findSpecialization($specialization_id);
                $specializationAlias                 = $this->_getUrl($specializationObj->getUrlName(1));
                $specializations[$specialization_id] = array("text" => $specializationObj->getName(), "link" => SHIKSHA_HOME . "/sitemap/browse-" . $specializationAlias . "-colleges-by-location-sp-".$specialization_id);   
            }
            elseif($state_id>1 && $city_id==1) // State url
            { 
                $statesObject                        =$locationRepository->findState($state_id);
                $stateUrls[$statesObject->getName()] = $categorypageUrl;
            }
            elseif($state_id==1 && $city_id==1) // All India url
            { 
                //$cityObject                        =$locationRepository->findState($city_id);
                $topCitiesUrl['India'] = $categorypageUrl;
            }
            elseif(in_array($city_id, $topCityIds))
            {
                $cityObject                           =$locationRepository->findCity($city_id);
                $topCitiesUrl[$cityObject->getName()] = $categorypageUrl;
            }
            elseif(in_array($city_id, $cityTier1Ids))
            {
                $cityObject                           =$locationRepository->findCity($city_id);
                $topCitiesUrl[$cityObject->getName()] = $categorypageUrl;
            }
            elseif($city_id>1)
            {
                $cityObject                           =$locationRepository->findCity($city_id);
                $allCitiesUrl[$cityObject->getName()] = $categorypageUrl;
            }
        }
         ksort($allCitiesUrl);
         ksort($topCitiesUrl);
       
        return array(
            'stateUrls'                 => $stateUrls,
            'substreams'                =>$substreams,
            'specializations'           =>$specializations,
            'metroAndPopularCitiesUrl'  => $topCitiesUrl,
            'alphabetWiseCitiesUrl'     => $allCitiesUrl,
        );
    }


    public function _getUrl($text){
        $url = str_replace(".", "", $text);
        $url = seo_url($url, "-", 100, true);
        return $url;
    }

}