<?php
$examNameNew = strtoupper($examName);
$count = 0 ;
?>
<?php if($examNameNew == 'JEE-MAINS'){ ?>
<div class="hlf-tabDv">
	<div class="tabDet-form wd50 flLt">
		<label>Percentile <?= $examNameNew == 'JEE-MAINS' ? "1 (Jan ".date('Y').")" : "" ;?></label>
		<div class="actl-div">
			<input class="frm-field" id="percentile" name="percentile" inputType = 'Percentile'  type="text" placeholder="<?=$examNameNew == 'JEE-MAINS' ? 'Enter JEE Main Percentile for January Exam' : 'Total %ile'?>" />
			<div id="error_percentile" class="errorMsg hid"></div>
		</div>
	</div>
	<div class="tabDet-form wd50 flRt">
		<?php if($examNameNew == 'JEE-MAINS') { ?>
			<label>Percentile 2 (Apr <?=date('Y')?>)</label>
			<div class="actl-div">
				<input class="frm-field" id="percentile2" name="percentile2" inputType = 'Percentile2'  type="text" placeholder="Enter JEE Main Percentile for April Exam" />
				<div id="error_percentile2" class="errorMsg hid"></div>
			</div>
		<?php } else {?>
			<label>Score </label>
			<div class="actl-div">
				<input class="frm-field" id="score" name="score" inputType = 'Score'  type="text" placeholder="Score in Numerals" />
				<div id="error_Score" class="errorMsg hid"></div>
			</div>
		<?php } ?>
	</div>
</div>
<?php } ?>
<div class="hlf-tabDv">
	<div class="tabDet-form wd50 flLt">
		<?php if($examNameNew != 'JEE-MAINS'){ ?>
			<label>Enter <?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank/Merit No.";?></label>
		<?php }else{ ?>
			<label>Rank <span class="optinl">(If Available)</span></label>
		<?php }?>
		<div class="actl-div">
			<input class="frm-field" id="rank" name="rank" inputType = '<?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank"; ?>'  type="text" placeholder="<?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank"; ?> in Numerals" />
			<div id="error_rank" class="errorMsg hid"></div>
			<?php if($examNameNew != 'JEE-MAINS' &&  $showRankOverlay=='YES'){ ?>
				<span>Don’t know your rank? 
				<?php if($examNameNew == 'JEE-MAINS'){ ?>
	                        <a id="external-link" href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/jee-main-rank-predictor'?>" target="_blank";> Find your Rank </a>
				<?php }else if($examNameNew == 'COMEDK'){ ?>
				<a id="external-link" href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/comedk-rank-predictor'?>" target="_blank"; > Find your Rank </a>
				<?php }else{ ?>
	                        <a id="external-link" class='rankOverlay' href="javascript:void(0)"> Find your Rank </a>
				<?php } ?>	
			<?php } 
 			if($examNameNew == 'JEE-MAINS'){ ?>
 				<span>
 					<a id="external-link" href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/jee-main-rank-predictor'?>" target="_blank";> Find your Rank </a>
 				</span>
 				<?php }
 				?>
			</span>
		</div>
	</div>
	<div class="tabDet-form wd50 flRt <?php if(count($categoryData)==1){ ?>hid<?php } ?>">
		<label>Select Category</label>
	    <div class='drop-select'>
	    	<select class="pre-select" id="category" name="category">
			<?php foreach($categoryData as $key=>$value){ ?>
	        	<option value="<?php echo $key;?>" <?php if(!empty($data->categoryName) &&  $data->categoryName ==  $key) {echo 'selected';}?> ><?php echo $value;?></option>
			<?php } ?>
	    	</select>
	    </div>
	</div>
</div>
<?php if($examNameNew != 'MAHCET') echo '<div class="hlf-tabDv">';?>
<?php if($examNameNew == 'MHCET') { ?>

<div id="quota_elig_mhcet" class="tabDet-form <?php if($examNameNew != 'MAHCET') echo "wd50 flLt"; ?> <?php if($showRankType=='NO'){ echo 'hid';} ?>" >
	<label>Select State Quota Eligibility</label>
	<span class="tab-radio">
		<input type="radio" name="rank_type" id="quota_1" value="Other" checked= "checked";?>	
		<label for="quota_1">All India</label>
	</span>
	<span class="tab-radio">
		<input type="radio" name="rank_type" id="quota_2" value="HomeUniversity">
		<label for="quota_2">Home State</label>
	</span>
</div>
<?php } else if ($examNameNew == 'KCET'){ ?>

<div id="quota_elig" class="tabDet-form <?php if($examNameNew != 'MAHCET') echo "wd50 flLt"; ?> <?php if($showRankType=='NO'){ echo 'hid';} ?>" >
	<label>Select State Quota Eligibility</label>
	<span class="tab-radio">
		<input type="radio" name="rank_type" id="quota_1" value="KCETGeneral" checked= "checked">	
		<label for="quota_1">All India</label>
	</span>
	<span class="tab-radio">
		<input type="radio" name="rank_type" id="quota_2" value="HyderabadKarnatakaQuota" >
		<label for="quota_2">Home State</label>
	</span>
</div>

<?php } else if($examNameNew != 'JEE-MAINS'){ ?>
<div id="quota_elig" class="tabDet-form <?php if($examNameNew != 'MAHCET') echo "wd50 flLt"; ?> <?php if($showRankType=='NO'){ echo 'hid';} ?>" >
	<label>Select State Quota Eligibility</label>
	<span class="tab-radio">
		<input type="radio" name="rank_type" id="quota_1" value="Other" <?php if($rankType == 'Other' || $rankType=='') echo "checked= checked";?>">	
		<label for="quota_1">All India</label>
	</span>
	<span class="tab-radio">
		<input type="radio" name="rank_type" id="quota_2" value="Home" <?php if($rankType=='HOME' || $rankType=='Home') echo 'checked=checked';?>>
		<label for="quota_2"><?php echo $examNameNew == 'MAHCET'? 'Maharashtra':'Home State'; ?></label>
	</span>
</div>
<?php } ?>
<?php if($examNameNew == 'MAHCET'){ ?>
<div id="city_dropdown" class="hlf-tabDv pos-rl hid">
	<div class="tabDet-form wd50 flLt mb32">
		<label>Select City <span class="optinl">(Optional)</span></label>
		<div class='drop-select'>
	    	<select class="pre-select" id="city" name="city">
	        	<option value="-1">Select</option>
			<?php foreach($mainFilterCities as $key=>$value){ 
				if (!empty($value['id'])){?>
	        	<option value="<?php echo $value['id'];?>" ><?php echo$value['cityName'];?></option>
			<?php }
			} ?>
	    	</select>
	    </div>
		<div id="error_city" class="errorMsg hid"></div>
	</div>
	<div class="sel-cutff flRt">
		<strong>Select a City to know <?php echo $inputData['stateName'];?> Cut-offs:</strong>
		<ul>
			<li>The City where your Bachelor's degree granting university is</li>
			<li>The City for which either of your parents have a domicile certificate</li>
			<li>The City your parents are posted/retired to from Government service </li>
		</ul>
	</div>
</div>
<?php } else if($examNameNew == 'MHCET') { ?> 
<div id="state_cat_dropdown" class="hlf-tabDv wd50 flRt pos-rl hid" >
	<div class="tabDet-form mb32">
		<label>Select state category</label>
		<div class='drop-select'>
	    	<select class="pre-select" id="state_cat_mhcet" name="state_category">
				<option value="HomeUniversity" selected>Home University</option>
				<option value="OtherThanHome">Other Than Home University</option>
				<option value="StateLevel">State Level</option>		
	    	</select>
	    </div>    
	</div>
</div>

<?php }else { ?>
<div id="city_dropdown" class="hlf-tabDv wd50 <?php  echo  ($examNameNew != 'JEE-MAINS')?"flRt pos-rl hid":"flLt pos-rl";?>" >
	<div class="tabDet-form mb32">
		<label>Select <?php if($examNameNew == 'JEE-MAINS') echo " home "?>state </label>
		<div class='drop-select'>
	    	<select class="pre-select" id="state" name="stateName">
	        	<option value="">Select</option>
			<?php foreach($states as $key=>$value){ ?>
	        	<option value="<?php echo $value['stateName'];?>" <?php if(!empty($this->settingArray[$examNameNew]['stateName']) && $this->settingArray[$examNameNew]['stateName'] == $value['stateName']) {echo 'selected';}?> ><?php echo $value['stateName'];?></option>
			<?php 
			} ?>
	    	</select>
	    </div>
	<div id="error_state" class="errorMsg hid">Please Select your state</div>    
	</div>
</div>
<?php if($examNameNew != 'MAHCET') echo '</div>';?>
<?php } ?>
<?php if($examNameNew != 'JEE-MAINS') { ?>
<div class="hlf-tabDv">
	<div class="tabDet-form wd50 flLt">
		<label>Select Preferred Colleges <span class="optinl">(Optional)</span></label>
		<!-- <p class="custm-Rnksrch">Selected (3) <i class="pointrDown"></i></p> -->
	<div class="pre-select drop-select" >
	<span id="preferredCollegeDropdown">
			Selected (<span id="institute_selected_number"><?php if(!empty($count)){echo $count;}else {echo '0';} ?></span>)
			</span>
		        	<div class="dropdown-layer customInputs hid" id="college_selector" >
		            	<div class="filter-search">
						    <i class="predictor-sprite filter-search-icon"></i>
						    <input type="text" id="institute_text" value="Search Institute ..." >
						</div>
	            						<ul class="dropdown-list" id="collge_selector_container">
	            							<?php $this->load->view('CP/V2/searchInstitutesCP',$data1);?>
	  	              					</ul>
	  	              <div class="tac clear-width" ><a class="orange-button2" id ='submitPreferredClg' href="javascript:void(0)">Ok</a></div>
		            </div>
		        </div>
	</div>
</div>
<?php } ?>
<input type="hidden" name="noStateDropDown" id ="noStateDropDown" value= "<?php echo $noStateDropDown ?>" />	   


<div id="error_predict" class="errorMsg hid"></div>
<div class="src-tac flex flex--row">
	<a href="javascript:void(0);" id="src-btn" trackingKey='<?=$trackingKeyId?>' class="src-btn">Predict Results</a>
	<?php if(array_key_exists($examNameNew,$nameToIdMappingForAllCollegePredictorPage)){?>
	<lable>-OR-</lable>
	<a href="<?php echo SHIKSHA_HOME?>/college-predictor?se[]=<?php echo $nameToIdMappingForAllCollegePredictorPage[$examNameNew] ?>" class="prd-btn">Predict for Other Exams</a>
	<?php } ?>
</div>
<input type ='hidden' id= 'directoryName' value = '<?=$directoryName?>' />
<div class="launch-predCont">
<?php 

$countsAndUrls = array();
$countsAndUrls["KCET"] = array(212,"/b-tech/colleges/b-tech-colleges-accepting-kcet-india");
$countsAndUrls["TS EAMCET"]=array(150,"/b-tech/colleges/b-tech-colleges-accepting-ts-eamcet-india");
$countsAndUrls["AP EAMCET"]=array(198,"/b-tech/colleges/b-tech-colleges-accepting-ap-eamcet-india");
$countsAndUrls["UPSEE"]=array(195,"/b-tech/colleges/b-tech-colleges-accepting-upsee-uttar-pradesh");
$countsAndUrls["WBJEE"] = array(93,"/b-tech/colleges/b-tech-colleges-accepting-wbjee-india");
$countsAndUrls["KEAM"] = array(75,"/b-tech/colleges/b-tech-colleges-accepting-keam-india");
$countsAndUrls["TNEA"] = array(75,"/b-tech/colleges/b-tech-colleges-accepting-tnea-india");
$countsAndUrls["GUJCET"] = array(35,"/b-tech/colleges/b-tech-colleges-accepting-gujcet-india");
$countsAndUrls["CGPET"] = array(8,"/b-tech/colleges/b-tech-colleges-accepting-cg-pet-india");
$countsAndUrls["GGSIPU"] = array(17,"/b-tech/colleges/b-tech-colleges-accepting-ipu-cet-india");
$countsAndUrls["BITSAT"] = array(7,"/b-tech/colleges/b-tech-colleges-accepting-bitsat-india");
?>
<?php if($examinationName == 'CLAT'){ ?>
CLAT College Predictor helps students to get an idea about the chances of admission in the Law College of their choice based on CLAT 2018 result. You can predict a Law College based on your rank using Shiksha's CLAT College Predictor 2018, which uses an advanced algorithm and opening &amp; closing ranks of the colleges using last year's CLAT counselling data.<br>

So what are you waiting for? Just predict your best possible law college by simply registering at Shiksha and providing your CLAT exam result/rank 2018.		
<?php } if($examNameNew == 'JEE-MAINS'){ ?>
	JEE Main Rank and College Predictor uses your<a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/jee-main-exam-results'?>" target="_blank";> JEE Main 2019 percentile </a>to predict your rank and chances of admission in the college of your choice. The Predictor combines opening and closing rank from 3500 courses, 800 colleges and 9 different counsellings including <a id="external-link" href="<?php echo SHIKSHA_HOME.'/exams/josaa'?>" target="_blank";> JOSAA </a>, JAC Delhi, <a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/cg-pet-exam'?>" target="_blank";> CGPET </a>,HSTES, <a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/mht-cet-exam'?>" target="_blank";> MHTCET </a>, MPPET, <a id="external-link" href="<?php echo SHIKSHA_HOME.'/mba/ojee-exam'?>" target="_blank";> OJEE </a> PTU, and <a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/wbjee-exam'?>" target="_blank";> WBJEE </a> Sign up to get Cut offs, Fees, Placement Reviews, Admission process and shortlist courses from IITs, NITs, IIITs, and 800 JEE Main colleges across India.
    <?php } else{ ?>

<?php
if($countsAndUrls[$examNameNew] != null){?>

<a id="external-link" href="<?php echo SHIKSHA_HOME.$countsAndUrls[$examNameNew][1]?>" target="_blank";>  <?php echo $countsAndUrls[$examNameNew][0]."+"?> Colleges accept <?php echo $examNameNew." ".date('Y'); ?></a> score. 

<?php }?>

Which college will you get admission in? <?php echo $examNameNew; ?> College Predictor <?=date('Y');?> enables students to predict chances of getting admission in the college of their choice. Shiksha's <?php echo $examNameNew; ?> College Predictor <?=date('Y');?> uses an advanced algorithm and opening &amp; closing ranks of last year's <?php echo $examNameNew; ?> Exam counselling data to predict the best possible college for you to pursue Engineering So go ahead, simply register at Shiksha and enter your <?php echo $examNameNew; ?> exam result/rank/score <?=date('Y');?>.


<!-- <?php echo $examNameDisplay; ?> College Predictor helps students to get an idea about the chances of admission in the college of their choice based on <?php echo $examNameDisplay; ?> 2018 result. You can predict your college based on your rank using Shiksha's <?php echo $examNameDisplay; ?> College Predictor <?=date('Y');?>, which uses an advanced algorithm and opening &amp; closing ranks of the colleges using last year's <?php echo $examNameDisplay; ?> counselling data. So what are you waiting for? Just predict your best possible college for pursuing B.Tech, by simply registering at Shiksha and providing your <?php echo $examNameDisplay; ?> main exam result/rank <?=date('Y');?>. -->

<?php } ?>


</div>
<?php if($examNameNew == 'JEE-MAINS'){ ?>
    <h2>
    	<div>
	    	<strong class="cutOff-hdng">JEE Main Cut-offs for top ranked engineering colleges are shown below</strong>
		</div>
	</h2>
<?php } else { ?>
	<div>
	    <strong class="cutOff-hdng">Cut-offs for select colleges are below</strong>
	</div>
<?php } ?>



<script type="text/javascript">
var lazyCommonewCSS = '//<?php echo $cssUrl; ?>/public/css/<?php echo getCSSWithVersion('common_new'); ?>';
var quota_state='<?php echo strtolower($inputData['stateName']);?>';// $state name in static box
var instituteJson = <?php echo json_encode($institutes,true);?>;
var instituteGroupJson = <?php echo json_encode($instituteGroups,true);?>;
</script>
