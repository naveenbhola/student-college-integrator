<script>
var isLogged = '<?php echo $userlogged; ?>';
var messageObj;
var FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
//var first_name = "<?php if(is_array($Validatelogged['0']))echo $Validatelogged['0']['firstname']?>";
//var mobile_no = "<?php if(is_array($Validatelogged['0'])) echo $Validatelogged['0']['mobile']?>";
//var email_no = "<?php  if(is_array($Validatelogged['0'])) echo $Validatelogged['0']['cookiestr']?>";
//email_no = email_no.split('|')['0'];
</script>
 <!-- flagfirsttime hidden field updated after form submitting -->
    <input type = "hidden" name = "flagfirsttime" id = "flagfirsttime" value =
""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value =
""/>
    <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName" value =
""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList" value =
""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName"
value = ""/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg" value =
""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg" value =
""/>
    <!-- required field .. identify between update or insert -->
    <input type = "hidden" name = "mupdateflag" id = "mupdateflag" value ="insert"/>
    <!-- required filed .. identify pagename -->
<input type = "hidden" name = "marketingpagename" id = "marketingpagename" value = "homepage"/>
<input type="hidden" name="categoryId" id="categoryId" value=""/>
<input type="hidden" name="subCategoryId" id="subCategoryId" value=""/>
<input type="hidden" name="desiredCourse" id="desiredCourse" value=""/>
	<ul class="find-inst-form">
    	<li><select name="board_id" id="fieldOfInterest"  validate="validateSelect" required="true" caption="desired education of interest" onchange="populateDesiredCourseCombo()">
                    <option value="">Education Interest</option>
                    <?php
                    foreach($categories as $categoryId => $categoryName) {
                        if($categoryId !=14) {
                        if($categoryName == 'Animation, Multimedia') {
                            echo "<option value='". $categoryId."'>". $categoryName ."</option>".
                            "<option value='-1'>Entrance Exam Preparation</option>";
                        } else {
                        echo "<option value='". $categoryId."'>". $categoryName ."</option>";
                        }
                     }
                    }
                    ?>
            </select>
            <div>
            <div class="errorMsg" id= "fieldOfInterest_error"></div>
            </div>
         </li>
         
         <li>
         	<div id="desiredCourseHome">
                <select name="" validate="validateSelect" required="true" caption="the desired course" id="homesubCategories" onChange="actionDesiredCourseDD(this.value);">
                <option value="">Desired Course</option>
                </select>
            </div>
            <div>
            <div class="errorMsg" id="<?php echo $prefix; ?>homesubCategories_error"></div>
            </div>
         </li>
     	<li id="marketing_form_html_it">
     <?php
        /* we don't have any db based identifier for PG , UG and localcources :( */
        $userdata = array();
        foreach ($data as $userdata) {
            $userdata =$userdata;
        }
        if(!empty($userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach']) &&
($logged != "No")) {
    //set Distance MBA as local, that is why below mentioned funny check is made :)
            if(($userdata['PrefData'][0]['SpecializationPref'][0]['SpecializationId']>=25 && $userdata['PrefData'][0]['SpecializationPref'][0]['SpecializationId']<=34) ||
$userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach'] == 'local') {
$this->load->view('/home/homepageRegistration/user_form_homepage_localcourses.php');
?>
            <script>
                FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
            </script>
             <?php }else if(($userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach']=='national') &&
            ($userdata['PrefData'][0]['SpecializationPref'][0]['CourseLevel1']=='PG')) {
$this->load->view('/home/homepageRegistration/user_form_homepage_ug_courses.php');
            ?>
            <script>
            //changeSelectionPreferredStudyLocation();
                FLAG_LOCAL_COURSE_FORM_SELECTION = 1;
            </script>
            <?php
            }else if(($userdata['PrefData'][0]['SpecializationPref'][0]['CourseReach']=='national') &&
            ($userdata['PrefData'][0]['SpecializationPref'][0]['CourseLevel1']=='UG')) {
                //echo "here23";
            $this->load->view('/home/homepageRegistration/user_form_homepage_pg.php');
            ?>
            <script>
            //changeSelectionPreferredStudyLocation();
                FLAG_LOCAL_COURSE_FORM_SELECTION = 2;
            </script>
            <?php
            }
} else if($logged=='No') { ?>

            <script>
                //changeSelectionPreferredStudyLocation();
                FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
                DEFAULT = true;
            </script>
<?php
    $this->load->view('/home/homepageRegistration/default_form_study_india.php');
}

else { ?>
            <script>
                //changeSelectionPreferredStudyLocation();
                FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
                DEFAULT = true;
            </script>
<?php
    $this->load->view('/home/homepageRegistration/default_form_study_india.php');
}
?>
    <div class="clearFix"></div>
     </li>
<?php
if($logged == "No") {
?>
<li id="homesecurityCode_block">
    <p>Type in the character you see in the picture below</p>
    <div class="clearFix spacer5"></div>
    <div>
    	<img align = "absbottom" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodehome_mp" width="100" height="34" id = "secureCode_mp"/>
        <input type="text" blurMethod='trackEventByGA("EnterCaptcha","captcha entered by user");' class="form-txt-field" style="margin-left:9px;width:135px;" name =
"homesecurityCode_mp" id = "homesecurityCode_mp" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
		<div class="errorPlace" style="display:none;">
        	
        	<div class="errorMsg" id= "homesecurityCode_mp_error"></div>
        </div>
    </div>
</li>

<li id="homepage_agree_div">
	<input type="checkbox" name="cAgree" id="cAgree" /> I agree to the <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition');">terms of services</a> and <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/privacyPolicy');">privacy policy</a>
<div class="errorPlace" style="display:none;">
	<div class="errorMsg" id= "cAgree_error"></div>
</div>
    
</li>
<?php } ?>


<!-- submit button section -->
<li>
	<input uniqueattr="homepageFindInstituteButton" type="submit" id="subm" value="Submit" class="orange-button" <?php if($logged!="No") echo 'disabled = "true"'; ?>/>
</li>
</ul>
<script>
// js var for google event tracking
var currentPageName = '<?php echo $pagename; ?>';
//var pageTracker = null;
</script>
<div id="marketingusersign_ajax"></div>
<div class="clear_L"></div>
<div id="emptyDiv" style="display:none;">&nbsp;</div>
<script type="text/javascript">
  function trackEventByGA(eventAction,eventLabel) {
    if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
        pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
    }
    return true;
    }
  function RenderInit() {
        addOnBlurValidate($('frm1'));
    }
window.onload = function () {
        try{
        RenderInit();
        } catch (e) {
             //alert(e);
        }
}
</script>
