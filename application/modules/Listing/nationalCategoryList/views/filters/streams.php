<li><a class="activeLeftNav"><?php echo $filterBucketName['stream']; ?><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu streamFilter" data-section="stream">
                    <?php foreach ($filters['stream'] as $value) {
                        $checkedString = "";
                        if(in_array($value['id'], $appliedFilters['stream'])) {
                            $checkedString = "checked='checked'";
                        }
                        $valueId = $fieldAlias['stream']."_".$value['id']; ?>
                        <li>
                            <a <?php echo $product == 'Category' ? 'class="checkboxnav categoryLink" href='.$value['url']: 'class="checkboxnav"'; ?> data-val="<?=$valueId?>" data-item=1>
                                <div class="nav-checkBx">
                                    <label <?php echo $product == 'Category' ? "" : "for=$valueId"; ?> class="nav-heck" style="padding-left:0px">
                                        <?=$value['name']?> (<?=$value['count']?>)
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