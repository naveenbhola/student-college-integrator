
    <tr>
     <td valign="top" align="center">
      <table width="96%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
       
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left;">Hi <?php echo ucwords($userName); ?>,</td>
       </tr>
       <tr>
        <td valign="top" height="6"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">First of all, thank you so much for constantly contributing to <strong>Shiksha Ask & Answer</strong>. A lot of students are getting valuable help and guidance from your answers. 
        </td>
       </tr>
       <tr>
        <td valign="top" height="17"></td>
       </tr>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
          <tr>
           <td valign="top" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
             <tr>
              <td width="13"></td>
              <td height="32" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#000000; font-weight:bold; text-align:left; line-height:18px;">Popularity stats of your previous answers</td>
              <td width="13"></td>
             </tr>
            </table>
           </td>
          </tr>
          <tr>
           <td width="13"></td>
           <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tr>
              <td valign="top" height="8"></td>
             </tr>
             <tr>
              <td valign="top">
               <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                <tr>
                 <td valign="top" width="12" align="left"><img src="https://www.ieplads.com/mailers/2018/shiksha/contributor-06feb/images/bullet.png" /></td>
                 <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">Total answers given by you till date: <strong><?=$mailerData['answerCount']?></strong> </td>
                </tr>
                <tr>
                 <td valign="top" height="1" colspan="2"></td>
                </tr>
                <tr>
                 <td valign="top" width="12" align="left"><img src="https://www.ieplads.com/mailers/2018/shiksha/contributor-06feb/images/bullet.png" /></td>
                 <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;"> Page views of all the answers you have given: <strong><?=$mailerData['totalViewCount']?></strong></td>
                </tr>
                <tr>
                 <td valign="top" height="1" colspan="2"></td>
                </tr>
                <tr>
                 <td valign="top" width="12" align="left"><img src="https://www.ieplads.com/mailers/2018/shiksha/contributor-06feb/images/bullet.png" /></td>
                 <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">Total upvotes to all your answers: <strong><?=$mailerData['totalUpvotesCount']?></strong>
                 </td>
                </tr>
               </table>
              </td>
             </tr>
             <tr>
              <td valign="top" height="6"></td>
             </tr>
            </table>
           </td>
           <td width="13"></td>
          </tr>
         </table>
        </td>
       </tr>
       <tr>
        <td valign="top" height="10"></td>
       </tr>
       <?php if(!empty($unansweredQuestionsFromTopic) || !empty($solrResults)){ ?>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">We have shortlisted a few questions that you'll be able to answer better than anyone else. Please help confused students by answering the questions below:</td>
       </tr>
       <?php } ?>
       <tr>
        <td valign="top" height="15"></td>
       </tr>
       <?php if(!empty($unansweredQuestionsFromTopic)){?>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
          <tr>
           <td valign="top" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
             <tr>
              <td width="13"></td>
              <td height="32" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#000000; font-weight:bold; text-align:left; line-height:18px;">Unanswered Questions from Topics you know</td>
              <td width="13"></td>
             </tr>
            </table>
           </td>
          </tr>
          <tr>
          <td width="13"></td>
          <td valign="top">
          <?php 
          $i = 1;
          foreach ($unansweredQuestionsFromTopic as $msgId => $msgText) { $questionUrl = getSeoUrl($msgId, 'question').'?openAnsBox=1';
          $questionUrl = add_query_params($questionUrl, 'utm_term='.$utmTerm['topic']);
          ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tr>
              <td valign="top" height="5"></td>
             </tr>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333333; text-align:left; line-height:19px;"><?=$msgText?></td>
             </tr>
             <tr>
              <td valign="top" height="6"></td>
             </tr>
             <tr>
              <td valign="top"><a href="<?=$questionUrl?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#03818d; text-align:left; text-decoration:none;">Write Answer</a></td>
             </tr> 
             <tr>
              <td valign="top" height="9"></td>
             </tr> 
             
             <?php if($i != count($unansweredQuestionsFromTopic)){?>
               <tr>
                <td valign="top" height="1" bgcolor="#f1f1f1"></td>
               </tr> 
               <tr>
                <td valign="top" height="5"></td>
               </tr>
             <?php } ?> 
             </table>
           <?php $i++;} ?>
            </td>
           <td width="13"></td>
          </tr>
         </table>
        </td>
       </tr>
       <tr>
        <td valign="top" height="10"></td>
       </tr>
       <?php
        } 

      if(!empty($solrResults)){ ?>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
          <tr>
           <td valign="top" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
             <tr>
              <td width="13"></td>
              <td height="32" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#000000; font-weight:bold; text-align:left; line-height:18px;">Unanswered questions similar to your last answered question</td>
              <td width="13"></td>
             </tr>
            </table>
           </td>
          </tr>
          <tr>
           <td width="13"></td>
           <td valign="top">
          <?php $i = 1; foreach ($solrResults as $key => $value) {

            $questUrl = getSeoUrl($value['id'], 'question').'?openAnsBox=1';
            $questUrl = add_query_params($questUrl, 'utm_term='.$utmTerm['similar']);?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tr>
              <td valign="top" height="5"></td>
             </tr>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333333; text-align:left; line-height:19px;"><?=$value['name']?></td>
             </tr>
             <tr>
              <td valign="top" height="6"></td>
             </tr>
             <tr>
              <td valign="top"><a href="<?=$questUrl?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#03818d; text-align:left; text-decoration:none;">Write Answer</a></td>
             </tr> 
             <tr>
              <td valign="top" height="9"></td>
             </tr> 
            
             <?php 
             if($i != count($solrResults)){ ?>
                <tr>
                  <td valign="top" height="1" bgcolor="#f1f1f1"></td>
                </tr> 
               <tr>
                <td valign="top" height="5"></td>
               </tr>
            <?php } ?> 

            </table>
           
           <?php $i++;}?>
           </td>
           <td width="13"></td>
          </tr>
         </table>
        </td>
       </tr>
        <tr>
        <td valign="top" height="10"></td>
       </tr>
       <?php }
       ?>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
          <tr>
           <td valign="top" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
             <tr>
              <td width="13"></td>
              <td height="32" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#000000; font-weight:bold; text-align:left; line-height:18px;">Find other unanswered questions from topics of your interest below:</td>
              <td width="13"></td>
             </tr>
            </table>
           </td>
          </tr>
          <tr>
           <td width="13"></td>
           <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tr>
              <td valign="top" height="13"></td>
             </tr>
             <tr>
              <td valign="top">
               <?php 
               $i = 1;
               foreach ($countUnansweredQuestion as $tagId => $count) { 
                $tagPageUrl = getSeoUrl($tagId, 'tag').'?type=unanswered';
                
                if($i == 3){
                  $width = 140;
                }else {
                  $width = 185;
                }?>
               <table width="<?=$width?>" align="left" border="0" cellspacing="0" cellpadding="0">
                <tr>
                 <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333333; text-align:left;"><?=$tagNames[$tagId]?></td>
                </tr>
                <tr>
                 <td valign="top" height="3"></td>
                </tr>
                <tr>
                 <td valign="top"><a href="<?=$tagPageUrl?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#03818d; text-align:left; text-decoration:none;"><?=$count?> Unanswered Qns</a></td>
                </tr>
                <tr>
                 <td valign="top" height="10"></td>
                </tr>
               </table>
               <?php $i++;}?>
               <table width="140" align="left" border="0" cellspacing="0" cellpadding="0">
                <tr>
                 <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333333; text-align:left;">View & Answer from</td>
                </tr>
                <tr>
                 <td valign="top" height="3"></td>
                </tr>
                <tr>
                 <td valign="top"><a href="<?php echo SHIKSHA_ASK_HOME_URL.'/unanswers'?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#03818d; text-align:left; text-decoration:none;">My Q&A Homepage</a></td>
                </tr>
                <tr>
                 <td valign="top" height="10"></td>
                </tr>
               </table>
              </td>
             </tr>
             <tr>
              <td valign="top" height="4"></td>
             </tr>
             <tr>
              <td valign="top" height="1" bgcolor="#f1f1f1"></td>
             </tr>
             <tr>
              <td valign="top" height="6"></td>
             </tr>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">Explore and Follow more topics of your interest. This will help us to improve the questions we suggest you. <a href="<?php echo SHIKSHA_HOME.'/tags'?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="color:#03818d; text-decoration:none;">Explore Topics</a></td>
             </tr>
             <tr>
              <td valign="top" height="9"></td>
             </tr>
            </table>
           </td>
           <td width="13"></td>
          </tr>
         </table>
        </td>
       </tr>
      </table>
     </td>
    </tr>
    <tr>
     <td valign="top" height="13"></td>
    </tr>
   
   
