<?php
	if(!isset($product)){
		$product = 'entire';
	}
    //An array that reverse maps product to Value in drop down.
	$tempSearchTypeArray = array('forums' => 'Ask & Answer','events' => 'Institutes & Courses','entire' => 'Entire Shiksha','Articles'=>'Articles');

    //An array that maps value in searchType drop down to product name
    $searchTextTempArray = array('0' => 'Institutes & Courses', 'question' => 'Ask & Answer', 'Event' => 'Important Dates', 'scholarship' => 'Scholarships', 'blog'=>'Articles' , 'course' => 'Institutes & Courses' , 'institute'=> 'Institutes','entire' => 'Entire Shiksha','ask' => 'Institutes & Courses');

    //An array that maps product to help text in search bar
    $helpTextMap = array(
                'askanswer'=> array('keywordInput'=>'Enter Keywords','keyword' => 'E.g. Which institute is the best for MBA, IILM, MTECH or MCA etc. ' , 'locationInput'=>'Enter Location','location' =>'E.g. Delhi, Pune, Canada etc.'),
                'importantdates' => array('keywordInput'=>'Enter Keywords','keyword' => 'Eg. MBA Tour, CAT admission etc.','locationInput'=>'Enter Location', 'location'=>'Eg. Australia, Karnataka or Delhi'),
                'entireshiksha' => array('keywordInput'=>'Enter Keyword','keyword'=>'E.g. MBA, Engineering, XLRI, BBA etc.' ,'locationInput'=>'Enter Location' , 'location'=>'E.g. Delhi, Pune, Canada etc.'),
                '0' => array('keywordInput'=>'Enter Keyword','keyword'=>'E.g. MBA, Engineering, XLRI, BBA etc.' ,'locationInput'=>'Enter Location' , 'location'=>'E.g. Delhi, Pune, Canada etc.'),
                'all' =>array('keywordInput'=>'Enter Keyword','keyword'=>'E.g. MBA, Engineering, XLRI, BBA etc.' ,'locationInput'=>'Enter Location' , 'location'=>'E.g. Delhi, Pune, Canada etc.'),
                'courses'=>array('keywordInput'=>'Enter Course Name','keyword' => 'E.g. MBA, BTECH, Software Engineering, Animation etc.' , 'locationInput'=>'Enter Location','location' =>'E.g. Delhi, Pune, Canada etc.'),
                'institutes'=>array('keywordInput'=>'Enter Institute Name','keyword' => 'E.g. IIM, Amity, MAAC, JNU etc.' , 'locationInput'=>'Enter Location','location' =>'E.g. Delhi, Pune, Canada etc.'),
                'articles'=>array('keywordInput'=>'Enter Keywords','keyword' => 'E.g. MBA , Study Abroad, IILM, MCA etc.' , 'locationInput'=>'Enter Location','location' =>'E.g. Delhi, Pune, Canada etc.'),
                'scholarships'=>array('keywordInput'=>'Enter Keywords','keyword' => 'E.g. MBA , Study Abroad, IILM, MBA or MCA etc.' , 'locationInput'=>'Enter Location','location' =>'E.g. Delhi, Pune, Canada etc.'));

	$helpTextMap['institutescourses'] = array();
	if(SHOW_AUTOSUGGESTOR){
		$helpTextMap['institutescourses']['keywordInput'] = 'Enter College or Course Name';
		$helpTextMap['institutescourses']['keyword'] = 'E.g. mba in delhi, animation in pune';
		$helpTextMap['institutescourses']['locationInput'] = 'Enter Location';
		$helpTextMap['institutescourses']['location'] = '';
	} else {
		$helpTextMap['institutescourses']['keywordInput'] = 'Enter College or Course Name';
		$helpTextMap['institutescourses']['keyword'] = 'E.g. MBA,MCA,BBA,Animation,IIM,IIPM etc.';
		$helpTextMap['institutescourses']['locationInput'] = 'Enter Location';
		$helpTextMap['institutescourses']['location'] = 'E.g. Delhi, Pune, Canada etc.';
	}
	
	$questionText = '';
	if(isset($_COOKIE['commentContent']) && ($questionText == '')){
		$questionText = $_COOKIE['commentContent'];
		if((stripos($questionText,'@$#@#$$') !== false) || (stripos($questionText,'@#@!@%@') !== false)){
			$questionText = '';
		}
	}
	$questionText = str_replace('"','&quot;',$questionText);
	
	if(!isset($searchType) || empty($searchType)){
		$searchType = "course";
	}
?>
<script>
function MM_showHideLayers1() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers1.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
  if(v=='hidden')
  {
    document.getElementById('iframe_div').style.display = 'none';
  }
}
function drpdwnSearchOpen(which, divId){       		
	var objElement = which;
	var objDropdown = document.getElementById(divId);
	var objElementTop = obtainPostitionY(objElement);
	var objElementLeft = obtainPostitionX(objElement);
	var objElementHeight = objElement.offsetHeight;
	objDropdown.style.left = (objElementLeft-150)+'px';
	objDropdown.style.top = (objElementTop+objElementHeight)+'px';		
	objDropdown.style.display = 'block';
	objDropdown.style.zIndex= 1500;
	setTimeout('overlayHackLayerForIE("'+divId+'", document.getElementById("'+divId+'"));',1);		
}

var locationBarLength = '<?php echo $inputBox2; ?>';
var keywordBarLength = '<?php echo $inputBox1; ?>';
var tdColoum3 = '<?php echo $coloum3; ?>';
var tdColoum4 = '<?php echo $coloum4; ?>';
var arrayHelpText = eval("eval("+'<?php echo json_encode($helpTextMap);?>'+")");

function getJsonKeyForSearch(val)
{
    return(val.replace(/[^a-zA-Z]*/g,"").toLowerCase())
}
function getHelpTextArrayValue(product , keyname)
{
    var helpTextKey = getJsonKeyForSearch(document.getElementById('tempSearchType').value);
    if(eval("arrayHelpText."+helpTextKey))
    {
        return  eval("arrayHelpText."+helpTextKey+"."+keyname);
    }
    else
    {
        if(keyname == "keywordInput")
        {
            return("Enter Keyword");
        }
        else if(keyname == "keyword")
        {
            return("E.g. MBA, Engineering, XLRI, BBA etc.");
        }
        else if(keyname == "locationInput")
        {
            return("Enter Location");
        }
        else if(keyname == "location")
        {
            return("E.g. Delhi, Pune, Canada etc.");
        }
    }
}
function showRelatedHelpTextOnSearchBar(changeKeywordText, changeLocationText)
{
    var tempSearchType = document.getElementById('tempSearchType').value;

    keywordHelpText = getHelpTextArrayValue(tempSearchType, "keywordInput");
    keywordExample = getHelpTextArrayValue(tempSearchType, "keyword");
    locationHelpText = getHelpTextArrayValue(tempSearchType, "locationInput");
    locationExample = getHelpTextArrayValue(tempSearchType, "location");

    if(changeKeywordText == undefined || changeKeywordText == '1')
    {
        document.getElementById('tempkeyword').value = keywordHelpText;
    }
    document.getElementById('helptextKeyword').innerHTML = keywordExample;
    if(changeLocationText == undefined || changeLocationText == '1')
    {
        document.getElementById('templocation').value = locationHelpText;
    }
    document.getElementById('helptextLocation').innerHTML = locationExample;

}
function selectValueInSearchDD(val)
{
    if(document.getElementById('tempkeyword').value == getHelpTextArrayValue(document.getElementById('tempSearchType').value , "keywordInput") || document.getElementById('tempkeyword').value == "")
    {
        changeKeywordText = '1';   
    }
    else
    {
        changeKeywordText = '0';   
    }
    if(document.getElementById('templocation').value == getHelpTextArrayValue(document.getElementById('tempSearchType').value , "locationInput") ||  document.getElementById('tempkeyword').value == "")
    {
        changeLocationText = '1';   
    }
    else
    {
        changeLocationText = '0';   
    }
	document.getElementById('tempSearchType').value = val;
	if(val=="Ask & Answer" || val =="Articles" || SHOW_AUTOSUGGESTOR_JS)
    {
        var altLength = parseInt(keywordBarLength)+ parseInt(locationBarLength) + 20;
		document.getElementById('tdcoloum_3').style.width = altLength +'px';
        document.getElementById('templocation').value = "";
        document.getElementById('tempkeyword').style.width = altLength +'px';
        document.getElementById('templocation').style.width = '0px';
        document.getElementById('helptextLocation').style.display = "none";
        document.getElementById('templocation').style.display= 'none';
		document.getElementById('tdcoloum_4').style.width = '20px';
    }
    else
    {
        if(document.getElementById('templocation').style.display == "none")
        {
			document.getElementById('tdcoloum_3').style.width = parseInt(tdColoum3) +'px';
			document.getElementById('tdcoloum_4').style.width = parseInt(tdColoum4) +'px';
            document.getElementById('templocation').style.display = '';
            document.getElementById('tempkeyword').style.width = keywordBarLength;
            document.getElementById('templocation').style.width = locationBarLength;
            document.getElementById('helptextLocation').style.display = "";
        }
    }
    showRelatedHelpTextOnSearchBar(changeKeywordText, changeLocationText);
	if(document.getElementById('tempSearchType').value != "Institutes & Courses" && SHOW_AUTOSUGGESTOR_JS){
		var keyele = document.getElementById("tempkeyword");
		if (window.removeEventListener){
			keyele.removeEventListener('keyup',handleInputKeys,false);
		} else if(window.detachEvent){
			keyele.detachEvent('onkeyup',handleInputKeys);
		}
	} else if(document.getElementById('tempSearchType').value == "Institutes & Courses" && SHOW_AUTOSUGGESTOR_JS){
		var keyele = document.getElementById("tempkeyword");
 		if(typeof(handleInputKeys) == "function") {
			if (window.addEventListener){
				keyele.addEventListener('keyup',handleInputKeys,false);
			} else if(window.attachEvent){
				keyele.attachEvent('onkeyup', handleInputKeys);
			}
		}
	}
} 

function addQuestionToCookie(){
	setCookie('commentContent',document.getElementById('questionText').value,0);
	return;
}
function validateQuestionForm_old(Object1,pageKey,action1,idOfForm){
	var isValidated = false;
	try{
		if(validateFields(Object1) == true) {
			isValidated = true;
		}
	} catch (e) { isValidated = false; }
	
	if(isValidated == true){
		/*if(trim(getCookie('user')) == ''){
			showuserLoginOverLay(Object1,pageKey,action1,idOfForm);
			return false;
		}*/
		deleteQuestionPostRelatedCookies();
		return true;
	}else{
		return false;
	}
}
</script>
<div>
			<table cellpadding="0" cellspacing="0" border="0" style="border:1px solid #4a6d9c;background:#fff url(/public/images/grdntBg.png) repeat-x left -740px;border-top:1px solid #FFF;" width="100%" align="center">
				<?php if($product != 'forums') {
					$searchFormStyle='style="display:block;"';
					$askFormStyle='style="display:none;"';
				?>
				<tr>
					<td style="width:<?php echo $coloum1; ?>" height="13"><img src="/public/images/blankImg.gif" width="1" height="13" /></td>
					<td style="width:<?php echo $coloum2; ?>" height="13"><img src="/public/images/blankImg.gif" width="1" height="13" /></td>
					<td style="width:<?php echo $coloum3; ?>" height="13"><img src="/public/images/blankImg.gif" width="1" height="13" /></td>
					<td style="width:<?php echo $coloum4; ?>" height="13"><img src="/public/images/blankImg.gif" width="1" height="13" /></td>
					<td style="width:<?php echo $coloum5; ?>" height="13"><img src="/public/images/blankImg.gif" width="1" height="13" /></td>
				</tr>
				<?php } else { 
					$searchFormStyle='style="display:none;"';
					$askFormStyle='style="display:block;"';
				?>
				<tr>
					<td colspan="5">
						<table cellpadding="0" cellspacing="0" border="0" style="width:<?php echo $insidetableWidthMoz; ?>;*width:<?php echo $insidetableWidthIE; ?>">
							<tr>
								<td style="width:<?php echo $coloum1; ?>" height="32">&nbsp;</td>
								<td style="width:<?php echo $coloum2; ?>">&nbsp;</td>
								<td style="width:<?php echo $coloum3; ?>;font-size:12px" valign="bottom" align="left">
									<ul class="browseCities">
										<li style="width:70px" class="" id="searchTabShow_LI">
											<a href="javascript:void(0);" id="searchTabShow_A" class="" onclick="flipAskQestionAndSearchForm('search');selectValueInSearchDD('Ask & Answer');">Search</a>
										</li>
										<li class="selected" style="width:100px" id="askTabShow_LI">
											<a href="javascript:void(0);"  id="askTabShow_A" class="selected" onclick="flipAskQestionAndSearchForm('askquestion');">Ask Question</a>
										</li>
									</ul>
									<div style="line-height:1px;clear:left">&nbsp;</div>
								</td>
								<td style="width:<?php echo $coloum4; ?>">&nbsp;</td>
								<td style="width:<?php echo $coloum5; ?>">&nbsp;</td>
							</tr>
						</table>
					</td>					
				</tr>
				<?php } ?>
				<tr>
					<td colspan="5">
						<div id="searchField_Row1" name="searchField_Row1" <?php echo $searchFormStyle; ?>>
							<?php
							if(SHOW_AUTOSUGGESTOR){
							?>
								<form id="dummyForm" name="dummyForm" method="get" action="" onsubmit="return false;" style="margin:0;padding:0">
							<?php
							} else {
								?>
								<form id="dummyForm" name="dummyForm" method="get" action="" onsubmit="validateSearch(1,0,1);return false;" style="margin:0;padding:0">
								<?php
							}
							?>
                            <table cellpadding="0" cellspacing="0" border="0" style="width:<?php echo $insidetableWidthMoz; ?>;*width:<?php echo $insidetableWidthIE; ?>">							
										<tr>
											<td style="width:<?php echo $coloum1; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td style="width:<?php echo $coloum2; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td id="tdcoloum_3" style="width:<?php echo $coloum3; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td id="tdcoloum_4"style="width:<?php echo $coloum4; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td style="width:<?php echo $coloum5; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
										</tr>
										<tr>
											<td height="31" valign="top" align="right"><img src="/public/images/search_icon.gif" align="top" class="posImg" /></td>
											<td valign="top" align="left">
												<span>
													<input id="tempSearchType" name="tempSearchType" disabled="true" type="text" style="border:2px solid #4A6D9C;border-right:none;background-color:#FFFFFF;color:#000000;padding:5px 10px 0 8px;height:22px;width:<?php echo $selectBox1; ?>" value="Institutes & Courses" /><img src="/public/images/searchCategoryDropDwn.gif" align="top" class="posImg" onclick="drpdwnSearchOpen(this, 'overlayDen'); MM_showHideLayers1('overlayDen','','show');" />
												</span>
											</td>
											<td valign="top" align="left">
												<?php
												if(SHOW_AUTOSUGGESTOR){
													?>
                                                    <div style="position:relative; z-index:1">
													<input id="tempkeyword" name="tempkeyword" onblur="if(trim(document.getElementById('tempkeyword').value)==''){ document.getElementById('tempkeyword').value = getHelpTextArrayValue(document.getElementById('tempSearchType').value,'keywordInput'); }" onfocus="if(trim(document.getElementById('tempkeyword').value)==getHelpTextArrayValue(document.getElementById('tempSearchType').value,'keywordInput')){ document.getElementById('tempkeyword').value =''; }"  autocomplete="off" value="<?php echo $tempkeyword; ?>" type="text" style="border:2px solid #4A6D9C;background-color:#FFFFFF;color:#000;padding:5px 0 0 8px;height:22px;width:<?php echo  $inputBox1; ?>"/>
                                                    <div id="suggestions_container" style="min-width:573px;_width:573px;display:none; position:absolute; left:0; top:35px; z-index:9999" onclick="handleOnclickOnSuggestionCont(event);"></div></div>
													<?php
												} else {
													?>
													<input id="tempkeyword" name="tempkeyword" onblur="if(trim(document.getElementById('tempkeyword').value)==''){ document.getElementById('tempkeyword').value = getHelpTextArrayValue(document.getElementById('tempSearchType').value,'keywordInput'); }" onfocus="if(trim(document.getElementById('tempkeyword').value)==getHelpTextArrayValue(document.getElementById('tempSearchType').value,'keywordInput')){ document.getElementById('tempkeyword').value =''; }"  autocomplete="off" value="<?php echo $tempkeyword; ?>" type="text" style="border:2px solid #4A6D9C;background-color:#FFFFFF;color:#000;padding:5px 0 0 8px;height:22px;width:<?php echo  $inputBox1; ?>" />
													<?php
												}
												?>
											</td>
											<?php
											if(SHOW_AUTOSUGGESTOR){
											?>
												<td valign="top" align="left">
													<input type="hidden" id="templocation" value="" style="width:0px;"/>
												</td>
											<?php
											} else {
											?>
											<td valign="top" align="left">
												<input name="templocation" id="templocation" onblur="if(document.getElementById('templocation').value==''){ document.getElementById('templocation').value =getHelpTextArrayValue(document.getElementById('tempSearchType').value,'locationInput'); }" onfocus="if(document.getElementById('templocation').value==getHelpTextArrayValue(document.getElementById('tempSearchType').value,'locationInput')){ document.getElementById('templocation').value =''; }" value="<?php echo $templocation; ?>" type="text" style="border:2px solid #4A6D9C;background-color:#FFFFFF;color:#000;padding:5px 0 0 8px;height:22px;width:<?php echo $inputBox2; ?>" />
											</td>
											<?php
											}
											?>
											<td valign="top" align="left">
												<?php
												if(SHOW_AUTOSUGGESTOR){
												?>
													<input type="button" class="searchButtonAll" value="Search" style="position:relative; top:-1px;*top:1px;left:0" onclick="trackUserAutoSuggestion('bc');"/>
												<?php
												} else {
												?>
													<input type="submit" class="searchButtonAll" value="Search" style="position:relative; top:-1px;*top:1px;left:0" />
												<?php
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="font-size:11px" valign="middle" height="22">&nbsp;</td>
											<td style="font-size:11px" valign="middle">&nbsp;</td>
											<?php
											if(SHOW_AUTOSUGGESTOR){
											?>
												<td style="font-size:11px" valign="middle"><span id="helptextKeyword">Eg. Mba in delhi</span></td>
											<?php
											} else {
												?>
												<td style="font-size:11px" valign="middle"><span id="helptextKeyword"><?php echo isset($helpTextMap[$product]['keyword'])?$helpTextMap[$product]['keyword']:"Eg. XLRI, MCA, or GMAT"; ?></span></td>
												<?php
											}
											?>
											<?php
											if(!SHOW_AUTOSUGGESTOR){
											?>
												<td style="font-size:11px" valign="middle"><span id="helptextLocation"><?php echo isset($helpTextMap[$product]['location'])?$helpTextMap[$product]['location']:"Eg. Australia, Karnataka or Delhi"; ?></span></td>	
											<?php
											} else {
											?>
												<td style="font-size:11px" valign="middle"><span id="helptextLocation" style="display:none;"></span></td>	
											<?php
											}
											?>
											<td style="font-size:11px" valign="middle">&nbsp;</td>
										</tr>
									</table>
							</form>
							<form id="searchForm" name="searchForm" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" onsubmit="">
								<input type="hidden" name="keyword" id="keyword" autocomplete="off" value="<?php echo htmlspecialchars($keyword); ?>"/>
								<input type="hidden" name="location" id="location" autocomplete="off" value="<?php echo htmlspecialchars($location); ?>"/>
								<input type="hidden" name="searchType" id="searchType" autocomplete="off" value="<?php echo htmlspecialchars($searchType); ?>"/>
								<input type="hidden" name="cat_id" id="cat_id" autocomplete="off" value="<?php echo htmlspecialchars($catID);?>"/>
								<input name="countOffsetSearch" id="countOffsetSearch" autocomplete="off" value="<?php echo htmlspecialchars($countOffsetSearch); ?>"  type="hidden" />
								<input name="startOffSetSearch" id="startOffSetSearch" autocomplete="off" value="<?php echo htmlspecialchars($startOffSetSearch); ?>" type="hidden" />
							<!--	<input name="subCategory" id="subCategory" autocomplete="off" value="<?php echo htmlspecialchars($subCategory); ?>" type="hidden" />-->
								<input name="subLocation" id="subLocation" autocomplete="off" value="<?php echo htmlspecialchars($subLocation); ?>" type="hidden" />
								<input name="cityId" id="cityId" autocomplete="off" value="<?php echo htmlspecialchars($cityId); ?>" type="hidden" />
								<input name="cType" id="cType" autocomplete="off" value="<?php echo htmlspecialchars($cType); ?>" type="hidden" />
								<!--<input name="durationMin" id="durationMin" autocomplete="off" value="<?php echo htmlspecialchars($durationMin); ?>" type="hidden" />
								<input name="durationMax" id="durationMax" autocomplete="off" value="<?php echo htmlspecialchars($durationMax); ?>" type="hidden" />-->
								<input name="courseLevel" id="courseLevel" autocomplete="off" value="<?php echo htmlspecialchars($courseLevel); ?>" type="hidden" />
								<input name="subType" id="subType" autocomplete="off" value="<?php echo htmlspecialchars($subType); ?>" type="hidden"/>
								<input name="showCluster" id="showCluster" autocomplete="off" value="<?php echo htmlspecialchars($showCluster); ?>" type="hidden" />
								<input name="channelId" id="channelId" autocomplete="off" value="<?php echo $channelId; ?>" type="hidden"/>
								<div id="overlayDen" style="z-index:100;position:absolute; border:2px solid #4a6d9c; background:#FFFFFF;visibility: hidden;" onmouseover="MM_showHideLayers1('overlayDen','','show')" onmouseout="MM_showHideLayers1('overlayDen','','hide')">
									<a href="#" onclick="MM_showHideLayers1('overlayDen','','hide'); selectValueInSearchDD('Institutes & Courses');">Institutes & Courses</a>
									<a href="#" onclick="MM_showHideLayers1('overlayDen','','hide'); selectValueInSearchDD('Ask & Answer');">Ask & Answer</a>
									<a href="#" onclick="MM_showHideLayers1('overlayDen','','hide'); selectValueInSearchDD('Articles');">Articles</a>
								</div>
								<!-- pankaj track params added for tracking search users-->
								<!--<input type="hidden" name="utm_campaign" value="site_search"/>
								<input type="hidden" name="utm_medium" value="internal"/>
								<input type="hidden" name="utm_source" value="shiksha"/>-->
								<!-- Pankaj for autosuggestor query params -->
								<input id="autosuggestor_suggestion_shown" type="hidden" name="autosuggestor_suggestion_shown" value="-1"/>
							</form>
						</div>

<?php 
	if($product == 'forums'): 
		$questionText = isset($questionText)?$questionText:'';
		$questionTextLength = strlen($questionText);
		$base64url = base64_encode(site_url("'".$_SERVER['REQUEST_URI']."'"));
?>
						<div id="askField_Row1" name="askField_Row1" <?php echo $askFormStyle; ?>>
							<form id="askQuestionForm" name="askQuestionForm" method="get" action="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/questionPostLandingPage" onsubmit="try{return validateQuestionForm_old(this,'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionForm');}catch (e){ return false;}" style="margin:0;padding:0">
								<table cellpadding="0" cellspacing="0" border="0" style="width:<?php echo $insidetableWidthMoz; ?>;*width:<?php echo $insidetableWidthIE; ?>">							
										<tr>
											<td style="width:<?php echo $coloum_rValue1; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td style="width:<?php echo $coloum2; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td style="width:<?php echo $coloum3; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td style="width:<?php echo $coloum4; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
											<td style="width:<?php echo $coloum_rValue5; ?>" height="1"><img src="/public/images/blankImg.gif" width="1" height="1" /></td>
										</tr>
										<tr>
											<td valign="top" align="right" height="32">&nbsp;</td>
											<td align="left" valign="middle"><img src="/public/images/askDiscussionSearchIcon.gif" align="absmiddle" class="posImg" /><span style="font-size:17px;font-weight:700">Ask Question</span></td>
											<td valign="top" align="left" colspan="2">
												<input name="questionText" id="questionText" autocomplete="off" value="<?php echo $questionText; ?>" type="text" onblur="enterEnabled=false;" onfocus="enterEnabled=true;" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Question" maxlength="300" minlength="2" required="true" style="border:2px solid #4a6d9c;background-color:#FFFFFF;color:#000;padding:5px 0 0 8px;height:22px;width:<?php echo $inputBoxAsk3; ?>" />
												<input name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" type="hidden" value="<?php echo $base64url; ?>"/>
											</td>
											<td valign="top" align="left">
												<input type="submit" class="searchButtonAllAsk" value="Post Question" style="position:relative; top:-1px;*top:1px;left:2px;" />
											</td>
										</tr>
										<tr>
											<td valign="middle" height="21">&nbsp;</td>
											<td valign="middle">&nbsp;</td>
											<td valign="middle">
												<div class="row errorPlace">
														<div id="questionText_error" class="errorMsg">&nbsp;</div>
												</div>
											</td>
											<td valign="middle" align="right"><span id="questionText_counter"><?php echo $questionTextLength; ?></span>&nbsp;out of 300 characters<span> &nbsp; &nbsp; </span></td>
											<td valign="middle">&nbsp;</td>
										</tr>
									</table>
							</form>
						</div>
<?php endif; ?>
					</td>
				</tr>
            </table>
            
<?php
       if(isset($tempSearchTypeArray[$product]))
       {
           echo "<script>";
           echo "selectValueInSearchDD('".$tempSearchTypeArray[$product]."');";
           echo "</script>";
       }
       else
       {        
           if(isset($searchTextTempArray[$searchType]))
           {
               echo "<script>";
               echo "selectValueInSearchDD('".$searchTextTempArray[$searchType]."');";
               echo "</script>";

           }
       }
?>
</div>
<?php
if(SHOW_AUTOSUGGESTOR) {
?>
	<script type="text/javascript">
		var userAutoSuggestorTrackingData = [];
		var AS_suggestion_shown = -1;
		var autoSuggestorInstance;
		function initializeAutoSuggestorInstance(){
			if (window.addEventListener){
				var ele = document.getElementById("tempkeyword");
				ele.addEventListener('keyup', handleInputKeys, false);
			} else if (window.attachEvent){
				var ele = document.getElementById("tempkeyword");
				ele.attachEvent('onkeyup', handleInputKeys);
			}
			autoSuggestorInstance = new AutoSuggestor("tempkeyword" , "suggestions_container", true);
			autoSuggestorInstance.callBackFunctionOnKeyPressed = handleAutoSuggestorKeyPressed;
			autoSuggestorInstance.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClick;
			autoSuggestorInstance.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressed;
		}
		
		function handleAutoSuggestorKeyPressed(dict){
			userAutoSuggestorTrackingData.push(dict);
		}
		
		function handleAutoSuggestorEnterPressed(dict){
			userAutoSuggestorTrackingData.push(dict);
			if(dict["suggestion_shown"] != undefined){
				AS_suggestion_shown = dict["suggestion_shown"];	
			}
			trackUserAutoSuggestion();
		}
		
		function handleAutoSuggestorMouseClick(dict){
			userAutoSuggestorTrackingData.push(dict);
		}
		
		function handleInputKeys(e){
			if(autoSuggestorInstance){
				autoSuggestorInstance.handleInputKeys(e);	
			}
		}
		
		function trackUserAutoSuggestion(actionType){
			if(autoSuggestorInstance){
				if(actionType != undefined){
					if(actionType == "bc"){
						if(document.getElementById("suggestions_container") && document.getElementById("suggestions_container").style.display == "none"){
							AS_suggestion_shown =  5;		
						} else {
							AS_suggestion_shown =  6;
						}
						var dict = {};
						dict['spn'] = -1;
						dict['ui'] = document.getElementById("tempkeyword").value;
						dict['at'] = "bc";
						dict['sp'] = " ";
						userAutoSuggestorTrackingData.push(dict);
					}
				}
				saveAutoSuggestorData(userAutoSuggestorTrackingData, AS_suggestion_shown);
				return false;	
			} else {
				saveAutoSuggestorData();
			}
		}
		
		function handleOnclickOnSuggestionCont(e) {
			if(!e){
				e = window.event;
			}
			if (e.cancelBubble) {
				e.cancelBubble = true;
			} else {
				if(e.stopPropagation) {
					e.stopPropagation();
				}
			}
		}
		
		function handleClickForAutoSuggestor(e){
			if(document.getElementById("suggestions_container")){
				document.getElementById("suggestions_container").style.display = "none";
			}
		}
		
		function saveAutoSuggestorData(userAutoSuggestorTrackingData, suggestionShown){
			if(TRACK_AUTOSUGGESTOR_RESULTS_JS && autoSuggestorInstance){
				if(document.getElementById('autosuggestor_suggestion_shown')){
					document.getElementById('autosuggestor_suggestion_shown').value = suggestionShown;
				}
				var jsonDecodedStr = getJsonDecodedString(userAutoSuggestorTrackingData);
				var suggestionShownStr = suggestionShown;
				var searchPageStr = "&page=sp";
				var queryString = "&autosuggestor="+jsonDecodedStr+"&autosuggestor_suggestion_shown="+suggestionShownStr + searchPageStr;
				var trackURL = '/searchmatrix/SearchMatrix/logASQueries';
				new Ajax.Request(trackURL,{method:'post', parameters: (queryString), onSuccess:function (response) {
					validateSearch(1,0,1);
					return false;
				}});
				return false;
			} else {
				validateSearch(1,0,1);
				return false;
			}
		}
	</script>
<?php
}
?>
