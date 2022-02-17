<li>
    <a class="activeLeftNav"><?php echo $filterBucketName['base_course']; ?><p id="clear_bc" style="<?php echo (!empty($appliedFilters['base_course']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="base_course">
                    <?php foreach ($filters['base_course'] as $value) {
                        $checkedString = "";
                        if(in_array($value['id'], $appliedFilters['base_course'])) {
                            $checkedString = "checked='checked'";
                        }
                        $baseCourseId = $fieldAlias['base_course']."_".$value['id']; ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk"  id="<?=$baseCourseId?>" <?=$checkedString?> data-val="<?=$baseCourseId?>">
                                    <label for="<?=$baseCourseId?>" class="nav-heck">
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