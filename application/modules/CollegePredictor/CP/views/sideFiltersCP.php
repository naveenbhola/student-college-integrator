<?php
if($defaultView!=1 && (!empty($branchInformation)  || $_COOKIE['collegepredictor_showFilters_'.$examinationName]=='display')){
  ?>
  <div class="filter-col" >
<!--<div id="loadingFilterImage" style="display:none;">
  <img id="loading-image-filter" src="/public/images/gif-load.gif" alt="Loading..." />
</div>-->
<?php
if($tab==1){ 
  if(count($defaultCollegePredictorFilters['college'])<=1){
  $filterSeparaterLast['college'] = '';
  if(count($defaultCollegePredictorFilters['location'])<=1 && count($defaultCollegePredictorFilters['branch'])<=1){
    $filterSeparaterLast['location'] = '';$filterSeparaterLast['branch'] = '';  
  }else if(count($defaultCollegePredictorFilters['location'])<=1 && count($defaultCollegePredictorFilters['branch'])>=1){
    $filterSeparaterLast['location'] = '';$filterSeparaterLast['branch'] = 'last';  
  }else if(count($defaultCollegePredictorFilters['location'])>=1 && count($defaultCollegePredictorFilters['branch'])<=1){
    $filterSeparaterLast['location'] = 'last';$filterSeparaterLast['branch'] = '';
  }else{
    $filterSeparaterLast['location'] = 'last';$filterSeparaterLast['branch'] = '';
  }
  }else{
  $filterSeparaterLast['college'] = 'last';
  }
  if($inputData['round']=='all' && $filterSeparaterLast['location']=='' && $filterSeparaterLast['branch']=='' && $filterSeparaterLast['college'] == ''){
    $filterSeparaterLast['round'] = 'last';
  }
}
if($tab==2){ 
  if(count($defaultCollegePredictorFilters['location'])<=1){
  $filterSeparaterLast['location'] = '';
  if(count($defaultCollegePredictorFilters['branch'])<=1){
    $filterSeparaterLast['branch'] = '';  
  }else{
    $filterSeparaterLast['branch'] = 'last';
  }
  }else{
    $filterSeparaterLast['location'] = 'last';
  }
  if($inputData['round']=='all' && $filterSeparaterLast['location']=='' && $filterSeparaterLast['branch']==''){
    $filterSeparaterLast['round'] = 'last';
  }
}
if($tab==3){ 
  if(count($defaultCollegePredictorFilters['college'])<=1){
  $filterSeparaterLast['location'] = '';
  if(count($defaultCollegePredictorFilters['location'])<=1){
    $filterSeparaterLast['location'] = '';  
  }else{
    $filterSeparaterLast['location'] = 'last';
  }
  }else{
  $filterSeparaterLast['college'] = 'last';
  }
  if($inputData['round']=='all' && $filterSeparaterLast['location']=='' && $filterSeparaterLast['college']==''){
    $filterSeparaterLast['round'] = 'last';
  }
}
?>
		<div class="filter-head">
                        	<p class="flLt">Filter by:</p>
                            <a href="javascript:void(0);" class="flRt font-12" onClick="trackEventByGA('FilterClick','CP_RESET_ALL_CLICK');resetFilters('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter');">Reset All</a>
                        </div>
			
                        <div class="filter-child-wrap customInputs">
<!--                        <div class="filter-child-wrap">-->
				<?php if($inputData['round']=='all' && count($defaultCollegePredictorFilters['round'])>1){ 
					$filteredRoundValues = array();
					$filteredRoundSelectedValues = array();
					foreach($collegePredictorFilters['round'] as $key=>$value){
						$filteredRoundValues[] = $value->getValue();
						$filteredRoundSelectedValues[$value->getValue()] = $value->isSelected();
					} //_p($filteredRoundValues);?>
                            <div class="filter-categories <?php echo $filterSeparaterLast['round'];?>">
                            	<h5><i class="predictor-sprite round-icon"></i>Round</h5>
                            	<ul class="filter-list">
					<?php
					foreach($defaultCollegePredictorFilters['round'] as $key=>$value){
					   $style = '';
					?>
					<?php //if( in_array($value->getValue(),$filteredRoundValues) && in_array($value->getValue(),$filterInputData['roundFilter'])){ $style = "style='font-weight:bold'";}?>
                                	<li>
					
                                    	<input id="round<?php echo $value->getValue();?>" <?php  if(!in_array($value->getValue(),$filteredRoundValues)){ echo 'disabled';} ?> <?php if(in_array($value->getValue(),$filterInputData['roundFilter'])){ echo 'checked';} ?> type='checkbox' value="<?php echo $value->getValue();?>" name='roundFilter[]' onClick="trackEventByGA('FilterClick','CP_ROUND_FILTER_CLICK');searchData('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter',this);" displayName="<?php echo $value->getName();?>" filterType="round" filterValue="<?php echo $value->getValue();?>"></input>

                                         <label for="round<?php echo $value->getValue();?>" <?php echo $style;?>>
                                           <span class="common-sprite"></span><p><?php echo $value->getName();?></p>
                                         </label>
                                	</li>
					<?php 
					}
					?>
                                </ul>
                            </div>
			    <?php } ?>
			    <?php
				if($inputData['tabType']!='branch' &&  count($defaultCollegePredictorFilters['branch'])>1){
					$filteredBranchValues = array();
					$filteredBranchSelectedValues = array();
					foreach($collegePredictorFilters['branch'] as $key=>$value){
						$filteredBranchValues[] = $value->getValue();
						$filteredBranchSelectedValues[$value->getValue()] = $value->isSelected();
					}
				    ?>
		                    <div class="filter-categories <?php echo $filterSeparaterLast['branch'];?>">
		                    	<h5><i class="predictor-sprite branch-icon"></i>Branch</h5>
					<div id="branchContainer">
		                        <div class="scrollbar1">
		                            <div class="scrollbar" style="visibility:hidden;">
		                                <div class="track">
		                                    <div class="thumb" id="thumbbranch"></div>
		                                </div>
		                            </div>
		                            <div class="viewport" style="height:150px"  id="branchViewport">
		                                <div class="overview" id="branchOverview">
		                                
		                                     <ul class="filter-list">
							<?php
							foreach($defaultCollegePredictorFilters['branch'] as $key=>$value){
							  $style = '';
							?>
							<?php //if(in_array($value->getValue(),$filteredBranchValues) && in_array($value->getValue(),$filterInputData['branchFilter'])){ $style = "style='font-weight:bold'";}?>
		                                        <li>
		                                            <input id="<?php echo $value->getValue();?>" <?php  if(!in_array($value->getValue(),$filteredBranchValues)){ echo 'disabled';} ?> <?php if(in_array($value->getValue(),$filterInputData['branchFilter'])){ echo 'checked';} ?> type='checkbox' value="<?php echo $value->getValue();?>" onClick="trackEventByGA('FilterClick','CP_BRANCH_FILTER_CLICK');searchData('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter',this);" name='branchFilter[]' displayName="<?php echo $value->getName();?>" filterType="branch" filterValue="<?php echo $value->getValue();?>"></input>
		                                            <label for="<?php echo $value->getValue();?>" <?php echo $style;?>>
		                                                <span class="common-sprite"></span><p><?php echo $value->getName();?></p>
		                                            </label>
		                                        </li>
							<?php 
							}
							?>
		                                     </ul>
		                                </div>
		                            </div>
		                        </div>
					</div>
		                   </div>
			   <?php } ?>
			   <?php
			if(count($defaultCollegePredictorFilters['location'])>1){
			    	$filteredLocationValues = array();
				$filteredLocationSelectedValues = array();
				foreach($collegePredictorFilters['location'] as $key=>$value){
					$filteredLocationValues[] = $value->getValue();
					$filteredLocationSelectedValues[$value->getValue()] = $value->isSelected();
				}
			    ?>
                            <div class="filter-categories <?php echo $filterSeparaterLast['location'];?>">
                            	<h5><i class="predictor-sprite loc-icon"></i>Location</h5>
				<div id="locationContainer">
                                <div class="scrollbar1">
                                    <div class="scrollbar" style="visibility:hidden;">
                                        <div class="track">
                                            <div class="thumb"  id="thumblocation"></div>
                                        </div>
                                    </div>
                                    <div class="viewport" style="height:100px"  id="locationViewport">
                                        <div class="overview" id="locationOverview">
                                             <ul class="filter-list">
						<?php 
						if($inputData['rankType']=='Home' || $inputData['rankType']=='StateLevel' || $inputData['rankType']=='HomeUniversity' || $inputData['rankType']=='HyderabadKarnatakaQuota'){
							$locationType = 'city';
						}else{
							$locationType = 'state';
						}
						foreach($defaultCollegePredictorFilters['location'] as $key=>$value){
						  $style = '';
						?>
						<?php //if( in_array($value->getValue(),$filteredLocationValues) && in_array($value->getValue(),$filterInputData['locationFilter'][$locationType])){ $style = "style='font-weight:bold'";}?>
                                                <li>
                                                    <input id="<?php echo $value->getValue();?>" <?php  if(!in_array($value->getValue(),$filteredLocationValues)){ echo 'disabled';} ?>  <?php if(in_array($value->getValue(),$filterInputData['locationFilter'][$locationType])){ echo 'checked';} ?> type='checkbox' value="<?php echo $value->getValue();?>" onClick="trackEventByGA('FilterClick','CP_LOCATION_FILTER_CLICK');searchData('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter',this);" name='locationFilter[]' filterType="location" filterValue="<?php echo $value->getValue();?>"></input>
                                                    <label for="<?php echo $value->getValue();?>" <?php echo $style;?>>
                                                        <span class="common-sprite"></span><p><?php echo $value->getName();?></p>
                                                    </label>
                                                </li>
                                               <?php } ?>
                                             </ul>
                                        </div>
                                    </div>
                                </div>
			    </div>
                           </div>
			<?php } ?>
			<?php if($inputData['tabType']!='college' && count($defaultCollegePredictorFilters['college'])>1){ ?>
			<?php
			  $filteredCollegeValues = array();
			  $filteredCollegeSelectedValues = array();
			 //$collegeNameToGroupNameMapping = array('NIT'=>array('collegeName'=>'NITs',''));
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

                            <div class="filter-categories <?php echo $filterSeparaterLast['college'];?>">
                            	<h5><i class="predictor-sprite collg-icon"></i>Colleges</h5>
                                <div class="filter-search">
                                    <i class="predictor-sprite filter-search-icon"></i>
                                    <input style="width: 168px" type="text" onclick="$j('#institute_text').val('');" onkeyup="instituteAutoSuggestor();" id="institute_text">
                            	</div>
				<div id="collegeContainer">
                                <div class="scrollbar1">
                                    <div class="scrollbar" style="visibility:hidden;" id="collegeScrollbar">
                                        <div class="track" id="collegeTrack">
                                            <div class="thumb" id="thumbCollege"></div>
                                        </div>
                                    </div>
                                    <div class="viewport"  style="height:200px"  id="collegeViewport">
                                        <div class="overview" id="collegeOverview">
                                             <ul class="filter-list">
						<li id="NIT" <?php if(!in_array('NIT',$filteredDefaultCollegeSelectedGroupName)){ $removeGroupName[] = 'NIT';?> style="display:none;"<?php } ?>  <?php /*if(in_array('NIT',$filterInputData['collegeFilter'])){ echo "class='active'";} */?>>
                                                    <input <?php if(!in_array('NIT',$filteredCollegeSelectedGroupName)){ echo 'disabled';} ?> id="grp_NIT" type="checkbox" value="NIT" onClick="trackEventByGA('FilterClick','CP_COLLEGE_FILTER_CLICK');searchData('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter',this);" name='collegeFilter[]' <?php if(in_array('NIT',$filterInputData['collegeFilter'])){ echo 'checked';} ?>  filterType="college" filterValue="NIT"></input>
                                                    <label for="grp_NIT">
                                                        <span class="common-sprite"></span><p>All NITs</p>
                                                    </label>
                                                </li>
						<li id="IIIT" <?php if(!in_array('IIIT',$filteredDefaultCollegeSelectedGroupName)){  $removeGroupName[] = 'IIIT';?> style="display:none;"<?php } ?>  <?php /*if(in_array('IIIT',$filterInputData['collegeFilter'])){ echo "class='active'";} */?>>
                                                    <input <?php if(!in_array('IIIT',$filteredCollegeSelectedGroupName)){ echo 'disabled';} ?> id="grp_IIIT" type="checkbox" value="IIIT" onClick="trackEventByGA('FilterClick','CP_COLLEGE_FILTER_CLICK');searchData('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter',this);" name='collegeFilter[]' <?php if(in_array('IIIT',$filterInputData['collegeFilter'])){ echo 'checked';} ?>  filterType="college" filterValue="IIIT"></input>
                                                    <label for="grp_IIIT">
                                                        <span class="common-sprite"></span><p>All IIITs</p>
                                                    </label>
                                                </li>
						<li id="BIT" <?php if(!in_array('BIT',$filteredDefaultCollegeSelectedGroupName)){  $removeGroupName[] = 'BIT';?> style="display:none;"<?php } ?> <?php /*if(in_array('BIT',$filterInputData['collegeFilter'])){ echo "class='active'";}*/ ?> >
                                                    <input <?php if(!in_array('BIT',$filteredCollegeSelectedGroupName)){ echo 'disabled';} ?> id="grp_BIT" type="checkbox" value="BIT" onClick="trackEventByGA('FilterClick','CP_COLLEGE_FILTER_CLICK');searchData('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter',this);" name='collegeFilter[]' <?php if(in_array('BIT',$filterInputData['collegeFilter'])){ echo 'checked';} ?>  filterType="college" filterValue="BIT"></input>
                                                    <label for="grp_BIT">
                                                        <span class="common-sprite"></span><p>All BITs</p>
                                                    </label>
                                                </li>
						<?php 
						foreach($defaultCollegePredictorFilters['college'] as $key=>$value){
						  $style = '';
						?>
						<?php //if( in_array($value->getValue(),$filteredCollegeValues) && in_array($value->getValue(),$filterInputData['collegeFilter'])){ $style = "style='font-weight:bold'";}?>
                                                <li id="institute_<?php echo $value->getValue();?>">
                                                    <input id="inst_<?php echo $value->getValue();?>" <?php  if(!in_array($value->getValue(),$filteredCollegeValues)){ echo 'disabled';} ?>  <?php if(in_array($value->getValue(),$filterInputData['collegeFilter'])){ echo 'checked';} ?> type='checkbox' value="<?php echo $value->getValue();?>" onClick="trackEventByGA('FilterClick','CP_COLLEGE_FILTER_CLICK');searchData('<?=$tab;?>','','rank','<?php echo $sortOrder;?>','filter',this);" name='collegeFilter[]' filterType="college" filterValue="<?php echo $value->getValue();?>"></input>
                                                    <label for="inst_<?php echo $value->getValue();?>" <?php echo $style;?>>
                                                        <span class="common-sprite"></span><p><?php echo $value->getName();?></p>
                                                    </label>
                                                </li>
                                                <?php } ?>
                                             </ul>
                                        </div>
                                    </div>
                                </div>
				</div>
				<p id="no_result_inst" style="display:none;">
				    No result found for this institute
				</p>
                           </div>
		<?php } ?>
	</div>
<?php		

//}
$count = count($instituteGroupsFilter);
foreach($removeGroupName as $key=>$value){
  for($i=0 ;$i<$count;$i++){
    if($value==$instituteGroupsFilter[$i]['collegeGroupName']) {
	unset($instituteGroupsFilter[$i]);
    }
  }
}
?>
</div>
<script>
var instituteJsonFilter = <?php echo json_encode($institutesFilter,true);?>;
var instituteGroupJsonFilter = <?php echo json_encode($instituteGroupsFilter,true);?>;
</script>
<?php 
}
?>
