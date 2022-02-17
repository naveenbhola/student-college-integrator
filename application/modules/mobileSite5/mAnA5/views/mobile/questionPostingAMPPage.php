<?php ob_start('compress'); ?>

<?php
$headerComponents = array(
      'noIndexNoFollow' => true
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

<div id="wrapper" data-role="page" style="min-height: 413px;" class="of-hide">

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
/*if(!$_SERVER['HTTP_REFEiRER']){  //If no referer is defined, show Hamburger menu
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
       <div id="page-header-container" style=""><?php // echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_LDP');?></div>
    </header>
<div data-role="content">  
      <div data-enhance="false" >
            <?php 
                $this->load->view('/mAnA5/mobile/questionpostingLayer');
             ?>
      </div>
  </div>
</div>

<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php $this->load->view('/mcommon5/footer');?> 
<?php ob_end_flush(); ?>
  <input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP" value="<?=$courseId;?>">
  <input type="hidden" id="instituteIdQP" name="instituteIdQP" value="<?=$instituteId;?>">
  <input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="<?=$actionType;?>">
  <input type="hidden" id="listingTypeQP" name="listingTypeQP" value="institute">
  <input type="hidden" id="exam_ask_id" name="exam_ask_id" value="<?=$examId?>">
  <input type="hidden" id="exam_group_ask_id" name="exam_group_ask_id" value="<?=$groupId?>">
<script>
  var pageName = 'quesAmpPage';
  jQuery(document).ready(function($){
    $('#wrapper').css({'padding-top':'0px'});
    
    var cookieVal = getCookie("asked_q");
    if(cookieVal) {
      $('#ques_title_ana').val(cookieVal).click().focus();
      handlePastedTextInTextField('ques_title_ana');
      setCookie("asked_q", '', 1, 'seconds');
    }
  });
</script>
