<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $mailHeading;?></title>
    </head>

    <body>
        <?php if($mailer_name == 'DetailedRecommendationMailer') { ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php } ?>
        <table style="max-width:600px;" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td width="600" bgcolor="#f1f1f2">
                    <table style="max-width:600px; border:1px solid #ededed; border-top:4px solid #ec9939;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>  
                            <td height="18">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="59"></td>
                                        <td width="83">
                                            <a target="_blank" style="text-decoration:none;color: inherit;" href="<?php echo SHIKSHA_HOME; ?>/shiksha/index<!-- #widgettracker --><!-- widgettracker# -->"><img width="83" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:25px; color:#098290;" src="<?php echo SHIKSHA_HOME; ?>/public/images/personalizedMailers/mailerlogo.gif" alt="Shiksha" /></a>
                                        </td>
                                        <!-- <td align="center" width="17" style="font-family: Tahoma, Helvetica, sans-serif; font-size:18px; color:#999999;">/</td> -->
                                        <td width="425" style="font-family:Tahoma, Helvetica, sans-serif; font-size:16px; color:#999999;"><?php echo $mailHeading;?></td>
                                    </tr>
                                </table>
                            </td> 
                        </tr>
                        <tr>
                            <td height="20" ></td>
                        </tr>