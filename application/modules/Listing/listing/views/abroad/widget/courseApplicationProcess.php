<?php
$showLoginBtn = false;
if(empty($loggedInUserData)){
  $showLoginBtn = true;
  $iconApp = 'lock-icon';
}else{
  $iconApp = 'plus-icon';
}

$stepCount=1;
$contentLength = 110;
if($courseFeeData["fromCurrency"] == $courseFeeData["toFormattedCurrency"])
    $hideToAmountSection = "display : none;";
    
if(!empty($currencyMapping[$courseFeeData['fromCurrency']])) {
$fromCurrencyUnit = $currencyMapping[$courseFeeData['fromCurrency']];
}else{
$fromCurrencyUnit = $courseFeeData["fromCurrenyObj"]->getCode();
}

$firstSteptoShow = array();

if($stepByStepFlag['step1']!='')
{
  if(strlen(strip_tags($applicationProcessData['sopComments']))<$contentLength/2)
  {
    if(strlen(strip_tags($applicationProcessData['sopComments']))>0){
    $firstSteptoShow['SOP'] = 'sopComments';
    }
    if($applicationProcessData['lorComments'] !='')
    {
      $firstSteptoShow['LOR'] = 'lorComments';
    }
    else
    {
      if($applicationProcessData['essayComments'] !='')
      {
        $firstSteptoShow['ESSAY'] = 'essayComments';
      }
      else
      {
        if($applicationProcessData['cvComments'] !='')
        {
          $firstSteptoShow['CV'] = 'cvComments';
        }
      }
    }
    
  }
  else
  {
    $firstSteptoShow['SOP'] = 'sopComments';
  }
}
$step1RequiredFlag = ($applicationProcessData['sopRequired']==1) || ($applicationProcessData['lorRequired']==1) || ($applicationProcessData['essayRequired']==1) || ($applicationProcessData['cvRequired']==1);
?>
<div class="course-detail-tab applicationProcess-Tab applicationProcess clearfix">
  <div class="course-detail-mid flLt">
    <div id="applicationProcess-Tab" class="clearwidth cons-scrollbar1 scrollbar1 soft-scroller">
        <div class="cons-scrollbar scrollbar" style="visibility:hidden;">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:328px">
            <div class="overview course-overview process-tab clearfix" style="width:100%;">
                <?php if($stepByStepFlag['step1']!='' || $step1RequiredFlag){?>
                <div class="app-tab">
                  <label for="app-tab-one" onclick="updateApplicationProcessScroll(this)"><span>Step <?php echo $stepCount++;?>: </span>Prepare documents for application
                    <i class="listing-sprite <?=$iconApp?>"></i>
                  </label>
                  <div class="app-tab-content">
                      <ul style="margin:0px;">                          
                          <!--Full Content Data Start--> 
                          <?php if($applicationProcessData['sopComments']!=''){?>
                          <li class="fullDataSop" style="margin-bottom:0px;"><div class="dyanamic-content-2"><strong>SOP</strong>: <?= $applicationProcessData['sopComments'];?></div></li>
                          <?php } ?>
                          <?php if($applicationProcessData['lorComments']!=''){?>
                          <li class="fullDataSop" style="margin-bottom:0px;"><div class="dyanamic-content-2"><strong>LOR</strong>: <?= $applicationProcessData['lorComments'];?></div></li>
                          <?php } ?>
                          <?php if($applicationProcessData['essayComments']!=''){?>
                          <li class="fullDataSop" style="margin-bottom:0px;"><div class="dyanamic-content-2"><strong>ESSAY</strong>: <?= $applicationProcessData['essayComments'];?></div></li>
                          <?php }?> 
                          
                          <?php if($applicationProcessData['cvComments']!=''){?>
                          <li class="fullDataSop" style="margin-bottom:0px;"><div class="dyanamic-content-2"><strong>CV</strong>: <?= $applicationProcessData['cvComments'];?></div></li>
                          <?php }?>                         
                      <!--Full Content Data End --> 
                      </ul> 
                      <?php if($showLoginBtn) {?>
                       <div class="gradient-app-div" style="display: block;">
                            <a href="Javascript:void(0)" class="clpLoginBtn button-style bold" onclick="showAppProcessAuthPage();" class="button-style bold">Login to View Details</a>
                       </div>
                      <?php $showLoginPage = false;}?>
                  </div> 
                </div>                 
                  <?php }?>
                  
                  <?php if($stepByStepFlag['step2']!=''){?>
                  <div class="app-tab">
                    <label for="app-tab-second" onclick="updateApplicationProcessScroll(this)"><span>Step <?php echo $stepCount++;?>: </span>Additional documents required<i class="listing-sprite <?=$iconApp?>"></i></label>
                    <div class="app-tab-content">                      
                    <?php if(strlen($applicationProcessData['allDocuments'])>$contentLength){?>
                      <div class="fullDataStep2 dyanamic-content-2"><?= $applicationProcessData['allDocuments'];?></div>                      
                    <?php }else{?>
                      <div class="smallDataStep2 dyanamic-content-2"><p><?php echo $applicationProcessData['allDocuments'];?></p></div>  
                    <?php } ?>
                    <?php if($showLoginBtn) {?>
                       <div class="gradient-app-div" style="display: block;">
                            <a href="Javascript:void(0)" class="clpLoginBtn button-style bold" onclick="showAppProcessAuthPage();" class="button-style bold">Login to View Details</a>
                       </div>
                      <?php $showLoginPage = false;}?>
                    </div>
                    <?php } ?>
                  </div>


                
                  
                  <?php if($stepByStepFlag['step3']!=''){?>
                  <div class="app-tab">                    
                    <label for="app-tab-third" onclick="updateApplicationProcessScroll(this)"><span>Step <?php echo $stepCount++;?>: </span>Start your online application<i class="listing-sprite <?=$iconApp?>"></i></label>
                    <div class="app-tab-content">
                      <p><a href="<?= $applicationProcessData['applyNowLink'];?>" target="_blank" rel=nofollow><?= htmlentities($applicationProcessData['applyNowLink']);?></a></p>                    
                      <?php if($showLoginBtn) {?>
                       <div class="gradient-app-div" style="display: block;">
                            <a href="Javascript:void(0)" class="clpLoginBtn button-style bold" onclick="showAppProcessAuthPage();" class="button-style bold">Login to View Details</a>
                       </div>
                      <?php $showLoginPage = false;}?>
                    </div>                    
                  </div>
                <?php } ?>

                <?php if($stepByStepFlag['step4']!=''){?>
                  <div class="app-tab">
                    <label for="app-tab-four" onclick="updateApplicationProcessScroll(this)"><span>Step <?php echo $stepCount++;?>: </span>Application fees for this course<i class="listing-sprite <?=$iconApp?>"></i></label>
                    <div class="app-tab-content">
                                  <?php if($applicationProcessData['feeAmount'] >0){?>
                                <p><span style="<?= $hideToAmountSection;?>"><?= $fromCurrencyUnit ?> <?= $applicationProcessData['feeAmount'];?> => </span><?= $applicationProcessData['convertedFeeDetail'];?></p>
                                <?php } ?>
                                <?php
                                $paymentMode = array();
                                if($applicationProcessData['isCreditCardAccepted']==1){
                                  $paymentMode[] = 'Credit card';
                                }
                                if($applicationProcessData['isDebitCardAccepted']==1){
                                  $paymentMode[] = 'Debit card';
                                }
                                if($applicationProcessData['iswiredMoneyTransferAccepted']==1){
                                  $paymentMode[] = 'Wire money transfer';
                                }
                                if($applicationProcessData['isPaypalAccepted']==1){
                                  $paymentMode[] = 'Paypal';
                                }
                                
                                if(count($paymentMode)>0){
                                ?>
                                <p>Mode of payment: <?= implode(', ',$paymentMode);?></p>
                                <?php } ?>
                                <?php if((count($paymentMode)>0 && strlen($applicationProcessData['feeDetails'])>0) || strlen($applicationProcessData['feeDetails'])>($contentLength/2)){?>
                                <div  class="fullDataStep4 dyanamic-content-2 clearfix"><?= $applicationProcessData['feeDetails'];?></div>                                
                                <?php }?>

                                <?php if($showLoginBtn) {?>
                       <div class="gradient-app-div" style="display: block;">
                            <a href="Javascript:void(0)" class="clpLoginBtn button-style bold" onclick="showAppProcessAuthPage();" class="button-style bold">Login to View Details</a>
                       </div>
                      <?php $showLoginPage = false;}?>
                                
                    </div>                    
                  </div>
                <?php } ?>

                <?php if($stepByStepFlag['step5']!=''){?>
                  <div class="app-tab">
                    <label for="app-tab-five" onclick="updateApplicationProcessScroll(this)"><span>Step <?php echo $stepCount++;?>: </span>Application deadline<i class="listing-sprite <?=$iconApp?>"></i></label>
                    <div class="app-tab-content">
                      <?php foreach($applicationProcessData['submissionDateData'] as $key=>$tuple){?>
                    <p><?= htmlentities($tuple['applicationSubmissionName'])?> : <?= date('jS M Y',strtotime($tuple['applicationSubmissionLastDate']));?></p>
                    <?php } ?>
                    <?php if($showLoginBtn) {?>
                       <div class="gradient-app-div" style="display: block;">
                            <a href="Javascript:void(0)" class="clpLoginBtn button-style bold" onclick="showAppProcessAuthPage();" class="button-style bold">Login to View Details</a>
                       </div>
                      <?php $showLoginPage = false;}?>
                    </div>                    
                  </div>
                <?php } ?>                  
            </div>
         </div>
  </div>
  </div>
<!--this section is populated by the apply content data not the application process data-->
  <div class="course-detail-rt flRt">
  <?php
  // we will show only SOP LOR AND Visa on right side widget so rest will be unset here 
  foreach ($applicationProcessRightWidgetData as $key => $value) {
     if(!in_array($value['type'], array('SOP','LOR','Visa'))){
        unset($applicationProcessRightWidgetData[$key]);
     }
   } 

  if(!empty($applicationProcessRightWidgetData)){?>
  <div style="padding:10px 7px; width:100%; float:left;" class="course-rt-tab">
    <strong class="font-11">Learn about application process</strong>
      <ul class="app-process-ryt-list">
        <?php 
        $count = count($applicationProcessRightWidgetData);
        $i=1;
        foreach ($applicationProcessRightWidgetData as $key => $value) { ?>
          <li <?php if($i==$count){ echo "class='last'"; } ?>>
            <a href="<?php echo $value['contentURL']; ?>"><?php if($value['type']=='CV' || $value['type']=='Visa' ){echo "Student ";} else if($value['type']=='Essay'){echo "Admission ";}?><?php echo $value['type']; ?></a>
              <p><?php echo $value['name']; ?></p>
          </li>
          <?php $i++; }?>
      </ul>
  </div>
  <?php } ?>
  <?php 
    $linkingWidgetData = array('gaParams'=>'COURSEPAGE_APPLICATION_PROCESS_TAB,applyPageLinkingWidget',
                                'applyLinkWidgetTitle' => '',
                                'applyLinkWidgetDesc'  => ''
                                );
    $this->load->view('listing/abroad/widget/applyHomeLinkingWidget',$linkingWidgetData);
    ?>
  <!--This rate my chance button-->
  <?php     //$param['widget'] = 'applicationProcessTab';
            //$param['trackingPageKeyId'] = 42;
            //$this->load->view('listing/abroad/widget/rateMyChanceWidget',$param);
  ?>
</div>
</div>