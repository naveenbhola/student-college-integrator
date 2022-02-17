<div class="filter-wrapper">
	<div id="filters" style="z-index:1;">
	<?php
		$this->load->view("categoryList/RNR/exam_filter");
		$this->load->view("categoryList/RNR/location_filter");
		$this->load->view("categoryList/RNR/fees_filter");
		$this->load->view("categoryList/RNR/specialization_filter");
	?>	
	</div>

	<div class="loader" id="loadingImage" style="position:absolute;display:none;z-index:9999;"><img src="/public/images/Loader2.GIF" /> Loading</div>
	<div class="abroad-layer" id="overlayContainer" style="display: none;">
		<div class="abroad-layer-head clearfix">
	    	<div class="abroad-layer-logo flLt"><i alt="shiksha.com" class="layer-logo"></i></div>
	        <a href="JavaScript:void(0);" onclick="hideAbroadOverlay();" title="close" class="common-sprite close-icon flRt"></a>
	    </div>
	    
	    <div class="abroad-layer-content clearfix">
	    	<div class="abroad-layer-title" id="overlayTitle"></div>
			<div id="overlayContent"></div>
	    </div>
	</div>
	<?php
		$this->load->view("categoryList/RNR/quickLinks");
	?>
</div>