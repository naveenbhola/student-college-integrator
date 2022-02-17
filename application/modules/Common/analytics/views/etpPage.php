<?php
if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
	$isMobile = 1;
    $this->load->view("analytics/etpMobile");
}
else{
	$isMobile = 0;
    $this->load->view("analytics/etpDesktop");
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

<?php
  if(!(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile'))){
?>
<div class="notify-bubble report-msg" id="noti" style="top: 130px;opacity: 1; display: none;">
   <div class="msg-toast">
   <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
   <p id="toastMsg"></p>
   </div>
</div>
<?php
  }
?>
<div class="main__layer" id="trendExmPopUpLayer"></div>
<?php
    $this->load->view('etpWidgets/courseLayer');
?>
<script type="text/javascript">

  isCompareEnable = true;
	var isMobileFlag      = <?php echo $isMobile;?>;
	var streamData        = <?php echo json_encode($popular_institutes['stream_data']);?>;    


	var statesChartData   = <?php echo json_encode($region_data['chart_data']);?>;
  var interestChartData = <?php echo json_encode($interest_data['chart_data']);?>;

  

	var credentialData    = <?php echo json_encode($popular_courses['credentials_data']);?>;
  updateStreamGraphTooltip();
  updateInterestChartData();
  updateCredentialChartData();
  
<?php if(in_array($entityType, array("exam"))){ ?>
  var  groupId = <?php echo $groupId;?>;
<?php } ?>
  jQuery(document).ready(function($) {
      initializeETP();
  });
</script>