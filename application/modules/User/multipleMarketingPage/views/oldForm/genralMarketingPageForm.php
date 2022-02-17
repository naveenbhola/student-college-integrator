    <script>
var isLogged = '<?php echo $logged; ?>';
var messageObj;
var FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
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
loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>', function(){
    //initialization code
});
</script>
<?php
$url = '/user/Userregistration/MultipleMarketingPageUserOperation';
?>

<script>
function updateRegTracker(id)   
{
    $('regTracker').src = '/registration/Tracker/newTracker/'+id+'/<?php echo $pageId; ?>?d='+Math.floor((Math.random()*10000)+1);
}
</script>
<img src="/registration/Tracker/newTracker/oldFormLoad1/<?php echo $pageId; ?>?d=<?php echo rand(1,100000); ?>" id="regTracker" />

<form method="post" onsubmit=" try{if(sendReqInfo(this)) { new Ajax.Request('<?php echo $url?>',{onSuccess:function(request){javascript:newuserresponse(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)});} return false;} catch(e) { alert(e);}" action="<?php echo $url?>" novalidate="novalidate" id = "frm1" name = "marketingUser">
    <input type = "hidden" name = "mmpPageId" id = "mmpPageId" value = "<?php echo $pageId; ?>" />
    <input type = "hidden" name = "pageReferer" id = "pageReferer" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
    <input type = "hidden" name = "flagfirsttime" id = "flagfirsttime" value = ""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
    <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName" value = ""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList" value = ""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName" value = ""/>
    <input type = "hidden" name = "mRegions" id = "mRegions" value = "#"/>
    <input type = "hidden" name = "mPageName" id = "mPageName" value = "<?php echo $pageName?>"/>
    <input type = "hidden" name = "mcourse" id = "mcourse" value = "<?php echo $course?>"/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg" value = ""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg" value = ""/>
    <input type = "hidden" name = "destination_url" id = "destination_url" value = "<?php echo $config_data_array['destination_url']?>"/>

<?php
    $marketingPageForms['studyAbroad'] = array(
            FIELD_OF_INTEREST_FIELD,
            DESIRED_COURSE_LEVEL_FIELD,
            GRADUATION_DETAILS_FIELD,
            XII_DETAILS_FIELD,
            DESTINATION_COUNTRY_FIELD,
            PLAN_TO_START_FIELD,
            SOURCE_OF_FUNDING_FIELD,
            EXAM_TAKEN_FIELD,
            NAME_FIELD,
            EMAIL_PASSWORD_FIELD,
            MOBILE_FIELD,
            RESIDENCE_LOCATION_FIELD,
            CALL_PREFERENCE_FIELD,
            CAPTCHA_FIELD
    );

    foreach($marketingPageForms[$pageName] as $marketingPageFormField) {
        $this->load->view(str_replace('marketing/','multipleMarketingPage/',$marketingPageFormField));
    }
    $submitBtnCaption = 'Submit';
    if($pageName == 'studyAbroad') {
        $submitBtnCaption = "I'm ready to Study Abroad";
    }
?>
    <div align="center">
        <div>
            <input type="submit"  id = 'subm' value="<?php echo $submitBtnCaption; ?>" style="border:0  none" class="study_btnReady" <?php if($logged!="No") echo 'disabled = "true"'; ?> uniqueattr="MultipleMarkeingPageAbroadLayer1Submit"/>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <?php
    if($pageName == 'studyAbroad') {
    ?> 
    <div style='color:#6b6b6b;font-size:11px;padding-top:10px'>
        *Mentioned figures are indicative for an year's expenses, may vary accord to country, university &amp; lifestyle selected.
    </div>
    <?php   } ?>

</form>
<script>
FLAG_LOCAL_COURSE_FORM_SELECTION = false;
//getCitiesForCountry('',false,'_studyAbroad');
<?php
if(is_array($userData) && is_array($userData[0])) {
    echo 'selectComboBox(document.getElementById("cities_studyAbroad"), "'.$userData[0]['city'] .'");';
}
?>
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>
<script>
if(typeof(setCustomizedVariableForTheWidget) == "function") {
if (window.addEventListener){
	window.addEventListener('click', setCustomizedVariableForTheWidget, false); 
} else if (window.attachEvent){
	document.attachEvent('onclick', setCustomizedVariableForTheWidget);
}
}
</script>
