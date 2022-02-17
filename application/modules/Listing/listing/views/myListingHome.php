	<?php
		$this->load->view('listing/myListingSearchPanel');
	//	echo "<script>var categoryTreeMain = eval(".$category_tree.");	</script>";
		$this->load->view('listing/myListingLeftPanel');
		$this->load->view('listing/myListingRightPanel');
	?>
    <script>
			var SITE_URL = '<?php echo base_url() ."/";?>';
	</script>
		<!--Start_Mid_Panel-->
	<div id="mid_Panel">
		<div style="margin-bottom:11px;">
			<span class="blogheading">
				<a href="#">Home</a> &gt; My Listing
			</span>
		</div>
		<div>
			<div class="normaltxt_11p_blk fontSize_16p OrgangeFont">
				<strong>My Listing</strong>
			</div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="lineSpace_11">&nbsp;</div>		
		</div>

		<div id="blogTabContainer">

	<?php
		$this->load->view('listing/myListingMidPanel');
	?>
		</div>	
		<br clear="all" />
	</div>

	<?php $this->load->view('listing/myListingFooter'); ?>

