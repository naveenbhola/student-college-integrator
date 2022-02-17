<table width="92%" border="0" cellspacing="0" cellpadding="0" align = "center" bgcolor="#f2f2f2">
<tr>
  <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" height="10" style="border-top:2px solid #dcdddf;"><span style="font-size:1px;"> </span></td>
      </tr>
      <tr>
        <td width="15"></td>
        <td height="40" align="left"><font face="Georgia, Times New Roman, Times, serif"
color="#020001" style="font-size:25px;">Institutes &amp; Courses</font></td>
      </tr>
    </table></td>
</tr>
<?php
    $recommendations_by_category = $data['recommendations'];
    foreach($recommendations_by_category as $category => $recommendation_details) {
    if(count($recommendations_by_category) > 1) {
      ?>
<tr>
  <td><table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td width="15" height="30"></td>
        <td bgcolor="#f2f2f2" ><font face="Arial, Helvetica, sans-serif" color="#4a4a4a" style="font-size:14px;"  ><strong><?php echo $recommendation_details['name']; ?> Institutes</strong> </font></td>
      </tr>
    </table></td>
</tr>
<?php
    }
    $category_recommendations_by_algo = $recommendation_details['recommendations'];
    $also_viewed = 'false';
    $similar_institutes = 'false';
    $profile_based = 'false';
    $RecomendationsCount = 0;
    $maxRecommendationCount = $isStudyAbroadRecommendation ? 3 : 5;

    foreach($category_recommendations_by_algo as $algo => $recommendation_details_by_algo)
    {
        ?>
<tr>
  <td>
       <table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
	  <?php  
        foreach($recommendation_details_by_algo as $recommendation_detail) {
           
            $recommendation_id = $recommendation_ids[$recommendation_detail['institute_id']];       
            $base_url = SHIKSHA_HOME."/recommendations/";
            $institute_url = $base_url."institute/$recommendation_id/0/1";
            $course_url = $base_url."course/$recommendation_id/0/1";
	    $brochure_url = $base_url."course/$recommendation_id/0/1/1";
	    $hasBrochure = $recommendation_detail['hasBrochure'];
	    $courseId = $recommendation_detail['course_id'];
                        ?>
      <?php
                if($RecomendationsCount == $maxRecommendationCount){
                    break;
                }
                ++$RecomendationsCount;
                ?>
     <tr>
        <td><table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
            <tr>
              <?php
               
                if($algo == "also_viewed" && $also_viewed != 'true' ){
                    $also_viewed = 'true';
                    ?>
              <td style="font-size:11px; padding:10px 10px 10px 15px; color:#717171;" align="left"><font face="Georgia, Times New Roman, Times, serif" color="#1e1d1d" style="font-size:16px; "> Since you viewed <?php echo htmlentities($institute_details['courseName']); ?> in <?php echo $isStudyAbroadRecommendation ? htmlentities($institute_details['universityName']) : htmlentities($institute_details['instituteName']); ?>, you may also be interested in learning more about the following institutes and courses:</font></td>
              <?php
                }elseif($algo == "similar_institutes" && $similar_institutes != 'true' ){
                    $similar_institutes = 'true';
                    ?>
              <td style="font-size:11px; padding:10px 10px 10px 15px; color:#717171;" align="left"><font face="Georgia, Times New Roman, Times, serif" color="#1e1d1d" style="font-size:16px;"> Based on your interest in <?php echo htmlentities($institute_details['courseName']); ?> in <?php echo $isStudyAbroadRecommendation ? htmlentities($institute_details['universityName']) : htmlentities($institute_details['instituteName']); ?>, you may also be interested in learning more about the following institutes and courses:</font></td>
              <?php
                }elseif($algo == "profile_based" && $profile_based != 'true' ){
                    $profile_based = 'true';
                    ?>
              <td style="font-size:11px; padding:10px 10px 10px 15px;  color:#717171;" align="left"><font face="Georgia, Times New Roman, Times, serif" color="#1e1d1d" style="font-size:16px;"> Based on your interest in
				<?php
					if($educationInterest['mode'] && $educationInterest['mode'] != 'NA') {
						echo htmlentities($educationInterest['mode']).", ";
					}
				?>
				<?php echo htmlentities($educationInterest['coursename']); ?>
                <?php if($educationInterest['city'] != NULL){ echo "in ".$educationInterest['city']; } ?>, you may also be interested in learning more about the following institutes and courses:</font></td>
				<?php
                }
                elseif($algo == "profile_based" && !empty($studyAbroadEducationInterest)  && $profile_based != 'true'  ){
                    $profile_based = 'true';
                    ?>
              <td style="font-size:11px; padding:10px 10px 10px 15px; color:#717171;" align="left"><font face="Georgia, Times New Roman, Times, serif" color="#1e1d1d" style="font-size:16px;"> Based on your interest in <?php echo htmlentities($studyAbroadEducationInterest['category']); ?>
                <?php if($studyAbroadEducationInterest['countries'] != ''){ echo " & preferred location ".$studyAbroadEducationInterest['countries']; } ?>
                , you may also be interested in learning more about the following institutes and courses:</font></td>
              <?php
                }
            ?>
            </tr>
          </table></td>
      </tr>
     
     
     <?php if(!$isStudyAbroadRecommendation) { ?>
      <tr>
        <td style="padding:0 15px;"><table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
            <tr>
              <td valign="bottom"><table border="0" align="left" cellspacing="0" cellpadding="0" style="max-width:383px;">
                  <tr>
                    <td width="340" height="80" valign="top" style="padding-bottom:5px;" align="left"><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:18px; text-decoration:none; color:#0065e8; font-family:Georgia;"><?php echo $recommendation_detail['institute_name']; ?></a><font face="Arial, Helvetica, sans-serif" color="#2c2c2c" style="font-size:13px;"><br />
                      <?php echo $recommendation_detail['city']; ?>, <?php echo $recommendation_detail['country']; ?></font></td>
                  </tr>
                </table>
                <table border="0" width="87" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="83" valign="top"><?php if($recommendation_detail['logo']){ ?>
                      <img src="<?php echo $recommendation_detail['logo']; ?>" width="87" height="73" vspace="0" hspace="0" align="right" />
                      <?php } else { ?>
                      <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/mrec_b_iimt.jpg" width="87" height="73" vspace="0" hspace="0" align="right" />
                      <?php }  ?></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td width="441" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#474a4b; font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:18px;">
                  <tr>
                    <td width="343" align="left"><b><?php echo $recommendation_detail['course_name']; ?></b><br>
		      <?php
						$approvalsAndAffiliations = array();
						
						if(!empty($recommendation_detail['Approval'])){
						$approvalsAndAffiliations[] = $recommendation_detail['Approval'];
						}					
						if(!empty($recommendation_detail['Affiliations'])){
						$approvalsAndAffiliations[] = $recommendation_detail['Affiliations'];
						}					
						echo implode(', ',$approvalsAndAffiliations);
					?>
		    </td>
                 </tr>
                  <tr>
                    <td><table border="0" cellspacing="0" cellpadding="0" align="left" width="276">
                        <tr>
              <td width="138" style="font-size:13px; color:#717171;" height="20" align="left"><?php
	  if($recommendation_detail['course_duration'] && $recommendation_detail['course_duration_unit'] && $recommendation_detail['course_type']){
		echo $recommendation_detail['course_duration']." ".$recommendation_detail['course_duration_unit'].", ".$recommendation_detail['course_type'];
		} ?></td>
                          <td width="138" style="font-size:13px; color:#717171;" height="20"><?php if(!empty($recommendation_detail['fees_value'])) { ?>
                            Fees: <?php echo $recommendation_detail['fees_unit']; ?> <?php echo $recommendation_detail['fees_value']; ?>
                            <?php } ?></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td style="padding-top: 10px;"><table cellspacing="0" cellpadding="0" border="0" width="100">
                  <tbody>
                    <tr>
                      <td bgcolor="#feed97" background="" width="100" height="23" align="center" style="border:1px solid #e9d58f;display:block"><a target="_blank" style="text-decoration: none; color: rgb(61, 61, 61); min-height: 17px; display: block; font-size: 14px; line-height: 23px;" href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->"><b>Know More</b></a></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td><table width="100%" cellspacing="0" cellpadding="0" border="0" align="left">
                  <tbody>
                    <tr>
                      <td height="40"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td bgcolor="#e2e2e2" height="1"></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            </table>
	  </td>
      </tr>
      <?php
	  }
	  else if($isStudyAbroadRecommendation) { 
      ?>
           <tr>
        <td style="padding:0 15px;"><table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
            <tr>
              <td valign="bottom"><table border="0" align="left" cellspacing="0" cellpadding="0" style="max-width:383px;">
                  <tr>
                    <td width="340" height="80" valign="top" style="padding-bottom:5px;" align="left"><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:18px; text-decoration:none; color:#0065e8; font-family:Georgia;"><?php echo $abroadCourseData[$courseId]['universityName']; ?></a><font face="Arial, Helvetica, sans-serif" color="#2c2c2c" style="font-size:13px;"><br />
                      <?php echo $abroadCourseData[$courseId]['courseLocation']; ?></font></td>
                  </tr>
                </table>
                <table border="0" width="87" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="83" valign="top"><?php if($abroadCourseData[$courseId]['universityImageURL']){ ?>
                      <img src="<?php echo $abroadCourseData[$courseId]['universityImageURL']; ?>" width="87" height="73" vspace="0" hspace="0" align="right" />
                      <?php } else { ?>
                      <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/mrec_b_iimt.jpg" width="87" height="73" vspace="0" hspace="0" align="right" />
                      <?php }  ?></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td width="441" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#474a4b; font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:18px;">
                  <tr>
                    <td width="343" align="left"><b><?php echo $abroadCourseData[$courseId]['courseName']; ?></b><br>
		    </td>
                 </tr>
                  <tr>
                    <td><table border="0" cellspacing="0" cellpadding="0" align="left">
                        <tr>
                          <td width="138" style="font-size:13px; color:#717171;" height="20" align="left"><?php if(!empty($abroadCourseData[$courseId]['courseFees'])) { ?>
                            <?php echo $abroadCourseData[$courseId]['courseFees']; ?>
                            <?php } ?></td>
			  <td style="font-size:13px; color:#717171;" height="20"><?php if(count($abroadCourseData[$courseId]['courseExam'])) { ?>
                            <?php echo implode(', ', $abroadCourseData[$courseId]['courseExam']); ?>
                            <?php } ?></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td style="padding-top: 10px;"><table cellspacing="0" cellpadding="0" border="0" width="100">
                  <tbody>
                    <tr>
                      <td bgcolor="#feed97" background="" width="100" height="23" align="center" style="border:1px solid #e9d58f;display:block"><a target="_blank" style="text-decoration: none; color: rgb(61, 61, 61); min-height: 17px; display: block; font-size: 14px; line-height: 23px;" href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->"><b>Know More</b></a></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td><table width="100%" cellspacing="0" cellpadding="0" border="0" align="left">
                  <tbody>
                    <tr>
                      <td height="40"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td bgcolor="#e2e2e2" height="1"></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            </table>
	  </td>
      </tr>
      <?php
	    }
      }
      ?>         
      </table>
	</td>
</tr>
      <?php
        }
        ?>
      <?php
        }
        ?>
        
<tr>
	<td style="padding:0px 15px;">
	  <?php if(!$isStudyAbroadRecommendation) { ?>
	  <table cellspacing="0" cellpadding="0" border="0" width="220" style="font-family: Arial; font-size: 13px; color: rgb(71, 74, 75); display: block;">
    <tbody><tr>
      <td bgcolor="#ffda3e" width="220" height="32" align="center" style="border:1px solid #e8b363;border-radius:2px"><a href="<?php echo SHIKSHA_HOME."/categoryList/CategoryList/recoOnMail/".$user_id."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="View more recommendations" style="text-decoration: none; color: rgb(75, 75, 75); display: block; font-size: 13px; line-height: 30px;" target="_blank"><b>View more recommendations</b> &gt;&gt;</a></td>
    </tr>
    <tr>
      <td height="15"></td>
    </tr>
  </tbody></table>
	  <?php } ?>
	 <table border="0" cellspacing="0" cellpadding="0" align="left" width="100%" >
    <tr>
      <td bgcolor="#f2f2f2" valign="top" height="25" align="left"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:16px;">Do you think above courses are relevant?</font></td>
    </tr>
    <tr>
      <td width="298"><table width="40" align="left" border="0" cellspacing="0"
                        cellpadding="0">
          <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;" align="center"><a href="<?php echo SHIKSHA_HOME."/recommendation/recommendation/registerFeedback/<!-- #encodedemail --><!-- encodedemail# -->/<!-- #mailerId --><!-- mailerId# -->/<!-- #mailId --><!-- mailId# -->/YES/".$isStudyAbroadRecommendation."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:13px; text-decoration:none; color:#3465E8;">Yes</a></td>
            <td width="5" style="border-right:1px solid #cccccc"></td>
          </tr>
        </table>
        <table width="40" align="left" border="0" cellspacing="0" cellpadding="0">
          <tr>
	    <td width="5"></td>
            <td style="font-family:Arial, Helvetica, sans-serif;" align="center"><a href=" <?php echo SHIKSHA_HOME."/recommendation/recommendation/registerFeedback/<!-- #encodedemail --><!-- encodedemail# -->/<!-- #mailerId --><!-- mailerId# -->/<!-- #mailId --><!-- mailId# -->/NO/".$isStudyAbroadRecommendation."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:13px; text-decoration:none; color:#3465E8;">No</a></td>
          </tr>
          <tr>
            <td height="20" colspan="2"></td>
          </tr>
        </table></td>
    </tr>
  </table>
</td>
</tr>
</table>
