<?php
    $taburl= site_url('enterprise/Enterprise');
    $headerComponents = array(
        'css'	=>	array('footer','category-styles','common_new','ent-home'),
        'js'	=>	array('common','user','enterprise'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'title'	=>	'Enterprise Shiksha Login',
        'taburl' => site_url(''),
        'bannerProperties' => array('pageId'=>'ENTERPRISE_HOME', 'pageZone'=>'TOP'),
        'metaKeywords'	=>''
    );
    
    $this->load->view('enterprise/headerCMSCommon.php', $headerComponents);
?>
<?php
$CI = & get_instance();
$CI->load->library('security');
$CI->security->setCSRFToken();
?>

<?php
    $attributes = array('name' => 'login', 'onSubmit' => 'return validateFields(this);');  
?>
<div class="ent-home-topRow">
    <div class="topRow-child">Institutes outside India can call us on <strong>Toll Free #1800-717-1094</strong></div>
</div>

<div id="content-wrapper">
    <div class="wrapperFxd">
	<div id="content-child-wrap" style="padding:0; margin:0; width:100%;">
	    <div id="ent-header" style="padding:10px;">
		<div class="shik-logo">
		    <a title="Shiksha.com" href="<?php echo SHIKSHA_HOME; ?>"><img border="0" class="flLt" title="Shiksha.com" alt="Shiksha.com" src="/public/images/nshik_ShikshaLogo1.gif"></a>
		</div>
		<p>14000+ institutes listed till date<br /> <em>India's no.1 and fastest growing education portal</em></p>
		<div class="clearFix"></div>
	    </div>
	    <div id="carausel-box">
            	<div class="slider-box">
		    <div style="width:580px; overflow: hidden;">
			<ul style="width:1740px; position: relative; left:0px;" id="slideContainer">

			    <li id="slide1" style="float: left; width:580px;">
							    <h3>Showcase Your Institute</h3>
							    <div class="slider-figure"><img src="/public/images/enterprisebanner2.jpg" /></div>
				<p class="info-txt">Add your course listings and give detailed information to attract students</p>
			    </li>
			    <li id="slide2" style="float: left; width:580px;">
							    <h3>Get High Quality Leads</h3>
							    <div class="slider-figure" style="height:190px; position:relative;">
							    <ol class="slide-items">
								    <li>Faster and Higher reach to market.</li>
								    <li>New, HOT and highly responsive Leads.</li>
								    <li>Get advantage by access to leads before competitors.</li>
								    <li>Increase ROI of your enterprise account.</li>
							    </ol>
							    <div class="lead-img"></div>
				</div>
				<p class="info-txt" style="padding-bottom:25px;">Access to New, Hot and highly responsive leads before competitors</p>
			    </li>
						    <li id="slide3" style="float: left; width:580px;">
				<h3>Manage Your Data Better</h3>
				<div class="slider-figure" style="height:210px;"><img src="/public/images/enterprisebanner1.jpg" /></div>
				<p class="info-txt" style="padding-bottom:5px;">View institute activity feed, performance, responses and queries all in one place with interactive reports and graphs</p>
			    </li>
						    <div class="clearFix"></div>
			</ul>
		    </div>
                    <ol class="controls">
                    	<li id="slidecontrol1" class="active" style="cursor:pointer;" onclick="enterpriseSlide(1);"></li>
                        <li id="slidecontrol2" style="cursor:pointer;" onclick="enterpriseSlide(2);"></li>
                        <li id="slidecontrol3" style="margin-right:0; cursor:pointer;" onclick="enterpriseSlide(3);"></li>
                    </ol>
                    <div class="next-prev-box">
                    	<a href="javascript:void(0)" class="prev-arr" title="Previous" onclick="enterpriseSlideLeft();"></a>
                        <a href="javascript:void(0)" class="next-arr" title="Next" onclick="enterpriseSlideRight();"></a>
                    </div>
                </div>
                <div class="ent-login-panel" style="min-height:335px;">
                    <div id="LoginBox" class="child-panel">
			<form method="post"  onSubmit="if(validateLOGIN() != true){ return false;} new Ajax.Request('/user/Login/submit',{ onSuccess:function(request){redirectToCms(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" id="loginForm">

				<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />

			    <input name = "mpassword" id = "mpassword1"  type="hidden" value="" />
			    <h4><i class="icon-user"></i>Existing users, Login here</h4>
			    <ul>
				<li>
				    <label>Email id</label>
				    <div>
					<input type="text" class="universal-txt-field" size="20" name="username" id="username" maxlength="125" minlength="5" validate="validateEmail" required="true" caption="Email id"/>
				    </div>
				    <div class="errorPlace">
                                            <div class="errorMsg" id="username_error"></div>
                                    </div>
				</li>
				
				<li>
				    <label>Password</label>
				    <div>
					<input type="password" class="universal-txt-field" size="20" id="password1" type="password" value="" maxlength="25" minlength="2" validate="validateStr" required="true" caption="Password"/>
				    </div>
				    <div class="errorPlace">
					<div class=" errorMsg" id="password1_error"></div>
				    </div>
				</li>
				<li>
				    <input type="submit" value="Login" class="orange-button flLt"/>
				    <a class="flRt" href="javascript:void(0)" style="margin-top:6px;" onClick="showForgotDiv();">Forgot Password?</a>
				    <div class="clearFix"></div>
				</li>
			    </ul>
			</form>
                    </div>
		    <div id="forgotDiv" class="child-panel" style="display:none; margin-bottom:41px;">
                        <h4><i class="icon-user"></i>Type email to reset password</h4>
			<ul>
			    <li>
				<label>Email id</label>
				<div>
				    <input type="text" class="universal-txt-field" size="20" id="forgotEmail" type="text" value="" maxlength="125" minlength="5" validate="validateEmail" caption="Password"/>
				</div>
				<div class="errorPlace" >
                                    <div class="errorMsg" id="forgotEmail_error"></div>
                                </div>
			    </li>
			    <li>
				<input type="button" value="Send" class="orange-button flLt" onclick="javascript:sendForgotEmail();"/>
				<a class="flRt" href="javascript:void(0)" style="margin-top:6px;" onClick="showLoginAgain();">Go Back and Login</a>
				<div class="clearFix"></div>
			    </li>
			</ul>
                    </div>
                    <div id="edit-section">
                    	<h4><i class="icon-edit"></i>Post, edit and delete FREE listings.</h4>
                        <div style="margin-left:30px;"><input type="button" value="Create an Account" class="orange-button" onClick="window.location='<?php echo site_url().'/enterprise/Enterprise/register';?>'" /></div>
                    </div>
                    <div class="login-shadow"></div>
                </div>
                <div class="clearFix"></div>
            </div>
            
            <div id="contact-section">
            	<h5>For a personalized campaign for your institute mail us at <a href="mailto:sales@shiksha.com">sales@shiksha.com</a> or<br />
		    <p><i class="icon-contact"></i>Contact us at:</p>
		</h5>
                
                <ul class="contact-items">
                    <li><span>North Zone (Prashant): +91 9818275222</span></li>
                    <li><span>South Zone (Praveen): +91 9886401542</span></li>
                    <li><span>East Zone (Biswakesh): +91 9836511010</span></li>
                </ul>
                
                <ul class="contact-items">
                    <li><span>West Zone (Saurabh) : +91 9594066280</span></li>
                    <li><span>International Sales (Nandita): +91 9811697252</span></li>
                </ul>
		<div class="contact-figure"></div>
                <div class="clearFix"></div>
            </div>
            
            <!--Code Ends here-->
        </div>
    </div>
    <?php $this->load->view('enterprise/footer'); ?>
</div>

<script>
var slideWidth = 580;
var numSlides = 3;
var currentSlide = 0;

var interval;
interval = setInterval(function(){changeSlider();},3000);
	
function enterpriseSlideRight()
{
	clearInterval(interval);
	currentSlide--;
	if (currentSlide == -3) {
		currentSlide = 0;
	}
	var shift = slideWidth * currentSlide;
	$j('#slideContainer').animate({left:shift+'px'});
	changeActive(Math.abs(currentSlide)+1);
	interval = setInterval(function(){changeSlider();},3000);
}
function enterpriseSlideLeft()
{
	if (currentSlide < 0) {
		clearInterval(interval);
		currentSlide++;
		var shift = slideWidth * currentSlide;
		$j('#slideContainer').animate({left:shift+'px'});
		changeActive(Math.abs(currentSlide)+1);
		interval = setInterval(function(){changeSlider();},3000);
	}
}
function enterpriseSlide(i)
{
	clearInterval(interval);
	currentSlide = (i-1)*-1;
	var shift = slideWidth * currentSlide;
	$j('#slideContainer').animate({left:shift+'px'});
	changeActive(Math.abs(currentSlide)+1);
	interval = setInterval(function(){changeSlider();},3000);
}
	
function changeSlider()
{
	clearInterval(interval);
	currentSlide--;
	if (currentSlide == -3) {
		currentSlide = 0;
	}
	var shift = slideWidth * currentSlide;
	$j('#slideContainer').animate({left:shift+'px'});
	changeActive(Math.abs(currentSlide)+1);
	interval = setInterval(function(){changeSlider();},3000);
}

function changeActive(a)
{
	for (var i=1;i<=numSlides;i++) {
		$('slidecontrol'+i).className = '';
	}
	$('slidecontrol'+a).className = 'active';
}
	
function pauseSlider()
{
	clearInterval(interval);
}
	
function resumeSlider()
{
	interval = setInterval(function(){changeSlider();},3000);
}	

function validateLOGIN()
{
	var flag = validateFields($('loginForm'));
	if (flag!=true)
	{
		return false;
	}
	else
	{
		document.getElementById('mpassword1').value = (document.getElementById('password1').value);
		return true;
	}
}

function redirectToCms(response){
	if (response == 0)
	{
		$('password1_error').parentNode.style.display = "inline";
		$('password1_error').innerHTML = "Invalid Login or Password";
	}
	if(response == 'invalid')
	{
		$('password1_error').parentNode.style.display = "inline";
		$('password1_error').innerHTML = "You are no longer a valid shiksha user";
	}
	if(response != 0 && response != 'invalid')
	{
		window.location.replace('/enterprise/Enterprise');
	}

}

function showForgotDiv(){
	document.getElementById('LoginBox').style.display = 'none';
	document.getElementById('forgotDiv').style.display = '';
}

function showLoginAgain(){
	document.getElementById('forgotDiv').style.display = 'none';
	document.getElementById('LoginBox').style.display = '';
}

$j(function(){
	$j('#slideContainer').mouseout(function(){
		resumeSlider();
	}).mouseover(function(){
		pauseSlider();
	});
});
fillProfaneWordsBag();
setCookie('_groupFilter','');
</script>
