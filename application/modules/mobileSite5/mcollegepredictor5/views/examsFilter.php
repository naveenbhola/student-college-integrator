<?php
if((!empty($filters['defaultCollegePredictorFilters']['round']) &&
                 count($filters['defaultCollegePredictorFilters']['round'])>1 && trim($searchParameters['tabType'])!='round' ) ||
(!empty($filters['defaultCollegePredictorFilters']['branch']) &&
                count($filters['defaultCollegePredictorFilters']['branch'])>1 &&
                trim($searchParameters['tabType'])!='branch')  ||
(!empty($filters['defaultCollegePredictorFilters']['location']) &&
                count($filters['defaultCollegePredictorFilters']['location'])>1 &&
                trim($searchParameters['tabType'])!='location')||
(!empty($filters['defaultCollegePredictorFilters']['college']) &&
                count($filters['defaultCollegePredictorFilters']['college'])>1 &&
                trim($searchParameters['tabType'])!='college')){
                 }else{
                        echo "NOFILTER";exit;
                 } ?>
<div style="background:#efefef !important" id="refineDivInner">

 	<header id="page-header" class="clearfix">
		<div class="head-group">
		    <a id="examFilterOverlayClose" href="javascript:void(0);" data-rel="back" onclick="searchCall='true';showEmail();"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
		    <h3>
			<div class="left-align">
				Refine Your Results
			</div>
		    </h3>
		</div>
	</header>
        <?php $tabMapping = array('rank'=>'1','college'=>'2','branch'=>'3')?>
       <?php if($searchParameters['round']=='all' && count($filters['defaultCollegePredictorFilters']['round'])>1){ ?>
	<section class="refine-section" style="margin-top: 15px;">
	    <label class="text-shadow-w">Round</label>
	    <a href="#roundFilterDiv" class="selectbox" data-inline="true" data-rel="dialog" data-transition="slide"><p><span id="roundSelectionText"><?php if($roundFilter && $roundFilter!=''){ echo 'Selected ('.$roundFilter.')';} else {echo "Change Round";}?></span><i class="icon-select2"></i></p></a>
	</section>
        <?php } ?>
        <?php if($searchParameters['tabType']!='branch' && count($filters['defaultCollegePredictorFilters']['branch'])>1){ ?>
	<section class="refine-section">
	    <label class="text-shadow-w">Branch</label>
	    <a href="#branchFilterDiv" class="selectbox" data-inline="true" data-rel="dialog" data-transition="slide"><p><span id="branchSelectionText"><?php if($branchFilter && $branchFilter!=''){ echo 'Selected ('.$branchFilter.')';} else {echo "Change Branch";}?></span><i class="icon-select2"></i></p></a>
	</section>
        <?php } ?>
        <?php if(count($filters['defaultCollegePredictorFilters']['location'])>1){ ?>
	<section class="refine-section">
	    <label class="text-shadow-w">Location</label>
	    <a href="#locationFilterDiv" class="selectbox" data-inline="true" data-rel="dialog" data-transition="slide"><p><span id="locationSelectionText"><?php if($locationFilter && $locationFilter!=''){ echo 'Selected ('.$locationFilter.')';} else {echo "Change Location";}?></span> <i class="icon-select2"></i></p></a>
	</section>
        <?php } ?>
        <?php if(trim($searchParameters['tabType'])!='college' && count($filters['defaultCollegePredictorFilters']['college'])>1){ ?>
        <section class="refine-section">
	    <label class="text-shadow-w">Colleges</label>
	    <a href="#collegeFilterDiv" class="selectbox" data-inline="true" data-rel="dialog" data-transition="slide"><p><span id="collegeSelectionText"><?php if($collegeFilter && $collegeFilter!=''){ echo 'Selected ('.$collegeFilter.')';}else {echo "Change College";}?></span><i class="icon-select2"></i></p></a>
	</section>
        <?php } ?>
        <div class="clearfix" id="button_div">
	<div style="margin:0 0 5px">
	<a href="javascript:void(0);" onclick="searchCall='true';filterTypeDataStatus = 'true';refineFilters(0,'<?=$tabMapping[$searchParameters['tabType']];?>','YES');trackEventByGAMobile('HTML5_JEECollegePredictor_Filter_Refine');" class="refine-btn">Refine</a>
	</div>
	<a href="javascript:void(0);" onclick="resetFilters();trackEventByGAMobile('HTML5_JEECollegePredictor_Filter_Reset');" class="cancel-btn flLt" style="width:49%; background:#b7b5b6;">Reset</a>
        <a href="javascript:void(0);" onClick="searchCall='true';cancelExamFilter();trackEventByGAMobile('HTML5_JEECollegePredictor_Filter_Cancel');" class="cancel-btn flRt" style="width:49%;">Cancel</a>
        </div>
        
</div>