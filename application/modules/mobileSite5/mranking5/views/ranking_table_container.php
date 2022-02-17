<?php
$result_type = "main";
if(!empty($resultType)){
	$result_type = $resultType;
}

?>
<div id="rtc_<?php echo $result_type;?>" style="margin-bottom:0.9em;">

	<?php
	if( isset($tracking_keyid) ) { // if tracking key has been passed on from the previous view, pass it to the view ahead
		$this->load->view('ranking_table', array('tracking_keyid' => $tracking_keyid));
	} else {
		$this->load->view('ranking_table');
	}
	?>
</div>
