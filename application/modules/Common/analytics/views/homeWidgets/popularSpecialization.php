<?php
    if(empty($popular_specialization['specialization']))
        return;

    $heightClass = "";
    
    $numResults = 0;
    if($totalResults) {
        $numResults = $totalResults;
    } else if($popular_specialization['totalResults']) {
        $numResults = $popular_specialization['totalResults'];
    }

    if($numResults <= 10 && !$callType){
        $heightClass = " auto-height";
    }
?>

<div class="<?php echo $popular_specialization['fullwidthview'] ? 'row' : 'col-md-6 pull-right twoCol'; ?> widget-parent" id="popularSpecCard">
                <div class="anl-card clearfix">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="anl-crd-blk clearfix">
                            <form id="popularSpecForm" onsubmit="return false;"  autocomplete="off">
                                <div class="col-md-8 col-sm-12 clearfix">
                                    <h2 class="pull-left">Popular Specializations <i class="t-icons t-info help-txt" helptext="Popular Specializations are identified using the student visits within specialization pages on Shiksha in the last 12 months. Scoring is on a relative scale from 0 to 100, where 100 signifies the specialization with most visits and a value of 50 signifies a specialization which received half as many visits."></i></h2>
                                </div>
                                <?php if(!empty($popular_specialization['streams']) && count($popular_specialization['streams']) > 1){
                                    ?>  
                                        <div class="col-md-4 col-sm-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <select class="form-control bdr-rd" id="specStreamFilter" name="specStreamFilter" autocomplete="off">
                                                        <option value="">Education Stream</option>
                                                        <?php
                                                            foreach ($popular_specialization['streams'] as $key => $value) {
                                                                $selected = "";

                                                        ?>
                                                                <option value="<?php echo $key;?>" <?php if($popular_specialization['current_stream'] == $key) echo "selected='selected'";?>><?php echo $value;?></option>
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
                                
                                <input type="hidden" name="pageNumber" value="<?php echo $popular_specialization['pageNumber'];?>">
                                <input type="hidden" name="maxPages" value="<?php echo $popular_specialization['maxPages'];?>">
                                <input type="hidden" name="entityType" value="<?php echo $popular_specialization['entityType'];?>">
                                <input type="hidden" name="entityId" value="<?php echo $popular_specialization['entityId'];?>">
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-parent<?php echo $heightClass; ?>">
                        <div class="anl-det clearfix">
                            <?php
                                    if(empty($popular_specialization['specialization']))
                                        echo "No Data.";

                                    $startingCount = (($popular_specialization['pageNumber']-1) * $popular_specialization['itemsPerPage'])+1;
                                    $counter = $startingCount;
                                    foreach ($popular_specialization['specialization'] as $key => $value) {
                                        $url = getETPUrl("specialization", $value['id']);
                            ?>
                            <a class="click-abl-card" href="<?php echo $url;?>" target="<?php echo $linkTarget;?>" title="<?php echo htmlentities($value['text']);?>">
                            <div class="anl-det-bx">
                                <label class="pull-left"><?php echo $counter++;?></label>
                                <div class="anl-uni pull-left wd-70"><?php echo htmlentities($value['text']);?></div>
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
                            if(!empty($popular_specialization['specialization']) && $popular_specialization['maxPages'] > 1){
                        ?>
                        <div class="text-center pb-15">
                            <span class="t-icons t-left<?php echo ($popular_specialization['pageNumber']==1 ? '' : '-a');?> prev-icon"></span>
                            <span><strong><?php echo $startingCount."-" ;?><?php echo ($popular_specialization['pageNumber'] == $popular_specialization['maxPages']) ? $popular_specialization['totalResults'] : ($popular_specialization['itemsPerPage']*$popular_specialization['pageNumber']);?></strong> of <?php echo $popular_specialization['totalResults'] ;?> Specializations</span>
                            <span class="t-icons t-right<?php echo ($popular_specialization['pageNumber']< $popular_specialization['maxPages'] ? '-a' : '');?> next-icon"></span>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
