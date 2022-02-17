<tr>
  <td valign="top" align="center">
    <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; min-width:280px;" width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
      <tbody>

        <?php 
        $i = 1;
        foreach($articleIds as $articleId) { 
          $article = $articles[$articleId]; 
          ?>

        <tr>
          <td valign="top" height="16"></td>       
        </tr>

        <tr>    
          <td valign="top">
            <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; min-width:280px;" width="75%" cellspacing="0" cellpadding="0" border="0" align="left">
              <tbody>
                <tr>
                  <td valign="top">
                    <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" width="100%" cellspacing="0" cellpadding="0" border="0">
                      <tbody>
                        <tr>
                          <td valign="top" height="3"></td>
                        </tr>
                        <tr>
                          <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#04828c; font-weight:bold; text-align:left;" valign="top"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>?utm_source=<?php echo $utm_source;?>&utm_medium=<?php echo $utm_medium;?>&utm_campaign=<?php echo $utm_campaign;?><!-- #widgettracker --><!-- widgettracker# -->" style="color: #04828c;text-decoration: none;"><?php echo trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle']; ?></a></td>
                        </tr>
                        <tr>
                          <td valign="top" height="5"></td>
                        </tr>
                        <tr>
                          <td style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#2e3030; font-weight:normal; text-align:justify;" valign="top"><?php
                                $articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
                                if(strlen($articleSummary) > 225) {
                                    $articleSummary = substr($articleSummary,0,225).'...';
                                }
                                echo $articleSummary;
                              ?></td>
                        </tr>
                        <tr>
                          <td valign="top" height="7"></td>
                        </tr>

                        <tr>
                          <td valign="top"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>?utm_source=<?php echo $utm_source;?>&utm_medium=<?php echo $utm_medium;?>&utm_campaign=<?php echo $utm_campaign;?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#04828c; font-weight:normal; text-align:left;">Read more</a></td>
                        </tr>

                        <tr>
                          <td valign="top" height="7"></td>
                        </tr>

                      </tbody>
                    </table>
                  </td>
                  <td width="15"></td>
                </tr>
              </tbody>
            </table>
            <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" width="121" cellspacing="0" cellpadding="0" border="0" align="left">
              <tbody>
                <tr>
                  <td valign="top"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>?utm_source=<?php echo $utm_source;?>&utm_medium=<?php echo $utm_medium;?>&utm_campaign=<?php echo $utm_campaign;?><!-- #widgettracker --><!-- widgettracker# -->"><img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/newsletter/article_default2.gif'; ?>" width="121" height="99"></a></td>
                </tr>
                <tr>
                  <td valign="top" height="2"></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>



        <?php if($i != count($articles)) { ?>
          <tr>
            <td valign="top" height="13"></td>
          </tr>

          <tr>
            <td valign="top" height="1" bgcolor="#c5c1c1"></td>       
          </tr>

        <?php }
         
          $i++;
        } ?>
              
        <tr>
          <td valign="top" height="21"></td>
        </tr>

        <tr>
          <td valign="top" align="left">
            <table width="116" cellspacing="0" cellpadding="0" border="0" align="left">
              <tbody>
                <tr>
                  <td style="border-radius:2px; -webkit-border-radius:2px; -moz-border-radius:2px;" height="26" bgcolor="#ee9521" align="center"><a href="<?php echo $newsArticlesPageUrl; ?>?utm_source=<?php echo $utm_source;?>&utm_medium=<?php echo $utm_medium;?>&utm_campaign=<?php echo $utm_campaign;?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#ffffff; font-weight:bold; text-decoration:none; display:block; line-height:26px;">More Updates</a></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>

      </tbody>
    </table>
  </td>
</tr>

