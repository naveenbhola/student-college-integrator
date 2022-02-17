<?php
	$total_content_rows = $solr_content_data['numfound_content'];
	if($total_content_rows <= 0) {
		if(empty($solr_institute_data['raw_keyword'])){
		?>
		<h5 class="search-result-title">No result found in Questions, Answers and Articles</h5>
		<?php
		} else {
			?>
			<h5 class="search-result-title">No result found in Questions, Answers and Articles on <span>&ldquo;<?php echo htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
			<?php
		}
	} else {
		?>
		<div class="result-section">
			<?php
			if($search_type == "content"){
				?>
				<h5 class="search-result-title">Read through <?php echo $total_content_rows;?> &lsquo;Questions&sbquo; Answers and Articles&rsquo; on <span>&ldquo;<?php echo  htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
				<div class="spacer20 clearFix"></div>
				<?php
			} else {
				?>
				<!--<h4 class="search-result-title">Cash in on our knowledge bank!</h4>-->
				<h5 class="search-result-title"><?php echo $total_content_rows;?> questions, answers & articles on <span>&ldquo;<?php echo htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
				<div class="spacer10" style="border-top:1px solid #EEE;"></div>
				<div class="spacer10 clearFix"></div>
				<?php
			}
			?>
			<ul>
				<?php
				$contentData = $content;
				$totalContentKeys = array_keys($contentData);
				
				$currentPagestart = $solr_content_data['start'];
				$resultPerPage = $general['rows_count']['content_rows'];
				$totalResultForThisSearch = $solr_content_data['numfound_content'];
				$totalResultOnCurrentPage = count($content);
				$nextPageStart = $currentPagestart + $totalResultOnCurrentPage;
				$displayRowCount = 0;
				$pageId = calculatePageId($general['rows_count']['content_rows'], $solr_content_data['start']);
				foreach($contentData as $contentRow){
					$displayRowCount++;
					$rowType = $contentRow->getFacetype();
					$viewData = array(
								'data' 		=> $contentRow,
								'rowType' 	=> $rowType,
								'count' 	=> $displayRowCount,
								'pageId'	=> $pageId
								);
					
					switch($rowType){
						case 'article':
							$this->load->view('search/search_article_snippet', $viewData);
							break;
						
						case 'question':
							$this->load->view('search/search_question_snippet', $viewData);
							break;
						
						case 'discussion':
							$this->load->view('search/search_discussion_snippet', $viewData);
							break;
					}
				}
				?>
			</ul>
			<div class="clearFix"></div>
			<ul id="content_more_search_results_<?php echo $nextPageStart;?>" style="display:none;"></ul>
			<?php
				$resultLeft = $totalResultForThisSearch - $nextPageStart;
				if($resultLeft >= $nextPageStart){
					$resultLeft = $nextPageStart;
				}
				if($resultLeft > $resultPerPage){
					$resultLeft = $resultPerPage;
				}
				
				if($totalResultOnCurrentPage > 0 && $totalResultOnCurrentPage >= $resultPerPage && $totalResultForThisSearch > $nextPageStart){
					if($search_type == "content"){
					?>
						<div class="spacer10"></div>
						<div class="btn-box" id="content-pagination-block">
							<div id="pagination-loder" style="float:left;padding-top:2px;display:none;">
								<img src='/public/images/loader_hpg.gif'></img>
							</div>
							<input type="button" onclick="displayMoreContentResults('<?php echo urlencode($urlparams['keyword']);?>', '<?php echo $nextPageStart;?>', '<?php echo $resultPerPage;?>', 'content_more_search_results_<?php echo $nextPageStart;?>');" class="see-more-res" value="See <?php echo $resultLeft;?> more <?php echo getPlural($resultLeft, 'result', false)?>" />
						</div>
					<?php
					} else {
						?>
						<div class="spacer10"></div>
						<div class="btn-box" id="content-pagination-block">
							<input type="button" onclick="postFormWithSearchType('content');" class="see-more-res" value="See more results"/>
						</div>
						<?php
					}
				}
				?>
		</div>
		<?php
	}
?>
