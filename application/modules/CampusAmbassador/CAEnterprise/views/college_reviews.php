<?php

        if($num_rows == 0 && $start==0){ ?>
            <div class="review-details clear-width" style="text-align:center;">
            <b>No Reviews Available</b>
            </div>
        <?php }
        ?>
            <?php foreach($result as $results) { 

                if(empty($results->isShikshaInstitute)){continue;}?>
	      <div class="review-details clear-width">
		<?php if($results->isShikshaInstitute == 'YES') { ?>
            	<div class="review-college-title"><span id="clg-name<?php echo $results->main_reviewid; ?>">
                	<?php
                        if($results->courseId != ''){
                            $course = $courseRepository->find($results->courseId); echo $course->getInstituteName();
                        }
                        if($results->instituteId != ''){
                            $institute = $instituteRepository->find($results->instituteId);
                        }
                    ?></span>
		        <i class="review-sprite arrow-icon" id="clg-url<?php echo $results->main_reviewid; ?>" onclick="window.open('<?php echo $institute->getURL(); ?>');return false;"></i>
                </div>
                <div class="review-status-tabs clear-width">	
                      <ul>
                    	<li style="margin-right:30px;"><a href="javascript:void(0);" onclick="submitFilterManually('Pending','<?php $course = $courseRepository->find($results->courseId); echo addslashes($course->getInstituteName());?>',<?php echo $results->instituteId; ?>,'Mapped-colleges',1)">Pending</a><span class="moderate-count">
			<?php if(isset($InstituteCounts[$results->instituteId]['totalPending']) && $InstituteCounts[$results->instituteId]['totalPending'] != '' ){ echo $InstituteCounts[$results->instituteId]['totalPending'];} else{ echo 0;}?></span></li>
                        <li style="margin-right:30px;"><a href="javascript:void(0);" onclick="submitFilterManually('Rejected','<?php $course = $courseRepository->find($results->courseId); echo addslashes($course->getInstituteName());?>',<?php echo $results->instituteId; ?>,'Mapped-Colleges')">Rejected</a><span class="ignore-count">
			<?php if(isset($InstituteCounts[$results->instituteId]['totalIgnored']) && $InstituteCounts[$results->instituteId]['totalIgnored'] != '' ){ echo $InstituteCounts[$results->instituteId]['totalIgnored'];} else{ echo 0;}?></span></li>
                        <li><a href="javascript:void(0);" onclick="submitFilterManually('Published','<?php $course = $courseRepository->find($results->courseId); echo addslashes($course->getInstituteName());?>',<?php echo $results->instituteId; ?>,'Mapped-Colleges',1)">Published </a> <span class="publish-count"><?php if(isset($InstituteCounts[$results->instituteId]['totalPublished']) && $InstituteCounts[$results->instituteId]['totalPublished'] != '' ){ echo $InstituteCounts[$results->instituteId]['totalPublished'];} else{ echo 0;}?></span></li>
                    </ul>
		</div>
		<?php } else if($results->isShikshaInstitute == 'NO' && $typeFilter != 'UnMapped-colleges' && !($results->instituteName)){?>
		<div class="review-college-title">
                	<?php echo $results->instituteId;?>
                    <br />
                   <a href='javascript:void(0)' name='<?=addslashes($results->instituteId) ?>' class='suggestor' style='font-size:10px;'>Show Suggestions <i class="review-sprite plus-icon" id="minusplus-icon"></i></a>
                    <div class='suggestions_options' style='display:none;font-size:12px;' ></div>
                </div>
		<?php } else { ?>
		<div class="review-college-title">
			<?php echo $results->instituteName;?>
            <br />
                    <a href='javascript:void(0)' name='<?=addslashes($results->instituteName) ?>' class='suggestor' style='font-size:10px;'>Show Suggestions <i class="review-sprite plus-icon" id="minusplus-icon"></i></a>
                    <div class='suggestions_options' style='display:none;font-size:12px;'></div>
                </div>
		<?php } ?>
    
		    
                    <table cellpadding="0" cellspacing="0" class="review-table">
                    	<tr>
                        	<th width="30%">Personal Details </th>
                            <th width="70%">Review</th>
                        </tr>
                        <tr>
                            <td>
                            	<div class="student-details">
                            		<p style="font-size:16px;"><?php echo $results->firstname;?> <?php echo $results->lastname; ?></p>
					<p><?php echo $results->email; ?></p>
                            	</div>

                                <div class="other-details">
                                	<p><span>Other Details:</span></p>
				    <?php if(isset($results->mobile) && $results->mobile != '') {?>
					<p><i class="review-sprite phone-icon"></i><?php echo '+'.$results->isdCode.'-'.$results->mobile; ?></p>
				    <?php } ?>
				    <?php if(isset($results->facebookURL) && $results->facebookURL != ''){
                        $socialProfile = $results->facebookURL;
                            if (
                                    (strpos($results->facebookURL, "http")      === false) ||
                                    (strpos($results->facebookURL, "https")     === false)
                                ) 
                            {
                                $socialProfile = 'https://'.$results->facebookURL;
                            }
                        ?>
					<p><?php echo $results->facebookURL; ?><i class="review-sprite arrow-icon" onclick="window.open('<?php echo $socialProfile; ?>');return false;" ></i></p>
				    <?php } ?>
                                </div>
                                <div class="other-details" id="anonymous<?php echo $results->main_reviewid; ?>">
                                	<p><span>Posting as anonymous :</span></p>
                                    <p id="aFlag<?php echo $results->main_reviewid; ?>"><?php echo $results->anonymousFlag; ?></p>
                                    <a href="javascript:void(0);" onclick="changeAnonymousFlag(<?php echo $results->main_reviewid; ?>);">Change</a>
                                </div>
                                <div id="anonymousFlagUpdate<?php echo $results->main_reviewid; ?>" style="display:none;">
                                    <select id="anonyFlag<?php echo $results->main_reviewid; ?>" name ="anonyFlag<?php echo $results->main_reviewid; ?>">
                                        <option value="YES" <?php if($results->anonymousFlag == 'YES') echo 'selected'; ?> >YES</option>
                                        <option value="NO" <?php if($results->anonymousFlag == 'NO') echo 'selected'; ?> >NO</option>
                                    </select>
                                    <a class="submit-btn" id="saveAnoFlag<?php echo $results->main_reviewid;?>" onclick="saveAnonymousFlag(<?php echo $results->main_reviewid;?>);" href="javascript:void(0);">Save</a>
                                </div>
                            </td>
                            <td>
                            <div class="other-details">
                                <?php if(is_numeric($results->qualityScore)){ ?>
                                    <p><span><b>Quality Score :</b></span></p>
                                    <?php
                                        $style = "";
                                        $text = "";
                                        if($results->qualityScore >= 0.8){
                                            $style = 'color:green';
                                            $text = '(Verify reviewer details &amp; Publish)';
                                        }else if($results->qualityScore > 0.2 && $results->qualityScore < 0.8){
                                            $style = 'color:red';
                                            $text = '(Verify, Edit &amp; Publish)';
                                        }
                                    ?>
                                    <span style="<?php echo $style;?>"><?php echo $results->qualityScore;?></span> <?php echo $text;?>
                                    
                                <?php } ?>
                            </div>
                            <div class="other-details">
                            	<p><span>Date Posted:</span></p>
                            	<?php if($results->modificationDate == '0000-00-00 00:00:00' && $results->creationDate != '0000-00-00 00:00:00'){ ?>
					    			<p><?php echo $results->creationDate;?></p>
					    		<?php }else{ ?>
					    			<p><?php echo $results->modificationDate;?></p>
					    		<?php } ?>
                            </div>
                             <div class="other-details">
                                	<p><span>Course:</span></p>
					<?php if($results->isShikshaInstitute == 'NO' && $typeFilter != 'UnMapped-colleges' && !($results->courseName)){?>
					    <p><?php echo $results->courseId;?></p>
					<?php } else if($results->isShikshaInstitute == 'NO' && $typeFilter =='UnMapped-colleges'){?>
					    <p><?php echo $results->courseName;?></p>
					<?php } else if($results->isShikshaInstitute == 'NO' && $results->courseName){ ?>
						<p><?php echo $results->courseName;?></p>
					<?php } else {
						
								// $course = $courseRepository->find($results->courseId); ?>
                                <div id="courseName<?php echo $results->main_reviewid; ?>">
								<p><span id="crsNm<?php echo $results->main_reviewid; ?>"><?php echo $course->getName();?> (<?=($results->paidStatus=='1')?'Paid':'Free';?>)</span><i id="courseUrl<?php echo $results->main_reviewid; ?>" class="review-sprite arrow-icon" onclick="window.open('<?php echo $course->getURL();?>'); return false;"></i></p>
                                <a href="javascript:void(0);" onclick="changeCourseId(<?php echo $results->main_reviewid; ?>);">Change</a>
                                </div>
                                <div id="newCourseId<?php echo $results->main_reviewid; ?>" style="display:none;">
                                    <input id="newCourse<?php echo $results->main_reviewid; ?>" name="newCourse<?php echo $results->main_reviewid; ?>" type="text" placeholder="Enter Course ID" value="" style="width:120px; padding:3px 2px 4px; margin-left:10px;margin-right:3px;"/>
                                    <a class="submit-btn" id="saveNewCourse<?php echo $results->main_reviewid;?>" onclick="saveNewCourse(<?php echo $results->main_reviewid;?>,<?php echo $results->courseId; ?>);" href="javascript:void(0);">Save</a>
                                </div>
					    
					    
					<?php } ?>
                             </div>
                             <div class="other-details">
                                    <p><span>Year of Graduation :</span></p>
                                    <p><?php echo $results->yearOfGraduation; ?></p>
                             </div>

			
			    		<?php if($results->placementDescription || 1){ ?> <!-- check skipped -->
                             <div id ="placementDescription_<?php echo $results->main_reviewid; ?>" class="other-details">
                                	<p><span>Placements:</span></p>
                                    <p id="placementDescriptionTextField_<?php echo $results->main_reviewid; ?>"><?php echo $results->placementDescription; ?></p>
                                    <a href="javascript:void(0);" onclick="editReviewDescription('<?php echo $results->main_reviewid; ?>','placement')"><i class="review-sprite edit-icon"></i>Edit</a>
                             </div>
                        <?php } ?>

                        <div id="placementeditReviewDiv_<?php echo $results->main_reviewid; ?>" style="display:none;" >
                            <form id="editReviewForm_<?php echo $results->main_reviewid; ?>" method="post" onsubmit="return false;" name="editReviewForm_<?php echo $results->main_reviewid; ?>">
                                <div  id="review_box_<?php echo $results->main_reviewid;?>">
                                    <div class="submit-ans-child clear-width">
                                        <textarea  required="false" minlength="0" maxlength="2500" caption="Placement Review" validate="validateReviewFields" rows="10" style="width:100%" class="ftxArea" id="placementreviewText<?php echo $results->main_reviewid;?>" name="placementreviewText<?php echo $results->main_reviewid;?>"><?php echo trim($results->placementDescription); ?></textarea>
                                        
                                        <div style="display:none;font-size:12px;color:red;" id="placementreviewText<?php echo $results->main_reviewid;?>_error" class="errorMsg">
                                        </div>
                                        <div class="info-text clear-width" style="font-size: 14px;margin-top: 10px;">
                                            <a class="submit-btn" id="submitButton<?php echo $results->id;?>" onclick="saveEditedReview(<?php echo $results->main_reviewid;?>,'placement');" href="javascript:void(0);">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $results->main_reviewid; ?>" onclick="hideReviewEditForm('<?php echo $results->main_reviewid; ?>','placement')">Cancel</a>
                                        </div>
                                        <input type="hidden" value="<?php echo $results->main_reviewid; ?>" name="reviewId<?php echo $results->main_reviewid; ?>">
                                        <input type="hidden" id="actionPerformed<?php echo $results->main_reviewid; ?>" value="editReview" name="actionPerformed<?php echo $results->main_reviewid; ?>">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php if($results->infraDescription || 1){ ?>   <!-- check skipped -->
                             <div id ="infraDescription_<?php echo $results->main_reviewid; ?>" class="other-details">
                                	<p><span>Infrastructure:</span></p>
                                    <p id="infraDescriptionTextField_<?php echo $results->main_reviewid; ?>"><?php echo $results->infraDescription; ?></p>
                                    <a href="javascript:void(0);" onclick="editReviewDescription('<?php echo $results->main_reviewid; ?>','infra')"><i class="review-sprite edit-icon"></i>Edit</a>
                             </div>
                        <?php } ?>

                        <div id="infraeditReviewDiv_<?php echo $results->main_reviewid; ?>" style="display:none;" >
                            <form id="editReviewForm_<?php echo $results->main_reviewid; ?>" method="post" onsubmit="return false;" name="editReviewForm_<?php echo $results->main_reviewid; ?>">
                                <div  id="review_box_<?php echo $results->main_reviewid;?>">
                                <div class="submit-ans-child clear-width">
                                <textarea  required="false" minlength="0" maxlength="2500" caption="Infrastructure Review" validate="validateReviewFields" rows="10" style="width:100%" class="ftxArea" id="infrareviewText<?php echo $results->main_reviewid;?>" name="infrareviewText<?php echo $results->main_reviewid;?>"><?php echo trim($results->infraDescription);?></textarea>
                                <div style="display:none;font-size:12px;color:red;" id="infrareviewText<?php echo $results->main_reviewid;?>_error" class="errorMsg"></div>
                                        <div class="info-text clear-width" style="font-size: 14px;margin-top: 10px;">
                                            <a class="submit-btn" id="submitButton<?php echo $results->id;?>" onclick="saveEditedReview(<?php echo $results->main_reviewid;?>,'infra');" href="javascript:void(0);">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $results->main_reviewid; ?>" onclick="hideReviewEditForm('<?php echo $results->main_reviewid; ?>','infra')">Cancel</a>
                                        </div>
                                        <input type="hidden" value="<?php echo $results->main_reviewid; ?>" name="reviewId<?php echo $results->main_reviewid; ?>">
                                        <input type="hidden" id="actionPerformed<?php echo $results->main_reviewid; ?>" value="editReview" 
    name="actionPerformed<?php echo $results->main_reviewid; ?>">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php if($results->facultyDescription || 1) { ?>    <!-- check skipped -->
                             <div id ="facultyDescription_<?php echo $results->main_reviewid; ?>" class="other-details">
                                	<p><span>Faculty:</span></p>
                                    <p id="facultyDescriptionTextField_<?php echo $results->main_reviewid; ?>"><?php echo $results->facultyDescription; ?></p>
                                    <a href="javascript:void(0);" onclick="editReviewDescription('<?php echo $results->main_reviewid; ?>','faculty')"><i class="review-sprite edit-icon"></i>Edit</a>
                             </div>
                        <?php } ?>

                        <div id="facultyeditReviewDiv_<?php echo $results->main_reviewid; ?>" style="display:none;" >
                    <form id="editReviewForm_<?php echo $results->main_reviewid; ?>" method="post" onsubmit="return false;" name="editReviewForm_<?php echo $results->main_reviewid; ?>">
                  <div  id="review_box_<?php echo $results->main_reviewid;?>">
                  <div class="submit-ans-child clear-width">
                <textarea  required="false" minlength="0" maxlength="2500" caption="Review" validate="validateReviewFields" rows="10" style="width:100%" class="ftxArea" id="facultyreviewText<?php echo $results->main_reviewid;?>" name="facultyreviewText<?php echo $results->main_reviewid;?>"><?php echo trim($results->facultyDescription);?></textarea>
                <div style="display:none;font-size:12px;color:red;" id="facultyreviewText<?php echo $results->main_reviewid;?>_error" class="errorMsg"></div>
                <div class="info-text clear-width" style="font-size: 14px;margin-top: 10px;">
                  
             <a class="submit-btn" id="submitButton<?php echo $results->id;?>" onclick="saveEditedReview(<?php echo $results->main_reviewid;?>,'faculty');" href="javascript:void(0);">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $results->main_reviewid; ?>" onclick="hideReviewEditForm('<?php echo $results->main_reviewid; ?>','faculty')">Cancel</a>
                        

                         </div>
                           <input type="hidden" value="<?php echo $results->main_reviewid; ?>" name="reviewId<?php echo $results->main_reviewid; ?>">
                           <input type="hidden" id="actionPerformed<?php echo $results->main_reviewid; ?>" value="editReview" name="actionPerformed<?php echo $results->main_reviewid; ?>">
                  </div>
                  </div>
                 </form>
    
                </div>  
                
                <?php if($results->reviewDescription || 1){     /*check skipped*/
                    $titleDescr = 'Other Details';
                    } ?>
                <div id ="otherDescription_<?php echo $results->main_reviewid; ?>" class="other-details">
                	<?php if($results->placementDescription){ $titleDescr = 'Other Details'; } ?>
                    <p><span><?php echo $titleDescr; ?></span></p>
                    <p id="otherDescriptionTextField_<?php echo $results->main_reviewid; ?>"><?php echo $results->reviewDescription; ?></p>
                    <a href="javascript:void(0);" onclick="editReviewDescription('<?php echo $results->main_reviewid; ?>','other')"><i class="review-sprite edit-icon"></i>Edit</a>
                </div>
                


                <div id="othereditReviewDiv_<?php echo $results->main_reviewid; ?>" style="display:none;" >
                    <form id="editReviewForm_<?php echo $results->main_reviewid; ?>" method="post" onsubmit="return false;" name="editReviewForm_<?php echo $results->main_reviewid; ?>">
                  <div  id="review_box_<?php echo $results->main_reviewid;?>">
                  <div class="submit-ans-child clear-width">
                  <?php if($results->placementDescription){ ?>
                    <textarea minlength="0" maxlength="2500" caption="Review" validate="validateReviewFields" rows="1" style="width:100%" class="ftxArea" id="otherreviewText<?php echo $results->main_reviewid;?>" name="otherreviewText<?php echo $results->main_reviewid;?>"><?php echo trim($results->reviewDescription);?></textarea>
                <?php }else{ ?>
                    <textarea required="true" minlength="0" maxlength="10000" caption="Review" validate="validateReviewFields" rows="10" style="width:100%" class="ftxArea" id="otherreviewText<?php echo $results->main_reviewid;?>" name="otherreviewText<?php echo $results->main_reviewid;?>"><?php echo trim($results->reviewDescription);?></textarea>
                    <?php } ?>
                <div style="display:none;font-size:12px;color:red;" id="otherreviewText<?php echo $results->main_reviewid;?>_error" class="errorMsg"></div>
                <div class="info-text clear-width" style="font-size: 14px;margin-top: 10px;">
                  
             <a class="submit-btn" id="submitButton<?php echo $results->id;?>" onclick="saveEditedReview(<?php echo $results->main_reviewid;?>,'other');" href="javascript:void(0);">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $results->main_reviewid; ?>" onclick="hideReviewEditForm('<?php echo $results->main_reviewid; ?>','other')">Cancel</a>
                        

                         </div>
                           <input type="hidden" value="<?php echo $results->main_reviewid; ?>" name="reviewId<?php echo $results->main_reviewid; ?>">
                           <input type="hidden" id="actionPerformed<?php echo $results->main_reviewid; ?>" value="editReview" name="actionPerformed<?php echo $results->main_reviewid; ?>">
                  </div>
                  </div>
                 </form>
    
                </div>  


                <?php if($results->reviewTitle){ ?>
                <div class="other-details" id="reviewTitleDiv_<?php echo $results->main_reviewid; ?>">
                    <p><span>Title of Review:</span></p>
                    <p id="reviewTitle_<?php echo $results->main_reviewid; ?>"><?php echo $results->reviewTitle; ?></p>
                    <a href="javascript:void(0);" onclick="editReviewTitle('<?php echo $results->main_reviewid; ?>')"><i class="review-sprite edit-icon"></i>Edit</a>
                    <!---<input id="titleReview" name="titleReview" value="<?php //echo $results->reviewTitle; ?> <!---"> -->
                    <input type="hidden" value="<?php echo $results->reviewTitle; ?>" name="revTtl" id="revTtl">
                </div>
                <?php } ?>

                <div id="editTitleReview_<?php echo $results->main_reviewid; ?>" style="display:none;" >
                    <form id="editTitle_<?php echo $results->main_reviewid; ?>" method="post" onsubmit="return false;" name="editTitle_<?php echo $results->main_reviewid; ?>">
                  <div  id="review_box_<?php echo $results->main_reviewid;?>">
                  <div class="submit-ans-child clear-width">
                <input  required="true" minlength="25" maxlength="100" caption="Title of Review" validate="validateReviewFields" style="width:100%" class="ftxArea" id="reviewTitleText<?php echo $results->main_reviewid;?>" name="reviewTitleText<?php echo $results->main_reviewid;?>" value="<?php echo $results->reviewTitle; ?>">
                <div style="display:none;font-size:12px;color:red;" id="reviewTitleText<?php echo $results->main_reviewid;?>_error" class="errorMsg"></div>
                <div class="info-text clear-width" style="font-size: 14px;margin-top: 10px;">
                  
             <a class="submit-btn" id="submitButton<?php echo $results->id;?>" onclick="saveEditedTitle(<?php echo $results->main_reviewid;?>);" href="javascript:void(0);">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $results->main_reviewid; ?>" onclick="hideTitleEditForm('<?php echo $results->main_reviewid; ?>')">Cancel</a>
                        

                         </div>
                           <input type="hidden" value="<?php echo $results->main_reviewid; ?>" name="reviewId<?php echo $results->main_reviewid; ?>">
                           <input type="hidden" id="actionPerformed<?php echo $results->main_reviewid; ?>" value="editReview" name="actionPerformed<?php echo $results->main_reviewid; ?>">
                  </div>
                  </div>
                 </form>
    
                </div>  






                             <div class="camp-rating other-details">
                             	<p><span>Rating:</span></p>
                             	<ul>
                                

                                    <?php foreach ($results->reviewDetail as $description => $rating) {?>

                                        <li class="clear-width">
                                                    <span class="flLt comment-width"><?php echo $description;?></span>
                                                    <div id="Excellent" class="flLt">
                                                        <ol class="rating-list" >
                                                            <?php if($rating>0){for($i=1;$i<=$rating;$i++){?>
                                                            <li><a href="javascript:void(0);" class="campus-sprite  rated-star-icon"></a></li>
                                                                                    <?php }}?>
                                                            
                                                            <?php if($rating>0){for($i=1;$i<=5-$rating;$i++){?>
                                                            <li><a href="javascript:void(0);" class="campus-sprite  star-icon"></a></li>
                                                                                    <?php }}?>
                                                        </ol>
                                                        <span class="flRt review-comment"><?php echo $ratingText[$rating];?></span>
                                                    </div>
                                        </li>
                                    <?php }?> 



                                </ul>
                                    
                                    <p style="margin-bottom:10px;">Given a chance, would you go back to this college again : <?php echo $results->recommendCollegeFlag; ?></p>

                                    <p style="display:inline;"> Review Quality: </p>
                                    <select style="display:inline;" id="revQlty<?php echo $results->main_reviewid; ?>" name="revQlty<?php echo $results->main_reviewid; ?>" onchange="showSaveButton(<?php echo $results->main_reviewid;?>);">
                                        <option value="excellent" <?php if($results->reviewQuality == 'excellent') echo 'selected'; ?> >Excellent</option>
                                        <option value="good" <?php if($results->reviewQuality == 'good') echo 'selected'; ?> >Good</option>
                                        <option value="average" <?php if($results->reviewQuality == 'average') echo 'selected'; ?>>Average</option>
                                    </select>
                                    <a class="submit-btn" style="display:none;" id="qltyButton<?php echo $results->main_reviewid;?>" onclick="saveQualityFlag(<?php echo $results->main_reviewid;?>);" href="javascript:void(0);">Save</a>
                                    <div style="display:none;font-size:12px;" id="revQlty<?php echo $results->main_reviewid;?>_error" class="errorMsg">Flag updated successfully</div>
				    <?php $arr = array('crowdcampuslife'=>'Crowd and Campus Life',
						       'placementsalary'=>'Placements & Salary',
						       'campusinfrastructure'=>'Campus Infrastructure',
						       'rankingbrandname'=>'Ranking / Brand name of College',
						       'fees'=>'Fees',
						       'coursestream'=>'Specific Course & Stream',
						       'faculty'=>'Faculty'
						       );?>
                                    
                                <div class="btn-col" id="mainStausBtn_<?php echo $results->main_reviewid; ?>">
                <style>.disabled {pointer-events: none;cursor: default;}</style>
                    <?php if($results->status=='accepted'){ ?>
                        
                        
                        <input id='rejectReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Reject Review" class="fontSize_10p" onclick=" reviewReject('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>');" />
                        <input id='laterReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Mark Later" class="fontSize_10p" onclick="  reviewLater('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>'); " />
                        
                        <?php if($results->isShikshaInstitute != 'YES') { ?>
                        <input id="CourseMapField<?php echo $results->main_reviewid; ?>" name="CourseMapField<?php echo $results->main_reviewid; ?>" type="text" placeholder="Enter Course ID" value="" style="width:120px; padding:3px 2px 4px; margin-left:10px;margin-right:3px;"/>
                        <?php } ?>
                        
                                            <input id='publishReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Publish Review" style="margin:5px !important;width:132px;" class="fontSize_10p" onclick="<?php if($results->isShikshaInstitute != 'YES') { ?>mapCourseIdToNonMappedCollege($j('#CourseMapField<?php echo $results->main_reviewid; ?>').val(),'<?php echo $results->main_reviewid; ?>','<?php echo $results->yearOfGraduation; ?>','<?php echo $results->isShikshaInstitute; ?>',this); <?php } else { ?> updateReviewStatus('published','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this); <?php } ?>" />
                         <?php } else if($results->status=='rejected'){ ?>
                                             <input id='acceptReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Accept Review" class="fontSize_10p" onclick=" updateReviewStatus('accepted','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this)"/>
                                             <input id='rejectReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Reject Review" class="fontSize_10p" onclick=" reviewReject('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>');" />
                                             <input id='laterReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Mark Later" class="fontSize_10p" onclick="  reviewLater('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>'); " />
                        
                        <?php if($results->isShikshaInstitute != 'YES') { ?>
                        <input id="CourseMapField<?php echo $results->main_reviewid; ?>" name="CourseMapField<?php echo $results->main_reviewid; ?>" type="text" placeholder="Enter Course ID" value="" style="width:120px; padding:3px 2px 4px; margin-left:10px; margin-right:3px;"/>
                        <?php } ?>
                        
                            <input id='publishReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Publish Review" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick="<?php if($results->isShikshaInstitute != 'YES') { ?>mapCourseIdToNonMappedCollege($j('#CourseMapField<?php echo $results->main_reviewid; ?>').val(),'<?php echo $results->main_reviewid; ?>','<?php echo $results->yearOfGraduation; ?>','<?php echo $results->isShikshaInstitute; ?>',this); <?php } else { ?> updateReviewStatus('published','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this); <?php } ?>" /> 
                            <input id='unverifiedReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Mark Unverified" class="fontSize_10p" onclick=" updateReviewStatus('unverified','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this)"/>                    
                            
                    <?php } else if($results->status=='published') {?>
                    
                            <input id='unpublishReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="UnPublish Review" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick=" updateReviewStatus('accepted','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this)" />
                            <input id='rejectReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Reject Review" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick=" reviewReject('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>');" />
                            <input id='laterReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Mark Later" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick="  reviewLater('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>'); " />

                    <?php } else if($results->status=='unverified'){ ?>
                            <input id='acceptReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Accept Review" class="fontSize_10p" onclick=" updateReviewStatus('accepted','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this)"/>
                            <input id='rejectReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Reject Review" class="fontSize_10p" onclick=" reviewReject('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>');" />
                            <!--<input id='laterReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Mark Later" class="fontSize_10p" onclick="  reviewLater('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>'); " />-->

                        <?php if($results->isShikshaInstitute != 'YES') { ?>
                        <input id="CourseMapField<?php echo $results->main_reviewid; ?>" name="CourseMapField<?php echo $results->main_reviewid; ?>" type="text" placeholder="Enter Course ID" value="" style="width:120px; padding:3px 2px 4px; margin-left:10px; margin-right:3px;"/>
                        <?php } ?>

                            <input id='publishReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Publish Review" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick="<?php if($results->isShikshaInstitute != 'YES') { ?>mapCourseIdToNonMappedCollege($j('#CourseMapField<?php echo $results->main_reviewid; ?>').val(),'<?php echo $results->main_reviewid; ?>','<?php echo $results->yearOfGraduation; ?>','<?php echo $results->isShikshaInstitute; ?>',this); <?php } else { ?> updateReviewStatus('published','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this); <?php } ?>" />
                    
                    <?php } else { ?>
					
					<div style="float:left;">
                        <input id='laterReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Mark Later" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick="  reviewLater('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>'); " />
					    <input id='acceptReview_<?php echo $results->main_reviewid; ?>'  class= "orange-button" type="button" value="Accept Review" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick=" updateReviewStatus('accepted','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this)"/></div>
					
					<div style="float:left;">
                                            <input id='rejectReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Reject Review" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick=" reviewReject('<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this,'<?php echo $results->reason; ?>');" />
					    <?php if($results->isShikshaInstitute != 'YES') { ?>
					    <input id="CourseMapField<?php echo $results->main_reviewid; ?>" name="CourseMapField<?php echo $results->main_reviewid; ?>" type="text" placeholder="Enter Course ID" value="" style="padding:3px 2px 4px;margin:5px !important;width:125px;"/>
					    <?php } ?>
					    
                                            <input id='publishReview_<?php echo $results->main_reviewid; ?>' class= "orange-button" type="button" value="Publish Review" class="fontSize_10p" style="margin:5px !important;width:132px;" onclick="<?php if($results->isShikshaInstitute != 'YES') { ?>mapCourseIdToNonMappedCollege($j('#CourseMapField<?php echo $results->main_reviewid; ?>').val(),'<?php echo $results->main_reviewid; ?>','<?php echo $results->yearOfGraduation; ?>','<?php echo $results->isShikshaInstitute; ?>',this); <?php } else { ?> updateReviewStatus('published','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this); <?php } ?>" />
                                            <input id='unverifiedReview_<?php echo $results->main_reviewid; ?>' style="margin:5px !important;width:132px;" class= "orange-button" type="button" value="Mark Unverified" class="fontSize_10p" onclick=" updateReviewStatus('unverified','<?php echo $results->main_reviewid; ?>','<?php echo $results->isShikshaInstitute; ?>','','<?php echo $results->yearOfGraduation; ?>','YES',this)"/>

					<?php } ?>
					  	</div>	
                                    </div>
			          
                       <div style="width: 100%; float: left"><div style="display:none;font-size:12px;color:red;" id="reviewlength<?php echo $results->main_reviewid;?>_error" class="errorMsg"></div></div>


                    <div style="float:none;display: block;width: 160px;" class="view_change">
                        <input id='viewChangeBtn<?php echo $results->main_reviewid; ?>'  class= "orange-button" type="button" value="View Change" class="fontSize_10p" style="margin:5px !important;width:132px;background-color: #fff; color: #f23f29;" onclick="getReviewViewChange('<?php echo $results->main_reviewid; ?>',this)"/>
                    </div>

                    <div style="float:none;display: block;width: 160px;" class="view_change">
                        <input id='viewChangeBtn<?php echo $results->main_reviewid; ?>'  class= "orange-button" type="button" value="Check AutoModeration" class="fontSize_10p" style="margin:5px !important;width:190px;background-color: #fff; color: #f23f29;" onclick="getReviewViewChange('<?php echo $results->main_reviewid; ?>',this,'checkAuto')"/>
                    </div>


						<div style='display:none' id= 'CourseFieldErrorDiv<?php echo $results->main_reviewid; ?>'><div class='errorMsg'>Please enter course Id with correct numeric value</div></div>
				    
			        
				    <div style="font-family:Open Sans; font-size:12px margin: 10px 0px 10px 0px;">
					    <p id='acceptmsg_<?php echo $results->main_reviewid; ?>' <?php echo ($results->status=='accepted')?'':'style="display:none;"'?>>The Review has been Accepted</p>
					    <p id='rejectmsg_<?php echo $results->main_reviewid; ?>' <?php echo ($results->status=='rejected')?'':'style="display:none;"'?>>The Review has been Rejected</p>
						<p id='latermsg_<?php echo $results->main_reviewid; ?>'	<?php echo ($results->status=='later')?'':'style="display:none;"'?>>The Review has been marked Later</p>
                        <p id='unverifiedmsg_<?php echo $results->main_reviewid; ?>' <?php echo ($results->status=='unverified')?'':'style="display:none;"'?>>The Review has been marked Unverified</p>
                        <p id='moderationDetail_<?php echo $results->reviewId; ?>' > <?php if(!empty($results->moderationDetail[0]['moderationTime'])){ echo 'Last moderated by '.$results->moderationDetail[0]['moderatorEmail'].' on '.$results->moderationDetail[0]['moderationTime'].'.';} ?></p>
				    </div>
                             </div>
                             
                            </td>
                        </tr>
		    </table>
		    
		    </div>
		    <?php } ?>
		    

                    <?php if(($num_rows-$start)>$count){ ?>
		    <div class="clear-width" style="margin:20px;" id="showMoreReview<?=$start+$count?>"><a href="javascript:void(0);" style="text-align:center; display:block; text-decoration:none;font-size:16px;" id="viewMoreCRBtn" onClick="doReviewPagination();">Show More Reviews <i class="review-sprite more-icon"></i></a></div>
		    <?php }else if(($num_rows-$start)<=$count && $num_rows > 0){ ?>
		    <div class="clear-width" style="margin:20px;text-align:center;font-size:16px;"><b>No More Reviews</b></div>
		    <?php } ?>
                    
                    

<script>
var COOKIEDOMAIN = "<?php echo COOKIEDOMAIN; ?>";
var startUnmapped = "<?php echo $startUnmapped; ?>";
$(".suggestor").unbind();
$(".suggestor").click(function(){
        $case = $(this).find("#minusplus-icon").attr('class');
        if($case.indexOf("plus-icon") > -1){
            $(this).find("#minusplus-icon").removeClass('plus-icon').addClass("minus-icon");
            $cname = $(this).attr('name');
            if($.trim($(this).parent().find(".suggestions_options").html()) == ""){

                $(this).parent().find(".suggestions_options").slideDown().html("Loading...");
                var ajaxURL = "/CAEnterprise/CampusAmbassadorEnterprise/fuzzyMatch/";
                $this = $(this);

                $.post(ajaxURL,{name : $cname },function(data){
                     data = JSON.parse(data);
                                $html = "";
                                for(key in data){
                                    $html = $html + "<a href='/getListingDetail/"+key+"/institute/ss' target='_blank'>"+data[key]+"</a><br />";
                                }
                    $this.parent().find(".suggestions_options").html($html);
                });

            } else {
                $(this).parent().find(".suggestions_options").slideDown();
            }   
        } else {
             $(this).find("#minusplus-icon").removeClass('minus-icon').addClass("plus-icon");
             $(this).parent().find(".suggestions_options").slideUp();
        }
        
        
});
</script>
