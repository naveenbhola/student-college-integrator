            <table width="190" border="0" cellspacing="0" cellpadding="0" <?php echo $table_ui; ?> style="padding:5px;">
              <tr>
                <td height="15" bgcolor="#FFFFFF" style="border:1px solid #e0e0e0; border-radius:5px 5px 0 0; border-bottom:none;"></td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF" style="border-left:solid 1px #e0e0e0; border-right:solid 1px #e0e0e0;">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="40" style="border-bottom:solid 1px #dadada;"></td>
                          </tr>
                        </table>
                      </td>
                      <td width="75" align="center"><img src="<?php echo SHIKSHA_HOME.'/public/images/MOneRecMailer/icon4.gif'; ?>" width="75" height="75" /></td>
                      <td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="40" style="border-bottom:solid 1px #dadada;"></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td height="15" bgcolor="#FFFFFF" style="border-left:solid 1px #e0e0e0; border-right:solid 1px #e0e0e0;"></td>
              </tr>
              <tr>
                <td align="center" bgcolor="#FFFFFF" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666; font-weight:bold; border-left:solid 1px #e0e0e0; border-right:solid 1px #e0e0e0;">Recruiting Companies</td>
              </tr>
              <tr><td height="95" valign="top" bgcolor="#FFFFFF" style="border-left:solid 1px #e0e0e0; border-right:solid 1px #e0e0e0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <?php
                $companyCount = 0;
                $maxCompany = count($recommendationWidgets['recruiters']['companyLogo']) >= 2 ? 2 : count($recommendationWidgets['recruiters']['companyLogo']);
                foreach($recommendationWidgets['recruiters']['companyLogo'] as $logoURL) {
                  $companyCount++;
              ?>
              <tr>
                <td height="12" bgcolor="#FFFFFF"></td>
              </tr>
              <tr>
                <td height="20" align="center" bgcolor="#FFFFFF" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"><img src="<?php echo $logoURL; ?>" width="79" height="26" /></td>
              </tr>
              <?php 
                  if($companyCount == 2) {
                    break;
                  }
                } 
              ?>
              </table></td></tr>
              <tr>
                <td style="border-left:solid 1px #e0e0e0;border-right:solid 1px #e0e0e0" bgcolor="#FFFFFF" height="12"></td>
              </tr>
              <tr>
                <td height="20" align="center" bgcolor="#FFFFFF" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; color:#666666; border-left:solid 1px #e0e0e0; border-right:solid 1px #e0e0e0;">
                  <a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="View All <?php echo $recommendationWidgets['recruiters']['count']; ?> Companies" target="_blank" style="color:#009999;">
                    View All <?php echo $recommendationWidgets['recruiters']['count']; ?> Companies
                  </a>
                </td>
              </tr>
              <tr>
                <td height="22" bgcolor="#FFFFFF" style="border-left:solid 1px #e0e0e0; border-right:solid 1px #e0e0e0;border:1px solid #e0e0e0; border-radius:0 0 5px 5px; border-top:none;"></td>
              </tr>
              <tr>
                <td height="6"></td>
              </tr>
            </table>