<?php 
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
                    <strong>Scholarships for this Course</strong>
                    <div class="sch-wdgt">
                        <?php
                        foreach ($scholarshipCardData['scholarshipData'] as $key=>$value)
                        {
                            ?>
                            <div class="sch-wdgt-list clearfix" onclick="goToScholarshipPage('<?php echo $page; ?>','scholarshipPopularWidget','viewAndApply','<?php echo $value['url'];?>');">
                                <a href="<?php echo $value['url'];?>"><?php echo (strlen($value['name'])>65)?(substr($value['name'],0,65).'...'):($value['name']);?></a>
                                <ul>
                                    <li>
                                        <label>Scholarship Amount</label>
                                        <p><?php echo trim($value['amountStr1'],'/-').
                                                ((strpos($value['amountStr1'],'available')!==false)?'':'');?> <?php if(is_numeric($value['awards']) && $value['awards']>0){?>
                                                <span>(<?php echo moneyFormatIndia($value['awards']).' '.((is_numeric($value['awards']))?(($value['awards']==1) ? 'student award' : 'student awards'):''); ?>)</span>
                                            <?php } ?></p>
                                    </li>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                    <?php
                }
                else
                {
                    ?>
                    <strong>Scholarships for this Course</strong>
                    <div class="sch-wdgt">
                        <div class="sch-wdgt-list clearfix">
                            No scholarships are offered by this course but you can consider applying to generic scholarships applicable for <?php echo $scholarshipCardData['genericScholarshipsText'];?>.
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="sch-link"><a onclick="gaTrackEventCustom('<?php echo $page; ?>','scholarshipPopularWidget','viewAll');" href="<?php echo $scholarshipCardData['viewAllUrl'];?>">View<?php  echo ($scholarshipCardData['totalCount']>1?(' All '.$scholarshipCardData['totalCount'].' Scholarships'):(' '.$scholarshipCardData['totalCount'].' scholarship'));?></a></div>
            </div>
        </div>

    </section>
    <?php
}
?>