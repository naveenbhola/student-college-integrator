<?php
class MiscClient{
	var $CI_Instance;
    function init(){
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $this->CI_Instance->xmlrpc->server(MISC_SERVER_URL, MISC_SERVER_PORT);
    }

    function trackCommunications($appID, $recipients, $senderId, $senderDetail, $subject, $content, $product, $communicationMode) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sTrackCommunication');
        $request = array($appID, $recipients, $senderId, $senderDetail, $subject, $content, $product, $communicationMode);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
        }
        return $response;
    }

    function getCommunicationTracking($appId, $userIds, $trackBoth, $mode) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sGetCommunicationTracking');
        $request = array($appId, $userIds, $trackBoth, $mode);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
        }
        return $response;
    }
}
?>
