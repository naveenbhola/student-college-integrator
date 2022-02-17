<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="content-language" content="EN" />
	<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="publisher" href="https://plus.google.com/+shiksha"/> 
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
	<title><?php echo $headerComponents['title']; ?></title>
	
	<?php foreach($headerComponents['css'] as $cssFile) { ?>
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
	<?php } ?>
	
	<?php foreach($headerComponents['js'] as $jsFile) { ?>
	<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
	<?php } ?>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<script> $j = $.noConflict(); </script>
	
	<script>
	function $(element) {
		return ((typeof(element) == 'string')?document.getElementById(element):element);
	}
	</script>

	<style>
	/*Sign Up Registration CSS*/
	.reg-benefit-section{background:#fafafa; border:1px solid #f2f2f2; border-radius:8px 8px 0 0;}
	.reg-benefit-steps{padding:12px 25px 22px; background:#fafafa; color:#666;border-radius:8px 8px 0 0; border-bottom:1px solid #f2f2f2;position:relative; -webkit-box-shadow: 0 8px 10px -8px #bfbfbf; -moz-box-shadow: 0 8px 10px -8px #bfbfbf; box-shadow: 0 8px 10px -8px #bfbfbf;}
	.reg-benefit-steps p{font-size:24px; margin:0 0 18px 0;}
	.reg-benefit-column{color:#666; font-size:16px; font-family:Verdana, Geneva, sans-serif; float:left; width:28%; margin-right:40px;}
	.reg-benefit-column p{padding-top:4px; font-size:18px; margin:0 0 0 65px}
	.reg-benefit-steps .last{width:32%; margin:0 !important;}
	.col-profile-icon, .free-guideBro-icon, .updated-ruleAdm-icon{background:url(<?php echo SHIKSHA_HOME; ?>/public/images/step-sprite.png);display:inline-block;font-style:none; vertical-align:middle; position:relative; float:left;width:55px; height:55px;}
	.col-profile-icon{background-position:0 0;}
	.free-guideBro-icon{background-position:0px -62px;}
	.updated-ruleAdm-icon{background-position:0px -127px;}
	.signup-profile-section{display:table; width:100%;}
	.complete-reg-form{padding:35px 0 35px 65px; width:55%;border-right:1px solid #eee; display:table-cell; vertical-align:top}
	.reg-info-title{color:#f78640; font-size:26px; margin-bottom:5px}
	.complete-reg-form p{font-size:18px;color:#999;}
	.profile-completion-detail{background:#fff; width:45%;padding:30px 20px 28px 30px; color:#333;display:table-cell; vertical-align:top}
	.prev-step-detail{font-size:11px; color:#333; margin:10px 0;}
	.prev-step-detail ul li{margin-bottom:12px;}
	.prev-step-detail ul li label{color:#999; margin-bottom:2px; display:block;}
	.completion-profle-section{border:1px solid #a0bde2; background:#f2f2f2; height:22px; box-sizing:border-box; margin:5px 0 7px; position:relative;}
	.complete-bar{background:#a0bde2;height:21px; box-sizing:border-box;}
	.complete-percent{color:#fff; position:absolute; top:3px; left:10px; font-weight:bold;}
	.complete-reg-form .abroad-register-details-2 {float:left !important; margin-left:0 !important; margin-top:10px !important; width:335px !important;}
	.complete-reg-form .abroad-register-details-2 .abroad-register-head p{font-size:14px !important; color: #333;}
	.complete-reg-form  form{clear:both;}
	.complete-reg-form  form ul li{margin-bottom:12px;}
	.complete-reg-form  form ul li p{font-size:14px !important; color:#333;}
	.complete-reg-form .form-sec{float:none;}
	.complete-reg-form .select-opt-layer p strong{font-size:9px;}
	.complete-reg-form .select-opt-layer p.choose-CountryTitle{font-size:11px !important;}
	.complete-reg-form .select-opt-layer ul li{margin-bottom:12px; float: left;}
	.complete-reg-form .select-opt-layer ol li p{font-size:12px !important;}
	.select-overlap {background: none repeat scroll 0 0 rgba(0, 0, 0, 0); height: 41px; left: 0; position: absolute; top: 0; width: 410px; z-index: 9;}
	</style>
</head>

<body>



<div id="main-wrapper">
	<div id="header" class="clearfix">
		<div class="clearwidth" id="header-top-section">
    <div class="logo-sec">
        <div id="logo" class="flLt"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/logo-abroad.gif" /></div>
    </div>
		</div>
</div>
        <div class="content-wrap clearfix">
            <div class="reg-benefit-section clearwidth">
				<div class="reg-benefit-steps clearwidth">
                	<p>What are the benefits of completing your registration?</p>
                	<div class="reg-benefit-column">
                    	<i class="common-sprite col-profile-icon"></i>
                    	<p>Find the right colleges to suit your profile</p>
					</div>
                    <div class="reg-benefit-column">
                    	<i class="common-sprite free-guideBro-icon"></i>
                    	<p>Download free college brochures and student guides</p>
					</div>
                    <div class="reg-benefit-column last">
                    	<i class="common-sprite updated-ruleAdm-icon"></i>
                    	<p>Stay updated on visa rules and admission guidelines</p>
					</div>
                </div>
                
                <div class="signup-profile-section">
                    <div class="complete-reg-form">
                        <div class="reg-info-title clearwidth">Complete Your Information</div>
                        <p>Tell us what & where you want to study?</p>
			
		      <?php echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'twoStepRegister'); ?>
		      
                    </div>
                    <div class="profile-completion-detail">
                         <div style="width:335px;">
                            <div class="completion-profle-section">
                                <div class="complete-bar" style="width:50%"></div>
                                <div class="complete-percent">50%</div>
                            </div>
                            <p style="line-height:18px">Your profile is 50% complete. Complete your profile to help us get closer to your dream college.</p>
                            <div class="prev-step-detail">
                             <ul>
                                <li>
                                    <label>Your name</label>
                                    <p><?php echo $abroadShortRegistrationData['firstName'].' '.$abroadShortRegistrationData['lastName']; ?></p>
                                </li>
                                <li>
                                    <label>Email id</label>
                                    <p><?php echo $abroadShortRegistrationData['email']; ?></p>
                                </li>
                                <li>
                                    <label>Mobile no</label>
                                    <p><?php echo $abroadShortRegistrationData['mobile']; ?></p>
                                </li>
                                <li>
				<?php if(!empty($abroadShortRegistrationData['examsAbroad'])) { ?>
                                    <label>Exams scores</label>
				    <p>
				    <?php
				    global $examGrades;
				    global $examFloat;
				    $examScoreText = '';
				    
				    foreach($abroadShortRegistrationData['examsAbroad'] as $examName => $examScore) {
					if(!empty($examGrades[$examName])) {
						$examScoreText .= $examName.': '.$examGrades[$examName][intval($examScore)].' | ';
					}
					else if($examFloat[$examName]) {
						$examScoreText .= $examName.': '.$examScore.' | ';
					}
					else {
						$examScoreText .= $examName.': '.intval($examScore).' | ';
					}
				    }
				    
				    echo rtrim($examScoreText, ' | ');
				    ?>
				    </p>
				<?php
				      }
				      else if(!empty($abroadShortRegistrationData['passport'])) {
				?>
				    <label>Passport</label>
                                    <p><?php echo ucfirst($abroadShortRegistrationData['passport']); ?></p>
				<?php } ?>
                                 </li>
                                 <li>
                                 <label>Planning to join in</label>
                                 <p><?php echo date('Y', strtotime($abroadShortRegistrationData['whenPlanToGo'])); ?></p>
                                 </li>
                            </ul> 
                            </div>
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
</div>
</div>
</body>
</html>

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<script>
var mailerStudyAbroadFullRegister = true;
$j('#finalStepSubmit').html('Save & Update');
</script>
