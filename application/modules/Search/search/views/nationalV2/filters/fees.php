<li><a class="activeLeftNav">Total Fees <span style="font-weight:lighter;">(&#8377;)</span><p id="clearfe" style="<?php echo (count($appliedFilters['fees']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="fees">
                <?php 
                foreach ($data as $feesId => $value) {
                        $checkedFeesString = "";
                        if($value['checked']){
                            $checkedFeesString = "checked ='chechked'";
                        }
                    ?>
                    <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" name="fees" <?=$checkedFeesString?> id="<?=$feesId?>" data-val="<?=$feesId?>">
                                    <label for="<?=$feesId?>" class="nav-heck">
                                        <i class="icons ic_checkdisable1"></i><?=$value['name']?> (<?=$value['count']?>)
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
