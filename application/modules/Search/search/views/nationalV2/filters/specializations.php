<li>
    <?php
    $this->load->config("search/SearchPageConfig");
    $nameMappingConfig = $this->config->item('SPECIALIZATION_FILTERNAME_MAPPING');
    $specialisation = 'Course';
    foreach($nameMappingConfig as $key => $arr){
        if(in_array($subCatId, $arr)){
            $specialisation = $key;
            break;
        }
    }
    ?>
    <a class="activeLeftNav"><?php echo $specialisation; ?><p id="clearsp" style="<?php echo (count($appliedFilters['course']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="specialisation">
                    <?php
                     foreach ($data as $specializationId => $spec) {
                            $checkedSpecString = "";
                            if($spec['checked']){
                                $checkedSpecString = "checked ='chechked'";
                            }
                        ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" <?=$checkedSpecString?> id="<?=$specializationId?>" data-val="<?=$specializationId?>">
                                    <label for="<?=$specializationId?>" class="nav-heck">
                                        <i class="icons ic_checkdisable1"></i><?=$spec['name']?> (<?=$spec['count']?>)
                                    </label>
                                </div>
                            </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </div>
</li>