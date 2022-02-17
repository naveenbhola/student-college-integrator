<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
<style type="text/css">
.formPreviewMain {font-size:14px; font-family:Arial, Helvetica, sans-serif !important}
.previewHeader{display:block; margin-bottom:12px;}
.instLogoBox{text-align:center; padding-bottom:3px}
.previewTetx{font-size:22px; color:red; margin-bottom:20px; clear:both}
.previewContentWrapper, .previewBody{width:890px; margin:0 auto;}
.instructiontxt{font-size:12px; font-weight:normal; color:#3e5a76}
.courseNameDetails{text-align:center; font-size:18px; color:#000000; font-weight:normal; line-height:normal; padding-top:10px}
.courseNameDetails p{padding-bottom:15px}
.courseNameDetails strong{color:#ff9000; font-weight:normal;}
.picBox{width:197px; float:right; background-color:#e2e3e4; height:218px}
.picBox img{border:1px solid #cacaca; width:195px}
.picBox p{text-align:center; padding-top:80px; font-size:12px}
.formRows{display:block; margin-bottom:2px; float:left; width:100%}
.formRows ul li{display:block; margin-bottom:15px; float:left; width:100%;}
.formRows ul li label{float:left; padding-right:5px; font-weight:normal;}
.previewFieldBox, .previewFieldBox2{float:left; width:853px;}
.previewFieldBox2 span{border-bottom:1px dotted #000000; display:block; padding:2px 0 1px 0}
.previewFieldBox span, .enrollmentPreviewField span{display:block; border-bottom:solid 1px #000000;}
.formColumns2{float:left}
.flRt{float:right}
.checkBox{width:18px; height:15px; padding-top:3px; text-align:center; border:1px solid #000000; float:left; margin-right:10px}
.adRows{width:700px;; float:left}
.boxTable{border: 1px solid #000000; border-collapse: collapse;}
.boxTable tr td{height:21px; width:22px; padding:0; margin:0; text-align: center;}
.educationTable{border: 1px solid #000000; border-collapse: collapse;}
.educationTable tr th{text-align:center; font-weight:normal}
.educationTable2{border: 1px solid #000000; border-collapse: collapse;}
.educationTable2 tr th, .educationTable2 tr td{text-align:left; font-weight:normal}
.formWordWrapper{white-space: pre-wrap; word-wrap: break-word; overflow:hidden}
.IFIMFormTitle{border-bottom:2px solid #000; padding-bottom:3px; text-align:center}
.preff-cont .option-box{margin:0 5px 0 0px !important}
</style>
<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <img src="/public/images/onlineforms/institutes/ifim/logo2.jpg" alt="" />
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
            <div class="appForm-box">Application Form: PGDM <?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2015";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+2;} else{echo "2017";}?></div>  
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
	   

	   <li> 
            	<div>
                    <label>PROGRAM OPTED FOR: </label>
		    <div class="spacer10 clearFix"></div>
                    <div class="form-details">
 
<div class="preff-cont"><span class="option-box"><?php if( strpos($programIFIM,'POST GRADUATE DIPLOMA IN MANAGEMENT (PGDM)') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></span> PROGRAM OPTED FOR POST GRADUATE DIPLOMA IN MANAGEMENT (PGDM) </div> 
<div class="clearFix"></div>
<div class="preff-cont"><span class="option-box"><?php if( strpos($programIFIM,'FINANCE') !== false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></span> POST GRADUATE DIPLOMA IN MANAGEMENT - FINANCE (PGDM-FINANCE)</div>

	</div>
                </div>
            </li>
<li> 
            	<div>
                    <label style="padding-top: 3px">CATEGORY: </label>
                    <div class="form-details">
 
<div class="preff-cont"> <span class="option-box"><?php if($categoryIFIM == 'SELF - SPONSORED') echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></span> SELF - SPONSORED</div>
<div class="preff-cont"><span class="option-box"><?php if($categoryIFIM == 'COMPANY - SPONSORED') echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></span> COMPANY - SPONSORED </div>

	</div>
	</div>
</li>
	<li>
	 <div>
                                            <label>BATCH: </label>
                                            <div class="form-details">
                                                <span style="border-color:red">&nbsp;<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo $instituteInfo[0]['instituteInfo'][0]['sessionYear'];} else{echo "2015";}?>-<?php if($instituteInfo[0]['instituteInfo'][0]['sessionYear']){ echo intval($instituteInfo[0]['instituteInfo'][0]['sessionYear'])+1;} else{echo "2016";}?></span>
                                            </div>
                                        </div>
            </li>
	    <li>
<h3 class="form-title">Applicant's Information</h3>
		<?php $surName = ($middleName!='')?$middleName." ".$lastName:$lastName; ?>
		<div class="colums-width">
            	<label>First Name: </label>
                <div class="form-details"><?php echo $firstName; ?></div>
		</div>
		<div class="colums-width">
            	<label>SurName: </label>
                <div class="form-details"><?php echo $surName; ?></div>
		</div>
	    </li>
            	
           <li> 
            	<div class="colums-width">
                    <label>Date Of Birth: </label>
                    <div class="form-details"><?=$dateOfBirth;?></div>
                </div>
		<div class="colums-width">
            	<label>Blood Group: </label>
                <div class="form-details"><?=$bloodGroupIFIM?></div>
		</div>
		
            </li>
	    <li>
		<div class="colums-width" >
                <label>Marital Status:</label>
                <div class="form-details"><?=$maritalStatus?></div>
                </div>
	    </li>
	   <li> 
            	<div>
                    <label style="padding-top: 3px">Category: </label>
                    <div class="form-details">
 
<div class="preff-cont">SC <span class="option-box"><?php if($preferredGDPILocation=='30'){echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">ST <span class="option-box"><?php if(strpos($applicationCategory,'SC') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>
<div class="preff-cont">Others <span class="option-box"><?php if(strpos($applicationCategory,'OBC') !== false || strpos($applicationCategory,'DEFENCE') !== false || strpos($applicationCategory,'GENERAL') !== false || strpos($applicationCategory,'HANDICAPPED') !== false){ echo "<img src='/public/images/onlineforms/institutes/alliance/tick-icn.gif' border=0 />";} ?></span></div>

	</div>
                </div>
            </li>

 
	     
            <li>
            	<h3 class="form-title">Applicant's contact details</h3>
            	<label>PERMANENT ADDRESS:</label>
                <div class="form-details"><?php if($houseNumber) echo $houseNumber.', ';
									if($streetName) echo $streetName.', ';?>
								<?php if($area) echo $area.', '; 
								 if($city) echo $city ; ?></div>
            </li>
           
            
            <li>
            	 <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$pincode;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$state;?></div>
                </div>
            	 <div class="colums-width" style="width:227px;">
                    <label>Country:</label>
                    <div class="form-details"><?=$country?></div>
                </div>
               
            </li>

	     <li>
            	<label>ADDRESS FOR CORRESPONDENCE:</label>
                <div class="form-details"><?php if($ChouseNumber) echo $ChouseNumber.', ';
									if($CstreetName) echo $CstreetName.', ';?>
								<?php if($Carea) echo $Carea.', '; 
								 if($Ccity) echo $Ccity ; ?></div>
            </li>
           
            
            <li>
            	 <div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$Cpincode;?></div>
                </div>
            	<div class="colums-width" style="width:227px;">
                    <label>State:</label>
                    <div class="form-details"><?=$Cstate;?></div>
                </div>
            	 <div class="colums-width" style="width:227px;">
                    <label>Country:</label>
                    <div class="form-details"><?=$Ccountry?></div>
                </div>
               
            </li>
           
            <li>
            	<div class="colums-width">
                    <label>Telephone No: (including STD Code):</label>
                    <?php if($landlineISDCode) $isd = $landlineISDCode.'-';else $isd ='';?>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <div class="form-details">&nbsp;<?php echo $isd.$std.$landlineNumber; ?></div>
               	</div>
                
            	<div class="colums-width">
                    <label>Mobile No:</label>
                    <div class="form-details">&nbsp;<?=$mobileISDCode.'-'.$mobileNumber?></div>
                </div>
            </li>
            
            <li>
            	<div class="colums-width">
                    <label>E-MAIL:</label>
                    <div class="form-details"><?=$email;?></div>
                </div>
            	
            </li>
	   
            <li> 
		<h3 class="form-title">Family's details</h3>
		    
            	<div class="colums-width">
                    <label>Father's Name: </label>
                    <div class="form-details"><?=$fatherName?></div>
                </div>
		<div class="colums-width">
            	<label>Occupation: </label>
                <div class="form-details"><?=$fatherOccupation?></div>
		</div>
            </li>
	   
	    <li> 
            	<div class="colums-width" >
                        	<label >Contact No (Res):</label>
                            <div class="form-details" >
                            	<span>&nbsp;<?php echo ($landlineSTDCode!='')?$landlineSTDCode.' '.$landlineNumber:$landlineNumber; ?></span>
                            </div>
                        </div>
		<div class="colums-width">
            	<label>Contact No (Mob):</label>
                <div class="form-details"><?=$fatherMobileIFIM?></div>
		</div>
            </li>

	  
	    <li> 
            	<div class="colums-width">
                    <label>Mothers's Name: </label>
                    <div class="form-details"><?=$MotherName?></div>
                </div>
		<div class="colums-width">
            	<label>Occupation: </label>
                <div class="form-details"><?=$MotherOccupation;?></div>
		</div>
            </li>
		
	    <li> 
            	<div class="colums-width" >
                        	<label >Contact No (Res):</label>
                            <div class="form-details" >
                            	<span>&nbsp;<?php echo ($landlineSTDCode!='')?$landlineSTDCode.' '.$landlineNumber:$landlineNumber; ?></span>
                            </div>
                        </div>
		<div class="colums-width">
            	<label>Contact No (Mob):</label>
                <div class="form-details"><?=$motherMobileIFIM?></div>
		</div>
            </li>
	 
	    <li>
		<div class="colums-width">
                <label>Husband's Name:</label>
                <div class="form-details">
                            	<?=$husbandNameIFIM?></div>
                        </div>
		<div class="colums-width">
                <label>Occupation:</label>
                <div class="form-details">
                            	<?=$husbandOccupationIFIM?></div>
                           </div>
	    </li>
	    <li>
		<div class="colums-width" >
                        	<label >Contact No (Res):</label>
                            <div class="form-details" >
                            	<span>&nbsp;<?php if(isset($husbandNameIFIM) && $husbandNameIFIM!='') echo ($landlineSTDCode!='')?$landlineSTDCode.' '.$landlineNumber:$landlineNumber; ?></span>
                            </div>
                        </div>
		<div class="colums-width">
                        	<label>Contact No (Mob): </label>
                            <div class="form-details" >
                            	<?=$husbandMobileIFIM?>
                            </div>
                        </div>
	    </li>		
	    <li>
            	<label>Residential Address Line 1: </label>
                <div class="form-details"><?php echo $houseNumber;
									if($streetName) echo ', '.$streetName;
									if($area) echo ', '.$area;
									//if($Cstate) echo ', '.$Cstate;
									//if($Ccountry) echo ', '.$Ccountry;
								?></div>
            </li>
            <li>
		    <label>Residential Address Line 2: </label>
                <div class="form-details"><?php if($city) echo $city.", "; echo $pincode; ?></div>
            </li>
             <li>
                        
                        	<label>Annual Income <sup>*1</sup></label>
				<div class="form-details" >
                        	<div class="preff-cont"><span  class="option-box"><?php if($annualIncomeIFIM=='Rs. 2,00,000 p.a') echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></span>
				&lt; Rs. 2,00,000 p.a</div>
                        
                       
                        	<div class="preff-cont"><span  class="option-box"><?php if($annualIncomeIFIM=='Rs. 2,00,000 to Rs. 4,00,000 p.a') echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></span>
				Rs. 2,00,000 to Rs. 4,00,000 p.a</div>
                       
                        	<div class="preff-cont"><span class="option-box"><?php if($annualIncomeIFIM=='Rs. 4,00,000 p.a.') echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></span>
				&gt; 4,00,000 p.a.
				</div>
				  </div>
                        
                    </li>
            <li>
            	<h3 class="form-title">Education qualifications</h3>
		 
                    	<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" style="border-collapse:collapse">
                            <tr>
                                <th style="text-align:center" valign="top" width="30">SR.<br />NO</th>
                                <th valign="top" width="150">EXAMINATION <sup>*2</sup></th>
                                <th valign="top"  width="200">NAME OF<br />COLLEGE/INSTITUTION</th>
                                <th valign="top"  width="200">UNIVERSITY/BOARD</th>
                                <th valign="top"  width="70">YEAR OF<br />PASSING</th>
                                <th valign="top"  width="90">PERCENTAG<br />E/CGPA<br />CLASS</th>
                            </tr>
                            
                            <tr>
                            	<td style="text-align:center" valign="top">1</td>
                                <td valign="top"><div class="formWordWrapper" style="width:150px">S.S.C./STD.X</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$class10School?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$class10Board?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$class10Year?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:70px"><?=$class10Percentage?></div></td>
                            </tr>
                            
                            <tr>
                            	<td style="text-align:center" valign="top">2</td>
                                <td valign="top"><div class="formWordWrapper" style="width:150px">H.S.C./STD.XII</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$class12School?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$class12Board?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$class12Year?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:70px"><?=$class12Percentage?></div></td>
                            </tr>
                            
                            <tr>
                            	<td style="text-align:center" valign="top">3</td>
                                <td valign="top"><div class="formWordWrapper" style="width:150px">NAME OF DEGREE <sup>*3</sup> <?=$graduationExaminationName?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationSchool?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationBoard?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$graduationYear?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:70px"><?=$graduationPercentage?></div></td>
                            </tr>
                            
                            <tr>
                            	<td style="text-align:center" valign="top">4</td>
                                <td valign="top"><div class="formWordWrapper" style="width:150px">FIRST YEAR</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationSchool?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationBoard?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$gradFirstPassingIFIM?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:70px"><?=$gradFirstPercentageIFIM?></div></td>
                            </tr>
                            
                            
                            <tr>
                            	<td style="text-align:center" valign="top">5</td>
                                <td valign="top"><div class="formWordWrapper" style="width:150px">SECOND YEAR</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationSchool?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationBoard?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$gradSecondPassingIFIM?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:70px"><?=$gradSecondPercentageIFIM?></div></td>
                            </tr>
                            
                            <tr>
                            	<td style="text-align:center" valign="top">6</td>
                                <td valign="top"><div class="formWordWrapper" style="width:150px">THIRD YEAR</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationSchool?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?=$graduationBoard?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:90px"><?=$gradThirdPassingIFIM?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:70px"><?=$gradThirdPercentageIFIM?></div></td>
                            </tr>
                            <?php if($gradDurationIFIM=='' || $gradDurationIFIM=='4'):?>
                            <tr>
                            	<td style="text-align:center" valign="top">7</td>
                                <td valign="top"><div class="formWordWrapper" style="width:150px">FOURTH YEAR</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?php if($gradFourthPassingIFIM!='NA') echo $graduationSchool;?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?php if($gradFourthPassingIFIM!='NA') echo $graduationBoard?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:90px"><?php if($gradFourthPassingIFIM!='NA') echo $gradFourthPassingIFIM?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:70px"><?php if($gradFourthPassingIFIM!='NA') echo $gradFourthPercentageIFIM?></div></td>
                            </tr>
			    <?php endif;?>


			    <!-- Block to show Blank PG course row if it is not available -->
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
				  <td style="text-align:center" valign="top"><?php if($gradDurationIFIM=='3')echo '7';else echo '8';?></td>
				  <td valign="top"><div class="formWordWrapper" style="width:150px">POST GRADUATION<br />(IF ANY)</div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:200px"></div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:200px"></div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:90px"></div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:70px"></div></td>
			    </tr>
			    <?php } ?>
			    <!-- Block End to show Blank PG course row if it is not available -->


			    <!-- Block to show PG course/Other courses row if it is available -->
			    <?php
			    $otherCourseShown = false;
			    $countOfPGCourses = 0;
			    for($i=1;$i<=4;$i++){ 
				    if(${'graduationExaminationName_mul_'.$i}){
				    ?>
				    <tr>
					<td valign="top" style="text-align:center"><?php if($pgShown && $gradDurationIFIM ==4) {echo $i+7;} else if($gradDurationIFIM ==4){echo $i+8; } else if($gradDurationIFIM==3 && $pgShown==1){echo $i+6;}else{echo $i+7;} ?></td>
					<?php if( ${'otherCoursePGCheck_mul_'.$i} == '1' ){ $countOfPGCourses++; ?>
					    <td valign="top"><div class="formWordWrapper" style="width:150px">POST GRADUATION<br />(IF ANY) : <?=${'graduationExaminationName_mul_'.$i}?></div></td>
					<?php }else{ $otherCourseShown = true;?>
					    <td valign="top"><div class="formWordWrapper" style="width:150px">PROFESSIONAL<br />COURSE (IF ANY) : <?=${'graduationExaminationName_mul_'.$i}?></div></td>
					<?php } ?>

					<td valign="top"><div class="formWordWrapper" style="width:200px"><?=${'graduationSchool_mul_'.$i}?></div></td>
					<td valign="top"><div class="formWordWrapper" style="width:200px"><?=${'graduationBoard_mul_'.$i}?></div></td>
					<td valign="top"><div class="formWordWrapper" style="width:90px"><?=${'graduationYear_mul_'.$i}?></div></td>
					<td valign="top"><div class="formWordWrapper" style="width:70px"><?=${'graduationPercentage_mul_'.$i}?></div></td>
				    </tr>
			    <?php }
			    } ?>
			    <!-- Block End to show PG course/Other courses row -->
			    
			    <!-- Block to show Blank other course row if it is not available -->
			    <?php if(!$otherCourseShown){ ?>
			    <tr> 
				  <td style="text-align:center" valign="top"><?php if($countOfPGCourses<=1 && $gradDurationIFIM == '4') echo '9'; else if($gradDurationIFIM == '3' && $countOfPGCourses=='1') {echo $countOfPGCourses+7;} else if($gradDurationIFIM == '3' && $countOfPGCourses==0){echo '8';} else echo $countOfPGCourses+8;?></td>
				  <td valign="top"><div class="formWordWrapper" style="width:150px">PROFESSIONAL<br />COURSE (IF ANY)</div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:200px"></div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:200px"></div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:90px"></div></td>
				  <td valign="top"><div class="formWordWrapper" style="width:70px"></div></td>
			    </tr>
			    <?php } ?>
			    <!-- Block Ends to show Balnk PG course row if it is not available -->

                        </table>
                    
	    </li>
	    
	     <li>
                    	<div class="formColumns2" style="width:300px; float:right">
                        	<label style="padding-top:2px;">AGGREGATE MARKS:</label>
                            <div class="previewFieldBox" style="width:140px;">
                            	<span>&nbsp;</span>
                            </div>
                        </div>
			
             </li>
	     
	       <li>
                    	<div class="formColumns2">
                        	<label style="padding-top:2px;">PREFERRED TEST CENTRE: <sup>*4</sup></label>
                            <div class="previewFieldBox" style="width:140px;">
                            	<span>&nbsp;<?=$gdpiLocation?></span>
                            </div>
                        </div>
			
             </li>
	     
	         <li>
                    	<div class="formColumns2" style="font-size:12px">
                        	<label style="padding-top:2px;">NOTES:</label>
							<div class="spacer5 clearFix"></div>
        
<p>*1 Document duly certified/authenticated by chartered Accountant/Bank Manager.</p>
<p>*2 For each & every examination, enclose photocopy duly certified/authenticated.</p>
<p>*3 Specify degree and specialization.</p>
<p>*4 For details, please visit our website / refer admission calendar.</p>

                        </div>
                    </li>
                    
	     
	       <li>
	       <h3 class="form-title">WORK EXPERIENCE DETAILS (LATEST FIRST)<sup>*1</sup></h3>
                    	<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                                <th valign="bottom" width="40"><div class="formWordWrapper" style="width:40px">SL<br />NO</div></th>
                                <th valign="bottom"  width="250">NAME OF THE COMPANY &amp;<br />ADDRESS</th>
                                <th valign="bottom"  width="200">DESIGNATION</th>
                                <th valign="bottom" width="250"  colspan="2">
                                
                                    PERIOD
                                
                                </th>
                                <th valign="bottom"  width="100">SALARY DRAWN</th>
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
				      if(trim(${'weTimePeriod'.$mulSuffix})){
					    $rolesVal = ${'weRoles'.$mulSuffix};
				      }
				      $designation = ${'weDesignation'.$mulSuffix};
				      $natureOfWork = ${'weRoles'.$mulSuffix};
				      $annualSalary = ${'annualSalaryIFIM'.$otherSuffix};
				      $companyAddress = ${'companyAddressIFIM'.$otherSuffix};

				      if($companyName || $designation){ $workExGiven = true; $total++; ?>
			    <tr>
                            	<td valign="top" align="center"><div class="formWordWrapper" style="width:40px"><?=($i+1)?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:250px"><?php echo $companyName; if($companyAddress!='') echo " , ".$companyAddress;?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px"><?php echo $designation; ?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:100px"><?php echo $durationFrom; ?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:100px"><?php echo $durationTo; ?></div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:100px"><?php echo $annualSalary; ?></div></td>
			    </tr>
			    <?php }} ?>
			    
			    <?php 
				  for($i=$total; $i<3; $i++){ ?>
			    <tr>
                            	<td valign="top"><div class="formWordWrapper" style="width:40px">&nbsp;</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:250px">&nbsp;</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:200px">&nbsp;</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td valign="top"><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
			    </tr>
			    <?php } ?>
                            
                        </table>
                    </li>
                    
                    <li>
                    	<h3 class="form-title">ACHIEVEMENTS (ACADEMIC / SPORTS / SCHOLARSHIPS / AWARDS / BANKS ETC.) <sup>*1</sup></h3>
                    	<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                            	<td valign="top">&nbsp;<?=$acheivements1IFIM?></td>
                            </tr>
                            
                            <tr>
                            	<td valign="top">&nbsp;<?=$acheivements2IFIM?></td>
                            </tr>
                            <tr>
                            	<td valign="top">&nbsp;<?=$acheivements3IFIM?></td>
                            </tr>
                        </table>
                    </li>
                    
                    <li>
                    	<h3 class="form-title">EXTRA-CURRICULAR / CO-CURRICULAR ACHIEVEMENTS<sup>*1</sup></h3>
                    	<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                            	<td valign="top">&nbsp;<?=$extra1IFIM?></td>
                            </tr>
                            
                            <tr>
                            	<td valign="top">&nbsp;<?=$extra2IFIM?></td>
                            </tr>
                            <tr>
                            	<td valign="top">&nbsp;<?=$extra3IFIM?></td>
                            </tr>
                        </table>
                    </li>
            <li>
            	<h3 class="form-title">TEST DETAILS</h3>
            	<div class="spacer15 clearFix"></div>
		 <div class="colums-width">
                        	<label >NAME OF ENTRANCE TEST: </label>
				 <div class="form-details" ><?=$entranceIFIM?></div>
                               </div>
                        
<?php
if($entranceIFIM=="CAT"){
      $rollNo = $catRollNumberAdditional;
      $percentile = $catScoreAdditional;
}
else if($entranceIFIM=="MAT"){
      $rollNo = $matRollNumberAdditional;
      $percentile = $matScoreAdditional;
}
else if($entranceIFIM=="GMAT"){
      $rollNo = $gmatRollNumberAdditional;
      $percentile = $gmatScoreAdditional;
}
else if($entranceIFIM=="IIFT"){
      $rollNo = $iiftRollNumberAdditional;
      $percentile = $iiftScoreAdditional;
}
else if($entranceIFIM=="XAT"){
      $rollNo = $xatRollNumberGAdditional;
      $percentile = $xatScoreAdditional;
}
else if($entranceIFIM=="ATMA"){
      $rollNo = $atmaRollNumberAdditional;
      $percentile = $atmaScoreAdditional;
}
?>
  
                        <div class="colums-width" >
                        	<label >REG. NO. OR ROLL NO. </label>
				 <div class="form-details" > <?=$rollNo?></div>
						</div>
                     </li>
		     <li>
                        <div class="colums-width">
                        	<label >PERCENTILE SCORE: </label>
				  <div class="form-details" ><?=$percentile?></div>
				</div>
                   
                    	<div class="colums-width" >
                        	<label >YEAR IN WHICH TAKEN: </label>
				<div class="form-details" ><?=$yearEntranceIFIM?></div>
                              </div>
		    </li>
		    <li>
                        
                        <div class="colums-width" >
                        	<label >CATEGORY: </label>
                           
                                   <div class="form-details" >
				    <?php if(isset($applicationCategory) && $applicationCategory!=''){ ?>
				    <?php echo ($applicationCategory=="GENERAL")?"GENERAL":"<strike>GENERAL</strike>";?>&nbsp;/
				    <?php echo ($applicationCategory=="SC")?"SC":"<strike>SC</strike>";?>&nbsp;/
				    <?php echo ($applicationCategory=="ST")?"ST":"<strike>ST</strike>";?>&nbsp;/
				    <?php echo ($applicationCategory=="OBC")?"OBC":"<strike>OBC</strike>";?>&nbsp;/
				    <?php echo ($applicationCategory=="HANDICAPPED")?"PH":"<strike>PH</strike>";?>&nbsp;/
				    <?php echo ($applicationCategory=="DEFENCE")?"OTHERS":"<strike>OTHERS</strike>";?>
				    <?php }else{ echo "GENERAL / SC / ST / OBC / PH / OTHERS";} ?>
				    </div>
					</div>
                        
                        <div class="colums-width">
                        	<label >NO. OF ATTEMPTS: </label>
				<div class="form-details" ><?=$numberOfAttempsIFIM?></div>
                              </div>
                    
                    
                
                

            

	   
	     
          
<?php if($coursesAlliance!='MBA'):?>
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
				      $emailCompany = ${'emailNumAlliance'.$otherSuffix};
				      $telCompany = ${'telNumAlliance'.$otherSuffix};
				      $mobCompany = ${'mobNumAlliance'.$otherSuffix};
				      if($companyName || $designation){ $workExGiven = true;$total++; ?>



 <?php }} ?>
<?php endif;?>

</li>

                	
 <li>
 
                   	  <h3 class="form-title">LANGUAGE PROFICIENCY</h3>
			  <div class="spacer5 clearFix"></div>
                   	  <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                              <th valign="bottom" width="300"><strong>LANGUAGE</strong></th>
                              <th valign="bottom"  width="150"><strong>CAN READ</strong></th>
                              <th valign="bottom"  width="150"><strong>CAN WRITE</strong></th>
                              <th valign="bottom"  width="150"><strong>CAN SPEAK</strong></th>
                          </tr>
                            
                          <tr>
                            <td valign="top" align="center"><div class="formWordWrapper" style="width:300px"><?=$language1IFIM;?></div></td>
                            <td valign="middle">
                            	<div style="padding:3px 0 3px 70px; float:left">
                            	<div class="option-box"><?php if($language1IFIM!='' && strpos($language1checksIFIM,'read')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
				</div>
				
                            </td>
                            <td valign="middle">
                            	<div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language1IFIM!='' && strpos($language1checksIFIM,'write')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                            </td>
                            <td valign="middle">
                            	<div style="padding:3px 0 3px 70px; float:left">
                            	<div class="option-box"><?php if($language1IFIM!='' && strpos($language1checksIFIM,'speak')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                                
                            </td>
                          </tr>
                          <tr>
                           	  <td valign="top" align="center"><div class="formWordWrapper" style="width:300px"><?=$language2IFIM;?></div></td>
                              <td valign="middle">
                              	<div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language2IFIM!='' && strpos($language2checksIFIM,'read')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                              
                              <td valign="middle">
                              	<div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language2IFIM!='' && strpos($language2checksIFIM,'write')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                              
                              <td valign="middle">
                              	<div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language2IFIM!='' && strpos($language2checksIFIM,'speak')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                          </tr>
                            
                          <tr>
                           	  <td valign="top" align="center"><div class="formWordWrapper" style="width:300px"><?=$language3IFIM;?></div></td>
                              <td valign="middle">
                              	<div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language3IFIM!='' && strpos($language3checksIFIM,'read')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                              <td valign="middle">
                              <div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language3IFIM!='' && strpos($language3checksIFIM,'write')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                              <td valign="middle">
                              <div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language3IFIM!='' && strpos($language3checksIFIM,'speak')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                              
                          </tr>
                          <tr>
                           	  <td valign="top" align="center"><div class="formWordWrapper" style="width:300px"><?=$language4IFIM;?></div></td>
                              <td valign="middle">
                              <div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language4IFIM!='' && strpos($language4checksIFIM,'read')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                              <td valign="middle">
                              <div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language4IFIM!='' && strpos($language4checksIFIM,'write')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                              <td valign="middle">
                              <div style="padding:3px 0 3px 70px; float:left">
                                <div class="option-box"><?php if($language4IFIM!='' && strpos($language4checksIFIM,'speak')!==false) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />"; ?></div>
                                </div>
                              </td>
                          </tr>
                          
                      </table>
                    </li>
		    
		    <li>
                    	<div class="clearFix spacer25"></div>
                    	 <h3 class="form-title">PAN CARD DETAILS:</h3>
                        <div class="clearFix spacer15"></div>
				<div class="colums-width">
				    <label style="float: left;">FATHER:</label>
				    <div style="float: left; width: 300px;">&nbsp;<?=$fatherPANIFIM?></div>
				</div>
				
                    
			    <div class="colums-width">
				    <label style="float: left;">MOTHER:</label>
				    <div style="float: left; width: 300px;">&nbsp;<?=$motherPANIFIM?></div>
				</div>
                       
                    </li>
                    
                    <li>
                    	
				 <div class="colums-width">
				    <label style="float: left;">SELF:</label>
				    <div style="float: left; width: 400px">&nbsp;<?=$studentPANIFIM?></div>
				</div>
				<div class="clearFix spacer15"></div>   
                    </li>
                 
                    
 <li>
                    	<table height="40" width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                                <td align="center"><span style="font-weight: bold">ENCLOSURES</span></td>
                            </tr>
                            
                            <tr>
                                <td>
                                *1 Whenever necessary attach additional sheets appropriately marked and enclose documentary evidence.
                               
                                </td>
                            </tr>
                        </table>
					</li>
                        
                    <li>
		    <div class="spacer20 clearFix"></div>
                    	<table height="40" width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                                <td>
                                	<label>I got to know about IFIM through:</label>
                                    <div class="spacer5 clearFix"></div>
                                    <div style="padding-left:100px">
                                        
                                            <label style="padding-top:4px; float: left">01) News Paper</label>
                                            <div class="option-box" style="margin:3px 10px 0 15px; float: left"><?php if( strpos($knowAboutIFIM,'News') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                      
                                        <div class="spacer5 clearFix"></div>
                                       
                                            <label style="padding-top:4px; float: left">02) Referred to by IFIM student</label>
                                            <div class="option-box" style="margin:3px 10px 0 15px; float: left"><?php if( strpos($knowAboutIFIM,'IFIM student') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                      
                                        
                                     
                                            <label style="padding-top:4px; float: left">Name of the student&nbsp;</label>
                                           
                                                <span style="border-bottom: 1px solid #000000; display: inline-block;width: 200px; float: left">&nbsp;<?=$refStudentNameIFIM?></span>
                                            
                                       
                                        
                                    
                                            <label style="padding-top:4px; float: left">&nbsp;&nbsp; Batch&nbsp;</label>
                                            
                                                <span style="border-bottom: 1px solid #000000; display: inline-block;width: 100px; float: left">&nbsp;<?=$refStudentBatchIFIM?></span>
                                           
                                       
                                        
                                        <div class="spacer5 clearFix"></div>
                                         
                                            <label style="padding-top:4px; width:125px; float: left">03) Education fair</label>
                                            <div class="option-box" style="margin:3px 30px 0 3px; float: left"><?php if( strpos($knowAboutIFIM,'fair') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                       
                                        
                                       
                                       
                                      
                                            <label style="padding-top:4px; width:152px; float: left">04) Radio</label>
                                            <div class="option-box" style="margin:3px 17px 0 6px; float: left"><?php if( strpos($knowAboutIFIM,'Radio') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                       
                                        
                                     
                                       
                                       
                                            <label style="padding-top:4px;padding-left:11px; width:165px; float: left">05) MAT Bulletin</label>
                                            <div class="option-box" style="margin:3px 0px 0 -7px; float: left"><?php if( strpos($knowAboutIFIM,'MAT') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                      
                                         <div class="spacer5 clearFix"></div>
                                       
                                            <label style="padding-top:4px; float: left">06) ATMA Bulletin</label>
                                            <div class="option-box" style="margin:3px 30px 0 5px; float: left"><?php if( strpos($knowAboutIFIM,'ATMA') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                       
                                        
                                       
                                            <label style="padding-top:4px; float: left">07) XAT advertisement</label>
                                            <div class="option-box" style="margin:3px 30px 0 5px; float: left"><?php if( strpos($knowAboutIFIM,'XAT') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                      
                                        
                                       
                                            <label style="padding-top:4px; float: left">08) CAT advertisement</label>
                                            <div class="option-box" style="margin:3px 0px 0 5px; float: left"><?php if( strpos($knowAboutIFIM,'CAT') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                      
                                        
                                        <div class="spacer5 clearFix"></div>
                                      
                                            <label style="padding-top:4px; float: left">09) Faculty/Staff of IFIM</label>
                                            <div class="option-box" style="margin:3px 30px 0 15px; float: left"><?php if( strpos($knowAboutIFIM,'Faculty') !== false ) echo "<img src='/public/images/onlineforms/institutes/ifim/tick-icn.gif' border=0 />";?></div>
                                       
                                        
                                       
                                            <label style="padding-top:4px; float: left">Name of the Faculty/Staff&nbsp;</label>
                                            
                                                <span style="border-bottom: 1px solid #000; width: 345px; display: inline-block; float: left">&nbsp;<?=$refStaffNameIFIM?></span>
                                            
					    
                                    </div>
                                </td>
                            </tr>
                         </table>
					</li>
                    
                    <li>
                    	<h3 class="form-title">DECLARATION</h3>
                        <div class="spacer10 clearFix"></div>
                        <p>I hereby certify that the information furnished in the Application Form is complete, accurate and true. I have carefully
read the contents of the Brochure. If admitted, I agree to abide by the rules and regulations of IFIM Business School as
may be in force from time to time. I understand that any information furnished falsely and/or a misrepresentation is a
sufficient ground for summarily canceling my admission and/or will result in the expulsion from IFIM Business School.</p>
                    </li>
                     
		    <li>
		    <div class="spacer25 clearFix"></div>
                    	<div class="colums-width" >
                        	<label >Place:</label>
                            
                            	<span>&nbsp;<?php if(isset($firstName) && $firstName!='') {echo $Ccity;} ?></span>
                            
                        </div>
                        
                        <div class="colums-width" >
                        	<label >Name:</label>
                            
                            	<span>&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
                            
                        </div>
			
                    </li>
                     
                    <li>
			<div class="spacer10 clearFix"></div>
                    	<div class="colums-width" >
                        	<label >Date:</label>
                            
                            	<span>&nbsp;<?php
                                       if( isset($paymentDetails['mode']) && ( $paymentDetails['mode']=='Offline' || ($paymentDetails['mode']=='Online' && $paymentDetails['mode']=='Success' ) ) ){
                                              echo date("d/m/Y", strtotime($paymentDetails['date']));
                                         }
                                ?></span>
                            
                        </div>
                        
                        <div class="colums-width" >
                        	<label >Signature:</label>
                            
                            	<span>&nbsp;&nbsp;<?php echo $firstName." ".$lastName;?></span>
                            
                        </div>
                    </li>
		    
 <li>
                    	<div class="clearFix spacer40"></div>
                    	<h3 class="form-title">FOR USE BY ADMISSION'S OFFICE</h3>
                    	<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                                <th width="400px"><span style="text-decoration:underline">GROUP DISCUSSION / ESSAY</span><br />(COMMENTS)</th>
                                <th width="400px"><span style="text-decoration:underline">PERSONAL INTERVIEW</span><br />(COMMENTS)</th>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:400px">&nbsp;</div></td>
                            </tr>
                         </table>
                        
                    </li>
 <li>
                    	<div class="clearFix spacer40"></div>
			<h3 class="form-title">EVALUATION TABLE :</h3>
                    	<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                            <tr>
                                <th width="50px" valign="bottom">STD X</th>
                                <th width="80px" valign="bottom">UG<br />DEGREE</th>
                                <th width="80px" valign="bottom">PG<br />DEGREE</th>
                                <th width="100px" valign="bottom">EXTRA/CO<br />CURRIC.<br />ACTIVITIES</th>
                                <th width="50px" valign="bottom">WORK<br />EX</th>
                                <th width="100px" valign="bottom">ENTRANCE<br />TEST<br />SCORES</th>
                                <th width="100px" valign="bottom">STATEMENT<br />OF<br />PURPOSE</th>
                                <th width="50px" valign="bottom">GD</th>
                                <th width="50px" valign="bottom">PI</th>
                                <th width="70px" valign="bottom">TOTAL</th>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
                            </tr>
                            <tr>
                            	<td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:80px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:100px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:50px">&nbsp;</div></td>
                                <td><div class="formWordWrapper" style="width:70px">&nbsp;</div></td>
                            </tr>
                            
                         </table>
                        
                    </li>
                    
  <li>
                   	<div class="spacer10 clearFix"></div>
                   	  <table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" class="educationTable">
                          <tr>
                       		  <td colspan="2">DETAILS OF ADMISSION</td>
                              <td colspan="3" rowspan="9" valign="top">
                              <p style="text-decoration:underline; text-align:center; line-height:24px">PAYMENT'S PARTICULAR<br /><br />
                              PART 'A' OF 1<sup>ST</sup> INSTALLMENT</p>
                              <p style="padding-left:100px; line-height:25px; width:350px">
                              DEMAND DRAFT NO :<br />
							  <span style="width:200px; float:left">DATED: </span>
                              <span style="float:left">RS:</span><br />
                              </p><br />

                              <p style="text-align:center; text-decoration:underline; padding-top:20px">PART 'B' OF 1<sup>ST</sup> INSTALLMENT</p>
							  <p style="padding-left:100px; line-height:25px; width:350px">
                              DEMAND DRAFT NO :<br />
							  <span style="width:200px; float:left">DATED: </span>
                              <span style="float:left">RS:</span><br />
                              </p>
                              
                              </td>
                          </tr>
                          <tr>
                       		  <td colspan="2">DATE OF GD/PI</td>
                          </tr>
                          <tr>
                       		  <td width="25%">CENTRE</td>
                              <td width="19%">&nbsp;</td>
                          </tr>
                          <tr>
                       		  <td rowspan="3" valign="top">NAME OF PANELISTS</td>
                              <td>1) </td>
                          </tr>
                          <tr>
                            <td>2) </td>
                          </tr>
                          <tr>
                            <td>3)</td>
                          </tr>
                          <tr>
                            <td>PROGRAM SELECTED</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>DT-OFFER OF ADMISSION</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>DATE OF ACCEPTANCE</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="3">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" align="center">COMMENT'S / DECISIONS</td>
                            <td colspan="3" align="center">DOCUMENTS SUBMITTED (DURING REGISTRATION)</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td width="270">LIST OF CERTITICATE (S)</td>
                            <td width="100">&nbsp;</td>
                            <td width="100">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>STANDARD X</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>STANDARD XII</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>UG DEGREE &amp; MARKS CARD</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>PG DEGREE (IF ANY)</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>ENTRANCE TEST SCORE CARD</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>WORK EXPERIENCE (IF ANY)</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>EXTRA / CO-CURRICULAR ACTIVITIES</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>ACHIEVEMENTS</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>LETTERS OF RECOMMENDATION (IF<br />
                            ANY)</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>TOTAL</td>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top">
                            <div style="width:170px; float:left">SIGNATURE <br />OF PANELISTS:</div>
                            <div class="formColumns2">
                            <span style="border-bottom:none; float:left">1)&nbsp;</span>
                            <div style="width:180px;" class="previewFieldBox">
                            	<span>&nbsp;</span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                            <span style="border-bottom:none; float:left">2)&nbsp;</span>
                            <div style="width:180px;" class="previewFieldBox">
                            	<span>&nbsp;</span>
                            </div>
                            <div class="spacer10 clearFix"></div>
                            <span style="border-bottom:none; float:left">3)&nbsp;</span>
                            <div style="width:180px;" class="previewFieldBox">
                            	<span>&nbsp;</span>
                            </div>
                            </div>
                            </td>
                            <td colspan="3" valign="top">
                            	<label style="float: left">Comments:</label>
                                <div style="width:400px;" class="previewFieldBox">
                            		<span>&nbsp;</span>
                            	</div>
                                <div class="spacer10 clearFix"></div>
                                <div style="width:98%;" class="previewFieldBox">
                            		<span>&nbsp;</span>
                            	</div>
                                <div class="spacer10 clearFix"></div>
                                <div style="width:98%;" class="previewFieldBox">
                            		<span>&nbsp;</span>
                            	</div>
                                <div class="spacer10 clearFix"></div>
                                <p>SIGNATURE OF DY.REGISTRAR(ADMISSIONS):</p>
                            </td>
                          </tr>
                          <tr>
                            <td valign="top" height="80" colspan="2">COLLEGE SEAL :-</td>
                            <td valign="top" colspan="3">SIGNATURE OF REGISTRAR :-</td>
                          </tr>
                      </table>
                    </li>
        </ul>
    </div>
    <div class="clearFix"></div>
</div>
