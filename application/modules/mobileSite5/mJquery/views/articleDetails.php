<?php 
ob_start('compress');
$order   = array("\r\n", "\n", "\r","\t","<br>","<br />","<br/>","&nbsp;");
$replace = '';
$blogDescriptionObj = $blogObj->getDescription();
if($blogObj->getBlogLayout() == 'qna'){
    $text = $blogDescriptionObj[0]->getQuestion();
}else if($blogObj->getBlogLayout() == 'slideshow'){
    $text = $blogDescriptionObj[0]->getDescription();
}else{
    $text = $blogDescriptionObj[0]->getDescription();
}
//$text = $blogDescription[0]['description'];
$text = str_replace($order, $replace, $text);
$text = preg_replace("/(\<script)(.*?)(script>)/si", "", $text);
$text = strip_tags($text);
$text = str_replace("<!--", "&lt;!--", $text);
$text = preg_replace("/(\<)(.*?)(--\>)/mi", "".nl2br("\\2")."", $text);
$search = array('@<script[^>]*?>.*?</script>@si',
		'@<[\/\!]*?[^<>]*?>@si',
		'@<style[^>]*?>.*?</style>@siU',
		'@<![\s\S]*?--[ \t\n\r]*>@'
	    );
$text = preg_replace($search, '', $text);
$metaDescription = substr(trim($text), 0, 160);

if($blogObj->getSeoDescription() != '') {
    $metaDescription = $blogObj->getSeoDescription();
    $metaDescription = substr(trim($metaDescription), 0, 160);
}
$seoTitle = $blogObj->getTitle();
if($blogObj->getSeoTitle() != '') {
    $seoTitle = $blogObj->getSeoTitle();
}
$canonicalURLAdd = '';
if( isset($canonicalURL) && $canonicalURL!=''){
    $canonicalURLAdd = $canonicalURL;
}
$headerComponents = array(
    'm_meta_title'=>$seoTitle,
    'm_meta_description'=>$metaDescription,
    'canonicalURL'=>$canonicalURLAdd,
    'nextURL'=>$nexturl,
    'previousURL'=>$previousurl,
    'pageType' => 'articlePage'
); ?>



<?php $this->load->view('/mcommon5/header_jq', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_ARTICLE_DETAILS_PAGE',1);
?>

<style type="text/css">
<?php 
    $this->load->view('mobile5CSS'); 
    $this->load->view('mcommonCSS'); 
    $this->load->view('jqueryMobileCSS'); 
?>
</style>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;">
    <?php
    //Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
    $displayHamburger = false;
    if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
    }else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
        $displayHamburger = true;
    }    
    if($displayHamburger){
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburgerDummy','mypanel');
    }
    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
        <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader', $displayHamburger);?>
    </header>
    <?php $this->load->view('articleDetailHeader'); ?>
    <div data-role="content">
        <div data-enhance="false">
            <?php 
            $blogDescriptionObj = $blogObj->getDescription();
            if($blogObj->getBlogLayout() != '') {
                switch($blogObj->getBlogLayout()){
                    case 'slideshow':$this->load->view('blogDetailsSlideShow');
                        break;
                    case 'qna':$this->load->view('blogDetailsQnA');
                        break;
                    default: $this->load->view('blogDetailDefault');
                        break;
                }
            }   
            ?>
            <?php 
            if($streamCheck == 'fullTimeMba'){
            ?>
                <div id="mbaToolsWidget" data-enhance="false">
                    <div style="margin: 20px; text-align: center;"><img border="0" src="/public/mobile5/images/ajax-loader.gif"></div>
                </div>
            <?php
            }
            ?>
            <?php $this->load->view('showRelatedArticles');?>
            <?php $this->load->view('/mcommon5/footerLinks');?>
         </div>
     </div>
	<?php //echo Modules::run('mcommon5/MobileSiteBottomNavBar/bottomNavBar','articlePage', $CategoryId);?>
</div>

<!-- Jquery-mobile and jquery-css -->

<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion('shiksha-mobile-jquery','nationalMobile'); ?>"></script>

<!-- <link rel="stylesheet" href="/public/mobile5/css/shiksha-mobile-jquery.css"> -->
<!-- <script type="text/javascript" src="/public/mobile5/js/shiksha-mobile-jquery.js"></script> -->

<?php $this->load->view('/mcommon5/footer_jq');?>

<script type="text/javascript">
    $(document).ready(function(){
        lazyLoadCss('//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("userRegistrationMobile","nationalMobile");?>');
    });

    // Load the Tools to decide MBA widget and bind the sliders
    new loadToolstoDecideMBACollegeWidget("mbaToolsWidget","ARTICLEPAGE_MOBILE").loadWidget();
    <?php if(!in_array($blogObj->getBlogLayout(), array('slideshow','qna'))){ ?>
    $(window).on('load',function(){
        if(typeof $("img.lazy").lazyload == "function"){
            $("img.lazy").lazyload({effect:"fadeIn",threshold:100});
        }
    });
<?php } ?>
</script>
<?php if($blogId == 13149){
    if($showCounter){
?>
<script>
        var deadline = '<?php echo $resultTime;?>';
        initializeClock('clockdiv', deadline);
        
</script>
<?php }?>
<script>
        initialiseDatePicker();
        CollegeResultData = new CollegeResultData({applicationNoField:'application_no_input' , dateOfBirthField : 'date_of_birth_input'});
        CollegeResultData.bindElementsAndInitialize();
</script>
<?php } ?>
