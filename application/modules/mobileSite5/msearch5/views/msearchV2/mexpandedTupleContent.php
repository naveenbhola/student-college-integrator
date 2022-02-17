<?php 
foreach ($courses as $key => $courseObj){
	$data['tupleCourseCount'] = ++$loadedCourseCount;
	$data['course'] = $courseObj;
	$data['courseWidgetData'] = $courseWidgetData[$data['course']->getId()];
	?>
	<div class="clg-detail-head brdrTop more_<?php echo $courseObj->getInstId()."_".$key; ?>">
		<?php $this->load->view('msearch5/msearchV2/mtupleBottom', $data);?>
		<input type='hidden' id='morecoursesnum_<?php echo $courseObj->getId(); ?>' value='<?php echo $data['tupleCourseCount']; ?>'>
	</div>
	<?php
}
?>