<style>
@media print
 {
.breakings {page-break-after: left}

 }
.formColumns2{float:left}
.checkOptionsBox{width:200px; float:left}
.checkOptionsBox label{padding:6px 0 0 10px}
.checkOptionsBox span{border-bottom:2px dotted #000000; display:block; width:100%; float:left; padding-bottom:8px}
.educationTable{border: 1px solid #000000; border-collapse: collapse;}
.educationTable tr th{font-weight:normal}
.formColumns2{float:left}
.boxTable{border: 1px solid #000000; border-collapse: collapse;}
.boxTable tr td{height: 32px; width:32px; padding-bottom: 0; padding-top: 0;  text-align: center;}
</style>

<?php $valuePrefix = '&nbsp;'; ?>
<!--XIME Form Preview Starts here-->
   	
<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <div class="float_L"><img src="/public/images/onlineforms/institutes/xime/logo.gif" alt="" /></div>
		<div class="">
		    <p>
		    <strong>XAVIER INSTITUTE OF MANAGEMENT & ENTREPRENEURSHIP</strong><br />
				    <span>Electronics City, Phase-II, Hosur Road, Bangalore - 560 100, Tel : 080 - 2852 8597 / 98</span><br />
				    <span>KINFRA Hi-Tech Park, HMT Post, Kalamassery, Kochi - 683 503, Tel : 0484 - 2972972/73</span></br>
				    <b>Fax: 080-2852 0809 E-mail : admissions@xime.org Website: www.xime.org</b>
				   
		    </p>
		</div>
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">APPLICATION FOR TWO-YEAR PGDM PROGRAMME (2015-17)</div>  
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
        <label>Form Id: </label>
                        <div class="form-details"><?php echo $instituteSpecId; ?></div>
        </li>
        <?php }?>
	<?php if($appliedLocation=='bangalore'){
		$appLoc = 'Bangalore';
	}else if($appliedLocation=='kochi'){
		$appLoc = 'Kochi';
	}else{
		$appLoc = 'Bangalore and Kochi';
	}
	?>
	<!---   <li>
		<div class="colums-width">
                    <label>Applied For: </label>
                    <div class="form-details"><?=$appLoc?></div>
                </div>
	    </li>
         -->
	 
	 
	  <li>
            	<h3 class="form-title">Applied For:</h3>
		
		
		
		<div style="border:1px solid #ccc; ">
		<div class="checkOptionsBox" style="border-right:1px solid #ccc;"><label style="padding:10px">My Preference</label></div>
		<div class="checkOptionsBox" style="border-right:1px solid #ccc;">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="0">
			    <tr>
				<td>&nbsp;<?php if($appliedLocation!='' && strpos($appliedLocation,'bangalore')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' style='margin:11px 0 0 10px;' border=0 />"; ?></td>
			   </tr>
			</table>
		    </div>
		    <label style="padding:10px 20px">Bangalore</label>
		</div>
		<div class="checkOptionsBox" style="border-right:1px solid #ccc;">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="0">
			    <tr>
				<td>&nbsp;<?php if($appliedLocation!='' && strpos($appliedLocation,'kochi')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' style='margin:11px 0 0 10px;' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label style="padding:10px 20px">Kochi</label>
		</div>
		
		<div class="checkOptionsBox">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="0">
			    <tr>
				<td>&nbsp;<?php if($appliedLocation!='' && strpos($appliedLocation,'bothBK')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' style='margin:11px 0 0 10px;' border=0/>"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label style="padding:10px 20px">Either</label>
		</div>
		
		<div class="clearFix"></div>
		</div>
	    </li>
	    
	   
	 
	 
	  <h3 class="form-title">Entrance Exam Details</h3>
	   <?php 
	$testsArray = explode(",",$XIMEKOCHI_testNames);
	
	if(in_array("CAT",$testsArray)){ ?>
	    <li>
			
			<div class="clearFix spacer5"></div>
			<strong>CAT 2014-15:</strong>
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
			<strong>XAT 2014-15:</strong>
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
			<strong>MAT 2014-15:</strong>
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
	
	if(in_array("CMAT",$testsArray)){ ?>   
	   <li>
			<div class="clearFix spacer5"></div>
			<strong>CMAT 2014-15:</strong>
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
			    <div class="form-details">&nbsp;<?=$cmatPercentileXIME;?></div>
			</div>
	   </li>
	   
<?php } ?> 
	   	

            <li>
		<h3 class="form-title">Preferred GD/Interview Centre: </h3>
		<div class="colums-width">
			<div class="form-details">
			<?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=''){ ?>
			    <?php if($preferredGDPILocation=='278') echo "Bangalore";?>
			    <?php if($preferredGDPILocation=='64') echo "Chennai";?>
			    <?php if($preferredGDPILocation=='127') echo "Kochi";?>
			    <?php if($preferredGDPILocation=='174') echo "Pune";?>
			    <?php if($preferredGDPILocation=='702') echo "Hyderabad";?>
			    <?php if($preferredGDPILocation=='130') echo "Kolkata";?>
			 <?php }?>
			</div>
                </div>
            </li>
	    
	    <li>
            	<h3 class="form-title">Personal Information</h3>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
		<div>
            	<label>Name (in capital letters as in Degree Certificate /Mark Sheet): </label>
                <div class="form-details"><?php echo $userName; ?></div>
		</div>
	    </li>

	    <li>  
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
            	<div class="colums-width">
                    <label>Age as on 1/7/2015: </label>
                    <div class="form-details"><?=$ageXIME;?></div>
                </div>
           </li> 

           <li> 
            	<div class="colums-width">
                    <label>Gender: </label>
                    <div class="form-details"><?=$gender?></div>
                </div>
		<div class="colums-width">
		    <label>Marital status: </label>
		    <div class="form-details">
                        <?php if(isset($maritalStatus) && $maritalStatus!=''){ ?>
                                <?php if($maritalStatus=='SINGLE') echo "UNMARRIED"; else echo "MARRIED";?>
                        <?php } ?>
		    </div>
		</div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>Religion: </label>
                    <div class="form-details"><?=$religion?></div>
                </div>
		<div class="colums-width">
		    <label>Mother tongue: </label>
		    <div class="form-details"><?php echo $motherTongueXIME; ?></div>
		</div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>Category: </label>
                    <div class="form-details">
                        <?php if(isset($applicationCategory) && $applicationCategory!=''){ ?>
                            <?php if($applicationCategory=="GENERAL" || $applicationCategory=="HANDICAPPED" || $applicationCategory=="DEFENCE")
					echo "Others";
				else
				        echo $applicationCategory;
			    ?>
                        <?php } ?>
		    </div>
                </div>
            	<div class="colums-width">
                    <label>Nationality: </label>
                    <div class="form-details"><?=$nationality?></div>
                </div>
            </li>

            <li>
            	<div class="colums-width">
                    <label>NRI: </label>
                    <div class="form-details"><?=$nriXIME?></div>
                </div>
            </li>

            <li>
            	<h3 class="form-title">Details of Parents</h3>
            	<div class="colums-width">
                    <label>Father Name: </label>
                    <div class="form-details"><?=$fatherName?></div>
                </div>
            	<div class="colums-width">
                    <label>Father Occupation / Current Job: </label>
                    <div class="form-details"><?=$fatherOccupation?></div>
                </div>
            </li>
            <li>
            	<div class="colums-width">
                    <label>Mother Name: </label>
                    <div class="form-details"><?=$MotherName?></div>
                </div>
            	<div class="colums-width">
                    <label>Mother Occupation / Current Job: </label>
                    <div class="form-details"><?=$MotherOccupation?></div>
                </div>
            </li>
	    
      
            <li>
            	<h3 class="form-title">Communication Information</h3>
            	<label>Mailing Address with PIN: </label>
                <div class="form-details"><?php if($ChouseNumber) echo $ChouseNumber.', ';
						if($CstreetName) echo $CstreetName.', ';
						if($Carea) echo $Carea;?>
		</div>
            </li>
            
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City:</label>
                    <div class="form-details"><?=$Ccity;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$Cstate;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Tel No. (with STD code):</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
               	</div>
            	<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details">&nbsp;<?=$email;?></div>
                </div>                
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Mobile Number:</label>
                    <div class="form-details">&nbsp;<?php if($mobileISDCode) echo $mobileISDCode.'-'; echo $mobileNumber;?></div>
                </div>
		<div class="spacer15 clearFix"></div>
            </li>
	            
            <li>
            	<label>Permanent Address with PIN: </label>
                <div class="form-details"><?php if($houseNumber) echo $houseNumber.', ';
						if($streetName) echo $streetName.', ';
						if($area) echo $area;?>
		</div>
            </li>
            
            <li>
            	<div class="colums-width" style="width:220px;">
                    <label>City:</label>
                    <div class="form-details"><?=$city;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            	
                <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$pincode; ?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>Tel No. (with STD code):</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $std.$landlineNumber; ?></div>
               	</div>
            </li>

	    

	    <li>
            	<h3 class="form-title">Academic Record:</h3>
		<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" style="border: 1px solid #000000;border-collapse: collapse;">
		    <tr>
			<th  align="center" width="170">Qualification</th>
			<th align="center" width="105">Year<br />From - To</th>
			<th align="center" width="200">Name of School,<br />Board, College<br />University &amp; Place</th>
			<th align="center" width="120">Major<br />Subjects</th>
			<th align="center">Total<br />Marks<br />obtained</th>
			<th align="center">Max.<br />Marks</th>
			<th align="center">% Marks<br />(Aggregate)</th>
			
		    </tr>
		    <tr>
			<td valign="top">Std X</td>
			<td valign="top" align="center"><?php if($class10YearFromXIME){ ?><?=$class10YearFromXIME?> - <?=$class10Year?><?php } ?></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?php if($class10School){ ?><?=$class10School?> , <?=$class10Board?><?php } ?></div></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:120px"><?=$class10SubjectsXIME?></div></td>
			<td valign="top" align="center" class="word-wrap"><?=$class10TotalMarksXIME?></td>
			<td valign="top" align="center"><?=$class10MaxMarksXIME?></td>
			<td valign="top" align="center"><?=$class10Percentage?></td>
		    </tr>
		    <tr>
			<td valign="top">Std XII / PUC</td>
			<td valign="top" align="center"><?php if($class12YearFromXIME){ ?><?=$class12YearFromXIME?> - <?=$class12Year?><?php } ?></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?php if($class12School){ ?><?=$class12School?> , <?=$class12Board?><?php } ?></div></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:120px"><?=$class12SubjectsXIME?></div></td>
			<td valign="top" align="center"><?=$class12TotalMarksXIME?></td>
			<td valign="top" align="center"><?=$class12MaxMarksXIME?></td>
			<td valign="top" align="center"><?=$class12Percentage?></td>
		    </tr>
		    
		    <tr>
			<td valign="top">Degree 

			      <?php if(isset($graduationExaminationName) && $graduationExaminationName!=''){ ?>
			      <?php if($graduationExaminationName=="B.A." || $graduationExaminationName=="B.A") echo "B.A."; else echo "<strike>B.A.</strike>"; ?> / 
			      <?php if($graduationExaminationName=="B.Sc" || $graduationExaminationName=="B.SC") echo "B.Sc"; else echo "<strike>B.Sc</strike>"; ?>/<br />
			      <?php if($graduationExaminationName=="B.Com." || $graduationExaminationName=="B.COM." || $graduationExaminationName=="B.COM") echo "B.Com."; else echo "<strike>B.Com.</strike>"; ?>/
			      <?php if($graduationExaminationName=="B.Tech." || $graduationExaminationName=="B.TECH." || $graduationExaminationName=="B.TECH") echo "B.Tech."; else echo "<strike>B.Tech.</strike>"; ?><br />
			      <?php }else{ ?>
			      <?php echo "B.A."; ?> / 
			      <?php echo "B.Sc"; ?>/<br />
			      <?php echo "B.Com."; ?>/
			      <?php echo "B.Tech."; ?><br />
			      <?php } ?>
			      (<img src="/public/images/onlineforms/institutes/xime/tick-icn.gif" alt="" /> one applicable)<br />
			      Others (specify) <br />
			      <?php if($graduationExaminationName!="B.A." && $graduationExaminationName!="B.A" && $graduationExaminationName!="B.SC" && $graduationExaminationName!="B.COM." && $graduationExaminationName!="B.COM" && $graduationExaminationName!="B.TECH" && $graduationExaminationName!="B.TECH."){ 
			      ?><div style="border-bottom:1px solid #000000; padding:5px"><?=$graduationExaminationName?></div><?php } ?>

			</td>
			<td valign="top" align="center"><?php if($gradYearFromXIME){ ?><?=$gradYearFromXIME?> - <?=$graduationYear?><?php } ?></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?php if($graduationSchool){ ?><?=$graduationSchool?> , <?=$graduationBoard?><?php } ?></div></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:120px"><?=$gradSubjectsXIME?></div></td>
			<td valign="top" align="center"><?=$gradTotalMarksXIME?></td>
			<td valign="top" align="center"><?=$gradMaxMarksXIME?></td>
			<td valign="top" align="center"><?=$graduationPercentage?></td>
		    </tr>

		    <!-- Block to show Balnk PG course row if it is not available -->
		    <?php
		    $pgShown = false;
		    for($i=1;$i<=4;$i++){
			    if(${'graduationExaminationName_mul_'.$i}){
				if( ${'otherCoursePGCheck_mul_'.$i} == '1' ){ 
				      $pgShown = true;						    
				 }
			    }
		    } ?>
		    <?php if(!$pgShown){ ?>
		    <tr>
			<td valign="top">P.G. Degree<br />(specify)<br/></td>
			<td valign="top">&nbsp;</td>
			<td valign="top"><div class="word-wrap" style="width:200px">&nbsp;</div></td>
			<td valign="top"><div class="word-wrap" style="width:120px">&nbsp;</div></td>
			<td valign="top">&nbsp;</td>
			<td valign="top">&nbsp;</td>
			<td valign="top">&nbsp;</td>
		    </tr>
		    <?php } ?>
		    <!-- Block End to show Balnk PG course row if it is not available -->


		    <!-- Block to show PG course/Other courses row if it is available -->
		    <?php
		    $otherCourseShown = false;
		    for($i=1;$i<=4;$i++){
			    if(${'graduationExaminationName_mul_'.$i}){
			    ?>
			    <tr>
				<?php if( ${'otherCoursePGCheck_mul_'.$i} == '1' ){ ?>
				    <td valign="top">P.G. Degree<br />(specify)<br/> <div style="border-bottom:1px solid #000000; padding:5px"><?=${'graduationExaminationName_mul_'.$i}?></div></td>
				<?php }else{ $otherCourseShown = true;?>
				    <td valign="top">Other Recognized<br />Course(of duration<br />1 year or more)<br/> <div style="border-bottom:1px solid #000000; padding:5px"><?=${'graduationExaminationName_mul_'.$i}?></div></td>
				<?php } ?>
				<td valign="top" align="center"><?php if(${'otherCourseYearFrom_mul_'.$i}){ ?><?=${'otherCourseYearFrom_mul_'.$i};?> - <?=${'graduationYear_mul_'.$i}?><?php } ?></td>
				<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?php if(${'graduationSchool_mul_'.$i}){ ?><?=${'graduationSchool_mul_'.$i}?> , <?=${'graduationBoard_mul_'.$i}?><?php } ?></div></td>
				<td valign="top" align="center"><div class="word-wrap" style="width:120px"><?=${'otherCourseSubjects_mul_'.$i};?></div></td>
				<td valign="top" align="center"><?=${'otherCourseTotalMarks_mul_'.$i};?></td>
				<td valign="top" align="center"><?=${'otherCourseMaxMarks_mul_'.$i};?></td>
				<td valign="top" align="center"><?=${'graduationPercentage_mul_'.$i}?></td>
			    </tr>
		    <?php }
		    } ?>
		    <!-- Block End to show PG course/Other courses row -->
		    
		    <!-- Block to show Blank other course row if it is not available -->
		    <?php if(!$otherCourseShown){ ?>
		    <tr>
			<td valign="top">Other Recognized<br />Course(of duration<br />1 year or more)</td>
			<td valign="top">&nbsp;</td>
			<td valign="top"><div class="word-wrap" style="width:200px">&nbsp;</div></td>
			<td valign="top"><div class="word-wrap" style="width:120px">&nbsp;</div></td>
			<td valign="top">&nbsp;</td>
			<td valign="top">&nbsp;</td>
			<td valign="top">&nbsp;</td>
		    </tr>
		    <?php } ?>
		    <!-- Block Ends to show Balnk PG course row if it is not available -->
		    
		    <tr>
			<td colspan="7"><span class="flLt">**</span> <span style="padding-left:60px; float:left">Applicants are required to have a bachelor's degree from a recognized university with a minium<br />
			aggregate of 50% for all subjects taken together (45% for SC/ST candidates)</span></td>
		    </tr>
		</table>
		<div class="spacer20 clearFix"></div>
	    </li>	   
                        
	    <li>
            	<h3 class="form-title">Awards, Scholarships, and Significant Achievements (Be specific):</h3>
		<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" style="border: 1px solid #000000;border-collapse: collapse;">
		    <tr>
			<th align="center" width="150">Level</th>
			<th align="center" width="200">Academics</th>
			<th align="center" width="200">Sports / Games</th>
			<th align="center" width="200">Cultural Events</th>
		    </tr>
		    <tr>
			<td valign="top">School Level<br />(upto 2nd position)</td>
			<td valign="top">&nbsp;<?=$schoolAcademicsXIME?></td>
			<td valign="top">&nbsp;<?=$schoolSportsXIME?></td>
			<td valign="top">&nbsp;<?=$schoolCulturalXIME?></td>
		    </tr>
		    <tr>
			<td valign="top">College Level<br />(upto 2nd position)</td>
			<td valign="top">&nbsp;<?=$collegeAcademicsXIME?></td>
			<td valign="top">&nbsp;<?=$collegeSportsXIME?></td>
			<td valign="top">&nbsp;<?=$collegeCulturalXIME?></td>
		    </tr>
		    
		    <tr>
			<td valign="top">University / State Level<br />(upto 10th position)</td>
			<td valign="top">&nbsp;<?=$univAcademicsXIME?></td>
			<td valign="top">&nbsp;<?=$univSportsXIME?></td>
			<td valign="top">&nbsp;<?=$univCulturalXIME?></td>
		    </tr>
		    
		    <tr>
			<td valign="top">National level<br />(upto 20th position)</td>
			<td valign="top">&nbsp;<?=$nationalAcademicsXIME?></td>
			<td valign="top">&nbsp;<?=$nationalSportsXIME?></td>
			<td valign="top">&nbsp;<?=$nationalCulturalXIME?></td>
		    </tr>
		</table>
		<div class="spacer20 clearFix"></div>
	    </li>

            <li>
            	<div class="colums-width">
                    <label>NCC / NSS Participation:</label>
                    <div class="form-details">&nbsp;<?php echo $nccXIME; ?></div>
               	</div>
            </li>
	    <li>
            	<div>
                    <label>If Yes, give details:</label>
                    <div class="form-details">&nbsp;<?=$nccDetailsXIME?></div>
                </div>
            </li>

	    <li>
            	<h3 class="form-title">List Work Experiences (of duration at least 6 months)</h3>
		<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" style="border: 1px solid #000000;border-collapse: collapse;">
		    <tr>
			<th  align="center" width="150">Organization</th>
			<th align="center" width="200">Designation</th>
			<th align="center" width="200" style="margin:0; padding:0">
			    <table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
				    <td style="border-bottom:1px solid #000000" align="center" colspan="2">Date</td>
				</tr>
				<tr>
				    <td width="50%" align="center" style="border-right:1px solid #000000">From</td>
				    <td align="center" width="50%">To</td>
				</tr>
			    </table>
			</th>
			<th align="center" width="200">Annual Salary</th>
			<th align="center" width="200">Reasons for Leaving</th>
		    </tr>

		    <?php 
			  $workExGiven = false;
			  $total = 0;
			  $rolesVal = '';
			  for($i=0; $i<4; $i++){

			      //$mulSuffix = $i>0?'_mul_'.$i:'';
			      $mulSuffix = $i>0?'_mul_'.$i:'';
			      $otherSuffix = '_mul_'.$i;
			      $companyName = ${'weCompanyName'.$mulSuffix};
			      $durationFrom = ${'weFrom'.$mulSuffix};
			      $durationTo = trim(${'weTimePeriod'.$mulSuffix})?'Till date':${'weTill'.$mulSuffix};
			      if($rolesVal==''){
				    $rolesVal = ${'weRoles'.$mulSuffix};
			      }
			      $designation = ${'weDesignation'.$mulSuffix};
			      $natureOfWork = ${'weRoles'.$mulSuffix};
			      $annualSalary = ${'annualSalaryXIME'.$otherSuffix};
			      $reasonsForLeaving = ${'reasonsLeavingXIME'.$otherSuffix};

			      if($companyName || $designation){ $workExGiven = true; $total++; ?>
		    <tr>
			<td align="center"><?php echo $companyName; ?></td>
			<td align="center"><?php echo $designation; ?></td>
			<td align="center"><?php echo $durationFrom . " - " . $durationTo; ?></td>
			<td align="center"><?php  echo $annualSalary; ?></td>
			<td align="center"><?php echo $reasonsForLeaving; ?></td>
		    </tr>
		    <?php }} ?>
		    
		    <?php 
			  for($i=$total; $i<3; $i++){ ?>
		    <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		    </tr>
		    <?php } ?>
		</table>
		<div class="spacer15 clearFix"></div>
	    </li>
	<?php
	 for($i=0; $i<3; $i++){
                              $mulSuffixP = $i>0?'_mul_'.$i:'';
                              $mulSuffix = '_mul_'.$i;
			      $otherSuffix = '_mul_'.$i;
                              $companyName = ${'weCompanyName'.$mulSuffixP};
                              $weTimePeriod = trim(${'weTimePeriod'.$mulSuffixP});
                              $natureWorkExpXIME = ${'natureWorkXIME'.$mulSuffix};
			      if($weTimePeriod=='I currently work here'){
	 ?>
            <li>
            	<div>
                    <label>Describe the nature of work and responsibilities associated with your most recent job (Job description for <?php echo $companyName;?>):</label>
                    <div class="form-details">&nbsp;<?php echo nl2br($natureWorkExpXIME); ?></div>
               	</div>
		<div class="spacer15 clearFix"></div>
            </li>
	   <?php }} ?>
            <li>
            	<h3 class="form-title">Explain why you have chosen to study at XIME.</h3>
            	<div>
                    <div class="form-details"><?=$whyChosenXIME?></div>
                </div>
		<div class="spacer15 clearFix"></div>
            </li>

	    <li>
            	<h3 class="form-title">How did you come to know about XIME ?</h3>
		<div class="checkOptionsBox">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
			    <tr>
				<td>&nbsp;<?php if($cameToKnowAboutXIME!='' && strpos($cameToKnowAboutXIME,'XIME Website')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label>XIME Website</label>
		</div>
		<div class="checkOptionsBox">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
			    <tr>
				<td>&nbsp;<?php if($cameToKnowAboutXIME!='' && strpos($cameToKnowAboutXIME,'Newspaper')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label>Newspaper</label>
		</div>
		
		
		<div class="checkOptionsBox">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
			    <tr>
				<td>&nbsp;<?php if($cameToKnowAboutXIME!='' && strpos($cameToKnowAboutXIME,'Coaching')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label>Coaching centre</label>
		</div>
		
		<div class="checkOptionsBox" style="width:250px">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
			    <tr>
				<td>&nbsp;<?php if($cameToKnowAboutXIME!='' && strpos($cameToKnowAboutXIME,'Other')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label>Other sources(specify)</label>
		</div>
		
		
		
		
	    </li>
	    <li>
		
		
		<div class="checkOptionsBox">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
			    <tr>
				<td>&nbsp;<?php if($cameToKnowAboutXIME!='' && strpos($cameToKnowAboutXIME,'Student')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label>XIME Student</label>
		</div>
		<div class="checkOptionsBox">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
			    <tr>
				<td>&nbsp;<?php if($cameToKnowAboutXIME!='' && strpos($cameToKnowAboutXIME,'Alumni')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label>Alumni</label>
		</div>
		
		<div class="checkOptionsBox" style="width: 257px">
		    <div class="formColumns2">
			<table width="35" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="boxTable">
			    <tr>
				<td>&nbsp;<?php if($cameToKnowAboutXIME!='' && strpos($cameToKnowAboutXIME,'Internet')!==false) echo "<img src='/public/images/onlineforms/institutes/xime/tick-icn.gif' border=0 />"; ?></td>
			    </tr>
			</table>
		    </div>
		    <label style="margin-top:-37px;margin-left:36px;">Internet (Mention Sites Visited)</label>
		    
		    
		</div>
		    
		
    
   
	    </li>
	    <li>
	    <div>
		<label>Other Sources:</label>
		<span>&nbsp;<?=$otherDetailsXIME?></span>
	    </div>
	    
		
	    </li>
	    <li>
	    <div>
		<label>Sites Visited (Comma Separated Values):</label>
		<span>&nbsp;<?=$internetDetailsXIME?></span>
	    </div>
	    
		
	    </li>
	    
	    
	    
	    
	     <li>
            	<h3 class="form-title">Medical Details</h3>
            	<div class="colums-width" style="width:100%;">
                    <label>Do you have any existing medical conditions or concerns:</label>
		    <div class="form-details"><?=$medicalconXIME?></div>
		    		<div class="spacer15 clearFix"></div>

		    <?php
		    if($medicalconXIME == "Yes"){?>
		    		    <div class="form-details"><?=$medicalDetailsXIME?></div>
		    <?php  }  ?>
               	</div>
		<div class="spacer15 clearFix"></div>
            
	     <div class="colums-width" style="width:100%;">
                    <label>Do you have any allergies:</label>
		    <div class="form-details"><?=$allergiesXIME?></div>
		    		<div class="spacer15 clearFix"></div>

		    <?php
		    if($allergiesXIME == "Yes"){?>
		    		    <div class="form-details"><?=$allergiesDetailsXIME?></div>
		    <?php  }  ?>
               	</div>
		<div class="spacer15 clearFix"></div>
            
	      <div class="colums-width" style="width:100%;" >
                    <label>Mention any other special health or medical needs you have:</label>
		    <div class="form-details"><?=$anyothermedicalDetailsXIME?></div>
		    		<div class="spacer15 clearFix"></div>

	     		<div class="spacer15 clearFix"></div>
	     </li>
			
         	<li>
            	<h3 class="form-title">Address For Correspondence</h3>
            	<div class="colums-width" style="width:100%;">
                    <label>Please write in capital letters the address to which communication should be sent:</label><br/>
		    <div class="form-details"><?=$addressforcommXIME?></div>
		    <div class="spacer15 clearFix"></div>
		</div>
		<div class="colums-width">
		<label>Email id:</label>
		<div class="form-details"><?=$email?></div>
		</div>
		<div class="colums-width" style="margin-top:0;">
			<label>Contact Number:</label>
		<div class='form-details'><?=$mobileNumber?></div>

		</div>
		</li>
		
	    
	    
	    
	    
	    
	    
	    
	    
            <!--<li>
            	<div class="colums-width">
                    <label>Have you ever applied to XIME before? </label>
                    <div class="form-details"><?=$appliedXIME?></div>
                </div>
            	<div class="colums-width">
                    <label>If Yes, mention year: </label>
                    <div class="form-details"><?=$yearAppliedXIME?></div>
                </div>
            </li>-->
	     
            <li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
		, hereby declare that the particulars given in this application are true and correct and will be supported by original documents when required. I confirm that I have fully read the terms and conditions regarding the admission to XIME's PGDM programme and pursuit of the same including those relating to withdrawal from the programme after admission.
                
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
<!--XIME Form Preview Ends here-->
