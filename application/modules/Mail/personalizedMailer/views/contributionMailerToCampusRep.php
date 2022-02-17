
    <tr>
     <td valign="top" align="center">
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
       <tr>
        <td valign="top" height="15"></td>
       </tr>
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
       <?php if(!empty($questionsOnCourse) || !empty($questionsOnInst)){ ?>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">We have shortlisted a few questions that you'll be able to answer better than anyone else. Please help confused students by answering the questions below:</td>
       </tr>
       <?php } ?>
       <tr>
        <td valign="top" height="15"></td>
       </tr>
       <?php if(!empty($questionsOnCourse)){?>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
                   <tr>
           <td valign="top" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
             <tbody><tr>
              <td width="13"></td>
              <td height="32">
               <table width="67%" align="left" border="0" cellspacing="0" cellpadding="0" style="min-width:250px;">
                <tbody><tr>
                 <td height="32" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#000000; font-weight:bold; text-align:left; line-height:18px;">Unanswered questions on your Course</td>
                </tr>
               </tbody></table>
               <table width="31%" align="left" border="0" cellspacing="0" cellpadding="0" style="min-width:170px;">
                <tbody><tr>
                 <td height="32"><a href="<?php echo $viewAllQuesOnCourse ?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#03818d; text-align:left; line-height:18px; text-decoration:none;">View all Unanswered Qns</a></td>
                </tr>
               </tbody></table>
              
              </td>
              <td width="13"></td>
             </tr>
            </tbody></table>
           </td>
          </tr>
          <tr>
          <td width="13"></td>
          <td valign="top">
          <?php 
          $i = 1;
          foreach ($questionsOnCourse as $userId => $msgData) { $questionUrl = getSeoUrl($msgData['msgId'], 'question').'?openAnsBox=1';
            $questionUrl = add_query_params($questionUrl, 'utm_term='.$utmTerm['course']);
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tr>
              <td valign="top" height="5"></td>
             </tr>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333333; text-align:left; line-height:19px;"><?=$msgData['question']?></td>
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
             <?php if($i != count($questionsOnCourse)){?>
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

      if(!empty($questionsOnInst)){ ?>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
          <tr>
           <td valign="top" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
             <tbody><tr>
              <td width="13"></td>
              <td height="32">
               <table width="67%" align="left" border="0" cellspacing="0" cellpadding="0" style="min-width:250px;">
                <tbody><tr>
                 <td height="32" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#000000; font-weight:bold; text-align:left; line-height:18px;">Unanswered questions on your College</td>
                </tr>
               </tbody></table>
               <table width="31%" align="left" border="0" cellspacing="0" cellpadding="0" style="min-width:170px;">
                <tbody><tr>
                 <td height="32"><a href="<?php echo $viewAllQuesOnInst ?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#03818d; text-align:left; line-height:18px; text-decoration:none;">View all Unanswered Qns</a></td>
                </tr>
               </tbody></table>
              
              </td>
              <td width="13"></td>
             </tr>
            </tbody></table>
           </td>
          </tr>
          <tr>
           <td width="13"></td>
           <td valign="top">
          <?php $i =1; foreach ($questionsOnInst as $userId => $value) {
              $questUrl = getSeoUrl($value['msgId'], 'question').'?openAnsBox=1';
              $questUrl = add_query_params($questUrl, 'utm_term='.$utmTerm['institute']);?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tr>
              <td valign="top" height="5"></td>
             </tr>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333333; text-align:left; line-height:19px;"><?=$value['question']?></td>
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
             <?php if($i != count($questionsOnInst)){?>
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
       <?php
       if(!empty($otherQuestionOnCourseHier)){ ?>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
          <tr>
           <td valign="top" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
             <tr>
              <td width="13"></td>
              <td height="32" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#000000; font-weight:bold; text-align:left; line-height:18px;"><?=$thirdSectionHeading?></td>
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
            <?php $i =1; foreach ($otherQuestionOnCourseHier as $userId => $msgData) { $questionUrl = getSeoUrl($msgData['msgId'], 'question').'?openAnsBox=1';
              $questionUrl = add_query_params($questionUrl, 'utm_term='.$utmTerm['other']);?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tr>
              <td valign="top" height="5"></td>
             </tr>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333333; text-align:left; line-height:19px;"><?=$msgData['question']?></td>
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
             <?php if($i != count($otherQuestionOnCourseHier)){?>
               <tr>
                <td valign="top" height="1" bgcolor="#f1f1f1"></td>
               </tr> 
               <tr>
                <td valign="top" height="5"></td>
               </tr>
             <?php } ?> 
             </table>
           <?php $i++; } ?>
            </td>
             </tr>
             <tr>
              <td valign="top" height="4"></td>
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
       <?php } ?>
       <tr>
              <td valign="top" height="9"></td>
             </tr>
       <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">Explore and Follow more topics of your interest. This will help us to improve the questions we suggest you. <a href="<?php echo SHIKSHA_HOME.'/tags'?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="color:#03818d; text-decoration:none;">Explore Topics</a></td>
      </tr>
      </table>
     </td>
    </tr>
    <tr>
     <td valign="top" height="13"></td>
    </tr>
   
   
