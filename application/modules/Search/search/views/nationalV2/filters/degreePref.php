<li><a class="activeLeftNav">Recognized By<p id="cleardp" style="<?php echo (count($appliedFilters['degreePref']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
<div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
    <ul class="srpsubmenu" data-section="degreePref">
    <?php 
    foreach ($data as $degreePrefName => $val) {
        $checkedDegreePrefString = "";
        if($val['checked']){
            $checkedDegreePrefString = "checked ='chechked'";
        }
        $dpId = str_replace(" ", "-", $degreePrefName);

        ?>
        <li>
            <a class="checkboxnav">
                <div class="nav-checkBx">
                    <input type="checkbox" class="nav-inputChk" <?=$checkedDegreePrefString;?> id="<?=$dpId?>" data-val="<?=$degreePrefName?>">
                    <label for="<?=$dpId?>" class="nav-heck">
                        <i class="icons ic_checkdisable1"></i><?=$val['name']?> (<?=$val['count']?>)
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