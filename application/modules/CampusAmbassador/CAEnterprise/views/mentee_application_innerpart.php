<?php
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';$Live='';$Removed='';
  switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'assigned': $assigned = "selected";
	      break;
  case 'unassigned': $unassigned = "selected";
	      break;  
  case 'deleted': $deleted = "selected";
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

  if(isset($userNameFieldDataMentee) && $userNameFieldDataMentee!=''){
      switch($filterTypeFieldDataMentee){
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
<style>.disabled {pointer-events: none;cursor: default;}</style>
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="menteeStartFrom" value="<?php echo $startFrom;?>"/>
	<input type="hidden" autocomplete="off" id="menteeCountOffset" value="<?php echo $countOffset;?>"/>
	<input type="hidden" autocomplete="off" id="menteeFilter" value=""/>
	<input type="hidden" autocomplete="off" id="methodName" value="showMenteeDetails"/>
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
												<option value="assigned" <?php echo $assigned;?>>Assigned</option>
												<option value="unassigned" <?php echo $unassigned;?>>Pending</option>
											   
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
											<span><input  class="universal-txt-field" type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" id="userNameField" default="Username or Email" value="<?php if(isset($userNameFieldDataMentee) && $userNameFieldDataMentee!='') echo $userNameFieldDataMentee; else echo "Username or Email";?>" class="" style="color: rgb(173, 166, 173);width:126px;height:14px;">&nbsp;</span>
										</td>
										<td valign="top">
											<input type="button" value="Search" class="orange-button" onClick="javascript: searchMenteeDetails();"/>
										</td>
										
									</tr>
									</table>
								</td>
								<td align="right">
									<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
										<td style="line-height:23px;display:none;"></span>
											<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'menteeStartFrom','menteeCountOffset');">
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
			      
	<div class="" id="mainContainer">
				  
			    <div class="lineSpace_10">&nbsp;</div>
			      <!-- Abuse report Start -->
			      <div id="userAnswers">
				      <?php 
					if($totalMentee <= 0){
					?>
					<div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No Mentee Application is available.</div>
					<?php }

				      ?>
			      </div>
						      
			    <div class="clearFix"></div>

<?php

foreach($menteeData as $menteeDatas){ 

$examtaken  = array();
	   
	    foreach($menteeExams[$menteeDatas['menteeId']] as $exams){
			
				$examtaken[] = $exams['examName'];
    
	     }
	     $examString = implode(',',$examtaken);
	     
 ?>	  
<div class="pending-section clearFix" style="background: #eee; padding: 7px 4px; color:#333; font-size:13px; margin:10px 10px; float:left; width:98%; -moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;" id="statusHeading_<?php echo $menteeDatas['menteeId']; ?>">
	<strong><?php if($menteeDatas['menteeStatus'] == 'unassigned'){echo 'Pending';}else {echo 'Assigned';} ?>:</strong> Mentee Approval
</div>
<div class="clearFix"></div>
<div style="margin:0 10px;">
													       
  <a href="javascript:void(0);" class="fontSize_10p" style="margin-bottom:5px; display: block;"><?php echo $menteeDatas['firstname'].' '.$menteeDatas['lastname'];?></a><br/>
  <img id="<?php echo $menteeDatas['menteeId']; ?>Toggler" src="/public/images/plusSign.gif" onclick="toggleMenteeDetails(<?php echo $menteeDatas['menteeId']; ?>)" align="absmiddle" style="cursor:pointer;margin-top:-8px"/>&nbsp; &nbsp;<a href="javascript:void(0);" class="fontSize_10p blackFont bld" onclick="toggleMenteeDetails(<?php echo $menteeDatas['menteeId']; ?>)">View Mentee Details</a> 
		  
</div>
					
<div id="menteeDetails_<?php echo $menteeDatas['menteeId']; ?>" style="display:none; margin:0 10px;">					
<p style="margin-top:10px;margin-bottom:10px;"">MENTEE INFORMATION</p>
<div style="padding: 0px 10px 0 0; margin-bottom:20px;" id="1077">
		<table cellspacing="0" cellpadding="1" border="0" width="100%" height="25" style="border: 1px solid #E6E7E9">
		  
		  <tbody>
		    
		    <tr height="30" style="border: 1px solid #E6E7E9" class="dcms_outerBrd">
			<td align="center" width="7%" style="border-right: 1px solid #E6E7E9;">User ID</td>
			<td align="center" width="10%" style="border-right: 1px solid #E6E7E9;">Name</td>
			<td align="center" width="16%" style="border-right: 1px solid #E6E7E9;">Email</td>
			<td align="center" width="16%" style="border-right: 1px solid #E6E7E9;">Exams Taken</td>
			<td align="center" width="10%" style="border-right: 1px solid #E6E7E9;">Mobile</td>
			<td align="center" width="10%" style="border-right: 1px solid #E6E7E9;">Engg target year</td>
		    </tr>

		    <tr height="45" style="border-style: dashed; border-width: medium;border-color: lime;">
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $menteeDatas['userId']; ?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $menteeDatas['firstname'].' '.$menteeDatas['lastname'];?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $menteeDatas['email']; ?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $examString;?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo '+'.$menteeDatas['isdCode'].'-'.$menteeDatas['mobile']; ?></td>
                        <td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $menteeDatas['startEngYear']; ?></td>
                    </tr>

		    <tbody>
	          </table>
</div>

<div style="padding: 0px 10px 0 0; margin-bottom:10px;" id="1077">
		<table cellspacing="0" cellpadding="1" border="0" width="100%" height="25" style="border: 1px solid #E6E7E9">
		  <tbody>
		    <tr height="30" style="border: 1px solid #E6E7E9" class="dcms_outerBrd">
			<td align="center" width="30%" style="border-right: 1px solid #E6E7E9;">Target Branches</td>
			<td align="center" width="30%" style="border-right: 1px solid #E6E7E9;">Target Locations</td>
			<td align="center" width="40%" style="border-right: 1px solid #E6E7E9;">Target Colleges</td>
		    </tr>

		    <tr height="45" style="border-style: dashed; border-width: medium;border-color: lime;">
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php if(isset($menteeDatas['prefEngBranche1']) && $menteeDatas['prefEngBranche1']!= ''){ echo $menteeDatas['prefEngBranche1'];}?><?php if(isset($menteeDatas['prefEngBranche2']) && $menteeDatas['prefEngBranche2'] != ''){echo ','.' '.$menteeDatas['prefEngBranche2'];} ?><?php if(isset($menteeDatas['prefEngBranche3']) && $menteeDatas['prefEngBranche3'] != ''){echo ','.' '.$menteeDatas['prefEngBranche3'];} ?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php if(isset($menteeDatas['prefCollegeLocation1']) && $menteeDatas['prefCollegeLocation1'] != ''){echo $menteeDatas['prefCollegeLocation1'];}?><?php if(isset($menteeDatas['prefCollegeLocation2']) && $menteeDatas['prefCollegeLocation2'] != ''){echo ','.' '.$menteeDatas['prefCollegeLocation2'];} ?><?php if(isset($menteeDatas['prefCollegeLocation3']) && $menteeDatas['prefCollegeLocation3'] != ''){echo ','.' '.$menteeDatas['prefCollegeLocation3'];} ?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;"><?php echo $menteeDatas['targetColleges']; ?></td>

                    </tr>

		    <tbody>
	          </table>
</div>
</div>

<?php if($menteeDatas['menteeStatus'] == 'assigned'){
    $style= 'style="margin-top:20px"';
    $style1 = 'display:none';
    
  }else if($menteeDatas['menteeStatus'] == 'deleted'){
    $style= 'style="display:none;margin-top:55px;"';
    $style1 = 'display:none';
    
  }else{ 
    $style= 'style="display:none;margin-top:55px;"';
    $style1 = ' ';
    
  }
?>

<div style="margin:30px 10px;<?=$style1;?>" id="assignMentorDiv_<?php echo $menteeDatas['menteeId']; ?>">
  <label style="display: block; margin-bottom:5px;">Assign Mentor</label>
  <input type="text" class="universal-txt-field" style="width:150px; float: left; margin-right:10px;" placeholder="Mentor Email Id" id="mentorEmail_<?php echo $menteeDatas['menteeId']; ?>"/>
  <input type="button" class="orange-button" value="Find Mentor" id="findMentor_<?php echo $menteeDatas['menteeId']; ?>" onclick="findMentorDetails($j('#mentorEmail_<?php echo $menteeDatas['menteeId']; ?>').val(),'<?php echo $menteeDatas['menteeId']; ?>','<?php echo $menteeDatas['userId']; ?>');">
</div>

<div <?=$style;?> id="mentorDetailDiv_<?php echo $menteeDatas['menteeId']; ?>">
<p style="margin:0 0 10px 10px;">MENTOR INFORMATION</p>
<div style="display: block; padding: 0px 10px 0 0; margin:0 0 10px 10px;" id="1077">
  
		<table cellspacing="0" cellpadding="1" border="0" width="100%" height="25" style="border: 1px solid #E6E7E9">
		  <tbody>
		    <tr height="30" style="border: 1px solid #E6E7E9" class="dcms_outerBrd">
			<td align="center" width="10%" style="border-right: 1px solid #E6E7E9;">Mentor User Id</td>
			<td align="center" width="20%" style="border-right: 1px solid #E6E7E9;">Mentor Name</td>
			<td align="center" width="20%" style="border-right: 1px solid #E6E7E9;">Mentor Email</td>
			<td align="center" width="30%" style="border-right: 1px solid #E6E7E9;">Mentor Mobile</td>
			<td align="center" width="20%" style="border-right: 1px solid #E6E7E9;">No. of Mentee</td>
		    </tr>

		    <tr height="45" style="border-style: dashed; border-width: medium;border-color: lime;">
			<td align="center" style="border-right: 1px solid #E6E7E9;" id="mentorUserId_<?php echo $menteeDatas['menteeId']; ?>"><?=$mentorDetails[$menteeDatas['menteeId']][0]['userid'];?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;" id="mentorName_<?php echo $menteeDatas['menteeId']; ?>"><?=$mentorDetails[$menteeDatas['menteeId']][0]['firstname'].' '.$mentorDetails[0]['lastname'];?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;" id="mentorEmailId_<?php echo $menteeDatas['menteeId']; ?>"><?=$mentorDetails[$menteeDatas['menteeId']][0]['email'];?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;" id="mentorMobile_<?php echo $menteeDatas['menteeId']; ?>"><?php echo '+'.$mentorDetails[$menteeDatas['menteeId']][0]['isdCode'].'-'.$mentorDetails[$menteeDatas['menteeId']][0]['mobile'];?></td>
			<td align="center" style="border-right: 1px solid #E6E7E9;" id="totalMentee_<?php echo $menteeDatas['menteeId']; ?>"><?=$menteeCount[$mentorDetails[$menteeDatas['menteeId']][0]['userid']];?></td>

                    </tr>

		    <tbody>
	          </table>
		
</div>

<div style="margin:30px 10px;margin-bottom:45px;<?=$style1;?>" id="chatIdDiv_<?php echo $menteeDatas['menteeId']; ?>">
  <label style="display: block; margin-bottom:5px;">CHAT ID</label>
  <input type="text" class="universal-txt-field" style="width:150px; float: left; margin-right:10px;" placeholder="Enter Chat Id" id="chatId_<?php echo $menteeDatas['menteeId']; ?>"/>
</div>

<?php if($menteeDatas['menteeStatus'] == 'unassigned'){?>
		  <input type="button" class="orange-button flRt" id="assignButton_<?=$menteeDatas['menteeId']?>" value="Assign" style="margin:0 10px 0px 0;padding:4px 30px;" onclick = "assignMentorToMentee('<?=$mentorDetails[$menteeDatas['menteeId']][0]['userid'];?>','<?php echo $menteeDatas['userId'] ?>','unassigned','<?php echo $menteeDatas['menteeId']; ?>',' ','<?=$menteeCount[$mentorDetails[$menteeDatas['menteeId']][0]['userid']];?>');">
<?php } elseif($menteeDatas['menteeStatus'] == 'assigned') { ?>
		  <input type="button" class="orange-button flRt" id="assignButton_<?=$menteeDatas['menteeId']?>" value="Unassign" style="margin:0 10px 0px 0; padding:4px 30px " onclick = "assignMentorToMentee('<?=$mentorDetails[$menteeDatas['menteeId']][0]['userid'];?>','<?php echo $menteeDatas['userId'] ?>','unassigned','<?php echo $menteeDatas['menteeId']; ?>',' ','<?=$menteeCount[$mentorDetails[$menteeDatas['menteeId']][0]['userid']];?>');">

<?php } ?>

</div>
<?php

} ?>



<div class="clearFix"></div>	  
					

						<!-- code for pagination start -->
						<div class="mar_full_10p">
							<div class="row" style="line-height:24px">
								<div class="normaltxt_11p_blk_arial" style="float:right;display:none;" align="right">
										<select name="countOffset" id="countOffset_DD2" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'menteeStartFrom','menteeCountOffset');">
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
function toggleMenteeDetails(id){
  if(document.getElementById('menteeDetails_'+id).style.display == 'none'){
    document.getElementById('menteeDetails_'+id).style.display = 'block';
    document.getElementById(id+'Toggler').src =  '/public/images/closedocument.gif';
  }
  else{
    document.getElementById('menteeDetails_'+id).style.display = 'none';
    document.getElementById(id+'Toggler').src = '/public/images/plusSign.gif';
  }
}

document.getElementById('statusFilter').value='All';
//document.getElementById('mentorEmail').value=' ';
//document.getElementById('chatId').value=' ';


</script>

<?php
		echo "<script>
			
			setStartOffset(0,'menteeStartFrom','menteeCountOffset');
			doPagination(".$totalMentee.",'menteeStartFrom','menteeCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";
?>
