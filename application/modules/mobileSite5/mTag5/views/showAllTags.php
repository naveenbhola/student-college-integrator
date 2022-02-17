<?php ob_start('compress'); ?>
<?php
  $preMetaString = $startOffSet == 1 ? '' : $startOffSet." - ";
  
  $headerComponents = array(
      'mobilecss' => array('style'),
      'm_meta_title' => $preMetaString."Popular Tags - Shiksha.com",
      'm_meta_description' => $preMetaString."Browse list of education topics to find questions, answers, and discussions on colleges, courses, exams, careers, admission, and more."
        );
  $this->load->view('/mcommon5/header',$headerComponents);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>

<div id="wrapper" data-role="page" style="min-height: 413px;padding-top:40px;" >	

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
}
else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
        $displayHamburger = true;
}
if($displayHamburger){
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
}
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>

    <header id="page-header"  data-role="header" class="header ui-header-fixed" data-position="fixed">
      <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger);?>
    </header>

   <div data-role="content" data-enhance="false">
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
     <div class="notification-fix">
         <div class="nrml-div"> 
              <div class="group-col">
                 <h1 class="group-h">All Tags</h1>
              </div>  
              <?php $this->load->view('tagSuggestor');?>
        </div>   
         <div class="notify-col">
           
           <div class="notify-list">
             <ul class="notify-list-ul">
              <?php
              foreach ($tags as $key => $value) {?>
                <li><a href="<?php echo $value['url'];?>" id="<?php echo $value['tag_id'];?>"><?php echo $value['tag_name'];?></a></li>  
              <?php }
              ?>
             </ul>
           </div>
         </div>
          <div class="pagnation-col">
                      <?php echo $paginationHTMLForGoogle;?>
                   </div>               
      </div>
    <?php $this->load->view('/mcommon5/footerLinks'); ?>
   </div>
</div>
<?php $this->load->view('/mcommon5/footer', array("jsMobileFooter" => array('ana')));?>
<?php echo includeJSFiles('shikshaMobileWebsiteTour'); ob_end_flush(); ?>

<script>
closeSugLayer();

$(document).ready(function(){
    window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
    initializeWebsiteTour('cta', 'mobileQuestion', contentMapping);
});

</script>