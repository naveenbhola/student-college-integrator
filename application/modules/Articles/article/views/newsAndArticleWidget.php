<div class="widget-wrap">
<h2>Latest News And Articles<?php echo $courseHomePageName;?></h2>
<?php foreach ($articleList as $key => $value) {
	if($key == 0){?>
		<div class="news-wrap">
			<div class="figure"><img width="106" height="82" class="lazy" data-original="<?php echo $$value['blogImageURL']; ?>" alt="Latest News" /></div>
			<div class="details">
		    	<div class="title"><a href="<?php echo $value['url']?>"><?php echo $value['blogTitle'];?></a></div>
		    	<p><?php echo formatArticleTitle($value['summary'], 90);	?></p>
		    	<p style="font-size:13px; color:#c2c2c2; margin-top:2px;"><?php echo $value['dateText']; if(!empty($value['blogView'])) { echo ", ".$value['blogView']." view"; if($value['blogView'] > 1){echo 's';}} ?></p>
			</div>
    	</div>
	<?php }else{if($key == 1){?>
    <ul class="bullet-item"><?php }?>
	    <li><a href="<?php echo $value['url'];?>"><?php echo $value['blogTitle']; ?></a>
	    <p><?php echo $value['dateText']; if(!empty($value['blogView'])) { echo ", ".$value['blogView']." view"; if($value['blogView'] > 1){echo 's';}} ?></p></li>
    <?php if($key == $limit-1){?>
    </ul>
    <?php } ?>
    <?php }} ?>
    <div class="clearFix spacer5"></div>
    <div class="tar"><a href="<?php echo $allPageUrl?>">View all Articles &raquo;</a></div>
</div>
<script src="//<?php echo JSURL; ?>/public/js/jquery.lazyload.min.js"></script>
<script type="text/javascript">
$j(window).load(function(){
	$j("img.lazy").lazyload({effect : "fadeIn",threshold : 100}); 
});
</script>