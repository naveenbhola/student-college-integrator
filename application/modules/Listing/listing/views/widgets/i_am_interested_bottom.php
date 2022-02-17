<?php if($details['packType']=='1'||$details['packType']=='2'){
	if(isset($identifier)){
		$listing_type = $identifier;
	}
?>
<script>/*
        <?php
        if (isset($validateuser[0]['firstname'])) {
        ?>
        var flagForSignedUser = true;
        <?php
        }else{
        ?>
            var flagForSignedUser = false;
            <?php }?>*/
	</script>


<?php
$main_course_id = $details['courseDetails'][0]['course_id'];

$instituteName = array_key_exists('institute_name',$details)?$details['institute_name']:$details['title'];
$googleTrackKey = 'IAmInterestForm';
if($flagForAB == 'false') {
  $googleTrackKey = 'IAmInterestForm_'.$details['institute_id'].'_case2';
}

$trackEvent = '';
if(isset($tab)){
        if($tab=='overview')
                $trackEvent = 'LISTING_OVERVIEW_BOTTOM_APPLY_BTN';
        else if($tab=='ana')
                $trackEvent = 'LISTING_OVERVIEW_BOTTOM_APPLY_BTN';
        else if($tab=='alumni')
                $trackEvent = 'LISTING_ALUMNITAB_BOTTOM_APPLY_BTN';
        else if($tab=='media')
                $trackEvent = 'LISTING_PHOTOSTAB_BOTTOM_APPLY_BTN';
}

?>
<?php $instituteName = array_key_exists('institute_name',$details)?$details['institute_name']:$details['title']; ?>
<div id="before_get_free_alerts_detail" style="display:block">
    <form  name="get_free_alerts" id="get_free_alerts_frm_detail" method="post" >
        <input type="hidden" name="listing_type_course" id="listing_type_course"  value="course"/>
        <input type="hidden" name="flag_check"  value="1"/>
        <!--<input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php //echo $type_id; ?>"/>-->
        <input type="hidden" name="listing_title" id="listing_title"  value="<?php echo $details['title']; ?>"/>
        <input type="hidden" name="listing_url" id="listing_url"  value="<?php echo site_url($thisUrl); ?>"/>
        <input type="hidden" name="isPaid" id="isPaid"  value="<?php echo $registerText['paid']; ?>"/>
        <input type="hidden" name="mailto" id="mailto"  value="<?php echo mencrypt($details['contact_email']); ?>"/>




        <?php if ($listing_type == 'institute') { ?>
        <input type="hidden" name="loginproductname_get_free_alert_detail" id="loginproductname_get_free_alert_detail"  value="NAUKRISHIKSHA_DETAIL_BOTTOM_GET_FREE_ALERTS_INSTITUTE"/>
        <?php } elseif ($listing_type == 'course') { ?>
        <input type="hidden" name="loginproductname_get_free_alert_detail" id="loginproductname_get_free_alert_detail"  value="NAUKRISHIKSHA_DETAIL_BOTTOM_GET_FREE_ALERTS__COURSE"/>
        <?php } ?>
        <input type="hidden" name="resolution_get_free_alert_detail" id="resolution_get_free_alert_detail"  value=""/>
        <input type="hidden" name="referer_get_free_alert_detail" id="referer_get_free_alert_detail"  value=""/>
        <input type="hidden" name="coordinates_get_free_alert_detail" id="coordinates_get_free_alert_detail"  value=""/>
    <div class="raised_10">
        <b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_10">
				<div style="background:url(/public/images/frmGirl.gif) 515px bottom no-repeat;height:<?php echo $show_localities?"405px":"375px"; ?>">
                <div class="bgRequestInfoHeader">
                    <div class="requestIcon"><div style="font-size:21px;padding-top:6px"><b>I want this institute to counsel me</b></div></div>
                </div>
                <div style="line-height:10px"><a name="btmForm">&nbsp;</a></div>
                <div style="margin:0 57px 0 15px">
                    <div style="width:100%">
						<div class="mb10">Interested in studying at <?php echo $details['title'];?>. Fill details for the <span class="bld">institute to counsel you</span></div>
						<!--<div>Fill Details for the <span class="bld">institute to counsel you</span></div>-->
                    </div>
                </div>
                <!--<div style="line-height:16px">&nbsp;</div>-->
                <div style="margin:0 10px 0 15px">
			
		<div id = 'fconnect_requestB_detail' style = "display:none">
		<div id = "fconnect_requestB_detail_Direct" style = "display:none">
			<fb:login-button scope="email,user_checkins,offline_access,read_stream,publish_stream" on-login="onLoginFromFacebook('request-E-BrochureDirect');hideOverlay();" uniqueattr="ListingPageFconnectBottom">Sign in With Facebook</fb:login-button>
		</div>
		<div id = "fconnect_requestB_detail_Indirect" style = "display:none">		
			<input type="button" class="fcnt_signBtn pointer" value="&nbsp;" onclick="eventTriggered = 'request-E-BrochureDirect'; showOverlayForFConnect();" uniqueattr="ListingPageFconnectBottom"/>
		</div>	
			<div class=lineSpace_10 mb8></div>
		</div>
		
		<div style="float:left;width:235px">			
			<div id = 'reqInfoDispName_foralert_detail_fb' class="wdh100" style="display:none">
					<div style="border:1px solid #ccc;background:#f2f2f2;padding:3px;height:50px;width:214px;overflow:hidden">
						<div class="float_L" style="width:50px">
							<img id = "fb_image_detail" src="" align="left" style="margin-right:5px" />
						</div>
						<div class="float_R" style="width:160px">
						<div style="height:10px;overflow:hidden" class="tar"><img src="/public/images/crossFB.gif" onclick="changeINNERHTML('bottom')" style="text-decoration:none"/></div>
						<div id="fb_innerHTML_detail"><div id = "fb_name_detail" class="Fnt11 bld fbGya" style="color:#565656">Ashish Mehra</div></div>
						<!--<span id = "fb_name_detail" class="Fnt11 bld">Ashish Mehra</span>-->
						<div id ="fb_friends_total_detail" class="Fnt10 fbGya" style="color:#565656">240 friends</div>
						</div>
					</div>
			</div>
			<div class="errorPlace"><div class="errorMsg" id="fb_name_detail_error"></div></div>
			<div id = "reqInfoDispName_foralert_detail_par">	
			    
				<div><input type="text" name="reqInfoDispName_foralert_detail" id="reqInfoDispName_foralert_detail"  value="<?php if (isset($validateuser[0]['firstname'])) { echo $validateuser[0]['firstname'];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "";  } ?> maxlength = "25" minlength = "3" validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off" style="width:180px;<?php if (isset($validateuser[0]['firstname'])) { echo 'color:#000;background:#FFF;border:1px solid #000;';} ?>" onFocus="checkTextElementOnTransition(this,'focus')" onblur ="checkTextElementOnTransition(this,'blur');" onfocus ="checkTextElementOnTransition(this,'focus');" default="Name" /></div>
				<div class="errorPlace"><div class="errorMsg" id="reqInfoDispName_foralert_detail_error"></div></div>
			</div>
			<div id = "reqInfoDispName_foralert_detail_wrapper" style="display:none"></div>	
		</div>
		
					<?php if(count($courseList) > 0) { ?>
					<div style="float:left;width:200px">

                        <div>
							<select name="listing_type_id_course" id="listing_type_id_detail" unrestricted="true" validate="validateSelect" required="1"  caption="Course of interest" style="width:200px;font-size:12px;font-family:Arial;padding:1px 0<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>">
							<?php if($tab=='overview'){?>
                                                        <option value="">Course of interest</option>
							<?php for($i = 0 ; $i < count($courseList); $i++){
							if($listing_type == "course"){
								if($type_id==$courseList[$i]['course_id']){
									$selectValue = "selected";
								} else {
									$selectValue ='';
								}
								echo '<option value="'.$courseList[$i]['course_id'].'" title="'.$courseList[$i]['courseTitle'].'" '.$selectValue.'>'.$courseList[$i]['courseTitle'].'</option>';
							} else {
								if($main_course_id==$courseList[$i]['course_id']){
									$selectValue = "selected";
								} else {
									$selectValue ='';
								}
								echo '<option value="'.$courseList[$i]['course_id'].'" title="'.$courseList[$i]['courseTitle'].'" '.$selectValue.'>'.$courseList[$i]['courseTitle'].'</option>';
							}
							}}else{ ?>
                                                        <option value="">Course of Interest</option>
						<?php for($i = 0 ; $i < count($courseList); $i++){
							if($listing_type == "course"){
								if($type_id==$courseList[$i]['course_id']){
									$selectValue = "selected";
								} else {
									$selectValue ='';
								}
								echo '<option value="'.$courseList[$i]['course_id'].'" title="'.$courseList[$i]['courseTitle'].'" '.$selectValue.'>'.$courseList[$i]['courseTitle'].'</option>';
							} else {
								echo '<option value="'.$courseList[$i]['course_id'].'" title="'.$courseList[$i]['courseTitle'].'">'.$courseList[$i]['courseTitle'].'</option>';
							}
						} ?>

                                                        <?php }?>
							</select>
						</div>
                        <div class="errorPlace"><div class="errorMsg" id="listing_type_id_detail_error"></div></div>
                    </div>
					<?php } ?>
					<div class="clear_L" style="line-height:15px;height:14px">&nbsp;</div>
                    <div style="float:left;width:235px">

                        <div><input type="text" name="reqInfoEmail_detail" id="reqInfoEmail_detail"  value="<?php if (isset($validateuser[0]['cookiestr'])) { $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0];  } ?>" <?php if (isset($validateuser[0]['cookiestr'])) {  echo "readonly";  } ?> maxlength = "125" validate = "validateEmail" required="true"  caption="Email-Id" autocomplete="off" style="width:180px;<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>"onblur ="checkTextElementOnTransition(this,'blur');" onfocus ="checkTextElementOnTransition(this,'focus');" default="Email Id" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoEmail_detail_error"></div></div>
                    </div>
                    <div style="float:left;width:200px">

                        <div><input type="text" name="reqInfoPhNumber_foralert_detail" id="reqInfoPhNumber_foralert_detail"  value="<?php if (isset($validateuser[0]['mobile'])) { echo $validateuser[0]['mobile']; } ?>"  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile Number" autocomplete="off" style="width:200px;<?php if (isset($validateuser[0]['mobile'])) { echo 'color:#000;background:#FFF;border:1px solid #000;'; } ?>"onblur ="checkTextElementOnTransition(this,'blur');" onfocus ="checkTextElementOnTransition(this,'focus');" default="Mobile Number" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="reqInfoPhNumber_foralert_detail_error"></div></div>
                    </div>
                    <div class="clear_L withClear">&nbsp;</div>
					
					<?php
					if($show_localities)
					{
					?>		
						<div class="clear_L" style="line-height:15px;height:14px">&nbsp;</div>
						<div style="float:left;width:235px">
							<div>
								<select name="preferred_city" id="preferred_city_bottom" unrestricted="true" validate="validateSelect" required="1"  caption="preferred city" onchange="populateLocalities(this,'bottom','<?php echo $listing_key; ?>');" style="width:220px;font-size:12px;font-family:Arial;padding:2px 0<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>">
									<option value="">Preferred City</option>
									<script>
									var listing_localities = custom_localities['<?php echo $listing_key; ?>'];
									for(i in listing_localities)
									{
										document.write("<option value='"+i+"'>"+i+"</option>");
									}
									</script>
								</select>
							</div>
							<div class="errorPlace"><div class="errorMsg" id="preferred_city_bottom_error"></div></div>
						</div>
						<div style="float:left;width:200px">
							<div>
								<select name="preferred_locality" id="preferred_locality_bottom" unrestricted="true" validate="validateSelect" required="1"  caption="preferred locality" style="width:220px;font-size:12px;font-family:Arial;padding:2px 0<?php if (isset($validateuser[0]['cookiestr'])) {  echo 'color:#acacac;background:#FFF;border:1px solid #CCC;';  } ?>">
									<option value="">Preferred Locality</option>
								</select>
							</div>
							<div class="errorPlace"><div class="errorMsg" id="preferred_locality_bottom_error"></div></div>
						</div>
						<div class="clear_L withClear">&nbsp;</div>
					<?php
					}
					?>
					
                </div>
		<div id = "captchaDivForHTMLCache_detail_par" style="display:block">
                <div id = "captchaDivForHTMLCache_detail" style="display:block">
                    <div style="padding:12px 18px 0 17px;font-size:11px">Type in the characters you see in the picture below </div>
                    <div style="padding:3px 0 0 17px"><img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodeforlistingalert_detail" width="100" height="40"  id = "getfreealertCaptacha_detail"/>&nbsp;<input type="text" name = "securityCodeforgetalert_detail" id = "securityCodeforgetalert_detail" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" autocomplete="off" style="width:100px;position:relative;top:-5px" /></div>
                    <div class="errorPlace"><div style="padding-left:20px;" class="errorMsg" id="securityCodeforgetalert_detail_error"></div></div>
                </div>
		</div>
                <div style="line-height:15px">&nbsp;</div>
                <div style="margin:0 57px 0 15px">
                   <div><input type="button" onclick="trackEventByGA('SubmitClick','<?php echo $trackEvent?>'); return run_click_detail_response();" class="sprt_nlt_btn nlt_btn4" id="Im_interestBtn_nb" value="&nbsp;" uniqueattr="ListingPageApplyNowBottom"/></div>

                    <?php if($countResponseFormDetails>10){ ?>
                    <div class="" style="width:300px"><a href="#" class="Fnt11" id="studentsApplied_bottom"><?php echo $countResponseFormDetails; ?> People have already applied</a></div>
                                          <?php }?>





                </div>
                <div style="line-height:10px"><a name="btmForm">&nbsp;</a></div>
				</div>
            </div>
        <!--<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>-->
		<div><img src="/public/images/frmBtn.jpg" /></div>
    </div>
    </form>
</div>
<div class="raised_10" style="display:none;" id="after_get_free_alerts_detail">
        <b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_10" style="background: url(/public/images/bgRequestInfoHeader.gif) repeat-x left bottom;height:50px">
            <div class="bgRequestInfoHeader">
					<div class="requestIcon" style="height:63px"><div style="font-size:17px;padding-top:4px"><b>Your request has been successfully submitted.</b></div></div>
                </div>
            </div>
        <b class="b4b" style="background:#ffe45d"></b><b class="b3b"  style="background:#ffe45d"></b><b class="b2b" style="background:#ffe45d"></b><b class="b1b"></b>
</div>

<div id="gotoBottom">&nbsp;</div>
<?php $details['lastModifyDate'] = explode("T",$details['lastModifyDate']);
$date1 = explode("-",$details['lastModifyDate']['0']);
$date2['0'] = $date1['1'];
$date2['1'] = $date1['2'];
$date2['2'] = $date1['0'];
$details['lastModifyDate']['0'] = implode("-",$date2);
?>
<div class="Fnt11 mt5" style="color:#5b5b5b">** This information has been collected from <?php if(!empty($details['url'])){echo $details['url'];}else{ echo "institute's website and
brochures" ;}?>. Trade Marks belong to the respective owners.<br />The listing was last modified on <?php echo $details['lastModifyDate']['0'];?></div>
<script>
windowonloadcheck();
$("Im_interestBtn_nb").removeAttribute('disabled');
jsPaidFree =0;

</script>
<?php }?>
