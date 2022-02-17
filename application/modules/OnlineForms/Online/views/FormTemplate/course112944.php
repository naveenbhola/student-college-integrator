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


<link rel="stylesheet" type="text/css" href="public/css/online-styles.css" />
	<div id="custom-form-main">
	<?php if(!isset($_REQUEST['viewForm']) && !$sample) { ?><div class="previewTetx">This is how your form will be viewed by the institute</div><?php } ?>
	<div id="custom-form-header">
    	<div class="app-left-box">

            <div class="inst-name" style="width:100%">
                <div class="float_L"><img src="/public/images/onlineforms/institutes/ifmr/logo2.gif" alt="" /></div>
		<div class="">
		    <p>
		    <strong>Institute for Financial Management and Research</strong><br />
				    <span>24, Kothari Road, Nungambakkam, Chennai 600 034, India</span><br />
				    <span>Phone: +91 44 28303400; Fax: +91 44 28279208</span></br>
				    <b>Email: pgdm@ifmr.ac.in Website: www.ifmr.ac.in</b>
				   
		    </p>
		</div>
            </div>
            <div class="clearFix spacer15"></div>
	    <?php if($showEdit == 'true' && ($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed')) { ?>
	    <div class="applicationFormEditLink"><strong class="editFormLink" style="font-size:14px">(<a title="Edit form" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/1">Edit form</a>)</strong></div>
			      <?php } ?>
	    <div class="clearFix spacer10"></div>
 
        </div>

            <div class="user-pic-box"><?php if($profileImage) { ?>
		    <img src="<?php echo $profileImage; ?>" />
		    <?php } ?>
            </div>
	    
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
	</ul>
            
                     <div class="appForm-box">APPLICATION FOR TWO-YEAR PGDM PROGRAMME</div>   
            <li>
            	<h3 class="form-title">Personal Information</h3>
	    </li>
	    
	    <ul class="reviewChildLeftCol" style="float:left;">
	    <li>
		<?php $userName = ($middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName; ?>
		<div>
            	<label>Name: </label>
                <span style="float:left;"><?php echo $userName; ?></span>
		</div>
	    </li>

	    <li>  
            	<div>
                    <label>Date Of Birth: </label>
                    <span><?=$dateOfBirth;?></span>
                </div>
            	<!--<div class="colums-width">
                    <label>Age as on 1/7/2015: </label>
                    <div class="form-details"><?=$ageXIME;?></div>
                </div>-->
           </li>
	</ul>
	
	<ul class="reviewChildRightCol">
           <li> 
            	<div>
                    <label>Gender: </label>
                    <span><?php echo $gender;?></span>
                </div>
		<!--<div class="colums-width">
		    <label>Marital status: </label>
		    <div class="form-details">
                        <?php if(isset($maritalStatus) && $maritalStatus!=''){ ?>
                                <?php if($maritalStatus=='SINGLE') echo "UNMARRIED"; else echo "MARRIED";?>
                        <?php } ?>
		    </div>
		</div>-->
            </li>
	

<!--            <li>
            	<div class="colums-width">
                    <label>Religion: </label>
                    <div class="form-details"><?=$religion?></div>
                </div>
		<!--<div class="colums-width">
		    <label>Mother tongue: </label>
		    <div class="form-details"><?php echo $motherTongueXIME; ?></div>
		</div
            </li>-->

            <li>
<!--            	<div class="colums-width">
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
                </div>-->
            	<div>
                    <label>Nationality: </label>
                    <span><?=$nationality?></span>
                </div>
            </li>
	</ul>

<!--            <li>
            	<div class="colums-width">
                    <label>NRI: </label>
                    <div class="form-details"><?=$nriXIME?></div>
                </div>
            </li>-->
	
            <li>
            	<h3 class="form-title">Details of Parents/Guardian</h3>
	    </li>
	<ul class="reviewChildRightCol">
	     <li>
	    	<div>
                    <label>Father Name: </label>
                    <span><?=$fatherName?></span>
                </div>
	     </li>
	     <li>
            	<div>
                    <label>Father's Occupation: </label>
                    <span><?=$fatherOccupation?></span>
                </div>
            </li>
	</ul>
	
	<ul class="reviewChildLeftCol">
            <li>
            	<div >
                    <label>Mother Name: </label>
                    <span><?=$MotherName?></span>
                </div>
	    </li>
	    <li>
            	<div >
                    <label>Mother's Occupation: </label>
                    <span><?=$MotherOccupation?></span>
                </div>
            </li>
	</ul>
      
        
	<li>
            	<h3 class="form-title">Communication Information</h3>
	</li>
	
	<ul class="reviewChildLeftCol">
	    <li>
            	<label>Mailing Address: </label>
                <span class="form-details" style="float:left;"><?php if($ChouseNumber) echo $ChouseNumber.', ';
						if($CstreetName) echo $CstreetName.', ';
						if($Carea) echo $Carea;?>
		</span>
            </li>
            
	    <li>
        
                    <label>State:</label>
                    <span><?=$Cstate;?></span>
                
            	
                <!--<div class="colums-width" style="width:227px;">
                    <label>PIN Code:</label>
                    <div class="form-details"><?=$Cpincode; ?></div>
                </div>-->
            </li>
            
            <li>
            	
                    <label>Tel No. (with STD code):</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <span>&nbsp;<?php echo $std.$landlineNumber;?></span>
        
            	<!--<div class="colums-width">
                    <label>Email:</label>
                    <div class="form-details">&nbsp;<?=$email;?></div>
                </div-->               
            </li>
            
            <li>
            	
                    <label>Mobile Number:</label>
                    <span>&nbsp;<?php if($mobileISDCode) echo $mobileISDCode.'-'; echo $mobileNumber;?></span>
               
		
            </li>
	</ul>
	    
	<ul class="reviewChildRightCol">
		<li>
			<label>City:</label>
			<span><?=$city?></span>
		</li>
		<li>
                    <label>PIN Code:</label>
                    <span><?=$Cpincode; ?></span>
                </li>
		
	</ul>

	<li>
		<h3 class="form-title">Permanent Address:</h3>
	</li>
	<ul class="reviewChildLeftCol">
            <li>
            	<label>Permanent Address: </label>
                <span style="float:left;"><?php if($houseNumber) echo $houseNumber.', ';
						if($streetName) echo $streetName.', ';
						if($area) echo $area;?>
		</span>
            </li>
            
            
            <li>
                    <label>State:</label>
                    <span><?=$state;?></span>
            </li>
            
            <li>
                    <label>Tel No. (with STD code):</label>
                    <?php if($landlineSTDCode) $std = $landlineSTDCode.'-';else $std='';?>
                    <span>&nbsp;<?php echo $std.$landlineNumber; ?></span>
	    </li>
	</ul>
	
	
	
        <ul class="reviewChildRightCol">
		<li>
            	
                    <label>City:</label>
                    <span><?=$city;?></span>
		</li>
		<li>
                    <label>PIN Code:</label>
                    <span><?=$pincode; ?></span>
                </li>
		<li >
                    <label>Email:</label>
                    <span>&nbsp;<?=$email;?></span>
                </li>
	</ul>

	<ul>
            <li>
		<h3 class="form-title">Preferred GD/Interview Centre: </h3>
		<div class="colums-width">
			<div class="form-details">
			<?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=''){ ?>
			    <?php if($preferredGDPILocation=='30') echo "Ahmedabad";?>
			    <?php if($preferredGDPILocation=='278') echo "Bangalore";?>
			    <?php if($preferredGDPILocation=='912') echo "Bhopal";?>
			    <?php if($preferredGDPILocation=='55') echo "Bhubaneswar";?>
			    <?php if($preferredGDPILocation=='64') echo "Chennai";?>
			    <?php if($preferredGDPILocation=='67') echo "Coimbatore";?>
                            <?php if($preferredGDPILocation=='74') echo "Delhi";?>
                            <?php if($preferredGDPILocation=='702') echo "Hyderabad";?>
                            <?php if($preferredGDPILocation=='109') echo "Jaipur";?>
                            <?php if($preferredGDPILocation=='127') echo "Kochi";?>
                            <?php if($preferredGDPILocation=='130') echo "Kolkata";?>
                            <?php if($preferredGDPILocation=='138') echo "Lucknow";?>
                            <?php if($preferredGDPILocation=='151') echo "Mumbai";?>
                            <?php if($preferredGDPILocation=='171') echo "Patna";?>
                            <?php if($preferredGDPILocation=='180') echo "Ranchi";?>
			 <?php }?>
			</div>
                </div>
            </li>
                        

	 
            <h3 class="form-title">Test Details</h3>
            <?php 
            $testsArray = explode(",",$testNamesIFMR);
	
	if(in_array("CAT",$testsArray)){ ?>
	    <li>
			
			<div class="clearFix spacer5"></div>
			<strong class="formGreyBox" style="padding:7px 7px;">CAT 2014</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>CAT Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$catRollNumberAdditional;?></div>
			</div>
			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$catDateOfExaminationAdditional;?></div>
			</div>
	    </li>
            
            
	    <li>
			<div class="colums-width">
			    <label>CAT VA & LR Score:</label>
			    <div class="form-details">&nbsp;<?=$cat_verbal_score_ifmr;?></div>
			</div>


			<div class="colums-width">
			    <label>CAT VA & LR Percentile:</label>
			    <div class="form-details">&nbsp;<?=$cat_verbal_Percentile_ifmr;?></div>
			</div>
	   </li>
                        
                        
                        <li>
			<div class="colums-width">
			    <label>CAT QA & DI Score:</label>
			    <div class="form-details">&nbsp;<?=$cat_quant_score_ifmr;?></div>
			</div>


			<div class="colums-width">
			    <label>CAT QA & DI Percentile:</label>
			    <div class="form-details">&nbsp;<?=$cat_quant_Percentile_ifmr;?></div>
			</div>
	   </li>
	    
            
            <li>
			<div class="colums-width">
			    <label>Total Score:</label>
			    <div class="form-details">&nbsp;<?=$catScoreAdditional;?></div>
			</div>


			<div class="colums-width">
			    <label>Total Percentile:</label>
			    <div class="form-details">&nbsp;<?=$catPercentileAdditional;?></div>
			</div>
	   </li>
	
        
            <?php } 
            if(in_array("XAT",$testsArray)){ ?>    
	    <li>
			<div class="clearFix spacer5"></div>
			<strong class="formGreyBox" style="padding:7px 7px;">XAT 2015</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>XAT Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$xatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$xatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
            
            <li>
                        <div class="colums-width">
			    <label>VA(english) Percentile:</label>
			    <div class="form-details">&nbsp;<?=$xat_va_Percentile;?></div>
			</div>

			<div class="colums-width">
			    <label>RA(Decision Making) Percentile:</label>
			    <div class="form-details">&nbsp;<?=$xat_ra_Percentile;?></div>
			</div>
	   </li>
	   
           
           <li>
			<div class="colums-width">
			    <label>DI(quant) Percentile:</label>
			    <div class="form-details">&nbsp;<?=$xat_di_Percentile;?></div>
			</div>

			<div class="colums-width">
			    <label>Total Percentile:</label>
			    <div class="form-details">&nbsp;<?=$xatPercentileAdditional;?></div>
			</div>
	   </li>
        <?php } 
	
	if(in_array("CMAT",$testsArray)){ ?>   
	   <li>
			<div class="clearFix spacer5"></div>
			<strong class="formGreyBox" style="padding:7px 7px;">CMAT(Sept. 2014)</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>CMAT Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$cmatRollNumberAdditional;?></div>
			</div>

			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$cmatDateOfExaminationAdditional;?></div>
			</div>
	   </li>
           
            <li>
			<div class="colums-width">
                            <label>Quantitative Techniques & Data Interpretation:</label>
			    <div class="form-details">&nbsp;<?=$cmat_qaunt_di;?></div>
			</div>

			<div class="colums-width">
			    <label>Logical Reasoning:</label>
			    <div class="form-details">&nbsp;<?=$cmat_logical_reasoning;?></div>
			</div>
	    </li>
	   
           
           <li>
			<div class="colums-width">
			    <label>Language Comprehension:</label>
			    <div class="form-details">&nbsp;<?=$cmat_lang_comprehension;?></div>
			</div>

			<div class="colums-width">
			    <label>General Awareness:</label>
			    <div class="form-details">&nbsp;<?=$cmat_general_awareness;?></div>
			</div>
	   </li>
           
            <li>
			<div class="colums-width">
			    <label>Total Score:</label>
			    <div class="form-details">&nbsp;<?=$cmatScoreAdditional;?></div>
			</div>
	    </li>
            <?php }
        
        
            if(in_array("GMAT",$testsArray)){ ?>
	    <li>
			
			<div class="clearFix spacer5"></div>
			<strong class="formGreyBox" style="padding:7px 7px;">GMAT</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>GMAT Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$gmatRollNumberAdditional;?></div>
			</div>
			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$gmatDateOfExaminationAdditional;?></div>
			</div>
	    </li>
            
            
                        <li>
			<div class="colums-width">
			    <label>GMAT Verbal Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gmat_verbal;?></div>
			</div>


			<div class="colums-width">
			    <label>GMAT Verbal Score:</label>
			    <div class="form-details">&nbsp;<?=$gmat_verbal_score;?></div>
			</div>
	   </li>
                        
                        
            <li>
			<div class="colums-width">
			    <label>GMAT Quantitative Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gmat_quant;?></div>
			</div>


			<div class="colums-width">
			    <label>GMAT Quantitative Score:</label>
			    <div class="form-details">&nbsp;<?=$gmat_quant_score;?></div>
			</div>
	   </li>
                        
                        
                                   
            <li>
			<div class="colums-width">
			    <label>GMAT Analytical Writing Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gmat_analytical;?></div>
			</div>


			<div class="colums-width">
			    <label>GMAT Analytical Writing Score:</label>
			    <div class="form-details">&nbsp;<?=$gmat_analytical_score;?></div>
			</div>
	   </li>
	    
	    
            
            <li>
			<div class="colums-width">
			    <label>Total Score:</label>
			    <div class="form-details">&nbsp;<?=$gmatScoreAdditional;?></div>
			</div>


			<div class="colums-width">
			    <label>Total Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gmatPercentileAdditional;?></div>
			</div>
	   </li>
            <?php }
        
        
            if(in_array("GRE",$testsArray)){ ?>
	    <li>
			
			<div class="clearFix spacer5"></div>
			<strong class="formGreyBox" style="padding:7px 7px;">GRE</strong>
			<div class="clearFix spacer10"></div>
			<div class="colums-width">
			    <label>GRE Reg. / Roll No:</label>
			    <div class="form-details">&nbsp;<?=$greRollNumberAdditional;?></div>
			</div>
			<div class="colums-width">
			    <label>Date of Exam:</label>
			    <div class="form-details">&nbsp;<?=$greDateOfExaminationAdditional;?></div>
			</div>
	    </li>
            
            
                        <li>
			<div class="colums-width">
			    <label>GRE Verbal Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gre_verbal;?></div>
			</div>


			<div class="colums-width">
			    <label>GRE Verbal Score:</label>
			    <div class="form-details">&nbsp;<?=$gre_verbal_score;?></div>
			</div>
	   </li>
                        
                        
            <li>
			<div class="colums-width">
			    <label>GRE Quantitative Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gre_quant;?></div>
			</div>


			<div class="colums-width">
			    <label>GRE Quantitative Score:</label>
			    <div class="form-details">&nbsp;<?=$gre_quant_score;?></div>
			</div>
	   </li>
                        
                        
                                   
            <li>
			<div class="colums-width">
			    <label>GRE Analytical Writing Percentile:</label>
			    <div class="form-details">&nbsp;<?=$gre_analytical;?></div>
			</div>


			<div class="colums-width">
			    <label>GRE Analytical Writing Score:</label>
			    <div class="form-details">&nbsp;<?=$gre_analytical_score;?></div>
			</div>
	   </li>
	    
	    
            
            <li>
			<div class="colums-width">
			    <label>Total Score:</label>
			    <div class="form-details">&nbsp;<?=$greScoreAdditional;?></div>
			</div>


			<div class="colums-width">
			    <label>Total Percentile:</label>
			    <div class="form-details">&nbsp;<?=$grePercentileAdditional;?></div>
			</div>
	   </li>
            <?php } ?>
            
            <li>
            	<h3 class="form-title">Educational Qualification:</h3>
		<table width="100%" cellpadding="6" cellspacing="0" border="1" bordercolor="#000000" style="border: 1px solid #000000;border-collapse: collapse;">
		    <tr>
			<th  align="center" width="170">Course</th>
			<th align="center" width="105">Year<br />(From - To)</th>
			<th align="center" width="200">College &amp Board/University</th>
			<th align="center" width="150">Major<br />Subjects</th>
			
			<th align="center" width="100">% Marks<br />(Aggregate)</th>
			<th align="center">Rank</th>
			
		    </tr>
                    
                    <tr>
			<td valign="top">Std X</td>
			<td valign="top" align="center"><?php if($year_10_ifmr){ ?><?=$year_10_ifmr?> - <?=$class10Year?><?php } ?></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?php if($class10School){ ?><?=$class10School?> , <?=$class10Board?><?php } ?></div></td>
	
			<td valign="top" align="center"><?=$subjects_10_ifmr?></td>
			<td valign="top" align="center"><?=$class10Percentage?></td>
			<td valign="top" align="center"></td>
		    </tr>
		    
		    <tr>
			<td valign="top">Std XII</td>
			<td valign="top" align="center"><?php if($year_10_ifmr){ ?><?=$year_10_ifmr?> - <?=$class12Year?><?php } ?></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?php if($class12School){ ?><?=$class12School?> , <?=$class12Board?><?php } ?></div></td>
			<td valign="top" align="center"><?=$subjects_12_ifmr?></td>
			<td valign="top" align="center"><?=$class12Percentage?></td>
			<td valign="top" align="center"></td>
		    </tr>
		    
		    <tr>
			<td valign="top">Degree 

			      <?php if(isset($graduationExaminationName) && $graduationExaminationName!=''){ ?>
			      <?php if($graduationExaminationName=="B.A." || $graduationExaminationName=="B.A") echo "B.A.";  ?> 
			      <?php if($graduationExaminationName=="B.Sc" || $graduationExaminationName=="B.SC") echo "B.Sc"; ?><br />
			      <?php if($graduationExaminationName=="B.Com." || $graduationExaminationName=="B.COM." || $graduationExaminationName=="B.COM") echo "B.Com.";  ?>
			      <?php if($graduationExaminationName=="B.Tech." || $graduationExaminationName=="B.TECH." || $graduationExaminationName=="B.TECH") echo "B.Tech."; ?><br />
			      <?php }else{ ?>
			      <?php echo "B.A."; ?> / 
			      <?php echo "B.Sc"; ?>/<br />
			      <?php echo "B.Com."; ?>/
			      <?php echo "B.Tech."; ?><br />
			      <?php } ?>
			      <?php if($graduationExaminationName!="B.A." && $graduationExaminationName!="B.A" && $graduationExaminationName!="B.SC" && $graduationExaminationName!="B.COM." && $graduationExaminationName!="B.COM" && $graduationExaminationName!="B.TECH" && $graduationExaminationName!="B.TECH."){ 
			      ?><div style=" padding:5px"><?=$graduationExaminationName?></div><?php } ?>

			</td>
			<td valign="top" align="center"><?php if($year_UG_ifmr){ ?><?=$year_UG_ifmr?> - <?=$graduationYear?><?php } ?></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?php if($graduationSchool){ ?><?=$graduationSchool?> , <?=$graduationBoard?><?php } ?></div></td>
			<td valign="top" align="center"><div class="word-wrap" style="width:120px"><?=$subjects_UG_ifmr?></div></td>
			
			<td valign="top" align="center"><?=$graduationPercentage?></td>
			<td valign="top" align="center"><?=$ifmr_rankDetail?></td>
		    </tr>
		    
		    <!-- Block to show Balnk PG course row if it is not available -->
		    <?php //$count_exam = count($exam_array); 
			for($j=1;$j<=4;$j++):?>
                    <?php if(!empty(${'graduationExaminationName_mul_'.$j})):?>
		    
		   
			    <tr>
				
				    <td valign="top"><div style="padding:5px"><?php echo ${'graduationExaminationName_mul_'.$j};?></div></td>
				
				    <td valign="top"><div style="padding:5px"><?php echo ${'otherCourseSoa_mul_'.$j};?>-<?php echo ${'graduationYear_mul_'.$j};?></div></td>
				
				<td valign="top" align="center"><?=${'graduationSchool_mul_'.$j}?> , <?=${'graduationBoard_mul_'.$j}?></td>
				<td valign="top" align="center"><div class="word-wrap" style="width:200px"><?=${'otherCourseMajorSub_mul_'.$j}?></div></td>
				<td valign="top" align="center"><div class="word-wrap" style="width:120px"><?=${'graduationPercentage_mul_'.$j}?></div></td>
				<td valign="top" align="center"><?=${'otherCourseRankDetailPg_mul_'.$j}?></td>
			    </tr>
		   <?php endif;endfor;//endif;?>
		    
		</table>
		<div class="spacer20 clearFix"></div>
	    </li>
	
	<div class="workInfoSection">
        	<div class="reviewTitleBox">
                <strong>Work Experience:</strong>
            </div>
		
	   <div class="reviewLeftCol"   style="width:98% !important">
				
				<ul class="reviewChildLeftCol"  style="width:600px !important;margin-bottom:30px">
					<li>
						<label style="width:255px">Do you have any work experience? :</label>
						<span><?php echo $Imfr_workExp;?></span>
					</li>	
					
				
				</ul>
	   </div>
		
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
                                      $workExpTotalInMonthValue=${'workExpTotalInMonth'.$otherSuffix};
                                      	      
				      
				      if($workCompany || $designation){$workExGiven = true;$total++; ?>
            <div class="reviewLeftCol widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <div style="width:290px; float:left"><?php echo $weCompanyName;?></div>
						</div>
                        	
                    	<div class="formColumns">
                    	<label>Designation:</label>
                        <div style="width:290px; float:right"><?php echo $weDesignation;?></div>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <div style="width:290px; float:left"><?php echo $weLocation;?></div>
                    </div> 
                    
                    <div class="formColumns">
						<label >Time Period:</label>
						<?php if($weTimePeriod):?>
                         <input type="checkbox" checked="checked" disabled="disabled" /> I currently work here<br />
                         <?php else:?>
                         <input type="checkbox" disabled="disabled" /> I currently work here<br />
                         <?php endif;?>
                        <div class="workExpDetails3">
                        	<span>From: <?php if(!empty($weFrom)) {echo date('F Y',strtotime(getStandardDate($weFrom)));}?></span>
                        	<span>&nbsp;&nbsp;-</span>
                            <span class="mL10">Till: <?php if(!$weTimePeriod) {if(!empty($weTill)) {echo date('F Y',strtotime(getStandardDate($weTill)));}} else {echo "Date";}?></span>
                        </div>
					</div>
                 </li>
		 
		 <li>
                    <div class="formColumns" style="width:98%">
                    	<label>No. of months:</label>
                        <span><?php echo ${'workExpTotalInMonth_mul_'.$i};?></span>
                    </div> 
                    
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="spacer20 clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Brief summary of your work experience:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim($weRoles));?></li>
            </ul>
        </div>
    </div>
    <?php //if(!empty($wecompany_array)):?>
    <?php //$count_company = count($wecompany_array);
	for($i=1;$i<=3;$i++):?>
    <?php if(!empty(${'weCompanyName_mul_'.$i})):?>
    <div class="reviewLeftCol widthAuto">
                <ul>
                	<li>
                    	<div class="formColumns">
                            <label>Company Name:</label>
                            <span><?php echo ${'weCompanyName_mul_'.$i};?></span>
						</div>
                        	
                    	<div class="formColumns">
                    	<label >Designation:</label>
                        <span><?php echo ${'weDesignation_mul_'.$i};?></span>
                    </div>    
                 </li>
                 
                 <li>
                    <div class="formColumns">
                    	<label>Location:</label>
                        <span><?php echo ${'weLocation_mul_'.$i}?></span>
                    </div> 
                    
                    <div class="formColumns">
						<label >Time Period:</label>
						<?php if(${'weTimePeriod_mul_'.$i}):?>
                         <input type="checkbox" checked="checked" disabled="disabled" /> I currently work here<br />
                         <?php else:?>
                         <input type="checkbox" disabled="disabled" /> I currently work here<br />
                         <?php endif;?>
                        <div class="workExpDetails3">
                        	<span>From: <?php if(!empty(${'weFrom_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weFrom_mul_'.$i})));}?></span>
                        	<span>&nbsp;&nbsp;-</span>
                            <span class="mL10">Till: <?php if(!${'weTimePeriod_mul_'.$i}) {if(!empty(${'weTill_mul_'.$i})) {echo date('F Y',strtotime(getStandardDate(${'weTill_mul_'.$i})));} } else {echo "Date";}?></span>
                        </div>
					</div>
                 </li>
		 <li>
                    <div class="formColumns" style="width:98%">
                    	<label>No. of months:</label>
                        <span><?php echo ${'workExpTotalInMonth_mul_'.$i};?></span>
                    </div> 
                    
                 </li>
              </ul>
          </div>
     <!--Work Exp Info Ends here-->
     <div class="spacer20 clearFix"></div>
     <div class="rolesResponsiblity">
     	<h3>Brief summary of your work experience:</h3>
        <div id="responsibilityList">
            <ul>
                <li><?php echo nl2br(trim(${'weRoles_mul_'.$i}));?></li>
            </ul>
        </div>
    </div>
    <?php endif;endfor;//endif;?>
   </div> 
    
	<?php }} ?>


	   
	   <li>
		<h3 class="form-title">Extra-Curricular Activities, Interests and Hobbies: </h3>
		<div class="form-tile">
			<div class="form-details">&nbsp;<?=$ifmr_hobbies;?></div>
		</div>
	   </li>
	   
	   <li>
		<h3 class="form-title">References:</h3>
	   <li>
	<ul class="reviewChildLeftCol" style="width:435px;">
		<li>
			
			
			<strong class="formGreyBox" style="padding:7px 7px;">Reference 1</strong>
			
			
		</li>
		<li>
			
			    <label>Name:</label>
			    <span>&nbsp;<?=$reference1_name;?></span>
			
			
	    
		</li>
		<li>
			
			    <label>Designation:</label>
			    <span>&nbsp;<?=$reference1_designation;?></span>
			
		
			    
		
	    
		</li>
		<li>
			
			    <label>Company/Institution:</label>
			    <span>&nbsp;<?=$reference1_workplace;?></span>
			
			
			    
			
	    
		</li>
		<li>
			
			    <label>Address:</label>
			    <span style="float:left;">&nbsp;<?=$reference1_address;?></span>
			
			
			    
			
	    
		</li>
		<li>
			
			    <label>Phone No:</label>
			    <span>&nbsp;<?=$reference1_phone;?></span>
			
		
			    
			
	    
		</li>
		<li>
			
			    <label>Mobile No:</label>
			    <span>&nbsp;<?=$reference1_mobile;?></span>
			
		</li>
		<li>
			
			    <label>Email:</label>
			    <span>&nbsp;<?=$reference1_email;?></span>
			
			
			  
			
		</li>
		
	   </li>
	</ul>
	
	<ul class="reviewChildRightCol" style="width:435px;">
		<li>
			
			<strong class="formGreyBox" style="padding:7px 7px;"> Reference 2</strong>
			
		</li>
		<li>
			
			    <label style="width:170px !important">Name:</label>
			    <span>&nbsp;<?=$reference2_name;?></span>
			
		</li>
		<li>
			<label style="width:170px !important">Designation:</label>
			<span>&nbsp;<?=$reference2_designation;?></span>
		</li>
		
		<li>
			<label style="width:170px !important">Company/Institution:</label>
			    <span><?=$reference2_workplace;?></span>
		</li>
		
		<li>
			<label style="width:170px !important">Address:</label>
			    <span>&nbsp;<?=$reference2_address;?></span>
		</li>
		
		<li>
			<label style="width:170px !important">Phone No:</label>
			    <span>&nbsp;<?=$reference2_phone;?></span>
		</li>
		<li>
			 <label style="width:170px !important">Mobile No:</label>
			    <span>&nbsp;<?=$reference2_mobile;?></span>
		</li>
		
		<li>
			
			    <label style="width:170px !important">Email:</label>
			    <span>&nbsp;<?=$reference2_email;?></span>	  
			
		</li>
		
	</ul>
	</li>
	   <li>
	<ul>   
	   <li>
		<h3 class="form-title">Essay:</h3>
		<div class="form-title">
			<div class="form-details">&nbsp;<?=$essay_ifmr;?></div>
		</div>
	   </li>
	   
	   <li>
            	<h3 class="form-title">Declaration</h3>
            	
		<div style="float: left; width: 100%">I, &nbsp; <span style="width:300px; border-bottom: 1px solid #000; display: inline-block">&nbsp;<?php echo (isset($middleName) && $middleName!='')?$firstName." ".$middleName." ".$lastName:$firstName." ".$lastName;?></span>
		, hereby declare that the particulars given in the application form are true and correct and will be supported by original
documents when asked for. I am also aware that in the event of any information being found incorrect or misleading my candidature shall
be liable to cancellation by the Institute at any time.

                
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
	</li>
    </div>
    <div class="clearFix"></div>
</div>