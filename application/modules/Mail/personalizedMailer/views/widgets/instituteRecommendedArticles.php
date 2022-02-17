<tr>
  <td height="5"></td>
</tr>
<tr>
  <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:#e7e7e7 solid 1px;">
      <tr>
        <td width="9"></td>
        <td align="left">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#2d2d2d;">
          <tr><td height="10"></td></tr>
            <tr>
              <td  align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000;">Articles Related to <?php echo htmlentities($instituteName);?></td>
            </tr>
            <?php
            foreach ($instituteRecommendedArticles['articlesDetail'] as $articleDetail) {
              ?>  
              <tr>
                <td height="22" align="left">
                  <a target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;" href="<?php echo $articleDetail['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" ><?php echo htmlentities($articleDetail['blogTitle']);?></a></td>
              </tr>
              <tr>
              <?php 
              } 
              ?>
            </table>
            <table width="139" cellspacing="0" cellpadding="0" border="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                <tbody>
                  <tr>
                      <td height="10"></td>
                    </tr>
                    <?php 
                    if($instituteRecommendedArticles['articleCount']>3){
                      ?>
                    <tr>
                        <td height="25" align="center" style="border:#03818d solid 1px;">
                            <a target="_blank" style="font-family:Arial, sans-serif; color:#03818d; font-size:11px; display:block; line-height:25px; text-decoration:none;" href="<?php echo $instituteRecommendedArticles['allArticleURL'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" >
                              <strong>View all <?php echo $instituteRecommendedArticles['articleCount']?> Articles</strong></a>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                      <td height="10"></td>
                    </tr>
                </tbody>
            </table>
        </td>
      </tr>
    </table>
  </td>
</tr>