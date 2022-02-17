<?php
$showFilters = false;
// determine whether to show filters or not
if($feeRangesForFilter|| $examsForFilter || $examsScoreForFilter || $specializationForFilters || $moreOptionsForFilters || (($categoryPageRequest->isAllCountryPage() && $countriesForFilters) || ($citiesForFilters || $statesForFilters)))
{
	$showFilters = true;
}
?>
<div id="filterContainer" data-role="page" data-enchance="false">
<form id="formCategoryPageFilter">
<div class="wrapper" data-enchance="false">
    	<div class="header-unfixed">
            <div class="layer-header">
                <a data-transition="slide" data-rel="back" href="#filterContainer" class="back-box"><i class="sprite back-icn"></i></a>
                <p style="text-align:center">Filter</p>
                <a href="javascript:void(0);" onclick="resetAllFilters();" class="clear-filter">Clear All</a>
            </div>
        
        	<section class="filter-row2">
            	<p>Selected Filters(<span id="totalFilterCount"><?= ($appliedFilterCount >0)?$appliedFilterCount:"None"?></span>)</p>
            </section>
        </div>
        
        <section class="explore-filters">
        	<nav>
            	<ul>
                    <?php if($examsForFilter) {?>
                    <li class="tablink active" id="examTabLink" onclick="selectFilterTab('examTab');">
                        <a href="javascript:void(0)">Exam<br>Accepted</a>
                        <span class="sprite pointer"></span>
                        <?php if($individualFilterCount["exam"]>0){?>
                        <div class="selected-filter">Selected <strong class="selectFilterCount"><?= $individualFilterCount["exam"];?></strong></div>
                        <?php }else{?>
                        <div class="selected-filter" style="display: none;">Selected <strong class="selectFilterCount"></strong></div>
                        <?php   }?>
                    </li>
                    <?php } 
                    if($feeRangesForFilter) { ?>
        		    <li class="tablink" id="feeTabLink" onclick="selectFilterTab('feeTab');">
                    	<a href="javascript:void(0)">1st Year Total<br>Fees (INR)</a>
                        <span class="sprite pointer"></span>
            			<?php if($individualFilterCount["fees"]>0){?>
            			<div class="selected-filter">Selected <strong class="selectFilterCount">1</strong></div>
            			<?php }else{?>
            			<div class="selected-filter" style="display: none;">Selected <strong class="selectFilterCount"></strong></div>
            			<?php 	}?>
                    </li>
		          <?php } 
		          if($specializationForFilters) {?>
                    <li class="tablink" id="specializationTabLink" onclick="selectFilterTab('specializationTab');">
                    	<a href="javascript:void(0)">Course<br>Specialization</a>
                        <span class="sprite pointer"></span>
            			<?php if($individualFilterCount["specialization"]>0){?>
            			<div class="selected-filter">Selected <strong class="selectFilterCount"><?= $individualFilterCount["specialization"];?></strong></div>
            			<?php }else{?>
            			<div class="selected-filter" style="display: none;">Selected <strong class="selectFilterCount"></strong></div>
            			<?php 	}?>
                    </li>
        		    <?php } 
        		    if(($categoryPageRequest->isAllCountryPage() && $countriesForFilters) || ($citiesForFilters || $statesForFilters)) { ?>
                        <li class="tablink" id="locationTabLink" onclick="selectFilterTab('locationTab');">
                        	<?php if($categoryPageRequest->isAllCountryPage() && $countriesForFilters) {
                        		$text = 'Countries';
                        	} else {
                        		$text = 'Locations';
                        	} ?>
                        	<a href="javascript:void(0)"><?= $text;?></a>
                                        <span class="sprite pointer"></span>
                        	<?php
                        	if($individualFilterCount["location"]>0){?>
                        	<div class="selected-filter">Selected <strong class="selectFilterCount"><?= $individualFilterCount["location"]?></strong></div>
                        	<?php }else{?>
                        	<div class="selected-filter" style="display: none;">Selected <strong class="selectFilterCount"></strong></div>
                        	<?php 	}?>
                        </li>
            		    <?php } 
                    if($moreOptionsForFilters) {?>
                        <li class="tablink" id="moreOptionTabLink" onclick="selectFilterTab('moreOptionTab');">
                        	<a href="javascript:void(0)" style="padding-top:29px">More Options</a>
                            <span class="sprite pointer"></span>
                			<?php if($individualFilterCount["moreoption"]>0){?>
                			<div class="selected-filter">Selected <strong class="selectFilterCount"><?= $individualFilterCount["moreoption"];?></strong></div>
                			<?php }else{?>
                			<div class="selected-filter" style="display: none;">Selected <strong class="selectFilterCount"></strong></div>
                			<?php 	}?>
                        </li>
        		    <?php } ?>
                </ul>
            </nav>
            <?php $this->load->view('widgets/examFilter');?>
            <?php $this->load->view('widgets/feeFilter');?>
            <?php $this->load->view('widgets/specializationFilter');?>
            <?php $this->load->view('widgets/locationFilter');?>
            <?php $this->load->view('widgets/moreOptionFilter');?>
        </section>
        <div class="apply-btn-row" style="position: fixed; width:100%; bottom: 0;"><a onclick="getFilteredResults();" href="javascript:void(0);" class="btn btn-default btn-primary btn-full">Apply all filters</a></div>    
    </div>
</form>	
</div>