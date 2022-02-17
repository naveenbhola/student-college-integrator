<?php
    if(empty($popular_courses['courses']) || empty($popular_courses['credentials_data']))
        return;
?>
<div class="row widget-parent" id="popularCourseCard">
            <div class="anl-card clearfix">
                <div class="col-md-12">
                    <div class="row">
                        <div class="anl-crd-blk clearfix">
                            <div class="col-md-10 col-sm-12 clearfix">
                                <h2 class="pull-left">Popular Courses <i class="t-icons t-info help-txt" helptext="Popular Courses are identified using the student visits within course pages on Shiksha in the last 12 months. Scoring is on a relative scale from 0 to 100, where 100 signifies the course with most visits and a value of 50 signifies a course which received half as many visits."></i></h2>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="row">
                                    <form id="popularCourseForm" onsubmit="return false;" autocomplete="off">
                                    <?php if(!empty($popular_courses['levels']) && count($popular_courses['levels']) > 1){
                                        ?>
                                       <div class="col-sm-12 col-md-12 ">
                                            <select class="form-control bdr-rd" id="courseLevelFilter" name="courseLevelFilter" autocomplete="off">
                                                <option value="">Levels</option>
                                                <?php
                                                    foreach ($popular_courses['levels'] as $key => $value) {
                                                        $selected = "";

                                                ?>
                                                        <option value="<?php echo $key;?>" <?php if($popular_courses['current_level'] == $key) echo "selected='selected'";?>><?php echo $value;?></option>
                                                <?php   
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                            <?php
                                    }
                                    ?>
         
                                    <div class="col-sm-12 col-md-6 " style="display: none;">
                                        <select class="form-control bdr-rd" id="courseCredentialFilter" name="courseCredentialFilter" autocomplete="off">
                                            <option value="">Credentials</option>
                                            <?php
                                                foreach ($popular_courses['credentials'] as $key => $value) {
                                                    $selected = "";

                                            ?>
                                                    <option value="<?php echo $key;?>" <?php if($popular_courses['current_credential'] == $key) echo "selected='selected'";?>><?php echo $value;?></option>
                                            <?php   
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="pageNumber" value="<?php echo $popular_courses['pageNumber'];?>">
                                    <input type="hidden" name="maxPages" value="<?php echo $popular_courses['maxPages'];?>">
                                    <input type="hidden" name="entityType" value="<?php echo $popular_courses['entityType'];?>">
                                    <input type="hidden" name="entityId" value="<?php echo $popular_courses['entityId'];?>">
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row  pb-15">
                        <div class="col-sm-5">
                            <div id="chart3"></div>
                        </div>
                        <div class="col-sm-7 col-parent">
                            <div class="anl-det clearfix">

                                <?php
                                    if(empty($popular_courses['courses']))
                                        echo "No Data.";

                                    $startingCount = (($popular_courses['pageNumber']-1) * $popular_courses['itemsPerPage'])+1;
                                    $counter = $startingCount;
                                    foreach ($popular_courses['courses'] as $key => $value) {
                                        $url = getETPUrl("base_course", $value['id']);
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
                                if(!empty($popular_courses['courses']) && $popular_courses['maxPages'] > 1){
                            ?>
                            <div class="text-center">
                                <span class="t-icons t-left<?php echo ($popular_courses['pageNumber']==1 ? '' : '-a');?> prev-icon"></span>
                                <span><strong><?php echo $startingCount."-" ;?><?php echo ($popular_courses['pageNumber'] == $popular_courses['maxPages']) ? $popular_courses['totalResults'] : ($popular_courses['itemsPerPage']*$popular_courses['pageNumber']);?></strong> of <?php echo $popular_courses['totalResults'] ;?> Courses</span>
                                <span class="t-icons t-right<?php echo ($popular_courses['pageNumber']< $popular_courses['maxPages'] ? '-a' : '');?> next-icon"></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
</div>
