<section id="contentStickyDownloadBrochureWidget" class="detail-widget"  data-enhance="false" style="padding:0px; position:fixed; bottom:0;z-index:1000;width:100%;display: none;">
	<div class="detail-widegt-sec">
		<div class="detail-info-sec" style="padding-bottom:0px;">
			<div class="offline-brochure-sec">
				<strong>Download <?php echo ($examName != '' ?strtoupper($examName):'this'); ?> guide to read it offline</strong>
				<a class="download-sop-btn dlGuideSticky" dlGuideTrackingId = "<?php echo ($examName != '' ?1049:638); ?>" type="<?php echo ($examName != '' ?'examContent':$content['data']['type']); ?>" href="javascript:void(0);">Email <?php echo ($examName != '' ?strtoupper($examName).' ':''); ?> Guide</a>
				<?php if($guideDownloadCount >= 50){
						$hideNumber = false;
					}else{
						$hideNumber = true;
					}
				?>
				<p style="text-align:center;<?=($hideNumber)?'display:none;':''?>">
					<span id="contentDownloadCountSticky" class = "dlCount">
						<?=$guideDownloadCount?>
					</span> 
					people downloaded this guide
				</p>
			</div>
		</div>
	</div>
</section>
