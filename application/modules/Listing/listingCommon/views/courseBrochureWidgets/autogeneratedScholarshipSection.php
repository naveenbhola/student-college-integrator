
<!--Here-->
<?php
if(is_array($scholarships) && count($scholarships)>0){

    if($listing_type == 'university')
    {
      $GA_currentPage = 'UNIVERSITY DETAIL PAGE';
      $GA_Tap_On_View_More = 'VIEW_MORE_SCHOLARSHIP_DESC_UNIVERSITYDETAIL_DESKLDP';
    }
    else
    {
      $GA_currentPage = 'INSTITUTE DETAIL PAGE';
      $GA_Tap_On_View_More = 'VIEW_MORE_SCHOLARSHIP_DESC_INSTIUTEDETAIL_DESKLDP';
    }

    $countSch = 0;
    $countFin = 0;
    $countDis = 0;
    foreach ($scholarships as $scholarship){
        if($scholarship->getScholarshipName() == "Scholarship" || $scholarship->getScholarshipName() == "Discount" || $scholarship->getScholarshipName() == "Others"){
            $countSch++;
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
<!--scholar ships -->   
<div class="cmn-card mb2">
        <h2 class="f20 clor3 mb2 f-weight1">Scholarships</h2>
         <?php if(count($scholarships)==1){ ?>
               <div class="ad-stage">
                    <p class="ad-txt"><?=nl2br( htmlentities($scholarships[0]->getScholarshipDescription()) )?></p>
               </div>
         <?php }else{ ?>
               <?php for ($i=0; $i<count($scholarships); $i++){ ?>
               <div class="ad-stage">
               <p class="stage-titl"><?=htmlentities($scholarships[$i]->getCustomizedScholarshipName())?> <?php if($multipleScholarships && $scholarships[$i]->getScholarshipName() == "Scholarship" || $scholarships[$i]->getScholarshipName() == "Discount" || $scholarships[$i]->getScholarshipName() == "Others"){echo $counterSch; $counterSch++;} else if($multipleFinances && $scholarships[$i]->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></p>
               <p class="ad-txt"><?=nl2br(htmlentities($scholarships[$i]->getScholarshipDescription()) )?></p>
               </div>
               <?php } ?>

         <?php } ?>
</div>
<?php 
} ?>
