     <td width="564" bgcolor="#ffffff" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f; text-align:left;">
        <tr>
          <td colspan="3" height="15"></td>
        </tr>
        <tr>
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
          <td width="508" align="center"><table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
              <tr>
                <td height="15"></td>
              </tr>
              <tr>
                <td width="508">Dear <?php echo ucfirst($other['firstname']);?>,<br />
                  <br />
					We have received your submission for mentorship program.  We will match you with   
					an appropriate mentor based on your submitted location, branch & exam preferences. <br/>
					You will receive a mail with the mentor's details in 3-5 business days. <br/>
              </tr>
            </table></td>
            <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
            </tr>
            <tr>
          		<td colspan="3" height="11"></td>
        	</tr>
            <tr>
            <td></td>
          <td bgcolor="#eeeeee" style="padding:10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:24px; text-align:left;">
              <tr>
                <td><strong>Your submitted preferences</strong>:</td>
              </tr>
              <tr>
                <td>Exams taken/planned: <?php echo implode(", ", $examName); ?>
                


                </td>
              </tr>
              <tr>
                <td>Preferred locations: <?php echo implode(", ", $main['location']); ?></td>
              </tr>
             <tr>
                <td>Preferred branches: <?php echo implode(", ", $main['branches']); ?></td>
              </tr>
            
            </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="20" colspan="3"></td>
        </tr>
        <tr>
        <td></td>
        <td>In the meantime, check out some of the engineering related information:<br/>
				<ul>
				<li><a href="<?=SHIKSHA_HOME?>/top-engineering-colleges-in-india-rankingpage-44-2-0-0-0<!-- #AutoLogin --><!-- AutoLogin# -->"> Top ranked engineering colleges  </a><br/></li>
				<li><a href="<?=SHIKSHA_HOME?>/engineering-exams-dates<!-- #AutoLogin --><!-- AutoLogin# -->"> Engineering Exam Calendar </a><br/></li>
				<li><a href="<?=SHIKSHA_HOME?>/jee-mains-college-predictor<!-- #AutoLogin --><!-- AutoLogin# -->"> JEE Main College Predictor </a><br/></li>
				<li><a href="<?=SHIKSHA_SCIENCE_HOME_PREFIX?>/engineering-news-articles-coursepage<!-- #AutoLogin --><!-- AutoLogin# -->"> News & Articles on Engineering </a><br/></li>
				<li><a href="<?=SHIKSHA_SCIENCE_HOME_PREFIX?>/engineering-questions-coursepage<!-- #AutoLogin --><!-- AutoLogin# -->"> Engineering Q&A from students like you </a><br/></li>
		</td>
		<td></td>
		</tr>
		<tr>
          <td height="20" colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td height="11">Best wishes,<br />
            <font color="#c76d32">Shiksha.</font><font color="#426491">com</font></td>
          <td></td>