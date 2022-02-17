<article>
	    
     <p><?=html_escape($course->getName()).'<br/>'?>				
				<?php
					echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; 
					echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():"");
					echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():"");
				?>
    </p>

   <em>			
	<?php	$approvalsAndAffiliations = array();
		$approvals = $course->getApprovals();
		foreach($approvals as $approval) {
			$approvalsAndAffiliations[] = langStr('approval_'.$approval);
		}
		$affiliations = $course->getAffiliations();
		foreach($affiliations as $affiliation) {
			$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
		}
		echo implode(', ',$approvalsAndAffiliations);
	?>
  </em>

                    <ul class="bullet-item">
				<?php if($course->getAccredited()){ ?>
		                <li>
				               Accrediation: <span><?=html_escape($course->getAccredited()); ?></span>
		                </li>

				<?php } if($course->getFees($currentLocation->getLocationId())->getValue($currentLocation->getLocationId())){ ?>
                                <li>Fees:
				      <span><?php
							$fees = $course->getFees($currentLocation->getLocationId());
							echo $fees;
							?></span>
									<div class="fees-disclaimer-text"><?php echo $fees->getFeeDisclaimer() == 1 ? FEES_DISCLAIMER_TEXT : ''; ?></div>
                                </li>
                                 <?php  } if($course->getTotalSeats() || $course->getManagementSeats() || $course->getGeneralSeats() || $course->getReservedSeats()){
					?>
                    
				<li>	Seats:
				    <span>
								 <?php
									$seatsArray = array();
									if($course->getTotalSeats()){
										$seatsArray[] = "Total - ".$course->getTotalSeats();
									}
									if($course->getGeneralSeats()){
										$seatsArray[] = "General - ".$course->getGeneralSeats();
									}
									if($course->getManagementSeats()){
										$seatsArray[] = "Management - ".$course->getManagementSeats();
									}
									if($course->getReservedSeats()){
										$seatsArray[] = "Reserved - ".$course->getReservedSeats();
									}
									echo implode('<i> | </i> ',$seatsArray);
								?>
				
				    </span>
				</li>
				  <?php }
				$exams = $course->getEligibilityExams();
						if(count($exams) > 0 || $course->getOtherEligibilityCriteria()){ 
							if($institute->getInstituteType() == "Test_Preparatory_Institute"){ ?>
				<li>Exams Prepared for: <span style="line-height: 22px;"><?php }else{ ?><li>Eligibility: <span style="line-height: 22px;"> <?php }
				                        $examAcronyms = array();
							$exampage = new ExamPageRequest;
							foreach($exams as $exam) {
								$tempExam = $exam->getAcronym();
								$exampage->setExamName($tempExam);
								$url = $exampage->getUrl();
								if(!in_array($exampage->getExamName(), $liveExamPages)){
								 $url = "";
							        }
								$exampage->reset();
								if($exam->getMarks()){
									$tempExam .= " - ".$exam->getMarks()." ".titleCase(str_replace("_"," ",$exam->getMarksType()));
								}
								if($exam->getPracticeTestsOffered()) {
									$tempExam = $exam->getAcronym()."(".$exam->getPracticeTestsOffered().")";
								}
								if(!empty($url['url'])){
                                    $tempExam = "<a href='".$url['url']."'>".$exam->getAcronym()."</a>";
                                }
                                if($abroadExamData!=false && is_array($abroadExamData) && array_key_exists($exam->getAcronym(), $abroadExamData)){
									$tempExam = "<a target='blank' href='".$abroadExamData[$exam->getAcronym()]['contentURL']."'>".$exam->getAcronym()."</a>";
								}
								$examAcronyms[] = $tempExam;
							}
							if($course->getOtherEligibilityCriteria()){
								$examAcronyms[] = html_escape($course->getOtherEligibilityCriteria());
							}
							echo implode(' <i>|</i> ',$examAcronyms);
				                 ?>
				</li>	
				
				
				<?php
				if(count($salientFeatures = $course->getSalientFeatures()) || count($classTimings = $course->getClassTimings())){
						?>		
				<li>Salient Featues:
				         <span>
				               <?php
							$salientArr = array();
							foreach($salientFeatures as $sf){
								$salientArr[] = langStr('feature_'.$sf->getName().'_'.$sf->getValue());
							}
							foreach($classTimings as $sf){
								$salientArr[] = langStr($sf);
							}
							echo implode(' <i>|</i> ',$salientArr);
						?>
						
						<?php
						}
					?>
				        </span>
		                </li>
				<?php } ?>
				
				
				 <?php 
                                         $form_sub_date = $course->getDateOfFormSubmission($currentLocation->getLocationId());
                                         $result_decl_date = $course->getDateOfResultDeclaration($currentLocation->getLocationId());
                                         $course_com_date = $course->getDateOfCourseComencement($currentLocation->getLocationId());
                                         if((!empty($form_sub_date) && $form_sub_date!='0000-00-00 00:00:00' && $form_sub_date!='undefined') || (!empty($result_decl_date) && $result_decl_date!='0000-00-00 00:00:00' && $result_decl_date!='undefined') || (!empty($course_com_date) && $course_com_date!='0000-00-00 00:00:00' && $course_com_date!='undefined')):?>
                                <li>
					   Important Dates:<span>
								<?php
									     $dates_array = array();
									     if(!empty($form_sub_date) && $form_sub_date!='0000-00-00 00:00:00' && $form_sub_date!='undefined') {
										$dates_array[] = "Form Submission: ".date("d-m-y",strtotime($form_sub_date));
									     } 
									     if(!empty($result_decl_date) && $result_decl_date!='0000-00-00 00:00:00' && $result_decl_date!='undefined') {
										$dates_array[] = "Declaration of Results: ".date("d-m-y",strtotime($result_decl_date)); 
									     }
									     if(!empty($course_com_date) && $course_com_date!='0000-00-00 00:00:00' && $course_com_date!='undefined') {
										$dates_array[] = "Course Commencement: ".date("d-m-y",strtotime($course_com_date));	
									     }
									     echo implode(' <i>|</i> ',$dates_array);
								
								?> 
								</span>
				</li>
                                        <?php endif;?>
                </ul>
</article>

				
                                       
					
			
