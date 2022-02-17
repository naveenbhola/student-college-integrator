<div class="col-bar">
	         	<h2 class="titl-main">Applicable course, university and country</h2>
	         	<?php if(!empty($courseLevels)){
                                    $showCourseLevels = array();
                                    $showCourseLevels = appendDegreeToLevel($courseLevels);
                                    sort($showCourseLevels);
                                    $showCourseLevels = implode(', ', $showCourseLevels);
                                }else if(!empty($courseLevel)){
                                    $showCourseLevels = appendDegreeToLevel($courseLevel);
                                    $showCourseLevels = implode(', ', $showCourseLevels);
                                }else{
                                    $showCourseLevels = appendDegreeToLevel($scholarshipObj->getHierarchy()->getCourseLevel());
                                    $showCourseLevels = implode(', ', $showCourseLevels);
                                }  ?>

				<p><?php echo 'This scholarship is applicable for '.$showCourseLevels; ?></p>

             	<?php if($scholarshipObj->getCategory() == 'external'){ ?>
             		<p>This scholarship is applicable for all universities in <span id="allCountryList"><?php echo implode(', ', array_slice($applicableCountries, 0, 5)); ?></span><?php if(count($applicableCountries) > 5){ ?><a href="javascript:void(0);" class="vw-country-list"> +<?php echo (count($applicableCountries)-5); ?> more </a><?php } ?></p>
             	<?php }else{ ?>
             		<p>This scholarship is applicable for the following courses in <a href="<?php echo $universityUrl; ?>"><?php echo ucfirst($universityName); ?></a> in <?php echo implode(', ', $applicableCountries); ?></p>
             	<?php } ?>
				 <?php if($scholarshipObj->getCategory() == 'internal'){ 
				 		$courseArr = array();
             			$num = 0;
             			foreach ($internalCourses as $courseId => $courseObj) { 
	             		
	             			$courseArr[$num]['url'] = $courseObj->getURL();
	             			$courseArr[$num]['name'] = $courseObj->getName();
	             			$num++;
             			}
				 	?>
						<ul class="cr-lnk sch-lst">
							<?php for ($i=0; $i < count($courseArr); $i+=2) { ?>
								<li>
                                                                        <div class="flLt">
										<a class="sch-anchr" href="<?php echo $courseArr[$i]['url']; ?>"> <?php echo $courseArr[$i]['name']; ?> </a>
									</div>
									<?php if(!empty($courseArr[$i+1])){ ?>
									<div class="flRt">
										<a class="sch-anchr" href="<?php echo $courseArr[$i+1]['url']; ?>"> <?php echo $courseArr[$i+1]['name']; ?> </a>
									</div>
									<?php } ?>
								</li>
							<?php } ?>
						</ul>
						<?php } if($scholarshipObj->getHierarchy()->getCourseCategoryComments() != ''){  ?>
						<p><?php echo $scholarshipObj->getHierarchy()->getCourseCategoryComments(); ?></p>
						<?php } ?>
	         </div>