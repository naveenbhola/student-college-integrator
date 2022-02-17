<li><a class="activeLeftNav">Course Level<p id="clearcl" style="<?php echo (count($appliedFilters['level']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="courseLevel">
                    <?php 
                    foreach ($data as $courseLevelName => $info) {
                        $count = $info['count'];
                        $checkedCourseLevelString = "";
                            if($info['checked']){
                                $checkedCourseLevelString = "checked ='chechked'";
                            }
                            $clId = str_replace(" ", "-",$courseLevelName);
                        ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" <?=$checkedCourseLevelString;?> id="<?=$clId?>" data-val="<?=$courseLevelName?>">
                                    <label for="<?=$clId?>" class="nav-heck">
                                        <i class="icons ic_checkdisable1"></i><?=$info['name']?> (<?=$count?>)
                                    </label>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</li>