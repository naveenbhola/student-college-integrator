<div class="overview-details flLt collegeRepresented-tab" style="width:100%;">
    <h2>Colleges where <?= htmlentities($consultantObj->getName());?> has sent students</h2>
    <div id="collegeRepresented-tab-scrollbar1" class="clearwidth cons-scrollbar1 scrollbar1 soft-scroller">
        <div class="cons-scrollbar scrollbar" style="visibility:hidden; left: 8px;">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        
        <div class="viewport" style="height:400px">
            <div class="overview" style="width: 100%">
                <ul class="college-list">
                    <?php   $universityOfficialRepresentative = array();
                            foreach($consultantObj->getUniversitiesMapped() as $universityMapped){
                                $universityOfficialRepresentative[$universityMapped['universityId']] = $universityMapped['isOfficialRepresentative'];
                            }
                            foreach($collegesRepresentedTabData as $tuple){
                    ?>
                    <li>
                        <div class="college-figure">
                            <a href="<?= $tuple['listing_seo_url']?>" title="<?= htmlentities($tuple['univName'])?>"><img src="<?= $tuple['logo_link']?>" width="137" height="43" alt="<?= htmlentities($tuple['univName'])?>" title="<?= htmlentities($tuple['univName'])?>"></a>
                        </div>
                        <div class="detail-sec consultantToolTip">
                            <p><a href="<?= $tuple['listing_seo_url']?>" title="<?= htmlentities($tuple['univName'])?>"><?= htmlentities($tuple['univName'])?></a></p>
                            <p class="college-country"><?= $tuple['city']?>, <?= $tuple['country']?></p>
                            <?php
                                if($universityOfficialRepresentative[$tuple['univId']] == 'yes'){
                            ?>
                                <div class="offered-rep-box" onmouseover="showToolTipConsultantVerified(this)" onmouseout="showToolTipConsultantVerified(this)" style="position: relative;">
                                    <i class="listing-sprite off-rep-icon"></i>
                                    <span>Official representative</span>
                                    <div style="left: auto; display:none; top: 32px; padding: 8px; width: 254px" class="tooltip-info">
                                        <i class="common-sprite verified-up-pointer"></i>
                                        This consultant is an official representative of this university
                                    </div>
                                </div>
                            <?php }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        <?php if($tuple['excludeCourseComment'] !=''){?>
                        <p class="service-info"><?= htmlentities($tuple['excludeCourseComment']);?></p>
                        <?php }?>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>