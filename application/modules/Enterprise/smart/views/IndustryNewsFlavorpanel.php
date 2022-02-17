			   <?php  $count_flavours = count($flavoredArticle);?>                          
			   <ul style="width:<?php echo $count_flavours*410;?>px" id="flavoured_update_ul_smart">
							<?php
								for($i=0;$i < $count_flavours; $i++):								
									$articleImage = $flavoredArticle[$i]['blogImageURL'] != '' ? $flavoredArticle[$i]['blogImageURL'] : '/public/images/defaultImgForFlavour.jpg';
									$articleImage = str_replace("_s", "_m",$articleImage);
									$snippet= $flavoredArticle[$i]['summary'] != '' ? $flavoredArticle[$i]['summary'] : $flavoredArticle[$i]['blogText'];
									$snippet = strip_tags($snippet);
									$articleTitle = $flavoredArticle[$i]['blogTitle'];
									$articleUrl = $flavoredArticle[$i]['url'];
							?>
										<li style="float:left;display:inline;width:410px;">
                                            <strong><a onclick="trackEventByGA('homepageflavouroftheweekclick',this.innerHTML);" href="<?php echo $articleUrl; ?>" class="Fnt14" title="<?php echo $articleTitle; ?>"><?php echo wordLimiter(substr($articleTitle,0,77),77); ?><?php if(strlen($articleTitle)>77) {echo "...";}?></a></strong>
                                            <div class="spacer10 clearFix"></div>
                                            <div class="carausel-figure"><a onclick="trackEventByGA('homepageflavouroftheweekclick',this.innerHTML);" href="<?php echo $articleUrl; ?>" title="<?php echo $articleTitle; ?>"><img src="<?php echo $articleImage; ?>" border="0" width="80" height="100"/></a></div>
                                            <div class="carausel-details"><?php echo wordLimiter(substr($snippet,0,155),155); ?><?php if(strlen($snippet)>155) {echo "...";}?>
                                            <div class="spacer8 clearFix"></div>
                                            <p><a class="flLt" style="background-color:#E9E9E9;padding:0 5px; "onclick="trackEventByGA('homepageflavouroftheweekclick',this.innerHTML);" href="<?php echo $articleUrl; ?>">Know More</a><a class="flRt" onclick="trackEventByGA('homepageflavouroftheweekclick',this.innerHTML);" href="<?php echo SHIKSHA_HOME.'/blogs/shikshaBlog/getFlavorArticles';?>">View all Features</a></p>
                                            </div>
                                        </li>
							<?php 
							endfor;
							?>
                           </ul>
                          
                                                      
                                    
