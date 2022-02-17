<?php
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';$Live='';$Removed='';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'Pending': $Pending = "selected";
	      break;
  case 'Accepted': $Accepted = "selected";
	      break;
  case 'Rejected': $Rejected = "selected";
	      break;
  case 'Deleted': $Deleted = "selected";
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
	<input type="hidden" autocomplete="off" id="methodName" value="insertExperts"/>
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
											<select name="filter" id="filter" class="normaltxt_11p_blk_arial" onChange="filterExperts();">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="Pending" <?php echo $Pending;?>>Pending</option>
												<option value="Accepted" <?php echo $Accepted;?>>Accepted</option>
												<option value="Rejected" <?php echo $Rejected;?>>Rejected</option>
												<option value="Deleted" <?php echo $Deleted;?>>Deleted</option>
											</select>					
										</td>
									</tr>
									</table>
								</td>
								<td width="10%">&nbsp;</td>
								<td width="40%">
									<div style="vertical-align: middle;"><table cellspacing="0" cellpadding="0" border="0" height="25" valign="top">
									<tr valign="middle">
										<td width="10%">&nbsp;</td>
										<td valign="top">
											<select name="filterLevel" id="filterLevel" class="normaltxt_11p_blk_arial" style="height:20px;">
											<option value="All">All</option>
											<?php foreach ($expertLevelsForFilter as $key => $value) {?>
												<option value="<?=$value['levelName'];?>"<?php if($value['levelName'] == $userLevelFieldData){?>selected="selected"<?php }?>><?=$value['levelName'];?></option>
											<?php }?>
											</select>&nbsp;
										</td>
										<td valign="top">
											<span><input type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" id="userNameField" default="Username or Email" value="<?php if(isset($userNameFieldData) && $userNameFieldData!='') echo $userNameFieldData; else echo "Username or Email";?>" class="" style="color: rgb(173, 166, 173);width:120px;height:14px;">&nbsp;</span>
										</td>
										<td valign="top">
											<input type="button" value="Search" class="fontSize_10p" onClick="javascript: searchExperts();"/>
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
				<?php if($selectedFilter=='Pending'){ ?><div style="margin-top:5px;" class="txt_align_c showMessages">You have <?php echo $totalAbuse;?> Users awaiting Expert Approval.</div><?php } ?>
				<div class="" id="mainContainer">
						<div class="lineSpace_10">&nbsp;</div>
						<!-- Abuse report Start -->
						<div id="userAnswers">
						      <?php 
							if(count($userAbuse) <= 0){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No Expert Application is available.</div>
							<?php }

						      ?>
						      </div>
						      <?php if(count($userAbuse) > 0){ ?>
						      <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
						      <tr>
							      <td width="7%" align="left">&nbsp;&nbsp;<a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(1);">Select All</a></td>
							      <td width="5%" align="left"><a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(0);">None</a></td>
							      <td width="14%"><input type="button" value="Accept Application" class="fontSize_10p" onClick="javascript: operateExpertAll('accept');"/></td>
  							      <td width="2%"></td>
							      <td width="14%"><input type="button" value="Reject Application" class="fontSize_10p" onClick="javascript: operateExpertAll('reject');"/></td>
							      <td width="2%"></td>
							      <td width="21%"><input disabled type="button" value="Remove as Expert" class="fontSize_10p" onClick="javascript: operateExpertAll('remove');"/></td>
							      <td width="43%" align="left"><div id='loadingImage' style="display:none;"><img src='/public/images/working.gif' border=0/></div></td>
						      </tr>
						      </table>
						      <?php } ?>
						      <?php foreach($userAbuse as $temp){ 
							      if(is_array($temp['abuse'])){ 
										  $statusOrig = $temp['abuse']['status'];
										  switch($temp['abuse']['status']){
												  case 'Draft': 
													  $temp['abuse']['status'] = "Pending";
													  break;
												  case 'Live': 
													  $temp['abuse']['status'] = "Approved";
													  break;
												  case 'Rejected': 
													  $temp['abuse']['status'] = "Rejected";
													  break;
												  case 'Deleted': 
													  $temp['abuse']['status'] = "Deleted";
													  break;
										  }

						      ?>
						      <div class="lineSpace_10">&nbsp;</div>
						      <div class="" id="reportAbuseDiv<?php echo $temp['abuse']['userId'];?>">
								      <input type="hidden" id="userId<?php echo $temp['abuse']['userId'];?>" value="<?php echo $temp['abuse']['userId'];?>">
								      <input type="hidden" id="status<?php echo $temp['abuse']['userId'];?>" value="<?php echo $statusOrig;?>">
								      <div class="">

										  <!-- Display the heading of Request -->
										  <div style="background:#dddddd;">
										    <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
										    <tr>
												<td width="5%" align="left"><input type="checkbox" name="raCheckbox" id="raCheckbox<?php echo $temp['abuse']['userId']; ?>"/></td>
												<td align="left"><b>&nbsp;<?php echo $temp['abuse']['status'];?>:</b>&nbsp;Expert recruitement request</td>
										    </tr>
										    </table>
										  </div>

										  <div style="padding:0 10px 0 10px">
										      <div class="lineSpace_10">&nbsp;</div>
										      <div>
											      <a href="javascript:void(0);" onClick="window.open('<?php echo $temp['abuse']['url']; ?>');" class="fontSize_10p"><?php echo isset($temp['abuse']['userName'])?nl2br(insertWbr($temp['abuse']['userName'],30)):''; ?></a>
										      </div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div style="line-height:22px" class="fontSize_10p">
													<table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
															<tr>
																<td align="left" width="<?php if($temp['abuse']['status']=="Pending") echo "54%"; else echo "64%"; ?>"><img id="<?php echo $temp['abuse']['userId']; ?>Toggler" src="/public/images/plusSign.gif" onclick="toggleAbuseDetails(<?php echo $temp['abuse']['userId']; ?>)" align="absmiddle" style="cursor:pointer;margin-top:-8px"/>&nbsp; &nbsp;<a href="javascript:void(0);" class="fontSize_10p blackFont bld" onclick="toggleAbuseDetails(<?php echo $temp['abuse']['userId']; ?>)">View Expert  Details</a> </td>

																<?php if($temp['abuse']['status']=="Approved"){ ?>
																  <td align="right"><input disabled type="button" value="Remove as Expert" class="fontSize_10p" onClick="javascript: actionExpert('remove','<?php echo $temp['abuse']['userId'];?>');"/></td>
																<?php }else if($temp['abuse']['status']=="Pending"){ ?>
																  <td align="right"><input type="button" value="Accept Application" class="fontSize_10p" onClick="javascript: actionExpert('accept','<?php echo $temp['abuse']['userId'];?>');"/>&nbsp;</td>
																  <td width="15%" align="right"><input type="button" value="Reject Application" class="fontSize_10p" onClick="javascript: actionExpert('reject','<?php echo $temp['abuse']['userId'];?>');"/></td>
																<?php }?>
															</tr>
													</table>
										      </div>

										      <div class="lineSpace_10">&nbsp;</div>	

										      <div id="<?php echo $temp['abuse']['userId'];?>" style="display:none;padding:0 10px 0 10px;">
											<table cellspacing="0" cellpadding="1" border="0" height="25" width="100%" style="border: 1px solid #E6E7E9">
											    <tr height="30" class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
											      <td width="7%" align="center" style="border-right: 1px solid #E6E7E9;">User ID</td>
											      <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Name</td>
											      <td width="16%" align="center" style="border-right: 1px solid #E6E7E9;">Email</td>
											      <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Mobile</td>
                                                                                              <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Level</td>
                                                                                              <td width="7%" align="center" style="border-right: 1px solid #E6E7E9;">Points</td>
											      <td width="15%" align="center" style="border-right: 1px solid #E6E7E9;">Qualification</td>
											      <td width="15%" align="center" style="border-right: 1px solid #E6E7E9;">Designation</td>
											      <td width="26%" align="center">Company Name</td>											      
											    </tr>

												<tr height="45" style="border-style: dashed; border-width: medium;border-color: lime;">
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['userId'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['userName'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['email'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['mobile'];?></td>
                                                                                              <td id="userLevel<?php echo $temp['abuse']['userId'];?>" align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['ownerLevel'];?></td>
                                                                                              <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['ownerPoints'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['highestQualification'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['abuse']['designation'];?></td>
											      <td align="center"><?php echo $temp['abuse']['instituteName'];?></td>
											    </tr>

											</table>
										    <div class="lineSpace_10">&nbsp;</div>
											<table cellspacing="0" cellpadding="5" border="0" height="35" width="70%" style="">
											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">About my company:</font></td>
											      <td width="70%" align="left"><?php echo $temp['abuse']['aboutCompany'];?></td>
											    </tr>

											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">About me:</font></td>
											      <td width="70%" align="left"><?php echo $temp['abuse']['aboutMe'];?></td>
											    </tr>

											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">Institute:</font></td>
											      <td width="70%" align="left"><?php echo $temp['abuse']['instituteName'];?></td>
											    </tr>

											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">Social Media Links:</font></td>
											      <td width="70%" align="left">
														<?php $pipe=false;
														if(isset($temp['abuse']['facebookURL']) && $temp['abuse']['facebookURL']!=''){ $pipe=true; ?><a href="<?php echo $temp['abuse']['facebookURL'];?>" target="_blank">Facebook</a><?php } ?>
														
														<?php if(isset($temp['abuse']['linkedInURL']) &&	$temp['abuse']['linkedInURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['abuse']['linkedInURL'];?>" target="_blank">LinkedIn</a><?php } ?>

														<?php if(isset($temp['abuse']['twitterURL']) && $temp['abuse']['twitterURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['abuse']['twitterURL'];?>" target="_blank">Twitter</a><?php } ?>
														
														<?php if(isset($temp['abuse']['blogURL']) && $temp['abuse']['blogURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['abuse']['blogURL'];?>" target="_blank">Blog</a><?php } ?>

														<?php if(isset($temp['abuse']['youtubeURL']) && $temp['abuse']['youtubeURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['abuse']['youtubeURL'];?>" target="_blank">Youtube</a><?php } ?>
												  </td>
											    </tr>

											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">Profile Picture:</font></td>
											      <td width="70%" align="left"><a href="<?php echo ($temp['abuse']['imageURL']=='')?'/public/images/dummyImg.gif':$temp['abuse']['imageURL'];?>" target="_blank">View</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0);" onClick="removeExpertProfilePic('<?php echo $temp['abuse']['userId'];?>');">Remove and replace with default</a></td>
											    </tr>
											</table>
												<div style="margin-top:10px; margin-left:20px;"><input type="button" value="View Current Profile" class="fontSize_10p" onClick="window.open('<?php echo $temp['abuse']['url']; ?>');"/></div>

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
