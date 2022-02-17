<title>Add Courses To Groups</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','smart'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<div class="mar_full_10p">
	<div class="lineSpace_10">&nbsp;</div>
	<div class="fontSize_14p bld">Client Course Matching Criteria</div>
    <div class="lineSpace_10">&nbsp;</div>
	<div style="float:left;width:100%">
		<div id="lms-port-content">
			<div class="form-section">
				<label> Client ID:</label>
				<div class="form-fields">
					<input type="text" style="width:200px; float:left" id="clientid" />
					<div class="spacer10 clearFix"></div>
					<input type="button" value="Go" class="gray-button" onclick="searchClientCourses()"/>
				</div>
				
				<div id="misForm-div"></div>
			</div>
			<div class="clearFix"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
function searchClientCourses() {
	var clientid = $('clientid').value;
	if (isNaN(clientid)) {
		alert("Please enter client id in numbers only.");
		return false;			
	}
	var regex=/^[0-9]+$/;
	if (!clientid.match(regex)){
		alert("Please enter client id in numbers only.");
		return false;	
		
	}
	if ((clientid.indexOf(".")) > -1) {
		alert("Decimal numbers in client id not allowed.");
		return false;		
	}
	$('misForm-div').innerHTML = '';
	if (clientid) {
		new Ajax.Request( '/enterprise/shikshaDB/clientCourseMatchingCriteriaForm',
			{	method:'post',
				onSuccess:function(request){
					if(request.responseText) {
						if (request.responseText == 'no_courses_found') {
							alert('No Courses available for this client');
						} else {
							$('misForm-div').innerHTML = request.responseText;
						}
					}
					else {
						alert('No data available for this client');
					}
				},
				onFailure: function(){ alert('Something went wrong...'); },
				evalScripts:true,
				parameters:'clientid='+clientid
			}
		);
	}
	else {
		alert("Please give valid Client ID.");
	}
}

function validateMatchingCriteria() {
	
	var course_ids = new Array();
	var i=0;
	$j("input[name='course_id[]']").each(function() {
		if (this.checked == true && this.value != -1) {
			var course_value = '';var course_id_array = '';		
			course_value = this.value;
			course_id_array = course_value.split('|');
			course_ids[i] = course_id_array[0];
			i++;
		}
		
	});
	if (course_ids.length == 0) {
		alert("Please select any course.");
		return false;
	}
	
	var qualitypercentage = $('qualitypercentage').value;

	if (!qualitypercentage) {
		alert("Please enter quality percentage for the course.");
		return false;
	} else {
		if (isNaN(qualitypercentage)) {
			alert("Please enter quality percentage in numbers only.");
			return false;			
		}
		var regex=/^[0-9]+$/;
		if (!qualitypercentage.match(regex)){
			alert("Please enter quality percentage in numbers only.");
			return false;	
			
		}
		if ((qualitypercentage.indexOf(".")) > -1) {
			alert("Decimal numbers in quality percentage not allowed.");
			return false;		
		}
		if(qualitypercentage > 100 || qualitypercentage <= 0) {
			alert("Please enter quality percentage between 1 to 100 only.");
			return false;
		}
	}
}

function getMatchedResponsesCount() {
	var result = validateMatchingCriteria();
	if (result != false) {	
		var course_ids = '';
		$j("input[name='course_id[]']").each(function() {
			if (this.checked == true && this.value != -1) {
				var course_value = '';var course_id_array = '';
				if (course_ids == '') {
					course_value = this.value;
					course_id_array = course_value.split('|');
					course_ids = course_id_array[0];
				} else {
					course_value = this.value;
					course_id_array = course_value.split('|');
					course_ids = course_ids+','+course_id_array[0];
				}
			}		
		});

		var clientid = $('clientid').value;
		var qualitypercentage = $('qualitypercentage').value;
		$('matchedresponsescount').innerHTML = 'Fetching Count. Please Wait...';
		new Ajax.Request( '/enterprise/shikshaDB/getMatchedResponsesCount',
			{	method:'post',
				onSuccess:function(request){
					if(request.responseText) {
						$('matchedresponsescount').innerHTML = 'Count: '+request.responseText;
					}
					else {
						alert('No data available for this client');
					}
				},
				onFailure: function(){ alert('Something went wrong...'); },
				evalScripts:true,
				parameters:'clientid='+clientid+'&qualitypercentage='+qualitypercentage+'&course_ids='+course_ids
			}
		);
	}

}
var instituteList = <?php echo json_encode($instituteList); ?>;	
	
function checkUncheckChilds1(obj, checkBoxesHolder)
{
	var checkBoxes = document.getElementById(checkBoxesHolder).getElementsByTagName("input");
	for(var i=0;i<checkBoxes.length;i++)
	{
		if(checkBoxes[i].checked!=obj.checked)
		{
			checkBoxes[i].checked = obj.checked;
		}
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
	for ( i=0; i < boxLength; i++ )
	{
		if (checks[i].parentNode.style.display == 'none' || checks[i].parentNode.parentNode.style.display == 'none' || checks[i].parentNode.parentNode.parentNode.style.display == 'none') {
			continue;
		}
		else {
			if ( checks[i].checked == true ) {
				allChecked = true;
				continue;
			}
			else {
				allChecked = false;
				break;
			}
		}
	}
	if ( allChecked == true )
		chkAll.checked = true;
	else
		chkAll.checked = false;
}

function displayMappedCourses(instituteId, id, holderId)
{
	var displayAll = false;
	var allCoursesElement = document.getElementById('all_holder');
	var allInstitutesElement = document.getElementById('all_institutes');
	var inputChks =  document.getElementById(holderId).getElementsByTagName("input");
	var boxLength = inputChks.length;
		
	if (instituteId == '-1') {
		var allDivs = document.getElementById("institute_courses_holder").getElementsByTagName("div");
		var NoOfDivs = allDivs.length;
		var allInputs = document.getElementById("institute_courses_holder").getElementsByTagName("input");
		var NoOfInputs = allInputs.length;
		
		for ( i=0; i < NoOfInputs; i++ ) {
			allInputs[i].checked = false;
		}
		if (allInstitutesElement.checked == true) {
			document.getElementById("all_courses").checked = false;
			for ( i=0; i < NoOfDivs; i++ ) {
				allDivs[i].style.display = 'block';
			}
			allCoursesElement.style.display = 'block';
		}
		else{
			for ( i=0; i < NoOfDivs; i++ ) {
				allDivs[i].style.display = 'none';
			}
			allCoursesElement.style.display = 'none';
		}
	}
	else {
		var instituteElement = document.getElementById(instituteId+'_institute');
		var holderElement = document.getElementById(instituteId+'_institute_holder');
		var allInputElements = holderElement.getElementsByTagName("input");
		var inputNumber = allInputElements.length;
		var allDivElements = holderElement.getElementsByTagName("div");
		var divNumber = allDivElements.length;
		
		for ( i=0; i < inputNumber; i++ ) {
			allInputElements[i].checked = false;
		}
			
		if (instituteElement.checked == true) {
			document.getElementById("all_courses").checked = false;
			holderElement.style.display = 'block';
			for ( i=0; i < divNumber; i++ ) {
				allDivElements[i].style.display = 'block';
			}
		}
		else{
			holderElement.style.display = 'none';
			for ( i=0; i < divNumber; i++ ) {
				allDivElements[i].style.display = 'none';
			}
		}
		for ( i=0; i < boxLength; i++ ) {
			if ( inputChks[i].checked == true ) {
				displayAll = true;
				break;
			}
			else {
				displayAll = false;
				continue;
			}
		}
		if (displayAll == true) {
			allCoursesElement.style.display = 'block';
			uncheckElement1(instituteId+'_all_courses', 'all_courses', 'institute_courses_holder');
		}
		else {
			allCoursesElement.style.display = 'none';
		}
	}
}
</script>
<?php $this->load->view('enterprise/footer'); ?>