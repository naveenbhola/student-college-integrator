<?php 
// _p($courseObj); die;

extract($fees);
$showFeesStructureLink = '';
if(isset( $_COOKIE['applied_'.$courseObj->getId()]) && $_COOKIE['applied_'.$courseObj->getId()] == 1){
    $applied = true;
}
 if(empty($otpAndHostelFees['durationWise'])){
    $showFeesStructureLink = 'display:none;';
}?>

<div class="new-row">
    <div class="courses-offered gap listingTuple" id="fees">
        <div class="group-card no__pad gap">
            <div class="">
                <h2 class="head-1 gap" style="margin:0;">Fees</h2>
            </div>
            <?php if(!(count($feesFilterOption) == 1 && $feesFilterOption[0] == 'general') && !empty($feesFilterOption)) { ?> 
                <div class="course-search gen-cat">
                    <p>Viewing info for </p>

                    <div class="dropdown-primary" id="categoryFeesOptions">
                        <span class="option-slctd"><?php if($feesSelectedCategory  != '') { echo ucfirst($categoriesNameMapping[$feesSelectedCategory]), " Category"; } else { ?>General Category<?php } ?></span>
                        <span class="icon"></span>
                        <ul class="dropdown-nav" style="display: none;">
                            <?php                    
                            foreach ($feesFilterOption as $oneFeeType) { ?>
                                <li><a value="<?php echo $oneFeeType; ?>" ga-track="<?php echo 'FEES_CATEGORY_'.strtoupper($oneFeeType).'_COURSEDETAIL_DESKTOP'; ?>"><?php echo ucfirst($categoriesNameMapping[$oneFeeType]); ?> Category</a></li>
                            <?php }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>
            <div class="fee-data pad__16">
                <div class="fee-str">
                    <div class="main-fee">
                        <h3 class="head-2">Total Fees</h3>
                        <div class="fee-total"><?=$totalFees;?>
                            <a href="javascript:void(0)" class="link popLayer feesStructureLink hide_fee_class <?php if($applied != 'true') echo "hid"; ?>" style="<?=$showFeesStructureLink?>" ga-track="FEES_STRUCTURE_COURSEDETAIL_DESKTOP">View Fee Structure</a>
                            <?php if(!$isLocationLevelFeesAvailable) {?>                        
                            <div class="speech hid" type="feeStructure">
                                <div class="speech-head">Fee Structure <a class="close-head" id="cl-s"></a></div>
                                <div class="speech-cont">
                                    <ul class="">
                                        <?php foreach ($otpAndHostelFees['durationWise'] as $duration => $oneDurationFees) { ?>
                                            <li>
                                                <span class="year"><?php echo $oneDurationFees['duration']." ".intval($duration+1); ?></span>
                                                <span class="t-fee"><?php echo $oneDurationFees['value']; ?></span>
                                            </li>
                                        <?php } ?>

                                        <li class="total-sum">
                                            <span class="year">Total fees</span>
                                            <span class="full-fee"><?=$totalFees?></span>
                                        </li>
                                    </ul>
                                    <?php if($totalFeesIncludes != ''){ ?>
                                    <p class="align-right fee-c">Fee components </p>
                                    <p class="align-right cost-dtls"><?=$totalFeesIncludes;?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } if($applied != 'true'){?>
                            <a href="javascript:void(0);" tracking-id="2029" class="button button--orange courseBrochure brochureClick " ga-track="GET_FEE_DETAIL_COURSEDETAIL_DESKTOP" cta-type="download_brochure" title='Get Fees Details of <?php echo addslashes(htmlentities($courseObj->getName()));?>' hideRecoLayer="true" fromFeeDetails="true" >Get Fee Details
                            </a>
                            <?php }?>
                        </div>

                        <?php if($totalFeesIncludes != '') { ?>
                            <span class="para-4 hide_fee_class <?php if($applied != 'true') echo "hid"; ?>">(Fees Components : <?=$totalFeesIncludes?>)</span>
                        <?php } ?>
                        
                        

                    </div>
                    <?php if(trim($otpAndHostelFees['otp']) != '') { ?>
                    <div class="main-fee otp hide_fee_class <?php if($applied != 'true') echo "hid"; ?>">
                        <h3 class="head-2">One Time Payment</h3>
                        <div class="head-2">
                            <strong><?php echo $otpAndHostelFees['otp']; ?></strong>
                            <div class="tp-block">
                                <i class="info-icn" infodata = "Note - Applicable if you want to pay the complete fees at one go" infopos="right"></i>
                            </div>
                            
                            </div>
                    </div>
                    <?php } ?>
                    <?php 
                        if($otpAndHostelFees['hostel'] != ''){
                            ?>
                            <div class="main-fee hostel hide_fee_class <?php if($applied != 'true') echo "hid"; ?>">
                                <h3 class="head-2">Hostel Fees (Yearly)</h3>
                                <p class="head-2">
                                    <strong><?php echo $otpAndHostelFees['hostel']; ?></strong>
                                </p>
                            </div>
                            <?php
                        }
                    ?>
                </div>
                <?php 
                    if(!empty($feesDescription)){
                        ?><p class="para-2 gap hide_fee_class <?php if($applied != 'true') echo "hid"; ?>"><?php echo $feesDescription;?></p><?php
                    }
                ?>
                <?php if($feesDisclaimer === true) { ?><p class="para-6 hide_fee_class <?php if($applied != 'true') echo "hid"; ?>">Disclaimer : Total fees has been calculated bases year/semester 1 fees provided by the college. The actual fees may vary. </p><?php } ?>
            </div>

            <?php if(!empty($scholarship)) { ?>
                <div class="findOut-sec">
                <div class='fntot-dv'>
                    <h2 class="para-3">Want to know more about <?php echo $instituteName; ?> Scholarship details ? <a target="_blank" id="readScholarship" ga-track="FEES_SCHOLARSHIP_COURSEDETAIL_DESKTOP" class="button button--secondary mL10" href="<?=$instituteObj->getAllContentPageUrl('scholarships');?>">Read
                            about scholarships</a></h2>
                </div>            
                </div>
            <?php }?>
        </div>
    </div>

</div>

<?php if($isLocationLevelFeesAvailable){?>
<script>

    var categoryFeesMapping = eval('(' + '<?=$categoryFeesMapping;?>' + ')');
</script>
<?php } ?>