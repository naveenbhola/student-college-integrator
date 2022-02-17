<?php

/**
 * File for handling Power User
 *
 */

/**
 * Class for Power User
 *
 */
///qna rehash phase-2 part-2 start////
class Poweruser_client {
    /**
     * Variable for Code Ignitor instance
     * @var object
     */
    var $CI = '';
    
    /**
     * Init function for initialization
     */
    function init() { error_log("poweruserclient".print_r(POWERUSER_SERVER_URL,true));
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $this->CI_Instance->xmlrpc->server(POWERUSER_SERVER_URL, POWERUSER_SERVER_PORT);
    }
    
    /**
     * Function to get the power user info
     *
     * @param integer $appId
     * @param integer $loggedUserId
     * @param integer $start
     * @param integer $count
     * @param string $module
     * @param string $status
     * @param string $userIdFieldData
     * @param string $userEmailFieldData
     * @param string $userminReputationPointFieldData
     * @param string $usermaxReputationPointFieldData
     * @param string $searchTypeVal
     * @param string $filterVal
     */
    function getPowerUserInfo($appId,$loggedUserId,$start,$count,$module,$status='',$userIdFieldData='',$userEmailFieldData='',$userminReputationPointFieldData='',$usermaxReputationPointFieldData='',$searchTypeVal,$filterVal='') {
        $this->init();
        $this->CI_Instance->xmlrpc->method('getPowerUserInfo');
        $request = array($appId,$loggedUserId,$start,$count,$module,$status,$userIdFieldData,$userEmailFieldData,$userminReputationPointFieldData,$usermaxReputationPointFieldData,$searchTypeVal,$filterVal);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }
    
    /**
     * Function to insert the Power User Details
     *
     * @param integer $appId
     * @param integer $userId
     * @param integer $newLevelOfUser
     * @param string $email
     * @param string $displayname
     */
    function insertPowerUserDetails($appId,$userId,$newLevelOfUser,$email,$displayname) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('insertPowerUserDetails');
        $request = array($appId,$userId,$newLevelOfUser,$email,$displayname);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }
    
     /**
     * Function to delete the Power User Details
     *
     * @param integer $appId
     * @param integer $userId
     */
    function deletePowerUserLevel($appId,$userId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('deletePowerUserLevel');
        $request = array($appId,$userId);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }

    /**
     * Function to change the Linked question status
     *
     * @param integer $appId
     * @param integer $id
     * @param string $newstatus
     * @param integer $msgId
     * @param integer $linkedQuestionId
     */
    function changeLinkedQuestionStatus($appId,$id,$newstatus,$msgId,$linkedQuestionId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('changeLinkedQuestionStatus');
        $request = array($appId,$id,$newstatus,$msgId,$linkedQuestionId);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }

    /**
     * Function to insert question into Discussion linking table
     *
     * @param integer $appId
     * @param integer $linkedQuestionId
     * @param integer $mainQuestionId
     * @param integer $userId
     * @param integer $questionOwerId
     * @param string $entityType
     *
     */
    function insertIntoQuestionDiscussionLinkingTable($appId,$linkedQuestionId,$mainQuestionId,$userId,$questionOwerId,$entityType) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('insertIntoQuestionDiscussionLinkingTable');
        $request = array($appId,$linkedQuestionId,$mainQuestionId,$userId,$questionOwerId,$entityType);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }
    
    
    /**
     * Function to get the linked question
     *
     * @param integer $appId
     * @param integer $startFrom
     * @param integer $count
     * @param string $filter
     * @param string $userNameFieldData
     * @param string $userLevelFieldData
     */
    function getLinkedQuestion($appId,$startFrom,$count,$filter='',$userNameFieldData='',$userLevelFieldData='') { error_log("finalquestionlinkedarray client ");
        $this->init();
        $this->CI_Instance->xmlrpc->method('getLinkedQuestion');
        $request = array($appId,$startFrom,$count,$filter,$userNameFieldData,$userLevelFieldData);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }
        
     /**
     * Function to unlink question
     * 
     * @param integer $appId
     * @param integer $linkedEntityId
     * @param integer $linkingEntityId
     */
    function unlinkedQuestion($appId,$linkedEntityId,$linkingEntityId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('unlinkedQuestion');
        $request = array($appId,$linkedEntityId,$linkingEntityId);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }
    
        
     /**
     * Function to make the sticky discussion announcement
     *
     * @param integer $appId
     * @param integer $powerUserId
     * @param integer $stickyMsgId
     * @param integer $stickyThreadId
     * @param integer $categoryId
     * @param string $entityType
     * @param string $status
     *
     */
    function makeStickyDiscussionAnnouncement($appId,$powerUserId,$stickyMsgId,$stickyThreadId,$categoryId,$entityType,$status){
        $this->init();
        $this->CI_Instance->xmlrpc->method('makeStickyDiscussionAnnouncement');
        $request = array($appId,$powerUserId,$stickyMsgId,$stickyThreadId,$categoryId,$entityType,$status);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }
    
    
     /**
     * Function to get the linked Discussion
     *
     * @param integer $appId
     * @param integer $startFrom
     * @param integer $count
     * @param string $filter
     * @param string $userNameFieldData
     * @param string $userLevelFieldData
     *
     */
    function getLinkedDiscussion($appId,$startFrom,$count,$filter='',$userNameFieldData='',$userLevelFieldData='') { error_log("finalquestionlinkedarray client ");
        $this->init();
        $this->CI_Instance->xmlrpc->method('getLinkedDiscussion');
        $request = array($appId,$startFrom,$count,$filter,$userNameFieldData,$userLevelFieldData);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }
    
     /**
     * Function to change the linked discussion status
     *
     * @param integer $appId
     * @param integer $id
     * @param string $newstatus
     * @param integer $msgId
     * @param integer $linkedDiscussionId
     *
     */
    function changeLinkedDiscussionStatus($appId,$id,$newstatus,$msgId,$linkedDiscussionId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('changeLinkedDiscussionStatus');
        $request = array($appId,$id,$newstatus,$msgId,$linkedQuestionId);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }
    
    /**
     * Function to make the discussion unsticky if they are linked
     *
     * @param integer $appId
     * @param string $newstatus
     * @param integer $msgId
     * @param integer $linkedDiscussionId
     *
     */
    function makeDiscussionUnStickyifItIsLinked($appId,$newstatus,$msgId,$linkedDiscussionId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('makeDiscussionUnStickyifItIsLinked');
        $request = array($appId,$newstatus,$msgId,$linkedDiscussionId);

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        }else {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }
   
}
///qna rehash phase-2 part-2 end////
?>