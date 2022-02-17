<title>Naukri Lead Details</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','mainStyle','footer','naukriLearningCMSPanel'),
        'js'    =>  array('common','enterprise','CalendarPopup','examResponseAccess'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');	
?>

<?php
	$this->load->view('common/calendardiv');
?>

<div class="mar_full_10p">
	<div style="float:left;width:100%">
		<div style="padding-bottom:15px;">
			<?php if(count($leadData) <=0){ ?>
				<p style="font-size: 16px;text-align: center"> <b>No Lead Allocated</b></p><br>
			<?php }else{  ?>
				<form autocomplete="off" action="/enterprise/NaukriLearningLeadsAccess/getLeadData" id="leadDetail" method="POST">
				<tr class="durationBased">
					<td>
						<input type="radio" name="campaignType" class="subscriptionQuantityFilter" required="true" value="duration" checked="", style="display: none" >
						<div style="display:block;vertical-align:top;margin :15px 0px 20px;float: left;width: 100%">
		                    <div class="to-col">
		                        <label>*From:</label>
		                        <input type="text" class="txt-ip cal-in" placeholder="yyyy/mm/dd" readonly="readonly" id="timeRangeDurationFrom" name="timeRangeDurationFrom" />
		                        <img src="/public/images/cal-icn.gif" id="timeRangeFromImage"/>
		                    </div>
		                    <div class="to-col">
		                        <label >*To:</label>
		                        <input type="text" class="txt-ip cal-in" placeholder="yyyy/mm/dd" readonly="readonly" id="timeRangeDurationTo" name="timeRangeDurationTo" />
		                        <img src="/public/images/cal-icn.gif" id="timeRangeToImage" />
		                    </div>
		                    
		                    <div class="to-col">
									<a href="javascript:void(0);" class="orange-button" id="getLeadData" style=" color: #ffffff;height: auto;display: inline-block;">Download CSV</a>
									<div id="timeRangeDuration_error" class="errorMsg" style="display:none;">
		                    		</div>
							</div>

		                </div>
		                
					</td>
				</tr>
			</form>

				<table class="subscriptionDetail-table">
					<thead>
						<tr>
							<td >Name</td>
							<td >Email</td>
							<td >Mobile</td>
							<td >Course Interested</td>
							<td >City</td>
							<td >State</td>
							<td >Creation Date</td>
							<td >Credits</td>
						</tr>
					</thead>
					<tbody >
			<?php }?>	 
					<?php
					foreach ($leadData as $key => $leadDetail) {?>
						<tr>
							<td ><?php echo $leadDetail['name']?></td>
							<td ><?php echo $leadDetail['email']?></td>
							<td ><?php echo $leadDetail['mobile']?></td>
							<td ><?php echo $leadDetail['course']?></td>
							<td ><?php echo $city[$leadDetail['city_id']]?></td>
							<td ><?php echo $state[$leadDetail['state_id']]?></td>
							<td ><?php echo explode(' ',$leadDetail['creation_time'])[0]?></td>
							<td ><?php echo $leadDetail['credits_deducted']?></td>
						</tr>
					<?php }
					?>
					</tbody>
				</table>
		</div>
	</div>
<?php 
$this->load->view('enterprise/footer'); ?>


<script>

	var examResponseAccessBinder = {};
	examResponseAccessBinder   = new examResponseAccess();
	examResponseAccessBinder.bindOnloadElements();
</script>
