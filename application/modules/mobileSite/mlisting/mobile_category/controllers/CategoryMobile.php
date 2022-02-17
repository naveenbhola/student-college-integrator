<?php

class CategoryMobile extends ShikshaMobileWebSite_Controller
{     	
	function __construct()
	{
		parent::__construct();
		$this->load->config('mcommon/mobi_config');
		$this->load->library('category_list_client');
		$this->config->load('categoryPageConfig');
	}      

	//$params == string of parameters needed for category page.
	//fetches data and passes to view for rendering the category page.	
	function categoryPage($params, $newUrlFlag = false)
	{
		ob_start();
		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->builder('LocationBuilder','location');
		
		//$categoryPageBuilder = new CategoryPageBuilder($params);
		$categoryPageBuilder 	= new CategoryPageBuilder($params, $newUrlFlag);

                //If study abroad category page, redirect the user to the new Category page
                if($categoryPageBuilder->getRequest()->isStudyAbroadPage())
                {
                    $this->redirectToNewAbroadCategoryPage($categoryPageBuilder);
                }

		$categoryBuilder = new CategoryBuilder;
                if(CP_SOLR_FLAG) {
                        $displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
                } else {
                        $displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
                }

		$catPageRequestValidateLib = $this->load->library('categoryList/CategoryPageRequestValidations');
		$LDBCourseBuilder = new LDBCourseBuilder;
		$locationBuilder = new LocationBuilder;
		$request = $displayData['categoryPage']->getRequest();
		$catPageRequestValidateLib->redirectIfInvalidRequestParamsExist($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder);
			
			$locationRepository = $locationBuilder->getLocationRepository();
			$countriesArray = $locationRepository->getCountriesByRegion($request->getRegionId());
			$countryStr = "";
			foreach($countriesArray as $country){
			    $countryStr .= $country->getId() . ",";
			}
			if(count($countriesArray) == 0)
			{
			    $urlRequest = clone $request;
			    $countryStr = $urlRequest->getCountryId()  . ",";
			}
			storeTempUserData("countriesArray",$countryStr);		
		if(empty($request))
		{
			$error = '$displayData[\'categoryPage\']->getRequest();';
			$function = "categoryPage function in CategoryMobile controller";
			sendMailAlert("data not coming from backend issue in".$error."in".$function, "Category mobile controller Issue","vikas.k@shiksha.com");
		}
		$this->checkForRedirection($displayData['categoryPage']);		
		$displayData['categoryRepository'] = $categoryBuilder->getCategoryRepository();
		
		$displayData['locationRepository'] = $locationBuilder->getLocationRepository();
		$subCategory = $displayData['categoryRepository']->find($request->getSubCategoryId());
	
	
		$displayData['request'] = $request;
		$displayData['subCategory']=$subCategory;
		$displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		if(empty($displayData['institutes']))
		{
			$error = '$displayData[\'categoryPage\']->->getInstitutes();';
			$function = "categoryPage function in CategoryMobile controller";
//			sendMailAlert("data not coming from backend issue in".$error."in".$function, "Category mobile controller Issue","vikas.k@shiksha.com");
		}

		$this->_checkForRedirectionForNewURLPattern($displayData['categoryPage']);
		/************Meta data for Seo ************/
		$metaData = $request->getMetaData();		
		$displayData['m_meta_title'] = $metaData['title'];
		$displayData['m_meta_description'] = $metaData['description'];
		$displayData['m_meta_keywords']= $metaData['keywords'];
		$displayData['m_canonical_url']= $request->getCanonicalURL($request->getPageNumberForPagination());
		$displayData['mobile_website_pagination_count'] = $this->config->item('mobile_website_pagination_count');
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$displayData['subcategoriesChoosenForRNR'] 	= $RNRSubcategories;
		$displayData['categoryPageTypeFlag']    	= $newUrlFlag;
		
		if(!$displayData['request']->isStudyAbroadPage())
		{
			$displayData['boomr_pageid'] = "category_listing";
			$this->load->view('mobileCategoryPage',$displayData);
		}else{
			$category_data = $displayData['categoryRepository']->find($request->getCategoryId());
			$displayData['category_data']=$category_data;
			$displayData['boomr_pageid'] = "category_listing";
			$this->load->view('mobileCategoryPageSA',$displayData);
		}
	}

        // Re-Direct To New Aborad Category Page
        public function redirectToNewAbroadCategoryPage(CategoryPageBuilder $categoryPageBuilder){
            $abroadCategoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');
            $this->load->config('categoryList/studyAbroadRedirectionConfig');
	    $this->load->builder('LocationBuilder','location');

            $studyAbroadParentCategoryIdMappings = $this->config->item('studyAbroadParentCategoryIdMappings');
            $studyAbroadSubcategoryIdMappings = $this->config->item('studyAbroadSubcategoryIdMappings');
            $studyAbroadRegionIdCountryMappings = $this->config->item('studyAbroadRegionIdCountryMappings');
            //$studyAbroadDomainCountryMappings = $this->config->item('studyAbroadDomainCountryMappings');
            $categoryId = $categoryPageBuilder->getRequest()->getCategoryId();
            $subCategoryId = $categoryPageBuilder->getRequest()->getSubCategoryId();
            $countryId = $categoryPageBuilder->getRequest()->getCountryId();
            $regionId = $categoryPageBuilder->getRequest()->getRegionId();
            if( $categoryId == 1 && $subCategoryId == 1){
                if( $countryId > 2){
                    $locationBuilder = new LocationBuilder();
                    $locationRepository = $locationBuilder->getLocationRepository();
                    $country = $locationRepository->getAbroadCountryByIds(array($countryId));
                    if(!empty($country)){
                        $url = $abroadCategoryPageRequest->getURLForCountryPage($countryId);
                        redirect($url, 'location', 301);
                    }
                }else{
                    $url = $abroadCategoryPageRequest->getURLForCountryPage($studyAbroadRegionIdCountryMappings[$regionId]);
                    redirect($url, 'location', 301);
                }
            }

            $data = array();
            if($categoryPageBuilder->getRequest()->isSubcategoryPage()){
                $data['subCategoryId'] = $studyAbroadSubcategoryIdMappings[$subCategoryId]['id'];
                $data['courseLevel'] = $studyAbroadSubcategoryIdMappings[$subCategoryId]['defaultLevel'];
                if($data['subCategoryId'] == ""){
                    $data['subCategoryId'] = 1;
                    $data['categoryId'] = $studyAbroadSubcategoryIdMappings[$subCategoryId]['parentId'];
                }
            }

            if($categoryPageBuilder->getRequest()->isMainCategoryPage()){
                $data['categoryId'] = $studyAbroadParentCategoryIdMappings[$categoryId]['id'];
                $data['subCategoryId'] = 1;
                $data['courseLevel'] = $studyAbroadParentCategoryIdMappings[$categoryId]['defaultLevel'];
            }

            if($countryId > 2){
                $locationBuilder = new LocationBuilder();
                $locationRepository = $locationBuilder->getLocationRepository();
                $country = $locationRepository->getAbroadCountryByIds(array($countryId));
                if(!empty($country)){
                    $data['countryId'] = array($countryId);
                }
            }

            if(!isset($data['countryId']) && $regionId){
                $data['countryId'] = array($studyAbroadRegionIdCountryMappings[$regionId]);
            }
            $abroadCategoryPageRequest->setData($data);

            $url = $abroadCategoryPageRequest->getURL();
            redirect($url, 'location', 301);
        }


	//if user already selects the location before, then updating his location preference.
	function checkForRedirection($categoryPage)
	{
		$request = $categoryPage->getRequest();
		if(!$request->isStudyAbroadPage()){
			if(isset($_COOKIE['userCityPreference'])){
				$location = explode(":",$_COOKIE['userCityPreference']);
				$request->setData(array('cityId'=>$location[0],'stateId'=>$location[1]));
				$categoryPage->setRequest($request);
			}
		}
		if($request->isStudyAbroadPage()){
	        	if(isset($_COOKIE['catIdCookie'])){
		   		$request->setData(array('categoryId'=>$_COOKIE['catIdCookie'],'subCategoryId'=>1,'LDBCourseId'=>1));	
				$categoryPage->setRequest($request);
			}
			
			if(isset($_COOKIE['regionId_countryIdCookie']))
			{
				$regionId = $request->getRegionId();
				$regionCountryCookieArray = explode("|",$_COOKIE['regionId_countryIdCookie']);
				//_p($regionId);die;
				if($regionCountryCookieArray[0]==$regionId)
				{
		   			$request->setData(array('countryId'=>$regionCountryCookieArray[1]));	
					$categoryPage->setRequest($request);
				}
							
			}
			
			/*if(isset($_COOKIE['countryId'])){
		   		$request->setData(array('countryId'=>$_COOKIE['countryId']));	
				$categoryPage->setRequest($request);
			}*/		
	        }
	}
	
	private function _checkForRedirectionForNewURLPattern($categoryPage)
	{
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$request = $categoryPage->getRequest();
		if( ( !$request->isStudyAbroadPage()) &&
			( in_array($request->getSubCategoryId(), $RNRSubcategories)) &&
			( $request->getNewURLFlag() != 1 )
		) 
		{
			$clonedRequest = clone $request;
			$url = $clonedRequest->getURL();
			 
			$URLData['categoryId'] 			= (int) $clonedRequest->getCategoryId();
			$URLData['subCategoryId'] 		= (int) $clonedRequest->getSubCategoryId();
			$URLData['LDBCourseId'] 		= (int) $clonedRequest->getLDBCourseId();
			$URLData['localityId'] 			= (int) $clonedRequest->getLocalityId();
			$URLData['zoneId'] 				= (int) $clonedRequest->getZoneId();
			$URLData['cityId'] 				= (int) $clonedRequest->getCityId();
			$URLData['stateId'] 			= (int) $clonedRequest->getStateId();
			$URLData['countryId'] 			= (int) $clonedRequest->getCountryId();
			$URLData['regionId'] 			= (int) $clonedRequest->getRegionId() ? $clonedRequest->getRegionId() : 0;
			$URLData['sortOrder'] 			= $clonedRequest->getSortOrder() !="" ? $clonedRequest->getSortOrder() : 'none';
			$URLData['pageNumber'] 			= (int) $clonedRequest->getPageNumberForPagination() != "" ? $clonedRequest->getPageNumberForPagination() : 1;
			$URLData['naukrilearning'] 		= (int) $clonedRequest->isNaukrilearningPage() ? 1 : 0;
			
			$this->load->library('categoryList/CategoryPageRequest');
			$categoryPageRequest = new CategoryPageRequest;
			$categoryPageRequest->setNewURLFlag(1); //New RNR URLS
			$categoryPageRequest->setData($URLData);
			$newRNRURL = $categoryPageRequest->getURL();
			if($newRNRURL === FALSE){
				show_404();
				exit();
			} else {
				Header( "HTTP/1.1 301 Moved Permanently" );
				Header( "Location: $newRNRURL");
				exit();
			}
		}
	}
}
