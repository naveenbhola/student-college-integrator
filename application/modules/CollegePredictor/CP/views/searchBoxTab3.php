<?php
$data= array();
$examNameNew = strtoupper($examName);
if(isset($_COOKIE["collegepredictor_search_desktop_".$examinationName])) {
	$data = $_COOKIE["collegepredictor_search_desktop_".$examinationName];
	$data = json_decode($data);
	if($data->tabType == 'branch') { 
		$data = $data;
	}else {
		$data = array();
	}
	$data1 = json_decode($_COOKIE["collegepredictor_search_desktop_".$examinationName],true);
}

if($linkUrl) {
	$data1['branches'] = $branches;
}


$count = 0 ;
$count = (!empty($data->branchAcronym)) ? count($data->branchAcronym) : count($data1['branches']);

?>
<script>currentPageName='College Predictor - Branch Predictor Tab';</script>	
	<div id="predictor-tab-details"  <?php if(!$defaultView):?> style="display: none;" <?php endif;?>>
	<form id="search_form">
	<input type="hidden" value="branch" name="tabType" />
	<ol>
	<li>
		<label>Select Preferred Branches:</label>
	    <div class="predictor-field-wrap">
	    	<div class="pre-select" style="width:207px; position:relative; padding: 4px 5px" onmouseover="$j('#branch_selector').show();"  onmouseout="if(getCookie('showOverlayBr') == 'no') $j('#branch_selector').hide();"> Selected (<span id="branch_selected_number"><?php if(!empty($count)){echo $count;}else {echo '0';} ?></span>)
	        	<div class="dropdown-layer customInputs" id="branch_selector" style="display:none;width:350px;">
	            	<div class="filter-search" onclick="setCookie('showOverlayBr','yes',0 ,'/',COOKIEDOMAIN);" style="width:300px;">
					    <i class="predictor-sprite  filter-search-icon"></i>
					    <input type="text" onclick="$j('#branch_text').val('');" onkeypress="return event.keyCode != 13;" onkeyup="getBranches();" id="branch_text" value="Search Branch ..." style="color:#7F7F7F" >
					</div>
					
							            	<ul class="dropdown-list" style="height:162px;overflow-y:auto;" onclick="setCookie('showOverlayBr','yes',0 ,'/',COOKIEDOMAIN);" id="branch_selector_container" >
							            		<?php $this->load->view('CP/searchBranchCP',$data1);?>
							                </ul>

  	              	<div class="tac clear-width" style="margin:8px 0 2px 0; border-top:1px solid #ccc; padding-top:5px"><a class="orange-button2" href="javascript:void(0)" onclick="selectBranch();" style="padding:4px 10px; font-size:13px">Ok</a></div>
	            </div>
	        </div>
	        <div id="error_branch" class="errorMsg" style="margin-top : 3px;display:none;"></div>
	    </div>
	</li>
	<li style="position: relative;">
		<label>Enter <?=$inputType?>/Predicted <?=$inputType?>:
		<br/>
		<?php if($showRankOverlay=='YES'){ ?>
                        <?php if($examNameNew == 'JEE-MAINS'){ ?>
                        <a id="external-link" href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/jee-main-rank-predictor'?>" target="_blank"; class="find-rank"> Find your Rank </a>
			<?php }else if($examNameNew == 'COMEDK'){ ?>
			<a id="external-link" href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/comedk-rank-predictor'?>" target="_blank"; class="find-rank"> Find your Rank </a>
			<?php }else{ ?>
                        <a id="external-link" href="javascript:void(0)" class="find-rank" onclick="openRankDetailsInOverlay();"> Find your Rank </a>
			<?php } ?>
		<?php } ?>
		</label>
	    <div class="predictor-field-wrap">
	    	<input class="field-styles" name="rank" id="rank" inputType = <?=$inputType?> type="text" value="<?php if(!empty($data->rank)){echo $data->rank;}elseif (!empty($rank)) {echo $rank;}else{ echo 'Enter '.$inputType.'/Predicted '.$inputType;}?>" onfocus="if($j('#rank').val() == 'Enter <?=$inputType?>/Predicted <?=$inputType?>') $j('#rank').val('');"  onblur="if($j('#rank').val() == '')$j('#rank').val('Enter <?=$inputType?>/Predicted <?=$inputType?>');" style="padding:4px 5px;width:207px;"/>
	    	<div id="error_rank" class="errorMsg" style="margin-top : 3px;display:none;"></div>
	    </div>
	</li>
	<li <?php if($showRankType=='NO'){ ?> style="display: none;"<?php } ?>>
		<label>Enter Rank Type:</label>
	    <div class="predictor-field-wrap">
		<?php if($showRankType=='NO' && ($rankType=='HOME' || $rankType=='Home')){ ?>
	    	<select class="pre-select" id="rank_type" name="rank_type">
	        	<option value="Home">Home State Rank</option>
	        </select>		
		<?php }else if($showRankType=='YES' && $examNameNew == 'MHCET'){
		?>
			<select class="pre-select" id="rank_type" name="rank_type" onchange="if($j('#rank_type').val() == 'Home' && $j('#noStateDropDown').val() != 1){$j('#state_container').show();}else {$j('#state_container').hide();}">>
				<option value="Other" <?php if(!empty($data->rankType) && $data->rankType == 'Other') { echo 'selected';}?>  >All India</option>
				<option value="HomeUniversity" <?php if(!empty($data->rankType) && $data->rankType == 'HomeUniversity') { echo 'selected';}?>  >Home University</option>
				<option value="OtherThanHome" <?php if(!empty($data->rankType) && $data->rankType == 'OtherThanHome') { echo 'selected';}?>  >Other Than Home University</option>
				<option value="StateLevel" <?php if(!empty($data->rankType) && $data->rankType == 'StateLevel') { echo 'selected';}?>  >State Level</option>
			</select>
		<?php 
		}else if($showRankType=='YES' && $examNameNew == 'KCET'){
		?>
			<select class="pre-select" id="rank_type" name="rank_type" onchange="if($j('#rank_type').val() == 'Home' && $j('#noStateDropDown').val() != 1){$j('#state_container').show();}else {$j('#state_container').hide();}">>
				<option value="KCETGeneral" <?php if(!empty($data->rankType) && $data->rankType == 'KCETGeneral') { echo 'selected';}?>  >General</option>
				<option value="HyderabadKarnatakaQuota" <?php if(!empty($data->rankType) && $data->rankType == 'HyderabadKarnatakaQuota') { echo 'selected';}?>  >Hyderabad-Karnataka Quota</option>
			</select>
		<?php 
		}else{ ?>
	    	<select class="pre-select" id="rank_type" name="rank_type" onchange="if($j('#rank_type').val() == 'Home' && $j('#noStateDropDown').val() != 1){$j('#state_container').show();}else {$j('#state_container').hide();}">>
	        	<option value="Other" <?php if(!empty($data->rankType) && $data->rankType == 'Other') { echo 'selected';}?>  >All India Rank</option>
	        	<option value="Home" <?php if(!empty($data->rankType) && $data->rankType == 'Home') { echo 'selected';}?>>Home State Rank</option>
	    	</select>
		<?php } ?>
	    </div>
	</li>
	<li id="state_container" style="display:<?php if(!empty($data->stateName) && $showRankType=='YES'){echo 'block';}else {echo 'none';}?>">
		<label>Select State:</label>
	    <div class="predictor-field-wrap">
		<?php if(($showRankType=='NO' && ($rankType=='HOME' || $rankType=='Home')) || $noStateDropDown == 1){ ?>
	    	<select class="pre-select" id="state" name="state">
	        	<option value="<?php echo $stateName?>"><?php echo $stateName?></option>        	
	        </select>		
		<?php }else{ ?>
	    	<select class="pre-select" id="state" name="state">
	        	<option value="">Select</option>
	        	<?php foreach ($states as $index => $value):?>
	        		<?php if(!empty($value['stateName'])) { ?>
	        			<option value="<?php echo $value['stateName']?>" <?php if(!empty($data->stateName) && $data->stateName == $value['stateName']) {echo 'selected';}?> ><?php echo $value['stateName'];?></option>
	        		<?php } ?>
	        	<?php endforeach;?>
	        </select>
		<?php } ?>
	        <div id="error_state" class="errorMsg" style="margin-top : 3px;display:none;">select your state</div>
	    </div>
	</li>	
	 <li <?php if(count($categoryData)==1){ ?> style="display: none;"<?php } ?>>
		<label>Select Category:</label>
	    <div class="predictor-field-wrap">
	    	<select class="pre-select" id="category" name="category">
	        	<?php foreach($categoryData as $key=>$value){ ?>
	        	<option value="<?php echo $key;?>" <?php if(!empty($data->categoryName) &&  $data->categoryName ==  $key) {echo 'selected';}?> ><?php echo $value;?></option>
			<?php } ?>
	    	</select>
	    </div>
	</li>
	<li>
		<label <?php if(count($roundData)==1){ ?> style="display: none;"<?php } ?>>Select Round:</label>
	    <div class="predictor-field-wrap">
	    	<select class="pre-select" id="round" name="round" <?php if(count($roundData)==1){ ?> style="display: none;"<?php } ?>>
			<?php foreach($roundData as $key=>$value){ ?>
			<option value="<?php echo $key;?>" <?php if(!empty($data->round) &&  $data->round ==  $key) {echo 'selected';}?> ><?php echo $value;?></option>
			<?php } ?>
	    	</select>
	        <div class="spacer15 clearFix"></div>
	        <a href="javascript:void(0);" onclick="if(!validateSearchForm()){return false;}else{searchData('3','','rank','asc','search','',<?=$trackingKeyId?>, '<?=$directoryName?>');} ; setCookie('showBox','no',0 ,'/',COOKIEDOMAIN);  trackEventByGA('SearchClick','SEARCH_BUTTON');" class="orange-button2">Search <i class="predictor-sprite search-arr"></i></a>
	    </div>
	</li>
	</ol>
	</form>
	<?php $examNameSmall = strtolower($examNameNew);?>
	<div class="branch-image" <?php if($examNameSmall=='comedk'){ ?> style="height: 210px;"<?php }?>>
		<!--<img src="public/images/collegepredictor/<?php //echo $examNameNew;?>/branch-image.jpg"  alt="<?php //echo $imageTitle;?>" title="<?php //echo $imageTitle;?>"  /></div>-->
		<span class="branch-image-<?php echo $examNameSmall;?>"></span>
	<div class="clearFix"></div>
	</div>
	<div class="clearFix"></div>
	
</div>
<script>

var branchJson = <?php echo json_encode($branches,true);?>;

</script>
