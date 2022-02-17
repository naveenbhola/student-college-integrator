<tr>
    <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:#e7e7e7 solid 1px;">
            <tbody>
                <tr>
                    <td width="9"></td>
                    <td align="left">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#2d2d2d;">
                            <tbody>
                                <tr><td height="10"></td></tr>
                                <tr>
                                    <td width="400" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000;">Exams Accepted by <?php echo $streamName; ?> Colleges</td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <?php 
                                                    $i = 0;
                                                    foreach($examData as $exam){
                                                        ?>
                                                        <td align="center">
                                                            <a href="<?php echo $exam['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;"><?php echo $exam['name']; ?></a>
                                                        </td>
                                                        <?php 
                                                            if($i < count($examData)-1){
                                                                ?>
                                                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#999999; line-height:22px;" width="16" align="center">|</td>
                                                                <?php
                                                            }
                                                            $i++;
                                                        ?>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table width="139" cellspacing="0" cellpadding="0" border="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tbody>
                                <tr>
                                    <td height="10"></td>
                                </tr>
                                <tr>
                                    <td height="25" align="center" style="border:#03818d solid 1px;">
                                        <a href="<?php echo $allExamPageUrl.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, sans-serif; color:#03818d; font-size:11px; display:block; line-height:25px; text-decoration:none;">
                                        <strong>View all Exams</strong></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="10"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td height="5"></td>
</tr>