<!--Start_Recent_Articles-->
<?php
	if(isset($recentArticles) && is_array($recentArticles) && count($recentArticles) > 1){
?>
<div class="related-article-box">
	<h4>Recent Articles</h4>
	<ul>
	<!--Start_Repeating_Data-->
	<?php
		$i = 0;
		foreach($recentArticles as $temp){
			$commentsText = '';
			if($temp['blogId'] == $blogId){
				continue;
			}
			$i++;
			if($i > 4){
				break;
			}
			$blogImageUrl  = $temp['blogImageURL'] == '' ? '/public/images/articlesDefault_s.gif' : $temp['blogImageURL'];
			$commentsCount = $temp['commentCount'];
			if($commentsCount == 1) {
				$commentsText = '1 Comment';
			}
			if($commentsCount > 1) {
				$commentsText = $commentsCount .' Comments';
			}
	?>

		<li>
		 	<div>
				<a href="<?php echo SHIKSHA_HOME.$temp['url']; ?>" title="<?php echo $temp['blogTitle']; ?>"><?php echo formatRelatedArticleTitle($temp['blogTitle'],42); ?></a>
				<div style="list-style:none;padding-left:10px;">
				<?php if($commentsCount > 0){ ?>
					<span style="display:inline; padding-right:15px; padding-left:0px;">
						<a href="<?php echo SHIKSHA_HOME.$temp['url'];?>#blogCommentSection" rel="nofollow"><?php echo $commentsText; ?></a>						
					</span>
				<?php } 
					if($temp['viewCount'] > 0){ ?>
					<span style="display:inline; padding-left:0; color:#999"><?=$temp['viewCount'];?> view<?php if($temp['viewCount'] > 1) echo s;?></span>
				<?php } 
					if($commentsCount == 0 && $temp['viewCount'] == 0){ ?>
					<span style="display:inline-block">&nbsp;</span>
				<?php } ?>
				</div>                                     
		 	</div>
		</li>
	<?php } ?>
	<!--End_Repeating_Data-->
	</ul>
	<div class="clearFix"></div>  
</div>
<?php
	}
?>
<!--End_Recent_Articles-->
