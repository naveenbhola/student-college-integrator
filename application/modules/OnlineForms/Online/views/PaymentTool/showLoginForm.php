<?php
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('online-styles','header','raised_all','mainStyle','cal_style'),
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','prototype','CalendarPopup','imageUpload','user','onlineForms'),
				      'title'	=>	$seo_title,
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>$seo_desc,	
				      'metaKeywords'	=>$seo_keyword,
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false,
				      'showApplicationFormHeader' => false
				   );

   $this->load->view('common/header', $headerComponents);
   $this->load->view('common/calendardiv');
?>   
<script>
var isUserLoggedInSystem = '<?php echo $userId;?>';
var urlToRedirect = '<?php echo $urlToRedirect;?>';
</script>
<div id="appsFormWrapper">



<div style="display:block;" id="userLoginOverlay_online" >
	<div>
		<input type = "hidden" name = "loginaction_ForAnA" id = "loginaction_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflag_ForAnA" id = "loginflag_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflagreg_ForAnA" id = "loginflagreg_ForAnA" value = ""/>
		<input type = "hidden" name = "loginactionreg_ForAnA" id = "loginactionreg_ForAnA" value = ""/>
		<input type = "hidden" name = "tyepeOfRegistration_ForAnA" id = "tyepeOfRegistration_ForAnA" value = ""/>


		<!--Login Layer Starts here-->
		<div class="loginLayer" id="loginLayer">
		    <div class="layerContent">

			<!-- Login form is inside: Start -->
			<div class="loginLeft" id="userLoginOverlay_ForAnA_loginform" style ="display:block;margin-left:300px;margin-top:20px;">
				<?php if($userId<=0){
					echo "<div style='margin-bottom:30px;font-size:16px;' class='bld'>Please login to access the page.</div>";
				}
				else{
					echo "<div style='margin-bottom:30px;font-size:16px;' class='bld'>You are not authorized to access this page. Please login again.</div>";	
				}
				?>
				<form id="LoginForm_ForAnA" action="/user/Login/submit" onsubmit="if(validateLoginForAnA(this) != true){return false;}; new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResponseForOnline(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post" novalidate="novalidate">

				<input type="hidden" name="typeOfLoginMade" id="typeOfLoginMade" value="veryQuck" />
				<input type = "hidden" name = "mpassword_ForAnA" id = "mpassword_ForAnA" value = ""/>
				<ul>
				    <li>
					<label>Login Email Id:</label>
					<div class="fieldBoxLarge">
						<input class="textboxLarge" type="text" id = "username_ForAnA" name = "username_ForAnA" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" minlength = "10" />
						<div style="display:none"><div class="errorMsg" id= "username_ForAnA_error"></div></div>
					</div>
				  </li>
				  <li>
					<div id="passwordPlaceHolder_ForAnA">
					      <label>Password:</label>
					      <div class="fieldBoxLarge">
						    <input type="password" id = "password_ForAnA" name = "password_ForAnA" validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password" class="textboxLarge" />
						    <div style="display:none"><div class="errorMsg" id="password_ForAnA_error"></div></div>
					      </div>
					</div>
				  </li>

				  <div id="remembermePlaceHolder_ForAnA" style="display:none;">
				  </div>
				  
				  <li>
					<div id="loginButtonPlaceHolder_ForAnA" class="paddLeft128">
					    <input type="submit" value="Login" title="Login" class="attachButton"/> <br />
					    <div class="clearFix spacer10"></div>        
					</div>
				  </li>
  
				</ul>

				</form>

			</div>
			<!-- Login form is inside: Ends -->



			<div class="clearFix"></div>
		    </div>
		    
		</div>
		<!--Login Layer Ends here-->

  

    </div>
</div>
</div>
<script>
    addOnBlurValidate($('RegistrationForm_ForAnA'));
    addOnBlurValidate($('LoginForm_ForAnA'));
</script>


<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 
<script>
var OnlineForm = {};
OnlineForm.displayAdditionalInfoForInstitute = function (style,divId) {
	if($(divId)) {
		$(divId).style.display = style;
	}
}
</script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormCommon"); ?>"></script>
