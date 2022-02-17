<?php
//$ldbObj = new LDB_client();
//code starts to get groupID for a particular tab
/*$split_array0 = explode('?',$_REQUEST['referer_url']);
$split_array1 = explode('&',$split_array0['1']);
$split_array2 = explode('=',$split_array1['0']);
$course_name = $actual_course_name;*/

/*switch($actual_course_name){
		case 'Study Abroad':
			$result = $ldbObj->getGroupForAcourse(357,'course');
		break;
		
		case 'Full Time MBA/PGDM':
			$result = $ldbObj->getGroupForAcourse(2,'course');
		break;

		case 'Distance/Correspondence MBA':
			$result = $ldbObj->getGroupForAcourse(24,'course');
		break;

		case 'IT Courses':
			$result = $ldbObj->getGroupForAcourse(162,'course');
		break;

		case 'IT Degree':
			$result = $ldbObj->getGroupForAcourse(49,'course');
		break;

		case 'Distance MCA/BCA':
			$result = $ldbObj->getGroupForAcourse(188,'course');
		break;

		case 'Animation, Multimedia Courses':
			$result = $ldbObj->getGroupForAcourse(213,'course');
		break;

		case 'Animation, Multimedia Degrees':
			$result = $ldbObj->getGroupForAcourse(190,'course');
		break;

		case 'Hospitality,Tourism Courses':
			$result = $ldbObj->getGroupForAcourse(291,'course');
		break;

		case 'Hospitality,Tourism Degrees':
			$result = $ldbObj->getGroupForAcourse(257,'course');
		break;

		case 'Science & Engineering':
			$result = $ldbObj->getGroupForAcourse(52,'course');
		break;

		case 'BBA':
			$result = $ldbObj->getGroupForAcourse(381,'course');
		break;

		case 'Test Preps':
			$result = $ldbObj->getGroupForAcourse(465,'testprep');
		break;

		case 'Clinical Research Courses':
			$result = $ldbObj->getGroupForAcourse(396,'course');
		break;

		case 'Clinical Research Degrees':
			$result = $ldbObj->getGroupForAcourse(384,'course');
		break;

		case 'Fashion Design Degrees':
			$result = $ldbObj->getGroupForAcourse(400,'course');
		break;

		case 'MASS Communications Courses':
			$result = $ldbObj->getGroupForAcourse(447,'course');
		break;

		case 'MASS Communications Degrees':
			$result = $ldbObj->getGroupForAcourse(422,'course');
		break;

		default:
			$result = $ldbObj->getGroupForAcourse(2,'course');
		break;

	}*/

	/*$hide_params = 'N';
				foreach ($coursesList as $course_name=> $details) {
					echo $search_agents_all_array["type"];
					if($details['actual_category_id'] == $category_id) {
						$hide_params = 'Y';
					
						break;
						
					}					
				}*/

$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','modal-message','searchCriteria'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','ldb_search','searchAgents','searchCriteria'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>'',
	'title' => 'Enterprise Student Search-Manage Search Agents'
        );
$this->load->view('enterprise/headerCMS', $headerComponents);

$this->load->view('enterprise/cmsTabs');
?>
<div style="width:100%">
	<div>
		<div style="margin:0 10px">
    	<div style="width:100%">
            <div id="studentresolutionSet_800">
            <script>
            if(document.body.offsetWidth<900){
                 document.getElementById('studentresolutionSet_800').style.width='994px';
            }
            </script>
            <div style="padding-bottom:11px"><b>Search Students:</b> Use the forms below to find relevant students matching your requirements</div>
<?php
	
	// $this->load->view('enterprise/searchTabs');
$this->load->view('enterprise/searchTabsNew');
	
$searchFormByCourse['flag_manage_page'] = "true";
if($flag_matched_responses == 'true'){
	$this->load->view('enterprise/searchFormMatchedResponses');
}
else{
	$this->load->view('enterprise/searchFormByCourse',$searchFormByCourse);
}
//sdump($search_agents_all_array);
//sdump($search_agents_all_display_array);
if($deliveryMethod == 'porting'){
	$this->load->view('searchAgents/updatePortingGenie');
}else{
	$this->load->view("searchAgents/updateNormalGenie");
}

?>


<script>

var deliveryMethod = '<?php echo $deliveryMethod; ?>';
function getPaginatedAgents(){
        var startOffset = document.getElementById('startOffset').value;
        var countOffset = document.getElementById('countOffset').value;
        location.replace('/searchAgents/searchAgents/openUpdateSearchAgent/'+startOffset+'/'+countOffset);
        }
	document.getElementById('studentSearchFormMain').style.display='none';
	document.getElementById('searchResultDiv').innerHTML = '';
	try {
		window.onload = function () {
			var el = document.createElement("iframe");
			el.setAttribute('id', 'ifrm_csv_download');
			el.setAttribute("height","0");
			el.setAttribute("width","0");
			el.setAttribute('src', '');
			document.body.appendChild(el);
		}
	} catch(e){
		//alert(e);
	}

function viewSMS(message){
        var overlayWidth = 500;
        var overlayHeight = 500;
	message = base64_decode(message);
        var overlayTitle = '<span style="padding-left:5px" class="Fnt12">SMS</span>';
        var overlayContent = '<div><b>Message: </b></div><br><div>'+message+'</div><div><input style="margin-top:10px;" class="fbBtn" type="button" onClick="hideOverlay()" value="Close"></div>';

        showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent);
		$j('.wrapperFxd').css("position","static");
		$j('.close').attr('href',"javascript:void(0)");
}

function viewEmail(subject,message,emailFrom){
	var overlayWidth = 500;
	var overlayHeight = 500;
	emailFrom = base64_decode(emailFrom);
	subject = base64_decode(subject);
	message = base64_decode(message);
	var overlayTitle = '<span style="padding-left:5px" class="Fnt12">Email</span>';
	var overlayContent = '<div><b>From: </b></div><br><div>'+emailFrom+'</div>';
	overlayContent += '<br><br><br><div><b>Subject:</b></div><br><div>'+subject+'</div>';
	overlayContent += '<br><br><br><div><b>Message:</b></div><br>';
	overlayContent += '<div style="height:250px;overflow:auto;">'+message+'</div>';
	overlayContent += '<div>';
	overlayContent += '<input class="fbBtn" style="margin-top:10px;" type="button" onClick="hideOverlay()" value="Close"></div>';

	showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent);
	$j('.wrapperFxd').css("position","static");
	$j('.close').attr('href',"javascript:void(0)");
}
function onLoadingThisPage(){
	if(document.getElementById('totalAgents').value!="0"){
		document.getElementById('runSearchAgentDiv').style.display="block";
	}else{
		document.getElementById('runSearchAgentDiv').style.display="none";
	}
	document.getElementById('manageLinkOrHeadingDiv').style.display="none";
	document.getElementById('manageHeadingOrLinkDiv').style.display="block";
}
onLoadingThisPage();

if(typeof(deliveryMethod) != 'undefined' && deliveryMethod == 'normal'){
	document.getElementById('manageHeadingOrLinkDiv').style.display='none';
	document.getElementById('runSearchAgentDiv').style.display='none';
}
</script>
<div style="margin-top:40px">
<div id="hack_ie_operation_aborted_error"></div>
<?php
$this->load->view('enterprise/footer');
?>

<style type="text/css">
.genie-name{
	overflow:hidden; white-space:nowrap;
}
</style>

<script type="text/javascript">
	function showMoreInfo(divId, searchagentid){
		$j('#'+divId+'Shorten_'+searchagentid).hide();
		$j('#'+divId+'_'+searchagentid).show();
	}
	var deliveryMethod = "<?php if(isset($deliveryMethod)){ echo $deliveryMethod; }else{ echo 'normal';}?>";
	var genieType = "<?php if(isset($genieType)){ echo $genieType; }else{ echo 'activated';}?>";
	var searchCriteria = "<?php if(isset($searchCriteria)){ echo $searchCriteria; }else{ echo 'created_on';}?>";

	function sortSearchAgentGenies(searchCriteria){
		window.location = '/searchAgents/searchAgents/openUpdateSearchAgent/0/10/'+deliveryMethod+'/'+genieType+'/'+searchCriteria;
	}

	function clickMyTabs(tabId){
		$(tabId).click();
	}
</script>