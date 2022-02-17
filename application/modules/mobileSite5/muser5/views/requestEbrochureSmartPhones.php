<?php 	
		$this->load->view('/mcommon5/header');
	global $user_logged_in;
	global $logged_in_usermobile;
	global $logged_in_user_name;
	global $logged_in_first_name;
	global $logged_in_last_name; 
	global $logged_in_user_email;
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("customCityList"); ?>"></script>
<?php $courseAtrr_decoded=(base64_decode($courseArray,false));
      $courseAtrr_unserialized=(unserialize($courseAtrr_decoded));
?>
<?php if(!empty($courseAtrr_unserialized)){?>
<?php
			// Loading Listing builder and repository..
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$courseRepository = $listingBuilder->getCourseRepository();
			$courseIdsArray = array();
			foreach($courseAtrr_unserialized as $name => $value){
				$value = explode('*', $value);
				$currentCourseId = $value[0];
				if(!in_array($currentCourseId, $courseIdsArray)) {
					$courseIdsArray[] = $currentCourseId;
				}
			}
			// Getting Courses info..
			$coursesObjAray = $courseRepository->findMultiple($courseIdsArray);
			$localityArray = array();
			$courseListOptions = array("0" => "Please Select Course");
			foreach($coursesObjAray as $course){
				$instituteId = $course->getInstId();
				$courseListOptions[$course->getId()] = html_escape($course->getName());
				$localityArray[$course->getId()] = getLocationsCityWise($course->getLocations());
				$instituteName = $course->getInstituteName();
				$insLocation = $course->getMainLocation()->getLocality()->getName()?', '.$course->getMainLocation()->getLocality()->getName():"";
				$insCity = ', '.$course->getMainLocation()->getCity()->getName();
				if($course->getId()==$list){
					$courseName = html_escape($course->getName());
				}
			}
?>
<?php } ?>
<div id="wrapper" class="clearfix of-hide" data-role="page" data-enhance="false">
	<header id="page-header" class="clearfix">
        <div class="head-group">
            <a id="preferredCityOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
		<?php if($getEBCall==0){ 
				echo "<h1>Request Free E-brochure</h1>";
		}
		else{
				echo "<h1>Get Free Brochure</h1>";
		} ?>
        </div>
    </header>
<div class="blue-bar">
		<?php if($getEBCall==0 || ($getEBCall==1 && $user_logged_in!="false") ){ 
				echo "<p>You have chosen to request free brochure from:</p>";
		}
		else{
				echo "<p>Just give us a few details to get brochure from:</p>";
		} ?>
	        <h5><?php echo $instituteName.$insLocation.$insCity;?></h5>
</div>
<script>var isInvalidSubmitEForm = true;</script>
<section class="content-wrap2 clearfix">
	<article class="content-child clearfix">
    	<form method="post" action="/muser5/MobileUser/request_validation" id="requestEBrochure" onSubmit="if(isInvalidSubmitEForm){ return false;}">
		<input name="action_type" type="hidden" value="<?=$action_type?>" />
		<input type="hidden" value="<?php echo $current_url;?>" name="currentUrl">
		<input type="hidden" value="<?php echo $referral_url;?>" name="referralUrl">
		<input type="hidden" value="<?php echo $courseArray;?>" name="courseArray">
<!--		<input type="hidden" value="<?php //echo $logged_in_user_email;?>" name="user_email">-->
		<input type="hidden" value="<?php echo $from_where;?>" name="from_where">
		<input type="hidden" value="Request E-brochure" name="login">
		<input type="hidden" value="REQUEST-E-BROCHURE" name="pageName">
		<input type="hidden" value="<?=$institute_id?>" name="institute_id">
        	<ol class="form-item">
                <li id="firstNameDiv">
			<div class="textbox" >
	   	 	<?php
		        if($user_logged_in!="false"){
			?>
				<input type="text" minlength="1" maxlength="50" placeholder="First Name" name="user_first_name" id="user_first_name" value="<?php echo $logged_in_first_name;?>" onBlur="checkEmpty(this.id);"/>
			<?php
		        }else{ ?> 
				<input type="text" placeholder="First Name" name="user_first_name" id="user_first_name" value="<?php echo set_value('user_first_name');?>" maxlength="50" minlength="1"  onBlur="checkEmpty(this.id);"/>
			<?php
		        }
        		?>
			</div>
			<div class="errorMsg" id="error_user_first_name"><?php $err=form_error('user_first_name');echo strip_tags($err);?></div>
                </li>
                <li id="lastNameDiv">
			 <div class="textbox" >
			<?php
                	if($user_logged_in!="false"){ 
	                ?>
        	                <input type="text" placeholder="Last Name" name="user_last_name" id="user_last_name" value="<?php echo $logged_in_last_name;?>" onBlur="checkEmpty(this.id);" minlength="1" maxlength="50" />
                	<?php
	                }else{ ?>
        	                <input type="text" placeholder="Last Name" name="user_last_name" id="user_last_name" value="<?php echo set_value('user_last_name');?>" maxlength="50" minlength="1" onBlur="checkEmpty(this.id);"/>
                	<?php
	                }
        	        ?>
			</div>
			<div class="errorMsg" id="error_user_last_name"><?php $err=form_error('user_last_name');echo strip_tags($err);?></div>
		</li> 
                <li id="emailDiv">
			<div class="textbox" >
			<?php 
			if($user_logged_in!="false"){
			?>
				<input type="email" disabled = "disabled" placeholder="Email" name="user_email" id="user_email" value="<?php echo $logged_in_user_email;?>" onBlur="checkEmpty(this.id);" />
			<?php
			}else{ ?>	
				<input type="email" placeholder="Email" name="user_email" pattern="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/" id="user_email" required="" value="<?php echo set_value('user_email');?>" maxlength="125" onBlur="checkEmpty(this.id);"/>
			<?php 
			} ?>
                	</div>
			<div class="errorMsg" id="error_user_email"><?php $err=form_error('user_email');echo strip_tags($err);?></div>
            
                </li>
                
                <li id="mobileDiv">
			<div class="textbox" >
			<?php 
			if($user_logged_in!="false"){
			?>
			<input type="tel" placeholder="Mobile" required="required" name="user_mobile" value="<?php echo $logged_in_usermobile;?>" maxlength="10" id="user_mobile"  onBlur="checkEmpty(this.id);"/>
			<?php }else{
			?>
			 <input type="tel" placeholder="Mobile" required="required" name="user_mobile" value="<?php echo set_value('user_mobile');?>" maxlength="10" id="user_mobile"  onBlur="checkEmpty(this.id);"/>
			<?php } ?>
			</div>
			<div class="errorMsg" id="error_user_mobile"><?php $err=form_error('user_mobile');echo strip_tags($err);?></div>
                </li>
		
		<li>
                        <a href="#preferredCourse" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox">
                                <p> 
                                        <span id="courseText" >Select course to get brochure</span>
                                        <i class="icon-select2"></i>
                                </p>
                        </a>
                <div class="errorMsg" id="error_list"><?php $err=form_error('list');echo strip_tags($err);?></div>
                
                </li>
		
		<li style="display:none;" id="preferred-city-li">
	               <div id="preferred-city-div"></div>
		       <div class="errorMsg" style="display:none;" id="error_preferred_city_dropdown"></div> 
		</li>
                <li style="display:none;" id="city-locality-li">
                       <div id="city-locality-div"></div> 
		       <div class="errorMsg" style="display:none;" id="error_preferred_locality_dropdwon"></div> 
                </li>
		<li style="display:none;" id="user_password_li">
			<div class="textbox">
			<input type="password" placeholder="Password" required="required" name="user_password" value="" id="user_password" onBlur="checkEmpty(this.id);"/>
			</div>
			<div class="errorMsg" id="error_user_password"><?php $err=form_error('user_password');echo strip_tags($err);?></div>
                </li>
		<li style="display:none;" id="user_forget_password_li">
			<p class="fs12">
				<a href="/muser5/MobileUser/forgot_pass">Forgot password?</a>
			</p>
                </li>
		
                <li>
                	<div style="margin-bottom:5px" class="errorMsg" id="mainError"><?php if($show_error=="User already exists."){echo $show_error." Please  ";?><a href="/muser5/MobileUser/login/">Login </a><?php }?></div>
<!--                	<input type="button" value="Submit" class="button yellow" onclick="if(!validateRequestEBrochure()){return false;} $('#requestEBrochure').submit();"/>-->

		        <?php if($getEBCall==0){ ?>
			<input id="submitBtn" type="button" value="Submit" class="button yellow" onclick=" if(!validateRequestEBrochure()){return false;} isInvalidSubmitEForm=false; trackEventByGAMobile('HTML5_Request_Brochure_Page_Submit_Button'); submitRequestEBrochureForm('<?=$institute_id?>');"/>
			<?php }else{ ?>
			<input id="submitBtn" type="button" value="Submit" class="button yellow" onclick=" if(!validateRequestEBrochure()){return false;} isInvalidSubmitEForm=false; trackEventByGAMobile('HTML5_GETRB_Request_Brochure_Page_Submit_Button'); submitRequestEBrochureForm('<?=$institute_id?>');"/>
			<?php } ?>
                </li>
            </ol>
		
	    <input name="list" value="<?php echo $list;?>" type="hidden"/>
	    <input name="preferred_city_dropdown" value="<?php echo $cityId;?>" type="hidden"/>
	    <input name="preferred_locality_dropdwon" value="" type="hidden"/>

        </form>
    </article>
    
</section>
</div>

<?php $data =array();$data['courseListOptions'] = $courseListOptions;$data['instituteId'] = $instituteId;$data['localityArray'] = $localityArray;?> 
<div data-role="page" id="preferredCourse" data-enhance="false"><!-- dialog-->
 <?php $this->load->view('preferredCourse',$data); ?>
<script></script>
</div>
<div data-role="page" id="preferredCity" data-enhance="false"><!-- dialog-->
 <?php $this->load->view('preferredCity'); ?>
</div>
<div data-role="page" id="preferredLocation" data-enhance="false"><!-- dialog-->
 <?php $this->load->view('preferredLocation'); ?>
</div>

<?php
if($user_logged_in!="false" && $getEBCall==1){
		echo "<script>";
		echo "$('#firstNameDiv').hide();";
		echo "$('#lastNameDiv').hide();";
		echo "$('#emailDiv').hide();";
		echo "$('#mobileDiv').hide();";
		echo "</script>";
}
?>
<script>


var courseIdForInstitute = '';
var institute_id = <?=$instituteId?>;
var listings_with_localities = <?php echo $listings_with_localities; ?>;
var localityArray = <?=json_encode($localityArray)?>;	
window.jQuery.each(localityArray,function(index,element){
	custom_localities[index] = element;
});
var widget = 'mobileResponseForm';
function validateCourse(){
        window.jQuery('#error_list').show();
        window.jQuery('#error_list').html("Please Select Course");
        window.jQuery('#error_list').parent().addClass('error');
	return 1;
}
function validateUserFirstName(){
	window.jQuery('#error_user_first_name').show();
        window.jQuery('#error_user_first_name').html("The First Name field is required.");
        window.jQuery('#error_user_first_name').parent().addClass('error');
	return 1;
}
function validateUserLastName(){
	  window.jQuery('#error_user_last_name').show();
          window.jQuery('#error_user_last_name').html("The Last Name field is required.");
          window.jQuery('#error_user_last_name').parent().addClass('error');
	  return 1;
}
function validateUserEmail(flag){
	var errorFlag = 0;
	if(flag == 'blank'){
		window.jQuery('#error_user_email').show();
        	window.jQuery('#error_user_email').html("The Email field is required.");
                window.jQuery('#error_user_email').parent().addClass('error');
		errorFlag = 1;
	}else{
		var email = window.jQuery('#user_email').val();
             	var regex =/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/;
                 if(!regex.test(email)){

                        window.jQuery('#error_user_email').show();
                        window.jQuery('#error_user_email').html("Email field is not valid.");
                        window.jQuery('#error_user_email').parent().addClass('error');
			errorFlag = 1;
                }
	}
	return  errorFlag;
}
function validateMobile(flag){
	var  errorFlag = 0;
	if(flag=='blank'){
		window.jQuery('#error_user_mobile').show();
                window.jQuery('#error_user_mobile').html("The Mobile field is required.");
                window.jQuery('#error_user_mobile').parent().addClass('error');
		errorFlag = 1;
	}else{
		var mobile = window.jQuery('#user_mobile').val();
       //         var regex = /^\d{10}$/;
                var intRegex = /^\d+$/;
                if(!intRegex.test(mobile)){
                        window.jQuery('#error_user_mobile').show();
                        window.jQuery('#error_user_mobile').html("The Mobile field must contain digits only.");
                        window.jQuery('#error_user_mobile').parent().addClass('error');
                        errorFlag = 1;
                //The Mobile field must contain a 10 digit valid number.
                }else{
                        var regex = /^\d{10}$/;
                        if(!regex.test(mobile)){
                                window.jQuery('#error_user_mobile').show();
                                window.jQuery('#error_user_mobile').html("The Mobile field must contain a 10 digit valid number");
                                window.jQuery('#error_user_mobile').parent().addClass('error');
                                errorFlag = 1;

                        }else{
                                var regex = /^[9|8|7]{1}[0-9]{9}$/;
                                if(!regex.test(mobile)){
                                        window.jQuery('#error_user_mobile').show();
                                        window.jQuery('#error_user_mobile').html("The Mobile field must start with 9 or 8 or 7.");
                                        window.jQuery('#error_user_mobile').parent().addClass('error');
                                        errorFlag = 1;
                                }
                        }
                }
	}
	return  errorFlag;
}
function validateRequestEBrochure(){
	window.jQuery('#submitBtn').attr('disabled', 'disabled');
	var errorFlagCourse = 0;
	var errorFlagFName = 0;
	var errorFlagLName = 0;
	var errorFlagEmail = 0;
	var errorFlagMobile = 0;
	var errorFlagPCity = 0;
	var errorFlagPLocality = 0;
	var errorFlagPassword = 0;
	if($('#courseText').html() == '' || $('#courseText').html() == 'Select course to get brochure'){
                errorFlagCourse = validateCourse();
	}
	if (window.jQuery('#user_first_name').val() == "") {
		errorFlagFName = validateUserFirstName();
	}else{
		var firstName = window.jQuery('#user_first_name').val();
		var minlength = window.jQuery('#user_first_name').attr('minlength');
		var maxlength = window.jQuery('#user_first_name').attr('maxlength');
		errorFlagFName = validateDisplayName(firstName,'user_first_name',minlength,maxlength,'First Name');
	}

	if (window.jQuery('#user_last_name').val() == "") {
                errorFlagLName = validateUserLastName();
        }else{
		
		var lastName = window.jQuery('#user_last_name').val();
		var minlength = window.jQuery('#user_last_name').attr('minlength');
		var maxlength = window.jQuery('#user_last_name').attr('maxlength');
		errorFlagLName = validateDisplayName(lastName,'user_last_name',minlength,maxlength,'Last Name');
	}
	if (window.jQuery('#user_email').val() == "") {
		errorFlagEmail = validateUserEmail('blank');
	}
	if(window.jQuery('#user_email').val() != ""){
		errorFlagEmail = validateUserEmail();
	}
	if (window.jQuery('#user_mobile').val() == "") {
		errorFlagMobile = validateMobile('blank');
	}
	if(window.jQuery('#user_mobile').val() != ""){
		errorFlagMobile = validateMobile();
		if(errorFlagMobile=='0'){
                        window.jQuery('#error_user_mobile').hide();
                        window.jQuery('#error_user_mobile').html('');
						window.jQuery('#error_user_mobile').parent().removeClass('error');
		}
	}
	if (window.jQuery("#preferred-city-li").is(':visible') && window.jQuery("#preferredCityText").html() == 'Preferred City') {
		var error_div_id = 'error_preferred_city_dropdown';
		window.jQuery('#' + error_div_id).show();
		window.jQuery('#' + error_div_id).html("The Preferred City is required.");
		window.jQuery('#' + error_div_id).parent().addClass('error');
		errorFlagPCity = 1;
	}
	if (window.jQuery("#city-locality-li").is(':visible') && window.jQuery("#preferredLocationText").html() == 'Preferred Locality') {
		var error_div_id = 'error_preferred_locality_dropdwon';
		window.jQuery('#' + error_div_id).show();
		window.jQuery('#' + error_div_id).html("The Preferred Locality is required.");
		window.jQuery('#' + error_div_id).parent().addClass('error');
		errorFlagPLocality = 1;
	}

	if(window.jQuery('#user_password_li').is(':visible')  && window.jQuery("#user_password").val() == ''){
		var error_div_id = 'error_user_password';
		window.jQuery('#' + error_div_id).show();
		window.jQuery('#' + error_div_id).html("The Password field is required.");
		window.jQuery('#' + error_div_id).parent().addClass('error');
		window.jQuery('#user_forget_password_li').show();
		window.jQuery('#mainError').hide();
		errorFlagPassword = 1;
	}
	if (errorFlagCourse || errorFlagFName || errorFlagLName || errorFlagEmail || errorFlagMobile || errorFlagPCity || errorFlagPLocality || errorFlagPassword) {
		window.jQuery('#submitBtn').removeAttr('disabled', '');
		return false;
	} else {
		return true;
	}
}
function trim(str) {
try{
    if(str && typeof(str) == 'string'){
        return str.replace(/^\s*|\s*$/g,"");
    } else {
        return '';
    }
} catch(e) { return str;  }

} 
function validateDisplayName(str,id,minLength,maxLength,caption){
        var strToValidate = trim(unescape(str));
        var allowedChars = /^([A-Za-z0-9\s\'](,|\.|_|-){0,2})*$/;
        if(strToValidate == '' || strToValidate == 'Your Name' || strToValidate == 'First Name' || strToValidate == 'Last Name'){
                //return "Please enter your "+caption;
		window.jQuery('#error_'+id).show();
		window.jQuery('#error_'+id).html("Please enter your "+caption);
	        window.jQuery('#error_'+id).parent().addClass('error');
		return 1;
        }

        if(strToValidate.length < minLength){
		window.jQuery('#error_'+id).show();
		window.jQuery('#error_'+id).html(caption+" should be atleast "+ minLength +" characters.");
	        window.jQuery('#error_'+id).parent().addClass('error');
		return 1;
        }

        if(strToValidate.length > maxLength){
		window.jQuery('#error_'+id).show();
		window.jQuery('#error_'+id).html(caption+" cannot exceed "+ maxLength +" characters.");
	        window.jQuery('#error_'+id).parent().addClass('error');
		return 1;
        }

        var result = allowedChars.test(strToValidate);
        if(result == false){
		window.jQuery('#error_'+id).show();
		window.jQuery('#error_'+id).html("The " + caption+" cannot contain special characters.");
	        window.jQuery('#error_'+id).parent().addClass('error');
		return 1;
        }


        // Check if none of the Blacklisted words are used in Display names
        textBoxContent = strToValidate.replace(/[(\n)\r\t\"\']/g,' ');
        textBoxContent = strToValidate.replace(/[^\x20-\x7E]/g,'');
        textBoxContent.toLowerCase();
        var blacklisted = false;
        if(typeof(blacklistWords) == 'undefined'){
                blacklistWords = new Array();
        }
        if(blacklistWords){
        for (i=0; i < blacklistWords.length; i++) {
                if(textBoxContent.indexOf( blacklistWords[i].toLowerCase() ) >= 0)
                blacklisted = true;
        }
        }
        if(blacklisted){
		window.jQuery('#error_'+id).show();
		window.jQuery('#error_'+id).html("This username is not allowed.");
	        window.jQuery('#error_'+id).parent().addClass('error');
		return 1;
        }
        // Check for Blacklisted words End

        return 0;
}
function checkEmpty(element_id) {
	if (window.jQuery('#'+element_id).val() != "") {		
		window.jQuery('#'+'error_'+element_id).html("");		
		window.jQuery('#'+'error_'+element_id).parent().removeClass('error');	
	}		
}
function hideError(element_id){
	 window.jQuery('#'+'error_'+element_id).hide();
	 window.jQuery('#'+'error_'+element_id).html("");
	 window.jQuery('#'+'error_'+element_id).parent().removeClass('error');
}
var countLocation = 0;
function courseSelected(courseId){
	courseIdForInstitute = courseId;
        //Change the Text in the Course Box
        var courseName = $('#courseName'+courseId).html();
	removeLocalties();
	hideError('list');
        $('#courseText').html(courseName);
        $('input[name=list]').val(courseId);
	$('#city-locality-li').hide();
	var listing_key = "";
	for(i in listings_with_localities)
	{	    
	    if (i == "COURSE_WISE_CUSTOMIZED_LOCATIONS") {		
		compared_listing_id = courseId;
	    } else {		
		compared_listing_id = institute_id;
	    }
	    
	    var listing_ids = listings_with_localities[i];
	    for(var j=0;j<=listing_ids.length;j++)
	    {  
		    if(listing_ids[j] == compared_listing_id)
		    {
			    listing_key = i;
		    }
	    }	    
	}
	countLocation = 0;
	var pref_city_name;
	if(listing_key != "") {
		if(listing_key == "COURSE_WISE_CUSTOMIZED_LOCATIONS") {
			var listing_localities = custom_localities[listing_key];
			for (var city_name in listing_localities[courseId]) {
			    if (listing_localities[courseId].hasOwnProperty(city_name)) {
			      countLocation++;
			      pref_city_name =  city_name;
			      if(countLocation  == 2) break;
			    }
			  }
		} else {
			// var listing_localities = custom_localities[courseId];
			var listing_localities = custom_localities[listing_key];
		       for(i in listing_localities) {
			        // alert("i = "+i);
				// pref_city_name = listing_localities[i].name;
				pref_city_name = i;
		        	countLocation++;
			        if(countLocation == 2) break;
		       }
		}
	} else {
		var listing_localities = custom_localities[courseId];
		countLocation = 0;
		listing_key = courseId;
		for(i in listing_localities) {
				var  my_flag = 0;
// alert("i = "+i+", cutom = "+listing_localities[i].name+" , countLocation ="+countLocation);
				var inner_localities = listing_localities[i].localities;
				//var lname = listing_localities[i];
				//alert("lname = "+lname);
				for(var ijk in inner_localities) {
					countLocation++;
					my_flag = 1;
				}
				if(!my_flag) {
			                countLocation++;
				}
				// pref_city_name = listing_localities[i].name;
				pref_city_name = i;
//alert("i = "+i+", cutom = "+listing_localities[i].name+" , countLocation ="+countLocation);

		               if(countLocation == 2) break;
		 }
	}
	  //Hide the Course Overlay
        $('#courseOverlayClose').click();
	reloadLocations(institute_id,courseId,countLocation); 
	if(countLocation  > 1) {
        	reloadLocations(institute_id,courseId,countLocation);
	}else{
		var key = listing_key;
		var listing_id = courseId;
		var city = pref_city_name;
		var pref_locality=0;
			if(is_int(key)){
				var localities = custom_localities[key][city]['localities'];
			}else{
				if(typeof(city) != 'undefined'){
						if(key == "COURSE_WISE_CUSTOMIZED_LOCATIONS" && listing_id != "") {
		
						    var localities = custom_localities[key][listing_id][city];
		
						} else {
						    var localities = custom_localities[key][city];
						}
				}
				
			}

			for(i in localities)
			{
				if(typeof(localities[i]) != "object"){ 
					pref_locality = localities[i];
				} else {
					pref_locality = i;
				}

			}
		
		if(typeof(pref_city_name) != 'undefined')
				$('input[name=preferred_city_dropdown]').val(pref_city_name);
		if(typeof(pref_locality) != 'undefined')
				$('input[name=preferred_locality_dropdwon]').val(pref_locality);

	} // End of if(countLocation > 1).

}
function preferredCitySelected(id,cityName){
        //Change the Text in the Preferrer City Box
        var preferredCityName = $('#preferredCityName'+id).html();
        $('#preferredCityText').html(preferredCityName);
        $('input[name=preferred_city_dropdown]').val(cityName);
	hideError('preferred_city_dropdown');
        //Hide the Course Overlay
        $('#preferredCityOverlayClose').click();
	populateLocalities(cityName);
}
function preferredLocationSelected(id,locationName){
        //Change the Text in the Preferrer Location Box
        var preferredLocationName = $('#preferredLocationName'+id).html();
        $('#preferredLocationText').html(preferredLocationName);
        $('input[name=preferred_locality_dropdwon]').val(locationName);
	hideError('preferred_locality_dropdwon');
        //Hide the Course Overlay
        $('#preferredLocationOverlayClose').click();
}
function reloadLocations(institute_id, course_id, countLocation){
	if (course_id == 0) {
		window.jQuery('#city-locality-div').hide();
		return ;
	}
	window.jQuery('#error_list').html("");
	var tempHTML = generatePreferredCityLocalityDropdowns(institute_id, widget, course_id, "new");
	
	if(countLocation > 1){ 
		window.jQuery('#preferred-city-li').show();
		window.jQuery('#preferred-city-div').html(tempHTML);
	}
	else{
		window.jQuery('#preferred-city-li').hide();
		window.jQuery('#preferred-city-div').html();
	}
}
function populateLocalities(city){
	if(listing_key)
        {
                key = listing_key;
        }else{
	     //   if(course_id){
        	        key = courseIdForInstitute;
       	//	 }
        }
	var listing_id = courseIdForInstitute; 
	if(city!=''){
                if(is_int(key)){ 	// alert("key =="+key+" , city=="+city);
                        var localities = custom_localities[key][city]['localities'];
                }else{
                        if(key == "COURSE_WISE_CUSTOMIZED_LOCATIONS" && listing_id != "") {
                            var localities = custom_localities[key][listing_id][city];
                        } else {
                            var localities = custom_localities[key][city];
                        }
                }
	}
	var locationList = '';
	if(Object.size(localities) != 1){
		 window.jQuery('#city-locality-li').show();
		 if(preselected_locality!=''){
			 window.jQuery('#city-locality-div').html('<a href="#preferredLocation" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox"><p><span id="preferredLocationText" >'+preselected_locality+'</span><i class="icon-select"></i></p></a>');
		 }else{
		 	window.jQuery('#city-locality-div').html('<a href="#preferredLocation" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox"><p><span id="preferredLocationText" >Preferred Locality</span><i class="icon-select"></i></p></a>');
		 }
		 count = 0; 
		 for(i in localities) 
                 { 	// alert("i==="+i); 
			count++;
			if(is_int(key)){
				var lname = localities[i].name;
			}else{
				var lname = localities[i];
			}	
			 locationList += '<li onClick="preferredLocationSelected(\''+count+'\',\''+lname+'\');" style="cursor:pointer;" id="preferredLocationName'+count+'">'+lname+'</li>';
		 }

		 window.jQuery('#preferredLocationList').html(locationList);
         }else{
		removeLocalties();
	 }
}
function removeLocalties(){
	window.jQuery('#city-locality-li').hide();
	window.jQuery('#city-locality-div').html('');
	window.jQuery('#error_preferred_locality_dropdwon').hide();
	window.jQuery('#error_preferred_locality_dropdwon').html('');
}
function generatePreferredCityLocalityDropdowns(id, widget, course_id, style)
{    
	listing_key = '';
	if(typeof(widget) == 'undefined'){
		widget = "";
	}
	if(typeof(course_id) == 'undefined'){
		course_id = 0;
	}
	if(typeof(style) == "undefined"){
		style = "new";
	}
	if(typeof(listings_with_localities) == 'undefined')
	{
		return '';
	}
	var compared_listing_id = "";
	for(i in listings_with_localities)
	{	    
	    if (i == "COURSE_WISE_CUSTOMIZED_LOCATIONS") {		
		compared_listing_id = course_id;
	    } else {		
		compared_listing_id = id;
	    }
	    var listing_ids = listings_with_localities[i];
	    for(var j=0;j<=listing_ids.length;j++)
	    {  
		    if(listing_ids[j] == compared_listing_id)
		    {
			    listing_key = i;
		    }
	    }	    
	}
	var locality_html = '';	
	if(listing_key)
	{
		locality_html = getLocalityHTML(id,listing_key,widget,style, course_id);
	}else{
		if(course_id){
			locality_html = getLocalityHTML(id,course_id,widget,style);
		}
	}	
	return locality_html;
}
function getLocalityHTML(id,listing_key,widget,style, listing_id)
{    
	if(typeof(listing_id) == "undefined")
	{
	    listing_id = '';
	}
	var locality_html_temp = '';
	var listing_localities = custom_localities[listing_key];
	var count = 0;
	var cityArr = [];	
	var cityList = '';
	var locality_htmls='';
//	var cityList = '<li class="search-option"><form id="searchbox2" action=""><span class="icon-search" aria-hidden="true"></span><input id="search" type="text" placeholder="Enter Preferred City Name" autocomplete="off" onkeyup="locationAutoSuggest(this.value);"><i class="icon-cl"><span class="icon-close" aria-hidden="true"></span></i></form></li>';
	var flag = 1;
	// For ticket #2289 requirement..
	if (listing_key == "COURSE_WISE_CUSTOMIZED_LOCATIONS" && listing_id != "") {
		    for (var city_name in listing_localities[listing_id]) {
			if (listing_localities[listing_id].hasOwnProperty(city_name)) {
			  count++;
				if (preselected_city == city_name) {
					 locality_htmls = '<a href="#preferredCity" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox">'
                               +' <p><span id="preferredCityText" >'+city_name+'</span>'
                                        +'<i class="icon-select"></i>'
                                +'</p></a>';
					flag = 0;
			//	cityList += '<li onClick="preferredCitySelected(\''+count+'\',\''+city_name+'\');" style="cursor:pointer;" id="preferredCityName'+count+'">'+city_name+'</li>';
			        } //else {
				cityList += '<li onClick="preferredCitySelected(\''+count+'\',\''+city_name+'\');" style="cursor:pointer;" id="preferredCityName'+count+'">'+city_name+'</li>';
			  	//}
			}
		    }
	} else {
		var tempCityName = '';
		// This is for the older customized city - localities solution..
		for(i in listing_localities)
		{
			count++;
			if(typeof(listing_localities[i].name) == "undefined"){
				//flag = 1;
				tempCityName = i;
				cityList += '<li onClick="preferredCitySelected(\''+count+'\',\''+i+'\');" style="cursor:pointer;" id="preferredCityName'+count+'">'+i+'</li>';
			}else{
				cityList += '<li onClick="preferredCitySelected(\''+count+'\',\''+i+'\');" style="cursor:pointer;" id="preferredCityName'+count+'">'+listing_localities[i].name+'</li>';
				//flag = 0;
				tempCityName = listing_localities[i].name;
			}
			if (preselected_city == tempCityName) {
                                         locality_htmls = '<a href="#preferredCity" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox">'
                               +' <p><span id="preferredCityText" >'+tempCityName+'</span>'
                                        +'<i class="icon-select"></i>'
                                +'</p></a>';
				flag = 0;
			}
		}
	}
	window.jQuery('#preferredCityList').html(cityList);	
	if(flag){
		locality_htmls = '<a href="#preferredCity" data-inline="true" data-rel="dialog" data-transition="slide" class="selectbox">'
                               +' <p><span id="preferredCityText" >Preferred City</span>'
                                        +'<i class="icon-select"></i>'
                                +'</p></a>';
	}
	return locality_htmls;
}
<?php
if(isset($preselected_course_id) && $preselected_course_id != "" && $getEBCall==0) { ?>
	window.jQuery('#courseText').html(window.jQuery('#courseName<?=$preselected_course_id?>').html());
	courseSelected('<?=$preselected_course_id?>');
	var preselected_city =  '<?php echo ($form_preferred_city == "" ? "" : $form_preferred_city);?>';
	window.jQuery('input[name=preferred_city_dropdown]').val(preselected_city);
	if(countLocation>1){
		reloadLocations('<?=$instituteId?>', '<?=$preselected_course_id?>',countLocation);
	}
	var preselected_locality = '<?php echo ($form_preferred_locality == "" ? "" : $form_preferred_locality); ?>';
	window.jQuery('input[name=preferred_locality_dropdwon]').val(preselected_locality);
	populateLocalities(preselected_city);
<?php } else { ?>
	var preselected_city = "";
	var preselected_locality = "";
<?php }?>
var status	    = 'false';
function loginValidationCheck(){
	var user_email	    = window.jQuery('input[name=user_email]').val();
	var user_password   = window.jQuery('input[name=user_password]').val();
	var currentUrl      =  window.jQuery('input[name=currentUrl]').val();
	var referralUrl     =  window.jQuery('input[name=referralUrl]').val();
	var courseArray     =  window.jQuery('input[name=courseArray]').val();
	var from_where      =  window.jQuery('input[name=from_where]').val();
	var login           = window.jQuery('input[name=login]').val();
	var user_first_name = window.jQuery('input[name=user_first_name]').val();
	var user_last_name  = window.jQuery('input[name=user_last_name]').val();
	var user_mobile	    = window.jQuery('input[name=user_mobile]').val();
	var list	    = window.jQuery('input[name=list]').val();
	var institute_id    = window.jQuery('input[name=institute_id]').val();
	var pageName	    = 'REQUEST-E-BROCHURE';
	var loginType       = 'ajax';
	var preferred_city_dropdown = window.jQuery('input[name=preferred_city_dropdown]').val()
	var preferred_locality_dropdwon = window.jQuery('input[name=preferred_locality_dropdwon]').val();
	//var dataString = 'user_email='+ user_email + '&user_pass=' + user_password + '&loginType=' + loginType + '&currentUrl='+ currentUrl + '&referralUrl=' + referralUrl + '&login= Sign in';
	//var dataStringREB = 'currentUrl='+ currentUrl + '&referralUrl=' + referralUrl + '&courseArray=' + courseArray + '&user_email=' + user_email + '&from_where=' + from_where+ '&login=' + login + '&user_first_name=' + user_first_name + '&user_last_name=' + user_last_name + '&user_mobile=' + user_mobile + '&list=' + list + '&preferred_city_dropdown=' + preferred_city_dropdown + '&preferred_locality_dropdwon=' + preferred_locality_dropdwon+ '&pageName=' + pageName+'&institute_id='+institute_id;
	//console.log(dataStringREB);
	jQuery.ajax({
            url: "/muser5/MobileUser/login_validation",  
            type: "POST",
            data: {'user_email':user_email, 'user_pass':user_password, 'loginType':loginType, 'currentUrl':currentUrl, 'referralUrl':referralUrl, 'login':'Sign in'},
            success: function(result) 
            {
		if(result == 'WRONG_DETAILS'){
			window.jQuery('#mainError').show();
			window.jQuery('#mainError').html('Login details are incorrect.');
			window.jQuery('#user_forget_password_li').show();
		}
		if(result == 'RIGHT_DETAILS'){
			jQuery.ajax({
				    url: "/muser5/MobileUser/request_validation/",  
				    type: "POST",
				    data: {'currentUrl':currentUrl, 'referralUrl':referralUrl, 'courseArray':courseArray, 'user_email':user_email, 'from_where':from_where, 'login':login, 'user_first_name':user_first_name, 'user_last_name':user_last_name, 'user_mobile':user_mobile, 'list':list, 'preferred_city_dropdown':preferred_city_dropdown, 'preferred_locality_dropdwon':preferred_locality_dropdwon, 'pageName':pageName, 'institute_id':institute_id, 'autoResponse':'0' },
				    success: function(result) 
				    {
					var res = result.split('#');		
					if(res[0] == 'REQUEST-E-BROCHURE'){
						//window.jQuery('#mainError').html('Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.');
						//setTimeout(function(){window.location = res[1]},1000);
						window.jQuery('#mainError').hide();
						window.location = res[1];
					}
	
				    },
				    error: function(e){ 
				    }   
		}); 
		}
            },
            error: function(e){ 
            }   
        }); 
}
function submitRequestEBrochureForm(institute_id){
	var currentUrl      =  window.jQuery('input[name=currentUrl]').val();
	var referralUrl     =  window.jQuery('input[name=referralUrl]').val();
	var courseArray     =  window.jQuery('input[name=courseArray]').val();
	var from_where      =  window.jQuery('input[name=from_where]').val();
	var login           = window.jQuery('input[name=login]').val();
	var user_first_name = window.jQuery('input[name=user_first_name]').val();
	var user_last_name  = window.jQuery('input[name=user_last_name]').val();
	var user_email	    = window.jQuery('input[name=user_email]').val();
	var user_mobile	    = window.jQuery('input[name=user_mobile]').val();
	var list	    = window.jQuery('input[name=list]').val();
	var pageName	    = 'REQUEST-E-BROCHURE';
	var preferred_city_dropdown = window.jQuery('input[name=preferred_city_dropdown]').val()
	var preferred_locality_dropdwon = window.jQuery('input[name=preferred_locality_dropdwon]').val();
	var actionType = window.jQuery('#requestEBrochure input[name=action_type]').val();
	if(status == 'user_exit_in_db'){
		window.jQuery('#submitBtn').removeAttr('disabled', '');
		loginValidationCheck();
		return;
	}
	jQuery.ajax({
            url: "/muser5/MobileUser/request_validation/",  
            type: "POST",
            data: {'currentUrl':currentUrl,'referralUrl':referralUrl, 'courseArray':courseArray,'user_email':user_email, 'from_where':from_where, 'login':login, 'user_first_name':user_first_name, 'user_last_name':user_last_name, 'user_mobile':user_mobile, 'list':list, 'preferred_city_dropdown':preferred_city_dropdown, 'preferred_locality_dropdwon':preferred_locality_dropdwon, 'pageName':pageName, 'institute_id':institute_id, 'autoResponse':'0' ,'action_type':actionType},
            success: function(result) 
            {
		var res = result.split('#');
		if(res[0]=='user_exit_in_db'){
			window.jQuery('#submitBtn').removeAttr('disabled', '');
			window.jQuery('#mainError').html('User already exists.');
			window.jQuery('#error_user_password').parent().show();
			window.jQuery('#user_forget_password_li').show();
			status = 'user_exit_in_db';								
		}
		if(res[0] == 'REQUEST-E-BROCHURE'){
			//window.jQuery('#mainError').html('Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.');
		        setCookie('rebOnInsDetailPageStatus','true',30);
//			setTimeout(function(){window.location = res[1]},1000);

			window.location = res[1];
		}
		setCookie('show_recommendation','yes',30);
                setCookie('recommendation_course',list,30);		
            },
            error: function(e){ 
            }   
        }); 
}
</script> 
<?php if($courseName!='' && $getEBCall==0){?>
	<script>
//	window.jQuery('#courseText').html('<?php echo $courseName;?>');
	courseSelected('<?php echo $list?>');
	</script>
<?php }?>
<?php if($courseName!=''){?>
	<script>
	if(countLocation>1){
		 reloadLocations('<?=$instituteId?>', '<?=$list?>',countLocation);
	}
	</script>
<?php }?>
<script>
window.jQuery("#user_email").bind("change paste keyup", function() {
	if(window.jQuery('#user_password_li').is(':visible')){
		//window.jQuery('#user_password_li').hide();
		var error_div_id = 'error_user_password';
		window.jQuery('#' + error_div_id).hide();
		window.jQuery('#' + error_div_id).html("");
		window.jQuery('#' + error_div_id).parent().removeClass('error');
		window.jQuery('#' + error_div_id).parent().hide();
		window.jQuery('#user_password').val('');
		window.jQuery('#mainError').html('');
		window.jQuery('#user_forget_password_li').hide();
		status = 'false';
	} 
});

</script>
<?php $this->load->view('/mcommon5/footer'); ?>
