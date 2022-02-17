<?php

$tagsDescription = get_listing_seo_tags($details['title'],$details['locations']['0']['locality'],$details['courseDetails']['0']['title'],$details['locations']['0']['city_name'],$details['locations']['0']['country_name'],'CourseTab',$details['abbreviation']);
$headerComponents = array('js'=>array('ajax-api','multipleapply','cityList','listingDetail','listingCourse','facebook','customCityList'),
//'css'	=>	array('raised_all','mainStyle','header'),
'css'=>array('listing','online-styles'),
    'jsFooter' =>array('common','alerts','myShiksha','listing_detail','lazyload'),
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


<?php $this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_COURSE_PAGE',1);
?>
<?php $this->load->view('listing/widgets/breadcrumb');?>

<!-- SITE HEADER END -->

<!-- Code start by Ankur for HTML caching -->
<script>
var currentPageName = 'LISTING_DETAIL_COURSES_TAB';
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

<?php
//echo "<pre>";print_r($otherCourseData);echo "</pre>";
$flagshipCourse = $otherCourseData[0][0];
$flagshipCourseAttributes = $flagshipCourse['courseAttributes'];
$flagshipCattr = array();
foreach($flagshipCourseAttributes as $attr){
    $flagshipCattr[$attr['attribute']] = $attr['value'];
}
$flagshipCourseApprovedBy = explode(',',$flagshipCourse['approvedBy']);
?>
<script type="text/javascript">
<!--
var flagshipCourseId = <?php echo $otherCourseData[0][0]['course_id'];?>; 
//-->
</script>


    	<!--Start_listing_detail_head-->
 	<?php $this->load->view('listing/widgets/header');?>
        <!--End_listing_detail_head-->

        <!--Start_Sub_Navigation-->
                <?php $this->load->view('listing/widgets/navigation');?>
        <!--End_Sub_Navigation-->

		<div class="wdh100 mt10">
        	<div class="mlr10">
            	<div class="wdh100 mb10">
                    <div class="float_L" style="width:690px">
                        <div class="wdh100 mb10"><div class="nlt_srlBar"><a style="text-decoration: none; color: rgb(0, 0, 0);" class="nlt_d_mLnk" href="javascript:void(0)" onclick="showCourseDetails(0)" name = "course_title" id="course_title_0"><H2><?php echo $flagshipCourse['title']?></H2></a> </div></div>
                        <div class="wdh100" id="course_details_0" name="course_details">
                            <?php                            
                            $temp_array['course_details'] = $flagshipCourse;                                                       
                            // $this->load->view('/listing/widgets/course_details_for_listing_detail_course', $temp_array);
                            $this->load->view('/listing/widgets/course_details', $temp_array);
                            unset($flagshipCourse);
                            ?>
                        <!--End_About_Course-->
                        </div>

<?php

if(sizeof($otherCourseData)>1)
{
    for($i = 1;$i<sizeof($otherCourseData);$i++){
            $flagshipCourse = $otherCourseData[$i][0];
                            //echo "<pre>-------<br/>";print_r($course_details);echo "</pre>";
                            $flagshipCourseAttributes = $flagshipCourse['courseAttributes'];
                            $flagshipCattr = array();
                            foreach($flagshipCourseAttributes as $attr){
                            $flagshipCattr[$attr['attribute']] = $attr['value'];
                            }
                            $flagshipCourseApprovedBy = explode(',',$flagshipCourse['approvedBy']);
?>
<!--Start_About_Course-->
<div class="wdh100 mb10"><div class="nlt_srlBar"><a style="text-decoration: none; color: rgb(0, 0, 0);" class="nlt_l_mLnk" href="javascript:void(0)" onclick="showCourseDetails(<?php echo $i; ?>)" name="course_title" id="course_title_<?php echo $i;?>"><H2><?php echo $flagshipCourse['title']?></H2></a></div></div>
<div class="wdh100" id="course_details_<?php echo $i;?>" style="display:none;" name="course_details">
<?php
                            $temp_array['course_details'] = $flagshipCourse;
                            // $this->load->view('/listing/widgets/course_details_for_listing_detail_course', $temp_array);
                             $this->load->view('/listing/widgets/course_details', $temp_array);

?>

</div>
<!--End_About_Course-->
<?php
       }
}
?>
                    </div>
                    <div class="float_R" style="width:262px;height:400px;">
                        <div class="wdh100">
                            <div style="width: 262px;" name ="i_am_interested">
                                <!--Start_Paid_Listing_Form-->
                                <?php $this->load->view('/listing/widgets/I_am_interested_right');?>
                                <?php $this->load->view('listing/widgets/connectInstituteTopWidget');?>
                                <!--End_Paid_Listing_Form-->
                          <?php if($details['locations'][0]['country_id'] != 2) { ?>
                          <div class="wdh100">
                                    <?php $this->load->view('listing/widgets/ets_campaign');?>
                          </div>
                        <?php } ?>

                            </div>
                        </div>
                    </div>                    
                    <div class="clear_B">&nbsp;</div>                
            </div>           
                
            <!--/div-->
            <!--div class="lineSpace_30" style="border: 1px solid;">&nbsp;</div-->
                <!--Start_Google_Ads-->
                <div class="wdh100">
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
                
			<!-- End Google Ads -->
            </div>
        </div>
        
<script type="text/javascript">
//<!--
	function showCourseDetails(countId){
		
                // alert(document.getElementById('course_details_'+countId).style.display);
		trackEventByGA('LinkClick','LISTING_COURSE_VIEW_DETAILS_LINK');
                if(document.getElementById('course_details_'+countId).style.display == "" ) {
                    
                    document.getElementById('course_details_'+countId).style.display = 'none';
                    document.getElementById('course_title_'+countId).className = 'nlt_l_mLnk';

                } else {
                    var others = document.getElementsByName("course_details");

                    for(var i=0;i<others.length;i++){
                         document.getElementById('course_details_'+i).style.display = 'none';
                         document.getElementById('course_title_'+i).className = "nlt_l_mLnk";
                    }

                    document.getElementById('course_details_'+countId).style.display = '';
                    document.getElementById('course_title_'+countId).className = 'nlt_d_mLnk';

                }
        }
//-->
</script>        


<!-- Code start by Ankur for HTML caching -->
<?php
  $pageContent = ob_get_contents();
  ob_end_clean();
  echo $pageContent;
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
//makeHTMLFiles('course','<?php echo $overviewTabUrl;?>','<?php echo $mediaTabUrl;?>','<?php echo $alumniTabUrl;?>','<?php echo $courseTabUrl;?>');
</script>
<?php } ?>

<!-- Code End by Ankur for HTML caching -->
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
