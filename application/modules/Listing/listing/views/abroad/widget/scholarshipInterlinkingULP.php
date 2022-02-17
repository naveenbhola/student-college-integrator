<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 17/4/18
 * Time: 11:27 AM
 */
$page = $beaconTrackData['pageIdentifier'];
if(isset($scholarshipCardData) && isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount']>0) {
    ?>
    <div class="widget-wrap clearwidth">
        <h2>Scholarships for this University</h2>
        <div class="course-detail-tab studentGuide-tab">
            <div class="course-detail-mid sch-dv">
                <?php
                if($scholarshipCardData['mapFlag'] == 1) {
                    foreach ($scholarshipCardData['scholarshipData'] as $key=>$value) {
                        ?>
                        <div class="table_c">
                            <div class="sch-list last">
                            <a class="gaTrack" gaparams="<?php echo $page; ?>,scholarshipPopularWidget,viewAndApply" title="<?php echo $value['name'];?>" href="<?php echo $value['url'];?>"><?php echo (strlen($value['name'])>65)?(substr($value['name'],0,65).'...'):($value['name']);?></a><div class="sch-Ldiv" style="">
                                <div class="sch-box">
                                    <label>Scholarship per student</label>
                                    <strong><?php echo trim($value['amountStr1'],'/-');?></strong>
                                    <?php if(is_numeric($value['awards']) && $value['awards']>0){?>
                                        <span class="block_span">(<?php echo moneyFormatIndia($value['awards']).' '.((is_numeric($value['awards']))?(($value['awards']==1) ? 'student award' : 'student awards'):''); ?>)</span>
                                    <?php } ?>
                                </div>
                                <?php
                                if(!empty($value['category']))
                                {
                                    ?>
                                    <div class="sch-box appl-wdt">
                                        <label>Course stream applicable</label>
                                        <strong><?php echo $value['category'];?></strong>
                                    </div>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                            <div class="sch-box tac" style="">
                                <a class="btns btn-trans n-btn-width gaTrack" gaparams="<?php echo $page; ?>,scholarshipPopularWidget,viewAndApply" href="<?php echo $value['url'];?>">View &amp; Apply <i class="arr__new"></i></a>
                            </div>
                        </div>
                        <?php
                    }
                }
                else
                {
                    ?>
                <div class="table_c">
                        <p class="cntr_align">No scholarships are offered by this university but you can consider applying to generic scholarships applicable for <?php echo (isset($scholarshipCardData['coreCountryFlag'])&&($scholarshipCardData['coreCountryFlag']==0)
                                ?$scholarshipCardData['countryName'].' and other countries':$scholarshipCardData['countryName']);?>.</p>
                </div>
                    <?php
                }
                ?>

                <div class="taR">
                    <a href="<?php echo $scholarshipCardData['viewAllUrl'];?>" class="sch-link gaTrack" gaparams="<?php echo $page; ?>,scholarshipPopularWidget,viewAll">
                        View<?php echo isset($scholarshipCardData['coreCountryFlag'])&&($scholarshipCardData['coreCountryFlag']==0) ? (' all scholarships') :
                         (($scholarshipCardData['totalCount']>1) ?(' all '.$scholarshipCardData['totalCount'].' scholarships'):(' '.$scholarshipCardData['totalCount'].' scholarship'));?><span class="rightLink"></span></a>
                </div>
            </div>
        </div>

    </div>
    <?php
}
?>
