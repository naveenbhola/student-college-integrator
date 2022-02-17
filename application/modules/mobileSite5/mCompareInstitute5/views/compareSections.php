<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
global $isFullRegisteredUser;
if(isset($_COOKIE['MOB_A_C'])){
        $appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
}
?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
<?php
$compare_count = count($courseIdArr);
global $empty_compares;
$empty_compares = $compare_count_max - $compare_count;
$filled_compares = $currentCourseCount;
?>
<section class="content-wrap2">
<script>
    var emailDataArray = new Array();
    var cookieDataArray = new Array();
</script>
	<div class="no-collge-to-compare-msg" id="no-collge-to-compare-msg">
	<h1 class="cmpr-head"><?=(isset($seoDetails['heading']) && $seoDetails['heading']!='')?$seoDetails['heading']:"Compare Courses in Colleges of your choice";?></h1>
	<p>
		Compare 2 courses and colleges on their fees, placements, seats, alumni ratings, infrastructure, and various other details. Select the courses and colleges below.
	</p></div>
	<input type="hidden" id="autoSuggSelInstId" />
	<?php  
 	$iter = 0; 
 	foreach ($academicUnitCookieData as $crs => $instData) { 
 	        $iter++; 
 	?> 
 		<input type="hidden" id="selectedInstId<?php echo $iter?>" value="<?php echo $instData['userSelectedInstitute']?>" /> 
 		<input type="hidden" id="selectedInstIdCrsId<?php echo $iter?>" value="<?php echo $instData['userSelectedInstitute']?>-<?php echo $crs?>" /> 
 	<?php  
 	} 
 	?> 
	<table class="compare-table" cellpadding="0" cellspacing="0" width="100%" id="comparePageDataTable">
	<?php 
	$recognised = array();
	$isAICTEApproved = '';
	$isDECApproved = '';
	$isUGCRecognised = '';
	$Affiliations = array();
	$instArr = $instIdArr;
	
	$displayDataSections['compare_count_max'] = $compare_count_max;
	$displayDataSections['filled_compares'] = $filled_compares;
	$displayDataSections['empty_compares'] = $empty_compares;
	
	$this->load->view('firstSection', $displayDataSections);
	?>
    	<tr>
        <?php
	    if($compare_count <= $compare_count_max)
	    {
		$j = 0;
		if($j == 0){ ?>
		    <tr class="collegeListSec last flagClassForAjaxContent">
		<?php } 
		foreach($courseIdArr as $courseId)
		{
		    $j++;
		    $course      = $courseObjs[$courseId];
			if($showAcademicUnitSection){
				$instituteId = $academicUnitRawData[$courseId]['userSelectedInstitute'];
			}else{
				$instituteId = $instIdArr[$courseId];
			}
			$institute   = $instituteObjs[$instituteId];
			$instNameDisplay = ($institute->getShortName() != '') ? $institute->getShortName() : $institute->getName();
		    if(strlen($instNameDisplay) > 100){
				$instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($instNameDisplay));
				$instStr .= "...";
			}else{
				$instStr = html_escape($instNameDisplay);
			}
			if(strlen($instStr) > 50){
				$instStr = substr($instStr, 0 , 47).'...';
			}
			$instituteIdForReview[$courseId] = $courseObjs[$courseId]->getInstituteId();
			$instituteIdCk = empty($userComparedData[$courseId]['instituteId']) ? $instituteId : $userComparedData[$courseId]['instituteId'];

			if($instituteIdCk && preg_match('/^\d+$/',$instituteIdCk)){
				$instituteIdCk = '::'.$instituteIdCk;	
			}
			$trackey_Id = empty($userComparedData[$courseId]['trackeyId']) ? $compareHomePageKeyId : $userComparedData[$courseId]['trackeyId'];

			if(preg_match('/^\d+$/',$courseId) && preg_match('/^\d+$/',$trackey_Id)){
				$cookieDataArray[] = $courseId.'::'.$trackey_Id.$instituteIdCk;
			}

		    ?>
		    <td <?php if($j<$compare_count_max) {?> class = "border-right" <?php } ?>>
			
			<script>
			var stringTemp = '';
			emailDataArray.push(stringTemp);
			</script>
			
			<!-- Request Brochure STARTS -->
			<div style="display:none;">
                <div id= "thanksMsg<?php echo $courseId;?>" class="thnx-msg" <?php if(!in_array($courseId,$appliedCourseArr)){?>style="display:none"<?php } ?>>
                        <i class="icon-tick"></i>
                        <p>Thank you for your request.</p>
                </div>
			</div>

			<?php
				$institute_id = $instituteId;
				if($checkForDownloadBrochure[$course->getId()]){
					$this->load->view('downloadEBrochure',array('courseObj'=>$course));
				}
		    ?>	
		    </td>
		    <?php		
			$signedInUser = $validateuser;
		}
	    // $filled_compares--;
	    $makeDisable = false;
	    if($empty_compares > 0)
	    {
			for($e = $filled_compares+1; $e<=$compare_count_max; $e++)
			{
				?>
				<td class="<?php echo ($e<$compare_count_max)?'border-right':'';?>" <?php if(!$makeDisable){ ?>id="newInstituteSection<?=$e?>"<?php } ?>>
					<div id="searchContainerDiv<?=$e?>" class="home-search">
						<div class="full-width">
						   <a href="javascript:;" compareBox="<?=$e?>" class="addACollegeButton cc-btn <?php if($e == 2 && $filled_compares == 0){ ?> disabled-btn <?php }?>">Add A College</a>
						</div>
					</div>
			    </td>
				<?php
				$makeDisable = true;
			}
	    }
	    ?>
		</tr>
	   <?php } ?>    
        </tr>
        <?php
       		$this->load->view('courseName', $displayDataSections);
       		if($showAcademicUnitSection){
       			$this->load->view('academicUnit', $displayDataSections);
       		}
       		if(!empty($compareData['ranks']) && !empty($compareData['ranks']['rankData'])){
       			$this->load->view('rank', $displayDataSections);
       		}
       		if(is_array($compareData['recognition'])){
       			$this->load->view('recognition', $displayDataSections);
       		}
       		if(is_array($compareData['courseStatus']) && count($compareData['courseStatus']) > 0){
       			$this->load->view('affiliation', $displayDataSections);
       		}
       		if(isset($compareData['accreditation'])){
       			$this->load->view('accreditation', $displayDataSections);
       		}

       		if(isset($compareData['alumniSalary'])){
       			$this->load->view('aluminiSalaryData',$displayDataSections);
       		}

       		?>
       		<tr>
       			<td colspan="2" class="compare-title">
       				<?php $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'content1'));?>
       			</td>
       		</tr>

       		<?php 
       		
       		if(!empty($compareData['eligibility'])){
       			$this->load->view('examsRequiredData', $displayDataSections);
       		}
       		if(is_array($compareData['courseFee'])){
       			$this->load->view('courseFees', $displayDataSections);
       		}
       		if(isset($compareData['courseSeats'])){
       			$this->load->view('courseSeats', $displayDataSections);
       		}
       		if(isset($compareData['courseDuration'])){
       			$this->load->view('courseDuration', $displayDataSections);
       		}
       		$this->load->view('facilities',$displayDataSections);
       		 ?>
       		  <tr>
       		  	<td colspan="2" class="compare-title">
       		  		<?php
       					$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'content2'));
       				?>
       			</td>
       		  </tr>
       		<?php
       		if($filled_compares != 0){
       			$this->load->view('collegeReviews');
       		}
       		$this->load->view('shortlist',$displayDataSections);
       		if($filled_compares != 0){
       			$this->load->view('askStudents',$displayDataSections);
       		}
       		$this->load->view('applyNow',$displayDataSections);
       	?>
	</table>
	<?php
	if($empty_compares != $compare_count_max)
	{
		$this->load->view('share');
	}
	?>
	<div class="clearfix"></div>
</section>

<?php
$this->load->view('popularComparisons',$displayDataSections);
$this->load->view('autoSuggestorInstitute');
$cookieDataStr = implode('|', $cookieDataArray);
?>


<img id = 'tracking_img' src="/public/images/blankImg.gif" width=1 height=1 />
<script>

var courseIdArrStr = '<?=implode(',',$courseIdArr)?>';
var filled_compares = '<?=$filled_compares?>';
var signedInUser = <?=$signedInUser?>;
var cookieDataStr       = '<?php echo $cookieDataStr; ?>';

	window.onload=function(){
		
		<?php if(count($institutes)<2){
		$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearchCompareMobile'));
		foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } ?>
		//Event listener for hiding dropdown suggestions when user clicks outside the suggestion container
		if(typeof(handleClickForAutoSuggestorCompare) == "function") {
		    if(window.addEventListener){
			document.addEventListener('click', handleClickForAutoSuggestorCompare, false);
		    } else if (window.attachEvent){
			document.attachEvent('onclick', handleClickForAutoSuggestorCompare);
		    }
		}
		<?php } ?>

	        setCookie('hide_recommendation','no',30);
        	setCookie('show_recommendation','no',30);
		
		updateNotification();
		$('#emailCompareDiv').click(function(){
			$(this).hide();
		});
		if(filled_compares != 0){
			getCollegeReviewsForCourse("<?=implode(',',$courseIdArr)?>",'mobile','<?php echo json_encode($instituteIdForReview);?>');
		}

		//window.onscroll = floatCompareWidgetScroll;
		
		//Track the courses, static/dynamic page, source
		<?php   if(isset($isStaticPage) && $isStaticPage){
				echo "var pageType = 'static';";
			}
			else{
				echo "var pageType = 'dynamic';";
			}
		?>
		var source = 'mobile';
		var courseString = '<?=implode(',',$courseIdArr)?>';
		var randNum = Math.floor(Math.random()*Math.pow(10,16));
		if(cookieDataStr){
			document.getElementById('tracking_img').src = '<?php echo SHIKSHA_HOME;?>/comparePage/comparePage/trackComparePage/'+randNum+'/'+pageType+'/'+source+'/'+cookieDataStr;
		}
		openAskNowLayerOnCompare(courseString, '<?=implode('::::',$courseNameArray)?>', '<?=implode(',',$instArr)?>','<?=implode(',',$subcatIdArray)?>','<?=implode(',',$locationIdArr)?>','<?=implode(',',$currentCityIdArr)?>','<?=implode(',',$currentLocalityArr)?>');

	};
	
</script>
<script>
function trackReqEbrochureClick(courseId){
try{
	_gaq.push(['_trackEvent', 'HTML5_Compare_Page_Request_Ebrochure', 'click',courseId]);
}catch(e){}
}


function floatCompareWidgetScroll() { 
	if ( ($(window).scrollTop()+$( window ).height()+20) >= $('#page-footer').offset().top ) {
		$('#emailCompareDiv').hide();
	}else if(!hamburgerFlag && typeof(hamburgerFlag) !='undefined'){
		$('#emailCompareDiv').show();
	}
}

</script>

<!--popup-->
<?php 
$recommendedInstitutesCount = count($institutesRecommended);
?>
<input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP">
<input type="hidden" id="instituteIdQP" name="instituteIdQP" >
<input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="Asked_Question_On_Compare_MOB">
<input type="hidden" id="listingTypeQP" name="listingTypeQP" value="institute">
<div class="wrapp" id="addToCompareLayerContent">
  	<a href="javascript:void(0);" id="addToCompareLayerClose" class="remove-tab flRt" >×</a>

<!--div class="clearfix content-wrap"-->
<div class="layerBlock" id="addToCompareLayerInner">
    <div class="r-h" id="addToCompareLayerHeading">
    	<div style="width:85%;" class="flLt">
        	<p class="reviewed-ins-title">Add A College for Comparison</p>
           
        </div>
        <div class="clr"></div>
    </div>
    <div class="collge-comparison-search" id="addToCompareLayerSearch">
    	  <div class="cc_inputsrchBx">
             <div class="cc_inputsrchBxInnr">
                 <i class="msprite cc-search"></i>
                 <input type="text" id="keywordSuggest" placeholder="Find College">
             </div>
            
             <div class="cc_sugestd" style="display:block;">
                <ul class="sugsted-ul" id="suggestions_container" style="top:0" onclick="trackEventByGAMobile('MOBILE_COLLEGE_SELECTION_SEARCH_FROM_COMPARE');"></ul>
             </div>
          </div>
           <p class="cntr-txt" style="display: none;" id="recomnded-colgs">Or</p>
    </div>
    <div class="recomnded-colgs" id="recomnded-colgs-inner"></div>
    </div>
</div>
