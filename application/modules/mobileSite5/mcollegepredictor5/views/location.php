<?php
$filteredLocationValues = array();
$filteredLocationSelectedValues = array();
foreach($collegePredictorFilters['location'] as $key=>$value){
        $filteredLocationValues[] = $value->getValue();
        $filteredLocationSelectedValues[$value->getValue()] = $value->isSelected();
}
?>
<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
            <a data-rel="back" id="locationFilterOverlayClose" onclick="" href="javascript:void(0);"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a> 
            <h3>Select Location</h3>
        </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
        <div class="search-option2" id="autoSuggestRefine">
           <div id="searchbox2">
               <span class="icon-search"></span>
               <input id="search" name="searchlocation"  type="text" placeholder="Enter Location Name" onkeyup="collegeAutoSuggestorForExams(this.value, 'location', 'locationName');" autocomplete="off">
               <i class="icon-cl" onClick="clearCPAutoSuggestorForExams('locationName','location');">&times;</i>
           </div>
        </div>
        
        <div class="content-child2 clearfix" style="padding:0;">
                <section id="loc-section">
                        <ul class="layer-list rnr-list">
                                <?php
                                if($inputData['rankType']=='Home' || $inputData['rankType']=='StateLevel' || $inputData['rankType']=='HomeUniversity' || $inputData['rankType']=='HyderabadKarnatakaQuota'){
                                        $locationType = 'city';
                                }else{
                                        $locationType = 'state';
                                }
                                $count = count($defaultCollegePredictorFilters['location']);
                                $i=0;
                                foreach($defaultCollegePredictorFilters['location'] as $key=>$value){ ?>
                                <li id="locationNameLI<?php echo $value->getValue();?>" <?php if($i==$count-1){?> style="padding-bottom: 60px;"<?php }?>>
                                        <label><input <?php  if(!in_array($value->getValue(),$filteredLocationValues)){ echo 'disabled';} ?>  <?php if(in_array($value->getValue(),$filterInputData['locationFilter'][$locationType])){ echo 'checked';} ?> type="checkbox" value="<?php echo $value->getValue();?>" name="locationFilter[]" style="vertical-align:middle;"/><span  style="vertical-align:middle;<?php  if(!in_array($value->getValue(),$filteredLocationValues)){ echo 'color:#cacaca;';} ?>" id="locationName-<?php echo $value->getValue();?>"> <?php echo $value->getName();?></span></label>
                                </li>
                                <?php
                                $i++;
                                } ?>
                                <li href="javascript:void(0);" id="not-found-location-list" style="display:none;">
                                        <label><span>No result found.</span></label>
                                </li>
                        </ul>
                </section>
        </div>
</section>
<a onclick="var filterStr = getFilterString();closeLayer('location');showSelected('location');callAllFilters(filterStr);trackEventByGAMobile('HTML5_JEECollegePredictor_Filter_Location');" href="javascript:void(0);" style="position:fixed;left:0px;bottom:0px;width:100%" class="refine-btn" id="examButton"><span class="icon-done"><i></i></span> Done</a>