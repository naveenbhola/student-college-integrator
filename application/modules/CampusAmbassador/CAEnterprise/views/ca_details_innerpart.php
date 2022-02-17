<?php
  $badgesCourse = array('None'=>'None','CurrentStudent'=>'Current Student','Alumni'=>'Alumni');
  $badgesCourseNew = array('None'=>'None','CurrentStudent'=>'Current Student');
  $badgesOfficial = array('None'=>'None','Official'=>'Official');
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $catFilter = isset($catFilter)?$catFilter:'All';
  $All = '';$Live='';$Removed='';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'draft': $draft = "selected";
	      break;
  case 'accepted': $accepted = "selected";
	      break;
  case 'incomplete': $incomplete = "selected";
	      break;
  case 'deleted': $deleted = "selected";
	      break;
  case 'removed': $removed = "selected";
              break;
  }
  
  switch($catFilter){
  case 'All': $catAll = "selected";
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

  if(isset($userNameFieldDataCA) && $userNameFieldDataCA!=''){
      switch($filterTypeFieldDataCA){
      case 'User Name': $userName = "selected";
		  break;
      //case 'Institute': $institute = "selected";
	//	  break;
      case 'Email': $email = "selected";
		  break;
      case 'Filter Type': $filterType = "selected";
		  break;
      default: $filterType = "selected";
		  break;
      }
  }

?>
<script>
var parameterObj = eval(<?php echo $parameterObj; ?>);
</script>
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="caStartFrom" value="<?php echo $startFrom;?>"/>
	<input type="hidden" autocomplete="off" id="caCountOffset" value="<?php echo $countOffset;?>"/>
	<input type="hidden" autocomplete="off" id="caFilter" value=""/>
	<input type="hidden" autocomplete="off" id="methodName" value="insertCampusAmbassabor"/>
<!--Pagination Related hidden fields Ends  -->

        
	<div style="float:left; width:100%;">
		<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><!--<b class="b3"></b><b class="b4"></b>-->
				<div class="lineSpace_10">&nbsp;</div>
				<div style="margin-top: 10px;">
						<table width="99%" cellspacing="0" cellpadding="0" border="0" height="25">
							<tr>
								<td width="22%">
									<table cellspacing="0" cellpadding="0" border="0" height="25" width="90%">
									<tr>
										  <td style="position: relative">
																		      
										    <span id="pagLabTop1" style="position:absolute;left:0; top:-16px;">Program</span>
										   
											<select class="universal-select flLt" name="catFilter" id="catFilter" class="normaltxt_11p_blk_arial" onChange="filterCAData();" style="width:99px; margin-right:5px;">
											<option value="All" <?php echo $catAll;?>>All</option>
											<?php foreach ($ccPrograms as $key => $value) {?>
													<option value="<?php echo $value['programId'];?>" 
													<?php if($catFilter == $value['programId']) { 
														echo "selected";
													}?>>
													<?php if($value['programName'] == 'All')
														  { 
														  	$programName = 'Generic';
														  }else{
															$programName = $value['programName'];
														  }
														echo $programName; ?></option>
													<?php } ?>
											</select>
										    <span id="pagLabTop" style="position:absolute; top:-15px;width: 100%">Filter by Status</span>
										      
										    
										    
											<select class="universal-select" name="filter" id="filter" class="normaltxt_11p_blk_arial" onChange="filterCAData();" style="width:80px;">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="draft" <?php echo $draft;?>>Pending</option>
												<option value="accepted" <?php echo $accepted;?>>Accepted</option>
												<!--<option value="incomplete" <?php //echo $incomplete;?>>Incomplete</option>-->
												<option value="deleted" <?php echo $deleted;?>>Reject</option>
												<option value="removed" <?php echo $removed;?>>Deleted</option>
											</select>					
										</td>
									</tr>
									</table>
								</td>
								<td width="55%">
									<table cellspacing="0" cellpadding="5" border="0" valign="top">
									<tr valign="middle">
										<td valign="top">
											<select class="universal-select" name="filterLevel" id="filterLevel" style="width:100px;">
						                                                <option value="Filter Type" <?php echo $filterType;?>>Filter Type</option>
												<option value="User Name" <?php echo $userName;?>>User Name</option>
												<!--<option value="Institute" <?php //echo $institute;?>>Institute</option>-->
												<option value="Email" <?php echo $email;?>>Email</option>
											</select>&nbsp;
										</td>
										<td valign="top">
											<span><input  class="universal-txt-field" type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" id="userNameField" default="Username or Email" value="<?php if(isset($userNameFieldDataCA) && $userNameFieldDataCA!='') echo $userNameFieldDataCA; else echo "Username or Email";?>" class="" style="color: rgb(173, 166, 173);width:126px;height:14px;">&nbsp;</span>
										</td>
										<td valign="top">
											<input type="button" value="Search" class="orange-button" onClick="javascript: searchCampusAmbassador();"/>
										</td>
										<td valign="top">
						                                    <div id="anaAutoSuggestor" style="position: relative;">
                                                                                    <input type="text" name="keywordSuggest" id="keywordSuggest"  class="universal-txt-field" autocomplete="off" default="Search By Institute name..." value="<?php if(!empty($keywordSuggest)){ echo $keywordSuggest;}else{ ?>Search By Institute name...<?php } ?>" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" />
                                                                                     <div id="suggestions_container" style="position:absolute; left:7px; top:44px;background-color:#fff;"></div>
						                                     </div>
               
                    <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
										</td>
									</tr>
									</table>
								</td>
								<td align="right">
									<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
										<td style="line-height:23px;display:none;"></span>
											<select name="countOffset" id="countOffset_DD1" style = "width:auto;" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'caStartFrom','caCountOffset');">
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
				<?php if($selectedFilter=='Pending'){ ?><div style="margin-top:5px;" class="txt_align_c showMessages">You have <?php echo $totalCA;?> Users awaiting Expert Approval.</div><?php } ?>
				<div class="" id="mainContainer">
						<div class="lineSpace_10">&nbsp;</div>
						<!-- Abuse report Start -->
						<div id="userAnswers">
						      <?php 
							if($totalCA <= 0){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No Campus Ambassador Application is available.</div>
							<?php }

						      ?>
						      </div>
						      <?php if($totalCA > 0){ ?>
						      <!--<table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
						      <tr>
							      <td width="7%" align="left">&nbsp;&nbsp;<a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllCACheckbox(1);">Select All</a></td>
							      <td width="5%" align="left"><a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllCACheckbox(0);">None</a></td>
							      <td width="14%"><input type="button" value="Accept" class="fontSize_10p" onClick="javascript: performMultipleAction('accepted');"/></td>
  							      <td width="2%"></td>
							      <td width="14%"><input type="button" value="Reject" class="fontSize_10p" onClick="javascript: operateExpertAll('reject');"/></td>
							      <td width="2%"></td>
							      <td width="21%"><input type="button" value="Remove / Update CA" class="fontSize_10p" onClick="javascript: operateExpertAll('remove');"/></td>
							      <td width="43%" align="left"><div id='loadingImage' style="display:none;"><img src='/public/images/working.gif' border=0/></div></td>
						      </tr>
						      </table>-->
						      <?php } ?>
						      <?php foreach($caData as $temp){ 
							      if(is_array($temp['ca'])){ 
										  $statusOrig = $temp['ca']['profileStatus'];
										  switch($temp['ca']['profileStatus']){
												  case 'draft': 
													  $temp['ca']['profileStatus'] = "Pending";
													  break;
												  case 'accepted': 
													  $temp['ca']['profileStatus'] = "Accepted";
													  break;
												  case 'deleted': 
													  $temp['ca']['profileStatus'] = "Rejected";
													  break;
												  /*case 'incomplete': 
													  $temp['ca']['profileStatus'] = "Incomplete";
													  break;*/
                                                                                                  case 'removed':
                                                                                                          $temp['ca']['profileStatus'] = "Deleted";
                                                                                                          break;
										  }

						      ?>
						      <div class="lineSpace_10">&nbsp;</div>
						      <div class="" id="reportcaDiv<?php echo $temp['ca']['userId'];?>">
								      <input type="hidden" id="userId<?php echo $temp['ca']['userId'];?>" value="<?php echo $temp['ca']['userId'];?>">
								      <input type="hidden" id="status<?php echo $temp['ca']['userId'];?>" value="<?php echo $statusOrig;?>">
								      <div class=""> <?php //_p($temp['ca']);?>
										  <!-- Display the heading of Request -->
										  <div style="background:#dddddd;">
										    <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
										    <tr>
												<!--<td width="5%" align="left"><input type="checkbox" name="caCheckbox" id="caCheckbox<?php echo $temp['ca']['userId']; ?>"/></td>-->
												<td align="left"><b>&nbsp;<?php echo $temp['ca']['profileStatus'];?>:</b>&nbsp;Campus Ambassador Recruitement Request</td>
										    </tr>
										    </table>
										  </div>

										  <div style="padding:0 10px 0 10px">
										      <div class="lineSpace_10">&nbsp;</div>
										      <div>
											      <a href="javascript:void(0);" onClick="window.open('<?php echo $temp['ca']['url']; ?>');" class="fontSize_10p"><?php echo isset($temp['ca']['userName'])?nl2br(insertWbr($temp['ca']['userName'],30)):''; ?></a>
										      </div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div style="line-height:22px" class="fontSize_10p">
													<table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
													    <tr>
													       <td>
													            <a href="javascript:void(0);" class="fontSize_10p"><?php echo $temp['ca']['displayName'];?></a>
											      		       </td>
													    </tr>							  
															<tr>
																<td align="left" width="<?php if($temp['ca']['status']=="Pending") echo "54%"; else echo "30%"; ?>"><img id="<?php echo $temp['ca']['id']; ?>Toggler" src="/public/images/plusSign.gif" onclick="toggleCADetails(<?php echo $temp['ca']['id']; ?>)" align="absmiddle" style="cursor:pointer;margin-top:-8px"/>&nbsp; &nbsp;<a href="javascript:void(0);" class="fontSize_10p blackFont bld" onclick="toggleCADetails(<?php echo $temp['ca']['id']; ?>)">View CA Details</a> </td>
													                        <?php if($temp['ca']['profileStatus']=="Accepted"){ ?>
																  <!--<td align="right"><input class="orange-button" type="button" value="Remove/Update" class="fontSize_10p" onclick="javascript: showRemoveUpdateLayer('<?php echo $temp['ca']['displayName'];?>','<?php echo $temp['ca']['userId']; ?>');"/></td>-->
																  <td width="15%" align="right"><input class="orange-button" type="button" value="Delete" class="fontSize_10p" onclick="javascript: updateStatusAndBadges('removed','<?php echo $temp['ca']['userId']; ?>');"/></td>
																<?php }else if($temp['ca']['profileStatus']=="Pending" && (!empty($temp['collegeReviewData']) || !empty($temp['mentorQna']))){ ?>
																  <td align="right"><input class="orange-button" type="button" value="Accept" class="fontSize_10p" onclick="javascript: updateStatusAndBadges('accepted','<?php echo $temp['ca']['userId']; ?>');"/>&nbsp;</td>
																  <td width="15%" align="right"><input class="orange-button" type="button" value="Reject" class="fontSize_10p" onclick="javascript: updateStatusAndBadges('deleted','<?php echo $temp['ca']['userId']; ?>');"/></td>
																  <!--<td width="15%" align="right"><input class="orange-button" type="button" value="Incomplete" class="fontSize_10p" onclick="javascript: showIncompleteDetailsLayer('incomplete','<?php //echo $temp['ca']['userId']; ?>');return false;"/></td>-->
																<?php }else if($temp['ca']['profileStatus']=="Rejected"){ ?>
																<?php }/*else if($temp['ca']['profileStatus']=="Incomplete"){ ?>
																  <td align="right"><input type="button" class="orange-button" value="Accept" class="fontSize_10p" onclick="javascript: updateStatusAndBadges('accepted','<?php echo $temp['ca']['userId']; ?>');"/>&nbsp;</td>
																  <td width="15%" align="right"><input class="orange-button" type="button" value="Reject" class="fontSize_10p" onclick="javascript: updateStatusAndBadges('deleted','<?php echo $temp['ca']['userId']; ?>');"/></td>
																<?php }*/ ?>
																
																
															</tr>
													</table>
										      </div>

										      <div class="lineSpace_10">&nbsp;</div>	

										      <div id="<?php echo $temp['ca']['id'];?>" style="display:none;padding:0 10px 0 10px;">
											<table cellspacing="0" cellpadding="1" border="0" height="25" width="100%" style="border: 1px solid #E6E7E9">
											    <tr height="30" class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
											      <td width="7%" align="center" style="border-right: 1px solid #E6E7E9;">User ID</td>
											      <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Name</td>
											      <td width="16%" align="center" style="border-right: 1px solid #E6E7E9;">Email</td>
											      <td width="16%" align="center" style="border-right: 1px solid #E6E7E9;">Secondary Email</td>
											      <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Mobile</td>
											      <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Social Media</td>
					

                                                                                              <!--<td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Level</td>
                                                                                              <td width="7%" align="center" style="border-right: 1px solid #E6E7E9;">Points</td>
											      <td width="15%" align="center" style="border-right: 1px solid #E6E7E9;">Qualification</td>
											      <td width="15%" align="center" style="border-right: 1px solid #E6E7E9;">Designation</td>
											      <td width="26%" align="center">Company Name</td>	-->										      
											    </tr>

												<tr height="45" style="border-style: dashed; border-width: medium;border-color: lime;">
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['userId'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['displayName'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['email'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['studentEmail'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo '+'.$temp['ca']['isdCode'].'-'.$temp['ca']['mobile'];?></td>

											      <td align="center" style="border-right: 1px solid #E6E7E9;">
												
													      <?php
													      if ((!preg_match("~^(?:f|ht)tps?://~i", $temp['ca']['facebookURL'])) && $temp['ca']['facebookURL']!=''){
														$temp['ca']['facebookURL'] = 'http://'.$temp['ca']['facebookURL'];
													      }
													      if ((!preg_match("~^(?:f|ht)tps?://~i", $temp['ca']['linkedInURL']))  && $temp['ca']['linkedInURL']!=''){
														$temp['ca']['linkedInURL'] = 'http://'.$temp['ca']['linkedInURL'];
													      }
													      $pipe=false;
														if(isset($temp['ca']['facebookURL']) && $temp['ca']['facebookURL']!=''){ $pipe=true; ?><a href="<?php echo $temp['ca']['facebookURL'];?>" target="_blank">Facebook</a><?php } ?>
														
														<?php if(isset($temp['ca']['linkedInURL']) &&	$temp['ca']['linkedInURL']!=''){ ?> <?php if($pipe) echo ","; else $pipe=true; ?> <a href="<?php echo $temp['ca']['linkedInURL'];?>" target="_blank">LinkedIn</a><?php } ?>

														<?php if(isset($temp['ca']['twitterURL']) && $temp['ca']['twitterURL']!=''){ ?> <?php if($pipe) echo ","; else $pipe=true; ?> <a href="<?php echo $temp['ca']['twitterURL'];?>" target="_blank">Twitter</a><?php } ?>
														
														<?php if(isset($temp['ca']['blogURL']) && $temp['ca']['blogURL']!=''){ ?> <?php if($pipe) echo ","; else $pipe=true; ?> <a href="<?php echo $temp['ca']['blogURL'];?>" target="_blank">Blog</a><?php } ?>

														<?php if(isset($temp['ca']['youtubeURL']) && $temp['ca']['youtubeURL']!=''){ ?> <?php if($pipe) echo ","; else $pipe=true; ?> <a href="<?php echo $temp['ca']['youtubeURL'];?>" target="_blank">Youtube</a><?php } ?>

</td>
                                                                                              <!--<td id="userLevel<?php echo $temp['ca']['userId'];?>" align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['ownerLevel'];?></td>
                                                                                              <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['ownerPoints'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['highestQualification'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['ca']['designation'];?></td>
											      <td align="center"><?php echo $temp['ca']['instituteName'];?></td>-->
											    </tr>

											</table>
										    <div class="lineSpace_10">&nbsp;</div>
											<table cellspacing="0" cellpadding="5" border="0" width="100%">
											    <!--<tr height=30>
											      <td width="20%" align="right" ><font color="grey">About my company:</font></td>
											      <td width="70%" align="left"><?php echo $temp['ca']['aboutCompany'];?></td>
											    </tr>-->

											    <tr>
											      <!--<td width="20%" align="right" ><font color="grey">About me:</font></td>
											      <td width="50%" align="left"><?php echo $temp['ca']['aboutMe'];?></td>
											      <td width="15%">&nbsp;</td>
											      <td width="15%">&nbsp;</td>-->
											    </tr>
						                                            <!--<tr height=30>
											      <td width="20%" align="right" ><font color="grey">Current Qualification:</font></td>
											      <td width="70%" align="left"><select id="<?php //echo $temp['ca']['userId'];?>_34"><?php foreach($badges as $key=>$value){?> <option value="<?php echo $key;?>"><?php echo $value;?></option><?php } ?></select></td>
											    </tr>
											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">About Course:</font></td>
											      <td width="70%" align="left"><?php //echo $temp['ca']['instituteName'];?></td>
											    </tr>
											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">More Qualification:</font></td>
											      <td width="70%" align="left"><select id="<?php //echo $temp['ca']['userId'];?>_56"><?php foreach($badges as $key=>$value){?> <option value="<?php echo $key;?>"><?php echo $value;?></option><?php } ?></select></td>
											    </tr>
											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">Official Representatives:</font></td>
											      <td width="70%" align="left"><?php echo $temp['ca']['instituteName'];?></td>
											      <td width="70%" align="left"><select id="<?php //echo $temp['ca']['userId'];?>_88"><?php foreach($badges as $key=>$value){?> <option value="<?php echo $key;?>"><?php echo $value;?></option><?php } ?></select></td>
											    </tr>-->
											    <?php
											    $storeCourseIds = '';
											    $uniqueId_education = '';
											    if (array_key_exists('mainEducationDetails', $temp['ca'])) { ?>
													  <tr>
													    <td align="right" width="20%"><font color="grey">Profile Picture:</font></td>
													    <td align="left"  width="55%"><a href="<?php echo ($temp['ca']['imageURL']=='')?'/public/images/dummyImg.gif':$temp['ca']['imageURL'];?>" target="_blank">View</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0);" onClick="removeCAProfilePic('<?php echo $temp['ca']['userId'];?>');">Remove and replace with default</a>
<?php if($temp['ca']['profileStatus'] === 'Accepted' || $temp['ca']['profileStatus'] === 'Pending'){?>
													    &nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0);" onClick="showCAInfoLayer(this,'open',<?php echo $temp['ca']['userId'];?>);">Edit CA Information</a>
													    <?php }?>
													    </td>
													    <td  width="15%"></td>
													    <td  width="10%"></td>
													  </tr>
    													    <?php
													    foreach($temp['ca']['mainEducationDetails'] as $key=>$value){ ?>
													    <input type="hidden" value="<?php echo $value['instituteId'].'|'.$value['courseId'];?>" id="caEducationDetail<?php echo $temp['ca']['userId'];?>">
													    <tr>
														<?php if($value['isCurrentlyPursuing']=='Yes'){ ?>						  
													         <td align="right" ><font color="grey"><?=(!empty($temp['mentorQna']))?'Under Graduate Institute:':'Post Graduate Institute:'?></font></td>
														 <?php }else{ ?>
														 <td align="right" ><font color="grey">More Qualification:</font></td>
														 <?php } ?>
														 <td align="left" ><?php 
														 if($value['insName']){
														 	echo $value['insName'];}?>
														 <?php if($value['locName']){
														 	echo ", ".$value['locName'];
														 }?>
														  <?php if($value['cityName']){
														 	echo ", ".$value['cityName'];
														 }?>
														  <?php if($value['stateName']){
														 	echo ", ".$value['stateName'];
														 }?>
														 <?php 
														 	echo ", India";
														 ?><?php if($value['stateName']){
														 	echo ", ".$value['stateName'];
														 }?>

														 , <?php echo $value['courseName'];?>,<?=(!empty($value['semester']))?' Semester:'.$value['semester']:' Graduation Year:'.$value['yearOfGrad']?></td>
														 <td align="left">
														  <?php if($temp['ca']['profileStatus']=='Accepted'){?>
														  <select class="universal-select" id="<?php echo $value['id'];?>_<?php echo $value['courseId']?>_education">
														  <option>
														    <?php echo $badgesCourse[$value['badge']];?>
														  </option>
														  </select>
														  <?php }else{ ?>
														  <select class="universal-select" id="<?php echo $value['id'];?>_<?php echo $value['courseId']?>_education"><?php foreach($badgesCourseNew as $bKey=>$bValue){?> <option value="<?php echo $bKey;?>" <?php if($bKey==$value['badge']){ echo "selected='selected'";} ?>><?php echo $bValue;?></option><?php } ?>
														  </select>
														  <?php } ?>
														 </td>
														 <?php if($temp['ca']['profileStatus']=='Accepted' && $temp['ca']['profileStatus']!='None'){ ?><td align="right"><a href="javascript:void(0);" onclick="showOtherCoursesLayer('<?php echo $value['instituteId'];?>','<?php echo $value['courseId'];?>','<?php echo $value['locationId'];?>','<?php echo $temp['ca']['userId'];?>','<?php echo $value['badge'];?>','<?php echo $value['id'];?>','<?php echo $temp['ca']['programId'];?>');">+Add More Courses</a></td><?php } ?>
													    </tr>
													   <!---- <tr> 
													         <td align="right" ><font color="grey">About Course:</font></td>
														 <td align="left" >
													               <font color="grey">Eligibilty:</font>
														       <span><?php //echo $value['eligibilty'];?></span>
														       <font color="grey">Selection Procedure:</font>
														       <span><?php //echo $value['selectionProcess'];?></span>
														       <font color="grey">Fees:</font>
														       <span><?php //echo $value['fees'];?></span>
														 </td>
														 <td></td>
														 <td></td>
													    </tr>-->
													    <input type="hidden" id="<?php echo $value['id'];?>_courseIds_education" value="<?php echo $value['courseId'];?>"/>
													    <?php
													    $storeCourseIds .= $value['courseId'].',';
													    $uniqueId_education .= $value['id'].',';
													    } ?>
						                                             
											    <?php } ?>
											     <input type="hidden"  value="<?php echo rtrim($uniqueId_education,',');?>" id="<?php echo $temp['ca']['userId'];?>_uniqueId_education"/>
											    <?php $storeOfficialCourseIds= '';$fromOfficialDate = '';$toOfficialDate = '';$uniqueId_official='';
											    if($temp['ca']['isOfficial']=='Yes'){ 
												if(strtotime($temp['ca']['officialDateFrom'])!=0){
													$fromOfficialDate = ' From:'.date('d/m/Y',strtotime($temp['ca']['officialDateFrom']));
												}
												if(strtotime($temp['ca']['officialDateTo'])!=0){
													$toOfficialDate = ' To:'.date('d/m/Y',strtotime($temp['ca']['officialDateTo']));
												}
											    ?>
													    <tr>
													         <td align="right" ><font color="grey">Official Qualification:</font></td>
														 <td align="left" ><?php echo $temp['ca']['officailInsName'];?>, <?php echo $temp['ca']['officailCourseName'];?><?php echo (($temp['ca']['officailLocName'])?', '.$temp['ca']['officailLocName'].", ":", ");?><?php echo $temp['ca']['officailCityName'];?>, <?php echo $temp['ca']['officailStateName'];?>, <?php echo $temp['ca']['officailCountryName'];?>, <?php echo $temp['ca']['officialDesignation'];?>, <?php echo $fromOfficialDate;?> <?php echo $toOfficialDate;?></td>
														 <td align="left">
														  <?php if($temp['ca']['profileStatus']=='Accepted'){ ?>
														  <select class="universal-select">
														    <option>
														    <?php echo $badgesOfficial[$temp['ca']['officialBadge']];?>
														  </option>
														  </select>
														  <?php }else{ ?>
														  <select class="universal-select" id="<?php echo $temp['ca']['id'];?>_<?php echo $temp['ca']['officialCourseId'];?>_official"><?php foreach($badgesOfficial as $key=>$value){?> <option value="<?php echo $key;?>" <?php if($key==$temp['ca']['officialBadge']){ echo "selected='selected'";} ?>><?php echo $value;?></option><?php } ?>
														  </select>
														  <?php } ?>
														 </td>
														 <?php if($temp['ca']['profileStatus']=='Accepted' && $temp['ca']['officialBadge']!='None'){ ?><td align="right"><a href="javascript:void(0);" onclick="showOtherCoursesLayer('<?php echo $temp['ca']['officialInstituteId'];?>','<?php echo $temp['ca']['officialCourseId'];?>','<?php echo $temp['ca']['officialInstituteLocId'];?>','<?php echo $temp['ca']['userId'];?>','<?php echo $temp['ca']['officialBadge'];?>','<?php echo $temp['ca']['id'];?>','<?php echo $temp['ca']['programId'];?>');">+Add More Courses</a></td><?php } ?>
													    </tr>
						                                            <?php
													    $storeOfficialCourseIds = $temp['ca']['officialCourseId'];
													    $uniqueId_official = $temp['ca']['id'];
											    }
											    ?>
											    <input type="hidden"  value="<?php echo $uniqueId_official;?>" id="<?php echo $temp['ca']['userId'];?>_uniqueId_official"/>
											    <!--<input type="hidden" id="<?php echo $temp['ca']['userId'];?>_courseIds" value="<?php echo rtrim($storeCourseIds,',')?>"/>-->
											    <input type="hidden" id="<?php echo $temp['ca']['id'];?>_courseId_official" value="<?php echo $storeOfficialCourseIds;?>"/>
											    <input type="hidden" id="<?php echo $temp['ca']['userId'];?>_uniqueId_official_main" value="<?php echo $temp['ca']['id'];?>"/>
											    
						                                            <!--
											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">Institute:</font></td>
											      <td width="70%" align="left"><?php //echo $temp['ca']['instituteName'];?></td>
											    </tr>

											    <tr height=30>
											      <td width="20%" align="right" ><font color="grey">Social Media Links:</font></td>
											      <td width="70%" align="left">
														<?php $pipe=false;
														if(isset($temp['ca']['facebookURL']) && $temp['ca']['facebookURL']!=''){ $pipe=true; ?><a href="<?php echo $temp['ca']['facebookURL'];?>" target="_blank">Facebook</a><?php } ?>
														
														<?php if(isset($temp['ca']['linkedInURL']) &&	$temp['ca']['linkedInURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['ca']['linkedInURL'];?>" target="_blank">LinkedIn</a><?php } ?>

														<?php if(isset($temp['ca']['twitterURL']) && $temp['ca']['twitterURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['ca']['twitterURL'];?>" target="_blank">Twitter</a><?php } ?>
														
														<?php if(isset($temp['ca']['blogURL']) && $temp['ca']['blogURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['ca']['blogURL'];?>" target="_blank">Blog</a><?php } ?>

														<?php if(isset($temp['ca']['youtubeURL']) && $temp['ca']['youtubeURL']!=''){ ?> <?php if($pipe) echo "|"; else $pipe=true; ?> <a href="<?php echo $temp['ca']['youtubeURL'];?>" target="_blank">Youtube</a><?php } ?>
												  </td>
											    </tr>-->
  <?php if(!empty($temp['mentorQna'])){ ?>
  <tr>
    
  </tr>
    <?php
    $qnaCounter = 0;
    foreach($temp['mentorQna'] as $tuple)
    {
      $qnaCounter++;
      if($qnaCounter == 1){
	?>
	<tr>
	  <td align="right" <?=(!empty($temp['mentorQna']))?'valign="top"':''?> ><font color="grey">Under Graduate Institute Answers:</font></td>
	  <td>
	    <strong><?=$qnaCounter.'. '.$tuple['questionText']?></strong><br/>
	    <?='A. '.$tuple['answerText']?>
	  </td>
	</tr>
	<?php
      }else{
	?>
	<tr>
	  <td align="right" ></td>
	  <td>
	    <strong><?=$qnaCounter.'. '.$tuple['questionText']?></strong><br/>
	    <?='A. '.$tuple['answerText']?>
	  </td>
	</tr>
	<?php
      }
    ?>
    <?php
    }
    ?>
  </tr>
  <?php } ?>
											    <?php if(!empty($temp['collegeReviewData']['ratingParam'])){ 
											    ?>
											    <tr>
											      <td align="right" ><font color="grey">Under Graduate Institute:</font></td>
											      <td align="left"><?php echo $temp['collegeReviewData']['courseInformation'];?></td>
											      <td></td>
											      <td></td>
											    </tr>
											    
											    <tr>
											      <td align="right" ><font color="grey">Under Graduate Institute Review:</font></td>
											      <td></td>
											      <td></td>
											    </tr>
											    <tr>
											      <td align="right" ></td>
											      <td>Post this review as anonymous when it is published on Shiksha: <?php echo ucfirst(strtolower($temp['collegeReviewData']['anonymousFlag']));?></td>
											      <td></td>
											    </tr>
											    <tr>
											      <td align="right" ></td>
											      <td><strong>Describe your college to someone who has never seen or heard of it.The good, the bad, the ugly.</strong></td>
											      <td></td>
											    </tr>
											<?php if(!empty($temp['collegeReviewData']['placementDescription'])){ ?>
											    <tr>
											      <td align="right" ></td>
											      <td align="left"><?php echo 'Placements : '.$temp['collegeReviewData']['placementDescription'];?></td>
											      <td></td>
											      <td></td>
											    </tr>
											<?php } ?>

											<?php if(!empty($temp['collegeReviewData']['infraDescription'])){ ?>
											    <tr>
											      <td align="right" ></td>
											      <td align="left"><?php echo 'Infrastructure : '.$temp['collegeReviewData']['infraDescription'];?></td>
											      <td></td>
											      <td></td>
											    </tr>
											<?php } ?>

											<?php if(!empty($temp['collegeReviewData']['facultyDescription'])){ ?>
											    <tr>
											      <td align="right" ></td>
											      <td align="left"><?php echo 'Faculty : '.$temp['collegeReviewData']['facultyDescription'];?></td>
											      <td></td>
											      <td></td>
											    </tr>
											<?php } ?>

											<?php if(!empty($temp['collegeReviewData']['reviewDescription'])){ 
												$titleDescr = 'Description : ';
												if(!empty($temp['collegeReviewData']['placementDescription'])){
													$titleDescr = 'Other Details : ';
												}

												?>
											    <tr>
											      <td align="right" ></td>
											      <td align="left"><?php echo $titleDescr.$temp['collegeReviewData']['reviewDescription'];?></td>
											      <td></td>
											      <td></td>
											    </tr>
											<?php } ?>

											    
											    <?php 
											    		 $this->load->view('CAEnterprise/ca_reviewRatingParameter',array('ratingValue'=>$temp['collegeReviewData']));?>
											    <tr>
											      <td colspan="3">
												<table style="margin-left:180px;">
												  <tr>
												    <td align="right"></td>
												    <td valign="top" width="332px">Given a chance, would you go back to this college again: </td>
												    <td><?php echo ucfirst(strtolower($temp['collegeReviewData']['recommendCollegeFlag']));?></td>
												 </tr>
												  <?php if($temp['collegeReviewData']['otherReason']!=''){?>
												  <tr>
												    <td align="right" ></td>
												    <td valign="top" width="332px">Others :</td>
												    <td><?php echo $temp['collegeReviewData']['otherReason'];?></td>
												  </tr>
												  <?php } ?>
												</table>
											    </td>
											    </tr>

											    <?php if(!empty($temp['collegeReviewData']['reviewTitle'])){ ?>
											    <tr>
											      <td colspan="3">
												<table style="margin-left:180px;">
												  <tr>
												    <td align="right"></td>
												    <td valign="top" width="332px">Title of Review: </td>
												    <td><?php echo ucfirst(strtolower($temp['collegeReviewData']['reviewTitle']));?></td>
												 </tr>
												</table>
											    </td>
											    </tr>
											    <?php } ?>

											    <tr>
											      <td colspan="3">
												<table style="margin-left:180px;">
												  <tr>
												    <td align="right"></td>
												    <td valign="top" width="332px">Review Quality: </td>
												    <td>
												    	<select style="display:inline;" id="revQlty<?php echo $temp['collegeReviewData']['reviewId']; ?>" name="revQlty<?php echo $temp['collegeReviewData']['reviewId']; ?>" onchange="showSaveButton(<?php echo $temp['collegeReviewData']['reviewId']; ?>);">
                                        					<option value="excellent" <?php if($temp['collegeReviewData']['reviewQuality'] == 'excellent') echo 'selected'; ?> >Excellent</option>
                                        					<option value="good" <?php if($temp['collegeReviewData']['reviewQuality'] == 'good') echo 'selected'; ?> >Good</option>
                                        					<option value="average" <?php if($temp['collegeReviewData']['reviewQuality'] == 'average') echo 'selected'; ?>>Average</option>
                                    					</select>
                                    					<a class="submit-btn" style="display:none;" id="qltyButton<?php echo $temp['collegeReviewData']['reviewId']; ?>" onclick="saveQualityFlag(<?php echo $temp['collegeReviewData']['reviewId']; ?>);" href="javascript:void(0);">Save</a>
                                    					<div style="display:none;font-size:12px;" id="revQlty<?php echo $temp['collegeReviewData']['reviewId']; ?>_error" class="errorMsg">Flag updated successfully</div>
												    </td>
												 </tr>
												</table>
											    </td>
											    </tr>

											    <?php } ?>
											</table>
												<div style="margin-top:10px; margin-left:190px;"><?php if($temp['ca']['profileStatus']!='Rejected'){ ?><input  class= "orange-button" type="button" value="View Current Profile" class="fontSize_10p" onClick="window.open('<?php echo SHIKSHA_ASK_HOME.'/getUserProfile/'.urlencode($temp['ca']['dName']); ?>');"/><?php } ?>
												<?php
												
										      if(isset($temp['collegeReviewData']) && is_array($temp['collegeReviewData'])){ ?>											
										    <span id="mainStausBtn_<?php echo $temp['collegeReviewData']['reviewId']; ?>">
										      <?php if($temp['collegeReviewData']['status']=='draft'){?>
										      
										      <input id='acceptReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>'  class= "orange-button" type="button" value="Accept Review" class="fontSize_10p" onclick="updateReviewStatus('accepted','<?php echo $temp['collegeReviewData']['reviewId']; ?>')"/>
										      <input id='rejectReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>' class= "orange-button" type="button" value="Reject Review" class="fontSize_10p" onclick=" reviewReject('<?php echo $temp['collegeReviewData']['reviewId']; ?>')" />
										      
										      <input id='publishReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>' class= "orange-button" type="button" value="Publish Review" class="fontSize_10p" onclick=" updateReviewStatus('published','<?php echo $temp['collegeReviewData']['reviewId']; ?>')" />										     
										      <?php }else if($temp['collegeReviewData']['status']=='accepted'){?>										
											<input id='rejectReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>' class= "orange-button" type="button" value="Reject Review" class="fontSize_10p" onclick=" reviewReject('<?php echo $temp['collegeReviewData']['reviewId']; ?>')" />
										      
										      <input id='publishReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>' class= "orange-button" type="button" value="Publish Review" class="fontSize_10p" onclick=" updateReviewStatus('published','<?php echo $temp['collegeReviewData']['reviewId']; ?>')" />
										      
										
											<?php }else if($temp['collegeReviewData']['status']=='rejected'){?>

<input id='acceptReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>'  class= "orange-button" type="button" value="Accept Review" class="fontSize_10p" onclick=" updateReviewStatus('accepted','<?php echo $temp['collegeReviewData']['reviewId']; ?>')"/>
										      
										      <input id='publishReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>' class= "orange-button" type="button" value="Publish Review" class="fontSize_10p" onclick=" updateReviewStatus('published','<?php echo $temp['collegeReviewData']['reviewId']; ?>')" />
										  
										      <?php }else if($temp['collegeReviewData']['status']=='published'){?>
										      
										       <input id='unpublishReview_<?php echo $temp['collegeReviewData']['reviewId']; ?>' class= "orange-button" type="button" value="UnPublish Review" class="fontSize_10p" onclick=" updateReviewStatus('accepted','<?php echo $temp['collegeReviewData']['reviewId']; ?>')" />
										       
										      <?php } ?></span><?php }?>
												
												</div>
												<div style="font-size: 14px; margin: 10px 0px 10px 190px;">
												  <p id='acceptmsg_<?php echo $temp['collegeReviewData']['reviewId']; ?>' <?php echo ($temp['collegeReviewData']['status']=='accepted')?'':'style="display:none;"'?>><strong>The Review has been Accepted</strong></p>
												  <p id='rejectmsg_<?php echo $temp['collegeReviewData']['reviewId']; ?>' <?php echo ($temp['collegeReviewData']['status']=='rejected')?'':'style="display:none;"'?>><strong>The Review has been Rejected</strong></p>
												</div>
										      	

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
										<select name="countOffset" id="countOffset_DD2" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'caStartFrom','caCountOffset');">
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
function toggleCADetails(id){
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
			setStartOffset(0,'caStartFrom','caCountOffset');
			doPagination(".$totalCA.",'caStartFrom','caCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";
?>
