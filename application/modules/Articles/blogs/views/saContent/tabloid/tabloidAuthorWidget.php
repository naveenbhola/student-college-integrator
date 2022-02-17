<?php
if($authorDataUser):
       if($authorDataUser['gender']=='male')
	      $prefix = 'him';
       else if($authorDataUser['gender']=='female')
	      $prefix = 'her';
       else $prefix = 'them';
       $gplusprofile = $authorDataUser['gplusprofile'];
       $fprofile = $authorDataUser['fprofile'];
       $tprofile = $authorDataUser['tprofile'];

?>

	<?php if($avatarimageurl=='') $avatarimageurl = '/public/images/photoNotAvailable.gif';?>

	 <div class="author-section clearwidth" style="margin:0px 0 15px 0;">
            	<h2>About the author</h2>
                <div class="author-content">
					  <div class="author-image flLt">
								 <img src="<?=$avatarimageurl;?>" width="104" height="78" alt="<?=$authoruserName ;?>"/>
								 <?php if(($authorDataUser['fprofile']!='') || ($authorDataUser['gplusprofile']!='') || ($authorDataUser['tprofile']!='' )):?>
											<br><p class="author-subtitle">Follow <?=$prefix;?> on</p>
											<p class="font-11">
													   <?php if($authorDataUser['fprofile']!=''):?><a href="<?=$fprofile;?>" class="mR15">Facebook</a>  <?php endif;?>
													   <?php if($authorDataUser['gplusprofile']!=''):?> <a href="<?=$gplusprofile;?>" class="mR15">Google+</a> <?php endif;?>
													   <?php if($authorDataUser['tprofile']!=''):?><a href="<?=$tprofile;?>">Twitter</a><?php endif;?>
											</p>
								 <?php endif;?>
					  </div>
					  <div class="abt-author">
								 <div class="athr-details">
											<strong class="authr-name">
													   <?= $authoruserName;?>
											</strong>
											<p class="author-subtitle"><?= $authorDataUser['about_me_current_position'];?></p>
											<p>
													   <?= $authorDataUser['about_me_education'];?>
											</p>
								 </div>
					  </div>
           </div>
		  <?php 
		     /*$count_articles = count($articlesList);
		     if($count_articles >12)
			    $noOfSlides = 3;
		     else if($count_articles >6)
			    $noOfSlides = 2;
		     else
			    $noOfSlides = 1;
		     
		      if(count($articlesList)!=0): ?>
		     <div class="author-related-articles clearwidth">
                	<div class="related-title clearwidth">
                    	<h2 class="flLt" style="margin-bottom:0">More articles by this author</h2>
			<?php if($noOfSlides > 1): ?>
			
		         <div class="next-prev flRt">
                            <a id="prev_article_rdgn" class="prev-box" href="javascript:void(0);" onclick="if(is_processed1 == true) {showNextArticle(--article_index_HPRDGN);}"><i id="prev_i" class="common-sprite prev-icon"></i></a>
                            <a id="next_article_rdgn" class="next-box active" href="javascript:void(0);" onclick="if(is_processed1 == true){showNextArticle(++article_index_HPRDGN);}"><i id="next_i" class="common-sprite next-icon-active"></i></a>
                        </div>
		    
		     <?php endif; ?>
                    </div>
		    
	   
		  <div style="overflow: hidden;width:612px; " id="flavouredArticle">
		  
                    <ul class="article-list clearwidth" style="width:<?=$noOfSlides* 612;?>px"  id="slider_ul">
		    <?php
		            $i = 0;
		     foreach($articlesList as $key=>$article){
			    $articleImage = str_replace("_s","_75x50",$article['contentImageURL']);
			    $articleImage = $articleImage == '' ? '/public/images/articlesDefault_s.gif' : $articleImage;
			    $numComments = $article['commentCount'];
			    $numViews = $article['viewCount'];
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
		         if($i%6==0):
		     ?>
		    <li class="clearwidth" style="width:612px;">
		      <?php endif;?>
		     <div class="flLt article-width">
                            <div class="related-article-img flLt">
                       	    	<img src="<?=$articleImage;?>" width="75" height="51" alt="<?=trim($article['strip_title'])?>" />
                            </div>
                            <div class="related-article-detail">
                            	<a href="<?=$article['contentURL'];?>">
				<?php $article['strip_title'] = trim($article['strip_title']);
				      echo substr($article['strip_title'],0,35); ?>
				<?php if(strlen(trim($article['strip_title']))>35) {echo "...";}?>
				
				</a>
                                <?php if($numComments!=0 || $numViews!=0):?>
				<span class="commnt-title">
				
				   <?php if($numComments!=0):?>
				   <i class="article-sprite gray-commnt-icon"> </i>
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
                            </div>
		     </div>
		      <?php $i++;
			    if($i%6==0): ?>
		     </li>
		     <?php endif;?>
		     <?php } ?>
                    </ul>
		  </div>
                 </div>
		 <?php endif; */?>
            </div>

<script>
       var is_processed = true;
       var is_processed1 = true;
       var article_index_HPRDGN =0;
       var total_article_element = '<?php echo $noOfSlides;?>';
       var index_article = 0;
</script>
<?php endif;?>
