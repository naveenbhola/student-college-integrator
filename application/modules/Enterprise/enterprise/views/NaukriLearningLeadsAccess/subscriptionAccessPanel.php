<title>Naukri Learning Subscription Panel</title>
<?php 
$headerComponents = array(
		'css'          =>  array('headerCms','mainStyle','footer','naukriLearningCMSPanel'),
		'js'           =>  array('common','enterprise','examResponseAccess','CalendarPopup'),
		'displayname'  => (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
		'tabName'      =>  '',
		'taburl'       => site_url('enterprise/Enterprise'),
		'metaKeywords' =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<?php
	$this->load->view('common/calendardiv');
?>

<div class="mar_full_10p">

	<?php
		$manualLDBAccessHeader = array(
								'isNaukriLearningLeadsAccess'  => true,
								'isNaukriLearningSubscription' => true
								);
		$this->load->view('examResponseAccess/manualLDBAccessHeader',$manualLDBAccessHeader);
	?>
	
	<div style="float:left;width:100%">
		<p style="font-size: 16px">
			<b>Info: This panel can be used to grant access to specific Naukri Learning Leads to clients.</b>
		</p>
		<br>
		<form autocomplete="off" action="saveNaukriLeadsSubscription" id="saveSubscription" method="POST">
			<div style="padding-bottom:15px;">
				<table class="subscription-table" style="width: 100%;">
					<tbody>
						<tr>
							<td class="l-width">
								<label class="label-text">*Enter Client ID: </label>
							</td>
							<td>
								<input type="text" id="clientId" name="clientId" style="width:150px;" value="" placeholder="Client Id"/>
								<div id="clientId_error" class="errorMsg" style="display:none;">&nbsp;</div>
							</td>
						</tr>

						<tr>
							<td class="l-width">
								<label class="label-text">Select State:</label>
								<label class="label-text" style="font-size: 12px">(By default, all states are selected)</label>
							</td>
							<td>
      							<div class="ul-block">
          							<ul>
									<?php
					                  	foreach($statesList as $stateId => $stateName) {
              						?>
										<li>
                      						<div class="custom-check-box">
                          						<input type="checkbox" name="states[]" id="<?php echo $stateId;?>" value="<?php echo $stateId;?>" />
                          						<label for="<?php echo $stateId;?>"><?php echo $stateName;?></label>
                      						</div>
                  						</li>
				                  	<?php
				                  		}                      
				                  	?>
									</ul>
      							</div>
							</td> 
						</tr>

						<tr>
							<td class="l-width">
								<label class="label-text">*Campaign Type:</label>
							</td>
							<td>
								<div class="cms-fields" style="margin-top:4px;">
									<input type="radio" name="campaignType" class="subscriptionQuantityFilter" required="true" value="duration" checked=""> Duration Based
                            		<input type="radio" name="campaignType" class="subscriptionQuantityFilter" required="true" value="quantity"> Quantity Based
                        		</div>
							</td>
						</tr>

						<tr class="durationBased">
							<td class="l-width">
								<label class="label-text">*Duration:</label>
								<label class="label-text" style="font-size: 12px">(You may select past or future dates as per your campaign requirements.)</label>
							</td>
							<td>
								<div style="display:inline-block;vertical-align:top">
				                    <div class="to-col">
				                        <label>*From:</label>
				                        <input type="text" class="txt-ip cal-in" placeholder="dd/mm/yyyy" readonly="readonly" id="timeRangeDurationFrom" name="timeRangeDurationFrom" />
				                        <img src="/public/images/cal-icn.gif" id="timeRangeFromImage"/>
				                    </div>
				                    <div class="to-col">
				                        <label >*To:</label>
				                        <input type="text" class="txt-ip cal-in" placeholder="dd/mm/yyyy" readonly="readonly" id="timeRangeDurationTo" name="timeRangeDurationTo" />
				                        <img src="/public/images/cal-icn.gif" id="timeRangeToImage" />
				                    </div>
				                    <div id="timeRangeDuration_error" class="errorMsg" style="display:none;">&nbsp;</div>
				                </div>
							</td>
						</tr>

						<tr class="quantityBased" style="display: none">
							<td class="l-width">
								<label class="label-text">*Specify Number Of Leads To Deliver:</label>
							</td>
							<td>
								<input type="text" id="quantityExpected" name="quantityExpected" style="width:150px;" value="" placeholder="" maxlength="6" />
								<div id="quantityExpected_error" class="errorMsg" style="display:none;">&nbsp;</div>
							</td>
						</tr>

						<tr class="quantityBased" style="display: none">
							<td class="l-width">
								<label class="label-text">*Responses Creation Date:</label>
								<label class="label-text" style="font-size: 12px">(Specify the starting date from which leads should be given. You may select past or future dates.)</label>
							</td>
							<td>
								<div class="to-col">
			                        <label>Starting:</label>
			                        <input type="text" class="txt-ip cal-in" placeholder="dd/mm/yyyy" readonly="readonly" id="creationDateFrom" name="creationDateFrom"/>
			                        <img src="/public/images/cal-icn.gif" id="creationDateFromImage"/>
			                        <div id="creationDateFrom_error" class="errorMsg" style="display:none;">&nbsp;</div>
			                    </div>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<label><b>You can also set-up delivery of these responses to specific Email ID & Mobile no. (optional)</b>	</label>
								
							</td>
						</tr>
						<tr>
							<td class="l-width">
								<label class="label-text">Email ID:</label>
							</td>
							<td>
								<input type="text" name="email" id="email" style="width:200px;" value="" placeholder="Email Id" maxlength="125" />
								<div id="email_error" class="errorMsg" style="display:none;">&nbsp;</div>
							</td>
						</tr>

						<tr>
							<td class="l-width">
								<label class="label-text">Mobile No:</label>
							</td>
							<td>
								<input type="text" name="mobileNo" id="mobileNo" style="width:150px;" value="" placeholder="Mobile No" maxlength="10" minlength="10" />
								<div id="mobileNo_error" class="errorMsg" style="display:none;">&nbsp;</div>
							</td>
						</tr>

						<tr>
							<td>
							</td>
							<td>
								<div style="margin-top:15px;">
									<a href="javascript:void(0);" class="orange-button" id="saveNLSubscriptionData" style=" color: #ffffff;">Give Access Now</a>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

			</div>				
		</form>
	</div>
</div>

<?php 
$this->load->view('enterprise/footer'); ?>

<script>
	//binding form elements
	var examResponseAccessBinder = {};
	examResponseAccessBinder     = new examResponseAccess();
	examResponseAccessBinder.bindOnloadElements();
</script>
