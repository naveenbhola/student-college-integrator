<section class="detail-widget">
	<div class="detail-widegt-sec">
		<strong style="padding:10px 5px; border-bottom:1px solid #e5e5da" class="article-info-title">You might also be interested in</strong>
		<ul class="article-list">
			<?php foreach($popularArticles as $article){ ?>
			<?php $showPipe = ($article->commentCount == 0 || $article->viewCount == 0)?false:true; ?>
				<li>
					<a href="<?=$article->contentURL?>"><?=htmlentities($article->strip_title)?></a>
					<p><?=formatArticleTitle(strip_tags($article->summary),140)?></p>
					<p class="comment-view-info">
						<?php if($article->commentCount > 0) { ?>
							<?=$article->commentCount?>  comment<?=$article->commentCount==1?'':'s'?>
						<?php } ?>
						<?php if($showPipe){?>
							<span>|</span>
						<?php } ?>
						<?php if($article->viewCount > 0){ ?>
							<?=$article->viewCount?> view<?=$article->viewCount==1?'':'s'?></p>
						<?php } ?>
				</li>
			<?php } ?>
		</ul>
	 </div>
</section>