<script>
   var formname = "<?php echo $formName; ?>";
</script>
<title>Upgrade/Downgrade Course</title>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','abroad_cms'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','upgradecourses'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('smart/SmartMis'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<div class="abroad-cms-head">
		<h1 class="abroad-title"> National Upgrade/Downgrade Course</h1>
		<div class="last-uploaded-detail"><br>
         *Mandatory<p></p>
	   </div>
</div>

<form id="form_addPaidClient" name="addPaidClient" action="/listing/NationalUpgradeCourses/savePaidClient" method="POST" enctype="multipart/form-data">
    <div class="cms-form-wrapper clear-width">
        <h3 class="section-title">Client Details</h3>
        <div class="cms-form-wrap"> 
	        <ul>
	            <li class="add-more-sec2">
	                <label style="width: 170px;">Course ID* : </label>
	                <div class="cms-fields" style="margin-left: 185px;">
                        <input class="universal-txt-field cms-text-field flLt" name = "course_id" id="course_id_<?=$formName?>" type="text" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" required = true maxlength=10 caption ="Course ID" style="width:120px !important;position:relative;z-index:1" validationType = "numeric" value=""/>
                        <a href="javascript:void(0);" class="edit-search-box" id ="course-search" style = "position:relative;z-index:1" onclick = "ajaxGetCourseOrClientData(this,'course','course_id_<?=$formName?>');"><i class="abroad-cms-sprite rank-search-icon"></i></a>
                        <span style="display:none;position:relative;top:7px;left:15px;" id="courseName"></span>
                        <br/>
                        <span style="display:none;position:relative;top:7px;left:15px;" id="courseEndDate"></span>
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
                        <table style="width:300px; margin-top: 3px; font:normal 12px Tahoma, Geneva, sans-serif;">
                            <tr><th align="left">Start Date </th><td>:</td><td align="right"><div id="startDate">N/A</div></td></tr>
                            <tr><th align="left">Expiry Date </th><td>:</td><td align="right"><div id="endDate">N/A</div></td></tr>
                            <tr><th align="left">Listings Remaining</th><td>:</td><td align="right"><div id="qtyRemaining">N/A</div></td></tr>
                        </table>
                    </div>
                </li>
	       
	        </ul>
    	</div><!-- end:: cms-form-wrap -->
    </div><!-- end:: cms-form-wrapper -->
        
        <input type="hidden" id="courseClientId" name="courseClientId" value="">
        <input type="hidden" id="subscriptionId" name="subscriptionId" value="">
        <input type="hidden" id="editedBy" name="editedBy" value="<?=$editedBy?>">
        <div class="button-wrap">
                <a href="JavaScript:void(0);" onclick="submitPaidClientData('addPaidClient');" class="orange-btn">Submit</a>
                <a href="JavaScript:void(0);" onclick="confirmRedirection();" class="cancel-btn">Cancel</a>
        </div><!-- end:: button-wrap -->
        
        <div class="clearFix" style="margin-bottom: 25px;"></div>
    </form>

    <script>
    var preventResubmit = false;
    var allowChangeCourse = true;
    function confirmRedirection()
    {   var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
        if (choice) {
            //window.onbeforeunload = null;
            window.location.href="/listing/NationalUpgradeCourses/index";
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


<?php $this->load->view('enterprise/footer'); ?>
