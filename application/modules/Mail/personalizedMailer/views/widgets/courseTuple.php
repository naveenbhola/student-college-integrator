<?php if($mailer_type == 'MMM') {
    $linkExt = "~recommendation<!-- #widgettracker --><!-- widgettracker# -->";
} else {
    $linkExt = "<!-- #AutoLogin --><!-- AutoLogin# -->";
} ?>
<tr>
    <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:#e7e7e7 solid 1px;">
            <tr>
                <td height="9"></td>
            </tr>
            
            <tr>
                <td align="left">
                    <table border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                        <tr>
                            <td width="9"></td>
                            <td align="left" width="204" height="156" style="border:#e7e7e7 solid 1px;">
                                <a href="<?php echo $instituteUrl.$linkExt; ?>"><img src="<?php echo $instituteLogoURL; ?>" width="204" height="156" alt="" /></a>
                            </td>
                            <td width="15"></td>
                        </tr>
                    </table>
                    
                    <table border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                        <tr>
                            <td width="9"></td>
                            <td align="left" width="297">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td height="14"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #333333; font-size:13px; line-height:18px;">
                                            <strong>
                                                <a href="<?php echo $instituteUrl.$linkExt; ?>" target="_blank" style="color: #333333; text-decoration:none;">
                                                    <?php echo $instituteName; ?>
                                                </a>
                                            </strong>
                                        </td>
                                    </tr>
                                        
                                    <tr>
                                        <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #909090; font-size:11px; line-height:14px;"><?php echo $instituteCityName; ?></td>
                                    </tr>
                                    
                                    <?php if(!empty($establishYear) || !empty($courseApproval)) { ?>
                                        <tr>
                                            <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #909090; font-size:11px;">
                                                <table border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <?php if($establishYear) { ?>
                                                            <td align="left" style="font-family:Arial, Helvetica, sans-serif; color: #666666; font-size:12px; line-height:18px;">Estd:<?php echo $establishYear; ?></td>
                                                        <?php } ?>
                                                        
                                                        <?php if($courseApproval) { ?>
                                                            <?php if($establishYear) { ?>
                                                                <td width="20" align="center" style="font-family:Arial, Helvetica, sans-serif; color: #cccccc; font-size:12px;">|</td>
                                                            <?php } ?>
                                                            <td style="font-family:Arial, Helvetica, sans-serif; color: #666666; font-size:12px; line-height:18px;"><?php echo $courseApproval; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <td height="10"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr>
                                        <td align="left" style="font-family:Arial, Helvetica, sans-serif; color: #000000; font-size:13px; line-height:18px;">
                                            <a href="<?php echo $courseUrl.$linkExt; ?>" target="_blank" style="color: #333333; text-decoration:none;">
                                                <?php echo $courseName; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <?php if(!empty($courseDuration) || !empty($feesInRupee)) { ?>
                                        <tr>
                                            <td align="left" style=" font-family:Arial, Helvetica, sans-serif; color: #909090; font-size:11px;">
                                                <table border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <?php if($courseDuration) { ?>
                                                            <td align="left" style="font-family:Arial, Helvetica, sans-serif; color: #666666; font-size:12px; line-height:18px;">Duration: <?php echo $courseDuration; ?></td>
                                                        <?php } ?>

                                                        <?php if($feesInRupee) { ?>
                                                            <td width="20" align="center" style="font-family:Arial, Helvetica, sans-serif; color: #cccccc; font-size:12px;">|</td>
                                                            <td style="font-family:Arial, Helvetica, sans-serif; color: #666666; font-size:12px; line-height:18px;">Fees: <?php echo $feesInRupee; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    
                                    <tr>
                                        <td height="12"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td align="left">
                                            <table cellspacing="0" cellpadding="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td height="30" bgcolor="#f29d37" align="center" width="129">
                                                            <a href="<?php echo $courseUrlDB.$linkExt; ?>" target="_blank" style="font-family:Arial, sans-serif; color:#ffffff; font-size:11px; display:block; line-height:30px; text-decoration:none;">
                                                                <strong>Download Brochure</strong>
                                                            </a>
                                                        </td>
                                                        
                                                        <td width="6"></td>
                                                        
                                                        <td height="30" bgcolor="#ffffff" align="center" width="129" style="border:#03818d solid 1px;">
                                                            <a href="<?php echo $courseUrlContact.$linkExt; ?>" target="_blank" style="font-family:Arial, sans-serif; color:#03818d; font-size:11px; display:block; line-height:30px; text-decoration:none;">
                                                                <strong>Show Phone &amp; Email</strong>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td height="9"></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td height="5"></td>
</tr>