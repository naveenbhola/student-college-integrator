<?php

function track_visitor()
{
    global $uniqueVisitorId;
    global $visitSessionId;
    if(isset($_REQUEST['isApp']) && $_REQUEST['isApp'] == "yes"){
        if(isset($_REQUEST['visitorSessionId']) && !empty($_REQUEST['visitorSessionId'])){
            $uniqueVisitorId = $_REQUEST['visitorId'];
            $visitSessionId = $_REQUEST['visitorSessionId'];
            return;
        }
    }
    
    global $isNewVisitor;
    $isNewVisitor = FALSE;
    global $isNewSession;
    $isNewSession = FALSE;
    
    /**
     * Visitor Id
     * If visitorId cookie not set, it's new visitor/user, generate new visitorId.
     * If set, use existing visitorId
     */
    
    if(!isset($_COOKIE['visitorId']) || strlen($_COOKIE['visitorId']) == 0) {

        $uniqueVisitorId = generateUniqueVisitorId();
        $isNewVisitor = TRUE;

        // exclude some urls from setting cookie
        $flagSetCookie = TRUE;
        if($_SERVER['SCRIPT_URL'] == '/ofconversion'){
            $visitSessionId = generateVisitorSessionId();
            $isNewSession = TRUE;
            return;
        }
        
        setcookie('visitorId', $uniqueVisitorId, time() + 15552000, '/', COOKIEDOMAIN);   
        
    }
    else {
        $uniqueVisitorId = $_COOKIE['visitorId'];
    }
    
    /**
     * Visitor session id
     * Generate new session id if:
     *  - It's a new visitor
     *  - visitorSessionId and lastVisitTime cookies not set, either first time or these cookies expired after 30 min of inactivity
     *  - Date has changed, new request after midnight 12:00
     */
    if($isNewVisitor) {
        $isNewSession = TRUE;
    }
    else if(isset($_COOKIE['visitorSessionId']) && strlen($_COOKIE['visitorSessionId']) > 0) {
        if(isset($_COOKIE['lastVisitTime']) && strlen($_COOKIE['lastVisitTime']) > 0) {
                if(date('Y-m-d', $_COOKIE['lastVisitTime']) != date('Y-m-d')) {
                        $isNewSession = TRUE;
                }
        }
        else {
                $isNewSession = TRUE;
        }
    }
    else {
        $isNewSession = TRUE;
    }

    
    if($isNewSession) {
        $visitSessionId = generateVisitorSessionId();
    }
    else {
        $visitSessionId = $_COOKIE['visitorSessionId'];
        
        if(!ctype_alnum($visitSessionId)) {
            $visitSessionId = generateVisitorSessionId();
        }
    }
    
    setcookie('visitorSessionId', $visitSessionId, time() + (60*60*24*30*12), '/', COOKIEDOMAIN);
    setcookie('lastVisitTime', time(), time() + 1800, '/', COOKIEDOMAIN);
}

function generateUniqueVisitorId()
{
    $uniqueVisitorId = base_convert(microtime(TRUE), 10, 36).rand(1000, 9999);
    return $uniqueVisitorId;
}

function generateVisitorSessionId()
{
    $sessid = '';
    while (strlen($sessid) < 32) {
        $sessid .= mt_rand(0, mt_getrandmax());
    }
    
    $sessid .= generateUniqueVisitorId();
    $visitorSessionId = sha1(uniqid($sessid, TRUE));
    $visitorSessionId .= date('YmdHis');
    return $visitorSessionId;
}
