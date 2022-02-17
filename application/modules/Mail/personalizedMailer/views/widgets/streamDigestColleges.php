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
                                    <td width="400" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000;">Popular <?php echo $streamName; ?> Colleges on Shiksha</td>
                                </tr>
                                <?php 
                                foreach ($instituteData as $row) {
                                    ?>
                                    <tr>
                                        <td height="22" align="left">
                                            <a href="<?php echo $row['url'].'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;"><?php echo $row['name']; ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <table width="139" cellspacing="0" cellpadding="0" border="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tbody>
                                <tr>
                                    <td height="10"></td>
                                </tr>
                                <tr>
                                    <td height="25" align="center" style="border:#03818d solid 1px;">
                                        <a href="<?php echo $ctpgLink.'<!-- #AutoLogin --><!-- AutoLogin# -->'; ?>" target="_blank" style="font-family:Arial, sans-serif; color:#03818d; font-size:11px; display:block; line-height:25px; text-decoration:none;">
                                        <strong><?php echo $ctpgButtonText; ?></strong></a>
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