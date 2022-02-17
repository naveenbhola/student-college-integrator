<style>
@media print
 {
.breakings {page-break-after: left}

 }
</style>

<?php $valuePrefix = '&nbsp;'; ?>
<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />

<!--Indira Form Preview Starts here--> 
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <div class="float_L" style="width:200px"><img src="/public/images/onlineforms/institutes/iba/logo2.gif" alt="" /></div>
		<div class="" style="margin-left:50px; float:left">
		    <p style="font-size:18px;line-height:23px;">
				    INDUS BUSINESS ACADEMY<br />
				    (Formerly Indian Business Academy) <br/>
				    Bangalore Campus<br />
						Approved by AICTE , Ministry of HRD, Govt. of India 
		    </p>
		</div>
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">APPLICATION FORM FOR PGDM/MBA 2015-2017</div>  
        </div>
        
        <div class="user-pic-box"><?php if($profileImage) { ?>
			    <img src="<?php echo $profileImage; ?>" />
			<?php } ?></div>
    </div>
    <div class="spacer15 clearFix"></div>
    <div id="custom-form-content">
    	<ul>
        <?php if( $instituteSpecId!=''){?>
        <li>
        <label>Serial No. : </label>
                        <div class="form-details"><?php echo $instituteSpecId; ?></div>
        </li>
        <?php }?>

	    <li>
            	<div class="colums-width" style="width:300px;">
                    <label>Course applied for:</label>
                    <div class="form-details"><?=$IBA_course;?></div>
                </div>
            </li>
	    <li>
            	<div class="colums-width" style="width:300px;">
                    <label>Salutation:</label>
                    <div class="form-details"><?=$salutationIBA;?></div>
                </div>
            </li>
            <li>
            	<div class="colums-width" style="width:300px;">
                    <label>First Name :</label>
                    <div class="form-details"><?=$firstName;?></div>
                </div>
            	<div class="colums-width" style="width:250px;">
                    <label>Middle Name :</label>
                    <div class="form-details"><?=$middleName;?></div>
                </div>
            	
                <div class="colums-width" style="width:300px;">
                    <label>Last Name :</label>
                    <div class="form-details"><?=$lastName; ?></div>
                </div>
            </li>
	
	   <li> 
            	<div class="colums-width">
                    <label>Email : </label>
                    <div class="form-details"><?php echo $email;?></div>
                </div>
				
				
		<div class="colums-width">
                    <label>Date of Birth : </label>
                    <div class="form-details"><?php echo str_replace("/","-",$dateOfBirth);?></div>
                </div>
				
				
            </li>
			
			<li>
			<div class="colums-width">
                    <label>Alternate Email : </label>
                    <div class="form-details"><?php echo $altEmail;?></div>
                </div>
				</li>

           <li> 
            	<div class="colums-width" style="width:300px;">
                    <label>Age : </label>
                    <div class="form-details"><?php echo $ageIBA;?></div>
                </div>
		<div class="colums-width" style="width:250px;">
		    <label>Gender : </label>
		    <div class="form-details"><?php if($gender=='MALE'){ echo 'M';}if($gender=='FEMALE'){ echo 'F';}?></div>
		</div>
		<div class="colums-width" style="width:300px;">
                    <label>Marital Status : </label>
                    <div class="form-details"><?=$maritalStatus?></div>
                </div>
            </li>
<!---
			<?php 

	   $testsArray = explode(",",$testNamesIBA);

	   if(in_array("CAT",$testsArray)){ ?>
	    <li>
			<h3 class="form-title">Aptitude Test Appeared</h3>
			<div class="clearFix spacer5"></div>
			<strong>CAT 2013:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$catRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$catDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$catScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$catPercentileAdditional;?></div>
			</div>
	   </li>
	<?php } 
	if(in_array("XAT",$testsArray)){ ?>    
	    <li>
			<div class="clearFix spacer5"></div>
			<strong>XAT 2014:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$xatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$xatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$xatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$xatPercentileAdditional;?></div>
			</div>
	   </li>
	<?php } 
	if(in_array("MAT",$testsArray)){ ?>    
	    <li>
			<div class="clearFix spacer5"></div>
			<strong>MAT (Sept. 2013, Dec 2013, Feb 2014 & May 2014):</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$matRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$matDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$matScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$matPercentileAdditional;?></div>
			</div>
	   </li>
 <?php } 
	if(in_array("ATMA",$testsArray)){ ?>   
	    <li>
			<div class="clearFix spacer5"></div>
			<strong>ATMA 2013 / 14:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$atmaRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$atmaDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$atmaScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$atmaPercentileAdditional;?></div>
			</div>
	   </li>
 <?php }
	if(in_array("GMAT",$testsArray)){ ?>   
	   <li>
			<div class="clearFix spacer5"></div>
			<strong>GMAT 2013 / 14:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$gmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$gmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$gmatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gmatPercentileAdditional;?></div>
			</div>
	   </li>
<?php }
	if(in_array("CMAT",$testsArray)){ ?>   
	   <li>
			<div class="clearFix spacer5"></div>
			<strong>CMAT 2013 / 2014:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$cmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$cmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$cmatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$cmatPercentileAdditionalIBA;?></div>
			</div>
	   </li>
  
	  <?php } ?>
	-->  
	   <li>
	    <h3 class="form-title">Preferred GD & PI Centres</strong></h3>
	    <div class="colums-width">
	    <label>First Choice :</label>
		 <div class="form-details">&nbsp;<?php echo $gdpiLocation;?><br></div>
	   </div>

	    <div class="colums-width">
	    <label>Second Choice :</label>
		 <div class="form-details">&nbsp;<?php echo $pref2IBA;?><br></div>
	   </div>
	   </li>

	   <li>
	    <h3 class="form-title">Category</strong></h3>

            <div class="formColumns3" style="width:100px;float:left;">
		    
		    <div class="previewFieldBox" style="width:30px;float:left;">
		        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="largeBoxTable">
		            <tr>
		                <td style="border:1px solid #000">&nbsp;<?php if($categoryIBA=='GEN') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
		            </tr>
		        </table>
		    </div>
		    <span style="padding:5px 0 0 7px; float:left">General</span>
		</div>
		<div class="formColumns3" style="width:100px;float:left;">
                            <div class="previewFieldBox" style="width:30px;float:left;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td style="border:1px solid #000">&nbsp;<?php if($categoryIBA=='SC') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
		    <span style="padding:5px 0 0 7px; float:left">SC</span>
                        </div>
	<div class="formColumns3" style="width:100px;float:left;">
                            <div class="previewFieldBox" style="width:30px;float:left;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td style="border:1px solid #000">&nbsp;<?php if($categoryIBA=='ST') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
			    <span style="padding:5px 0 0 7px; float:left">ST</span>
                        </div>
		<div class="formColumns3" style="width:100px;float:left;">
                            <div class="previewFieldBox" style="width:30px;float:left;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td style="border:1px solid #000">&nbsp;<?php if($categoryIBA=='OBC') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
			    <span style="padding:5px 0 0 7px; float:left">OBC</span>
                        </div>
		<div class="formColumns3" style="width:100px;float:left;">
                            <div class="previewFieldBox" style="width:30px;float:left;">
                                <table width="100%" cellpadding="4" cellspacing="0" border="1" bordercolor="#000000" class="largeBoxTable">
                                    <tr>
                                        <td style="border:1px solid #000">&nbsp;<?php if($categoryIBA=='PH') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
                                    </tr>
                                </table>
                            </div>
			    <span style="padding:5px 0 0 7px; float:left">PH</span>
                        </div>

	   </li>
	   
	   <h3 class="form-title">Entrance Exam Details</h3>
	   <?php 
	$testsArray = explode(",",$testNamesIBA);
	
	if(in_array("CAT",$testsArray)){ ?>
	    <li>
			
			<div class="clearFix spacer5"></div>
			<strong>CAT 2013:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$catRollNumberAdditional;?></div>
			</div>
<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$catScoreAdditional;?></div>
			</div>
			
			
	   </li>
	   <li>
			

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$catPercentileAdditional;?></div>
			</div>
	   </li>
	<?php } 
	if(in_array("XAT",$testsArray)){ ?>    
	    <li>
			<div class="clearFix spacer5"></div>
			<strong>XAT 2014:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$xatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$xatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$xatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$xatPercentileAdditional;?></div>
			</div>
	   </li>
	<?php } 
	if(in_array("MAT",$testsArray)){ ?>    
	    <li>
			<div class="clearFix spacer5"></div>
			<strong>MAT (Sept. 2013, Dec 2013, Feb 2014 & May 2014):</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$matRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$matDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$matScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$matPercentileAdditional;?></div>
			</div>
	   </li>
 <?php } 
	if(in_array("ATMA",$testsArray)){ ?>   
	    <li>
			<div class="clearFix spacer5"></div>
			<strong>ATMA 2013 / 14:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$atmaRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$atmaDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$atmaScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$atmaPercentileAdditional;?></div>
			</div>
	   </li>
 <?php }
	if(in_array("GMAT",$testsArray)){ ?>   
	   <li>
			<div class="clearFix spacer5"></div>
			<strong>GMAT 2013 / 14:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$gmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$gmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$gmatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gmatPercentileAdditional;?></div>
			</div>
	   </li>
<?php }
	if(in_array("CMAT",$testsArray)){ ?>   
	   <li>
			<div class="clearFix spacer5"></div>
			<strong>CMAT 2013 / 2014:</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$cmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$cmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
	   <li>
			<div class="colums-width">
			    <label>Score:</label>
			    <div class="form-details">&nbsp;<?=$cmatScoreAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Percentile:</label>
			    <div class="form-details">&nbsp;<?=$cmatPercentileAdditionalIBA;?></div>
			</div>
	   </li>
	    <li>
			<div class="colums-width">
			    <label>Rank:</label>
			    <div class="form-details">&nbsp;<?=$cmatRankAdditional;?></div>
			</div>
			</li>
	   
<?php } ?> 
	   
	   
	   
	   
		<?php 
						for($j=4;$j>=1;$j--):?>
					<?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
					<?php if(${'otherCoursePGCheck_mul_'.$j}=='1'):?>
                        <?php $pgExaminationName = ${'graduationExaminationName_mul_'.$j};?>
			<?php $pgSchoolName = ${'graduationSchool_mul_'.$j};?>
                        <?php $pgSpecializationName  = ${'otherCourseSubjects_mul_'.$j};?>
                        <?php $pgBoardName = ${'graduationBoard_mul_'.$j};?>
			<?php $pgMOIName = ${'otherCourseMoi_mul_'.$j};?>
			<?php $pgYoaName = ${'otherCourseYoa_mul_'.$j};?>
			<?php $pgYocName = ${'graduationYear_mul_'.$j};?>
			<?php $pgPercentage = ${'graduationPercentage_mul_'.$j};?>
			<?php $pgDivision = ${'otherCourseDivision_mul_'.$j};?>
					<?php break;endif;endif;endfor; ?>


		<li>
			<h3 class="form-title">Academic Background</strong></h3>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%; border-collapse:collapse">
					<tr>
						<td></td>
                    	<td width="30%"><strong>10th / SSLC</strong></td>
			<td width="30%"><strong>12th / PUC</strong></td>
                        <td><strong>Graduation</strong></td>
                        <td><strong>Post Graduation</strong></td>
                    </tr>
		   <tr>
			<td><strong>Name of the Degree / Course</strong></td>
                    	<td><?php echo $class10ExaminationName;?></td>
			<td><?=$class12ExaminationName?></td>
                        <td><?php echo $graduationExaminationName;?></td>
                        <td><?php echo $pgExaminationName;?></td>
                    </tr>
		    <tr>
			<td><strong>Name of the School / College</strong></td>
                    	<td><?php echo $class10School;?></td>
			<td><?php echo $class12School;?></td>
                        <td><?php echo $graduationSchool;?></td>
                        <td><?php echo $pgSchoolName;?></td>
                    </tr>
		    <tr>
			<td><strong>Board / University</strong></td>
                        <td><?php echo $class10Board;?></td>
			<td><?php echo $class12Board;?></td>
                        <td><?php echo $graduationBoard;?></td>
                        <td><?php echo $pgBoardName;?></td>
                    </tr>
		    <tr>
			<td><strong>Specialization (if any)</strong></td>
                        <td>N/A</td>
			<td>N/A</td>
                        <td><?php echo $gradYearSubjectsIBA;?></td>
                        <td><?php echo $pgSpecializationName;?></td>
                    </tr>
		    <tr>
			<td><strong>Medium of Instruction (like Hindi, English etc.)</strong></td>
                        <td><?php echo $class10mediumIBA;?></td>
			<td><?php echo $class12mediumIBA;?></td>
                        <td><?php echo $gradYearMOIIBA;?></td>
                        <td><?php echo $pgMOIName;?></td>
                    </tr>
		    <tr>
			<td><strong>Year of Admission</strong></td>
                        <td><?php echo $class10YOAIBA;?></td>
			<td><?php echo $class12YOAIBA;?></td>
                        <td><?php echo $gradYearYOAIBA;?></td>
                        <td><?php echo $pgYoaName;?></td>
                    </tr>
		    <tr>
			<td><strong>Year of Completion</strong></td>
                        <td><?php echo $class10Year;?></td>
			<td><?php echo $class12Year;?></td>
                        <td><?php echo $graduationYear;?></td>
                        <td><?php echo $pgYocName;?></td>
                    </tr>
		    <tr>
			<td><strong>Aggregate % / C.G.P.A</strong></td>
                        <td><?php echo $class10Percentage;?></td>
			<td><?php echo $class12Percentage;?></td>
                        <td><?php echo $graduationPercentage;?></td>
                        <td><?php echo $pgPercentage;?></td>
                    </tr>	
		    <tr>
			<td><strong>Division / Class</strong></td>
                        <td><?php echo $class10DivisionIBA;?></td>
			<td><?php echo $class12DivisionIBA;?></td>
                        <td><?php echo $gradYearDivisionIBA;?></td>
                        <td><?php echo $pgDivision;?></td>
                    </tr>
		</table>
		</li>
	    <li>
		<h3 class="form-title">Statement Of Purpose</h3>
		    <div class="form-details">&nbsp;<?=$shortEssayIBA?></div>
	    </li>
		<li>
			<h3 class="form-title">Work Experience</h3>
			<div class="colums-width">
			    <label>Total Work Experience in Months:</label>
			    <div class="form-details">&nbsp;<?=$totalWorkExpIBA_mul_0;?></div>
			</div>
			<div class="clearFix spacer10"></div>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%; border-collapse:collapse">
				<tr>
					
					<td><strong>Organisation</strong></td>
					<td><strong>Designation</strong></td>
					<td><strong>From Date</strong></td>
					<td><strong>To Date</strong></td>
					<td><strong>Duration in Months</strong></td>
					<td><strong>Annual Salary (in lakhs)</strong></td>
				</tr>
				
				<?php 
					for($i=3;$i>=1;$i--):?>
					<?php if(!empty(${'weCompanyName_mul_'.$i})):?>
				<tr>
					
					<td><?php echo ${'weCompanyName_mul_'.$i};?></td>
					<td><?php echo ${'weDesignation_mul_'.$i};?></td>
					<td><?php if(!empty(${'weFrom_mul_'.$i})) {echo date('d F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></td>
					<td><?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('d F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo date('d F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));}?></td>
					<td><?php echo ${'workExpInMonths_mul_'.$i};?></td>
					<td><?php echo ${'annualSalaryIBA_mul_'.$i};?></td>

				</tr>
				<?php endif;endfor;?>
				<tr>
					
					<td><?=$weCompanyName?></td>
					<td><?=$weDesignation?></td>
					<td><?php if(!empty($weFrom)) {echo date('d F Y',strtotime(getStandardDate($weFrom)));}?></td>
					<td><?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('d F Y',strtotime(getStandardDate($weTill)));}} else {echo date('d F Y',strtotime(getStandardDate($weTill)));}?></td>
					<td><?php echo $workExpInMonths_mul_0;?></td>
					<td><?php echo $annualSalaryIBA_mul_0;?></td>

				</tr>
			</table>
		</li>
		<li>
		<h3 class="form-title">Family Background</strong></h3>
		<div class="clearFix spacer5"></div>
		<p>Family Annual Income :</p>
		<div class="clearFix spacer10"></div>
		<div class="formColumns3" style="width:250px;float:left;">
		<div class="previewFieldBox" style="width:30px;float:left;">
			    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="largeBoxTable">
				<tr>
				    <td style="border:1px solid #000">&nbsp;<?php if($faiIBA=='Less than 3 lakhs') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
				</tr>
			    </table>
			</div>
			<span style="padding:5px 0 0 7px; float:left">Less than 3,00,000 p.a.</span>
		</div>
		<div class="formColumns3" style="width:250px;float:left;">
			<div class="previewFieldBox" style="width:30px;float:left;">
			    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="largeBoxTable">
				<tr>
				    <td style="border:1px solid #000">&nbsp;<?php if($faiIBA=='3-5 lakhs') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
				</tr>
			    </table>
			</div>
			<span style="padding:5px 0 0 7px; float:left">3,00,000 to 5,00,000 p.a.</span>
		</div>
		<div class="formColumns3" style="width:250px;float:left;">
			<div class="previewFieldBox" style="width:30px;float:left;">
			    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="largeBoxTable">
				<tr>
				    <td style="border:1px solid #000">&nbsp;<?php if($faiIBA=='More than 5 lakhs') echo "<img src='/public/images/onlineforms/institutes/iba/tick-icn.gif' border=0 />";?></td>
				</tr>
			    </table>
			</div>
			<span style="padding:5px 0 0 7px; float:left">More than 5,00,000 p.a.</span>
		</div>
	    </li>
	    <li>
			<div class="clearFix spacer10"></div>
			<table border="1" cellpadding="10" cellspacing="0" style="border:1px solid #000;width:100%; border-collapse:collapse">
				<tr>
					
					<td></td>
					<td><strong>Name</strong></td>
					<td><strong>Designation</strong></td>
					<td><strong>Name of the Organization (if applicable)</strong></td>
					<td><strong>Contact No.</strong></td>
				</tr>
				<tr>
					
					<td>Mother</td>
					<td><?php echo $MotherName;?></td>
					<td><?php echo $MotherDesignation;?></td>
					<td><?php echo $motherWifeOrgNameIBA;?></td>
					<td><?php echo $motherNumIBA;?></td>

				</tr>
				<tr>
					
					<td>Father</td>
					<td><?php echo $fatherName;?></td>
					<td><?php echo $fatherDesignation;?></td>
					<td><?php echo $fatherHusOrgNameIBA;?></td>
					<td><?php echo $fatherNumIBA;?></td>

				</tr>
			</table>
	    </li>
	    <li>
		<h3 class="form-title">Permanent Address</h3>
		<label>Door No.:</label>
		<div style="width:829px"><span>&nbsp;<?php echo $houseNumber;
							?></span></div>
            </li>
	     <li>
                <label>Street:</label>
                <div style="width:829px"><span>&nbsp;<?php
                                                                echo $streetName;
                                                        ?></span></div>
            </li>
	    <li>
                <label>Locality(P.O.):</label>
                <div style="width:829px"><span>&nbsp;<?php 
                                                                echo $area;
                                                        ?></span></div>
            </li>

	    
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City :</label>
                    <div class="form-details"><?=$city;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State :</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN:</label>
                    <div class="form-details"><?=$pincode; ?>&nbsp;(INDIA)</div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Phone No. :</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
               	</div>
            	<div class="colums-width">
                    <label>Mobile Number :</label>
                    <div class="form-details">&nbsp;<?php if($mobileISDCode) echo $mobileISDCode.'-'; echo $mobileNumber;?></div>
                </div>
            </li>
           
            <li>
		<h3 class="form-title">Address for Communication</h3>
		<label>Door No.:</label>
		<div style="width:829px"><span>&nbsp;<?php echo $ChouseNumber;
							?></span></div>
            </li>

	    <li>
		<label>Street:</label>
		<div style="width:829px"><span>&nbsp;<?php if($CstreetName) echo $CstreetName; ?></span></div>
	    </li>
            	    
            <li>
                    <label>Locality(P.O.):</label>
		    <div style="width:829px"><span>&nbsp;<?php echo $Carea; ?></span></div>
	   </li>
	   <li>
            	<div class="colums-width" style="width:227px;">
                    <label>City :</label>
                    <div class="form-details"><?=$Ccity;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State :</label>
                    <div class="form-details"><?=$Cstate;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code :</label>
                    <div class="form-details"><?=$Cpincode; ?>&nbsp;(INDIA)</div>
                </div>
            </li>
	   <li>
                <div class="colums-width">
                    <label>Phone No.:</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
                </div>
				
				
				
				
                <div class="colums-width">
                    <label>Mobile Number :</label>
                    <div class="form-details">&nbsp;<?php if($mobileISDCode) echo $mobileISDCode.'-'; echo $mobileNumber;?></div>
                </div>
				
				
				
				
				
            </li>
			
			<li>
			
			<div class="colums-width">
                    <label>Alternate Number :</label>
                    <div class="form-details">&nbsp;<?php /* if($mobileISDCode) echo $mobileISDCode.'-'; */ echo $alterNumIBA;?></div>
                </div>
				
				</li>
			
			
<li>
		<h3 class="form-title">Source of Information on IBA</h3>
		<div class="preffCol3">
				<div class="preff-cont"><span class="option-box" style="margin-right:8px; float:left;"><?php if(strpos($cameToKnowAboutIBA,'Shiksha') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span><label style="font-weight:normal; padding-top:3px;">Shiksha.com</label> </div>
				<div class="preff-cont"> <span class="option-box" style="margin-right:8px; float:left;"><?php if(strpos($cameToKnowAboutIBA,'Friend') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span><label style="font-weight:normal; padding-top:3px;">Friend</label></div>
				<div class="preff-cont"> <span class="option-box" style="margin-right:8px; float:left;"><?php if(strpos($cameToKnowAboutIBA,'Mailer') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span><label style="font-weight:normal; padding-top:3px;">Mailer</label></div>
				<div class="clearFix"></div>
				<div class="preff-cont">
					<div style="width:150px; float:left;"> <span class="option-box" style="margin-right:8px; float:left;"><?php if(strpos($cameToKnowAboutIBA,'Newspaper') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span><label style="font-weight:normal; padding-top:3px;">Newspaper Advt.</label>
					</div>
				<div class="source-underline">&nbsp;<?php echo $newspaperSourceIBA;?></div>
				</div>
				<div class="clearFix"></div>
				<div class="preff-cont" > <div style="width:150px; float:left;"> <span class="option-box" style="margin-right:8px; float:left;"><?php if(strpos($cameToKnowAboutIBA,'Internet') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span><label style="font-weight:normal; padding-top:3px;">Internet Portal</label></div>
				<div class="source-underline">&nbsp;<?php echo $internetSourceIBA;?></div>
				</div>
				<div class="clearFix"></div>
				<div class="preff-cont"> <div style="width:150px; float:left;"><span class="option-box" style="margin-right:8px; float:left;"><?php if(strpos($cameToKnowAboutIBA,'Alumni') !== false){ echo "<img src='/public/images/onlineforms/institutes/amrita/tick-icn.gif' border=0 />";} ?></span><label style="font-weight:normal; padding-top:3px;">Alumni/Student</label>
				</div>
				<div class="source-underline">&nbsp;<?php echo $alumniSourceIBA;?></div>
				</div>

	   </li>
            <li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I hereby declare that I have provided correct, complete and accurate information in this application form. I have carefully read the Information and Guidelines to Applicants. I understand and agree that any misrepresentation, false information or omission of facts in my application will lead to the denial of admission, cancellation of admission or expulsion from the PGDM/MBA Programme offered at IBA at any stage.
                <div class="spacer15 clearFix"></div>
                <label>Place:</label>
                <div class="form-details"><?php if(isset($firstName) && $firstName!='') {echo $Ccity;} ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Date:</label>
                <div class="form-details"><?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?></div>
                
                <div class="spacer15 clearFix"></div>
                <label>Signature of the Candidate:</label>
                <div class="form-details"><?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></div>
            </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
<!--Indira Form Preview Ends here-->
