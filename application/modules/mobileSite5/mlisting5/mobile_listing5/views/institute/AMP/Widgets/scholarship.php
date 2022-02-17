<?php
  $GA_Tap_On_View_All = 'VIEW_ALL_SCHOLARSHIP';
if(is_array($scholarships) && count($scholarships)>0){
    $countSch = 0;
    $countFin = 0;
    $countDis = 0;
    foreach ($scholarships as $scholarship){
           if($scholarship->getScholarshipName() == "Scholarship"){
                   $countSch++;
           }
           if($scholarship->getScholarshipName() == "Discount"){
                   $countDis++;
           }
           if($scholarship->getScholarshipName() == "Financial Assistance"){
                   $countFin++;
           }

    }
    if($countSch >= 2){
           $multipleScholarships = true;
           $counterSch = 1;
    }
    if($countDis >= 2){
           $multipleDiscounts = true;
           $counterDis = 1;
    }
    if($countFin >= 2){
           $multipleFinances = true;
           $counterFin = 1;
    }
?>

<section>
<div class="data-card m-5btm pos-rl">
   <h2 class="color-3 f16 heading-gap font-w6">Scholarships</h2>
   <div class="card-cmn color-w schlrs-ampDv">
      <input type="checkbox" class="read-more-state-out hide" id="post-11">	
      <ul class="sc-ul n-ul read-more-wrap">
        <?php for ($i=0; $i<min(1,count($scholarships)); $i++){
            if(count($scholarships) > 1){ ?>
           <p class="color-3 f14 font-w6"><?=htmlentities($scholarships[$i]->getScholarshipName())?> <?php if($multipleScholarships && $scholarships[$i]->getScholarshipName() == "Scholarship"){echo $counterSch; $counterSch++;} else if($multipleDiscounts && $scholarships[$i]->getScholarshipName() == "Discount"){echo $counterDis; $counterDis++;} else if($multipleFinances && $scholarships[$i]->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></p>
        <?php } ?>
        <li >
          <input type="checkbox" class="read-more-state hide" id="scholarship<?=$i?>">
          <p class="read-more-wrap">
              <?=$scholarships[$i]->getScholarshipDescription()?>
          </p>
        </li>
        <?php } ?>
      </ul>
      
   </div>
   <div class="gradient-col hide-class" id="">
          <a href="<?php echo $instituteObj->getAllContentPageUrl('scholarships');?>" class="color-b btn-tertiary f14 ga-analytic" data-vars-event-name="<?=$GA_Tap_On_View_All;?>">View Scholarships</a>
      </div>
</div>
</section>
<?php
} 
?>
