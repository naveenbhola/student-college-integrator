<?php
if((!empty($defaultCollegePredictorFilters['branch']) &&
                count($defaultCollegePredictorFilters['branch'])>1 &&
                trim($inputData['tabType'])!='branch')  ||
(!empty($defaultCollegePredictorFilters['location']) &&
                count($defaultCollegePredictorFilters['location'])>1 &&
                trim($inputData['tabType'])!='location')||
(!empty($defaultCollegePredictorFilters['college']) &&
                count($defaultCollegePredictorFilters['college'])>1 &&
                trim($inputData['tabType'])!='college')){
                 }else{
                        echo "NOFILTER"; return;
                 } 
?>




<div class="applyFilter-container">
    <div class="clearfix content-section boxSizing-class">
        <div class="filter-head">
            <p class="applied-count">FILTER YOUR SEARCH</p><a href="javascript:void(0);" data-rel="back" class="filter-cross flRt">×</a>
        </div>
    </div>
    <div class="filter-selction-criteria boxSizing-class">
        <div class="reset__sec clear-filters"><a class="clear-all" href="javascript:void(0);" >RESET FILTERS</a></div>
    </div>
    <div class="filter-catalog">
        <div class="filter-section" id="searchFilter" style="height: 520px;">
            <ul class="filter-container" style="height: 100%;">
            <?php if(count($defaultCollegePredictorFilters['branch'])>0){ ?>  
               <li data-tabid="Branch" class="active"><a href="javascript:void(0);" class="filter-col">Branch</a></li>
            <?php } if(count($defaultCollegePredictorFilters['location'])>0) { ?>
                <li data-tabid="Location"><a href="javascript:void(0);" class="filter-col">Location</a></li>
            <?php } if(count($defaultCollegePredictorFilters['college'])>0) { ?>
                <li data-tabid="College" ><a href="javascript:void(0);" class="filter-col">College</a></li>
            <?php } ?>
            </ul>
        <div class="options" style="height: 100%;">
            <?php if(count($defaultCollegePredictorFilters['branch'])>0){ ?>  
                <div id="srchfilterTabBranch" class="tbs loc-list">
                    <?php $this->load->view('V2/branch1');?>       
                </div>
            <?php } if(count($defaultCollegePredictorFilters['location'])>0) { ?>
                    <div id="srchfilterTabLocation" class="tbs loc-list hid">
                        <?php $this->load->view('V2/location1');?>   
                    </div>
            <?php } if(count($defaultCollegePredictorFilters['college'])>0) { ?>
                <div id="srchfilterTabCollege" class="tbs loc-list hid">
                    <?php $this->load->view('V2/college1');?>
                </div>
            <?php } ?>
        </div>    
        </div>
        <a href="#" id="applyFilter" class="green-btn filter-btn">APPLY ALL FILTERS</a>
    </div>
</div>