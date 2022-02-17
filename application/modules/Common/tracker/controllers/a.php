<?php

class Tracker extends CI_Controller
{
    private $_redis;
    
    private function _initRedis()
    {
        $this->config->load('redis');   
        $redisServer = $this->config->item('redis_server');
	//error_log("before	 redis load");
        $this->load->redis();

        $this->_redis = new Predis_Client($redisServer);
    }
        
    function trackClicks()
    {
       error_log("here in trackClick");
        $this->_initRedis();
        
       // error_log("after init trackClick");
        
        $pageType = 'Marketing_MMP';
        $pageId = $_POST['pageid'];
        //$pageURL = 'http://localshiksha.com/marketing/Marketing/index/pageID/86';
        
	//$pageURL = $_POST['add'];
	$timestamp = date('Y-m-d H:i:s');
        
        $x = $_POST['xCord'];
        $y = $_POST['yCord'];
    
        $positionClickData = array(
            'x' => $x,
            'y' => $y,
            'timestamp' => $timestamp
        );
        $this->_trackClickPosition('positionClickTracker',$pageType,$pageId,$positionClickData);
        
        if($formId = $_POST['formId']) {
            
            
            $formClickData = array(
                'inputType' => $_POST['inputType'],
                'inputName' => $_POST['inputName'],
                'inputId' => $_POST['inputId'],
                'inputValue' => $_POST['inputValue'],
                'fieldOrder' => $_POST['fieldOrder'],
                'sessionId'  => sessionId().date('Ymd'),
                'timestamp' => $timestamp
            );
error_log("28june " . print_r($formClickData, true));
            $this->_trackFormClick('formClickTracker',$pageType,$pageId,$formId,$formClickData);
        }
    }
    
    private function _registerNameSpaces($namespace,$pageType,$pageId,$date,$formId=0)
    {
        if(!$this->_redis->sismember($namespace.':pages',$pageType)) {
            $this->_redis->sadd($namespace.':pages',$pageType);
        }    
        
        if(!$this->_redis->sismember($namespace.':page:'.$pageType,$pageId)) {
            $this->_redis->sadd($namespace.':page:'.$pageType,$pageId);
        }
        
        if($formId) {
        
            if(!$this->_redis->sismember($namespace.':page:'.$pageType.':'.$pageId,$formId)) {
                $this->_redis->sadd($namespace.':page:'.$pageType.':'.$pageId,$formId);
            }
            
            if(!$this->_redis->sismember($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':set:dates',$date)) {
                $this->_redis->sadd($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':set:dates',$date);
                $this->_redis->rpush($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':list:dates',$date);
            }
        }
        else {
            
            if(!$this->_redis->sismember($namespace.':page:'.$pageType.':'.$pageId.':set:dates',$date)) {
                $this->_redis->sadd($namespace.':page:'.$pageType.':'.$pageId.':set:dates',$date);
                $this->_redis->rpush($namespace.':page:'.$pageType.':'.$pageId.':list:dates',$date);
            }
        }
    }
    
    function trackFormSubmit($pageType,$pageId)
    {
        $this->_initRedis();       
        $timestamp = date('Y-m-d H:i:s');
        $namespace = 'formSubmitTracker';
        $date = date("Ymd");
        $formId = $_POST['formId'];
            
        if($formId) {
            
            $this->_registerNameSpaces($namespace,$pageType,$pageId,$date,$formId);
        
                        
            $nextId = $this->_redis->incr($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');
            $this->_redis->hset($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$nextId,'sessionId',sessionId().date('Ymd'));
            $this->_redis->hset($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$nextId,'timestamp',$timestamp);
        }
    }
    
    function trackFormErrors( $pageType,$pageId )
    {
        $this->_initRedis();
        $timestamp = date('Y-m-d H:i:s');
        $namespace = 'formErrorTracker';
        $date = date("Ymd");
        $formId = $_POST['formId'];
            
        if($formId) {
            
            $formErrors = array();
            $formErrorData = $_POST['attributes'];
            $formErrorDataArray = explode('!$-$!',$formErrorData);
            foreach($formErrorDataArray as $formErrorDataValue) {
                if(trim($formErrorDataValue)) {
                    list($fieldId,$errorMsg) = explode(':',$formErrorDataValue);
                    $formErrors[$fieldId] = $errorMsg;
                }
            }
            
            $this->_registerNameSpaces($namespace,$pageType,$pageId,$date,$formId);
            
             
            $nextId = $this->_redis->incr($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');            
            $this->_redis->hset($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$nextId,'sessionId',sessionId().date('Ymd'));
            $this->_redis->hset($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$nextId,'timestamp',$timestamp);
            
            foreach($formErrors as $fieldId => $errorMsg) {
                $this->_redis->hset($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$nextId.":errors",$fieldId,$errorMsg);
                
                if(!$this->_redis->sismember($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':fields',$fieldId)) {   
                   $this->_redis->sadd($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':fields',$fieldId);
                }
                $this->_redis->incr($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$fieldId.'.count');
            }
        }
    }
    
    private function _trackClickPosition($namespace,$pageType,$pageId,$data)
    {
        $date = date("Ymd");
        error_log("data :" . print_r($data, true));
        $this->_registerNameSpaces($namespace,$pageType,$pageId,$date);
        
        error_log("in trackclickposition");
    
        $nextId = $this->_redis->incr($namespace.'.'.$pageType.'.'.$pageId.'.'.$date.'.id');
        error_log("in trackclickposition after incr");
        
        foreach($data as $key => $value) {
            error_log("namespace: " . $namespace.":".$pageType.":".$pageId.":".$date.":".$nextId);
            $d = $this->_redis->hset($namespace.":".$pageType.":".$pageId.":".$date.":".$nextId,$key,$value);
            error_log("d: " . print_r($d, true));
        }
    }
    
    private function _trackFormClick($namespace,$pageType,$pageId,$formId,$data)
    {
        $date = date("Ymd");
        
        $this->_registerNameSpaces($namespace,$pageType,$pageId,$date,$formId);
        
        $nextId = $this->_redis->incr($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');
        
        foreach($data as $key => $value) {
            $this->_redis->hset($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$nextId,$key,$value);
        }
        
        if(!$this->_redis->sismember($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':fields',$data['inputId'])) {
            $this->_redis->sadd($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':fields',$data['inputId']);
            $this->_redis->hset($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':fieldData:'.$data['inputId'],"id",$data['inputId']);
            $this->_redis->hset($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':fieldData:'.$data['inputId'],"order",$data['fieldOrder']);
        }
        
        $this->_redis->incr($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$data['inputId'].'.count');
    }
    
   
    function loadPixelMap($pageType,$date1,$date2,$pageId)
    {
        $this->_initRedis();
        if($date1=="null")
          $date1 = date("Ymd");               
        if($date2=="null")
          $date2 = date("Ymd");               
        $date = date("Ymd");          
        $date =  $date1;
        $pixelMapHtml = "<div id='clickmap'>";
        
        $i = 0;
	while( (strtotime($date)) <= (strtotime($date2)) )
        {
		   
		    //error_log("ira in loop date : " . $date);        
		    $count = $this->_redis->get('positionClickTracker.'.$pageType.'.'.$pageId.'.'.$date.'.id');
		    
		    for($i=1;$i<=$count;$i++) {
			$trackerData = $this->_redis->hgetall("positionClickTracker:".$pageType.":".$pageId.":".$date.":".$i);
			$left = $trackerData['x']-5;
			$top = $trackerData['y']-5;
			$pixelMapHtml .= "<div style='left:".$left."px; top:".$top."px;'></div>";
		    }
                    $date = date("Ymd", strtotime("+1 day", strtotime($date)));
        

        }//while
        $pixelMapHtml .= "</div>";
        echo $pixelMapHtml;	
    }
    
    function loadFormTrackingData( $pageType,$date1,$date2,$pageId,$exitCases = 0)
    {
        $this->_initRedis();       
        $formId = 'frm1';
        if($date1=="null")
          $date1 = date("Ymd");               
        if($date2=="null")
          $date2 = date("Ymd");               
        $date = date("Ymd");          
        $date =  $date1;
        $formData = array();
        $fieldClicks = array();
            
while( (strtotime($date)) <= (strtotime($date2)) )
        {	        
        if($exitCases) {
        error_log("29june exist case : 1 ");
            $sessionIdsForSubmittedForms = $this->_getSubmitSessionIds($pageType,$pageId,$formId,$date);
            $countClicks = $this->_redis->get('formClickTracker.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');
            
            for($i=1;$i<=$countClicks;$i++) {
                $clickData = $this->_redis->hgetall("formClickTracker:".$pageType.":".$pageId.":".$formId.":".$date.":".$i);
                $sessionId = $clickData['sessionId'];
                if(!in_array($sessionId,$sessionIdsForSubmittedForms)) {
                    $fieldId = $clickData['inputId'];
                    $fieldClicks[$fieldId]++;
                }
            }
            
            foreach($fieldClicks as $fieldId => $fieldClickCount) {
                
                $fieldOrder = $this->_redis->hget('formClickTracker:page:'.$pageType.':'.$pageId.':'.$formId.':fieldData:'.$fieldId,'order');
                
                $formData[] = array(
                                        'id' => $fieldId,
                                        'order' => $fieldOrder,
                                        'count' => $fieldClickCount
                                    );
            }
        }
        else {
        
//error_log("28june exist case : 0 ");
            $formFields = $this->_redis->smembers('formClickTracker:page:'.$pageType.':'.$pageId.':'.$formId.':fields');
            foreach($formFields as $fieldId) {
                
                $fieldOrder = $this->_redis->hget('formClickTracker:page:'.$pageType.':'.$pageId.':'.$formId.':fieldData:'.$fieldId,'order'); 
               
                $formData[] = array(
                                            'id' => $fieldId,
                                            'order' => $fieldOrder,
                                            'count' => $this->_redis->get('formClickTracker.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$fieldId.'.count')
                                        );
            }
        }
        
        usort($formData,array($this,'_sortByOrder'));
        
        $sortedFormData = array();
        foreach($formData as $data) {
            $sortedFormData[$data['id']] = $data['count'];
        }
         $date = date("Ymd", strtotime("+1 day", strtotime($date)));
        
        }//while
        echo json_encode($sortedFormData);
    }
    
    private function _sortByOrder($a,$b)
    {
        return $a['order'] - $b['order'];
    }
    
    function loadFormErrorTrackingData($pageType,$date1,$date2,$pageId,$exitCases = 0)
    {

        $this->_initRedis();
        if($date1=="null")
          $date1 = date("Ymd");               
        if($date2=="null")
          $date2 = date("Ymd");               
        $date = date("Ymd");          
        $date =  $date1;       
        $formId = 'frm1';
        $formData = array();
        $namespace = "formErrorTracker";
        $namespaceSubmit = "formSubmitTracker";
        $errorFields = array();    
        while( (strtotime($date)) <= (strtotime($date2)) )
        {	        
   
        if($exitCases) {
            
error_log("29june exitcase 1");
            /*
             * Get session ids for successfully submitted forms
             */ 
//error_log("29june values : " . $pageType . "--" . $pageId . "--" . $formId ."--". $date);
            $sessionIdsForSubmittedForms = $this->_getSubmitSessionIds($pageType,$pageId,$formId,$date);
//error_log("29june session ids " . print_r($sessionIdsForSubmittedForms, true));            
            $count = $this->_redis->get($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');        
            for($i=0;$i<$count;$i++) {
                
                $data = $this->_redis->hgetall($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$i);
                $errors = $this->_redis->hgetall($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$i.":errors");
                
                $sessionId = $data['sessionId'];
                
                if(!in_array($sessionId,$sessionIdsForSubmittedForms)) {
                    foreach($errors as $fieldId => $errorMsg) {
                        $errorFields[$fieldId]++;
                    }
                }
            }
            
           // echo json_encode($errorFields);
        }
        else {

//error_log("29june exitcase others");


            $formFields = $this->_redis->smembers($namespace.':page:'.$pageType.':'.$pageId.':'.$formId.':fields');

//error_log("29june formfields " . print_r($formFields, true));
            foreach($formFields as $fieldId) {
                $formData[$fieldId] = $this->_redis->get($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$fieldId.'.count');
            }
            //echo json_encode($formData);
        }
 $date = date("Ymd", strtotime("+1 day", strtotime($date)));
        
        }//while
 echo json_encode($formData);
          
}
    
    private function _getSubmitSessionIds($pageType,$pageId,$formId,$date)
    {
        /*
         * Get session ids for successfully submitted forms
         */ 
        $sessionIds = array();
        $namespace = 'formSubmitTracker';
        
        $count = $this->_redis->get($namespace.'.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');
        for($i=0;$i<$count;$i++) {
           $sessionId = $this->_redis->hget($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$i,'sessionId');
           if($sessionId) {
               $sessionIds[] = $this->_redis->hget($namespace.":".$pageType.":".$pageId.":".$formId.":".$date.":".$i,'sessionId');
           }
        }
        
        return $sessionIds;
    }
    
    
    public function backupToMongo()
    {
        $this->_initRedis();
        
        $conn = new Mongo('localhost');
        $db = $conn->shikshaTracking;
        
        $pageType = 'Marketing_MMP';
        $pageId = 86;
        $date = date("Ymd");
        $formId = 'frm1';
        
        /*
         * Backup position click tracking
         */ 
        //$collection = $db->positionClickTracking;
        //$count = $this->_redis->get('positionClickTracker.'.$pageType.'.'.$pageId.'.'.$date.'.id');
        //
        //for($i=1;$i<=$count;$i++) {
        //    
        //    $trackerData = $this->_redis->hgetall("positionClickTracker:".$pageType.":".$pageId.":".$date.":".$i);
        //    
        //    $clickData = array(
        //        'pageType' => $pageType,
        //        'pageId' => $pageId,
        //        'timestamp' => date('Y-m-d H:i:s'),
        //        'clickCoords' => array('x' => $trackerData['x'], 'y' => $trackerData['y'])
        //    );
        //    
        //    $collection->insert($clickData);
        //}
        
        //$collection = $db->formClickTracking;
        //$count = $this->_redis->get('formClickTracker.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');
        //
        //for($i=1;$i<=$count;$i++) {
        //    
        //    $trackerData = $this->_redis->hgetall("formClickTracker:".$pageType.":".$pageId.":".$formId.":".$date.":".$i);
        //    
        //    $clickData = array(
        //        'pageType'  => $pageType,
        //        'pageId'    => $pageId,
        //        'formId'    => $formId,
        //        'sessionId' => $trackerData['sessionId'],
        //        'timestamp' => date('Y-m-d H:i:s'),
        //        'fieldDate' => array(
        //                                'id'    => $trackerData['inputId'],
        //                                'name'  => $trackerData['inputName'],
        //                                'type'  => $trackerData['inputType'],
        //                                'value' => $trackerData['inputValue'],
        //                                'order' => $trackerData['fieldOrder']
        //                            )
        //    );
        //    
        //    $collection->insert($clickData);
        //}
        
        //$collection = $db->formErrorTracking;
        //$count = $this->_redis->get('formErrorTracker.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');
        //
        //for($i=1;$i<=$count;$i++) {
        //    
        //    $trackerData = $this->_redis->hgetall("formErrorTracker:".$pageType.":".$pageId.":".$formId.":".$date.":".$i);
        //    $trackerDataErrors = $this->_redis->hgetall("formErrorTracker:".$pageType.":".$pageId.":".$formId.":".$date.":".$i.":errors");
        //    
        //    $errorData = array(
        //        'pageType'  => $pageType,
        //        'pageId'    => $pageId,
        //        'formId'    => $formId,
        //        'sessionId' => $trackerData['sessionId'],
        //        'timestamp' => date('Y-m-d H:i:s'),
        //        'errors'    => $trackerDataErrors
        //    );
        //    
        //    $collection->insert($errorData);
        //}
        
        $collection = $db->formSubmitTracking;
        $count = $this->_redis->get('formSubmitTracker.'.$pageType.'.'.$pageId.'.'.$formId.'.'.$date.'.id');
    
        for($i=1;$i<=$count;$i++) {
            
            $trackerData = $this->_redis->hgetall("formErrorTracker:".$pageType.":".$pageId.":".$formId.":".$date.":".$i);
        
            $submitData = array(
                'pageType'  => $pageType,
                'pageId'    => $pageId,
                'formId'    => $formId,
                'sessionId' => $trackerData['sessionId'],
                'timestamp' => date('Y-m-d H:i:s')
            );
            $collection->insert($submitData);
        }
    }   
    function loadPixelMapMongo()
    {
        $conn = new Mongo('localhost');
        $db = $conn->shikshaTracking;
        
        $collection = $db->positionClickTracking;
                        
    }
}
