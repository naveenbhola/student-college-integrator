<?php
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
$totalSubmission = count($submissionInfo['userInfo']);
$totalUsers =  $submissionInfo['totalUsers'];
?>
<?php 
if($totalSubmission <= 0){
?>
<div id="userAnswers">
<div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No data available.</div>
<div class="lineSpace_10p">&nbsp;</div>
</div>
<?php }else{

?>
<script>
var parameterObj = eval(<?php echo $parameterObj; ?>);
</script>
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="userStartFrom" value="<?php echo $startFrom;?>"/>
	<input type="hidden" autocomplete="off" id="userCountOffset" value="<?php echo $countOffset;?>"/>
	<input type="hidden" autocomplete="off" id="methodName" value="moreUsers"/>
	<input type="hidden" autocomplete="off" id="taskId" value="<?php echo $taskId;?>"/>
<!--Pagination Related hidden fields Ends  -->
        
	<div style="float:left; width:100%;">
		<div class="raised_lgraynoBG">
				<div class="lineSpace_10">&nbsp;</div>
				<!--className boxcontent_lgraynoBG-->
				<div class="" id="mainContainer">
					<table width="99%" cellspacing="0" cellpadding="0" border="0" height="25" style="display: none;">
							<tr>
								
								<td align="right" style="padding-top: 15px">
									<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
										<td style="line-height:23px;display:none;"></span>
											<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'userStartFrom','userCountOffset');">
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
						<div class="new-task-form">
						    <h2 style="border:0 none;" class="">Task Name : <?php echo $taskName;?></h2>
						</div>
						<div style="margin-top:8px;" class="clear-width">
						  <span class="flLt" style="padding-top:13px;">Submissions:</span>
						  <a href="/CAEnterprise/CampusAmbassadorEnterprise/downloadAllSubmissions/<?php echo $taskId;?>" class="orange-button flRt">Download All Submissions</a>
						</div>
						
						<!-- Start -->

						<div class="lineSpace_10">&nbsp;</div>
						      <div class="" id="reportcaDiv<?php echo $key;?>" style="margin-top: 20px;">

								      <div class="" style="float: left; width: 100%; margin-top: 20px;">

										  <div>

										      <div id="<?php echo $value;?>">
											<table cellspacing="0" cellpadding="1" border="0" height="25" width="100%" style="border: 1px solid #E6E7E9">
											    <tr height="30" class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
											      <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">User ID</td>
											      <td width="20%" align="center" style="border-right: 1px solid #E6E7E9;">User Name</td>
											      <td width="30%" align="center" style="border-right: 1px solid #E6E7E9;">Institute Name</td>
											      <td width="30%" align="center" style="border-right: 1px solid #E6E7E9;">Total Payment</td>
											      <td width="10%" align="center" style="border-right: 1px solid #E6E7E9;">Submissions</td>										      
											    </tr>
						      
											  <?php
											  $count = 1;
											  foreach($submissionInfo['userInfo'] as $key=>$value){
												?>
											    <tr height="45" style="border-style: dashed; border-width: medium;border-color: lime;<?php if($count%2==0){ echo "background:#eef7fe;"; } ?>">
											      <td align="center" style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 10px;"><?php echo $value['userId'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 10px;"><?php echo $value['displayName'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 10px;"><?php echo $value['instituteName'];?></td>
											      <td align="center" style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 10px;">
												    <?php if(trim($value['rewardAmout'])==''){ ?><input type="text" style="border: 1px solid rgb(204, 204, 204); padding: 4px 2px; margin-right: 5px; width: 165px;" class="flLt" id="paymentBox_<?php echo $value['userId'];?>" caption="Task Name" required="true" validate="validateStr" minlength="1" maxlength="100"><?php } ?>
												    <?php if(trim($value['rewardAmout'])==''){ ?> <a class="orange-button" href="javascript:void(0);" style="display: inline-block; margin-top: 0px;" onclick="makePaymentForTask('paymentBox','<?php echo $value['userId'];?>','<?php echo $value['taskId'];?>');" id="updateButton_<?php echo $value['userId'];?>">Update</a><?php } ?>
												    <div><div id="paymentBox_<?php echo $value['userId'];?>_error" class="errorMsg" style="float: left;"></div></div>
												    <p style="margin-top:6px; text-align: center; font-size:14px; color:#666;<?php if(trim($value['rewardAmout'])==''){ echo "display: none;"; } ?>" id="showMsg_<?php echo $value['userId'];?>" >Rs : <?php echo $value['rewardAmout'];?></p>
											      </td>
											      <td style=" vertical-align: top; padding: 10px;">
												<ul>
											      <?php foreach($value['caSubmissions']['fileName'] as $k=>$fileName){ ?>
											      <li style="margin-bottom:4px;"><a href="<?php echo $value['caSubmissions']['url'][$k];?>" target="_blank"><?php echo $fileName;?></a></li>
											      <?php  } ?>
											      </ul>
											      </td>
											  <?php $count++; } ?>
						      </table>

										      </div>
										      <div class="lineSpace_2">&nbsp;</div>
										      <div class="lineSpace_10">&nbsp;</div>
										      <div class="lineSpace_10">&nbsp;</div>	
									      </div>

								      </div> 
						      </div>
						</div>
						<!-- Abuse report Start -->

						<div class="lineSpace_10">&nbsp;</div>
						<div class="clear_L"></div>
						<div class="lineSpace_11">&nbsp;</div>

						<!-- code for pagination start -->
						<div class="mar_full_10p" style="float: right;margin-bottom: 5px;">
							<div class="row" style="line-height:24px">
							       <div style="float:left;">
									<div style="display:none; float: left; margin-right: 5px">
									Show Rows: <select name="countOffset" id="countOffset_DD2" onChange="updateCountOffset(this,'userStartFrom','userCountOffset');">
									    <option value="5" <?php echo $number5;?>>5</option>
									    <option value="10" <?php echo $number10;?>>10</option>
									    <option value="20" <?php echo $number20;?>>20</option>
									    <option value="30" <?php echo $number30;?>>30</option>
									</select>
									</div>
								</div>
								<div class="pagingID" id="paginataionPlace2" style="float: left"></div> 
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
			setStartOffset(0,'userStartFrom','userCountOffset');
			doPagination(".$totalUsers.",'userStartFrom','userCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";
?>
<?php } ?>