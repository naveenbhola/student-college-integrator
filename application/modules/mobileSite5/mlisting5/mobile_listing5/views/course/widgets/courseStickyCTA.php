<div class="com-shrtlst-sec" id="stickyCTA" style="display: none;">
	<div class="lcard">
		<div class="com-col">
			<a class="compare-site-tour btn-mob-blue compareButton btnCmpGlobal<?=$courseId?>" href="javascript:void(0);" id="stickyCompareButton" ga-attr="COMPARE_STICKY_COURSEDETAIL_MOBILE">
				<strong id="plus-icon<?=$courseId?>" style="visibility:hidden" class="plus-icon"></strong>
				<span>Compare</span>
			</a>
		</div>
		<div class="com-col">
			<a class="deb-site-tour btn-mob<?php echo $brochureDownloaded?'-dis':''; ?>" href="javascript:void(0);" id="stickyBrochureButton<?php echo $brochureDownloaded?'-dis':''; ?>" ga-attr="DEB_STICKY_COURSEDETAIL_MOBILE" tracking-id="955" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>">
				<span><?php echo $brochureDownloaded?'Brochure Mailed':'Request Brochure'; ?></span>
			</a>
		</div>
	</div>
</div>
