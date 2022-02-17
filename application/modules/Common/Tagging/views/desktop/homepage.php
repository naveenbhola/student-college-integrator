<?php
ob_start('compressHTML');
$tempJsArray = array('user','common');
$bannerProperties = array('pageId'=>'TAGS', 'pageZone'=>'HEADER');
$headerComponents = array(
                'css'   =>  array('ana', 'bottomStickyRegistrationLayer'),
                'js' => array(),
                'jsFooter'=>    $tempJsArray,
                'showBottomMargin' => false,
                'product' => 'anaDesktopV2',
                'title' =>      $seoTitle,
                'metaDescription' => $metaDescription,
                'canonicalURL' =>$canonicalURL,
		        'bannerProperties' => $bannerProperties,
                'lazyLoadJsFiles' => true
);
$this->load->view('common/header', $headerComponents);
?>
<?php $this->load->view('messageBoard/desktopNew/listOfUserLayer');?>

<!-- Ask now layer -->
<div id="tags-layer" class="tags-layer"></div>
<div class="an-layer an-layer-inner" id="an-layer" style="display:none;">
    <?php $this->load->view('messageBoard/desktopNew/quesDiscPosting',$displayData);?>
</div>

 <div class="ana-main-wrapper">
       <div class="ana-container tag-detailpage">
           <div class="row">
                 <div class="ana-col-md-8">

                   <div class="row">

                        <div class="ana-wrap">
                            <?php $this->load->view('desktop/tdpBreadcrumb'); ?>
                        </div>

                    <!--- response layer-->
                    <div id="delete-layer" class="tags-layer" ></div>
                     <div id="closeDeletelayer" class="posting-layer"  style="width:580px;display: none">
                            <div class="tag-body">
                               <p class="msg" id="responseHeading"></p>
                                      <div class="btns-col">
                                          <span class="right-box" >               
                                              <a class="prime-btn" href="javascript:void(0);" onclick="hidecloseDeleteLayer('closeDeletelayer')">Ok</a>
                                          </span>
                                          <p class="clr"></p>
                                      </div>
                                </div>
                      </div> 

                   <!--tabs card-->
                      <div class="ana-selection">

                            <?php $this->load->view('desktop/tagDetails'); ?>

                            <?php $this->load->view('desktop/homePageTabs'); ?>
                            
                            <div class="tabs-content">
                              <?php $this->load->view('messageBoard/desktopNew/homepageTabsData'); ?>

			      <div class="n-pagination">
				      <?=$paginationHTML?>
			      </div>
                            </div>
                            
                    </div>
                    <!--end of tabs-->
                   </div>
                 </div>
                 <!--right panel-->
                     <?php $this->load->view("messageBoard/desktopNew/widgets/rightWidget"); ?>
                 <!--right panel ends-->
            </div>
            <p class="clr"></p>
       </div>
       <a href="javascript:void(0);" class="scrollToTop"></a>
    </div>

        <div data-enhance="false">
            <input type="hidden" id="pageType" value="<?php echo $pageType;?>" />
            <input type="hidden" id="entityIdUserList" value="">
            <input type="hidden" id="entityTypeUserList" value="">
            <input type="hidden" id="actIonForUserList" value="">
            <input type="hidden" id="userTotalCount" value="">
            <input type="hidden" id="tracking_keyid_UserList" value="">
            <input type="hidden" id="tdpTagId" value="<?php echo $data['tagId'];?>">
            <input type="hidden" id="tdpTagName" value="<?php echo $data['tagName'];?>">
            <input type="hidden" id="tracking_keyid_Most_Active" value="<?php echo $fuRightActiveTrackingPageKeyId;?>">
            <input type="hidden" id="tracking_keyid_Top_Contri" value="<?php echo $fuRightTopTrackingPageKeyId;?>">
        </div>
<script>
var GA_currentPage = "<?php echo $GA_currentPage;?>";
var ga_user_level = "<?php echo $GA_userLevel;?>";
function LazyLoadAnADesktopCallback(){
    $LAB
    .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_desktop");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v5");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("bottomStickyRegistrationLayer");?>')
    .wait(function(){
      LazyLoadCallBackTDP();
      prepareClickDataTDP();
    });
}
</script>
<?php
    $this->load->view('common/footer');
    $this->load->view('messageBoard/desktopNew/shareLayer');
    $this->load->view('messageBoard/desktopNew/toastLayer');
    echo includeJSFiles('shikshaDesktopWebsiteTour');
?>
<script>
    //Load website tour
    $j(document).ready(function(){
        window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        initializeWebsiteTour('cta','anaDesktopV2',contentMapping);
    });
<?php if(!empty($viewTrackParams)){ ?>
    window['trackingPageType'] = '<?php echo $viewTrackParams['trackingPageType'];?>';
    window['answerViewThreshold'] = <?php echo $viewTrackParams['answer'];?>;
    window['questionViewThreshold'] = <?php echo $viewTrackParams['question'];?>;
    window['threadViewJSWorkerInterval'] = <?php echo $viewTrackParams['trackDuration'];?>;
<?php } ?>
    lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
 </script>
<?php 
ob_end_flush(); ?>
