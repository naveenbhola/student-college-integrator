<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class response_abroad_model extends MY_Model{
    private $dbHandle;
    
    private function _initiateModel($mode = "write"){
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }
    
    
    public function addResponseDataToDBQueue($data){
        $this->_initiateModel('write');
        $insertData = array();
        $insertData['listingTypeId']                    = $data['listingTypeId'];
        $insertData['listingType']                      = $data['listingType'];
        $insertData['actionType']                       = $data['actionType'];
        $insertData['MISTrackingId']                    = $data['MISTrackingId'];
        $insertData['userId']                           = $data['userId'];
        $insertData['creationDate']                     = date('Y-m-d H:i:s');
        $insertData['visitorSessionId']                 = $data['visitorSessionId'];
        $insertData['sourcePage']                       = $data['sourcePage'];
        $insertData['listingsBrochureEmailTrackingId']  = $data['listingsBrochureEmailTrackingId'];
        $insertData['widget']                           = $data['widget'];
        $insertData['applicationSource']                = $data['applicationSource'];
        $this->dbHandle->insert('responsesMessageQueue',$insertData);

        $lastInsertId = $this->dbHandle->insert_id();
        
        //storing queue Id for elastic Indexing
        $sqlEl = "insert into response_elastic_map (queue_id,response_from) values (".$lastInsertId.",'abroad')";
        $this->dbHandle->query($sqlEl);

        return $lastInsertId;
    }
    
    public function getResponseDataFromQueue($responsesMessageQueueRowId,$workerId){
        $this->_initiateModel('write');
        $this->dbHandle->select('id, listingTypeId,listingType,actionType,MISTrackingId,userId,visitorSessionId,sourcePage,creationDate,widget');
        $this->dbHandle->from('responsesMessageQueue');
        $this->dbHandle->where('isProcessed','0');
        $this->dbHandle->where('id',$responsesMessageQueueRowId);
        $resultArray = $this->dbHandle->get()->result_array();
        if(!empty($resultArray[0])){
            $this->updatePickedAtTime($responsesMessageQueueRowId,$workerId);
        }
        return $resultArray[0];
    }
    
    private function updatePickedAtTime($responsesMessageQueueRowId,$workerId){
        $this->_initiateModel('write');
        $this->dbHandle->set('pickedAt',date('Y-m-d H:i:s'));
        $this->dbHandle->set('workerId',$workerId);
        $this->dbHandle->where('id',$responsesMessageQueueRowId);
        $this->dbHandle->update('responsesMessageQueue');
    }   
    
    public function setResponseProcessed($responsesMessageQueueRowId){
        $this->_initiateModel('write');
        $this->dbHandle->set('processedAt',date('Y-m-d H:i:s'));
        $this->dbHandle->set('isProcessed',1);
        $this->dbHandle->where('id',$responsesMessageQueueRowId);
        $this->dbHandle->update('responsesMessageQueue');
    }
    public function addRowToBrochureEmailTracking($data){
        $this->_initiateModel('write');
        $inserData['listingType']   = $data['listingType'];
        $inserData['listingTypeId'] = $data['listingTypeId'];
        $inserData['userId']        = $data['userId'];
        $inserData['source']        = $data['sourcePage'];
        $inserData['createdAt']     =  date('Y-m-d H:i:s', time());
        $this->dbHandle->insert('listingsBrochureEmailTracking',$inserData);
        return $this->dbHandle->insert_id();
    }
    public function updataBrochureEmailTracking($emailTrackingRowId,$tempLmsTableId,$tMailQueueId){
        $this->_initiateModel('write');
        $this->dbHandle->set('tempLmsId',$tempLmsTableId);
        $this->dbHandle->set('tMailQueueId',$tMailQueueId);
        $this->dbHandle->where('id',$emailTrackingRowId);
        $this->dbHandle->update('listingsBrochureEmailTracking');
    }
    public function setResponseCorrupted($queueDataFromDB){
        $this->_initiateModel('write');
        $this->dbHandle->set('isProcessed',2);
        $this->dbHandle->set('processedAt',date('Y-m-d H:i:s'));
        $this->dbHandle->where('id',$queueDataFromDB['id']);
        $this->dbHandle->update('responsesMessageQueue');
    }
    public function getDataForConvertingPendingResponsesFromDB(){
        $this->_initiateModel('write');
        $this->dbHandle->select('id,listingsBrochureEmailTrackingId');
        $this->dbHandle->from('responsesMessageQueue');
        $this->dbHandle->where('isProcessed','0');
        $resultArray = $this->dbHandle->get()->result_array();
        return $resultArray;
    }
    public function checkIfRowExistsForLastDay($data){
        $time = date('Y-m-d H:i:s', time()-86400);
        $this->_initiateModel('write');
        $this->dbHandle->select('id');
        $this->dbHandle->from('listingsBrochureEmailTracking');
        $this->dbHandle->where('listingTypeId',$data['listingTypeId']);
        $this->dbHandle->where('userId',$data['userId']);
        $this->dbHandle->where('listingType',$data['listingType']);
//        $this->dbHandle->where('source',$data['sourcePage']);
        $this->dbHandle->where('createdAt >',$time);
        $resultArray = $this->dbHandle->get()->result_array();
        return $resultArray[0]['id'];
    }

    public function updateCorruptedResponseData(){
        $this->_initiateModel('write');
        $sql = "select rmq.id, rmq.creationDate, dbem.tempLmsId,rmq.listingsBrochureEmailTrackingId from responsesMessageQueue rmq join listingsBrochureEmailTracking dbem on 
            rmq.listingsBrochureEmailTrackingId = dbem.id
            where rmq.listingsBrochureEmailTrackingId !=0 and rmq.creationDate >= '2017-06-21 13:30:16'";

       $data = $this->dbHandle->query($sql)->result_array();
       

       
       $updateTempLMSArray = array();
       $updateListingBrochureArray = array();
       $tempLMSIds = array();
        foreach($data as $key => $row){
            if(empty($row['tempLmsId'])){
                unset($data[$key]);
            }else{
                $tempLMSIds[] = $row['tempLmsId'];
            }
       }
//       
//       foreach($data as $row){
//            if(!empty($row['tempLmsId'])){
//                $updateTempLMSArray[] = array('id'=>$row['tempLmsId'],'submit_date'=>$row['creationDate']);
//                $updateListingBrochureArray[] = array('id'=>$row['listingsBrochureEmailTrackingId'],'createdAt'=>$row['creationDate']);
//            }
//       }
//       _p($updateTempLMSArray);
//       _p($updateListingBrochureArray);
//       
//       $response = $this->dbHandle->update_batch('tempLMSTable',$updateTempLMSArray,'id');
//       _p($this->dbHandle->last_query());
////       _p($response);
//       $response = $this->dbHandle->update_batch('listingsBrochureEmailTracking',$updateListingBrochureArray,'id');
//       _p($this->dbHandle->last_query());
////       _p($response);

       
        $CI = & get_instance();
        require_once('vendor/autoload.php');
       
        $clientParams = array();
        $clientParams['hosts'] = array('172.16.3.111'); //shikshaConfig
        $elasticClientCon =  new Elasticsearch\Client($clientParams);

        $params = array();
        $params['index'] = 'mis_responses';
        $params['type'] = 'response';
        $params['body']['size'] =6500;
        

        $params['body']['query']['filtered']['filter']['bool']['must']['terms']['tempLMSId'] = $tempLMSIds;
        $response = $elasticClientCon->search($params);
        $ESTempIds = array();
        
        foreach ($response['hits']['hits'] as $key => $value) {
            $ESTempIds[$value['_source']['tempLMSId']] = $value['_id'];
        }
        
        $params['body'] = array();
       foreach($data as $row){
            if(!empty($row['tempLmsId']) && in_array($row['tempLmsId'], array_keys($ESTempIds))){
                $params['body'][] = array(
                    'update' => array(
                    '_id' => $ESTempIds[$row['tempLmsId']]
                    )
                    );

                $params['body'][] = array('doc_as_insert' =>true ,
                    'doc'=> array('responseDate' => str_replace(' ', 'T', $row['creationDate'])));
            }
       }
       $response = $elasticClientCon->bulk($params);
       _p(json_encode($params));
       _p($response);
    }
}

