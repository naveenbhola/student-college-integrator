<div class="col-lg-4">
				<div class="slidrWidgt01 slidrWidgt02 newsAndFeatureHome">
					<span class="slidrBtnNav"><a class="prev_Slide "><i class="icons ic_sldr_prv"></i></a>
					<a class="next_Slide"><i class="icons ic_sldr_nxt"></i></a></span>
					<div class="slidrWidgt_inner">
					<h1 class="wgt_HeadT1">News & Features</h1>
					<ul>
						<?php foreach ($featureArticles as $key => $value) :
							
							$articleImage         = $value['blogImageURL'] != '' ? $value['blogImageURL'] : '/public/images/defaultImgForFlavour.jpg';
							$articleImage         = str_replace("_s", "_m",$articleImage);
							$snippet              = $value['summary'] != '' ? $value['summary'] : $value['blogText'];
							$snippet              = strip_tags($snippet);
							$articleTitle         = $value['blogTitle'];
							$articleUrl           = $value['url']; 
							
							$articleTitleModified = wordLimiter(substr($articleTitle,0,77),77);
							if(strlen($articleTitle) > 77){
								$articleTitleModified = $articleTitleModified."...";
							}

							$snippetModified = wordLimiter(substr($snippet,0,155),155);
							if(strlen($snippet) > 155){
								$snippetModified = $snippetModified."...";
							}
						?>
						<li>
							<div class="w01_head">
								<a href="<?=$articleUrl?>" trackaction="homepageAllArticleclick" title="<?=$articleTitle;?>"><p class="wgt_HeadT2"><?=$articleTitleModified;?></p></a>
							</div>
							<div class="w02_mid">
								<div>
									<a trackaction="homepageAllArticleclick" tracklable="image_<?=$articleUrl;?>" href="<?=$articleUrl;?>" title="<?=$articleTitle;?>">
										<img class="lazy" data-original="<?=$articleImage?>" />
									</a>
										<p><?=$snippetModified?></p>
								</div>
								<a  href="<?=$articleUrl?>" trackaction="homepageAllArticleclick" tracklable="Know More" class="n-knowMore">Know More</a>
								<a href="<?php echo SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';?>" trackaction="homepageAllArticleclick" tracklable="View all Articles" class="viewAllArtcl"> View all Articles</a>
							</div>
							<div class="w01_fot">
								<ul class="w02_newsLink">
								<?php 
									$totalArticleToShowOnSlide = 4*($key+1);
									$startArticleNumber 	   = $key*4;

									for($i=$startArticleNumber; $i < $totalArticleToShowOnSlide;$i++): 
									$articleTitle = trim(strip_tags($newsArticles[$i]['blogTitle']));
									if(strlen($articleTitle)>40) {$articleTitle = wordLimiter(substr($articleTitle,0,40),40)."...";} else {$articleTitle = $articleTitle;}
	
									?>
										<li>
											<a href="<?=$newsArticles[$i]['url'];?>" trackaction="homepageAllArticleclick" title="<?=$newsArticles[$i]['blogTitle']?>"><?=$articleTitle?></a>
										</li>
									<?php endfor; ?>
								</ul>
							</div>
						</li>
						<?php endforeach;?>  
					</ul>
					<p class="clr"></p>
					</div>
				</div>
			</div>