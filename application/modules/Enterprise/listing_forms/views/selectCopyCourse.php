<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
	$pageTitle = "";	
	$usergroup = is_array($validateuser[0])?$validateuser[0]['usergroup']:'user';
	if(strlen($pageTitle) <=0){ ?>
    <?php if($usergroup == "cms"){ ?>
        <title>CMS Control Page</title>
    <?php } ?>
    <?php if($usergroup == "enterprise"){ ?>
        <title>Enterprise Control Page</title>
    <?php } 
    } else { ?>
        <title><?php echo $pageTitle; ?></title>
<?php } 
	$headerComponents = array(
		'css'   => array('headerCms','mainStyle'),
		'js'    => array('header','common','listing','enterprise','instituteForm'),
		'taburl' => site_url('enterprise/Enterprise'),
		'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
	);
	$this->load->view('enterprise/headerCMS', $headerComponents);
	$this->load->view('common/calendardiv');
	$suscriptionDrpDwn = "<option value=\"\">Select Subscription</option>";
	foreach($SubscriptionArray as $SubscriptionId => $temp){
		if($temp['BaseProdCategory']=="Listing"){
		  $suscriptionDrpDwn .= "<option value=\"$SubscriptionId\">".$temp['BaseProdCategory']." ".$temp['BaseProdSubCategory']."</option>";
		}
	}
	$noOfLiveSubscription = count($SubscriptionArray);
?>
</head>
<style>
   .gBtm{background:url(/public/images/entGoBtn.gif);border:0 none;width:36px;height:20px;color:#fff}
</style>

<body>
<!-- ToDo:- add a view for configuration vars like packSelection etc. -->
<div><?php $this->load->view('enterprise/cmsTabs'); ?></div>
<div class="mar_full_10p Fnt16 bld"><span>Course Title : </span><span class="fcOrg"><?php echo $listingTitle; ?></span></div>
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p Fnt14 bld"><span>Institute Title : </span><span class="fcOrg"><?php echo $instituteName." ".$listingLocation[0]['city_name']; ?></span></div>
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p">
	<div>
           <div class="float_L lineSpace_22 bld">How many copies to be made &nbsp; &nbsp;</div>
           <div class="float_L">
			<select name="noOfCopiesComboBox" id="noOfCopiesComboBox" style="padding:1px">
				<?php for($i=1;$i<=$noOfCopies;$i++){
							echo "<option value=\"$i\">$i</option>";
					}	
				?>
			</select>
		</div>
                <div class="float_L" style="margin:2px 0 0 5px"><input type="button" class="gBtm bld" name="noOfCopiesGo" id="noOfCopiesGo" onClick="showCopyListingForms();" value="GO"></div>
		<div class="clear_L">&nbsp;</div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="bdr4">&nbsp;</div>	
	<div class="lineSpace_10">&nbsp;</div>
        <div class="bld">Creating <span id="copyListingNumber">1 copy</span> of the course.</div>
	<div id="copyListingFormId_error" class="errorMsg" style="margin:10px 0px;"></div>
	<form method="post" onsubmit="return validateCopyListingForm(this);" action="/enterprise/ShowForms/submitCopyCourse" id="copyListingFormId">
		<?php for($i=1;$i<=$noOfCopies;$i++){ ?>
			<div id="copyListingForm<?php echo $i; ?>" style="margin-top:20px;<?php if($i != 1){ echo 'display:none;'; } ?>">
				<fieldset><legend><span class="bld"> Copy <?php echo $i; ?> </span></legend>
				<?php if($ownerUserGroup == 'cms'){ ?>	
				<div class="float_L" style="width:290px;margin-right:15px;">
					<div>
						Institute Id : <input type="text" name="instituteIdForCopyCourse<?php echo $i; ?>" id="instituteIdForCopyCourse<?php echo $i; ?>" value="" validate="validateInteger" required="true" minlength="1" maxlength="8" caption="Institute Id" style="width:150px;" />
						&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="javascript:populateInstitute($('instituteId<?php echo $i; ?>'),$('instituteIdForCopyCourse<?php echo $i; ?>').value);">populate</a>
					</div>
					<div class="float_L" style="display:none;padding-left:64px">
						<div id="instituteIdForCopyCourse<?php echo $i; ?>_error" class="errorMsg"></div>
					</div>
				</div>
				<?php } ?>
				<div class="float_L" style="width:280px;margin-right:15px;">
					<div>
						Institute :
						<?php if($ownerUserGroup == 'cms'){ ?>	
							<select id="instituteId<?php echo $i; ?>" name="instituteId<?php echo $i; ?>" validate="validateSelect" required="true" minlength="1" maxlength="8" caption="Institute" style="width:200px;"  disabled="true"><option value="">Select Institute</option></select>
						<?php }else{ ?>
							<select id="instituteId<?php echo $i; ?>" name="instituteId<?php echo $i; ?>" validate="validateInteger" required="true" minlength="1" maxlength="8" caption="Institute" style="width:200px;">			
								<option value="">Select Institute</option>
							<?php
								foreach($instituteList as $temp){
									if(array_key_exists('institute_id',$temp)){
										if($temp['institute_id'] == $instituteId){ continue; }
										$instituteName = $temp['institute_id']."-".$temp['institute_name']."-".$temp['cityName']." ".$temp['countryName'];	
							?>
										<option value="<?php echo $temp['institute_id'] ?>" title="<?php echo $instituteName; ?>"><?php echo $instituteName; ?></option>
							<?php
									}
								}
							?>
							</select>	
						<?php } ?>		
					</div>
					<div class="float_L" style="display:none;padding-left:51px">
						<div id="instituteId<?php echo $i; ?>_error" class="errorMsg"></div>
					</div>
				</div>
				<div class="float_L" style="width:320px;margin-right:15px;">
					<div>
						<?php if ($subscriptionStrict == 'true') { ?>	
							Subscription : <?php echo "<select id=\"selectedSubs$i\" name=\"selectedSubs$i\" validate=\"validateSelect\" required=\"true\"  minlength=\"1\" maxlength=\"11\" caption=\"Subscription from list.\">".$suscriptionDrpDwn."</select>"; ?>
						<?php } ?>
						<?php if($i!=1){ ?>
							<a href="javascript:void(0);" onClick="closeCopyForm('<?php echo $i; ?>');" style="margin-left:5px"><img src="/public/images/crossImg_16x14.gif" border="0" align="absmiddle" /></a>
						<?php } ?>	
					</div>
					<div class="float_L" style="display:none;padding-left:74px">
						<div id="selectedSubs<?php echo $i; ?>_error" class="errorMsg"></div>
					</div>
				</div>
				</fieldset>
				<div class="clear_L">&nbsp;</div>
			</div>
		<?php }  ?>
		<input type="hidden" name="noOfCopiesMade" id="noOfCopiesMade" value="1" />
		<input type="hidden" name="copiesCancled" id="copiesCancled" value="" />
		<input type="hidden" name="clientId" id="clientId" value="<?php echo $listngOwnerId; ?>" />
		<input type="hidden" name="courseId" id="courseId" value="<?php echo $courseId; ?>" />
		<input type="hidden" name="courseTitle" id="courseTitle" value="<?php echo $listingTitle; ?>" />
		<input type="hidden" name="courseType" id="courseType" value="<?php echo $courseType; ?>" />
		<div style="margin-top:20px;">
			<div class="float_L"><input type="Submit" name="copy_course" id="copy_course" value="Copy Course" class="stqBtn bld" /></div>
                        <div class="float_L" style="margin:1px 0 0 10px"><input type="button" name="cancel_copy_institute" id="cancel_copy_institute" class="cancelGlobal" value="Cancel" onClick="javascript:location.href='/enterprise/Enterprise/index/6'"/></div>
			<div class="clear_L">&nbsp;</div>	
		</div>
	</form>		
</div>
<div class="lineSpace_28">&nbsp;</div>
<script>
var noOfTotalCopies = Number('<?php echo $noOfCopies; ?>');
function closeCopyForm(formNumber){
	var cancelCopy = confirm ("Are you sure you want to cancel the copy number "+formNumber);
	if(cancelCopy == true){
	  $('copyListingForm'+formNumber).style.display = 'none';
	  $('noOfCopiesMade').value = Number($('noOfCopiesMade').value) - 1;
	  $('copiesCancled').value += (trim($('copiesCancled').value) == "")?formNumber:(","+formNumber);
	  selectComboBox($('noOfCopiesComboBox'),$('noOfCopiesMade').value);
	  removeRequiredAttribute(formNumber);
	  updateNoOfCopiesInfo();
	  alert("Copy Number "+formNumber+" is cancelled successfully!!");
	}
	return false;
}
function showCopyListingForms(){
	clearMessages($('noOfCopiesMade').form,true)
	$('copiesCancled').value="";
	var noOfCopies = Number($('noOfCopiesComboBox').value);
	updateNoOfCopiesInfo();
	$('noOfCopiesMade').value = noOfCopies;
	for(var i=1;i<=noOfCopies;i++){
		$('copyListingForm'+i).style.display = 'block';
		addRequiredAttribute(i);
	}
	for(var i=(noOfCopies+1);i<=noOfTotalCopies;i++){
		$('copyListingForm'+i).style.display = 'none';
		removeRequiredAttribute(i);
	}
	return;
}
function updateNoOfCopiesInfo()
{
	var noOfCopies = Number($('noOfCopiesComboBox').value);
	$('copyListingNumber').innerHTML = (noOfCopies > 1)?(noOfCopies+' copies'):(noOfCopies+' copy');
}
function validateCopyListingForm(FormObject){
	orangeButtonDisableEnableWithEffect('copy_course',true);
	var result = validateFields(FormObject);
	if(result != true){
		orangeButtonDisableEnableWithEffect('copy_course',false);
		return false;
	}
	var copiesCancled = $('copiesCancled').value;
	var noOfCopiesMade = Number($('noOfCopiesMade').value);
	var cnt = 0;
	var postData = "";
	var duplicacyCheckFlag = 0;
	for(var i=1;i<=noOfTotalCopies;i++){
		if(copiesCancled.indexOf(i) == -1){
			cnt++;
			if(cnt > noOfCopiesMade){ break; }
			postData += "instituteId"+i+"="+$('instituteId'+i).value+"&";
			var cnt1 = 0;
			for(var j=1;j<=noOfTotalCopies;j++){
				if((i == j) || (copiesCancled.indexOf(j) != -1)){ continue; }else{ cnt1++; }
				if(cnt1 > noOfCopiesMade){ break; }
				if(trim($('instituteId'+i).value) == trim($('instituteId'+j).value)){
					duplicacyCheckFlag = 1;
					break;
				}
			}
		}
	}
	
	if(duplicacyCheckFlag == 1){
		alert("All courses must belong to seperate institute.");
		orangeButtonDisableEnableWithEffect('copy_course',false);
		return false;
	}else{
		postData += "courseTitle="+encodeURIComponent($('courseTitle').value)+"&courseType="+encodeURIComponent($('courseType').value);
		performUniqueCheck(postData)
	}
	return false;
}
function removeRequiredAttribute(formNumber){
	if ($('instituteIdForCopyCourse'+formNumber)) {
		$('instituteIdForCopyCourse'+formNumber).removeAttribute('required');
	}
	$('instituteId'+formNumber).removeAttribute('required');
	if ($('selectedSubs'+formNumber)) {
		$('selectedSubs'+formNumber).removeAttribute('required');
	}
}
function addRequiredAttribute(formNumber){
	if ($('instituteIdForCopyCourse'+formNumber)) {
		$('instituteIdForCopyCourse'+formNumber).setAttribute('required','true');
	}
	$('instituteId'+formNumber).setAttribute('required','true');
	if ($('selectedSubs'+formNumber)) {
		$('selectedSubs'+formNumber).setAttribute('required','true');
	}
}
function initializeCopyInstituteForm(startVal){
	for(var i = startVal;i<=noOfTotalCopies;i++){
		removeRequiredAttribute(i);
	}
}
function getOptionObject(value1,innerText,title1){
  var optionObject = document.createElement('option');
  optionObject.setAttribute('value', value1);	
  optionObject.setAttribute('title', title1);	
  optionObject.innerHTML = innerText;
  return optionObject;
}
function populateInstitute(droDwnBox,instituteId){
	droDwnBox.innerHTML = "";
	var optionObj = getOptionObject("","Populating...","");
	droDwnBox.appendChild(optionObj);
	var url = '/enterprise/ShowForms/getListingInfoForCopyCourse';
	if ((trim(instituteId) == "") || (Number(instituteId) == "NaN")) {
		alert("Please enter the correct institute id.");
		return false;
	}
	instituteId = instituteId.toString();
    instituteId = escapeHTML(instituteId);
    if(instituteId===false)
    {
        return;
    }
	var data = "listingId="+instituteId+"&listingType=institute";
	new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (xmlHttp) {
		try{
					if (xmlHttp.responseText == 'false') {
						alert("You are not owner of this institute.");
						return false;
					}
					var response = eval("eval("+xmlHttp.responseText+")");
					droDwnBox.removeAttribute('disabled');
					droDwnBox.innerText = "";
					var optionObj = getOptionObject("","Select Institute","");
					droDwnBox.appendChild(optionObj);
					if (typeof(response.institute_id) !== 'undefined') {
						var optionObj = getOptionObject(instituteId,response.institute_id+'-'+response['title']+'-'+response['locations'][0].city_name+' '+response['locations'][0].country_name,response['title']);
						droDwnBox.appendChild(optionObj);
					}
				//	droDwnBox.innerHTML = optionList;
			} catch (e){}
	}});
}
function performUniqueCheck(postData){
	var url = '/enterprise/ShowForms/performUniqueCheckForCourse';
	new Ajax.Request (url,{ method:'post', parameters: (postData), onSuccess:function (xmlHttp) {
		try{
				var response = eval("eval("+xmlHttp.responseText+")");
				if(response.duplicacy === 'true'){
					$('copyListingFormId_error').innerHTML = "We already have this course on shiksha. "+response.course_name+", "+response.institute_name+" - "+response.institute_id;
					orangeButtonDisableEnableWithEffect('copy_course',false);
					return false;
				}else{
					$('copyListingFormId').submit();
				}
			} catch (e){}
                }});
}
initializeCopyInstituteForm(2);
</script>
<?php  $this->load->view('enterprise/footer'); ?>
</body>
</html>
