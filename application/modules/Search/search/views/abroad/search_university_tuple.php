<li class="clearwidth">
	<div class="tuple-box">
		<div class="tuple-image flLt">
                        <a href="<?php $imgUrl = $university['university_photo']; if(empty($university['university_photo'])){$imgUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";} echo $university['university_seo_url'];?>" onmousedown = "searchTracking(<?=$tuplePostion?>,'university');"><img src="<?=$imgUrl;?>" alt="<?=$university['university_websitelink'];?>" align="center" width="172" height="115"/></a>
		</div>
		<div class="tuple-detail">
			<div class="tuple-title">
				<a class="univ-tuple-link" href="<?php echo $university['university_seo_url'];?>" onmousedown = "searchTracking(<?=$tuplePostion?>,'university');"><?php echo $university['university_name']?></a>
				<span class="font-11" style="display:block;"><?php echo $university['university_city'];?>, <?php echo $university['university_country']; ?> </span>
			 </div>
			 <div class="tuple-content">
				 <p>
                                     <?php echo ucfirst($university['university_type'])?> university
                                     <?php if(!empty($university['university_establishyear'])){?>
									, Estd <?php echo $university['university_establishyear'];}?>
                                 </p>
				 <?php if((array_key_exists('university_accreditation', $university) && (!empty($university['university_accreditation']))) || (array_key_exists('university_affiliation', $university) && (!empty($univeristy['university_affiliation'])))) { ?>
                                 <p>
                                     
                                     Accreditation/Affiliation: 
                                     <?php if(array_key_exists('university_accreditation', $university) && !empty($university['university_accreditation']) )
                                        {
                                             echo $university['university_accreditation'];
                                        }
                                        if(array_key_exists('university_affiliation', $university) && !empty($university['university_affiliation'])){
                                            ?>, <?php 
                                            echo $university['university_affiliation'];
                                        }
                                     ?>
                                  </p>
                                 <?php } ?>
                                  <?php if(array_key_exists('university_international_student_website', $university) && !empty($university['university_international_student_website']))
                                  { 
                                    ?>  
                                  
                                          <p>International Student Website: <a href= "<?=$university['university_international_student_website']?>" onmousedown = "searchTracking(<?=$tuplePostion?>,'university');" target="new" rel="nofollow"><?php echo $university['university_international_student_website']?><i class="common-sprite ex-link-icon"></i></a></p>
                                  <?php }?>
                                          <?php if(array_key_exists('university_course_count', $university) && !empty($university['university_course_count'])) { ?>
                                          <?php if($university['university_course_count'] > 1) {?>
                                          <a href="<?=$university['university_seo_url'] . "#coursesOfUniversitySec"?>" onmousedown = "searchTracking(<?=$tuplePostion?>,'university');" class="view-btn" style="line-height:17px;">View all <?=$university['university_course_count']?> Courses<span>&rsaquo;</span></a>
                                          <?php } else {?>
                                          <a href="<?=$university['university_seo_url'] . "#coursesOfUniversitySec"?>" onmousedown = "searchTracking(<?=$tuplePostion?>,'university');"  class="view-btn" style="line-height:17px;">View Course<span>&rsaquo;</span></a>
                                          <?php }}?> 
                                          
                         </div>
		</div>
	</div>
</li>