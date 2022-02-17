<?php 	$salientFeatures = $course->getSalientFeatures();
		$classTimings = $course->getClassTimings();
		if(count($salientFeatures) || count($classTimings))
				$flag_salient = true;
		else
				$flag_salient = false;
		//return if the widget is not supposed to come
		$approvals = $course->getApprovals();
		$affiliations = $course->getAffiliations();
		if($course->getFees($currentLocation->getLocationId()) == "" &&
		   (count($approvals)<=0 && count($affiliations)<=0) &&
		   (empty($wikisData['Eligibility'])) && 
		   $flag_salient != true &&
		   $course->getDuration($currentLocation->getLocationId()) == "" &&
		   !$course->hasRecruitingCompanies()
		)
		{ return false;
				//echo "fail";
		}
		$allowedTags = '<td><dt><dd><ul><li><br><table><colgroup><tbody><tr><td><img><ol><strong><em><span><a><textarea><iframe><cite><code><blockquote><h><b><i><small><sup><sub><ins><del><mark><kbd><samp><var><pre><abbr><address><bdo><blockquote><q><dfn>';
?>
<div class="animation-list insitute-criteria-box other-details-wrap clear-width">
		<h2 style="margin-bottom: 10px;">Important Information</h2>
		<ul>
		<?php
        if($isCutoffRankPresent) {
		$this->load->view('listing/national/widgets/beBetchExamCuttOffWidget'); 
		} else if($courseExamDetails){
          $this->load->view('listing/national/widgets/beBetchExamWidgetDefault');
		}
		?>
			
				<?php
				$rupeeIconFlag = false;
				if($course->getFees($currentLocation->getLocationId())->getCurrency() == "INR") {
						$rupeeIconFlag = true;
				}
				if($course->getFees($currentLocation->getLocationId()) != "")
				{ ?>
						<li>
								<div class="details">
										<p class="criteria-icn-box"><i class=" <?=($flag_salient)?'sprite-bg rupee-icon':'sprite-bg rupee-icon'?>"></i></p>
										<p class="title-txt2">Total Fees</p>
										<p class="label-sep">-</p>
										<div class="criteria-content"><?php
												$fees = $course->getFees($currentLocation->getLocationId());
												echo $fees;

												echo "  ";?><span class="fees-disclaimer-text"><?php echo $fees->getFeeDisclaimer() == 1 ? FEES_DISCLAIMER_TEXT : ''; ?></span>
										</div>
								</div>
						</li>
				<?php } ?>
				        <!-- COLLEGE REVIEWS STARTS-->
        <?php
        if($dominantSubcatData['dominant'] == '56'){

	        $courseReviewsCount = $courseReviews[$course->getId()]['overallRecommendations'];
	        $totalCourseReviewsCount = count($courseReviews[$course->getId()]['reviews']);
	            if($totalCourseReviewsCount > 0) { ?>
			
			<li>
			    <div class="details">
				<p class="criteria-icn-box"><i class="sprite-bg college-review-icon"></i></p>
				<p class="title-txt2" style="line-height:19px;">College Reviews</p>
				<p class="label-sep">-</p>
			       
			       <div class="criteria-content ">
				Average Alumni Rating: 
	                        <div class="ranking-bg" style="float:none; display:inline-block; line-height:15px;">
	                            <?php echo $courseReviews[$course->getId()]['overallAverageRating'];?><sub>/<?php echo $courseReviews[$course->getId()]['reviews'][0]['ratingParamCount'];?></sub>
	                        </div>
	                        <?php if($courseReviewsCount >= 5) { ?>
	                        <div class="recommended-title">
	                            <i class="sprite-bg thumb-up-icon" style="top:-2px!important;"></i>
	                            <?php echo $courseReviews[$course->getId()]['overallRecommendations'];?> Students Recommend This Course
	                        </div>
	                        <?php } ?>
				</div>
			    </div>
			</li>
	        <!-- COLLEGE REVIEWS END-->
		<?php 
			}
	 }?>
				<?php

				if(count($approvals)){
					foreach($approvals as $approval) {
						$approvalsArr[] = langStr('approval_'.$approval);
					}
					$approvalsStr = implode(', ',$approvalsArr);
					?>
					<li>
						<div class="details">
								<p class="criteria-icn-box">
										<i class="sprite-bg recognition-icon"></i>
								</p>
								<p class="title-txt2" >
										Recognition
								</p>
								<p class="label-sep">-</p>
								<div class="criteria-content"><?php echo $approvalsStr;?></div>
						</div>
					</li>
					<?php
				}
				if(count($affiliations)){
					foreach($affiliations as $affiliation) {
						$affiliationsArr[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
					}
					$affiliationsStr = implode(', ',$affiliationsArr);
					$affiliationsStr = str_replace("Autonomous Program","Autonomous Institute",$affiliationsStr);
					?>
					<li>
						<div class="details">
								<p class="criteria-icn-box">
										<i class="sprite-bg affiliation-icon"></i>
								</p>
								<p class="title-txt2" >
										Course Status
								</p>
								<p class="label-sep">-</p>
								<div class="criteria-content"><?php echo $affiliationsStr;?></div>
						</div>
					</li>
					<?php
				}
				?>
				
				<?php if(!empty($wikisData['Eligibility']))
				{
						$summary = new tidy();
						$summary->parseString($wikisData['Eligibility'],array('show-body-only'=>true),'utf8');
						$summary->cleanRepair();
						?>
						<li>
								<div class="details">
										<p class="criteria-icn-box"><i class="sprite-bg done-icon"></i></p>
										<p class="title-txt2" >Eligibility</p>
										<p class="label-sep">-</p>
										<div class="criteria-content tiny-content">
												<div id="scrollbar_defaultPage" class="scrollbar2 scrollbar1">
														<div class="scrollbar">
																<div class="track">
																		<div class="thumb">
																				<div class="end"></div>
																		</div>
																</div>
														</div>
														<div class="viewport_h viewport" style="height: 90px; width:490px;">
																<div class="overview_h overview">
																		<?=$summary?>
																</div>
														</div>
														<div class="scrollbar_h">
																<div class="track_h">
																	<div class="thumb_h">
																		<div class="end_h"></div>
																	</div>
																</div>
														</div>
												</div>
										</div>
								</div>
						</li>
				<?php }?>
				<?php  
				if ( !empty($wikisData['Admission Procedure'])) //admission procedure available 
					{
						$isAdmissionProcedureOnTop = TRUE;
						$summary = new tidy();
						$summary->parseString (strip_tags($wikisData['Admission Procedure'],$allowedTags), array ('show-body-only' => true ), 'utf8' );
						$summary->cleanRepair();?>
					<!-- admission procedure -->
					<li>
					    <div class="details">
						<p class="criteria-icn-box"><i class="sprite-bg procedure-icon"></i></p>
						<p class="title-txt2">Admission Procedure</p>
						<p class="label-sep">-</p>
					       
					       <div class="criteria-content tiny-content">
						    <div class="bubble-box-details scrollbar2 scrollbar1" id="scrollbar_admissionProcedure">
							    <div class="scrollbar">
								    <div class="track">
									    <div class="thumb">
										    <div class="end"></div>
									    </div>
								    </div>
							    </div>			
							    <div class="viewport viewport_h" style="height: 120px; width: 490px;">
								    <div class="overview_h overview">
									    <?=$summary?>
								    </div>
							    </div>
							    <div class="scrollbar_h">
								    <div class="track_h">
									    <div class="thumb_h">
										    <div class="end_h"></div>
									    </div>
								    </div>
							    </div>	
						    </div>
						</div>
					    </div>
					</li>	
					<!-- admission procedure END -->	
				<?php } ?>
	
				
				<?php if($course->getDuration($currentLocation->getLocationId()) != "")
				{ ?>
						<li>
								<div class="details">
										<p class="criteria-icn-box"><i class="sprite-bg duration-icon"></i></p>
										<p class="title-txt2" >Duration</p>
										<p class="label-sep">-</p>
										<div class="criteria-content">
										<?php echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():"";
												echo ($course->getDuration()->getDisplayValue() && $course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():"");
												echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():"");
												?>
										</div>
								</div>
						</li>
				<?php } ?>
				
				<?php if ($course->isSalaryTypeExist())// check for Placement and salary
				    {?>
				    <!-- salary statistics -->
				    <li>
					<div class="details">
					    <p class="criteria-icn-box"><i class="sprite-bg salary-icon"></i></p>
						<p class="title-txt2">Campus Placement <br/><span style="font-size:10px;font-weight:normal;font-style:italic;">(Provided by <?php echo $collegeOrInstituteRNR;?>)</span></p>
					    <p class="label-sep">-</p>
					    <div class="criteria-content">
					    <?php $this->load->view('listing/national/listingSalaryStatsWidget',array('salary'=>$course->getSalary())); ?>
					    </div>
					</div>
				    </li>
				    <!-- salary statistics END-->
				<?php }?>
			    
				<?php if($course->hasRecruitingCompanies())
				{
						$recruitingCompanies = $course->getRecruitingCompanies(); ?>
						<li class="noBorder">
								<div class="details">
										<p class="criteria-icn-box"><i class="sprite-bg placement-icon"></i></p>
										<p class="title-txt2" >Recruiting Companies </p>
										<p class="label-sep">-</p>
										<div class="criteria-content">
										<?php $this->load->view('listing/national/listingCourseRecruitingCompanies',array('recruitingCompanies'=>$recruitingCompanies)); ?>
										</div>
								</div>
						</li>
				<?php } ?>
		</ul>
</div>
