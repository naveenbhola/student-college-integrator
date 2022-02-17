<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 196           $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-09-09 06:12:09 $:  Date of last commit


*/


class paymentServer extends MX_Controller {

    function index(){
        
        $this->dbLibObj = DbLibCommon::getInstance('Payment');
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('paymentconfig'); 

        $config['functions']['getProductData'] = array('function' => 'paymentServer.getProductData');
        $config['functions']['addTransaction'] = array('function' => 'paymentServer.addTransaction');
        $config['functions']['getProductForUser'] = array('function' => 'paymentServer.getProductForUser');
        $config['functions']['updateUserAsset'] = array('function' => 'paymentServer.updateUserAsset');
        $config['functions']['sgetTransactionHistory'] = array('function' => 'paymentServer.sgetTransactionHistory');
        /******* NEW WS CODE*******/


        
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    function addTransaction($request){
        
        $parameters    = $request->output_parameters();
        $appId         = $parameters['0']; 
        $inputArr      = $parameters['1']; // key value pair array.
        $userId        = $parameters['2'];
        $paymentOption = $parameters['3'];
        //connect DB
        $dbHandle      = $this->_loadDatabaseHandle('write');
        
        if($inputArr != 0) {
            
            $queryCmd = 'select * from tProduct where ProductId='.$dbHandle->escape($inputArr);

        }else {
            $queryCmd = 'select * from tProduct order by tProduct.ProductId';
        }
        
        $query    = $dbHandle->query($queryCmd);
        $msgArray = array();
        foreach ($query->result() as $row){
            $queryCmd     = 'select ProductId,Property,Value from tProduct_Property where ProductId=?';
            $queryTemp    = $dbHandle->query($queryCmd, array($row->ProductId));
            $msgArrayTemp = array();
            $queryCmd     = 'insert into tTransaction (UserId,ProductId,Price) values(?,?, ?)';
            $query        = $dbHandle->query($queryCmd, array($userId, $inputArr, $row->Price));
            $id           = $dbHandle->insert_id();
            foreach ($queryTemp->result() as $rowTemp) {
                $queryCmd = 'insert into tTransaction_Property values(?,?, ?)';
                $query    = $dbHandle->query($queryCmd, array($id, $rowTemp->Property, $rowTemp->Value));
                $msgArrayTemp[$rowTemp->Property] = $rowTemp->Value;
            }
            if($msgArrayTemp["Num"] == "UNLIMTED") {
                $msgArrayTemp["Num"] = 99999990;
            }
            if($paymentOption == "Cheque Or Draft"){
                $queryCmd = 'insert into tAsset (UserId,TransactionId,TotalNo,Remaining,ListingType,FormOfListing) values (?,?,?,?,"All","Not Paid")';
                $query    = $dbHandle->query($queryCmd, array($userId, $id, $msgArrayTemp["Num"], $msgArrayTemp["Num"]));
            }else {
                $queryCmd = 'insert into tAsset (UserId,TransactionId,TotalNo,Remaining,ListingType) values (?,?,? ,?,?)'; 
                $query    = $dbHandle->query($queryCmd, array($userId, $id, $msgArrayTemp["Num"], $msgArrayTemp["Num"], 'All'));
            }
            if($msgArrayTemp["Num"] == "99999990") {
                $msgArrayTemp["Num"] = "UNLIMITED";
            }

            array_push($msgArray,array(
                        array(
                            'ProductId'   =>array($row->ProductId,'string'),
                            'ProductName' =>array($row->ProductName,'string'),
                            'Type'        =>array($row->Type,'string'),
                            'Price'       =>array($row->Price,'int'),
                            'Property'    =>array($msgArrayTemp,'struct')
                            ),'struct')
                    );//close array_push
                    break;

        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);	

    }


    function updateUserAsset($request){

        $parameters = $request->output_parameters();
        $appId      = $parameters['0']; 
        $userId     = $parameters['1']; // key value pair array.
        $val        = $parameters['2']; 
        $type       = $parameters['3'];
        $val        = 0-$val;
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = 'select * from tAsset,tTransaction where tAsset.TransactionId = tTransaction.TransactionId and ProductId=? and tAsset.UserId=?';
        $query    = $dbHandle->query($queryCmd, array($type, $userId));
        
        foreach ($query->result() as $row){
            if(($row->Remaining > $val )&&($row->FormOfListing == "PAID")){
                $queryCmd  = 'UPDATE tAsset,tTransaction  SET Remaining = Remaining-? where tAsset.TransactionId = tTransaction.TransactionId and ProductId=? and tAsset.UserId=?';
                $queryTemp = $dbHandle->query($queryCmd, array($val, $type, $userId));
                $response  = true;
                return $this->xmlrpc->send_response($response);
            }elseif(($row->Remaining = $val )&&($row->FormOfListing == "PAID")){ 
                $queryCmd  = 'Delete from tAsset where TransactionId=?';
                $queryTemp = $dbHandle->query($queryCmd, array($row->TransactionId));
                $response  = true;
                return $this->xmlrpc->send_response($response);
            }else {
                $response = false;
            }
        }

        //error_log_shiksha(print_r($response,true));
        return $this->xmlrpc->send_response($response);	

    }

    function getProductForUser($request){
        
        $parameters = $request->output_parameters();
        
        $appId    = $parameters['0']; 
        $userId   = $parameters['1']; // key value pair array.
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = 'select * from tAsset where UserId= ? ';
        $query    = $dbHandle->query($queryCmd, array($userId));
        $msgArray = array();
        foreach ($query->result() as $row){
            $queryCmd     = 'select TransactionId,Property,Value from tTransaction_Property where TransactionId=?';
            $queryTemp    = $dbHandle->query($queryCmd, array($row->TransactionId));
            $msgArrayTemp = array();
            foreach ($queryTemp->result() as $rowTemp) {
                $msgArrayTemp[$rowTemp->Property]=$rowTemp->Value;
            }
            $queryCmd  = 'select ProductId from tTransaction where TransactionId=?';
            $queryTemp = $dbHandle->query($queryCmd, array($row->TransactionId));
            foreach ($queryTemp->result() as $rowTemp) {
                $queryCmd      = 'select * from tProduct where ProductId=?';
                $queryTempTemp = $dbHandle->query($queryCmd, array($rowTemp->ProductId));
                foreach ($queryTempTemp->result() as $rowTemp1) {
                
                
                
            array_push($msgArray,array(
                        array(
                            'productId'   =>array($rowTemp1->ProductId,'string'),
                            'productName' =>array($rowTemp1->ProductName,'string'),
                            'type'        =>array($rowTemp1->Type,'string'),
                            'price'       =>array($rowTemp1->Price,'int'),
                            'property'    =>array($msgArrayTemp,'struct'),
                            'remaining'   =>$row->Remaining
                            ),'struct')
                    );//close array_push
                }
            }

        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);	

    }

    function getProductData($request){
        
        $parameters = $request->output_parameters();
        $appId      = $parameters['0']; 
        $inputArr   = $parameters['1']; // key value pair array.
        //connect DB
        $dbHandle   = $this->_loadDatabaseHandle();
        
        if($inputArr != 0) {
            $queryCmd = 'select * from tProduct where ProductId='.$dbHandle->escape($inputArr);

        }else {
            $queryCmd = 'select * from tProduct order by tProduct.ProductId';
        }

        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        foreach ($query->result() as $row){
            $queryCmd     = 'select ProductId,Property,Value from tProduct_Property where ProductId=?';
            $queryTemp    = $dbHandle->query($queryCmd, array($row->ProductId));
            $msgArrayTemp = array();
            foreach ($queryTemp->result() as $rowTemp) {
                $msgArrayTemp[$rowTemp->Property]=$rowTemp->Value;
            }
            array_push($msgArray,array(
                        array(
                            'ProductId'   =>array($row->ProductId,'string'),
                            'ProductName' =>array($row->ProductName,'string'),
                            'Type'        =>array($row->Type,'string'),
                            'Price'       =>array($row->Price,'int'),
                            'Property'    =>array($msgArrayTemp,'struct')
                            ),'struct')
                    );//close array_push

        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);	

    }

    function sgetTransactionHistory($request){

        $parameters = $request->output_parameters();
        $appId      = $parameters['0']; 
        $userId     = $parameters['1']; 
        //connect DB
        $dbHandle   = $this->_loadDatabaseHandle();
        $queryCmd   = 'select * from tTransaction where UserId=?';
        $query      = $dbHandle->query($queryCmd, array($userId));
        $msgArray   = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                array(
                    'transactionId' =>array($row->TransactionId,'int'),
                    'productId'     =>array($row->ProductId,'string'),
                    'price'         =>array($row->Price,'string'),
                    'time'          =>array($row->Time,'string')
                ),'struct')
            );//close array_push
        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);	
    }


}
?>
