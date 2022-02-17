<?php
$headerComponents = array(
        'js'=>array('homePage','CalendarPopup'),
	    'css'=>array('shiksha_common','online-styles'),
        'jsFooter'=>array('ana_common','user','common'),
        'title'	=>	'Application Form - Dashboard',
        'metaDescription' => '',
        'metaKeywords'	=>'',
        'product' => 'online',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
        );    
?>
<?php $this->load->view('common/header', $headerComponents); ?>
<div id="UserFormId" style="display:none;"><?php echo $form_list[0]['onlineFormId'] .'-'.$form_list[0]['userId'];?></div>
<div id="drftInstIdUser" style="display:none;"><?php echo $inst_id;?></div>
<div id= "userDraftNumber" style="display:none;"></div>
<div id= "userDraftPayeeBank" style="display:none;"></div>
<div id= "userDraftDate" style="display:none;"></div>
<div id= "userDraftDate" style="display:none;"></div>
<div id= "typeaction" style="display:none;">user</div>
<div id= "frmiduser" style="display:none;"><?php echo $inst_id;?></div>
<input type='hidden' name='onlineFormIdForm' id='onlineFormIdForm' value="<?php echo $form_details['0']['onlineFormId'];?>">
<div id="appsFormWrapper">
	<!--Starts: breadcrumb-->
    <div id="breadcrumb">
    	<ul>
        	<li><a href="<?php echo $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME'].'';?>" title="Application Forms">Application Forms</a></li>
        	<li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/index';?>" title="My Dashboard" >My Dashboard</a></li>
            <li class="last"><?php if(!empty($form_details['0']['instituteSpecId'])) {echo $form_details['0']['instituteSpecId'];} else {echo $form_details['0']['onlineFormId'];}?></li>
        </ul>
    </div>
    <!--Ends: breadcrumb-->
    <div id="contentWrapper">
    	<?php if(!empty($validateuser[0]['displayname'])):?><h2 class="welcome">Welcome <span><?php echo $display_name = isset($validateuser[0]['displayname'])? $validateuser[0]['displayname']:"";?></span></h2><?php endif;?>
        <div class="myForms myFormsMargin">
        	<table width="100%" border="1" cellpadding="12">
            	<thead>
                	<tr>
                        <td width="50" valign="top">Application <br />Number</td>
                        <td width="190" valign="top">Institute</td>
                        <td width="220" valign="top">Courses</td>
                        <td width="90" valign="top">Status</td>
                        <td width="50" valign="top" id='update_score'> Update<br />Scores</td>
                        <td width="110" valign="top">Action</td>
                    </tr>
                </thead>
                <?php 
                if(in_array($form_details['0']['status'],array('started','uncompleted','completed'))  && $form_is_expired[$form_details['0']['courseId']] == 'notexpired') {
                	$url = "/Online/OnlineForms/showOnlineForms/".$form_details['0']['courseId']."/0/0/editForm";
                } else {
                	$url ="javascript:void(0)";
                }
                ?>
                <tr class="evenRow">
                	<td width="50" valign="top"><a href="/Online/OnlineForms/displayForm/<?php echo $form_details['0']['courseId'];?>" target="blank"><?php if(!empty($form_details['0']['instituteSpecId'])) {echo $form_details['0']['instituteSpecId'];} else {echo $form_details['0']['onlineFormId'];}?></a></td>
                    <td width="190" valign="top"><?php echo $form_details['0']['institute_name'];?></td>
                    <td width="220" valign="top"><?php echo $form_details['0']['courseTitle'];  echo " - <i>for session year ".(intval($form_details['0']['sessionYear']))." - ".(intval($form_details['0']['sessionYear'])+1)."</i>";?></td>                    
                    <td width="90" valign="top"><?php if(in_array($form_details['0']['status'],array('started','uncompleted','completed'))) {echo "Saved";} elseif($form_details['0']['status'] == 'draft' || $form_details['0']['status'] == 'paid') {echo "Submitted";} elseif($form_details['0']['status'] == 'accepted') {echo "Selected";}else {echo ucfirst($form_details['0']['status']);}?></td>
		     <?php if(!in_array($form_details['0']['status'], array('started','uncompleted','completed'))){?>
					<?php if(in_array($form_details['0']['courseId'],$nonEditableForms)){?>
					<td valign="top" align="center"  style="color:#a4a4a4">Edit</td>
					<?php }else{?>
                    <td valign="top" align="center"><a href="/Online/OnlineForms/showOnlineForms/<?php echo $form_details['0']["courseId"]?>/1/4/updateScore" title="Edit">Edit</a></td>
					<?php } ?>
                    <?php }else{?>
                    <script type="text/javascript">
                    $('update_score').style.display = 'none';
                    </script>
                    <?php }?>
                    <td width="110" valign="top" class="greyText">
                    	<a  href="<?php echo $url;?>" <?php if(in_array($form_details['0']['status'],array('started','uncompleted','completed')) && $form_is_expired[$form_details['0']['courseId']] == 'expired'):?>onclick="alert('The form has expired and cannot be submitted.');return false;"<?php endif;?> <?php if(!in_array($form_details['0']['status'],array('started','uncompleted','completed'))):?> onclick="showEditMessageLayer('<?php echo $inst_addrs;?>','<?php echo $instituteLandline;?>','<?php echo $inst_email;?>')" <?php endif;?>  title="Edit">Edit</a> | 
                        <a target="blank" href="/Online/OnlineForms/displayForm/<?php echo $form_details['0']['courseId'].'?print=true';?>" title="Print">Print</a> | 
                        <a href="/Online/OnlineForms/displayForm/<?php echo $form_details['0']['courseId'];?>" target="blank" title="Preview">Preview</a>
                        </td>
                </tr>
                
            </table>
        </div>


          <?php if(!empty($payment_array) && is_array($payment_array) && (!in_array($form_details[0]['status'],array('started','uncompleted','completed','Payment Awaited')))):
        			$payment_array = $payment_array[0];
        ?>
        <div class="payDetails" id='online_payment_details_of_bug_fix'>
        	<h3>Payment Details</h3>
            <div class="payDetailsLeftCol">
             <?php if($payment_array['mode'] == 'Online' && $payment_array['status'] =='Success'):?>
                <ul>
                    <li><strong>Order Value : </strong> <span>INR <?php echo $payment_array['amount'];?></span></li>
                    <li><strong>Date : </strong> <span><?php echo $payment_array['date'];?></span></li>
                    <li><strong>Payment Mode : </strong> <span>Online</span></li>
                    <li><strong>Payment Status : </strong> <span>Payment Successful</span></li>
                    <li><strong>Transaction ID: </strong> <span><?php echo $payment_array['orderId'];?></span></li>
                    <li><strong>Status : </strong> <span>Transaction Successful</span></li>
                    <!-- <li><a href="javascript:void(0);" onclick="canCelPaymentRequestOnline('<?php if(!empty($form_details['0']['instituteSpecId'])) {echo $form_details['0']['instituteSpecId'];} else {echo $form_details['0']['onlineFormId'];}?>','<?php echo $form_details['0']['courseTitle'];?>','<?php echo $inst_addrs;?>','<?php echo $inst_email;?>');return false;" class="font10">Request to cancel payment </a></li> -->
                </ul>
                <?php endif;
                if($payment_array['mode'] == 'Offline' && $payment_array['status'] =='Started'):
                ?>
                 <ul>
                    <li><strong>Order Value : </strong> <span>INR <?php echo $payment_array['amount'];?></span></li>
                    <li><strong>Payment Mode : </strong> <span>Draft</span></li>
                    <li><strong><a href="javascript:void(0);" onclick="showDDOverlay('','','','',<?php echo $form_list[0]['userId']?>,<?php echo $form_list[0]['onlineFormId']?>,<?php echo $inst_id;?>); return false;">Update draft details</a></strong></li>
                </ul>
                <?php endif; 
                if($payment_array['mode'] == 'Offline' && ($payment_array['status'] =='Pending' || $payment_array['status'] =='Success')):?>
                 <ul>
                    <li><strong>Order Value : </strong> <span>INR <?php echo $payment_array['amount'];?></span></li>
		    <?php if( (!isset($payment_array['draftDate'])) || $payment_array['draftDate']=='' || $payment_array['draftDate']=='0000-00-00' || $payment_array['draftDate']=='0000-00-00 00:00:00'){ ?>
                    <li><strong>Date : </strong></li>
		    <?php }else{ ?>
                    <li><strong>Date : </strong> <span><?php echo date('d/m/Y',strtotime($payment_array['draftDate']));?></span></li>
		    <?php } ?>
                    <li><strong>Payment Mode : </strong> <span>Draft</span></li>
                    <li><strong>Draft No : </strong> <span><?php echo $payment_array['draftNumber'];?></span></li>
                    <li><strong>Payee Bank : </strong> <span><?php echo $payment_array['bankName'];?></span></li>
                    <li><strong>Transaction ID: </strong> <span><?php echo $payment_array['orderId'];?></span></li>
                    <li><strong>Status : </strong> <span>Transaction Successful</span></li>
                    <!--  <li><a href="javascript:void(0);" onclick="canCelPaymentRequestOnline('<?php if(!empty($form_details['0']['instituteSpecId'])) {echo $form_details['0']['instituteSpecId'];} else {echo $form_details['0']['onlineFormId'];}?>','<?php echo $form_details['0']['courseTitle'];?>','<?php echo $inst_addrs;?>','<?php echo $inst_email;?>'); return false;" class="font10">Request to cancel payment no man </a></li> -->
                </ul>
                <?php endif;?>
            </div>    
        </div>
        <?php endif;?>
        
         <div class="communicationHistory" id="communicationHistory">
        	<h3>Communication History</h3>
            <ul>
            	<?php $info =$form_list[0]['alertsMessageText'];?>
                <?php for($k=0;$k<count($info);$k++){
                $date = explode('-',$form_list[0]['alertsCreatedDate'][$k]);	
                $timeandday = explode(' ',$date[2]);
                $alertsCreatedDate = $timeandday[0].'/'.$date[1].'/'.$date[0].' '.$timeandday[1];
		if(!empty($form_details['0']['instituteSpecId'])) {$formId = $form_details['0']['instituteSpecId'];} else {$formId = $form_details['0']['onlineFormId'];}
                ?>
                <?php $course_id_link = $form_details[0][courseId]; $link1 = "<a href=\"/Online/OnlineForms/displayForm/$course_id_link\" target='blank'>here</a>";?>
                <?php $link = "<a href=\"javascript:void(0);\" onclick=\"showDDOverlay('','','','','".$userId."','".$formId."','".$data[institute_id]."'); return false;\">here.</a>";?>
                <?php if(($form_list[0]['alertsMsgId'][$k]=='31' || $form_list[0]['alertsMsgId'][$k]=='29') && $form_list[0]['alertStatus'][$k]=='Viewed'){ $link='here.';}?>
                                        <li><span id="<?php echo "alert".$k;?>"><?php echo date("F d",strtotime($form_list[0]['alertsCreatedDate'][$k]));?></span>:&nbsp;<?php echo preg_replace(array('/{DDhere}/','/<Fistname>/','/ <MiddleName>/','/<Lastname>/','/<Photograph_specs>/','/<communication address_mail>/','/<communication address_email>/','/<GD\/PI Time>/','/<GD\/PI Location>/','/<course_name>/','/<application number>/','/<Institute_name>/','/DD\/MM\/YY Min:Sec/','/<Documents_Specs>/','/<communication address>/','/<Transaction Number>/','/{ScoreDeatails}/','/GD\/PI/'),array($link,$profile_data['firstName'],$profile_data['middleName'],$profile_data['lastName'],$form_list[0]['imageSpecifications'],$form_list[0]['instituteAddress'],$form_list[0]['instituteEmailId'],date("Y/m/d",strtotime($form_list[0]['GDPIDate'])),$form_list[0]['GDPILocation'],$form_list[0]['course_name'],$formId,$form_list[0]['institute_name'],$alertsCreatedDate,$form_list[0]['documentsRequired'],$form_list[0]['instituteAddress'],$payment_array['orderId'],$link1,$gdPiName),$info[$k]); ?></li>
                <?php }?>
            </ul>
            <a href="/studentFormsDashBoard/StudentDashBoard/index" class="font14"> <span class="font16">&laquo;</span> My Application dashboard</a>
        </div>
    </div>
<div class="clearFix"></div>
</div>

<?php $this->load->view('common/footerNew'); ?>
<script>
var req = '<?php echo $_REQUEST['alertId'];?>';
if(req) {
$(req).scrollIntoView(true);
}
</script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("json2"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormEnterprise"); ?>"></script>
