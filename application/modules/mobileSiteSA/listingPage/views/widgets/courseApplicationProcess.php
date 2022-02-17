<?php
    $contentLength = 120;
    $stepCount = 1;
    if($courseFeeData["fromCurrency"] == $courseFeeData["toFormattedCurrency"]){
        $hideToAmountSection = "display : none;";
    }
    if(!empty($currencyMapping[$courseFeeData['fromCurrency']])) {
        $fromCurrencyUnit = $currencyMapping[$courseFeeData['fromCurrency']];
    }else{
        $fromCurrencyUnit = $courseFeeData["fromCurrenyObj"]->getCode();
    }
    $step1RequiredFlag = ($applicationProcessData['sopRequired']==1) || ($applicationProcessData['lorRequired']==1) || ($applicationProcessData['essayRequired']==1) || ($applicationProcessData['cvRequired']==1);
?>
<section class="detail-widget navSection" data-enhance="false" id="applicationProcessSection">
    <div class="detail-widegt-sec">
        <div style="padding:10px 0px;" class="detail-info-sec clearfix">
            <div id="applicationProcessSectionTitle" style="padding:0 10px">
                <strong class="flLt">Application process</strong>
                <div class="clearfix"></div>
            </div>
            <div class="step-sections" style="overflow:hidden; padding:0px;">
                <?php if($stepByStepFlag['step1']!='' || $step1RequiredFlag){ ?>
                    <div>
                        <div class="step-section-title">
                            <span>STEP <?=$stepCount++?>:</span>
                            <strong>Prepare documents for application</strong>
                            <i class="minus-preTab"></i>
                        </div>
                        <div class="step-content hidden">
                            <ul class="entry-req-list">
                                <?php if($applicationProcessData['sopComments'] != ''){ ?>
                                    <li>
                                        <?php if(strlen($applicationProcessData['sopComments'])>$contentLength){ ?>
                                            <div class="longVersion dynamic-content">SOP: <?=$applicationProcessData['sopComments'];?></div>
                                        <?php }else{ ?>
                                            <p>SOP: <?=$applicationProcessData['sopComments'];?></p>
                                        <?php } ?>
                                    </li>
                                <?php }else if($applicationProcessData['sopRequired']==1){ ?>
                                    <li>
                                        <div class="shortVersion">SOP: Required</div>
                                    </li>
                                <?php } ?>
                                <?php if ($applicationProcessData['lorComments'] != ''){ ?>
                                    <li>
                                        <?php if(strlen($applicationProcessData['lorComments'])>$contentLength){ ?>
                                            <div class="longVersion dynamic-content">LOR: <?=$applicationProcessData['lorComments'];?></div>
                                        <?php }else{ ?>
                                            <p>LOR: <?=$applicationProcessData['lorComments'];?></p>
                                        <?php } ?>
                                    </li>
                                <?php }else if($applicationProcessData['lorRequired']==1){ ?>
                                    <li>
                                        <div class="shortVersion">LOR: Required</div>
                                    </li>
                                <?php } ?>
                                <?php if ($applicationProcessData['essayComments'] != ''){ ?>
                                    <li>
                                        <?php if(strlen($applicationProcessData['essayComments'])>$contentLength){ ?>
                                            <div class="longVersion dynamic-content">Essay: <?=$applicationProcessData['essayComments'];?></div>
                                        <?php }else{ ?>
                                            <p>Essay: <?=$applicationProcessData['essayComments'];?></p>
                                        <?php } ?>
                                    </li>
                                <?php }else if($applicationProcessData['essayRequired']==1){ ?>
                                    <li>
                                        <div class="shortVersion">Essay: Required</div>
                                    </li>
                                <?php } ?>
                                <?php if($applicationProcessData['cvComments']!=''){?>
                                    <li>
                                        <?php if(strlen($applicationProcessData['cvComments'])>$contentLength){ ?>
                                            <div class="longVersion dynamic-content">CV: <?=$applicationProcessData['cvComments'];?></div>
                                        <?php }else{ ?>
                                            <p>CV: <?=$applicationProcessData['cvComments'];?></p>
                                        <?php } ?>
                                    </li>
                                <?php }else if($applicationProcessData['cvRequired']==1){ ?>
                                    <li>
                                        <div class="shortVersion">CV: Required</div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <?php if($stepByStepFlag['step2']!=''){?>
                    <div>
                        <div class="step-section-title">
                            <span>STEP <?=$stepCount++?>:</span>
                            <strong>Additional documents required </strong>
                            <i class="plus-preTab"></i>
                        </div>
                        <div class="step-content hidden">
                            <ul class="entry-req-list">
                                <li style="list-style:none;">
                                    <?php if(strlen($applicationProcessData['allDocuments'])>$contentLength){ ?>
                                        <div class="longVersion dynamic-content"><?= $applicationProcessData['allDocuments'];?></div>
                                    <?php }else{ ?>
                                        <p><?= $applicationProcessData['allDocuments'];?></p>
                                    <?php } ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <?php if($stepByStepFlag['step3']!=''){?>
                    <div>
                        <div class="step-section-title">
                            <span>STEP <?=$stepCount++?>:</span>
                            <strong>Start your online application</strong>
                            <i class="plus-preTab"></i>
                        </div>
                        <div class="step-content hidden">
                            <a href="<?= $applicationProcessData['applyNowLink'];?>" rel="nofollow" target="_blank"><?= htmlentities($applicationProcessData['applyNowLink']);?></a>
                        </div>
                    </div>
                <?php } ?>
                <?php if($stepByStepFlag['step4']!=''){?>
                    <div>
                        <div class="step-section-title">
                            <span>STEP <?=$stepCount++?>:</span>
                            <strong>Application fees for this course</strong>
                            <i class="plus-preTab"></i>
                        </div>
                        <div class="step-content hidden">
                            <p><span style="<?= $hideToAmountSection;?>"><?= $fromCurrencyUnit ?> <?= $applicationProcessData['feeAmount'];?> => </span><?= $applicationProcessData['convertedFeeDetail'];?></p>
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
                            ?>
                            <?php if(count($paymentMode)>0){ ?>
                                    <p>Mode of payment: <?= implode(', ',$paymentMode);?></p>
                            <?php } ?>
                            <?php if(strlen($applicationProcessData['feeDetails'])>0 && count($paymentMode)==0){ ?>
                                <?php if(strlen($applicationProcessData['feeDetails'])>$contentLength){ ?>
                                    <div class="longVersion dynamic-content"><?= $applicationProcessData['feeDetails'];?></div>
                                <?php }else{ ?>
                                    <p><?= $applicationProcessData['feeDetails'];?></p>
                                <?php } ?>
                            <?php } ?>
                            <?php if(strlen($applicationProcessData['feeDetails'])>0 && count($paymentMode)>0){ ?>
                                <?php if(strlen($applicationProcessData['feeDetails'])>$contentLength){ ?>
                                    <div class="longVersion dynamic-content"><?= $applicationProcessData['feeDetails'];?></div>
                                <?php }else{ ?>
                                    <p><?= $applicationProcessData['feeDetails'];?></p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if($stepByStepFlag['step5']!=''){?>
                    <div>
                        <div class="step-section-title">
                            <span>STEP <?=$stepCount++?>:</span>
                            <strong>Application deadline</strong>
                            <i class="plus-preTab"></i>
                        </div>
                        <div style="margin:0;" class="step-content hidden">
                            <?php foreach($applicationProcessData['submissionDateData'] as $key=>$tuple){?>
                                <p><?= htmlentities($tuple['applicationSubmissionName'])?> : <?= date('jS M Y',strtotime($tuple['applicationSubmissionLastDate']));?></p>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>           
            <div class="clearfix"></div>
        
    </div>
</section>

<section class="detail-widget navSection" data-enhance="false" id="applicationProcessSection12">
    <div class="detail-widegt-sec">
        <?php $showAdmissionHelpFlag = true; ?>
        <?php
        $brochureDataObj['pageTitle']       = $courseObj->getName();
        $brochureDataObj['userRmcCourses']  = $userRmcCourses;
        $brochureDataObj['widget']          = 'courseApplicationProcess';
        $brochureDataObj['trackingPageKeyId'] = 463;
        if($courseObj->showRmcButton()){
        ?>
            <div class="admission-help-sec" style="padding:20px 10px;">
                <p class="need-help-title">Need help in admission?</p>
                <p>An expert Shiksha counselor can rate your chances of Admission on this course</p>
                 <div class="rate-chances-sec">
                    <?= $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj); ?>
                </div>
            </div>
            <? }
            //_p($applicationProcessRightWidgetData);
            //die;
            if(!empty($applicationProcessRightWidgetData)){?>
            <div class="more-app-process-sec">
                <strong>Learn more about application process</strong>
                    <ul class="apply-content-widget">
                        <?php foreach ($applicationProcessRightWidgetData as $key => $value) {
                            if($value['type'] == 'CV')
                            {
                                $searchStr = 'stu-cv-icon';
                                $replaceStr = 'cv-icn';
                            }
                            else
                            {
                                $searchStr = '-icon';
                                $replaceStr = '-icn';
                            }
                            $value['icon'] = str_replace($searchStr,$replaceStr,$value['icon']);
                        ?>
                         <li>
                            <i class="mobile-sop-sprite <?php echo $value['icon'];?> flLt"></i>
                            <div class="app-process-block">
                                <strong><a href="<?php echo $value['contentURL']; ?>"><?php echo $value['heading']; ?></a></strong>
                                <p><?php echo $value['name']; ?></p>
                            </div>
                        </li>
                        <?php
                         } ?>
                    </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</section>