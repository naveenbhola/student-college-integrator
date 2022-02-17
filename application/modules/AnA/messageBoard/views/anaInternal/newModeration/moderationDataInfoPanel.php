<style>
.table-width tr td.span-width span{display:block; width:400px; word-wrap:break-word;}
.table-width tr td.link-width a{display:inline-block; width:150px; word-wrap:break-word;}
</style>
<div class="pull-right"><a onclick="<?=(!$this->input->is_ajax_request())?'getLockedEntities();':'location.reload();'?>" href="javascript:void(0);"><?=(!$this->input->is_ajax_request())?'Show Locked Entities':'Show all'?></a></div>
<h4 id="cmpPageHeading"><?=($hasModeratorAccess==3)?((!$this->input->is_ajax_request())?'All pending entities':'Locked entities'):((!$this->input->is_ajax_request())?'Total entities':'All locked entities')?> <?='<span class="badge" id="finishModerationCount">'.$totalSearchRecords.'</span>'?></h4>
<table class="table-width table <?=($hasModeratorAccess==3)?'table-hover table-striped':''?> table-responsive">
	<tr class="<?=($hasModeratorAccess==1)?'bg-primary':''?>">
		<!--td>S No</td-->
		<th>Id</th>
		<th>Type</th>
		<th>Text</th>
		<th>User</th>
		<th>Creation Time</th>
		<th>Status</th>
		<?php
		if($hasModeratorAccess==1 || $hasModeratorAccess==2){
			echo '<th>Moderated by</th>';
		}
		?>
		<th>Action</th>
		<th>Auto-moderation Flag </th>
	</tr>
	<?php
	if(count($anaBasicInfo) == 0)
	{
	?>
	<tr>
		<td colspan="100">No entity found.</td>
	</tr>
	<?php
	}
	foreach ($anaBasicInfo as $key => $value) {
		if($value['fromOthers']=='user' && $value['parentId']==0) $type='Question';
		if($value['fromOthers']=='user' && $value['parentId']==$value['threadId'])$type='Answer';
		if($value['fromOthers']=='user' && $value['mainAnswerId']==$value['parentId'] && $value['mainAnswerId']>0) $type='Answer Comment';
		if($value['fromOthers']=='user' && $value['mainAnswerId']!=$value['parentId'] && $value['mainAnswerId']>0) $type='Comment Reply';
		if($value['fromOthers']=='discussion' && $value['mainAnswerId']==0) $type='Discussion';
		if($value['fromOthers']=='discussion' && $value['mainAnswerId']==$value['parentId']) $type='Discussion Comment';
		if($value['fromOthers']=='discussion' && $value['mainAnswerId']>0 && $value['mainAnswerId']!=$value['parentId'] ) $type='Discussion Reply';

		if($value['moderatorId'] == $validateuser[0]['userid'] && $value['moderationStatus'] == 'locked'):
			$strToBeDisplayed = 'Finish moderation';
			$stsToBePassed    = 'locked by me';
			$classToBePlaced  = 'bg-warning';
		elseif($value['moderationStatus'] == 'locked'):
			$strToBeDisplayed = 'Locked';
			$stsToBePassed    = 'locked';
			$classToBePlaced  = 'bg-warning';
		elseif($value['moderationStatus'] == 'completed'):
			$strToBeDisplayed = 'Moderated';
			$stsToBePassed    = 'pending';
			$classToBePlaced  = 'bg-success';
		else:
			$strToBeDisplayed = 'Start moderation';
			$stsToBePassed    = 'pending';
			$classToBePlaced  = '';
		endif;
	?>
	<tr id="tupleForEntity<?=$value['msgId']?>" class="<?=($hasModeratorAccess==1 || $hasModeratorAccess==2)?$classToBePlaced:''?> <?=($stsToBePassed=='locked by me')?'lockedEntityTuple':''?>" style="<?=($hasModeratorAccess==3 && $stsToBePassed=='locked by me')?'background-color:#DFF0D8':''?>">
		<!--td><?=($key+1)?></td-->
		<td><?=$value['msgId']?></td>
		<td><?=$type?></td>
		<td class="span-width">
			<?php
			$msgTxt = (strlen($value['msgTxt'])>150)?substr($value['msgTxt'],0,150).'... <a href="javascript:;" onclick="showMoreText(\'type4\', \''.$value['msgId'].'\')">read more</a>':$value['msgTxt'];
			?>
			<span id="readMoreQuestionTextPart<?=$value['msgId']?>"><?=$msgTxt?></span>
			<span id="CRAns_<?=$value['msgId']?>" style="display:none;"><?=$value['msgTxt']?></span>
			<?php
			$isShown = false; 
			if($stsToBePassed == 'locked by me'){
				//setcookie('cafeModerationStart', $value['msgId'], time()+(24*7*3600), '/', COOKIEDOMAIN);
				$isShown = true;
			}
			$subArr = array('value'=>$value, 'type'=>$type, 'isShown'=>$isShown);
			$this->load->view('anaInternal/newModeration/msgTxtWidget', $subArr);
			?>
		</td>
		<td class="link-width"><a href="/getUserProfile/<?=$value['displayname']?>" target='_blank'><?=$value['displayname']?></a><br/><?=$value['levelName']?></td>
		<td style="width:122px;"><?=date("j M, Y g:i A", strtotime($value['creationDate']))?></td>
		<td>
			<a href="javascript:void(0);" id="statusOf<?=$value['msgId']?>" class="statusCheck" onclick="startModeration('<?=$stsToBePassed?>','<?=$value['msgId']?>', '<?=$value['moderatorEmail']?>');"><?=$strToBeDisplayed?></a>
		</td>
		<?php
		if($hasModeratorAccess==1 || $hasModeratorAccess==2){
			if($strToBeDisplayed=='Moderated' || $strToBeDisplayed=='Locked')
				echo '<td>'.(($value['moderatorId']==$validateuser[0]['userid'])?'You':$value['moderatorEmail']).'</td>';
			else if($strToBeDisplayed == 'Finish moderation')
				echo '<td>You</td>';
			else
				echo '<td>-</td>';
		}
		?>
		<td>
		<?php if(($value['mainAnswerId']==0 && $value['fromOthers']=='user') || ($value['mainAnswerId']==$value['parentId'] && $value['fromOthers']=='discussion')){

                                $title = seo_url_lowercase($value['questionTxt'],"-",'','110');
                                if($value['fromOthers']=='discussion'){
                                        $entityType = 'discussion';
                                        $shareUrl = SHIKSHA_ASK_HOME_URL."/".$title."-dscns-".$value['threadId'].'?referenceEntityId='.$value['msgId'];
                                }else{
                                        $entityType = 'question';
                                        $shareUrl = SHIKSHA_ASK_HOME_URL."/".$title."-qna-".$value['threadId'].'?referenceEntityId='.$value['msgId'];
                                }
	
			    //$shareUrl = getSeoUrl($value['threadId'], $entityType, $value['questionTxt']).'?referenceEntityId='.$value['msgId'];
			?>
				<a href='<?=$shareUrl?>' target='_blank' class="moderatorControl moderatorControl<?=$value['msgId']?>" style='<?=$isShown?'display:block;':'display:none;'?>'>View Entity, </a>
			<?php }else{?>
				<a href='/getTopicDetail/<?=$value['threadId']?>/' class="moderatorControl moderatorControl<?=$value['msgId']?>" target='_blank' style='<?=$isShown?'display:block;':'display:none;'?>'>View Entity, </a>
			<?php }?>
				<span id='deleteLink<?=$value['msgId']?>' style="<?=!$isShown?'display:none;':''?>" class="moderatorControl moderatorControl<?=$value['msgId']?>"><a href='javascript:void(0);' onClick='deleteCommentFromCMS("<?=$value['msgId']?>","<?=$value['threadId']?>","<?=$value['userId']?>","<?=$type?>","<?=$value['fromOthers']?>");'>Delete</a></span><?php if($type=='Question' || $type=='Discussion'){ ?><a href="javascript:void(0);" class="moderatorControl moderatorControl<?=$value['msgId']?>" onClick="openPage('<?=$value['threadId']?>');" style="<?=!$isShown?'display:none;':''?>">, Edit <?=$type?></a>
				<?php } ?>
				<?php if($value['mainAnswerId'] == 0 && $value['parentId'] == $value['threadId']) { 
					if(array_key_exists($value['msgId'], $checkIfEditRequested)){
						if($checkIfEditRequested[$value['msgId']] == 'yes'){
							$textToShow = 'Edit Done';
							$cssClass = 'not-active';
						}else if($checkIfEditRequested[$value['msgId']] == 'no'){
							$textToShow = 'Request Sent';
							$cssClass = 'not-active';
						}
					}else{
						$textToShow = 'Request to Edit';
						$cssClass = '';
						} 
					?>
						<a href="javascript:void(0);" data-toggle="modal" data-target="#showRequestToEditOverlay" onclick="showRequestToEditOverlay('<?=$value['msgId']?>', '<?=$value['userId']?>', '<?=$value['threadId']?>')" id='reqToEditAnchor_<?php echo $value['msgId']; ?>' class = '<?=$cssClass?>'><span id='reqToEditText_<?php echo $value['msgId']; ?>'><?=$textToShow?></span></a>
				<?php  }?>
		</td>
		<td>
		<?php if($value['autoModeratedFlag'] == 1 || $value['autoModeratedFlag'] == 2 || $value['autoModeratedFlag'] == 3 ){
				
			echo'YES';
		?>
			<br/>
			  <a href="javascript:void(0);" data-toggle="modal" data-target="#autoModerateViewChange" onclick="viewAutomoderationChange('<?=$value['msgId']?>','<?=$value['autoModeratedFlag'];?>')">View Change</a>

		<?php }else{
			echo 'No';
		}?>
			<br><br>
			<a href="javascript:void(0);" data-toggle="modal" data-target="#autoModerateViewChange" onclick="viewAutomoderationChange('<?=$value['msgId']?>','<?=$value['autoModeratedFlag'];?>','yes')">Check AutoModeration</a>
		</td>
		
	</tr>
	<?php
	}
	?>
</table>
<hr/>

<!-- Automoderation Change Layer  -->
	<div class="container">
	  <div class="modal fade" id="autoModerateViewChange" role="dialog">
	    <div class="modal-dialog">
	    
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">View Change</h4>
	        </div>
	        <div class="modal-body">
	        <div id="anaVCldr" style="margin-left: 42%;display: none;">Please wait...</div>
	        <div id="msgTitleChange" style="display:none;">
	          <p><b>Original Title:</b> <span id="autoOriginalTitle"></span></p>
	          <p><b>Difference:</b><span id="autoModerateTitleDiff"></span></p>
	        </div>
	        <div id="msgDescriptionChange" style="display:none;">
	          <p><b>Original Description:</b> <span id="autoOriginalDescription"></span></p>
	          <p><b>Difference:</b><span id="autoModerateDescDiff"></span></p>
	        </div>
	        </div>
	        
	      </div>
	      
	    </div>
	  </div>	  
	</div>


<div id="rabgLayer" class="tags-layer" style="display:none"></div>
<div id="abuse-layer" class="posting-layer" style="width:680px;display:none;">
          <div class="tags-head">Please select one or more reason to request edit <a id="cls-add-tags" class="cls-head" href="javascript:void(0);" onclick="closeReportAbuseLayer()"></a></div>
           <div class="tag-body">
               <ul class="report-ul">
               	  <?php foreach ($editRequestReasons as $key => $value) { ?>
	                  <li class="">
	                      <div class="report-col">
	                         <input type="checkbox" name="report" id="<?=$key?>" class="reportCheckbox">
	                         <label for="<?=$key?>"><?=$value?></label>
	                      </div>
	                  </li>
	              <?php } ?>
                   
                  <li class="">
                     <div class="report-col reportAbuseTextArea">
                          <div class="write-col">
                            <textarea class="write-txt" name="write-cmnt" placeholder="Comment(Optional)" id="requestEditReasonText" maxlength="200"></textarea>  
                            <p class="count-numbr" ></p> 
                         
                          </div>
                     </div>
                  </li>
               </ul>
               <div class="btns-col">
				    <span class="right-box">
				       
				        <a id="submitReason" class="ana-btns a-btn" href="javascript:void(0)" style="cursor:default" onclick="submitReasons()" answerIdForSubmit="" userIdForSubmit="" questionIdForSubmit= "">Submit</a>
				    </span>
            		<p class="clr"></p>
        		</div>
            </div>
</div> 
	

<!-- Pagination HTML start -->
<nav>
  <ul class="pager">
  	<?php if(isset($_POST['start']) && $_POST['start']!=0 && $_POST['start']>=50){ ?>
    <li class="previous"><a href="javascript:void(0);" onClick="prev();"><span aria-hidden="true">&larr;</span> Previous</a></li>
    <?php } ?>
    <?php if(count($anaBasicInfo)==50 && $totalSearchRecords > 50){ ?>
    <li class="next"><a href="javascript:void(0);" onClick="next();">Next <span aria-hidden="true">&rarr;</span></a></li>
    <?php } ?>
  </ul>
</nav>
<!-- Pagination HTML end -->
