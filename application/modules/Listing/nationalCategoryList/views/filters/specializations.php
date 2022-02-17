<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['specialization']; ?><p id="clear_sp" style="<?php echo (!empty($appliedFilters['specialization'])) ? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="specialization">
                    <?php foreach ($filters['specialization'] as $value) {
                        $checkedString = "";
                        if(in_array($value['id'], $appliedFilters['specialization'])) {
                            $checkedString = "checked='checked'";
                        }
                        $specId = $fieldAlias['specialization']."_".$value['id']; ?>
                        <li>
                            <a <?php echo $product == 'Category' ? 'href='.$value['url']:''; ?> class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk"  id="<?=$specId?>" <?=$checkedString?> data-val="<?=$specId?>">
                                    <label <?php echo $product != 'Category' ? "for=$specId" : ''; ?> class="nav-heck">
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