<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<?php
	echo includeJSFiles('jQuery-v-1.7');
?>
<script>$j = $.noConflict();</script>
<!--Code Starts form here-->
<script src="/public/js/header.js" language="javascript"></script>
<script type="text/javascript">
	
var subscriptions = <?php echo (empty($subscriptions) ? '{}' : json_encode($subscriptions)); ?>;

function testPositiveInt(number) {
	var intRegex = /^\d+$/;
	if(number > 0 && intRegex.test(number)) {
		return true;
	}
	else {
		return false;
	}
}

function checkDailyLimit(dailyLimits){
	
		if (isNaN(dailyLimits) || dailyLimits < 0) {
			if (dailyLimits < 0) {
				alert("Please enter a positive value");
			}else{
				alert("Please enter a valid number");	
			}
		}	
}

function getClientData() {
	var clientId = $('client_id').value;
	subscriptions = {};
	$('userid').value = clientId;
	$('subscription-div').innerHTML = '<select style="width:220px"><option>Select Subscription</option></select>';
	$('subscription-result').innerHTML = '';
	$('response-div').innerHTML = '';
	$('lead-div').innerHTML = '';
	$('exam-response-div').innerHTML = '';
	$('porting_data').value = '';
	$('porting-data-div').style.display = 'none';
	$('response-div').style.display = 'none';
	$('lead-div').style.display = 'none';
	$('exam-response-div').style.display = '';
	var porting_type_val = $j("[name='response_type']:checked").val();

	if (testPositiveInt(clientId)) {
		new Ajax.Request( '/lms/Porting/getDetailsForClientId',
			{	method:'post',
				onSuccess:function(request){
					var clientData = $j.parseJSON(request.responseText);
					if(clientData) {
						subscriptions = clientData['subscriptions'];
						$('subscription-div').innerHTML = clientData['subscriptionsHtml'];
						$('response-div').innerHTML = clientData['responseData'];
						$('lead-div').innerHTML = clientData['leadGenies'];
						$('exam-response-div').innerHTML = clientData['examSubscriptionHtml'];

						if(porting_type_val =='exam_response'){
							showExamSubscriptions();
						}

					}
					else {
						alert('No Subscriptions exists for this user');
					}
				},
				onFailure: function(){ alert('Something went wrong...'); },
				evalScripts:true,
				parameters:'client_id='+clientId
			}
		);
	}
	else {
		alert("Please give valid Client ID.");
	}
}

function doSubmitPortingForm()
{
	var porting_type_val = $j("[name='response_type']:checked").val();

	if($('timerange_from') && ($('timerange_from').value == "yyyy-mm-dd" || $('timerange_from').value == "0000-00-00")) {
		$('timerange_from').value = "";
	}
	
	var formobj = $('newPortingForm');
	var formData = $j(formobj).serializeArray();
	var formStringData = $j(formobj).serialize();
	var formDataObj = {};
	$j.each(formData, function() {
		if (formDataObj[this.name] !== undefined) {
			if (!formDataObj[this.name].push) {
				formDataObj[this.name] = [formDataObj[this.name]];
			}
			formDataObj[this.name].push(this.value || '');
		} else {
			formDataObj[this.name] = this.value || '';
		}
	});
	
	var portingNameValidation = validateDisplayName(formDataObj['porting_name'], "Porting Name", 50, 1);
	if(portingNameValidation !== true) {
		alert(portingNameValidation);
		return false;
	}
	
	if(!testPositiveInt(formDataObj['userid'])) {
		alert("Please give valid Client ID.");
		return false;
	}
	
	if(porting_type_val != 'exam_response'){
		if(formDataObj['selected_subscription'] == "") {
			alert("Please select Subscription.");
			return false;
		}
	}
	
	if(porting_type_val == 'exam_response'){
		if(formDataObj['subscriptionIds[]'] == undefined){
			alert('Please select valid exam subscriptions');
			return false;
		}
		
	}
	
	if(formDataObj['porting_data'] == "") {
		alert("Please select what you want to port.");
		return false;
	}
	else if(formDataObj['porting_data'] == "response") {
		if((formDataObj['institute_id[]'] == undefined || formDataObj['university_id[]'] == undefined) && formDataObj['course_id[]'] == undefined) {
			alert("Please select atleast one Institute/University or Course you want to port.");
			return false;
		}
		if(formDataObj['response_types[]'] == undefined) {
			alert("Please select atleast one Response type you want to port.");
			return false;
		}
	}
	else if(formDataObj['porting_data'] == "lead" || formDataObj['porting_data'] == "matched_response") {
		if(formDataObj['lead_genies[]'] == undefined) {
			alert("Please select atleast one genie you want to port.");
			return false;
		}
	}
	
	var fields = document.getElementById('porting_method');
	var selectedField = fields.options[fields.selectedIndex].text;
	
	if(selectedField != "Email" && validateURLNew(formDataObj['porting_url']) !== true) {
		alert("Please enter valid Porting URL.");
		return false;
	}
	
	var jsonDataFormat = $j('#jsonDataFormat').is(":checked");
	var jsonFormatVal = $j('#jsonFormat').val();
	if(jsonDataFormat && jsonFormatVal != '') {
		try{
			JSON.parse(jsonFormatVal);
		} catch(err) {
			alert('JSON Regular Expression is incorrect (Boolean value might be there)');
			return false;
		}		
	}

	var fieldNames = [];
	var fieldKeys = [];
	for(var i=1; i<formDataObj['var_name[]'].length; i++) {
		if(formDataObj['var_name[]'][i] != "") {
			fieldNames.push(formDataObj['var_name[]'][i]);
			fieldKeys.push(formDataObj['var_key[]'][i]);
		}
	}
	
	if(fieldNames.length == 0) {
		alert("Please give atleast one field mapping.");
		return false;
	}
	
	if(selectedField == "Email") {
		for(var i=0; i<fieldNames.length; i++) {
			if((fieldKeys[i] == 12 || fieldKeys[i] == 13) && validateEmail(fieldNames[i]) !== true) {
				alert("Please enter valid Email Id.");
				return false;
			}
		}
	} 
	var dailyLimits = $('porting_DailyLimits').value;
	if (isNaN(dailyLimits) || dailyLimits < 0) {
		if (dailyLimits < 0) {
			alert("Please enter a positive value in Daily Limit field");
		}else{
			alert("Please enter a valid number in Daily Limit field");
		}
		return false;
	}
	
	var countTo = 0;
	if(selectedField == "Email") {
		for(var i=0; i<fieldNames.length; i++) {
			if(fieldKeys[i] == 12 && fieldNames[i] !== "") {
				countTo+=1;
			}
		}
		if (countTo == 0) {
			alert("Please enter an Email Id.");
		}
	}
	
	var url = '';
	if(formDataObj['porting_id'] == undefined) {
		url = '/lms/Porting/addPorting';
	}
	else {
		url = '/lms/Porting/updatePorting';
	}
	
	new Ajax.Request(url,
		{	method:'post',
			onSuccess:function(request){
				if(request.responseText == 'success') {
					
					window.location = '/lms/Porting/managePortings';
				}
				else {
					alert('Something went wrong...');
				}
			},
			onFailure: function(){ alert('Something went wrong...'); },
			evalScripts:true,
			parameters:formStringData
		}
	);
}

//radio button to select lead genie
function selectLeadGenieType(){
	$j('.porting_response').hide();
	$j('.porting_lead').show();
	$j(".porting_genie_response").attr("checked", false);
	$('porting_data').value='lead';
}

//radio button to select MR genie
function selectMRGenieType(){
	$j('.porting_response').show();
	$j('.porting_lead').hide();
	$j(".porting_genie_lead").attr("checked", false);
	$('porting_data').value='matched_response';
}


function select_porting_data_type()
{
	var porting_type = $j("[name='response_type']:checked").val();

	var selectedSubscriptionId = '';
	var selectedSubscription = '';

	if(porting_type != 'exam_response'){
		var selectedSubscriptionId = $('selected_subscription').value
		var selectedSubscription = subscriptions[selectedSubscriptionId];
	}
	
	if (selectedSubscription && (selectedSubscription['BaseProductId'] == subscriptions['GOLD_SL_LISTINGS_BASE_PRODUCT_ID'] || selectedSubscription['BaseProductId'] == subscriptions['GOLD_ML_LISTINGS_BASE_PRODUCT_ID']) ) {
		$('subscription-result').innerHTML = selectedSubscription['BaseProdSubCategory'] + ' valid from ' + selectedSubscription['SubscriptionStartDate'] + ' to ' + selectedSubscription['SubscriptionEndDate'];
		$('porting_data').value = 'response';
		$('porting-data-div').style.display = '';
		$('lead-div').style.display = 'none';
		$('response-div').style.display = '';
		$('exam-response-div').style.display = 'none';
		$j('.variablesList option[portingType="response"]').show();
		$j('.variablesList option[portingType="lead"]').hide();
		$j('.variablesList option[portingType="exam"]').hide();
	}
	else if (selectedSubscription && selectedSubscription['BaseProductId'] == subscriptions['LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID']) {
		$('subscription-result').innerHTML = selectedSubscription['BaseProdSubCategory'] + ' valid from ' + selectedSubscription['SubscriptionStartDate'] + ' to ' + selectedSubscription['SubscriptionEndDate'];
		$('subscription-result').innerHTML = selectedSubscription['BaseProdSubCategory'] + ' remaining quantity ' + selectedSubscription['BaseProdRemainingQuantity'];
		$('porting_data').value = 'lead';
		$('porting-data-div').style.display = '';
		$('response-div').style.display = 'none';
		$('lead-div').style.display = '';
		$('exam-response-div').style.display = 'none';
		$j('.variablesList option[portingType="lead"]').show();
		$j('.variablesList option[portingType="response"]').hide();
		$j('.variablesList option[portingType="exam"]').hide();
	}
	else if (selectedSubscription && selectedSubscription['BaseProductId'] == subscriptions['LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID']) {
		$('subscription-result').innerHTML = selectedSubscription['BaseProdSubCategory'] + ' remaining quantity ' + selectedSubscription['BaseProdRemainingQuantity'];
		$('porting_data').value = 'lead';
		$('porting-data-div').style.display = '';
		$('response-div').style.display = 'none';
		$('lead-div').style.display = '';
		$('exam-response-div').style.display = 'none';
		$j('.variablesList option[portingType="lead"]').show();
		$j('.variablesList option[portingType="response"]').hide();
		$j('.variablesList option[portingType="exam"]').hide();
	}
	else if(porting_type == 'exam_response'){
		showExamSubscriptions();

	}else{
		$('subscription-result').innerHTML = '';
		$('porting_data').value = '';
		$('porting-data-div').style.display = 'none';
	}
}

function showExamSubscriptions(){
	$('subscription-result').innerHTML = '';
	$('porting_data').value = 'examResponse';
	$('porting-data-div').style.display = '';
	$('lead-div').style.display = 'none';
	$('response-div').style.display = 'none';
	$('exam-response-div').style.display = '';
	$j('#exam-response-div').show();
	$j('.variablesList option[portingType="response"]').hide();
	$j('.variablesList option[portingType="lead"]').hide();
	$j('.variablesList option[portingType="exam"]').show();
	setTimeout(function(){
		$j('#porting_subscrptn').hide();
		$j('#port_daily_limit').hide();
		$j('#port_duration').hide();	
	},1000);
	
}
function selectExamResponsePorting(){
	$j('#porting_subscrptn').hide();
	$j('#port_daily_limit').hide();
	$j('#port_duration').hide();
	$j('#email_del').hide();

	uncheckAllChilds();
	select_porting_data_type();
}

function selectLeadResponsePorting(){
	$j('#porting_subscrptn').show();
	$j('#port_daily_limit').show();
	$j('#port_duration').show();
	$j('#email_del').show();

	uncheckAllChilds();
	$('exam-response-div').style.display = 'none';
	$j('#selected_subscription').val('-1');

}

function checkUncheckChilds1(obj, checkBoxesHolder)
{
	var checkBoxes = document.getElementById(checkBoxesHolder).getElementsByTagName("input");
	for(var i=1;i<checkBoxes.length;i++) {
		if(checkBoxes[i].checked!=obj.checked) {
			checkBoxes[i].checked = obj.checked;
		}
	}
}

function uncheckAllChilds()
{
	var checkBoxes = document.getElementById('response_type_holder_exam').getElementsByTagName("input");
	for(var i=0;i<checkBoxes.length;i++) {
		
			checkBoxes[i].checked = false;
		
	}
}

function uncheckElement1(obj ,id, holderId)
{
	var allChecked = false;
	if(!obj.checked) {
		if(document.getElementById(id).checked) {
			document.getElementById(id).checked = false;
		}
	}
	var checks =  document.getElementById(holderId).getElementsByTagName("input");
	var boxLength = checks.length;
	var chkAll = document.getElementById(id);
	for ( i=1; i < boxLength; i++ ) {
		if( checks[i].checked == true ) {
			allChecked = true;
			continue;
		}
		else {
			allChecked = false;
			break;
		}
	}
	if( allChecked == true ) {
		chkAll.checked = true;
	}
	else {
		chkAll.checked = false;
	}
}

function validateVariables(select_box_id,span_id,index)
{
	var toList = $(select_box_id);
	if (toList.value == '-1') {
		$(span_id).style['display']='';
	} else {
		$(span_id).style['display']='none';
	}
	
	var select_box_value = $(select_box_id).value;
	$('customizedValue_'+index).style.display  = "none";
	var customizedButtons = $('customizedButtons').innerHTML.trim().split(",");
	for (var i = customizedButtons.length - 1; i >= 0; i--) {
		if (customizedButtons[i] ==select_box_value){
			$('customizedValue_'+index).style.display  = "block";
		}
	}
}

function addNewFeildMapping()
{
	var liElems = $('field_mappings').getElementsByTagName('li');
	var elemId = liElems[liElems.length-1].id;
	var keys = elemId.split("_"); //field_0
	var num = parseInt(keys[1])+1;
	var template = $('mapping-sample');
	template = template.getElementsByTagName('li')[0].cloneNode(true);
	template.id = 'field_' + num;
	
	var shikshaFields_select = template.getElementsByTagName('select')[0];
	shikshaFields_select.id = shikshaFields_select.id.replace(/keyid/g,num);
	
	var shikshaFields_onchange = function() { validateVariables('var_key_'+num,'otherValue_'+num, num); };
	shikshaFields_select.onchange = shikshaFields_onchange;
	
	var inputElems = template.getElementsByTagName('input');
	for(var i=0; i < inputElems.length; i++) {
		inputElems[i].id = inputElems[i].id.replace(/keyid/g,num);
	}
	var others_span = template.getElementsByTagName('span')[0];
	others_span.id = others_span.id.replace("keyid",num);
	$('field_mappings').appendChild(template);
	$j( '#customizedValue_' + num ).click(function() {
		showCourseDetails('var_key_' + num);
	});
	
	return false;
}

function addNewEmailFieldMapping()
{
	var template = $('email_sample');
	template = template.getElementsByTagName('li')[0].cloneNode(true);
	$('porting_email').appendChild(template);
	return false;
}

function validateURLNew(value) {
	var result = false;
	if(value) {
		var regx = new RegExp();
		regx.compile("^[A-Za-z]+://[A-Za-z0-9-]+\.[A-Za-z0-9]+");
		result = regx.test(value);
	}
	return result;
}

function validateEmail(value) {
	if(value) {
		var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
		if(!filter.test(value)) {
			return false;
		}
	}
	return true;
}

function timerangeFrom()
{
	var calMain = new CalendarPopup('calendardiv');
	disDate = null;
	frmdisDate = new Date();
	isresponseViewer = 1;
	calMain.select($('timerange_from'),'timerange_from_img','yyyy-mm-dd');
	return false;
}

function showCourseDetails(select_box_id)
{
	var clientId = null;        
	if ($j('#client_id').length == 0) {
		clientId = '<?php echo $portingData['client_id'];?>';
	} else {
		clientId = $('client_id').value;
	}
	if (!trim(clientId)) {
		alert("Please give a valid Client Id");
	}
	
	var fields = document.getElementById(select_box_id);
	var selectedField = fields.options[fields.selectedIndex].text;

	$j.get('/lms/Porting/getCommonCustomizationDetails/' + clientId + '/' + selectedField, function(response) {
		showListingsOverlay(500,'auto','',response,'',400,100);
		overlayParentListings = $('Main');
		$('Main').style.display = 'block';
	});
}

function portingThroughEmail()
{
	var fields = document.getElementById('porting_method');
	var selectedField = fields.options[fields.selectedIndex].text;
	
	if (selectedField == "Email")
	{
		$('porting_email_mapping').style.display = '';
		$('porting-timeSet').style.display = '';
		$('porting_get_post').style.display = 'none';	
	}
	else
	{
		if(selectedField == "Post"){
			$j('#SOAPDataFormat').css({"display":"inline"});
		}else{
			$j('#SOAPDataFormat').css({"display":"none"});
			if($j('#SOAPDataFormat').find("[name='dataFormatType']").is(":checked") == true){
				$j('#regularDataFormat').click();	
			}
		}
		
		$('real_time_porting').checked = true;
		$('porting_email_mapping').style.display = 'none';
		$('porting_get_post').style.display = '';
		$('porting-timeSet').style.display = 'none';
	}
}

function displayKeyDiv(obj) {
	if (obj.value == "json") {
		$('JSONKeyDiv').style.display = '';
		$('jsonFormatDiv').style.display = '';
		$('xmlFormatDiv').style.display = 'none';
		$('xmlFormat').value = "";
	} else if (obj.value == "xml") {
		$('xmlFormatDiv').style.display = '';
		$('JSONKeyDiv').style.display = '';
		$('jsonFormatDiv').style.display = 'none';
	}  else if (obj.value == "soap") {
		$('xmlFormatDiv').style.display = '';
		$('JSONKeyDiv').style.display = 'none';
		$('jsonFormatDiv').style.display = 'none';
		$('jsonDataKey').value = "";
	} else { 
		$('JSONKeyDiv').style.display = 'none';
		$('xmlFormatDiv').style.display = 'none';
		$('jsonFormatDiv').style.display = 'none';
		$('xmlFormat').value = "";
		$('jsonDataKey').value = "";
	}
}
	
</script>

<?php
        $this->load->view('listing/national/widgets/listingsOverlay');
?>

<div id = "customizedButtons" style="display: none">
	<?php echo implode(',',$customizedButtonIds) ?>
</div>

<div id="lms-port-wrapper">
	<div class="page-title">
		<h2>Porting-Setup</h2>
	</div>
	<div id="lms-port-content">
	<form id="newPortingForm" autocomplete="off" onsubmit="return false;" >
		<div class="lms-section">
			<div class="form-section">
				<ul>
					<li>
						<label>Porting Name:</label>
						<div class="form-fields">
							<input type="text" name="porting_name" id="porting_name" value="<?php echo $portingData['name'];?>" style="width:200px" maxlength="50" />
							<input type="hidden" name="userid" id="userid" value="<?php echo $portingData['client_id'];?>" />
						</div>
					</li>
					<?php if(empty($portingData['client_id'])) { ?>
					<li>
						<label>Client ID:</label>
						<div class="form-fields">
							<input type="text" id="client_id" style="width:200px" /> &nbsp;
							<input type="button" value="Go" class="gray-button" onclick="getClientData();" />
						</div>
					</li>
					
					<li id="response_porting_type" >
						<div>
                        	<input  type="radio" name="response_type" onclick="selectLeadResponsePorting()" value="lead_response" checked > Lead & Responses</input>
                            <input type="radio" onclick="selectExamResponsePorting()" name="response_type" value="exam_response"> Exam Responses</input>
						</div>
                                <!--  <div class="spacer10 clearFix"></div>	 -->
					</li>


					<li id="porting_subscrptn">
						<label>Subscription:</label>
						<div class="form-fields" id="subscription-div">
							<select style="width:220px"><option>Select Subscription</option></select>
						</div>
					</li>
					<?php } else { 					
						if($portingData['type'] != 'examResponse'){
					?>
						<li>
							<label>Subscription:</label>
							<div class="form-fields" id="subscription-div">
								<?php $this->load->view('lms/subscriptionDetails'); ?>
							</div>
						</li>
						<?php }else{?>
							<input type="radio"  name="response_type" value="exam_response" checked="checked" style="display:none;" />
							<?php } ?>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php if(empty($portingData['client_id'])) { ?>
		<div class="lms-section" id="porting-data-div" style="display:none;">
			<input type="hidden" name="porting_data" id="porting_data" value="" />
			<div class="lms-section last-section" id="subscription-result" style="padding-bottom: 15px;"></div>
			<div class="lms-section last-section" id="response-div" style="display:none;"></div>
			<div class="lms-section last-section" id="lead-div" style="display:none;"></div>
			<div class="lms-section last-section" id="exam-response-div" style="display:none;"></div>
		</div>
		<?php } else { ?>
		<div class="lms-section" id="porting-data-div">
			<div class="lms-section last-section">
				<input type="hidden" name="porting_id" value="<?php echo $portingData['id']; ?>" />
				<input type="hidden" name="porting_data" id="porting_data" value="<?php echo $portingData['type']; ?>" />
				<div class="lms-section last-section" id="subscription-result" style="padding-bottom: 15px;"></div>
				<div class="lms-section last-section" id="response-div" style="display:none;"></div>				
				<div class="lms-section last-section" id="exam-response-div" style="display:none;">
					<?php echo $examSubscriptionDetails;?>
				</div>				
				<div class="lms-section last-section" id="lead-div" style="display:none;"></div>
				<?php echo $dataported; ?>
			</div>
		</div>
			<?php if($portingData['type'] == 'examResponse'){?>
			<script type="text/javascript">showExamSubscriptions();</script>
			<?php } else{ ?>
			<script type="text/javascript">select_porting_data_type();</script>
			<?php } ?>
		<?php } ?>
		<div class="lms-section last-section">
			<div class="form-section">
				<ul>
					<li>
						<label style="font-size:14px">Porting Method:</label>
						<div class="form-fields">
							<select name="porting_method" id="porting_method" style="width:150px" onchange="portingThroughEmail();">
								<option value="GET" <?php if ($portingData['request_type'] == "GET") { echo "selected";}?> >Get</option>
								<option value="POST" <?php if ($portingData['request_type'] == "POST") { echo "selected";}?> >Post</option>
								<?php if($portingData['type'] != 'examResponse'){?>
								<option id="email_del" value="EMAIL" <?php if ($portingData['request_type'] == "EMAIL") { echo "selected";}?> >Email</option>
								<?php } ?>
							</select>
						</div>
					</li>
					
					<li>
						<label>Custom Header:</label>
						<div class="form-fields">
							<input type="text" name="custom_header" id="custom_header" value="<?php echo $portingData['custom_header'];?>" style="width:290px" />
						</div>
					</li>

					<div id ="porting_get_post" <?php if ($portingData['request_type'] == "EMAIL"):?> style="display: none;" <?php else:?> style="display:block;" <?php endif;?> > 
					
					<li>
						<label>Data Format:</label>
						<div class="form-fields">
							<input type='radio' name='dataFormatType' id= "regularDataFormat" value='regular' onclick="displayKeyDiv(this);" checked='<?php if ($portingData['data_format'] == "regular") { echo "unchecked";} else { echo "checked"; } ?>' >Regular</input>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type='radio' name='dataFormatType' id="jsonDataFormat" value='json' onclick="displayKeyDiv(this);" <?php if ($portingData['data_format'] == "json") { echo "checked";} ?> >JSON</input>
							<input type='radio' name='dataFormatType' value='xml' style= 'margin-left:20px' onclick="displayKeyDiv(this);" <?php if ($portingData['data_format'] == "XML") { echo "checked";} ?> >XML</input>
							<div id = "SOAPDataFormat" style="display :<?php echo (($portingData['data_format'] == 'SOAP' || $portingData['request_type'] == "POST")?'inline':'none');?>">
							<input type='radio' name='dataFormatType'  value='soap' style= 'margin-left:20px;' onclick="displayKeyDiv(this);" <?php if ($portingData['data_format'] == "SOAP") { echo "checked";} ?> >SOAP</input>
							</div>
						</div>
					</li>
					<li>
						<label>Data Encoding:</label>
						<div class="form-fields">
							<input type="checkBox" name="dataEncode" id="dataEncode" <?php if($portingData['dataEncode']=='yes'){echo "checked";}if(empty($portingData)){echo "checked";}?> >Yes</input>
						</div>
					</li>
					
					<li id="JSONKeyDiv" style="<?php if ($portingData['data_format'] == 'json' || $portingData['data_format'] == 'XML') { echo 'display: block;'; } else { echo 'display: none;'; } ?>" >
						<label>Key Name:</label>
						<div class="form-fields">
							<input type="text" id="jsonDataKey" name="jsonDataKey" value="<?php if ($portingData['data_format'] == 'json' || $portingData['data_format'] == 'XML') { echo $portingData['data_key']; } ?>" style="width:290px" />
						</div>
					</li>
					
					<li id="jsonFormatDiv" style="<?php if ($portingData['data_format'] == 'json') { echo 'display: block;'; } else { echo 'display: none;'; }  ?>" >
						<label>JSON-Regular Expression:</label>
						<div class="form-fields">
							<textarea id="jsonFormat" name="jsonFormat" value="<?php if ($portingData['data_format'] == 'json' ) { echo htmlentities($portingData['xml_format']); } ?>" style="width:290px" /><?php if ($portingData['data_format'] == 'json') { echo htmlentities($portingData['xml_format']); } ?></textarea>
						</div>
					</li>

					<li id="xmlFormatDiv" style="<?php if ($portingData['data_format'] == 'XML' || $portingData['data_format'] == 'SOAP') { echo 'display: block;'; } else { echo 'display: none;'; }  ?>" >
						<label>XML-Format:</label>
						<div class="form-fields">
							<textarea id="xmlFormat" name="xmlFormat" value="<?php if ($portingData['data_format'] == 'XML' || $portingData['data_format'] == 'SOAP') { echo htmlentities($portingData['xml_format']); } ?>" style="width:290px" /><?php if ($portingData['data_format'] == 'XML' || $portingData['data_format'] == 'SOAP') { echo htmlentities($portingData['xml_format']); } ?></textarea>
						</div>
					</li>
					
					<li>
						<label>Porting URL:</label>
						<div class="form-fields">
							<input type="text" name="porting_url" id="porting_url" value="<?php echo $portingData['api'];?>" style="width:290px" />
						</div>
					</li>
					
					<li>
						<label>Field Mapping:</label>
						<div class="form-fields">
							<div class="mapping-section">
								<ol id="mapping-sample" style="display:none;">
									<li>
										<div class="col-1"><input type="text" maxlength="50" name="var_name[]" id="var_name_keyid" value="" /></div>
										<div class="col-1">
											<select name="var_key[]" id="var_key_keyid" class="variablesList">
												<?php
													for($i=0;$i<count($shikshaFields);$i++) {
												?>
													<option value ="<?php echo $shikshaFields[$i]['id'];?>" portingType="<?php echo $shikshaFields[$i]['portingType'];?>"><?php echo $shikshaFields[$i]['name'];?></option>
												<?php
													}
												?>
													<option value="-1" portingType="both">Others</option>
											</select>
										</div>
										<div>	
											<input type="button" class="gray-button" value="Customize" id="customizedValue_keyid" style="display: none; width: 80px;"/>
										</div>
										<span class="col-2" id="otherValue_keyid" style="<?php echo (count($shikshaFields) ? 'display:none;': ''); ?>">
											<input type="text" maxlength="250" name="temp_name[]" id="temp_name_keyid" value="" />
										</span>
									</li>
								</ol>
								<ol>
									<li>
										<div class="col-1"><strong>Client Fields</strong></div>
										<div class="col-1"><strong>Shiksha Fields</strong></div>
									</li>
								</ol>
								<ol id="field_mappings">
									<?php
									if (count($clientFields) > 0 && $portingData['request_type'] !== "EMAIL") {
										for($j=0; $j < count($clientFields);$j++) {
									?>
									<li id="field_<?php echo $j; ?>">
										<div class="col-1"><input type="text" maxlength="50" name="var_name[]" id="var_name_<?php echo $j; ?>" value="<?php  echo $clientFields[$j]['client_field_name'];  ?>" /></div>
										<div class="col-1">
											<select name="var_key[]" id="var_key_<?php echo $j; ?>" class="variablesList" onchange="validateVariables('var_key_<?php echo $j; ?>','otherValue_<?php echo $j; ?>',<?php echo $j;?>);">
												<?php
													for($i=0;$i<count($shikshaFields);$i++) {
												?>
													<option value ="<?php echo $shikshaFields[$i]['id'];?>" portingType="<?php echo $shikshaFields[$i]['portingType'];?>" <?php if ($clientFields[$j]['master_field_id'] == $shikshaFields[$i]['id']) { echo "selected";}?> ><?php echo $shikshaFields[$i]['name'];?></option>
												<?php
													}
												?>
													<option value="-1" portingType="both" <?php if($clientFields[$j]['master_field_id'] == -1) { echo "selected";} ?> >Others</option>
											</select>
										</div>
										<div>
											<input type="button" class="gray-button" value="Customize" id="customizedValue_<?php echo $j;?>" onclick="showCourseDetails('var_key_<?php echo $j; ?>');" style="display: none; width: 80px;"/>
										</div>
										<span class="col-2" id="otherValue_<?php echo $j;?>" style="display:none;">
											<input type="text" maxlength="250" name="temp_name[]" id="temp_name_<?php echo $j; ?>" value="<?php if($clientFields[$j]['master_field_id'] == -1) { echo $clientFields[$j]['other_value']; } ?>" />
										</span>
										<?php
											if($clientFields[$j]['master_field_id'] == -1) {
										?>
										<script>
											$('otherValue_<?php echo $j;?>').style.display = '';
										</script>
										<?php
											}
										?>
										<?php
											if (in_array($clientFields[$j]['master_field_id'],$customizedButtonIds)){
										?>
										<script>
											$('customizedValue_<?php echo $j;?>').style.display = 'block';
										</script>
										<?php
											}
										?>
									</li>
									<?php
											}
										} else {
									?>
									<li id="field_0">
										<div class="col-1"><input type="text" maxlength="50" name="var_name[]" id="var_name_0" value="" /></div>
										<div class="col-1">
											<select name="var_key[]" id="var_key_0" class="variablesList" onchange="validateVariables('var_key_0','otherValue_0',0);">
												<?php
													for($i=0;$i<count($shikshaFields);$i++) {
												?>
													<option value ="<?php echo $shikshaFields[$i]['id'];?>" portingType="<?php echo $shikshaFields[$i]['portingType'];?>"><?php echo $shikshaFields[$i]['name'];?></option>
												<?php
													}
												?>
													<option value="-1" portingType="both">Others</option>
											</select>
										</div>
										<div>
											<input type="button" class="gray-button" value="Customize" id="customizedValue_0" onclick="showCourseDetails('var_key_0')" style="display: none; width: 80px;"/>
										</div>
										<span class="col-2" id="otherValue_0" style="<?php echo (count($shikshaFields) ? 'display:none;': ''); ?>">
											<input type="text" maxlength="250" name="temp_name[]" id="temp_name_0" value="" />
										</span>
									</li>
									<?php
									}
									?>
								</ol>
								<ol>
									<li><a href="#" onclick="return addNewFeildMapping();">+ Add More</a></li>
								</ol>
								<div class="clearFix"></div>
							</div>
						</div>
					</li>
					</div>
					
					<li>
						<div class="porting-timeSet" id="porting-timeSet" <?php if($editPortedValues) {  echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?> >
							<label class="flLt">Porting Time: </label>
							<div class="form-fields">
								<div class="flLt">
									<input type="radio" value="real_time" name="porting_time" id="real_time_porting" <?php if($portingData['porting_time'] == 'real_time'){ echo "checked"; }else if(editPortedValues){ echo "checked"; }?> >Real Time
									<input type="radio" value="24_hours" name="porting_time" id="24_hours_porting" <?php if($portingData['porting_time'] == '24_hours') { echo "checked"; }?> >Every 24 hours
								</div>
							</div>
						</div>
					</li>
						
					<div id="porting_email_mapping" class="form-section" <?php if($portingData['request_type'] == "EMAIL") { echo ''; } else { echo 'style="display:none;"'; } ?>>
						<ol id="email_sample" style="display: none;">					
							<li>
								<label>To:</label>
								<div class="mapping-section" style="padding: 0px;">
									<input type="text" name="var_name[]" value="" style="width:290px" />
									<input type="hidden" name="var_key[]" value="12" style="width:290px" />
									<input type="hidden" name="temp_name[]" value="" style="width:290px" />
								</div>
								<label>CC(Optional):</label>
								<div class="mapping-section" style="padding: 0px;">
									<input type="text" name="var_name[]" value="" style="width:290px" />
									<input type="hidden" name="var_key[]" value="13" style="width:290px" />
									<input type="hidden" name="temp_name[]" value="" style="width:290px" />
								</div>
							</li>
						</ol>
						<ol id="porting_email">
							<?php
							if (count($clientFields) > 0 && $portingData['request_type'] == 'EMAIL') {
									for($j=0; $j < count($clientFields);$j++) {
							?>
							<li>
								<label>To:</label>
								<div class="mapping-section" style="padding: 0px;">
									<input type="text" name="var_name[]" value="<?php if(($clientFields[$j]['master_field_id']) == 12) { echo $clientFields[$j]['client_field_name']; }?>" style="width:290px" />
									<input type="hidden" name="var_key[]" value="12" style="width:290px" />
									<input type="hidden" name="temp_name[]" value="" style="width:290px" />
								</div>
								<label>CC(Optional):</label>
								<div class="mapping-section" style="padding: 0px;">
									<input type="text" name="var_name[]" value="<?php if(($clientFields[$j+1]['master_field_id']) == 13) { echo $clientFields[++$j]['client_field_name']; }?>" style="width:290px" />
									<input type="hidden" name="var_key[]" value="13" style="width:290px" />
									<input type="hidden" name="temp_name[]" value="" style="width:290px" />
								</div>
							</li>
							<?php }
							} else { ?>
							<li>
								<label>To:</label>
								<div class="mapping-section" style="padding: 0px;">
									<input type="text" name="var_name[]" value="" style="width:290px" />
									<input type="hidden" name="var_key[]" value="12" style="width:290px" />
									<input type="hidden" name="temp_name[]" value="" style="width:290px" />
								</div>
								<label>CC(Optional):</label>
								<div class="mapping-section" style="padding: 0px;">
									<input type="text" name="var_name[]" value="" style="width:290px" />
									<input type="hidden" name="var_key[]" value="13" style="width:290px" />
									<input type="hidden" name="temp_name[]" value="" style="width:290px" />
								</div>
							</li>
							<?php }
							?>
						</ol>
						<ol>
							<li>
								<div class="mapping-section">
									<a href="#" onclick="return addNewEmailFieldMapping();">+ Add More</a>
								</div>
								<div class="clearFix"></div>
							</li>
						</ol>
					</div>
					
					<!-- id of the element changed to remove text box -->
					<li id="port_daily_limit_removed" style="display: none">
						<div class="porting-limits">
							<label class="flLt">Daily Limit: </label>
							<div class="form-fields">
								<div class="flLt">
									<input type="text" style="width:75px; color:#888a89"  name="porting_DailyLimits" id="porting_DailyLimits" onblur="checkDailyLimit(this.value)" <?php if($editPortedValues){ echo 'value='.$portingData['Daily_Limit']; } ?> >
								</div>
							</div>
						</div>
					</li>
					
					<li id="port_duration">
						<label>Duration: </label>
						<div class="form-fields">
							<input type='radio' name='porting_duration' value='fresh' checked='<?php if ($portingData['duration'] !== "fresh") { echo "unchecked";} else { echo "checked"; } ?>' >Fresh</input>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type='radio' name='porting_duration' value='old' <?php if ($portingData['duration'] == "old") { echo "checked";} ?> >Old</input>
						</div>
					</li>
					
					<?php if($portingData['isrun_firsttime'] != 'yes') { ?>
					<li>
						<div class="porting-duration">
							<input type="hidden" name="porting_first_time" value="1" />
							<label class="flLt">Also send data from:</label>
							<div class="form-fields">
								<div class="flLt">
									<input type="text" style="width:75px; color:#888a89" value="<?php echo (!empty($portingData['firsttime_startdate']) ? $portingData['firsttime_startdate'] : 'yyyy-mm-dd'); ?>" readonly="true" name="timerange_from" id="timerange_from">&nbsp;&nbsp; <img style="cursor:pointer;" src="/public/images/calen-icn.gif" id="timerange_from_img" onclick="timerangeFrom();">
								</div>
							</div>
						</div>
					</li>
					<?php } ?>
					<li>
						<div class="porting-duration">
							<div class="form-fields">
								<input type="button" class="orange-button" value="Save" onClick="doSubmitPortingForm();" />
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="clearFix"></div>
	</form>
	</div>
</div>
<div id="responseFormNew" style="display:none"></div>
<!--Code Ends here-->
<?php $this->load->view('common/footer');?>
<script>
	if(typeof(COOKIEDOMAIN) == 'undefined'){
		var COOKIEDOMAIN = cookieDomain;	
	}
	
	if($('porting_method').value != 'EMAIL'){
		$('porting-timeSet').style.display = 'none';
	}	
	//$j(".porting_genie_response").attr("checked", false);
	//$j(".porting_genie_lead").attr("checked", false);
	<?php if(!$editPortedValues){ ?>
		if($('radioDiv') && $('main-leads-porting')){
			$('radioDiv').style.display = 'none';
			$('main-leads-porting').style.display = 'none';
		}
	<?php  } ?>
	
	<?php if($portingData['type'] == 'matched_response' && $editPortedValues == 1){ ?>
	selectMRGenieType();
	<?php }else if($portingData['type'] == 'lead' && $editPortedValues == 1){?>
	selectLeadGenieType();
	<?php } ?>
	
</script>
<style>
.cross-icon{background:url(/public/images/management-sprite.png) no-repeat;background-position:-323px 0; width:19px; height:16px; top:5px; right:-4px};
</style>
