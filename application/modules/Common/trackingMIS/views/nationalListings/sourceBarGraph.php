<?php
$class = "cropper-hidden";
if (count($splitForBarGraph) > 0 ) { $class = ""; }
else {
	$class = "";
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 <?php echo $class; ?>">
	<div class="x_panel" id="trafficSplitBarGraph">
		<div class="x_title">
			<h2><i class="fa fa-bars"></i> Traffic Sources </h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<div class="clear"></div>
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="sourceTabList" class="nav nav-tabs bar_tabs" role="tablist">
					<?php foreach ($splitForBarGraph['barGraphOptions'] as $key => $value) {
						if ($value == 1)
							$sourceTabActive = $key;
						?>

						<li role="presentation" class=""><a href="" id="<?php echo $key ?>" role="tab" data-toggle="tab"
															aria-expanded="true"><?php echo ucfirst($key); ?></a>
						</li>
					<?php } ?>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
						<?php foreach ($splitForBarGraph['splitInformation'] as $headingKey => $headingValue) {
							$count = count($headingValue);
							if ($count > 8) {
								$class = "overflow_BR";
								$style = "height:320px !important";
							} else {
								$class = "";
								$style = "height: " . ($count * 36) . "px";
							}

							if ($headingKey != 'utm Campaign') {
								$colClass = "col-md-6 col-sm-6 col-xs-6";
								$w25      = "";
								$w42      = "";
								$w10      = "";
							} else {
								$colClass = "col-md-12 col-sm-12 col-xs-12";
								$w25      = "style='width: 55% !important;'";
								$w42      = "style='width: 30% !important;'";
								$w10      = "style='width: 15% !important;'";
							} ?>
							<div class="<?php echo $colClass; ?>">
								<div class="x_panel tile">
									<div id="">
										<div class="x_title">
											<h2 id=""><?php echo ucfirst($headingKey); ?></h2>

											<div class="clearfix"></div>
										</div>
										<div id="<?php echo $headingKey; ?>" class="x_content <?php echo $class; ?>"
											 style="<?php echo $style; ?>">
											<?php
											foreach ($headingValue as $resultkey) {
												?>

												<div class="widget_summary">
													<div class="w_left w_25" <?php echo $w25; ?>>
														<span
															title="<?php echo $resultkey->Pivot; ?>"><?php echo $resultkey->Pivot; ?></span>
													</div>
													<div class="w_center w_42" <?php echo $w42; ?>>
														<div class="progress">
															<div class="progress-bar bg-green" role="progressbar"
																 aria-valuenow="60" aria-valuemin="0"
																 aria-valuemax="100"
																 style="width: <?php echo $resultkey->Percentage . '%'; ?>;"
																 title="<?php echo $resultkey->ResponseCount; ?>">
																<span
																	class="sr-only"><?php echo $resultkey->Percentage; ?></span>
															</div>
														</div>
													</div>
													<div class="textLength w_10" <?php echo $w10; ?>>
														<span><?php echo $resultkey->ResponseCount; ?></span>
													</div>
													<div class="textLength w_10">
														<span><?php echo '(' . $resultkey->Percentage . '%)'; ?></span>
													</div>
													<div class="clearfix"></div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<?php
						} ?>
					</div>
					<div id="utmWise" class="loader_utm_overlay utmWise" style="display:none">
											<img
												src="http://localshiksha.com/public/images/trackingMIS/loader_MIS.gif">
										</div>
				</div>
				<div class="loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>" style="display:none;"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
activeElementInTab = '<?php echo $sourceTabActive;?>';
pageIdentifier = '<?php echo $metricName;?>';
pivotName = '<?php echo $pivot?>';
$('#' + activeElementInTab).parent().attr('class', 'active');
dashboardMetric = '<?php echo $actionName?>';
if(dashboardMetric == 'response')
	barGraphAjaxUrlFun = 'getUpdateSourceBarGraphData';
else
	barGraphAjaxUrlFun = 'getUpdateBarGraphDataForTraffic';
	$('#sourceTabList li').on('click', function () {
		if( activeElementInTab != $(this).find('a').attr('id'))
				updateBarGraph(barGraphAjaxUrlFun,$(this).find('a').attr('id'));
	});
</script>