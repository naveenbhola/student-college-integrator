<?php 
if(count($similarScholarships['scholarships']) > 1){
?>
<section class="ps bg_white">
    <h2 class="title-main">Similar Scholarships</h2>
    <div class="sld_card">
        <ul class="popular_ul">
            <?php 
            foreach ($similarScholarships['scholarships'] as $key => $value) {
                $schlrName = $similarScholarships['name'][$value['saScholarshipId']];
                if(strlen($schlrName) > 40){
                    $schlrName = substr($schlrName, 0, 37).'...';
                }
                if(!empty($similarScholarships['amount'][$value['saScholarshipId']]) && $similarScholarships['amount'][$value['saScholarshipId']] > 0){
                    $amountStr = "Rs ".moneyFormatIndia($similarScholarships['amount'][$value['saScholarshipId']]);
                }else{
                    $amountStr = "Not available";
                }
                $awardCountStr = '';
                if(!empty($similarScholarships['awards'][$value['saScholarshipId']]) && $similarScholarships['awards'][$value['saScholarshipId']] > 0){
                    if($similarScholarships['awards'][$value['saScholarshipId']] == 1)
                        $awardCountStr = '('.$similarScholarships['awards'][$value['saScholarshipId']].' student award)';
                    else
                        $awardCountStr = '('.$similarScholarships['awards'][$value['saScholarshipId']].' student awards)';
                }
                if(empty($value['saScholarshipUnivName'])){
                    $applicabilityStr = 'All universities in '.$value['countryStr'];
                }else{
                    $applicabilityStr = $value['saScholarshipUnivName'].' in '.$value['countryStr'];
                }
            ?>
            <li>
                <div class="ndiv">
                    <div class="fixAt">
                    <p class="sch_ttl" lang="en">
                        <a href="<?php echo $similarScholarships['urls'][$value['saScholarshipId']]?>" class="fnt12_bold clr_blue ui-link"><?php echo $schlrName?></a>
                    </p>
                    </div>
                    <div class="schrl_dtls">
                        <div class="hf mb_10">
                            <p class="fnt_12 clr_6">Scholarship amount <strong class="block clr_1"><?php echo $amountStr?></strong> </p>
                            <p class="fnt_12 clr_9"><?php echo $awardCountStr?></p>
                        </div>
                        <p class="fnt_12 clr_6 mb_10">Applicability<strong class="block clr_1 apcl"><?php echo $applicabilityStr?></strong> </p>
                        <p class="fnt_12 clr_6">Course stream applicable<strong class="block clr_1"><?php echo $value['categoryStr']?></strong> </p>
                    </div>
                    <a href="<?php echo $similarScholarships['urls'][$value['saScholarshipId']]?>" class="btn_new p_btn fnt12_bold ui-link">View &amp; Apply</a>
                </div>
            </li>
            <?php 
            }
            ?>
        </ul>
    </div>
</section>
<?php 
}
?>