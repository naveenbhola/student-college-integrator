                           <?php  $count_articles = count($allArticles);?>
                           <ul style="width:<?php echo $count_articles*342;?>px" id="flavoured_update_ul">
							<?php
								for($i=0;$i < $count_articles; $i++):								
									$articleImage = $allArticles[$i]['blogImageURL'] != '' ? $allArticles[$i]['blogImageURL'] : '/public/images/defaultImgForFlavour.jpg';
									$articleImage = str_replace("_s", "_m",$articleImage);
									$snippet= $allArticles[$i]['summary'] != '' ? $allArticles[$i]['summary'] : $allArticles[$i]['blogText'];
									$snippet = strip_tags($snippet);
									$articleTitle = $allArticles[$i]['blogTitle'];
									$articleUrl = $allArticles[$i]['url'];
							?>
										<li style="float:left;display:inline;width:342px;">
                                            <strong><a onclick="trackEventByGA('homepageAllArticleclick',this.innerHTML);" href="<?php echo $articleUrl; ?>" class="Fnt14" title="<?php echo $articleTitle; ?>"><?php echo wordLimiter(substr($articleTitle,0,77),77); ?><?php if(strlen($articleTitle)>77) {echo "...";}?></a></strong>
                                            <div class="figure"><a onclick="trackEventByGA('homepageAllArticleclick',this.innerHTML);" href="<?php echo $articleUrl; ?>" title="<?php echo $articleTitle; ?>"><img src="<?php echo $articleImage; ?>" border="0" width="80" height="100"/></a></div>
                                            <div class="details"><?php echo wordLimiter(substr($snippet,0,155),155); ?><?php if(strlen($snippet)>155) {echo "...";}?>
                                            <div class="clearFix spacer5"></div>
                                            <p><a class="flLt" style="background-color:#E9E9E9;padding:0 5px;"onclick="trackEventByGA('homepageAllArticleclick',this.innerHTML);" href="<?php echo $articleUrl; ?>">Know More</a><a class="flRt" onclick="trackEventByGA('homepageAllArticleclick',this.innerHTML);" href="<?php echo SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';?>">View all Articles</a></p>
                                            </div>
                                        </li>
							<?php 
							endfor;
							?>
                           </ul>
                          
                                                      
                                    
