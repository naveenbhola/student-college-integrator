<?php

class Sums_Product_client
{
	var $CI="";
	function init()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/sums/Product_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}

	function initread()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/sums/Product_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	function getDerivedProducts($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getDerivedProducts');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getProductFeatures($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetProductFeatures');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getProductsForUser($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetProductsForUser');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function productConsume($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('sproductConsume');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getAllBaseProductProperties($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getAllBaseProductProperties');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getAllCurrency($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getAllCurrency');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}


	function submitBaseProductForm($baseProductArray,$propertyArray,$priceArray)
		{
		$this->init();
		$this->CI->xmlrpc->method('sSubmitBaseProductForm');
		$requestArr = array (
			array($baseProductArray,'struct'),
			array($propertyArray,'struct'),
			array($priceArray,'struct')
		);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getBaseProductList($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetBaseProductList');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getAllDerivedProductProperties($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getAllDerivedProductProperties');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getDurationTypeForBase($baseProductId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetDurationTypeForBase');
		$requestArr = array($baseProductId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getSuggestedPrice($requestArray)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetPriceSugestionForBase');
		$requestArr = array(array($requestArray,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function submitDerivedProductForm($derivedProductArray,$derivedProductPropertyArray,$derivedProductPriceArray,$derivedProductElementArray)
		{
		$this->init();
		$this->CI->xmlrpc->method('sSubmitDerivedProductForm');
		$requestArr = array (
			array($derivedProductArray,'struct'),
			array($derivedProductPropertyArray,'struct'),
			array($derivedProductPriceArray,'struct'),
			array($derivedProductElementArray,'struct')
		);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getCategoriesForDerived($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getCategoriesForDerived');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getAllSumsParameters($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetAllSumsParameters');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getDerivedProductProperties($derivedProductId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetDerivedProductProperties');
		$requestArr = array($derivedProductId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

		}

	function getPropertiesOfThisDerived($derivedProdId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetPropertiesOfThisDerived');
		$requestArr = array($derivedProdId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

		}

	function getAllBaseProdsForDerived($derivedProdId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetAllBaseProdsForDerived');
		$requestArr = array($derivedProdId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

		}

	function getFreeDerivedId($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetFreeDerivedId');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function getAllSubscriptionsForUser($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetAllSubscriptionsForUser');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
    function getAllSubscriptionsForUserLDB($appId,$request)
        {
        $this->initread();
        $this->CI->xmlrpc->method('sgetAllSubscriptionsForUserLDB');
        $requestArr = array (array($appId,'int'),array($request,'struct'));
        $this->CI->xmlrpc->request($requestArr);
        if (!$this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
        }
	function getAllDerivedProducts($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getAllDerivedProducts');
		$requestArr = array($appId,'int');
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}

	function updateDerivedProdStatus($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('supdateDerivedProdStatus');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

        function getAllPseudoSubscriptionsForUser($appId,$request)
	{
		$this->initread();
                $this->CI->xmlrpc->method('getAllPseudoSubscriptionsForUser');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
         function getSalesPersonWiseClientList() {

		$this->initread();
                $this->CI->xmlrpc->method('getSalesPersonWiseClientList');
		$requestArr = array();
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}

       }

    function getSalesDataByClientId($appId,$client_id) {
        $this->initread();
        $this->CI->xmlrpc->method('getSalesDataByClientId');
        $requestArr = array (array($appId,'int'),array($client_id,'int'));
        $this->CI->xmlrpc->request($requestArr);
        if (!$this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getOldActiveSubscriptionClients($appId) {
        $this->initread();
        $this->CI->xmlrpc->method('getOldActiveSubscriptionClients');
        $requestArr = array (array($appId,'int'));
        $this->CI->xmlrpc->request($requestArr);
        if (!$this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

}
?>
