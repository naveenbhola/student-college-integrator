<?php
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';$Live='';$Delete='';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'draft': $Draft = "selected";
	      break;
  case 'accepted': $Live = "selected";
	      break;
  case 'deleted': $Delete = "selected";
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
	<input type="hidden" autocomplete="off" id="methodName" value="insertLinkedAndOrignalDiscussionsAndQuestions"/>
	<input type="hidden" autocomplete="off" id="moduleNameSel" value="<?php echo $moduleName;?>"/>
        <input type="hidden" autocomplete="off" id="typeOfModeration" value="<?php echo 'discussion';?>"/>
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
											<select name="filter" id="filter" class="normaltxt_11p_blk_arial" onChange="filterLinkDiscussionInfo();">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="draft" <?php echo $Draft;?>>Draft</option>
												<option value="accepted" <?php echo $Live;?>>Accepted</option>
												<option value="deleted" <?php echo $Delete;?>>Delete</option>
											</select>
										</td>
									</tr>
									</table>
								</td>
								<td width="60%">
									<div style="vertical-align: middle;"><table cellspacing="0" cellpadding="0" border="0" height="25" valign="top">
									<tr valign="middle">
										<td width="10%">&nbsp;</td>
										<td valign="middle">
											<span>Linked by: </span>
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
											<input type="button" value="Search" class="fontSize_10p" onClick="javascript: searchLinkedEntityReport('disc');"/>
										</td>
									</tr>
									</table></div>
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
							if($totalCount <= 0){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No Data is available.</div>
							<?php }							      //$threadIdList = '';

						      ?>
						      </div> </div>

						      <?php if($totalCount > 0 && $selectedFilter!='deleted'){ ?>
                                                      <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
						      <tr>
							      <td width="7%" align="left">&nbsp;&nbsp;<a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(1);">Select All</a></td>
							      <td width="5%" align="left"><a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(0);">None</a></td>
							      <td width="8%"><input type="button" value="Delete" class="fontSize_10p" onClick="javascript: operateAbuseAll('deleteLinkDiscussion',0);"/></td>
							      <td width="14%"><input type="button" value="Accept" class="fontSize_10p" onClick="javascript: operateAbuseAll('acceptLinkDiscussion',0);"/></td>
							      <td width="65%"><div id='loadingImage' style="display:none;"><img src='/public/images/working.gif' border=0/></div></td>
						      </tr>
						      </table>
                                                      <?php } ?>

                                                    <?php

                                                   for($i=0;$i<count($userInfo1);$i++){
                                                          ?>
                                <div class="lineSpace_10">&nbsp;</div>
						      <div class="" id="linkDiscussionDiv<?php echo $userInfo1[$i]['id'];?>">
								      <input type="hidden" id="discussionId<?php echo $userInfo1[$i]['id'];?>" value="<?php echo $userInfo1[$i]['id']; ?>">
                                                                      <input type="hidden" id="threadId<?php echo $userInfo2[$i]['id'];?>" value="<?php echo $userInfo2[$i]['msgId']; ?>">
                                                                      <input type="hidden" id="linkedDiscussionId<?php echo $userInfo1[$i]['id'];?>" value="<?php echo $userInfo1[$i]['msgId'];?>">

								      <div class="">
										  <div style="background:#dddddd;">
										    <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
										    <tr>
											    <td width="5%" align="left"><input type="checkbox" name="raCheckbox" id="raCheckbox<?php echo $userInfo1[$i]['id']; ?>"/></td>
											    <td width="20%" align="left">&nbsp;&nbsp;<b><?php echo $entityTypeDisplay." Original Thread ID:".$userInfo2[$i]['threadId'];?></b></td>
											    <td width="50%">by:&nbsp;<?php echo $userInfo3[$i]['firstname']."(".$userInfo3[$i]['displayname'].")".",".$userInfo3[$i]['level']." on ".$userInfo1[$i]['createdDate'];?></td>
											    <td align="right"><b>Status:&nbsp;<?php echo $userInfo1[$i]['status'];?></b>&nbsp;&nbsp;</td>
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
												<td align="left" width="<?php if($info[0]->status[$i]=="Removed" || $temp['abuse']['status']=="Removed by Admin") echo "54%"; else echo "64%"; ?>"><img id="<?php echo $userInfo1[$i]['id']; ?>Toggler" src="/public/images/plusSign.gif" onclick="toggleAbuseDetails(<?php echo $userInfo1[$i]['id']; ?>)" align="absmiddle" style="cursor:pointer;margin-top:-8px"/>&nbsp; &nbsp;<a href="javascript:void(0);" class="fontSize_10p blackFont bld" onclick="toggleAbuseDetails(<?php echo $userInfo1[$i]['id']; ?>)">View Discussion Details</a> </td>
												<?php if($userInfo1[$i]['status']=="draft"){ ?>
												  <td align="right"><input type="button" value="Delete" class="fontSize_10p" onclick="changeLinkedDiscussionStatus('<?php echo $userInfo1[$i]['id']; ?>','<?php echo $userInfo2[$i]['threadId'];?>','<?php echo $userInfo1[$i]['threadId'];?>','deleted')"/></td>

  <td align="right"><input type="button" value="Accept" class="fontSize_10p" onClick="changeLinkedDiscussionStatus('<?php echo $userInfo1[$i]['id']; ?>','<?php echo $userInfo2[$i]['msgId'];?>','<?php echo $userInfo1[$i]['msgId'];?>','accepted');""/></td>
												<?php }else if($userInfo1[$i]['status']=="accepted"){ ?>
												  <td align="right"><input type="button" value="Delete" class="fontSize_10p" onclick="changeLinkedDiscussionStatus('<?php echo $userInfo1[$i]['id']; ?>','<?php echo $userInfo2[$i]['threadId'];?>','<?php echo $userInfo1[$i]['threadId'];?>','deleted')"/></td>

												<?php }else{ ?>
												<!--<td align="right"><input type="button" value="Accept" class="fontSize_10p" onClick="changeLinkedDiscussionStatus('<?php //echo $userInfo1[$i]['id']; ?>','<?php //echo $userInfo2[$i]['threadId'];?>','<?php //echo $userInfo1[$i]['threadId'];?>','accepted');"/></td>-->

												<?php }

												?>
											</tr>
											</table>
										      </div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div id="<?php echo $userInfo1[$i]['id'];?>" style="display:none;padding:0 10px 0 10px;">
											<table cellspacing="0" cellpadding="1" border="0" height="40" width="75%" style="border: 1px solid #E6E7E9">
											    <tr class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
											      <td width="9%" align="center" style="border-right: 1px solid #E6E7E9;">UserID</td>
											      <td width="17%" align="center" style="border-right: 1px solid #E6E7E9;">User Level</td>
											      <td width="25%" align="center" style="border-right: 1px solid #E6E7E9;">Original Thread</td>
											      <td width="25%" style="border-right: 1px solid #E6E7E9;">Linked Thread</td>
											      <td width="15%" style="border-right: 1px solid #E6E7E9;">Date</td>
											      <td width="15%" >Status</td>
											    </tr>


											    <tr height="30" style="border-style: dashed; border-width: medium;border-color: lime;">
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $userInfo3[$i]['userid'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $userInfo3[$i]['level'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><a href="javascript:void(0);" onClick="window.open('<?php echo getSeoUrl( $userInfo2[$i]['threadId'],'question', $userInfo2[$i]['msgTxt'],'','',$userInfo2[$i]['creationDate']); ?>');"><?php echo $userInfo2[$i]['msgTxt'];?></a></td>
                                                                                              <td style="border-right: 1px solid #E6E7E9;"><a href="javascript:void(0);" onClick="window.open('<?php echo getSeoUrl( $userInfo1[$i]['threadId'],'question', $userInfo1[$i]['msgTxt'],'','',$userInfo1[$i]['creationDate']); ?>');"><?php echo $userInfo1[$i]['msgTxt'];?></a></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php echo $userInfo1[$i]['createdDate'];?></td>
											      <td><?php echo $userInfo1[$i]['status'];?></td>
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
function toggleAbuseDetails(id){ alert(id);
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
			doPagination(".$totalCount.",'qaStartFrom','qaCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";

?>


