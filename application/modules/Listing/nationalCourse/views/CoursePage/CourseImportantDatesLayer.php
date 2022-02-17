<div id="important-dates-layer" style="display: none;">
    <div>
            <?php 
                if(count($importantDatesData['examsHavingDates']) > 1 || (count($importantDatesData['examsHavingDates']) > 0 && $importantDatesData['isCourseDates'])){
                    ?>
                    <div class="gen-cat exam-cat">
                        <p>View Dates by</p>

                        <div class="dropdown-primary importantDatesLayerOptions">
                            <span class="option-slctd">Select</span>
                            <span class="icon"></span>
                            <ul class="dropdown-nav" style="display: none;">
                                <li><a value="">Select</a></li>
                                <?php 
                                foreach ($importantDatesData['examsHavingDates'] as $row) {
                                    ?><li><a value="<?php echo $row['exam_id']; ?>" ga-track="ADMISSION_EXAM_FILTER_LAYER_COURSEDETAIL_DESKTOP"><?php echo $row['exam_name'] ?></a></li><?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
            ?>
            <div class="listing-div">
                <div class="imp-dt-sec clrTpl listing-div layerContent">
                            <ul class="layerData"></ul>                    
                </div>
            </div>
    </div>
</div>