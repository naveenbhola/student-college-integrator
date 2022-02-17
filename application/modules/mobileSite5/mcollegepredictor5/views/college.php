<?php
$filteredCollegeValues = array();
$filteredCollegeSelectedValues = array();
foreach($collegePredictorFilters['college'] as $key=>$value){
        $filteredCollegeValues[] = $value->getValue();
        $filteredCollegeSelectedValues[$value->getValue()] = $value->isSelected();
        if(!in_array($value->getGroupName(),$filteredCollegeSelectedGroupName)){
          $filteredCollegeSelectedGroupName[] = $value->getGroupName();
        }
        
}
foreach($defaultCollegePredictorFilters['college'] as $key=>$value){
        $filteredDefaultCollegeValues[] = $value->getValue();
        $filteredDefaultCollegeSelectedValues[$value->getValue()] = $value->isSelected();
        if(!in_array($value->getGroupName(),$filteredDefaultCollegeSelectedGroupName)){
          $filteredDefaultCollegeSelectedGroupName[] = $value->getGroupName();
        }
        
}
?>                          
<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
            <a data-rel="back" id="collegeFilterOverlayClose" onclick="" href="javascript:void(0);"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a> 
            <h3>Select Colleges</h3>
        </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
        <div class="search-option2" id="autoSuggestRefine">
           <div id="searchbox2">
               <span class="icon-search"></span>
               <input id="search" name="searchcollege" type="text" placeholder="Enter College Name" onkeyup="collegeAutoSuggestorForExams(this.value, 'college', 'collegeName');" autocomplete="off">
               <i class="icon-cl" onClick="clearCPAutoSuggestorForExams('collegeName','college');">&times;</i>
           </div>
        </div>
        
        <div class="content-child2 clearfix" style="padding:0;">
                <section id="loc-section">
                        <ul class="layer-list rnr-list">
                                <?php if(in_array('NIT',$filteredDefaultCollegeSelectedGroupName)){ ?>
                                <li  id="collegeNameLINIT">
                                        <label><input <?php if(!in_array('NIT',$filteredCollegeSelectedGroupName)){ echo 'disabled';} ?> <?php if(in_array('NIT',$filterInputData['collegeFilter'])){ echo 'checked';} ?> type="checkbox" value="NIT" name="collegeFilter[]" style="vertical-align:middle; float:left; width:18px""/><span  style="vertical-align:middle;margin-left:20px; display:block;<?php if(!in_array('NIT',$filteredCollegeSelectedGroupName)){ echo 'color:#cacaca;';} ?>" id="<?php echo 'collegeName-NIT';?>"> All NITs</span></label>
                                </li>
                                <?php } ?>
                                <?php if(in_array('IIIT',$filteredDefaultCollegeSelectedGroupName)){ ?>
                                <li id="collegeNameLIIIIT">
                                        <label><input <?php if(!in_array('IIIT',$filteredCollegeSelectedGroupName)){ echo 'disabled';} ?> <?php if(in_array('IIIT',$filterInputData['collegeFilter'])){ echo 'checked';} ?> type="checkbox" value="IIIT" name="collegeFilter[]" style="vertical-align:middle; float:left; width:18px""/><span  style="vertical-align:middle;margin-left:20px; display:block;<?php if(!in_array('IIIT',$filteredCollegeSelectedGroupName)){ echo 'color:#cacaca;';} ?>" id="<?php echo 'collegeName-IIIT';?>"> All IIITs</span></label>
                                </li>
                                <?php } ?>
                                <?php if(in_array('BIT',$filteredDefaultCollegeSelectedGroupName)){ ?>
                                <li id="collegeNameLIBIT">
                                        <label><input <?php if(!in_array('BIT',$filteredCollegeSelectedGroupName)){ echo 'disabled';} ?> <?php if(in_array('BIT',$filterInputData['collegeFilter'])){ echo 'checked';} ?> type="checkbox" value="BIT" name="collegeFilter[]" style="vertical-align:middle; float:left; width:18px""/><span  style="vertical-align:middle;margin-left:20px; display:block;<?php if(!in_array('BIT',$filteredCollegeSelectedGroupName)){ echo 'color:#cacaca;';} ?>" id="<?php echo 'collegeName-BIT';?>"> All BITS</span></label>
                                </li>
                                <?php }
                                $count = count($defaultCollegePredictorFilters['college']);
                                $i=0;
                                foreach($defaultCollegePredictorFilters['college'] as $key=>$value){ ?>
                                <li  id="collegeNameLI<?php echo $value->getValue();?>" <?php if($i==$count-1){?> style="padding-bottom: 60px;"<?php }?>>
                                        <label><input <?php  if(!in_array($value->getValue(),$filteredCollegeValues)){ echo 'disabled';} ?>  <?php if(in_array($value->getValue(),$filterInputData['collegeFilter'])){ echo 'checked';} ?> type="checkbox" value="<?php echo $value->getValue();?>" name="collegeFilter[]" style="vertical-align:middle; float:left; width:18px"/><span  style="vertical-align:middle;margin-left:20px; display:block;<?php  if(!in_array($value->getValue(),$filteredCollegeValues)){ echo 'color:#cacaca;';} ?>" id="collegeName-<?php echo $value->getValue();?>"> <?php echo $value->getName();?></span></label>
                                </li>
                                <?php $i++;} ?>
                               <li href="javascript:void(0);" id="not-found-college-list" style="display:none;">
                                        <label><span>No result found.</span></label>
                                </li>
                        </ul>
                </section>
        </div>
</section>
<a onclick="var filterStr = getFilterString();closeLayer('college');showSelected('college');callAllFilters(filterStr);trackEventByGAMobile('HTML5_JEECollegePredictor_Filter_Colleges');" href="javascript:void(0);" style="position:fixed;left:0px;bottom:0px;width:100%" class="refine-btn" id="examButton"><span class="icon-done"><i></i></span> Done</a>