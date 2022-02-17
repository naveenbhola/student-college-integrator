<div class="sop-other-widget clearwidth" style="border:none;">
	<strong class="sop-other-widget-head" style="border:1px solid #e5e5e5; border-bottom:2px solid #008489">Related Guides & Articles</strong>
	<ul class="sop-article-list sop-border">
		<?php
			$totalResultCount = count($relatedGuideArticles);
			$count = 1;
			foreach($relatedGuideArticles as $key=>$value){
        ?>
		<li>
			<a href="<?php echo $value['contentUrl'];?>"><?php echo htmlentities($value['strip_title']); ?></a>
			<?php
				$displayString = '';
				if($value['commentCount']>0){
					$displayString .= $value['commentCount'];
					$displayString .= " ";
					$displayString .= ($value['commentCount'])>1?'comments':'comment';
                }
                if($value['viewCount']>0){
					$displayString .= ($displayString != '')?' | ':'';
					$displayString .= $value['viewCount'];
					$displayString .= " ";
					$displayString .= ($value['viewCount'])>1?'views':'view';
                }
            ?>
			<?php if($displayString!=''){ ?>
					<span class="sop-commnt-title"><i class="sop-sprite gray-commnt-icon"></i><?=$displayString?></span>
			<?php } ?>
		</li>
		<?php	$count++;
			}
        ?>
	</ul>
</div>
