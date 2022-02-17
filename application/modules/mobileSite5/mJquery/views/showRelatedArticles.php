<?php
// $articleStreamUrl = ($blogInfo[0]['blogType'] == 'news') ? SHIKSHA_HOME.'/news' : $this->config->item('articleUrl');
if(count($relatedBlogs) > 0){
	$showHeader = true;
	$i = 0;
	foreach($relatedBlogs  as $key=>$blogData) { ?>
	   <?php
	   $i++;
	   if($i>4)
	   break;
		   $originalSummary = $blogData['summary'];
		   $originalSummaryEncoded = base64_encode($originalSummary);
		   $refinedSummary = strip_tags($blogData['summary']);
		   $refinedSummaryEncoded = base64_encode($refinedSummary);
		   $summary = '';
		   if($originalSummaryEncoded == $refinedSummaryEncoded ) {
		   	$summary = $originalSummary;
		   }
		  
	   
	   ?>
	   
	  
	  
		<section id="relatedArticles" class="content-wrap2">
		<?php if($showHeader) : ?><h2 class="related-articles-head">Related Articles</h2> <?php $showHeader= false; endif; ?>
		<div class="tupple-wrap" onclick="window.location.href='<?=$blogData['url'];?>';" style="cursor: pointer;">
		<h3><a href="<?=$blogData['url'];?>"><?=$blogData['blogTitle'];?></a></h3>
		<?php if(!empty($summary)):?>
		<p class="tupple-content"><?=$summary;?></p>
		<?php endif;?>
		<?php if($blogData['viewCount'] > 0):?>
		<div class="footer-links" style="color:#aeadad !important">
			<?=$blogData['viewCount'];?> view<?php if($blogData['viewCount']>1) echo s;?>
		</div>
		<?php endif;?>
		</div>
		</section>
	<?php }
}
?>
