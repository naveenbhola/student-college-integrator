<?php

ob_start('compressHTML');
$headerComponents = array(
                'css'              =>   array('listingCommon','courseDetailPage'),
                'js'               =>   array(),
                'jsFooter'         =>   array(),
                'product'          =>  'coursePage',
                'showBottomMargin' =>   false,
                'title'            =>   $seoTitle,
                'metaDescription'  =>   $metaDescription,
                'canonicalURL'     =>   $canonicalURL,
                'lazyLoadJsFiles' => true,
                'ampUrl'                    => $amphtmlUrl
);
$this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_NATIONAL_COURSE_DETAIL_PAGE',1);
?>
<script type="text/javascript">
var isShowGlobalNav = false;
isCompareEnable = true;
</script>
<div class="notify-bubble report-msg" id="noti" style="top: 130px;opacity: 1; display: none;">
    <div class="msg-toast">
    <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
    <p id="toastMsg"></p>
    </div>
</div>

  <?php
  $this->load->view('CoursePage/CourseInnerPage'); ?>

 <script>
function LazyLoadCourseDetailsCallback(){
  lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
    $LAB
  .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>')
  .wait(function(){
// LazyLoadCallBackCourse();
courseLazyLoadCallBack();
  });
}
</script>
  
<?php 
    $this->load->view('common/footer');
    echo includeJSFiles('shikshaDesktopWebsiteTour');
?>  

<img id = 'beacon_img' src="//<?php echo IMGURL; ?>/public/images/blankImg.gif" width=1 height=1 >
<script src="//<?php echo JSURL; ?>/public/js/trackingMIS/chartjs/chart.min.js" ></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/loader.js"></script>
<script type="text/javascript">
var courseId = '<?=$courseObj->getId()?>',courseName = '<?php echo addslashes(htmlentities($courseObj->getName()));?>',instituteId = '<?=$courseObj->getInstituteId()?>',myCompareObj = new myCompareClass(),ga_user_level='<?=$GA_userLevel?>',GA_userLevel='<?=$GA_userLevel?>',GA_currentPage='<?=$GA_currentPage?>',validateuser='<?=$validateuser?>',validResponseUser='<?=$validResponseUser?>',viewedResponseAction='<?=$viewedResponseAction?>',courseViewedTrackingPageKeyId='<?=$courseViewedTrackingPageKeyId?>',showLLOnLoad='<?=$_REQUEST["showAllBranches"]?>',isMultiLoc='<?=$isMultilocation?>';
</script>

<script>
    $j(document).ready(function() {
        var contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        initializeWebsiteTour('cta',window.shikshaProduct,contentMapping);

        <?php if($showToastMsg && $validateuser!='false'){?>
                setTimeout(function(){
                  handleAnAToastMsg("<?=$SRM_DATA['ToastMsg']?>",5000);
                },3000);

        <?php } ?> 

        // initializeMouseHoversOnPage();
    });    
    var mmp_page_type = '<?php echo $mmpData['mmp_details']['display_on_page'];?>';
    function seoMMPRegLayer(){
        var mmp_form_id_on_popup   = '<?php echo $mmpData['mmp_details']['page_id'];?>';
        var customFieldValueSource = <?php echo json_encode($mmpData['regFormPrefillValue']['customFieldValueSource']);?>;
        var customFields           = <?php echo json_encode($mmpData['regFormPrefillValue']['customFields']);?>;
        if(mmp_form_id_on_popup != ''){            
            var formData = {
                'trackingKeyId' : '<?php echo $mmpData['trackingKeyId'];?>',
                'customFields':customFields,
                'customFieldValueSource':customFieldValueSource,
                'submitButtonText':'<?php echo addslashes(htmlentities($mmpData['submitButtonText']));?>',
                'httpReferer':'',
                'formHelpText':'<?php echo json_encode($mmpData['customHelpText']);?>'
            };
            //MMPLayerCommon(formData); 
            //registrationForm.showRegistrationForm(formData);
        }
    }
    var img = document.getElementById('beacon_img');
    var randNum = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$courseObj->getId()?>+course';
 </script>
<div id="tags-layer" class="tags-layer"></div>
<div class="an-layer an-layer-inner" id="an-layer" style="display:none;">
    <?php 
    $displayData['instituteId'] = $instituteObj->getId();
    $displayData['courseIdQP'] = $courseObj->getId();
    $displayData['responseAction'] = 'Asked_Question_On_Listing';
    $displayData['qtrackingPageKeyId'] = 938;  
    $this->load->view('messageBoard/desktopNew/quesDiscPosting',$displayData);
    ?>
</div>
<?php
ob_end_flush();
?>
