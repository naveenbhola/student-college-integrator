<?php
class Deferral_Server extends MX_Controller 
{

    function index()
    {
        $this->init();
        //error_log("i am here at server 1");

        $config['functions']['getDetails']=array('function'=>'Deferral_Server.getDetails');
        $config['functions']['getTransactionDetails']=array('function'=>'Deferral_Server.getTransactionDetails');
        $config['functions']['getbranchNamebyID']=array('function'=>'Deferral_Server.getbranchNamebyID');
        $config['functions']['UncountedTransaction']=array('function'=>'Deferral_Server.UncountedTransaction');
        $args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
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
        $this->dbLibObj = DbLibCommon::getInstance('deferral');
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

    function init()
    {
        set_time_limit(0);
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('search/parsexml');
        $this->load->library('messageboardconfig');
        $this->load->helper('url');
    }
    function getDetails($request)
    {
        $parameters=$request->output_parameters();
        error_log("i am here at server".print_r($parameters,true));
        $req_par=$parameters[1];
        $alloc_date_from=$req_par['afy']."-".$req_par['afm']."-01";
        $alloc_date_to=$req_par['aty']."-".$req_par['atm']."-31";
        $collec_date_to=$req_par['cty']."-".$req_par['ctm']."-31";
        $collec_date_from=$req_par['cfy']."-".$req_par['cfm']."-01";
        if($req_par['branches'][0] != "") //If the user hasn't selected "ALL"
        {
            $branchArray=explode(",",$req_par['branchList']);
            foreach($branchArray as $value)
            {
                error_log("Shivam".print_r($value,true));
                $new_branch[]="'".$value."'";
                $center_arr[]=$value;
            }
            $center_sql= " AND CENTER IN (" . implode($new_branch,',') .") ";
        }
        else
        {
            $center_sql= "";
            $center_arr=array();
        }

        if($req_par['currency']=='INR') // in case of Rupee
        {

            $currency_sql=" AND CUR_TYPE=1";

        }
        else // in case of Dollar
        {
            $currency_sql=" AND CUR_TYPE=2";    
        }

        if($req_par['include_st'] == "0")//in case of service tax  not included
        {
            $sql1="SELECT TD.CENTER,DATE,MONTH(DATE) AS MONTH, YEAR(DATE ) AS YEAR ,SUM(COLLECTION/ST_RATE) SC,SUM(ASSIGN/ST_RATE) SA, SUM(CARRY_FORWARD/ST_RATE) SCF FROM  Deferral.TRANSACTION_DISTRIBUTION T,Deferral.TRANSACTION_DETAILS TD WHERE T.TRANS_ID=TD.TRANS_ID AND DATE BETWEEN '$collec_date_from' AND '$collec_date_to' $center_sql $currency_sql GROUP BY CENTER, YEAR, MONTH";
        }
        else
        {
            $sql1="SELECT TD.CENTER,DATE,MONTH(DATE) AS MONTH, YEAR(DATE ) AS YEAR ,SUM(COLLECTION) SC,SUM(ASSIGN) SA, SUM(CARRY_FORWARD) SCF FROM  Deferral.TRANSACTION_DISTRIBUTION T,Deferral.TRANSACTION_DETAILS TD WHERE T.TRANS_ID=TD.TRANS_ID AND DATE BETWEEN '$collec_date_from' AND '$collec_date_to' $center_sql $currency_sql GROUP BY CENTER, YEAR, MONTH";

        }
        $dbHandle = $this->getDbHandler();
        if($dbHandle == ''){
            log_message('error','getMostContributingUser can not create db handle');
        }
        error_log("Shivam sql ".$sql1);
        $result1 = $dbHandle->query($sql1);
        $result1Array=array();
        foreach($result1->result_array() as $row1)
        {
            array_push($result1Array,array($row1,'struct'));

            /*$center_index =  array_search($row1['CENTER'],$center_arr);
              if($center_index === FALSE)
              {
              $center_arr[] = $row1['CENTER'];
              $center_index =  array_search($row1['CENTER'],$center_arr);
              }
              $year=$row1['YEAR'];
              $month=$row1['MONTH'];
              $branch[$center_index][$year][$month]['COLLECTION']=round($row1['SC'],2);
              $branch[$center_index][$year][$month]['ASSIGN']=round($row1['SA'],2);
            $branch[$center_index][$year][$month]['CF']=round($row1['SCF'],2);*/
        }

        if($req_par['include_st'] == "0")//in case of service tax  not included
        {

            $sql2="SELECT CENTER,MONTH(DATE) AS MONTH, YEAR(DATE) AS YEAR, SUM(BROUGHT_FORWARD/ST_RATE) AS SBF FROM  Deferral.BROUGHT_FORWARD B,Deferral.TRANSACTION_DETAILS TD WHERE  TD.TRANS_ID=B.TRANS_ID AND FROM_DATE BETWEEN '$collec_date_from' AND '$collec_date_to' AND DATE BETWEEN '$alloc_date_from' AND '$alloc_date_to' $center_sql  $currency_sql GROUP BY CENTER, YEAR, MONTH";
        }
        if($req_par['include_st'] == "1")// if st included
        {

            $sql2="SELECT CENTER,MONTH(DATE) AS MONTH, YEAR(DATE) AS YEAR, SUM(BROUGHT_FORWARD) AS SBF FROM  Deferral.BROUGHT_FORWARD B,Deferral.TRANSACTION_DETAILS TD WHERE  TD.TRANS_ID=B.TRANS_ID AND FROM_DATE BETWEEN '$collec_date_from' AND '$collec_date_to' AND DATE BETWEEN '$alloc_date_from' AND '$alloc_date_to' $center_sql $currency_sql GROUP BY CENTER, YEAR, MONTH";
        }
        // error_log("Shivam sql2 ".$sql2);
        $result2 = $dbHandle->query($sql2);
        $result2Array=array();
        foreach($result2->result_array() as $row2)
        {

            array_push($result2Array,array($row2,'struct'));
            /*$center_index =  array_search($row2['CENTER'],$center_arr);
              if($center_index === FALSE)
              {
              $center_arr[] = $row2['CENTER'];
              $center_index =  array_search($row2['CENTER'],$center_arr);
              }
              $year=$row2['YEAR'];
              $month=$row2['MONTH'];
            $branch[$center_index][$year][$month]['BF']=round($row2['SBF'],2);*/
        }
        /*
           $loop=0;
           foreach($center_arr as $center_index => $cname)
           {
           for($year=$afy;$year<=$aty;$year++)
           {
           if(!$loop)
           $date_year[]=$year;
           if($year<$aty)
           {
           $end_month=12;
           }
           else
           {
           $end_month=$atm;
           }
           if($year==$afy)
           {
           $start_month=$afm;
           }
           else
           $start_month=1;
           $count=0;
           for($month=$start_month;$month<=$end_month;$month++)
           {

           if(!$loop)
           {
           $date_month[$year][$count]['value']=$month;
           $date_month[$year][$count]['label']=GetMonth($month);
        //print_r($date_month[$year][$count]);
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
        }*/
        /*$smarty->assign("center_arr",$center_arr);
          $smarty->assign("timespan",$timespan);
          $smarty->assign("total",$total);
          $smarty->assign("sum",$sums);
          $smarty->assign("total_sum",$total_sums);
          $smarty->assign("branch",$branch);
          $smarty->assign("date_year",$date_year);
          $smarty->assign("date_month",$date_month);
          $smarty->assign("currency",$currency);
          $smarty->assign("afy",$afy);
          $smarty->assign("aty",$aty);
          $smarty->assign("atm",$atm);
          $smarty->assign("afm",$afm);
          $smarty->assign("cfm",$cfm);
          $smarty->assign("cfy",$cfy);
          $smarty->assign("ctm",$ctm);
          $smarty->assign("cty",$cty);
          $smarty->assign("include_st",$include_st);
          $smarty->assign("show_result","yes_mis");

          if($r_output=="b")//browser
          {
          $smarty->display("deferral_new.htm");
          }
          elseif ($r_output=="ex")//excel sheet
          {
          $clients=$smarty->fetch("deferral_new.htm");
          $file_name="deferral_mis.xls";
          header("Content-Type:  application/vnd.ms-excel");
          header("Content-Disposition: filename=\"$file_name\"");
          header("Expires: 0");
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          echo $clients;
        }*/
        $msgArray=array(); 
        array_push($msgArray,array(array('result1array'=>array($result1Array,'struct'),'result2array'=>array($result2Array,'struct')),'struct'));
        $response = array($msgArray,'struct');
        // error_log("server ".print_r($response,true));
        return $this->xmlrpc->send_response($response);

    }

    function getTransactionDetails($request)
    {
        $parameters=$request->output_parameters();
        // error_log("i am here at server".print_r($parameters,true));
        $req_par=$parameters[1];
        $deferral_array=array();
        if($req_par['criteria_check']=="I")//individual transaction check is selected
        {

            $trans_sql= "AND TD.TRANS_ID='".$req_par['trans_id_text']."'";

            $date_sql='';
            $center_sql='';
            $collection_sql="";
            $bf_sql="";
        }

        elseif($req_par['criteria_check']=="C")//if search criteria is selected
        {
            $alloc_date_from=$req_par['afy']."-".$req_par['afm']."-01";
            $alloc_date_to=$req_par['aty']."-".$req_par['atm']."-31";
            $collec_date_to=$req_par['cty']."-".$req_par['ctm']."-31";
            $collec_date_from=$req_par['cfy']."-".$req_par['cfm']."-01";
            if($req_par['branches'][0] != "") //If the user hasn't selected "ALL"
            {
                $branchArray=explode(",",$req_par['branchList']);
                foreach($branchArray as $value)
                {
                    // error_log("Shivam".print_r($value,true));
                    $new_branch[]="'".$value."'";
                    $center_arr[]=$value;
                }
                $center_sql= " AND CENTER IN (" . implode($new_branch,',') .") ";
            }
            else
            {
                $center_sql= "";
                $center_arr=array();
            }
            $date_sql="AND T.DATE BETWEEN '$collec_date_from' AND '$collec_date_to'";
            $collection_sql=" AND COLLECTION_DATE BETWEEN '$collec_date_from' AND '$collec_date_to'";
            $bf_sql="AND BF.DATE BETWEEN '$alloc_date_from' AND '$alloc_date_to'";
        }
        if($req_par['currency']=='INR') // in case of Rupee
        {

            $currency_sql=" AND CUR_TYPE=1";

        }
        else // in case of Dollar
        {
            $currency_sql=" AND CUR_TYPE=2";    
        }
        $trans_arr=array();
        $psid_arr=array();
        $cid_arr=array();
        $sql1="SELECT BranchName, T.PSID as PSID,T.CID,TD.TRANS_ID,TD.ST_RATE,TD.CENTER,TD.AMOUNT FROM  Deferral.TRANSACTION_DISTRIBUTION T,Deferral.TRANSACTION_DETAILS TD, SUMS.Sums_Branch_List WHERE Sums_Branch_List.BranchId=TD.CENTER and T.TRANS_ID=TD.TRANS_ID $date_sql  $center_sql $trans_sql $currency_sql   ORDER BY TRANS_ID,PSID,CID";
        //    error_log("transsql".$sql1);
        $dbHandle = $this->getDbHandler();
        if($dbHandle == ''){
            log_message('error','getMostContributingUser can not create db handle');
        }

        $result1 = $dbHandle->query($sql1);
        //$result1Array=array();
        //foreach($result1->result_array() as $row1)
        //{

        //$result1 = mysql_query($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
        if($result1->num_rows()==0)// if no records are found
        {
            $error_msg2="No Transactions exist according to your earch criteria ! ";
        }
        $flag=0;
        $count_trans=0;
        $trans_id='';

        foreach($result1->result_array() as $row1)
        {

            $cid=$row1['CID'];

            $count_trans++;
            if($flag==1)
            {
            }
            $trans_id=$row1['TRANS_ID'];
            $row1_AMOUNT=$row1['AMOUNT'];

            if($req_par['include_st'] == "0")//in case of service tax not included
            {
                $row1_AMOUNT=round(($row1_AMOUNT/$row1['ST_RATE']),2);
            }

            $rc=0;

            $psid=$row1['PSID'];
            $sql_p="SELECT * FROM SUMS.Subscription PH, SUMS.Subscription_Product_Mapping SPM, Deferral.PRODUCT_SUBSCRIPTION PS, SUMS.Base_Products WHERE SPM.Status!='HISTORY' AND PH.SubscriptionId=PS.SUBID AND SPM.SubscriptionId=PH.SubscriptionId AND  PH.BaseProductId=Base_Products.BaseProductId AND PS.TRANS_ID='$row1[TRANS_ID]' AND PS.PSID=$row1[PSID]";
            // error_log("productsql".$sql_p);
            $result_p = $dbHandle->query($sql_p);
            //$result_p=mysql_query($sql_p);

            foreach($result_p->result_array() as $row_p)
            {
                //   $row_p=mysql_fetch_array($result_p);
                $selling_price=($row_p['SHARE']*$row1_AMOUNT)/100;
                $exp_start_date=explode("-",$row_p['START_DATE']);
                $exp_end_date=explode("-",$row_p['END_DATE']);

                $sd=mktime(0,0,0, (int) $exp_start_date[1],(int) $exp_start_date[2],(int) $exp_start_date[0]);
                $ed=mktime(0,0,0,(int) $exp_end_date[1], (int) $exp_end_date[2],(int)$exp_end_date[0]);
                $month_difference =(floor(($ed-$sd)/2628000)+1);

                if($exp_start_date[0]==$exp_end_date[0])  
                {
                    if($exp_end_date[1]>$exp_start_date[1]) // in case of jan 07 to mar 07
                    {
                        $month_difference=($exp_end_date[1]-$exp_start_date[1])+1;
                    }
                    else
                        $month_difference=0;

                }
                elseif($exp_start_date[0]<$exp_end_date[0])
                {
                    if($exp_end_date[1]==$exp_start_date[1]) //in case of jan 07 to jan 08
                    {
                        $month_difference=13;
                    }
                    elseif ($exp_end_date[1]>$exp_start_date[1]) // in case of jan 07 to mar 08
                    {
                        $month_difference=13+($exp_end_date[1]-$exp_start_date[1]);
                    }
                    elseif($exp_end_date[1]<$exp_start_date[1]) // in case of dec 07 to mar 08
                    {
                        $month_difference=((13-$exp_start_date[1])+$exp_end_date[1]);
                    }
                }


                $required_month=round(($selling_price/$month_difference),2);
                $for_month="$month_difference months";
                if($row_p['PROD_DURATION_TYPE']=='Y')
                {
                    $prod_duration_type='Year(s)';
                }
                elseif($row_p['PROD_DURATION_TYPE']=='M')
                {
                    $prod_duration_type='Month(s)';
                }
                else
                {
                    $prod_duration_type='Day(s)';
                }

                //fetching receipt details by cid
                $sql_c="SELECT * FROM Deferral.COLLECTION C WHERE C.TRANS_ID='$row1[TRANS_ID]' AND CID=$cid $collection_sql";
                //  error_log($sql_c);
                //            $result_c=mysql_query($sql_c);
                $result_c = $dbHandle->query($sql_c);
                //$result_p=mysql_query($sql_p);

                foreach($result_c->result_array() as $row_c)
                {

                    //  $row_c=mysql_fetch_array($result_c);
                    if($row_c)
                    {
                        $row_c_AMOUNT=$row_c['AMOUNT'];
                        if($req_par['include_st'] == "0")//in case of service tax not included
                        {
                            $row_c_AMOUNT=round(($row_c_AMOUNT/$row1['ST_RATE']),2);
                        }
                        $receipt_share=round((($row_p['SHARE']*$row_c_AMOUNT)/100),2);

                        $alloc_sql="SELECT *,BF.DATE AS BDATE,T.DATE AS TDATE FROM Deferral.TRANSACTION_DISTRIBUTION T LEFT JOIN Deferral.BROUGHT_FORWARD BF ON T.TRANS_ID=BF.TRANS_ID AND T.CID=BF.CID AND T.PSID=BF.PSID WHERE T.CID=$cid AND T.PSID=$psid $date_sql";
                        error_log("alloc sql".$alloc_sql);
                        $alloc_result = $dbHandle->query($alloc_sql);
                        //$result_p=mysql_query($sql_p);

                        foreach($alloc_result->result_array() as $alloc_row)
                        {

                            //                $alloc_result=mysql_query($alloc_sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$brought_sql,"ShowErrTemplate");
                            //              while($alloc_row=mysql_fetch_array($alloc_result))
                            //            {
                            $alloc_COLLECTION=$alloc_row['COLLECTION'];
                            $alloc_ASSIGN=$alloc_row['ASSIGN'];
                            $alloc_CARRY_FORWARD=$alloc_row['CARRY_FORWARD'];

                            if($req_par['include_st'] == "0")//in case of service tax not included
                            {
                                $alloc_COLLECTION=round(($alloc_COLLECTION/$row1['ST_RATE']),2);
                                $alloc_ASSIGN=round(($alloc_ASSIGN/$row1['ST_RATE']),2);
                                $alloc_CARRY_FORWARD=round(($alloc_CARRY_FORWARD/$row1['ST_RATE']),2);
                            }
                            $exp_date=explode("-",$alloc_row['TDATE']);
                            $a_date=$exp_date[1]."-".$exp_date[0];

                            $alloc_BROUGHT_FORWARD=$alloc_row['BROUGHT_FORWARD'];

                            if($req_par['include_st'] == "0")//in case of service tax not included
                            {
                                $alloc_BROUGHT_FORWARD=round(($alloc_BROUGHT_FORWARD/$row1['ST_RATE']),2);
                            }
                            $exp_date=explode("-",$alloc_row['BDATE']);
                            $b_date=$exp_date[1]."-".$exp_date[0];
                            $insert_array=array();
                            $insert_array['TRANSACTION_ID']=$trans_id;
                            $insert_array['CENTER']=$row1['BranchName'];
                            $insert_array['AMOUNT']=$row1_AMOUNT;
                            $insert_array['PSID']=$psid;
                            $insert_array['PRODUCT']=$row_p['BaseProdSubCategory']." ".$row_p['BaseProdCategory'];
                            $insert_array['PRODUCT_SHARE']=$row_p['SHARE'];
                            $insert_array['QUANTITY']=$row_p['TotalBaseProdQuantity'];
                            $insert_array['DURATION']=$for_month;
                            $insert_array['SELLING_PRICE']=$selling_price;
                            $insert_array['REQUIRED_MONTH']=$required_month;
                            $insert_array['START_DATE']=$row_p['START_DATE'];
                            $insert_array['END_DATE']=$row_p['END_DATE'];
                            $insert_array['DEFERRABLE']=$row_p['DEFERRABLE'];
                            $insert_array['RECEIPT_ID']=$row_c['RECEIPT_ID'];
                            $insert_array['RECEIPT_AMOUNT']=$row_c_AMOUNT;
                            $insert_array['RECEIPT_DATE']=$row_c['COLLECTION_DATE'];
                            $insert_array['RECEIPT_SHARE']=$receipt_share;
                            $insert_array['COLLECTION_DATE']=$a_date;
                            $insert_array['COLLECTION']=round($alloc_COLLECTION,2);
                            $insert_array['ASSIGN']=round($alloc_ASSIGN,2);
                            $insert_array['CARRY_FORWARD']=round($alloc_CARRY_FORWARD,2);
                            $insert_array['BROUGHT_FORWARD_DATE']=$b_date;
                            $insert_array['BROUGHT_FORWARD_AMOUNT']=round($alloc_BROUGHT_FORWARD,2);
                            //inserting data into temporay table
                            $temp_insert_sql="INSERT INTO DEF_TEMP (TRANSACTION_ID,
                                CENTER,
                                AMOUNT,
                                PSID,
                                PRODUCT,
                                PRODUCT_SHARE,
                                QUANTITY,
                                DURATION,
                                SELLING_PRICE,
                                REQUIRED_MONTH,
                                START_DATE,
                                END_DATE,
                                DEFERRABLE,
                                RECEIPT_ID,
                                RECEIPT_AMOUNT,
                                RECEIPT_DATE,
                                RECEIPT_SHARE,
                                COLLECTION_DATE,
                                COLLECTION,
                                ASSIGN,
                                CARRY_FORWARD,
                                BROUGHT_FORWARD_DATE,
                                BROUGHT_FORWARD_AMOUNT)
                                VALUES ('$trans_id',
                                    '$row1[CENTER]',
                                    '$row1_AMOUNT',
                                    '$psid',
                                    '$row_p[PROD_NAME] duration ($row_p[PROD_DURATION].$prod_duration_type)',
                                    '$row_p[SHARE]',
                                    '$row_p[PROD_QTY]',
                                    '$for_month',
                                    $selling_price, 
                                    '$required_month',
                                    '$row_p[START_DATE]',   
                                    '$row_p[END_DATE]',
                                    '$row_p[DEFERRABLE]',
                                    '$row_c[RECEIPT_ID]',
                                    $row_c_AMOUNT,
                                    '$row_c[COLLECTION_DATE]',
                                    '$receipt_share',
                                    '$a_date',
                                    '".round($alloc_COLLECTION,2)."',
                                    '".round($alloc_ASSIGN,2)."',
                                    '".round($alloc_CARRY_FORWARD,2)."',
                                    '$b_date',
                                    '".round($alloc_BROUGHT_FORWARD,2)."')";
                            //                                    error_log("insertsql".$temp_insert_sql);
                            //                                   error_log("isertarray".print_r($insert_array,true));
                            array_push($deferral_array,array($insert_array,'struct'));
                            //$temp_insert_result = mysql_query($temp_insert_sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$temp_insert_sql,"ShowErrTemplate");
                        }
                    }
                }
            }
        }

        $response = array($deferral_array,'struct');
        //        error_log("server ".print_r($response,true));
        return $this->xmlrpc->send_response($response);

    }
    function getbranchNamebyID($request)
    {
        $parameters=$request->output_parameters();
        //$center_list=implode(",",$parameters[1]);
        $center_list=$parameters[1];
        $sql1="select BranchName,BranchId from SUMS.Sums_Branch_List where BranchId in (?)";
        $dbConfig = array( 'hostname'=>'localhost');
        $dbHandle = $this->getDbHandler();
        if($dbHandle == ''){
            log_message('error','getMostContributingUser can not create db handle');
        }
        // error_log("Shivam sql ".$sql1);
        $result1 = $dbHandle->query($sql1,array($center_list));
        $result1Array=array();
        foreach($result1->result_array() as $row1)
        {
            $result1Array[$row1['BranchId']]=$row1['BranchName'];
            //            array_push($result1Array,array($row1,'struct'));
        }
        $response = array($result1Array,'struct');
        //  error_log("server ".print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }

    function UncountedTransaction($request)
    {
        $sql1="select (select displayname from shiksha.tuser where userid=SalesBy) SalesBy ,Transaction.TransactTime, Cheque_Date as `Payment Date`, Payment_Mode, CancelCommets as Cancellation_Comments, Cheque_DD_Comments, Cheque_No, Transaction.TransactionId, State as Transaction_Status, (Amount_Received+TDS_Amount) as Amount,(select CurrencyCode from SUMS.Currency where Currency.CurrencyId=Quotation.CurrencyId) as Currency ,ifnull((select concat('<a href=\"mailto:',email,'\">',displayname,'</a>') from shiksha.tuser,SUMS.Transaction_Queue where userid=ApproverId and Transaction_Queue.TransactionId=Transaction.TransactionId and Transaction_Queue.State='PENDING'),'OPS_TEAM') as `To be Approved By`, Payment.Payment_Id, Part_Number, isPaid as Payment_Status from SUMS.Transaction_Queue, SUMS.Transaction, SUMS.Quotation, SUMS.Quotation_Product_Mapping, SUMS.Payment, SUMS.Payment_Details where Transaction.UIQuotationId = Quotation.UIQuotationId and Quotation.Status !='HISTORY' and Quotation.QuotationId = Quotation_Product_Mapping.QuotationId and (Quotation_Product_Mapping.DerivedProductId not in (select DerivedProductId from SUMS.Subscription_Product_Mapping, SUMS.Subscription where Subscription.TransactionId=Transaction.TransactionId and Subscription_Product_Mapping.SubscriptionId=Subscription.SubscriptionId) or Transaction_Queue.State='CANCELLED') and Transaction_Queue.TransactionId=Transaction.TransactionId and Payment.Transaction_Id=Transaction.TransactionId and Payment_Details.Payment_Id=Payment.Payment_Id and Payment_Details.isPaid='Paid' and Payment.Sale_Type!='Trial' group by concat(Payment.Payment_Id,'-','Part_Number') order by TransactTime ";
        $dbHandle = $this->getDbHandler();
        if($dbHandle == ''){
            log_message('error','getMostContributingUser can not create db handle');
        }
        error_log("Shivam sql ".$sql1);
        $result1 = $dbHandle->query($sql1);
        $result1Array=array();
        foreach($result1->result_array() as $row1)
        {
            array_push($result1Array,array($row1,'struct'));
            //            array_push($result1Array,array($row1,'struct'));
        }
        $response = array($result1Array,'struct');
        //  error_log("server ".print_r($response,true));
        return $this->xmlrpc->send_response($response);

    }

}
?>
