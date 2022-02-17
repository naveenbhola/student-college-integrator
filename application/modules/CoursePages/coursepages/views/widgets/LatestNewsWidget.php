<?php if(!empty($widgets_data['latestNews']['articleList'])){
	$articleList = $widgets_data['latestNews']['articleList'];
	$allPageUrl = $widgets_data['latestNews']['allPageUrl'];
	$limit = $widgets_data['latestNews']['limit'];
	?>
	<div <?php echo $cssClass;?> id="<?php echo $widgetObj->getWidgetKey().'Container'?>">
	<h2><?php echo $widgetObj->getWidgetHeading(); ?></h2>
	<?php foreach ($articleList as $key => $value) {
		$count = $key + 1;
		if($key == 0){?>
			<div class="news-wrap">
				<div class="figure"><img width="106" height="82" class="lazy" data-original="<?php echo $value['blogImageURL']; ?>" alt="Latest News" /></div>
				<div class="details">
			    	<div class="title"><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "position1"?>" href="<?php echo $value['url']?>"><?php echo $value['blogTitle'];?></a></div>
			    	<p><?php echo formatArticleTitle($value['summary'], 90);	?></p>
			    	<p style="font-size:13px; color:#c2c2c2; margin-top:2px;"><?php echo $value['dateText']; if(!empty($value['blogView'])) { echo ", ".$value['blogView']." view"; if($value['blogView'] > 1){echo 's';}} ?></p>
				</div>
	    	</div>
		<?php }else{if($key == 1){?>
	    <ul class="bullet-item"><?php }?>
		    <li><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "position".$count?>" href="<?php echo $value['url'];?>"><?php echo $value['blogTitle']; ?></a>
		    <p><?php echo $value['dateText']; if(!empty($value['blogView'])) { echo ", ".$value['blogView']." view"; if($value['blogView'] > 1){echo 's';}} ?></p></li>
	    <?php if($key == $limit-1){?>
	    </ul>
	    <?php } ?>
	    <?php }} ?>
	    <div class="clearFix spacer5"></div>
	    <div class="tar"><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "View all ".$courseHomePageName." Articles"?>" href="<?php echo $allPageUrl?>">View all <?php $courseHomePageName ?> Articles &raquo;</a></div>
	</div>
<?php } ?>