   	<!--Alliance Form Preview Starts here-->
	<link href="/public/css/onlineforms/alliance/alliance_styles.css" rel="stylesheet" type="text/css"/>
    <div class="formPreviewMain">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
			      
    	<div class="previewHeader">
        	<div class="instLogoBox"><img src="/public/images/onlineforms/institutes/alliance/logo.gif" alt="" /></div>
            <div class="courseNameDetails">
            	<p>
               	<strong>School of Business</strong><br />
				Chikkahagade Cross<br />
				Chandapura - Anekal Main Road<br />
				Anekal Bangalore – 562106, India<br />
				Tel.: +91 80 30938000/1/2/3/4<br />
				www.alliance.edu.in 				
                </p>
            </div>
            <div class="formNumb">Application Form <span>&nbsp;</span></div>
			<div class="editFormCol applicationFormEditLink">
            	<?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	  <strong class="editFormLink">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong>
			      <?php } ?>
            </div>
            
            <div class="clearFix"></div>         
        </div>
        <div class="clearFix"></div>         
       	<div class="clearFix"></div>
        <div class="previewBody">
        	<div class="formRows">
            	<div class="formRowsChild">
            	<ul>
      				<li>
                		<span style="padding-left:30px">Year: <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2012";}?></span>
                        <span style="padding-left:230px">(Please tick the course applied for)</span>
                        <div class="clearFix spacer5"></div>    	
				  		<table width="100%" cellpadding="5" cellspacing="0" border="0">
                       	  <tr>
                           	  <td rowspan="2">
                              	<div class="checkBox"><?php if(strpos($coursesAlliance,'Executive') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                <span class="checkBoxLabel">Executive <strong>MBA</strong></span>
                              </td>
                              <td><div class="checkBox">
				    <?php 
				      $coursesArr = preg_split(',',$coursesAlliance);
				      foreach ($coursesArr as $course){
				      if( $course == 'MBA'){ ?>
					<img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" />
				    <?php }} ?>
				  </div><strong>MBA</strong>
			      </td>
                              <td><div class="checkBox"><?php if(strpos($coursesAlliance,'Entrepreneurship') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                              <span class="checkBoxLabel"><strong>MBA</strong> - Enterpreneurship and Family Business</span></td>
                          </tr>
                                
                          <tr>
                           	  <td><div class="checkBox"><?php if(strpos($coursesAlliance,'International') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                              <span class="checkBoxLabel"><strong>MBA</strong> - International Business</span></td>
                              <td><div class="checkBox"><?php if(strpos($coursesAlliance,'Communications') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                              <span class="checkBoxLabel"><strong>MBA</strong> - Communications Management</span></td>
                          </tr>
                          </table>
                          <div class="clearFix"></div>
                	</li>
                 </ul>
               <div class="clearFix"></div>
            </div>
            </div>
        	
            <div class="formRows">
            	<div class="formRowsChild">
                	<div class="applicantInfoCol">
                		<h3>Applicant's information</h3>
                        <ul>
                            <li>
                                <label>Name:</label>
                                <div style="width:680px" class="previewFieldBox2"><span>&nbsp;<?php echo ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?></span></div>
                                <div class="clearFix"></div>   
                            </li>
                            
                            <li>
                            	<div class="formColumns2" style="width:300px">
                                    <label>Gender:</label>
                                    <div style="width:200px" class="previewFieldBox2"><span>&nbsp;<?php echo $gender;?></span></div>
                                </div>
                                
		    <?php
		    list($dobDay,$dobMonth,$dobYear) = explode('/',$dateOfBirth);
		    ?>
                                <div class="formColumns2" style="width:400px">
                                    <label>Date of Birth:<br /><span>(dd/mm/yyyy)</span>
									</label>
                                    <div style="width:50px; text-align:center" class="previewFieldBox2"><span>&nbsp;<?php echo $dobDay[0].$dobDay[1];?></span></div>
                                    <div class="dateSeparater">/</div>
                                    <div style="width:50px; text-align:center" class="previewFieldBox2"><span>&nbsp;<?php echo $dobMonth[0].$dobMonth[1];?></span></div>
                                    <div class="dateSeparater">/</div>
                                    <div style="width:80px; text-align:center" class="previewFieldBox2"><span>&nbsp;<?php echo $dobYear[0].$dobYear[1].$dobYear[2].$dobYear[3];?></span></div>
                                </div>
                                <div class="clearFix"></div>   
                            </li>
                            
                            <li>
                                <label>Blood Group:</label>
                                <div style="width:160px" class="previewFieldBox2"><span>&nbsp;<?=$bloodGroupAlliance?></span></div>
                                <div class="clearFix"></div>   
                            </li>
                            <li>
                                <label style="width:70px">Category:</label>
                                <div class="formColumns2" style="width:100px">
                                	<div class="checkBox"><?php if(strpos($applicationCategory,'SC') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
		                            <span class="checkBoxLabel">SC</span>
                                </div>
                                
                                <div class="formColumns2" style="width:100px">
                                	<div class="checkBox"><?php if(strpos($applicationCategory,'ST') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
		                            <span class="checkBoxLabel">ST</span>
                                </div>
                                
                                <div class="formColumns2" style="width:100px">
                                	<div class="checkBox"><?php if(strpos($applicationCategory,'OBC') !== false || strpos($applicationCategory,'DEFENCE') !== false || strpos($applicationCategory,'GENERAL') !== false || strpos($applicationCategory,'HANDICAPPED') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
		                            <span class="checkBoxLabel">Others</span>
                                </div>
                                <div class="clearFix"></div>   
                            </li>
                            
                            <li>
                                <label style="width:70px">Nationality:</label>
                                <div class="formColumns2" style="width:100px">
                                	<div class="checkBox"><?php if(strpos($nationalityAlliance,'INDIAN') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
		                            <span class="checkBoxLabel">Indian</span>
                                </div>
                                
                                <div class="formColumns2" style="width:100px">
                                	<div class="checkBox"><?php if(strpos($nationalityAlliance,'NRI') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
		                            <span class="checkBoxLabel">NRI</span>
                                </div>
                                
                                <div class="formColumns2" style="width:450px">
                                	<div class="checkBox"><?php if(strpos($nationalityAlliance,'Foreign') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
		                            <span class="checkBoxLabel"> Foreign National (Citizenship)&nbsp;</span>
                                    <div style="width:240px;" class="previewFieldBox2"><span>&nbsp;<?php if(strpos($nationalityAlliance,'INDIAN') === false){ echo $nDetailsAlliance;} ?></span></div>
                                </div>
                                
                                <div class="clearFix"></div>   
                            </li>
                         </ul>
                 	</div>
			<div class="picBox">
			<?php if($profileImage) { ?>
			    <img width="170" height="200" src="<?php echo $profileImage; ?>" />
			<?php } ?>
			</div>
               	<div class="clearFix"></div>
               </div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	
                	<h3>Applicant's contact details for correspondence</h3>
                    <ul>
                        <li>
                            <label>Address:</label>
                            <div style="width:850px" class="previewFieldBox2">
                            	<span>&nbsp;<?php echo $ChouseNumber;
									if($CstreetName) echo ', '.$CstreetName;
									if($Carea) echo ', '.$Carea;
									//if($Cstate) echo ', '.$Cstate;
									//if($Ccountry) echo ', '.$Ccountry;
								?></span>
                                <div class="clearFix spacer10"></div>
                                <span>&nbsp;<?php if($Ccity) echo $Ccity; ?></span>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                            <div class="formColumns2" style="width:300px">
                                <label>Pin Code:</label>
                                <div style="width:220px" class="previewFieldBox2"><span>&nbsp;<?=$Cpincode?></span></div>
                            </div>
                            
                            <div class="formColumns2" style="width:300px">
                                <label>State:</span></label>
                                <div style="width:242px;" class="previewFieldBox2"><span>&nbsp;<?=$Cstate?></span></div>
                            </div>
                            
                            <div class="formColumns2" style="width:300px; float:right">
                                <label>Country:</span></label>
                                <div style="width:242px;" class="previewFieldBox2"><span>&nbsp;<?=$Ccountry?></span></div>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                            <label>Tel.: (Residence)<br /><span>(with STD/ISD code)</span></label>
                            <div style="width:790px" class="previewFieldBox2"><span>&nbsp;<?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></span></div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                            <label>Mobile:</label>
                            <div style="width:850px" class="previewFieldBox2"><span>&nbsp;<?=$mobileNumber?></span></div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                        	<div class="formColumns2" style="width:400px;">
                                <label>Email:</label>
                                <div style="width:350px" class="previewFieldBox2"><span>&nbsp;<?=$email?></span></div>
                            </div>
                            <div class="formColumns2" style="width:500px;">
                                <label>Alternative Email:</label>
                                <div style="width:390px" class="previewFieldBox2"><span>&nbsp;<?=$altEmail?></span></div>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                     </ul>
                	<div class="clearFix"></div>
               </div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<ul>
                        <li>
                            <label>Father's Name:</label>
                            <div style="width:810px" class="previewFieldBox2">
                            	<span>&nbsp;<?=$fatherName?></span>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                            <label>Occupation:</label>
                            <div style="width:825px" class="previewFieldBox2"><span>&nbsp;<?=$fatherOccupation?></span></div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                            <label>Mothers's Name:</label>
                            <div style="width:795px" class="previewFieldBox2">
                            	<span>&nbsp;<?=$MotherName?></span>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                            <label>Occupation:</label>
                            <div style="width:825px" class="previewFieldBox2"><span>&nbsp;<?=$MotherOccupation?></span></div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                            <label>Residential Address:</label>
                            <div style="width:778px" class="previewFieldBox2">
                            	<span>&nbsp;<?php echo $houseNumber;
									if($streetName) echo ', '.$streetName;
									if($area) echo ', '.$area;
									//if($Cstate) echo ', '.$Cstate;
									//if($Ccountry) echo ', '.$Ccountry;
								?></span>
                            	<div class="clearFix spacer10"></div>
                            	<span>&nbsp;<?php if($city) echo $city.", "; echo $pincode; ?></span>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                        	<div class="formColumns2" style="width:400px;">
                                <label>Mobile:</label>
                                <div style="width:340px" class="previewFieldBox2"><span>&nbsp;<?=$mobileNumber?></span></div>
                            </div>
                            <div class="formColumns2" style="width:500px;">
                                <label>Email:</label>
                                <div style="width:455px" class="previewFieldBox2"><span>&nbsp;<?=$fatherEmailAlliance?></span></div>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                        
                        <li>
                        	<div class="formColumns2" style="width:500px;">
                                <label>Telephone: Office:<br /><span>(with STD/ISD code)</span></label>
                                <div style="width:375px" class="previewFieldBox2"><span>&nbsp;<?=$fatherNoAlliance?></span></div>
                            </div>
                            <div class="formColumns2" style="width:400px;">
                                <label>Residence:</label>
                                <div style="width:327px" class="previewFieldBox2"><span>&nbsp;<?php echo $landlineISDCode.'-'.$landlineSTDCode.'-'.$landlineNumber; ?></span></div>
                            </div>
                            <div class="clearFix"></div>   
                        </li>
                     </ul>
                	<div class="clearFix"></div>
               </div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<h3>Education qualifications</h3>
                    <ul>
                        <li>
                        	<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                            	<tr>
                                	<th>Name of <br />Examination/Degree</th>
                                    <th>Name of <br />Institution</th>
                                    <th>Name of <br />University/Board</th>
                                    <th>State</th>
                                    <th>Year of <br />passing</th>
                                    <th>Percentage<br />Year/Semester</th>
                                    <th>Main subject</th>
                                    <th>Mode of study</th>
                                </tr>

				<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				$countOfPGCourses = 0;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					?>
					<?php if( ${'otherCoursePGCheck_mul_'.$i} == '1' ){ $countOfPGCourses++; ?>
					<tr>
						  <td valign="top">
						  <div style="width:120px; text-align:center">
							  Post graduate degree<br />
												  (if any, please specify)
						      <div class="spacer15 clearFix"></div>
						      <div class="previewFieldBox" style="width:100%">
							  <div class="formWordWrapper" style="width:120px; text-align:left; float:left">
								  <span>&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></span>
							  </div>
						      </div>
						  </div>
					      </td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationSchool_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationBoard_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=${'otherCourseState_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationYear_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=${'otherCourseSubjects_mul_'.$i}?></div></td>
					      <td valign="top">
						  <div style="width:100px;">
							  <div class="checkBox" style="margin-right:2px"><?php if(strpos(${'otherCourseMode_mul_'.$i},'Full-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
								  <span class="checkBoxLabel" style="font-size:12px">Full-time</span>
						  
							  <div class="clearFix spacer10"></div>   
							  <div class="checkBox" style="margin-right:2px"><?php if(strpos(${'otherCourseMode_mul_'.$i},'Part-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
								  <span class="checkBoxLabel" style="font-size:12px">Part-time</span>
						  
							  <div class="clearFix spacer10"></div>   
							  <div class="checkBox" style="margin-right:2px"><?php if(strpos(${'otherCourseMode_mul_'.$i},'Correspondence') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
								  <span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
						  </div>
					      </td>

					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->
                                <?php if($countOfPGCourses==0){ ?>
                                <tr>
                                	<td valign="top">
                                    	<div style="width:120px; text-align:center">
                                        	Post graduate degree<br />
											(if any, please specify)
                                            <div class="spacer15 clearFix"></div>
                                            <div class="previewFieldBox" style="width:100%">
                                            	<div class="formWordWrapper" style="width:120px; text-align:left; float:left">
                                            		<span>&nbsp;</span>
                                            	</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
                                    		<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Full-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
                                    	</div>
                                    </td>
                                </tr>
                                <?php } ?>

                                <tr>
                                	<td valign="top">
                                    	<div style="width:120px; text-align:center">
                                        	Bachelor's degree<br />
											(Please specify)
                                            <div class="spacer15 clearFix"></div>
                                            <div class="previewFieldBox" style="width:100%">
                                            	<div class="formWordWrapper" style="width:120px; text-align:left; float:left">
                                            		<span>&nbsp;<?=$graduationExaminationName?></span>
                                            	</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$graduationSchool?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$graduationBoard?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=$gradStateAlliance?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=$graduationYear?></div></td>
                                    <td valign="top"><div style="width:115px;">
                                    	<table width="100%" cellpadding="4" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                                        	<tr>
                                            	<td width="40">I</td>
                                                <td width="60" style="text-align:center"><?=$gradMarksSem1Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>II</td>
                                                <td style="text-align:center"><?=$gradMarksSem2Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>III</td>
                                                <td style="text-align:center"><?=$gradMarksSem3Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>IV</td>
                                                <td style="text-align:center"><?=$gradMarksSem4Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>V</td>
                                                <td style="text-align:center"><?=$gradMarksSem5Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>VI</td>
                                                <td style="text-align:center"><?=$gradMarksSem6Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>VII</td>
                                                <td style="text-align:center"><?=$gradMarksSem7Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td>VIII</td>
                                                <td style="text-align:center"><?=$gradMarksSem8Alliance?></td>
                                            </tr>
                                            <tr>
                                            	<td style="font-size:11px">Aggregate</td>
                                                <td style="text-align:center"><?=$graduationPercentage?></td>
                                            </tr>
                                        </table>
                                    </div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=$gradSubjectsAlliance?></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
                                    		<div class="checkBox" style="margin-right:2px"><?php if(strpos($gradModeAlliance,'Full-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"><?php if(strpos($gradModeAlliance,'Part-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px;">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"><?php if(strpos($gradModeAlliance,'Correspondence') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
                                    	</div>
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td>
                                    	<div style="width:120px; text-align:center">
                                        	XII
										</div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class12School?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class12Board?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=$class12StateAlliance?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=$class12Year?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=$class12Percentage?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=$class12SubjectsAlliance?></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
                                    		<div class="checkBox" style="margin-right:2px"><?php if(strpos($class12ModeAlliance,'Full-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"><?php if(strpos($class12ModeAlliance,'Part-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px;">Part-time</span>

                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"><?php if(strpos($class12ModeAlliance,'Correspondence') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
                                    	</div>
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td>
                                    	<div style="width:120px; text-align:center">
                                        	X
										</div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class10School?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$class10Board?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=$class10StateAlliance?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=$class10Year?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=$class10Percentage?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=$class10SubjectsAlliance?></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
                                    		<div class="checkBox" style="margin-right:2px"><?php if(strpos($class10ModeAlliance,'Full-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"><?php if(strpos($class10ModeAlliance,'Part-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px;">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"><?php if(strpos($class10ModeAlliance,'Correspondence') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
                                    	</div>
                                    </td>
                                </tr>





				<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					?>
					<?php if( ${'otherCoursePGCheck_mul_'.$i} != '1' ){ $otherCourseShown = true; ?>
					<tr>
						<td valign="top">
						  <div style="width:120px; text-align:center">
						      Other professional<br />qualifications
						      <div class="spacer15 clearFix"></div>
						      <div class="previewFieldBox" style="width:100%">
							  <div  style="width:120px; text-align:left; float:left">
								  <span>&nbsp;<?=${'graduationExaminationName_mul_'.$i}?></span>
							  </div>
						      </div>
						  </div>
					      </td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationSchool_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=${'graduationBoard_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=${'otherCourseState_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationYear_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					      <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"><?=${'otherCourseSubjects_mul_'.$i}?></div></td>
					      <td valign="top">
						  <div style="width:100px;">
							  <div class="checkBox" style="margin-right:2px"><?php if(strpos(${'otherCourseMode_mul_'.$i},'Full-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
								  <span class="checkBoxLabel" style="font-size:12px">Full-time</span>
						  
							  <div class="clearFix spacer10"></div>   
							  <div class="checkBox" style="margin-right:2px"><?php if(strpos(${'otherCourseMode_mul_'.$i},'Part-time') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
								  <span class="checkBoxLabel" style="font-size:12px">Part-time</span>
						  
							  <div class="clearFix spacer10"></div>   
							  <div class="checkBox" style="margin-right:2px"><?php if(strpos(${'otherCourseMode_mul_'.$i},'Correspondence') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
								  <span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
						  </div>
					      </td>

					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->

                                <?php if(!$otherCourseShown){ ?>
                                <tr>
                                	<td>
                                    	<div style="width:120px; text-align:center">
                                        	Other professional<br />qualifications
										</div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:115px; text-align:center"></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:90px; text-align:center"></div></td>
                                    <td valign="top">
                                    	<div style="width:100px;">
                                    		<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Full-time</span>
                                    		
                                            <div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px;">Part-time</span>
                                    	
                                        	<div class="clearFix spacer10"></div>   
                                        	<div class="checkBox" style="margin-right:2px"></div>
                                			<span class="checkBoxLabel" style="font-size:12px">Correspondence</span>
                                    	</div>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                            </table>
                            <div class="clearFix"></div>   
                        </li>
                        
                        
                     </ul>
                	<div class="clearFix"></div>
               </div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<h3>Aptitute test score</h3>
                    <p style="text-align:center; font-size:16px">GMAT/CAT/XAT/MAT/ATMA</p>
                    <div class="clearFix spacer10"></div>
                    <ul>
                        <li>
                        	<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                            	<tr>
                            		<th>Test</th>
                                    <th>Date</th>
                                    <th>Percentile</th>
                                    <th>Composite score</th>    
                                </tr>

				<?php
				    $total = 0;
				    $tests = explode(",",$testsAlliance);
				    $dateTest = $percentileTest = $scoreTest = '';
				    foreach ($tests as $test){ 	
					  $total++;
					  if($test == 'GMAT' || $test == 'MAT' || $test == 'CAT'){
						$test = strtolower($test);
						$testdate = $test.'DateOfExaminationAdditional';
						$testperc = $test.'PercentileAdditional';
						$testscore = $test.'ScoreAdditional';
					  }
					  else if($test == 'XAT' || $test == 'ATMA'){
						$test = strtolower($test);
						$testdate = $test.'DateAlliance';
						$testperc = $test.'PercentileAlliance';
						$testscore = $test.'ScoreAlliance';
					  }

					  $dateTest = $$testdate;
					  $percentileTest = $$testperc;
					  $scoreTest = $$testscore
				?>
                                <tr>
				    <td valign="top" align="center" width="250"><div class="formWordWrapper" style="width:250px; text-align:center">&nbsp;<?php echo strtoupper($test);?></div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;<?=$dateTest?></div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;<?=$percentileTest?></div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;<?=$scoreTest?></div></td>
                                </tr>
				<?php } ?>

				<?php for($i=$total; $i<4;$i++ ){ ?>
                                <tr>
				    <td valign="top" align="center" width="250"><div class="formWordWrapper" style="width:250px; text-align:center">&nbsp;</div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;</div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;</div></td>
                                    <td align="center" valign="top" width="200"><div class="formWordWrapper" style="width:200px; text-align:center">&nbsp;</div></td>
                                </tr>
				<?php } ?>
                            </table>
                    	</li>
                	</ul>
            	</div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<h3>Statement of purpose</h3>
                    <div class="clearFix spacer10"></div>
                    <ul>
                        <li class="purposeStatement">
                    		<label><span>a.</span>What motivates you to apply Alliance University School of Business?</label>    
                            <div class="spacer5 clearFix"></div>
                            <p><?=$motivatesAlliance?></p>
                        </li>
                        
                        <li class="purposeStatement">
                    		<label><span>b.</span>What is your career vision and why is this choice meaningful to you?</label>    
                            <div class="spacer5 clearFix"></div>
                            <p><?=$careerVisionAlliance?></p>
                        </li>
                        
                        <li class="purposeStatement">
                    		<label><span>c.</span>When you join Alliance University School of Business, how will you introduce yourself to your new classmates?</label>    
                            <div class="spacer5 clearFix"></div>
                            <p><?=$whyJoinAlliance?></p>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<h3>Employment history <span>(Please give your full-time employment history to date or other relevant professional experiences)</span></h3>
                    <ul>
                        <li>
                        	<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                            	<tr>
                            		<th rowspan="2">Name and address of the organisation</th>
                                    <th colspan="3">Work experience</th>
                                    <th rowspan="2">Designation and responsibilities</th>
                                </tr>
                                <tr>
                            		<th>From</th>
                                    <th>To</th>
                                    <th>In months</th>
                                </tr>


			    <?php 
				  $workExGiven = false;
				  $total = 0;
				  for($i=0; $i<4; $i++){

				      //$mulSuffix = $i>0?'_mul_'.$i:'';
				      $mulSuffix = $i>0?'_mul_'.$i:'';
				      $otherSuffix = '_mul_'.$i;
				      $companyName = ${'weCompanyName'.$mulSuffix};
				      $durationFrom = ${'weFrom'.$mulSuffix};
				      $durationTo = trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
				      $designation = ${'weDesignation'.$mulSuffix};
				      $natureOfWork = ${'weRoles'.$mulSuffix};
				      $addressCompany = ${'orgnAddressAlliance'.$otherSuffix};
				      if($companyName || $designation){ $workExGiven = true;$total++; ?>

					    <tr>
						<td valign="top" width="300"><div class="formWordWrapper" style="width:300px"><?php echo $companyName .", ".$addressCompany; ?></div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo $durationFrom;?></div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px;  text-align:center"><?php echo $durationTo;?></div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php
										    if($durationFrom) {
											    $startDate = getStandardDate($durationFrom);
											    $endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
											    $totalDuration = getTimeDifference($startDate,$endDate);
											    echo ($totalDuration['months']<0)?0:$totalDuration['months'];
										    }
										    ?></div></td>
						<td valign="top" width="250"><div class="formWordWrapper" style="width:250px"><?php echo $designation.", ".$natureOfWork;?></div></td>
					    </tr>

			    <?php }} ?>
			    
			    <?php  
				  for($j=$total; $j<3; $j++){ ?>
					    <tr>
						<td valign="top" width="300"><div class="formWordWrapper" style="width:300px">&nbsp;</div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center">&nbsp;</div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center">&nbsp;</div></td>
						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center">&nbsp;</div></td>
						<td valign="top" width="2500"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
					    </tr>
			    <?php } ?>
                                
                            </table>
                    	</li>
                	</ul>
            	</div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<ul>
                        <li>
                    		<div style="width:400px; float:left;"><h3>Do you need hostel accommodation?</h3></div>
                            <div style="width:400px; float:left;padding-top:3px">
                            	<div style="width:100px; float:left">
                                    <div style="margin-right:8px" class="checkBox"><?php if(strpos($hostelAlliance,'Yes') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                    <span style="font-size:14px" class="checkBoxLabel">Yes</span>
                                </div>
                                <div style="width:100px; float:left">
                                    <div style="margin-right:8px" class="checkBox"><?php if(strpos($hostelAlliance,'No') !== false){ ?><img src="/public/images/onlineforms/institutes/alliance/tick-icn.gif" /><?php } ?></div>
                                    <span style="font-size:14px" class="checkBoxLabel">No</span>
                                </div>
                            </div>
                    	</li>
                	</ul>
            	</div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<h3>Declaration</h3>
                	<ul>
                        <li>
                    		<ul>
                            	<li>I have read and understood the full requirements of the course, eligibility criteria, terms and conditions and other important information as indicated in the prospectus.</li>
                                <li>I confirm that the information furnished by me in this Application Form is true to the best of my knowledge. I understand that any false or misleading information given by me may lead to the cancellation of admission or expulsion from the course at any stage.</li>
                                <li>I undertake to abide by the rules and regulations of Alliance University School of Business as prescribed from time to time. If I violate at any given point of time any of the stipulated rules and regulations, the University is free to initiate appropriate disciplinary action against me.</li>
                            </ul>
                            <div class="clearFix spacer10"></div>
                            <div class="flLt formWordWrapper" style="width:400px;">Signature: <span>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span></div>
                            <div class="flRt" style="width:130px;">Date: 
			    <span>
				<?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?>
			    </span>
			    </div>
                            <div class="clearFix"></div>
                        </li>
                	</ul>
            	</div>
            </div>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<h3>Application fee</h3>
                	<ul>
                        <li>
                        	Enclose along with your completed application form a non-refundable processing fee of Rs. 1,000/- in the form of a crossed demand draft in favour of "Alliance University" payable at Bangalore.
                    		<div class="clearFix"></div>
                        </li>
                	</ul>
            	</div>
            </div>
            
            <div class="formRows formRowsLast">
            	<div class="formRowsChild">
                	<h3>Payment Details</h3>
                    <strong style="font-size:18px">Application form fee</strong>
                    <div class="clearFix spacer15"></div>
                	<ul>

			<?php if(is_array($paymentDetails)){ ?>
			<?php if($paymentDetails['mode']=='Offline'){ ?>
			    <?php $bankName = $paymentDetails['bankName'];?>
			    <?php $draftNo = $paymentDetails['draftNumber'];?>
			    <?php if(strtotime($paymentDetails['draftDate'])) $draftDate = date("d/m/Y", strtotime($paymentDetails['draftDate'])); else $draftDate = '';?>
			<?php }else if($paymentDetails['mode']=='Online'){ ?>
			    <?php $bankName =  $paymentDetails['bankName'];?>
			    <?php $draftNo =  $paymentDetails['orderId'];?>
			    <?php if(strtotime($paymentDetails['date'])) $draftDate =  date("d/m/Y", strtotime($paymentDetails['date'])); else $draftDate = '';?>
			<?php }
			}else { ?>
			    <?php $bankName =  '';?>
			    <?php $draftNo =  '';?>
			    <?php $draftDate =  '';?>
			<?php } ?>

                        <li>
                        	<label>DD No.:</label>
							<div class="previewFieldBox2" style="width:450px"><span>&nbsp;<?=$draftNo?></span></div>
                    		<div class="clearFix"></div>
                        </li>
                        <li>
                        	<label>Date:</label>
							<div class="previewFieldBox2" style="width:468px"><span>&nbsp;<?=$draftDate?></span></div>
                    		<div class="clearFix"></div>
                        </li>
                        <li>
                        	<label>Bank:</label>
							<div class="previewFieldBox2" style="width:466px"><span>&nbsp;<?=$bankName?></span></div>
                            <div class="clearFix spacer10"></div>
                            <p>Amount: Rs. 1,000/-</p>
                    		<div class="clearFix"></div>
                        </li>
                	</ul>
            	</div>
            </div>
         </div>
    </div>
    <!--Alliance Form Preview Ends here-->
