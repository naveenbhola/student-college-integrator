<?php
$headerComponents = array(
        'js'=>array('homePage'),
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
        	<li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/index';?>" title="My forms">Home</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/myProfile';?>" title="My profile">My Profile</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyDocuments/index';?>" title="My Documents">My Documents</a></li>
            <li class="active wCurve">My Forms</li>
        </ul>
    </div>
    <!--Ends: Left Column-->
    
    <div id="appsRightCol">
    	<?php if(!empty($validateuser[0]['displayname'])):?><h2 class="welcome">Welcome <span><?php echo $display_name = isset($validateuser[0]['displayname'])? $validateuser[0]['displayname']:"";?></span></h2><?php endif;?>
        <?php if(!empty($form_list) && is_array($form_list)):?>
        <div class="myForms">
        	<h3 class="noBorder">My Forms</h3>
            <div class="clearFix"></div>
            <table width="100%" border="1" cellpadding="12">
            	<thead>
                	<tr>
                        <td width="78">Application <br />Number</td>
                        <td width="120">Institute</td>
                        <td width="140">Courses</td>
                        <td width="80">Status</td>
                        <td width="50" id="update_score">Update Scores</td>
                        <td width="130">Action</td>
                        <td width="42">Alerts</td>
                    </tr>
                </thead>
                <?php $count_form=0;foreach($form_list as $form):?>
                <?php
				global $onlineFormsDepartments;
				$form['status'] = str_ireplace("GD/PI",$onlineFormsDepartments[$form['departmentName']]['gdPiName'],$form['status']);
                if(in_array($form['status'],array('started','uncompleted','completed')) && $form_is_expired[$form['courseId']] == 'notexpired') {
                	$url = "/Online/OnlineForms/showOnlineForms/".$form['courseId']."/0/0/editForm";
                } else {
                	$url ="javascript:void(0)";
                }
                ?>
                <tr <?php if($count_form%2==0):?>class="evenRow" <?php endif;?>>
                	<td valign="top"><a  href="/studentFormsDashBoard/StudentDashBoard/studentCommunicationHistoryDashboard?userId=<?php echo $userId;?>&formId=<?php echo $form['onlineFormId'];?>"><?php if(!empty($form['instituteSpecId'])) {echo $form['instituteSpecId'];} else {echo $form['onlineFormId'];}?></a></td>
                    <td valign="top"><?php echo $form['institute_name']?></td>
                    <td valign="top"><?php echo $form['courseTitle'];  echo " - <i>for session year ".(intval($form['sessionYear']))." - ".(intval($form['sessionYear'])+1)."</i>";?></td>                    
                    <td valign="top"><?php if(in_array($form['status'],array('started','uncompleted','completed'))) {echo "Saved";} elseif($form['status'] == 'draft' || $form['status'] == 'paid') {echo "Submitted";} elseif($form['status'] == 'accepted' ) {echo "Selected";} else {echo ucfirst($form['status']);}?></td>
                    <?php if(!in_array($form['status'], array('started','uncompleted','completed'))){?>
		    <?php if(in_array($form['courseId'],array('126875','128902','9190','150962','863','136364','121900','129170','125555','92358','155474','111737','157230','154002'))){?>
		    <td valign="top" align="center"  style="color:#a4a4a4">Edit</td>
		    <?php }else{?>
                    <td valign="top" align="center"><a href="/Online/OnlineForms/showOnlineForms/<?php echo $form["courseId"]?>/1/4/updateScore" title="Edit">Edit</a></td>		   <?php } ?>
                   <?php }else{?>
                    <td valign="top" align="center" style="color:#a4a4a4">Edit</td>
                    <?php }?>
                    <td valign="top" class="greyText"><a href="<?php echo $url;?>" <?php if(in_array($form['status'],array('started','uncompleted','completed')) && $form_is_expired[$form['courseId']] == 'expired'):?>onclick="alert('The form has expired and cannot be submitted.');return false;"<?php endif;?> <?php if(!in_array($form['status'],array('started','uncompleted','completed'))):?> onclick="showEditMessageLayer('<?php echo $form['instituteAddress'];?>','<?php echo $form['instituteLandline'];?>','<?php echo $form['instituteEmailId'];?>')" <?php endif;?>  title="Edit">Edit</a> | <a target="blank" href="/Online/OnlineForms/displayForm/<?php echo $form['courseId'].'?print=true';?>" title="Print">Print</a> | <a href="/Online/OnlineForms/displayForm/<?php echo $form['courseId'];?>" target="blank" title="Preview">Preview</a></td>
                   <td align="center" valign="top">
                   <div style="position:relative;z-index:0" id="<?php echo 'layerdiv'.$count_form;?>">
                   <a <?php if(count($form['alerts']['alertsMessageText'])>0):?>href="javascript:void(0);" <?php endif;?> <?php if(count($form['alerts']['alertsMessageText'])>0){?>onclick="setZindexForm('<?php echo 'layerdiv'.$count_form;?>');showNotification('<?php echo $form['onlineFormId'];?>','<?php echo $userId;?>','<?php echo $form['instituteId'];?>');"<?php }?> class="notifyAlerts" title=""><?php echo count($form['alerts']['alertsMessageText']);?></a>
                	<div <?php if($i>0 && ($i+1)%2==0){ ?>class="evenRow"<?php }?> style="display:none;" id="alerts_<?php echo $form['onlineFormId'];?>_<?php echo $userId;?>">

                     <!--Alert Cloud starts here-->
                    	<div class="alertCloud">
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
                <?php $count_form++;endforeach;?>
            </table>
        </div>
        <?php else :?>
        <h3 class="errorMsg">You have not started filling up any forms. Choose an application form of your choice from your <a href="/studentFormsDashBoard/StudentDashBoard/index">Dashboard</a>.</h3>
        <?php endif;?>
    </div>
    </div>
    <div class="clearFix"></div>
</div>

<?php $this->load->view('common/footerNew'); ?> 
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormEnterprise"); ?>"></script>
<script>
function setZindexForm(id) {
	$(id).style.zIndex=1
}
</script>
