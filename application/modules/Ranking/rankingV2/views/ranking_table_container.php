<?php
$result_type = "main";
if(!empty($resultType)){
	$result_type = $resultType;
}
?>
<div id="rtc_<?php echo $result_type;?>">
	<?php $this->load->view('ranking/ranking_table'); ?>
	<?php $this->load->view('ranking/ranking_table_viewmore'); ?>
</div>