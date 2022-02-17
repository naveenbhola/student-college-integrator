<?php extract($fees);
?>

<a class="block f14 color-b ga-analytic" on="tap:<?=$category;?>-fee-data" role="button" tabindex="0" data-vars-event-name="FEE_VIEWFEE_STRUCTURE">View fee structure</a>
   <amp-lightbox id="<?=$category;?>-fee-data" layout="nodisplay" scrollable>
       <div class="lightbox">
          <a class="cls-lightbox  color-f font-w6 t-cntr" on="tap:<?=$category;?>-fee-data.close" role="button" tabindex="0">&times;</a>
             <div class="m-layer">
               <div class="min-div color-w pad10">
                  <h3 class="color-3 font-w6 m-btm f14">Fee Structure</h3>
                  <ul class="speech-cont m-btm">
                    <?php foreach ($otpAndHostelFees[$category]['durationWise'] as $duration => $oneDurationFees) { ?>
                         <li>
                             <span class="year f14 color-3"><?php echo $oneDurationFees['duration']." ".intval($duration+1); ?></span>
                             <span class="t-fee f14 color-3"><?php echo $oneDurationFees['value']; ?></span>
                          </li>
                    <?php } ?>
                     <li class="total-sum">
                      <span class="year">Total fees</span>
                       <span class="full-fee font-w7 f14 color-6"><?=$totalFeesForCat?></span>
                     </li>
                  </ul>
                  <?php if($feeIncludes != ''){ ?>
                      <p class="color-6 f14">Fee components</p>
                      <p class="color-6 f14 word-break"><?=$feeIncludes;?></p>
                  <?php } ?>
               </div>
             </div>
       </div>
    </amp-lightbox>