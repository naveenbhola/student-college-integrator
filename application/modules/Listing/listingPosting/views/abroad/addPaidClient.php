<?php
$editedBy = $userid;
?>
<div class="abroad-cms-rt-box">
    <?php
        $displayData["breadCrumb"] 	= array(array("text" => "All Upgraded/Downgraded Courses", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT ),
                                                array("text" => ($paramCourseId == "" ?"Upgrade/Downgrade":"Edit Upgraded")." Course", "url" => "") );
        $displayData["pageTitle"]  	= ($paramCourseId == "" ?"Upgrade/Downgrade":"Edit Upgraded")." Course";
        /*if($formName != "addUniversityForm"){
            $displayData["lastUpdatedInfo"] = array("title"    => "Last modified",
                                                    "date"     => $univMainData['univ_last_modify_date'],
                                                    "username" => $univMainData['univ_modified_by_name']);
        }*/
        // load the title section
        $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>
    <form id ="form_<?=$formName?>" name="<?=$formName?>" action="<?=ENT_SA_CMS_PATH?>savePaidClient" method="POST" enctype="multipart/form-data">
        <div class="cms-form-wrapper clear-width">
                <h3 class="section-title">Client Details</h3>
                <div class="cms-form-wrap"> 
                    <ul>
                        <li class= "add-more-sec2">
                            <label style="width: 170px;">Course ID* : </label>
                            <div class="cms-fields" style="margin-left: 185px;">
                                <input class="universal-txt-field cms-text-field flLt" name = "course_id" id="course_id_<?=$formName?>" type="text" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required = true maxlength=10 caption ="Course ID" style="width:120px !important;position:relative;z-index:1" validationType = "numeric" value="<?=($paramCourseId)?>"/>
                                <a href="javascript:void(0);" class="edit-search-box" id ="course-search" style = "position:relative;z-index:1" onclick = "ajaxGetCourseOrClientData(this,'course','course_id_<?=$formName?>');"><i class="abroad-cms-sprite rank-search-icon"></i></a>
                                <span style="display:none;position:relative;top:7px;left:15px;" id="courseName"></span>
                                <div style="display: none;position:relative;top:7px;left:15px;" class="errorMsg" id="course_id_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li class= "add-more-sec2">
                            <label style="width: 170px;">Client ID* : </label>
                            <div class="cms-fields" style="margin-left: 185px;">
                                <input class="universal-txt-field cms-text-field flLt" name = "client_id" id="client_id_<?=$formName?>" type="text" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required = true maxlength=10 caption ="Client ID" style="width:120px !important;position:relative;z-index:1" validationType = "numeric" value=""/>
                                <a href="javascript:void(0);" class="edit-search-box" id= "client-search" style = "position:relative;z-index:1" onclick = "clearSubscriptionDetails();ajaxGetCourseOrClientData(this,'client','client_id_<?=$formName?>');"><i class="abroad-cms-sprite rank-search-icon"></i></a>
                                <span style="display:none;position:relative;top:7px;left:15px;" id="clientName"></span>
                                <div style="display: none;position:relative;top:7px;left:15px;" class="errorMsg" id="client_id_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        
                        <li class= "add-more-sec2">
                            <label style="width: 170px;">Subscription* : </label>
                            <div class="cms-fields" style="margin-left: 185px;">
                                <select class="universal-select cms-field" <?=$disabled?> name = "subscription" id = "subscription_<?=$formName?>" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');setSubscriptionDetails();" required = true caption="Subscription" validationType = "select">
                                    <option value=''>Select a subscription</option>
                                    <?php echo $subscriptionDropDownHtml; ?>
                                </select>
                                <div style="display: none;position:relative;top:7px;left:15px;" class="errorMsg" id="subscription_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li class = "add-more-sec2">
                            <label style="width: 170px;">Subscription Details : </label>
                            <div class="cms-fields" style="margin-left: 185px;" id="subscriptionDetails">
                                <table style="width:300px; margin-top: 3px;">
                                    <tr><th align="left">Start Date </th><td>:</td><td align="right"><div id="startDate">N/A</div></td></tr>
                                    <tr><th align="left">Expiry Date </th><td>:</td><td align="right"><div id="endDate">N/A</div></td></tr>
                                    <tr><th align="left">Listings Remaining</th><td>:</td><td align="right"><div id="qtyRemaining">N/A</div></td></tr>
                                </table>
                            </div>
                        </li>
                    </ul>
                </div><!-- end:: cms-form-wrap -->
        </div><!-- end:: cms-form-wrapper -->
        
        <input type = "hidden" id = "courseClientId" name = "courseClientId" value = "" />
        <input type = "hidden" id = "subscriptionId" name = "subscriptionId" value = "" />
        <input type = "hidden" id = "editedBy" name = "editedBy" value = "<?=$editedBy?>" />
        <input type = "hidden" id = "univActionType" name = "univActionType" value="<?=$formName?>" /> 
        <div class="button-wrap">
                <a href="JavaScript:void(0);" onclick = "submitPaidClientData('<?=$formName?>');" class="orange-btn">Submit</a>
                <a href="JavaScript:void(0);" onclick = "confirmRedirection();" class="cancel-btn">Cancel</a>
        </div><!-- end:: button-wrap -->
        
        <div class="clearFix"></div>
    </form>
</div><!-- abroad-cms-rt-box -->
<script>
    var preventResubmit = false;
    var allowChangeCourse = true;
    function confirmRedirection()
    {   var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
        if (choice) {
            //window.onbeforeunload = null;
            window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT?>";
        }
    }
    function setSubscriptionDetails() {
        var details = getSubscriptionDetails(subscription_<?=$formName?>.value);
        if (details) {
            document.getElementById("startDate").innerHTML = details[0];
            document.getElementById("endDate").innerHTML = details[1];
            document.getElementById("qtyRemaining").innerHTML = details[2];
        }else{
            document.getElementById("startDate").innerHTML = "N/A";
            document.getElementById("endDate").innerHTML = "N/A";
            document.getElementById("qtyRemaining").innerHTML = "N/A";
        }
    }
    
    function clearSubscriptionDetails() {
        if (document.getElementById('client_id_<?=$formName?>').getAttribute('disabled')=="disabled") {
            document.getElementById("startDate").innerHTML = "N/A";
            document.getElementById("endDate").innerHTML = "N/A";
            document.getElementById("qtyRemaining").innerHTML = "N/A";
        }
        
    }
</script>