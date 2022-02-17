<?php
$this->load->view('coursepages/coursePagesHeader',array('tab' => 'home'));
echo jsb9recordServerTime('SHIKSHA_COURSEPAGES_HOME_PAGE',1); ?>
<script>isCPGSAutoScrollEnabled = 0;</script>
<?php
	$curPage = $COURSE_HOME_PAGES_LIST[$courseHomePageId];

?>
<script>
	var streamId = '<?php echo $curPage['streamId']?>';
	var baseCourseId = '<?php echo $curPage['baseCourseId']?>';
	var curPageName = '<?php echo $curPage['Name'];?>';
</script>
<?php
global $coursePagesWidgetsTemplatesArray;
foreach($processedWidgetsList as $columnKey => $widgetListArray) {	
	if($columnKey == 1) {			?>
		<div id="course-left"><?php
				$widgetListArrayCount = count($widgetListArray);
				$countVar = 1;
				foreach($widgetListArray as $key => $widgetObj) {
					if($widgetListArrayCount == $countVar) {
						$cssClass = 'class="widget-wrap last-widget"';
					} else {
						$cssClass = 'class="widget-wrap"';
					}
		
					$viewTemplate = '/coursepages/widgets/'.$widgetObj->getWidgetKey();
					
					if( $widgetObj->getWidgetKey() == "CollegeReviewWidget")
					{
						$this->load->view($viewTemplate, array('widget_collegeReviewData' => $widget_collegeReviewData,'widgetForPage' => $widgetForPage,'widgetObj' => $widgetObj, 'cssClass' => $cssClass));
					}
					else{
						$this->load->view($viewTemplate, array('widgetObj' => $widgetObj, 'cssClass' => $cssClass));
					}
					
					$countVar++;
				}
				?>
		</div>
	<?php  } else if($columnKey == 2) {	?>
                
                <div id="course-right"><?php
                
			
				$widgetListArrayCount = count($widgetListArray);
				$countVar = 1;
				foreach($widgetListArray as $key => $widgetObj) {
					if($widgetListArrayCount == $countVar) {
						$cssClass = 'class="widget-wrap last-widget"';
					} else {
						$cssClass = 'class="widget-wrap"';
					}
								
				$viewTemplate = '/coursepages/widgets/'.$widgetObj->getWidgetKey();
				
			
					$this->load->view($viewTemplate, array('widgetObj' => $widgetObj, 'cssClass' => $cssClass));
					if($widgetObj->getWidgetKey() == 'RankPredictorWidget'){
						
                		if(false && $courseHomePageId == '6'){
                			echo "<p style='font-size:18px; margin:18px 0 12px; float:left;width:100%;'>Engineering College Reviews</p>";
                			$bannerProperties1 = array('pageId'=>'COURSE_HOME', 'pageZone'=>'RIGHT');
 							$this->load->view('common/banner',$bannerProperties1);	
						}
					}	
				
				
				
				
				$countVar++;
			}
			?>
                </div>
<?php
	}
	
 } 	// End of foreach($processedWidgetsList as $columnKey => $widgetListArray).
?>
<div id="tags-layer" class="tags-layer"></div>
 <div class="an-layer an-layer-inner" id="an-layer">
      <?php $this->load->view('messageBoard/desktopNew/quesDiscPosting');?>
 </div>
<?php
$this->load->view('coursepages/coursePagesFooter');
?>

<script type="text/javascript">
lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
setTimeout(function(){
	$LAB.script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>').wait(function(){
	    LazyLoadAnACoursePageLayer();
	});
},2000);
var courseName = '<?php echo $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?>';

</script>
