<li><a class="activeLeftNav">Course Status<p id="clearaf" style="<?php echo (count($appliedFilters['affiliation']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="affiliation">
                    <?php
                     foreach ($data as $affiliationName => $val) {
                        $checkedAffiliationString = "";
                            if($val['checked']){
                                $checkedAffiliationString = "checked ='chechked'";
                            }
                            $afId = str_replace(" ", "-", $affiliationName);
                        ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" <?=$checkedAffiliationString;?> id="<?=$afId?>" data-val="<?=$affiliationName?>">
                                    <label for="<?=$afId?>" class="nav-heck">
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