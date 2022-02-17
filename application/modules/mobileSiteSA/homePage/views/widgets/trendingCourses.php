<section class="content-wrap clearfix" style="height:450px;">
	<nav class="tabs">
		<ul>
			<li class="active" id="trendingTab"><a href="javascript:void(0);" onclick="switchRecentOrTrending('trending')">Trending Courses</a></li>
		 <?php if($recentCourses['showRecentTab']==1){?>   
			<li class="active" id="recentTab"><a href="javascript:void(0);" onclick="switchRecentOrTrending('recent')">Recently Viewed</a></li>
		 <?php } ?>       
		</ul>
	</nav>
	<?php
	if($recentCourses['showRecentTab']==1)
	{  
		$this->load->view('widgets/recentCourses');
	}
	?>
	<div class="slider-box" id="trendingWrapper">
		<ul id="trendingUl" style="width:10000px;left:-6px;" class="sliderUl">
			<?php $courseCount = 0;

				foreach($trendingCourses as $tuple){ $courseCount++; ?>
                <li class="trendtuple" onclick="goToCoursePage(this);" style="width:302px;height:390px;">
                	<div class="caption">
                        <strong><a href="<?php echo $tuple['university_url']?>"><?= ($tuple['university_name']!='')?htmlentities(formatArticleTitle(html_entity_decode($tuple['university_name']),30)):""?></a></strong>
                            <p><?= $tuple['city_name'].", ".$tuple['country_name']?></p>
                    </div>
                    <div class="figure">
                        <?php if($tuple['university_image'] !=''){
                                $imgpath = $tuple['university_image'];
                                }else{
                                $imgpath = IMGURL_SECURE.'/public/images/defaultCatPage1.jpg';        
                                }
								if($recentCourses['showRecentTab']==1)
								{
									$lazyClass = (($courseCount==1) || ($courseCount == count($trendingCourses))?'lazy':'lazySwipe');
								}else{
									$lazyClass = ($courseCount>2 && $courseCount < count($trendingCourses)? 'lazySwipe':'lazy');
								}
								?>
                            <a href="<?= $tuple['course_url']?>" class="courseLinkImg">
                            <img class="hm-wdgt-img <?php echo $lazyClass;?>" data-src="<?php echo $imgpath;?>" alt="<?= $tuple['course_name'];?>" style="display:inline-block; width: 300px; height: 200px;">

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
</section>
