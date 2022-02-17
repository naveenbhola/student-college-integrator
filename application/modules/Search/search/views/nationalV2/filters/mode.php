<li>
    <a class="activeLeftNav">Mode of Study<p id="clearmo" style="<?php echo (count($appliedFilters['mode']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="mode">
                <?php 
                foreach ($data as $modeName => $info) {
                        $count = $info['count'];
                        $checkedModeLevelString = "";
                        if($info['checked']){
                            $checkedModeLevelString = "checked ='chechked'";
                        }

                        $mId = str_replace(" ", "-", $modeName);
                    ?>
                    <li>
                        <a class="checkboxnav">
                            <div class="nav-checkBx">
                                <input type="checkbox" class="nav-inputChk" <?=$checkedModeLevelString;?> id="<?=$mId?>" data-val="<?=$modeName?>">
                                <label for="<?=$mId?>" class="nav-heck">
                                    <i class="icons ic_checkdisable1"></i><?=$info['name']?> (<?=$count?>)
                                </label>
                            </div>
                        </a>
                    </li>
                <?php }?>
                </ul>
            </div>
        </div>
    </div>
</li>