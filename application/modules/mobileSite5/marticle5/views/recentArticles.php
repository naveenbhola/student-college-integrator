<?php
	if(count($recentArticles) > 0){
		$showHeader = true;
		$i = 0;
		foreach($recentArticles as $temp){
			if($temp['blogId'] == $blogId){
				continue;
			}
			$i++;
			if($i > 4){
				break;
			}
	   	?>
		<section id="relatedArticles" class="content-wrap2">
		<?php if($showHeader){ ?>
			<h2 class="related-articles-head">Recent Articles</h2> 
		<?php $showHeader = false;
			} ?>
			<div class="tupple-wrap" onclick="window.location.href='<?php echo SHIKSHA_HOME.$temp['url']; ?>';" style="cursor: pointer;">
				<h3>
					<a href="<?php echo SHIKSHA_HOME.$temp['url']; ?>"><?php echo $temp['blogTitle']; ?></a>
				</h3>
				<?php if($temp['viewCount'] > 0){ ?>
				<div class="footer-links" style="color:#aeadad !important">
					<?=$temp['viewCount'];?> view<?php if($temp['viewCount'] > 1) echo s;?>
				</div>
				<?php } ?>
			</div>
		</section>
<?php 
		}
	}
?>
