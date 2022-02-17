<?php
    $loadMoreStyle = ($totalSimilarCourseCount > $resultSetChunksSize) ? "" : "display:none";
?>
<div id="cateLeftCol" style="width:665px;">
	<div class="instituteLists" id="instituteLists">
			<div class="resultfound-section">
			    <?php if($totalSimilarCourseCount) { ?>
					<span class="resultfound-title" id="resultfound-title"><?=$totalSimilarCourseCount?></span>
					Similar Institutes Found
					<?php } else {
					    ?>
					    <span class="resultfound-title" id="resultfound-title">No</span>
					     similar institutes found.<br/>
					    Please select some other course or institute above.
					    <?php
					    } ?>
			</div>
			<ul>
			<?php $this->load->view('categoryList/similarCourseListings'); ?>
			</ul>
	</div>
	<div onclick="getMoreResults()" id="more-results-tab" class="more-results-tab" style="<?=$loadMoreStyle?>"><a href="javascript:void(0);">Load more results...</a></div>
</div>