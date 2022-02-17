<?php
ob_start('compressHTML');
$tempJsArray = array('common');
$tempCssArray = array('quesDiscPosting');
if($entityType == 'question'){
  array_push($tempJsArray, 'recoSlider');
  array_push($tempCssArray, 'entityWidget');
}
$bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER');
$headerComponents = array(
                'css'   =>  array('ana', 'bottomStickyRegistrationLayer'),
                'js' => array(),
                'jsFooter'=>    $tempJsArray,
                'cssFooter' => $tempCssArray,
                'showBottomMargin' => false,
                'product' => 'anaDesktopV2',
                'title' =>      $metaTitle,
                'metaDescription' => $metaDescription,
                'canonicalURL' =>$canonicalURL,
                'previousURL' => $prevURL,
                'nextURL' => $nextURL,
            		'googleAds' => true,
            		'bannerProperties' => $bannerProperties,
                'lazyLoadJsFiles' => true,
		'alternate' => $alternate
);
$this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_CAFE_QUESTION_DETAIL',1);
?>
<style type="text/css">
/* homepageRegistrationLayer*/
.Overlay{display:none;padding:10px;position:absolute;width:250px;z-index:1000001}
/*quesDiscPsosting*/
.posting-layer{display: none;}
.post-col {
    display: block;
    padding: 15px 20px;
    width: 780px;
    background: #fff;
    position: relative;
    border: 1px solid;
    border-color: #e5e6e9 #dfe0e4 #babbbd; }
     .post-col .post-qstn {
      display: block;
      position: relative; }
       .post-col .post-qstn h3.post-h2 {
        font: 400 12px/16px "Open Sans",sans-serif;
        margin-bottom: 12px;
        color: #4d4d4d;
        display: block;}
       .post-col .post-qstn p.qstn-title {
        position: relative;
        color: #595a5c;
        font-size: 18px;
        font-weight: 600;
        margin-top: -8px; }
  .post-col .post-qstn p.qstn-title .edit-qstn {
          color: #0aa5b5;
          text-transform: capitalize;
          font-size: 12px;
          text-decoration: none;
          position: relative;
          margin-left: 5px;
          padding-left: 18px;
          font-weight: 400; }
 .post-col .post-qstn .qstn-input {
        width: 100%;
        display: inline-block;
        font-size: 14px;
        padding: 8px 15px;
        overflow: auto;
        resize: none;
        height: 40px;
        color: #1c252c;
        overflow: hidden;
        border: 1px solid #ccc;
        -webkit-appearance: none; }
         .post-col .post-qstn .qstn-input::-webkit-input-placeholder {
          color: #a2a9ae; }
         .post-col .post-qstn .qstn-input::-moz-placeholder {
          color: #a2a9ae; }
         .post-col .post-qstn .qstn-input:-ms-input-placeholder {
          color: #a2a9ae; }

/*exam CTA success msg layer*/
.layer__blue {
      position: fixed;
      top: 40%;
      left: 50%;
      width: 550px;
      background: #fff;
      transform: translate(-50%, -50% );
      -webkit-transform: translate(-50% , -50%);
      -moz-transform: translate(-50% . -50%);
      z-index: 99999;
    }
    .blue__bg {
      background-color: #00a5b5;
      padding: 10px 10px 10px 18px;
      position: relative;
  }
    .f16__clrf {
       color: #fff;
       font-size: 16px;
       font-weight: 600;
     }
     .txt__contnets {
        padding-left: 10px;
        display: table-cell;
        height: 130px;
        width: 100%;
        vertical-align: middle;
    }
     a.rmv__layer {
      color: #fff;
      position: absolute;
      font-weight: 600;
      cursor: pointer;
      right: 13px;
      top: 7px;
      width: 22px;
      font-size: 19px;
      text-align: center;
  }
  .email__sent__sucess {
      text-align: left;
      font-size: 14px;
      
      list-style-type: none;
      padding: 0 27px 0 45px;
      position: relative;
  }
  .email__sent__sucess:before {
      content: " ";
      display: block;
      border: solid 14px #0c6;
      border-radius: 50%;
      height: 1px;
      width: 0px;
      position: absolute;
      left: 0.5em;
      top: 50%;
      margin-top: -14px;
  }
  .email__sent__sucess:after {
    content: " ";
    display: block;
    width: 7px;
    height: 11px;
    border: solid #fff;
    border-width: 0 0.2em 0.2em 0;
    position: fixed;
    left: 2em;
    top: 50%;
    margin-top: 15px;
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    transform: rotate(45deg);
}
.email__sent__sucess.long_text:before{
  top:50%;
}
.email__sent__sucess.long_text:after{
  top:50%;
}
.main__layer {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 999;
    display: none;
}
.ana-btns.a-btn.dwn-eguide.ecta-disable-btn{background:none repeat scroll 0 0 #e7e9e7;color:#babbbd!important;cursor:not-allowed;border:1px solid #e7e9e7;pointer-events: none;}
.ana-btns.a-btn.dwn-eguide.ecta-disable-btn:hover{background:#e7e9e7!important}
/*end success layer*/

</style>
<?php if(isset($data['entityDetails']['referrenceName']) && $data['entityDetails']['referrenceName'] != ''){
    $btnClass = 'a-btn';
    $moveHeading = 'Move to AnA';
}else{
    $btnClass = 'd-btn';
    $moveHeading = 'Move to Listing';
}?>
<div id="delete-layer" class="tags-layer"></div>
<?php $this->load->view('desktopNew/qdpFeedbackLayer');?>
<?php if($data['entityDetails']['queryType'] == 'Q'){?>
<div  id="moveQuestionLayer" class="posting-layer" style="width:680px;">
       <form id="moveQuestionToInst" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="moveQuestionToInst">
          <div class="tags-head"><?=$moveHeading?> <a id="cls-add-tags" class="cls-head closeDelLayer" href="javascript:void(0);"></a></div>
          <div class="tag-body">
             <p style="font-size:12px" id="moveQuesHeading">Enter Id of the course to which this question should be moved</p>
                    <div class="btns-col">
                        <span class="tag-div" id="courseIdField">
                          <input type="text" placeholder="Enter Course ID..." minLength='1' maxLength='11' autofocus="" name="mcourseId" id="mcourseId" validate="validateInteger" caption="courseId" required="true" onkeyup = "changeSubmitButtonCss('mcourseId');"/>
                        </span>
                        
                        <span class="right-box">
                             <a class="exit-btn closeDelLayer" href="javascript:void(0);">Cancel</a>
                            <!--<a id="submt-pst" class="ana-btns <?=$btnClass?>" href="javascript:void(0);" onclick="if(validateFields(document.getElementById('moveQuestionToInst')) != true){return false;}else{moveAnaQuesToListingsOrAnA('<?=$data['entityDetails']['threadId']?>','<?=$data['entityDetails']['userId']?>')};">Submit</a>-->
                        </span>
                        <p class="clr"></p>
                    </div>
                    <div>
                        <p class="err0r-msg"  id="mcourseId_error" ></p>
                    </div>
          </div>
      </form>
</div> 
<?php }?>

<!--edit or delete layer-->
<div id="closeDeletelayer" class="posting-layer" style="width:680px;">
        <div class="tags-head" id="actionLayerHeading"><a class="cls-head" href="javascript:void(0);"></a></div>
        <div class="tag-body">
           <p class="msg" id="alertHeading"></p>
                  <div class="btns-col">
                      <span class="right-box" >               
                          <a id="performAction" class="prime-btn" href="javascript:void(0);"></a>
                      </span>
                      <p class="clr"></p>
                  </div>
            </div>
</div> 
<div class="ana-main-wrapper">
   <div class="ana-container qdp-page">
       <div class="row">
             <div class="ana-col-md-8">
               <div class="row">
                    <div class="ana-selection">
                      <div class="breadcrumb3">
                                                 <span><a href="<?=SHIKSHA_HOME;?>"><span><i class="icons ic_brdcrm homeType1"></i></span></a></span>
                                                 <span class="breadcrumb-arrow">&#8250;</span>
                                                 <span><a href="<?=SHIKSHA_ASK_HOME;?>"><span>Ask & Answer</span></a></span>
                                                 <span class="breadcrumb-arrow">&#8250;</span>
                                                 <span class="page-t"><?=ucfirst($entityType); ?> </span>
                            
                                       </div>
                        <!--tabs-content-->
                       <div class="tabs-content">
                          <div class="active" data-index="1">
                             <div> 
                                 <div class="post-col new-post-col">
                                   <?php $this->load->view('desktopNew/entityDetailContent');?>
                                   <?php if($data['entityDetails']['childCount']>0){?>
                                    <ul class="ans-ul">          
                                      <?php $this->load->view('desktopNew/answerCommentTab');?>
                                      <div id='answerTuple'>
                                      <?php $this->load->view('desktopNew/answerCommentDetailContent');?>
                                      </div>
                                    </ul>
                                   
                                    <?php if($data['entityDetails']['childCount']>$count){?>
                                    <div class="n-pagination" id="paginationDiv">

                                            <ul>
                                              <?php echo $paginationHtml; ?>
                                            </ul>
                                      <p class="clr"></p>
                                  </div>
                                  
                                  <?php }} ?>


                                 </div>
                          <?php $this->load->view('dfp/dfpCommonHtmlBanner.php',array('bannerType' => 'content','bannerPlace' => 'C_LAA')); ?>
                              </div>

 	 		      <?php echo Modules::run('messageBoard/AnADesktop/clientWidgetAnA', $data['entityDetails']['tagsDetail']); ?>

                              <?php
                              $this->load->view('desktopNew/linkedAndRelatedEntityTuple',$linkedData);
                              // echo Modules::run('messageBoard/AnADesktop/getRelatedLinkedQuestions',$entityId,$entityType,'moduleRun'); ?> 
                          
                           <p class="clr"></p>
                      </div>
                      
                   </div>
                </div>

                <input type="hidden" name="start" id="start" value="<?php echo $start;?>" />
                <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
                <input type="hidden" name="sortOrder" id="sortOrder" value="<?php echo $sortOrder;?>" />
                <input type="hidden" name="referenceAnswerId" id="referenceAnswerId" value="<?php echo $referenceAnswerId;?>" />
                <input type="hidden" name="entityType" id="entityType" value="<?php echo $entityType;?>" />
                <input type="hidden" name="overFlowTabClickedId" id="overFlowTabClickedId" value="" />
				<input type="hidden" id="entityIdUserList" value="">
                <input type="hidden" id="entityTypeUserList" value="">
                <input type="hidden" id="actIonForUserList" value="">
                <input type="hidden" id="userTotalCount" value="">
                <input type="hidden" id="tracking_keyid_UserList" value="">
				<input type="hidden" id="msgId" value="<?php echo $data['entityDetails']['msgId'];?>">
				<input type="hidden" id="threadId" value="<?php echo $data['entityDetails']['threadId'];?>">	
    			<input type="hidden" id="tracking_keyid_Most_Active" value="<?php echo $fuRightActiveTrackingPageKeyId;?>">
    			<input type="hidden" id="tracking_keyid_Top_Contri" value="<?php echo $fuRightTopTrackingPageKeyId;?>">
        </div>
        <p class="clr"></p>
        <div id='sponsored-links-new'></div>
   </div>
   <!--right panel-->
       <?php $this->load->view("desktopNew/widgets/rightWidget"); ?>
   <!--right panel ends-->    
   
</div>
<script>
function LazyLoadAnADesktopCallback(){
    $LAB
  .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_desktop");?>',
                '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>',
                '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v5");?>',
                '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("bottomStickyRegistrationLayer");?>')
  .wait(function(){ LazyLoadCallBackQDP(); prepareClickData();});
}
</script>
<?php 
    $data1 = Modules::run('mcommon5/MobileSiteHamburger/getAppBanner','desktop');
    echo json_decode($data1, true);
    $this->load->view('desktopNew/shareLayer');
    $this->load->view('messageBoard/desktopNew/toastLayer');
    ?>
    <div id='sponsored-links-old' style='display:none;'>
      <?php //$this->load->view('messageBoard/desktopNew/widgets/googleAds',array('pageId'=>'DISCUSSION_DETAIL')); ?>
    </div>
    <?php
    $this->load->view('common/footer', array('product' => 'anaDesktopV2',));
    echo includeJSFiles('shikshaDesktopWebsiteTour');
?>
<?php $http_referer_cleaned = $this->security->xss_clean($_SERVER['HTTP_REFERER']); ?>
<script>
    var HTTP_REFERER = '<?php echo $http_referer_cleaned;?>';
    var GA_userLevel_QDP = '<?php echo $GA_userLevel;?>';
    var GA_userLevel_AllTags = '<?php echo $GA_userLevel;?>';
    var GA_currentPage = "<?=$GA_currentPage;?>";
    var ga_user_level = "<?php echo $GA_userLevel;?>";
    var ga_commonCTA_name = "<?=$GA_commonCTA_name;?>";
    var isUserModerator = "<?=$isUserModerator;?>";
    var answerIdToEdit  = "<?=$answerIdToEdit?>";
    var openAnsBox  = "<?=$openAnsBox?>";
    var showFeedbackLayer = <?php echo $showFeedbackLayer;?>;
    if(showFeedbackLayer==1)
    {
      var lastAnswerId = "<?php echo $lastAnswerId; ?>";
      var numberOfAnswers = "<?php echo $numberOfAnswers;?>";
      var questionId = <?php echo $entityId;?>;
    }

<?php if(!empty($viewTrackParams)){ ?>
    window['trackingPageType'] = '<?php echo $viewTrackParams['trackingPageType'];?>';
    window['answerViewThreshold'] = <?php echo $viewTrackParams['answer'];?>;
    window['questionViewThreshold'] = <?php echo $viewTrackParams['question'];?>;
    window['threadViewJSWorkerInterval'] = <?php echo $viewTrackParams['trackDuration'];?>;
<?php } ?>
    var isShowAppBanner = 0; //Display App Banner on Detail pages\
    <?php if($entityType == 'question') { ?>
          searchCompareCTAEventAttach();
    <?php } ?>

    //Load website tour
    $j(document).ready(function(){
        window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
        initializeWebsiteTour('cta',window.shikshaProduct,contentMapping);
        examCTAObj.bindExamPageElements();

        //QDP/DDP page view count tracking
        var img = document.getElementById('beacon_img');
        var randNum = Math.floor(Math.random()*Math.pow(10,16));
        img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0003003/<?=$data['entityDetails']['msgId'];?>';

        var bntop = $j('#rightPanelDFPBanner').offset().top + 500;
        $j(window).scroll(function(){
              var ftrh = ($j('#footer').length>0) ? $j('#footer').offset().top : 0; 
              var maxh = $j(this).scrollTop() + $j(this).height();
              if(maxh >= ftrh) {
                $j('#rightPanelDFPBanner').css({'position':'','width':'373px','top':'70px'}); 
              }else if(maxh >= bntop) {
                $j('#rightPanelDFPBanner').css({'position':'fixed','width':'373px','top':'70px'});
              }else {
                $j('#rightPanelDFPBanner').css({'position':'','width':'373px','top':$j('#rightPanelDFPBanner').offset().top}); 
              }
        });

    });
 </script>
 <div  class="main__layer" id="exmPopUpLayer"></div>
 <div id="tags-layer" class="tags-layer"></div>
 <div class="an-layer an-layer-inner" id="an-layer">
      <?php 
      $this->load->view('desktopNew/quesDiscPosting',$data);

      ?>
 </div>
 <img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php 
ob_end_flush();
?>
