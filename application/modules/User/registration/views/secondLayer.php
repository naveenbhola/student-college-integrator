<div class="layer-outer" style="width:650px;">
	<?php error_log("ExecConversionTrackingCode -- ".print_r($userData,true)); ?>
	<?php $this->load->view('registration/common/conversionTracking'); ?>

    <!-- Facebook Pixel Code -->
    <!--
    <script>

    !function(f,b,e,v,n,t,s){
        if(f.fbq)return;

        n=f.fbq=function(){
        
        n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};

        if(!f._fbq)f._fbq=n;

        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;

        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window,document,'script','//connect.facebook.net/en_US/fbevents.js');

        fbq('init', '639671932819149');

        fbq('track', " CompleteRegistration");
    </script>

    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=639671932819149&ev=PageView&noscript=1"/></noscript>
    -->
    <!-- End Facebook Pixel Code -->

    <div class="layer-title">
        <a title="Close" class="close" href="#" onClick="shikshaUserRegistration.closeSecondLayer('<?php echo $redirectURL; ?>'); return false;"></a>
		<div class="title">Congratulations! Your details have been successfully submitted</div>
    </div>
    <div class="layer-contents" style="padding:0px 15px 0; *padding-bottom:10px;">
    <p class="student-info">
        Hi <span style="color:orange"><?php echo $userData['name']; ?></span>, please provide additional details below to complete your profile and get maximum benefits.
    </p>
    
    <div class="form-cols" id="secondaryInfoRegistrationForm"><?php echo Modules::run('registration/Forms/secondLayer'); ?></div>

    <div class="connect-cols" style="padding-bottom:30px;">
        <div class="student-info-box">
            <div style="height:103px; text-align:center; position:relative"><img src="/public/images/unified-students.gif" alt="" /></div>
            <div class="box-shadow-unified" style="margin-right:10px;">
            	<div style="padding:50px 0 0 15px">
                <h2>Why Complete your profile?</h2>
                <ul style="padding:0 0 0 18px">
                    <li><strong>Get Connected</strong> Let institutes contact you directly basis your preference</li>
                    <li><strong>Customized Advice</strong> Get personalized expert career counseling</li>
                    <li><strong>Free Alerts</strong> Get alerts for all important dates and events of your interest</li>
                </ul>
                <div style="margin: 0 10px 0 0px;">
                Registering at <b>Shiksha.com</b> is like offloading all your worries with a trusted friend and guide. Try it and feel the difference!
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearFix"></div>
    </div>
</div>
