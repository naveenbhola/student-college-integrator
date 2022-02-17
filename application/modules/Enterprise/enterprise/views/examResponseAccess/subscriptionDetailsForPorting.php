<!-- <title>Add Courses To Groups</title> -->


<div class="mar_full_10p">

	<div style="float:left;width:100%">
		<div style="padding-bottom:15px;">
			<?php if(count($subscriptionDetails) <=0){ ?>
				<p style="font-size: 16px;text-align: center"> <b>No Exam Subscription Available</b></p><br>
			<?php }else{ ?>
			<?php if($type == 'active' || $type == 'all'){?>
					<p style="font-size: 16px"> <b>Exam Page Responses Access is currently given to:</b></p><br>
			<?php }else{?>
					<p style="font-size: 16px"> <b>Exam Page Responses Access is inactive / expired for:</b></p><br>
			<?php }?>
				<table class="subscriptionDetail-table">
					<thead>
						<tr>
							<td >Select</td>
							<td >Exam</td>
							<td >Course Group(s)</td>
							<td >Location(s)</td>
							<td >Start Date</td>
							<td >End Date</td>
							<td >Total Responses</td>
							
						</tr>
					</thead>
					<tbody >
					<?php
					//_P($subscriptionDetails);
					foreach ($subscriptionDetails as $key => $subscriptionDetail) {
						$checkedHtml = "";						
						if(count($portingConditions['examSubscription']) > 0){
							if(in_array($subscriptionDetail['id'], array_keys($portingConditions['examSubscription']))){
								$checkedHtml = "checked='checked'";
							}							
						}
						
						?>
						<tr>
							<td>
								<?php if($subscriptionDetail['status'] != 'active'){?>
									<input type="checkbox" name="subscriptionIds[]" <?=$checkedHtml?> disabled=disabled value="<?php echo $subscriptionDetail['id']?>">							
									<input type="checkbox" name="subscriptionIds[]" <?=$checkedHtml?> value="<?php echo $subscriptionDetail['id']?>" style="display:none;">
								<?php }else{ ?>
									<input type="checkbox" name="subscriptionIds[]" <?=$checkedHtml?>  value="<?php echo $subscriptionDetail['id']?>">
								<?php } ?>
							</td>
							<td ><?php echo $subscriptionDetail['examName']?></td>
							<td title="<?php echo $subscriptionDetail['groupNamesToolTip']?>"><?php echo $subscriptionDetail['groupNames']?></td>
							<td title="<?php echo $subscriptionDetail['userLocationsToolTip']?>" class="userLocations"><?php echo $subscriptionDetail['userLocations']?></td>
							<td ><?php echo $subscriptionDetail['startDate']?></td>
							<td ><?php echo $subscriptionDetail['endDate']?></td>
							<td ><?php echo $subscriptionDetail['quantityExpected']?></td>
							
						</tr>
					<?php }
					?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</div>

	<div>
	<li>
			<?php
				$this->load->config('response/responseConfig');
				//$responseTypes = $this->config->item('examResponseGrades');

				global $examResponseGrades;
				$responseTypes = $examResponseGrades;
			?>

			<label>Exam Response type:</label>
			<div class="form-fields">
				<div class="course-box" id="response_type_holder_exam">
					<ol>
						<li><input type="checkbox" name="response_types[]" id="all_response_types" onClick="checkUncheckChilds1(this, 'response_type_holder_exam')" <?php echo (!empty($portingConditions['responsetype']['All']) ? 'checked' : ''); ?> value="All" />All</li>
						<?php foreach($responseTypes as $resName => $resType) { ?>
						<li><input type="checkbox" name="response_types[]" onClick="uncheckElement1(this,'all_response_types','response_type_holder_exam');" <?php echo ((!empty($portingConditions['responsetype']['All']) || !empty($portingConditions['responsetype'][$resName])) ? 'checked' : ''); ?> value="<?php echo $resName; ?>"/><?php echo $resName; ?></li>
						<?php } ?>
						<li><input type="checkbox" name="response_types[]" onClick="uncheckElement1(this,'all_response_types','response_type_holder_exam');" <?php echo ((!empty($portingConditions['responsetype']['All']) || !empty($portingConditions['responsetype']['Others'])) ? 'checked' : ''); ?> value="Others"/>Others</li>
					</ol>
				</div>
			</div>
			
		</li>
	</div>
</div>
<style type="text/css">
	
	.subscriptionDetail-table tr td {
	    vertical-align: top;
	    border-bottom: 1px solid #111;
	    padding-right: 0px;
	    word-wrap: break-word;
	    border-right: 1px solid #111;
	    padding: 6px;
	    text-align: center;
	}

	.subscriptionDetail-table{
		width: 100%;
	    table-layout: fixed;
	    border: 1px solid #111;
    	border-collapse: collapse;
	}

	.btn-submit7.w9{
		width: auto;
	}

	.subscriptionDetail-table thead{
	  background: #e6e5e5;
	  font-size: 14px;
	}

	.subscriptionDetail-table tbody{
		font-size: 12px;
	}

	.subscriptionDetail-table tbody tr:nth-child(odd){
		background: #f4f4f4;
	}

	.disabled{
		pointer-events : none;
	}
</style>


