<?php

?>
<div class = "tuple-box tuple-separater" id="categoryPageSnapshotListing_<?=$course->getId()?>" style="display: none; padding-top:3px;">
    <div class="tuple-detail">
        <div class="course-tuple clearwidth">
            <p class="tuple-sub-title mt5">
                <a href="<?=$course->getSeoUrl()?>" target="_blank"><?=htmlentities($course->getName())?></a>
            </p>
        </div>
        <div class="clearwidth">
            <div class="uni-course-details flLt">
                <div class="detail-col flLt">
                    <?php
                    if($university->getTypeOfInstitute()=='public'){
                        $mark = 'tick';
                        $symbol = "&#10004";
                        $ptag = '<p style="font-size:12px">';
                        $endptag = '</p>';
                    }
                    else{
                        $mark = 'cross';
                        $symbol = "&times;";
                        $ptag = '<p class="non-available" style="font-size:12px">';
                        $endptag = "</p>";
                    }
                    ?>
                    <?=$ptag?>
                        <span class="<?=$mark?>-mark"><?=$symbol?></span>
                        Public University
                    <?=$endptag?>
                </div>
                <div class="detail-col flLt" style="width:120px">
                    <?php
                    if($university->hasCampusAccommodation()){
                        $mark = 'tick';
                        $symbol = "&#10004";
                        $ptag = '<p style="font-size:12px">';
                        $endptag = '</p>';
                    }
                    else{
                        $mark = 'cross';
                        $symbol = "&times;";
                        $ptag = '<p class="non-available" style="font-size:12px">';
                        $endptag = "</p>";
                    }
                    ?>
                    <?=$ptag?>
                        <span class="<?=$mark?>-mark"><?=$symbol?></span>
                        Accommodation
                    <?=$endptag?>
                </div>
            </div>
        </div>
    </div>
</div>
