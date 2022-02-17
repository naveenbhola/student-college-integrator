<div id="content-wrap">
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>  
		<div id="contents">
		<ul>
		<?php 
			$courses_list;
			$count = 0;
			$listings = array();
			
			foreach($institutes as $institute) {
				$course = $institute->getFlagshipCourse();
				$course->setCurrentLocations($request);
				$displayLocation = $course->getCurrentMainLocation();
				$courses = $institute->getCourses();
				$count++;
				$locations = $institute->getLocations();
		?>
	        <li>
            	<div class="figure2">
		<?php 	if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){ ?>	
			<!--<?php echo '<img src="'.$institute->getMainHeaderImage()->getThumbURL().'" width="72" alt="" />';?> -->
			
                        <img alt=""  src ="<?php echo base64_encode_image($institute->getMainHeaderImage()->getThumbUrl());?>" width="72"/>
			
				<?php }else{ ?>
				                    <img alt=""  src ="<?php echo base64_encode_image(SHIKSHA_HOME.'/public/images/avatar.gif');?>" width="72"/>
				 <?php } ?>
		
		</div>

        <div class="details2" style="cursor: pointer;" onclick="window.location='<?php echo $course->getURL(); ?>';">
                	<h2><a href="<?php echo $course->getURL(); ?>"><?php echo html_escape($institute->getName()); ?>,</a>
                 <span><?php if($displayLocation){ echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName();}else{
						 echo $institute->getMainLocation()->getLocality()->getName()?$institute->getMainLocation()->getLocality()->getName().", ":"";?><?=$institute->getMainLocation()->getCity()->getName();}?></span> </h2>
		<?php if($institute->getAIMARating()) { ?>
							<div class="aimaRating">
								<span>AIMA Rating:</span>
								<span class="rating"><?=$institute->getAIMARating()?></span>
							</div>
		<?php } ?>
		             
		<?php if($institute->getAlumniRating()) { ?>
							<div class="alumini-rating">
								<label>Alumni Rating:</label>
								<span>
									<?php
									$i = 1;
									while($i <= $institute->getAlumniRating()){
									?>
										<img src="/public/images/nlt_str_full.gif"  alt=""/>
									<?php
										$i++;	
									}
									?>
								</span>
								<span class="rateNum">&nbsp;<?= $institute->getAlumniRating();?>/5</span>
							</div>
							<?php } ?>

		   	                       
                    
                    <strong><a href="<?php echo $course->getURL(); ?>"><?php echo html_escape($course->getName()); ?></a></strong>
                    <p>
                  	<span>
			 <?php echo $course->getDuration()->getDisplayValue()?"- ".$course->getDuration()->getDisplayValue():""; ?>
			 <?php echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():""); ?>
			<?php echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():""); ?>
			</span><br>
		<?php	if($course->getFees()->getValue()){ ?>
							<label>Fees: </label> <span><?=$course->getFees()?></span> 
							<?php }else{
							?>
							<label>Fees: </label> <span>Not Available</span><?php } ?>   
		 </p>
		 <p>
						<?php	$affiliations = $course->getAffiliations();
							
							       	foreach($affiliations as $affiliation) {
									$Affiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);
								}echo $Affiliations[0];
								unset($Affiliations);	
													
								?>	
		</p><?php
			$addReqInfoVars = array();
			foreach($courses as $c){
				if($c->isPaid()=="TRUE"){
					foreach($c->getLocations() as $course_location){
							$locality_name = $course_location->getLocality()->getName();
							if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
							$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($institute->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
					}		
				//	$addReqInfoVars[$c->getName()]=$c->getId()."*".html_escape($institute->getName())."*".$c->getUrl();
				}
			}
			$addReqInfoVars=serialize($addReqInfoVars);
			$addReqInfoVars=base64_encode($addReqInfoVars);
			?>
		
		    <?php if($course->isPaid()=="TRUE"){?>
		    <form action="/muser/MobileUser/renderRequestEbrouchre" method="post">
                    <input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
		    <input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
		    <input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
		    <input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
                    <input class="brochure-btn orange-button" type="submit" value="Request E-brochure" />
                    </form>
			<?php } ?>
                </div>
            </li>
	
<?php } ?>
</ul>
	
<?php
	$totalResults = $categoryPage->getTotalNumberOfInstitutes();
	$currentPage = $request->getPageNumberForPagination();
	$totalPages = ceil($totalResults/$mobile_website_pagination_count);
	$lastPage = $totalResults%$mobile_website_pagination_count; //results in last page.
	$urlRequest = clone $request;
	
?>

	<div id="see-more">
		<?php
			if($currentPage < $totalPages-1){
				echo '<li><a href="'.$urlRequest->getURL($currentPage+1).'">See next 10</a></li>';
			}
			//if penultimate page
			if($totalPages-$currentPage ==1){
				if($lastPage!=0)
					echo '<li><a href="'.$urlRequest->getURL($currentPage+1).'">See next '.$lastPage.'</a></li>';
			}
		?>	
        </div>
 </div> 
</div>
