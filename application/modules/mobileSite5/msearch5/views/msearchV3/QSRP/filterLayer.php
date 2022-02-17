<div class="hid" data-role="dialog" data-transition="none" id="searchFilters" data-enhance="false"><!-- dialog--> 
    <div class="applyFilter-container">
        <div class="clearfix content-section boxSizing-class">
            <div class="filter-head">
                <p class="applied-count">FILTER YOUR SEARCH</p><a href="javascript:void(0);" data-rel="back" class="filter-cross flRt">×</a>
            </div>
        </div>

        <div class="filter-catalog">
            <div class="filter-section" style="height: 520px;">
                <ul class="filter-container" style="height: 100%;">
                   <li data-tabid="filterByTime" class="active"><a href="javascript:void(0);" class="filter-col">BY TIME</a></li>
                </ul>
                <div class="options" style="height: 100%;">
                    <div class="tbs loc-list overide_div">
                        <ul>
                            <?php if(empty($tupleData['activeFilter'])) { $class = 'checkmark';  $href="javascript:void(0);"; } else { $class = ''; $href = $tupleData['no_filter']['url']; } ?>
                            <li><a val="all" currentTab="<?php echo $currentTab; ?>" href="<?php echo $href; ?>" class="filterByVal <?php echo $class; ?>">All Time</a></li>

                            <?php foreach ($tupleData['filters'] as $filter) { ?>
                                <?php if($tupleData['activeFilter'] == $filter['name']) {
                                    $class = 'checkmark';
                                    $href = "javascript:void(0);";
                                } else {
                                    $class = '';
                                    $href = $filter['url'];
                                } ?>
                                <?php switch ($filter['name']) {
                                    case 'week': ?>
                                        <li><a val="week" currentTab="<?php echo $currentTab; ?>" class="filterByVal <?php echo $class; ?>" href="<?php echo $href; ?>">Past Week</a></li>
                                        <?php break;
                                    
                                    case 'month': ?>
                                        <li><a val="month" currentTab="<?php echo $currentTab; ?>" class="filterByVal <?php echo $class; ?>" href="<?php echo $href; ?>">Past Month</a></li>
                                        <?php break;

                                    case 'year': ?>
                                        <li><a val="year" currentTab="<?php echo $currentTab; ?>" class="filterByVal <?php echo $class; ?>" href="<?php echo $href; ?>">Past Year</a></li>
                                        <?php break;
                                } ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>