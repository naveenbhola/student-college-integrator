<?php 
$headerComponents = array(
		'css'   =>      array('college-predictorV2'),
		'js' => array('collegePredictor'),
		'jsFooter'=>    $tempJsArray,
		'title' =>      $seoData['seoTitle'],
		'metaDescription' => $seoData['seoDesc'],
		'canonicalURL' =>$canonicalURL,
		'product'       =>'collegeCutoff',
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'noIndexNoFollow' => $noIndexNoFollow

);

$this->load->view('common/header', $headerComponents);
$this->load->view('messageBoard/desktopNew/toastLayer');
?>
<div class="clg-predctr cutOff-Dv">
	<div id="cutOff-bg">
		<div class="base-frame">
			<div class="clg-bg">
				<div class="clg-txBx">
					<h1><?php echo $instituteName; ?> Cut-off</h1>
				</div>
			</div>
		</div>
	</div>
	<div class="cutOff-sec">
		<div class="base-frame">
			<div class="cutOff-textSec">
				<div class="cutOff-info">
					<table>
						<tr>
							<th>
								<h2><?php echo $instituteName; ?> Cut-off <?php echo date('Y'); ?></h2>
							</th>
						</tr>
						<tr>
							<td>
								<?php 
								foreach ($previewText as $row) {
									?>
									<p><?php echo $row; ?></p>
									<?php
								}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php $this->load->view('collegeCutoffFilters');?>
		<?php $this->load->view('collegeCutoffTupleList');?>
	</div>
</div>
<?php 
    $this->load->view('common/footer');
?>
<script type="text/javascript">
	var start = 30;
	var GA_currentPage = '<?php echo 'cutoff_page_desktop_'.$examName;?>';
	var totalCount = '<?php echo $totalCount;?>';
	var viewedResponseAction = 'Institute_Viewed';
	var viewedResponseTrackingKey = 2073;
	var viewedResponseCourseId = '<?=$flagshipCourseId;?>';
	$j(document).ready(function () {
	    initDuCollegePredictor();
		createCutOffViewedResponse();
	});
</script>