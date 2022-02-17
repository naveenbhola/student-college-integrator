<?php
exit();
class shikshaStatsServer1 extends Controller 
{

    function index()
    {
        $this->init();

        $config['functions']['getData']=array('function'=>'shikshaStatsServer1.getData');
        $this->xmlrpcs->initialize($config);
        $this->xmlrpcs->set_debug(0);
        $this->xmlrpcs->serve();
    }

    function init()
    {
        set_time_limit(0);
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('parsexml');
        $this->load->library('messageboardconfig');
        $this->load->helper('url');
    }

    function getData($request)
    {
        $parameters=$request->output_parameters();
        $fromDate=$parameters[0];
        $toDate=$parameters[1];
        $parentId=$parameters[2];
        $idFilter=$parameters[3];
        $columnFilter=$parameters[4];
        $sortorder = $parameters[5];
        $userid= $parameters[6];
        $useridstr='';
        if($userid==2)
        {
            $useridstr=" and user_id=$userid";
        }
        $this->init();
        //connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->messageboardconfig->getDbConfig($appID,$dbConfig);       
        $dbHandle = $this->load->database($dbConfig,TRUE);
        if($dbHandle == ''){
            log_message('error','ShikshaStats can not create db handle');
        }
        $queryCmd = "select * from tStatsTable where  parentId=".$parentId." ".$useridstr;
        /*        if($idFilter!=-1)
                  {
                  $queryCmd.=" and id=".$idFilter;
                  }*/
        log_message('debug', 'ShikshaStats query cmd is ' . $queryCmd);

        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        $queryArray= array();
        foreach ($query->result_array() as $row){
            array_push($queryArray,$row);
            // array_push($msgArray,array($row,'struct'));
        }
        $newqueryArray= array();
        if($idFilter!=-1)
        {
        $queryCmd = "select * from tStatsTable where parentId=".$idFilter." ".$useridstr;
        $query = $dbHandle->query($queryCmd);
        $tempArray1 = array();
        foreach ($query->result_array() as $row){
            array_push($tempArray1,$row);
        }
        array_push($newqueryArray,$tempArray1);
        }
        array_push($newqueryArray,$queryArray);
        if($parentId!=0){
            $newparentId=$parentId;
            while($newparentId!=0)
            {
                $queryCmd = "select * from tStatsTable where id=".$newparentId." ".$useridstr;
                $query = $dbHandle->query($queryCmd);
                $tempArray = array();
                foreach ($query->result_array() as $row){
                    $newparentId=$row['parentId'];
                   // error_log("HII".$newparentId);
                    // array_push($msgArray,array($row,'struct'));
                }   
                $queryCmd = "select * from tStatsTable where  parentId=".$newparentId." ".$useridstr;
                $query = $dbHandle->query($queryCmd);
                foreach ($query->result_array() as $row){
                    array_push($tempArray,$row);
                }
                array_push($newqueryArray,$tempArray);
            }
            /* $queryCmd = "select * from tStatsTable where  parentId=".$newparentId;
               $query = $dbHandle->query($queryCmd);
               foreach ($query->result_array() as $row){
               array_push($newqueryArray,array($row,'struct'));
               }*/
        }
//        error_log("HII".print_r($newqueryArray,true));
//        error_log("HII".print_r($queryArray,true));
        $count=0;
        foreach($queryArray as $queryrow)
        {
            $queryCmd=$queryrow['query'];
            $timeFilter=$queryrow['time_filter'];
            $id=$queryrow['id'];
            $hasChild=$queryrow['hasChild'];
            $queryName = $queryrow['query_name'];
            $querytype = $queryrow['query_type'];
            /*list($queryCommand,$where)=split('where',$queryCmd);
              if($where=='')
              {
              list($finalquery,$groupquery)=split('group by',$queryCommand);
              if($groupquery=='')
              {
              $queryCmd=$finalquery." where ".$timeFilter.">'".$fromDate."' and ".$timeFilter."<'".$toDate."'";
              }
              else
              {
              $queryCmd=$finalquery." where ".$timeFilter.">'".$fromDate."' and ".$timeFilter."<'".$toDate."' group by ".$groupquery;
              }
              }
              else
              {
              list($wherequery,$groupquery)=split('group by',$where);
              if($groupquery=='')
              {
              $queryCmd=$queryCommand." where ".$timeFilter.">'".$fromDate."' and ".$timeFilter."<'".$toDate."' and ".$wherequery;
              }
              else
              {
              $queryCmd=$queryCommand." where ".$timeFilter.">'".$fromDate."' and ".$timeFilter."<'".$toDate."' and ".$wherequery." group by ".$groupquery;
              }
              }*/
            $dataArray=array();
            $tempArray=array();
           // error_log($id." ".$idFilter);
            if($idFilter==$id)
            {
                $queryCmd=preg_replace("/<from_date>/","'".$fromDate."'",$queryCmd);
                $queryCmd=preg_replace("/<to_date>/","'".$toDate."'",$queryCmd);
                if($querytype=="mysql")
                {
                if($idFilter==$id and $columnFilter!=-1)
                {
                    if($sortorder!=1)
                    {
                    $queryCmd.=" order by ".$columnFilter;
                    }
                    else
                    {
                    $queryCmd.=" order by ".$columnFilter." desc";
                    }
                }
               error_log($queryCmd);
                $query = $dbHandle->query($queryCmd);
                
                foreach ($query->result_array() as $row){
                    array_push($tempArray,$row);
                }
                }
                else
                {
                   // error_log("shivam at else".print_r($queryrow,true));
                   // error_log("shivam".$queryCmd);
                    $output = shell_exec($queryCmd);
                  //  error_log("shivam".$output);
                    array_push($tempArray,$output);
                }

                array_push($dataArray,array('id'=>$id,'hasChild'=>$hasChild,'query'=>$queryCmd,'query_name'=>$queryName,'table'=>$tempArray,'type'=>$querytype));
                array_push($msgArray,array('tabledata'=>$dataArray));
                $count++;
            }
        }
        if($count==0)
        {
            array_push($msgArray,array('tabledata'=>$dataArray));
        }
        array_push($msgArray,array('tree'=>$newqueryArray));
       // error_log("HIII ".print_r($msgArray,true));
        $msgArray = base64_encode(serialize($msgArray));
        $response = array($msgArray,'string');
        return $this->xmlrpc->send_response($response);

    }
}
?>
