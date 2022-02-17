<?php
    /*
     * Note :: this same file is rendered for both add & edit.
     * Any kind of distinction can be made using $formName (ENT_SA_FORM_ASSIGN_REGION = assignRegionForm / ENT_SA_FORM_EDIT_ASSIGNED_REGION = editRegionForm)
     */
    //echo "me".$formName;
    $consultantSubscriptionData = reset($consultantSubscriptionData);
    //_p($consultantSubscriptionData);
    $tableDataArray = array();
    $tableDataArray['data'] = $consultantBottomTableData;
    $tableDataArray['consultantId'] = $requestedConsultantId;
    $consultantDropDownHtml = '<option value="" >Select Consultant</option>';
    foreach ($consultantList as $consultantElem)
    {
        $selectedConsultant      = '';
        if($consultantElem['consultantId'] == $requestedConsultantId )
        {
            $selectedConsultant      = 'selected="selected"';
            $selectedConsultantName  = $consultantElem['name'];
            $tableDataArray['consultantName'] = $selectedConsultantName;
        }
        $consultantDropDownHtml .= '<option value="'.$consultantElem['consultantId'].'" '.$selectedConsultant.'>'.$consultantElem['name'].'</option>';
    }
    // university dropdown    
    $consultantUniversityListHtml = '<option value="" >Select University</option>';
    foreach ($consultantUniversityList as $consultantUniversity)
    {
        $selectedUniversity      = '';
        if($consultantUniversity['universityId'] == $requestedUniversityId )
        {
            $selectedUniversity      = 'selected="selected"';
            $selectedUniversityName  = $consultantUniversity['universityName'];
            $tableDataArray['universityName'] = $selectedUniversityName;
        }
        $consultantUniversityListHtml .= '<option value="'.$consultantUniversity['universityId'].'" '.$selectedUniversity.'>'.$consultantUniversity['universityName'].'</option>';
    }
    // options for location city dropdown
    $consultantRegionDropDownHtml = '<option value="">Select Region</option>';
    foreach($consultantLocationRegions as $v){
        $selectedConsultantCity = '';
        if($v['id'] == $requestedCityId)
        {
            $selectedConsultantCity  = 'selected="selected"';
        }
        $consultantRegionDropDownHtml .= '<option value="'.$v['id'].'" '.$selectedConsultantCity.'>'.$v['regionName'].'</option>';
    }
    // consultant Sales person
    $consultantSalesPersonListHtml = '<option value="">Select Sales Person</option>';
    foreach($consultantSalesPersons as $k => $consultantSalesPerson){
        $selectedConsultantSalesPerson = '';
        if($consultantSalesPerson['name'] == $consultantSubscriptionData['salesPersonName'] )
        {
            $selectedConsultantSalesPerson  = 'selected="selected"';
        }
        $consultantSalesPersonListHtml .= '<option value="'.$k.'" '.$selectedConsultantSalesPerson.'>'.$consultantSalesPerson['name'].'</option>';
    }
    
    $assignmentNum = 0;
    $assignNumCustomAttr = ' assignment-num="'.$assignmentNum.'" ';
    
?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
    
    <?php
        $displayData["breadCrumb"] 	= array(array("text" => "Assigned Regions", "url" => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_ASSIGNED_REGION ),
                                                array("text" => ($formName==ENT_SA_FORM_ASSIGN_REGION?"Add New":"Edit")." Location", "url" => "") );
        $displayData["pageTitle"]  	= ($formName==ENT_SA_FORM_ASSIGN_REGION?"Assign New":"Edit Assigned")." Region";
        
        if($formName != ENT_SA_FORM_ASSIGN_REGION){
            $displayData["lastUpdatedInfo"] = array("title"    => "Last modified ",
                                                    "date"     => date_format(date_create_from_format('Y-m-d H:i:s',$consultantSubscriptionData['lastModifiedAt']),'d-m-Y'),
                                                    "username" => $consultantSubscriptionData['lastModifiedBy']);
        }
        // load the title section
        $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>

    <form id ="form_<?=$formName?>" name="<?=$formName?>" action="<?=ENT_SA_CMS_CONSULTANT_PATH?>saveConsultantRegionSubscriptionData" method="POST" enctype="multipart/form-data">
        <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <h3 class="section-title non-collapsible"><?=($formName==ENT_SA_FORM_ASSIGN_REGION?"Assign city":"Edit city assigned")?> <?=($selectedConsultantName != ''? ' to '.htmlentities($selectedConsultantName):'')?> <?=($selectedUniversityName != ''? ' for '.htmlentities($selectedUniversityName):'')?></h3>
                <div class="cms-form-wrap" style="margin-bottom:0;">
                    <ul>
                        <li>
                            <label>Consultant Name * : </label>
                            <div class="cms-fields">
                                <?php if($formName == ENT_SA_FORM_EDIT_ASSIGNED_REGION){?>
                                    <input type="hidden" value="<?=$requestedConsultantId?>" name="consultantId"/>
                                <?php } ?>
                                <select class="universal-select cms-field" name = "<?php if($formName != ENT_SA_FORM_EDIT_ASSIGNED_REGION){?>consultantId<?php } ?>" id = "consultantId_<?=$formName?>" caption = "Consultant" tooltip="cons_name" validationType = "select" required = "true" onchange="if(getUniversitiesMappedToConsultant()) return false;" <?php echo ($selectedConsultantName != '' ? 'disabled="disabled"':''); ?>>
                                    <?php echo $consultantDropDownHtml; ?>
                                </select>
                                <?php if(count($consultantUniversityList)==0 && $selectedConsultantName != '' ){?>
                                <div style="" class="errorMsg" id="consultantId_<?=$formName?>_error">Selected consultant does not have a university/student profile mapped to itself</div>
                                <?php } else { ?>
                                <div style="display: none" class="errorMsg" id="consultantId_<?=$formName?>_error"></div>
                                <?php } ?>
                            </div>
                        </li>
                              
                        <li>
                            <label>University Name * : </label>
                            <div class="cms-fields">
                                    <?php if($formName == ENT_SA_FORM_EDIT_ASSIGNED_REGION){?>
                                        <input type="hidden" value="<?=$requestedUniversityId?>" name="universityId"/>
                                    <?php } ?>
                                    <select class="universal-select cms-field" name = "<?php if($formName != ENT_SA_FORM_EDIT_ASSIGNED_REGION){?>universityId<?php } ?>" id = "universityId_<?=$formName?>" caption = "University" tooltip="univ_name" validationType = "select" required = "true" onchange = "showErrorMessage(this, '<?=$formName?>');"  <?php echo ($selectedUniversityName != '' ? 'disabled="disabled"':''); ?>>
                                        <?php echo $consultantUniversityListHtml; ?>
                                    </select>
                                    <div style="display: none" class="errorMsg" id="universityId_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        
                        <li>
                            <div class="add-more-sec2 clear-width">
                                <ul>
                                    <li>
                                        <label>Assign Region * : </label>
                                        <strong style="margin-bottom:3px;margin-left: 255px; display:block;" class="subscription-block-head">Assign Region <?=($assignmentNum+1)?></strong>
                                        <div class="cms-fields">
                                                <?php if($formName == ENT_SA_FORM_EDIT_ASSIGNED_REGION){?>
                                                    <input type="hidden" value="<?=$requestedCityId?>" name="regionId[]"/>
                                                <?php } ?>
                                                <select class="universal-select cms-field" name = "<?php if($formName != ENT_SA_FORM_EDIT_ASSIGNED_REGION){?>regionId[]<?php } ?>" id = "regionId_<?=$formName?>" caption = "Region" tooltip="assign_city" validationType = "select" required = "true" <?=($assignNumCustomAttr)?> onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" <?php if($requestedCityId){echo "disabled='disabled'";}?>>
                                                    <?php echo $consultantRegionDropDownHtml; ?>
                                                </select>
                                                <div style="display: none" class="errorMsg" id="regionId_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <label>Start date * : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                            <?php
                                            $disableStartDate = false;
                                            if($formName == ENT_SA_FORM_EDIT_ASSIGNED_REGION){    // In case of edit, we may want to change start date
                                                $curStartDate = $consultantSubscriptionData['startDate'];
                                                $curStartDate = date_create_from_format('d/m/Y',$consultantSubscriptionData['startDate']);
                                                $today = date_create_from_format('d/m/Y',date('d/m/Y'));
                                                
                                                if($curStartDate <= $today){
                                                    $disableStartDate = true;
                                                }
                                            }
                                            
                                            if($disableStartDate) { ?>
                                                <input id="disabledStartDateHiddenElement" type="hidden" name="startdate[]" value="<?=$consultantSubscriptionData['startDate']?>" />
                                            <?php } ?>
                                            <input class="universal-txt-field cms-text-field" name = "<?=$disableStartDate?"":"startdate[]"?>" id = "startdate_<?=$assignmentNum?>_<?=$formName?>" type="text" maxlength = "10" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "Start Date" tooltip = "start_date"  validationType = "str" value="<?=$consultantSubscriptionData['startDate']?>" placeholder="DD/MM/YYYY" readonly <?=($assignNumCustomAttr)?> style = "width:150px !important;margin-right:5px;" required = "true" onchange="resetEndDateForCitySubscription(this);" <?=$disableStartDate?"disabled='disabled'":""?>>
                                            <?php if(!$disableStartDate){ ?>
                                                <i id="startdate_<?=$assignmentNum?>_<?=$formName?>_img" class="abroad-cms-sprite calendar-icon" name="startdate_<?=$assignmentNum?>_<?=$formName?>_img" onclick="pickStartDateForCitySubscription(this);"></i>
                                            <?php } ?>
                                            <div style="display: none;margin:top:-5px;" class="errorMsg" id="startdate_<?=($assignmentNum)?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>End date * : </label>
                                        <div class="cms-fields" style="margin-top:6px;">
                                            <input class="universal-txt-field cms-text-field" name = "enddate[]" id = "enddate_<?=$assignmentNum?>_<?=$formName?>" type="text" maxlength = "10" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "End Date" tooltip = "end_date"  validationType = "str" value="<?=$consultantSubscriptionData['endDate']?>" placeholder="DD/MM/YYYY" readonly <?=($assignNumCustomAttr)?> style = "width:150px !important;margin-right:5px;" required = "true">
                                            <i id="enddate_<?=$assignmentNum?>_<?=$formName?>_img" class="abroad-cms-sprite calendar-icon" name="enddate_<?=$assignmentNum?>_<?=$formName?>_img" onclick="pickEndDateForCitySubscription(this);"></i>
                                            <div style="display: none;margin:top:-5px;" class="errorMsg" id="enddate_<?=($assignmentNum)?>_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Sales Person Name * : </label>
                                        <div class="cms-fields">
                                                <select class="universal-select cms-field" name = "salesPerson[]" id = "salesPerson_<?=$formName?>" caption = "Sales Person" tooltip="sales_rep_name" validationType = "select" required = "true" <?=($assignNumCustomAttr)?> onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');">
                                                    <?php echo $consultantSalesPersonListHtml; ?>
                                                </select>
                                                <div style="display: none" class="errorMsg" id="salesPerson_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                </ul>
                                <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeAddedCityAssignment(this);" style="margin-right:5px;display:none;"><i class="abroad-cms-sprite remove-icon"></i>Remove Region Assignment</a>
                            </div>
                            <?php if($formName==ENT_SA_FORM_ASSIGN_REGION){ ?>
                            <a href="Javascript:void(0);" onclick = "assignMoreRegions(this);" class="add-more-link">[+] Assign another region</a>
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
                                <textarea class="cms-textarea" name = "cityAssignmentComments" style="width:75%;" id = "cityAssignmentComments_<?=$formName?>" maxlength="256" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required = true caption = "Comments" validationType = "str"></textarea>
                                <div style="display: none" class="errorMsg" id="cityAssignmentComments_<?=$formName?>_error"></div>
                                <br/><br/>
                                <div style="display: none" class="errorMsg" id="cityAssignmentOverAll_<?=$formName?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
            </div><!-- end:: section parent(clear-width) -->
            
        </div><!-- end:: cms-form-wrapper -->
        <input type = "hidden" id = "createdAt" name = "createdAt" value = "<?=($consultantSubscriptionData['createdAt'])?>" />
        <input type = "hidden" id = "createdBy" name = "createdBy" value = "<?=($consultantSubscriptionData['createdBy'])?>" />
        <input type = "hidden" id = "modifiedBy" name = "modifiedBy" value = "<?=($userid)?>" />
        <input type = "hidden" id = "subscriptionId" name ="subscriptionId" value="<?=$subscriptionId?>"/>
        <!--<input type = "hidden" id = "oldConsultantLocationId" name = "oldConsultantLocationId" value = "<?=($consultantLocationDetails['consultantLocationId'])?>" />
        <input type = "hidden" id = "oldDefaultBranchStatus" name = "oldDefaultBranchStatus" value = "<?=($consultantLocationDetails['defaultBranch'])?>" />-->
        <input type = "hidden" id = "cityAssignmentSaveMode" name = "cityAssignmentSaveMode" value="<?=$formName?>" />
        <div class="button-wrap">
                <a href="JavaScript:void(0);" onclick = "submitAssignCityFormData();" class="orange-btn">Save & Publish</a>
                <a href="JavaScript:void(0);" onclick = "confirmRedirection();" class="cancel-btn">Cancel</a>
        </div><!-- end:: button-wrap -->
        <?php
            if($selectedConsultantName != ''){
                $this->load->view('consultantCitySubscriptionTable',$tableDataArray);
            }
        ?>
        <div class="clearFix"></div>
    </form>
</div><!-- abroad-cms-rt-box -->

<script>
    var subscriptionId = '<?=$subscriptionId?>';
    window.onbeforeunload =confirmExit; 
    var preventOnUnload = false;
    var saveInitiated = false;  
		function confirmExit()
		{
			if(preventOnUnload == false){
				return 'Any unsaved change will be lost.';
			}
            //return '';
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
            //console.log("res:::"+respData);
            if (typeof(respData.error_type) != 'undefined') {
                alert(respData.error_type);
                return;
            }
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
            alert("Subscription(s) assigned successfully.");
            preventOnUnload = true;
            window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_ASSIGNED_REGION?>";
        }
        return true;
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
            window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_ASSIGNED_REGION?>";
        }
        else{
            preventOnUnload = true;
        }
    }
</script>
