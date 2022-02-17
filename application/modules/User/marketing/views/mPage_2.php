	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("marketing"); ?>" type="text/css" rel="stylesheet" />
<style>
.cssSprite{
background:url(/public/images/crossImg_14_12.gif) no-repeat;
};
.quesAnsBullets{
background-image: none;
};

</style>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("homePage"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("EduList"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("categoryHomePage"); ?>"></script>
<script>
var page=new Array();
page[0]="/public/images/no_1.gif";
page[1]="/public/images/no_2.gif";
page[2]="/public/images/no_3.gif";
page[3]="/public/images/no_4.gif";
var pageS=new Array();
pageS[0]="/public/images/no_a1.gif";
pageS[1]="/public/images/no_a2.gif";
pageS[2]="/public/images/no_a3.gif";
pageS[3]="/public/images/no_a4.gif";
var insName=new Array();
insName[0]="JK Business School, Gurgoan";
insName[1]="IILM Institute for Higher Education, Delhi";
insName[2]="WLC College India, Mumbai";
insName[3]="ITM business School, Mumbai";
var insCnt=new Array();
insCnt[0]="Rated in 'A' Category by business India 2008. Admissions Open.";
insCnt[1]="Ranked amongst the 1000 best B-Schools in the world by EDUNIVERSAL";
insCnt[2]="Ranked 2nd best B-School in Haryana by CSR-GHRDC Survey 2008";
insCnt[3]="Ranked A++ by Business India and 41st by Outlook";
var photos=new Array();
var photoslink=new Array();
var which=0;
//define images. You can have as many as you want:
photos[0]="/public/images/img_mkt_1.jpg";
photos[1]="/public/images/img_mkt_2.jpg";
photos[2]="/public/images/img_mkt_3.jpg";
photos[3]="/public/images/img_mkt_4.jpg";
var linkornot=1;
//Set corresponding URLs for above images. Define ONLY if variable linkornot equals "1"
photoslink[0]="";
photoslink[1]="";
photoslink[2]="";
photoslink[3]="";
photoslink[4]="";
photoslink[5]="";
var preloadedimages=new Array();
for (i=0;i<photos.length;i++){
	preloadedimages[i]=new Image()
	preloadedimages[i].src=photos[i];
}

function backward(){
	if(which ==0){
		which=photos.length;
	}
	if (which>0){
		which--;
		document.images.photoslider.src=photos[which];
	}
	stopCount();
}

function forward(){
	if(which ==(photos.length-1)){
		which=photos.length-(photos.length+1);
	}
	if (which<photos.length-1){
		which++;
		document.images.photoslider.src=photos[which];
	}
	stopCount();
}

function showImg(img){
	which = img;
	document.images.photoslider.src=photos[which];
	stopCount();
}

function transport(){
	window.location=photoslink[which];
	stopCount();
}

function roll(img_name, img_src){
	document[img_name].src = img_src;
}
function aopshowOverlay(divName){
	objD = document.getElementById(divName);
	if(objD.className=="dollarSubMenuHide"){
	objD.className="dollarSubMenuShow";
	} else {
		aophideOverlay(divName)
	}
}
function aophideOverlay(divName){
	objD = document.getElementById(divName);
	objD.className="dollarSubMenuHide";
}
function findPosition(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}
function closefeaturedcaption(){
	document.getElementById('galleryDiv').style.display = 'none';
}
function showGalleryImage(imageObj) {
	var imgXY = findPosition(imageObj);
	document.getElementById('galleryDiv').style.display = 'block';
	var divX = imgXY[0] - 0;
	var divY = imgXY[1] - 48;
	document.getElementById('galleryDiv').style.left = divX +'px';
	document.getElementById('galleryDiv').style.top = divY +'px';
}
function hideGalleryImage(galleryObj) {
		document.getElementById('galleryDiv').style.display = 'none';
}
</script>
<div style="width:959px;margin: 0 auto;" align="right"><span><img src="/public/images/naukrilogo_small.gif"/></div>
<?php
    $headerData['partnerPage'] = 'shiksha';
    $headerData['naukriAssoc'] = "false";
    $headerHtml = $this->load->view('marketing/headerView'.$type,array(),true);
    $headerData['headerHtml'] = $headerHtml;
    $headerData['title'] = 'Let us find an institute for you';
	$this->load->view('common/homepage_simple',$headerData);
    ?>
<body style="margin: 0 auto" onClick="">
<div style="width: 950px; margin: 0 auto; position:relative;top:-35px">
<div>
    <?php if($logged=="No") {?>
    <div class="txt_align_r lineSpace_25">Already Registered? <a href="javascript:void(0);" onClick="oristate1();">Sign In</a></div>
    <?php }else {?>
    <div class="txt_align_r lineSpace_25">Hi <?php echo $userData[0]['displayname']; ?> <a href="#" onClick="SignOutUser();">Sign Out</a></div>
    <?php }?>
    <!--Start_OuterBorder-->
	<div>
		<div style="float:left;width:470px">
				<div style="width:414px">
				<?php $this->load->view('marketing/'.$pagename);?>
				</div>
		</div>
		<div style="float:left;width:480px;background:#FFFF00">
			<div style="background:#f4f4f4">
				<?php $this->load->view('marketing/mPageRightPanel'.$extraPram);?>
			</div>
		</div>
		<div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
	</div>
    <script>
    var userCity = "";
    <?php if($logged == "Yes") {?>
    userCity = "<?php echo $userData[0]['city']?>";
    <?php }?>
    function getCitiesForMReg(objId) {
        var divObj = document.getElementById(objId);
        var cityHtml = "";
        for(var key in citiesarray ) {
            if(userCity == citiesarray[key].id) {
                cityHtml += '<option title="'+key+'" value="'+citiesarray[key].id+'" selected>'+key+'</option>';
            }else {
                cityHtml += '<option title="'+key+'" value="'+citiesarray[key].id+'">'+key+'</option>';
            }

        }
        cityHtml += '<option title="Others" value="-1">Others</option>';
        cityHtml = '<select  style = "width:150px" class = "normaltxt_11p_blk_arial fontSize_11p" id = "cities<?php echo $prefix; ?>" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "your city of residence"><option value="">Select City</option>'+cityHtml+'</select>';
        divObj.innerHTML=cityHtml;
    }
    function showSubcategoriesM(objId, categoryId){
        var divObj = document.getElementById(objId);
        var returnHtml = getSubCategoriesM(categoryId);

        var innerHtml = "<select name = 'homesubCategories' validate = 'validateSelect' required = 'true' caption = 'the desired course' id='<?php echo $prefix; ?>homesubCategories' style='width:170px;font-size:11px'><option value=''>Select</option>"+returnHtml+"</select>";
        divObj.innerHTML=innerHtml;
        addOnFocusToopTip1(document.getElementById("<?php echo $prefix; ?>marketingUser"));
        addOnBlurValidate(document.getElementById('marketingUser'));
    }
function getSubCategoriesM(id) {
var subCatergoryHtml = "";
var userInterest = '<?php echo $userInterest; ?>';
for(var categoryCount = 0; categoryCount < categoryList.length; categoryCount++) {
	var selectedAttributeVal = '';
    if(id == categoryList[categoryCount].parentId) {
		if(userInterest == categoryList[categoryCount].categoryID){
			var selectedAttributeVal = 'selected';
		}
        subCatergoryHtml += '<option title="'+categoryList[categoryCount].categoryName+'" value="'+categoryList[categoryCount].categoryID+'" '+selectedAttributeVal+'>'+categoryList[categoryCount].categoryName+'</option>';
    }
}
return subCatergoryHtml;
}
getCitiesForMReg("residenceLoc");
categoryList = eval(<?php echo json_encode($allCategories);?>);

    </script>
<script>
fillProfaneWordsBag();
    var isLogged = '<?php echo $logged; ?>';
function newuserresponse<?php echo $prefix;?>(responseText)
{
    reloadCaptcha('<?php echo $prefix;?>secureCode','<?php echo $prefix; ?>seccodehome');
    if((trim(responseText) == 'both') || (trim(responseText) == 'email') || (trim(responseText) == 'false')){
        document.getElementById('<?php echo $prefix; ?>homeemail_error').innerHTML = 'You are already registered with us. Please <a onclick="oristate1();" href="javascript:void(0);">Sign In</a>.';
        document.getElementById('<?php echo $prefix; ?>homeemail_error').parentNode.style.display = 'inline';
        return;
    }
    if(trim(responseText) == 'code')
    {

        var securityCodeErrorPlace = '<?php echo $prefix; ?>homesecurityCode_error';
        document.getElementById(securityCodeErrorPlace).parentNode.style.display = 'inline';
        document.getElementById(securityCodeErrorPlace).innerHTML = 'Please enter the Security Code as shown in the image.';
    }
    else
    {
      //  window.location=responseText;
       // alert(responseText);
        returnArr = responseText.split("###");
        if(document.getElementById('userLoginOverlay')){
            document.getElementById('userLoginOverlay').style.display = 'none';
        }
        var divX = document.body.offsetWidth/2 - 150;
        var   divY = screen.height/2 - 200;
        var  h = document.documentElement.scrollTop;
        divY = divY + h;
        if(returnArr.length > 0) {
            Message = 'Thank you for providing your educational interests. The same has been updated in your profile.';
        }else{
            Message = 'Congratulations you have successfully registered on shiksha.com.';
        }
        if(isLogged != 'No')
        {
            window.location = responseText;
        }
        else
        {
            showConfirmation(divX,divY,Message);
        }
        //alert(Message);
        document.getElementById("loginactionreg").value=base64_encode(responseText);
        document.getElementById("loginflagreg").value="redirect";
    }
}
function validateFieldsM(objForm){
    var returnFlag = true;
    for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++) {
        var formElement = objForm.elements[formElementsCount];
        if(formElement.getAttribute('validate')) {
            var methodName = formElement.getAttribute('validate');
            var textBoxContent = trim(formElement.value);
            textBoxContent = stripHtmlTags(textBoxContent);
            formElement.value = textBoxContent;
	    var strictCheck = false;
            if((formElement.getAttribute('validateSpecial')) && (formElement.getAttribute('validateSpecial') == 'strict')){
		var strictCheck = true;
		textBoxContent = escape(textBoxContent);
	    }
	    if(!strictCheck){
		textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\'\\]/g,' ');
		textBoxContent = textBoxContent.replace(/[^\x20-\x7E]/g,'');
	    }
        var textBoxMaxLength = "";
        if(methodName == "validateMobileInteger") {
            textBoxMaxLength  = formElement.getAttribute('maxlength1');
        }else {
            textBoxMaxLength = formElement.getAttribute('maxlength');
        }
            var textBoxMinLength  = formElement.getAttribute('minlength');
            var displayprop = formElement.style.display ;
            var caption;
            try{
                caption  = formElement.getAttribute('caption');
            } catch(e){
                caption = 'field';
            }

            if((!checkRequired(formElement) || displayprop == "none")) {
            if(displayprop == "none") {
            document.getElementById(formElement.id +'_error').parentNode.style.display = 'none';
            document.getElementById(formElement.id +'_error').innerHTML = '';
            }
            continue;
            }
            var multipleValidateMethods = methodName.split(',');
            var validationResponse;
            for(var multipleValidateMethodCount = 0, validateMethodName;validateMethodName = multipleValidateMethods[multipleValidateMethodCount++];) {
                validateMethodName = trim(validateMethodName);
                var methodSignature = validateMethodName+ '("'+ textBoxContent +'", "'+ caption +'", '+ textBoxMaxLength +', '+ textBoxMinLength +')';
                        validationResponse = eval(methodSignature);
                        if(validationResponse !== true) { break; }
            }
            /*
            if(methodName == 'validateEndDate')alert(validationResponse);
            if(methodName == 'validateEndTime')alert(validationResponse + "tiem");
            */
            if(validationResponse !== true) {
           		/*if(methodName == 'validateEndDate')alert(validationResponse +"assaas");*/
	            document.getElementById(formElement.id +'_error').parentNode.style.display = 'inline';
	            //document.getElementById(formElement.id +'_error').style.display = 'inline';
	            document.getElementById(formElement.id +'_error').innerHTML = validationResponse;
	            returnFlag = false;
            } else {

	            document.getElementById(formElement.id +'_error').parentNode.style.display = 'none';
	            //document.getElementById(formElement.id +'_error').style.display = 'none';
	            document.getElementById(formElement.id +'_error').innerHTML = '';
	 	  	if(!checkProfanity(formElement, caption)) { returnFlag = false;continue; }
            }
            //alert(methodSignature + "===" + returnFlag);
		} else {
			try{
				var caption  = formElement.getAttribute('caption');
	        	var textBoxMaxLength  = formElement.getAttribute('maxlength');
	            var textBoxMinLength  = formElement.getAttribute('minlength');
	            textBoxMinLength = textBoxMinLength == null ? 0 :  textBoxMinLength;
	            textBoxMaxLength = textBoxMaxLength == null ? 0 :  textBoxMaxLength;
	            //alert(this.type + "=="+ this.value.length+"=="+ textBoxMinLength + "=="+ textBoxMaxLength);
                    if(((formElement.value.length > textBoxMaxLength || formElement.value.length < textBoxMinLength) && (formElement.value.length != 0) && (textBoxMaxLength != 0)) && (formElement.type == 'text' || formElement.type=='textarea')) {
	           		document.getElementById(formElement.id +'_error').parentNode.style.display = 'inline';
	            	document.getElementById(formElement.id +'_error').innerHTML = 'Please fill the '+ caption + ' within the range of '+ textBoxMinLength + ' to '+ textBoxMaxLength +' characters.';
	            	returnFlag = false;
	            	continue;
	            } else {
	            	document.getElementById(formElement.id +'_error').parentNode.style.display = 'none';
	            }

	        	if(!checkProfanity(formElement, caption)) { returnFlag = false;  continue; }
	        } catch(e){}
		}
    }
        return returnFlag;
}


function sendReqInfo<?php echo $prefix; ?>(objForm){
    document.getElementById('refererreg').value = location.href;
    document.getElementById('resolutionreg').value = screen.width +'X'+ screen.height;
    var flag = validateFieldsM(objForm);
//    var flag1 = validateGender('marketingRegistrationMale','marketingRegistrationFemale','homegender_error');
    var flag1 = true;
    if(document.getElementById('studylocation'))
    {
	    var mCityListVal = document.getElementById('mCityList').value;
	    if(trim(mCityListVal) == "") {
		    flag1 = false;
		    document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").innerHTML = "Please select preferred study location(s).";
		    document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").parentNode.style.display = "inline";
	    }else {
		    document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").innerHTML = "";
		    document.getElementById("<?php echo $prefix?>"+"preferedLoc_error").parentNode.style.display = "none";
	    }
    }
    var flag2 = true;
    if(trim(document.getElementById("<?php echo $prefix?>" + "homephone").value) == "")
    {
        document.getElementById("<?php echo $prefix?>"+"homephone_error").innerHTML = "Please enter your correct mobile number";
        document.getElementById("<?php echo $prefix?>"+"homephone_error").parentNode.style.display = "inline";
        flag2 = false;
    }
    if(flag != true || flag1 != true || flag2 != true){
        return false;
    }
    else{
        if(document.getElementById('<?php echo $prefix;?>cAgree')) {
            var checkboxAgree = document.getElementById('<?php echo $prefix;?>cAgree');
            if(checkboxAgree.checked != true)
            {
                document.getElementById('<?php echo $prefix;?>cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('<?php echo $prefix;?>cAgree_error').parentNode.style.display = 'inline';
                return false;
            }
            else {
                document.getElementById('<?php echo $prefix;?>cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('<?php echo $prefix;?>cAgree_error').parentNode.style.display = 'none';
                return true;
            }
        }

    }
}
function addOnFocusToopTip1(objForm)
{
    for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++) {
        var formElement = objForm.elements[formElementsCount];
        //if (formElement.getAttribute('tip')){
            formElement.onfocus = showTip1;
        //}
    }
}




addOnFocusToopTip1(document.getElementById("<?php echo $prefix; ?>marketingUser"));
addOnBlurValidate(document.getElementById("<?php echo $prefix; ?>marketingUser"));

function showTip1()
{
    document.getElementById('helpbubble1').style.display = "none";
    if (this.getAttribute('tip'))
    if((ie||ns6)&&document.getElementById("hintbox1"))
    {
        dropmenuobj=document.getElementById("hintbox1");
        var tip = this.getAttribute('tip');
        //        alert(tip);
        dropmenuobj.innerHTML=tiptext[tip];
        var leftPosition = 20;
        if(this.getAttribute('leftPosition')){
            leftPosition = parseInt(this.getAttribute('leftPosition'));
        }
        document.getElementById('helpbubble1').style.left = getposOffset(this,"left") + leftPosition +"px";
        document.getElementById('helpbubble1').style.top = getposOffset(this,"top") +"px";
        document.getElementById('helpbubble1').style.display = "";
        document.getElementById('helpbubble1').style.zIndex = 10000;

        /*overlayHackLayerForIE('helpbubble1',document.getElementById('helpbubble1'));*/
        if(tip == "email_idM")
        {
            document.getElementById('helpbubble1').style.top = getposOffset(this,"top") - 20 + "px";
            document.getElementById('helpbubble1').style.left = getposOffset(this,"left") - 430 +"px";
        }
        if(tip == "mobile_numM")
        {
            document.getElementById('helpbubble1').style.top = getposOffset(this,"top")-20 + "px";
            document.getElementById('helpbubble1').style.left = getposOffset(this,"left") - 390 +"px";
        }
        //dropmenuobj.style.left=getposOffset(this,"left") +"px";
        //dropmenuobj.style.top=getposOffset(this,"top") + "px";
        dropmenuobj.style.display ="";
    }
    //        document.getElementById('iframe_div').style.left="0px";
}
</script>
<script>
                                    <?php if($type != "generic") { ?>
                                    if(document.getElementById('homesubCategories')) {
<?php                                    echo "showSubcategoriesM('subCategory',document.getElementById('".$prefix."board_id').value)";?>
                                    }
<?php                                }    ?>
                                    </script>



                            <?php
                            $this->load->view('marketing/marketingCityOverlay');

                            ?>

<?php
		$this->load->view('marketing/marketingSignInOverlay');
		$this->load->view('user/registerConfirmation');
?>

</div>
<div class="clear_L"></div><input id="loginflagreg" type="hidden" value="redirect"/>
<input id="loginactionreg" type="hidden" value=""/>
<div class="lineSpace_10">&nbsp;</div>
<div id="emptyDiv" style="display:none;">&nbsp;</div>
<script>
var openCityOverLay = false;
function abc(obj) {
    hideOverlay();
    var heightTop = document.all ? document.documentElement.scrollTop : window.pageYOffset;
    var heightLeft = document.all ? document.documentElement.scrollLeft : window.pageXOffset;
    //alert(obtainPostitionY(obj)-window.pageYOffset);
    var overlayCon = document.getElementById('userPreferenceCategoryCity').innerHTML;
    if( overlayCon == '') {
        overlayCon = document.getElementById('genOverlayContents').innerHTML;
    }
    showOverlay(450,310,'',overlayCon,true,obtainPostitionX(obj)-heightLeft-310,obtainPostitionY(obj)-heightTop+5);
    overlayParent = document.getElementById('userPreferenceCategoryCity');
    //document.getElementById('userPreferenceCategoryCity') = '';

//    document.getElementById('genOverlay').onmouseout=hideOverlay;
    //document.getElementById('genOverlayHolderDiv').style.display='none';
    document.getElementById('overlayCloseCross').style.display= "none";
    putOnTheOldData();
    document.getElementById('genOverlayContents').style.border='1px';
    document.getElementById('genOverlayContents').style.background='#FFF';
    document.getElementById('overlayShadow1').className='';
    document.getElementById('overlayShadow2').className='';
    document.getElementById('overlayShadow3').className='';
    document.getElementById('overlayContainer4').className='';
    /*
    document.getElementById('iframe_div').style.width='410';
    document.getElementById('iframe_div').style.height='350px';
    document.getElementById('iframe_div').style.left=obtainPostitionX(obj)-heightLeft-400+'px';
    document.getElementById('iframe_div').style.top=obtainPostitionY(obj)+5+'px';
    */
    //document.getElementById('genOverlay').style.height='350px';
    openCityOverLay = true;
    attachOutMouseClickEventForPage(document.getElementById('genOverlay'),'isCityOverlayOpen()');
    if(document.getElementById('homesubCategories'))
    document.getElementById('homesubCategories').onclick = isCityOverlayOpen;
}
function isCityOverlayOpen() {
    if(openCityOverLay) {
        document.getElementById('userPreferenceCategoryCity').innerHTML = document.getElementById('genOverlayContents').innerHTML;
        hideOverlay();
        document.getElementById('overlayCloseCross').style.display= "";
        document.getElementById('genOverlayHolderDiv').style.display='';
        openCityOverLay = false;
    }
}
getCitiesForCountry('',false,'<?php echo $prefix; ?>');
<?php
    if(is_array($userData) && is_array($userData[0])) {
        echo 'selectComboBox(document.getElementById("cities'. $prefix .'"), "'.$userData[0]['city'] .'");';
    }
?>
</script>
</div>
<?php $this->load->view('common/ga'); ?>
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
</body>
</html>
