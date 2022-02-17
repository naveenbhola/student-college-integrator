<table width="92%" border="0" cellspacing="0" cellpadding="0" align = "center" bgcolor="#f2f2f2">
<tr>
  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" height="10" style="border-top:2px solid #dcdddf;"><span style="font-size:1px;"> </span></td>
      </tr>
      <tr>
        <td width="15"></td>
        <td height="40"><font face="Georgia, Times New Roman, Times, serif"
color="#020001" style="font-size:25px;">Institutes &amp; courses</font></td>
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
        <td bgcolor="#f2f2f2" ><font face="Arial, Helvetica, sans-serif" color="#4a4a4a" style="font-size:15px;"  ><strong><?php echo $recommendation_details['name']; ?> Institutes</strong> </font></td>
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
                       
                        ?>
      <?php
                if($RecomendationsCount == 5){
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
              <td width="100" style="font-size:11px; padding:10px 0 10px 15px; color:#717171;"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:18px; "> Its because you viewed <?php echo htmlentities($institute_details['courseName']); ?> in <?php echo htmlentities($institute_details['instituteName']); ?>, we thought you would be interested in the following: </font></td>
              <?php
                }elseif($algo == "similar_institutes" && $similar_institutes != 'true' ){
                    $similar_institutes = 'true';
                    ?>
              <td width="100" style="font-size:11px; padding:10px 0 10px 15px; color:#717171;"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:18px;"> Based on your interest <?php echo htmlentities($institute_details['courseName']); ?> in <?php echo htmlentities($institute_details['instituteName']); ?>, we thought you would be interested in the following : </font></td>
              <?php
                }elseif($algo == "profile_based" && $profile_based != 'true' ){
                    $profile_based = 'true';
                    ?>
              <td  width="100" style="font-size:11px; padding:10px 0 10px 15px;  color:#717171;"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:18px;"> Based on your education interest <?php echo htmlentities($educationInterest['mode']); ?> , <?php echo htmlentities($educationInterest['coursename']); ?>
                <?php if($educationInterest['city'] != NULL){ echo "in ".$educationInterest['city']; } ?>
                , we thought you would be interested in the following: </font></td>
              <?php
                }
                elseif($algo == "profile_based" && !empty($studyAbroadEducationInterest)  && $profile_based != 'true'  ){
                    $profile_based = 'true';
                    ?>
              <td width="100" style="font-size:11px; padding:10px 0 10px 15px; color:#717171;"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:18px;"> Based on your education interest <?php echo htmlentities($studyAbroadEducationInterest['category']); ?>
                <?php if($studyAbroadEducationInterest['countries'] != ''){ echo " & preferred location ".$studyAbroadEducationInterest['countries']; } ?>
                , we thought you would be interested in the following: </font></td>
              <?php
                }
            ?>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding:0 15px;"><table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
            <tr>
              <td valign="bottom"><table border="0" align="left" cellspacing="0" cellpadding="0" style="max-width:383px;">
                  <tr>
                    <td width="340" height="80" valign="top" style="padding-bottom:5px;"><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:21px; text-decoration:none; color:#0065e8; font-family:Georgia;"><?php echo $recommendation_detail['institute_name']; ?></a><font face="Arial, Helvetica, sans-serif" color="#2c2c2c" style="font-size:13px;"><br />
                      <?php echo $recommendation_detail['city']; ?>, <?php echo $recommendation_detail['country']; ?></font></td>
                  </tr>
                </table>
                <table border="0" width="87" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="73" valign="top"><?php if($recommendation_detail['logo']){ ?>
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
                    <td width="343"><?php echo $recommendation_detail['course_name']; ?> Approved by AICTE, Ministry of HRD, Govt. of India </td>
                  </tr>
                  <tr>
                    <td><table border="0" cellspacing="0" cellpadding="0" align="left" width="276">
                        <tr>
                          <td width="138" style="font-size:13px; color:#717171;" height="20">2 Years, Full Time</td>
                          <td width="138" style="font-size:13px; color:#717171;" height="20"><?php if(!empty($recommendation_detail['fees_value'])) { ?>
                            Fees: <?php echo $recommendation_detail['fees_unit']; ?> <?php echo $recommendation_detail['fees_value']; ?>
                            <?php } ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td height="8"></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td><table width="86" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr>
                      <td height="17" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/know_bg.gif" bgcolor="#feed97" style="border:1px solid #e9d58f; display:block;" align="center" width="86"><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:11px; text-decoration:none; color:#3d3d3d; height:17px; line-height:17px; display:block;">Know more</a></td>
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
                              <td style="border-bottom:1px solid #e2e2e2">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            </table>
            <?php
                }
        ?>
          </td>
      </tr>
      <?php
        }
        ?>
      <?php
        }
        ?>
        </table>
	</td>
</tr>
<tr>
	<td style="padding:0px 15px;"><table border="0" cellspacing="0" cellpadding="0" align="left" style="max-width:280px;">
    <tr>
      <td bgcolor="#f2f2f2" valign="top" height="25"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:16px;">Are you happy with our choice?:</font></td>
    </tr>
    <tr>
      <td width="298"><table width="40" align="left" border="0" cellspacing="0"
                        cellpadding="0">
          <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;" align="center"><a href="<?php echo SHIKSHA_HOME."/recommendation/recommendation/registerFeedback/<!-- #encodedemail --><!-- encodedemail# -->/<!-- #mailerId --><!-- mailerId# -->/<!-- #mailId --><!-- mailId# -->/YES~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:11px; text-decoration:none; color:#3465E8;">Yes</a></td>
            <td width="10"></td>
          </tr>
        </table>
        <table width="40" align="left" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;" align="center"><a href=" <?php echo SHIKSHA_HOME."/recommendation/recommendation/registerFeedback/<!-- #encodedemail --><!-- encodedemail# -->/<!-- #mailerId --><!-- mailerId# -->/<!-- #mailId --><!-- mailId# -->/NO~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:11px; text-decoration:none; color:#3465E8;">No</a></td>
          </tr>
          <tr>
            <td height="20"></td>
          </tr>
        </table></td>
    </tr>
  </table>
  	<table width="183" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#474a4b;">
    <tr>
      <td width="183" bgcolor="#ffda3e" height="30" align="center" valign="top" style="border:1px solid #e8b363; border-radius:2px;"><a href="<?php echo SHIKSHA_HOME."/categoryList/CategoryList/recoOnMail/".$user_id."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="Apply online now" target="_blank" style="text-decoration:none; font-size:11px; color:#4b4b4b; line-height:28px; display:block"><strong>View more recommendations</strong></a></td>
    </tr>
    <tr>
      <td height="10"></td>
    </tr>
  </table></td>
</tr>
</table>
