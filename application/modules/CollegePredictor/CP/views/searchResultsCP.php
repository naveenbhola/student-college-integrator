<?php 
$tableStyle = '';
$tdStyle = '';
if($print) $tdStyle="style='border-bottom:1px solid #e5e5e5'";
$i=0;
$sortKey                =  "rank";
$sortKeyValue   =  "";
$sortOrder              =  "asc";
$enableSortFunctionality = false;
//$courseCodeFlag = false;
$examNameNew = strtoupper($examName);
$codeHeading = '';

$codeHeading = 'CSAB course code';
$courseCodeFlag = true;


if($rankPredictor=='1'){
	$brochureAction = 'RankPredictor';
	$trackingPageKeyId=186;
	$comparetrackingPageKeyId = 187;
}
else{
	$brochureAction = 'CollegePredictor';
	$trackingPageKeyId=$trackingPageKeyId;
	$comparetrackingPageKeyId = $comparetrackingPageKeyId;
}

if(!empty($sorter) && $defaultView!=1 && !$print && $sortStatus=='YES'){
	if(count($branchInformation) > 1){					
        $enableSortFunctionality = true;
	}
/*	if(count($branchInformation) > 0){					
       	$sortKey                =  $sorter->getKey();
        $sortKeyValue   =  $sorter->getKeyValue();
        $sortOrder              =  $sorter->getOrder();
	}*/
	}
if($sortStatus=='YES'){	
$sortKey                =  $sorter->getKey();
$sortKeyValue   =  $sorter->getKeyValue();
			$sortOrder  =  $sorter->getOrder();
			if($sortOrder=='desc'){
	$sortOrderForFilter = 'asc';
}else{
	$sortOrderForFilter = 'desc';
			}
			?>
			
<input type="hidden" id="sortOrder" value="<?php echo $sortOrder;?>" />
<?php
}
if($inputData['rankType']=='Home' || $inputData['rankType']=='StateLevel' || $inputData['rankType']=='HomeUniversity' || $inputData['rankType']=='HyderabadKarnatakaQuota'){
$locationType = 'city';
}else{
$locationType = 'state';
}
$roundNumArr = array('1'=>'Round 1','2'=>'Round 2','3'=>'Round 3','4'=>'Round 4','5'=>'Round 5', '6'=>'Round 6');
if($defaultView){
	$collegeWidth = '38%';
	$rankWidth    = '17%';
	$branchWidth  = '32%'; 
}else{
	$collegeWidth = '44%';
	$rankWidth    = '19%';
	$branchWidth  = '24%';
}
?>
<div class="result-right-col" <?php if(($defaultView || (count($branchInformation) < 1 && empty($filterTypeValueData) )||  $print || $_COOKIE['collegepredictor_showFilters_'.$examinationName]=='notdisplay') || $filterStatus=='NO'){ ?> style="margin:0px !important"; <?php } ?> >
<?php
if(!$defaultView && !$print){
$collegeGroupNameMappingArr = array('NIT'=>'All NITs','IIIT'=>'All IIITs','BIT'=>'All BITs');
//_p($_COOKIE['COLLEGE_PREDICTOR_TOP_FILTER']);
if(isset($_COOKIE['COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName]) && $_COOKIE['COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName]!='' && $filterStatus=='YES'){
$cookieData = $_COOKIE['COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName];
$cookieDataArr = explode("::::",$cookieData);

?>
<div class="selection-criteria">
<?php
foreach($cookieDataArr as $key=>$value){
$rankTypeValueArr = explode(":",$value);
//_p($branchInformation);
foreach($mainObj as $key=>$value){
	$data['mainObjfullCollegePath'][$value->getInstituteId()] = $value->getCollegeName().', '.$value->getCityName().', '.$value->getStateName();
        $cityArr[$value->getLocationId()] = $value->getCityName();
}

foreach($objAfterAppliedFilter as $key=>$value){
      $data['round'][] = $value->getNumberOfRound();
      $data['branchAcronym'][] = $value->getBranchAcronym();
      if($locationType=='state'){
	$data['location'][] = $value->getStateName();		
      }
      if($locationType=='city'){
        $data['location'][] = $value->getLocationId();			
      }
      
      $data['fullCollegePath'][$value->getInstituteId()] = $value->getCollegeName().', '.$value->getCityName().', '.$value->getStateName();
      $data['collegeGroupName'][] = $value->getCollegeGroupName();
}
?>
<?php
if($rankTypeValueArr[0]=='round'){
	if(in_array($rankTypeValueArr[1],$data['round'])){
        ?>
						<p><?php echo $roundNumArr[$rankTypeValueArr[1]]; ?>
						<a class="cross-icon" href='javascript:void(0);' onClick="$('round<?php echo $rankTypeValueArr[1];?>').checked=true;$('round<?php echo $rankTypeValueArr[1];?>').click();">&times;</a>
						</p>
									
	<?php }else{ ?>
			<p class="disable-text-color"><?php echo $roundNumArr[$rankTypeValueArr[1]];?>
			<a href="#" class="cross-icon disable">&times;</a>
			</p>
			<?php
	}
}else if($rankTypeValueArr[0]=='branch'){
        if(in_array($rankTypeValueArr[1],$data['branchAcronym'])){
        ?>
			<p><?php  echo $rankTypeValueArr[1]; ?>
			<a class="cross-icon" href='javascript:void(0);'  onClick="$('<?php echo $rankTypeValueArr[1];?>').checked=true;$('<?php echo $rankTypeValueArr[1];?>').click();">&times;</a>
			</p>
	<?php
	}else{ ?>
			<p class="disable-text-color"><?php echo $rankTypeValueArr[1];?>
			<a href="#" class="cross-icon disable">&times;</a>
			</p>
			<?php
	}
}else if($rankTypeValueArr[0]=='location'){
        if(in_array($rankTypeValueArr[1],$data['location'])){ ?>
			 <?php if($locationType=='city'){ ?>
                                <p><?php  echo $cityArr[$rankTypeValueArr[1]]; ?>
                        <?php }else{ ?>
                                <p><?php  echo $rankTypeValueArr[1]; ?>
                        <?php }?>
			<a class="cross-icon" href='javascript:void(0);'  onClick="$('<?php echo $rankTypeValueArr[1];?>').checked=true;$('<?php echo $rankTypeValueArr[1];?>').click();">&times;</a>
			</p>
	<?php
	}else{ ?>
			<p class="disable-text-color">
			<?php if($locationType=='city'){ ?>
                                <?php echo $cityArr[$rankTypeValueArr[1]]; ?>
                        <?php
                        }else{ ?>
                                <?php  echo $rankTypeValueArr[1]; ?>
                        <?php } ?>
			<a href="#" class="cross-icon disable">&times;</a>
			</p>
       <?php
	}
}
else if($rankTypeValueArr[0]=='college'){
        foreach($data['fullCollegePath'] as $key=>$value){
			$collegeIds[] = $key;
	}
	if(in_array($rankTypeValueArr[1],array('NIT','IIIT','BIT'))){
			if( in_array($rankTypeValueArr[1],$data['collegeGroupName'])){
			?>
			<p><?php echo $collegeGroupNameMappingArr[$rankTypeValueArr[1]];?>
			<a class="cross-icon" href='javascript:void(0);'  onClick="$('grp_<?php echo $rankTypeValueArr[1];?>').checked=true;$('grp_<?php echo $rankTypeValueArr[1];?>').click();">&times;</a>						
			</p>			
			<?php
			}else{ ?>
			<p class="disable-text-color"><?php echo $collegeGroupNameMappingArr[$rankTypeValueArr[1]];?>
			<a href="#" class="cross-icon disable">&times;</a>
			</p>
			<?php
			}
	}else{
			if(in_array($rankTypeValueArr[1],$collegeIds) && isset($data['fullCollegePath'][$rankTypeValueArr[1]]) && $data['fullCollegePath'][$rankTypeValueArr[1]]!=''){ ?>
			<p><?php  echo $data['fullCollegePath'][$rankTypeValueArr[1]]; ?>
			<a class="cross-icon" href='javascript:void(0);'  onClick="$('inst_<?php echo $rankTypeValueArr[1];?>').checked=true;$('inst_<?php echo $rankTypeValueArr[1];?>').click();">&times;</a>						
			</p>
			<?php
			}else if(isset($data['mainObjfullCollegePath'][$rankTypeValueArr[1]]) && $data['mainObjfullCollegePath'][$rankTypeValueArr[1]]!=''){ ?>
			<p class="disable-text-color"><?php echo $data['mainObjfullCollegePath'][$rankTypeValueArr[1]];?>
			<a href="#" class="cross-icon disable">&times;</a>
			</p>
			<?php
			}
	}
}
}
?>
</div>

<?php
}
}
?>
<?php if(count($branchInformation) < 1 && !empty($filterTypeValueData)){ ?>
<div class="filter-zero-result"> No institutes found for your critera. <a href="javascript:void(0);" onClick="resetFilters('<?php echo $tab;?>','','rank','<?php echo $sortOrder;?>','filter');">Reset all filters</a></div>
<?php } ?>
<?php if($total > 0):?>

                   	<table id="cp_data_table" cellpadding="0" cellspacing="0" class="search-result-table" style="<?php echo $tableStyle; ?>">
                       	<tr>
                           	
                              <?php if($tab==3){?>
			<th width="<?php echo $branchWidth;?>">Branch Name</th>
			<th width="<?php echo $collegeWidth;?>">College Name</th>
		<?php }else{ ?>
			<th width="<?php echo $collegeWidth;?>">College Name</th>
			<th width="<?php echo $branchWidth;?>">Branch Name</th>
		<?php } ?>
                               <?php if($inputData['round']=='all'){?>
			       <th width="13%">Round</th>
			       <?php }else{ ?>
                               <th width="13%">Location</th>
			       <?php } 
				$sortByRank = 'rank';
				?>
				
                               <th width="<?php echo $rankWidth;?>">
				<div style="position:relative;">
							<span class="flLt">Closing <?php echo $inputType?></span>
							<?php
			                                if($enableSortFunctionality){
                        			        ?>
							<div class="sorting-arrows">
								<?php
								if($sortKey == "rank" && $sortOrder == "asc"){
									?>
									<span class="sort-by-high predictor-sprite"  onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'up');"></span>
									<span class="sort-dwn-arr predictor-sprite" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'down');" onclick="searchData('<?php echo $tab;?>','','<?php echo $sortByRank;?>','desc','sorting');" ></span>
								<?php
								} else if($sortKey == "rank" && $sortOrder == "desc"){
									?>
									<span class="sort-up-arr predictor-sprite" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'up');" onclick="searchData('<?php echo $tab;?>','','<?php echo $sortByRank;?>','asc','sorting')"></span>
									<span class="sort-by-low predictor-sprite" onmouseover="showSorterTooltip('rank', 'down');" onmouseout="hideToolTip('rank');"></span>
								<?php
								} else {
									?>
									<span class="sort-up-arr predictor-sprite" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'up');" onclick="searchData('<?php echo $tab;?>','','<?php echo $sortByRank;?>','asc','sorting');" ></span>
									<span class="sort-dwn-arr predictor-sprite" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'down');" onclick="searchData('<?php  echo $tab; ?>','','<?php echo $sortByRank;?>','desc','sorting');" ></span>
									<?php
								}
								?>
                            				</div>
							<div class="help-tool" id="rank_help_tooltip" style="display:none;">
								<div class="predictor-sprite help-pointer"></div>
								<div class="help-content" id="rank_help_tooltip_content"></div>
							</div>
						    <?php } ?>
					</div>
				</th>
                           </tr>
                            <?php
                            $displayedTupleCount =0;
                             foreach($branchInformation as $key=>$branchObj){
	                            	$roundsInfo = $branchObj->getRoundsInfo();
	                            	// _p($roundsInfo); 
									foreach ($roundsInfo as $roundData) {
										$closingRank = $roundData['closingRank'];
										$roundNumber = $roundData['round'];
										if($closingRank == 0) {
											continue;
										}
										
                            	$courseTypeHtml = '';
				    $i++;
   if($branchObj->getShikshaCourseId()) {
   		$course = $courseRepository->find($branchObj->getShikshaCourseId());
   		if($validateuser != 'false') {
   			if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
   				if(is_object($course)){
   					if($course->isPaid()){
   						$courseTypeHtml = '<label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label>';
   					}else{
   						$courseTypeHtml = '<label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label>';
   					}
   				}
   			}
   		}
   		 
   }
	    if($branchObj->getShikshaCourseId()){
			$courseUrl = $course->getURL();
			$type = "internal";
	    }
	    else{
			$courseUrl = $branchObj->getInstCourseLink();
			if(empty($courseUrl) || $courseUrl == 'NULL')
			{
				$courseUrl = $branchObj->getInstLink();
			}
			$type = "external";
	    }
  ?>
  
     <?php
	      	$instUrl = '';

	   	$courseId = $branchObj->getShikshaCourseId();
	   	if($courseId > 0 && $courseId !='')
	   	{
		   	$courseObj = $courseRepository->find($courseId);
		   	$instId = $courseObj->getInstituteId();
		   	if($instId > 0) {
		   		$instObj = $instituteRepository->find($instId);
				$instUrl = $instObj->getURL();
		   	}
		   	else {
		   		continue;
		   	}
			
		}
	    if ($branchObj->getShikshaCourseId() && isset($_COOKIE["applied_".$course->getId()]) && ($validateuser != "false")) {
		      $class = "gray-bro-btn bro-btn-disable";
		      $brochureDisable = "true";
		      $style="display: block; margin: 8px 0;";
		      echo "<script>brochureDisable = 'true';</script>";
		      $aa = "return false";
		      
	    }
	    else{
			$aa = "";
			$style="display: none;margin: 8px 0;";
			$class  = "gray-bro-btn";
			$brochureDisable = "false";
			echo "<script>brochureDisable = 'false';</script>";
	    }
   ?>
                           <tr class="<?php if($i%2==0 && !$print) echo "alt-rowbg";?>">
			
      <?php if($tab==3){ ?>
       <td <?php echo $tdStyle;?>>
       <?php if($courseUrl=='NULL'){ ?>
			<?php echo $branchObj->getBranchName();?>
       <?php }else{ ?>
			<a href="<?php echo addhttp($courseUrl);?>" <?php if($type == "external"): ?>target="_blank" rel="nofollow" <?php endif;?> onclick="trackEventByGA('Branchtabclick','BRANCH_TAB');"><?php echo $branchObj->getBranchName();?></a>
       <?php } ?>
<?php echo $courseTypeHtml;?>
<?php if(!$courseCodeFlag){ ?>
      <span><?php echo $codeHeading; ?>:<?php echo $branchObj->getCourseCode();?></span>
<?php } ?>
      </td>
      <td <?php echo $tdStyle;?>>
<?php if($inputData['round']=='all'){ ?>
			<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?php echo $branchObj->getCollegeName().', '.$branchObj->getCityName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>
<?php }else{ ?>
			<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?php echo $branchObj->getCollegeName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>

	<?php } ?>
	       
			<?php
	    if($branchObj->getShikshaCourseId()  && !$print) {?>
			<?php if($validateuser != "false"){  ?>
			
			<a id="courseid_<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?php echo $class?>"
			  onmouseover="if(typeof(activateDownload) !='undefined'){activateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
		          onmouseout= "if(typeof(deactivateDownload) !='undefined'){deactivateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
			  
		       	 onclick="<?php if($brochureDisable=='true'){ ?> return false;<?php } else {?>
			 responseForm.showResponseForm('<?php echo $course->getId()?>','CollegePredictor','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{});<?php }?> ;  trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?php echo $branchObj->getNumberOfRound();?>');" >
						Download E-Brochure
			</a>
				        
			<?php } else{ ?>
			
			  <a id="courseid_<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?php echo $class?>"
			  onmouseover= "if(typeof(activateDownload) !='undefined'){activateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
			 onmouseout= "if(typeof(deactivateDownload) !='undefined'){deactivateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
			 
			  onclick="<?php if($brochureDisable=='true'){ echo 'disable'; ?> return false;<?php } else { ?>
			 responseForm.showResponseForm('<?php echo $course->getId()?>','CollegePredictor','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{});<?php }?>;  trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?php echo $branchObj->getNumberOfRound();?>');">
			 
						Download E-Brochure
			</a>
			<?php }?>
				    
			<div id="apply_confirmation_<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>" style="<?php echo $style;?>">
						       <i class="predictor-sprite tick-icon" style="margin: 0; float: left"></i>
						       <p style="margin:0 0 0 23px;float: none; color: #4EC42E; font-size:12px;">E-brochure successfully mailed.</p>
			</div>
	    <?php  } ?>
	    
		       <?php
		       if($branchObj->getShikshaCourseId() && $print != 1) {
		       $getInstituteImage = $instituteRepository->find($course->getInstituteId());
		       ?>
			<p class="flRt">
			
<input onclick="myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this,  {});trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');" 
	
	type="checkbox" name="compare" id="compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>" 
	class="addToCompareOnCollegePredictorCheckBox compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>" value="<?php echo $course->getInstituteId().'::'.' '.'::'.($getInstituteImage->getHeaderImage()?$getInstituteImage->getHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($course->getInstituteName())).', '.$branchObj->getCityName().'::'.$course->getId().'::'.$course->getURL();?>"/>

<a style="font-size:13px;"  href="javascript:void(0);" onclick="
trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');
myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this, {});
return false;" id="compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>lable" class="addToCompareOnCollegePredictor compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>lable">Add to Compare</a>
			</p>
			<div style="display:none">
			<input type="hidden" name="compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>list[]"  value= "<?php echo $course->getId()?>" /></div>
			<?php }?> 
	    
      </td>
      <?php }else{ ?>
    <td <?php echo $tdStyle;?>>
<?php if($inputData['round']=='all'){ ?>
			<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?php echo $branchObj->getCollegeName().', '.$branchObj->getCityName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>
<?php }else{ ?>
			<p><?php if($instUrl != ''){ ?><a href="<?php echo $instUrl; ?>"><?php } ?><?php echo $branchObj->getCollegeName();?><?php if($instUrl != ''){ ?></a><?php } ?></p>
<?php } ?>
<?php if($branchObj->getShikshaCourseId() && !$print) { ?>

			<?php if($validateuser != "false"){ ?>
			
			<a id="courseid_<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?php echo $class?>"
			  onmouseover= "if(typeof(activateDownload) !='undefined'){activateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
		          onmouseout= "if(typeof(deactivateDownload) !='undefined'){deactivateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
			  
		       	 onclick="<?php if($brochureDisable=='true'){ ?> return false;<?php } else {?>
			responseForm.showResponseForm('<?php echo $course->getId();?>','<?php echo ($predictorType != '')?$predictorType:'CollegePredictor';?>','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{});<?php }?> ;  trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?php echo $branchObj->getNumberOfRound();?>');" >
						Download E-Brochure
			</a>
				        
			<?php } else{ ?>
			
			  <a id="courseid_<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>" href="javascript:void(0);" class="<?php echo $class?>"
			  onmouseover= "if(typeof(activateDownload) !='undefined'){activateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
			 onmouseout= "if(typeof(deactivateDownload)){deactivateDownload('<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>');}"
			 
			  onclick="<?php if($brochureDisable=='true'){ ?> return false;<?php } else {?>
			 responseForm.showResponseForm('<?php echo $course->getId()?>',  '<?php echo ($predictorType != '')?$predictorType:'CollegePredictor';?>','course',{'trackingKeyId': '<?php echo $trackingPageKeyId?>','callbackFunction': 'downloadBrochureCP','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{});<?php }?> ;  trackEventByGA('DownloadBrouchureClick','DOWNLOAD_EBROCHURE_BUTTON');setValueOfRoundForREB('<?php echo $branchObj->getNumberOfRound();?>');">
			 
						Download E-Brochure
			</a>
			<?php }?>
					    
			<div id="apply_confirmation_<?php echo $branchObj->getShikshaCourseId(); ?>_<?php echo $branchObj->getNumberOfRound();?>" style="<?php echo $style;?>">
						       <i class="predictor-sprite tick-icon" style="margin: 0; float: left"></i>
						       <p style="margin:0 0 0 23px;float: none; color: #4EC42E; font-size:12px;">E-brochure successfully mailed.</p>
			</div>
	    <?php  } ?>
	    
		       <?php
		       if($branchObj->getShikshaCourseId() && $print != 1)
		       {
				
				   $getInstituteImage = ($course->getInstituteId())?$instituteRepository->find($course->getInstituteId()):'';
		       ?>
			<p class="flRt">
<input onclick="myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this,  {});
trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');"
 type="checkbox" name="compare" id="compare<?php echo$course->getInstituteId();?>-<?php echo$course->getId()?>" class="addToCompareOnCollegePredictorCheckBox compare<?php echo$course->getInstituteId();?>-<?php echo$course->getId()?>" value="<?php echo $course->getInstituteId().'::'.' '.'::'.($getInstituteImage->getHeaderImage()?$getInstituteImage->getHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($course->getInstituteName())).', '.$branchObj->getCityName().'::'.$course->getId().'::'.$course->getURL();?>"/>
<a style="font-size:13px;"  href="javascript:void(0);" onclick="
trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COLLEGE_PREDICTOR_PAGE');

myCompareObj.addToCompare({'courseId' : <?php echo $course->getId();?> ,'instituteId':<?php echo $course->getInstituteId();?>,'tracking_keyid' :<?php echo $comparetrackingPageKeyId;?>,'customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this,  {});

return false;" id="compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>lable" class="addToCompareOnCollegePredictor compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>lable">Add to Compare</a>
			</p>
			<div style="display:none">
			<input type="hidden" name="compare<?php echo $course->getInstituteId();?>-<?php echo $course->getId()?>list[]"  value= "<?php echo $course->getId()?>" /></div>
			<?php
			}?> 
	    
	    
</td>		       
   <td <?php echo $tdStyle;?>>
   <?php if($courseUrl=='NULL'){ ?>
			<?php echo $branchObj->getBranchName();?>
   <?php }else{ ?>
			<a href="<?php echo addhttp($courseUrl);?>"  <?php if($type == "external"): ?> target="_blank" rel="nofollow" <?php endif;?> onclick="trackEventByGA('Branchtabclick','BRANCH_TAB');"><?php echo $branchObj->getBranchName();?></a>
   <?php } ?>
   <?php echo $courseTypeHtml;?>
   <?php if(!$courseCodeFlag){ ?>
  <span><?php echo $codeHeading; ?>: <?php echo $branchObj->getCourseCode();?></span>
  <?php } ?>
   </td>
      <?php } ?>

                           	<?php if($inputData['round']=='all'){ ?>
						<td <?php echo $tdStyle;?>><?php echo "Round ".$roundNumber;?></td>	
				<?php }else{ ?>
                               <td <?php echo $tdStyle;?>><?php echo $branchObj->getCityName();?></td>
				<?php } ?>
                               
                               <td class="last-item" <?php echo $tdStyle;?>><?php echo $closingRank;?></td>
                           </tr>
                        <?php $displayedTupleCount++;
   if($displayedTupleCount == 3) { ?>
   		 <tr>
   		 	<td colspan="4">
   			<?php $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA'));
   			$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_LAA1')); ?>
   			</td>
   		</tr>
   		<?php } ?>
   		<?php if($displayedTupleCount == 6) { ?>
   			<tr>
   				<td colspan="4">
		   			<?php $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON'));
		   			$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_AON1')); ?>
	   			</td>
	   		</tr>
   		<?php } 
                    }
                        
                    }
                    ?>
                           
                       </table>
						<?php endif;?>
						<?php if(!$print):?>
							<?php if(!$defaultView && $total > 0):?>
							
							<?php if($total > 15):?>
	                       <div class="more-result" id="moreResults">
				<?php if($sortKey == "rank" && $sortOrder!= ""){ ?>
	                       	<a href="javascript:void(0);" onclick="searchData('<?php echo $tab;?>',startCount,'rank','<?php echo $sortOrder;?>','loadmore','','<?php echo $loadtrackingPageKeyId?>'); startCount+=count; trackEventByGA('Loadmoreresults','LOAD_MORE_BUTTON'); if(typeof(pageTracker)!='undefined'){ pageTracker._trackPageview();} ">Load more results...</a>
				<?php }else{ ?>
	                       	<a href="javascript:void(0);" onclick="searchData('<?php echo $tab;?>',startCount); startCount+=count; trackEventByGA('Loadmoreresults','LOAD_MORE_BUTTON'); if(typeof(pageTracker)!='undefined'){ pageTracker._trackPageview();}">Load more results...</a>
				<?php } ?>
	                       </div>
							<?php endif;?>
	                       <div class="result-pagination clear-width" style="margin-top:10px;margin-bottom:30px;">
						<?php if($showInvite=='YES' || $showPrint=='YES' || $showEmail=='YES'){?>
	                       	<ul>
	                           	<?php
			         if($showEmail=='YES'){
			               if($validateuser != 'false'):?>
	                           	   <li><i class="predictor-sprite mail-icon"></i><a id="email_friends" href="javascript:void(0);" onclick="sendEmail('<?php echo $emailtrackingPageKeyId?>');return false;">Email results</a></li>
	                           	   <?php else:?>
	                           	   <li><i class="predictor-sprite mail-icon"></i><a id="email_friends" href="javascript:void(0);" onclick="showFormInCollegePredictorEmailResult('<?php echo $emailtrackingPageKeyId?>');return false;">Email results</a></li>
	                           	   <?php endif;?>
				       <?php } ?>
				       <?php if(($showPrint=='YES' && $showEmail=='YES') || ($showInvite=='YES' && $showEmail=='YES')){ ?> <li> | </li><?php } ?>
				       <?php
				       if($showPrint=='YES'){ ?>
	                               <li><i class="predictor-sprite print-icon"></i> <a href="javascript:void(0);" onclick="printData('<?php echo $tab;?>')">Print results</a></li>
				       <?php } ?>
				       <?php if($showPrint=='YES' && $showInvite=='YES'){ ?> <li> | </li><?php } ?>
				       <?php if($showInvite=='YES'){ ?>
				          <li><i class="predictor-sprite invite-icon"></i> <a id="invite_friends" href="javascript:void(0);" onclick="inviteFriends('<?php echo  $invitetrackingPageKeyId?>');return false;">Invite Friends</a></li>
					<?php } ?> 
	                           </ul>
				<?php } ?>
	                       </div>


<?php endif;?>		
<?php if(count($branchInformation) >0 && $showFeeback=='YES'){ ?>	
		     <div class="helpful-info">
	                       	<p><strong>Was the information helpful?</strong></p>
	                           <div class="vote-section">
	                           	<a href="javascript:void(0);" onclick="sendFeedback('1','rate')"; style="margin-right:10px"><i id="yes" class="predictor-sprite upVote-icon"></i> Yes</a>
	                           	<a href="javascript:void(0);" onclick="sendFeedback('0','rate')";><i id="no" class="predictor-sprite dwnVote-icon"></i> No</a>
	                           </div>
				   
				    <div class="comment-section">
                                            <p id="thankMsg" style="display: none" class="thnku-msg">Thanks for your feedback!</p>
						<div class="comment-box" id="comment-box" style="display: none">
						   <i class="predictor-sprite comment-arrw"></i>
						   <textarea id="feebackMsg" name="textarea" class="commnt-textarea" onclick = "
						   $(this).innerHTML='';$j('#feebackMsg').css('color','#666'); ">Please share your comments?</textarea>
						   <input type="button" class="sbmt-btn" onclick="sendFeedback('3','msg');" value="Submit"/>
						   <div class="errorMsg" id="commentErr" style="display: none"></div>
						</div>
				    </div>
			       </div>
			       
<?php } ?>			       
		       <div class="disclaimer-sec">
			   <strong>Disclaimer:</strong>
			   <p>Please note that the above displayed colleges and branches are only for reference purpose. Shiksha.com does not take any responsibility for the validity of the predictions and analysis shown above.
			  <br/>
			  <br/><?php echo $notice;?>
			
		
			</div>
	                       <div class="clearFix"></div>
	                      
	                    
                       <?php endif;?>
</div>
<?php
$textArr = explode('::::',$text);
$text1   = $textArr[0];
$text2   = $textArr[1];
?>
<script>
  var filterDisplayStatus = '<?php echo $filterStatus;?>';
  var startCount = <?php echo $count;?>;
   count = <?php echo $count;?>;
   $j("#result_text").html('<?php echo $text1;?>');
   $j("#result_text_1").html('<?php echo $text2;?>');
   <?php if($filterStatus=='YES'){ ?>
   <?php if($total == 0 && $_COOKIE['collegepredictor_showFilters_'.$examinationName]=='notdisplay'):?>
   $j('#zero_result').show();
   <?php else:?>
   $j('#zero_result').hide();
   <?php endif;?>
   <?php }else{ ?>
   <?php if($total == 0){?>
   $j('#zero_result').show();
   <?php }else{ ?>
   $j('#zero_result').hide();
   <?php } }?>
   <?php if($print):?>
   window.onload = window.print();
   <?php endif;?>
   
</script>
<input type="hidden" value="<?php echo $total;?>" id="total">
<input type="hidden" value="<?php echo $count;?>" id="count">

<?php if(count($branchInformation) >0 && $showFeeback=='YES'){ ?>
<script>
	    if(getCookie('feedback') != ''){
	        feedbackVal = getCookie('feedback');
		var res = feedbackVal.split("|");
		if(res[1] == '1'){
			$j('#yes').addClass('upVote-orng-icon');
		}
		else
			$j('#no').addClass('dwnVote-orng-icon');
		$('thankMsg').innerHTML = 'You have already given the feedback.';
		$('thankMsg').style.display = 'inline';
	}
</script>
<?php  } ?>
<script>
compareDiv = 1;
var currentPageName= 'College Predictor Page'; 
</script>
