<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['approvals']; ?><p id="clear_appr" style="<?php echo (!empty($appliedFilters['approvals']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="approvals">
                    <?php foreach ($filters['approvals'] as $value) {
                        $checkedString = "";
                        if(in_array($value['id'], $appliedFilters['approvals'])) {
                            $checkedString = "checked='checked'";
                        }
                        $valueId = $fieldAlias['approvals']."_".$value['id']; ?>
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