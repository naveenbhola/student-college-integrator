<?php extract($fees);?>
<div class="speech">
   <div class="speech-cont">
       <h3>Fee structure</h3>
       <ul>
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
    <p class="align-lft fee-c">Fee components </p>
    <p class="align-lft fee-c"><?=$totalFeesIncludes;?></p>
    <?php } ?>
</div>
</div>