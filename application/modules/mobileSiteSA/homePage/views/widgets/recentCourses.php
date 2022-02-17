<div class="slider-box" id="recentWrapper">
            <ul id="recentUl" style="width:10000px;" class="sliderUl">
                <?php $courseCount = 0;
	    foreach($recentCourses['finalRecentCourseData'] as $tuple){ $courseCount++; ?>
                <li class="trendtuple" onclick="goToCoursePage(this);" style="width:302px;">
                    <div class="caption">
                        <strong><a href="<?php echo $tuple['university_url']?>"><?= ($tuple['university_name']!='')?htmlentities(formatArticleTitle(html_entity_decode($tuple['university_name']),40)):""?></a></strong>
                            <p><?= $tuple['city_name'].", ".$tuple['country_name']?></p>
                    </div>
                    <div class="figure">
                        <?php if($tuple['university_image'] !=''){
                                $imgpath = $tuple['university_image'];
                                }else{
                                $imgpath = '/public/images/defaultCatPage1.jpg';        
                                }?>
                            <a href="<?= $tuple['course_url']?>" class="courseLinkImg">
                            <img class="<?php echo ($courseCount==1||$courseCount==2||$courseCount ==count($recentCourses['finalRecentCourseData'])?'lazy':'lazySwipe'); ?>" src="" width="300" height="200" data-src="<?php echo $imgpath;?>" alt="<?= $tuple['course_name'];?>">
                            </a>
                     </div>
                    <div class="univ-details">
                        <strong><?= ($tuple['course_name']!='')?(htmlentities(formatArticleTitle(html_entity_decode($tuple['course_name']),30))):""?></strong> 
                    </div>
                    <ol class="univ-info">
							<?php if(!empty($tuple['course_duration'])){ ?>
								<li>
									<label>Duration</label>
									<p>
										<span style="color:#000; display:inline-block; width:58px; margin:0 0 4px 0">
											<?= $tuple['course_duration']?>
										</span>
									</p>
								</li>
							<?php } ?>
                            <?php if($tuple['first_year_fees']!=''){?>
                            <li>
                            <label>1st Year Total Fees</label>
                                <p><?php
                                $string = explode(' ',$tuple['first_year_fees']);
                                if(count($string)<3){
                                   $string[2] = ' ';     
                                }
                                $patterns = array();
                                $patterns[0] = '/'.$string[0].'/';
                                $patterns[1] = '/'.$string[2].'/';
                                $replacements = array();
                                $replacements[1] = $string[0].'<big>';
                                $replacements[0] = '</big>'.$string[2];
                                
                                $finaltxt = (preg_replace($patterns, $replacements, $string));
                                 $finaltxt = implode(' ',$finaltxt);
                                 echo $finaltxt;
                                 ?>
                                </p>
                            </li>
                            <?php }?>
                            <?php if(count($tuple['eligibility_exam'])>0){?>
							<li>
									<label style="vertical-align: top;">Eligibility</label>
									<p>
											<span style="color:#000; display:inline-block; width:44px; margin:0 0 4px 0"><?= $tuple['eligibility_exam'][0]['name']?> </span><big> : <?= $tuple['eligibility_exam'][0]['cutoff']?></big>
											<?php if(array_key_exists(1,$tuple['eligibility_exam'])){?>    
													<br/>
													<span style="color:#000; display:inline-block; width:44px; margin:0"><?= $tuple['eligibility_exam'][1]['name']?> </span><big> : <?= $tuple['eligibility_exam'][1]['cutoff']?></big> 
											<?php }else{ ?>
													<!-- This section ensures that height remains constant -->
													<br/>
													<span style="color:#000; display:inline-block; width:44px; margin:0">&nbsp;</span><big></big>
											<?php } ?>
									</p>
							</li>
						<?php } ?>
                    </ol>
				</li>
			<?php } ?>
		</ul>
	</div>