<?php ob_start('compress'); ?>

<?php
$this->load->view('/mcommon5/headerV2',$headerComponents);
?>
<style type="text/css"> 
        <?php $this->load->view('/mAnA5/mobile/css/anaStyleCSS'); ?> 
</style> 
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>

<div id="wrapper" data-role="page" style="min-height: 413px;padding-top:40px;" >	

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER'] || ($_SERVER['HTTP_REFERER'] == $currentUrlWithParams)){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
}
else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
        $displayHamburger = true;
}

//Put a check that if Hash value is added, we have to show the Hamburger
if(strpos($_SERVER["REQUEST_URI"], 'showHam') > 0){
	$displayHamburger = true;
}

if(isset($data['entityDetails']['viewCount']) && $data['entityDetails']['viewCount']==0){
	$displayHamburger = true;
}

if($displayHamburger){
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
}
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>

    <header id="page-header"  data-role="header" class="header ui-header-fixed" data-position="fixed">
      <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'',$boomr_pageid,$isShowIcpBanner);?>
    </header>

   <div data-role="content" data-enhance="false">
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
<?php $this->load->view('qdpFeedbackLayer');?>
    <div class="qdp-container" id="qdpContainer">
       <?php if($entityType == 'discussion'){?>
       <div class="ana-head">
            <p class="ana-titl">           
              <i class="ana-img"></i>
            <?=$entityType?></p>
       </div>
       <?php } ?>
   
            <div class="qdp-layer" style="display:none;">
                <div class="cmnts-layer" style="display:none" id="confirmLayer_page">
                   <a href="javascript:void(0);" class="layer-cls" onclick="hideConfirmationMessage('page')">&times</a>
                      <div class="cmnts-msg">
                            <h2 id="confirmHeading_page"></h2>
                            <p id="alertHeading_page"></p>
                      </div>
                      <div class="usr-choice">
                        <a href="javascript:void(0);" onclick="hideConfirmationMessage('page')">CANCEL</a>
                        <a href="javascript:void(0);" id="performAction_page">YES</a>
                      </div>
              </div>
        </div>
        
        <!-- Alert Message Layer Starts -->
        	<!-- 
        Movd to FooterDialogCode,php
          <div class="comment-layer" id="answerResoonseLayer" style="display:none;">
        		<div class="cmnts-layer">
                	<a href="javascript:void(0);" class="layer-cls" onclick="hideAnswerResponseMessage()">&times</a>
                   	<div class="cmnts-msg">
                       	<p id="answerMessageLayer"></p>
                   	</div>
                   	<div class="usr-choice">
                   		<a href="javascript:void(0);" onclick="hideAnswerResponseMessage()" >OK</a>
                    </div>
              	</div>
        	</div> -->
        <!-- Alert Message Layer Ends -->
    	  <?php $this->load->view('mobile/entityDetailContent');?>

           <div class="qdp-card" >

                <?php $this->load->view('mobile/answerCommentTab');
                      if($entityType == 'question'){
                            $childEntity = 'Answers';
                            $GA_viewMoreChild = 'VIEWMOREANSWERS_QUEST_QUESTIONDETAIL_WEBAnA';
                            $GA_currentPage = 'QUESTION DETAIL PAGE';
                            $GA_commonCTA_Name = '_QUESTION_DETAIL_PAGE_MOB';

                      }else if($entityType == 'discussion'){
                            $childEntity = 'Comments';
                            $GA_viewMoreChild = 'VIEWMORECOMMENTS_DISC_DISCUSSIONDETAIL_WEBAnA';
                            $GA_currentPage = 'DISCUSSION DETAIL PAGE';
                            $GA_commonCTA_Name = '_DISCUSSION_DETAIL_PAGE_MOB';

                      }  

                ?>
                <?php if($data['entityDetails']['childCount']>0){?>
                    <div class="mt-card" id="answerContainer">
                    <div id="answerTuple">
                        <?php $this->load->view('mobile/answerCommentDetailContent');?>
                    </div>
                    </div>
                    <?php if($data['entityDetails']['showViewMore']==true){?>
                          <a class="view-ans" id="viewMoreButton_<?=$entityId?>" href="javascript:void(0)" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_viewMoreChild?>','<?=$GA_userLevel?>');loadMoreChildEntity('<?=$entityId?>')">View more <?=$childEntity?></a>
               <?php } }?>
        
            </div>
           
            <?php 
              $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'QDP_LAA'));
               if($data['entityDetails']['childCount'] == 0){?>
            <div style="background: #ececec;border-bottom: 1px solid #ccc">
              <p style="display:block;text-align:center;color:#000;opacity:0.6;padding:14px 0px;background:#FFF;font-size:14px;">No <?=strtolower($childEntity);?> yet</p>
            </div>
            <?php } ?>

            <?php echo Modules::run('mAnA5/AnAMobile/clientWidgetAnA', $data['entityDetails']['tagsDetail']); ?>

            <?php
            if($pageType == 'question'){
                echo Modules::run("Interlinking/InterlinkingFactory/getEntityRHSWidget", $tagRHSwidgetData,'questionDetailPage');
            }

            ?>

            <div id="relatedLinkedWidget">

            </div>

            <?php $this->load->view('mobile/promotionWidget');?>

    </div>
       
            <div data-enhance="false">
                <input type="hidden" name="start" id="start" value="<?php echo ($start+$count);?>" />
                <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
                <input type="hidden" name="sortOrder" id="sortOrder" value="<?php echo $sortOrder;?>" />
                <input type="hidden" name="referenceAnswerId" id="referenceAnswerId" value="<?php echo $referenceAnswerId;?>" />
                <input type="hidden" name="entityType" id="entityType" value="<?php echo $entityType;?>" />
                <input type="hidden" name="showViewMore" id="showViewMore" value="<?php echo $data['entityDetails']['showViewMore'];?>" />
                <input type="hidden" name="commentCount" id="commentCount" value="" />
                <input type="hidden" name="userLoggedState" id="userLoggedState" value="<?=$GA_userLevel?>">
                <input type="hidden" name="userTotalCount" id="userTotalCount" value="">
                <input type="hidden" name="entityIdUserList" id="entityIdUserList" value="">
                <input type="hidden" name="entityTypeUserList" id="entityTypeUserList" value="">
                <input type="hidden" name="actIonForUserList" id="actIonForUserList" value="">

            </div>
          

            <?php 
            global $pagesToShowBtmRegLyr;
            if($validateuser['userid']<1 && in_array($beaconTrackData['pageIdentifier'],$pagesToShowBtmRegLyr) ){
              $footerJs = array('ana','websiteTour','bootstrap-tour-standalone.min','bottomStickyRegistration');
              $footerCss= array('tuple','websiteTour','bottomStickyRegistration');
            }else{
              $footerJs = array('ana','websiteTour','bootstrap-tour-standalone.min');
              $footerCss= array('tuple','websiteTour');
            }

            ?>
            <?php $this->load->view('/mcommon5/footerLinksV2',array("jsFooter"=>$footerJs,
              "cssFooter"=>$footerCss)); ?>
	    <?php $this->load->view('mobile/shareLayer'); ?>
   </div>
   
</div>

<!--comment detail layer section start-->
<div data-role="page" id="commentDetails" class="clearfix content-wrap" data-enhance="false" style="display:none;">
    <div class="cmnst-container">
         <?php if($entityType == 'question'){
                  $childType = 'Comment';
                  $GA_currentPageComment = 'ANSWERCOMMENT';
                  $GA_tapOnAddComment = 'ADD_ANSCOMMENTPAGE_WEBAnA';
          }else{
                  $childType = 'Reply';
                  $GA_currentPageComment = 'COMMENTREPLY';
                  $GA_tapOnAddComment = 'ADD_COMMENTREPLYPAGE_WEBAnA';
          } ?>
        <!--comment detail layer section heading-->
         <div class="cmnts-col" data-enhance="false">
            <div class="c-box"><p class="c-titl" id="commentLayerHeading"></p></div>
            <div class="c-cls"><a href="javascript:void(0);" onclick ="closeCommentLayer();" id="commentLayerClose">&times;</a></div>
         </div>
         <div class="cmnts-show">
             <div class="n-cmnt" >
                 <div class="comment-Container" style="overflow:auto;">
                    <div id="commentContainer"></div>
                    <div id="lazyLoadDiv"></div>
                 </div>
             </div>
         </div>
    </div>
    <div class="btn-tab-wrap" >
      <?php if($childType == 'Reply')
            $addNewPostTrackingPageKeyId = $rtrackingPageKeyId;
            elseif($childType == 'Comment')
              $addNewPostTrackingPageKeyId = $ctrackingPageKeyId;
      ?>
        <a href="javascript:void(0);" onclick="gaTrackEventCustom('<?=$GA_currentPageComment?>','<?=$GA_tapOnAddComment?>','<?=$GA_userLevel?>');makeCommentPostingLayer('<?=$entityId ?>','','<?=$entityType?>','<?=$childType?>','0','<?php echo $addNewPostTrackingPageKeyId;?>')" data-inline="true" data-rel="dialog" data-transition="fade" class="add-cmnt commentPosting">Add new <?=$childType;?></a>
    </div>  
</div>
<!--comment detail layer section end-->

<!--user list layer section start-->
<div data-role="page" id="userListLayerDiv" class="clearfix content-wrap" data-enhance="false" style="display:none;">
    <div class="upvote-layer">
        <div class="upvote-h1">
            <h1 id="userListHeading"></h1>
            <a class="upvote-cls flRt" href="javascript:void(0);" onclick="closeUserListlayer()">×</a>
        </div>
        <div class="upvote-data" style="overflow:auto;">
              <div id="userListContainer"></div>
               <div id="userListLazyLoadDiv"></div>
        </div>
    </div>
</div>
<!--user list layer section end-->
<style type="text/css">
  /*success msg layer*/
        .layer__blue {
            position: fixed;
            top: 40%;
            left: 50%;
            width:90%;
            max-width: 350px;
            background: #fff;
            transform: translate(-50%, -50% );
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50% . -50%);
            z-index: 99999;
        }

        .blue__bg {
            background-color: #00a5b5;
            padding: 10px;
            position: relative;
        }

        .f14__clrf {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .txt__contnets {
            padding: 10px 15px 10px 10px;
            display: table-cell;
            height: 85px;
            width: 100%;
            vertical-align: middle;
        }

        a.rmv__layer {
            color: #fff;
            position: absolute;
            font-weight: 600;
            cursor: pointer;
            right: 0px;
            top: 0px;
            width: 40px;
            font-size: 14px;
            line-height:36px;
            text-align: center;
        }

        .email__sent__sucess {
            text-align: left;
            font-size: 13px;
            list-style-type: none;
            padding: 0 0 0 40px;
            position: relative;
            line-height:1.4;
        }

            .email__sent__sucess:before {
                content: " ";
                display: block;
                border: solid 0.8em #0c6;
                border-radius: 50%;
                height: 1px;
                width: 0px;
                position: absolute;
                left: 0.5em;
                top: 40%;
                margin-top: -0.5em;
            }

            .email__sent__sucess:after {
                content: " ";
                display: block;
                width: 0.3em;
                height: 0.6em;
                border: solid #fff;
                border-width: 0 0.2em 0.2em 0;
                position: absolute;
                left: 1.1em;
                top: 40%;
                margin-top: -2px;
                -webkit-transform: rotate(45deg);
                -moz-transform: rotate(45deg);
                -o-transform: rotate(45deg);
                transform: rotate(45deg);
            }

        .main__layer {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }
        .dwn-eguide.ecta-disable-btn{background:none repeat scroll 0 0 #e7e9e7;color:#333!important;cursor:not-allowed;border:1px solid #e7e9e7;pointer-events: none;}
        .dwn-eguide.ecta-disable-btn:hover{background:#e7e9e7!important}
        /*end success layer*/
</style>

<?php $this->load->view("mobile_examPages5/newExam/widgets/successLayer")?>
<?php $this->load->view('/mcommon5/footerV2');?>


<script>
    
  <?php if(!empty($viewTrackParams)){ ?>
  window['trackingPageType'] = '<?php echo $viewTrackParams['trackingPageType'];?>';
  window['answerViewThreshold'] = <?php echo $viewTrackParams['answer'];?>;
  window['questionViewThreshold'] = <?php echo $viewTrackParams['question'];?>;
  window['threadViewJSWorkerInterval'] = <?php echo $viewTrackParams['trackDuration'];?>;
  <?php } ?>
  var HTTP_REFERER = '<?php echo $_SERVER["HTTP_REFERER"];?>';
  var entityId ='<?php echo $entityId;?>';
  var type = '<?php echo $pageType;?>';
  $(document).ready(function(){
    handleToastMsg();
    if(typeof(initHideLayer) == 'function'){
      initHideLayer();
    }

    //QDP/DDP page view count tracking
    var img = document.getElementById('beacon_img');
    var randNum = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0003003/<?=$data['entityDetails']['msgId']?>';
  });
  var GA_currentPage = "<?php echo $GA_currentPage;?>";
  var ga_user_level = "<?php echo $GA_userLevel;?>";
  var ga_commonCTA_name = "<?php echo $GA_commonCTA_Name;?>";
  var answerIdToEdit  = "<?=$answerIdToEdit?>";
  var showFeedbackLayer = <?php echo $showFeedbackLayer;?>;
  if(showFeedbackLayer==1)
  {
    var lastAnswerId = "<?php echo $lastAnswerId; ?>";
    var numberOfAnswers = "<?php echo $numberOfAnswers;?>";
    var questionId = <?php echo $entityId;?>;
  }
  var openAnsBox  = "<?=$openAnsBox?>";
  var searchPageName = "mobilePageQDP";
  var contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
</script>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php ob_end_flush(); ?>
