<?php
	if(!$institutes){
		if($request->getPageNumberForPagination() > 1){
			$urlRequest = clone $request;
			$urlRequest->setData(array('pageNumber'=>1));
			$url = $urlRequest->getURL();
			header("location:".$url);
		}
		
		global $dataFromDBExistsOnPage;		
?>		
		<div class="error_msg" id="error_msg_div"><?php if(isset($dataFromDBExistsOnPage) && $dataFromDBExistsOnPage == 0) { ?>
			<div id="error_msg_db">		
				<p class="flLt">Your search refinement resulted in zero results.
				<br/>Please change your Category or Location.</p>
			</div>		
			<?php } else { ?>
			<div id="error_msg_appliedfilters">
				<p class="flLt">Your search refinement resulted in zero results.
				<br/>Please clear all filters and try again.</p>
				<div class="flRt"><input type="button" value="Clear all filters" class="orange-button" onclick="clearAllFiltersOnCategoryPages();return false;" /></div>
			</div>			
			<?php } ?>
		</div>	
<?php
	}else{
?>
<div class="instituteLists">
	<ul>
<?php
		$count = 0;
		foreach($institutes as $institute) {
			$count++;
            $course = $institute->getFlagshipCourse();
			$courses = $institute->getCourses();
?>
                <input type="hidden" id="institute_city_info_<?php echo $institute->getId();?>" value="<?php echo $institute->getMainLocation()->getCity()->getId();?>" />
		<li>
			<div class="instituteListsTitle">
				<div class="checkCol">
					<?php
						if($institute->isSticky() || $institute->isMain()){
							echo '<span class="checkIcon"></span>';
						}
					?>	
				</div>
				<h3><a href=<?=(($course->getOrder()==1)?'"'.$institute->getURL().'"':'"'.$course->getURL().'" rel="nofollow"')?> title="<?php echo html_escape($institute->getName()); ?>"><?php echo html_escape($institute->getName()); ?>, </a> <span><?php echo $institute->getMainLocation()->getCity()->getName()?$institute->getMainLocation()->getCity()->getName().", ":"";?><?=$institute->getMainLocation()->getCountry()->getName()?></span></h3>
				
			</div>
			
			<div class="instituteListsDetails">
				<div class="collegeDetailCol">
				  		<div class="collegePic">
							<?php
								if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){
									echo '<a href="'.(($course->getOrder()==1)?$institute->getURL():$course->getURL()).'" rel="nofollow"><img src="'.$institute->getMainHeaderImage()->getThumbURL().'" width="118" alt="'.html_escape($institute->getName()).'" title="'.html_escape($institute->getName()).'"/></a>';
								}else{
									echo '<a href="'.(($course->getOrder()==1)?$institute->getURL():$course->getURL()).'" rel="nofollow"><img src="/public/images/avatar.gif" alt="'.html_escape($institute->getName()).'" title="'.html_escape($institute->getName()).'"/></a>';
								}
							?>
							<?php
								if($validateuser != 'false') {
									if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
										if(is_object($course)){
											if($course->isPaid()){
												echo '<div style="margin-top: 4px;"><label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label></div>';
											}else{
												echo '<div style="margin-top: 4px;"><label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label></div>';	
											}
										}
									}
								}
						        ?>
						</div>
				   <div class="collegeDescription" style="min-height: 120px;">
                                               <div id="collegeDescription<?php echo $count?>">
						<div class="collegePics" uniqueattr="StudyAbroadPage/photoVideo">
							<?php
							if($institute->getPhotoCount()){
							?>
								<span class="pohoto-icon"></span>
								<a uniqueattr="Studyabroad-photo-link" href="#" onclick="showPhotoVideoOverlay('Photo',<?=$institute->getId()?>,'<?=base64_encode(html_escape($institute->getName()))?>'); return false;"><?=$institute->getPhotoCount()?> Photos</a> 
							<?php
							}
							if($institute->getPhotoCount() && $institute->getVideoCount()){
							?>	
								&nbsp;&nbsp;|&nbsp;&nbsp;
							<?php
							}
							?>
							<?php
							if($institute->getVideoCount()){
							?>
								<span class="video-icon"></span>
								<a uniqueattr="Studyabroad-video-link" href="#" onclick = "showPhotoVideoOverlay('Video',<?=$institute->getId()?>,'<?=base64_encode(html_escape($institute->getName()))?>'); return false;"><?=$institute->getVideoCount()?> Videos</a>
							<?php
							}
							?>
						</div>
						<?php if($institute->getAlumniRating()) { ?>
							<div class="alumniRating">
								<span>Alumni Rating:</span>
								<span>
									<?php
									$i = 1;
									while($i <= $institute->getAlumniRating()){
									?>
										<img border="0" src="/public/images/nlt_str_full.gif">
									<?php
										$i++;
									}
									?>
								</span>
								<span class="rateNum">&nbsp;<?=$institute->getAlumniRating()?>/5</span>
							</div>
							<?php } ?>
						<div class="clearFix spacer5"></div>
						<h5><a href="<?php echo $course->getURL(); ?>"><?php echo $course->getName(); ?></a>
							<span>
								<?php
									if($course->getDuration()->getDisplayValue() || $course->getCourseType() || $course->getCourseLevel()){
								?>
								 - 
								<?		
									}
								?>
								<?php echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; ?>
								<?php echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():""); ?>
								<?php echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():""); ?>
							</span></h5>
						<div class="feeStructure">
							<?php
							$exams = $course->getEligibilityExams();
							if(count($exams) > 0){ ?>
								<label>Eligibility: </label> <span>
								<?php
								$examAcronyms = array();
								foreach($exams as $exam) {
									$examAcronyms[] = $exam->getAcronym();
								}
								echo implode('<b>|</b>',$examAcronyms);
								?>
								</span>
							<?php } ?>
							<div class="clearFix spacer5"></div>
							<?php
								if($course->getFees()->getValue()){ ?>
									<label>Fees: </label> <span><?=$course->getFees()?></span> 
							<?php
								}else{
							?>
									<label>Fees: </label> <span>Not Available</span>
							<?php
								}
							?>
									<b>|</b>
							<?php
								if($course->getDateOfCommencement()){ ?>
									<label>Starts in: </label> <span><?=date('F, Y',strtotime($course->getDateOfCommencement()))?></span>
							<?php }else{ ?>
									<label>Starts in: </label> <span>Not Available</span>
							<?php
							}
								if($course->getRanking()->getRankingValue()){ ?>
									<b>|</b>
							<?php } ?>
							<?php
								if($course->getRanking()->getRankingValue()){ ?>
									<label onmouseover="catPageToolTip('saRanking','<?=$course->getRanking()->getSource()?>',this,0,-10);" onmouseout="hidetip();">Course Ranking: <span><?=$course->getRanking()->getRankingValue()?></span></label>
							<?php } ?>
							
						</div>
						<div class="spacer10 clearFix"></div>
						<?php if($course->easesImmigration()){
						?>
						<div onmouseover="catPageToolTip('immigration',['<?=$institute->getMainLocation()->getCountry()->getName()?>','<?=langStr("immigration_".$institute->getMainLocation()->getCountry()->getId())?>'],this);" onmouseout="hidetip();" class="immigration"><span class="immigration-icon"></span>Eases Immigration</div>
                                                <div class="spacer10 clearFix"></div> 
						<?
						}
						?>


</div>
 				<!--University Rank Column Starts-->
				<?php
				if($institute->getRanking()->getRankingValue()){ 
				?>
                                <script>
                                 if($("collegeDescription<?php echo $count?>") && typeof($j) !='undefined') {
                                 	$j("#collegeDescription<?php echo $count?>").css('width',"375px");
                                 	$j("#collegeDescription<?php echo $count?>").css('float',"left");        
                                 }
                                 </script> 
				<div class="rankingCol" onmouseover="catPageToolTip('saRanking','<?=$institute->getRanking()->getSource()?>',this,0,55);" onmouseout="hidetip();">
					<div class="rankingBox">
						<span>University <br />Ranking</span>
						<strong><?=$institute->getRanking()->getRankingValue()?></strong>
					</div>
				</div>
				<?php
				}
				?>
				<!--University Rank Column Ends-->
<?php
			if($course->isPaid() || $brochureURL[$course->getId()] != ''){
				 $insti_city = $institute->getMainLocation()->getCity()->getName()? $institute->getMainLocation()->getCity()->getName() :"";
					
		?>
                <div class="compareInstBox">
				<div  uniqueattr="StudyAbroadPage/reqEBrocher">
                	<!--<p> <input type="checkbox" /> <a href="#">Compare</a></p> -->
			<?php
			//keep brochure url as a hidden parameter, to later check for its type(PDF/IMAGE) in js at the time of start download
			echo '<input type = "hidden" id = "course'.$course->getId().'BrochureURL" value="'.$brochureURL[$course->getId()].'">';
			?>
                	<p  uniqueattr="CategoryPage/addCompare"><input onclick="updateCompareText('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');updateCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');" type="checkbox" name="compare" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>" value="<?php echo $institute->getId().'::'.' '.'::'.($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($institute->getName())).', '. $insti_city.', '.$institute->getMainLocation()->getCountry()->getName().'::'.$course->getId();?>"/> <a  href="#" onclick="toggleCompareCheckbox('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');updateCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');return false;" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>lable">Compare</a></p>                    
					<div id="applyCategoryPageConfirmation<?php echo $institute->getId(); ?>" class="recom-aply-row" style="margin-bottom: 8px;" >
						<i class="thnx-icon" style="margin: 0; float: left"></i>
						<p style="margin:0 0 0 23px;float: none; color: inherit">E-brochure successfully mailed.</p>
					</div>
					<input id="categoryPageApplyButton<?php echo $institute->getId(); ?>" class="orangeButtonStyle" type="button" value="Download E-brochure" title="Download E-brochure" onclick="if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;}return multipleCourseApplyForCategoryPage(<?php echo $institute->getId()?>,'StudyAbroadApplyRegisterButton',this);"/>
				</div>
                </div>
				<?
					}
				?>




				   </div>
                   
				</div>
		
				
			</div>
			
			<?php
			if($course->isPaid() || $brochureURL[$course->getId()] != ''){
			?>
			<!-- Hidden Div for Apply Now -->
			<div id="institute<?=$institute->getId()?>name" style="display:none"><?=html_escape($institute->getName())?>, <?=$institute->getMainLocation()->getCity()->getName();?></div>
			<select id="applynow<?php echo $institute->getId()?>" style="display:none">
                                        <option value="">Select course</option>
					<?php
						foreach($courses as $applyCourse){
					?>               
							<option title="<?php echo html_escape($applyCourse->getName()); ?>" value="<?php echo $applyCourse->getId(); ?>"><?php echo html_escape($applyCourse->getName()); ?></option>
					<?php
						}
					?>
			</select>
			
			 <div style="display:none">
                        <?php
                                foreach($courses as $applyCourse){
                        ?>
                                <input type="hidden" name="compare<?php echo $institute->getId();?>-<?=$course->getId()?>list[]"  value= "<?=$applyCourse->getId()?>" />
                        <?php
                                }
                        ?>
                </div>

			<?php
				}
			?>
			
			<div id="recommendation_inline<?php echo $institute->getId();?>" style="display:none; float: left; width: 100%; margin-top:20px;"></div>
		</li>
<?php
		}
?>
	</ul>
    <div class="clearFix"></div>
	</div>
<div class="clearFix"></div>
<div class="pagingsContBot">
	<?php $this->load->view('/categoryList/categoryPagePageNumbers'); ?>
	<?php $this->load->view('/categoryList/categoryPagePagination'); ?>
	<div class="clearFix"></div>
</div>
<?php	
	}
?>
<script>
compareDiv = 1;
</script>

