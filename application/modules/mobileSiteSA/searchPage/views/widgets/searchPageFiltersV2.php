<?php
$feesDetails = $filters['fees_parent']; // max and min 
$examDetails = $filters['exam_parent']; // name id count and scores
$courseDetails = $filters['course_parent']; // either category, subcategory or specialization
$courseDuration = $filters['duration_parent']; // min and maximum
$courseLevel = $filters['courseLevel_parent']; // level and count
$courseDeadline = $filters['deadline_parent']; // dates and seasons 
$scholarship = $filters['scholarship_parent'];
$location = $filters['location_parent'];
$rmc = $filters['rmc_parent']; // count valie
$sop = $filters['sop_parent']; // count valie
$lor = $filters['lor_parent']; // count valie
$course12thDetails = $filters['12thCutoff_parent']; // cut off and count
$ugCutOff = $filters['UGCutoff_parent']; // cut off and count
$workExperience = $filters['WorkExperience_parent']; // cut off and count
$feesDetailsCurrent = $filters['fees']; // max and min 
$examDetailsCurrent = $filters['exam']; // name id count and scores
$courseDetailsCurrent = $filters['course']; // either category, subcategory or specialization
$courseDurationCurrent = $filters['duration']; // min and maximum
$courseLevelCurrent = $filters['courseLevel']; // level and count
$courseDeadlineCurrent = $filters['deadline']; // dates and seasons 
$scholarshipCurrent = $filters['scholarship'];
$locationCurrent = $filters['location'];
$rmcCurrent = $filters['rmc']; // count valie
$sopCurrent = $filters['sop']; // count valie
$lorCurrent = $filters['lor']; // count valie
$course12thDetailsCurrent = $filters['12thCutoff']; // cut off and count
$ugCutOffCurrent = $filters['UGCutoff']; // cut off and count
$workExperienceCurrent = $filters['WorkExperience']; // cut off and count
$filterInitialState = $filters['originalState'];
?>
<div class="src-cont" id ="<?php echo ($filterUpdateCall == 1)?"filterLayerNew":"filterLayer";?>" data-role="page" data-enhance="false">
<script>
var filterdata = <?php echo json_encode($filters); ?>;
</script>
<div id="ftwrap" data-enhance="false" style="height:100%;overflow-y: auto;overflow-x: hidden">
<input type="hidden" id="ost" value="<?php echo $filterInitialState;?>"><?php if($filterUpdateCall == 1) {?><div class="src-head">
<p class="src-title">Filter your results</p>
<a href="javaScript:void(0);" id="closeLayer" class="src-rmv" data-transition="slide" data-rel="back" data-direction="reverse"><i class="search-sprite src-crss"></i></a>
</div>
<div class="fil-selctn" style="display:none">
<a id="clrall" class="clr-al" href="javascript:void(0);" >Clear All</a>
</div>
<div class="fil-cat">
<div class="fil-sec" id="searchFilter">
<ul class="fil-con">
<?php if(count($workExperience)>0){?>
<li data-tabid="1">
<a href="#" class="fil-col">Work Experience</a>
</li>
<?php
}if(count($ugCutOff)>0){
?>
<li data-tabid="2" class="">
<a href="#" class="fil-col">Bachelors Marks</a>
</li>
<?php
}if(count($course12thDetails)>0){
?>
<li data-tabid="3" class="">
<a href="#" class="fil-col">Class 12th Marks</a>
</li>
<?php
}if(count($feesDetails)>0){
?>
<li data-tabid="4" class="">
<a href="#" class="fil-col">Fees &amp; Expenses</a>
</li>
<?php
}if(count($filters['exam_parent'])>0){
?>
<li data-tabid="5" class="">
<a href="#" class="fil-col">Eligibility Exam</a>
</li>
<?php
}if(count($specFilter)>0){
?>
<li data-tabid="8" >
<a href="#" class="fil-col">Specialization</a>
</li>
<?php
}
?>
<?php if(count($courseDeadline['dates'])>0) { ?>
<li data-tabid="9" class="">
<a href="#" class="fil-col">Application Deadline</a>
</li>
<?php }?>
<?php if(count($courseDeadline['seasons'])>0){?>
<li data-tabid="10" class="">
<a href="#" class="fil-col">Intake Season</a>
</li>
<?php }       ?>
<?php
if(count($locationFilter)>0){
?>
<li data-tabid="11" class="">
<a href="#" class="fil-col">Location</a>
</li>
<?php
}if(count($scholarship)>0){
?>
<li data-tabid="12" class="">
<a href="#" class="fil-col">Scholarship</a>
</li>
<?php
}if(count($courseLevel)>0){
?>
<li data-tabid="13" class="">
<a href="#" class="fil-col">Course Level</a>
</li>
<?php
}
?>
<?php
if(count($courseDuration)>0){
?>
<li data-tabid="15" class="">
<a href="#" class="fil-col">Course Duration</a>
</li>
<?php
}?>
<li data-tabid="16" class="">
<a href="#" class="fil-col">Application Process</a>
</li>
</ul>
<div class="options">
<?php if(count($workExperience)>0){?>
<div id="srchfilterTab1" class="tbs loc-list" style="display: none;">
<div class="sld-tab">
<div id="wExShd" class="sld-head" data-pattern="%mn% year(s) -- %mx% year(s)"></div>
<div class="slider-div">
<div id="wExS" name="<?php echo $searchFieldParams['workex'];?>" data-max="<?php echo $workExperience['max']; ?>" data-min="<?php echo $workExperience['min']; ?>" data-scale=" <?php echo $workExperience['scale']; ?>" data-val="<?php echo $searchFieldParams['workex'] ;?>" ></div>	
<div id="upwExS" class='up-range' data-pattern="%mx% year(s)">%mx% year(s)</div>
<div id="dnwExS" class='dwn-range' data-pattern="%mn% year(s)">%mn% year(s)</div>
</div>
</div>
</div>
<?php
}if(count($ugCutOff)>0){
?>
<div id="srchfilterTab2" class="tbs crseL-list" style="display: none;">
<div class="sld-tab">
<div id="bcmShd" class="sld-head" data-pattern="%mn%% -- %mx%%"></div>
<div class="slider-div">
<div id="bcmS" name="<?php echo $searchFieldParams['ugMarks'];?>" data-max="<?php echo $ugCutOff['max']; ?>" data-min="<?php echo $ugCutOff['min']; ?>" data-scale=" <?php echo $ugCutOff['scale']; ?> " data-val="<?php echo $searchFieldParams['ugMarks'] ;?>"></div>	
<div id="upbcmS" class='up-range' data-pattern="%mx%%">%mx%%</div>
<div id="dnbcmS" class='dwn-range' data-pattern="%mn%%">%mn%%</div>
<p class="sliderNte"><strong>Note:</strong> Universities have different scales for GPA to percentage conversion. For sake of simplicity, multiply GPA by 25 to get percentage.</p>
</div>
</div>
</div>
<?php
}if(count($course12thDetails)>0){
?>
<div id="srchfilterTab3" class="tbs crseL-list" style="display: none;">
<div class="sld-tab">
<div id="mrk12hd" class="sld-head" data-pattern="%mn%% -- %mx%%"></div>
<div class="slider-div">
<div id="mrk12" name="<?php echo $searchFieldParams['class12'];?>" data-max="<?php echo $course12thDetails['max']; ?>" data-min="<?php echo $course12thDetails['min']; ?>" data-scale=" <?php echo $course12thDetails['scale']; ?>" data-val="<?php echo $searchFieldParams['class12'] ;?>"></div>	
<div id="upmrk12" class='up-range' data-pattern="%mx%%">%mx%%</div>
<div id="dnmrk12" class='dwn-range' data-pattern="%mn%%">%mn%%</div>
</div>
</div>
</div>
<?php
}if(count($feesDetails)>0){
?>
<div id="srchfilterTab4" class="tbs crseL-list" style="display: none;">
<div class="sld-tab">
<div id="feeshd" class="sld-head" data-pattern="Rs %mn% Lakh(s) -- %mx% Lakh(s)"></div>
<div class="slider-div">
<div id="fees" name="<?php echo $searchFieldParams['courseFee'];?>" data-max="<?php echo $feesDetails['max']; ?>" data-min="<?php echo $feesDetails['min']; ?>" data-scale="<?php echo $feesDetails['scale']; ?>" data-val="<?php echo $searchFieldParams['courseFee'];?>"></div>	
<div id="upfees" class='up-range' data-pattern="Rs %mx% Lakh(s)">Rs %mx% Lakh(s)</div>
<div id="dnfees" class='dwn-range' data-pattern="Rs %mn% Lakh(s)">Rs %mn% Lakh(s)</div>
</div>
</div>
</div>
<?php
}if(count($filters['exam_parent'])>0){
?>
<div id="srchfilterTab5" class="tbs crseL-list" style="display: none;">
<div class="ap-col">
<ul class="a-ul">
<?php foreach ($filters['exam_parent'] as $examDetails) { ?> 
<li>
<div class="e-col">
<input type="radio" id="ed<?php echo $examDetails['id']; ?>" class="exmR" name="<?php echo $searchFieldParams['exams'] ?>" data-enhance="false" value="<?php echo $searchFieldParams['exams'].'_'.$examDetails['name']; ?>" <?php echo ($examDetails['disabled']==1)?'data-disabled="disabled"':""?> >
<label for="ed<?php echo $examDetails['id']; ?>"> <?php echo $examDetails['name']; ?> <span><?php echo "(" . $examDetails['count'] . ")" ;?></span></label>
</div>  
<div class="sld-tab" style="display: none">
<div id="eScr<?php echo $examDetails['id']; ?>hd" class="sld-head" data-pattern="Score between %mn%-%mx%"></div>
<div class="slider-div">
<div class="exScr" name="<?php echo $searchFieldParams['examScore'] ?>" id="eScr<?php echo $examDetails['id']; ?>" data-max="<?php echo $examDetails['scores']['max']; ?>" data-min="<?php echo $examDetails['scores']['min']; ?> " data-scale="<?php echo $examDetails['scores']['scale']; ?> " data-val="<?php echo $searchFieldParams['examScore']; ?>"></div>
<div id="upeScr<?php echo $examDetails['id']; ?>" class='up-range' data-pattern="%mx%">%mx%</div>
<div id="dneScr<?php echo $examDetails['id']; ?>" class='dwn-range' data-pattern="%mn%">%mn%</div>
</div>
</div>
</li>
<?php } ?>
</ul>
</div>
</div>
<?php
}if(count($specFilter)>0){
?>
<div id="srchfilterTab8" class="tbs crseL-list" style="display:none">
<div class="ap-col">
<div class="filter-l">
<i class="search-sprite src-icn"></i>
<input type="text" name="spl" placeholder="Search" class="sa-srch" >
</div>
</div>
<?php
if (count($specFilter) > 0) {
?>
<div class="accordion">
<?php
foreach ($specFilter as $specDetails) {
?>
<div class="n-accordion <?php if(count($specDetails['children']) == 0){echo 'n-accrWht';} ?>">
<div style="width:100%; float:left;">
<div class="spc-box">
<input type="checkbox" name="<?php echo $specDetails['type']; ?>" id="<?php echo $specDetails['type'].$specDetails['id']; ?>" value="<?php echo $specDetails['type'].'_'.$specDetails['id']; ?>" style="margin:8px 6px 10px 10px;" <?php if(isset($specDetails['repeat']) && $specDetails['repeat']==1){ echo 'data-repeat-par=1';} ?><?php echo ($specDetails['disabled']==true)?' data-disabled="disabled"':""?>>
<span class="spc-chkbx <?php echo ($specDetails['disabled']==true)?' disbld':""?>"></span>
</div>
<div class="accr-a <?php if(count($specDetails['children']) > 0){echo 'accSign';} ?>">
<div style="width:85%;"><label><?php echo $specDetails['heading']; ?></label>&nbsp; <span> (<?php echo $specDetails['count']; ?>)</span>
</div>
</div>
</div> 
<?php
if (count($specDetails['children']) > 0) {
?>                <div class="sec-cont" style="display:block;">
<ul class="a-ul">

<?php
foreach ($specDetails['children'] as $childrenDetails) {
?> 
<li>
<div class="r-col">
<input type="checkbox" name="<?php echo $childrenDetails['type']; ?>" id="<?php echo $childrenDetails['type'].$childrenDetails['id']; ?>" value="<?php echo $childrenDetails['type'].'_'.$childrenDetails['id']; ?>" <?php if(isset($childrenDetails['repeat']) && $childrenDetails['repeat']==1){ echo 'data-repeat=1';} ?> <?php echo ($childrenDetails['disabled']==true)?' data-disabled="disabled"':""?>> 
<label for="<?php echo $childrenDetails['type'].$childrenDetails['id']; ?>"> <?php echo $childrenDetails['heading']; ?> <span>(<?php echo $childrenDetails['count']; ?>)</span> </label> 
</div></li> 
<?php
}
?> 
</ul>
</div>         
<?php
}
?> 
</div>
<?php
}
?>
<p class="nRst" style="display:none"></p>
</div>
<?php
}
?>
</div>
<?php
}
?>
<?php if(count($courseDeadline['dates'])>0) {?>
<div id="srchfilterTab9" class="tbs loc-list" style="display: none;">
<div class="c-d">
<div class="n-div" id="ddntc">
<h2 class="div-titl">Deadline : Not Chosen</h2>
<div class="cal-div">
<a href="#datepickerlayer" class="dlne-det" data-rel="dialog" data-inline="true" ><i class="search-sprite cal-icn" ></i> Choose Deadline</a>
</div>
</div>
<div class="n-div" id="ddc" style="display: none;">
<h2 class="div-titl">Deadline : Chosen</h2>
<div class="cal-div">
<strong class="dline-date" id="sldate"></strong>
<a href="#" class="dlne-det" id="rmdd"><i class="search-sprite rmv-dline"></i> Remove Deadline</a>
</div>
</div>
</div>
</div>
<?php } ?>
<?php if(count($courseDeadline['seasons'])>0){?>
<div id="srchfilterTab10" class="tbs crseL-list" style="display: none;">
<div class="ap-col">
<ul class="a-ul">
<?php foreach ($courseDeadline['seasons'] as $index => $seasonDetails) { ?>
<li>
<div class="e-col">
<input type="radio" id="cd<?php echo $index; ?>" name="<?php echo $searchFieldParams['intseason'];?>" data-enhance="false" value="<?php echo $searchFieldParams['intseason'].'_'.$seasonDetails['season']; ?>" <?php echo ($seasonDetails['disabled']==1)?'data-disabled="disabled"':""?>>
<label for="cd<?php echo $index; ?>"> <?php echo $seasonDetails['season'] ?> <span><?php echo '(' . $seasonDetails['count'] . ')'; ?></span></label>
</div>  
</li>
<?php } ?>  </ul>       
</div>
</div>
<?php }       ?>
<?php
if(count($locationFilter)>0){
?>
<div id="srchfilterTab11" class="tbs crseL-list" style="display:none">
<div class="ap-col">
<div class="filter-l">
<i class="search-sprite src-icn"></i>
<input type="text" name="lct" placeholder="Search" class="sa-srch">
</div>
</div>
<?php
if (count($locationFilter) > 0) {
?>
<div class="accordion">
<?php
foreach ($locationFilter as $locationDetails) {
?>
<div class="n-accordion <?php if(count($locationDetails['children']) == 0){echo 'n-accrWht';} ?>">
<div style="width:100%; float:left;">
<div class="spc-box">
<input type="checkbox" name="<?php echo $locationDetails['type']; ?>" id="<?php echo $locationDetails['type'].$locationDetails['id']; ?>" value="<?php echo $locationDetails['type'].'_'.$locationDetails['id']; ?>" style="margin:8px 0 0 10px;" <?php echo ($locationDetails['disabled']==1)?'data-disabled="disabled"':""?>>
<span class="spc-chkbx <?php echo ($locationDetails['disabled']==1)?'disbld':""?>"></span>
</div>
<div class="accr-a <?php if(count($locationDetails['children']) > 0){echo 'accSign';}?>">
<div style="width:85%;"> <label><?php echo $locationDetails['heading']; ?></label>  <span>(<?php echo $locationDetails['count']; ?>)</span>
</div></div>
</div> 
<?php
if (count($locationDetails['children']) > 0) {
?>                <div class="sec-cont" style="display:block;">
<ul class="a-ul">

<?php
foreach ($locationDetails['children'] as $childrenDetails) {
?> 
<li>
<div class="r-col">
<input type="checkbox" name="<?php echo $childrenDetails['type']; ?>" id="<?php echo $childrenDetails['type'].$childrenDetails['id']; ?>" value="<?php echo $childrenDetails['type'].'_'.$childrenDetails['id']; ?>" <?php echo ($childrenDetails['disabled']==1)?'data-disabled="disabled"':""?>>
<label for="<?php echo $childrenDetails['type'].$childrenDetails['id']; ?>"> <?php echo $childrenDetails['heading']; ?> <span> (<?php echo $childrenDetails['count']; ?>)</span></label>
</div></li> 
<?php
}
?> 

</ul>
</div>         
<?php
}
?> 
</div>
<?php
}
?>
<p class="nRst" style="display:none" > </p>
</div>
<?php
}
?>
</div>
<?php
}if(count($scholarship)>0){    
?>
<div id="srchfilterTab12" class="tbs crseL-list" style="display: none;">
<div class="ap-col">
<ul class="a-ul">
<?php if(isset($scholarship['Yes'])){ ?>
<li>
<div class="r-col">
<input type="checkbox" name="<?php echo $searchFieldParams['scholarship']; ?>" id="sc" name="sch" value="<?php echo $searchFieldParams['scholarship']; ?>_Yes" data-filter-val="Scholarship (Available)" <?php echo ($scholarship['Yes_disabled']==1)?'data-disabled="disabled"':'';?>>
<label for="sc">Available <span>(<?php echo $scholarship["Yes"]; ?>)</span></label>
</div>  
</li>
<?php }if(isset($scholarship['No'])) {?>
<li>
<div class="r-col">
<input type="checkbox" name="<?php echo $searchFieldParams['scholarship']; ?>" id="scn" name="<?php echo $searchFieldParams['scholarship']; ?>" value="<?php echo $searchFieldParams['scholarship']; ?>_No" data-filter-val="Scholarship (Not Available)" <?php echo ($scholarship['No_disabled']==1)?'data-disabled="disabled"':""?>>
<label for="scn">Not Available <span>(<?php echo $scholarship["No"] ;?>)</span></label>
</div>  
</li>
<?php }?>
</ul>
</div>
</div>
<?php
}if(count($courseLevel)>0){
?>
<div id="srchfilterTab13" class="tbs crseL-list" style="display: none;">
<div class="ap-col">
<ul class="a-ul">
<?php foreach ($courseLevel as $index => $courseLevelDetails) {
$courseLevelDetails['level']=  ucwords($courseLevelDetails['level']);
$levelNameToBeDisplayed=$courseLevelDetails['level'];
if($courseLevelDetails['level']=='Masters'){
$levelNameToBeDisplayed.=" Degree";
}
if($courseLevelDetails['level']=='Bachelors'){
$levelNameToBeDisplayed.=" Degree";
}
?>
<li>
<div class="r-col">
<input type="checkbox" id="cl<?php echo $index; ?>" name="<?php echo $searchFieldParams['level']; ?>"  value="<?php echo $searchFieldParams['level'].'_'.$courseLevelDetails['level']; ?>" <?php echo ($courseLevelDetails['disabled']?'data-disabled="disabled"':''); ?>>
<label for="cl<?php echo $index; ?>"> <?php echo $levelNameToBeDisplayed; ?> <span>(<?php echo $courseLevelDetails['count'];?>)</span></label>
</div>  
</li>
<?php } ?>
</ul>
</div>
</div>
<?php
}
?>
<?php
if(count($courseDuration)>0){
?>
<div id="srchfilterTab15" class="tbs crseL-list" style="display: none;">
<div class="sld-tab">
<div id="crdurhd" class="sld-head" data-pattern="%mn% months - %mx% months"></div>
<div class="slider-div">
<div id="crdur" name="<?php echo $searchFieldParams['courseDuration']; ?>" data-min="<?php echo $courseDuration['min']; ?>" data-max="<?php echo $courseDuration['max']; ?>" data-scale="<?php echo $courseDuration['scale']; ?>" data-val="<?php echo $searchFieldParams['courseDuration']; ?>"></div>
<div id="upcrdur" class='up-range' data-pattern="%mx% months">%mx% months</div>
<div id="dncrdur" class='dwn-range' data-pattern="%mn% months">%mn% months</div>
</div>
</div>
</div>
<?php
}?>
<div id="srchfilterTab16" class="tbs crseL-list" style="display: none;">
<div class="ap-col">
<ul class="a-ul">
<?php
if (is_numeric($sop)) {
?>
<li>
<div class="r-col">
<input type="checkbox" name="<?php echo $searchFieldParams['sop']; ?>" id="sop" value="<?php echo $searchFieldParams['sop'].'_'.'1'; ?>" <?php echo ($sop==0)?'data-disabled="disabled"':""?> >
<label for="sop">SOP <span> (<?php echo $sop; ?>)</span></label>
</div>  
</li>
<?php } ?>
<?php
if (is_numeric($lor)) {
?>
<li>
<div class="r-col">
<input type="checkbox" name="<?php echo $searchFieldParams['lor']; ?>" id="lor" value="<?php echo $searchFieldParams['lor'].'_'.'1'; ?>" <?php echo ($lor==0)?'data-disabled="disabled"':""?>>
<label for="lor">LOR <span> (<?php echo $lor; ?>)</span></label>
</div>  
</li>
<?php } ?>
</ul>
</div>
</div>
</div>
</div>
<a href="#" id="alyFtr" class="aSrc-btn fil-btn">Apply All Filters</a>
</div>
<input type="hidden" value="<?php echo $maxFeesValue; ?>" id="mFv">
<input type="hidden" value="<?php echo $maxWexValue; ?>" id="mWv">
<input type="hidden" id="fsh" value="<?php echo $feesDetails['showPlus'];?>">
<?php } ?> 
</div></div>
<div class="src-cont" id ="datepickerlayer" data-role="page" data-enhance="false">
<div class="src-head">
<p class="src-title">Select deadline</p>
<a href="#filterLayer" class="src-rmv" data-transition="slide" data-direction="reverse" data-rel="back"><i class="search-sprite src-crss"></i></a>
</div>
<div id="datepick" class="ctdtp" name="<?php echo $searchFieldParams['apdeadline']; ?>" data-min="<?php echo $courseDeadline['dates'][0]; ?>" data-max="<?php echo $courseDeadline['dates'][count($courseDeadline['dates']) - 1]; ?>" data-val="<?php echo $searchFieldParams['apdeadline']; ?>"></div>
<div class="done-btn">
<a href="#filterLayer" class="aSrc-btn" id="selectDate" data-transition="slide"  data-direction="reverse" data-rel="back">Done</a>       
</div>
</div>