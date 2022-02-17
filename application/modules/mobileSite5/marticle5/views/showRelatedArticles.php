
<?php
// $articleStreamUrl = ($blogInfo[0]['blogType'] == 'news') ? SHIKSHA_HOME.'/news' : $this->config->item('articleUrl'); 
if(count($relatedBlogs) > 0){
	$i = 0;
	?>
	<section id="relatedArticles" class="content-wrap2">
		<h2 class="related-articles-head">Related Articles</h2>
		<?php foreach($relatedBlogs  as $key=>$blogData) { 	   
		   $i++;
		   if($i>4)
		   break;
		   ?>
			<div class="tupple-wrap" >
				<h3><a href="<?php echo addingDomainNameToUrl(array('domainName' => SHIKSHA_HOME ,'url' => $blogData['url'])); ?>"><?=$blogData['blogTitle'];?></a></h3>
				<?php if($blogData['viewCount'] > 0):?>
				<div class="footer-links" style="color:#aeadad !important">
					<?=$blogData['viewCount'];?> view<?php if($blogData['viewCount']>1) echo s;?>
				</div>
				<?php endif;?>
			</div>
		<?php } ?>
	</section>
	<?php
}
?>