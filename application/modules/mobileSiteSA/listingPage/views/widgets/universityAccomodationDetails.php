<?php
    if(strlen($universityAccomodationDetails)>160){
        $shortUniversityAccomodationDetails = formatArticleTitle($universityAccomodationDetails, 150);
        $remainingUniversityAccomodationDetails = substr($universityAccomodationDetails, strlen($shortUniversityAccomodationDetails)-3);
        $shortUniversityAccomodationDetails .= '<shikshadynamic></shikshadynamic>';
    }
?>
<!--<section class="detail-widget">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec dynamic-content viewMoreSection">
            <strong>Accommodation details</strong>
            <?php //if(isset($universityAccomodationDetails)){?>
                    <p class="contentSection"><?//=$shortUniversityAccomodationDetails;//(isset($shortUniversityAccomodationDetails))?$shortUniversityAccomodationDetails:$universityAccomodationDetails;?></p>
            <?php //}
                //if(isset($shortUniversityAccomodationDetails)){
            ?>
                    <div class="remainigText" style="display: none"><?//=$remainingUniversityAccomodationDetails?></div>
                        <?php
                            //if(isset($universityAccomodationURL)){
                        ?>
                                <div class="tac">
                                <a href="<?//=$universityAccomodationURL?>" class="ui-link" onclick="studyAbroadTrackEventByGA('ABROAD_<//?=strtoupper($listingType)?>_PAGE', 'outgoingLink');" rel="nofollow" target="_blank">Accommodation details
                                    <i class="sprite arrow-icon"></i>
                                </a>
                            </div>  
                        <?php //}?>
                        <div class="less-more-sec">
                        <a href="javascript:void(0)" onclick="showFullDynamicContent(this)">+View More</a>
                    </div>
            <?php //}
                    //if(isset($universityAccomodationURL) && !isset($shortUniversityAccomodationDetails)){
            ?>
                <div class="tac">
                    <a href="<?//=$universityAccomodationURL?>" class="ui-link" onclick="studyAbroadTrackEventByGA('ABROAD_<?//=strtoupper($listingType)?>_PAGE', 'outgoingLink');" rel="nofollow" target="_blank">Accommodation details
                        <i class="sprite arrow-icon"></i>
                    </a>
                </div>
            <?php //}?>
        </div>
    </div>
</section>-->

<section class="detail-widget">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec dynamic-content viewMoreSection">
            <strong>Accommodation details</strong>
            <p class="contentSection"><?=$universityAccomodationDetails?></p>
            <?php 
                if(isset($universityAccomodationURL)){
            ?>
                <div class="tac">
                    <a href="<?=$universityAccomodationURL?>" class="ui-link" rel="nofollow" target="_blank">Accommodation details
                        <i class="sprite arrow-icon"></i>
                    </a>
                </div>
        <?php }?>
        </div>
        
    </div>
</section>