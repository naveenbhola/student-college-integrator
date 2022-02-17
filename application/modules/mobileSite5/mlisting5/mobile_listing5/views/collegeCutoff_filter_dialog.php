<div class=" hid flt-DuCutoff" data-role="dialog" data-transition="none" id="searchFilters" data-enhance="false"><!-- dialog--> 
<div class="applyFilter-container">
    <div class="clearfix content-section boxSizing-class">
        <div class="filter-head">
            <p class="applied-count">FILTER YOUR SEARCH</p><a href="javascript:void(0);" data-rel="back" class="filter-cross flRt">×</a>
        </div>
    </div>
    <div class="filter-catalog">
        <div class="filter-section" id="searchFilter" >
            <ul class="filter-container" style="height: 100%;">
                <li data-tabid="Colleges"><a href="javascript:void(0);" class="filter-col">Colleges</a></li>
                <li data-tabid="Category" class='active' id ='fltByCatgry'><a href="javascript:void(0);" class="filter-col">Category</a></li>
                <?php if(count($filterData['specializationFilters'])>0){?>
                    <li data-tabid="Specializations"><a href="javascript:void(0);" class="filter-col">Specialization</a></li>
                <?php } ?>
                <li data-tabid="Cutoffs"><a href="javascript:void(0);" class="filter-col">CUT-OFF</a></li>

            </ul>
            <div class="options" style="height: 84%;">
                    <div id="srchfilterTabColleges" class="tbs loc-list hid">
                        <ul>
                            <?php foreach ($filterData['collegeFilters'] as $collegeData) { ?>
                                <li><a  <?php if($collegeData['id']==$instituteId) { echo 'class =checkmark'; }?>  href="<?=$collegeData['url'];?>"><?= $collegeData['name']; ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                <div id="srchfilterTabCategory" class="tbs loc-list ">
                    <ul>
                    	<?php foreach ($filterData['categoryFilters'] as $name => $categoryUrl) { ?>
	                        <li><a <?php if(strtolower($name)==strtolower($categoryName)) { echo 'class =checkmark';}?> href="<?=$categoryUrl ;?>"><?=$name;?></a></li>
                    	<?php } ?>
                    </ul>
                    <div class="no-rslt-cont" style="display: none;"></div>
                </div>
                <?php if(count($filterData['specializationFilters'])>0){?>
                    <div id="srchfilterTabSpecializations" class="tbs loc-list hid">
                        <ul>
                            <li><a href="javascript:void(0)" class ='spFlt <?php if(!($specializationId>0)) { echo 'checkmark'; }?>' sp_id = null>All</a></li>
                             <?php foreach ($filterData['specializationFilters'] as $value) {
                                if($value['specialization_id']){

                            ?> 
                                <li><a href="javascript:void(0)" class ='spFlt <?php if($value['specialization_id']==$specializationId) { echo 'checkmark'; }?>' sp_id = <?= $value['specialization_id']; ?>><?php echo $value['name'];?></a></li>
                            <?php }
                                }
                            ?>
                        </ul>
                    </div>
                <?php } ?>
                <div id="srchfilterTabCutoffs" class="tbs loc-list hid">
                    <ul>
                         <?php foreach ($filterData['cutoffFiltersCompleteList'] as $key => $value) {
                            if($filterData['bucketToShow'][$key]){
                        ?> 
                            <li><a href="javascript:void(0)" class ='filterCutoff <?php if($key ==$cutoffRange) { echo 'checkmark'; }?>' value = <?= $key; ?>><?php echo ($key ==0) ? $value: $value[0].'%-'.$value[1].'%';?></a></li>
                        <?php }
                            }
                        ?>
                    </ul>
                </div>                
            </div>
        </div>
    </div>
</div>
</div>