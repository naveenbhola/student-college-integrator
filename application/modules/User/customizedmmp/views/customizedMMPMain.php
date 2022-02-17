<!-- customizedMMPHeader starts -->
<?php $this->load->view('customizedmmp/customizedMMPHeader');?>
<!-- customizedMMPHeader ends -->
<div style="width:97%;padding-left:10px;">
	<div>
		<!--Start_OuterBorder-->
		<div style="width:100%;">
			<div style="float:left;width:100%;">
				<div>
					<!-- customizedMMPForm starts -->
					<?php $this->load->view('customizedmmp/customizedMMPForm');?>
					<!-- customizedMMPForm ends -->
				</div>
			</div>
			<div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
		</div>
		<!-- marketingLocationLayer_ajax starts -->
		<div id="marketingLocationLayer_ajax"></div>
		<!-- marketingLocationLayer_ajax ends -->
		<!-- marketingusersign_ajax starts -->
		<div id="marketingusersign_ajax"></div>
		<!-- marketingusersign_ajax ends -->
	</div>
	<div class="clear_L"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div id="emptyDiv" style="display:none;">&nbsp;</div>
</div>
<script>
    <?php addJSVariables(); ?>
	var isLogged = '<?php echo $logged; ?>';
	var FLAG_LOCAL_COURSE_FORM_SELECTION;
	function loadScript(url, callback){
		var script = document.createElement("script")
		script.type = "text/javascript";
		if (script.readyState){  //IE
			script.onreadystatechange = function(){
				if (script.readyState == "loaded" ||
						script.readyState == "complete"){
					script.onreadystatechange = null;
					callback();
				}
			};
		} else {  //Others
			script.onload = function(){
				callback();
			};
		}
		script.src = url;
		document.getElementsByTagName("head")[0].appendChild(script);
	}
	loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>', function(){
		//initialization code
		messageObj = new DHTML_modalMessage();
		messageObj.setShadowDivVisible(false);
		messageObj.setHardCodeHeight(0);
	});

	function validateMMPMobileNo(){
		var other= document.getElementById('mobile').value;
		var objErr = document.getElementById('mobile_error');
		msg = validateMobileInteger(other,'mobile number',10,10,1);
		if(msg!==true)
		{
			objErr.innerHTML = 'please enter your mobile number';
			objErr.parentNode.style.display = 'inline';
			return 'please enter your mobile number';
		}
		else
		{
			objErr.innerHTML = '';
			objErr.parentNode.style.display = 'none';
			return '';
		}
	}
	
	function removetip() {
		if (document.getElementById('helpbubble1') != undefined) {
			document.getElementById('helpbubble1').style.display='none';
		}
		var other= document.getElementById('mobile').value;
		var objErr = document.getElementById('mobile_error');
		msg = validateMobileInteger(other,'mobile number',10,10,1);
		if(msg!==true)
		{
			objErr.innerHTML = msg;
			objErr.parentNode.style.display = 'inline';
			return false;
		}
		else
		{
			objErr.innerHTML = '';
			objErr.parentNode.style.display = 'none';
			return true;
		}
	}
	
	function check_degree_pref() {
		if((document.getElementById("pref_deg_aicte").checked == false ) && (document.getElementById("pref_deg_inter").checked == false) && (document.getElementById("pref_deg_ugc").checked == false) && (document.getElementById("pref_deg_any").checked == false) ) {
				document.getElementById("degree_preference_error").innerHTML = "Please select your degree preferences.";
				document.getElementById("degree_preference_error").parentNode.style.display = "inline";
				return false;
		} else {
			document.getElementById("degree_preference_error").innerHTML = "";
			document.getElementById("degree_preference_error").parentNode.style.display = "none";
			return true;
		}
	}
	
	if(document.getElementById('marketingPreferedCity') != undefined){
		document.getElementById('marketingPreferedCity').innerHTML= "&nbsp;Select";
	}
	
	function showPreferedCityOverlay(obj) {
        var heightTop = document.all ? document.documentElement.scrollTop : window.pageYOffset;
        var heightLeft = document.all ? document.documentElement.scrollLeft : window.pageXOffset;
        var overlayCon = document.getElementById('userPreferenceCategoryCity').innerHTML;
		if( overlayCon == '') {
            overlayCon = document.getElementById('genOverlayContents').innerHTML;
        }
		
		showOverlay(570,400,'',overlayCon,true,obtainPostitionX(obj)-heightLeft-215,obtainPostitionY(obj)-heightTop+10);
        document.getElementById('overlayCloseCross').style.display= "none";
        putOnTheOldData();
        document.getElementById('genOverlayContents').style.border='1px solid #c4c4c6';
        document.getElementById('genOverlayContents').style.background='#FFF';
        document.getElementById('overlayShadow1').className='';
        document.getElementById('overlayShadow2').className='';
        document.getElementById('overlayShadow3').className='';
        document.getElementById('overlayContainer4').className='';
        openCityOverLay = true;
        attachOutMouseClickEventForPage(document.getElementById('genOverlay'),'isCityOverlayOpen()');
        if(document.getElementById('homesubCategories')) {
			document.getElementById('homesubCategories').onclick = isCityOverlayOpen;	
		}
        overlayHackForIE('genOverlay',document.getElementById('genOverlay'));
    }

	// js var for google event tracking
	var currentPageName = '<?php echo $pagename; ?>';
	var pageTracker = undefined;
</script>

<script id="galleryDiv_script_validate">
    function RenderInit() {
        addOnBlurValidate(document.getElementById('frm1'));
        //addOnFocusToopTip1(document.getElementById('frm1'));
		if(document.getElementById('mCityList') != undefined){
			document.getElementById('mCityList').value = '';
		}
    }

    function OneCourseForm(){
        var selectObj = $("homesubCategories");
        var num = selectObj.options.length;
        if(num == 2){
            selectObj.selectedIndex = 1;
            actionDesiredCourseDD(selectObj.options[1].value);
            $("homesubCategories").style.display = 'none';
            var newdiv = document.createElement('div');
            newdiv.innerHTML = selectObj.options[1].text;
            $("subCategory").appendChild(newdiv);
        }
    }

    window.onload = function () {
        try{
			ajax_loadContent_customized('marketingLocationLayer_ajax', customizedMMPController + 'customizedMMPAjaxForm/mr_page');
            OneCourseForm();
			RenderInit();
            publishBanners();
		} catch (e) {
             //alert(e);
        }
    }
	
	function putOnTheOldData() {
        var inputElems = document.getElementsByTagName('input');
        for(var inputElemsCount =0,inputElem; inputElem = inputElems[inputElemsCount++];) {
            if(inputElem.type == 'checkbox'){
                if(inputElem.value != 'on') {
                    if(finalArr[inputElem.value] == 1) {
                        inputElem.checked=true;
                    }
                }
            }
        }
        document.getElementById('userPreferenceCategoryCity').innerHTML = "";
    }
	
    function getDataFromCityLayer() {
		document.getElementById('userPreferenceCategoryCity').innerHTML = document.getElementById('genOverlayContents').innerHTML; 
        var hiddenVar = document.getElementById('mCityList');
        var hiddenVarCityNam = document.getElementById('mCityListName');
        var inputElems = document.getElementsByTagName('input');
        var cityNames="";
        var numCity = 0;
        hiddenVar.value  ="";
        finalArr = new Array();
        for(var inputElemsCount =0,inputElem; inputElem = inputElems[inputElemsCount++];) {
            if(inputElem.type == 'checkbox' && inputElem.checked && inputElem.name == 'locationPref[]'){
                if(inputElem.value != 'on') {
                    if(finalArr[inputElem.value] == 1) {
                        continue;
                    }
                    hiddenVar.value += inputElem.value + ',';
                    finalArr[inputElem.value] = 1;
                    if(inputElem.getAttribute('tag') != null) {
                        cityNames += inputElem.getAttribute('tag')+',';
                        numCity++;
                    }
                }
            }
        }
        hiddenVarCityNam.value = cityNames;
        document.getElementById('marketingPreferedCity').innerHTML= "&nbsp;Selected ("+numCity+")"; 
        document.getElementById('marketingPreferedCity').style.display="inline";
        var flag1 = true;
        var mCityListVal = document.getElementById('mCityList').value;
        if(trim(mCityListVal) == "") {
            flag1 = false;
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").innerHTML = "Please select preferred study location(s).";
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").parentNode.style.display = "inline";
            document.getElementById("marketingPreferedCity").innerHTML= "&nbsp;&nbsp;Select";
        } else {
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").innerHTML = "";
            document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").parentNode.style.display = "none";
        }

        hideOverlayMarketingPage();
        document.getElementById('overlayCloseCross').style.display= "";
        document.getElementById('genOverlayHolderDiv').style.display='';
        openCityOverLay = false;
        return;
    }
	
</script>
<?php
    //$this->load->view('customizedmmp/customizedMMPLocationLayer');
	// singlesinginoverlay html
	$this->load->view('customizedmmp/customizedMMPSignInOverlay');
	// load customizedMMPFooter: This file contains code related to tracking etc
	$this->load->view('customizedmmp/customizedMMPFooter');
	
	$this->load->view('customizedmmp/customizedStudyCountryOverlay');
?>
