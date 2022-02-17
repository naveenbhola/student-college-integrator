<tr>
  <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #e8e8e8; border-radius:5px 5px 5px 5px; background-color:#ffffff">
      <tr>
        <td height="26"></td>
      </tr>
      <tr>
        <td align="center">
          <table width="80%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="21%">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="center" style="font-family:open sens, Arial, Helvetica, sans-serif; font-size:14px; color:#333333; font-weight:bold;"><?php echo (($articles[0]['relatedDate']) ? ucwords(date('l',strtotime($articles[0]['relatedDate']))) : ucwords(date('l'))); ?></td>
                              </tr>
                              <tr>
                                <td height="10"></td>
                              </tr>
                              <tr>
                                <td height="76" align="center">
                                  <table width="76" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td bgcolor="#ff9933">
                                        <table width="76" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td height="76" align="center" background="<?php echo SHIKSHA_HOME; ?>/public/images/circal.jpg" style="font-family:open sens, Arial, Helvetica, sans-serif; font-size:40px; color:#ffffff; font-weight:bold;"><?php echo (($articles[0]['relatedDate']) ? date('d',strtotime($articles[0]['relatedDate'])) : date('d')); ?></td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td height="10"></td>
                              </tr>
                              <tr>
                                <td align="center" style="font-family:open sens, Arial, Helvetica, sans-serif; font-size:14px; color:#333333; font-weight:bold;"><?php echo (($articles[0]['relatedDate']) ? ucwords(date('M',strtotime($articles[0]['relatedDate']))) : ucwords(date('M'))); ?> <?php echo (($articles[0]['relatedDate']) ? ucwords(date('Y',strtotime($articles[0]['relatedDate']))) : ucwords(date('Y'))); ?></td>
                              </tr>
                            </table>
                          </td>
                          <td width="6%" style="border-right:solid 1px #d7d7d7;"></td>
                          <td width="6%"></td>
                          <td width="67%">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#333333; line-height:17px; font-weight:bold;">
                                  <a href="<?php echo SHIKSHA_HOME.$articles[0]['url']; ?>~ImportantDatesMailer<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="color:#333333; text-decoration:none;">
                                    <?php echo trim($articles[0]['mailerTitle']) ? trim($articles[0]['mailerTitle']) : $articles[0]['blogTitle']; ?>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td height="18"></td>
                              </tr>
                              <tr>
                                <td style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; color:#666666; line-height:17px;">
                                  <?php
                                    $articleSummary = trim($articles[0]['mailerSnippet']) ? trim($articles[0]['mailerSnippet']) : $articles[0]['summary'];
                                      if(strlen($articleSummary) > 100) {
                                        $articleSummary = substr($articleSummary,0,100).'...';
                                      }
                                    echo $articleSummary;
                                  ?>
                                </td>
                              </tr>
                              <tr>
                                <td height="18"></td>
                              </tr>
                              <tr>
                                <td>
                                  <table border="0" cellspacing="0" cellpadding="0" style="max-width:130px;">
                                    <tr>
                                      <td width="130" height="35" align="center" bgcolor="#009999" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; font-weight:bold; border-radius:5px;">
                                        <a href="<?php echo SHIKSHA_HOME.$articles[0]['url']; ?>~ImportantDatesMailer<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="color:#ffffff; text-decoration:none; cursor:pointer; display:block; width:100%; line-height:27px;">Read more</a>
                                      </td>
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
                    <td height="26"></td>
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
<?php foreach($articles as $article) {
  if($article['blogId'] !== $articleIds[0]) {
?>
<tr>
  <td bgcolor="#f1f1f1" height="14"></td>
</tr>
<tr>
  <td bgcolor="#f1f1f1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:solid 1px #e8e8e8; border-radius:5px; background-color:#ffffff">
      <tr>
        <td>
          <table width="228" border="0" cellspacing="0" cellpadding="0" align="left">
            <tr>
              <td align="left">
                <img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/newsletter/img1.jpg' ?>" width="228" height="189" />
              </td>
            </tr>
          </table>
          <table width="218" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td width="18"></td>
              <td height="30"></td>
            </tr>
            <tr>
              <td></td>
              <td style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666; line-height:17px; font-weight:bold;">
                <a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~ImportantDatesMailer<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="color:#666666; text-decoration:none;"><?php echo trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle']; ?></a>
              </td>
            </tr>
            <tr>
              <td></td>
              <td height="18"></td>
            </tr>
            <tr>
              <td></td>
              <td style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; color:#666666; line-height:17px;">
                <?php
                  $articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
                  if(strlen($articleSummary) > 100) {
                    $articleSummary = substr($articleSummary,0,100).'...';
                  }
                  echo $articleSummary;
                ?>
                <a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~ImportantDatesMailer<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="color:#009999;">Read more</a>
              </td>
            </tr>
            <tr>
              <td></td>
              <td height="30"></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
<?php } 
} ?>
<tr>
  <td height="14"></td>
</tr>