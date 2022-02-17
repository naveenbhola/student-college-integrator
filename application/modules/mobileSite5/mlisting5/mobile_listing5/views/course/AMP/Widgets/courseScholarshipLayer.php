<?php
if(is_array($scholarships) && count($scholarships)>0){

        $countSch = 0;
        $countFin = 0;
        // $countDis = 0;
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
        if($countFin >= 2){
                $multipleFinances = true;
                $counterFin = 1;
        }

 for ($i=0; $i<count($scholarships); $i++){ ?>
                <li>

		    <?php if(count($scholarships) > 1){ ?>
                    <strong class="block m-3btm color-3 f14 font-w6"><?=htmlentities($scholarships[$i]->getCustomizedScholarshipName())?> <?php if($multipleScholarships && $scholarships[$i]->getScholarshipName() == "Scholarship" || $scholarships[$i]->getScholarshipName() == "Discount" || $scholarships[$i]->getScholarshipName() == "Others"){echo $counterSch; $counterSch++;} else if($multipleFinances && $scholarships[$i]->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></strong>
		    <?php }else{?>
                    <strong class="block m-3btm color-3 f14 font-w6">Scholarships</strong>
            <?php } ?>

                    <p class="color-3 l-18 f12"><?=nl2br(makeURLasHyperLink( htmlentities($scholarships[$i]->getScholarshipDescription()),true));?></p>
                </li>
		<?php }} ?>
    
