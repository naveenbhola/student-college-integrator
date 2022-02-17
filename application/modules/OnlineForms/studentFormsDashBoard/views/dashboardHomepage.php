<?php
$headerComponents = array(
        'js'=>array('homePage','onlinetooltip'),
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
<div id="appsFormWrapper">
	<!--Starts: breadcrumb-->
	<?php echo $breadCrumbHTML;?>
	<!--Ends: breadcrumb-->
    <div id="contentWrapper">
	
    <!--Starts: Left Column-->
    <div id="appsLeftCol">
    	<ul>
        	<li class="active wCurve">Home</li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/myProfile';?>" title="My profile">My Profile</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyDocuments/index';?>" title="My Documents">My Documents</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyForms/index';?>" title="My forms">My Forms</a></li>
        </ul>
    </div>
    <!--Ends: Left Column-->
    
    <div id="appsRightCol">
    	<?php if(!empty($validateuser[0]['firstname'])):?><h2 class="welcome">Welcome <span><?php echo $display_name = isset($validateuser[0]['firstname'])? $validateuser[0]['firstname']:"";?></span></h2><?php endif;?>
        <!--h3 class="confirmationMsg">Your application form(Amity2011MBA00723) to Amity has been successfully submitted. <a href="#">View Details</a></h3-->
       <?php if(!empty($form_list) && is_array($form_list)):?>
        <div class="myForms">
        	<h3 class="noBorder">My Application Forms</h3>
            <div class="clearFix"></div>
            <table width="100%" border="1" cellpadding="12">
            	<thead>
                	<tr>
                        <td width="80" valign="top">Application <br />Number <a onmouseout="hidetip();"  onmouseover="showTipOnline('This column lists all your forms with their application numbers. Always mention your application number and name while communicating with the Institute outside of Shiksha.',this);" href="javascript:void(0)" id="app100" class="qualifyHelp">&nbsp;</a>
                        </td>
                        <td width="150" valign="top">Institute</td>
                        <td width="180" valign="top">Courses</td>
                        <td width="100" valign="top">Status <a href="javascript:void(0)" onmouseout="hidetip();"onmouseover="showTipOnline('This column shows the current status of your application form.',this);" id="app200"class="qualifyHelp">&nbsp;</a></td>
                        <td id='update_score' width="85" valign="top">Update Scores <a href="javascript:void(0)" onmouseout="hidetip();"onmouseover="showTipOnline('Update/enter your qualifying examination by clicking on Edit link.',this);" id="app300"class="qualifyHelp">&nbsp;</a></td>
                        <td width="130" valign="top">Notifications <a href="javascript:void(0)" onmouseout="hidetip();"onmouseover="showTipOnline('This column shows the alerts and notification received towards your application form.',this);" id="app400"class="qualifyHelp">&nbsp;</a></td>
                    </tr>
                </thead>
                <?php $count_form=0; foreach($form_list as $form):
					global $onlineFormsDepartments;
					$form['status'] = str_ireplace("GD/PI",$onlineFormsDepartments[$form['departmentName']]['gdPiName'],$form['status']);
				?>
                <tr <?php if($count_form%2==0):?>class="evenRow" <?php endif;?>>
                	<td valign="top"><a href="/studentFormsDashBoard/StudentDashBoard/studentCommunicationHistoryDashboard?userId=<?php echo $userId;?>&formId=<?php echo $form['onlineFormId'];?>" title="View All"><?php if(!empty($form['instituteSpecId'])) {echo $form['instituteSpecId'];} else {echo $form['onlineFormId'];}?></a></td>
                    <td valign="top"><?php echo $form['institute_name']?></td>
                    <td valign="top"><?php echo $form['courseTitle'];  echo " - <i>for session year ".(intval($form['sessionYear']))." - ".(intval($form['sessionYear'])+1)."</i>";?></td>
                    <td valign="top"><?php if(in_array($form['status'],array('started','uncompleted','completed'))) {echo "Saved";} elseif($form['status'] == 'draft' || $form['status'] == 'paid') {echo "Submitted";} elseif($form['status'] == 'accepted' ) {echo "Selected";} else {echo ucfirst($form['status']);}?></td>
                    <?php if(!in_array($form['status'], array('started','uncompleted','completed'))){?>
                    <?php if(in_array($form['courseId'],$nonEditableForms)){?>
                    <td valign="top" align="center"  style="color:#a4a4a4">Edit</td>
                    <?php }else{?>
                    <td valign="top" align="center"><a href="/Online/OnlineForms/showOnlineForms/<?php echo $form["courseId"]?>/1/4/updateScore" title="Edit">Edit</a></td>
                    <?php } ?>
                    <?php }else{?>
                      <td valign="top" align="center" style="color:#a4a4a4">Edit</td>
                    <?php }?>

                   <td valign="top" align="center">
                   <div style="position:relative;z-index:0" id="<?php echo 'layerdiv'.$count_form;?>">
                   <a <?php if(count($form['alerts']['alertsMessageText'])>0):?>href="javascript:void(0);" <?php endif;?> <?php if(count($form['alerts']['alertsMessageText'])>0){?>onclick="setZindexForm('<?php echo 'layerdiv'.$count_form;?>');showNotification('<?php echo $form['onlineFormId'];?>','<?php echo $userId;?>','<?php echo $form['instituteId'];?>');"<?php }?> class="notifyAlerts" title=""><?php echo count($form['alerts']['alertsMessageText']);?></a>
                   <div <?php if($i>0 && ($i+1)%2==0){ ?>class="evenRow"<?php }?> style="display:none;" id="alerts_<?php echo $form['onlineFormId'];?>_<?php echo $userId;?>">

                     <!--Alert Cloud starts here-->
                    	<div class="alertCloud2">
                        	<div class="alertCloudPointer"></div>
                            <div class="notifyContent alertCloudContent">
                            	<h3>Notifications</h3>
                                <?php $totalNotifications = count($form['alerts']['alertsMessageText']);// print_r($info['alertsNotifications']);//print_r($onlineFormEnterpriseInfo['alerts']);for($i=0;$i<count($onlineFormEnterpriseInfo['alerts']);$i++){?>
                                <ul>
                                    <?php for($k=0;$k<$totalNotifications;$k++){?>
                                        <li><a href="/studentFormsDashBoard/StudentDashBoard/studentCommunicationHistoryDashboard?userId=<?php echo $userId;?>&formId=<?php echo $form['onlineFormId'];?>&alertId=<?php if($form['alerts']['alertsMsgId'][$k] == '31') {echo 'online_payment_details_of_bug_fix';} else {echo 'alert'.$k;}?>"><?php echo $form['alerts']['alertsNotifications'][$k]; ?></a></li>
                                         <?php if($k>1) break;?>
                                    <?php }?>
                                </ul>
                                <?php //} ?>
                                <p class="viewAllNotify"><a href="/studentFormsDashBoard/StudentDashBoard/studentCommunicationHistoryDashboard?userId=<?php echo $userId;?>&formId=<?php echo $form['onlineFormId'];?>&alertId=communicationHistory" title="View All">View All <span>&raquo;</span></a></p>
                            </div>
                        </div>

                        <!--Alert Cloud ends here-->
                </div>
                </div>
                   </td>
                </tr>
                
                <?php if($count_form == 4) { break;} else {$count_form++;} endforeach;?>
            </table>

            <?php if(count($form_list) > 5){ ?>
            <div style="margin-top:10px;"><a href="/studentFormsDashBoard/MyForms/index">View all Application Forms <span>&raquo;</span></a></div>
            <?php } ?>
        </div>
        <?php endif;?>
		
        <?php if(!empty($instituteList) && is_array($instituteList)):?>
        <h3>Apply online to these <?=$onlineFormsDepartments[$of_departmentName]['shortName'];?> colleges</h3>
        <div>
            <div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px;">&nbsp;<?php echo $paginationHTML;?></div>
        </div>

        <?php $this->load->view('studentFormsDashBoard/common_template_across');?>
        <?php endif;?>
    </div>
    </div>
<div class="clearFix"></div>
</div>
<?php $this->load->view('common/footerNew'); ?>
<script>
var OnlineForm = {};
OnlineForm.displayAdditionalInfoForInstitute = function (style,divId) {
	if($(divId)) {
		$(divId).style.display = style;
	}
}
function setZindexForm(id) {
	$(id).style.zIndex=1
}
</script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormEnterprise"); ?>"></script>
