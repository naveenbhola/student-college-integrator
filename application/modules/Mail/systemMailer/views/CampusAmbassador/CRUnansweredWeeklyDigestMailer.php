<td width="564" bgcolor="#ffffff" align="center">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f;">
          <tr>
            <td colspan="3" height="20"></td>
          </tr>
          <tr>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
            <td width="524" align="center">
            	<table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
                  <tr>
                    <td>Dear <?php echo $crName;?>,</td>
                  </tr>
		  <?php /*if($potentialEarn >0) {?>
		  <tr>
                    <td height="20"></td>
                  </tr>
                  <tr>
                    <td width="524">Earn upto <strong>Rs.<?=$potentialEarn; ?></strong> by answering questions asked about your college.</td>
		    </tr>
		<?php }*/ ?>
		
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524">You have the following unanswered questions to answer. Click on the below questions to start answering.</td>
		    </tr>
		  <tr>
			<tr><td height="15"></td></tr>
		    <td width="524">
			<ol>
			<?php
			foreach($ques as $q)
			{
			?>
				<li><a href="<?=$urlOfLandingPage;?><!-- #AutoLogin --><!-- AutoLogin# -->"><?=$q->msgTxt;?></a></li>
			<?php
			}
			?>
			</ol>
		    </td>
                  </tr>
		  <tr><td height="15"></td></tr>
		  <tr><td>Best wishes,</td></tr>
		  <tr><td>Shiksha</td></tr>
                </table>
			</td>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td></td>
            <td valign="top">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:block;">
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524" valign="top">
                    </td>
                  </tr>
                </table>
            </td>
            <td></td>
