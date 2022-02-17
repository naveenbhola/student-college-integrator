<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 29/3/18
 * Time: 12:49 PM
 */
if($beaconTrackData['pageIdentifier']=='categoryPage')
{
    $page = 'ABROAD_CAT_PAGE';
}else{
    $page = $beaconTrackData['pageIdentifier'];
}
$scholarSliderTitle = str_replace("in All Countries","Abroad",$scholarshipSliderTitle);
if(isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount'] >0)
{
    ?>
    <section class="ps bg_white">
        <h2 class="fnt16_bold clr_3">Popular Scholarships for <?php echo $scholarSliderTitle;?></h2>
        <div class="sld_card">
            <ul class="popular_ul">
                <?php foreach ($scholarshipCardData['scholarshipData'] as $key=>$value) {  ?>
                    <li>
                        <div class="ndiv">
                           <div class="fixat">
                            <p class="sch_ttl">
                                <a href="<?php echo $value['url'];?>" class="fnt12_bold clr_blue"><?php echo (strlen($value['name'])>50)?(trim(substr($value['name'], 0, 50)).'...'):($value['name']);?></a>
                            </p>
                           </div>
                            <div class="schrl_dtls">
                                <div class="hf mb_10">
                                    <p class="fnt_12 clr_6">Scholarship amount <strong class="block clr_3">
                                            <?php if(!is_null($value['amountStr1'])){
                                                echo trim($value['amountStr1'],'/-');
                                            }else{
                                                echo "Not available";
                                            }
                                            ?>
                                        </strong>
                                    </p>
                                    <?php if(is_numeric($value['awards']) && $value['awards']>0){?>
                                        <p class="fnt_12 clr_9">(<?php echo moneyFormatIndia($value['awards']).' '.((is_numeric($value['awards']))?(($value['awards']==1) ? 'student award' : 'student awards'):''); ?>)</p>
                                    <?php } ?>
                                </div>
                                <?php if($value['category']!=''){ ?>
                                    <p class="fnt_12 clr_6 m-10">Course <strong class="block amountX"><?php echo $value['category']; ?></strong></p>
                                <?php } ?>
                            </div>
                            <a onclick="gaTrackEventCustom('<?php echo $page; ?>','scholarshipPopularWidget','viewAndApply');" href="<?php echo $value['url'];?>" class="btn_new p_btn fnt12_bold">View &amp; Apply</a>
                        </div>
                    </li>
                <?php } ?>
                <?php if($scholarshipCardData['viewAllUrl'] != ''){ ?>
                <li>
                    <div class="ndiv">
                       <a class="trans_col" onclick="gaTrackEventCustom('<?php echo $page; ?>','scholarshipPopularWidget','viewAll');"
                          href="<?php echo $scholarshipCardData['viewAllUrl'];?>">
                        <div class="cntr_div">
                            <div class="round_arrow "><p class="right_arrow"></p></div>
                            <p class="show_in">View All <br/><?php echo $scholarshipCardData['totalCount'];?> Scholarships</p>
                        </div>
                       </a>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
    </section>
    <?php
}
?>