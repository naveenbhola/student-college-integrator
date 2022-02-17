<?php ob_start('compress');

$headerComponents = array(
      'm_meta_title' => 'JEE Result',
      'm_meta_description' => 'JEE Result',
      'm_meta_keywords' => ' ',
      'noIndexNoFollow' => 1
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

<style>
  .jee-page * {
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box; }
  body,.jee-page{
    background: #f1f1f1;
    margin: 0;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-family: 'Open Sans', sans-serif; 
    margin:0;
    padding:0;
  }
  .jee-page{
      padding:0px 10px;
  }
  .jee-page h1,
  .jee-page h2,
  .jee-page h3,
  .jee-page p {
    margin: 0; }
  .jee-page ol, .jee-page li,
  .jee-page ul {
    list-style: none;
    margin: 0;
    padding: 0; }
  .jee-page a {
    text-decoration: none; }
  .jee-page .jee-result-iframe {
    width: 100%;
    border: 0px;
    height: 780px; }
  .jee-page .gap {
    margin: 0 0 20px; }
  .jee-page .btn-primary {
    outline: 0;
    -webkit-appearance: none;
    box-sizing: content-box; }
  .jee-page .btn-primary {
    background: #f29d37;
    color: #fff;
    border: 1px solid #f29d37; }
  .jee-page .btn-primary {
    text-align: center;
    vertical-align: middle;
    text-transform: capitalize;
    font-size: 14px;
    font-weight: 400;
    cursor: pointer;
    height: 30px;
    touch-action: manipulation;
    white-space: nowrap;
    background-image: none;
    line-height: 30px;
    padding: 0px 30px;
    display: inline-block; }
  .jee-page .btn-primary:hover {
    background: #ee9521; }
  .jee-page .views-ol h2.rgt-title {
    font-size: 14px;
    color: #666;
    font-weight:600;
    margin-bottom: 10px; }
  .jee-page .views-ol ul > li a,
  .jee-page .views-ol > a {
    color: #00a5b5;
    font-size: 13px; }
  .jee-page .col-md-3,
  .jee-page .col-md-9,
  .jee-page .col-md-12 {
    position: relative;
    min-height: 1px;
 }
  .jee-page .col-md-3,
  .jee-page .col-md-9,
  .jee-page .col-md-12 {
    float: none; }
  .jee-page .col-md-3 {
    width: 100%; }
  .jee-page .col-md-9 {
    width: 100%; }
  .jee-page .col-md-12 {
    width: 100%; 
    margin-top: 15px;
    margin-bottom: 10px;}
  .jee-page .group-card {
    position: relative;
    border: 1px solid;
    padding: 20px;
    border-color: #e5e6e9 #dfe0e4 #babbbd;
    background: #fff; }
  .jee-page .col-md-9 .group-card{
      padding:0px;
  }
  .jee-page .almuni-h3 {
    font-size: 16px;
    color: #00a5b5;
    font-weight: 600;
    margin: 0px 0px 10px 0px; }
  .jee-page .top-b {
    margin: 20px 0 0px;
    width: 100%;
    box-sizing: border-box; }
  .jee-page .text14 {
    font-size: 14px;
    color: #333; }
  .jee-page .bg-img {
    background: #fff url("/public/images/bg-img.png") no-repeat top center;
    padding-top: 90px; }
  .holds-the-iframe {
   background:url(/public/mobile5/images/ShikshaMobileLoader.gif) center center no-repeat;
  }
</style>

<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>

<div id="wrapper" data-role="page" style="min-height: 413px;" class="of-hide">

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
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
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>

    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
       <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_LDP');?></div>
    </header>


    <div data-role="content" id="pageMainContainerId">
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
      ?>
      <div data-enhance="false" >

            <div class="jee-page">
                <div class="col-md-12">
                    <p class="text14">
                        Check your JEE Main 2017 result below. After checking the result, predict your college and branch <a href="http://www.shiksha.com/b-tech/resources/jee-mains-college-predictor">here</a>.
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="group-card gap pad-off">
                        <div class="holds-the-iframe"><iframe id="result_frame" src="" class="jee-result-iframe"></iframe></div>
                    </div>
                </div>
                <div class="col-md-3 no-padRgt">
                    <div class="group-card gap bg-img">
                        <h2 class="almuni-h3">JEE Main 2017 college predictor</h2>
                        <p class="text14">
                            Enter you expected rank. Find your college and branch
                        </p>
                        <a href="<?=SHIKSHA_HOME?>/b-tech/resources/jee-mains-college-predictor" class="btn-primary top-b">Predict college</a>
                    </div>
                    <div class="group-card gap views-ol">
                        <h2 class="rgt-title">Got a great rank? Explore Top Ranked Colleges</h2>
                        <ul>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/college/indian-institute-of-technology-delhi-hauz-khas-3050" >IIT Delhi</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/college/indian-institute-of-technology-bombay-iit-powai-mumbai-28130" >IIT Bombay</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/university/national-institute-of-technology-tiruchirappalli-2996" >NIT Trichy</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0" >All Engineering College Rankings</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0" >Top Engineering Colleges by Location</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0" >Top Engineering Colleges by Specialization</a>
                            </li>
                        </ul>
                    </div>
                    <div class="group-card gap views-ol">
                        <h2 class="rgt-title">Not so great rank? Find the best college for you</h2>
                        <ul>
                           <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-india" >Engineering Colleges in India</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-andhra-pradesh" >Engineering Colleges in Andhra Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-arunachal-pradesh" >Engineering Colleges in Arunachal Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-assam" >Engineering Colleges in Assam</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-bihar" >Engineering Colleges in Bihar</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-chhattisgarh" >Engineering Colleges in Chhattisgarh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-haryana" >Engineering Colleges in Haryana</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-karnataka" >Engineering Colleges in Karnataka</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-kerala" >Engineering Colleges in Kerala</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-goa" >Engineering Colleges in Goa</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-gujarat" >Engineering Colleges in Gujarat</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-himachal-pradesh" >Engineering Colleges in Himachal Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-jammu-and-kashmir" >Engineering Colleges in Jammu and Kashmir</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-jharkhand" >Engineering Colleges in Jharkhand</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-madhya-pradesh" >Engineering Colleges in Madhya Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-maharashtra" >Engineering Colleges in Maharashtra</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-manipur" >Engineering Colleges in Manipur</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-meghalaya" >Engineering Colleges in Meghalaya</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-mizoram" >Engineering Colleges in Mizoram</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-nagaland" >Engineering Colleges in Nagaland</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-orissa" >Engineering Colleges in Orissa</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-punjab" >Engineering Colleges in Punjab</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-rajasthan" >Engineering Colleges in Rajasthan</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-sikkim" >Engineering Colleges in Sikkim</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-tamil-nadu" >Engineering Colleges in Tamil Nadu</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-tripura" >Engineering Colleges in Tripura</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-uttar-pradesh" >Engineering Colleges in Uttar Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-west-bengal" >Engineering Colleges in West Bengal</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-uttarakhand" >Engineering Colleges in Uttarakhand</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-dadra-and-nagar-haveli" >Engineering Colleges in Dadra and Nagar Haveli</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-telangana" >Engineering Colleges in Telangana</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
    
        <?php $this->load->view('/mcommon5/footerLinks'); ?>
      </div>
  </div>

</div>

<div id="popupBasicBack" data-enhance='false'></div>

<?php $this->load->view('/mcommon5/footer', array());?>

<script>
  jQuery(document).ready(function($) {
	$('#result_frame').prop('src', '<?=$jeeResultPageURL?>');
  });
</script>

<?php ob_end_flush(); ?>
