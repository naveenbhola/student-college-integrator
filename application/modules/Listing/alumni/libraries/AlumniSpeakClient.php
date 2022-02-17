<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 103           $:  Revision of last commit
$Author: build $:  Author of last commit
$Date: 2010-01-06 10:42:10 $:  Date of last commit

listing_client.php makes call to server using XML RPC calls.

$Id: AlumniSpeakClient.php,v 1.3 2010-01-06 10:42:10 build Exp $: 

*/

class AlumniSpeakClient{
	var $CI_Instance;
    function init(){
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $this->CI_Instance->xmlrpc->server(ALUMNI_SPEAK_SERVER_URL, ALUMNI_SPEAK_SERVER_PORT);
    }

    function getFeedBackCriterias($appID) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sgetFeedbackCriterias');
        $request = array($appID);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
            $response = json_decode(base64_decode($response), true);
        }
        return $response;
    }

    function insertAlumnusFeedBack($appID,$feedback){
        $this->init();
        $this->CI_Instance->xmlrpc->method('sinsertAlumnusFeedBack');
        $request = array($appID, $feedback);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
        }
        return $response;
    }

    function getFeedbackList($appID, $criteria, $sort, $pageNum, $numRecords, $courseId="") {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sgetFeedbackList');
        $request = array($appID, $criteria, $sort, $pageNum, $numRecords, $courseId);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
            $response = json_decode(base64_decode($response), true);
        }
        return $response;
    }

    function getFeedbacksForInstitute($appID, $instituteId, $sort, $courseId = 0) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sgetFeedbacksForInstitute');
        $request = array($appID, $instituteId, $sort, $courseId);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
            $response = json_decode(base64_decode($response), true);
        }
        return $response;
    }

    function updateReviewStatus($appId, $instituteId, $criteriaId, $email, $status) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('supdateReviewStatus');
        $request = array($appId, $instituteId, $criteriaId, $email, $status);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
        }
        return $response;
    }

    function getExcludedCourses($appId, $instituteId, $criteriaId, $email, $status) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sgetExcludedCourses');
        $request = array($appId, $instituteId, $criteriaId, $email, $status);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
            $response = json_decode(base64_decode($response), true);
        }
        return $response;
    }

    function setExcludedCourses($appId, $instituteId, $criteriaId, $email, $courseList) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('ssetExcludedCourses');
        $request = array($appId, $instituteId, $criteriaId, $email, $courseList);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
        }
        return $response;
    }

    function checkReviewStatusMail($appId, $instituteId, $status) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('scheckReviewStatusMail');
        $request = array($appId, $instituteId, $status);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
            $response = json_decode(base64_decode($response), true);
        }
        return $response;
    }

    function insertThreadId($appId, $instituteId,$criteria_id, $email, $thread_id){
        $this->init();
        $this->CI_Instance->xmlrpc->method('sinsertThreadId');
        $request = array($appId, $instituteId,$criteria_id, $email, $thread_id);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
        }
        return $response;

     }

    function getRepliesForInstitute($appID, $threadIdList, $userId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sgetRepliesForInstitute');
        $request = array($appID, $threadIdList, $userId);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()) {
            $response = $this->CI_Instance->xmlrpc->display_error();
        } else {
            $response = $this->CI_Instance->xmlrpc->display_response();
            $response = json_decode(base64_decode($response), true);
        }
        return $response;
    }

}
?>
