<section id="inlineEmailBrochureButton" class="detail-widget"  data-enhance="false">
	<div class="detail-widegt-sec">
		<div class="detail-info-sec">
			<div class="offline-brochure-sec">
				<strong>Download this guide to read it offline</strong>
				<a class="download-sop-btn" href="javascript:void(0);" onclick="downloadGuidePDF('<?php echo $contentId; ?>',502,'applyContent',null);">Email <?=$contentDisplayValue?> Guide</a>
				<?php if($guideDownloadCount >= 50){
						$hideNumber = false;
					}else{
						$hideNumber = true;
					}
				?>
				<p style="text-align:center;<?=($hideNumber)?'display:none;':''?>"><span id="applyContentGuideDownloadCount"><?=$guideDownloadCount?></span> people downloaded this guide</p>
			</div>
		</div>
	</div>
</section>

<script>
	function updateGuideDownloadCount(){
		$j.ajax({
			url : '/applyContent/applyContent/totalGuideDownloaded',
			type: 'POST',
			data: {'contentId': '<?=$contentId?>'},
			success: function(res){
				res = parseInt(res);
				if(res > 50){
					$j("#applyContentGuideDownloadCount").html(res).parent().show();
					$j("#applyContentGuideDownloadCountSticky").html(res).parent().show();
				}
			}
		});
	}
</script>
