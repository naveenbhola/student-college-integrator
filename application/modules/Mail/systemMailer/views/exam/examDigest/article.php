<?php
$heading = "Articles related to ".$article['examName'];
if($totalCount==1){
	$heading = "Article related to ".$article['examName'];
}
?>
<tr>
                 <td valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                   <tr>
                    <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:16px; color:#666666;"><?php echo $heading;?></td>
                   </tr>
                   <tr>
                    <td valign="top" height="13"></td>
                   </tr>
                   <tr>
                    <td valign="top">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
		     <?php $articleLoopCount = 0;
                            if($totalCount>1){
                                foreach($article['examPageArticles'] as $key=>$data){
                                        if($articleLoopCount==2 || ($totalCount==2 && ($totalCount-$articleLoopCount)==1)){
                                               break;
                                        }
                        ?>
                      <tr>
                       <td valign="top" width="16"><img src="http://ieplads.com/mailers/2017/shiksha/exam-digest-07sept/images/bullet.png" /></td>
                       <td valign="top" colspan="2"><a href="<?php echo $data['url'];?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#1f65a6; text-decoration:none;"><?php echo $data['blogTitle'];?></a></td>
                      </tr>
                      <tr>
                       <td valign="top" height="6" colspan="3"></td>
                      </tr>
		      <?php $articleLoopCount++;}} ?>
                      <tr>
                       <td valign="top" colspan="3">
                        <table width="68%" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                         <tr>
                          <td width="16" valign="top"><img src="http://ieplads.com/mailers/2017/shiksha/exam-digest-07sept/images/bullet.png" /></td>
                          <td valign="top"><a href="<?php echo $article['examPageArticles'][$articleLoopCount]['url'];?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#1f65a6; text-decoration:none;"><?php echo $article['examPageArticles'][$articleLoopCount]['blogTitle'];?></a></td>
                         </tr>
                         <tr>
                          <td valign="top" height="10" colspan="2"></td>
                         </tr>
                        </table>
			<?php if($totalCount>3){ ?>
                        <table width="160" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                         <tr>
                          <td valign="top"><a href="<?php echo $viewAllLink;?><!-- #AutoLogin --><!-- AutoLogin# -->" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#1f65a6; font-weight:bold;" target="_blank">View All Articles</a></td>
                         </tr>
                         <tr>
                          <td valign="top" height="10"></td>
                         </tr>
                        </table>
			<?php } ?>
                       </td>
                      </tr>
                      <tr>
                       <td valign="top" height="7" colspan="3"></td>
                      </tr>
                      <tr>
                       <td valign="top" height="1" bgcolor="#e0dddd" colspan="3"></td>
                      </tr>
                    </table>

                    </td>
                   </tr>
                  </table>
                 </td>
                </tr>

