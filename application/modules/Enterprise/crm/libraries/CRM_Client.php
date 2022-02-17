<?php

/**
 * Description of CRM_Client
 * @author ashish mishra
 */
class CRM_Client {

    private $CI;

    /**
     * init API 4 write DB call
     */
    function init() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
        global $ip;
        $server_url = "http://$ip/crm/CRM_Server";
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->timeout(6000);
        $this->CI->xmlrpc->server($server_url, 80);
        //echo $server_url;
    }

    /**
     * initread API for Read DB call
     */
    function initread() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
        global $searchIP;
        $server_url = "http://$searchIP/crm/CRM_Server";
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->timeout(6000);
        $this->CI->xmlrpc->server($server_url, 80);
    }

    function getPastResponses($userid = 0) {
        $this->init();
        $this->CI->xmlrpc->method('getPastResponses');
        $request = array($userid);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return json_decode($this->CI->xmlrpc->display_response(), true);
        }
    }

    function getAllocatedUser($prefArr) {
        $this->init();
        $this->CI->xmlrpc->method('getAllocatedUser');
        $request = array(json_encode($prefArr));
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getLoginInfo($userIdArr) {
        $this->init();
        $this->CI->xmlrpc->method('getLoginInfo');
        $request = array(json_encode($userIdArr));
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }


    function exportByCron($userid = "") {
        $this->init();
        $this->CI->xmlrpc->method('exportByCron');
        $request = array($userid);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function exportLeadid($userid) {
        $this->init();
        $this->CI->xmlrpc->method('exportLeadid');
        $request = array();
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getCounsellorList() {
        $this->initread();
        $this->CI->xmlrpc->method('getCounsellorList');
        $request = array();
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return json_decode($this->CI->xmlrpc->display_response(), true);
        }
    }

    function getPendingLeadsForResponse() {
        $this->initread();
        $this->CI->xmlrpc->method('getPendingLeadsForResponse');
        $request = array();
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return json_decode($this->CI->xmlrpc->display_response(), true);
        }
    }

    /* API that store CRM made response data locally .. later that data */

    function dumpCrmResponseData($uid, $listingid, $email, $mobile, $name, $status='to_be_processed', $counsellorid) {
        $this->init();
        $this->CI->xmlrpc->method('dumpCrmResponseData');
        $request = array($uid, $listingid, $email, $mobile, $name, 'to_be_processed', $counsellorid);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function responseSentSuccess($id, $status) {
        $this->init();
        $this->CI->xmlrpc->method('responseSentSuccess');
        $request = array($id, $status);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function responsesalreadymadeforuser($userid) {
        $this->init();
        $this->CI->xmlrpc->method('responsesalreadymadeforuser');
        $request = array($userid);
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            return $this->CI->xmlrpc->display_response();
        }
    }

}

