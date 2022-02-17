<table width="92%" border="1" cellspacing="0" cellpadding="6" style="font-family:Tahoma; font-size:13px; color:#474a4b; line-height:17px; border-collapse:collapse; text-align:left;" bordercolor="#e1e4e8">
          <tr bgcolor="#e1e4e8">
            <td colspan="2" style="font-family:Arial; font-size:16px; color:#474a4b">Institutes' Comparison</td>
          </tr>
         
	  <tr>
            <td width="50%" valign="top" style="border-bottom:0px;"><a style="font-size:14px; color:#0065e8;" href="<?php echo SHIKSHA_HOME."/getListingDetail/".$compare[0]['instituteid']."/institute~CompareTableTemplate"; ?><!-- #widgettracker --><!-- widgettracker# -->"><strong><?php echo htmlentities($compare[0]['InstituteTitle'])." ,".$compare[0]['locality']; ?></strong></a><br />
</td>
            <td width="50%" valign="top" style="border-bottom:0px;"><a style="font-size:14px; color:#0065e8;" href="<?php echo SHIKSHA_HOME."/getListingDetail/".$compare[1]['instituteid']."/institute~CompareTableTemplate"; ?><!-- #widgettracker --><!-- widgettracker# -->"><strong><?php echo htmlentities($compare[1]['InstituteTitle'])." ,".$compare[1]['locality']; ?></strong></a><br />
</td>
          </tr>
	  
          <tr>
            <td style="border-top:0px; border-bottom:0px;"><div style="width:100%" align="center">
	    <img <?php if($compare[0]['HeaderImage']){ ?>	 
		 src="<?php echo $compare[0]['HeaderImage']; ?>" 
	    <?php } else { ?>
		  src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/mrec_b_iimt.jpg"
		  <?php } ?> vspace="0" hspace="0" align="absmiddle" style="max-width:193px; padding-top:10px; width:inherit;" /></div></td> 
            <td style="border-top:0px; border-bottom:0px;"><div style="width:100%" align="center"><img
	  <?php if($compare[1]['HeaderImage']){ ?>	 
		 src="<?php echo $compare[1]['HeaderImage']; ?>" 
	    <?php } else { ?>
		  src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/mrec_b_iimt.jpg"
		  <?php } ?>
		     
		     vspace="0" hspace="0" align="absmiddle" style="max-width:193px; padding-top:10px; width:inherit;" /></div></td>
          </tr>
          <tr>
		    <?php if($compare[0]['showOFLink']) {?>
            <td style="border-top:0px;" align="center"><table border="0" cellspacing="0" cellpadding="0" width="140">
                  <tr>
                    <td height="35" width="140" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/ShineBTNbg.gif" bgcolor="#FFD933" align="center" style="border:1px solid #e8b363; border-radius:4px"><a href="<?php echo $compare[0]['OFlink']; ?>~Compare.php<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial; font-size:14px; color:#4a4a4a; text-decoration:none; line-height:30px; display:block;">Apply online now</a></td>
                  </tr>
                </table></td>
	    <?php } ?><?php if($compare[1]['showOFLink']) {?>

            <td style="border-top:0px;" align="center"><table width="140" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="35" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/ShineBTNbg.gif" bgcolor="#FFD933" align="center" style="border:1px solid #e8b363; border-radius:4px"><a href="<?php echo $compare[1]['OFlink']; ?>~Compare.php<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial; font-size:14px; color:#4a4a4a; text-decoration:none; line-height:30px; display:block;">Apply online now</a></td>
                  </tr>
                </table></td><?php } ?>
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Course Name</font><br /><?php echo $compare[0]['CourseTitle']; ?>
</td>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Course Name</font><br /><?php echo $compare[1]['CourseTitle']; ?>
</td>
          </tr>
       
          <tr>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Alumni Rating</font><br /><div>
          <?php 		$finalFeedbackForFirstInstitute = $compare[0]['alumnisReviews'];
		if(isset($finalFeedbackForFirstInstitute) && $finalFeedbackForFirstInstitute > 0){	      
	    for($i=0; $i<$finalFeedbackForFirstInstitute; $i++){
					
					if($i<$finalFeedbackForFirstInstitute){
						echo '<img src="'.SHIKSHA_HOME.'/public/images/starg.gif" />';
					}
					else{
						echo '<img src="'.SHIKSHA_HOME.'/public/images/stars.gif" />';
					}
				}
		    ?>
		   <?php echo $finalFeedbackForFirstInstitute."/5"; } else{ ?>
		    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
		    <?php }?>
</td>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Alumni Rating</font><br /><div>
             <?php 		$finalFeedbackForSecondInstitute = $compare[1]['alumnisReviews'];
			      if(isset($finalFeedbackForSecondInstitute) && $finalFeedbackForSecondInstitute > 0){
	     
	    for($i=0; $i<$finalFeedbackForSecondInstitute; $i++){
					
					if($i<$finalFeedbackForSecondInstitute){
						echo '<img src="'.SHIKSHA_HOME.'/public/images/starg.gif" />';
					}
					else{
						echo '<img src="'.SHIKSHA_HOME.'/public/images/stars.gif" />';
					}
				}
		    ?>
		    <?php echo $finalFeedbackForSecondInstitute."/5";  }  else{ ?>
		    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
		    <?php } ?>
</td>
          </tr>
	  
          
	  
	  <tr>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Course Duration</font><br /><?php if($compare[0]['CourseDuration']){ echo $compare[0]['CourseDuration']; } else{ ?>
		    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
		    <?php }?></td>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Course Duration</font><br /><?php if($compare[1]['CourseDuration']){ echo $compare[1]['CourseDuration']; } else{ ?>
		    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
		   <?php  }?></td>
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Mode of Study</font><br /><?php if($compare[0]['ModeOfStudy']){ echo $compare[0]['ModeOfStudy']; } else{ ?>
		    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
		    <?php }?></td>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Mode of Study</font><br /><?php if($compare[1]['ModeOfStudy']){ echo $compare[1]['ModeOfStudy']; } else{ ?>
		    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
		    <?php }?></td>
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Affiliated To</font><br />
		    
		    <?php if($compare[0]['Affiliations'] && count($compare[0]['Affiliations'] > 0) ){ ?>
		    
		    <span style="color:#000000; padding-right:6px;">&bull;</span>
	   <?php $approvalsAndAffiliations = array(); 
	   foreach($compare[0]['Affiliations'] as $affiliation) {
	  $approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
		    }
	  echo implode(', ',$approvalsAndAffiliations); ?>
	    
	    <?php } else{?> 
	    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
	    <?php } ?>
	    
	    </td>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Affiliated To</font><br />
		    
		    <?php if($compare[1]['Affiliations'] && count($compare[1]['Affiliations'] > 0) ){ ?>
		    
		    <span style="color:#000000; padding-right:6px;">&bull;</span><?php $approvalsAndAffiliations = array();
	    foreach($compare[1]['Affiliations'] as $affiliation) {
	  $approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
		    }
	  echo implode(', ',$approvalsAndAffiliations); ?>
	    
	    <?php } else{?> 
	    <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" /> 
	    <?php } ?>
	    
	    </td>
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Average Salary (p.a.)</font><br />
		    <?php  if($compare[0]['AverageSalary'] > 0){   ?>    
		    INR <?php echo $compare[0]['AverageSalary']; ?>
		     <?php
		    }
		    else{?>
			   <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		    
		   <?php }  ?>
		   </td>
	    <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Average Salary (p.a.)</font><br />
		    <?php  if($compare[1]['AverageSalary'] > 0){   ?>   
		   INR <?php echo $compare[1]['AverageSalary']; ?>
		    
		    <?php
		    }
		    else{?>
			   <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		   <?php }  ?>
	    
	    </td>
          </tr>
          <tr>
            
	    
	    
	    <td><font color="#b25f19" style="font-size:14px;">Top Recruiting Companies</font>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:11px; line-height:18px; font-family:Tahoma; font-size:13px; color:#474a4b;">
		 <?php $recruitingCompanies = $compare[0]['RecruitingCompanies'];
		 if(($recruitingCompanies) && (!(count($recruitingCompanies) == 1 && !$recruitingCompanies[0]->getName()))){ ?>   
		    
               <?php $Companiescount = 0;
		    foreach($recruitingCompanies as $recruitment){ 
	       
		  if($Companiescount < 3 && ($recruitment->getname()) ) 	{   
	       ?>
		    
	          <tr>
                    <td width="10" valign="top"><span style="color:#000000; padding-right:6px;">&bull;</span></td>
                    <td><?php echo $recruitment->getname(); ?></td>
                  </tr>
		  <?php
			      } 		  $Companiescount++ ;
                    } ?>
		  
		  
		    <tr>
             <td colspan = "2" style="padding-left:15px " ><?php if($Companiescount > 3) { echo $Companiescount-3; ?> more recruiters <?php } ?> 
                 </td></tr>
		    <?php  }
		    else{?>
			<br />   <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		    
		   <?php } 
		    ?>
		    
		    
		    
	        </table>

            </td>
            <td><font color="#b25f19" style="font-size:14px;">Top Recruiting Companies</font>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:11px; line-height:18px; font-family:Tahoma; font-size:13px; color:#474a4b;">
		    <?php $recruitingCompanies = $compare[1]['RecruitingCompanies'];
		 if(($recruitingCompanies) && (!(count($recruitingCompanies) == 1 && !$recruitingCompanies[0]->getName()))){    
		       $Companiescount = 0;
		    foreach($recruitingCompanies as $recruitment){
			      if($Companiescount < 3 && ($recruitment->getname()) ) 	{       
	       ?>
	          <tr>
                    <td width="10" valign="top"><span style="color:#000000; padding-right:6px;">&bull;</span></td>
                    <td><?php echo $recruitment->getname(); ?></td>
                  </tr>
                   <?php
				} 		  $Companiescount++ ;

                    } ?>
		    <tr>
             <td colspan = "2" style="padding-left:15px " ><?php if($Companiescount > 3) { echo $Companiescount-3; ?> more recruiters <?php } ?> 
                 </td></tr>
		     <?php  }
		    else{
			      ?>
			<br />   <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		    
		   <?php	     
		    }
		    
		    
		    ?>
		    
                </table>
            </td>
	    	    
	    
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px;">AICTE Approved</font>   <img <?php if(($compare[0]['isAICTEApproved'] == 'yes' )) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?>  vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">AICTE Approved</font>  <img <?php if(($compare[1]['isAICTEApproved'] == 'yes' )) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?>  vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px;">UGC Recognised</font>  <img <?php if(($compare[0]['isUGCRecognised'] == 'yes' )) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?>  vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">UGC Recognised</font>  <img <?php if(($compare[1]['isUGCRecognised'] == 'yes' )) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?>  vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px;">DEC Approved</font>  <img <?php if(($compare[0]['isDECApproved'] == 'yes' )) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?>  vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">DEC Approved</font>  <img <?php if(($compare[1]['isDECApproved'] == 'yes' )) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?>  vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          <tr>
           
	    <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Fees</font><br />
	    
	    <?php
	    if($compare[0]['CourseFees']->getvalue()){
	    echo $compare[0]['CourseFees'];
	    }
	    else{?>
			   <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		    
		   <?php  }
	    ?>
	    
	    </td>
            <td><font color="#b25f19" style="font-size:14px; line-height:25px;">Fees</font><br />
	    <?php
	    if($compare[1]['CourseFees']->getvalue()){
	    echo $compare[1]['CourseFees'];
	    }
	    else{ ?>
			   <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		    
		   <?php   
	    }
	    ?>
	    
	    </td>
          </tr>
	   <?php
		    
		    if(count($compare[0]['ExamsDetails']) > 0 || count($compare[1]['ExamsDetails']) > 0) {
	       ?>
          <tr>
            <td><font color="#b25f19" style="font-size:14px;">Eligibility</font>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:11px; line-height:18px; font-family:Tahoma; font-size:13px; color:#474a4b;">
		    
		    <?php
		    
		    if(count($compare[0]['ExamsDetails']) > 0){
		    foreach($compare[0]['ExamsDetails'] as $exam){ 
	       ?>
                  <tr>
                    <td width="10" valign="top"><span style="color:#000000; padding-right:6px;">&bull;</span></td>
                    <td><?php echo $exam->getacronym(); ?></td>
                 
		 <?php  }}
		 else{
		?>
			 <br />  <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		    
		   <?php   
		 }
		 ?>
                </table>

            </td>
            <td><font color="#b25f19" style="font-size:14px;">Eligibility</font>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:11px; line-height:18px; font-family:Tahoma; font-size:13px; color:#474a4b;">
                 
		 <?php
		    if(count($compare[1]['ExamsDetails']) > 0){
		    foreach($compare[1]['ExamsDetails'] as $exam){ 
	       ?> <tr>
		    <td width="10" valign="top"><span style="color:#000000; padding-right:6px;">&bull;</span></td>
                    <td><?php echo $exam->getacronym(); ?></td>
                  </tr>
                 <?php  }}
		 else{?>
			 <br /> <br /> <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" vspace="0" hspace="0" align="absmiddle" width="14" height="18" />  
		    
		   <?php    }
		 
		 ?>
		 
		 
                </table>
            </td>
          </tr>
	  <?php  } ?>
          <tr>
		
            <td><font color="#b25f19" style="font-size:14px;">Dual Degree</font>  <img <?php if(is_object($compare[0]['offersDualDegree'])) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">Dual Degree</font>  <img <?php if(is_object($compare[1]['offersDualDegree'])) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          <tr>
            <td><font color="#b25f19" style="font-size:14px;">Foreign Travel</font> <img <?php if(is_object($compare[0]['offersForeignTravel'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">Foreign Travel</font>  <img <?php if(is_object($compare[1]['offersForeignTravel'])) { ?>src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          <tr>
		    
            <td><font color="#b25f19" style="font-size:14px;">Free Laptop</font>  <img <?php if(is_object($compare[0]['providesFreeLaptop'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">Free Laptop</font>  <img <?php if(is_object($compare[1]['providesFreeLaptop'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          
	  <tr>
            <td><font color="#b25f19" style="font-size:14px;">In-Campus Hostel</font>  <img <?php if(is_object($compare[0]['providesHostelAccomodation'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">In-Campus Hostel</font>  <img <?php if(is_object($compare[1]['providesHostelAccomodation'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          
	  
	  <tr>
            <td><font color="#b25f19" style="font-size:14px;">WiFi Campus</font>  <img <?php if(is_object($compare[0]['HasWifiCampus'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td><font color="#b25f19" style="font-size:14px;">WiFi Campus</font>  <img <?php if(is_object($compare[1]['HasWifiCampus'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
       
       
          <tr>
            <td style="border-bottom:0px;"><font color="#b25f19" style="font-size:14px;">AC Campus</font>  <img <?php if(is_object($compare[0]['HasACCampus'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
            <td style="border-bottom:0px;"><font color="#b25f19" style="font-size:14px;">AC Campus</font>  <img <?php if(is_object($compare[1]['HasACCampus'])) { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/checkSign.gif" <?php } else { ?> src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/crossSign.gif" <?php } ?> vspace="0" hspace="0" align="absmiddle" width="14" height="18" /></td>
          </tr>
          <tr>
            
	    
	    <td align="center"><table border="0" cellspacing="0" cellpadding="0" width="140">
                  <tr>  <?php if($compare[0]['showOFLink']) {?>
                    <td height="35" width="140" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/ShineBTNbg.gif" bgcolor="#FFD933" align="center" style="border:1px solid #e8b363; border-radius:4px"><a href="<?php echo $compare[0]['OFlink']; ?>~Compare.php<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial; font-size:14px; color:#4a4a4a; text-decoration:none; line-height:30px; display:block;">Apply online now</a></td><?php } ?>
                  </tr>
                </table></td>
            <td align="center"><table width="140" border="0" cellspacing="0" cellpadding="0">
                  <tr><?php if($compare[1]['showOFLink']) {?>
                    <td height="35" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/ShineBTNbg.gif" bgcolor="#FFD933" align="center" style="border:1px solid #e8b363; border-radius:4px"><a href="<?php echo $compare[1]['OFlink']; ?>~Compare.php<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial; font-size:14px; color:#4a4a4a; text-decoration:none; line-height:30px; display:block;">Apply online now</a></td><?php } ?>
                  </tr>
                </table></td>
	    
	    
          </tr>
        </table>