<?php
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','modal-message','online-styles','common'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','tooltip','onlineFormEnterprise','ana_common','json2'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>'',
	'title' => 'Enterprise User Dashboard'
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>

</script>
<?php
$this->load->view('enterprise/cmsTabs');
?>

<?php $data = $onlineFormEnterpriseInfo[instituteInfo][0][0];?>
<input type="hidden" name="instituteId" id="instituteId" value="<?php echo $data['institute_id'];?>">
<?php $info = $onlineFormEnterpriseInfo['instituteDetails'][0][0];?>
<?php if(!empty($info['instituteSpecId'])) {$displayFormId = $info['instituteSpecId'];} else {$displayFormId = $info['onlineFormId'];} ?>
<?php $formId = $info['onlineFormId']; ?>
<?php if(isset($info['GDPIDate']) && trim($info['GDPIDate'])!='' && trim($info['GDPIDate'])!='0000-00-00 00:00:00'){$gdpiDate = date("d/m/Y",strtotime($info['GDPIDate']));$gdpiLinkText = $gdpiDate;}else{$gdpiDate = date('d/m/Y');$gdpiLinkText = 'Update '.$gdPiName;}?>
<input type="checkbox" id="<?php echo $gdpiDate;?>#<?php echo $info['GDPILocation'];?>" style="display:none" checked="checked">
<?php $userId = isset($_REQUEST['userId'])?$_REQUEST['userId']:''?>
<div style="display:none;" id="requestDocumentContent"><?php echo $onlineFormEnterpriseInfo['instituteDetails'][0][0]['documentsRequired'];?></div>
<div style="display:none;" id="requestPhotographContent"><?php echo $onlineFormEnterpriseInfo['instituteDetails'][0][0]['imageSpecifications'];?></div>
<div id="UserFormId" style="display:none;"><?php echo $formId .'-'.$userId;?></div>
<div id="singleUserFormId" style="display:none;"></div>
<div id="userDraftDate" style="display:none"><?php echo date('d/m/Y',strtotime($onlineFormEnterprisePaymentInfo[0][draftDate]));?></div>
<div id="userDraftNumber" style="display:none"><?php echo $onlineFormEnterprisePaymentInfo[0][draftNumber];?></div>
<div id="userDraftPayeeBank" style="display:none"><?php echo $onlineFormEnterprisePaymentInfo[0][bankName];?></div>
<div id="instituteSpecId" style="display:none;"><?php echo $info['instituteSpecId'];?></div>
<?php //if(isset($info['GDPIDate']) && $info['GDPIDate']!=''){$gdpiDate = date("Y-m-d",strtotime($info['GDPIDate']));}else{$gdpiDate = 'yyyy-mm-dd';}?>
<div class="wrapperFxd">
<div id="appsFormWrapper">
<div id="departmentId" style="display:none;"><?php echo $_REQUEST['departmentId'];?></div>
<div id="courseId" style="display:none;"><?php echo $_REQUEST['courseId'];?></div>
<div id="totalNumberOfForms" style="display:none;"><?php echo $totalForm;?></div>
<div id="gdpiDateNew" style="display:none;"><?php echo $gdpiDate;?></div>
<div id="gdpiPlaceNew" style="display:none;"><?php echo $info['gdpiId'];?></div>
<div id="gdpiCityNames" style="display:none;"><?php echo $gdpiInfoCourseWise[$info['courseId']]['cityName'];?></div>
<div id="gdpiCityIds" style="display:none;"><?php echo $gdpiInfoCourseWise[$info['courseId']]['cityId'];?></div>
<div id="formId" style="display:none;"><?php echo $formId;?></div>
<div id="mainOnlineFormEnterpriseDiv">
	<!--Starts: breadcrumb-->
    <div id="breadcrumb">
    	<ul>
            <li><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?departmentId=<?php echo $info['departmentId'];?>"><?php echo $info['departmentName']?></a></li>
            <li class="last"><?php echo $info['firstname'].$displayFormId; ?></li>
        </ul>
    </div>
    <!--Ends: breadcrumb-->
        
    <div id="contentWrapper">
    	<!--<h2 class="welcome">Welcome <a href="#"><?php //echo $info['firstname']; ?></a></h2>-->
        <div class="myForms myFormsMargin">
        	<table width="100%" border="1" cellpadding="12">
            	<thead>
                	<tr>
                        <td width="100" valign="top">Application <br />Number</td>
                        <td width="100" valign="top">Form Stage</td>
                        <td width="100" valign="top">Location</td>
                        <td width="150" valign="top">Submission Date</td>
                        <td width="100" valign="top"><?=$gdPiName?></td>
                        <td width="120" valign="top">Form Status</td>
                        <td width="80" valign="top">Payment</td>
                        <td width="120" valign="top">View form</td>
                    </tr>
                </thead>
                <tr class="evenRow">

                    <td valign="top"><a href="javascript:void(0);" onclick="window.open('/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId=<?php echo $info['userId']; ?>&formId=<?php echo $info['onlineFormId']; ?>&institute_id=<?php echo $info['instituteId']; ?>&cId=<?php echo $info['courseId']; ?>&viewForm=true');"><?php echo $displayFormId;?> (<?php echo $info['firstname'];?><?php if(isset($info['middlename']) && $info['middlename']!=''){?>&nbsp;<?php echo $info['middlename'];}?><?php if(isset($info['lastname']) && $info['lastname']!=''){?>&nbsp;<?php echo $info['lastname'];}?>)</a></td>
                    <td valign="top"><?php if($info['formStatus']=='accepted') echo 'Accepted';else if($info['formStatus']=='rejected') echo 'Rejected';else if($info['formStatus']=='shortlisted') echo 'Shortlisted';else if($info['formStatus']=='cancelled') echo 'Cancelled';else echo 'Under Process';?></td>
                    <td valign="top"><?php echo $info['cityName']; ?></td>
                    <td valign="top"><?php echo date('m/d/Y H:i:s ',strtotime($info['cDate'])); ?></td>
                    <td valign="top"><a href="javascript:void(0);" onclick="showGDPIDateOverlay('<?php echo $gdpiDate;?>','<?php echo $info['gdpiId'];?>','<?php echo $gdpiInfoCourseWise[$info['courseId']]['cityName'];?>','<?php echo $gdpiInfoCourseWise[$info['courseId']]['cityId'];?>','<?php echo $info['onlineFormId'];?>');var x= sendAlerts();x('5');setUserFormId('<?php echo $info['onlineFormId'];?>-<?php echo $info['userId'];?>','<?php echo $info['instituteSpecId'];?>');"><?php echo $gdpiLinkText;?></a></td>
                    <td valign="top" <?php if($info['onlineFormEnterpriseStatus']=='New Submission'){?> style="color:#d61a18;"<?php }?>><?php echo str_ireplace("GD/PI",$gdPiName,$info['onlineFormEnterpriseStatus']);?></td>
                    <td valign="top"><?php if($onlineFormEnterprisePaymentInfo[0][mode]=='Offline'){echo 'Draft';}else{echo $onlineFormEnterprisePaymentInfo[0][mode];}?></td>
                    <td valign="top" align="center"><a title="View" class="viewIcon" onclick="window.open('/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId=<?php echo $info['userId']; ?>&formId=<?php echo $info['onlineFormId']; ?>&institute_id=<?php echo $info['instituteId']; ?>&cId=<?php echo $info['courseId']; ?>&viewForm=true');">&nbsp;</a></td>
                </tr>
            </table>
            <div class="buttonBlock">
            	<!--<input type="button" value="Draft received" class="entOrangeButton" id="1"/> &nbsp;-->
                <input type="button" value="Request photographs" class="entOrangeButton" id="2"/> &nbsp;
                <input type="button" value="Request Documents" class="entOrangeButton" id="3"/> &nbsp;
                <div class="spacer10 clearAll" ></div>
				<input type="button" value="Confirm Acceptance" class="entOrangeButton" id="4"/> &nbsp;
                <input type="button" value="Update <?=$gdPiName?>" class="entOrangeButton" id="5"/> &nbsp;
                <input type="button" value="Reject Application" class="entOrangeButton" id="6"/> &nbsp;
                <input type="button" value="Shortlist Application" class="entOrangeButton" id="18"/>
                <div class="spacer10 clearAll" ></div>
                <!-- <input type="button" value="Cancel Application" class="cancelButton" id="17"/>
 -->
            </div>
        </div>
        <?php if($onlineFormEnterprisePaymentInfo[0][draftDate]=='0000-00-00'){$draftdate = date('d/m/Y');}else{$draftdate = date('d/m/Y',strtotime($onlineFormEnterprisePaymentInfo[0][draftDate]));}?>
        <div class="payDetails" id='online_payment_details_of_bug_fix'>
            <h3>Payment Details</h3>
            <?php if($onlineFormEnterprisePaymentInfo[0][mode]=='Online'):?>
            <div class="payDetailsLeftCol">
                <ul>
                    <li><strong>Order Value : </strong> <span>INR <?php echo $onlineFormEnterprisePaymentInfo[0]['amount'];?></span></li>
                    <li><strong>Date : </strong> <span><?php echo date('d/m/Y h:m:s A',strtotime($onlineFormEnterprisePaymentInfo[0]['date']));?></span></li>
                    <li><strong>Payment Mode : </strong> <span>CCAvenue Payment Gateway</span></li>
                    <li><strong>Payment Status : </strong> <span>Payment Successful</span></li>
                    <li><strong>Transaction ID: </strong> <span><?php echo $onlineFormEnterprisePaymentInfo[0]['orderId'];?></span></li>
                    <li><strong>Status : </strong> <span>Transaction Successful</span></li>
                </ul>
            </div>
            <?php endif;?>
           <?php if($onlineFormEnterprisePaymentInfo[0][status]=='Started' && $onlineFormEnterprisePaymentInfo[0][mode]=='Offline'):?>
            <div class="payDetailsMidCol">
                <ul>
                    <li><strong>Order Value: </strong> <span>INR <?php echo $onlineFormEnterprisePaymentInfo[0]['amount'];?></span></li>
                    <li><strong>Payment Mode: </strong> <span>Draft</span></li>
                    <li><b><a href="javascript:void(0);" onclick="showDDOverlay('<?php echo $draftdate;?>','<?php echo $onlineFormEnterprisePaymentInfo[0][mode];?>','<?php echo $onlineFormEnterprisePaymentInfo[0][draftNumber];?>','<?php echo $onlineFormEnterprisePaymentInfo[0][bankName];?>','<?php echo $userId; ?>','<?php echo $displayFormId;?>','<?php echo $data[institute_id];?>'); return false;">Confirm draft receipt</a></b></li>
                </ul>
            </div>
            <?php endif;?>
            <?php if(($onlineFormEnterprisePaymentInfo[0][status]=='Pending' || $onlineFormEnterprisePaymentInfo[0][status]=='Success') && $onlineFormEnterprisePaymentInfo[0][mode]=='Offline'):?>
            <div class="payDetailsRtCol">
                <ul>
                    <li><strong>Order Value : </strong> <span>INR <?php echo $onlineFormEnterprisePaymentInfo[0]['amount'];?></span></li>
		    <?php if( (!isset($onlineFormEnterprisePaymentInfo[0]['draftDate'])) || $onlineFormEnterprisePaymentInfo[0]['draftDate']=='' || $onlineFormEnterprisePaymentInfo[0]['draftDate']=='0000-00-00' || $onlineFormEnterprisePaymentInfo[0]['draftDate']=='0000-00-00 00:00:00'){ ?>
                    <li><strong>Date : </strong></li>
		    <?php }else{ ?>
                    <li><strong>Date : </strong> <span><?php echo date('d/m/Y',strtotime($onlineFormEnterprisePaymentInfo[0]['draftDate']));?></span></li>
		    <?php } ?>
                    <li><strong>Payment Mode : </strong> <span>Draft</span></li>
                    <li><strong>Draft No : </strong> <span><?php echo $onlineFormEnterprisePaymentInfo[0][draftNumber];?></span></li>
                    <li><strong>Payee Bank : </strong> <span><?php echo $onlineFormEnterprisePaymentInfo[0][bankName];?></span></li>
                    <li><strong>Transaction ID: </strong> <span><?php echo $onlineFormEnterprisePaymentInfo[0]['orderId'];?></span></li>
                    <li><strong>Status : </strong> <span>Transaction Successful</span></li>
                    <?php if($onlineFormEnterprisePaymentInfo[0][status]=='Pending'){?>
                    <li><b><a href="javascript:void(0);" onclick="showDDOverlay('<?php echo $draftdate;?>','<?php echo $onlineFormEnterprisePaymentInfo[0][mode];?>','<?php echo $onlineFormEnterprisePaymentInfo[0][draftNumber];?>','<?php echo $onlineFormEnterprisePaymentInfo[0][bankName];?>','<?php echo $userId; ?>','<?php echo $displayFormId;?>','<?php echo $data[institute_id];?>'); return false;">Confirm draft receipt</a></b></li>
                    <?php } ?>
                </ul>
            </div>
            <?php endif;?>

        </div>
       
        <div class="communicationHistory" id="communicationHistory">
        	<h3>Communication History</h3>
            <ul>
                <?php for($k=0;$k<count($info['alertsMessageText']);$k++){?>
                <?php $date = explode('-',$info['alertsCreatedDate'][$k]);?>
                <?php $timeandday = explode(' ',$date[2]);?>
                <?php $alertsCreatedDate = $timeandday[0].'/'.$date[1].'/'.$date[0].' '.$timeandday[1];?>
                <?php if($onlineFormEnterprisePaymentInfo[0][status]=='Success'){ $paymentStatus ='Confirmed';}else{$paymentStatus = $onlineFormEnterprisePaymentInfo[0][status];}?>
                <?php $link1 = "<a title=\"View\" onclick=\"window.open('/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId=$info[userId]&formId=$info[onlineFormId]&cId=$info[courseId]&viewForm=true');\">here</a>";?>
                <?php $link = "<a href=\"javascript:void(0);\" onclick=\"showDDOverlay('".$draftdate."','".$onlineFormEnterprisePaymentInfo[0][mode]."','".$onlineFormEnterprisePaymentInfo[0][draftNumber]."','".$onlineFormEnterprisePaymentInfo[0][bankName]."','".$userId."','".$displayFormId."','".$data[institute_id]."'); return false;\">here.</a>";?>

                <?php if($info['alertsMsgId'][$k]=='40' && $info['alertStatus'][$k]=='Viewed'){ $link='here.';}?>
                                       <li><span id="<?php echo "alert".$info['alertStatusId'][$k];?>"><?php echo date("F d",strtotime($info['alertsCreatedDate'][$k]));?></span>:&nbsp;<?php echo preg_replace(array('/{DDhere}/','/<Fistname>/','/<MiddleName>/','/<Lastname>/','/<Photograph_specs>/','/<communication address_mail>/','/<communication address_email>/','/<GD\/PI Time>/','/<GD\/PI Location>/','/<course_name>/','/<application number>/','/<Institute_name>/','/DD\/MM\/YY Min:Sec/','/<Documents_Specs>/','/<communication address>/','/<payment status>/','/<Transaction Number>/','/<Invoice Number>/','/{ScoreDeatails}/','/GD\/PI/'),array($link,$info['firstname'],$info['middlename'],$info['lastname'],$info['imageSpecifications'],$info['instituteAddress'],$info['instituteEmailId'],$gdpiDate,$info['GDPILocation'],$info['courseTitle'],$displayFormId,$data['institute_name'],$alertsCreatedDate,$info['documentsRequired'],$info['instituteAddress'],$paymentStatus,$onlineFormEnterprisePaymentInfo[0]['orderId'],$onlineFormEnterprisePaymentInfo[0]['orderId'],$link1,$gdPiName),$info['alertsMessageText'][$k]); ?></li>

                <?php }?>
            </ul>
            <a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?courseId=<?php echo $info['courseId'];?>" class="font14"> <span class="font16">&laquo;</span> My Dashboard</a>
        </div>
        <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
</div>
</div>
</div>
<?php
$this->load->view('enterprise/footer');
?>

<?php if(isset($_REQUEST['alertId']) && $_REQUEST['alertId']!=''){ ?>
 <script>
 //var alertType = '<?php //echo $_REQUEST['alertId'];?>';
 //if(alertType!='communicationHistory') {
     $('<?php echo $_REQUEST['alertId'];?>').scrollIntoView(true);
 //}
 </script>
<?php } ?>

