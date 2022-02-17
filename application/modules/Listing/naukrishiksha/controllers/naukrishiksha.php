<?php  
/*
   Copyright 2007 Info Edge India Ltd

   $Rev::               $:  Revision of last commit
   $Author: build $:  Author of last commit
   $Date: 2010-08-18 12:06:50 $:  Date of last commit

   This class provides the Blog Server Web Services. 
   The blog_client.php makes call to this server using XML RPC calls.

   $Id: naukrishiksha.php,v 1.6 2010-08-18 12:06:50 build Exp $: 

 */
class naukrishiksha extends MX_Controller{
    function bestplacestostudy()
    {

        $this->load->library('ajax');
        $this->load->helper('url');
        $Validate = $this->checkUserValidation();
        $data['validateuser'] = $Validate;
        $url = '';
        if($Validate == "false"){
            $editform = 0;
        }
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId, 1);
        $tier1 = $categoryClient->getCitiesInTier($appId, 1,2);
        $tier2 = $categoryClient->getCitiesInTier($appId, 2,2);
	$cities = array_merge($tier1,$tier2);
        $data['categoryList'] = $categoryList;
	$data['cities'] = $cities;
	$data['validateuser'] = $Validate;
        $this->load->view('naukrishiksha/naukrishiksha',$data);
    }

    function sendCourses($start = 0,$count = 30,$category,$subcategory,$country,$city,$pagename,$noofcourses,$courselevel = '',$courselevel1 = '',$coursetype = '')
    {
        $appId = 1;
	$this->load->library('listing_client');
        $listingClient = new listing_client();
        $courselevel = str_replace('okok','/',$courselevel);
        $resultset = $listingClient->getListingsForNaukriShiksha($appId,$category,$subcategory,$country,$city,$courselevel,$courselevel1,$coursetype,$start,$count,$pagename,$noofcourses);
	$result = array('results' => $resultset['institutesarr'],'totalCount' => count($resultset['institutesarr']));
	echo json_encode($result);	
    }	

    function showList($categoryId=3,$subcategoryId=83,$country=2,$cityId=10223,$start=0,$count=30,$courselevel = 'Degree',$courselevel1 = 'Post Graduate',$coursetype = 'All')
    {
        //redirect old category page urls to new category pages
        return;
        $request = $this->load->library('categoryList/CategoryPageRequest');
        if(in_array($subcategoryId,array(23,56))){
            $request->setNewURLFlag(1);
        }
        $request->setData(array('categoryId'=>$categoryId,'subCategoryId'=>$subcategoryId,'countryId'=>$country,'cityId'=>$cityId));
        $url = $request->getURL();
        redirect($url,'location',301);

        $appId = 1;
        $this->load->library('ajax');
        $this->load->helper(array('url','form','image','shikshautility'));
        $Validate = $this->checkUserValidation();
        $data['validateuser'] = $Validate;
        $url = '';
        if($Validate == "false"){
            $editform = 0;
        }
        $this->load->library('listing_client');
        $listingClient = new listing_client();

        $overridecache = isset($_REQUEST['overridecache']) ? 1 : 0;
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId, 1);
        global $categoryParentMap;
        $selectedCityName = '';
        $selectedCategoryName = '';
        $selectedSubCategoryName = '';
        foreach($categoryParentMap as $key=>$value) {
            if($value['id'] == $categoryId){
               $selectedCategoryName = $key;}} 
            for($i = 0;$i < count($categoryList);$i++) { if($categoryList[$i]['parentId'] == $categoryId) { if($categoryList[$i]['categoryID'] == $subcategoryId) { 
                $selectedSubCategoryName = $categoryList[$i]['categoryName'];}}} 
                $tier1 = $categoryClient->getCitiesInTier($appId, 1,2);
                $tier2 = $categoryClient->getCitiesInTier($appId, 2,2);
                $cities = array_merge($tier1,$tier2);
																for($j = 0;$j < count($cities); $j++) { 
																if($cities[$j]['cityId'] == $cityId) {
																$selectedCityName = $cities[$j]['cityName']; } 
}
        $resultset = $listingClient->getListingsForNaukriShiksha($appId,$categoryId,$subcategoryId,$country,$cityId,$courselevel,$courselevel1,$coursetype,$start,$count,'naukrishiksha',1,$overridecache);
        $newresultSet = $resultset['institutesarr'];
        $data['countArray'] = $resultset['countArray'];
        $data['resultSet'] = $newresultSet;
        $data['categoryList'] = $categoryList;
        $data['selectedcategoryId'] = $categoryId;
        $data['selectedsubcategoryId'] = $subcategoryId;
        $data['selectedCategoryName'] = $selectedCategoryName;
        $data['selectedSubCategoryName'] = $selectedSubCategoryName;
        $data['selectedCityName'] = $selectedCityName;
        $data['cities'] = $cities;
        $data['selectedcity'] = $cityId;
        $data['validateuser'] = $Validate;
        if($start % $count == 0)
        $pagenumber = $start/$count;
        else
        $pagenumber = $start/$count + 1; 
        $data['start'] = $start;
        $data['pagenumber'] = $pagenumber;
        $data['countoffset'] = $count;
        $data['selcourselevel'] = $courselevel;
        $data['selcourselevel1'] = $courselevel1;
        $data['selcoursetype'] = $coursetype;
        $this->load->view('naukrishiksha/collegelist',$data);
    }

    function init()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('ajax');
        $this->load->library('naukrishiksha_client');
    }
}
