<?php
ob_start('compressHTML');
$tempJsArray = array();
$headerComponents = array(
                'css'   =>  array('listingCommon','instituteDetailPage'),
                'js' => array(),
                'jsFooter'=>    $tempJsArray,
                'product' => 'institutePage',
                'showBottomMargin' => false,
                'title' =>      $seoTitle,
                'metaDescription' => $metaDescription,
                'canonicalURL' =>$canonicalURL,
                'lazyLoadJsFiles' => true,
                'ampUrl'  => $amphtmlUrl
);
$this->load->view('common/header', $headerComponents);
if($listing_type == 'institute')
  echo jsb9recordServerTime('SHIKSHA_NATIONAL_INSTITUTE_DETAIL_PAGE',1);
elseif($listing_type == 'university')
  echo jsb9recordServerTime('SHIKSHA_NATIONAL_UNIVERSITY_DETAIL_PAGE',1);


?>
<script type="text/javascript">
var isShowGlobalNav = false;
</script>

<div class="notify-bubble report-msg" id="noti" style="top: 130px;opacity: 1; display: none;">
   <div class="msg-toast">
   <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
   <p id="toastMsg"></p>
   </div>
</div>

  <?php 
  
  $displayData['courseDetail'] = $instituteCourses;
  $displayData['courseViewFlag'] = true;
  $displayData['instituteId'] = $listing_id;
  $displayData['responseAction']='Asked_Question_On_Listing';
  if($listing_type == 'university')
  {
    $displayData['qtrackingPageKeyId'] = 973;  
    $displayData['GA_Tap_On_What_Question'] = 'TYPE_QUESTION_UNIVERSITYDETAIL_DESKLDP';
    $GA_currentPage = "UNIVERSITY DETAIL PAGE";
    $GA_commonCTA_Name = "_UNIVERSITYDETAIL_DESKTOP";
  }
  elseif($listing_type == 'institute')
  {
    $displayData['qtrackingPageKeyId'] = 933;  
    $displayData['GA_Tap_On_What_Question'] = 'TYPE_QUESTION_INSTITUTEDETAIL_DESKLDP';
    $GA_currentPage = "INSTITUTE DETAIL PAGE";
    $GA_commonCTA_Name = "_INSTITUTEDETAIL_DESKTOP";
  }
  
  $this->load->view('InstitutePage/InstituteInnerPage');

   ?>

  <script>
function LazyLoadInstituteDetailsCallback(){
  lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
    $LAB
  .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>')
  .wait(function(){
LazyLoadCallBackInstitute();
  });
}
</script>

  <?php 
    $this->load->view('common/footer'); 
    echo includeJSFiles('shikshaDesktopWebsiteTour');
  ?>  
    
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<script type="text/javascript">
jQuery(document).ready(function() {
          initializeNationalInstitutePage('<?=$listing_id;?>','<?=$listing_type;?>');
          <?php
                if( $validateuser != "false" && $validResponseUser  ){
          ?>
                  if(isUserLoggedIn){
                    responseForm.createViewedResponse('<?php echo $course->getId();?>', '<?php echo $viewedResponseAction;?>', '<?php echo $instituteViewedTrackingPageKeyId;?>');
                  }
          <?php
                  }
                  if($_REQUEST['showAllBranches'] == 1 && $isMultilocation){
          ?>
                    if($j("#colg-loc-layer").length > 0)
                      showMultilocationLayer();
          <?php
                  }
          ?>
          var contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
          initializeWebsiteTour('cta',window.shikshaProduct,contentMapping);
          
          <?php if($showToastMsg && $validateuser != "false"){?>
                setTimeout(function(){
                  handleAnAToastMsg("<?=$SRM_DATA['ToastMsg']?>",5000);
                },3000);

          <?php } ?>  

    });
var instituteId = '<?=$instituteObj->getId()?>';
var instituteType = '<?php echo $listing_type;?>';
isCompareEnable = true;
</script>

<script>
    var mmp_form_id_on_popup = '<?php echo $mmpData['mmp_details']['page_id'];?>';
    var mmp_page_type = '<?php echo $mmpData['mmp_details']['display_on_page'];?>';
    var mbaCourseExistsFlag = '<?php echo $mbaCourseExistsFlag;?>';
    var customFieldValueSource = <?php echo json_encode($streamIds);?>;
    if(mmp_form_id_on_popup != '' && mbaCourseExistsFlag){
      var customFields ={};
         <?php if(count($streamIds) == 1){ ?>
            var streamId = 0;
            for(var stream in customFieldValueSource){
              streamId = stream;
            }
            if(streamId){              
              customFields['stream']={
                'value':streamId,
                'hidden':0
              };
            }
         <?php } ?>

            var formData = {
                'trackingKeyId' : '<?php echo $mmpData['trackingKeyId'];?>',
                'customFields':customFields,
                'customFieldValueSource':customFieldValueSource,
                'submitButtonText':'<?php echo $mmpData['submitButtonText'];?>',
                'httpReferer':'',
                'formHelpText':<?php echo json_encode($mmpData['customHelpText']);?>
            };
            //MMPLayerCommon(formData);
            //registrationForm.showRegistrationForm(formData);
    }

    var img = document.getElementById('beacon_img');
    var randNum = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$listing_id?>+<?=$viewCountListingType?>';
 </script>
 <div id="tags-layer" class="tags-layer"></div>
 <div class="an-layer an-layer-inner" id="an-layer" style="display:none;">
      <?php $this->load->view('messageBoard/desktopNew/quesDiscPosting',$displayData);?>
 </div>
<?php
ob_end_flush();
?>
<script>
  var GA_currentPage = "<?php echo $GA_currentPage;?>";
  var ga_user_level = "<?php echo $GA_userLevel;?>";
  var ga_commonCTA_name = "<?php echo $GA_commonCTA_Name;?>";
  searchCompareCTAEventAttach();
</script>

