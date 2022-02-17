<?php foreach(array_keys($coursesData) as $mainCourseId){ ?>
	<?php //$widgetData = $coursesData[$mainCourseId]['widgetData'];?>
	<div class="right-col flRt mainCourse<?php echo $mainCourseId; ?>">
	<?php
		$x = ''; 
	    foreach($coursesData[$mainCourseId]['widgetData'] as $k=>$orderNo){
	        // load that widget's view
	        // file exists....
	        $x .= $this->load->view('widgets/countryHomeRevamped/'.$k,array('mainCourseId'=>$mainCourseId),true);
	    }
	    echo $x;
	?>
	</div>
<?php } ?>