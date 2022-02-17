<div class="dyanamic-content" itemprop="articleBody">
	<?php 
        echo $contentData['details'];?>
	<br/>
	<?php if(!empty($alsoLikeInlineArticlesData)) { ?>
		<div class="newExam-widget">
			<h2>
		    	<div class="widget-head"><p>You might also like this<i class="common-sprite blue-arrw"></i></p></div>
		    </h2>
		    <ul class="examArtcle-list">
		        <li class="last">
		        	<?php 
		        		$fl = 0;
		        		foreach ($alsoLikeInlineArticlesData as $article) {  
		        	?>
			        	<div class="examArtcle-col <?php echo $fl?'flRt':'flLt';?>">
			            	<div class="examArtcle-img">
			            		<img width="75" height="50" alt="<?php echo htmlentities(formatArticleTitle($article['strip_title'],100)); ?>" src="<?php echo $article['contentImageURL'];?>">
			            	</div>
			                <div class="examArtcle-info">
			                	<a href="<?php echo $article['contentUrl']; ?>" style="font-size:12px !important; line-height:18px;">
			                		<?php echo htmlentities(formatArticleTitle($article['strip_title'],100)); ?>
			                	</a>
			                </div>
			            </div>
			        <?php 
			        	$fl++;
			        	} 
			        ?>
			        <div class="clearfix"></div>
		        </li>
		    </ul>
		</div>
		<!-- Old Widget!
			<div class="other-detail-sec also-like-widget"><strong>You might also like this</strong>
		       <ul class="sop-detail-list">
		       	<?php foreach ($alsoLikeInlineArticlesData as $article) {  ?>
		            <li>
		            	<a href="<?php echo $article['contentUrl']; ?>"><?php echo htmlentities(formatArticleTitle($article['strip_title'],100)); ?> </a>
		        	</li>
				<?php } ?>            
				</ul>
		    </div> 
	    -->
	<?php }	?>
	<?php
	 	echo $contentData['description2'];
	 	
	?>
</div>
<?php 
$this->load->view('widget/applyContentDownloadGuideMiddleWidget',array('isBottomWidget'=>true));?>
