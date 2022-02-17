	<?php  if(!empty($featuredInstitutes)) { ?>
	
	<h1 class="flLt" style="font-weight:normal;margin-bottom:10px;"> Featured Institutes on Shiksha </h1> 
	<div class="ranking-details-cont">
	   <span class="top-flag flLt" style="margin-top: 5px;"> </span>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<th width="500">Name of Institute</th>
				<th width="150">
				<div style="position:relative;">City</div>
				</th>
				<th width="100">Fees</th>
				<th width="150">Cutoff</th>	
			</tr>
			<?php
			$count = 1;
			foreach($featuredInstitutes as $institute){
				$trClass = "";
				
				$course = $institute->getFlagshipCourse();
				$exams = $course->getEligibilityExams();
				$examString = "";
				$extraExamString = "";
				$count_exam =0;
				$totalExams = count($exams);
				foreach($exams as $exam){
					$count_exam++;
					$examAcronym = $exam->getAcronym();
					
					if(!empty($examAcronym)){
				      if($count_exam == 1 && $totalExams > 1){
				      	$examMarks = $exam->getMarks();
				      	if(!empty($examMarks)){
                      	$examString = $examString." ".$examAcronym;
						$examString = $examString." : ".$examMarks.",";
						}
						else {
						$examString = $examString." ".$examAcronym.",";
						}
				      	
				      }elseif(($totalExams == 1 && $count_exam == 1) || $count_exam == 2)
				        { 
				      	$examString = $examString." ".$examAcronym;
				      	$examMarks = $exam->getMarks();
				      	if(!empty($examMarks)){
				      		$examString = $examString." : ".$examMarks;
				      	}
				      }elseif($count_exam>2 && $count_exam != $totalExams)
				       {
				      	$examMarks = $exam->getMarks();
				      	if(!empty($examMarks)){
				      	$extraExamString = $extraExamString." ".$examAcronym;
						$extraExamString = $extraExamString." : ".$examMarks.",";
				      	}
				      	else {
                        $extraExamString = $extraExamString." ".$examAcronym.",";
				       }
				     
					  }elseif($count_exam>2 && $count_exam == $totalExams)
					  {
					  	$extraExamString= $extraExamString." ".$examAcronym;
					  	$examMarks = $exam->getMarks();
					  	if(!empty($examMarks)){
					  		$extraExamString = $extraExamString." : ".$examMarks;
					  	}
				   	}	
				
				} }
				if($count % 2 == 0){
					$trClass = "alt-rows";
				}
				if($count > 10)
				{
					$style = "style = 'display : none'";
				}	
				
				?>
				
				<tr class="<?php echo $trClass;?> inst-detls" <?php echo $style;?>>
					<td>
						<div class="inst-name-featured">
							<a target="_blank" href="<?php echo $institute->getURL();?>"><?php echo $institute->getName();?></a>
						<?php
						   if($validateuser != 'false') {
						      if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
							 if(is_object($course)){
							    if($course->isPaid()){
							       echo '<br/><label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label>';
							    }else{
							       echo '<br/><label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label>';	
							    }
							 }
						      }
						   }
						?>
						</div>
						<div class="course-name-featured">
						<div class="course-name-link flLt" style="width:300px"><a target="_blank" href="<?php echo $course->getURL();?>" ><?php echo $course->getName();?></a></div>
						<?php
						if(true){ ?>
						<div class="flRt">
						<a style="position: relative; top: 25px;" href="javascript:void(0)" onclick="changeDropDownSelectedIndex('applynow<?php echo $institute->getId();?>', <?php echo $institute->getFlagshipCourse()->getId();?>); return multipleCourseApplyForCategoryPage(<?php echo $institute->getId();?>,'RANKING_PAGE',this, <?php echo $institute->getFlagshipCourse()->getId();?>);" value="Request E-brochure" title="Request E-brochure" onmouseover="makeButtonAcitve(this);" onmouseout="makeButtonInAcitve(this);" class="request-button">Request E-brochure</a>
						</div>
						<div class="clearFix"></div>
						<?php
						}
						?>
						</div>
					
		     <?php if($course->isPaid() || $brochureURL->getCourseBrochure($course->getId())){?>	
						
						<div class=flLt>			   
			<!--------------------compare tool--------------------------->
							
			<p class="flLt" style="margin:3px 0 0 0px;">
			    <input onclick="updateCompareText('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');
			    updateAddCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_RANKING_PAGE');"
			    type="checkbox" name="compare" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>" class="compare<?php echo $institute->getId();?>-<?=$course->getId()?>" value="<?php echo $institute->getId().'::'.' '.'::'.($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'')
.'::'.htmlspecialchars(html_escape($institute->getName())).', '.$course->getMainLocation()->getCity()->getName().'::'.$course->getId().'::'.$course->getURL();?>" style="margin-left:0;"/>
			    <a href="javascript:void(0);" onclick="checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_RANKING_PAGE');toggleCompareCheckbox('compare<?php echo $institute->getId();
			    ?>-<?=$course->getId()?>');updateAddCompareList('compare<?php echo $institute->getId();
			    ?>-<?=$course->getId()?>');return false;" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>lable" class="compare<?php echo $institute->getId();?>-<?=$course->getId()?>lable">Add to Compare</a>
			</p>
			<div style="display:none">
			<input type="hidden" name="compare<?php echo $institute->getId();?>-<?=$course->getId()?>list[]"  value= "<?=$course->getId()?>" />	 
			</div>
			<!--------------------compare tool--end--------------------------->		
						</div>
			<?php }?>			
					</td>
					<td><?php echo  $course->getMainLocation()->getCity()->getName(); ?></td>
						<td>
						    
							<?php
							$fees = $course->getFees($course->getMainLocation()->getLocationId());
							$feesUnit = $fees->getCurrency();
							$feesValue = $fees->getValue();
							if(!empty($feesUnit) && !empty($feesValue)){
								echo $feesUnit;
							}
							?>
							<?php echo $fees->getValue();?>
						</td>
						<?php
					
					  if(true){
					?>
						<td class="cutoff-details">
							<?php echo !empty($examString) ?  $examString : "";?>
							<?php
							if(!empty($extraExamString)){
								?>
								<br/>
								<span style="display:none;" id="more_exams_<?php echo $course->getId();?>"><?php echo $extraExamString;?></span>
									<a href="javascript:void(0);" id="more_exams_handle_<?php echo $course->getId();?>" onclick="toggleMoreExams('<?php echo $course->getId();?>')">+ view more</a>
									<?php
							}
							?>
						</td>
					<?php
					}
					?>
				</tr>
				<?php
				$count++;
				}
				?>
		</table>
		<span class="bot-flag"> </span>
		<?php if(count($featuredInstitutes) > 10 ) {?>
		<div style="margin-top: 8px" class="tar"><a  id ="linkMoreInst" href="javascript:void(0);" onclick = "showMoreFeaturedInstitute();">Show More Featured Institutes<i class="view-more2"></i></a></div>
		<script type="text/javascript">

    	 function showMoreFeaturedInstitute()
   		  {
    	    $j('.inst-detls').show();
    	    $j('#linkMoreInst').hide();
  		   }
     	</script>
		 <?php }?>
		</div>
		<script type="text/javascript">     
    	
    	 function makeButtonAcitve(thisObj)
    	 {
    		 $j(thisObj).addClass('request-active'); 
       	 }
    	 function makeButtonInAcitve(thisObj)
    	 {
    		 $j(thisObj).removeClass('request-active');
    	 }
    	 </script>
		<?php }?>
		