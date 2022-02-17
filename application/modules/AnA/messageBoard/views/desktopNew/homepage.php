<?php
ob_start('compressHTML');
$tempJsArray = array('user','common');
$googleAds = false;
if(in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
	$googleAds = true;
}
$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
$headerComponents = array(
                'css'   =>  array('ana','quesDiscPosting'),
                'js' => array(),
                'jsFooter'=>    $tempJsArray,
                'showBottomMargin' => false,
                'product' => 'anaDesktopV2',
                'title' =>      $seoTitle,
                'metaDescription' => $metaDescription,
                'canonicalURL' =>$canonicalURL,
            		'googleAds' => $googleAds,
            		'bannerProperties' => $bannerProperties,
                'lazyLoadJsFiles' => true
);
$this->load->view('common/header', $headerComponents);
if(!in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
    echo jsb9recordServerTime('SHIKSHA_CAFE_BUZZ_TAB',1);
}else{
    echo jsb9recordServerTime('SHIKSHA_CAFE_QNA_TAB',1);
}
?>
<?php $this->load->view('desktopNew/listOfUserLayer');?>
 <div class="ana-main-wrapper">
       <div class="ana-container">
           <div class="row">
                 <div class="ana-col-md-8">
                 
                   <div class="row">
                      <div class="ana-wrap">
                        <div class="breadcrumb3">
                             <span><a href="<?=SHIKSHA_HOME;?>"><span><i class="icons ic_brdcrm homeType1"></i></span></a></span>
                             <span class="breadcrumb-arrow">&#8250;</span>
                             <span class="page-t">Ask & Answer </span>                            
                         </div>

                          <div class="pr-titl">
                             <h1>Ask & Answer: <span>India's Largest Education Community</span></h1>
                             <div class="event">1000+Experts<span class="n-bullet"></span>Quick Responses<span class="n-bullet"></span>Reliable Answers</div>
                          </div>
                          
                          

                            <?php $this->load->view('desktopNew/quesDiscPosting'); ?>
                  
                                </div>
                                
                   <!--tabs card-->
                   <div id="delete-layer" class="tags-layer" ></div>
                   <div id="closeDeletelayer" class="posting-layer"  style="width:580px;">
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
                      <div class="ana-selection">
                      		<?php	if($pageType == "ALL_QUESTION_PAGE"){?>
                      					<h2 class="m-head">All Questions</h2>
                      		<?php	}elseif($pageType == "ALL_DISCUSSION_PAGE"){?>
                      					<h2 class="m-head">All Discussions</h2>
                      		<?php	}elseif($pageType == "discussion"){?>
                      					<h2 class="m-head">Discussions for you</h2>
                      		<?php	}elseif ($pageType == "unanswered") {?>
                                        <h2 class="m-head">Unanswered questions for you</h2>
                            <?php   }else{?>
                                        <h2 class="m-head">Questions & Discussions for you</h2>
                            <?php   }?>
                      		
                            <?php	if(!in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
                            			$this->load->view('desktopNew/homePageTabs');
		                            }
                            ?>
                            <div class="tabs-content">
                            <?php	$this->load->view('desktopNew/homepageTabsData');
                            		if(!in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
                            ?>
                            		<div id='ajaxData'>
                              	</div>
                                <div id='noData'>
                                  <?php
                                  if($pageType == "discussion"){
                                    echo "No more discussions for you.";
                                  }else if($pageType == "unanswered"){
                                    echo "No more unanswered questions for you";
                                  }else{
                                    echo "No more stories for you.";
                                  }                                  
                                  ?>
                                </div>
                            <?php	}
                            ?>
                            <?php	if(in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
                            ?>
                            		<div class="n-pagination">
                            			<?php	if(is_array($datePagination) && isset($datePagination['nextDayPagination'])){?>
                            						<a href="<?=$datePagination['nextDayPagination']['url']?>" class="left-a">« <?=$datePagination['nextDayPagination']['text']?></a>
                            			<?php	}?>
                            			<?= doPagination_AnA($totalRecords, $paginationURLPattern, $currentPage, $resultPerPage, 10, $pageType.'_D',$GA_userLevel)?>
                            			<?php	if(isset($datePagination) && isset($datePagination['previousDayPagination'])){?>
                            						<a href="<?=$datePagination['previousDayPagination']['url']?>" class="right-a"> <?=$datePagination['previousDayPagination']['text']?>»</a>
                            			<?php	}?>
                            			<p class="clr"></p>
                            		</div>
		                    <?php	}?>
                            </div>
                            <?php	if(!in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
                           	?>
                           			<div id="loadingNew" style="text-align: center; margin-top: 10px;display:none;">
                                		<img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader">
                            		</div>
		                    <?php	}
                            ?>
                            <?php	if(in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
                           //   $this->load->view('messageBoard/desktopNew/widgets/googleAds',array('pageId'=>'ASK_DISCUSS'));
                              ?>
                              <div id='sponsored-links-new'></div>
                              <?php
                					}
                			?>
                    </div>
                    <!--end of tabs-->
                   </div> 
                 </div>
                 <!--right panel-->
                <?php $this->load->view("desktopNew/widgets/rightWidget"); ?>
                 <!--right panel ends-->    
            </div>
            <p class="clr"></p>
       </div>
       <a href="javascript:void(0);" class="scrollToTop"></a>
    </div>

        <div data-enhance="false">
            <!--input type="hidden" id="nextPaginationIndex" value="<?php echo $nextPaginationIndex;?>" />
            <input type="hidden" id="nextPageNo" value="<?php echo $nextPageNo;?>" /-->
            <input type="hidden" id="pageType" value="<?php echo $pageType;?>" />
            <input type="hidden" id="entityIdUserList" value="">
            <input type="hidden" id="entityTypeUserList" value="">
            <input type="hidden" id="actIonForUserList" value="">
            <input type="hidden" id="userTotalCount" value="">
            <input type="hidden" id="tracking_keyid_UserList" value="">
            <input type="hidden" id="tracking_keyid_Most_Active" value="<?php echo $fuRightActiveTrackingPageKeyId;?>">
        </div>  
<script>
function LazyLoadAnADesktopCallback(){
    $LAB
    .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_desktop");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>',
            '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v5");?>')
    .wait(function(){
      LazyLoadCompleteCallbackAnA({'page' : homePageType , 'postingType' : postingType});
    });
}
</script>
<?php 
    $dataApp = Modules::run('mcommon5/MobileSiteHamburger/getAppBanner','desktop');
    echo json_decode($dataApp, true);
    if(in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
        ?>
    <div id='sponsored-links-old' style='display:none;'>
      <?php //$this->load->view('messageBoard/desktopNew/widgets/googleAds',array('pageId'=>'ASK_DISCUSS'));?>
    </div>
    <?php
    }  
    $this->load->view('common/footer');
    $this->load->view('desktopNew/shareLayer');
    $this->load->view('messageBoard/desktopNew/toastLayer');
    echo includeJSFiles('shikshaDesktopWebsiteTour');
?>
<script>
var GA_currentPage = "<?=$GA_currentPage;?>";
var ga_user_level = "<?php echo $GA_userLevel;?>";
var isShowAppBanner = 0; //Display App Banner on Homepage, All QUestion page, All Discussion page
<?php if(!empty($viewTrackParams)){ ?>
  window['trackingPageType'] = '<?php echo $viewTrackParams['trackingPageType'];?>';
  window['answerViewThreshold'] = <?php echo $viewTrackParams['answer'];?>;
  window['questionViewThreshold'] = <?php echo $viewTrackParams['question'];?>;
  window['threadViewJSWorkerInterval'] = <?php echo $viewTrackParams['trackDuration'];?>;
<?php } ?>
    //Load website tour
    $j(document).ready(function(){
        window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        initializeWebsiteTour('cta','anaDesktopV2',contentMapping);
    });
</script>
<?php
		if(in_array($pageType, array('ALL_QUESTION_PAGE', 'ALL_DISCUSSION_PAGE'))){
?>
			<script>
      var homePageType = 'allQDPage';
			var postingType = 'question';
			<?php	if($pageType == 'ALL_DISCUSSION_PAGE' && isset($data['userDetails']['levelId']) && intval($data['userDetails']['levelId']) >= 11){?>
						postingType = "discussion";
			<?php	}?>
			</script>
<?php	}
    else{?>
			<script>
      var homePageType = 'homePageAnA';
        nextPaginationIndex = "<?php echo $nextPaginationIndex;?>";
        nextPageNo = "<?php echo $nextPageNo;?>";
        postingType = "question";
        <?php
            if($pageType == 'discussion' && isset($data['userDetails']['levelId']) && intval($data['userDetails']['levelId']) >= 11){
                ?>
                    postingType = "discussion";
                <?php
            }
        ?>
 			</script>
<?php	}
?>
<?php
ob_end_flush();
?>
