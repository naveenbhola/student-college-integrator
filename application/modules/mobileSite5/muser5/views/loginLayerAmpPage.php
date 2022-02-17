<?php ob_start('compress'); ?>

<?php
$headerComponents = array(
      'noIndexNoFollow' => true,
      'noHeader' => true
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

<div id="wrapper" data-role="page" style="min-height: 413px;" class="of-hide">

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
/*$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
}else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
        $displayHamburger = true;
}

//Put a check that if Hash value is added, we have to show the Hamburger
if(strpos($_SERVER["REQUEST_URI"], 'showHam') > 0){
    $displayHamburger = true;
}

if($displayHamburger){
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
}
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');*/
?>
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
       <div id="page-header-container" style=""><?php //echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_LDP');?></div>
    </header>
<div data-role="content">  
      <div data-enhance="false" >
            <?php 
                $this->load->view('/muser5/loginLayer', array('isAmpPage'=>'yes'));
                //$this->load->view('/mcommon5/footerLinks');
             ?>
      </div>
  </div>
</div>

<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php $this->load->view('/mcommon5/footer');?>    
<?php ob_end_flush(); ?>
<script type="text/javascript">
      var pageName = 'loginAmpPage';
        <?php if($userId>0){?>
          window.location = '<?php echo $HTTP_REFERER;?>';
        <?php }else{?> 
           jQuery(document).ready(function(){  
                $('#wrapper').css({'padding-top':'0px'});      
                if(typeof(userRegistrationRequest['<?php echo $regFormId; ?>']) == 'undefined'){
                  userRegistrationRequest['<?php echo $regFormId; ?>'] = new UserRegistrationRequest('<?php echo $regFormId; ?>');
                }
                userRegistrationRequest['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($customValidations); ?>);

                registrationForm.bindLoginFields();
                registrationForm.callbackFunction = 'redirectBack';
                registrationForm.trackingKeyId = <?php echo $trackingKeyId;?>;
                registrationForm.callbackFunctionParams = {
                  'HTTP_REFERER':'<?php echo $HTTP_REFERER; ?>',
                  'AMP_FLAG' : true
                };
                
                setTimeout(function() {
                    registrationForm.handleLoginSavedCredentailsBrowserFunc();
                }, 500);
                $('.lyr-cls ').hide();

            });
             function redirectBack(loginStatus, HTTP_REFERER_object){
                HTTP_REFERER_object = JSON.parse(HTTP_REFERER_object);
                if(getCookie('feedback') == 1){
                  reactCallBackSaveFeedback(loginStatus, HTTP_REFERER_object);
                }else{
                  window.location = HTTP_REFERER_object['HTTP_REFERER'];
                }
              }
        <?php } ?>     
</script>
