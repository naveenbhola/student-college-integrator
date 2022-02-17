<?php foreach($articles as $article):  $i=0; ?>
        <?php 
         			if($article['parentCategoryId']) {
        				$url = $baseUrl . '?subcat=' . $article['boardId'];
         			}else {
         				$url = $baseUrl;
         			}

        			$originalSummary = $article['summary'];
				
        			$originalSummaryEncoded = base64_encode($originalSummary);
        			$refinedSummary = strip_tags($article['summary']);
        			$refinedSummaryEncoded = base64_encode($refinedSummary);
        			$summary = '';
        			if($originalSummaryEncoded == $refinedSummaryEncoded ) { 
        				$summary = $originalSummary; 
        			}
        			
        ?>       
		<section class="content-wrap2 clearfix">
			<div class="tupple-wrap" onclick="window.location.href='<?=$article["url"];?>';" style="cursor: pointer;<?php if($i==0) echo "padding-top:22px";?> ">
			<h3><a href="<?php echo $article['url'];?>"><?php echo $article['blogTitle'];?></a></h3>
			<?php if(!empty($summary)):?>
			<p class="tupple-content"><?php echo $summary; ?> </p>
			<?php endif;?>
			<!--- Display course name only when All Articles(Default) is selected -->
			<?php if(!$categoryId && !$subCategoryId && $blogType != 'kumkum'):?>  
			<div class="footer-links">
				<label>Course:</label>
			  <a href="<?php echo $url;?>"><?php echo $article['name'];?></a>
			</div>
			<?php endif;?>
		    </div>	
		</section>

<?php $i++; endforeach;?>


