<section id="emailGuideSticky" class="detail-widget"  data-enhance="false" style="padding:0px; position:fixed; bottom:0;z-index:1000;width:100%;display: none;">
	<div class="detail-widegt-sec">
		<div class="detail-info-sec" style="padding:15px 15px 5px 15px">
			<div class="offline-brochure-sec">
				<strong>Download this guide to read it offline</strong>
				<a class="download-sop-btn" href="javascript:void(0);" onclick="emailGuide('<?php echo $examPageObj->getExamPageId();?>','abroadExamPage',498);">Email <?php echo $examPageObj->getExamName();?> Guide</a>
					<p style="margin:0;text-align:center; <?php if($guideDownloadCount < 50){ echo 'display:none;'; } ?>"><span id="examGuideDownloadCountSticky"><?=$guideDownloadCount?></span> people downloaded this guide</p>
			</div>
		</div>
	</div>
</section>

<script>
	var downloaderLocation = 0;

	function initDownloaderHideEffect(){
		if ($j("#emailGuideSticky").length == 0) {
			return;
		}
		$j(document).on('scrollstart',function(){
			downloaderLocation = $j("#emailGuideInline").offset().top - $j(window).height() + $j("#emailGuideInline").height();
		});
		$j(document).on('scrollstop',function(){
			if ($j(window).scrollTop() > downloaderLocation){
				$j("#emailGuideSticky").css("visibility","hidden");
			}else{
				if ($j("#emailGuideSticky").css("visibility") == "hidden"){
					$j("#emailGuideSticky").css("height",0).css("visibility","visible").animate({"height":stickyHeight["emailGuideSticky"]});
				}
			}
		});
		$j(window).on("orientationchange",function(){
			$j(document).trigger("scrollstart").trigger("scrollstop");
		});
	}
</script>