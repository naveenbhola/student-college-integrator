<?php
    if($courseFeeData["fromCurrency"] == $courseFeeData["toFormattedCurrency"]){
        $hideToAmountSection = "visibility : hidden;";
    }
    if(!empty($currencyMapping[$courseFeeData['fromCurrency']])) {
        $fromCurrencyUnit = $currencyMapping[$courseFeeData['fromCurrency']];
        $fromCurrencyUnitName = $courseFeeData["fromCurrenyObj"]->getName()." (".$currencyMapping[$courseFeeData['fromCurrency']].")";
    }else{
        $fromCurrencyUnit = $courseFeeData["fromCurrenyObj"]->getCode();
        $fromCurrencyUnitName = $courseFeeData["fromCurrenyObj"]->getName()." (".$courseFeeData["fromCurrenyObj"]->getCode().")";
    }
?>
<div class="course-detail-tab fees-tab clearfix">
    <div class="course-detail-mid flLt">
        <div id ="abroadCourseFee" class="cons-scrollbar1 scrollbar1 clearwidth">
            <div class="cons-scrollbar scrollbar" style="visibility:hidden;">
                <div class="track">
                    <div class="thumb"></div>
                </div>
            </div>
            <div class="viewport" style="height:338px">
                <div class="overview">
                    <div class="dyanamic-content"  style="width: 98%; top: -17px;">
                        <h2 style="margin:0px 0 3px;">1st year tuition fees</h2>
                        <table cellspacing="0" cellpadding="0" border="0" style="margin:0 !important;" class="fee-table">
                            <tbody>
                                <tr>
                                    <th width="33%">Fees components</th>
                                    <?php   if(!isset($hideToAmountSection)){?>
                                                <th width="33%">Amount in <br><?=$fromCurrencyUnitName?></th>
                                    <?php   }?>
                                    <th width="33%">Amount in<br> Indian Rupees (Rs.)</th>
                                </tr>
                                <tr class="last">
                                    <td>Tuition &amp; fees</td>
                                    <?php   if(!isset($hideToAmountSection)){?>
                                                <td><?php if(!$totalFormattedFees){echo $fromCurrencyUnit.' ';}?><?=$courseFeeData['fromFormattedFees']?></td>
                                    <?php   }?><td><?php if(!$totalFormattedFees){ echo "Rs. ";}?><?=$courseFeeData['toFormattedFees']?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php   if(count($customFees) > 0){?>
                                    <h2 style="height: 18px; margin:12px 0 3px;">Other expenses in 1st year</h2>
                                    <div style="top: -17px;">
                                        <table cellspacing="0" cellpadding="0" border="0" style="margin:0 !important;" class="fee-table">
                                            <tbody>
                                                <tr>
                                                    <th width="33%">Fees components</th>
                                                    <?php   if(!isset($hideToAmountSection)){?>
                                                                <th width="33%">Amount in<br><?=$fromCurrencyUnitName?></th>
                                                    <?php   }?>
                                                    <th width="33%">Amount in<br> Indian Rupees (Rs.)</th>
                                                </tr>
                                                <?php   foreach($customFees as $cellData){
                                                ?>
                                                            <tr>
                                                                <td><?=htmlentities($cellData['caption'])?></td>
                                                                <?php   if(!isset($hideToAmountSection)){?>
                                                                            <td><?=$cellData['fromFormattedValue']?></td>
                                                                <?php   }?>
                                                                <td><?=$cellData['toFormattedValue']?></td>
                                                            </tr>
                                                <?php   }
                                                        if($totalFormattedFees){
                                                ?>
                                                            <tr class="last total-row">
                                                                <td>Total</td>
                                                                <?php   if(!isset($hideToAmountSection)){?>
                                                                            <td><?php if($fromCurrencyUnit){echo $fromCurrencyUnit;} echo ' '.$totalFormattedFees; ?></td>
                                                                <?php   }?>
                                                                <td>Rs. <?=$totalConvertedFees?></td>
                                                            </tr>
                                                <?php   }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                        <?php   }?>
                    </div>
                    <div class="font-11 clearwidth">
                        <?php   if(!isset($hideToAmountSection)){?>
                                    Calculated at the exchange rate of 1 <?=$fromCurrencyUnit?> = Rs <?=$exchangeRate;?><br>
                        <?php   }?>
                        Tuition &amp; expenses were last updated on <?=date_format(date_create($courseObj->getLastUpdatedDate()),'jS F Y');?><br>
                    </div>
                    <div class="clearwidth report-info"><a href="javascript:void(0);" onclick="reportIncorrect('reportIncorrectContainer','Feetab','<?= $listingTypeId?>');">Report incorrect information</a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="course-detail-rt flRt clearfix">
        <div class="course-rt-tab">
        <ul class="course-dur">
            <li class="course-fees-bdr <?=(!isset($customFeesIndianDisplayableFormat))?'course-fees-bdr-non':''?>">
                <p>
                    <span class="flLt"><strong>Tuition </strong></span>
                    <span class="flRt"><?=$toFormattedFeesIndianDisplayableFormat?></span>
                </p>
                <?php   if(isset($customFeesIndianDisplayableFormat)){
                ?>
                            <p>
                                <span class="flLt"><strong>Other </strong></span>
                                <span class="flRt">+ <?=$customFeesIndianDisplayableFormat?></span>
                            </p>
                <?php   }?>
            </li>
            <?php   if(isset($customFeesIndianDisplayableFormat)){?>
                        <li style="border:none !important;" class="course-fees-bdr course-dur-bdr">
                            <p>
                                <span class="flLt"><strong>1st year total fees</strong></span>
                                <span class="flRt">= <?=$totalFirstYearAndCustomFeesIndianDisplayableFormat?></span>
                            </p>
                        </li>
            <?php   }?>
        </ul>
        </div>
        <?php 
        $linkingWidgetData = array('gaParams'=>'COURSEPAGE_FEE_TAB,applyPageLinkingWidget',
                                    'applyLinkWidgetTitle' => 'Looking for colleges that match your budget?',
                                    'applyLinkWidgetDesc'  => 'Get expert help from Shiksha counselors'
                                    );
        $this->load->view('listing/abroad/widget/applyHomeLinkingWidget',$linkingWidgetData);
        ?>
    </div>
</div>
