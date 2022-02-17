<section class="detail-widget">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec">
            <strong>Popular Course<?=count($popularCourses)==1?"":"s"?> at this University</strong>
            <ul class="univ-detail-list">
                <?php foreach($popularCourses as $course){ ?>
                <li>
                    <div class="univ-detail-section">
                        <a href="<?=$course->getURL()?>" style="font-weight:bold; width:90%; display:block; word-wrap:break-word;"><?=htmlentities($course->getName())?></a>
                        <div class="fee-eligibilty-criteria">
                            <div class="fee-eligibilty-col">
                                <span>Total fees</span>
                                <p><a href="<?=$course->getURL()?>" style="color: #333;">
                                    <?=$abroadListingCommonLib->getIndianDisplableAmount($abroadListingCommonLib->convertCurrency($course->getTotalFees()->getCurrency(), 1, $course->getTotalFees()->getValue()),1)?>
                                </a></p>
                            </div>
                            <div class="fee-eligibilty-col">
                                <span>Eligibility</span>
                                <p><a href="<?=$course->getURL()?>" style="color: #333;">
                                    <?php
                                        $count = 0;
                                        foreach($course->getEligibilityExams() as $exam){
                                            if($exam->getId() != -1){   ?>
                                                <?php $count++; ?>
                                                <?php if($count == 2){
                                                    echo "|";
                                                }?>
                                                <?=htmlentities($exam->getName())?>:<?=$exam->getCutOff()=="N/A"?"Accepted":$exam->getCutOff()?>
                                            <?php
                                                
                                                if($count == 2){
                                                    break;
                                                }
                                            }
                                        }
                                    ?>
                                </a></p>
                            </div>
                            <div class="fee-eligibilty-col" style="width:5%">
                                <a href="<?=$course->getURL()?>"><i class="sprite univ-detail-arrw"></i></a>
                            </div>
                        </div>
                     </div>
                </li>
                <?php } ?>
            </ul>
            <div class="clearfix"></div>
        </div>	
    </div>
</section>