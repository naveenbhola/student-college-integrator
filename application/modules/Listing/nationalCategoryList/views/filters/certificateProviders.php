<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['certificate_provider']; ?><p id="clear_cp" style="<?php echo (!empty($appliedFilters['certificate_provider']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="certificateProvider">
                    <?php foreach ($filters['certificate_provider'] as $value) {
                        $checkedString = "";
                        if(in_array($value['id'], $appliedFilters['certificate_provider'])) {
                            $checkedString = "checked='checked'";
                        }
                        $valueId = $fieldAlias['certificate_provider']."_".$value['id']; ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk"  id="<?=$valueId?>" <?=$checkedString?> data-val="<?=$valueId?>">
                                    <label for="<?=$valueId?>" class="nav-heck">
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