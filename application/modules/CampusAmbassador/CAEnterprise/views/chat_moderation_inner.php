<?php
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'draft': $pending = "selected";
	      break;
  case 'approved': $approved = "selected";
	      break;  
  case 'disapproved': $disapproved = "selected";
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

  if(isset($userNameFieldDataChatModeration) && $userNameFieldDataChatModeration!=''){
      switch($filterTypeFieldChatModeration){
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

<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="chatStartFrom" value="<?php echo $startFrom;?>"/>
	<input type="hidden" autocomplete="off" id="chatCountOffset" value="<?php echo $countOffset;?>"/>
	<input type="hidden" autocomplete="off" id="chatFilter" value=""/>
	<input type="hidden" autocomplete="off" id="methodName" value="showChatModerationDetails"/>
<!--Pagination Related hidden fields Ends  -->

        
	<div style="float:left; width:100%;">
		<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><!--<b class="b3"></b><b class="b4"></b>-->
				<div class="lineSpace_10">&nbsp;</div>
				<div style="margin:10px;">
						<table width="99%" cellspacing="0" cellpadding="0" border="0" height="25">
							<tr>
								<td width="22%">
									<table cellspacing="0" cellpadding="0" border="0" height="25" width="90%">
									<tr>
										  <td style="position: relative">
																		      
										    <span id="pagLabTop" style="position:absolute; top:-15px;">Filter by Status</span>
										      
										    
										    
											<select class="universal-select" name="statusFilter" id="statusFilter" class="normaltxt_11p_blk_arial" onChange="filterChatModerationData();" style="width:180px;">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="draft" <?php echo $pending;?>>Pending</option>
												<option value="approved" <?php echo $approved;?>>Approved</option>
												<option value="disapproved" <?php echo $disapproved;?>>Disapproved</option>
											   
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
											<span><input  class="universal-txt-field" type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" id="userNameField" default="Username or Email" value="<?php if(isset($userNameFieldDataChatModeration) && $userNameFieldDataChatModeration!='') echo $userNameFieldDataChatModeration; else echo "Username or Email";?>" class="" style="color: rgb(173, 166, 173);width:126px;height:14px;">&nbsp;</span>
										</td>
										<td valign="top">
											<input type="button" value="Search" class="orange-button" onClick="javascript: searchChatModerationDetails();"/>
										</td>
										
									</tr>
									</table>
								</td>
								<td align="right">
									<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
										<td style="line-height:23px;display:none;"></span>
											<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'chatStartFrom','chatCountOffset');">
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

<?php
$totalSubmission = count($moderationData);
?>

     <div style="float:left; width:100%; padding-bottom:86px;">
         <div class="raised_lgraynoBG">
                 <div class="lineSpace_10">&nbsp;</div>
                 <!--className boxcontent_lgraynoBG-->
                 <div class="" id="mainContainer">
		    
                        
                                       <div class="" style="float: left; 
width: 100%; margin-top: 20px;">

                                           <div>

                                               <div>
						<form>	    
						     <table cellspacing="0" 
cellpadding="1" border="0" height="25" width="100%" style="border: 1px 
solid #E6E7E9" id="mainIncentiveListTbl">
                                                 <tr height="30" 
class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
                                                   <td width="15%" 
align="center" style="border-right: 1px solid #E6E7E9;">Time Slot</td>
                                                   <td width="16%" 
align="center" style="border-right: 1px solid #E6E7E9;">Mentor Id</td>
						    <td width="9%" 
align="center" style="border-right: 1px solid #E6E7E9;">Mentor Name</td>
                                                   <td width="9%" 
align="center" style="border-right: 1px solid #E6E7E9;">Institute Name</td>
						   <td width="10%" 
align="center" style="border-right: 1px solid #E6E7E9;">Chat with (Mentee Name)</td>
                                                   <td width="13%" 
align="center" style="border-right: 1px solid #E6E7E9;">Chat History</td>
													<td width="13%" 
align="center" style="border-right: 1px solid #E6E7E9;">Approve/Disapprove</td>
						                         
						                         </tr>
<?php

 $even = 1;if($totalSubmission > 0)
				{foreach($moderationData as $key=>$value){
								$result = $instituteRepository->find($value['instituteId']);
								$chatLogSlotId = 0;
								if($value['chatStatus'] == 'draft')
								{
									$chatLogSlotId = $value['id'];
								}
								?>

                                                 <tr height="45" 
style="border-style: dashed; border-width: medium;border-color: lime;<?php if(($even % 2) == 0){?> background-color:#eef7fe <?php };?>">
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo $value['slotTime'];?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo $value['mentorId'];?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo $value['mentorName'];?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;" ><?php echo $result->getName();?></td>
						   							<td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo $value['menteeName'];?></td>
						   							<td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;" ><a href="javascript:void(0);" onclick="viewChatByCMS('<?=$chatLogSlotId?>', '<?php echo $value['chatText']; ?>')">View Chat </a></td>
													<td id ="statusButtons_<?=$chatLogSlotId?>" align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"> 
<?php if($value['chatStatus'] == 'draft'){ ?>
<a style="display:block; height:18px; margin-bottom:5px;" class="orange-button" onclick="chatApprove('<?=$chatLogSlotId?>','<?=$value['mentorId']?>')">Approve</a>
<a style="display:block; height:18px;" class="orange-button" onclick="chatDisapprove('<?=$chatLogSlotId?>')">Disapprove</a>
<?}else { echo ucfirst($value['chatStatus']); }?>
</td>
						 </tr>
                                               <?php $even++;}}else{ ?>
					       
					       <tr>
				<td colspan="8" align='center' style="padding:10px;">
				No data available.
				</td>
					       </tr>
				<?php }?>	       
			       </table>
						     </form>
			       </div>
                                               <div 
class="lineSpace_2">&nbsp;</div>
                                               <div 
class="lineSpace_10">&nbsp;</div>
                                               <div 
class="lineSpace_10">&nbsp;</div>
					       <div 
class="lineSpace_10">&nbsp;</div>
                                           </div>

                                       </div>
                               </div>
                         </div>
                        
                         <div class="clearFix"></div>	  
					

						<!-- code for pagination start -->
						<div class="mar_full_10p">
							<div class="row" style="line-height:24px">
								<div class="normaltxt_11p_blk_arial" style="float:right;display:none;" align="right">
										<select name="countOffset" id="countOffset_DD2" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'chatStartFrom','chatCountOffset');">
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

             </div>
             <div class="lineSpace_11">&nbsp;</div>
	<div class="session-popup-layer" id="viewChatLayer" style="display: none;">
	<a class="flRt session-cross-icon" href="javascript:void(0);" onclick="$j('#viewChatLayer, #popupLayerBasicBack').hide();">&times;</a>
	<p class="paste-chat-heading">Chat</p>
	<textarea class="pasted-chat-area" id="chatViewBox"></textarea>
	<div id="chatEditBoxErrorDiv" class="pasted-chat-error"></div>
	<a href="javascript:void(0);" class="ok-button" id="mentorChatEditBtn">Update chat</a>
    </div>
    <div id="popupLayerBasicBack" style="opacity: 0.4; z-index: 9999; display: none; height: 100%; position: fixed; left: 0px; bottom: 0px; top: 0px; right: 0px; background: rgb(0, 0, 0);"></div>

<style>
.disabled {
   pointer-events: none;
   cursor: default;
}
</style>

<script>
document.getElementById('statusFilter').value='All';
</script>

<?php
		echo "<script>
			
			setStartOffset(0,'chatStartFrom','chatCountOffset');
			doPagination(".$totalData.",'chatStartFrom','chatCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";
?>


