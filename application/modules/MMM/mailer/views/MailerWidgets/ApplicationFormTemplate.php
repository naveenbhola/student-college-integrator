<table border="0" cellspacing="0" cellpadding="0" align="center" width="93%">
  <tr>
    <td valign="bottom">
    <table border="0" align="left" cellspacing="0" cellpadding="0" style="max-width:383px;">
      <tr>
        <td width="340" height="80" valign="top" style="padding-bottom:5px;"> <a href="<?php echo $OFlink; ?>~ApplicationForm<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:21px; text-decoration:none; color:#0065e8; font-family:Georgia;"><?php echo $instituteName;  ?></a><font face="Arial, Helvetica, sans-serif" color="#2c2c2c" style="font-size:13px;"><br />
<?php  echo $locality;  ?>, India</font></td>
      </tr>
    </table>
    <table border="0" width="87" cellspacing="0" cellpadding="0">
              <tr>
                <td height="73" valign="top"><img src="<?php echo $HeaderImage;  ?>" width="87" height="73" vspace="0" hspace="0" align="right" /></td>
              </tr>
            </table>
     </td>
  </tr>
  <tr>
    <td width="441" valign="top">
    		<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#474a4b; font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:18px;">
              <tr>
                <td><a href="<?php echo $OFlink; ?>~ApplicationForm<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-size:15px; text-decoration:none; color:#4a4a4a; font-family:Arial, Helvetica, sans-serif; line-height:28px;"><strong><?php echo $CourseTitle;  ?></strong></a>
                  </td>
              </tr>
              <tr>
              	<td height="8"></td>
              </tr>
              <tr>
              	<td width="343"><?php echo $CourseDuration.", ".$CourseType.", ".$CourseLevel; ?></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="0" align="left" width="276">
                          <tr>
                            
			    <?php if(isset($CourseFees) && trim($CourseFees) > 0){?>
			    <td width="110" style="font-size:13px; color:#717171;" height="20">
			      <span style="display:block;">Fee: <strong><?php echo $CourseFees;  ?></strong></span>
			    </td>
			     <td width="20" align="center"><font color="#CCCCCC">|</font></td>
			   <?php }
			    ?>
			    <?php if(trim($ExamsDetails) > 0){?>
				<td width="115" style="font-size:13px; color:#717171;" height="20"><span style="display:block;">Eligibility: <strong><?php echo $ExamsDetails->getAcronym();  ?></strong></span></td>
			<?php }
			    ?>	
                          </tr>
                        </table></td>
              </tr>
              <tr>
              	<td height="8"></td>
              </tr>
              <tr>
              	<td>
                	<table border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:12px; color:#474a4b; max-width:343px;">
              <tr bgcolor="#edeef0">
                <td width="180" align="center" height="29">Last Date: <strong><?php echo $OnlineFormLastDay;  ?></strong></td>
                <td width="10" align="center"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/sepIMG.jpg" width="3" height="15" vspace="0" hspace="0" align="absmiddle" /></td>
                <td width="148" align="center">Form: <strong>Rs <?php echo $OnlineFormFees;  ?></strong></td>
              </tr>
              <tr>
              	<td colspan="4" height="10"></td>
              </tr>
            </table>
                </td>
              </tr>
            </table>
            
        </td>
        </tr>
        <tr>
          <td>
            <table width="183" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#474a4b;">
              <tr>
                <td width="183" bgcolor="#ffda3e" height="37" align="center" valign="top" style="border:1px solid #e8b363; border-radius:2px;"><a href="<?php echo $OFlink; ?>~ApplicationForm<!-- #widgettracker --><!-- widgettracker# -->" title="Apply online now" target="_blank" style="text-decoration:none; font-size:18px; color:#4b4b4b; line-height:39px; display:block"><strong>Apply online now</strong></a></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td valign="top" height="50" style="padding-top:8px; font-family:Arial; font-size:11px; color:#888888;">This online application is approved by <?php echo $instituteName;  ?></td>
        </tr>
      </table>