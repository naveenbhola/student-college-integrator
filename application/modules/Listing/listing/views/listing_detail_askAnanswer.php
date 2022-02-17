<?php
$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
if($userId != 0){
    $loggedIn =1;
 }
 else
     $loggedIn =0;
?>

<?php

$tagsDescription = get_listing_seo_tags($details['title'],$details['locations']['0']['locality'],$details['courseDetails']['0']['title'],$details['locations']['0']['city_name'],$details['locations']['0']['country_name'],'AnaTab',$details['abbreviation']);
$headerComponents = array('js'=>array('discussion','multipleapply','cityList','listingDetail','lazyload','facebook','customCityList'),
//'css'	=>	array('raised_all','mainStyle','header'),
'css'=>array('listing','online-styles'),
    'jsFooter' =>array('common','ana_common','commonnetwork','alerts','myShiksha','listing_detail'),
                        //'css'=>array('modal-message','raised_all','mainStyle'),
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


<?php $this->load->helper('image');?>
<?php $this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_ANA_PAGE',1);
?>
<?php $this->load->view('listing/widgets/breadcrumb');?>

<!-- SITE HEADER END -->

<?php $this->load->view('listing/widgets/header');?>
<script>
var currentPageName = 'LISTING_DETAIL_QNA_TAB';
var loggedUserEMailTemp = '<?php echo $loggedUserEMail;?>';
if(loggedUserEMailTemp=='')
flagForSignedUser = false;
else
flagForSignedUser = true;

</script>
<?php $this->load->view('listing/widgets/navigation');?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
</head>
<body id="wrapperMainForCompleteShiksha">
<input type="hidden" id="unified_country_id" value="<?php echo $details['locations']['0']['country_id']?>" />
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

<?php $this->load->view('/listing/widgets/I_am_interested_right'); ?>

<?php $this->load->view('listing/widgets/connectInstituteTopWidget');?>

<?php $this->load->view('/listing/widgets/alumniSpeak_widget');?>
<?php $this->load->view('listing/widgets/imp_dates');?>
<?php $this->load->view('/listing/widgets/top_companies')?>


<?php $this->load->view('/listing/widgets/salary_stats');?>

<?php $this->load->view('listing/widgets/shiksha_analytics');?>

<?php
                    if ( $ListingMode == 'view' ) {
                    if(!isset($cmsData)){
                            $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                            $this->load->view('common/banner',$bannerProperties);
                                        }
                                                    }
                    if($details['locations'][0]['country_id'] != 2) { ?>
                          <div class="wdh100">
                                    <?php $this->load->view('listing/widgets/ets_campaign');?>
                          </div>
              <?php } ?>
<div><img border="0"></div>
</div>
<div class="float_L" style="width:690px">


<?php
$pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
$categoryId = 0;
if(count($details['subCatArray']) == 1){
    $categoryId = $details['subCatArray'][0];
}
$locationId = is_array($details['locations'][0])?$details['locations'][0]['country_id']:2;
$instituteId = $details['institute_id'];
$titleOfInstitute = isset($details['institute_name'])?$details['institute_name']:((isset($details['instituteArr'][0]['institute_name']))?$details['instituteArr'][0]['institute_name']:$details['title']);
$this->load->view('listing/widgets/ask_institute_form',array('categoryId' => $categoryId,'locationId' => $locationId,'instituteId' => $instituteId , 'pageName' => 'institute','titleOfInstitute' => $titleOfInstitute ,'pageKeyForAskQuestion' => $pageKeyForAskQuestion));
$displayData['categoryId'] = $category_id_array;
$this->load->view('listing/widgets/ask&answerWall',$displayData);
?>

</div>
<div class="clear_B">&nbsp;</div>
</div>
</div>
</div>
</div>
</body>
</html>




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


<!-- SITE FOOTER -->
<?php $this->load->view('common/footerNew');?>
<!-- SITE FOOTER END -->
<!-- Code added to add tracking when the user is shown the FConnect button on Listing detail page -->
<script>
if(loggedUserName==''){
trackEventByGA('LinkClick','FCONNECT_BUTTON_SHOWN_ON_LISTING_DETAIL');
}else{
trackEventByGA('LinkClick','FCONNECT_BUTTON_NOT_SHOWN_ON_LISTING_DETAIL');
}
</script>
