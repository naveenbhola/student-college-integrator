<?php
class ShikshaStats3 extends Controller {

    function init() {
        set_time_limit(0);
        $this->load->helper(array('form', 'url','image'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client','alerts_client','shikshaStats_client3'));
    }


    function index()
    {
        $appId = 1;
        $this->init();
        $displayData = array();
        $parentId = $_REQUEST['parentId'];
        $fromDate= $_REQUEST['from_date_main'];
        $toDate= $_REQUEST['to_date_main'];
        $id=$_REQUEST['id'];
        $column=$_REQUEST['column'];
        $order = $_REQUEST['order'];
        $export_csv = $_REQUEST['export_csv'];
        $filter1= $_REQUEST['filter1'];
        $filter2= $_REQUEST['filter2'];
        echo print_r($_REQUEST,true);
        echo "\n".$filter1."\n";
        if(!isset($parentId) || $parentId=='')
        {
            $parentId=0;
        }
        if(!isset($fromDate) || $fromDate=='')
        {
            $fromDate=date("Y-m-d",mktime(date("H"), date("i"), date("s"), date("m"), date("d")-1, date("Y")));
        }
        if(!isset($toDate) || $toDate=='')
        {
            $toDate=date("Y-m-d");
        }
        if(!isset($column) || $column=='')
        {
            $column=-1;
        }
        if(!isset($id) || $id=='')
        {
            $id=-1;
            $column=-1;
        }
        if(!isset($order) || $order=='')
        {
            $order=0;
        }
        $fromDateTime = $fromDate." 00:00:00";
        $toDateTime = $toDate." 23:59:59";
        $ShikshaStatsClientObj = new ShikshaStats_client3();
        $displayData['results'] = $ShikshaStatsClientObj->getData($fromDateTime,$toDateTime,$parentId,$id,$column,$order,$filter1,$filter2);

        $displayData['results'] = unserialize(base64_decode($displayData['results']));
        error_log(print_r($displayData['results'],true));
        error_log("DODO".print_r($displayData,true));
        $displayData['fromDate']=$fromDate;
        $displayData['toDate']=$toDate;
        $displayData['parentId']=$parentId;
        $displayData['idFilter']=$id;
        $displayData['columnFilter']=$column;
        $displayData['order']= $order;
        //echo "<pre>";
        //echo print_r($displayData,true);
        //echo "</pre>";

        if($export_csv==1)
        { 
            $leads=$displayData['results'][0]['tabledata'][0]['table'];
            header("Content-type: text/x-csv");
            $filename =preg_replace('/[^A-Za-z0-9]/', '',$displayData['results'][0]['tabledata'][0]['query_name']);
            header("Content-Disposition: attachment; filename=".$filename.".csv");
            $csv = '';
            if($displayData['results'][0]['tabledata'][0]['type']=='mysql')
            {
            foreach ($leads as $lead){
                foreach ($lead as $key=>$val){
                    $csv .= '"'.$key.'",'; 
                }
                $csv .= "\n"; 
                break;
            }
            foreach ($leads as $lead){
                foreach ($lead as $key=>$val){
                    $csv .= '"'.strip_tags($val).'",'; 
                }
                $csv .= "\n"; 
            }
            }
            else
            {
                $csv.= $leads[0];
            }
            echo $csv;
        }
        else
        {
        $this->load->view('shikshaStats/shikshaStats1',$displayData);
        }
    }
}

?>
