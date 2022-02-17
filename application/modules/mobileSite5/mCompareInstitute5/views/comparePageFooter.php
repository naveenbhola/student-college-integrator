    <!-- Show the page Footer -->
    <?php
    if(count($institutes)>=2)
    {
	//$this->load->view('socialShare');
    ?>
	<div class="clearfix"></div>
	<div style="position:fixed;left:0px;bottom:0px;width:100%;float:left;display:block;z-index: 9999;" id="emailCompareDiv">
	<a href="javascript:void(0);" class="refine-btn email-btn" onclick="emailCompareResults('<?php echo $emailTrackingPageKeyId;?>'); return false;"><i class="sprite email-icon2"></i>Email this comparison</a>
	</div>
    <?php
    }
    ?>
    <!-- End the Footer page -->
