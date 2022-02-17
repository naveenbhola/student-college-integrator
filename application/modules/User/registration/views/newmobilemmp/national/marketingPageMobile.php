<?php
	$destination_url = $mmp_details['destination_url'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<base href="<?php echo base_url(); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name = "format-detection" content = "telephone=no" />
	<meta name = "format-detection" content = "address=no" />
	<meta name="description" content="Registration"/>
	<meta name="keywords" content="Registration"/>
	<meta name="copyright" content="2013 Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
	<meta name="robots" content="ALL" />
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320"/>
	<meta http-equiv="cleartype" content="on">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta http-equiv="x-dns-prefetch-control" content="off">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/public/mobile/images/touch/apple-touch-icon-144x144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/public/mobile/images/touch/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/public/mobile/images/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/public/mobile/images/apple-touch-icon-57x57-precomposed.png">
	<link rel="shortcut icon" href="/public/mobile/images/apple-touch-icon.png">
	<link rel="dns-prefetch" href="//ask.shiksha.com"> 
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans">
	<title>Shiksha.com - India's no. 1 college selection website: Register</title>
	<?php global $user_logged_in; ?>
	<script>
	</script>
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/vendor/<?php echo getCSSWithVersion('jquery.mobile-1.4.5','nationalMobileVendor'); ?>" >
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('userRegistrationMobile','nationalMobile'); ?>">
	<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('search-widget','nationalMobile'); ?>">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<!-- <script src="//<?php //echo JSURL; ?>/public/mobile5/js/<?php //echo getJSWithVersion('main'); ?>"></script>  -->
	<script src="/public/mobile5/js/vendor/<?php echo getJSWithVersion('jquery.mobile-1.3.2.min.full','nationalMobileVendor'); ?>"></script>
	<script src="/public/mobile5/js/<?php echo getJSWithVersion('userRegistrationFormMobile','nationalMobile'); ?>"></script>
	<!-- <script src="/public/js/<?php //echo getJSWithVersion('jquery-2.1.4.min'); ?>"></script> -->
</head>

<body>
	<a href="#registerNow" id="openRegFreeForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<a href="#forgtPassDiv" id="forgtPassDivClick" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none"> </a>
	<div data-role="page" id="registerNow">
	</div>

	<div data-role="page" id="forgtPassDiv">
	</div>

	<a href="#signUpForm" id="openSignUpForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="signUpForm">
	</div>

	<!-- Page for Registration First Screen -->
	<a href="#regFScreen" id="openRegFScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="regFScreen" data-enhance="false">
	</div>

	<!-- Page for Registration Second Screen -->
	<a href="#regSScreen" id="openRegSScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<div data-role="page" id="regSScreen">
	</div>

	<!-- Page for Registration dialog Screen -->
	<a href="#dialogPage" id="ssLayerOn" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" class="regCss" id="dialogPage" data-enhance="false">
	</div>

	<!-- Needed in jquery.mobile-1.4.5.min.js -->
	<script>
	
		$(document).ready(function(){
			// var formData = {
   //              'trackingKeyId' : '<?php echo $trackingKeyId;?>',
   //              'customFields':customFields,
   //              'callbackFunction':'registrationFromMMPCallback',
   //              'submitButtonText':'<?php echo $submitButtonText;?>',
   //              'httpReferer':'<?php echo $httpReferer;?>',
   //              'formHelpText':'<?php echo $customHelpText;?>'
   //          };
   			
   				$.mobile.ajaxEnabled = false;
   				$.mobile.ignoreContentEnabled = true; //Disable the Auto styling by JQuery Mobile CSS
                $.mobile.pushStateEnabled = false;  // Disable the AJAX Navigation 
   				registrationForm.showRegistrationForm();
   			
			
		});

		/*Function to validate mobile field*/

		function validateMobileInteger(number,caption,maxlength,minlength,required,isNationalNumber){

			var filter = /^(\d)+$/;

			if (typeof(minlength) =='undefined' || !minlength) {
				minlength = 10;
			}

			if(typeof(isNationalNumber) == 'undefined'){
				isNationalNumber = true;
			}

			if(number != '')
			{
				if(!filter.test(number)){
					return "Please enter your correct " + caption;
				}
				if(isNationalNumber && (number.substr(0,1) != 9)&&(number.substr(0,1) != 8)&&(number.substr(0,1) != 7))
					return "The mobile number can start with 9 or 8 or 7 only.";
				if(maxlength == minlength && (number.length  > maxlength || number.length < minlength))
					return "The mobile number must have " + maxlength + " digits only";
				if(number.length < minlength){
					return "The mobile number must contain minimum " + minlength + " digits";
				}
				if(number.length > maxlength){
					return "The mobile number can contain maximum " + maxlength + " digits";
				}
				return true;
			}
			if(number == '' && required) {
				return "Please enter your "+ caption;
			}
			return true;
		}

		function validateEmailAddress(email, caption, maxLength, minLength) {
			if(email.length ==0 && (typeof(minLength)) && minLength ==0){
				return true;
			}

			var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/

			if(email == '') {
				return  "Please enter your "+ caption ;
			}else if(!filter.test(email)) {
				return "The "+ caption +" specified is not correct";
			}
			return true;
		}

		function validateDisplayNameReg(str,caption, maxLength,minLength, allowedChars){

			var strToValidate = $.trim(unescape(str));
			var allowedChars = /^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/; 
			if(strToValidate == '' || strToValidate == 'Your Name' || strToValidate == 'Your First Name' || strToValidate == 'Your Last Name')
				return "Please enter your "+caption;

			if(strToValidate.length < minLength)
				return caption+" should be atleast "+ minLength +" characters.";

			if(strToValidate.length > maxLength)
				return caption+" cannot exceed "+ maxLength +" characters.";

			var result = allowedChars.test(strToValidate);
			if(result == false)
				return "The " + caption+" can not contain special characters.";


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
    if(blacklisted)
    	return "This username is not allowed.";
    // Check for Blacklisted words End
    
    return true;
}

function isProfaneReg(str) {
	/* code start to avoid dissallowed chars */
	var responseValue = checkDisAllowedWord(str);
	console.log(responseValue);
	if(responseValue !== true){
		return responseValue;
	}
}

function checkDisAllowedWord(str){
	str = str.replace(/[^\x20-\x7E]/g,'');
	var disallowdWordsList = base64_decode("bWVyYWNhcmVlcmd1aWRlfG1lcmFjYXJlZXJndWlkfHJlYWNoQGluZHJhaml0LmlufHd3dy5pbmRyYWppdC5pbnwwOTgxMDIyNTExNA==");
	console.log(disallowdWordsList);

	var url_pattern = new RegExp(disallowdWordsList,"i");
	return false;
	var dissallowedWord = url_pattern.exec(str);
	console.log(dissallowedWord);
	if(dissallowedWord != null){
		return dissallowedWord;
	}
	return true;
}

function base64_decode( data ) {

	var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

    do {  // unpack four hexets into three octets using index points in b64
    	h1 = b64.indexOf(data.charAt(i++));
    	h2 = b64.indexOf(data.charAt(i++));
    	h3 = b64.indexOf(data.charAt(i++));
    	h4 = b64.indexOf(data.charAt(i++));

    	bits = h1<<18 | h2<<12 | h3<<6 | h4;

    	o1 = bits>>16 & 0xff;
    	o2 = bits>>8 & 0xff;
    	o3 = bits & 0xff;

    	if (h3 == 64)      enc += String.fromCharCode(o1);
    	else if (h4 == 64) enc += String.fromCharCode(o1, o2);
    	else               enc += String.fromCharCode(o1, o2, o3);
    } while (i < data.length);

    return enc;
}

function registrationFromMMPCallback() {
    if(destination_url != '') {
        window.location = destination_url;
    }  else {
        window.location.reload();
    }
}
</script>
</body>
</html>