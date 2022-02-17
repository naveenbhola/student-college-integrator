<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$tagsDescription = get_listing_seo_tags($details['title'],$details['locations']['0']['locality'],$details['courseDetails']['0']['title'],$details['locations']['0']['city_name'],$details['locations']['0']['country_name'],$identifier,$details['abbreviation']);
if(!empty($seoTitle)){
    $tagsDescription['Title'] = $seoTitle;
}
if(!empty($seoDescription)){
    $tagsDescription['Description'] = $seoDescription;
}
if(!empty($seoKeywords)){
    $tagsDescription['Keywords'] = $seoKeywords;
}
$headerComponents = array('js'=>array('multipleapply','category','user','listingDetail','lazyload','ajax-api','listingOverview','facebook','customCityList'),
			'jsFooter' =>array('common','ana_common'),
                        //'css'=>array('modal-message','raised_all','mainStyle'),
						'css'=>array('listing','online-styles'),
			'product'=>'categoryHeader',
                        'taburl' =>  site_url(),
			'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
			'title'	=>	$tagsDescription['Title'],
			'metaKeywords'	=>$tagsDescription['Keywords'],
			'metaDescription' => $tagsDescription['Description']
                         );

?>

<?php $this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_OVERVIEW_PAGE',1);
?>
<?php $this->load->view('listing/widgets/breadcrumb');?>

<!-- Code start by Ankur for HTML caching -->
<script> 
var currentPageName = 'LISTING_DETAIL_OVERVIEW_TAB';
var loggedUserEMailTemp = '<?php echo $loggedUserEMail;?>';
if(loggedUserEMailTemp== '') {
flagForSignedUser = false;
} else {
flagForSignedUser = true;
}
</script>

<?php
if(file_exists($overviewFile) && $isCached == 'true'){
    echo file_get_contents($overviewFile);
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
<!-- SITE HEADER END -->
    	<!--Start_listing_detail_head-->
 	<?php $this->load->view('listing/widgets/header');?>
        <!--End_listing_detail_head-->

        <!--Start_Sub_Navigation-->
                <?php $this->load->view('listing/widgets/navigation');?>
        <!--End_Sub_Navigation-->




        <!--Start_Mid_Panel-->
        <div class="wdh100 mt10">
        	<div class="mlr10">
            	<div>
                	
                    <div class="float_L" style="width:690px">
                    	<div class="wdh100">
                            <div class="float_R" style="width:262px">
                                <!--Start_Top_RecuritingCompanies-->

                                    <?php $this->load->view('listing/widgets/top_companies');?>

                                <!--End_Top_RecuritingCompanies-->
                               
                                <!--Start_Top_RecuritingCompanies-->

                                    <?php $this->load->view('listing/widgets/salary_stats');?>

                                <!--End_Top_RecuritingCompanies-->
                                <!--Start_ContactInfo-->
                                     <?php $this->load->view('listing/widgets/contact_details');?>
                                <!--End_ContactInfo-->
                                
                                <!--Start_Alumini_Speak-->
                                
                                    <?php $this->load->view('listing/widgets/alumniSpeak_widget');?>
                                
                                <!--End_Alumini_Speak-->
                                
                                <!--Start_Photo_Videos-->
                                <?php $this->load->view('listing/widgets/listing_overview_media');?>
                                <!--End_Photo_Videos-->
                                
                                <!--Start_Why_Join-->
                                <div class="wdh100">
                                    <?php $this->load->view('/listing/widgets/why_join');   ?>
                                </div>
                                <!--End_Why_Join-->
                                <!--Start_Importants_Dates-->
                                <?php if(empty($topCompaniesLogo) && empty($details['courseDetails']['0']['courseAttributes'])){?>
                                <div class="wdh100">
                                    <?php $this->load->view('listing/widgets/imp_dates');?>
                                </div>
                                <?php }?>
                                <!--End_Importants_Dates-->
                            </div>
                            <div class="float_L" style="width:413px">
                                <!--Start_CourseProgram_with_Image-->
                                <div class="wdh100">
                                      <?php $this->load->view('listing/widgets/headerImage');?>
                                    <?php $this->load->view('listing/widgets/course_details');?>
                                </div>
                                <!--End_CourseProgram_with_Image-->
                                <div class="lineSpace_20">&nbsp;</div>
                               <div id ="askWidget" class="lineSpace_25" style="width: 200px; height: 200px;">&nbsp;</div>
			<?php if(!($callQNAWidgetFlag=='false')){ ?>
			<script>
			    ajax_loadContent('askWidget','/listing/Listing/getDataForAnAWidget/<?php echo $details['institute_id']?>/institute/overview');
			    if( $('askWidget')){ $('askWidget').className = "loaderBg lineSpace_25";}
			</script>
			<?php } ?>
                                <!--Start_Ask_n_Answer-->
                                
                                   <?php //$this->load->view('listing/widgets/askNAnswer_widget')?>
                                
                                <!--End_Ask_n_Answer-->
                            </div>
                            <div class="clear_B">&nbsp;</div>
                        </div>
                        
                        <!--Start_Course_Description-->
                        <div class="wdh100 mb15">
				<a name ="courseDescription"></a>
                        	<?php $this->load->view('listing/widgets/courseDescription');?>
                        </div>
                        <!--End_Course_Description-->
                        <!--Start_Institute_Description-->
				<a name ="instituteDescription"></a>
                        	<?php $this->load->view('listing/widgets/instituteDescription');?>
                        
                        <!--End_Institute_Description-->
                        <div class="lineSpace_20">&nbsp;</div>
                        <div class="lineSpace_20">&nbsp;</div>



                        <!--Start_contact_dt_bottom-->
                        <?php $this->load->view('listing/widgets/contact_details_big');?>
                        <!--Start_contact_dt_bottom-->
                        <div class="lineSpace_20">&nbsp;</div>


                        <!--Start_Paid_Listing_Form-->
                        <a name ="interested_bottom"></a>
                        <div class="wdh100">
                            <?php $this->load->view('listing/widgets/i_am_interested_bottom'); ?>
                        </div>
                        <!--End_Paid_Listing_Form-->


                    	<div class="lineSpace_20">&nbsp;</div>
                        <!--Start_FreeListing_Marketing_Page_link-->
                        <div class="wdh100">
                            <?php $this->load->view('listing/widgets/connectInstituteBottomWidget');?>
                        </div>
                        <!--End_FreeListing_Marketing_Page_link-->
                        <div class="lineSpace_30">&nbsp;</div>
                        <!--Start_Shiksha_Recomonded-->
                        <div class="wdh100">
                            <?php $this->load->view('listing/widgets/shiksha_recommends_big');?>
                        </div>
                        <!--End_Shiksha_Recomonded-->
                        <!--Start_Google_Ads-->
                        <div class="wdh100" style="margin-top:70px">
                        <div>
                    <?php
                    if ( $ListingMode == 'view' ) {
                    if(!isset($cmsData)){
                            $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                            $this->load->view('common/banner',$bannerProperties);
                                        }
                                                    }
                    ?>
                        </div>
                        </div>
                        
                    </div>
                    <div class="float_R" style="width:262px">
                    	<!--Start_Paid_Listing_Form-->
                        	<?php $this->load->view('listing/widgets/I_am_interested_right');?>
                        <!--End_Paid_Listing_Form-->
                    	<!--Start_free_institute_drpdwn-->

                           <?php $this->load->view('listing/widgets/connectInstituteTopWidget');?>

                        <!--End_free_institute_drpdwn-->
                       <?php if(!empty($topCompaniesLogo) || !empty($details['courseDetails']['0']['courseAttributes'])){?>
                                <div class="wdh100">
                                    <?php $this->load->view('listing/widgets/imp_dates');?>
                                </div>
                                <?php }?>
                        <!--Start_Shiksha_Recomonded-->
                        <?php //$this->load->view('listing/widgets/shiksha_recommends');?>
                        <!--End_Shiksha_Recomonded-->

                        <!--Start_Shiksha_Analysis-->
                        <?php $this->load->view('listing/widgets/shiksha_analytics');?>
                        <!--End_Shiksha_Analysis-->
                        <!--Start_google_ads-->
                        <div>
                    <?php
                    if ( $ListingMode == 'view' ) {
                    if(!isset($cmsData)){
                            $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                            $this->load->view('common/banner',$bannerProperties);
                                        }
                                                    }
                    ?>
                        </div>
                        <!--End_google_ads-->
                        <?php if($details['locations'][0]['country_id'] != 2) { ?>
                          <div class="wdh100">
                                    <?php $this->load->view('listing/widgets/ets_campaign');?>
                          </div>
                        <?php } ?>

                    </div>
                    <div class="clear_B">&nbsp;</div>
                </div>
            </div>
            </div>
        
        <!--End_Mid_Panel-->

    <!--End_pagewrapper-->
<!-- SITE FOOTER -->
<!-- SITE FOOTER END -->


<!-- Code start by Ankur for HTML caching -->
<?php
  $pageContent = ob_get_contents();
  ob_end_clean();
  echo $pageContent;
  //In case the HTML caching on On and also the Listing type is not a link
  if( strpos($listing_type,'http') === false && $HTMLCaching=='true'){
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
$currentURL =  explode("?",$_SERVER['REQUEST_URI']);
$currentURL = $currentURL[0];

//echo "<pre>";
//print_r($details['courseDetails'][0]);
//echo "</pre>";
$ts=$this->uri->total_segments();
$offset= $this->uri->segment($ts);
$category= isset($_REQUEST['cat']) ? $_REQUEST['cat'] : 0;
$offset = isset($_REQUEST['apply']) ? 1 : 0;
if($offset == 1){
?>
<script>

var listing_key = '<?=$listing_key?>';
var $categorypage =  new Array();
$categorypage.LDBCourseId = 0;
var subcatSameAsldbCourseCategoryPage = 0;
function ApplyNowFromCategory(){
	//messageObj = new DHTML_modalMessage();
	//messageObj.setShadowOffset(5);
	category_course_base_url = '<?php echo $currentURL; ?>';
	var inst_id = '<?php echo $details['institute_id'] ?>';
    var details_title = '<?php echo addslashes($details['title']); ?>';
    var courseDetails_title = '<?php echo addslashes($details['courseDetails'][0]['title']);?>';
	c_value_html += '<div style="height:33px;">';
	c_value_html += '<div class="float_L" style="width:400px;">';
	c_value_html += '<div class="float_L" style="width:25px;"><input name ="overlay[]" value="'+inst_id+'" checked type="checkbox" /></div><div style="margin-left:30px;margin-top:3px;" id="reqEbr_'+inst_id+'" title="'+details_title+'">'+details_title+'</div>';
	c_value_html += '</div>';
	c_value_html += '<div class="float_R" style="margin-right:40px;">';
	c_value_html += '<select id="apply'+inst_id+'" style="width:250px">';
	c_value_html += '<option title="'+courseDetails_title+'" value="<?php echo $details['courseDetails'][0]['course_id'];?>">'+courseDetails_title+'</option>';
	
	c_value_html += '</select>';
	c_value_html += '</div>';
	c_value_html += '</div><div class="clear_L withClear">&nbsp;</div>';
	
	if(listing_key != '')
	{
		c_value_html += getLocalityHTML(inst_id,listing_key);
	}
	
	c_value_html += '<input type="hidden" id ="category_unified_id" value="<?php echo $category; ?>"/>';
	c_value_html += '<input type="hidden" id="categorypage_unified_thankslayer_identifier" value=""/>';
	displayMessage('/MultipleApply/MultipleApply/showoverlay/17/',750,380);
}
</script>
<?
}
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
var listing_type = "'<?php echo $listingType; ?>'";
var type_id = '<?php echo $type_id; ?>';
var div_id = "<?php echo $listingType.$type_id;?>";
fillSaveInfo(saved,loggedUserName,source,quickClickAction,listing_type,type_id,div_id);

//Code to show the Shiksha Analytics by JS whenever the detail page is displayed through HTML
var sA = '<?php echo $countResponseFormDetails;?>';
var sVC = '<?php if($listingType=='institute') echo $details['viewCount']; else echo $details['courseDetails']['0']['viewCount']; ?>';
var sSC = '<?php echo $details['summaryCount'] ?>';
<?php if($listingType=='institute'){ ?>    
    fillAnalyticsData(sA,sVC,sSC,'institute');

<?php }else{ ?>
    fillAnalyticsData(sA,sVC,sSC,'course');
	<?php        
	if($loggedUserEMail){        
	?>
	instituteId = '<?php echo $details['institute_id'] ?>';
	instituteName = '<?php echo addslashes($details['title']); ?>';        
	courseId = '<?php echo $details['courseDetails'][0]['course_id'];?>';
        <?php if($loggedUserMobile != '' && ($details['courseDetails'][0]['packType'] == 1 || $details['courseDetails'][0]['packType'] == 2) ){ ?>
            makeAutoResponse(loggedUserName,loggedUserMobile,loggedUserEMail,instituteId, escape(instituteName),courseId);
	<?php }
	}   // End of if($loggedUserEMail).
	?>
<?php } ?>

</script>

<?php
//Create the HTML files for other tabs only when the HTML file for this tab is created
if( !file_exists($overviewFile) || $isCached != 'true' ){
?>
<script>
	window.onload = function(){
		showWikiOnOtherPage();
		makeHTMLFiles('overview','<?php echo $overviewTabUrl;?>','<?php echo $mediaTabUrl;?>','<?php echo $alumniTabUrl;?>','<?php echo $courseTabUrl;?>');
	}
</script>
<?php }else{ ?>
<script>
	window.onload = function(){
		showWikiOnOtherPage();
	}
</script>
<?php }?>
<!-- Code End by Ankur for HTML caching -->
<!-- beacon code start-->
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<script>
       var img = document.getElementById('beacon_img');
       var randNum = Math.floor(Math.random()*Math.pow(10,16));
       img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?php echo $type_id; ?>+<?php echo $listingType; ?>';
       //fillProfaneWordsBag() ;
</script>
<input type="hidden" id="unified_country_id" value="<?php echo $details['locations']['0']['country_id']?>" />
<?php $this->load->view('common/footerNew');?>

<!-- Code added to add tracking when the user is shown the FConnect button on Listing detail page -->
<script>
if(loggedUserName==''){
trackEventByGA('LinkClick','FCONNECT_BUTTON_SHOWN_ON_LISTING_DETAIL');
}else{
trackEventByGA('LinkClick','FCONNECT_BUTTON_NOT_SHOWN_ON_LISTING_DETAIL');
}
</script>

