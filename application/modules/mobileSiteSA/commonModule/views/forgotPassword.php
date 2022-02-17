<?php
$headerComponents = array(
    'cssBundleMobile'         => 'sa-forgot-password-mobile',
    'canonicalURL'    => '',
    'title'           => 'Reset your Password - Shiksha.com',
    'metaDescription' => '',
    'metaKeywords'    => '',
    'firstFoldCssPath'    => 'commonModule/css/forgotPasswordFirstFoldCss',
    'deferCSS' => true
);
$this->load->view("commonModule/headerV2",$headerComponents);
?>
    <div class="layer-header" style="padding:5px 0;">
            <p style="text-align:center">Reset Your Password</p>
    </div>

    	<section class="layer-wrap clearfix">
        	<article class="content-inner2">
			<div class="wrap-title" style="margin:5px 0 15px; font-weight:normal">Please Enter Your Password here</div>
                <form>
                    <input type="hidden" value = "<?php echo $uname?>" id = "uname" name  = "uname"/>
                	<ul class="form-display">
                    	<li id="email_cont">
                            <input type="email" name="email" placeholder="Email ID" id="email" required="true" class="universal-txt" maxlength="125"  minlength="5" />
                            <div id="email_error" class="error-msg" style="display: none;"></div>
                        </li>
                        <li id="passwordr_cont">
                            <input type="password" name="passwordr" placeholder="Enter New Password" value="" id="passwordr" required="true" class="universal-txt" maxlength="20"  minlength="5" />
                            <div id="passwordr_error" class="error-msg" style="display: none;"></div>
                        </li>
                        <li id="confirmpassword_cont">
                            <input type="password" name="confirmpassword" placeholder="Confirm New Password" value="" id="confirmpassword" required="true" class="universal-txt" maxlength="20"  minlength="5" />
                            <div id="confirmpassword_error" class="error-msg" style="display: none;"></div>
                        </li>
                        <li style="margin:0px 0 15px">
                            <a href="javascript:void(0);" onclick = "return validateforgot(this.form)" class="btn btn-default btn-full">Submit</a>
                        </li>
                        <li class="tac" style="margin-bottom:10px;">
                            <a href="<?=SHIKSHA_STUDYABROAD_HOME?>">Cancel</a>
                        </li>
                    </ul>
                </form>
                </article>
        </section>
<?php
$footerComponents = array(
    'pages'=>array(),
    'js'=> array('forgotPassword'),
    'trackingPageKeyIdForReg' => 792,
    'commonJSV2'=>true,
    'loadLazyJSFile'=>false,
);
$this->load->view("commonModule/footerV2",$footerComponents);
?>

<script>
    var homePageUrl = '<?=SHIKSHA_STUDYABROAD_HOME?>';
</script>