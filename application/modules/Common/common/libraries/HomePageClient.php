<?php
class HomePageClient{

    var $CI;
    var $cacheLib;
    function init(){
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
        $this->CI->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->server(HOMEPAGE_SERVER_URL, HOMEPAGE_SERVER_PORT);	
        $this->cacheLib = new cacheLib();
    }

    function getCategoryMainHomePage ($appId){
        $this->init();
        $key = md5('getCategoryMainHomePage'.$appId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE') {
            $this->CI->xmlrpc->method('sGetHomeMainCategoryPage');
            $request = array($appId); 
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response =  $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,2400,'Shiksha');
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }

    function getCountryMainHomePage($appId){
        $this->init();
        $key = md5('getCountryMainHomePage'.$appId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE') {
            $this->CI->xmlrpc->method('sGetHomeMainCountryPage');
            $request = array($appId); 
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response =  $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,2400,'Shiksha');
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }

    function getTestPrepMainHomePage($appId){
        $this->init();
        $key = md5('sGetHomeMainTestPrepPage'.$appId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE') {
            $this->CI->xmlrpc->method('sGetHomeMainTestPrepPage');
            $request = array($appId); 
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response =  $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,2400,'Shiksha');
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }

    function getProductsCountForHomePages($appId, $criteria) {
        $this->init();
        ksort($criteria);
        $key = md5('getProductsCountsForHomePages'.serialize($criteria));
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE') {
            $this->CI->xmlrpc->method('sGetProductsCountForHomePages');
            $request = array($appId , array($criteria, 'struct'));
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()) {
                return $this->CI->xmlrpc->display_error();
            } else {
                $productsCount = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$productsCount,2400,'Shiksha');
                return $productsCount;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }
}
?>
