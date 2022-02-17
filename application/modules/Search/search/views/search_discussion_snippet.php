<?php
if($_REQUEST['debug'] == "general"){
	echo "DS: ". $data->getDocumentScore() . "<br/>";
	echo "pageId: ". $pageId . "<br/>";
	echo "RC: ". $count . "<br/>";
}
?>
<li>
	<h5>
		<a href="<?php echo $data->getUrl();?>" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>', '<?php echo $data->getThreadId(); ?>', 'discussion-title', 'normal', '<?php echo $pageId; ?>');">
			<?php echo $data->getTitle();?></b>
		</a>
	</h5>
	<div class="res-figure">
		<a href="javascript:void(0);">
			<img src="<?php echo getImageUrlBySize($data->getCommentUserImageUrl(), 'medium');?>" width="117" height="78" alt="comment author image" />
		</a>
	</div>
	<div class="res-details">
		<em>Commented by
			<a href="<?php echo getUserProfileLink($data->getCommentUserDisplayName());?>" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo getUserProfileLink($data->getCommentUserDisplayName());?>', '<?php echo $data->getThreadId(); ?>', 'discussion-user-profile', 'normal', '<?php echo $pageId; ?>');">
				<?php echo $data->getCommentUserDisplayName();?>
			</a>
		</em>
		<p><?php echo $data->getCommentText();?></b></p>
		<a href="<?php echo $data->getUrl();?>" style="text-decoration:none;" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $data->getUrl();?>', '<?php echo $data->getThreadId(); ?>', 'discussion-comment-button', 'normal', '<?php echo $pageId; ?>');">
		<?php
		if($data->getCommentCount() > 1){
			?>
			<input type="button" value="View All <?php echo getPlural($data->getCommentCount(), 'Comment');?>" class="gray-button" />
			<?php
		} else if($data->getCommentCount() == 1){
			?>
			<input type="button" value="View 1 Comment" class="gray-button" />
		<?php
		} else {
			?>
			<input type="button" value="No Comments" class="gray-button" />
			<?php
		}
		?>
			
		</a>
		<span class="view-commt">
			(<?php echo getPlural($data->getCommentCount(), 'Comment');?>)
		</span>
	</div>
</li>
