	<div class="wlcm-lyr">
        <div class="logo-img"><img src="/public/mobile5/images/shiksha_logo.png"/></div>
       <div class="div-position"> 
        <p class="sgup-title"><?php echo $customHelpText['heading'];?></p>
		<ul class="sgup-cont">
            <?php foreach($customHelpText['body'] as $key=>$bodyText){ ?>
                    <li><?php echo $bodyText; ?></li>
            <?php } ?>
        </ul>
       </div> 
		<div class="clear clr">
            <a class="wlcmbtn-login flLt" id="wlcmLogin">Login</a>
            <a class="wlcmbtn-login wlcbtn-signup flRt" id="wlcmRegistration">Signup</a>
        </div>
	</div>
	