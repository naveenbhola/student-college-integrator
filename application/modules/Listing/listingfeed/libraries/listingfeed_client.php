<?php

   /**
    * Class listingfeed_client for the shiksha
    *
    * @package listingfeed_client
    * @author shiksha team
    */
class Listingfeed_client{

    var $CI_Instance;
    function init() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->helper ('url');
        $this->CI->load->library('xmlrpc');
        global $ip;
        $server_url = "http://$ip/listingfeed/listingfeed_server";
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->timeout(6000);
        $this->CI->xmlrpc->server($server_url,80);
    }

    function initread(){
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->helper ('url');
        $this->CI->load->library('xmlrpc');
        global $searchIP;
        $server_url = "http://$searchIP/listingfeed/listingfeed_server";
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->timeout(6000);
        $this->CI->xmlrpc->server($server_url,80);
    }

   /**
    * runCronJob
    * @return string
    * @param int $appId
    * @param int $limit
    * @param string $type
    * @param string $time
    * @return string
    */

    function runCronJob($appId,$type,$time,$flag,$queryCmd_offset,$queryCmd_rows) {
        $this->initread();
        if ($type == 'institute') {
        $this->CI->xmlrpc->method('runCronJobListingFeed');
        } elseif($type == 'event') {
        $this->CI->xmlrpc->method('runCronJobEventFeed');
        }
        $request = array($appId,$time,$flag,$queryCmd_offset,$queryCmd_rows);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return ($this->CI->xmlrpc->display_response());
        }
    }

    function ResultCountSet($appId,$time) {
        $this->initread();
        $this->CI->xmlrpc->method('ResultCountSet');
        $request = array($appId,$time);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return ($this->CI->xmlrpc->display_response());
        }
    }
}        