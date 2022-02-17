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
                //$this->load->view('/mcommon5/footerLinks');
             ?>
      </div>
  </div>
</div>

<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php $this->load->view('/mcommon5/footer');?>
<script type="text/javascript">
    var pageName = 'registrationAmpPage';
    var fromwhere = '<?php echo $fromwhere ?>';
    <?php
     if($userId>0 && $regType!='shortRegistration'){?>
        window.location = '<?php echo $HTTP_REFERER;?>';
    <?php }else{?>
        $('#wrapper').css({'padding-top':'0px'});
          var formData = {
              'trackingKeyId': '<?php echo $trackingKeyId;?>',
              'customFields': <?php echo $customFields;?>,
              'callbackFunction': 'redirectBack',
              'callbackFunctionParams':{
                'HTTP_REFERER':'<?php echo $HTTP_REFERER; ?>',
                'trackData':'<?php echo $trackData; ?>',
                'AMP_FLAG':true
              },
          };

      registrationForm.showRegistrationForm(formData);

      function redirectBack(loginStatus, HTTP_REFERER_object){

        HTTP_REFERER_object = JSON.parse(HTTP_REFERER_object);

        if(fromwhere == 'allCollegePredictor'){
          reactCallBackCollegePredictorTracking(loginStatus, HTTP_REFERER_object);
        }else{
          if(typeof(WebView) == 'undefined'){
            window.location = HTTP_REFERER_object['HTTP_REFERER'];
          }
          else{
            WebView.onRegistrationDone('',true);
          }
        }
        if(getCookie('feedback') == 1){
          reactCallBackSaveFeedback(loginStatus, HTTP_REFERER_object);
        }
      }
  <?php } ?>
</script>
<?php ob_end_flush(); ?>