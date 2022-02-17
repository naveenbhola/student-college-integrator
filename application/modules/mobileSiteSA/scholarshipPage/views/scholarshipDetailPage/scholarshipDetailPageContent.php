<div class="sch-main">
<?php 
$this->load->view('scholarshipDetailPage/widgets/scholarshipSummary');
if($scholarshipObj->getApplicationData()->getBrochureUrl() != ''){
    $topCTA = $allCTAData['topCTA'];
    $this->load->view('scholarshipDetailPage/widgets/scholarshipCTA', $topCTA);
}
$this->load->view('scholarshipDetailPage/widgets/scholarshipDetails');
if($scholarshipObj->getApplicationData()->getBrochureUrl() != ''){
    $bottomCTA = $allCTAData['bottomCTA'];
    $this->load->view('scholarshipDetailPage/widgets/scholarshipCTA', $bottomCTA);
}
$this->load->view('scholarshipDetailPage/widgets/reportIncorrectInfo');
$this->load->view('scholarshipDetailPage/widgets/similarScholarships');
$this->load->view('scholarshipHomePage/widgets/countryScholarships');

if($scholarshipObj->getApplicationData()->getBrochureUrl() != ''){
?>
	<div class="stky-cta" id="stkyCta">
	<?php 
	$stickyCTA = $allCTAData['stickyCTA'];
	$this->load->view('scholarshipDetailPage/widgets/scholarshipCTA', $stickyCTA);
	?>
	</div>
<?php 
}
?>
</div>
<script>
var isBrochureAvailable = '<?php echo ($scholarshipObj->getApplicationData()->getBrochureUrl() != '') ? 1 : 0; ?>';
</script>