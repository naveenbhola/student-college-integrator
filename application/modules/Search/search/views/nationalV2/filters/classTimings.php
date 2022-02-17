<li><a class="activeLeftNav">Class Timings<p id="clearclt" style="<?php echo (count($appliedFilters['classTimings']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="classTimings">
                    <?php foreach ($data as $timingname => $val) {
                        $checkedString = "";
                            if($val['checked']){
                                $checkedString = "checked ='chechked'";
                            }
                        ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" <?=$checkedString;?> id="<?=$timingname?>" data-val="<?=$timingname?>">
                                    <label for="<?=$timingname?>" class="nav-heck">
                                        <i class="icons ic_checkdisable1"></i><?=$val['name']?> (<?=$val['count']?>)
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