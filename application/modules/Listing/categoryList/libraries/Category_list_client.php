<?php

/*

   Copyright 2007 Info Edge India Ltd

   $Rev::               $:  Revision of last commit
   $Author: raviraj $:  Author of last commit
   $Date: 2010/09/21 08:38:42 $:  Date of last commit

   category_list_client.php makes call to server using XML RPC calls.

   $Id: Category_list_client.php,v 1.33.30.3 2010/09/21 08:38:42 raviraj Exp $:
 */
class Category_list_client  {
    var $CI;
    var $cacheLib;
    function init(){
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
        $this->CI->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->server(CATEGORY_SERVER_URL, CATEGORY_SERVER_PORT);
        $this->cacheLib = new cacheLib();
    }

//TODO: Though caching has been implemented in get functions , but the cache also needs to be invalidated once some update or insert happens in categories

    //get category list based on category ID
    function getCategoryList($appID,$category_id,$flag =''){

        $this->init();
        $key = md5('getCategoryList'.$appID.$category_id.$flag);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCategoryList');
            $request = array($appID,$category_id,$flag);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
                error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }
        else{
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function get_category_name($appID, $category_id)
    {
        $this->init();
        $key = md5('get_category_name'.$appID.$category_id);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){

            //$this->CI->xmlrpc->set_debug(TRUE);
            $this->CI->xmlrpc->method('get_category_name');
            $request = array($category_id);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
                error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }
        else{
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function get_testprep_category_list($category_id)
    {
        $this->init();
        $key = md5('get_testprep_category_list'.$category_id);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCategoryList');
            $request = array($appID,$category_id);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
                error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }
        else{
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function get_testprep_menu_tree()
    {
        $this->init();
        $key = md5('get_testprep_menu_tree');
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('get_testprep_menu_tree');
            $request = array();
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }
        else{
            return $this->cacheLib->get($key);
        }


    }

    function get_blog_category_map()
    {
        $this->init();
        $key = md5('get_blog_category_map');
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
                $response = array(
                    300=>149,
                    299=>3,
                    298=>2,
                    297=>5,
                    464=>8
                );
                $this->cacheLib->store($key,$response);
                return $response;
        }
        else{
            return $this->cacheLib->get($key);
        }

    }

    function index(){
        echo "Use any webservice method to continue";
    }


    /*
     * 	Method to insert a category
     */
    function insertCategory($appID,$categoryName,$parentID,$userID){
        $this->init();
        $this->CI->xmlrpc->method('insertCategory');
        $request = array($appID,$categoryName,$parentID,$userID);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return $this->CI->xmlrpc->display_response();
        }
    }

    /*
     *	enable/disable a given category in category table 0 is enable and 1 is disabled
     */
    function enableCategory($appID,$category_id,$enable){
        $this->init();
        $this->CI->xmlrpc->method('enableCategory');
        $request = array($appID,$enable,$category_id);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return $this->CI->xmlrpc->display_response();
        }
    }


    function getCategoryTree($appID,$orderBy=0,$flag=''){
        $this->init();
        $key = md5('getCategoryTree'.$appID.$orderBy.$flag);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCategoryTree');
            $request = array($appID,$orderBy,$flag);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
		//Modified for Shiksha performance task on 8 March
		//return $this->CI->xmlrpc->display_response();
		$response = ($this->CI->xmlrpc->display_response());
		$response = json_decode(gzuncompress(base64_decode($response)),true);

                $categoryTree = $response;
                $this->cacheLib->store($key,$response);
                $catTree = array();
                for($categoryTreeCount = 0; $categoryTreeCount < count($categoryTree); $categoryTreeCount++) {
                    $parentId = $categoryTree[$categoryTreeCount]['parentId'];
                    $categoryId = $categoryTree[$categoryTreeCount]['categoryID'];
                    if($parentId == 0) {continue;}
                    if($parentId == 1) {
                        $catTree[$categoryId] = $categoryTree[$categoryTreeCount];
                    } else {
                        $catTree[$parentId]['subCategories'][$categoryId] = $categoryTree[$categoryTreeCount];
                    }
                }
                if( ($this->cacheLib->get("catsubCatList") == "ERROR_READING_CACHE") && $orderBy ==1)
                    $this->cacheLib->store('catsubCatList',serialize($catTree));
                    
                return $response;
            }
        }
        else
        {
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryTree: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }
    
    function getCategoryCourses($appID){
        $this->init();
        $key=md5('getCategoryCourses'.$appID);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCategoryCourses');
            $request = array($appID);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response=$this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }else{
            return  $this->cacheLib->get($key);
        }
    }
    function getInstituteForTabs($appID){
        $this->init();
        $key=md5('getInstituteForTabs');
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getInstituteForTabs');
            $request = array($appID);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response=$this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,86400);
                return $response;
            }
        }else{
            return  $this->cacheLib->get($key);
        }
    }

	function getZones($appID){
        $this->init();
//        $this->CI->xmlrpc->set_debug(1);
        $this->CI->xmlrpc->method('getZones');
        $request = array($appID);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
        }
    }
        function getCountries($appID){
        $this->init();
        /*$this->CI->xmlrpc->method('getCountries');
        $request = array($appID);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
	    //Modified for Shiksha performance task on 8 March
	    //return $this->CI->xmlrpc->display_response();
	    $response = ($this->CI->xmlrpc->display_response());
	    $res = json_decode(gzuncompress(base64_decode($response)),true);
	    return $res;
        }*/

        $key=md5('getCountriesCategoryClient'.$appID);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
             $this->CI->xmlrpc->method('getCountries');
             $request = array($appID);
             $this->CI->xmlrpc->request($request);
             if ( ! $this->CI->xmlrpc->send_request()){
                   return $this->CI->xmlrpc->display_error();
             }else{
                   $response = ($this->CI->xmlrpc->display_response());
                   $res = json_decode(gzuncompress(base64_decode($response)),true);
                   $this->cacheLib->store($key,$res);
                   return $res;
             }
        }else{
             return  $this->cacheLib->get($key);
        }
    }
    

    function getCategoryFeedsForHomePage($appID){
        $this->init();
        $key=md5('getCategoryFeedsForHomePage'.$appID);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCategoryFeedsForHomePage');
            $request = array($appID);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response=$this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }else{
            return  $this->cacheLib->get($key);
        }
    }



    function getCategoryTreeArray(& $returnArray, $categoryTree, $parentId, $parentCategoryName) {
        if(is_array($categoryTree)) {
            $i=0;
            foreach($categoryTree as $categoryLeaf) {
                if($categoryLeaf['parentId'] == $parentId) {
                    $returnArray[$parentCategoryName][$i++] = $categoryLeaf['categoryID'] ."<=>". $categoryLeaf['categoryName'] ."<=>". $categoryLeaf['urlName'];;
                    $this->getCategoryTreeArray($returnArray[$parentCategoryName], $categoryTree, $categoryLeaf['categoryID'], $categoryLeaf['categoryName']);
                }
            }
        }
        return $returnArray;
    }

    function getCategoryIdByURLName($appId, $urlName){
        $this->init();
        $this->CI->xmlrpc->method('getCategoryIdByURLName');
        $request = array($appId, $urlName);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getCategoryIdByName($appId,$parentId, $name){
        $this->init();
        $this->CI->xmlrpc->method('getCategoryIdByName');
        $request = array($appId,$parentId, $name);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    //get subcategory list based on category ID
    function getSubCategories($appID,$category_id, $flag = "national"){
        $this->init();
        $key = md5('getSubCategories'.$appID.$category_id.$flag);
        
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getSubCategories');
            $request = array($appID,$category_id,$flag);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
				return $response;
            }
        }else{
            return  $this->cacheLib->get($key);
        }
    }

    //get All Parent Categories list
    function getParentCategories($appID, $flag="national"){
        return; /*
        $this->init();
        $key = md5('getParentCategories'.$appID.$flag);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){

            $this->CI->xmlrpc->method('sgetParentCategories');
            $request = array($appID, $flag);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response=$this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }else{
            return  $this->cacheLib->get($key);
        }
        */
    }

    function getSubCategoryIdByURLName($appId, $parentCategory, $urlName){
        $this->init();
        $key = md5('getSubCategoryIdByURLName'.$appID.$parentCategory.$urlName);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getSubCategoryIdByURLName');
            // $request = array($appId, $parentCategory, $urlName);
            // Updated by Amit Kuksal on 24th Feb 2011 for Category Page revamp || Paasing the urlName information as an array if required..
            $request = array($appId, $parentCategory, is_array($urlName) ? array($urlName, 'array') : $urlName);            
	    $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()) {
                return $this->CI->xmlrpc->display_error();
            } else {
                $response=$this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }else{
            return  $this->cacheLib->get($key);
        }
    }

    function getCityId($appId, $cityName)
    {
        $this->init();
        $this->CI->xmlrpc->method('getCityId');
        $request = array($cityName);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getCityName($cityId)
    {
        $this->init();
        $this->CI->xmlrpc->method('getCityName');
        $request = array($cityId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getBlogId($appId, $blogAcronym)
    {
        $this->init();
        $this->CI->xmlrpc->method('getBlogId');
        $request = array($blogAcronym);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getBlogAcronym($blogId)
    {
        $this->init();
        $this->CI->xmlrpc->method('getBlogAcronym');
        $request = array($blogId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getBlogParent($blogId)
    {
        $this->init();
        $this->CI->xmlrpc->method('getBlogParent');
        $request = array($blogId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getBlogTitle($blogId)
    {
        $this->init();
        $this->CI->xmlrpc->method('getBlogTitle');
        $request = array($blogId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getCityList($appId, $flag=''){
        $this->init();
        $this->CI->xmlrpc->method('getCityListS');
        $request = array($appId, $flag);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getNewCityList($appId, $flag='', $limit  ='100'){
        $this->init();
        $this->CI->xmlrpc->method('getNewCityList');
        $request = array($appId, $flag, $limit);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    //get category list based on tier criteria -- SUMS usage
    function getCategoryBasedOnTier($appID,$tier = 0,$type = '', $typeId = ''){
        $this->init();
        if(strlen($typeId) <=0 || strlen($type) <= 0){
            return $this->getCategoryBasedOnTierOnly($appID, $tier);
        }
        else{
            return $this->getListingsCategoriesInTier($appID, $tier, $type, $typeId);
        }
    }

    function getCategoryBasedOnTierOnly($appID,$tier = 0){
        $key = md5('getCategoryBasedOnTierOnly'.$appID.$tier);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCategoryBasedOnTier');
            $request = array($appID,$tier);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
                error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                //                $this->cacheLib->store($key,$response);
                return $response;
            }
        }
        else{
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function getListingsCategoriesInTier($appID,$tier = 0,$type,$typeId){
        $this->CI->xmlrpc->method('getListingsCategoriesInTier');
        $request = array($appID,$tier,$type,$typeId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
            return $this->CI->xmlrpc->display_error();
        }else{
            return $this->CI->xmlrpc->display_response();
        }
    }

	    //get city list based on tier criteria -- SUMS usage
    function getCityBasedOnTier($appID,$tier = 0, $countryId =1,$type = '', $typeId = ''){
        $this->init();
        if(strlen($typeId) <=0 || strlen($type) <= 0){
            return $this->getCitiesInTier($appID, $tier, $countryId);
        }
        else{
            return $this->getListingsCitiesInTier($appID, $tier, $countryId,$type, $typeId);
        }

    }

    function getListingsCitiesInTier($appID,$tier = 0, $countryId =1,$type , $typeId){
        $this->CI->xmlrpc->method('getListingsCitiesInTier');
        $request = array($appID,$tier,$countryId,$type,$typeId);
        $this->CI->xmlrpc->request($request);
        if(!$this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }
    
    
    function getSubcategoryBasedontier($appID,$tier,$type, $typeId){
        $this->init();
        $this->CI->xmlrpc->method('getSubcategoryBasedontier');
        $request = array($appID,$tier,$type,$typeId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            
               return $this->CI->xmlrpc->display_error();
        }else{
            
                return $this->CI->xmlrpc->display_response();
        }        

    }


    function getstateBasedontier($appID,$tier,$type, $typeId){
        $this->init();
        $this->CI->xmlrpc->method('getstateBasedontier');
        $request = array($appID,$tier,$type,$typeId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            
               return $this->CI->xmlrpc->display_error();
        }else{
            
                return $this->CI->xmlrpc->display_response();
        }        

    }

    function getCountrydependOnTier($appID,$tier,$type, $typeId){
        $this->init();
        $this->CI->xmlrpc->method('getCountrydependOnTier');
        $request = array($appID,$tier,$type,$typeId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            
               return $this->CI->xmlrpc->display_error();
        }else{
            
                return $this->CI->xmlrpc->display_response();
        }        

    }

    function getInstituteTypes(){
    	$this->init();
    	$key = md5('instituteType');
    	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getInstituteTypes');
            $request = array($appID,$tier,$countryId);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = json_decode($this->CI->xmlrpc->display_response(),true);
                $this->cacheLib->store($key,$response,30*86400);
                return $response;
            }
        }
        else{
//            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }
        function getCitiesInTier($appID,$tier = 0, $countryId =1){
        $this->init();
        $key = md5('getCitiesInTier'.$appID.$tier.$countryId);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCityBasedOnTier');
            $request = array($appID,$tier,$countryId);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
                error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = json_decode($this->CI->xmlrpc->display_response(),true);
                $this->cacheLib->store($key,$response,14400);
                return $response;
            }
        }
        else{
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

	    //get city list based on tier criteria -- SUMS usage
    function getCountriesBasedOnTier($appID,$tier = 0, $type = '', $typeId = ''){
        $this->init();
        error_log("huhuuhhuuhuhuhu");
        if(strlen($typeId) <=0 || strlen($type) <= 0){
            return $this->getCountriesInTier($appID, $tier);
        }
        else{
            return $this->getListingsCountriesInTier($appID, $tier, $type, $typeId);
        }

    }

    function getListingsCountriesInTier($appID,$tier = 0, $type , $typeId){
        $this->CI->xmlrpc->method('getListingsCountriesInTier');
        $request = array($appID,$tier,$type,$typeId);
        $this->CI->xmlrpc->request($request);
        if(!$this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

    function getCountriesInTier($appID,$tier = 0){
        $key = md5('getCountriesInTier'.$appID.$tier);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getCountriesInTier');
            $request = array($appID,$tier);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
                error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }
        else{
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function getCategoryDetailsById($appId, $categoryId){
        $this->init();
        $this->CI->xmlrpc->method('sgetCategoryDetailsById');
        $request = array($appId, $categoryId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getSubToParentCategoryMapping($appID){
	    $this->init();
	    $key = md5('getSubToParentCategoryMapping');
	    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
		    $this->CI->xmlrpc->method('getSubToParentCategoryMapping');
		    $request = array($appID);
		    $this->CI->xmlrpc->request($request);
		    if ( ! $this->CI->xmlrpc->send_request()){
			    return $this->CI->xmlrpc->display_error();
		    }else{
			    $response = $this->CI->xmlrpc->display_response();
			    $this->cacheLib->store($key,$response);
			    return $response;
		    }
	    }
	    else{
		    return $this->cacheLib->get($key);
	    }
    }

        function fillJSRepos($jsArray){
            $this->init();
            $key = md5('JS_REPOS');
            $jsRepos = array();
            if($this->cacheLib->get($key) !='ERROR_READING_CACHE') {
                $jsRepos = json_decode($this->cacheLib->get($key), true);
                error_log('ASHISH::'. print_r($jsRepos, true));
            }
            foreach($jsArray as $jsName => $jsCode) {
                $jsRepos[$jsName] = $jsCode;
            }
            $this->cacheLib->store($key, json_encode($jsRepos));
        }

        function getJSFromRepos() {
            $this->init();
            $key = md5('JS_REPOS');
            if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
                return array();
            } else {
                return json_decode($this->cacheLib->get($key), true);
            }
        }

        function getDetailsForCityId($appId, $cityId) {
            $this->init();
            $this->CI->xmlrpc->method('sGetDetailsForCityId');
            $request = array($appId,$cityId);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                return json_decode($response,true);
            }
        }

    function getLocalitiesForCityId($appId, $cityId){
        $this->init();
        $this->CI->xmlrpc->method('sgetLocalitiesForCityId');
        $request = array($appId, $cityId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

   function getZonesForCityId($appId, $cityId){
        $this->init();
        $this->CI->xmlrpc->method('sgetZonesForCityId');
        $request = array($appId, $cityId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

  function getLocalitiesForZoneId($appId, $zoneId){
        $this->init();
        $this->CI->xmlrpc->method('sgetLocalitiesForZoneId');
        $request = array($appId, $zoneId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

  function getCityGroupInSameVirtualCity($appId, $cityId){
        $this->init();
        $this->CI->xmlrpc->method('sgetCityGroupInSameVirtualCity');
        $request = array($appId, $cityId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }



    function getAllLocalities($appId){
        $this->init();
        $this->CI->xmlrpc->method('sgetAllLocalities');
        $request = array($appId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

    function getSpecializationForCategoryId($appId, $categoryId){
        $this->init();
        $this->CI->xmlrpc->method('sgetSpecializationForCategoryId');
        $request = array($appId, $categoryId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

    function getCourseSpecializationForCategoryIdGroups($appId, $categoryId){
        $this->init();
        $this->CI->xmlrpc->method('sgetCourseSpecializationForCategoryIdGroups');
        $request = array($appId, $categoryId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

    function getCitiesForVirtualCity($appId, $cityId){
        $this->init();
        $key = md5('getCitiesForVirtualCity'.$appId.$cityId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
                $this->CI->xmlrpc->method('sgetCitiesForVirtualCity');
                $request = array($appId, $cityId);
                $this->CI->xmlrpc->request($request);
                if (!$this->CI->xmlrpc->send_request()) {
                    $response = $this->CI->xmlrpc->display_error();
                    return $response;
                } else {
                    $response = $this->CI->xmlrpc->display_response();
                    $this->cacheLib->store($key,$response,86400);
                    return $response;
                }
        }
        else{
                 return $this->cacheLib->get($key);
        }
    }


    function getZonewiseLocalitiesForCityId($appId, $cityId){
        $this->init();
        $this->CI->xmlrpc->method('getZonewiseLocalitiesForCityId');
        $request = array($appId, $cityId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
     }

    /* added APC cache */

    function getCountriesWithRegions($appId)
    {
        $this->init();
	$key = md5('getCountriesWithRegions'.$appId);
	error_log_shiksha("key for cache is : ".$key);
	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
		$this->CI->xmlrpc->method('sgetCountriesWithRegions');
		$request = array($appId);
		$this->CI->xmlrpc->request($request);
		if (!$this->CI->xmlrpc->send_request()) {
			$response = $this->CI->xmlrpc->display_error();
			return $response;
		} else {
			$response = $this->CI->xmlrpc->display_response();
			$this->cacheLib->store($key,$response,7200,'misc');
			return $response;
		}
	}
	else
	{
		return $this->cacheLib->get($key);
	}
    }

 function getCategoryCourseList($categoryId=0){
        $this->init();
        $this->CI->xmlrpc->method('sgetCategoryCourseList');
        $request = array($categoryId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }
    
	function getShikshaCourseCategories()
 	{
        $this->init();
        $this->CI->xmlrpc->method('getShikshaCourseCategories');
        $request = array($categoryId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }
    
	function getShikshaCourses($category_id=0)
 	{
        $this->init();
        $this->CI->xmlrpc->method('getShikshaCourses');
        $request = array($category_id);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }
    
	function getShikshaMappedCourses($course_id)
 	{ 	
        $this->init();
        $this->CI->xmlrpc->method('getShikshaMappedCourses');
        $request = array($course_id);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

	function getTestPrepCoursesList($appId)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsgetTestPrepCoursesList');
		$request = array($appId);
		$this->CI->xmlrpc->request($request);
		if (!$this->CI->xmlrpc->send_request())
		{
			$response = $this->CI->xmlrpc->display_error();
			return $response;
		} else {
			$response = $this->CI->xmlrpc->display_response();
			return json_decode($response,true);
		}
	}

	function getblogNameCsvFromBlogIdCsv($appId,$csvblogid)
	{
		$this->init();
		$this->CI->xmlrpc->method('wsgetblogNameCsvFromBlogIdCsv');
		$request = array($appId,$csvblogid);
		$this->CI->xmlrpc->request($request);
		if (!$this->CI->xmlrpc->send_request())
		{
			$response = $this->CI->xmlrpc->display_error();
			return $response;
		} else {
			$response = $this->CI->xmlrpc->display_response();
			return json_decode($response,true);
		}
	}
    
    function getPopularCourses($subcategories){
        $this->init();
        $this->CI->xmlrpc->method('getPopularCourses');
        $request = array($subcategories);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }
    
    function getSubCategoryCourses($subcategories, $onlyCourses=False){
        $this->init();
        $this->CI->xmlrpc->method('getSubCategoryCourses');
        $request = array($subcategories, $onlyCourses);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }
    
    function setPopularCourses($selectedCourse,$unSelectedCourse){
        $this->init();
        //$this->setMode('write');
        $this->CI->xmlrpc->method('setPopularCourses');
        $request = array($selectedCourse,$unSelectedCourse);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }
    
    function getCatSubcatList($countryId,$instituteType){
        $this->init();
        $this->CI->xmlrpc->method('getCatSubcatList');
        $request = array($countryId,$instituteType);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }
    
    function getLdbMappedCourses($courseId){
        $this->init();
        $this->CI->xmlrpc->method('getLdbMappedCourses');
        $request = array($courseId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }
    
    function getCategoryPageHeaderText($page_type,$type_id,$location_type,$location_id){
        $this->init();
        $this->CI->xmlrpc->method('getCategoryPageHeaderText');
        $request = array($page_type,$type_id,$location_type,$location_id);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }
    
        
    function setCategoryPageHeaderText($page_type,$type_id,$location_type,$location_id,$text){
        $this->init();
        //$this->setMode('write');
        $this->CI->xmlrpc->method('setCategoryPageHeaderText');
        $request = array($page_type,$type_id,$location_type,$location_id,$text);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }

    function removeCategorypageWidgetsDataInfo($regionId, $countryId, $categoryID, $widgetType) {

        $regionId = ($regionId == "" ? 0 : $regionId);
        $countryId = ($countryId == "" ? 0 : $countryId);
        
        // error_log("\n\n region = ".$regionId.", country = ".$countryId.", categoryID = ".$categoryID.", widgetType = ".$widgetType,3,'/home/infoedge/Desktop/log.txt'); // die;

        $this->init();       
        // Clearing Cache for this Category Widget key..
        // $widget_type_value = ($widgetType == 1 ? 'quick_links' : 'latest_news');
        $widget_type_value = $this->getWidgetType($widgetType);
        
        $key = md5($widget_type_value."_".$regionId."_".$countryId."_".$categoryID);
        $this->cacheLib->clearCache($key, 1);

        // Need to remove Subcategories' Cache as well to avoid the inheritence case..
        if($categoryID <= 14 && $countryId == 2) { // i.e. Remove all Subcat's cache if India page..
            $this->CI->load->builder('CategoryBuilder','categoryList');
            $categoryBuilder = new CategoryBuilder;
            $repository = $categoryBuilder->getCategoryRepository();
            $subCategoriesOfthisCategory  = $repository->getSubCategories($categoryID,'national');
            foreach($subCategoriesOfthisCategory as $subcategory) {
                    $key = md5($widget_type_value."_".$regionId."_".$countryId."_".$subcategory->getId());
                    $this->cacheLib->clearCache($key, 1);
            }
        }   // if($categoryID <= 14 && $countryId == 2).

        if($countryId != 2) {
            $this->CI->load->builder('LocationBuilder','location');
            $locationBuilder = new LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();

            // error_log("\n\n YES INSIDE 3, widget_type_value = ".$widget_type_value,3,'/home/infoedge/Desktop/log.txt');

            if($regionId != "" || $regionId != 0){
                $countriesForThisRegion = $locationRepository->getCountriesByRegion($regionId);
                // error_log("\n\n cn in region = ".print_r($countriesForThisRegion, true),3,'/home/infoedge/Desktop/log.txt'); die;
                foreach($countriesForThisRegion as $country) {
                    // error_log("\n Region WALA = ".$regionId.", country = ".$country->getId().", categoryID = ".$categoryID.", widgetType = ".$widget_type_value,3,'/home/infoedge/Desktop/log.txt');
                    $key = md5($widget_type_value."_".$regionId."_".$country->getId()."_".$categoryID);
                    $this->cacheLib->clearCache($key, 1);                    
                }
            } else {
                    // error_log("\n Region = ".$regionId.", country WALA = ".$countryId.", categoryID = ".$categoryID.", widgetType = ".$widget_type_value,3,'/home/infoedge/Desktop/log.txt');
                    $key = md5($widget_type_value."_".$regionId."_".$countryId."_".$categoryID);
                    $this->cacheLib->clearCache($key, 1);
            }
        }

        $this->CI->xmlrpc->method('removeCategorypageWidgetsDataInfo');
        $request = array($regionId, $countryId, $categoryID, $widgetType);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }

    function setCategorypageWidgetsDataInfo($regionId, $countryId, $categoryID, $dataIDs, $widgetType, $widgetImageName) {
            $this->init();
            //error_log("\n\n Articles in client: ".print_r($dataIDsArray,true),3,'/home/infoedge/Desktop/log.txt');

            $widget_type_value = $this->getWidgetType($widgetType);

            if($regionId == "") $regionId = 0;
            if($countryId == "") $countryId = 0;

            // Clearing Cache for this Category Widget key..
            // $key = md5($widget_type_value."_".$categoryID);
            $key = md5($widget_type_value."_".$regionId."_".$countryId."_".$categoryID);
            // error_log("Set time KEY: ".$key);
            $this->cacheLib->clearCache($key, 1);
            // error_log("KEY cat id: ".$categoryID);

            // Need to remove Subcategories' Cache as well to avoid the inheritence case..
            if($categoryID <= 14 && $countryId == 2) { // i.e. Remove all Subcat's cache if India page..

                $this->CI->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $repository = $categoryBuilder->getCategoryRepository();
                $subCategoriesOfthisCategory  = $repository->getSubCategories($categoryID,'national');
                foreach($subCategoriesOfthisCategory as $subcategory) {
                        // $key = md5($widget_type_value."_".$subcategory->getId());
                        $key = md5($widget_type_value."_".$regionId."_".$countryId."_".$subcategory->getId());
                        $this->cacheLib->clearCache($key, 1);
                       // error_log("KEY removal for subcat id : ".$subcategory->getId().", key : ".$key);
                }
            }   // End of if($categoryID <= 14).


        if($countryId != 2) {
            $this->CI->load->builder('LocationBuilder','location');
            $locationBuilder = new LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();

            // error_log("\n\n YES INSIDE 3, widget_type_value = ".$widget_type_value,3,'/home/infoedge/Desktop/log.txt');

            if($regionId != "" || $regionId != 0){
                $countriesForThisRegion = $locationRepository->getCountriesByRegion($regionId);
                // error_log("\n\n cn in region = ".print_r($countriesForThisRegion, true),3,'/home/infoedge/Desktop/log.txt'); die;
                foreach($countriesForThisRegion as $country) {
                    // error_log("\n Region WALA = ".$regionId.", country = ".$country->getId().", categoryID = ".$categoryID.", widgetType = ".$widget_type_value,3,'/home/infoedge/Desktop/log.txt');
                    $key = md5($widget_type_value."_".$regionId."_".$country->getId()."_".$categoryID);
                    $this->cacheLib->clearCache($key, 1);
                }
            } else {
                    // error_log("\n Region = ".$regionId.", country WALA = ".$countryId.", categoryID = ".$categoryID.", widgetType = ".$widget_type_value,3,'/home/infoedge/Desktop/log.txt');
                    $key = md5($widget_type_value."_".$regionId."_".$countryId."_".$categoryID);
                    $this->cacheLib->clearCache($key, 1);
            }
        }
        
        $this->CI->xmlrpc->method('setCategorypageWidgetsDataInfo');
        $request = array($regionId, $countryId, $categoryID, $dataIDs, $widgetType, $widgetImageName);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(),true);
        }
    }

    function getWidgetType($widgetType) {
            switch($widgetType) {
                case '1':    case 1:
                    $widget_type_value = 'quick_links';
                    break;
                case '2':  case 2:
                    $widget_type_value = 'latest_news';
                    break;
                case '3':  case 3:
                    $widget_type_value = 'quick_links';
                    break;
                case '4':  case 4:
                    $widget_type_value = 'latest_news';
                    break;
                default:
                    $widget_type_value = 'quick_links';
                    break;
            }

            return($widget_type_value);
    }

     function getCategorypageWidgetsDataInfo($regionId, $countryId, $categoryID, $widgetType){
            $this->init();
            $this->CI->xmlrpc->method('getCategorypageWidgetsDataInfo');
            $request = array($regionId, $countryId, $categoryID, $widgetType);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                return json_decode($this->CI->xmlrpc->display_response(),true);
            }
     }
     
    function getSAWidgetArticles($location_id, $location_type, $category_id, $widget){
            $this->init();
            $this->CI->xmlrpc->method('getSAWidgetArticles');
            $request = array($location_id, $location_type, $category_id, $widget);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                return json_decode($this->CI->xmlrpc->display_response(),true);
            }
    }
    function saveSAWidgetContent($location_id, $location_type, $category, $widget,$value,$position,$type,$image){
        $this->init();
            $this->CI->xmlrpc->method('saveSAWidgetContent');
            $request = array($location_id, $location_type, $category, $widget,$value,$position,$type,$image);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                return json_decode($this->CI->xmlrpc->display_response(),true);
            }
    }
    
    function getTabsContentByCategory(){
        $this->init();
        
        $coursePagesUrlRequest = $this->CI->load->library('coursepages/CoursePagesUrlRequest');
        
        if($coursePagesUrlRequest->checkIfMobileDevice()) {
            $isMobileDevice = 1;
            $key = md5('getTabsContentByCategoryMobile');
        } else {
            $isMobileDevice = 0;
            $key = md5('getTabsContentByCategoryDesktop');
        }
        
        $data = $this->cacheLib->get($key);

        if($data =='ERROR_READING_CACHE'){            
            $this->CI->xmlrpc->method('getTabsContentByCategory');
            $this->CI->xmlrpc->request($request);
            if (! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{                
                $response = ($this->CI->xmlrpc->display_response());
                $response = json_decode($response,true);
                $categoryTree = $response;                
                $response = $this->populateURLsForTabs($response, $isMobileDevice, $coursePagesUrlRequest);
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }
        else
        {
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getTabsContentByCategory: EXIT SUCCESS Reading from cache");                        
            return $data;
        }
    }
    
    function populateURLsForTabs($response, $isMobileDevice, $coursePagesUrlRequest){        
        $this->CI->load->library('categoryList/categoryPageRequest');
        $requestURL = new CategoryPageRequest();
        
        global $COURSE_PAGES_SUB_CAT_ARRAY;    
        $coursePagesSubcategories = array_keys($COURSE_PAGES_SUB_CAT_ARRAY);
        
        foreach($response as $key=>$category){
            $requestURL = new CategoryPageRequest();
            // Hardcoded check for engineering category page link in sitefooter
            if( $key == 2 )
            {
                $requestURL->setNewURLFlag (1);
                $requestURL->setData(array('categoryId'=>$key,'subCategoryId'=>1));
                $response[$key]['url'] = $requestURL->getURL();
                $requestURL->setNewURLFlag (0);
            }
            else
            {
                $requestURL->setData(array('categoryId'=>$key));
                $response[$key]['url'] = $requestURL->getURL();
            }            
   
            foreach($category['subcats'] as $key2=>$subcat){
                if(!$isMobileDevice && in_array($key2, $coursePagesSubcategories)) {                    
                    $response[$key]['subcats'][$key2]['url'] = $coursePagesUrlRequest->getHomeTabUrl($key2);
                } else {
                $requestURL = new CategoryPageRequest();
                //********************************************
                // Added by Romil Goel on 21 Nov-13 for RNR phase2 
                $this->CI->config->load('categoryPageConfig');
                $list = $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST");
                if( array_key_exists($key2, $list ) )
                {
                    $requestURL->setNewURLFlag (1);
                }
                //*********************************************
                $requestURL->setData(array('categoryId'=>$key,'subCategoryId'=>$key2));
                $response[$key]['subcats'][$key2]['url'] = $requestURL->getURL();
                }

            }
            foreach($category['popcourses'] as $key3=>$ldbcourse){
                $requestURL = new CategoryPageRequest();
                $requestURL->setData(array('categoryId'=>0,'subCategoryId'=>0,'LDBCourseId'=>$key3));
                $response[$key]['popcourses'][$key3]['url'] = $requestURL->getURL();
            }
        }
        return $response;
    }
    
    public function applyZeroResultHandlingCheck(CategoryPageBuilder &$categoryPageBuilder) {
        $this->init();
        $this->CI->config->load('categoryPageConfig');
        $returnResults = array('categorypage' => false, 'institutes' => false, 'zero_results' => false, 'zero_result_step' => false);
		$request = $categoryPageBuilder->getRequest();
        
		$paramsKnockOutPriority = $this->CI->config->item('CP_FILTER_KNOCKOUT_PRIORITY');
        $originalAppliedFilters = $request->getAppliedFilters();
        
        $categoryPage 			= $categoryPageBuilder->getCategoryPage();
        $categoryPageInstitutes = $categoryPage->getInstitutes();
        
        $returnResults['categorypage']  = $categoryPage;
        $returnResults['institutes']    = $categoryPageInstitutes;
        
        if(count($categoryPageInstitutes) <= 0) {
            foreach($paramsKnockOutPriority as $index => $param) {
                $tempRequest            = clone $request;
                $tempAppliedFilters     = $originalAppliedFilters;
                
                $appliedFilters         = $this->__knockOutFilterValues($param, $tempAppliedFilters);
                $tempRequest            = $this->__knocOutRequestParams($param, $tempRequest);
                $tempRequest->setAppliedFilters($appliedFilters);
                $categoryPageBuilder->setRequest($tempRequest);
                
                $categoryPage 			= $categoryPageBuilder->getCategoryPage();
                $categoryPageInstitutes = $categoryPage->getInstitutes();
                $returnResults['categorypage']      = $categoryPage;
                $returnResults['institutes']        = $categoryPageInstitutes;
                $returnResults['zero_results']      = TRUE;
                $returnResults['zero_result_step']  = $param;
                $resultCount 			        = count($categoryPageInstitutes);
                if($resultCount > 0){
                    break;
                }
            }    
        }
        return $returnResults;
    }
	
	private function __knockOutFilterValues($key, $appliedFilters) {
        switch($key){
            case 'locality':
                $appliedFilters['locality'] = array();
                break;
            case 'examsscore':
                $appliedFilters = $this->__alterExamScoresInFilters($appliedFilters);
                break;
            case 'locality-examsscore':
                $appliedFilters['locality'] = array();
                $appliedFilters = $this->__alterExamScoresInFilters($appliedFilters);
                break;
            case 'locality-zone':
                $appliedFilters['locality'] = array();
                $appliedFilters['zone']     = array();
                break;
            case 'city':
                $appliedFilters['locality'] = array();
                $appliedFilters['zone']     = array();
                $appliedFilters['city'] = array();
                break;
            case 'state':
                $appliedFilters['locality'] = array();
                $appliedFilters['zone']     = array();
                $appliedFilters['city'] = array();
                $appliedFilters['state'] = array();
		break;
            case 'exam':
                $appliedFilters['courseexams'] = array();
                break;
        }
        return $appliedFilters;
	}
    
    private function __knocOutRequestParams($key, CategoryPageRequest $request){
        switch($key){
            case 'locality':
                $data               = array();
                $cityId             = $request->getCityId();
                $data['localityId'] = 0;
                $data['cityId']     = $cityId;
                $request->setData($data);
                break;
            case 'examsscore':
                break;
            case 'locality-examsscore':
                $data               = array();
                $cityId             = $request->getCityId();
                $data['localityId'] = 0;
                $data['cityId']     = $cityId;
                $request->setData($data);
                break;
            case 'locality-zone':
                $data               = array();
                $cityId             = $request->getCityId();
                $data['zoneId']     = 0;
                $data['localityId'] = 0;
                $data['cityId']     = $cityId;
                $request->setData($data);
                break;
            case 'city':
                $data               = array();
                $data['localityId'] = 0;
                $data['zoneId']     = 0;
                $data['cityId']     = 1;
                $request->setData($data);
                break;
            case 'state':
                $data               = array();
                $data['localityId'] = 0;
                $data['zoneId']     = 0;
                $data['cityId']     = 1;
                $data['stateId']    = 1;
                $request->setData($data);
		break;
            case 'exam':
                $data['examName'] = "";
                $request->setData($data);
                break;
        }
        return $request;
    }
    
    private function __alterExamScoresInFilters($appliedFilters) {
        $courseExams = $appliedFilters['courseexams'];
        $examList = array();
        foreach($courseExams as $exam) {
            $explode = explode("_", $exam);
            if(count($explode) > 0){
                $examList[] = $explode[0] . "_0";
            }
        }
        if(count($examList) > 0){

            $appliedFilters['courseexams'] = array();
            $appliedFilters['courseexams'] = $examList;
        }
        return $appliedFilters;
    }
    
    public function getNewPageRequestForZeroResultHandling(CategoryPageRequest $request) {
        $this->init();
        $data = array();
        $data['categoryId']             = $request->getCategoryId();
        $data['subCategoryId']          = $request->getSubCategoryId();
        $data['LDBCourseId']            = $request->getLDBCourseId();
        $data['cityId']                 = $request->getCityId();
        $data['countryId']              = $request->getCountryId();
        $data['stateId']                = $request->getStateId();
        $data['affiliation']            = "";
        $data['feesValue']              = "";
        $data['examName']               = "";
        $data['localityId']				= 0;
        $requestURL = new CategoryPageRequest();
        $requestURL->setNewURLFlag(1);
        $requestURL->setData($data);
        $preappliedFilters = $requestURL->getAppliedFilters();
        $preappliedFilters['courseexams']=array();
        $preappliedFilters['degreePref']=array();
        $preappliedFilters['fees']=array();
        $preappliedFilters['locality']=array();
        $encoded_filters_updated = base64_encode(json_encode($preappliedFilters));
        $encoded_filters_old = base64_encode(json_encode($request->getAppliedFilters()));
        
        if(!(($requestURL->getPageKey() == $request->getPageKey()) && (strcmp($encoded_filters_old,$encoded_filters_updated) == 0)))
        {
         $this->setCookieCategoryPage('filters-'.$requestURL->getPageKey(),$encoded_filters_updated,0,'/',COOKIEDOMAIN);
        }
        
         return $requestURL;
    }

    public function getRequestDataFromKey($params = NULL, $urlType = FALSE) {
		if(empty($params)){
			return FALSE;
		}
		$urlData = FALSE;
		$trackData = explode("-", $params);
        if($urlType == 'RNRURL') {
            if(count($trackData >= 10)){
                $urlData['categoryId'] 				= $trackData[0];
                $urlData['subCategoryId'] 			= $trackData[1];
                $urlData['LDBCourseId'] 			= $trackData[2];
                $urlData['localityId'] 				= $trackData[3];
                $urlData['cityId'] 					= $trackData[4];
                $urlData['stateId'] 				= $trackData[5];
                $urlData['countryId'] 				= $trackData[6];
                $urlData['regionId'] 				= $trackData[7];
                $urlData['affiliation'] 			= $trackData[8];
                $urlData['examName'] 				= $trackData[9];
                $urlData['feesValue'] 				= $trackData[10];
            }    
        } else {
            $urlData['categoryId'] 				= $trackData[0];
            $urlData['subCategoryId'] 			= $trackData[1];
            $urlData['LDBCourseId'] 			= $trackData[2];
            $urlData['localityId'] 				= $trackData[3];
            $urlData['cityId'] 					= $trackData[4];
            $urlData['stateId'] 				= $trackData[5];
            $urlData['countryId'] 				= $trackData[6];
            $urlData['regionId'] 				= $trackData[7];
        }
		return $urlData;
	}

	public function setCookieCategoryPage($name, $value, $expire, $path, $domain, $secure, $httponly)
    {
        $expire = time()+1800;
        $path = '/';
        $domain = COOKIEDOMAIN;
        $bufferLimit = 10;
        $secure = false;
        $httponly = false;
        
        $cookieQueue = array();
        $flag_pop = true;
        $flag_cookie_set = false;
        
        $cookieVal = $_COOKIE['CatPgCks'];
        if ($cookieVal) {
            $cookieQueue = explode(",", $cookieVal);
        }
        
        if ($value == "" || $value == "none") {
            if ($_COOKIE[$name]) {
                for ($i=0; $i < sizeof($cookieQueue); $i++)
                {
                    if ($cookieQueue[$i] == $name) {
                        $cookieQueue[$i] = "";
                        break;
                    }
                }
                $cookieQueue = array_filter($cookieQueue);
                if($cookieQueue) {
                    $cookieValue = implode(",",$cookieQueue);
                    setcookie('CatPgCks', $cookieValue, 0, $path, $domain);
                    $_COOKIE['CatPgCks'] = $cookieValue;
                }
                setcookie($name, "", time()-60*60*24*360, $path, $domain);
                $_COOKIE[$name] = "";
                $sortByCookieName = str_replace("filters-", "sortby-", $name);
                setcookie($sortByCookieName, "", time()-60*60*24*360, $path, $domain);
            }
            return;
        }
        
        if (sizeof($cookieQueue) == 0) {
            array_push($cookieQueue, $name);
            $cookieValue = implode(",",$cookieQueue);
            setcookie('CatPgCks', $cookieValue, 0, $path, $domain);
            $_COOKIE['CatPgCks'] = $cookieValue;
        }
        else if (sizeof($cookieQueue) < $bufferLimit)
        {
            for ($i=0; $i < sizeof($cookieQueue); $i++)
            {
                if ($cookieQueue[$i] == $name) {
                    $cookieQueue[$i] = "";
                    //delete this cookie from browser
                    setcookie($cookieQueue[$i], "", time()-60*60*24*360, $path, $domain);
                    $_COOKIE[$cookieQueue[$i]] = "";
                    $sortByCookieName = str_replace("filters-", "sortby-", $cookieQueue[$i]);
                    setcookie($sortByCookieName, "", time()-60*60*24*360, $path, $domain);
                    break;
                }
            }
            $cookieQueue = array_filter($cookieQueue);
            
            array_push($cookieQueue, $name);
            $cookieValue = implode(",",$cookieQueue);
            setcookie('CatPgCks', $cookieValue, 0, $path, $domain);
            $_COOKIE['CatPgCks'] = $cookieValue;
        }
        else
        {
            for ($i=0; $i < sizeof($cookieQueue); $i++)
            {
                if ($cookieQueue[$i] == $name)
                {
                    $cookieQueue[$i] = "";
                    //delete this cookie from browser
                    setcookie($cookieQueue[$i], "", time()-60*60*24*360, $path, $domain);
                    $_COOKIE[$cookieQueue[$i]] = "";
                    $sortByCookieName = str_replace("filters-", "sortby-", $cookieQueue[$i]);
                    setcookie($sortByCookieName, "", time()-60*60*24*360, $path, $domain);
                    $flag_pop = false;
                    break;
                }
                else
                {
                    if (!$_COOKIE[$cookieQueue[$i]] || $_COOKIE[$cookieQueue[$i]] == "") {
                        $cookieQueue[$i] = "";
                        $sortByCookieName = str_replace("filters-", "sortby-", $cookieQueue[$i]);
                        setcookie($sortByCookieName, "", time()-60*60*24*360, $path, $domain);
                    }
                    else
                    {
                        $cookieVal = json_decode(base64_decode($_COOKIE[$cookieQueue[$i]]), true);
                        $flag_cookie_set = false;
                        foreach($cookieVal as $element)
                        {
                            if(sizeof($element) > 0)
                            {
                                $flag_cookie_set = true;
                                break;
                            }
                        }
                        if (!$flag_cookie_set) {
                            $cookieQueue[$i] = "";
                            setcookie($cookieQueue[$i], "", time()-60*60*24*360, $path, $domain);
                            $_COOKIE[$cookieQueue[$i]] = "";
                            $sortByCookieName = str_replace("filters-", "sortby-", $name);
                            setcookie($sortByCookieName, "", time()-60*60*24*360, $path, $domain);
                            $flag_pop = false;
                        }
                    }
                }
            }
            if ($flag_pop)
            {
                $temp = array_shift($cookieQueue);
                //delete this cookie from browser
                setcookie($temp, "", time()-60*60*24*360, $path, $domain);
                $_COOKIE[$temp] = "";
                $sortByCookieName = str_replace("filters-", "sortby-", $temp);
                setcookie($sortByCookieName, "", time()-60*60*24*360, $path, $domain);
            }
            $cookieQueue = array_values(array_filter($cookieQueue));
            array_push($cookieQueue, $name);
            $cookieValue = implode(",",$cookieQueue);
            setcookie('CatPgCks', $cookieValue, 0, $path, $domain);
            $_COOKIE['CatPgCks'] = $cookieValue;
        }
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        $_COOKIE[$name] = $value;
        //$sortByCookieName = str_replace("filters-", "sortby-", $name);
        //setcookie($sortByCookieName, "", time()-60*60*24*360, $path, $domain);
        
        $cookieVal = $_COOKIE['CatPgCks'];
    }
		
	/*
	 * Function to check if any institute exists in the provided category page or not Input : Category page request Return Type : Boolean : (TRUE, FALSE)
	 */
	public function isCategoryPageEmpty($request) {
        $this->init ();
        $this->CI->load->model('categoryList/CategoryPageModel');
        $categoryPageModel = new CategoryPageModel();
        
        $data['category_id']        = $request->getCategoryId();

        $subCategoryId              = $request->getSubCategoryId();
        if(!empty($subCategoryId)){
            $data['sub_category_id']    = $subCategoryId;
        }

        $ldbCourseId = $request->getLDBCourseId();
        if(!empty($ldbCourseId)){
            $data['LDB_course_id']      = $ldbCourseId;
        }

        $data['country_id']         = $request->getCountryId();
        $data['state_id']           = $request->getStateId();
        $data['city_id']            = $request->getCityId();
        $data['region_id']          = $request->getRegionId();
        $data['zone_id']            = $request->getZoneId();
        $data['locality_id']        = $request->getLocalityId();

        $examName = $request->getExamName();
        $feesValue = $request->getFeesValue();
        $affiliationValue = $request->getAffiliationName();
        
        if(!empty($examName)){
            $data['exam_value'] = $request->getExamName();
        }
        if(!empty($feesValue) && $feesValue > 0){
            $data['fees_value'] = $request->getFeesValue();
        }
        if(!empty($affiliationValue)){
            $data['affiliation_value'] = $request->getAffiliationName();
        }

        $result = $categoryPageModel->checkIfCategoryPageIsNonZero($data);
        
        return $result;
	}

    function getLocalitiesByCity($appId, $cityId){
        $this->init();
        $this->CI->xmlrpc->method('getLocalitiesByCity');
        $request = array($appId, $cityId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

    function getAllCitiesHavingLocalities($appId){
        $this->init();
        $this->CI->xmlrpc->method('getAllCitiesHavingLocalities');
        $request = array($appId);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            $response = $this->CI->xmlrpc->display_error();
            return $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

}
?>
