<?php
$data= array();
$examNameNew = strtoupper($examName);
if(isset($_COOKIE["collegepredictor_search_desktop_".$examinationName])) {
	$data = $_COOKIE["collegepredictor_search_desktop_".$examinationName];
	$data = json_decode($data);
	if($data->tabType == 'college') { 
		$data = $data;
	}else {
		$data = array();
	}
	
	$data1 = json_decode($_COOKIE["collegepredictor_search_desktop_".$examinationName],true);

}

	if($seoUrl) {
		$data1['colleges'] = $colleges;
	}
	

	$count = 0 ;
	$count = (!empty($data->colleges)) ? count($data->colleges) : count($data1['colleges']);
?>
<script>currentPageName='College Predictor - Cut Off Predictor Tab';</script>
<div id="predictor-tab-details"  <?php if(!$defaultView):?> style="display: none;" <?php endif;?>>
	<form id="search_form" >
	<input type="hidden" value="college" name="tabType" />
	<ol>
	<li>
		<label>Select Preferred Colleges:</label>
	    <div class="predictor-field-wrap">
	    	<div class="pre-select" style="width:207px; position:relative; padding: 4px 5px; position:relative" onmouseover="$j('#college_selector').show();"  onmouseout="if(getCookie('showOverlayInst') == 'no') $j('#college_selector').hide();" >Selected (<span id="institute_selected_number"><?php if(!empty($count)){echo $count;}else {echo '0';} ?></span>)
	        	<div class="dropdown-layer customInputs" id="college_selector" style="display:none; width:350px;" >
	            	<div class="filter-search" onclick="setCookie('showOverlayInst','yes',0 ,'/',COOKIEDOMAIN);" style="width:300px;">
					    <i class="predictor-sprite filter-search-icon"></i>
					    <input type="text" onclick="$j('#institute_text').val('');" onkeyup="getInstitutes();" id="institute_text" value="Search Institute ..." style="color:#7F7F7F">
					</div>
            						<ul class="dropdown-list" id="collge_selector_container" onclick="setCookie('showOverlayInst','yes',0 ,'/',COOKIEDOMAIN);" style="height: 162px; overflow-y: auto;"	 		>
            							<?php $this->load->view('CP/searchInstitutesCP',$data1);?>
  	              					</ul>
  	              <div class="tac clear-width" style="margin:8px 0 2px 0; border-top:1px solid #ccc; padding-top:5px"><a class="orange-button2" href="javascript:void(0)" onclick="selectInstitutes();" style="padding:4px 10px; font-size:13px">Ok</a></div>
	            </div>
	        </div>
	        <div id="error_institute" class="errorMsg" style="margin-top : 3px;display:none;"></div>
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
	<li <?php if($showRankType=='NO'){ ?> style="display: none;"<?php } ?>>
		<label>Avail State Quota:</label>
	    <div class="predictor-field-wrap">
		<?php if($showRankType=='NO' && ($rankType=='HOME' || $rankType=='Home')){ ?>
	    	<select class="pre-select" id="state_quota" name="quota" >
	        	<option value="yes">YES</option>
	        </select>		
		<?php }else if($showRankType=='YES' && $examNameNew == 'MHCET' && in_array($rankType, array('StateLevel', 'HomeUniversity'))){
		?>
			<select class="pre-select" id="state_quota" name="quota" >
	        	<option value="yes">YES</option>
	        </select>
		<?php 
		}else if($showRankType=='YES' && $examNameNew == 'KCET' && $rankType == 'HyderabadKarnatakaQuota'){
		?>
			<select class="pre-select" id="state_quota" name="quota" >
	        	<option value="yes">YES</option>
	        </select>
		<?php 			
		}else{ ?>
	    	<select class="pre-select" id="state_quota" name="quota" onchange="if($j('#state_quota').val() == 'yes' && $j('#noStateDropDown').val() != 1){$j('#state_container').show();}else {$j('#state_container').hide();}">
	        	<option vlaue="no" <?php if(!empty($data->domicile) && $data->domicile == 'NO' ){echo 'selected';} ?> >No</option>
	        	<option value="yes" <?php if(!empty($data->domicile) && $data->domicile == 'YES' ){echo 'selected';} ?>  >Yes</option>
	        </select>
		<?php } ?>
	    </div>    
	</li>
	<li id="state_container" style="display:none;">
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
	<li>
		<label <?php if(count($roundData)==1){ ?> style="display: none;"<?php } ?>>Select Round:</label>
	    <div class="predictor-field-wrap">
	    	<select class="pre-select" id="round" name="round" <?php if(count($roundData)==1){ ?> style="display: none;"<?php } ?>>
			<?php foreach($roundData as $key=>$value){ ?>
			<option value="<?php echo $key;?>" <?php if(!empty($data->round) &&  $data->round ==  $key) {echo 'selected';}?> ><?php echo $value;?></option>
			<?php } ?>
	    	</select>
	        <div class="spacer15 clearFix"></div>
	        <a href="javascript:void(0);" onclick="if(!validateSearchForm()){return false;}else{searchData('2','','rank','asc','search','',<?=$trackingKeyId?> , '<?=$directoryName?>');}; setCookie('showBox','no',0 ,'/',COOKIEDOMAIN); trackEventByGA('SearchClick','SEARCH_BUTTON'); " class="orange-button2">Search <i class="predictor-sprite search-arr"></i></a>
	    </div>
	</li>
	</ol>
	</form>
	<?php $examNameSmall = strtolower($examNameNew);?>
	<div class="cutoff-image" <?php if($examNameSmall=='comedk'){ ?> style="height: 210px;"<?php }?>>
		<!--<img src="public/images/collegepredictor/<?php //echo $examNameNew;?>/cutoff-image.png"  alt="<?php //echo $imageTitle;?>" title="<?php //echo $imageTitle;?>" /></div>-->
		<span class="cutoff-image-<?php echo $examNameSmall;?>"></span>
	
	<div class="clearFix"></div>
	</div>
	<div class="clearFix"></div>
	</div>
<script>

var instituteJson = <?php echo json_encode($institutes,true);?>;
var instituteGroupJson = <?php echo json_encode($instituteGroups,true);?>;

</script>	
