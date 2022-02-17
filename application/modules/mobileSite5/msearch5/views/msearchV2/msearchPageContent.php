<?php 
if($isAjax) { ?>
    <?php $this->load->view('msearch5/msearchV2/msearchPageBody'); ?>
<?php } else {
    $this->load->view('msearch5/msearchV2/msearchPageHeader'); ?>
    
	<?php if ($totalInstituteCount > 0) { ?>
	    <div class="location-container srp-container" id="searchTuples">
	        <?php $this->load->view('msearch5/msearchV2/msearchPageBody'); ?>
	    </div>
	    <!-- Loading Image -->
	    <div id="loader" class="srp-loader"><img class="small-loader" id="loadingImage1" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" border=0 alt="" /></div>
	    <?php $this->load->view('msearch5/msearchV2/msearchPagination'); ?>
	<?php } else {
	    $this->load->view('msearch5/msearchV2/mserpNoResultPage.php');
	} 
	$this->load->view('msearch5/msearchV2/msearchPageFooter');
} ?>