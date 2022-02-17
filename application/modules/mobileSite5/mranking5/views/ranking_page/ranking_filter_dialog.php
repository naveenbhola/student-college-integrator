<div class=" hid" data-role="dialog" data-transition="none" id="searchFilters" data-enhance="false"><!-- dialog--> 
<div class="applyFilter-container">
    <div class="clearfix content-section boxSizing-class">
        <div class="filter-head">
            <p class="applied-count">FILTER YOUR SEARCH</p><a href="javascript:void(0);" data-rel="back" class="filter-cross flRt">×</a>
        </div>
    </div>
    <?php if($filters['currentPageUrl'] != $filters['resetAllFilter']->getUrl() ) { ?>
        <div class="filter-selction-criteria boxSizing-class">
            <div class="reset__sec"><a class="clear-all" href="<?=$filters['resetAllFilter']->getUrl();?>">RESET FILTERS</a></div>
        </div>
    <?php } ?>
    <div class="filter-catalog">
        <div class="filter-section" id="searchFilter" style="height: 520px;">
            <ul class="filter-container" style="height: 100%;">
               <li data-tabid="Publisher"><a href="javascript:void(0);" class="filter-col">RANKED BY</a></li>
            <?php if(count($filters['specialization']) > 1) { ?>
                <li data-tabid="Specialization"><a href="javascript:void(0);" class="filter-col">SPECIALIZATION</a></li>
            <?php } ?>
                <li data-tabid="Location" class="active"><a href="javascript:void(0);" class="filter-col">LOCATION</a></li>
            <?php if(count($filters['exam']) > 1) { ?>
                <li data-tabid="Exam"><a href="javascript:void(0);" class="filter-col">EXAMS</a></li>
            <?php } ?>
            </ul>
            <div class="options" style="height: 100%;">
                <div id="srchfilterTabLocation" class="tbs loc-list">
                    <div class="search-field-box2 flLt"><i class="msprite search-sml-icn"></i>
                        <input type="text" class="loc-search-field" placeholder="Enter Location"><a id="searchCross" style="display: none;" onclick="var RPFilters = new RPFilterEvents(); RPFilters.searchList(true, event);" class="clg-rmv">×</a>
                    </div>
                    <ul>
                    	<?php foreach ($filters['state'] as $locationFilter) { ?>
	                        <li><a ga-attr="FILTER" class="<?=($locationFilter->getId() == $filters['selectedLocationFilter']->getId() ) ? 'checkmark' : ''?>" href="<?=$locationFilter->getUrl();?>"><?=$locationFilter->getName();?></a></li>
                    	<?php } ?>
                    	<?php foreach ($filters['city'] as $locationFilter) { ?>
	                        <li><a ga-attr="FILTER" class="<?=($locationFilter->getId() == $filters['selectedLocationFilter']->getId() && $locationFilter->getName() == $filters['selectedLocationFilter']->getName()) ? 'checkmark' : ''?>" href="<?=$locationFilter->getUrl();?>"><?=$locationFilter->getName();?></a></li>
                    	<?php } ?>
                    </ul>
                    <div class="no-rslt-cont" style="display: none;"></div>
                </div>
                <?php if(count($filters['specialization']) > 1) { ?>
	            	<div id="srchfilterTabSpecialization" class="tbs loc-list hid">
	                    <ul>
	                    	<?php foreach ($filters['specialization'] as $specializationFilter) { ?>
		                        <li><a ga-attr="FILTER" class="<?=($specializationFilter->isSelected()) ? 'checkmark' : ''?>" href="<?=$specializationFilter->getUrl();?>"><?=$specializationFilter->getName();?></a></li>
	                    	<?php } ?>
	                    </ul>
	                </div>
	            <?php } ?>
	            <?php if(count($filters['exam']) > 1) { ?>
                <div id="srchfilterTabExam" class="tbs loc-list hid">
                    <ul>
                    	<?php foreach ($filters['exam'] as $examFilter) { ?>
	                        <li><a ga-attr="FILTER" class="<?=($examFilter->isSelected()) ? 'checkmark' : ''?>" href="<?=$examFilter->getUrl();?>"><?=$examFilter->getName();?></a></li>
                    	<?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <div id="srchfilterTabPublisher" class="tbs loc-list hid">
                    <ul>
                    	<?php foreach ($filters['publisher'] as $publisherFilter) { ?>
	                        <li><a ga-attr="PUBLISHER" class="<?=($publisherFilter->getId() == $filters['selectedPublisherFilter']->getId()) ? 'checkmark' : ''?>" href="javascript:void(0);" publisher-name="<?=$publisherFilter->getName();?>" publisher-id="<?=$publisherFilter->getId();?>"><?=$publisherFilter->getName();?><span>&nbsp;(<?=$publisherFilter->getCount()?>)</span></a></li>
                    	<?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
</div>