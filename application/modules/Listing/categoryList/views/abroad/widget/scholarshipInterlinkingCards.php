<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 10/4/18
 * Time: 2:52 PM
 */
if($beaconTrackData['pageIdentifier']=='categoryPage')
{
    $page = 'ABROAD_CAT_PAGE';
}else{
    $page = $beaconTrackData['pageIdentifier'];
}
if(isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount'] >0) {
    $scholarSliderTitle = str_replace("in All Countries", "Abroad", $scholarshipSliderTitle);
    $scholarshipsToDisplay = (is_null($scholarshipsToDisplay)?4:$scholarshipsToDisplay);
    if($scholarshipCardData['totalCount'] > 7)
    {
        $totalScholarships = ($scholarshipCardData['viewAllUrl']!=''?8:9);
    }else{
        $totalScholarships = $scholarshipCardData['totalCount']+1;
    }
    $sliderCount = ($totalScholarships % $scholarshipsToDisplay == 0) ? ($totalScholarships / $scholarshipsToDisplay) : (($totalScholarships / $scholarshipsToDisplay) + 1);
    $sliderCount = floor($sliderCount);
    $showSliderArrows = $totalScholarships > $scholarshipsToDisplay ? 1 : 0;
    $index = 0;
    ?>
    <div class="fluid_div">
        <h2 class="fnt_30 clr_3 txt_cntr">Popular Scholarships for <?php echo $scholarSliderTitle;?></h2>
        <div class="max_container">
            <div id="popular_ul" class="c-cont">
                <div class="slider-box">
                    <?php if ($showSliderArrows) { ?>
                        <a class="buttons prev ui-arrow lft-arrw popularScholarshipLeft"><i class="prv-disbl"></i></a>
                    <?php } ?>
                    <div class="viewport">
                        <ul class="popular_ul popularScholarship-list">
                            <?php 
                            foreach ($scholarshipCardData['scholarshipData'] as $key => $value) { 
                                if ($index % $scholarshipsToDisplay == 0) { 
                            ?><li class="clearfix">
                                <?php } ?>
                                <div class="block new_div">
                                   <div class="fixat">
                                    <div class="schlr_title">
                                        <a href="<?php echo $value['url']; ?>" gaparams="<?php echo $page; ?>,scholarshipPopularWidget,viewAndApply"
                                           class="fnt_14_bold clr_blue gaTrack"><?php echo (strlen($value['name']) > 50) ? (trim(substr($value['name'], 0, 50)). '...') : ($value['name']); ?></a>
                                    </div>
                                   </div>
                                    <div class="schrl_dtls">
                                        <div class="height_fix mb_10">
                                            <p class="fnt_12 clr_6">Scholarship amount
                                                <strong class="block">
                                                    <?php if(!is_null($value['amountStr1'])) {
                                                        echo trim($value['amountStr1'], '/-');
                                                    }else{
                                                        echo "Not available";
                                                    }
                                                    ?>
                                                </strong>
                                            </p>
                                            <?php if (is_numeric($value['awards']) && $value['awards'] > 0) { ?>
                                                <p class="fnt_12 clr_9">
                                                    (<?php echo moneyFormatIndia($value['awards']) . ' ' . ((is_numeric($value['awards'])) ? (($value['awards'] == 1) ? 'student award' : 'student awards') : ''); ?>
                                                    )</p>
                                            <?php } ?>
                                        </div>
                                        <?php if($value['category']!=''){ ?>
                                                <p class="fnt_12 clr_6 m-10">Course <strong class="block clr_3 amountX"><?php echo $value['category']; ?></strong></p>
                                            <?php } ?>
                                    </div>
                                    <a href="<?php echo $value['url']; ?>"
                                       class="btns_new prime_btn fnt_12_bold gaTrack"
                                       gaParams="<?php echo $page; ?>,scholarshipPopularWidget,viewAndApply">View & Apply <span class="rightLink"></span></a>
                                </div>
                                <?php
                                $index++;
                                if (($index % $scholarshipsToDisplay == 0)&&($index == $totalScholarships-1)) { ?>
                                    </li><li>
                                <?php }
                                if (($index == $totalScholarships-1) && $scholarshipCardData['viewAllUrl']!='') {
                                    ?>
                                    <div class="block new_div">
                                        <a class="ndiv trans_col gaTrack" gaParams="<?php echo $page; ?>,scholarshipPopularWidget,viewAll" href="<?php echo $scholarshipCardData['viewAllUrl'];?>">
                                            <div class="cntr_div">
                                                <div class="round_arrow "><p class="right_arrow"></p></div>
                                                <p class="show_in">
                                                    <p>View All <br/><?php echo $scholarshipCardData['totalCount'];?> Scholarships</p>
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                                if (($index % $scholarshipsToDisplay == 0) || ($index == $totalScholarships-1)) { ?>
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

