<section class="detail-widget"  data-enhance="false">
	<div class="detail-widegt-sec">
		<div class="detail-info-sec sop-content dyanamic-content">
			<?php  // Getting title heading
		$title=$contentData['strip_title'];
		if(empty($topNavData['links_data'])){
				$title_heading="<h1 itemprop='headline name'> $title </h1>"; }
			else
				$title_heading="<h2 class='ContentHeading-h2'> $title </h2>"; 
		echo $title_heading;
		?>
		
			<div itemprop="articlebody">
				<?php echo $contentData['details']; ?>
				<?php if(!empty($alsoLikeInlineArticlesData)) { ?>
					<div class="sop-dynamic-content sop-dynamic-content2 sop-widget2">
						<strong>You might also like to read</strong>
						<div class="">
							<ul>
								<?php foreach ($alsoLikeInlineArticlesData as $key => $article) { ?>
									<li class="<?php if($key == 1){echo "last";}?>">
					                	<div class="article-img"><img src="<?php echo str_replace("_s", "_75x50", $article['contentImageURL']); ?>" height="50" width="75"></div>
					                    <div class="article-info"><a href="<?php echo $article['contentUrl']; ?>"><?php echo htmlentities(formatArticleTitle($article['strip_title'],50)); ?></a></div>
					                </li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<!-- Old widget
					<div class="sop-dynamic-content sop-dynamic-content2">
	                    <strong>You might also like to read</strong>
	                    <ul>
	                      <?php foreach ($alsoLikeInlineArticlesData as $article) {  ?>
					            <li><a href="<?php echo $article['contentUrl']; ?>"><?php echo htmlentities(formatArticleTitle($article['strip_title'],50)); ?> </a></li>
						  <?php } ?> 
	                    </ul>
	                 </div>-->
                 <?php } ?>
				<br/>
				<?php echo $contentData['description2']; ?>
			</div>
		</div>
	</div>
</section>