<?php
if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
	$isMobile = 1;
    $this->load->view("analytics/trendsHomeMobile");
}
else{
	$isMobile = 0;
    $this->load->view("analytics/trendsHomeDesktop");
}
?>
<div class="md-modal md-effect-3" id="modal-3">
    <div class="md-content">
        <div>
          <div class="md-hdr">
                <p class="help-txt-title">Title</p>
                <a id="close" onclick="closePopup();">&times;</a>
         </div>
        <p class="help-txt-container"></p>
  </div>
</div>
</div>
<div class="shadow_bg"></div>

<script type="text/javascript">
  var isShowAppBanner = 0; 
	var isMobileFlag      = <?php echo $isMobile;?>;
	var ownershipData  = <?php echo json_encode($popular_universities['ownership_data']);?>;
	var streamData     = <?php echo json_encode($popular_institutes['stream_data']);?>;
	var credentialData = <?php echo json_encode($popular_courses['credentials_data']);?>;
  updateStreamGraphTooltip();
  updateCredentialChartData();
	jQuery(document).ready(function($) {
		initializeTrendHome();
	});
</script>