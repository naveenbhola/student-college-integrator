<?php
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';$Live='';$Delete='';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'Draft': $Draft = "selected";
	      break;
  case 'Live': $Live = "selected";
	      break;
  case 'Delete': $Delete = "selected";
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
	<input type="hidden" autocomplete="off" id="methodName" value="insertQuestionTitleAndDiscussion"/>
	<input type="hidden" autocomplete="off" id="moduleNameSel" value="<?php echo $moduleName;?>"/>
<!--Pagination Related hidden fields Ends  -->


	<div style="float:left; width:100%;">
		<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><!--<b class="b3"></b><b class="b4"></b>-->
				<div class="lineSpace_10">&nbsp;</div>
				<div>
						<table width="99%" cellspacing="0" cellpadding="0" border="0" height="25">
							<tr>
								<td>
									<table cellspacing="0" cellpadding="0" border="0" height="25">
									<tr><td width="10%">&nbsp;</td>
										<td style="line-height:23px;display:block;"><span id="pagLabTop">Filter by Status</span>
											<select name="filter" id="filter" class="normaltxt_11p_blk_arial" onChange="filterQuestionInfo();">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="Draft" <?php echo $Draft;?>>Draft</option>
												<option value="Live" <?php echo $Live;?>>Live</option>
												<option value="Delete" <?php echo $Delete;?>>Delete</option>
											</select>
										</td>
									</tr>
									</table>
								</td>
								<td>
									<table cellspacing="0" cellpadding="0" border="0" height="25">
									<tr><td width="10%">&nbsp;</td>
										<td valign="middle">
											<span><input type="radio" name="reported" value="reportedBy" onClick="javascript: radioVal = 'reportedBy';" checked/></span>
										</td>
										<td valign="middle">
											Reported by&nbsp;
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
											<input type="button" value="Search" class="fontSize_10p" onClick="javascript: searchAbuseReport('0');"/>
										</td>
									</tr>
									</table>
								</td>
								<td align="right">
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
				
				<div class="" id="mainContainer">
						<div class="lineSpace_10">&nbsp;</div>
						<!-- Abuse report Start -->
						<div id="userAnswers">
						      <?php
							if($totalQuestion <= 0){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No Data is available.</div>
							<?php }							      //$threadIdList = '';

						      ?>
						      </div> </div>

						      <?php if($totalQuestion > 0){ ?>
						      <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
						      <tr>
							      <td width="7%" align="left">&nbsp;&nbsp;<a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(1);">Select All</a></td>
							      <td width="5%" align="left"><a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(0);">None</a></td>
							      <td width="8%"><input type="button" value="Delete" class="fontSize_10p" onClick="javascript: operateAbuseAll('delete',0);"/></td>
							      <td width="14%"><input type="button" value="Accept" class="fontSize_10p" onClick="javascript: operateAbuseAll('accept',0);"/></td>
							      <td width="65%"><div id='loadingImage' style="display:none;"><img src='/public/images/working.gif' border=0/></div></td>
						      </tr>
						      </table>
						      <?php } ?>

                                                    <?php
                                                    
                                                   
                                                   for($i=0;$i<count($info[0]->msgId);$i++){
                                                          ?>
                                <div class="lineSpace_10">&nbsp;</div>
						      <div class="" id="reportAbuseDiv<?php echo $info[0]->msgId[$i];?>">
								      <input type="hidden" id="msgTitle<?php echo $info[0]->msgId[$i];?>" value="<?php echo addslashes(htmlspecialchars($info[0]->msgTitle[$i])); ?>">
								      <input type="hidden" id="msgId<?php echo $info[0]->msgId[$i];?>" value="<?php echo $info[0]->msgId[$i]; ?>">
								      <input type="hidden" id="userId<?php echo $info[0]->msgId[$i];?>" value="<?php echo $info[0]->userId[$i];?>">
								      <input type="hidden" id="displayName<?php echo $info[0]->msgId[$i];?>" value="<?php echo $info[0]->displayname[$i];?>">
								      <input type="hidden" id="questionUserId<?php echo $info[0]->msgId[$i];?>" value="<?php echo $info[0]->questionUserId[$i];?>">
								      <input type="hidden" id="description<?php echo $info[0]->msgId[$i];?>" value="<?php echo mysql_escape_string($info[0]->description[$i]); ?>">
								      <input type="hidden" id="status<?php echo $info[0]->msgId[$i];?>" value="<?php echo $info[0]->status[$i]; ?>">
								      <div class="">
										  <div style="background:#dddddd;">
										    <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
										    <tr>
											    <td width="5%" align="left"><input type="checkbox" name="raCheckbox" id="raCheckbox<?php echo $info[0]->msgId[$i]; ?>"/></td>
											    <td width="20%" align="left">&nbsp;&nbsp;<b><?php echo $entityTypeDisplay." ID:".$info[0]->msgId[$i];?></b></td>
											    <td width="50%">by:&nbsp;<?php echo $info[0]->firstname[$i]."(".$info[0]->displayname[$i].")".",".$info[0]->userLevel[$i]." on ".$info[0]->editDate[$i];?></td>
											    <td align="right"><b>Status:&nbsp;<?php echo $info[0]->status[$i];?></b>&nbsp;&nbsp;</td>
										    </tr>
										    </table>

										  </div>
										  <div style="padding:0 10px 0 10px">
										      <div class="lineSpace_10">&nbsp;</div>
										      <div>
											      <a href="javascript:void(0);" onClick="window.open('<?php echo $temp['abuse']['url']; ?>');" class="fontSize_10p"><?php echo isset($temp['abuse']['msgTxt'])?nl2br(insertWbr($temp['abuse']['msgTxt'],30)):''; ?></a>
										      </div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div style="line-height:22px" class="fontSize_10p">
											<table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
											<tr>
												<td align="left" width="<?php if($info[0]->status[$i]=="Removed" || $temp['abuse']['status']=="Removed by Admin") echo "54%"; else echo "64%"; ?>"><img id="<?php echo $info[0]->msgId[$i]; ?>Toggler" src="/public/images/plusSign.gif" onclick="toggleAbuseDetails(<?php echo $info[0]->msgId[$i] ?>)" align="absmiddle" style="cursor:pointer;margin-top:-8px"/>&nbsp; &nbsp;<a href="javascript:void(0);" class="fontSize_10p blackFont bld" onclick="toggleAbuseDetails(<?php echo $info[0]->msgId[$i] ?>)">View Question Details</a> </td>
												<?php if($info[0]->status[$i]=="draft"){ ?>
												  <td align="right"><input type="button" value="Delete" class="fontSize_10p" onclick="deleteQuestionInfoInLog('<?php echo (mysql_escape_string($info[0]->msgTitle[$i])); ?>','<?php echo $info[0]->msgId[$i]; ?>','<?php echo $info[0]->userId[$i];?>','<?php echo $info[0]->displayname[$i];?>','1')"/></td>
												  <td align="right"><input type="button" value="Edit/Modify" class="fontSize_10p" onClick="showTitleDiv('<?php echo (mysql_escape_string($info[0]->msgTitle[$i])); ?>','<?php echo $info[0]->msgId[$i]; ?>','<?php echo $info[0]->questionUserId[$i];?>','<?php echo $info[0]->userId[$i];?>','admin')"/></td>
												  <td align="right"><input type="button" value="Accept" class="fontSize_10p" onClick="liveQuestionInfoInLog('<?php echo (mysql_escape_string($info[0]->msgTitle[$i])); ?>','<?php echo $info[0]->msgId[$i]; ?>','<?php echo $info[0]->userId[$i];?>','<?php echo $info[0]->questionUserId[$i];?>','1','<?php echo mysql_escape_string($info[0]->description[$i]); ?>','live');"/></td>
												<?php }
                                                                                            //else if($info[0]->status[$i]=="live"){ ?>
												 <!--<td align="right"><input type="button" value="Delete" class="fontSize_10p" onclick="deleteQuestionInfoInLog('<?php //echo $info[0]->msgTitle[$i]; ?>','<?php //echo $info[0]->msgId[$i]; ?>','<?php //echo $loggedInUserId;?>','<?php //echo $info[0]->questionUserId[$i];?>','1')"/></td>-->
												  <!--<td align="right"><input type="button" value="Edit" class="fontSize_10p" onClick="showTitleDiv('<?php //echo $info[0]->msgTitle[$i]; ?>','<?php //echo $info[0]->msgId[$i]; ?>','<?php //echo $loggedInUserId;?>','<?php //echo $info[0]->questionUserId[$i];?>','<?php //echo $info[0]->userId[$i];?>')"/></td>-->
												<?php //}else if($info[0]->status[$i]=="deleted"){ ?>
												 <!--td align="right"><input type="button" value="Edit" class="fontSize_10p" onClick="showTitleDiv('<?php //echo $info[0]->msgTitle[$i]; ?>','<?php //echo $info[0]->msgId[$i]; ?>','<?php //echo $loggedInUserI/d;?>','<?php //echo $info[0]->questionUserId[$i];?>','<?php //echo $info[0]->userId[$i];?>')"/></td>-->
												  <!--<td align="right"><input type="button" value="Live" class="fontSize_10p" onClick="javascript:liveQuestionInfoInLog('<?php //echo $info[0]->msgTitle[$i]; ?>','<?php //echo $info[0]->msgId[$i]; ?>','<?php //echo $info[0]->userId[$i];?>','<?php //echo $info[0]->questionUserId[$i];?>','1','live');"/></td>-->
												<?php //}?>
											</tr>
											</table>
										      </div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div id="<?php echo $info[0]->msgId[$i];?>" style="display:none;padding:0 10px 0 10px;">
											<table cellspacing="0" cellpadding="1" border="0" height="40" width="75%" style="border: 1px solid #E6E7E9">
											    <tr class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
											      <td width="9%" align="center" style="border-right: 1px solid #E6E7E9;">OwnerID</td>
											      <td width="17%" align="center" style="border-right: 1px solid #E6E7E9;">Owner Level</td>
											      <td width="12%" align="center" style="border-right: 1px solid #E6E7E9;">Expert ID</td>
											      <td width="15%" style="border-right: 1px solid #E6E7E9;">Expert Level</td>
											      <td width="25%" style="border-right: 1px solid #E6E7E9;">Question Title</td>
											      <td width="28%" >Question Detail</td>
											    </tr>
											
											
											    <tr height="30" style="border-style: dashed; border-width: medium;border-color: lime;">
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $info[0]->questionUserId[$i];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $info[0]->questionUserLevel[$i];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $info[0]->userId[$i];?></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php echo $info[0]->userLevel[$i];?></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php echo $info[0]->msgTitle[$i];?></td>
											      <td><?php echo ($info[0]->description[$i]);?></td>
											    </tr>
											
											</table>

										      </div>
										      <div class="lineSpace_2">&nbsp;</div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div class="lineSpace_10">&nbsp;</div>
									      </div>

								      </div>
						      </div>
                                <?php
                                                       
                                                   }
                                                    ?>
                                
						      
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
			doPagination(".$totalQuestion.",'qaStartFrom','qaCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";

?>


