<?php 
$countSch = 0;
$countFin = 0;
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
<div class="col-md-8 no-padLft left-widget">
	<div class="data-card" style="margin-bottom:20px;">
		<h2 class="cmn-h2 mb20"><?php echo empty($instituteAbbrev) ? $instituteNameWithLocation:$instituteAbbrev. ' ( '.$instituteNameWithLocation.' ) ' ;?> Scholarships and Funding</h2>
		<div class="admsn-dsc blog-cont">
            <?php 
            if(count($scholarships) == 1){
                ?>
                <p><?php echo nl2br(makeURLAsHyperlink(htmlentities($scholarships[0]->getScholarshipDescription()))); ?></p>
                <?php
            }
            else{
                foreach ($scholarships as $scholarship) {
                    ?>
                    <div class="schlrshp-cont">
                        <p><strong><?php echo htmlentities($scholarship->getCustomizedScholarshipName()); ?> <?php if($multipleScholarships && $scholarship->getScholarshipName() == "Scholarship" || $scholarship->getScholarshipName() == "Discount" || $scholarship->getScholarshipName() == "Others"){echo $counterSch; $counterSch++;} else if($multipleFinances && $scholarship->getScholarshipName() == "Financial Assistance"){echo $counterFin; $counterFin++;} ?></strong></p>
                        <p><?php echo nl2br(makeURLAsHyperlink(htmlentities($scholarship->getScholarshipDescription()))); ?></p>
                    </div>
                    <?php
                }
            }
            ?>
		</div>
	</div>
    <?php $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2','bannerType'=>"content"));
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2_2','bannerType'=>"content"));
    ?>
</div>