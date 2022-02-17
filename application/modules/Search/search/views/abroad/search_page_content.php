<div class="content-wrap clearfix">
	<?php
	$this->load->view('abroad/search_bar');
	$this->load->view('abroad/search_title_navbar');
	$this->load->view('abroad/search_university_results');
	$this->load->view('abroad/search_course_results');
//	$this->load->view('abroad/search_content_results');
//	$this->load->view('abroad/search_page_pagination');
	?>
</div>
<?=$trackingId > 0 ? "<script>var trackingId = ".$trackingId.";</script>" :"" ?>
<script>
var searchKeyword = '<?=empty($keywordEncoded) ? "none" : $keywordEncoded?>';	
var pageType = '<?=empty($from_page) ? "searchPage" : urlencode($from_page);?>';
var courseResultCount = <?=empty($sa_course_count) ? 0 : $sa_course_count?>;
var universityResultCount = <?=empty($university_count) ? 0 : $university_count?>;
var courseResultPageNo = 1;
var universityResultPageNo = 1;
</script>