<?php

class NewCategoryPageURLCtrl extends MX_Controller
{

	private $CI;
	public function test($url)
	{
			$this->CI =& get_instance();

			$this->CI->load->library("CategoryPageURLManager");
			$catPageUrlMgrObj = new CategoryPageURLManager();
	
			echo "URL : <b>".$url."</b><br>";
			// if URL is parsed successfully by the parser
			if( $catPageUrlMgrObj->URLParser($url) )
			{
				$parsedURLObj  = $catPageUrlMgrObj->getURLRequestData();
				_p($parsedURLObj);
				_p($catPageUrlMgrObj->getURL());
				_p($catPageUrlMgrObj->getMetaData());
			}

	} 	

	public function testZeroResult($url, $type = false)
	{
		$this->load->library(array("Category_list_client"));
		$this->load->library(array('categoryList/categoryPageRequest','listing/listing_client'));
		
		//_p($request->getVars());
		
		if($type)
			$request = new CategoryPageRequest($url,"RNRURL");
		else
			$request = new CategoryPageRequest($url);
			
		$obj = new Category_list_client();
		$obj->init();
		var_dump($obj->isCategoryPageEmpty($request));
		
	}
public function getCatPageURLFromKey($key,$type)
	{
		$this->load->library(array('categoryList/categoryPageRequest','listing/listing_client'));

		$request = new CategoryPageRequest($url);
		
		if($type)
			$request->setNewURLFlag(1);
			
		$request->setDataByPageKey($key);
		
		_p($request->getURL());
		//
		//$this->config->load('categoryPageConfig');
		//$feeRange = $this->config->item("CP_FEES_RANGE");
		//$feeRange = $feeRange["RS_RANGE_IN_LACS"];
		//_p($feeRange);
		////_p(array_keys($feeRange));
		//
		//$ranges = array_keys($feeRange);
		//$index = array_search(500000, $ranges);
		////_p($index);
		////_p(array_slice($ranges, 0, $index));
		//
		//$this->load->library('CategoryPageZeroResultHandler');
		//$handler = new CategoryPageZeroResultHandler();
		//$rowset = array(array('subCategoryId' => 23, 'final_fees'=>100000)
		//		//,array('subCategoryId' => 56, 'final_fees'=>1000000)
		//		);
		//
		//_p($handler->_createCategoryPageCombinationsForFees($rowset));

		
	}
};
