<?php
if($taskType=='open'){
    $openTabUrl = 'javascript:void(0)';
    $closedTabUrl = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/closed/';
    $upcomingTabUrl = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/upcoming/';
}else if($taskType=='closed'){
    $openTabUrl = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/open/';
    $closedTabUrl = 'javascript:void(0)';
    $upcomingTabUrl = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/upcoming/';
}else{
    $openTabUrl = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/open/';
    $closedTabUrl = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/closed/';
    $upcomingTabUrl = 'javascript:void(0)';
}
?>
<div class="view-sorting clear-width">
        View:  <a href="<?php echo $openTabUrl;?>" <?php if($taskType=='open'){ ?>class="active" <?php } ?>>Open Tasks (<?php echo $tasks['totalCount']['openCount'];?>)</a> | <a <?php if($taskType=='closed'){ ?>class="active" <?php } ?> href="<?php echo $closedTabUrl;?>">Closed Tasks (<?php echo $tasks['totalCount']['closedCount'];?>)</a> | <a href="<?php echo $upcomingTabUrl;?>" <?php if($taskType=='upcoming'){ ?>class="active" <?php } ?>>Upcoming (<?php echo $tasks['totalCount']['upcomingCount'];?>)</a>
</div>
<?php
$errorMsg = array('open'=>'There are no open tasks.','closed'=>'There are no closed tasks.','upcoming'=>'There are no upcoming tasks.');
?>
<?php if(empty($tasks['taskInfo'][$taskType])){ ?> 
    <div class="no-result"><?php echo $errorMsg[$taskType];?></div>
<?php } else if($status=='false'){ ?>
    <div class="no-result">No Result Found.</div>
<?php }else{ ?>
<div class="tast-tab">
    <ul>
        <?php
        foreach($tasks['taskInfo'][$taskType]['taskList'] as $key=>$value){
        $url = SHIKSHA_HOME."/CA/CRDashboard/myTaskTab/".$taskType."/".$value['id'];    
        ?>
        <li <?php if($tasks['taskInfo'][$taskType]['defaultId']==$value['id']){ ?>class="active" <?php $url = "javascript:void(0);";} ?>><a href="<?php echo $url;?>"><?php echo $value['taskName'];?></a><i class="campus-sprite pointer"></i></li>
        <?php } ?>
    </ul>
</div>
<div class="task-content">
		<div class="task-title"><?php echo $tasks['taskInfo'][$taskType]['defaultName'];?></div>
		
		<div class="task-desc">
			<strong>Task Description: </strong>
		    <p class="task-summary"><?php echo $tasks['taskInfo'][$taskType]['defaultDescription'];?></p>
		    
		    <div class="task-duration">
			<?php echo date('d-m-Y',strtotime($tasks['taskInfo'][$taskType]['defaultStartDate']));?> <span>is the Start Date</span><br />
						<?php if($tasks['taskInfo'][$taskType]['defaultEndDate']!=''){ echo date('d-m-Y',strtotime($tasks['taskInfo'][$taskType]['defaultEndDate']));?> <span>is the Closing Date</span><?php } ?>
		    </div>
		    
		    <div class="task-rewards clear-width" id="rewardsSection">
			<p class="reward-title">Rewards</p>
			<ol class="reward-options">
                            <?php foreach($tasks['taskInfo'][$taskType]['defaultRewards'] as $key=>$value){ ?>
			    <li>
				<i class="campus-sprite reward-icn"></i>
				<p><strong>&#8377;<?php echo $value['prize_amount'];?></strong><br /><?php echo $value['prize_name'];?> </p>
			    </li>
			    <?php } ?>
			</ol>
			<?php if($taskType=='open'){ ?>
			<p class="reward-title clear-width" style="margin-top:15px">Submission</p>
			<div class="reward-form">
				<ul>
				<li>
				    <form action="/CA/CRDashboard/uploadMedia" accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="taskUploadForm" id="taskUploadForm">
					<div id="FileUpload" class="field-col">
					    <label>Upload Files <span> ( Maximum file size : 5 MB )</span></label>
					    <input type="file" style="width:195px; margin-bottom:5px;" onchange="$('FileField').value = $('BrowserHidden').value;" id="BrowserHidden" value="" name="userApplicationfile[]" size="24">
					    <input type="button" class="flRt" value="Upload" onclick="uploadMedia($('taskUploadForm'));" style="border: 1px solid #cbcbcb; background: #ededed; padding:5px 6px; font: normal 13px Arial; color: #333">
					    <div style="width: 198px; top:18px" id="BrowserVisible2">
						<input type="text" disabled="" value="" id="FileField" style="width:115px !important; border-radius:0; padding: 4px">
					    </div>
					</div>
					<div style="display:none;"><div style="*float:left" id="BrowserHidden_error" class="errorMsg"></div></div>
					<input type="hidden" name="userId" value="<?php echo $userId;?>" />
					<input type="hidden" name="taskId" value="<?php echo $tasks['taskInfo'][$taskType]['defaultId'];?>" />
				    </form>
				</li>
				<li style="margin:5px 0 10px 0; text-align:center; color:#666666; font-size:12px">(Or)</li>
				<li style="margin-bottom:0">
				    <form action="/CA/CRDashboard/addLink" accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="taskAddLinkForm" id="taskAddLinkForm">
				    <label>Post a link <span> ( eg. Google drive, Dropbox etc. )</span></label>
				    <div class="field-wrap" style="margin-bottom:5px;">
					<input type="text" class="txt-fld" id="postlink" name="postlink"/>
					<input type="button" value="Add" class="add-btn" onclick="if(validateURL($j('#postlink').val())){uploadMedia($('taskAddLinkForm'));}else{showError('postlink_error');return false;}"/>
					<input type="hidden" name="AL_userId" value="<?php echo $userId;?>" />
					<input type="hidden" name="AL_taskId" value="<?php echo $tasks['taskInfo'][$taskType]['defaultId'];?>" />
				    </div>
				    <div style="display:none;"><div style="*float:left" id="postlink_error" class="errorMsg"></div></div>
				    </form>
				</li>
			    </ul>
			</div>
			<?php } ?>
			<input type="hidden" id="uploadedFileCount" value="<?php echo count($uploadedTasks);?>"/>
			<div class="uploaded-files clear-width" id="addFile" <?php if(count($uploadedTasks)<=0){ ?> style="display: none;"<?php } ?>>
			<strong id="uploadedCountText">You have uploaded <?php echo count($uploadedTasks);?> <?php echo count($uploadedTasks)>1?'files':'file';?>:</strong>
			<?php foreach($uploadedTasks as $key=>$value){?>
			<p id="uploadedTask_<?php echo $value['id'];?>"><?php echo $value['name'];?> <?php if($taskType=='open'){ ?><a href="javascript:void(0);" onclick="removeUploadedTask('<?php echo $value['id'];?>')">Remove</a><?php } ?></p>
			<?php } ?>
			</div>
			<?php if($taskType=='open'){ ?><p><input type="submit" class="submit-btn" value="Make your submission" onclick="checkAtleastOneTaskSubmitted('<?php echo $tasks['taskInfo']['open']['defaultId'];?>','<?php echo $userId;?>');"/></p>
			<div style="display:none;"><div style="*float:left" id="submit_error" class="errorMsg"></div></div>
			<?php } ?>
		    </div>
		</div>
                
</div>
<?php } ?>