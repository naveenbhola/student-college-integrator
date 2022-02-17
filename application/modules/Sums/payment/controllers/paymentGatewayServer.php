<?php

/*

   Copyright 2007 Info Edge India Ltd

   $Rev::               $:  Revision of last commit
   $Author: amitj $:  Author of last commit
   $Date: 2008-09-09 06:12:09 $:  Date of last commit

   This class provides the Event Cal Server Web Services. 

   $Id: paymentGatewayServer.php,v 1.4 2008-09-09 06:12:09 amitj Exp $: 

 */

class paymentGatewayServer extends MX_Controller {

    /*
     *	index function to recieve the incoming request
     */

    function index(){

        $this->dbLibObj = DbLibCommon::getInstance('Payment');
        //load XML RPC Libs
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('paymentconfig'); 


        //Define the web services method
        $config['functions']['getProductData'] = array('function' => 'paymentGatewayServer.getProductData');


        //initialize
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }




    function getProductData($request){
        //error_log_shiksha("under getProductData server");

        $parameters = $request->output_parameters();
        $appId = $parameters['0']; 
        $inputArr = $parameters['1']; // key value pair array.
        /*        $month=$parameters['1'];
                  $year=$parameters['2'];
                  $boardId=$this->getBoardChilds($parameters['3']);
                  $countryId=$parameters['4'];
         */
        //        $date = $year.'-'.$month;
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        if($inputArr['productId'] != 0) {
            $queryCmd = 'select * from tProduct where ProductId='.$dbHandle->escape($inputArr['productId']);

        }else {
            $queryCmd = 'select * from tProduct order by tProduct.ProductId';
        }
        /*        if($countryId>1){
                  $queryCmd = 'select count(*) count, date(s.start_date) start_date from event_date s, event e where (e.boardId in (' . $boardId .')) and e.countryId='. $countryId .' and s.event_id=e.event_id and s.start_date >= \''. $date .'\' group by day(s.start_date)';
                  }
                  else{
                  $queryCmd = 'select count(*) count, date(s.start_date) start_date from event_date s, event e where (e.boardId in (' . $boardId .')) and s.event_id=e.event_id and s.start_date >= \''. $date .'\' group by day(s.start_date)';
                  }	
         */
        log_message('debug', 'getProductData query cmd is ' . $queryCmd);

        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        foreach ($query->result() as $row){
            $queryCmd = 'select * from tProduct_Property where ProductId=?';
            $queryTemp = $dbHandle->query($queryCmd, array($row->ProductId));
            $msgArrayTemp = array();
            foreach ($queryTemp->result() as $rowTemp) {
                array_push($msgArrayTemp,array(
                            array(
                                $rowTemp->Property=>array($rowTemp->Value,'string')
                                ),'struct')
                        );//close array_push
            }
            array_push($msgArray,array(
                        array(
                            'ProductId'=>array($row->ProductId,'string'),
                            'ProductName'=>array($row->ProductName,'string'),
                            'Type'=>array($row->Type,'string'),
                            'Price'=>array($row->Price,'int'),
                            'Property'=>array($msgArrayTemp,'struct')
                            ),'struct')
                    );//close array_push

        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);	

    }






}
?>
