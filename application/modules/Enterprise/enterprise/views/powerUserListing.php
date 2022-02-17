<?php
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';$Live='';$Removed='';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case '1': $Level1 = "selected";
	      break;
  case '2': $Level2 = "selected";
	      break;
  default: $All = "selected";
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

  
  $checked1='checked=checked';
  $checked2='';
  $checked3='';
  if(isset($_REQUEST['searchTypeVal'])){
	switch($_REQUEST['searchTypeVal']){
      case '1': $checked1 = "checked=checked";
		  break;
      case '2': $checked2 = "checked=checked";
		$checked1 = '';
		  break;
      case '3': $checked3 = "checked=checked";
    		$checked1 = '';
		  break;
      default:
		$checked1 = "checked=checked";
		  break;
      }
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
	<input type="hidden" autocomplete="off" id="methodName" value="insertPowerUser"/>
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
											<select name="filter" id="filter" class="normaltxt_11p_blk_arial" onChange="searchPowerUser(searchTypeVal);">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="1" <?php echo $Level1;?>>Level1</option>
												<option value="2" <?php echo $Level2;?>>Level2</option>
											</select>					
										</td>
									</tr>
									</table>
								</td>
								<td width="60%">
									<div style="vertical-align: middle;"><table cellspacing="0" cellpadding="0" border="0" height="25" valign="top">
									<tr valign="middle">
										
										<td><input type="radio" name="powerUser" value="1" onclick="searchType('1');" <?php echo $checked1;?> ></td>
										<td valign="middle">
											UserId&nbsp;
										</td>
										<td valign="top">
											<span><input type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" id="userIdField" default="User Id" value="<?php if(isset($userIdFieldData) && $userIdFieldData!='') echo $userIdFieldData; else echo "User Id";?>" class="" style="color: rgb(173, 166, 173);width:50px;height:14px;">&nbsp;</span>
										</td>
										<td valign="middle">&nbsp;OR&nbsp;</td>
										<td><input type="radio" name="powerUser" value="2" onclick="searchType('2');" <?php echo $checked2;?> ></td>
										<td valign="middle">
											&nbsp;Email Id&nbsp;
										</td>
										<td valign="top">
											<span><input type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" id="userEmailField" default="Email Id" value="<?php if(isset($userEmailFieldData) && $userEmailFieldData!='') echo $userEmailFieldData; else echo "Email Id";?>" class="" style="color: rgb(173, 166, 173);width:100px;height:14px;">&nbsp;</span>
										</td>
										<td valign="middle">&nbsp;OR&nbsp;</td>
										<td><input type="radio" name="powerUser" value="3" onclick="searchType('3');" <?php echo $checked3;?>></td>
										<td valign="top">
											
											<input name="minReputationPoint"  id="minReputationPoint" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" value="<?php if(isset($userminReputationPointFieldData) && $userminReputationPointFieldData!='') echo $userminReputationPointFieldData; else echo "Reputation Min";?>"  default="Reputation Min" class="" style="color: rgb(173, 166, 173);width:100px;height:14px;"/>
										</td>
										<td valign="middle">&nbsp;From&nbsp;</td>
										<td valign="top">
																			<input name="maxReputationPoint" id="maxReputationPoint" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" value="<?php if(isset($usermaxReputationPointFieldData) && $usermaxReputationPointFieldData!='') echo $usermaxReputationPointFieldData; else echo "Reputation Max";?>"  default="Reputation Max" class="" style="color: rgb(173, 166, 173);width:100px;height:14px;"/>									
										</td>
										<td width="3%">&nbsp;</td>
										<td valign="top">
											<input type="button" value="Search" class="fontSize_10p" onClick="javascript: searchPowerUser(searchTypeVal);"/>
										</td>
									</tr>
									</table></div>
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
							if($totalCount<= 0){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No Data is available.</div>
							<?php }

						      ?>
						      </div>
						      <?php if($totalCount > 0){ ?>
						      <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
						      <tr>
							      <td width="8%" align="left">&nbsp;&nbsp;<a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(1);">Select All</a></td>
							      <td width="5%" align="left"><a style="text-decoration:underline;" href="javascript:void(0);" onClick="selectAllRACheckbox(0);">None</a></td>
							     <td width="8%"><input type="button" value="Delete" class="fontSize_10p" onClick="javascript: operateAbuseAll('powerUserDelete',0);"/></td>
							      <td width="14%"><input type="button" value="Level1" class="fontSize_10p" onClick="javascript: operateAbuseAll('level1',1);"/></td>
							      <td width="1%"></td>
							      <td width="21%"><input type="button" value="Level2" class="fontSize_10p" onClick="javascript: operateAbuseAll('level2',2);"/></td>
							      <td width="43%" align="left"><div id='loadingImage' style="display:none;"><img src='/public/images/working.gif' border=0/></div></td>
                                                              <?php if($totalCount){ ?>
								<td align="right">
									<table cellspacing="0" cellpadding="0" border="0" height="25">
									<tr>

										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;width:180px;"></div></td>

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
							<?php } ?>
						      </tr>
						      </table>
						      
						      <?php foreach($userInfo as $temp){
							      if(is_array($temp)){ 
								$entityTypeDisplay = 'User ';
								if($temp['level']=='1' && $temp['status']=='live'){
									$levelName = 'Power User Level 1'; 
									//$type = "level1";	
								}else if($temp['level']=='2' && $temp['status']=='live'){
									$levelName = 'Power User Level 2';
									//$type = "level2"; 
								}else if(($temp['level']=='1' && $temp['status']=='deleted') || $temp['level']=='2' && $temp['status']=='deleted'){
									//$type = "powerUserDelete";
									$levelName = 'Power User Deleted';
								}else{
                                    $levelName = 'New User';
                                }
								   
						      ?>
						      <div class="lineSpace_10">&nbsp;</div>
						      <div class="" id="reportAbuseDiv<?php echo $temp['userid'];?>">
									<div class="" id="powerUserDiv<?php echo $temp['userid'];?>">
								      <input type="hidden" id="userid<?php echo $temp['userid'];?>" value="<?php echo $temp['userid'];?>">
								      <input type="hidden" id="level<?php echo $temp['userid'];?>" value="<?php echo $temp['level'];?>">
								      <input type="hidden" id="email<?php echo $temp['userid'];?>" value="<?php echo $temp['email'];?>">
								      <input type="hidden" id="displayname<?php echo $temp['userid'];?>" value="<?php echo $temp['displayname'];?>"></div>
								      <div class="">
										  <div style="background:#dddddd;">
										    <table cellspacing="0" cellpadding="0" border="0" height="25" width="100%">
										    <tr>
											    <td width="5%" align="left"><input type="checkbox" name="raCheckbox" id="raCheckbox<?php echo $temp['userid']; ?>"/></td>
											    <td width="20%" align="left">&nbsp;&nbsp;<b><?php echo $entityTypeDisplay." ID:".$temp['userid'];?></b></td>
											    <!--<td width="50%">by:&nbsp;<?php echo $temp['abuse']['firstname']."(".$temp['abuse']['displayname'].")".",".$temp['abuse']['ownerLevel']." on ".$temp['abuse']['msgCreationDate'];?></td>-->
									<?php 
									//if(trim($temp['level'])=='1' || trim($temp['level'])=='2')
									//{ ?>
										<td align="right"><b><?php echo $levelName;?></b>&nbsp;&nbsp;</td> 
									<?php //} 
									
									?>
										    </tr>
										    </table>

										  </div>
										  <div style="padding:0 10px 0 10px">
										     
										      <div class="lineSpace_10">&nbsp;</div>	
										      <div id="<?php echo $temp['userid'];?>" style="display:block;padding:0 10px 0 10px;clear:both;">
											<table cellspacing="0" cellpadding="1" border="0" height="40" width="75%" style="border: 1px solid #E6E7E9; float:left">
											    <tr class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
											      <td width="9%" align="center" style="border-right: 1px solid #E6E7E9;">User ID</td>
											      <td width="17%" align="center" style="border-right: 1px solid #E6E7E9;">Display Name</td>
											      <td width="32%" style="border-right: 1px solid #E6E7E9;">Email</td>
											      <td width="15%" style="border-right: 1px solid #E6E7E9;">Level of User</td>
											      <td width="10%" style="border-right: 1px solid #E6E7E9;">Reputaion Points</td>
											      <td width="33%" >Status</td>
											    </tr>
											<?php  //foreach($abuseDetails as $report){
											//if($report['entityId'] == $temp['abuse']['entityId']){ 
											
											  ?>
											    <tr height="30" style="border-style: dashed; border-width: medium;border-color: lime;">
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['userid'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $temp['displayname'];?></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php echo $temp['email'];?></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php if($temp['levelOfUser']!=''){ echo $temp['levelOfUser'];}else { echo 'Beginner';}?></td>
											      <td style="border-right: 1px solid #E6E7E9;"><?php if($temp['points']>0 && $temp['points']!='9999999'){
                echo round($temp['points']);
                }elseif($temp['points']<=0 && $temp['points']!='9999999'){
                  echo '0';
                }elseif($temp['points']=='9999999'){
                  echo '10';
                }?>
											      <td><?php  //if($temp['level']==1 || $temp['level']==2) 
													echo $levelName; 
													//else echo 'None';?></td>
											    </tr>
											<? //}}  ?>
											
											</table>
											
                                            <table style="float:right;">
											<tr>
												<td>
	<?php if(($temp['level']==1 || $temp['level']== 2  || $temp['level']!=0) && ($temp['level']!=NULL ) && $temp['status']=='live'){?>
              <input type="button" name="deleteLevel" value="Delete" onclick="deleteUserLevel('<?php echo $temp['userid'];?>');"/>
	<?php }else{ ?>
	      <input type="button" name="level1" value="level1" onclick="changeUserLevel('<?php echo $temp['userid'];?>','1','<?php echo $temp['email']; ?>','<?php echo $temp['displayname']; ?>');"/>
              <input type="button" name="level2" value="level2" onclick="changeUserLevel('<?php echo $temp['userid'];?>','2','<?php echo $temp['email']; ?>','<?php echo $temp['displayname']; ?>');"/>
												
					<?php } ?>	
					     						</td>
											</tr>
											</table>
										     <div style="clear:both;height:1px; overflow:hidden;">&nbsp;</div>
                                             </div>
											
					
									      </div>

								      </div> 
						      </div>
						      <?php  } 
							//} 
						      } ?>
						</div>
                                <?php } ?>
						<!-- Abuse report Start -->
						<div class="lineSpace_10">&nbsp;</div>
						<div class="clear_L"></div>
						<div class="lineSpace_11">&nbsp;</div>
						<!-- code for pagination start -->
						<?php if($totalCount){ ?>
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
								<div class="pagingID" id="paginataionPlace2" align="right" style="width:180px;float:right;"></div> 
							</div>
						</div>
						<?php } ?>
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
var searchTypeVal='1';
function searchType(val){
searchTypeVal = val;
if(val=='2'){
	document.getElementById('userIdField').value='User Id';
	document.getElementById('minReputationPoint').value='Reputation Min';
	document.getElementById('maxReputationPoint').value='Reputation Max';
}else if(val=='1'){
	document.getElementById('userEmailField').value='Email Id';
	document.getElementById('minReputationPoint').value='Reputation Min';
	document.getElementById('maxReputationPoint').value='Reputation Max';
}else{
	document.getElementById('userIdField').value='User Id';
	document.getElementById('userEmailField').value='Email Id';
}
}
</script>
<?php if($totalCount>0){
		echo "<script> 
			setStartOffset(0,'qaStartFrom','qaCountOffset');
			doPagination(".$totalCount.",'qaStartFrom','qaCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";
}
?>

