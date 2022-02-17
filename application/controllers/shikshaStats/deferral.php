<?php
class deferral extends MX_Controller {

    function init() {
        set_time_limit(0);
        $this->load->helper(array('form', 'url','image'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client','alerts_client','shikshaStats_client'));
    }
    function index()
    {
      $ADMIN_USERNAME = 'deferral';
      $ADMIN_PASSWORD = '201P@ssw0rd';
	if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
	$_SERVER['PHP_AUTH_USER'] != $ADMIN_USERNAME ||$_SERVER['PHP_AUTH_PW'] != $ADMIN_PASSWORD) {
	  Header("WWW-Authenticate: Basic realm=\"Deferral Login\"");
	  Header("HTTP/1.0 401 Unauthorized");
	  echo "<html><body><h1>Rejected!</h1><big>Wrong Username or Password!</big></body></html>";
	  exit;
	}
	
        $appId=1;
        $this->load->library('sums_manage_client'); 
                $manObj = new Sums_Manage_client();    
                                        for($i=0;$i<12;$i++)                // Month array
                                                    $mmarr[$i]=$i+1;
                                                                for($i=0; $i < 10; $i++)             // Years Array
                                                                            $yyarr[$i] = 2008 + $i;
                        $data['branchList'] = $manObj->getBranches($appId);
                        $data['MonthList']=$mmarr;
                        $data['YearList']=$yyarr;
          //              echo print_r($data,true);
        $this->load->library('deferral_client');
        $deferralObj= new Deferral_client();
          $uncountedPayments=$deferralObj->uncountedTransaction(1);
          $data['ReportResult']=$uncountedPayments;
        //  echo print_r($uncountedPayments,true);
        $this->load->view('shikshaStats/deferral',$data);
    }

    function getdetails()
    {
        //echo print_r($_REQUEST,true);
        $_REQUEST['branchList']=implode(",",$_REQUEST['branches']);
        $this->load->library('deferral_client');
        $deferralObj= new Deferral_client();
        $results = $deferralObj->getdetails(1,$_REQUEST);
      //  echo "<pre>";
      //  echo print_r($results,true);
      //  echo "</pre>";
        
        if($_REQUEST['branches'][0] != "") //If the user hasn't selected "ALL"
        {
            foreach($_REQUEST['branches'] as $value)
            {
                $center_arr[]=$value;
            }
        }
        else
        {
            $center_arr=array();
        }


        foreach($results[0]['result1array'] as $row1)
        {
            // echo "<pre>";
            // echo print_r($row1,true);
            // echo "</pre>";
            $center_index =  array_search($row1['CENTER'],$center_arr);
            if($center_index === FALSE)
            {
                $center_arr[] = $row1['CENTER'];
                $center_index =  array_search($row1['CENTER'],$center_arr);
            }
            $year=$row1['YEAR'];
            $month=$row1['MONTH'];
            $branch[$center_index][$year][$month]['COLLECTION']=round($row1['SC'],2);
            $branch[$center_index][$year][$month]['ASSIGN']=round($row1['SA'],2);
            $branch[$center_index][$year][$month]['CF']=round($row1['SCF'],2);

        }
        foreach($results[0]['result2array'] as $row2)
        {
            //echo "<pre>";
            // echo print_r($row2,true);
            // echo "</pre>";
            $center_index =  array_search($row2['CENTER'],$center_arr);
            if($center_index === FALSE)
            {
                $center_arr[] = $row2['CENTER'];
                $center_index =  array_search($row2['CENTER'],$center_arr);
            }
            $year=$row2['YEAR'];
            $month=$row2['MONTH'];
            $branch[$center_index][$year][$month]['BF']=round($row2['SBF'],2);
        }
        foreach($center_arr as $center_index => $cname)
        {
            // echo "i am here 1";
            for($year=$_REQUEST['afy'];$year<=$_REQUEST['aty'];$year++)
            {
                // echo "i am here 2";
                if(!$loop)
                    $date_year[]=$year;
                if($year<$_REQUEST['aty'])
                {
                    $end_month=12;
                    // echo "i am here 3";
                }
                else
                {
                    $end_month=$_REQUEST['atm'];
                }
                if($year==$_REQUEST['afy'])
                {
                    $start_month=$_REQUEST['afm'];
                }
                else
                    $start_month=1;
                $count=0;
                for($month=$start_month;$month<=$end_month;$month++)
                {
                    // echo "i am here 4";

                    if(!$loop)
                    {
                        $date_month[$year][$count]['value']=$month;
                        $date_month[$year][$count]['label']=$month;
                        // print_r($date_month[$year][$count]);
                    }
                    if(!$branch[$center_index][$year][$month]['COLLECTION'])
                    {
                        $branch[$center_index][$year][$month]['COLLECTION']=0;
                    }
                    if(!$branch[$center_index][$year][$month]['ASSIGN'])
                    {
                        $branch[$center_index][$year][$month]['ASSIGN']=0;
                    }
                    if(!$branch[$center_index][$year][$month]['CF'])
                    {
                        $branch[$center_index][$year][$month]['CF']=0;
                    }
                    if(!$branch[$center_index][$year][$month]['BF'])
                    {
                        $branch[$center_index][$year][$month]['BF']=0;
                    }
                    $branch[$center_index][$year][$month]['REVENUE']=$branch[$center_index][$year][$month]['ASSIGN']+$branch[$center_index][$year][$month]['BF'];

                    $total[$center_index]['COLLECTION']+=$branch[$center_index][$year][$month]['COLLECTION'];
                    $total[$center_index]['ASSIGN']+=$branch[$center_index][$year][$month]['ASSIGN'];
                    $total[$center_index]['CF']+=$branch[$center_index][$year][$month]['CF'];
                    $total[$center_index]['BF']+=$branch[$center_index][$year][$month]['BF'];
                    $total[$center_index]['REVENUE']+=$branch[$center_index][$year][$month]['REVENUE'];
                    $total_sums['COLLECTION']+=$branch[$center_index][$year][$month]['COLLECTION'];
                    $total_sums['ASSIGN']+=$branch[$center_index][$year][$month]['ASSIGN'];
                    $total_sums['CF']+=$branch[$center_index][$year][$month]['CF'];
                    $total_sums['BF']+=$branch[$center_index][$year][$month]['BF'];
                    $total_sums['REVENUE']+=$branch[$center_index][$year][$month]['REVENUE'];
                    $sums[$year][$month]['COLLECTION']+=$branch[$center_index][$year][$month]['COLLECTION'];
                    $sums[$year][$month]['ASSIGN']+=$branch[$center_index][$year][$month]['ASSIGN'];
                    $sums[$year][$month]['CF']+=$branch[$center_index][$year][$month]['CF'];
                    $sums[$year][$month]['BF']+=$branch[$center_index][$year][$month]['BF'];
                    $sums[$year][$month]['REVENUE']+=$branch[$center_index][$year][$month]['REVENUE'];
                    $count++;
                }
            }
            $loop=1;
        }
        $branchList=$deferralObj->getbranchNamebyID($appId,$center_arr);
       // echo print_r($branchList,true);
        $data=array();
        $data['branchList']=$branchList;
        $data["center_arr"]=$center_arr;
        $data["timespan"]=$timespan;
        $data["total"]=$total;
        $data["sum"]=$sums;
        $data["total_sum"]=$total_sums;
        $data["branch"]=$branch;
        $data["date_year"]=$date_year;
        $data["date_month"]=$date_month;
        $data["currency"]=$_REQUEST['currency'];
        $data["afy"]=$_REQUEST['afy'];
        $data["aty"]=$_REQUEST['aty'];
        $data["atm"]=$_REQUEST['atm'];
        $data["afm"]=$_REQUEST['afm'];
        $data["cfm"]=$_REQUEST['cfm'];
        $data["cfy"]=$_REQUEST['cfy'];
        $data["ctm"]=$_REQUEST['ctm'];
        $data["cty"]=$_REQUEST['cty'];
        $data["include_st"]=$_REQUEST['include_st'];
        $data["show_result"]="yes_mis";
        $data["r_output"]=$_REQUEST['r_output'];
        //echo "<pre>";
        //  echo print_r($data,true);
        // echo "</pre>";

        if($_REQUEST['r_output']=="b")//browser
        {
            //echo "<pre>";
            //echo print_r($data,true);
            //echo "</pre>";
            $this->load->view('shikshaStats/deferral',$data);
            //$smarty->display("deferral_new.htm");
        }
        elseif ($_REQUEST['r_output']=="ex")//excel sheet
        {
            $clients=$this->load->view('shikshaStats/deferral',$data);
            $file_name="deferral_mis.xls";
            header("Content-Type:  application/vnd.ms-excel");
            header("Content-Disposition: filename=\"$file_name\"");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            echo $clients;
        }
        //echo print_r($_REQUEST,true);
    }

    function gettransactiondetails()
    {
//        echo print_r($_REQUEST,true);
        $_REQUEST['branchList']=implode(",",$_REQUEST['branches']);
        $this->load->library('deferral_client');
        $deferralObj= new Deferral_client();
        $results = $deferralObj->gettransactiondetails(1,$_REQUEST);
        $data['show_result']="yes_trans"; 
        $data['results']=$results;
        if($_REQUEST['r_output']=="b")//browser
        {
            $this->load->view('shikshaStats/deferral',$data);
        }
        elseif ($_REQUEST['r_output']=="ex")//excel sheet
        {
            //$clients=$this->load->view('shikshaStats/deferral',$data);
            $leads=$results;
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
            $file_name="deferral_mis.xls";
             header("Content-Type:  application/vnd.ms-excel");
            header("Content-Disposition: filename=\"$file_name\"");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            echo $csv;
            //echo $clients;
        }
        /*$array_mis.="<head><meta http-equiv='Content-Type'
            content='text/html; charset=iso-8859-1'>
            <link rel='stylesheet' href='99acres.css' type='text/css'>
            <link rel='stylesheet' href='/css/99style.css' type='text/css'>
            <link rel='stylesheet' href='styles.css' type='text/css'>
            <link rel='stylesheet' type='text/css' href='css/homepage1.css'>
            <link rel='stylesheet' type='text/css' href='css/head_subhead.css'>
            </head>";
        $array_mis.="<table border='1'>";//main table starts here
        $array_mis.="<tr class='label'><td colspan=5> <b>Transaction Distribution MIS</b></td></tr>";
        foreach($results as $temp_select_row)
        {
            //check for same trans_id
            if($trans_id!=$temp_select_row['TRANSACTION_ID'])
            {
                $oldtID=$trans_id;
                $trans_id=$temp_select_row['TRANSACTION_ID'];
                $count_trans++;
                if($flag==1)
                {
                    if($ammount!=$receipt_share)
                    {
                //        echo "CheckTrans ".$oldtID."\n";
                    }
                    $array_mis.="</td>";
                    $array_mis.="</tr>";//end of receipt row
                    $array_mis.="</table>";//end of receipt table
                    $array_mis.="</td>";
                    $array_mis.="</tr>";//end of product row
                    $array_mis.="</table>";//end of product table
                    $array_mis.="</td>";
                    $array_mis.="</tr>";//end of transaction row

                }
                $array_mis.="<tr class='label'><td>SERIAL No.</td><td>TRANSACTION_ID</td><td>CENTER</td><td>AMOUNT</td><td>PRODUCTS AND THEIR COLLECTION DETAILS</td></tr>";
                $array_mis.="<tr><td valign='top' class='text2'>".$count_trans."</td><td valign='top' class='text2'>".$temp_select_row['TRANSACTION_ID']."</td><td valign='top' class='text2'>".$temp_select_row['CENTER']."</td><td valign='top' class='text2'>".$temp_select_row['AMOUNT']."</td>";
                $array_mis.="<td>";
                $ammount=$temp_select_row['AMOUNT'];
                $receipt_share=0;
                $flag2=0;
            }
            if($trans_id==$temp_select_row['TRANSACTION_ID'])
            {

                //check for same product
                if($psid!=$temp_select_row['PSID'])
                {
                    if($flag2==1)
                    {
                        $array_mis.="</td>";
                        $array_mis.="</tr>";//end of receipt row
                        $array_mis.="</table>";//end of receipt table
                        $array_mis.="</td>";
                        //$array_mis.="</tr>";//end of product row
                        //$array_mis.="</table>";//end of product table

                    }
                    $psid=$temp_select_row['PSID'];
                    $array_mis.="<table border='1'><tr class='label'><td>PRODUCT</td><td>PRODUCT_SHARE</td><td>QUANTITY</td><td>DEFERRABLE_DURATION</td><td>SELLING_PRICE</td><td >REQUIRED_PER_MONTH</td><td>START_DATE</td><td>END_DATE</td><td>DEFERRABLE</td></tr>";//table for product start
                    $array_mis.="<tr>";
                    $array_mis.="<td valign='top' class='text2'>".$temp_select_row['PRODUCT']."</td>";
                    $array_mis.="<td valign='top' class='text2'>".$temp_select_row['PRODUCT_SHARE']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>".$temp_select_row['QUANTITY']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>".$temp_select_row['DURATION']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>".$temp_select_row['SELLING_PRICE']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>".$temp_select_row['REQUIRED_MONTH']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>".$temp_select_row['START_DATE']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>".$temp_select_row['END_DATE']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>".$temp_select_row['DEFERRABLE']."</td>";
                    $array_mis.= "<td valign='top' class='text2'>";//cell for Receipt table start
                    $flag2=1;
                    $flag3=0;
                    unset($receipt);
                    unset($collection_date);
                    unset($brought_forward_date);
                }
                //$array_mis.="psid".$psid."tran".$temp_select_row['RECEIPT_ID'];
                if($psid==$temp_select_row['PSID'])
                {
                    if($receipt!=$temp_select_row['RECEIPT_ID'])//checking for receipt
                    {
                        if($flag3==1)
                        {
                            $array_mis.="</td>";
                            $array_mis.="</tr>";//end of receipt row
                            $array_mis.="</table>";//end of receipt table

                        }
                        $receipt=$temp_select_row['RECEIPT_ID'];
                        $array_mis.="<table border='0'><tr class='label'><td>RECEIPT_ID</td><td>RECEIPT_AMOUNT</td><td>RECEIPT_DATE</td><td>RECEIPT_SHARE</td></tr>";//table for receipt start
                        $array_mis.="<tr>";
                        $array_mis.="<td valign='top' class='text2'>".$temp_select_row['RECEIPT_ID']."</td>";
                        $array_mis.="<td valign='top' class='text2'>".$temp_select_row['RECEIPT_AMOUNT']."</td>";
                        $array_mis.="<td valign='top' class='text2'>".$temp_select_row['RECEIPT_DATE']."</td>";
                        $array_mis.="<td valign='top' class='text2'>".$temp_select_row['RECEIPT_SHARE']."</td>";
                        $array_mis.="<td valign='top' class='text2'>";//cell for collection table start
                        $receipt_share=$receipt_share+$temp_select_row['RECEIPT_SHARE'];
                        $flag3=1;
                        $header_flag=1;
                        //$array_mis.="xyz";
                    }
                    if($receipt==$temp_select_row['RECEIPT_ID'])
                    {  
                        //$array_mis.="xyz";
                        if($header_flag==1)
                        {
                        //$array_mis.="abc";
                            $array_mis.="<table border='0' width='100%'><tr class='label'><td width='15%' >COLLECTION_DATE</td><td  width='10%'>COLLECTION</td><td width='10%'>ASSIGN</td><td width='15%'>CARRY_FORWARD</td>
                                <td width='20%' >BROUGHT_FORWARD_DATE </td><td width='20%'>BROUGHT_FORWARD_AMOUNT </td></tr>";//table for collection start
                                $header_flag=0;
                        }
                        else 
                        {
                            $array_mis.="<table border='0' width='100%'>";
                        }

                        if($pre_receiptid==$temp_select_row['RECEIPT_ID'] && $pre_prod==$temp_select_row['PSID'] )
                        {
                            $temp_select_row['COLLECTION_DATE']="";
                            $temp_select_row['COLLECTION']="";
                            $temp_select_row['ASSIGN']="";
                            $temp_select_row['CARRY_FORWARD']="";

                        }
                        else 
                        {
                            $pre_receiptid=$temp_select_row['RECEIPT_ID'];
                            $pre_prod=$temp_select_row['PSID'];
                        }

                        $array_mis.="<tr>";
                        $array_mis.="<td valign='top' width='15%' class='text2'>".$temp_select_row['COLLECTION_DATE']."</td>";
                        $array_mis.="<td valign='top' width='10%' class='text2'>".$temp_select_row['COLLECTION']."</td>";
                        $array_mis.="<td valign='top' width='6%' class='text2'>".$temp_select_row['ASSIGN']."</td>";
                        $array_mis.="<td valign='top'  width='14%' class='text2'>".$temp_select_row['CARRY_FORWARD']."</td>";
                        $array_mis.="<td valign='top'  width='21%' class='text2'>".$temp_select_row['BROUGHT_FORWARD_DATE']."</td>";
                        $array_mis.="<td valign='top'  width='24%'  class='text2'>".$temp_select_row['BROUGHT_FORWARD_AMOUNT']." </td>";
                        $array_mis.="</tr>";//end of receipt row
                        $array_mis.="</table>";//end of collection table
                            $flag=1;
                    }
                }
            }
        }
        $array_mis.="</table></table></table>";
        echo $array_mis;
        //echo "<pre>"; 
        //echo print_r($results,true);
       //echo "</pre>";*/
    }
}

?>
