<?php if($mailer_type == 'MMM') {
    $linkExt = "~recommendation<!-- #widgettracker --><!-- widgettracker# -->";
} else {
    $linkExt = "<!-- #AutoLogin --><!-- AutoLogin# -->";
} ?>
<tr>
    <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:#e7e7e7 solid 1px;">
            <tr>
                <td width="9"></td>
                <td align="left">
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#2d2d2d;">
                        <tr>
                            <td height="30"  align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000;">More Details &amp; Insights</td>
                        </tr>
                        
                        <tr>
                            <td align="left">
                                <?php if($reviewCount >= 3) { ?>
                                    <table border="0" width="175" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="22" align="left">
                                                <a href="<?php echo $reviewUrl.$linkExt; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;"><?php echo $reviewCount; ?> Alumini Reviews</a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                
                                <?php if($articleCount > 0) { ?>
                                    <table border="0" width="175" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="22" align="left">
                                                <a href="<?php echo $articleUrl.$linkExt; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;"><?php echo $articleCount; ?> News &amp; Articles</a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>

                                <?php if($mediaCount > 0) { ?>
                                    <table border="0" width="175" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="22" align="left">
                                                <a href="<?php echo $mediaUrl.$linkExt; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;"><?php echo $mediaCount; ?> Campus Photos</a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                
                                <?php if($anaCount > 0) { ?>
                                    <table border="0" width="175" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="22" align="left">
                                                <a href="<?php echo $anaUrl.$linkExt; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;"><?php echo $anaCount; ?> Answered Questions</a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                
                                <?php if($showPlacements > 0) { ?>
                                    <table border="0" width="175" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="22" align="left">
                                                <a href="<?php echo $placementUrl.$linkExt; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;">Placements &amp; Companies</a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                
                                <?php if(!empty($admission)) { ?>
                                    <table border="0" width="175" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="22" align="left">
                                                <a href="<?php echo $admissionUrl.$linkExt; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;">Admission Process</a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                
                                <?php if(!empty($eligibility)) { ?>
                                    <table border="0" width="175" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="22" align="left">
                                                <a href="<?php echo $eligibilityUrl.$linkExt; ?>" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#03818d; text-decoration:none; line-height:22px;">Eligibility &amp; Exams</a>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>