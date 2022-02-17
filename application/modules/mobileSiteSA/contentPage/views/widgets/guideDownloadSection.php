<section id="emailGuideInline" class="detail-widget"  data-enhance="false">
	<div class="detail-widegt-sec">
		<div class="detail-info-sec">
			<div class="offline-brochure-sec">
				<strong>Download this guide to read it offline</strong>
				<a class="download-sop-btn" href="javascript:void(0);" onclick="emailGuide('<?php echo $examPageObj->getExamPageId();?>','abroadExamPage',497);">Email <?php echo $examPageObj->getExamName();?> Guide</a>
					<p style="text-align:center; <?php if($guideDownloadCount < 50){ echo 'display:none;'; } ?>"><span id="examGuideDownloadCount"><?=$guideDownloadCount?></span> people downloaded this guide</p>
			</div>
		</div>
	</div>
</section>
