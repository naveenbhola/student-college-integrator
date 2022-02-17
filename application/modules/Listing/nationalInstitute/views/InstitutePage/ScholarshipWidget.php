<?php
if(is_array($scholarships) && count($scholarships)>0){

      $GA_Tap_On_View_More = 'VIEW_MORE_SCHOLARSHIP_DESC';

	$countSch = 0;
	$countFin = 0;
	$countDis = 0;
	foreach ($scholarships as $scholarship){
		if($scholarship->getScholarshipName() == "Scholarship" || $scholarship->getScholarshipName() == "Discount" || $scholarship->getScholarshipName() == "Others"){
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
<!--scholar ships -->   
<?php if(!$hideHeading) { ?>
     <div class="new-row">
       <div class="group-card no__pad gap listingTuple" id="scholar">
  
<?php } ?>
     
          <h2 class="head-1 gap"> Scholarships</h2>
         <div class="new-row events scholarship">
	     <?php if(count($scholarships)==1){ ?>
             <div class="col-md-6" style="width: 100%">
               <div class="gap">
               <p class="para-2"><?=cutStringWithoutShowMore($scholarships[0]->getScholarshipDescription())?></p>
               </div>
             </div>
	     <?php }else{ ?>
            <ul class="flex-ul">
               <?php for ($i=0; $i<count($scholarships); $i++){ 
                    if($i >= 2) break;
                ?>
               <li class="">
               <div class="gap">
               <h3 class="head-2"><?=htmlentities($scholarships[$i]->getCustomizedScholarshipName())?> <?php if($multipleScholarships && $scholarships[$i]->getScholarshipName() == "Scholarship" || $scholarships[$i]->getScholarshipName() == "Discount" || $scholarships[$i]->getScholarshipName() == "Others"){echo $counterSch; $counterSch++;} else if($multipleFinances && $scholarships[$i]->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></h3>
               <p class="para-2"><?=cutStringWithoutShowMore($scholarships[$i]->getScholarshipDescription(),350,'scholarship'.$i,'view more','scholarship')?></p>
               </div>
               </li>
               <?php } ?>
             </ul>

	     <?php } ?>
       <div class="gradient-col ankit" id="viewMoreLink" style="display: block"><a href="<?php echo $instituteObj->getAllContentPageUrl('scholarships');?>" class="button button--secondary mL10 arw_link" ga-attr="VIEW_MORE_SCHOLARSHIP" target="_blank">View Scholarships</a></div>
        </div>
<?php if(!$hideHeading) { ?>
       </div>
     </div>    
<?php } ?>
            <input type="hidden" name="gaActionName" id="gaActionName_scholarship" value="<?php echo $GA_Tap_On_View_More;?>">
<?php 
} ?>
