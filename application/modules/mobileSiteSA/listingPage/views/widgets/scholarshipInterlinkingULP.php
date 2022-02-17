<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 13/4/18
 * Time: 4:22 PM
 */
//_p($scholarshipCardData);die;
$page = $beaconTrackData['pageIdentifier'];
if(isset($scholarshipCardData) && isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount']>0)
{
    ?>
    <section class="detail-widget navSection clearfix" data-enhance="false" id="feeSection" style="">
        <div style="padding:0;" class="detail-widegt-sec clearfix">
            <div class="detail-info-sec">
                <?php
                if($scholarshipCardData['mapFlag'] == 1)
                {
                    ?>
                    <strong>Scholarships for this University</strong>
                    <div class="sch-wdgt">
                        <?php
                        foreach ($scholarshipCardData['scholarshipData'] as $key=>$value)
                        {
                            ?>
                            <a class="sch-wdgt-list clearfix" onclick="gaTrackEventCustom('<?php echo $page; ?>','scholarshipPopularWidget','viewAndApply');" href="<?php echo $value['url'];?>">
                                <p class="hideTxt"><?php echo (strlen($value['name'])>65)?(substr($value['name'],0,65).'...'):($value['name']);?></p>
                                <ul>
                                    <li>
                                        <label>Scholarship amount</label>
                                        <p><?php echo trim($value['amountStr1'],'/-');?> <?php if(is_numeric($value['awards']) && $value['awards']>0){?>
                                                <span>(<?php echo moneyFormatIndia($value['awards']).' '.((is_numeric($value['awards']))?(($value['awards']==1) ? 'student award' : 'student awards'):''); ?>)</span>
                                            <?php } ?></p>
                                    </li>
                                    <?php
                                    if(!empty($value['category']))
                                    {
                                        ?>
                                        <li>
                                            <label>Course stream applicable</label>
                                            <p><?php echo $value['category'];?></p>
                                        </li>
                                        <?php
                                    }
                                    ?>

                                </ul>
                            </a>
                            <?php
                        }
                        ?>

                    </div>
                    <?php
                }
                else
                {
                    ?>
                    <strong>Scholarships for this University</strong>
                    <div class="sch-wdgt">
                        <div class="sch-wdgt-list clearfix">
                            No scholarships are offered by this university but you can consider applying to generic scholarships applicable for <?php echo (isset($scholarshipCardData['coreCountryFlag'])&&($scholarshipCardData['coreCountryFlag']==0)
                                ?$scholarshipCardData['countryName'].' and other countries':$scholarshipCardData['countryName']);?>.
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="sch-link"><a onclick="gaTrackEventCustom('<?php echo $page; ?>','scholarshipPopularWidget','viewAll');" href="<?php echo $scholarshipCardData['viewAllUrl'];?>">View<?php echo isset($scholarshipCardData['coreCountryFlag'])&&($scholarshipCardData['coreCountryFlag']==0) ? (' all scholarships') :
                            (($scholarshipCardData['totalCount']>1) ?(' all '.$scholarshipCardData['totalCount'].' scholarships'):(' '.$scholarshipCardData['totalCount'].' scholarship'));?></a></div>
            </div>
        </div>

    </section>
    <?php
}
?>