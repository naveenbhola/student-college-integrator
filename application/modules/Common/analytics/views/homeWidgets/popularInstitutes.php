<?php

if(empty($entityId)){
    $columnClass = "col-md-6 pull-right";
    $title = "Popular Colleges";
}
else{

    $columnClass = "";
    if($entityType == "university"){
        $title = "Popular Colleges/Dept.";
    }else{            
        $title = "Popular Colleges/Universities";
    }
}
$heightClass = "";

$numResults = 0;

if($totalResults) {
    $numResults = $totalResults;
} else if($popular_institutes['totalResults']) {
    $numResults = $popular_institutes['totalResults'];
}
if($numResults <= 10 && !$callType){
    $heightClass = " auto-height";
}
?>
<div class="<?php echo $columnClass;?> twoCol widget-parent" id="popularInstituteCard">
            <div class="anl-card clearfix">
                <div class="col-md-12">
                    <div class="row">
                        <div class="anl-crd-blk clearfix">
                            <div class="col-md-8 col-sm-12 clearfix">
                                <h2 class="pull-left"><?php echo $title;?> <i class="t-icons t-info help-txt" helptext="Popular Colleges are identified using the student visits within college pages on Shiksha in the last 12 months. Scoring is on a relative scale from 0 to 100, where 100 signifies the college with most visits and a value of 50 signifies a college which received half as many visits."></i></h2>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="row">
                                    <form id="popularInstituteForm" onsubmit="return false;" autocomplete="off">
                                    <?php if(!empty($popular_institutes['locations']) && count($popular_institutes['locations']) > 1){
                                        ?>
                                            <div class="col-sm-12 col-md-12 ">
                                                <select class="form-control bdr-rd" id="instLocFilter" name="instLocFilter" autocomplete="off">
                                                    <option value="">Location</option>
                                                    <?php
                                                        foreach ($popular_institutes['locations'] as $key => $value) {
                                                            $selected = "";
                                                    ?>
                                                            <option value="<?php echo $key;?>" <?php if($popular_institutes['current_location'] == $key) echo "selected='selected'";?>><?php echo $value;?></option>
                                                    <?php   
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php
                                    }
                                    ?>
                                    
                                    <div class="col-sm-12 col-md-6" style="display: none;">
                                        <select class="form-control bdr-rd" id="instStreamFilter" name="instStreamFilter"  autocomplete="off">
                                            <option value="">Stream</option>
                                            <?php
                                                foreach ($popular_institutes['stream_share'] as $key => $value) {
                                                    $selected = "";
                                            ?>
                                                    <option value="<?php echo $key;?>" <?php if($popular_institutes['current_stream'] == $key) echo "selected='selected'";?>><?php echo $value['text'];?></option>
                                            <?php   
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="pageNumber" value="<?php echo $popular_institutes['pageNumber'];?>">
                                    <input type="hidden" name="maxPages" value="<?php echo $popular_institutes['maxPages'];?>">
                                    <input type="hidden" name="entityType" value="<?php echo $popular_institutes['entityType'];?>">
                                <input type="hidden" name="entityId" value="<?php echo $popular_institutes['entityId'];?>">
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row  pb-15">
                        <div class="col-parent<?php echo $heightClass;?>">
                            <div class="anl-det clearfix">
                                <?php
                                    if(empty($popular_institutes['institutes']))
                                        echo "No Data.";
                                    $startingCount = (($popular_institutes['pageNumber']-1) * $popular_institutes['itemsPerPage'])+1;
                                    $counter = $startingCount;
                                    foreach ($popular_institutes['institutes'] as $key => $value) {
                                        $url = getETPUrl("institute", $value['id'], $value['type']);
                                ?>
                                <a class="click-abl-card" href="<?php echo $url;?>" target="<?php echo $linkTarget;?>" title="<?php echo htmlentities($value['name']);?>">
                                <div class="anl-det-bx">
                                    <label class="pull-left"><?php echo $counter++;?></label>
                                    <div class="anl-uni pull-left"><?php echo htmlentities($value['name']);?></div>
                                    <div class="anl-cty pull-left"><p title="<?php echo htmlentities($value['location']);?>"><?php echo htmlentities($value['location']);?></p></div>
                                    <div class="pg-br pull-left">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $value['share'];?>%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pull-left pull-count"><?php echo $value['share'];?></div>
                                </div>
                                </a>
                                <?php
                                    }
                                ?>
                            </div>
                            <?php 
                            if(!empty($popular_institutes['institutes']) && $popular_institutes['maxPages'] > 1){
                            ?>
                            <div class="text-center">
                                <span class="t-icons t-left<?php echo ($popular_institutes['pageNumber']==1 ? '' : '-a');?> prev-icon"></span>
                                <span><strong><?php echo $startingCount."-" ;?><?php echo ($popular_institutes['pageNumber'] == $popular_institutes['maxPages']) ? $popular_institutes['totalResults'] : ($popular_institutes['itemsPerPage']*$popular_institutes['pageNumber']);?></strong> of <?php echo $popular_institutes['totalResults'] ;?> Colleges</span>
                                <span class="t-icons t-right<?php echo ($popular_institutes['pageNumber']< $popular_institutes['maxPages'] ? '-a' : '');?> next-icon"></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
</div>
