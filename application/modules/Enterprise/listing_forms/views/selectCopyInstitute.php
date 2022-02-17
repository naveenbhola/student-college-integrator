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
		'js'    => array('header','common','listing','enterprise','instituteForm','cityList'),
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
<div class="Fnt16 mar_full_10p bld"><span>Institute Title : </span><span class="fcOrg"><?php echo $listingTitle; ?></span></div>
<div class="mar_full_10p bld">Location : <?php echo $listingLocation[0]['city_name']; ?></div>
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p">
	<div>
			<div class="float_L lineSpace_22 bld">How many copies to be made &nbsp; &nbsp; </div>
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
	<div id="copyListingFormId_error" class="errorMsg"></div>
	<form method="post" onsubmit="try{ return validateCopyListingForm(this); }catch(e){ return false;}" action="/enterprise/ShowForms/submitCopyInstitute" id="copyListingFormId">
           <div>
              <div class="bld Fnt14">Creating <span id="copyListingNumber">1 copy</span> of the institute.</div>
	<?php for($i=1;$i<=$noOfCopies;$i++){ ?>
	<div style="margin-top:20px;<?php if($i != 1){ echo 'display:none;'; } ?>" id="copyListingForm<?php echo $i; ?>">
		<fieldset><legend><span class="bld"> Copy <?php echo $i; ?> </span></legend>
		<div class="float_L" style="width:300px;margin-right:15px;height:80px;">
			<div>
                           Name:<br /><input type="text" name="nameOfInstitute<?php echo $i; ?>" id="nameOfInstitute<?php echo $i; ?>" value="<?php echo $listingTitle; ?>" validate="validateStr" required="true" maxlength="100" minlength="10" caption="Institute title" />
			</div>
			<div>
				<div class="float_L" style="display: none;"><div id="nameOfInstitute<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>
		</div>
		<div class="float_L" style="width:300px;margin-right:15px;height:80px;">
			<div>
                           Country:<br /><select validate="validateSelect" required="true" name="country<?php echo $i; ?>" id="country<?php echo $i; ?>" minlength="1" maxlength="100" style="width:150px" onchange="getCitiesForCountry('',false,'<?php echo $i; ?>',false);">
						<?php
							foreach($country_list as $countryItem) {
								$countryId = $countryItem['countryID'];
								$countryName = $countryItem['countryName'];
								if($countryId == 1) { continue; }
									if($countryId == $contactAddress['country_id']) { $selected = 'selected'; }
								else { $selected = ''; }
						?>
							<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php
							}
						?>
					</select>
			</div>
			<div>
				<div class="float_L" style="display: none;"><div id="country<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>	
		</div>
		<div class="float_L" style="width:300px;margin-right:15px;height:80px;">
			<div>
                           City:<br /><select name="cityOfInstitute<?php echo $i; ?>" id="cities<?php echo $i; ?>" onChange="checkCity(this, 'updateInstitutes');" validate="validateSelect" required="true"  minlength="1" maxlength="100" caption="City from the list" style="width:200px"/></select>
				<script>getCitiesForCountry('',false,'<?php echo $i; ?>',false);</script>
			</div>
			<div>
				<div class="float_L" style="display: none;"><div id="cities<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>
		</div>
		<div class="float_L" style="width:300px;margin-right:15px;height:80px;">
			<div>
                           Pincode:<br /><input type="text" name="pincodeOfInstitute<?php echo $i; ?>" id="pincodeOfInstitute<?php echo $i; ?>" value="" validate="validateInteger" required="true" maxlength="6" minlength="6" caption="Pincode" />
			</div>
			<div>
				<div class="float_L" style="display: none;"><div id="pincodeOfInstitute<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>
		</div>
		<div class="float_L" style="width:300px;margin-right:15px;height:90px;">
			<div>
                           Address:<br /><textarea name="addressOfInstitute<?php echo $i; ?>" id="addressOfInstitute<?php echo $i; ?>" minlength="0" maxlength="1000" caption="address" validate="validateStr" style="width:200px"></textarea>
			</div>
			<div>
				<div class="float_L"><div id="addressOfInstitute<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>
		</div>
		 <div class="float_L" style="width:300px;margin-right:15px;height:90px;">
			<div>
                           Email:<input type="text" profanity="true" style="width:150px;" name="institute_email<?php echo $i; ?>" validate="validateEmail" maxlength="125" minlength="0" caption="Email" id="institute_email<?php echo $i; ?>" caption="Email" value="<?php echo $contact_email; ?>" />
			</div>
			<div>
				<div class="float_L"><div id="institute_email<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>
		</div> 
		<div class="float_L" style="width:300px;margin-right:15px;height:90px;">
			<div>
                           Main phone:<input profanity="true" type="text" style="width:150px" caption="Main phone" name="main_phone_number<?php echo $i; ?>"  id="main_phone_number<?php echo $i; ?>" minlength="10" maxlength="15" validate="validateext" value="" />
			</div>
			<div>
				<div class="float_L"><div id="main_phone_number<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>
		</div>
		<?php if($subscriptionStrict == 'true') { ?>
		<div class="float_L" style="width:300px;margin-right:15px;height:80px;">
			<div>
                           Subscription:<br /><?php echo "<select id=\"selectedSubs$i\" name=\"selectedSubs$i\" validate=\"validateSelect\" required=\"true\"  minlength=\"1\" maxlength=\"11\" caption=\"Subscription from the list\">".$suscriptionDrpDwn."</select>"; ?>
			</div>
			<div>
				<div class="float_L" style="display: none;"><div id="selectedSubs<?php echo $i; ?>_error" class="errorMsg"></div></div>
			</div>
		</div>
		<?php } ?>
		<?php if($i != 1) { ?>
			<div class="float_L" style="margin-left:10px;"><a href="javascript:void(0);" onClick="closeCopyForm('<?php echo $i; ?>');"><img src="/public/images/crossImg_16x14.gif" border="0" align="absmiddle" /></a></div>
		<?php } ?>
	</fieldset>
	</div>
	<?php } ?>
	<input type="hidden" name="noOfCopiesMade" id="noOfCopiesMade" value="1" />
	<input type="hidden" name="copiesCancled" id="copiesCancled" value="" />
	<input type="hidden" name="clientId" id="clientId" value="<?php echo $listngOwnerId; ?>" />
	<input type="hidden" name="instituteId" id="instituteId" value="<?php echo $instituteId; ?>" />
	<div style="margin-top:20px;">
           <div class="float_L"><input type="Submit" name="copy_institute" id="copy_institute" value="Copy Institute" class="stqBtn bld"/></div>
           <div class="float_L" style="margin:1px 0 0 10px"><input type="button" name="cancel_copy_institute" id="cancel_copy_institute" class="cancelGlobal"  value="Cancel" onClick="javascript:location.href='/enterprise/Enterprise/index/7'" /></div>
	</div>
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
	clearMessages($('noOfCopiesMade').form,true);
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
	orangeButtonDisableEnableWithEffect('copy_institute',true);
	var result = validateFields(FormObject);
	if(result != true){
		orangeButtonDisableEnableWithEffect('copy_institute',false);
		return false;
	}
	var copiesCancled = $('copiesCancled').value;
	var noOfCopiesMade = Number($('noOfCopiesMade').value);
	var cnt = 0;
	var uniqueNessCheckflag = 0;
	var postData = "";
	for(var i=1;i<=noOfTotalCopies;i++){
		if(copiesCancled.indexOf(i) == -1){
			cnt++;
			if(cnt > noOfCopiesMade){ break; }
			postData = postData + ("institute"+i+"="+encodeURIComponent($('nameOfInstitute'+i).value)+"&pincode"+i+"="+encodeURIComponent($('pincodeOfInstitute'+i).value)+"&");
			var cnt1 = 0;
			for(var j=1;j<=noOfTotalCopies;j++){
				if((i == j) || (copiesCancled.indexOf(j) != -1)){ continue; }else{ cnt1++; }
				if(cnt1 > noOfCopiesMade){ break; }
				if((trim($('nameOfInstitute'+i).value) == trim($('nameOfInstitute'+j).value)) && (Number($('pincodeOfInstitute'+i).value) == Number($('pincodeOfInstitute'+j).value))){
					var uniqueNessCheckflag = 1;
					break;
				}
			}
		}
	}
	if(uniqueNessCheckflag == 1){
		alert("All institutes to be copied must be unique");
		orangeButtonDisableEnableWithEffect('copy_institute',false);
		return false;
	}else{
		performUniqueCheck(postData.substring(0,(postData.length-1)));
	}
	return false;
}

function performUniqueCheck(postData){
	var url = '/enterprise/ShowForms/performUniqueCheckForInstitute';
	new Ajax.Request (url,{ method:'post', parameters: (postData), onSuccess:function (xmlHttp) {
		try{
				var response = eval("eval("+xmlHttp.responseText+")");
				if(response.duplicacy === 'true'){
					$('copyListingFormId_error').innerHTML = "We already have this institute on shiksha. "+response.institute_name;
					orangeButtonDisableEnableWithEffect('copy_institute',false);
					return false;
				}else{
					$('copyListingFormId').submit();
				}
			} catch (e){}
                }});
}
function removeRequiredAttribute(formNumber){	
	$('nameOfInstitute'+formNumber).removeAttribute('required');
	$('country'+formNumber).removeAttribute('required');
	$('cities'+formNumber).removeAttribute('required');
	$('pincodeOfInstitute'+formNumber).removeAttribute('required');
	//$('main_phone_number'+formNumber).removeAttribute('required');
	//$('institute_email'+formNumber).removeAttribute('required');
	if ($('selectedSubs'+formNumber)) {
		$('selectedSubs'+formNumber).removeAttribute('required');
	}	
}
function addRequiredAttribute(formNumber){
	$('nameOfInstitute'+formNumber).setAttribute('required','true');
	$('country'+formNumber).setAttribute('required','true');
	$('cities'+formNumber).setAttribute('required','true');
	$('pincodeOfInstitute'+formNumber).setAttribute('required','true');
	//$('main_phone_number'+formNumber).setAttribute('required','true');
	//$('institute_email'+formNumber).setAttribute('required','true');  
	if ($('selectedSubs'+formNumber)) {
		$('selectedSubs'+formNumber).setAttribute('required','true');
	}
}
function initializeCopyInstituteForm(startVal){
	for(var i = startVal;i<=noOfTotalCopies;i++){
		removeRequiredAttribute(i);
	}
}
initializeCopyInstituteForm(2);
</script>
<?php  $this->load->view('enterprise/footer'); ?>
</body>
</html>
