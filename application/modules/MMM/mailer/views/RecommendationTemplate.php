
<?php
$recommendations_by_category = $data['recommendations'];
//_p($recommendations_by_category);

foreach($recommendations_by_category as $category => $recommendation_details) {
    if(count($recommendations_by_category) > 1) {
?>
        <div style="margin-left:20px; margin-bottom:15px;">  
            <p><strong><?php echo $recommendation_details['name']; ?> Institutes</strong></p> 
        </div>
<?php
    }
    $category_recommendations_by_algo = $recommendation_details['recommendations'];
    foreach($category_recommendations_by_algo as $algo => $recommendation_details_by_algo) {
?>
        <table border="0" cellspacing="0" cellpadding="0" align="center" width="92%">
<?php
        if(count($recommendation_details_by_algo) > 0) { ?>    
        <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="border-top:1px solid #e2e2e2;">
            <tr>
           <td height="77"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:25px;">Institutes & courses</font></td>
            </tr>
          </table></td>
      </tr>
      <?php } ?>  
<?php        
        foreach($recommendation_details_by_algo as $recommendation_detail) {
?>
            <?php
            if($count == 3){
                break;
            }            $count++;

            ?>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                        <tr>
                            <td>
                                <table border="0" width="126" cellspacing="0" cellpadding="0" align="left" style="margin:0 15px 0 0px;">
                                    <tr>
                                        <td>
                                        <?php if($recommendation_detail['logo']){ ?>
                                            <img src="<?php echo $recommendation_detail['logo']; ?>" width="126" height="106" vspace="0" hspace="0" align="left" />
                                        <?php } else { ?>	
                                            <img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/mrec_b_iimt.jpg" width="126" height="106" vspace="0" hspace="0" align="left" />
                                        <?php }  ?>	
                                        </td>
                                    </tr>
                                </table>
                                <table border="0" cellspacing="0" cellpadding="0" style="font-size:15px; color:#474a4b; font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:18px;">
                                    <tr>
                                        <td>
                                            <a href="<?php echo $recommendation_detail['course_url'];  ?><!-- #tracker --><!-- tracker# --> "target="_blank" style="font-size:16px; text-decoration:none; color:#0065e8; font-family:Georgia, 'Times New Roman', Times, serif;"><strong><?php echo $recommendation_detail['institute_name']; ?></strong></a><br/>
                                            <font color="#2c2c2c">
                                            <?php echo $recommendation_detail['city']; ?>, <?php echo $recommendation_detail['country']; ?></font><br/>
                                            <?php echo $recommendation_detail['course_name']; ?><br/>
                                            Approved by AICTE, Ministry of HRD, Govt. of India
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="30" valign="bottom">
                                            <table border="0" cellspacing="0" cellpadding="0" align="left">
                                                <tr>
                                                    <td width="100" style="font-size:11px; color:#717171;">2 Years, Full Time</td>
                                                </tr>
                                            </table>
                                            <table border="0" cellspacing="0" cellpadding="0" align="left">
                                                <tr>
                                                    <td width="100" style="font-size:11px; color:#717171;">
                                                    <?php if(!empty($recommendation_detail['fees_value'])) { ?>Fees:  <?php echo $recommendation_detail['fees_unit']; ?> <?php echo $recommendation_detail['fees_value']; ?><?php } ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="78" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td height="17" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/know_bg.gif" bgcolor="#feed97" style="border:1px solid #e9d58f;" align="center"><a href="<?php echo $recommendation_detail['course_url']; ?><!-- #tracker --><!-- tracker# -->" target="_blank" style="font-size:11px; text-decoration:none; color:#3d3d3d; height:17px; line-height:17px;">Know more</a></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                  </table>
                </td>
            </tr>
            
            <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="left">
                <tr>
                  <td height="20"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="1" width="15"></td>
                        <td style="border-bottom:1px solid #fdfdfd;"></td>
                        <td width="15"></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
<?php
        }
?>
        </table>
<?php
    }?>
  
  <div class= "center">
   </td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
        <tr>
          <td width="30" height="40"></td>
          <td width="15" bgcolor="#f2f2f2"></td>
          <td bgcolor="#f2f2f2" valign="top"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:18px;">Are you happy with our choice?:</font></td>
          <td bgcolor="#f2f2f2" valign="top" style="font-family:Arial, Helvetica, sans-serif;" align="right"><a href="#" target="_blank" style="font-size:12px; text-decoration:none; color:#3465e8;">80 more recommendations <span style="letter-spacing:-1px">&gt;&gt;</span></a></td>
          <td width="22" bgcolor="#f2f2f2"></td>
          <td width="29"></td>
        </tr>
      </table></td>
    <td></td></tr>
  <tr>
  
  <td></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
        <tr>
          <td width="30" height="45"></td>
          <td width="15" bgcolor="#f2f2f2" style="border-bottom:2px solid #dcdddf;"><font></font></td>
          <td bgcolor="#f2f2f2" valign="top" style="border-bottom:2px solid #dcdddf;"><table width="36" align="left" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/know_bg.gif" bgcolor="#feed97" style="border:1px solid #e9d58f; font-family:Arial, Helvetica, sans-serif;" align="center"><a href="#" target="_blank" style="font-size:11px; text-decoration:none; color:#3d3d3d; height:17px; line-height:17px;">Yes</a></td>
              </tr>
            </table>
            <table width="10" border="0" cellspacing="0" cellpadding="0" align="left">
              <tr>
                <td height="17" width="10" align="left"></td>
              </tr>
            </table>
            <table width="36" align="left" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" background="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/know_bg.gif" bgcolor="#feed97" style="border:1px solid #e9d58f; font-family:Arial, Helvetica, sans-serif;" align="center"><a href="#" target="_blank" style="font-size:11px; text-decoration:none; color:#3d3d3d; height:17px; line-height:17px;">No</a></td>
              </tr>
            </table></td>
          <td width="29"></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  </div>
<?php
}
?>
