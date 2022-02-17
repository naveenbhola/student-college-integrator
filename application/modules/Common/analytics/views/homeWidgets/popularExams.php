<?php
    if(empty($popular_exams['exams']))
        return;
?>
<div class="col-md-6 pull-left twoCol widget-parent" id="popularExamCard">
                <div class="anl-card clearfix">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="anl-crd-blk clearfix">
                            <form id="popularExamForm" onsubmit="return false;" autocomplete="off">
                                <div class="col-md-8 col-sm-12 clearfix">
                                    <h2 class="pull-left">Popular Exams <i class="t-icons t-info help-txt" helptext="Popular Exams are identified using the student visits within exam pages on Shiksha in the last 12 months. Scoring is on a relative scale from 0 to 100, where 100 signifies the exam with most visits and a value of 50 signifies a exam which received half as many visits."></i></h2>
                                </div>
                                <?php if(!empty($popular_exams['streams']) && count($popular_exams['streams']) > 1){
                                        ?>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select class="form-control bdr-rd" id="examStreamFilter" name="examStreamFilter" autocomplete="off">
                                                            <option value="">Education Stream</option>
                                                            <?php
                                                                foreach ($popular_exams['streams'] as $key => $value) {
                                                                    $selected = "";

                                                            ?>
                                                                    <option value="<?php echo $key;?>" <?php if($popular_exams['current_stream'] == $key) echo "selected='selected'";?>><?php echo $value;?></option>
                                                            <?php   
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                }
                                ?>

                                <input type="hidden" name="pageNumber" value="<?php echo $popular_exams['pageNumber'];?>">
                                <input type="hidden" name="maxPages" value="<?php echo $popular_exams['maxPages'];?>">
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-parent">
                        <div class="anl-det clearfix">
                            <?php
                                    if(empty($popular_exams['exams']))
                                        echo "No Data.";

                                    $startingCount = (($popular_exams['pageNumber']-1) * $popular_exams['itemsPerPage'])+1;
                                    $counter = $startingCount;
                                    foreach ($popular_exams['exams'] as $key => $value) {
                                        $url = getETPUrl("exam", $value['id']);
                                ?>
                                <a class="click-abl-card" href="<?php echo $url;?>" target="<?php echo $linkTarget;?>" title="<?php echo htmlentities($value['name']);?>">
                            <div class="anl-det-bx">
                                <label class="pull-left"><?php echo $counter++;?></label>
                                <div class="anl-uni pull-left wd-70"><?php echo htmlentities($value['name']);?></div>
                                <div class="pg-br pull-left">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $value['share'];?>%">
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-left pull-count wd-cnt"><?php echo $value['share'];?></div>
                            </div>
                            </a>
                            <?php   
                                    }
                            ?>
                        </div>

                        <?php 
                                if(!empty($popular_exams['exams']) && $popular_exams['maxPages'] > 1){
                        ?>
                        <div class="text-center pb-15">
                            <span class="t-icons t-left<?php echo ($popular_exams['pageNumber']==1 ? '' : '-a');?> prev-icon"></span>
                            <span><strong><?php echo $startingCount."-" ;?><?php echo ($popular_exams['pageNumber'] == $popular_exams['maxPages']) ? $popular_exams['totalResults'] : ($popular_exams['itemsPerPage']*$popular_exams['pageNumber']);?></strong> of <?php echo $popular_exams['totalResults'] ;?> Exams</span>
                            <span class="t-icons t-right<?php echo ($popular_exams['pageNumber']< $popular_exams['maxPages'] ? '-a' : '');?> next-icon"></span>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
