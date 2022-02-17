<div class="col-md-6 pull-left twoCol widget-parent" id="popularUniversityCard">
            <div class="anl-card clearfix">
                <div class="col-md-12">
                    <div class="row">
                        <div class="anl-crd-blk clearfix">
                            <div class="col-md-8 col-sm-12 clearfix">
                                <h2 class="pull-left">Popular Universities <i class="t-icons t-info help-txt" helptext="Popular Universities are identified using the student visits within university pages on Shiksha in the last 12 months. Scoring is on a relative scale from 0 to 100, where 100 signifies the University with most visits and a value of 50 signifies a university which received half as many visits."></i></h2>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="row">
                                    <form id="popularUniversityForm" onsubmit="return false;" autocomplete="off">
                                    <div class="col-sm-12 col-md-12 ">
                                        <select class="form-control bdr-rd" id="univLocFilter" name="univLocFilter" autocomplete="off">
                                            <option value="">Location</option>
                                            <?php
                                                foreach ($popular_universities['locations'] as $key => $value) {
                                                    $selected = "";

                                            ?>
                                                    <option value="<?php echo $key;?>" <?php if($popular_universities['current_location'] == $key) echo "selected='selected'";?>><?php echo $value;?></option>
                                            <?php   
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6 " style="display: none;">
                                        <select class="form-control bdr-rd" autocomplete="off" id="univOwnershipFilter" name="univOwnershipFilter">
                                            <option value="">Ownership</option>
                                            <?php
                                                foreach ($popular_universities['ownership_list'] as $key => $value) {
                                            ?>
                                                    <option value="<?php echo $key;?>" <?php if($popular_universities['current_ownership'] == $key) echo "selected='selected'";?>><?php echo $value;?></option>
                                            <?php   
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="pageNumber" value="<?php echo $popular_universities['pageNumber'];?>">
                                    <input type="hidden" name="maxPages" value="<?php echo $popular_universities['maxPages'];?>">
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row pb-15">
                        <div class="col-parent">
                            <div class="anl-det clearfix">
                                <?php
                                    if(empty($popular_universities['universities']))
                                        echo "No Data.";

                                    $startingCount = (($popular_universities['pageNumber']-1) * $popular_universities['itemsPerPage'])+1;
                                    $counter = $startingCount;
                                    foreach ($popular_universities['universities'] as $key => $value) {
                                        $url = getETPUrl("university", $value['id'], $value['type']);
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
                                if(!empty($popular_universities['universities']) && $popular_universities['maxPages'] > 1){
                            ?>
                            <div class="text-center">
                                <span class="t-icons t-left<?php echo ($popular_universities['pageNumber']==1 ? '' : '-a');?> prev-icon"></span>
                                <span><strong><?php echo $startingCount."-" ;?><?php echo ($popular_universities['pageNumber'] == $popular_universities['maxPages']) ? $popular_universities['totalResults'] : ($popular_universities['itemsPerPage']*$popular_universities['pageNumber']);?></strong> of <?php echo $popular_universities['totalResults'] ;?> Universities</span>
                                <span class="t-icons t-right<?php echo ($popular_universities['pageNumber']< $popular_universities['maxPages'] ? '-a' : '');?> next-icon"></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
