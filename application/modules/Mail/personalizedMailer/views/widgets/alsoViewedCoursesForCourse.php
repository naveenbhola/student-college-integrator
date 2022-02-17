<tr>
  <td height="10"></td>
</tr>
<tr>
  <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:#e7e7e7 solid 1px;">
      <tr>
        <td width="9"></td>
        <td align="left">
            <table border="0" cellspacing="0" cellpadding="0" widhth="100%" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#2d2d2d;">
              <tr>
              <td height="10"></td>
              </tr>
              <tr>
                <td  align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000;">Colleges Similar to <?php echo htmlentities($instituteName);?></td>
              </tr>
              <?php
                foreach ($alsoViewedCoursesForCourse as $alsoViewedCourses) {
              ?>  
                  <tr>
                  <td height="22" align="left">
                    <a style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;" target="_blank" href="<?php echo $alsoViewedCourses['courseUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->';?>" ><?php echo htmlentities($alsoViewedCourses['instituteName']);?></a></td>
                  </tr>
              <?php 
                } 
              ?>
            </table>
        </td>
      </tr>
    </table>
  </td>
</tr>