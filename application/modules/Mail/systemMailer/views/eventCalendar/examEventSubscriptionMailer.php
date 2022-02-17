<?php $this->load->view('systemMailer/eventCalendar/examCalendarHeader'); ?>
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
            <td>Hi <?=$firstname?>,</td>
          </tr>
          <tr>
            <td height="15"></td>
          </tr>
          <tr>
            <td>Alert for <?=$exam_name?>: <?=$event_name?></td>
          </tr>
          <tr>
            <td>When : <?=date('d-m-y', strtotime($exam_from_date))?> </td>
          </tr>
          <tr>
            <td><a href="<?=$calendar_url?>">Check Event on calender</a></td>
          </tr>
          <tr>
            <td></td>
          </tr>
          <tr>
            <td height="15"></td>
          </tr>
          <tr>
            <td>You are receiving this email at the account <em><?=$email?></em> because you set a notification for this event on the <a href="<?=$calendar_url?>">calendar</a>.</td>
          </tr>
          <tr>
            <td height="15"></td>
          </tr>
          <tr>
            <td>
              Best wishes, <br />
              <font color="#03828E">Shiksha</font><font color="#231E19"> team</font>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</td>
<?php $this->load->view('systemMailer/eventCalendar/examCalendarFooter'); ?>