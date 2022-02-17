<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['review']; ?><p id="clear_fees" style="<?php echo (!empty($appliedFilters['review']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="fees">
                    <?php foreach ($filters['review'] as $value) {
                        $valueId = $fieldAlias['review']."_".$value['id'];
                        $checkedString = "";
                        if(in_array($value['id'], $appliedFilters['review'])) {
                            $checkedString = "checked='checked'";
                        } ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" name="review" <?=$checkedString?> id="<?=$valueId?>" data-val="<?=$valueId?>">
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