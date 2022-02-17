<div id="left-col">
	<?php
	
	switch($search_type){
		case 'institute':
			if($solr_institute_data['single_result'] == 0){ //Normal search results
				if($solr_institute_data['total_institute_groups'] <= 0){
					if(empty($solr_institute_data['raw_keyword'])){
						?>
						<h5 class="search-result-title">No institute or course found</h5>
						<?php
					} else {
						?>
						<h5 class="search-result-title">No institute or course found for <span>&ldquo;<?php echo  htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
						<?php
					}
				} else {
					?>
					<!--results found ==> Header message -->
					<h5 class="search-result-title">Total <?php echo getPlural($solr_institute_data['total_institute_groups'], 'institute');?> and <?php echo getPlural($solr_institute_data['numfound_course_documents'], 'course');?> for <span>&ldquo;<?php echo htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
					<div class="spacer10"></div>
					<?php
				}
				if((int)$solr_institute_data['total_institute_groups'] > 0){
					// Load clusters view
					$this->load->view('search/search_clusters');
					//Compare institute
					//$this->load->view('search/search_compare_institute_bar');
					//sort bar
					$this->load->view('search/search_sort_bar');
				}
				// Load institute listings view
				$this->load->view('search/search_institute_listings');
			} else {
				?>
				<!-- SINGLE SEARCH RESULT -->
				<!--results found ==> Header message -->
				<h5 class="search-result-title">Total <?php echo getPlural($solr_institute_data['total_institute_groups'], 'institute');?> and <?php echo getPlural($solr_institute_data['numfound_course_documents'], 'course');?> for <span>&ldquo;<?php echo  htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
				<div class="spacer10"></div>
				<?php
				if($solr_institute_data['total_institute_groups'] > 0){
					// Load clusters view
					$this->load->view('search/search_clusters');
					//sort bar
					//$this->load->view('search/search_sort_bar');
					?>
					<div class="spacer20 clearFix"></div>
					<?php
				}
				// Load single search listings view
				$this->load->view('search/search_single_institute_listing');
			}
			//Display content search results

            //if($urlparams['search_source'] != "STATIC_SEARCH"){
			?>
			
			<div class="spacer20 clearFix"></div>
			<?php
			$this->load->view('search/search_content_listings');
			?>
			<div class="spacer20" style="border-bottom:1px solid #EEE;"></div>
			<?php
            //}
			break;
		
		case 'content':
			$this->load->view('search/search_content_listings');
			if($solr_institute_data['single_result'] == 0){ //Normal search results
				if($solr_institute_data['total_institute_groups'] <= 0) {
					if(empty($solr_institute_data['raw_keyword'])){
					?>
					<div class="spacer20 clearFix"></div>
					<h5 class="search-result-title">No institute or course found</h5>
					<?php
					} else {
					?>
					<div class="spacer20 clearFix"></div>
					<h5 class="search-result-title">No institute or course found for <span>&ldquo;<?php echo  htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
					<?php
					}
				} else {
					?>
					<!--results found ==> Header message -->
					<div class="spacer20 clearFix"></div>
					<h5 class="search-result-title">Total <?php echo getPlural($solr_institute_data['total_institute_groups'], 'institute');?> and <?php echo getPlural($solr_institute_data['numfound_course_documents'], 'course');?> for <span>&ldquo;<?php echo  htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
					<div class="spacer10"></div>
					<?php
				}
				// Load institute listings view
				$this->load->view('search/search_institute_listings');
			} else {
				?>
				<!-- SINGLE SEARCH RESULT -->
				<!--results found ==> Header message -->
				<div class="spacer20 clearFix"></div>
				<h5 class="search-result-title">Total <?php echo getPlural($solr_institute_data['total_institute_groups'], 'institute');?> and <?php echo getPlural($solr_institute_data['numfound_course_documents'], 'course');?> for <span>&ldquo;<?php echo  htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
				<div class="spacer10 clearFix"></div>
				<?php
				// Load single search listings view
				$this->load->view('search/search_single_institute_listing');
			}
			break;
	}
	?>
	<?php $this->load->view('search/search_google_results_csa'); ?>
	<!-- For Google ads -->
	<!--div  class="txt_align_c ">
		<div id="wide_ad_unit"  style="text-align:left;margin:auto;"></div>
	</div-->
	<!-- For google ads ends -->
</div><!-- left-col ends -->
