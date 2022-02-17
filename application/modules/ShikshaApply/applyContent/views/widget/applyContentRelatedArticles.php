<?php   if(!empty($popularArticlesData)){ ?>
<div class="sop-other-widget clearfix">
	<strong class="sop-other-widget-head">Article<?php if(count($popularArticlesData)>1)echo 's'; ?> related to <?php echo htmlentities($contentHeading);?></strong>
	<ul class="sop-article-list apply-cont-related-articles-widget">
		<?php 	foreach ($popularArticlesData as $value) { ?>
		<li><a href="<?php echo $value['contentUrl']; ?>"><?php echo htmlentities($value['strip_title']); ?></a><br>
			<?php if($value['viewCount']!="0" || $value['commentCount']!="0" ){ ?>
				<span class="sop-commnt-title">
				  <?php if($value['commentCount']!="0" || !empty($value['commentCount'])){
					  		echo '<i class="sop-sprite gray-commnt-icon"></i>'.$value['commentCount'];
					  		echo ($value['commentCount']=="1")?" comment":" comments";  } ?>
				  <?php if($value['viewCount']!="0" && $value['commentCount']!="0" ){echo  '|';  }?>
				  <?php if($value['viewCount']!="0" || !empty($value['viewCount'])){
				  	  		echo $value['viewCount'];
					  		echo ($value['viewCount']=="1")?" view":" views";  } ?>
				</span>
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
</div>
<?php }?>