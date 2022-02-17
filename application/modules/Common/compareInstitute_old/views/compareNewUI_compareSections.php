<div class="compare-connect" id="compare-cont">
	<!-- breadcrumb html - start -->
	<div class="back-inst">
		<a href="<?=SHIKSHA_HOME?>">Home</a>
		<span class="breadcrumb-arrow" style="margin-right:0px;">&gt;</span>
		<?php if(!$isStaticPage){ ?>Compare Colleges<?php }else{ ?>
		<a href="<?=SHIKSHA_HOME?>/compare-colleges">Compare Colleges </a>
		<span class="breadcrumb-arrow" style="margin-right:0px;">&gt;</span>
		<?=$seoDetails['breadcrumb']?>
		<?php } ?>
	</div>
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
// _p($institutes); die;
foreach($institutes as $institute){
	$filled_compares ++ ;
	$j++;
	$course = $institute->getFlagshipCourse();
	$courseArray[]= $course->getId;
	$dominantSubCatArray = $course->getDominantSubcategory();
	$subcatIdArray[] = $dominantSubCatArray->getId();
	$instituteName[] = $institute->getName();
	$locationName[] = $institute->getMainLocation()->getCity()->getName();
	$courseNameArray[] = $course->getName();
	}
?>
<img id = 'tracking_img' src="/public/images/blankImg.gif" width=1 height=1 >
<script>
	var SHIKSHA_HOME = '<?php echo SHIKSHA_HOME; ?>';
	var filled_compares = '<?php echo count($institutes); ?>';
	//Set the Cookie for the Compare widget
	if (cookieDataArray.length>0) {
		setCookie("compare-global-categoryPage",cookieDataArray.join("|||"));
	}
	else{
		setCookie("compare-global-categoryPage","");	
	}
	var listings_with_localities = <?php echo $listings_with_localities; ?>;
	var localityArray = <?=json_encode($localityArray)?>;

	window.onscroll = floatCompareWidgetScrollNew;
	
	window.onload=function(){
		if(filled_compares != 0){
			getCollegeReviewsForCourse("<?=implode(',',$courseArray)?>",0,1,"<?=implode(',',$subcatIdArray)?>",'compareDesk','<?php echo base64_encode(json_encode($instituteName));?>','<?php echo base64_encode(json_encode($courseNameArray));?>',0,"<?=implode(',',$locationName)?>");
		}


		$j.each(localityArray,function(index,element){
			custom_localities[index] = element;
		});
		
		//Track the courses, static/dynamic page, source
		<?php   if(isset($isStaticPage) && $isStaticPage){
				echo "var pageType = 'static';";
			}
			else{
				echo "var pageType = 'dynamic';";
			}
		?>
		var source = 'desktop';
		var courseString = '<?=implode(',',$courseArray)?>';
		var trackCookiename = "compare-tracking";
		if(getCookie(trackCookiename)){
		    var trackeyStr = getCookie(trackCookiename);
		}
		if(courseString !=''){
			var randNum = Math.floor(Math.random()*Math.pow(10,16));
			$('tracking_img').src = '/compareInstitute/compareInstitutes/trackComparePage/'+randNum+'/'+pageType+'/'+source+'/'+courseString+'/'+trackeyStr+'/'+compareHomePageKeyId;
		}
};
</script>