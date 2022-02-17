<?php
if($listing_type == 'university')
{
  $GA_Tap_On_View_All = 'VIEW_ALL_SCHOLARSHIP';
}
else
{
  $GA_Tap_On_View_All = 'VIEW_ALL_SCHOLARSHIP';
}
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

<div class="crs-widget listingTuple" id="scholarship">
        <h2 class="head-L2">Scholarships</h2>
        <div class="lcard">
            <ul class="schlrshp-list">
        		<?php for ($i=0; $i<min(1,count($scholarships)); $i++){ ?>
                    <?php if(count($scholarships) > 1){ ?>
                       <p class="color-3 f14 font-w6"><?=htmlentities($scholarships[$i]->getScholarshipName())?> <?php if($multipleScholarships && $scholarships[$i]->getScholarshipName() == "Scholarship"){echo $counterSch; $counterSch++;} else if($multipleDiscounts && $scholarships[$i]->getScholarshipName() == "Discount"){echo $counterDis; $counterDis++;} else if($multipleFinances && $scholarships[$i]->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></p>
                    <?php } ?>
                    <li>
                        <p class="para-L3"><?=$scholarships[$i]->getScholarshipDescription();?></p>
                    </li>
        		<?php } ?>
            </ul>           
            <div class="gradient-col" id="scholarshipsViewAll" style="display: block">
                    <a href="<?php echo $instituteObj->getAllContentPageUrl('scholarships');?>" class="btn-tertiary  mL10" ga-attr="<?=$GA_Tap_On_View_All;?>">View Scholarships</a>
            </div>
        </div>
</div>
<?php
} 
?>
