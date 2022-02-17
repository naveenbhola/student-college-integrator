<?php if(count($popularArticles)!=0):?>
 <div class="artcl-right-wdgt clearwidth">
	       <div class="widget-heading clearfix">
		       <i class="article-sprite guide-artcl-icon"></i><h2 style="margin:3px 0 0 5px; float:left">Popular Guides & Articles </h2><span>(in last 30 days)</span>
	       </div>
	       <div class="widget-list">
		       <ul>
		       <?php foreach($popularArticles as $key=>$article){
			      $numComments= $article['commentCount'];
			      $numViews = $article['articleViewCnt'];
			      switch($numComments) {
			              case '':
			              case '0' : $numComments = ''; break; 
			              case '1' : $numComments = '1 comment'; break; 
			              default : $numComments .= ' comments'; break; 
			      }
			      switch($numViews) {
				     case '':
				     case '0' : $numViews = ''; break; 
				     case '1' : $numViews = '1 view'; break; 
				     default : $numViews .= ' views'; break; 
			      }
			      
			      ?>
			      <li class="<?php if($key == count($popularArticles)-1) echo 'last';?>">
				 <a href="<?=$article['contentUrl'];?>">
				 <?php echo substr($article['strip_title'],0,110); ?>
				<?php if(strlen(trim($article['strip_title']))>110) {echo "...";}?>
				</a>
				    <?php if($numComments!=0 || $numViews!=0):?>
						<span class="commnt-title">
						  
						    <?php if($numComments!=0):?>
						     <i class="article-sprite gray-commnt-icon"></i>
							      <?=$numComments;?>
							<?php endif;?>  
							 <?php if($numComments!=0 && $numViews!=0 ):?>
								      |
							<?php endif;?>
							<?php if($numViews!=0):?>
							      <?=$numViews;?>
							<?php endif;?>
						</span>
				    <?php endif;?>
			     </li>
			     <?php }?>
		   </ul>
	       </div>
	       </div>
<?php endif;?>
