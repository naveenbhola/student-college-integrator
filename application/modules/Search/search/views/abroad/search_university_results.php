<?php if( !empty($university_count) && $university_count > 0){ ?>
<div class="univ-tab-details clearwidth" id="univ_results_section">
    <h2 class="result-head"><?php echo $university_count;?> <?php echo ($university_count == 1) ? "University" : "Universities"; ?> found for <span class="cate-color">&ldquo;<?php echo $keyword;?>&rdquo;</span></h2>
	<?php $this->load->view('abroad/search_university_tuple_list'); ?>
</div>
<?php } ?>