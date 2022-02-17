<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 25/7/18
 * Time: 12:17 PM
 */


    // if banner where unsubscribe link will come is black than style will be different.
    $styleTr = "padding-top: 5px;font-size: 11px;color:#F1f1f1;";
    $styleAnchor = "color: #ffffff;text-decoration: underline;font-size:10px;font-weight:  bold;";
    if($whiteBanner) {
        $styleTr = "padding-top: 5px;font-size: 11px;color: #666;font-family: Arial, Helvetica, sans-serif;";
        $styleAnchor = "color: #0000ee;text-decoration: underline;font-size: 11px;font-weight: normal;";
    }
?>

<tr>
    <td height="26" align="center" style="<?php echo $styleTr ?>">To unsubscribe
        <a href="<!-- #Unsubscribe --><!-- Unsubscribe# -->" target="_blank" style="<?php echo $styleAnchor ?>">click here</a> to visit the Accounts &amp; Settings page and change your mail preferences</td>
</tr>
