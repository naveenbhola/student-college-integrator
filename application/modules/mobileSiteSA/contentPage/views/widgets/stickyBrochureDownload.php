<section id="applyContentStickyDownloadBrochure" class="detail-widget"  data-enhance="false" style="padding:0px; position:fixed; bottom:0;z-index:1000;width:100%;display: none;">
	<div class="detail-widegt-sec">
		<div class="detail-info-sec" style="padding-bottom:0px;">
			<div class="offline-brochure-sec">
				<strong>Download this guide to read it offline</strong>
				<a class="download-sop-btn" href="javascript:void(0);" onclick="downloadGuidePDF('<?php echo $contentId; ?>',503,'applyContent',null);">Email <?=$contentDisplayValue?> Guide</a>
				<?php if($guideDownloadCount >= 50){
						$hideNumber = false;
					}else{
						$hideNumber = true;
					}
				?>
				<p style="text-align:center;<?=($hideNumber)?'display:none;':''?>"><span id="applyContentGuideDownloadCountSticky"><?=$guideDownloadCount?></span> people downloaded this guide</p>
			</div>
		</div>
	</div>
</section>

<script>
	var downloaderLocation = 0;

	function initDownloaderHideEffect(){
		$j(document).on('scrollstart',function(){
			downloaderLocation = $j("#inlineEmailBrochureButton").offset().top - $j(window).height() + $j("#inlineEmailBrochureButton").height();
		});
		$j(document).on('scrollstop',function(){
			return;
			if ($j(window).scrollTop() > downloaderLocation){
				$j("#applyContentStickyDownloadBrochure").css("visibility","hidden");
			}else{
				if ($j("#applyContentStickyDownloadBrochure").css("visibility") == "hidden"){
					$j("#applyContentStickyDownloadBrochure").css("height",0).css("visibility","visible").animate({"height":stickyHeight["applyContentStickyDownloadBrochure"]});
				}
			}
		});
		$j(window).on("orientationchange",function(){
			$j(document).trigger("scrollstart").trigger("scrollstop");
		});
	}
</script>
