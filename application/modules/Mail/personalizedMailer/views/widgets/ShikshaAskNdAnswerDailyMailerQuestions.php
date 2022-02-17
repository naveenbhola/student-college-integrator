<tr>
    <td valign="top">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <td valign="top" bgcolor="#f1f1f2">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #e8e8e8; border-radius:5px 5px 5px 5px; background-color:#ffffff; box-shadow: -2px 0 5px #e0e0e0;">
                            <tbody>
                                <tr>
                                    <td width="45"></td>
                                    <td>
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tbody>
                                                <tr>
                                                    <td height="30"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; color:#666666;">
                                                        <strong>
                                                            Please answer the question(s) below:
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="15"></td>
                                                </tr>
                                                <?php for($i=0; $i < count($threadData); $i++){?>
                                                <tr>
                                                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">
                                                        Q: <?php echo html_entity_decode($threadData[$i]['threadText']);?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="14%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666;">
                                                                        Asked by
                                                                    </td>
                                                                    <td width="27%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">
                                                                        <?php echo limitTextLength(htmlentities(ucfirst($threadData[$i]['threadOwnerFirstName']).' '.ucfirst($threadData[$i]['threadOwnerLastName'])), 15);?>
                                                                    </td>
                                                                    <td width="28%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666; border-left:1px solid #e1e1e1;border-right:1px solid #e1e1e1;">
                                                                        Answer(s)
                                                                            <font style="color:#333333;">
                                                                                <?php echo $threadData[$i]['threadAnswers'];?>
                                                                            </font>
                                                                    </td>
                                                                    <td width="31%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999;">
                                                                        <a target="_blank" href="<?php echo $threadData[$i]['threadUrl'];?><!-- #AutoLogin --><!-- AutoLogin# -->" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999; text-decoration:none;">
                                                                            Answer Now
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <?php if($i < (count($threadData) - 1)){?>
                                                <tr>
                                                    <td height="15" style="border-bottom:1px solid #e3e3e3;"></td>
                                                </tr>
                                                <?php }?>
                                                <?php }?>
                                                <!-- <tr>
                                                    <td height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">
                                                        Q: Which option is best: pursuing MBA or job?
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="14%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666;">
                                                                        Asked by
                                                                    </td>
                                                                    <td width="27%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">
                                                                        Bharat Singh
                                                                    </td>
                                                                    <td width="28%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666; border-left:1px solid #e1e1e1;border-right:1px solid #e1e1e1;">
                                                                        Answers
                                                                            <font style="color:#333333;">
                                                                                105
                                                                            </font>
                                                                    </td>
                                                                    <td width="31%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999;">
                                                                        <a target="_blank" href="#" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999; text-decoration:none;">
                                                                            Answer Now
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="15" style="border-bottom:1px solid #e3e3e3;"></td>
                                                </tr>
                                                <tr>
                                                <td height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">
                                                        Q: Which option is best: pursuing MBA or job?
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="14%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666;">
                                                                        Asked by
                                                                    </td>
                                                                    <td width="27%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">
                                                                        Prashant
                                                                    </td>
                                                                    <td width="28%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666; border-left:1px solid #e1e1e1;border-right:1px solid #e1e1e1;">
                                                                        Answers
                                                                        <font style="color:#333333;">
                                                                            0
                                                                        </font>
                                                                    </td>
                                                                    <td width="31%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999;">
                                                                        <a target="_blank" href="#" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999; text-decoration:none;">
                                                                            Answer Now
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="15" style="border-bottom:1px solid #e3e3e3;"></td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">Q: Which option is best: pursuing MBA or job? IIM Bangalore vs.<br> IIM Bangalore. Which option is best: pursuing MBA or job? IIM<br> Bangalore vs. ?</td>
                                                </tr>
                                                <tr>
                                                    <td height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="14%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666;">
                                                                        Asked by
                                                                    </td>
                                                                    <td width="27%" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#333333;">
                                                                        Ravishankar Prasad
                                                                    </td>
                                                                    <td width="28%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666; border-left:1px solid #e1e1e1;border-right:1px solid #e1e1e1;">
                                                                        Answers
                                                                        <font style="color:#333333;">
                                                                            72
                                                                        </font>
                                                                    </td>
                                                                    <td width="31%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999;">
                                                                        <a target="_blank" href="#" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999; text-decoration:none;">
                                                                            Answer Now
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                  <td height="15" style="border-bottom:1px solid #e3e3e3;"></td>
                                                </tr> -->
                                                <tr>
                                                  <td height="15"></td>
                                                </tr>
                                                <!-- <tr>
                                                  <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#048088;">You are only <font style="color:#003333;">2</font> points away from reaching level <font style="color:#003333;">&lt; name &gt;</font></td>
                                                </tr> -->
                                                <tr>
                                                  <td height="25"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="45"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="14" align="top"></td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>