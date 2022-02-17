<title>Add Courses To Groups</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','mainStyle','footer','searchCriteria'),
        'js'    =>  array('common','enterprise','searchCriteria','examResponseAccess','CalendarPopup'),
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

	<?php
		$manualLDBAccessHeader = array(
								'isExamResponseAccess' => true,
								'isClientSubscription' => true
								);
		$this->load->view('examResponseAccess/manualLDBAccessHeader',$manualLDBAccessHeader);
	?>
	
	<div style="float:left;width:100%">
		<p style="font-size: 16px"> <b>Info: This panel can be used to grant access to specific Exam Page Responses to clients for a specific duration.</b></p><br>
		<form autocomplete="off" action="saveSubscription" id="saveSubscription" method="POST">
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
									<label class="label-text">*Specify Exam: </label>
								</td>
								<td>
									<input style="width: 377px;" type="text" name="examField" id="examField" class="input-txt" placeholder="Start typing Exam name ..." minlength="1" maxlength="50" required="true" caption="exam" validate="validateStr" autocomplete="off">
									
			                        <ul id="examSearchOptions" style="display:none;" class="examSearch">
			                            <?php foreach($examList as $id=>$name){?>
			                            	<li><a class="examList" examId="<?=$id?>"><?=$name?></a></li>
			                            <?php } ?>
			                        </ul>
			                        <label style="display:block;" id="examName"></label> 
			                        <input type="hidden" value=""  id="examId" name="examId">
			                        <div id="examId_error" class="errorMsg" style="display:none;"></div>
								</td>
							</tr>
							<tr id="examGroupFilters">

							</tr>
							<?php 
								$cityInput = array();
								$cityInput['cityLabel'] = 'Select Location(s) Of Responders:';
								$this->load->view('userSearchCriteria/searchWidgets/city',$cityInput); 
							?>
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
									<label class="label-text">*Specify Number Of Responses To Deliver:</label>
								</td>
								<td>
									<input type="text" id="quantityExpected" name="quantityExpected" style="width:150px;" value="" placeholder="" maxlength="6" />
									<div id="quantityExpected_error" class="errorMsg" style="display:none;">&nbsp;</div>
								</td>
							</tr>

							<tr class="quantityBased" style="display: none">
								<td class="l-width">
									<label class="label-text">*Responses Creation Date:</label>
									<label class="label-text" style="font-size: 12px">(Specify the starting date from which responses should be given. You may select past or future dates.)</label>
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
								<td>
									
								</td>

								<td>
									<div style="margin-top:15px;">
										<a href="javascript:void(0);" id="responseCount" class="orange-button" style=" color: #ffffff;">Get Response count</a>
										<label style="margin-left:20px">Response count : <span id="totalResponse">0</span></label>
									</div>
									<div style="margin-top:15px;">
										<div id="responseCount_error" class="errorMsg" style="display:none;">&nbsp;</div>
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
										<a href="javascript:void(0);" class="orange-button" id="saveSubscriptionData" style=" color: #ffffff;">Give Access Now</a>
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

	//binding serach form elements
	var searchCMSBinder = {};
    var searchCMSBinder =  new SearchCMSBinder();

    var examResponseAccessBinder = {};
	examResponseAccessBinder = new examResponseAccess();	
		
	searchCMSBinder.bindOnloadElements();
	searchCMSBinder.criteriaNo = <?php echo $criteriaNo;?>

	searchCMSBinder.virtualCitiesParentChildMapping = new  Array('<?php echo json_encode($virtualCitiesParentChildMapping);?>');
    searchCMSBinder.virtualCitiesChildParentMapping = new  Array('<?php echo json_encode($virtualCitiesChildParentMapping);?>');

    examResponseAccessBinder.bindOnloadElements();
</script>

<style type="text/css">
	.l-width{
		width: 31%;
		vertical-align:top;
	}

	.subscription-table tr td{
		padding: 10px 15px;
		font-size: 14px;
	}

	.label-text {
		display: block;
		text-align: right;
	}
		
	.examSearch {
	    list-style-type: none;
	    padding: 0;
	    margin: 0;
	    position: absolute;
	    overflow-y: auto;
	    width: 377px;
	    z-index: 1000;
	    height: auto;
	    max-height: 203px;
	}

	.examSearch li a {
	    border: 1px solid #ddd; 
	    margin-top: -1px; 
	    background-color: #f6f6f6; 
	    padding: 5px;
	    text-decoration: none;
	    font-size: 13px; 
	    color: black;
	    display: block; 
	}

	.examSearch li a:hover{
	    background-color: #eee; 
	}

	.disabled{
		pointer-events : none;
	}
</style>
