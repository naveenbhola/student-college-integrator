 <div class="exam-wrap clearfix">
    	<h1 class="examPage-title">Preparation Tips</h1>
        <ul class="prep-tip-list" id="main_preptip_container">
		<?php $this->load->view('innerPrepTipPage');?>
	</ul>
	<?php if(isset($totalNumRowsPrep) && $totalNumRowsPrep >3){?>
	<a class="show-more" href="javascript:void(0);" id="load_more_article">Show More</a>
	<?php }?>
</div>
<script>
// show load more preptips
<?php $total_pages = ceil($totalNumRowsPrep/$item_per_page);?>
var total_pages = '<?php echo $total_pages; ?>';
$("#load_more_article").unbind('click');
$("#load_more_article").click(function (e) { 
getMoreArticlePage(this, '<?php echo $params;?>', total_pages, 'main_preptip_container','article');
trackEventByGAMobile('MOBILE_PREPARATION_TIPS_EXAMPAGE_SHOW_MORE');
});
</script>	
    