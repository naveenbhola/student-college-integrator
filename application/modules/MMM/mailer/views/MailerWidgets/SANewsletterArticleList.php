<table>
<?php foreach($articles as $article) {
    if(in_array($article['content_id'],$featuredArticleIdsArray)) {
?>
<tr>
    <td align="left" width="600">
        <table width="280" border="0" cellspacing="0" cellpadding="0" align="left">
            <tr>
                <td width="280">
                    <img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/SAnewsletter/banner-img.jpg'; ?>" width="280" height="200" />
                </td>
            </tr>
        </table>
        <table width="280" bgcolor="#fc9e47" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="200" align="center" valign="middle">
                    <table width="250" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="left" style="text-align:left; line-height:20px; font-family:Arial, Helvetica, sans-serif; font-size:21px; font-family:Arial, Helvetica, sans serif; color:#ffffff;" valign="top">
                                <strong>
                                    <?php
                                        $articleTitle = trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle'];
                                        if(strlen($articleTitle) > 50) {
                                            $articleTitle = substr($articleTitle,0,50).'...';
                                        }
                                        echo $articleTitle;
                                    ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="text-align:left; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#ffffff;">
                                <?php
                                    $articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
                                    if(strlen($articleSummary) > 100) {
                                        $articleSummary = substr($articleSummary,0,100).'...';
                                    }
                                    echo $articleSummary;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td height="10" align="left">
                                <table width="138" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                    <tr>
                                        <td height="35" width="80%" align="center">
                                            <a href="<?php echo $article['url']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; color:#fc9e47; text-decoration:none; float:left; width:100%; height:35px; line-height:35px">Read More</a>
                                        </td>
                                        <td width="20%" height="35" valign="middle">
                                            <a href="<?php echo $article['url']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; color:#fc9e47; text-decoration:none; float:left; width:100%; height:35px; line-height:15px;">
                                                <img style='border:none;' src="<?php echo SHIKSHA_HOME.'/public/images/SAnewsletter/arrow-img3.jpg'; ?>" width="20" height="35" />
                                            </a>
                                        </td>
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
<?php   }
} 
?>

<tr>
    <td align="left">
        <?php
        foreach($articles as $article) {
            if(in_array($article['content_id'],$regularArticleIdsArray)) {
        ?>
        <table width="275" border="0" cellspacing="0" cellpadding="0" align="left">
            <tr>
                <td width="270" align="left">
                    <table width="270" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td width="100%">
                                <img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/SAnewsletter/banners-img3.jpg'; ?>" width="270" height="161" />
                            </td>
                        </tr>
                        <tr>
                            <td height="10"></td>
                        </tr>
                        <tr>
                            <td style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#3c4b5f" width="100%" align="left">
                                <strong>
                                    <?php
                                        $articleTitle = trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle'];
                                        if(strlen($articleTitle) > 60) {
                                            $articleTitle = substr($articleTitle,0,60).'...';
                                        }
                                        echo $articleTitle;
                                    ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#858585; line-height:20px;">
                                <i>
                                <?php
                                    $articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
                                    if(strlen($articleSummary) > 200) {
                                        $articleSummary = substr($articleSummary,0,200).'...';
                                    }
                                    echo $articleSummary;
                                ?>
                                </i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="110" border="0" cellspacing="0" cellpadding="0" bgcolor="#fc9e47">
                                    <tr>
                                        <td height="30" width="80%" align="center">
                                            <a href="<?php echo $article['url']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none; float:left; width:100%; height:30px; line-height:30px; font-size:12px;">Read More</a>
                                        </td>
                                        <td width="20%" height="30" valign="middle">
                                            <a href="<?php echo $article['url']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; color:#fc9e47; text-decoration:none; float:left; width:100%; height:30px; line-height:10px;">
                                                <img width="20" height="30" style='border:none;' src="<?php echo SHIKSHA_HOME.'/public/images/SAnewsletter/arrow-img2.jpg'; ?>" />
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php   }
        } ?>
    </td>
</tr>
<tr>
    <td height="40"></td>
</tr>
<tr>
    <td align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="30" bgcolor="#4573b1">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20"></td>
                            <td height="30" valign="middle" align="left" style="font-size:16px; font-family:Arial, Helvetica, sans-serif; color:#ffffff"> 
                                Useful Links to <?php echo $countryName; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="left" height="8" valign="top">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td align="left" height="8" valign="top" width="23">
                                    <img src="<?php echo SHIKSHA_HOME.'/public/images/SAnewsletter/arrow-img.jpg'; ?>" width="23" height="8" align="left"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <table width="270" border="0" cellspacing="0" cellpadding="0" align="left">
                        <tr>
                            <td align="left">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                    <tr>
                                        <td width="44%"></td>
                                        <td width="5%"></td>
                                        <td width="51%"></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center;">
                                            <a target="_blank" href="<?php echo $links['CountryPage']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center; text-decoration: none;">
                                                Universities in <?php echo $countryName; ?>
                                            </a>
                                        </td>   
                                        <td height="7"></td>
                                        <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center;">
                                            <a target="_blank" href="<?php echo $links['MBA']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center; text-decoration: none;">
                                                MBA in <?php echo $countryName; ?>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table width="270" border="0" cellspacing="0" cellpadding="0" align="left">
                        <tr>
                            <td align="left">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                                    <tr>
                                        <td width="44%"></td>
                                        <td width="5%"></td>
                                        <td width="51%"></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center;">
                                            <a target="_blank" href="<?php echo $links['MS']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center; text-decoration: none;">
                                                MS in <?php echo $countryName; ?>
                                            </a>
                                        </td>
                                        <td height="7"></td>
                                        <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center;">
                                            <a target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#9a9999; text-align: center; text-decoration: none;" href="<?php echo $links['BTech']; ?>~SANewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->">
                                                BTech in <?php echo $countryName; ?>
                                            </a>
                                        </td>
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
</table>