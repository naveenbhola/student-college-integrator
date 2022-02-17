<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['exam']; ?><p id="clear_ex" style="<?php echo (!empty($appliedFilters['exam']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="exam">
                    <?php foreach ($filters['exam'] as $value) {
                        $valueId = $fieldAlias['exam']."_".$value['id'];
                        $checkedString = "";
                        if(in_array($value['id'], $appliedFilters['exam'])) {
                            $checkedString = "checked='checked'";
                        } ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" <?=$checkedString;?> data-val="<?=$valueId?>" id="<?=$valueId?>" />
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