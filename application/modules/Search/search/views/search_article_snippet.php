<li>
	<?php
	if($_REQUEST['debug'] == "general"){
		echo "DS: ". $data->getDocumentScore() . "<br/>";
		echo "pageId: ". $pageId . "<br/>";
		echo "RC: ". $count . "<br/>";
	}
	?>
	<h5>
		<a href="<?php echo $data->getUrl();?>"  onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>', '<?php echo $data->getId(); ?>', 'article-title', 'normal' , '<?php echo $pageId; ?>');">
			<?php echo $data->getTitle();?></b>
		</a>
	</h5>
	<div class="res-figure">
		<a href="javascript:void(0);">
			<img src="<?php echo getImageUrlBySize($data->getImageUrl(), 'medium');?>" width="117" height="78" alt="blog image" />
		</a>
	</div>
	<div class="res-details">
		<em>Article by <?php echo $data->getUserDisplayName();?></em>
		<p>...<?php echo $data->getBody();?>...</b></p>
		<a href="<?php echo $data->getUrl();?>" style="text-decoration:none;" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>', '<?php echo $data->getId(); ?>', 'article-read-more', 'normal', '<?php echo $pageId; ?>');">
			<input type="button" value="Read more" class="gray-button" />
		</a>
		<span class="view-commt">
			(<?php echo getPlural($data->getViewCount(), 'View');?>,
			<a href="<?php echo $data->getUrl();?>#blogCommentSection" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>#blogCommentSection', '<?php echo $data->getId(); ?>', 'article-comment-link', 'normal', '<?php echo $pageId; ?>');"><?php echo getPlural($data->getCommentCount(), 'Comment');?></a>
			)
		</span>
	</div>
</li>
