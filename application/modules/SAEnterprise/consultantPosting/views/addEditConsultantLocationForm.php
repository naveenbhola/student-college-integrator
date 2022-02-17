<?php
    /*
     * Note :: this same file is rendered for both add & edit.
     * Any kind of distinction can be made using $formName (addConsultantLocationForm / editConsultantLocationForm)
     */
    
    $consultantDropDownHtml = '<option value="" >Select Consultant</option>';
    foreach ($consultantList as $consultantElem)
    {
        $selectedConsultant      = '';
        if($consultantElem['consultantId'] == $requestedConsultantId )
        {
            $selectedConsultant      = 'selected="selected"';
            $selectedConsultantName  = $consultantElem['name'];
        }
        $consultantDropDownHtml .= '<option value="'.$consultantElem['consultantId'].'" '.$selectedConsultant.'>'.$consultantElem['name'].'</option>';
    }
    // options for location city dropdown
    $consultantCityDropDownHtml = '<option value="">Select City</option>';
    foreach($consultantLocationCities as $v){
        $selectedConsultantCity = '';
        if($v['cityId'] == $consultantLocationDetails['cityId'] )
        {
            $selectedConsultantCity  = 'selected="selected"';
        }
        $consultantCityDropDownHtml .= '<option value="'.$v['cityId'].'" '.$selectedConsultantCity.'>'.$v['cityName'].'</option>';
    }
    //$consultantLocationCitiesWithLocalities
    /*  we will keep a location-num custom attribute that will tell us which location are we dealing with. Each of the inputs will be array type
        i.e. name  = 'input-field[]'
        input which are itself repeatable will be like this:
        i.e. name  = 'phone-input-field[location-num][]' where locationnum is the same custom attribute
    */
    $locationNum = 0;
    $fieldNum = 0; // used for giving unique ids 
    $locNumCustomAttr = ' location-num="'.$locationNum.'" ';
    
?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
    
    <?php
        $displayData["breadCrumb"] 	= array(array("text" => "All Consultants", "url" => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE ),
                                                array("text" => ($formName=="addConsultantLocationForm"?"Add New":"Edit")." Location", "url" => "") );
        $displayData["pageTitle"]  	= ($formName=="addConsultantLocationForm"?"Add New":"Edit")." Consultant Location";
        
        if($formName != "addConsultantLocationForm"){
            $displayData["lastUpdatedInfo"] = array("title"    => "Last modified",
                                                    "date"     => $consultantLocationDetails['modifiedAt'],
                                                    "username" => $consultantLocationDetails['modifiedBy']);
        }
        // load the title section
        $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>
    <?php if($selectedConsultantName!= ''){ ?>
    <strong style="font-size:14px; margin:0 0 8px 10px; display:block"><?=($formName=="addConsultantLocationForm"?"Add New":"Edit")?> Location of <?=(htmlentities($selectedConsultantName))?></strong>
    <?php } ?>
    <form id ="form_<?=$formName?>" name="<?=$formName?>" action="<?=ENT_SA_CMS_CONSULTANT_PATH?>saveConsultantLocationFormData" method="POST" enctype="multipart/form-data">
        <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <h3 class="section-title non-collapsible" style="cursor:pointer;"></i>New Location Details</h3>
                <div class="cms-form-wrap" style="margin-bottom:0;">
                    <ul>
                        <li>
                            <label>Consultant Name * : </label>
                            <div class="cms-fields">
                                    <select class="universal-select cms-field" name = "consultantId" id = "consultantId_<?=$formName?>" caption = "Consultant" tooltip="cons_name" validationType = "select" required = "true" <?php echo ($selectedConsultantName != '' ? 'disabled="disabled"':''); ?>>
                                        <?php echo $consultantDropDownHtml; ?>
                                    </select>
                                    <div style="display: none" class="errorMsg" id="consultantId_<?=$formName?>_error"></div>
                            </div>
                        </li>
                              
                        <li>
                            <div class="add-more-sec2 clear-width">
                                <ul>
                                    <li>
                                        <label style="margin-top:10px;">Contact person name* : </label>
                                        <strong style="margin-bottom:3px; margin-left: 255px; display:block;" class="location-block-head">Consultant Location <?=($locationNum+1)?></strong>
                                        <div class="cms-fields">
                                            <input class="universal-txt-field cms-text-field" name = "contactPersonName[]" id="contactPersonName_<?=$locationNum?>_<?=$formName?>" type="text" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required = true maxlength=100 caption ="contact person name" tooltip="contact_personName" validationType = "str" value="<?=htmlspecialchars($consultantLocationDetails['contactName'])?>" <?=($locNumCustomAttr)?>/>
                                            <div style="display: none" class="errorMsg" id="contactPersonName_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Phone no * : </label>
                                        <div class="cms-fields">
                                            <?php  if($formName == 'addConsultantLocationForm'){ ?>
                                            <div class = "clonable-phone-field">
                                                <input style = "margin-bottom:5px;" class="universal-txt-field cms-text-field" name = "consultantLocationPhone[<?=($locationNum)?>][]" type="text" id = "consultantLocationPhone_<?=($locationNum)?>_<?=($fieldNum)?>_<?=$formName?>" maxlength = "10" minlength = "10" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "phone number" tooltip="phoneNo" validationType = "mobile" value="<?=($consultantLocationDetails['phone'])?>" required = "true" <?=($locNumCustomAttr)?>/>
                                                <a class="flRt remove-phone-link" href="Javascript:void(0);" onclick = "removePhoneNumberDiv(this);" style="margin-right:5px;color:#333 !important;display:none;"><i class="abroad-cms-sprite remove-icon"></i>Remove Additional Phone no</a>
                                                <div style="display: none;margin:top:-5px;" class="errorMsg" id="consultantLocationPhone_<?=($locationNum)?>_<?=($fieldNum)?>_<?=$formName?>_error"></div>
                                            </div>
                                            <a class = "flLt" href="Javascript:void(0);" onclick = "addMorePhoneNumbers(this);" style = "margin-top:5px;">[+] Add another Phone No</a>
                                            <?php } else {
                                                $phones = explode(',',$consultantLocationDetails['phones']);
                                                foreach($phones as $k=>$v){ ?>
                                            <div class = "clonable-phone-field">
                                                <input style = "margin-bottom:5px;" class="universal-txt-field cms-text-field" name = "consultantLocationPhone[<?=($locationNum)?>][]" type="text" id = "consultantLocationPhone_<?=($locationNum)?>_<?=($k)?>_<?=$formName?>" maxlength = "10" minlength = "10" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "phone number" validationType = "mobile" value="<?=(trim($v))?>" <?=($k == 0? 'required = "true"':'')?> <?=($locNumCustomAttr)?>/>
                                                <a class="flRt remove-phone-link" href="Javascript:void(0);" onclick = "removePhoneNumberDiv(this);" style="margin-right:5px;color:#333 !important;<?=($k == 0? 'display: none;':'')?>"><i class="abroad-cms-sprite remove-icon"></i>Remove Additional Phone no</a>
                                                <div style="display: none;margin:top:-5px;" class="errorMsg" id="consultantLocationPhone_<?=($locationNum)?>_<?=($k)?>_<?=$formName?>_error"></div>
                                            </div>
                                            <?php   }//end:foreach ?>
                                            <a class = "flLt" href="Javascript:void(0);" onclick = "addMorePhoneNumbers(this);" style = "margin-top:5px;">[+] Add another Phone No</a>
                                            <?php }//end: else ?>
                                        </div>
                                    </li>
                                    <!--This PRI number would be stored in DB and to be shown on website frontend but not to be used in any data processing logics-->
                                    <li>
                                        <label>Display PRI no : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                            <input class="universal-txt-field cms-text-field" name = "displayPRI[]" id = "displayPRI_<?=$locationNum?>_<?=$formName?>" type="text" maxlength = "18" caption = "Display PRI no." tooltip = "displayPRI" value="<?=($consultantLocationDetails['displayPRINumber']!=0?$consultantLocationDetails['displayPRINumber']:'')?>" <?=($locNumCustomAttr)?>>
                                        </div>
                                    </li>
                                    <!--This PRI number would be stored in DB but not to be shown on website frontend while to be used in all data processing logics-->
                                    <li>
                                        <label>Shiksha PRI no : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                            <input class="universal-txt-field cms-text-field" name = "shikshaPRI[]" id = "shikshaPRI_<?=$locationNum?>_<?=$formName?>" type="text" maxlength = "18" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "Shiksha PRI no." tooltip = ""  validationType = "numeric" value="<?=($consultantLocationDetails['shikshaPRINumber']!=0?$consultantLocationDetails['shikshaPRINumber']:'')?>" <?=($locNumCustomAttr)?>>
                                            <div style="display: none;margin:top:-5px;" class="errorMsg" id="shikshaPRI_<?=($locationNum)?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Email ID * : </label>
                                        <div class="cms-fields">
                                            <input class="universal-txt-field cms-text-field" name = "consultantLocationEmail[]" id = "consultantLocationEmail_<?=$locationNum?>_<?=$formName?>" type="text" maxlength = "500" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "Email Address" tooltip = "emailId"  validationType = "email" value="<?=($consultantLocationDetails['email'])?>" <?=($locNumCustomAttr)?> required = "true"/>
                                            <div style="display: none" class="errorMsg" id="consultantLocationEmail_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Location city * : </label>
                                        <div class="cms-fields">
                                            <select class="universal-select cms-field" name = "consultantLocationCity[]" id="consultantLocationCity_<?=$locationNum?>_<?=$formName?>" <?=($locNumCustomAttr)?> validationType = "select" required = "true" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "getConsultantLocalitiesByCity(this);showErrorMessage(this, '<?=$formName?>');" caption = "city" tooltip="city" >
                                                <?=($consultantCityDropDownHtml)?>
                                            </select>
                                            <div style="display: none" class="errorMsg" id="consultantLocationCity_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Location name * : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                            <select class="universal-select cms-field" name = "consultantLocationLocality[]" id="consultantLocationLocality_<?=$locationNum?>_<?=$formName?>" <?=($locNumCustomAttr)?> validationType = "select" required = "true" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "locality" tooltip="locality" >
                                                <option value="">Select locality</option>
                                            </select>
                                            <div style="display: none" class="errorMsg" id="consultantLocationLocality_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li class="locationRegion">
                                        <label>Location region : </label>
                                        <div class="cms-fields">
                                            <p style="margin: 6px 0 0;color: #000"></p>
                                            <input type="hidden" name="regionId[]">
                                        </div>
                                    </li>
                                    <li>
                                        <label>Location Address * : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                             <textarea class="cms-textarea" name = "consultantLocationAddress[]" id="consultantLocationAddress_<?=$locationNum?>_<?=$formName?>" <?=($locNumCustomAttr)?> validationType = "str" required = "true" maxlength = "2000" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "address" tooltip="location_address" ><?=(htmlentities($consultantLocationDetails['locationAddress']))?></textarea>
                                             <div style="display: none" class="errorMsg" id="consultantLocationAddress_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Pin Code : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                            <input class="universal-txt-field cms-text-field" name = "consultantLocationPincode[]" id="consultantLocationPincode_<?=$locationNum?>_<?=$formName?>" type="text" <?=($locNumCustomAttr)?> validationType = "numeric" tooltip="pincode" maxlength = "6" minlength = "6" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "pincode" value = "<?=($consultantLocationDetails['pincode']!= '0' ? $consultantLocationDetails['pincode']:'')?>">
                                            <div style="display: none" class="errorMsg" id="consultantLocationPincode_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Latitude * : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                             <input class="universal-txt-field cms-text-field" name = "consultantLocationLatitude[]" id="consultantLocationLatitude_<?=$locationNum?>_<?=$formName?>" value="<?=($consultantLocationDetails['latitude'])?>" type="text" <?=($locNumCustomAttr)?> required = "true" validationType = "float" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "latitude coordinates as 33.755783" tooltip="latitude" >
                                             <div style="display: none" class="errorMsg" id="consultantLocationLatitude_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Longitude * : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                             <input class="universal-txt-field cms-text-field" name = "consultantLocationLongitude[]" id="consultantLocationLongitude_<?=$locationNum?>_<?=$formName?>" value="<?=($consultantLocationDetails['longitude'])?>" type="text" <?=($locNumCustomAttr)?> required = "true" validationType = "float" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "longitude coordinates as -116.360066" tooltip="longitude" >
                                             <div style="display: none" class="errorMsg" id="consultantLocationLongitude_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Default Branch * : </label>
                                        <div style="margin-top:6px;" class="cms-fields">
                                            <input value="yes" type="radio" name = "consultantLocationDefaultBranch[0]" id="consultantLocationDefaultBranch_yes_<?=$locationNum?>_<?=$formName?>" <?=($locNumCustomAttr)?> validationType = "radio" caption = "default branch" tooltip="default_branch" required = "true" <?=($consultantLocationDetails['defaultBranch']=='yes'?'checked="checked"':'')?>> Yes
                                            <input value="no"  type="radio" name = "consultantLocationDefaultBranch[0]" id="consultantLocationDefaultBranch_no_<?=$locationNum?>_<?=$formName?>" <?=($locNumCustomAttr)?>  validationType = "radio" caption = "default branch" tooltip="default_branch" required = "true" <?=($consultantLocationDetails['defaultBranch']=='no' ?'checked="checked"':'')?>> No
                                            <div style="display: none" class="errorMsg" id="consultantLocationDefaultBranch_yes_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Contact hours & days * : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                             <textarea class="cms-textarea tinymce-textarea" name = "consultantContactHours[]" id="consultantContactHours_<?=$locationNum?>_<?=$formName?>" <?=($locNumCustomAttr)?> required = "true" validationType = "html" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "contact hours & days" tooltip="contact_hours" maxlength="100"><?=(htmlentities($consultantLocationDetails['contactHours']))?></textarea>
                                             <div style="display: none" class="errorMsg" id="consultantContactHours_<?=$locationNum?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                </ul>
                                <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeLocation(this);" style="margin-right:5px;display:none;"><i class="abroad-cms-sprite remove-icon"></i>Remove Location</a>
                            </div>
                            <?php if($formName=="addConsultantLocationForm"){ ?>
                            <a href="Javascript:void(0);" onclick = "addMoreLocations(this);" class="add-more-link">[+] Add another location</a>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </div><!-- end:: section parent(clear-width) -->
            
            <div class="clear-width">
                <div class="cms-form-wrap" style="margin:0 0 10px 0; padding-top:8px; border-top:1px solid #ccc;">
                    <ul>
                        <li>
                            <label>User Comments*: </label>
                            <div class="cms-fields">
                               <textarea class="cms-textarea" name = "consultantLocationComments" style="width:75%;" id = "consultantLocationComments_<?=$formName?>" maxlength="256" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required = true caption = "Comments" validationType = "str"></textarea>
                               <div style="display: none" class="errorMsg" id="consultantLocationComments_<?=$formName?>_error"></div>
                               <div style="display: none" class="errorMsg" id="consultantLocationOverAll_<?=$formName?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->
            
        </div><!-- end:: cms-form-wrapper -->
        <input type = "hidden" id = "createdAt" name = "createdAt" value = "<?=($consultantLocationDetails['createdAt'])?>" />
        <input type = "hidden" id = "createdBy" name = "createdBy" value = "<?=($consultantLocationDetails['createdBy'])?>" />
        <input type = "hidden" id = "modifiedBy" name = "modifiedBy" value = "<?=($userid)?>" />
        <input type = "hidden" id = "oldConsultantLocationId" name = "oldConsultantLocationId" value = "<?=($consultantLocationDetails['consultantLocationId'])?>" />
        <input type = "hidden" id = "oldDefaultBranchStatus" name = "oldDefaultBranchStatus" value = "<?=($consultantLocationDetails['defaultBranch'])?>" />
        <input type = "hidden" id = "consultantLocSaveMode" name = "consultantLocSaveMode" value="<?=$formName?>" />
        <div class="button-wrap">
                <a href="JavaScript:void(0);" onclick = "submitConsultantLocationFormData();" class="orange-btn">Save & Publish</a>
                <a href="JavaScript:void(0);" onclick = "confirmRedirection();" class="cancel-btn">Cancel</a>
        </div><!-- end:: button-wrap -->
        <?php $this->load->view('consultantLocationTable',array('selectedConsultantName'=>$selectedConsultantName)); ?>
        <div class="clearFix"></div>
    </form>
</div><!-- abroad-cms-rt-box -->

<script>
    var cityRegionData = (<?=  json_encode($consultantRegionsData)?>);
    window.onbeforeunload =confirmExit; 
    var preventOnUnload = false;
    var saveInitiated = false;  
		function confirmExit()
		{//alert(saveInitiated);
			if(preventOnUnload == false)
				return 'Any unsaved change will be lost.';
		}     
    
    function startCallback() {
        // make something useful before submit (onStart)
        //alert("Going to submit");
        return true;
    }

    function completeCallback(response) {
        saveInitiated = false;
        // check response
        var respData;
        if (response != 0) {
            respData = JSON.parse(response);
            console.log("res:::"+respData);
            if (respData == -1) {
                alert("Some error has occured.");
                preventOnUnload = false;
                return false;
            }
        }
        
        if (typeof respData != 'undefined' &&typeof respData.Fail != 'undefined') {
            preventOnUnload = true;
            //var respData = JSON.parse(response);
            //alert("All submitted"+response);
            $j("#consultantLocationOverAll_<?=$formName?>_error").html("Please scroll up & correct the fields shown with error message.").show();
            //console.log(respData.Fail);
        }
        else{
            alert("Location has been saved successfully.");
            preventOnUnload = true;
            window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE?>";
        }
    }
	
    function initFormPosting() {
                    AIM.submit(document.getElementById('form_<?=$formName?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }
    
    if(document.all) {
            document.body.onload = initFormPosting;
    } else {
            initFormPosting();
    }
    function confirmRedirection()
    {   var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
        if (choice) {
            preventOnUnload = true;
            window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE?>";
        }
        else{
            preventOnUnload = true;
        }
    }
</script>