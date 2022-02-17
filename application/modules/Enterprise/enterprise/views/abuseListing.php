<?php
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';$Live='';$Removed='';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'Pending': $Pending = "selected";
	      break;
  case 'Moderated': $Moderated = "selected";
	      break;
  case 'Archived': $Archived = "selected";
	      break;
  }
  $reportsPerPage = isset($countOffset)?$countOffset:5;
  $number5 = '';$number10='';$number20='';$number30='';
  switch($reportsPerPage){
  case 5: $number5 = "selected";
	      break;
  case 10: $number10 = "selected";
	      break;
  case 20: $number20 = "selected";
	      break;
  case 30: $number30 = "selected";
	      break;
  }

  $reportedByCheck = 'checked'; $reportedForCheck = '';
  if(isset($reported) && $reported!=''){
      switch($reported){
	  case 'reportedBy' : $reportedByCheck = 'checked';
			      break;
	  case 'reportedFor' : $reportedForCheck = 'checked';
			      break;
      }
  }
?>
<script>
var parameterObj = eval(<?php echo $parameterObj; ?>);
</script>
<input type="hidden" id="pageKeyForReportAbuse" value="ASK_USERQNA_RIGHTPANEL_REPORTABUSE" />
<input type="hidden" id="pageKeyForSubmitComment" value="ASK_USERQNA_RIGHTPANEL_SUBMITANSWER" />
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="qaStartFrom" value="<?php echo $startFrom;?>"/>
	<input type="hidden" autocomplete="off" id="qaCountOffset" value="<?php echo $countOffset;?>"/>
	<input type="hidden" autocomplete="off" id="abuseFilter" value=""/>
	<input type="hidden" autocomplete="off" id="methodName" value="insertUserAbuse"/>
	<input type="hidden" autocomplete="off" id="moduleNameSel" value="<?php echo $moduleName;?>"/>
<!--Pagination Related hidden fields Ends  -->


	<div style="float:left; width:100%;">
		<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><!--<b class="b3"></b><b class="b4"></b>-->
				<div class="lineSpace_10">&nbsp;</div>
				<div>
						<table width="99%" cellspacing="0" cellpadding="0" border="0" height="25">
							<tr>
								<td width="20%">
									<table cellspacing="0" cellpadding="0" border="0" height="25" width="90%">
									<tr><td >&nbsp;</td>
										  <td style="line-height:23px;display:block;"><span id="pagLabTop">Filter by Status</span>
											<select name="filter" id="filter" class="normaltxt_11p_blk_arial" onChange="filterAbuseReport();">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="Pending" <?php echo $Pending;?>>Pending</option>
												<option value="Moderated" <?php echo $Moderated;?>>Moderated</option>
												<option value="Archived" <?php echo $Archived;?>>Archived</option>
											</select>					
										</td>
									</tr>
									</table>
								</td>
								<td width="60%">
									<div style="vertical-align: middle;"><table cellspacing="0" cellpadding="0" border="0" height="25" valign="top">
									<tr valign="middle">
										<td width="30%">
										<?php if(!empty($abuseReasonsForFilter)){?>
										<span id="pagLabTop">Filter by Reason</span>
											<select name="filter" id="filterByReason" class="normaltxt_11p_blk_arial" onChange="filterAbuseReport();">
												<option value="All">All</option>
												<?php foreach ($abuseReasonsForFilter as $key => $value) {?>
													<option value="<?=$value['ID'];?>"<?php if($value['ID'] == $filterAbuseReasonSelected){?>selected="selected"<?php }?>><?=$value['Title'];?></option>
												<?php }?>
											</select>	
											<?php } ?>				
										</td>

										<td valign="middle">
											<span><input type="radio" name="reported" value="reportedBy" onClick="javascript: radioVal = 'reportedBy';" <?php echo $reportedByCheck;?>/></span>
										</td>
										<td valign="middle">
											Reported by&nbsp;
										</td>
										<td valign="middle">
											<span><input type="radio" name="reported" value="reportedFor" onClick="javascript: radioVal = 'reportedFor';" <?php echo $reportedForCheck;?>/></span>
										</td>
										<td valign="middle">
											Reported for&nbsp;
										</td>
										<td valign="top">
											<select name="filterLevel" id="filterLevel" class="normaltxt_11p_blk_arial">
											<option value="All">All</option>
											<?php foreach ($expertLevelsForFilter as $key => $value) {?>
												<option value="<?=$value['levelName'];?>"<?php if($value['levelName'] == $userLevelFieldData){?>selected="selected"<?php }?>><?=$value['levelName'];?></option>
											<?php }?>
											</select>&nbsp;
										</td>
										<td valign="top">
											<span><input type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" id="userNameField" default="User Name" value="<?php if(isset($userNameFieldData) && $userNameFieldData!='') echo $userNameFieldData; else echo "User Name";?>" class="" style="color: rgb(173, 166, 173);width:100px;height:14px;">&nbsp;</span>
										</td>
										<td valign="top">
											<input type="button" value="Search" class="fontSize_10p" onClick="javascript: searchAbuseReport('1');"/>
										</td>
									</tr>
									</table></div>
								</td>
								<td align="right" width="18%">
									<table cellspacing="0" cellpadding="0" border="0" height="25">
									<tr>
										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
										<td style="line-height:23px;display:none;"></span>
											<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'qaStartFrom','qaCountOffset');">
												<option value="5" <?php echo $number5;?>>5</option>
												<option value="10" <?php echo $number10;?>>10</option>
												<option value="20" <?php echo $number20;?>>20</option>
												<option value="30" <?php echo $number30;?>>30</option>
											</select>					
										</td>
									</tr>
									</table>
								</td>
							</tr>
						</table>
				</div>
				<!--className boxcontent_lgraynoBG-->
				<?php if($selectedFilter=='Pending'){ ?><div style="margin-top:5px;" class="txt_align_c showMessages">You have <?php echo $totalAbuse;?> abuse reports awaiting moderation.</div><?php } ?>
				<div class="" id="mainContainer">
						<div class="lineSpace_10">&nbsp;</div>
						<!-- Abuse report Start -->
						<div id="userAnswers">
						      <?php 
							if(count($userAbuse) <= 0){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No abuse report is available.</div>
							<?php }

						      ?>
						      </div>
						      <?php if(count($userAbuse) > 0){ ?>
						      <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
						      <tr>
							      <td width="7%" align="left">&nbsp;&nbsp;<a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(1);">Select All</a></td>
							      <td width="5%" align="left"><a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(0);">None</a></td>
							      <td width="8%"><input type="button" value="Accept With Penalty" class="fontSize_10p" onClick="javascript: operateAbuseAll('remove',1);"/></td>
							      <td width="16%"><input type="button" value="Accept Without Penalty" class="fontSize_10p" onClick="javascript: operateAbuseAll('remove',0);"/></td>
							      <td width="12%"><input type="button" value="Reject with Penalty" class="fontSize_10p" onClick="javascript: operateAbuseAll('reject',1);"/></td>
							      <td width="2%"></td>
							      <td width="21%"><input type="button" value="Reject without Penalty" class="fontSize_10p" onClick="javascript: operateAbuseAll('reject',0);"/></td>
							      <td width="16%"><input type="button" value="Accept & Archive" class="fontSize_10p" onClick="javascript: operateAbuseAll('archive');"/></td>
							      <td width="43%" align="left"><div id='loadingImage' style="display:none;"><img src='/public/images/working.gif' border=0/></div></td>
						      </tr>
						      </table>
						      <?php } ?>
						      <?php foreach($userAbuse as $temp){ 
							      if(is_array($temp['abuse'])){ 
								if($moduleName == 'Events'){
								    $temp['abuse']['firstname'] = $temp['event']['firstname'];
								    $temp['abuse']['displayname'] = $temp['event']['displayname'];
								    $temp['abuse']['ownerLevel'] = $temp['event']['ownerLevel'];
								    $temp['abuse']['msgCreationDate'] = $temp['event']['msgCreationDate'];
								    $temp['abuse']['msgTxt'] = $temp['event']['msgTxt'];
								    $temp['abuse']['ownerId'] = $temp['event']['ownerId'];
								}
							      //if(!(is_int(strpos($threadIdList,$temp['abuse']['entityId']))))
							      //{
								//$threadIdList .= $temp['abuse']['entityId'].",";
							      $statusOrig = $temp['abuse']['status'];
							      switch($temp['abuse']['status']){
							      case 'RemovedByA': 
									  $temp['abuse']['status'] = "Removed by Admin With Penalty";
									  break;
								  case 'RemovedByAWithoutPenalty': 
									  $temp['abuse']['status'] = "Removed by Admin Without Penalty";
									  break;
							      case 'Republishedwp': 
									  $temp['abuse']['status'] = "Republished with Penalty By Admin";
									  break;
							      case 'Republishedwop': 
									  $temp['abuse']['status'] = "Republished without Penalty By Admin";
									  break;
							      case 'Rejectwp': 
									  $temp['abuse']['status'] = "Rejected with Penalty";
									  break;
							      case 'Rejectwop': 
									  $temp['abuse']['status'] = "Rejected without Penalty";
									  break;
							      }
							      if($temp['abuse']['entityType'] == "Blog Comment" || $temp['abuse']['entityType'] == "Event Comment" || $temp['abuse']['entityType'] == "announcement Comment" || $temp['abuse']['entityType'] == "discussion Comment" || $temp['abuse']['entityType'] == "review Comment" || $temp['abuse']['entityType'] == "eventAnA Comment")
								  $entityTypeDisplay = "Comment";
							      if($temp['abuse']['entityType'] == "announcement Reply" || $temp['abuse']['entityType'] == "discussion Reply" || $temp['abuse']['entityType'] == "review Reply" || $temp['abuse']['entityType'] == "eventAnA Reply")
								  $entityTypeDisplay = "Reply";
							      else if($temp['abuse']['entityType'] == "eventAnA")
								  $entityTypeDisplay = "Event";
							      else if($temp['abuse']['entityType'] == "review")
								  $entityTypeDisplay = "Review";
							      else if($temp['abuse']['entityType'] == "discussion")
								  $entityTypeDisplay = "Discussion";
							      else if($temp['abuse']['entityType'] == "announcement")
								  $entityTypeDisplay = "Announcement";
							      else
								  $entityTypeDisplay = $temp['abuse']['entityType'];
						      ?>
						      
						      
						      <div class="lineSpace_10">&nbsp;</div>
						      <div class="" id="reportAbuseDiv<?php echo $temp['abuse']['entityId'];?>">
								      <input type="hidden" id="entityId<?php echo $temp['abuse']['entityId'];?>" value="<?php echo $temp['abuse']['entityId'];?>">
								      <input type="hidden" id="ownerId<?php echo $temp['abuse']['entityId'];?>" value="<?php echo $temp['abuse']['ownerId'];?>">
								      <input type="hidden" id="entityType<?php echo $temp['abuse']['entityId'];?>" value="<?php echo $temp['abuse']['entityType'];?>">
								      <input type="hidden" id="threadId<?php echo $temp['abuse']['entityId'];?>" value="<?php echo $temp['abuse']['threadId'];?>">
								      <input type="hidden" id="status<?php echo $temp['abuse']['entityId'];?>" value="<?php echo $statusOrig;?>">
								      
								      <div class="">
										  <div style="background:#dddddd;">
										    <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
										    <tr>
											    <td width="5%" align="left"><input type="checkbox" name="raCheckbox" id="raCheckbox<?php echo $temp['abuse']['entityId']; ?>"/></td>
											    <td width="20%" align="left">&nbsp;&nbsp;<b><?php echo $entityTypeDisplay." ID:".$temp['abuse']['entityId'];?></b></td>
											    <td width="50%">by:&nbsp;<?php echo $temp['abuse']['firstname']."(".$temp['abuse']['displayname'].")".",".$temp['abuse']['ownerLevel']." on ".$temp['abuse']['msgCreationDate'];?></td>
											    <td align="right"><b>Status:&nbsp;<?php echo $temp['abuse']['status'];?></b>&nbsp;&nbsp;</td>
										    </tr>
										    </table>

										  </div>
										  <div style="padding:0 10px 0 10px">
										      <div class="lineSpace_10">&nbsp;</div>
										      <div>
											      <a href="javascript:void(0);" onClick="window.open('<?php echo $temp['abuse']['url']; ?>');" class="fontSize_10p"><?php echo isset($temp['abuse']['msgTxt'])?nl2br(insertWbr($temp['abuse']['msgTxt'],30)):''; ?></a>
											      <?php $this->load->view('enterprise/showAnswerSuggestionsEnt',array('answerId'=>$temp['abuse']['entityId'],'answerSuggestions'=>$answerSuggestions)); ?>
										      </div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div style="line-height:22px" class="fontSize_10p">
											<table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
											<tr>
												<td align="left" width="<?php if($temp['abuse']['status']=="Removed" || $temp['abuse']['status']=="Removed by Admin With Penalty" || $temp['abuse']['status']=="Removed by Admin Without Penalty") echo "54%"; else echo "64%"; ?>"><img id="<?php echo $temp['abuse']['entityId']; ?>Toggler" src="/public/images/plusSign.gif" onclick="toggleAbuseDetails(<?php echo $temp['abuse']['entityId']; ?>)" align="absmiddle" style="cursor:pointer;margin-top:-8px"/>&nbsp; &nbsp;<a href="javascript:void(0);" class="fontSize_10p blackFont bld" onclick="toggleAbuseDetails(<?php echo $temp['abuse']['entityId']; ?>)">View Report Abuse Details</a> </td>
												<?php if($temp['abuse']['status']=="Live"){ ?>
												  <td align="right"><input type="button" value="Accept With Penalty" class="fontSize_10p" onClick="javascript: removeAbuse('<?php echo $temp['abuse']['entityId'];?>','<?php echo $temp['abuse']['ownerId'];?>','<?php echo $temp['abuse']['entityType'];?>','<?php echo $temp['abuse']['threadId'];?>',1);"/></td>
												  <td align="right"><input type="button" value="Accept Without Penalty" class="fontSize_10p" onClick="javascript: removeAbuse('<?php echo $temp['abuse']['entityId'];?>','<?php echo $temp['abuse']['ownerId'];?>','<?php echo $temp['abuse']['entityType'];?>','<?php echo $temp['abuse']['threadId'];?>',0);"/></td>
												  <td align="right"><input type="button" value="Reject with Penalty" class="fontSize_10p" onClick="javascript: rejectAbuse('<?php echo $temp['abuse']['entityId'];?>','<?php echo $temp['abuse']['ownerId'];?>','<?php echo $temp['abuse']['entityType'];?>','<?php echo $temp['abuse']['threadId'];?>',1);"/></td>
												  <td align="right"><input type="button" value="Reject without Penalty" class="fontSize_10p" onClick="javascript: rejectAbuse('<?php echo $temp['abuse']['entityId'];?>','<?php echo $temp['abuse']['ownerId'];?>','<?php echo $temp['abuse']['entityType'];?>','<?php echo $temp['abuse']['threadId'];?>',0);"/></td>
												<?php }else if($temp['abuse']['status']=="Removed" || $temp['abuse']['status']=="Removed by Admin With Penalty" || $temp['abuse']['status']=="Removed by Admin Without Penalty"){ ?>
												  <td align="right"><input type="button" value="Republish with Penalty" class="fontSize_10p" onClick="javascript: republishAbuse('<?php echo $temp['abuse']['entityId'];?>','<?php echo $temp['abuse']['ownerId'];?>','<?php echo $temp['abuse']['entityType'];?>','<?php echo $temp['abuse']['threadId'];?>',1);"/>&nbsp;</td>
												  <td align="right"><input type="button" value="Republish without Penalty" class="fontSize_10p" onClick="javascript: republishAbuse('<?php echo $temp['abuse']['entityId'];?>','<?php echo $temp['abuse']['ownerId'];?>','<?php echo $temp['abuse']['entityType'];?>','<?php echo $temp['abuse']['threadId'];?>',0);"/></td>
												  <td align="right"><input type="button" value="Accept & Archive" class="fontSize_10p" onClick="javascript: archiveAbuse('<?php echo $temp['abuse']['entityId'];?>','<?php echo $temp['abuse']['ownerId'];?>','<?php echo $temp['abuse']['entityType'];?>','<?php echo $temp['abuse']['threadId'];?>');"/></td>
												<?php }?>
											</tr>
											</table>
										      </div>
										      <div class="lineSpace_10">&nbsp;</div>	
										      <div id="<?php echo $temp['abuse']['entityId'];?>" style="display:none;padding:0 10px 0 10px;">
											<table cellspacing="0" cellpadding="1" border="0" height="40" width="75%" style="border: 1px solid #E6E7E9">
											    <tr class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
											      <td width="9%" align="center" style="border-right: 1px solid #E6E7E9;">User ID</td>
											      <td width="17%" align="center" style="border-right: 1px solid #E6E7E9;">User Level</td>
											      <td width="7%" align="center" style="border-right: 1px solid #E6E7E9;">Points</td>
											      <td width="35%" style="border-right: 1px solid #E6E7E9;">Reason</td>
											      <td width="10%" style="border-right: 1px solid #E6E7E9;">Date</td>
											      <td width="28%" >Status</td>
											    </tr>
											<?php  foreach($abuseDetails as $report){
											if($report['entityId'] == $temp['abuse']['entityId']){ 
											      switch($report['status']){
											      case 'RemovedByA': 
													  $report['status'] = "Removed by Admin With Penalty";
													  break;
												  case 'RemovedByAWithoutPenalty': 
									  				  $report['status'] = "Removed by Admin Without Penalty";
									  				  break;
											      case 'Republishedwp': 
													  $report['status'] = "Republished with Penalty By Admin";
													  break;
											      case 'Republishedwop': 
													  $report['status'] = "Republished without Penalty By Admin";
													  break;
												  case 'Rejectwop': 
													  $report['status'] = "Rejected without Penalty";
													  break;
												  case 'Rejectwop': 
													  $report['status'] = "Rejected without Penalty";
													  break;
											      }
											  ?>
											    <tr height="30" style="border-style: dashed; border-width: medium;border-color: lime;">
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $report['userId'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $report['userLevel'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $report['pointsAdded'];?></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php echo $report['abuseReason'];?> <?php if($report['otherReason']!=''){echo ' : '.$report['otherReason'];} ?></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php echo $report['creationDate'];?></td>
											      <td><?php echo $report['status'];?></td>
											    </tr>
											<?php }}  ?>
											</table>

										      </div>
										      <div class="lineSpace_2">&nbsp;</div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div class="lineSpace_10">&nbsp;</div>	
									      </div>

								      </div> 
						      </div>
						      <?php  } 
							//} 
						      } ?>
						</div>
						<!-- Abuse report Start -->
						<div class="lineSpace_10">&nbsp;</div>
						<div class="clear_L"></div>
						<div class="lineSpace_11">&nbsp;</div>
						<!-- code for pagination start -->
						<div class="mar_full_10p">
							<div class="row" style="line-height:24px">
								<div class="normaltxt_11p_blk_arial" style="float:right;display:none;" align="right">
										<select name="countOffset" id="countOffset_DD2" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'qaStartFrom','qaCountOffset');">
										    <option value="5" <?php echo $number5;?>>5</option>
										    <option value="10" <?php echo $number10;?>>10</option>
										    <option value="20" <?php echo $number20;?>>20</option>
										    <option value="30" <?php echo $number30;?>>30</option>
										</select>
								</div>
								<div class="pagingID" id="paginataionPlace2" align="right"></div> 
							</div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<!-- code for pagination ends -->
						</div>
			</div>
			<div class="lineSpace_11">&nbsp;</div>
		</div>





<script>
function toggleAbuseDetails(id){
  if(document.getElementById(id).style.display == 'none'){
    document.getElementById(id).style.display = 'block';
    document.getElementById(id+'Toggler').src =  '/public/images/closedocument.gif';
  }
  else{
    document.getElementById(id).style.display = 'none';
    document.getElementById(id+'Toggler').src = '/public/images/plusSign.gif';
  }
}
</script>
<?php
		echo "<script> 
			setStartOffset(0,'qaStartFrom','qaCountOffset');
			doPagination(".$totalAbuse.",'qaStartFrom','qaCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";
?>
