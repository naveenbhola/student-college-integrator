<?php
exit();
class shikshaStatsServer extends Controller 
{

    function index()
    {
        $this->init();
        error_log("i am here at server");

        $config['functions']['getData']=array('function'=>'shikshaStatsServer.getData');
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
        error_log("i am here at server");
        $parameters=$request->output_parameters();
        $fromDate=$parameters[0];
        $toDate=$parameters[1];
        $parentId=$parameters[2];
        $idFilter=$parameters[3];
        $columnFilter=$parameters[4];
        //$time="-".$timeDuration." ".$timeUnit;
        $this->init();
        //connect DB
        $dbHandle = $this->getDbHandler('write');
        if($dbHandle == ''){
            log_message('error','getMostContributingUser can not create db handle');
        }
        $queryCmd = "select * from tStatsTable where query_type='mysql' and parentId=".$parentId;
/*        if($idFilter!=-1)
        {
            $queryCmd.=" and id=".$idFilter;
}*/
        log_message('debug', 'getMostContributingUser query cmd is ' . $queryCmd);

        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        $queryArray= array();
        foreach ($query->result_array() as $row){
            array_push($queryArray,array($row,'struct'));
            // array_push($msgArray,array($row,'struct'));
        }
        foreach($queryArray as $queryrow)
        {
            error_log("qu ".print_r($queryrow,true));
            $queryCmd=$queryrow[0]['query'];
            $timeFilter=$queryrow[0]['time_filter'];
            $id=$queryrow[0]['id'];
            $hasChild=$queryrow[0]['hasChild'];
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
            if($idFilter==$id)
            {
                $queryCmd=preg_replace("/<from_date>/","'".$fromDate."'",$queryCmd);
                $queryCmd=preg_replace("/<to_date>/","'".$toDate."'",$queryCmd);        
                if($idFilter==$id and $columnFilter!=-1)
                {
                    $queryCmd.=" order by ".$columnFilter;
                }
                error_log("query is".$queryCmd);
                $query = $dbHandle->query($queryCmd);
                foreach ($query->result_array() as $row){
                    array_push($tempArray,array($row,'struct'));
                }
            }
            array_push($dataArray,array(array('id'=>$id,'hasChild'=>$hasChild,'query'=>$queryCmd,'table'=>array($tempArray,'struct')),'struct'));
            array_push($msgArray,array(array($queryrow[0]['query_name']=>array($dataArray,'struct')),'struct'));
        }
        $response = array($msgArray,'struct');
        error_log("server ".print_r($response,true));
        return $this->xmlrpc->send_response($response);

    }

    /**
     * This method adds new marketing page entry in the database.
     * $dbHandle = $this->getDbHandler('write');
     * @access	public
     * @return  object
     */
    private function getDbHandler($flag='read')
    {
        $dbHandle = NULL;
        $this->dbLibObj = DbLibCommon::getInstance('Enterprise');
        if ( $flag == 'read')
        {
            $dbHandle = $this->_loadDatabaseHandle();   //For Read Handle
        }
        else
        {
            $dbHandle = $this->_loadDatabaseHandle('write');   //For Write Handle
        }

        return $dbHandle;

    }
}
?>
