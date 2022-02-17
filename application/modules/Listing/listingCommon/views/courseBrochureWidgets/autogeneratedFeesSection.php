<div class="cmn-card mb2 fees-sec">
      <h2 class="f20 clor3 mb2 f-weight1">Fees</h2>
  <?php foreach ($fees['feesData'] as $category => $categoryWiseFees) { ?>
    <h3 class="prt-title mb2 f16"><?=$categoriesNameMapping[$category];?> category</h3>
       <div class="fee-data">
         <h3 class="head-2">Total Fees </h3>
        <div class="fee-str">
            <div class="main-fee">
                <p class="fee-total clor6"><?=$categoryWiseFees['totalFees']?></p>
                <?php if($categoryWiseFees['totalFeesIncludes'] != ''){ ?>
                        <span class="f12 clor6">(Fees Components : <?=$categoryWiseFees['totalFeesIncludes'];?>)</span>
                <?php } ?>
            </div>
            <?php if(trim($categoryWiseFees['otpAndHostelFees']['otp']) != '') { ?>
                    <div class="main-fee">
                        <p class="f16 clor9 f-semi">One Time Payment <strong class="f-bold"><?=$categoryWiseFees['otpAndHostelFees']['otp']; ?></strong><i class="info-icn"></i></p>
                        <span class="f12 clor6">Note - Applicable if you want to pay the complete fees at one go</span>
                    </div>
            <?php } ?>
            <?php if($categoryWiseFees['otpAndHostelFees']['hostel'] != '') { ?>
                    <div class="main-fee">
                        <p class="f16 clor9 f-semi">Hostel Fees <strong class="f-bold"><?=$categoryWiseFees['otpAndHostelFees']['hostel']?></strong></p>
                    </div>
            <?php } ?>
        </div>
        <div class="view-fees">
          <?php if(!empty($categoryWiseFees['otpAndHostelFees']['durationWise'])) { ?>
             <p class="f16 clor3 f-semi">Fee structure</p>
             <ul class="view-fee">
              <?php foreach ($categoryWiseFees['otpAndHostelFees']['durationWise'] as $duration => $oneDurationFees) { ?>
                  <li><p class="f16 clor3"><?php echo $oneDurationFees['duration']." ".intval($duration+1); ?> <span><?=$oneDurationFees['value']; ?></span></p></li>
              <?php } ?>
             </ul>
          <?php } ?>
             <?php if(!empty($fees['description']) && !$feesDescriptionShown) { 
                      $feesDescriptionShown = true; ?>
                      <p class="fee-disc f13 clor3"><?=$fees['description'];?></p>
             <?php } ?>
             <?php if($categoryWiseFees['feesDisclaimer']) { ?>
  <p class="f12 clor9">Disclaimer : Total fees has been calculated bases year/semester 1 fees provided by the college. The actual fees may vary.</p>
             <?php } ?>
        </div>
    </div>
  <?php } ?>
</div>