<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['sub_spec']; ?><p id="clear_subspec" style="<?php echo (!empty($appliedFilters['sub_spec']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="sub_spec">
                    <?php foreach ($filters['sub_spec'] as $key => $value) {
                        //foreach ($typeValue as $value) {
                            $valueId = "sub_spec*".$value['alias']."_".$value['id'];
                            $checkedStringOuter = "";
                            if(($value['type'] == 'substream' && in_array($value['alias']."_".$value['id'], $appliedFilters['sub_spec'])) || ($value['type'] == 'specialization' && (in_array($value['alias']."_".$value['id'], $appliedFilters['sub_spec']) || in_array($fieldAlias['substream']."_any::".$fieldAlias['specialization']."_".$value['id'], $appliedFilters['sub_spec']))) ) {
                                $checkedStringOuter = "checked='checked'";
                            } ?>
                            <li>
                                <a <?php echo $product == 'Category' ? 'href='.$value['url']:''; ?> class="checkboxnav">
                                    <div class="nav-checkBx">
                                        <input type="checkbox" class="nav-inputChk"  id="<?php echo str_replace(array('*','::'), '_', $valueId); ?>" <?=$checkedStringOuter?> ele-type="outer" data-val="<?=$valueId?>">
                                        <label <?php echo $product != 'Category' ? "for=".str_replace(array('*','::'), '_', $valueId) : ''; ?>  class="nav-heck">
                                            <i class="icons ic_checkdisable1"></i><?=$value['name']?> (<?=$value['count']?>)
                                        </label>
                                    </div>
                                </a>
                                
                                <?php if(!empty($value['specialization'])) { ?>
                                    <ul class="inner-ul">
                                        <?php foreach ($value['specialization'] as $innerSpecValue) {
                                            $specId = "sub_spec*".$value['alias']."_".$value['id']."::".$fieldAlias['specialization']."_".$innerSpecValue['id'];
                                            $checkedString = "";
                                            if(!empty($checkedStringOuter) || in_array($value['alias']."_".$value['id']."::".$fieldAlias['specialization']."_".$innerSpecValue['id'], $appliedFilters['sub_spec']) || in_array($value['alias']."_any::".$fieldAlias['specialization']."_".$innerSpecValue['id'], $appliedFilters['sub_spec'])) {
                                                $checkedString = "checked='checked'";
                                            } ?>
                                            <li>
                                                <a <?php echo $product == 'Category' ? 'href='.$innerSpecValue['url']:''; ?> class="checkboxnav">
                                                    <div class="nav-checkBx">
                                                        <input type="checkbox" class="nav-inputChk"  id="<?php echo str_replace(array('*','::'), '_', $specId); ?>" <?=$checkedString?> ele-type="inner" data-val="<?=$specId?>">
                                                        <label <?php echo $product!='Category' ? "for=".str_replace(array('*','::'), '_', $specId) : ''; ?> class="nav-heck">
                                                            <i class="icons ic_checkdisable1"></i><?=$innerSpecValue['name']?> (<?=$innerSpecValue['count']?>)
                                                        </label>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php //}
                    } ?>
                </ul>
            </div>
        </div>
    </div>
</li>
