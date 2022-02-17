<?php
$this->load->view('listing/national/instituteHeader');
switch ($abTestVersion) {
	case 1:
		$this->load->view('listing/national/institutePageContent_v1');
		break;

	case 2:
		$this->load->view('listing/national/institutePageContent_v2');
		break;

	default:
		$this->load->view('listing/national/institutePageContent');
		break;
}
$this->load->view('listing/national/instituteRightColumn');
$this->load->view('listing/national/instituteFooter');
?>

<script>
    studyAbroad = 0;
</script>