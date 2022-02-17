<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<style type="text/css">
.preff-cont .option-box{margin:0 5px 0 0px !important; width:20px;}
</style>
<?php
$instituteInfo = $instituteInfo[0]['instituteInfo'][0];
?>
        
    	<!--Personal Info Starts here-->
    	<div class="personalDetailsSection">
	<div class="appsFormBg">
    	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
         
	<div id="custom-form-header">
    	<div class="app-left-box" style="width:100%;">
         <div class="clearFix spacer10"></div>
              
            <div class="inst-name" style="width:70%;margin:0 0 10px 0;">
                <img src="/public/images/onlineforms/institutes/scbja/logo2.gif" alt="<?=$instituteInfo['institute_name']?>" style="float:left" />
			<div style="float:right">
				<h2 style="font-size:20px;">St. Joseph's College of Business Administration</h2>
				<div style="text-align:left;margin-left:20px; line-height:17px;">                              
                                        18, RESIDENCY ROAD, BANGALORE – 560 025<br/>
                                             Mobile: +91 99722 52765,<br/>
                                         E- Mail :admissions@sjcba.edu.in
				</div>
			</div>
             </div>


<div class="user-pic-box" style="float:left; margin-left:75px;"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>

             <div id="custom-form-content">
    	   <li>

        <label style="float:left;">Form Id: </label>

                        <div class="form-details" style="float:left;"><?php if(!empty($instituteSpecId)) {echo $instituteSpecId;} else {echo $onlineFormId;} ?></div>

        </li>




        
   </div>
   
            
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            
        </div>

      
        
    
    <div class="spacer15 clearFix"></div>
   </div>
	<div class="appForm-box">APPLICATION FOR TWO YEAR FULL TIME<br />POST GRADUATE DIPLOMA IN MANAGEMENT</br />
                                <span>Year:<?php /* if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2014";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2016";}?><?php echo " 2015-2017 (AICTE Approved)
"*/ ?> 2015-2017 (AICTE Approved) </div>


       
        <div class="spacer15 clearFix"></div>
	
	<div id="custom-form-content">
	 <ul>
	  <li>
		<h3 class="form-title">Applicant's Information</h3>
		<div class="colums-width">
		  <?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
            	<label><strong>Name of the Applicant:</strong><br />(As per degree marks card) </label>
                <div class="form-details"><?php echo $userName; ?></div>
		</div>
  
            	<div class="reviewChildRightCol">
                        <label><span style="width:88px;">Date of birth:<br />(dd/mm/yyy)</span></label>
                        
                        <?php echo $dateOfBirth;?>
			  </div>
           </li>
	   
	  
	   <li>
	    		<div class="colums-width">

                        <label><strong>Gender:</strong></label>
                        <span><?php echo $gender;?></span>
			</div>
			
                    
                        <div class="reviewChildRightCol">
		        <label><strong>Religion:</strong></label>
                        <span><?php echo $religion;?></span>
                    
			</div>
	   </li>
	   <li>
			 <div class="columns-width">
		<label><strong>Reserved Category :</strong></label>
            	
			
				<div class="preff-cont"> SC <span class="option-box"><?php if( strpos($SCBJA_category,'SC') !== false ) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span></div> 
				<div class="preff-cont"> ST <span class="option-box"><?php if( strpos($SCBJA_category,'ST') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span></div>
				<div class="preff-cont"> OBC <span class="option-box"><?php if( strpos($SCBJA_category,'OBC') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span></div>
			</div>
			
			
			<div class="reviewChildRightCol" style="width:38%;">
                        <label><strong>Are you a Catholic:</strong></label>
                        <span><?php echo $SCBJA_catholic;?></span>
			</div>
                 
            </li>
	   
	 </ul> 
	</div>
	<div id="custom-form-content">
	 <ul>
      <li>

            <h3 class="form-title">Contact Information </h3>
               
              <table>
                  <tr>
            	       <td colspan="7">
		       <div class="colums-width" style="width:500px;margin-bottom:5px;">
		       <b><label style="float:left;">Candidate's permanent Address: </label></b>

                       <div class="form-details">&nbsp;

		          <?php if($ChouseNumber) echo $ChouseNumber.', ';

			    if($CstreetName) echo $CstreetName.', ';?>
                       </div>
		       </div>
           
                       <div class="colums-width" style="width:220px;margin-bottom:5px;">

                         <b><label style="float:left;">Area:</label></b>

                         <div class="form-details">&nbsp;<?=$area;?></div>

                      </div>
                 
                <div class="colums-width" style="width:220px;margin-bottom:5px;">

                    <b><label style="float:left;">City:</label></b>

                    <div class="form-details">&nbsp;<?=$city;?></div>

                </div>

                <div class="colums-width" style="width:220px;margin-bottom:5px;">

                    <b><label style="float:left;">State:</label></b>

                    <div class="form-details">&nbsp;<?=$state;?></div>

                </div>



                <div class="colums-width" style="width:227px;margin-bottom:5px;">

                    <b><label style="float:left;">PIN Code:</label></b>

                    <div class="form-details">&nbsp;<?=$pincode;?></div>


               </div>

                <div class="colums-width">

                    <b><label style="float:left;">Tel code:</label></b>

                    <?php if($landlineISDCode) $isd = $landlineISDCode.'-';else $isd ='';?>

                    <?php if($landlineSTDCode) $std = $landlineSTDCode;else $std='';?>
                     <div class="form-details" style="width:60px; float:left;">&nbsp;<?php echo $isd.$std; ?></div>

                    <b><label style="float:left;">No:</label></b>


                    <div class="form-details">&nbsp;<?php echo $landlineNumber; ?></div>

                </div>
      
        </td>

		<td colspan="7">
		<div class="colums-width" style="width:400px;margin-bottom:5px;">
		<b><label style="float:left;">Address For Communication: </label></b>
                <div class="form-details">&nbsp;

                  <?php if($ChouseNumber) echo $ChouseNumber.', ';

                            if($CstreetName) echo $CstreetName.', ';?></div>

		</div>
		<div class="reviewChildRightCol" style="width:450px">
		     <div class="colums-width" style="width:225px;margin-bottom:5px;">

                       <b><label style="float:left;">Area:</label></b>

                       <div class="form-details">&nbsp;<?=$area;?></div>
                    </div>
		     
		     <div class="colums-width" style="width:225px;margin-bottom:5px;">

                    <b><label style="float:left;">City:</label></b>

                    <div class="form-details">&nbsp;<?=$city;?></div>

                </div>
		     
               </div>
	        </div>
           




                
		<div class="reviewChildRightCol" style="width:450px">
		  <div class="colums-width" style="width:225px;margin-bottom:5px;">

                    <b><label style="float:left;">State:</label></b>

                    <div class="form-details">&nbsp;<?=$state;?></div>

                </div>



                <div class="colums-width" style="width:225px;margin-bottom:5px;">

                    <b><label style="float:left;">PIN Code:</label></b>

                    <div class="form-details">&nbsp;<?=$pincode;?></div>


               </div>
 
		</div>

                
                <div class="colums-width" style="width:260px;margin-bottom:5px;">

                    <b><label style="float:left;">Email:</label></b>

                    <div class="form-details">&nbsp;<?=$email;?></div>

                </div>

            
            	<div class="colums-width">

                    <b><label style="float:left;">Mobile No:</label></b>

                    <div class="form-details">&nbsp;<?=$mobileISDCode.'-'.$mobileNumber?></div>

                </div>

         </td>
     
      </tr>
   </table>
      </li>
	      </ul>
       </div>
<div id="custom-form-content">
<ul>
 <li>
	    <h3 class="form-title">FAMILY DETAILS</h3>

            	<div class="colums-width" style="margin-bottom:5px; width:504px;">

                    <b><label style="float:left;">Father's / Guardian's Name &amp; Occupation :</label></b>

                    <div class="form-details">&nbsp;<?php echo $fatherName; echo ($fatherOccupation!='')?', '.$fatherOccupation:'';?></div>

                </div>

            	
		 

                <div class="colums-width" style="margin-bottom:5px;width:400px;">

                    <b><label style="float:left;">Father's Organisation: </label></b>

                    <div class="form-details">&nbsp;<?=$SCBJA_fOrganization;?></div>

                </div>

               <div class="colums-width" style="margin-bottom:5px;width:504px;">

                   <b> <label style="float:left;">Father's Designation: </label></b>

                    <div class="form-details">&nbsp;<?=$fatherDesignation;?></div>

                </div>


                <div class="colums-width" style="margin-bottom:5px;width:400px;">

                    <b><label style="float:left;">Father's Annual Income: </label></b>

                    <div class="form-details">&nbsp;<?=$SCBJA_fannualIncome;?></div>

                </div>

                 <div class="colums-width" style="margin-bottom:5px;width:504px;">

                    <b><label style="float:left;">Cell Number: </label></b>

                    <div class="form-details">&nbsp;<?=$SCBJA_fCellnumber;?></div>

                </div>



           <div class="spacer15 clearFix"></div>

                <div class="colums-width" style="margin-bottom:5px; width:504px;">

                    <b><label style="float:left;">Mother's Name &amp; Occupation :</label></b>

                    <div class="form-details">&nbsp;<?php echo $MotherName; echo ($MotherOccupation!='')?', '.$MotherOccupation:'';?></div>

                </div>




                <div class="colums-width" style="margin-bottom:5px;width:400px;">

                    <b><label style="float:left;">Mother's Organisation: </label></b>

                    <div class="form-details">&nbsp;<?=$SCBJA_mOrganization;?></div>

                </div>

            

                <div class="colums-width" style="margin-bottom:5px;width:504px;">

                    <b><label style="float:left;">Mother's  Designation: </label></b>
                                   
                    <div class="form-details">&nbsp;<?=$MotherDesignation;?></div>

                </div>


                <div class="colums-width" style="margin-bottom:5px;width:400px;">

                    <b><label style="float:left;">Mother's Annual Income: </label></b>

                    <div class="form-details">&nbsp;<?=$SCBJA_mannualIncome;?></div>
                                                                     
                </div>

                <div class="colums-width" style="margin-bottom:5px;width:504px;">

                    <b><label style="float:left;">Cell Number: </label></b>

                    <div class="form-details">&nbsp;<?=$SCBJA_mCellnumber;?></div>

                </div>
       
  </li>
</ul>
</div>

<div id="custom-form-content">
<ul>
             <li class="marginEducation">

                <h3 class="form-title">Educational Qualifications</h3>

                          <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">

                             <tr>

                                <th>Name of Examination/<br />Degree</th>

                                <th>Name of Institution</th>

                                <th>Name of University/<br />Board</th>

                                <th>State</th>

                                <th>Year of<br />Passing</th>

                                <th>Percentage<br />Year/Semester</th>

                                <th>Mode Of<br />Study</th>

                            
                              </tr>



                      <?php
				$otherCourseShown = false;
				$countOfPGCourses = 0;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					?>
					<?php if( ${'otherCoursePGCheck_mul_'.$i} == 'Yes'){ $countOfPGCourses++; ?>

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
					    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'otherCourseMoi_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:140px"><?=${'graduationYear_mul_'.$i}?></div></td>
					   <td valign="top"><div class="formWordWrapper" style="width:70px; text-align:center"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:135px; white-space:nowrap;"><li> 
            	<div>
                    
		    <div class="spacer10 clearFix"></div>
                    <div class="form-details">
 
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos(${'otherCourseYoa_mul_'.$i},'Full Time') !== false ) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Full Time</div> 
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos(${'otherCourseYoa_mul_'.$i},'Part Time') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Part Time</div>
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos(${'otherCourseYoa_mul_'.$i},'Correspondence') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Correspondence </div>

	</div>
                </div>
		</li>

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
							  
						      </div>
						  </div>
					      </td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:135px; white-space:nowrap;">
	       <li> 
            	<div>
                    
		    <div class="spacer10 clearFix"></div>
                    <div class="form-details">
 
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos(${'otherCourseYoa_mul_'.$i},'Full Time') !== false ) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Full Time</div> 
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos(${'otherCourseYoa_mul_'.$i},'Part Time') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Part Time</div>
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos(${'otherCourseYoa_mul_'.$i},'Correspondance') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Correspondance </div>

	</div>
                </div>
		</li>
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
                                            	<div class="formWordWrapper" style="width:120px; text-align:left; float:left; white-space: nowrap">
                                            		<span>&nbsp;<?=$graduationExaminationName?></span>
                                            	</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$graduationSchool?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center"><?=$graduationBoard?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:80px; text-align:center"><?=$SCBJA_bachelorDegreeState?></div></td>
                                    <td valign="top"><div class="formWordWrapper" style="width:60px; text-align:center"><?=$graduationYear?></div></td>
                                    <td valign="top"><div style="width:115px;">
                                    	<table width="100%" cellpadding="4" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                                            <tr>
                                            	<td width="40">I</td>
                                                <td width="60" style="text-align:center"><?=$SCBJA_Sem1BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td>II</td>
                                                <td style="text-align:center"><?=$SCBJA_Sem2BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td>III</td>
                                                <td style="text-align:center"><?=$SCBJA_Sem3BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td>IV</td>
                                                <td style="text-align:center"><?=$SCBJA_Sem4BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td>V</td>
                                                <td style="text-align:center"><?=$SCBJA_Sem5BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td>VI</td>
                                                <td style="text-align:center"><?=$SCBJA_Sem6BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td>VII</td>
                                                <td style="text-align:center"><?=$SCBJA_Sem7BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td>VIII</td>
                                                <td style="text-align:center"><?=$SCBJA_Sem8BachelorDegree?></td>
                                            </tr>
                                            <tr>
                                            	<td style="font-size:11px">Aggregate</td>
                                                <td style="text-align:center"><?=$SCBJA_SemAggrBachelorDegree?></td>
                                            </tr>
                                        </table>
                                    </div></td>
                                   
                                    

<td valign="top"><div class="formWordWrapper" style="width:135px; white-space:nowrap;"><li> 
            	<div>
                    
		    <div class="spacer10 clearFix"></div>
                    <div class="form-details">
 
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_BachelorDegreemodeofStudy,'Full Time') !== false ) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Full Time</div> 
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_BachelorDegreemodeofStudy,'Part Time') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Part Time</div>
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_BachelorDegreemodeofStudy,'Correspondence') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> Correspondence </div>

	</div>
                </div>
            </td>


                                </tr>  


                         <tr>

                              <td valign="top"><div class="formWordWrapper" style="width:80px;">&nbsp;<?=$class12ExaminationName?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center;">&nbsp;<?=$class12School?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center">&nbsp;<?=$class12Board?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:110px">&nbsp;<?=$SCBJA_class12?></div></td>

                             <td valign="top"><div class="formWordWrapper" style="width:110px">&nbsp;<?=$class12Year?></div></td>


                              <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$class12Percentage?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:135px; white-space:nowrap;"><li> 
            	<div>
                    
		    <div class="spacer10 clearFix"></div>
                    <div class="form-details">
 
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_mode12,'CBSE') !== false ) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> CBSE</div> 
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_mode12,'ICSE') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> ICSE</div>
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_mode12,'Other States') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> State Board</div>

	</div>
                </div>
		</li>
            </td>

                         </tr>



           
                        <tr>

                              

                              <td valign="top"><div class="formWordWrapper" style="width:110px">&nbsp;<?=$class10ExaminationName?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center">&nbsp;<?=$class10School?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:110px; text-align:center">&nbsp;<?=$class10Board?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:110px">&nbsp;<?=$SCBJA_class10?></div></td>

                             <td valign="top"><div class="formWordWrapper" style="width:110px">&nbsp;<?=$class10Year?></div></td>


                              <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;<?=$class10Percentage?></div></td>

                              <td valign="top"><div class="formWordWrapper" style="width:135px; white-space:nowrap;"><li> 
            	<div>
                    
		    <div class="spacer10 clearFix"></div>
                    <div class="form-details">
 
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_mode10,'CBSE') !== false ) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> CBSE</div> 
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_mode10,'ICSE') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> ICSE</div>
<div class="clearFix"></div>
<div class="preff-cont" style="margin:0 0 5px 0"><span class="option-box"><?php if( strpos($SCBJA_mode10,'Other States') !== false) echo "<img src='/public/images/onlineforms/institutes/scbja/tick-icn.gif' border=0 />";?></span> State Board</div>

	</div>
                </div>
            </td>

                              

                          </tr>

<!-- Block to show PG course/Other courses row if it is available -->
				<?php
				$otherCourseShown = false;
				for($i=1;$i<=4;$i++){ 
					if(${'graduationExaminationName_mul_'.$i}){
					?>
					<?php  if( ${'otherCoursePGCheck_mul_'.$i} == 'No' ){ $otherCourseShown = true; ?>
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
					    
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationSchool_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationBoard_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'otherCourseMoi_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:100px"><?=${'graduationYear_mul_'.$i}?></div></td>
					    
					    <td valign="top"><div class="formWordWrapper" style="width:70px"><?=${'graduationPercentage_mul_'.$i}?></div></td>
					    <td valign="top"><div class="formWordWrapper" style="width:135px"><?=${'otherCourseYoa_mul_'.$i}?></div></td>
					</tr>
				<?php }
				    }
				} ?>
				<!-- Block End to show PG course/Other courses row -->

                                <?php if(!$otherCourseShown){ ?>
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
					  
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:60px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:140px">&nbsp;</div></td>
					  <td valign="top"><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
				      </tr>
                                <?php } ?>
                            

                     </li> </table>
			  </ul>
</div>
                
<li>
            
            <div class="formRows">
            	<div class="formRowsChild">
                	<h3 class="form-title">Aptitude Test Scores:<style="font-weight:normal"> MAT / XAT /ATMA / CAT / CMAT. Indicate all tests taken
</h3>
                    
                    <div class="clearFix spacer10"></div>
                    <ul>
                        <li>
                        	<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                            	<tr>
                                   <th style="text-align: center;">Exam Taken</th>
                                    <th style="text-align: center;">Test Date</th>
                                    <th style="text-align: center;">Percentile score</th>
                                    <th style="text-align: center;">Composite score</th>    
                                </tr>

				<?php
				    $total = 0;
				    $tests = explode(",",$SCBJA_testNames);
				    $dateTest = $percentileTest = $scoreTest = '';
				    foreach ($tests as $test){ 	
					  $total++;
					  if($test == 'MAT' || $test == 'CAT' || $test == 'XAT' || $test == 'ATMA'){
						$test = strtolower($test);
						$testdate = $test.'DateOfExaminationAdditional';
						$testperc = $test.'PercentileAdditional';
						$testscore = $test.'ScoreAdditional';
					  }
                                          if($test == 'CMAT' ){
						$test = strtolower($test);
						$testdate = $test.'DateOfExaminationAdditional';
						$testperc = $test.'RankAdditional';
						$testscore = $test.'ScoreAdditional';
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




                            </table>
                    	</li>
                	</ul>
            	
            </div>
</li>
            

<li>

            	<h3 class="form-title">GDPI Preference location:</h3>

                 <div class="form-details" style="float:left;"><?php if(!empty($preferredGDPILocation)) {echo $gdpiLocation;} ?></div>
                
<br/>
     
</li>
<?php 
        if(!empty($SCBJA_Sem1BachelorDegreeR))
           $arr[0]=$SCBJA_Sem1BachelorDegreeR;
	else
	   $arr[0]='0';
	if(!empty($SCBJA_Sem2BachelorDegreeR))
           $arr[1]=$SCBJA_Sem2BachelorDegreeR;
	else
	    $arr[1]='0';
	if(!empty($SCBJA_Sem3BachelorDegreeR))
           $arr[2]=$SCBJA_Sem3BachelorDegreeR;
	else
	   $arr[2]='0';
	if(!empty($SCBJA_Sem4BachelorDegreeR))
          $arr[3]=$SCBJA_Sem4BachelorDegreeR;
	else
	    $arr[3]='0';
	if(!empty($SCBJA_Sem5BachelorDegreeR))
           $arr[4]=$SCBJA_Sem5BachelorDegreeR;
	else
	    $arr[4]='0';
	if(!empty($SCBJA_Sem6BachelorDegreeR))
           $arr[5]=$SCBJA_Sem6BachelorDegreeR;
	else
	    $arr[5]='0';
	if(!empty($SCBJA_Sem7BachelorDegreeR))
           $arr[6]=$SCBJA_Sem7BachelorDegreeR;
	else
	  $arr[6]='0';
	if(!empty($SCBJA_Sem8BachelorDegreeR))
           $arr[7]=$SCBJA_Sem8BachelorDegreeR;
	else
	   $arr[7]='0';

	   
	for($i=0;$i<=7;$i++){
        
	     if( $arr[$i]!='0'){
		if(!empty($seventh))
		   $eigth= $arr[$i];
		if(!empty($sixth) && empty($eigth))
		   $seventh= $arr[$i];
		if(!empty($fifth) && empty($seventh))
		   $sixth= $arr[$i];
		if(!empty($fourth) && empty($sixth))
		   $fifth= $arr[$i];
		if(!empty($third) && empty($fifth))
		   $fourth= $arr[$i];
		if(!empty($second) && empty($fourth))
		   $third= $arr[$i];
		if(!empty($first) && empty($third)){
		    $second= $arr[$i];
	}
		if(empty($first)){
			$first=$arr[$i];
		}
	    }
	}
	
	 ?>
	 
<li>
                <div class="formRows">
            	<div class="formRowsChild">
                	<h3 div class="form-title">Indicate the subjects that you had to repeat in graduation:</div></h3>
                           <ul>
                             <li>
                        	<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                            	<tr>
                                    <th style="text-align:center;">S.No</th>
                                    <th Colspan="5" style="text-align:center;">Subject Repeated</th>
                                    <th style="text-align:center;">S.No</th>
                                    <th colspan="5" style="text-align:center;">Subject Repeated</th>

                                 </tr>


                                 <tr>
                                               <td valign="top" width="80" ><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "1";?></div></td>
						<td valign="top" colspan="5" width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $first;?></div></td>
						

						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "5";?></div></td>
						<td valign="top" colspan="5"width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $fifth;?></div></td>

                                 </tr>


                                  <tr>
                                               <td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "2";?></div></td>
						<td valign="top" colspan="5"width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $second;?></div></td>
						

						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "6";?></div></td>
						<td valign="top" colspan="5"width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $sixth;?></div></td>

                                 </tr>



                                  <tr>
                                               <td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "3";?></div></td>
						<td valign="top" colspan="5"width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $third;?></div></td>
						

						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "7";?></div></td>
						<td valign="top" colspan="5"width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $seventh;?></div></td>

                                  </tr>


                                   <tr>
                                               <td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "4";?></div></td>
						<td valign="top" colspan="5"width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $fourth;?></div></td>
						

						<td valign="top" width="80"><div class="formWordWrapper" style="width:80px; text-align:center"><?php echo "8";?></div></td>
						<td valign="top" colspan="5"width="80"><div class="formWordWrapper" style="width:100%;  text-align:center"><?php echo $eigth;?></div></td>

                                  </tr>

                  </table>
                </li>
            </ul>
       </div>
       </div>
 </li>


      
<li style="margin-bottom:10px">

       <h3 class="form-title" >Is there any break in your studies since SSLC ?</h3>
       <?php echo $SCBJA_break ?>

 <?php if($SCBJA_break=="Yes"){ ?>
       <h3 class="form-title" style="font-weight:normal;" >If yes, give reasons </h3>

        
        <?php  echo $SCBJA_breakreason;} ?>
       

</li>




<li style="margin-bottom:10px">
       

          <h3 class="form-title" style="font-weight:normal;"> WORK EXPERIENCE:(Include full time employment only)</h3>
                    <ul>
                        <li>
                        	<table width="100%" cellpadding="6" border="1" bordercolor="#000000" cellspacing="0" class="educationTable2">
                            	<tr>
                                    <th style="text-align:center;">Company</th>
                                    <th style="text-align:center;">Designation</th>
                                    <th style="text-align:center;">From</th>
                                    <th style="text-align:center;">To</th>
                                    <th style="text-align:center;">No. of months</th>
                                    <th style="text-align:center;">Monthly Remuneration</th>
                                </tr>


			    <?php 
				  $workExGiven = false;
				  $total = 0;
				  for($i=0; $i<3; $i++){

				      $mulSuffix = $i>0?'_mul_'.$i:'';
				      $mulSuffix = $i>0?'_mul_'.$i:'';
				      $otherSuffix = '_mul_'.$i;
				      $workCompany = ${'weCompanyName'.$mulSuffix};
				      $workCompaniesExpFrom = ${'weFrom'.$mulSuffix};
				      $workCompaniesExpTo= trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
				      $designation = ${'weDesignation'.$mulSuffix};
                                      $workExpInMonthsValue=${'workExpInMonths'.$otherSuffix};
                                      $grossSalaryFieldValue=${'annualSalarySCBJA'.$otherSuffix};
                                      

				      
				      
				      if($workCompany || $designation){$workExGiven = true;$total++;
				      ?>

					    <tr>
						<td valign="top" width="100"><div class="formWordWrapper" style="width:100%;text-align:center;"><?php echo $workCompany; ?></div></td>
                                                <td valign="top" width="100"><div class="formWordWrapper" style="width:100%;text-align:center;"><?php echo $designation ; ?></div></td>

						<td valign="top" width="70"><div class="formWordWrapper" style="width:100%; text-align:center;"><?php echo $workCompaniesExpFrom;?></div></td>
						<td valign="top" width="70"><div class="formWordWrapper" style="width:100%;  text-align:center;"><?php echo $workCompaniesExpTo;?></div></td>
						
						
        <td valign="top" width="40"><div class="formWordWrapper" style="width:100%;text-align:center"><?php echo $workExpInMonthsValue; ?></div></td>
         <td valign="top" width="60"><div class="formWordWrapper" style="width:100%;text-align:center"><?php echo $grossSalaryFieldValue; ?></div></td>

					    </tr>

         <?php }} ?>


			    
	        
                                
                            </table>
                    	</li>
                   </ul>
            </li>
            
  <li>

		<h3 class="form-title">YOUR PARTICIPATION IN EXTRA CURRICULAR ACTIVITIES: <span style="font-weight:normal;">college level onwards</span> </h3>
                  

             <li>

                <div class="colums-width" style="width:350px;">

                    <label><b>a. Sports and games / NSS / NCC:</b>
</label>

                    <div class="form-details">&nbsp;<?=$SCBJA_Games; ?></div>

                </div>
            </li>
            <li>



                <div class="colums-width" style="width:350px;">

                    <label><b>b. Debates / Quiz:</b>
</label>

                    <div class="form-details">&nbsp;<?=$SCBJA_Debate; ?></div>

                </div>

            </li>

            <li>

                <label><b>c. Any other:</b>
</label>

                <div class="form-details">&nbsp;<?=$SCBJA_Others ; ?></div>

            </li>

 </li>




   <li style="margin-bottom:10px">

       <h3 class="form-title" >Write in about 100 words what you expect from this course:<style="font-weight:normal">
        </h3><?php echo $SCBJA_essay ?>

   </li>

  

  <li>

       <h3 class="form-title" >How do you propose to finance your studies?<style="font-weight:normal">

       </h3><?php echo $SCBJA_finance ?>

</li>



<li>

    <h3 class="form-title">NAME, DESIGNATION, ADDRESS AND CONTACT NO. OF TWO REFEREES:

        <strong style="font-weight:normal">If you are employed for more than one year submit reference letter from a) your immediate         supervisor and b) employer.<BR />
In other cases, reference letters should be from your college teachers.<br />
To be submitted in sealed envelopes.</strong></h3>



		<div class="colums-width">

		<strong>1st Reference:</strong>

		<div class="clearFix spacer10"></div>

                    <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Name : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Ref1?></div>
		    </div>
		    <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Designation : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Desg1?></div>
		    </div>
		    <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Address : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Add1?></div>
		    </div>
		    <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Contact no : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Cont1?></div>
		    </div>

                </div>


            	<strong>2nd Reference:</strong>

		<div class="spacer10"></div>

                <div class="colums-width">
		    <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Name : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Ref2?></div>
		    </div>
		    <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Designation : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Desg2?></div>
		    </div>
                     <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Address : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Add2?></div>
		     </div>
		    <div class="colums-width">
			<label style="float:left; margin-bottom:5px;">Contact no : </label> <div class="form-details" style="float:left;">&nbsp;<?=$SCBJA_Cont2?></div>
		    </div>


               </div>
      
</li>




<li style="margin-bottom:10px">

       <h3 class="form-title" >How did you come to know about SJCBA?<style="font-weight:normal">

        </h3><?php echo $SCBJA_know ?>

</li>


                  		
 <li>

                  <h3 class="form-title">DECLARATION</h3>

                        <div class="spacer15 clearFix"></div>

                        <div>
                             a)<h> By the Applicant</h><br />
                                   I have read the rules and regulations governing the PGDM Course and hereby undertake to follow them
                                   and any other the college may enact, and assure that I will participate effectively in the Course.<br />
                                   I certify that the particulars given by me in this application form are true to the best of my knowledge and
                                   belief.<br/></div>
           
                        <div class="clearFix spacer25"></div>
                        

                               <div class="clearFix spacer10"></div>
                           
                                
                                  <div class="formColumns2" style="width:250px;">

                                    <label style="width:auto; padding-top:0">Date :</label>

                                     <span>&nbsp;
					<?php
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
						}
					?>
				    </span>

                                </div>

                            

                            

                            <div style="float:right; width:500px; text-align:right">

                                <p>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></p>

                                <div>Signature of the Applicant</div>

                             </div>

                        

 </li>

	    
                <li>

                        <div class="spacer15 clearFix"></div>

                        <div>



                       <p>b) <h>By the Parent / Guardian</h>
                                 I assure the College authorities that my son / daughter / ward will abide by the rules and regulations of the
                                 College and ensure that he / she will participate effectively in the Course.
                       </p></div>

             
                      

                        <div class="clearFix spacer25"></div>

		                                    
                                <div class="clearFix spacer10"></div>
                          
                                <div class="formColumns2" style="width:250px;">

                                    <label style="width:auto; padding-top:0">Date :</label>
                       <span>&nbsp;
					<?php
					      if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
						      echo date("d/m/Y", strtotime($paymentDetails['date']));
						}
					?>
				    </span>

                                </div>  

           
                                       
                            <div style="float:right; width:500px; text-align:right">

                                <p>&nbsp;<?=$fatherName?></p>

                                <div>Signature of Parent/Guardian</div>

                             </div>
                          
                        

                    </li>

  <div class="clearFix"></div>

  <li style="margin-bottom:10px">

       <h3 class="form-title" >Documents to be attached with the application<style="font-weight:normal"></h3>


     <li>Applicants are requested to submit their filled in Application Form along with attested copies of the below
         mentioned documents:<br />
            1.Standard X mark sheet<br />
            2.Standard XII mark sheet<br />
            3.Bachelor's degree mark sheets of all years (available as on date)<br />
            4.Degree Certificate (Provisional or Final)<br />
            5.Post-graduate degree mark sheets (if any)<br />
            6.Work experience certificates and latest pay slip (if any)<br />
            7.Entrance exam score card, if available.<br />
            <br/>

     </li>

  </li>



<li style="margin-bottom:10px">

       <h3 class="form-title" ><u>Important Instructions<style="font-weight:normal">
</u></h3>
<?php echo "Original certificates must be produced at the time of interview."
?>

</li>




