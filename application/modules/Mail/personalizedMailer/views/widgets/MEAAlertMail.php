<?php 
$streamName = $params['stream']['name'];
if($params['stream']['id'] == 1 && $params['stream']['level'] == 'PG'){
  $streamName = 'MBA';
}else if($params['stream']['id'] == 21){
  $streamName = 'Government';
}
?><tr>
  <td style="font-family: Verdana, Geneva, sans-serif; font-size:13px; color:#323232;">Dear <!-- #username --><!-- username# -->,</td>
  </tr>
<tr>
  <td height="9" align="center" valign="top"></td>
  </tr>
<tr>
  <td style="font-family: Verdana, Geneva, sans-serif; font-size:12px; color:#323232; line-height:19px;"><strong>Here are some important <?php echo $streamName ?> exam dates to mark on your calendar.</strong><br>
    Learn more about the exam syllabus, cut-offs and download question papers by clicking on the respective exam names.  </td>
</tr>
<tr>
  <td height="16" valign="top" bgcolor="#f7f7f7">
    <table border="0" cellspacing="0" cellpadding="5" align="center" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border: solid 1px #e1e1e1;width: 100%;font-family: Verdana, Geneva, sans-serif; font-size:11px; color:#666666;" bgcolor="#fafafa">
      <tr>
        <td width="5" bgcolor="#03818d"></td>
        <td height="43" align="center" bgcolor="#03818D" style="font-family: Verdana, Geneva, sans-serif; font-size:13px; color:#ffffff;"><strong>Exam</strong></td>
        <td width="5" bgcolor="#03818d" style=" border-right:solid 1px #20b3c0;"></td>
        <td height="43" align="center" bgcolor="#03818D" style="font-family: Verdana, Geneva, sans-serif; font-size:13px; color:#ffffff; border-right:solid 1px #20b3c0;"><strong>Event</strong></td>
        <td align="center" bgcolor="#03818D" style="font-family: Verdana, Geneva, sans-serif; font-size:13px; color:#ffffff;"><strong>Date</strong></td>
      </tr>
      <?php 
      foreach ($params['formattedDates'] as $groupId => $groupData) {
        $rowspan = count($groupData);
        $row = '';
        $flag = true;
        $trCount = 0;
        foreach ($groupData as $group) {
          $row = '<tr>';
          $trCount++;

          $examName = $group['examName'];
          if(strlen($group['examName']) > 16){
            $examName = substr($group['examName'], 0, 13) . '...';
          }
          $eventName = $group['event_name'];
          if(strlen($group['event_name']) > 50){
            $eventName = substr($group['event_name'], 0, 47) . '...';
          }

          if($flag){
            $row .= '<td aaa="1" bgcolor="#FFFFFF" style=""></td>';
            $row .= '<td aaa="2" rowspan="'.$rowspan.'" bgcolor="#ffffff" style="border-bottom:solid 1px #d5d4d4;">';
            $row .= '<strong><a style="text-decoration:none; color:#008489;" href="'.SHIKSHA_HOME.$group['url'].'<!-- #widgettracker --><!-- widgettracker# -->">'.$examName.'</a></strong>';
            $row .= '</td>';
            $row .= '<td aaa="3" bgcolor="#FFFFFF" style=" border-right:solid 1px #d5d4d4;"></td>';
          }
          if(!$flag){
            if($trCount!=$rowspan){
              $row .= '<td aaa="6" bgcolor="#FFFFFF"></td>';
              $row .= '<td aaa="7" bgcolor="#FFFFFF" style="border-right:solid 1px #d5d4d4;"></td>';
            }else{
              $row .= '<td aaa="6" bgcolor="#FFFFFF" style="border-bottom:1px solid #d5d4d4;"></td>';
              $row .= '<td aaa="7" bgcolor="#FFFFFF" style="border-bottom:1px solid #d5d4d4; border-right:solid 1px #d5d4d4;"></td>';
            }
          }
          $flag = false;
          
          $row .= '<td aaa="4" bgcolor="#FFFFFF" style=" border-right:solid 1px #d5d4d4; border-bottom:solid 1px #d5d4d4;">';
          $row .= $eventName;
          $row .= '</td>';
          
          $row .= '<td aaa="5" bgcolor="#FFFFFF" style=" border-right:solid 1px #d5d4d4; border-bottom:solid 1px #d5d4d4;">';
          if($group['start_date'] == $group['end_date'] || $group['end_date'] == '' || $group['end_date'] == '0000-00-00'){
            $row .= date('M j', strtotime($group['start_date']));
          }else{
            $row .= date('M j', strtotime($group['start_date'])).' - '.date('M j', strtotime($group['end_date']));
          }
          $row .= '</td>';
          
          $row .= '</tr>';
        $html .= $row;
        }
      }
      echo $html;
      ?>      
    </table>
  </td>
</tr>
<tr>
  <td height="20" valign="top" colspan="5"></td>
</tr>
<tr>
  <td height="18" align="center" valign="top" colspan="5">
    <table width="320" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td height="31" align="center">
          <?php 
          if(strlen($streamName) > 13){
            $streamPlaceholder = 'More Relevant Exams';
          }else{
            $streamPlaceholder = 'More '.$streamName.' Entrance Exams';
          }
          ?>
          <a href="<?php echo SHIKSHA_HOME.$params['stream']['hierarchy'] ?>/exams-st-<?php echo $params['stream']['id']?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family: Verdana, Geneva, sans-serif; font-size:14px; color:#ffffff; text-decoration:none; display:block; line-height:31px;background-color: #f39d38;width: max-content;width: -webkit-max-content;width: -moz-max-content; padding: 0 15px;"><?php echo $streamPlaceholder ?></a>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td height="16" valign="top"></td>
</tr>