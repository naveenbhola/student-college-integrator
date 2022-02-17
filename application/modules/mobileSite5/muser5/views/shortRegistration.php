<?php 	
$this->load->view('/mcommon5/header');
global $user_logged_in;
global $logged_in_usermobile;
global $logged_in_user_name;
global $logged_in_first_name;
global $logged_in_last_name; 
global $logged_in_user_email;
?>

<div id="wrapper" class="clearfix of-hide" data-role="page" data-enhance="false">
	<header id="page-header" class="clearfix">
        <div class="head-group">
            <a id="preferredCityOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
		<h1 id="headingText">Register User on Shiksha</h1>
        </div>
    </header>
<div class="blue-bar" id="subheadingText">
		<?php  
				echo "<p>Just give us a few details</p>";
		?>
</div>

<script>var isInvalidSubmitEForm = true;</script>
<section class="content-wrap2 clearfix" id="registrationFormLayer">
	<article class="content-child clearfix">
    	<form method="post" action="/muser5/MobileUser/shortRegistrationSubmit" id="requestEBrochure" onSubmit="if(isInvalidSubmitEForm){ return false;}">
		<input type="hidden" value="<?php echo $current_url;?>" name="currentUrl">
		<input type="hidden" value="<?php echo $referral_url;?>" name="referralUrl">
		<input type="hidden" value="<?php echo $from_where;?>" name="from_where">
		<input type="hidden" value="SHORT_REGISTRATION" name="pageName">
		<input type="hidden" value="<?=$action_type?>" name="action_type"/>
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
		        <input type="hidden" value="Short Registration" name="login">
                	<div style="margin-bottom:5px" class="errorMsg" id="mainError"><?php if($show_error=="User already exists."){echo $show_error." Please  ";?><a href="/muser5/MobileUser/login/">Login </a><?php }?></div>
			<input id="submitBtn" type="button" value="Submit" class="button yellow" onclick=" if(!validateRequestEBrochure()){return false;} isInvalidSubmitEForm=false; trackEventByGAMobile('HTML5_Short_Registration_Page_Submit_Button'); submitShortRegistrationForm();"/>
			&nbsp;<img id="searchLoader" style="display:none;" border=0 alt="" src="/public/images/loader_small_size.gif" />
                </li>
		
		<li>
			<p class="fs12">
				<a href="javascript:void(0)" onClick="toggleRegisterLogin(true);">Already Registered? Login Here</a>
			</p>
                </li>
		
            </ol>
		
        </form>
    </article>
    
</section>


<section class="content-wrap2 clearfix" id="loginFormLayer" style="display: none;">
	<article class="content-child clearfix">
    	<form method="post" action="/muser5/MobileUser/shortRegistrationSubmit" id="requestEBrochure1" onSubmit="if(isInvalidSubmitEForm){ return false;}">
        	<ol class="form-item">
                <li id="emailDiv1">
			<div class="textbox" >
			<?php 
			if($user_logged_in!="false"){
			?>
				<input type="email" disabled = "disabled" placeholder="Email" name="user_email1" id="user_email1" value="<?php echo $logged_in_user_email;?>" onBlur="checkEmpty(this.id);" />
			<?php
			}else{ ?>	
				<input type="email" placeholder="Email" name="user_email1" pattern="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/" id="user_email1" required="" value="<?php echo set_value('user_email');?>" maxlength="125" onBlur="checkEmpty(this.id);"/>
			<?php 
			} ?>
                	</div>
			<div class="errorMsg" id="error_user_email1"><?php $err=form_error('user_email');echo strip_tags($err);?></div>
            
                </li>
                	
		<li id="user_password_li">
			<div class="textbox">
			<input type="password" placeholder="Password" required="required" name="user_password" value="" id="user_password" onBlur="checkEmpty(this.id);"/>
			</div>
			<div class="errorMsg" id="error_user_password"><?php $err=form_error('user_password');echo strip_tags($err);?></div>
                </li>

                <li>
		        <input type="hidden" value="Short Registration" name="login">
                	<div style="margin-bottom:5px" class="errorMsg" id="mainError1"><?php if($show_error=="User already exists."){echo $show_error." Please  ";?><a href="/muser5/MobileUser/login/">Login </a><?php }?></div>
			<input id="submitBtn1" type="button" value="Submit" class="button yellow" onclick=" if(!validateRequestEBrochure1()){return false;} isInvalidSubmitEForm=false; trackEventByGAMobile('HTML5_Short_Registration_Page_Login_Button'); loginValidationCheck();"/>
			&nbsp;<img id="searchLoader1" style="display:none;" border=0 alt="" src="/public/images/loader_small_size.gif" />
                </li>
		
		<li id="user_forget_password_li">
			<p class="fs12">
				<a href="/muser5/MobileUser/forgot_pass">Forgot password?</a>
			</p>
                </li>

		<li>
			<p class="fs12">
				<a href="javascript:void(0)" onClick="toggleRegisterLogin(false);">New User? Register here.</a>
			</p>
                </li>
		
            </ol>
		
        </form>
    </article>
    
</section>

</div>

<?php
$data =array();
?>

<script>
var widget = 'mobileResponseForm';
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
function validateUserEmail(flag,counter){
	var errorFlag = 0;
	if(flag == 'blank'){
		window.jQuery('#error_user_email'+counter).show();
        	window.jQuery('#error_user_email'+counter).html("The Email field is required.");
                window.jQuery('#error_user_email'+counter).parent().addClass('error');
		errorFlag = 1;
	}else{
		var email = window.jQuery('#user_email'+counter).val();
             	var regex =/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/;
                 if(!regex.test(email)){

                        window.jQuery('#error_user_email'+counter).show();
                        window.jQuery('#error_user_email'+counter).html("Email field is not valid.");
                        window.jQuery('#error_user_email'+counter).parent().addClass('error');
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
        window.jQuery('#mainError').hide();		
	window.jQuery('#submitBtn').attr('disabled', 'disabled');
	$('#searchLoader').show();
	var errorFlagFName = 0;
	var errorFlagLName = 0;
	var errorFlagEmail = 0;
	var errorFlagMobile = 0;
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
		errorFlagEmail = validateUserEmail('blank','');
	}
	if(window.jQuery('#user_email').val() != ""){
		errorFlagEmail = validateUserEmail('','');
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

	if (errorFlagFName || errorFlagLName || errorFlagEmail || errorFlagMobile) {
		window.jQuery('#submitBtn').removeAttr('disabled', '');
		$('#searchLoader').hide();
		return false;
	} else {
		return true;
	}
}

function validateRequestEBrochure1(){
        window.jQuery('#mainError1').hide();
	window.jQuery('#submitBtn1').attr('disabled', 'disabled');
	$('#searchLoader1').show();
	var errorFlagEmail = 0;
	var errorFlagPassword = 0;
	if (window.jQuery('#user_email1').val() == "") {
		errorFlagEmail = validateUserEmail('blank','1');
	}
	if(window.jQuery('#user_email1').val() != ""){
		errorFlagEmail = validateUserEmail('','1');
	}
	if( window.jQuery("#user_password").val() == ''){
		var error_div_id = 'error_user_password';
		window.jQuery('#' + error_div_id).show();
		window.jQuery('#' + error_div_id).html("The Password field is required.");
		window.jQuery('#' + error_div_id).parent().addClass('error');
		window.jQuery('#user_forget_password_li').show();
		window.jQuery('#mainError1').hide();
		errorFlagPassword = 1;
	}

	if (errorFlagEmail || errorFlagPassword) {
		window.jQuery('#submitBtn1').removeAttr('disabled', '');
		$('#searchLoader1').hide();
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

var status	    = 'false';
function loginValidationCheck(){
	var user_email	    = window.jQuery('input[name=user_email1]').val();
	var user_password   = window.jQuery('input[name=user_password]').val();
	var currentUrl      =  window.jQuery('input[name=currentUrl]').val();
	var referralUrl     =  window.jQuery('input[name=referralUrl]').val();
	var from_where      =  window.jQuery('input[name=from_where]').val();
	var login           = window.jQuery('input[name=login]').val();
	var pageName	    = 'SHORT_REGISTRATION';
	var loginType       = 'ajax';

	jQuery.ajax({
            url: "/muser5/MobileUser/login_validation",  
            type: "POST",
            data: {'user_email':user_email, 'user_pass':user_password, 'loginType':loginType, 'currentUrl':currentUrl, 'referralUrl':referralUrl, 'login':'Sign in'},
            success: function(result) 
            {
		if(result == 'WRONG_DETAILS'){
			window.jQuery('#mainError1').show();
			window.jQuery('#mainError1').html('Login details are incorrect.');
		        $('#searchLoader1').hide();
			window.jQuery('#submitBtn1').removeAttr('disabled', '');
		}
		
		if(result == 'RIGHT_DETAILS'){
			jQuery.ajax({
				    url: "/muser5/MobileUser/shortRegistrationSubmit/",  
				    type: "POST",
				    data: {'currentUrl':currentUrl, 'referralUrl':referralUrl, 'user_email':user_email, 'from_where':from_where, 'login':login, 'pageName':pageName },
				    success: function(result) 
				    {
					var res = result.split('#');		
					if(res[0] == 'SUCCESS'){
						window.jQuery('#mainError1').hide();
						window.location = res[1];
						$('#searchLoader1').hide();
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

function submitShortRegistrationForm(){

	var currentUrl      =  window.jQuery('input[name=currentUrl]').val();
	var referralUrl     =  window.jQuery('input[name=referralUrl]').val();
	var from_where      =  window.jQuery('input[name=from_where]').val();
	var login           = window.jQuery('input[name=login]').val();
	var user_first_name = window.jQuery('input[name=user_first_name]').val();
	var user_last_name  = window.jQuery('input[name=user_last_name]').val();
	var user_email	    = window.jQuery('input[name=user_email]').val();
	var user_mobile	    = window.jQuery('input[name=user_mobile]').val();
	var pageName	    = 'SHORT_REGISTRATION';

	jQuery.ajax({
            url: "/muser5/MobileUser/shortRegistrationSubmit/",  
            type: "POST",
            data: {'currentUrl':currentUrl,'referralUrl':referralUrl, 'user_email':user_email, 'from_where':from_where, 'login':login, 'user_first_name':user_first_name, 'user_last_name':user_last_name, 'user_mobile':user_mobile, 'pageName':pageName,'cookieKey':'comparePageMobile' },
            success: function(result) 
            {
		var res = result.split('#');		
		if(res[0]=='user_exit_in_db'){
			window.jQuery('#submitBtn').removeAttr('disabled', '');
			$('#searchLoader').hide();
			window.jQuery('#mainError').show();
			window.jQuery('#mainError').html('User already exists. <a href="javascript:void(0)" onClick="toggleRegisterLogin(true,true);">Click here to Login</a>');
			status = 'user_exit_in_db';								
		}
		if(res[0] == 'SUCCESS'){
			window.location = res[1];
		}
		
            },
            error: function(e){ 
            }   
        }); 
}

function toggleRegisterLogin(showLogin,prefillEmail){
		prefillEmail = (typeof(prefillEmail)!='undefined')?prefillEmail:false;
		if(showLogin){
				$('#registrationFormLayer').hide();				
				$('#loginFormLayer').show();
				$('#headingText').html('Log In on Shiksha');
				$('#subheadingText').html('<p>Please enter your email id and password to log in</p>');
		}
		else{
				$('#loginFormLayer').hide();
				$('#registrationFormLayer').show();				
				$('#headingText').html('Register User on Shiksha');
				$('#subheadingText').html('<p>Just give us a few details:</p>');
		}

		if(prefillEmail){
				emailValue = $('#user_email').val();
				$('#user_email1').val(emailValue);
		}

		clearErrors(showLogin);
}

function clearErrors(showLogin){
		window.jQuery('#error_user_first_name').hide();
		window.jQuery('#error_user_first_name').html("");
		window.jQuery('#error_user_first_name').parent().removeClass('error');

		window.jQuery('#error_user_last_name').hide();
		window.jQuery('#error_user_last_name').html("");
		window.jQuery('#error_user_last_name').parent().removeClass('error');

		window.jQuery('#error_user_email').hide();
		window.jQuery('#error_user_email').html("");
		window.jQuery('#error_user_email').parent().removeClass('error');

		window.jQuery('#error_user_mobile').hide();
		window.jQuery('#error_user_mobile').html("");
		window.jQuery('#error_user_mobile').parent().removeClass('error');

		window.jQuery('#error_user_email1').hide();
		window.jQuery('#error_user_email1').html("");
		window.jQuery('#error_user_email1').parent().removeClass('error');

		window.jQuery('#error_user_password').hide();
		window.jQuery('#error_user_password').html("");
		window.jQuery('#error_user_password').parent().removeClass('error');		
}


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
