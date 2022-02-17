<div id="fbGoogleDiv" style="width:275px;visibility: hidden;">

	<?php 
		if($coursePageCategoryIdentifier != 'ENGINEERING_PAGE')
				 echo $rankingWidgetHTML; 
	?>
	<div class="section-cont" id="google_add_content">
	<?php
		$bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
		$this->load->view('common/banner',$bannerProperties);
	?>
	</div>
	<div id="fbTitle" class="section-cont-title"><b style="font-size:14px;">Like us on Facebook</b></div>
	<div id="fbContent" class="shadow-box">
		<div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="265" data-show-faces="true" data-border-color="#f2f2f2" data-stream="false" data-header="false"></div>
	</div>
</div>
<script>
	var isfbGoogleAdWidgetAppearing = 1;
</script>

