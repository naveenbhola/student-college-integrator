<!-- top tiles -->
<?php  if($metric == 'overview'){?>
    <div class="row tile_count">
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="text-center">
                <span class="count_top">Unique Users</span>
                <div class="count topTiles_size" id="users"></div>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
            </div>
        </div>

        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="text-center">
                <span class="count_top">Sessions</span>
                <div class="count topTiles_size" id="sessions"></div>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
            </div>
        </div>

        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="text-center">
                <span class="count_top">Page Views</span>
                <div class="count topTiles_size" id="pageviews"></div>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
            </div>

        </div>

        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="text-center">
                <span class="count_top"></i> Avg Session (Mins)</span>
                <div class="count topTiles_size" id="avgsessdur"></div>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
            </div>

        </div>

        <!-- <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="text-center">
                <span class="count_top">Paid Responses / Paid Courses</span>
                <div class="count topTiles_size" id="response-course-ratio"></div>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
            </div>

        </div>
 -->
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="text-center">
                <span class="count_top">Registrations</span>
                <div class="count topTiles_size" id="registration"></div>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
            </div>

        </div>
    </div>
<?php }
if(preg_match('/^response(s)?$/i', $metric) == 1 || $metric == 'leads'){ ?>
    <div class="row tile_count">
        <?php 
            $topTiles = $pageDetails['SA_TOP_Tiles'];
            $i=1;
            foreach ($topTiles as $key => $value) {         
        ?>
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count" id="<?php echo 'topHeading_'.$i; ?>">
            <div class="left"></div>
            <div class="right">
                <span class="count_top" style="color: #73879c !important;"><i class="fa fa-user"></i> <?php echo $value; ?></span>
                <div class="count topTiles_size" id="<?php echo 'topTiles_'.$i; ?>">0</div>
                <span class="count_bottom"><i id="<?php echo 'bottom_'.$i; ?>"><i class="fa"></i></i></span>
                <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>

            </div>
        </div>
        <?php $i++;  } ?>
    </div>
<?php }
if($metric == 'engagementNotUsed'){?>
    <div class="row tile_count">
        <?php 
            $topTiles = $pageDetails['SA_TOP_Tiles'];
            $i=1;
            foreach ($topTiles as $key => $value) {         
                if($key != 'newsessions') {
                    $class = 'cursor_pointer border_1px_solid_eee border_radius_6 ';
                    if($key == 'users' || $key == 'pageview')
                        $class .= 'defaultColor';
                    else $class .= 'bgColor';
                } else {
                    $class = 'bgColor';
                }
                ?>
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count <?php echo $class; ?>" id="<?php echo 'topHeading_'.$i; ?>"  data-text="<?php echo $key; ?>">
            <div class="left"></div>
            <div class="right">
                <span class="count_top" style="color: #73879c !important;"><i class="fa fa-user"></i> <?php echo $value; ?></span>
                <div class="count topTiles_size" id="<?php echo 'topTiles_'.$i; ?>">0</div>
                <span class="count_bottom"><i id="<?php echo 'bottom_'.$i; ?>"><i class="fa"></i></i></span>
            </div>
        </div>
        <?php $i++;  } ?>
    </div>
<?php }?>
<?php 
if(
    $metric == 'registration' || $metric == 'traffic' || $metric == 'engagement' || $metric == 'response' || $metric == 'RMC' ||
    ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home") ||
    ($teamName =="Study Abroad" && $metric == "exam_upload")
   )
{?>
    <div class="row tile_count x_title">
        <?php 
            $topTiles = $pageDetails['TOP_TILES'];
            $colMDClass = 'col-md-2';
            foreach ($topTiles as $key => $value) {
                if($metric == 'traffic'){
                        $colMDClass = 'col-md-3';
                        if($key != 'perNewSessions') {
                            $class = 'cursor_pointer border_1px_solid_eee border_radius_6 ';
                            if($key == 'Users'){
                                $class .= 'defaultColor';
                            }else{
                                $class .= 'bgColor';
                            }                    
                        } else {
                            $class = 'bgColor';
                        }    
                }else if($metric == 'engagement'){
                    if($pageName){
                        $colMDClass = 'col-md-2';
                    }else{
                        $colMDClass = 'col-md-2';    
                    }
                    if($key != "totalSessions"){
                        $class = 'cursor_pointer border_1px_solid_eee border_radius_6 ';
                    }else{
                        $class = 'bgColor';
                    }   
                    //$class = 'cursor_pointer border_1px_solid_eee border_radius_6 ';
                    if($key == 'Page Views'){
                        $class .= 'defaultColor';
                    }else{
                        $class .= 'bgColor';
                    }
                }
        ?>
                <div class="animated flipInY <?php echo $colMDClass;?> col-sm-4 col-xs-4 tile_stats_count <?php echo $class; ?>" id="<?php echo 'topHeading_'.$value['id']; ?>" data-text="<?php echo $key; ?>">
                    <div class="left"></div>
                    <div class="right">
                        <span class="count_top" style="color: #73879c !important;"><i class="fa fa-user"></i> <?php echo $value['title']; ?></span>
                        <div class="count topTiles_size" id="<?php echo $value['id']; ?>">0</div>
                        <span class="count_bottom"><i id="<?php echo 'bottom_'.$value['id']; ?>"><i class="fa"></i></i></span>
                        <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>

                    </div>
                </div>
        <?php }  ?>
    </div>
<?php } ?>
<!-- /top tiles -->
