<?php ob_start('compress'); ?>

<?php
$headerComponents = array(
      'mobilecss' => array('search-widget'),
      'noIndexNoFollow' => true,
      'noHeader' => true
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

<div id="wrapper" data-role="page" class="of-hide">

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
/*if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
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
       <div id="page-header-container" style=""><?php //echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','');?></div>
    </header>
    <div data-role="content" id="search-widget-page" data-enhance="false">       
        <div data-enhance="false" >
            <?php $this->load->view('msearch5/msearchV3/searchWidgetLayer',array('pageName' => 'searchWidgetPage'));?>
            <?php //$this->load->view('/mcommon5/footerLinks');?>
        </div>
    </div>
</div>    

<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >

<?php $this->load->view('/mcommon5/footer');?>    
<?php ob_end_flush(); ?>

<script type="text/javascript">
pageName = 'searchWidgetPage';
  jQuery(document).ready(function($){
    if(typeof searchFilterData == 'undefined') {
                    searchFilterData = null;
        }
    updateSearchFormData(searchFilterData);
    SSW.bindOnloadElements();
    setClassOniphone();
    $('#wrapper').css({'padding-top':'0px'});
    $('#search-widget-page').css({'min-height':'285px'});
    var searchTab = location.hash.split('#');
    if(searchTab[1] == 'exams' || searchTab[1]=='questions')
    {
          toggleSearchTabs($('#mainTabs a[tabname="'+searchTab[1]+'"]').get(0));
          $(document).on('labLoadedJs', function() {
            if(searchTab[1] == 'exams')
              initShikshaSearch(examAutosuggestorOptions);
            else
              initShikshaSearch(questionAutosuggestorOptions);

          });
        setTimeout(function(){
        },100);
    }
    jQuery(window).on( "orientationchange", function( event ) {
        if(event.orientation == "portrait")
        {
            $('#wrapper').css({'padding-top':'0px'});
        }
    });
  });
</script>
