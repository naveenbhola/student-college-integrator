<li><a class="activeLeftNav">Facilities<p id="clearfa" style="<?php echo (count($appliedFilters['facilities']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="facilities">
                    <?php 
                    foreach ($data as $facilities => $val) {
                            $checkedfacilitiesString = "";
                            if($val['checked']){
                                $checkedfacilitiesString = "checked ='chechked'";
                            }
                            $facId = "fac_".$val['id'];
                        ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk"  id="<?=$facId?>" <?=$checkedfacilitiesString?> data-val="<?=str_replace(":", "|", $facilities)?>">
                                    <label for="<?=$facId?>" class="nav-heck">
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