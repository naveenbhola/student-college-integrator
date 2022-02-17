<?php
if($predictorData['eligibilityCount']>0 && $predictorData['inEligibilityCount']>0):?>
      <ul class="prdct-tabs" id="">
       <?php if($predictorData['eligibilityCount']>0) :?>
           <li class="current eligibile-act"><a href="#tab_0">Eligible</a></li>
           <?php endif;?>
           <?php if($predictorData['inEligibilityCount']>0) :?>
           <li class="ineligibile-act"><a href="#tab_1">Ineligible</a></li>
           <?php endif;?>
      </ul>
<?php endif;?>
