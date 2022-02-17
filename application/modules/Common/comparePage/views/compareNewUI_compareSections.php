<div class="compare-connect" id="compare-cont">
	<!-- breadcrumb html - start -->
	<?php $this->load->view('breadcrumb');?>
	<!-- breadcrumb html - end -->

	<!--sticky html - start-->
	<?php $this->load->view('compareNewUI_topStickyWidget');?>
	<!--sticky html - end-->

	<!-- share comparison html - start -->
	<?php $this->load->view('compareNewUI_comparePageTop');?>
	<!-- share comparison html - end -->

	<!-- compare course parameters html - start -->
	<?php $this->load->view('compareNewUI_comparePageInner');?>
	<!-- compare course parameters html - end -->

	<!-- compare course parameters html - start -->
		<?php $this->load->view('compareNewUI_comparePageBottom');?>
	<!-- compare course parameters html - end -->
</div>
<?php 
$filled_compares = 0;
$subcatIdArray = array();
foreach ($courseIdArr as $key => $courseId) {
	$institute_Id[$courseId] = $courseObjs[$courseId]->getInstituteId();
	if($userComparedData[$courseId]['instituteId'] && preg_match('/^\d+$/',$userComparedData[$courseId]['instituteId'])){
		$instituteId = '::'.$userComparedData[$courseId]['instituteId'];
	}
	$trackey_Id = empty($userComparedData[$courseId]['trackeyId']) ? $compareHomePageKeyId : $userComparedData[$courseId]['trackeyId'];
	if(preg_match('/^\d+$/',$courseId) && preg_match('/^\d+$/',$trackey_Id)){
		$cookieDataArray[] = $courseId.'::'.$trackey_Id.$instituteId;
	}
}
$cookieDataStr = implode('|', $cookieDataArray);
?>
<img id = 'tracking_img' src="/public/images/blankImg.gif" width=1 height=1 />
<script>
	var SHIKSHA_HOME    = '<?php echo SHIKSHA_HOME; ?>';
	var filled_compares = '<?php echo count($courseIdArr); ?>';
	    cookieDataStr       = '<?php echo $cookieDataStr; ?>';
	//Set the Cookie for the Compare widget
	if (cookieDataStr) {
		setCookie("compare-global-data",cookieDataStr,30);
	}
	else{
		setCookie("compare-global-data","",0);	
	}

	if(typeof(floatCompareWidgetScrollNew) == 'function'){
		window.onscroll = floatCompareWidgetScrollNew;
	}
	
	window.onload=function(){
		if(filled_compares != 0){
			getCollegeReviewsForCourse("<?php echo implode(',',$courseIdArr);?>",'desktop','<?php echo json_encode($institute_Id);?>');
		}
		
		//Track the courses, static/dynamic page, source
		<?php   if(isset($isStaticPage) && $isStaticPage){
				echo "var pageType = 'static';";
			}else{
				echo "var pageType = 'dynamic';";
			}
		?>

		// compare push data for response table
		var source = 'desktop';
		if(cookieDataStr !=''){
			var randNum = Math.floor(Math.random()*Math.pow(10,16));
			$('tracking_img').src = '<?php echo SHIKSHA_HOME;?>/comparePage/comparePage/trackComparePage/'+randNum+'/'+pageType+'/'+source+'/'+cookieDataStr;
		}
};
</script>