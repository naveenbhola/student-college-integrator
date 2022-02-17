<?php
exit();
class shikshaStatsServer3 extends Controller 
{

    function index()
    {
        $this->init();

        $config['functions']['getData']=array('function'=>'shikshaStatsServer3.getData');
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
        $filter1="";
        $filter2="";
        $parameters=$request->output_parameters();
        $fromDate=$parameters[0];
        $toDate=$parameters[1];
        $parentId=$parameters[2];
        $idFilter=$parameters[3];
        $columnFilter=$parameters[4];
        $sortorder = $parameters[5];
        $filter1 = $parameters[6];
        $filter2 = $parameters[7];
        $this->init();
        //connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->messageboardconfig->getDbConfig($appID,$dbConfig);       
        $dbHandle = $this->load->database($dbConfig,TRUE);
        if($dbHandle == ''){
            log_message('error','ShikshaStats can not create db handle');
        }
        $queryCmd = "select * from tStatsTable where  parentId=".$parentId;
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
        $queryCmd = "select * from tStatsTable where parentId=".$idFilter;
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
                $queryCmd = "select * from tStatsTable where id=".$newparentId;
                $query = $dbHandle->query($queryCmd);
                $tempArray = array();
                if ($query->num_rows() > 0)
                {
                foreach ($query->result_array() as $row){
                    $newparentId=$row['parentId'];
                   // error_log("HII".$newparentId);
                    // array_push($msgArray,array($row,'struct'));
                }   
                $queryCmd = "select * from tStatsTable where  parentId=".$newparentId;
                $query = $dbHandle->query($queryCmd);
                if ($query->num_rows() > 0)
                                {

                foreach ($query->result_array() as $row){
                    array_push($tempArray,$row);
                }
                array_push($newqueryArray,$tempArray);
                }
                else
                {
                    break;
                }
                }
                else
                {
                break;
                }
            }
            /* $queryCmd = "select * from tStatsTable where  parentId=".$newparentId;
               $query = $dbHandle->query($queryCmd);
               foreach ($query->result_array() as $row){
               array_push($newqueryArray,array($row,'struct'));
               }*/
        }
//        error_log("HII".print_r($newqueryArray,true));
        error_log("HII".print_r($queryArray,true));
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
            error_log($id." ".$idFilter);
            if($idFilter==$id)
            {
                $queryCmd=preg_replace("/<from_date>/","'".$fromDate."'",$queryCmd);
                $queryCmd=preg_replace("/<to_date>/","'".$toDate."'",$queryCmd);
                $queryCmd=preg_replace("/<filter1>/",$filter1,$queryCmd);
                $queryCmd=preg_replace("/<filter2>/",$filter2,$queryCmd);
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
                    error_log("shivam at else".print_r($queryrow,true));
                    $output = shell_exec($queryCmd);
                    error_log("shivam".$output);
                    array_push($tempArray,array($output,'string'));
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
        error_log("HIII ".print_r($msgArray,true));
        $msgArray = base64_encode(serialize($msgArray));
        $response = array($msgArray,'string');
        return $this->xmlrpc->send_response($response);

    }
}
?>
