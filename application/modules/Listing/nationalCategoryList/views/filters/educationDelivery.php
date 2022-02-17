<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['et_dm']; ?><p id="clear_etdm" style="<?php echo (!empty($appliedFilters['et_dm']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="et_dm">
                    <?php foreach ($filters['et_dm'] as $value) {
                        $type = $value['type'];
                        $valueId = "et_dm*".$fieldAlias[$type]."_".$value['id'];
                        if($type == 'delivery_method') {
                            //if($value['id'] == 33) { //in case of dm - online, need to apply filter for et - part time
                                $valueId = "et_dm*".$fieldAlias['education_type']."_21::".$fieldAlias[$type]."_".$value['id'];
                            //}
                        }
                        $checkedString = "";
                        if(($value['type'] == 'education_type' && in_array($fieldAlias[$type]."_".$value['id'], $appliedFilters['et_dm'])) || ($type == 'delivery_method' && (in_array($fieldAlias['education_type']."_21::".$fieldAlias[$type]."_".$value['id'], $appliedFilters['et_dm']) || in_array($fieldAlias['education_type']."_21::".$fieldAlias[$type]."_any", $appliedFilters['et_dm'])) )) {
                            $checkedString = "checked='checked'";
                        } ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk"  id="<?php echo str_replace(array('*','::'), '_', $valueId); ?>" <?=$checkedString?> data-val="<?=$valueId?>">
                                    <label for="<?php echo str_replace(array('*','::'), '_', $valueId); ?>" class="nav-heck">
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