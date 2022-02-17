<div class="layer-outer">
    <div class="layer-title">
    	
     <a title="Close" class="close" href="#" onClick="shikshaUserRegistration.closeUnifiedLayer(); return false;"></a>
	<h4><?php echo $layerTitle ? $layerTitle : "Let us find institutes for you"; ?></h4>
         </div>
    <div class="">


<?php if($registrationMessage) { ?>
                        <div class="confirm-bro-msg"><?php echo $registrationMessage; ?></div>
                <?php } ?>

    <p class="student-info">
        Hi <?php echo $userData['name']; ?>, <?php echo $layerHeading ? $layerHeading : "we need a few details from you to suggest you relevant institutes and create your free Shiksha account."; ?>
    </p>
    
	<?php if($showBothIndiaAbroadForms) { ?>
	<ul class="find-inst-form" style="margin-top: 10px; margin-bottom: 0px !important;">
		<li class="pref-row" style="font-size:16px; margin-bottom: 0px !important;">Study Preference: 
        	<input type="radio" name="unifiedFormSelector" <?php if(!$studyAbroad) echo "checked='checked'"; ?> onclick="$('unifiedFormIndia').style.display = ''; $('unifiedFormAbroad').style.display = 'none';" /> India &nbsp;
        	<input type="radio" name="unifiedFormSelector" <?php if($studyAbroad) echo "checked='checked'"; ?> onclick="$('unifiedFormIndia').style.display = 'none'; $('unifiedFormAbroad').style.display = '';" /> Abroad
        </li>
    </ul>
	
	<div id="unifiedFormIndia" class="form-cols" style="width:270px; <?php if($studyAbroad) echo "display:none;"; ?>">
		<?php echo Modules::run('registration/Forms/LDB',$courseGroup,'unified',array('preSelectedCategoryId' => $categoryId,'preSelectedDesiredCourse' => $desiredCourse)); ?>
	</div>
	<div id="unifiedFormAbroad" class="form-cols" style="width:270px; <?php if(!$studyAbroad) echo "display:none;"; ?>">
		<?php echo Modules::run('registration/Forms/LDB','studyAbroad','unified',array('preSelectedCategoryId' => $categoryId,'preSelectedDesiredCourse' => $desiredCourse)); ?>
	</div>
	
	<?php } else { ?>
	
	<div class="form-cols" style="width:270px;">
    <?php
        $courseGroup = NULL;
        if($studyAbroad) {
            $courseGroup = 'studyAbroad';
        }
        echo Modules::run('registration/Forms/LDB',$courseGroup,'unified',array('preSelectedCategoryId' => $categoryId,'preSelectedDesiredCourse' => $desiredCourse));
    ?>
	</div>

	<?php } ?>
    <div class="connect-cols" style="padding-bottom:30px;">
        <div class="student-info-box">
            <div style="margin:0 0 -5px 10px; height:103px; position:relative"><img src="/public/images/unified-students.gif" alt="" /></div>
            <div class="box-shadow-unified">
                <ul>
                    <li><strong>Get Connected</strong> with institutes directly</li>
                    <li><strong>Get Personalized</strong> expert career advice</li>
                    <li><strong>Get Alerts</strong> to important dates and events</li>
                </ul>
            </div>
        </div>
                
        <div class="fb-plugin-box">
            <h4>Students who currently use shiksha</h4>
            <div class="shiksha-fb-plugin">
                <div class="figure"><img src="/public/images/shik-fb-logo.gif" alt="" /></div>
                <div style="margin-left:50px;">
                    <p><strong>Shiksha Cafe</strong> on Facebook</p>
                    <div class="fb-like-btn"><iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fshikshacafe&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=trebuchet+ms&amp;height=21&amp;appId=<?php echo FACEBOOK_API_ID; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:210px; height:21px;" allowTransparency="true"></iframe></div>                    
                </div>
            </div>
            <div id="facepile" style="display: none; border-top:1px solid #D8DEEA; margin-top:10px;">
            <iframe src="//www.facebook.com/plugins/facepile.php?href=http%3A%2F%2Fwww.facebook.com%2Fshikshacafe&amp;action=like&amp;size=medium&amp;max_rows=2&amp;width=300&amp;colorscheme=light&amp;appId=<?php echo FACEBOOK_API_ID; ?>" scrolling="no" frameborder="0" style="border:none; width:280px; height:100px;" allowTransparency="true"></iframe>
            </div>
        </div>
    </div>
    <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
</div>
<div id="fb-root"></div>
<script src="//connect.facebook.net/en_US/all.js"></script>
<script>
//window.fbAsyncInit = function () {
FB.init({
    appId: <?php echo FACEBOOK_API_ID; ?>, // App ID
    status: true, // check login status
    cookie: true, // enable cookies to allow the server to access the session
    xfbml: true  // parse XFBML
});

// Additional initialization code here
FB.getLoginStatus(function (response) {
    if (response.status === 'connected' || response.status === 'not_authorized') {
        $('facepile').style.display = '';
    }
});
//};
</script>
