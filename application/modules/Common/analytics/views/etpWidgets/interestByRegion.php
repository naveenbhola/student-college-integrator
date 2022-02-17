<?php 
    if(empty($region_data['chart_data']))
        return;
?>
<div class="row widget-parent" id="interestByRegionCard">
            <div class="anl-card clearfix">
                <div class="col-md-12">
                    <div class="row">
                    <form id="interestByRegionForm" onsubmit="return false;">
                        <div class="anl-crd-blk clearfix">
                            <div class="col-md-8 col-sm-12 clearfix">
                                <h2 class="pull-left">Interest By Region <i class="t-icons t-info help-txt" helptext="Interest By Region represents visits on Shiksha by students from various states in the country during the last 12 months. Values are calculated on a scale of 0 to 100, where 100 signifies maximum visits from a state and a value of 50 indicates a state that generated half as many visits."></i></h2>
                            </div>
                         </div>
                        <input type="hidden" name="pageNumber" value="<?php echo $region_data['pageNumber'];?>">
                        <input type="hidden" name="maxPages" value="<?php echo $region_data['maxPages'];?>">
                        <input type="hidden" name="entityType" value="<?php echo $region_data['entityType'];?>">
                        <input type="hidden" name="entityId" value="<?php echo $region_data['entityId'];?>">
                    </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row  pb-15">
                        <div class="col-sm-5"><div id="regionChart"></div></div>
                        <div class="col-sm-7 col-parent">
                            <div class="anl-det clearfix">
                            <?php
                                if(empty($region_data['states']))
                                    echo "No Data.";

                                $startingCount = (($region_data['pageNumber']-1) * $region_data['itemsPerPage'])+1;
                                $counter = $startingCount;
                                foreach ($region_data['states'] as $key => $value) {
                            ?>
                                <div class="anl-det-bx">
                                    <label class="pull-left"><?php echo $counter++;?></label>
                                    <div class="anl-uni pull-left wd-70" title="<?php echo htmlentities($value['name']);?>"><?php echo htmlentities($value['name']);?></div>
                                    <div class="pg-br pull-left">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $value['share'];?>%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pull-left pull-count wd-cnt"><?php echo $value['share'];?></div>
                                </div>
                            <?php
                            }
                            ?>
                            </div>

                             <?php 
                                if(!empty($region_data['states']) && $region_data['maxPages'] > 1){
                            ?>
                            <div class="text-center">
                                <span class="t-icons t-left<?php echo ($region_data['pageNumber']==1 ? '' : '-a');?> prev-icon"></span>
                                <span><strong><?php echo $startingCount."-" ;?><?php echo ($region_data['pageNumber'] == $region_data['maxPages']) ? $region_data['totalResults'] : ($region_data['itemsPerPage']*$region_data['pageNumber']);?></strong> of <?php echo $region_data['totalResults'] ;?> States</span>
                                <span class="t-icons t-right<?php echo ($region_data['pageNumber']< $region_data['maxPages'] ? '-a' : '');?> next-icon"></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
</div>
