<?php

$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;

?>

<?php

$tagsDescription = get_listing_seo_tags($details['title'],$details['locations']['0']['locality'],$details['courseDetails']['0']['title'],$details['locations']['0']['city_name'],$details['locations']['0']['country_name'],'AlumniTab',$details['abbreviation']);
$headerComponents = array('js'=>array('multipleapply','cityList','listingDetail','ajax-api','facebook','customCityList'),
'css'	=>	array('raised_all','mainStyle','header'),
    'jsFooter' =>array('alerts','myShiksha','lazyload','common','multipleapply','user','listing_detail'),
//'css'=>array('modal-message','raised_all','mainStyle'),
'css'=>array('listing','online-styles'),
						'product'=>'categoryHeader',
    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                'taburl' =>  site_url(),
								'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
								'title'	=>	$tagsDescription['Title'],
								'metaKeywords'	=>$tagsDescription['Keywords'],
								'metaDescription' => $tagsDescription['Description']
);
if($instituteType == 2){
    $headerComponents['product'] = "testprep";
}
?>



<?php $this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_ALUMNI_PAGE',1);
?>
<?php $this->load->view('listing/widgets/breadcrumb');?>

<!-- Code start by Ankur for HTML caching -->
<script>
var currentPageName = 'LISTING_DETAIL_ALUMNI_TAB';
var loggedUserEMailTemp = '<?php echo $loggedUserEMail;?>';
if(loggedUserEMailTemp=='')
flagForSignedUser = false;
else
flagForSignedUser = true;

</script>

<?php
if(file_exists($overviewFile) && $isCached == 'true'){
    echo file_get_contents($overviewFile);
    ?>
    <script>
    //Code to show the Shiksha Analytics by JS whenever the detail page is displayed through HTML
    var sA = '<?php echo $countResponseFormDetails;?>';
    var sVC = '<?php echo $details['viewCount'] ?>';
    var sSC = '<?php echo $details['summaryCount'] ?>';
    fillAnalyticsData(sA,sVC,sSC);
    </script>
    <?php
}
else
{
  ob_start(); 

?>
<!-- Code End by Ankur for HTML caching -->

<style>
   .loaderBg {
    background: url("/public/images/loader.gif") no-repeat scroll center center transparent;
}
</style>


<?php $this->load->view('listing/widgets/header');?>
<?php $this->load->view('listing/widgets/navigation');?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
</head>
<body id="wrapperMainForCompleteShiksha">
<div class="wrapperFxd">
<div class="mlr10">
<div class="wdh100 mb5">


<div class="clear_B">&nbsp;</div>
</div>
</div>

<div class="wdh100 mt10">
<div class="mlr10">
<div>
<div class="float_R" style="width:262px">

<?php $this->load->view('/listing/widgets/I_am_interested_right');?>
 <?php $this->load->view('listing/widgets/connectInstituteTopWidget');?>

<div class="lineSpace_20">&nbsp;</div>

<div id ="askWidget" class="lineSpace_25" style="width: 200px; height: 200px;">&nbsp;</div> 
<?php if(!($callQNAWidgetFlag=='false')){ ?>
<script>
    ajax_loadContent('askWidget','/listing/Listing/getDataForAnAWidget/<?php echo $details['institute_id']?>/institute/alumni');
    if( $('askWidget')){ $('askWidget').className = "loaderBg lineSpace_25";}
</script>
<?php } ?>


<?php $this->load->view('/listing/widgets/top_companies');?>


<?php $this->load->view('/listing/widgets/salary_stats');?>

<?php
                    if ( $ListingMode == 'view' ) {
                    if(!isset($cmsData)){
                            $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                            $this->load->view('common/banner',$bannerProperties);
                                        }
                                                    }
                    ?>
                        <?php if($details['locations'][0]['country_id'] != 2) { ?>
                          <div class="wdh100">
                                    <?php $this->load->view('listing/widgets/ets_campaign');?>
                          </div>
                        <?php } ?>


<!--<div><img border="0"></div>-->
</div>
<div class="float_L" style="width:690px">

<?php $this->load->view('listing/widgets/alumniReviews_main',$displayData);?>
<div class="lineSpace_10">&nbsp;</div>
<!-- I am Interested bottom widget begins -->
<?php $this->load->view('listing/widgets/i_am_interested_bottom');  ?>
<!-- I am Interested bottom widget Ends -->

</div>
<div class="clear_B">&nbsp;</div>

</div>
</div>
</div>
</div>
</body>
</html>






<!-- Code start by Ankur for HTML caching -->
<?php
  $pageContent = ob_get_contents();
  ob_end_clean();
  echo $pageContent;
  //In case the HTML caching on On and also the Listing type is not a link
  if( strpos($listing_type,'http') === false  && $HTMLCaching=='true'){
    $fp=fopen($overviewFile,'w+');
    flock( $fp, LOCK_EX ); // exclusive lock
    fputs($fp,$pageContent);
    flock( $fp, LOCK_UN ); // release the lock
    fclose($fp);
    ?>
    <script>
       var data = "";
       var url = '/listing/Listing/callToCopyFileToServers/<?php echo base64_encode($overviewFile);?>';
       new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (request) {}});
    </script>
    <?php
  }
}
?>
<?php
$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'institute','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id']);
$overviewTabUrl = getSeoUrl($details['institute_id'], 'institute', $details['title'], array('location' => array($details['locations']['0']['locality'], $details['locations']['0']['city_name'])));
$askNAnswerTabUrl = listing_detail_ask_answer_url($params);
$mediaTabUrl = listing_detail_media_url($params);
$alumniTabUrl = listing_detail_alumni_speak_url($params);
$courseTabUrl = listing_detail_course_url($params);
?>
<script>
//Code to show the I am interested widgets filled with User's data in case the User is logged in
var loggedUserName = '<?php echo $loggedUserName; ?>';
var loggedUserMobile = '<?php echo $loggedUserMobile; ?>';
var loggedUserEMail = '<?php echo $loggedUserEMail;?>';
fillUserData(loggedUserName,loggedUserMobile,loggedUserEMail);
var saved = "'<?php echo $saved; ?>'";
var source = "'<?php echo $source."_TOP_SAVEINFO"?>'";
var quickClickAction = "javascript:window.location = '/user/Userregistration/index/<?php echo base64_encode($thisUrl);?>/1'";
var listing_type = "'<?php echo $identifier; ?>'";
var type_id = '<?php echo $type_id; ?>';
var div_id = "<?php echo $identifier.$type_id;?>";
fillSaveInfo(saved,loggedUserName,source,quickClickAction,listing_type,type_id,div_id);
</script>
<?php
//Create the HTML files for other tabs only when the HTML file for this tab is created
if( !file_exists($overviewFile) || $isCached != 'true' ){
?>
<script>
makeHTMLFiles('alumni','<?php echo $overviewTabUrl;?>','<?php echo $mediaTabUrl;?>','<?php echo $alumniTabUrl;?>','<?php echo $courseTabUrl;?>');
</script>
<?php } ?>

<!-- Code End by Ankur for HTML caching -->
<!-- beacon code start-->
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<script>
       var img = document.getElementById('beacon_img');
       var randNum = Math.floor(Math.random()*Math.pow(10,16));
       img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?php echo $type_id; ?>+<?php echo $identifier; ?>';
       //fillProfaneWordsBag() ;
</script>
<?php $this->load->view('common/footerNew');?>
<!-- Code added to add tracking when the user is shown the FConnect button on Listing detail page -->
<script>
if(loggedUserName==''){
trackEventByGA('LinkClick','FCONNECT_BUTTON_SHOWN_ON_LISTING_DETAIL');
}else{
trackEventByGA('LinkClick','FCONNECT_BUTTON_NOT_SHOWN_ON_LISTING_DETAIL');
}
</script>
