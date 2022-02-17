<?php $i=0; foreach ($articleList as $article) {
	$blogTitle = $article->getBlogTitle();
	$blogURL = $article->getUrlNew();
	if(empty($blogURL)){
		$blogURL = $article->getBlogUrl();
	}
	$summary = strip_tags($article->getSummary());
	if(strlen($summary) > 200){
		$summary = preg_replace('/\s+?(\S+)?$/', '',substr($summary, 0,200))."...";
	}
	$numberOfComments = $commentCount[$article->getDiscussionTopicId()];
?>
<section class="content-wrap2 clearfix">
	<a href="<?php echo $blogURL;?>" class="tupple-wrap" style="cursor: pointer;<?php if($i==0) echo "padding-top:22px";?> ">
	<h3><?php echo $blogTitle;?></h3>
	<?php if(!empty($summary)){?>
	<span class="tupple-content"><?php echo $summary;?></span>
	<?php } ?>
    </a>	
</section>
<?php $i++; } ?>