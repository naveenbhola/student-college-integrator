

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
            <td  align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000;">Exam Accepted by <?php echo htmlentities($instituteName);?></td>
          </tr>
          <tr>
            <td align="left">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
              <?php
              foreach ($courseExams as $key => $examDetail) {
                ?>  
                <td align="center">
                  <a target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;" href="<?php echo $examDetail['exam_url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" ><?php echo $examDetail['exam_name']?> </a>
                </td>
                <?php
                if($key <count($courseExams)-1){
                ?>
                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px;color:#999999; line-height:22px;" width="16" align="center">|</td>
                <?php
                } 
                } 
                ?>
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
