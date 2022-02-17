<script type="text/javascript">

	function addOnFocusToopTip1(){
		// do nothing, we don't want to show any tooltips on customized mmp pages.
	}
	
	function readCookieValues(c_name)
	{
		var i,x,y,ARRcookies=document.cookie.split(";");
		for (i=0;i<ARRcookies.length;i++)
		{
			x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			x=x.replace(/^\s+|\s+$/g,"");
			if (x==c_name)
			{
				return unescape(y);
			}
		}
	}
	
	function showHideCustomDiv(id, display){
		if(document.getElementById(id) != undefined){
			var ele = document.getElementById(id);
			ele.style.display = display;
		}
	}
	
	function setSubmitButtonEnableProperty(){
		var userCookieSet = readCookieValues("user");
		var userCookieSetFlag = false;
		if(userCookieSet != undefined){
			userCookieSetFlag = true;
		}
		var userEmailValue = "";
		var userEmailFound = false;
		if(userCookieSet != undefined){
			var splitData = userCookieSet.split("|");
			for(var i=0; i<splitData.length; i++){
				if(splitData[i].indexOf("@") != -1){
					userEmailFound = true;
					userEmailValue = splitData[i];
				}
			}
		}
		
		if(userCookieSetFlag && userEmailValue !=""){
			// if user if logged in
			if(document.getElementById("subm") != undefined){
				var ele = document.getElementById("subm");
				ele.setAttribute("disabled", "true");
			}
			if(document.getElementById("email") != undefined){
				var emailEle = document.getElementById("email");
				emailEle.setAttribute("value", userEmailValue);
			}
			if(document.getElementById("homeemail") != undefined){
				var emailEle = document.getElementById("homeemail");
				emailEle.setAttribute("value", userEmailValue);
			}
			showHideCustomDiv("customized_mmp_captacha", "none");
			showHideCustomDiv("customizedMMPTNC", "none");
			showHideCustomDiv("customizedTNC", "none");
			showHideCustomDiv("customizedMMPCaptcha", "none");
		} else {
			// if user is not logged in
			showHideCustomDiv("study_abroad_email_block", "block");
		}
	}
	
	setSubmitButtonEnableProperty();
</script>
<?php $this->load->view('common/ga'); ?>
<script> 
	function trackEventByGA(eventAction,eventLabel) {
		if(typeof(pageTracker)!='undefined') {
			pageTracker._trackEvent('cmmp', eventAction, eventLabel);
		}
		return true;
	}
</script>

<?php
	global $serverStartTime;
	$trackForPages = isset($trackForPages)?$trackForPages:false;
	$endserverTime =  microtime(true);
	$tempForTracking = ($endserverTime - $serverStartTime)*1000;
	echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');
?>

<!-- Begin comScore Tag -->
<script>
  var _comscore = _comscore || [];
  _comscore.push({ c1: "2", c2: "6035313" });
  (function() {
    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
    el.parentNode.insertBefore(s, el);
  })();
</script>
<noscript>
	<img src="https://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->