<?php

if ($defaultView != 1 && (!empty($branchInformation))) {
?>
 <div class="filter-col" >
<?php
    if ($tab == 1) {
        if (count($defaultCollegePredictorFilters['college']) <= 0) {
            $filterSeparaterLast['college'] = '';
            if (count($defaultCollegePredictorFilters['location']) <= 0 && count($defaultCollegePredictorFilters['branch']) <= 0) {
                $filterSeparaterLast['location'] = '';
                $filterSeparaterLast['branch']   = '';
            } else if (count($defaultCollegePredictorFilters['location']) <= 0 && count($defaultCollegePredictorFilters['branch']) >= 0) {
                $filterSeparaterLast['location'] = '';
                $filterSeparaterLast['branch']   = 'last';
            } else if (count($defaultCollegePredictorFilters['location']) >= 0 && count($defaultCollegePredictorFilters['branch']) <= 0) {
                $filterSeparaterLast['location'] = 'last';
                $filterSeparaterLast['branch']   = '';
            } else {
                $filterSeparaterLast['location'] = 'last';
                $filterSeparaterLast['branch']   = '';
            }
        } else {
            $filterSeparaterLast['college'] = 'last';
        }
        
        if ($inputData['round'] == 'all' && $filterSeparaterLast['location'] == '' && $filterSeparaterLast['branch'] == '' && $filterSeparaterLast['college'] == '') {
            $filterSeparaterLast['round'] = 'last';
        }
    }
    
?>
                        <div class="filter-child-wrap customInputs">
                       <label class="nav_main_head">Filters <a href="javascript:void(0);" class="flRt font-12" onClick="trackEventByGA('FilterClick','CP_RESET_ALL_CLICK');resetFilters('<?php echo $tab; ?>','','rank','<?php echo $sortOrder; ?>','filter');">Reset All</a></label>  
                <?php
    if ($inputData['tabType'] != 'branch' && count($defaultCollegePredictorFilters['branch']) > 0) {
        $filteredBranchValues = array();
        foreach ($collegePredictorFilters['branch'] as $key => $value) {
            $filteredBranchValues[] = $value->getValue();
        }
        
?>
                           <div class="filter-categories <?php
        echo $filterSeparaterLast['branch'];
?>">
                                <h5><i class="branch-icon"></i>Branch</h5>
                                <div class="filter-search">
                                    <i class="filter-search-icon"></i>
                                    <input class='filter-input' style="width: 100%" type="text" onblur="focusoutSection(this);" onfocus="focusSection(this);"  onkeyup="filterList(this);" id="branch" placeholder="Search Branch">
                                </div>
                    <div id="branchContainer">
                                
                                        
                                             <ul class="filter-list">
                            <?php
        foreach ($defaultCollegePredictorFilters['branch'] as $key => $value) {
            $style = '';
?>
                           
                                               <li>
                                                    <input id="<?php
            echo $value->getValue();
?>" <?php
            // if (!in_array($value->getValue(), $filteredBranchValues)) {
            //     echo 'disabled';
            // }
?> <?php
            if (in_array($value->getValue(), $appliedFilters['branchFilter'])) {
                echo 'checked';
            }
?> type='checkbox' value="<?php
            echo $value->getValue();
?>" onClick="gaTrackEventCustom(GA_currentPage,'CP_BRANCH_FILTER_CLICK','Response');searchData('<?php
            echo $tab;
?>','','rank','<?php
            echo $sortOrder;
?>','filter',this);" name='branchFilter[]' displayName="<?php
            echo $value->getName();
?>" filterType="branch" filterValue="<?php
            echo $value->getValue();
?>"></input>
                                                    <label for="<?php
            echo $value->getValue();
?>" <?php
            echo $style;
?>>
                                                        <span class="pred-spriteSVG"></span><p><?php
            echo $value->getName();
?></p>
                                                    </label>
                                                </li>
                            <?php
        }
        
?>
                                            </ul>
                                
                    </div>
                    <p id="branch-norslt" style="display:none;">
                    No result found for this branch
                        </p>
                           </div>
               <?php
    }
?>
              <?php
    if (count($defaultCollegePredictorFilters['location']) > 0) {
        $filteredLocationValues = array();
        foreach ($collegePredictorFilters['location'] as $key => $value) {
            $filteredLocationValues[] = $value->getValue();
        }
        
?>
                           <div class="filter-categories <?php
        echo $filterSeparaterLast['location'];
?>">
                                <h5><i class="loc-icon"></i>Location</h5>
                                <div class="filter-search">
                                    <i class="filter-search-icon"></i>
                                    <input class='filter-input' style="width: 100%" type="text" onblur="focusoutSection(this);" onfocus="focusSection(this);" onkeyup="filterList(this);" id="location" placeholder="Search Location">
                                </div>
                <div id="locationContainer">
                                             <ul class="filter-list">
                        <?php
        if ($inputData['rankType'] == 'Home' || $inputData['rankType'] == 'StateLevel' || $inputData['rankType'] == 'HomeUniversity' || $inputData['rankType'] == 'HyderabadKarnatakaQuota' || strtolower($inputData['examName']) == 'mhcet' || strtolower($inputData['examName']) == 'ptu' || strtolower($inputData['examName']) == 'mppet' || strtolower($inputData['examName']) == 'upsee'  || strtolower($inputData['examName']) == 'wbjee'|| strtolower($inputData['examName']) == 'kcet') {
            $locationType = 'city';
        } else {
            $locationType = 'state';
        }
        
        foreach ($defaultCollegePredictorFilters['location'] as $key => $value) {
            $style = '';
?>
                       <?php //if( in_array($value->getValue(),$filteredLocationValues) && in_array($value->getValue(),$appliedFilters['locationFilter'][$locationType])){ $style = "style='font-weight:bold'";}
            
?>
                                               <li>
                                                    <input id="<?php
            echo $value->getValue();
?>" <?php
            // if (!in_array($value->getValue(), $filteredLocationValues)) {
            //     echo 'disabled';
            // }
?>  <?php
            if (in_array($value->getValue(), $appliedFilters['locationFilter'][$locationType])) {
                echo 'checked';
            }
?> type='checkbox' value="<?php
            echo $value->getValue();
?>" onClick="gaTrackEventCustom(GA_currentPage,'CP_LOCATION_FILTER_CLICK','Response');searchData('<?php
            echo $tab;
?>','','rank','<?php
            echo $sortOrder;
?>','filter',this);" name='locationFilter[]' filterType="location" filterValue="<?php
            echo $value->getValue();
?>"></input>
                                                    <label for="<?php
            echo $value->getValue();
?>" <?php
            echo $style;
?>>
                                                        <span class="pred-spriteSVG"></span><p><?php
            echo $value->getName();
?></p>
                                                    </label>
                                                </li>
                                               <?php
        }
?>
                                            </ul>
                </div>
                        <p id='location-norslt' style="display:none;">
                            No result found for this location
                        </p>
                           </div>
            <?php
    }
?>
           <?php
    if ($inputData['tabType'] != 'college' && count($defaultCollegePredictorFilters['college']) > 0) {
?>
           <?php
        $filteredCollegeValues = array();
        
        // $collegeNameToGroupNameMapping = array('NIT'=>array('collegeName'=>'NITs',''));
        
        foreach ($collegePredictorFilters['college'] as $key => $value) {
            $filteredCollegeValues[] = $value->getValue();
            if (!in_array($value->getGroupName(), $filteredCollegeSelectedGroupName)) {
                $filteredCollegeSelectedGroupName[] = $value->getGroupName();
            }
        }
        
        foreach ($defaultCollegePredictorFilters['college'] as $key => $value) {
            if (!in_array($value->getGroupName(), $filteredDefaultCollegeSelectedGroupName)) {
                $filteredDefaultCollegeSelectedGroupName[] = $value->getGroupName();
            }
        }
        
?>

                            <div class="filter-categories <?php
        echo $filterSeparaterLast['college'];
?>">
                                <h5><i class="collg-icon"></i>Colleges</h5>
                                <div class="filter-search">
                                    <i class="filter-search-icon"></i>
                                    <input class='filter-input' style="width: 100%" type="text" onfocus="focusSection(this);" onblur="focusoutSection(this);" onkeyup="filterList(this);" id="college" placeholder="Search Colleges">
                                </div>
                <div id="collegeContainer">
                                             <ul class="filter-list">
                        <?php
        foreach ($defaultCollegePredictorFilters['college'] as $key => $value) {
            $style = '';
?>
                       <?php //if( in_array($value->getValue(),$filteredCollegeValues) && in_array($value->getValue(),$appliedFilters['collegeFilter'])){ $style = "style='font-weight:bold'";}
            
?>
                                               <li id="institute_<?php
            echo $value->getValue();
?>">
                                                    <input id="inst_<?php
            echo $value->getValue();
?>" <?php
            // if (!in_array($value->getValue(), $filteredCollegeValues)) {
            //     echo 'disabled';
            // }
?>  <?php
            if (in_array($value->getValue(), $appliedFilters['collegeFilter'])) {
                echo 'checked';
            }
?> type='checkbox' value="<?php
            echo $value->getValue();
?>" onClick="gaTrackEventCustom(GA_currentPage,'CP_COLLEGE_FILTER_CLICK','Response');searchData('<?php
            echo $tab;
?>','','rank','<?php
            echo $sortOrder;
?>','filter',this);" name='collegeFilter[]' filterType="college" filterValue="<?php
            echo $value->getValue();
?>"></input>
                                                    <label for="inst_<?php
            echo $value->getValue();
?>" <?php
            echo $style;
?>>
                                                        <span class="pred-spriteSVG"></span><p><?php
            echo $value->getName();
?></p>
                                                    </label>
                                                </li>
                                                <?php
        }
?>
                                            </ul>
                </div>
                <p id="college-norslt" style="display:none;">
                    No result found for this institute
                </p>
                </div>
        <?php
    }
?>
   </div>
<?php
    
    // }
    
?>
</div>
<script>
var instituteJsonFilter = <?php
    echo json_encode($institutesFilter, true);
?>;
</script>
<?php
}

?>