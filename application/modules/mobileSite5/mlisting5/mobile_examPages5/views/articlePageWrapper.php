<div class="exam-wrap clearfix">
    	<h1 class="examPage-title">News and Articles</h1>
		<span class="widget-badge">NEWS</span>
        <ul class="exam-news-box" id="main_news_container">
			<?php $this->load->view('innerNewsPage');?>
		</ul>
		<?php if(isset($totalNumRowsNews) && $totalNumRowsNews >3){?>
			<a class="show-more" href="javascript:void(0);" id="load_more_news">Show More</a>
		<?php }?>
<span class="widget-badge">ARTICLES</span>
	<ul class="exam-news-box articleBox" id="main_article_container">
		<?php $this->load->view('innerArticlePage');?>
	</ul>
	<?php if(isset($totalNumRowsArticle) && $totalNumRowsArticle >3){?>
		<a class="show-more" href="javascript:void(0);" id="load_more_article">Show More</a>
	<?php }?>
</div>
<script>
// show load more articles
<?php $total_pages = ceil($totalNumRowsArticle/$item_per_page);?>

// show load more News
<?php $total_pages_news = ceil($totalNumRowsNews/$item_per_page);?>

var total_pages = '<?php echo $total_pages; ?>';
var total_pages_news = '<?php echo $total_pages_news; ?>';

$("#load_more_article").unbind('click');
$("#load_more_article").click(function (e) { 
getMoreArticlePage(this, '<?php echo $params;?>_exampageajax', total_pages, 'main_article_container','article');
trackEventByGAMobile('MOBILE_ARTICLE_EXAMPAGE_SHOW_MORE');
});

$("#load_more_news").unbind('click');
$("#load_more_news").click(function (e) { 
getMoreArticlePage(this, '<?php echo $params;?>_exampageajax', total_pages_news, 'main_news_container','news');
trackEventByGAMobile('MOBILE_ARTICLE_EXAMPAGE_SHOW_MORE');
});
</script>	