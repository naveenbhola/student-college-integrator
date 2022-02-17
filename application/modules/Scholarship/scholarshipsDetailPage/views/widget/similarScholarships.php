<?php
if(!is_null($similarScholarships['scholarships']) && count($similarScholarships['scholarships'])>0) 
{
    $page = $beaconTrackData['pageIdentifier'];
    $scholarSliderTitle = "Similar Scholarships";
    $scholarshipsToDisplay = 5;
    $totalScholarships = count($similarScholarships['scholarships']);
    $sliderCount = ($totalScholarships % $scholarshipsToDisplay == 0) ? ($totalScholarships / $scholarshipsToDisplay) : (($totalScholarships / $scholarshipsToDisplay) + 1);
    $sliderCount = floor($sliderCount);
    $showSliderArrows = true;
    $index = 0;
    ?>
    <div class="fluid_div" id="similarSch">
        <h2 class="titl-main"><?php echo $scholarSliderTitle; ?></h2>
        <div class="max_container">
            <div id="popular_ul" class="c-cont">
                <div class="slider-box">
                    <?php if ($showSliderArrows) { ?>
                        <a class="buttons prev ui-arrow lft-arrw popularScholarshipLeft"><i class="prv-disbl"></i></a>
                    <?php } ?>
                    <div class="viewport">
                        <ul class="popular_ul popularScholarship-list">
                        <?php 
                            foreach ($similarScholarships['scholarships'] as $key => $value) {
                                $schlrName = $similarScholarships['name'][$value['saScholarshipId']];
                                if(strlen($schlrName) > 50){
                                    $schlrName = substr($schlrName, 0, 50).'...';
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
                                if ($index % $scholarshipsToDisplay == 0) { 
                            ?><li class="clearfix">
                        <?php } ?>
                                <div class="block new_div">
                                  <div class="fixAt">
                                    <div class="schlr_title">
                                        <a href="<?php echo $similarScholarships['urls'][$value['saScholarshipId']]; ?>" class="fnt_14_bold clr_blue gaTrack"> <?php echo $schlrName; ?> </a>
                                    </div>
                                    </div>   
                                    <div class="schrl_dtls">
                                        <div class="height_fix mb_10">
                                            <p class="fnt_12 clr_6">Scholarship amount <strong class="block apcl">
                                                <?php if(!empty($similarScholarships['amount'][$value['saScholarshipId']]) && $similarScholarships['amount'][$value['saScholarshipId']] > 0){ 
                                                    echo $amountStr; 
                                                }else{
                                                    echo "Not available";
                                                } ?>
                                            </strong></p>
                                            <?php if (!empty($similarScholarships['awards'][$value['saScholarshipId']]) && $similarScholarships['awards'][$value['saScholarshipId']] > 0) { ?>
                                                <p class="fnt_12 clr_9"><?php echo $awardCountStr; ?></p>
                                            <?php } ?>
                                        </div>
                                        <p class="fnt_12 clr_6 mb_10 height_fix">Applicability<strong class="block amountX"><?php echo $applicabilityStr; ?></strong> </p>
                                        <?php if($value['categoryStr']!=''){ ?>
                                            <p class="fnt_12 clr_6 m-10">Course stream applicable <strong class="block amountX"><?php echo $value['categoryStr']; ?></strong></p>
                                        <?php } ?>
                                    </div>
                                    <a href="<?php echo $similarScholarships['urls'][$value['saScholarshipId']]; ?>"
                                       class="btns_new prime_btn fnt_12_bold gaTrack"
                                       gaParams="<?php echo $page; ?>,similarScholarshipWidget,viewAndApply">View & Apply <span class="rightLink"></span></a>
                                </div>
                                <?php
                                $index++;
                                if (($index % $scholarshipsToDisplay == 0) && $index == $totalScholarships) { ?>
                                    </li><li>
                                <?php }
                                if (($index % $scholarshipsToDisplay == 0) || ($index == $totalScholarships)) { ?>
                                    </li><?php }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php if ($showSliderArrows) { ?>
                        <a class="buttons next ui-arrow rgt-arrw popularScholarshipRight"><i
                        class="<?php echo ($sliderCount == 1) ? 'nxt-disbl' : 'nxt' ?>"></i></a>
                        <?php } ?>
                </div>
                <?php if ($showSliderArrows) { ?>
                    <div class="slider-indicator popularScholarshipSliderInd">
                        <ul class="">
                            <?php for ($i = 0; $i < $sliderCount; $i++) { ?>
                                <li><a class="bullet dot-pt <?php echo($i == 0 ? "active" : ""); ?>"></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
}

