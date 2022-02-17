<?php

/*
 *     A InfoEdge Limited Property
 *     --------------------------- * 
 */

/**
 * Description of offline_client
 *
 * @author nawal
 */
class Offline_client {
    
    function init()
    {
            $this->CI = & get_instance();
            $this->CI->load->helper('url');
            $this->CI->load->library('xmlrpc');
            //$this->CI->load->library('cacheLib');
            //$this->cacheLib = new cacheLib();
            $this->CI->xmlrpc->set_debug(0);
            $this->CI->xmlrpc->server(OFFLINE_OPS_SERVER_URL, OFFLINE_OPS_SERVER_PORT);
    }
    
    function updateOfflineOpsTable($userId = null){
        if(!$userId){
            return false;
        }
        $this->init();
        $this->CI->xmlrpc->method('updateOfflineOpsTable');
        $request = array($userId);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return ($this->CI->xmlrpc->display_error());
        }else{
            $response = base64_decode($this->CI->xmlrpc->display_response());
            return $response;
        }
    }
}
