<?php
if($listing_type == 'university')
{
  $GA_currentPage = 'UNIVERSITY DETAIL PAGE';
  $GA_Tap_On_View_More = 'VIEW_MORE_SCHOLARSHIP_DESC_UNIVERSITYDETAIL_MOBLDP';
  $GA_Tap_On_View_All = 'VIEW_ALL_SCHOLARSHIP_UNIVERSITYDETAIL_MOBLDP';
}
else
{
  $GA_currentPage = 'INSTITUTE DETAIL PAGE';
  $GA_Tap_On_View_More = 'VIEW_MORE_SCHOLARSHIP_DESC_INSTIUTEDETAIL_MOBLDP';
  $GA_Tap_On_View_All = 'VIEW_ALL_SCHOLARSHIP_INSTITUTEDETAIL_MOBLDP';
}
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

?>

<div class="crs-widget listingTuple" id="scholarship">
    <?php if(count($scholarships)==1){ ?>
        <h2 class="head-L2">Scholarships</h2>
    <?php } ?>
        <div class="lcard">
            <ul class="schlrshp-list schlrshp-lyr">
		<?php for ($i=0; $i<count($scholarships); $i++){ ?>
                <li>

		    <?php if(count($scholarships) > 1){ ?>
                    <p class="head-L3"><?=htmlentities($scholarships[$i]->getCustomizedScholarshipName())?> <?php if($multipleScholarships && $scholarships[$i]->getScholarshipName() == "Scholarship" || $scholarships[$i]->getScholarshipName() == "Discount" || $scholarships[$i]->getScholarshipName() == "Others"){echo $counterSch; $counterSch++;} else if($multipleFinances && $scholarships[$i]->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></p>
		    <?php } ?>

                    <p class="para-L3"><?=nl2br(makeURLasHyperLink( htmlentities($scholarships[$i]->getScholarshipDescription()) ));?></p>
                </li>
		<?php } ?>
            </ul>
            <input type="hidden" name="gaPageName" id="gaPageName_scholarship" value="<?php echo $GA_currentPage;?>">
            <input type="hidden" name="gaActionName" id="gaActionName_scholarship" value="<?php echo $GA_Tap_On_View_More;?>">
            <input type="hidden" name="gaUserLevel" id="gaUserLevel_scholarship" value="<?php echo $GA_userLevel;?>">

        </div>
</div>
<?php
} 
?>