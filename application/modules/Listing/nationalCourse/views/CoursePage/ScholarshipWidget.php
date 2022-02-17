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
         <div class="events scholarship">
	     <?php if(count($scholarships)==1){ ?>
             <div class="col-md-6" style="width: 100%">
               <div class="gap">
               <p class="para-2"><?=nl2br(makeURLasHyperLink( htmlentities($scholarships[0]->getScholarshipDescription()) ) )?></p>
               </div>
             </div>
	     <?php }else{ ?>
            <ul class="flex-ul">
               <?php for ($i=0; $i<count($scholarships); $i++){ ?>
               <li class="">
               <div class="gap">
               <h3 class="head-2"><?=htmlentities($scholarships[$i]->getCustomizedScholarshipName())?> <?php if($multipleScholarships && $scholarships[$i]->getScholarshipName() == "Scholarship" || $scholarships[$i]->getScholarshipName() == "Discount" || $scholarships[$i]->getScholarshipName() == "Others"){echo $counterSch; $counterSch++;} else if($multipleFinances && $scholarships[$i]->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></h3>
               <p class="para-2"><?=nl2br(makeURLasHyperLink( htmlentities($scholarships[$i]->getScholarshipDescription()) ))?></p>
               </div>
               </li>
               <?php } ?>
             </ul>

	     <?php } ?>
        </div>
            <input type="hidden" name="gaPageName" id="gaPageName_scholarship" value="<?php echo $GA_currentPage;?>">
            <input type="hidden" name="gaActionName" id="gaActionName_scholarship" value="<?php echo $GA_Tap_On_View_More;?>">
            <input type="hidden" name="gaUserLevel" id="gaUserLevel_scholarship" value="<?php echo $GA_userLevel;?>">
<?php 
} ?>
