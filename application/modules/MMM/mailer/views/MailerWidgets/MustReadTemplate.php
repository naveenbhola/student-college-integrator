<table border="0" bgcolor="#ffffff" width="93%" cellspacing="0" cellpadding="0" align="center">
<tr>
<td bgcolor="#FFFFFF">
    <table border="0" bgcolor="#ffffff" align="left" cellspacing="0" cellpadding="0" style="max-width: 305px">
    <tbody>
    
    
    <tr>
	    <td colspan="3">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
    <tr>
    <td valign="bottom"><font color="#020001" face="Georgia, Times New Roman, Times, serif" style="font-size:25px;">Must read NEWS</font></td>
    <td width="29"></td>
    </tr>
    <tr>
    <td colspan="4" height="20"></td>
    </tr>
    </tbody></table>
	    </td>
    <tr>
    <?php
    $totalRecords = count($articleWidgetsData);
    $widgetArticleIds = array();
    for($i=0; $i < $totalRecords; $i++) {
	?>
	    <tr>
	    <td width="70" valign="top">
	    <?php
	    $articleImage = "<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/mrec_a_mr1.jpg";
	    if($articleWidgetsData[$i]['blogImageURL']) {
		$articleImage = $articleWidgetsData[$i]['blogImageURL'];
	    }
	    ?>
	    <img width="60" vspace="0" align="left" hspace="0" height="44" src="<?php echo $articleImage; ?>" />
	    </td>
	    <td width="269" valign="top"><a style="font-size:14px; text-decoration:none; color:#0065e8; font-family:Georgia" target="_blank" href="<?php echo $articleWidgetsData[$i]['url']."~mustread"; ?><!-- #widgettracker --><!-- widgettracker# -->"> <strong><?php echo formatArticleTitle($articleWidgetsData[$i]['blogTitle'],500); ?></strong></a></td>
	    <td width="8"></td>
	    </tr>
	    <tr><td colspan="3" height="20"></td></tr>
	    <?php $widgetArticleIds[] = $articleWidgetsData[$i]['blogId']; } ?>
	    
	    <tr>
	    <td colspan="3"><table cellspacing="0" cellpadding="0" border="0" width="150" style="font-family: Arial; font-size: 13px; color: rgb(71, 74, 75); display: block;">
    <tbody><tr>
      <td bgcolor="#ffda3e" width="150" height="37" align="center" style="border:1px solid #e8b363;border-radius:2px"><a href="<?php echo SHIKSHA_HOME; ?>/blogs/shikshaBlog/mustReadArticles/<?php echo $mailerId; ?>/<?php echo implode(',',$widgetArticleIds); ?>~mustread<!-- #widgettracker --><!-- widgettracker# -->" title="View more NEWS" style="text-decoration: none; color: rgb(75, 75, 75); display: block; font-size: 13px; line-height: 35px;" target="_blank"><b>View more NEWS</b> &gt;&gt;</a></td>
    </tr>
    </tbody></table></td>
	    </tr>
	    <tr><td colspan="3" height="20"></td></tr>
    </tbody>
    </table>
    <table border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="0" width="160">
	    <tbody><tr>

	    <td align="right"><font color="#474a4b" face="Arial, Helvetica, sans-serif" style="font-size:11px;">Advertisement</font></td>
	    <td width="29"></td>
	    </tr>
	    <tr>

	    <td><a target="_blank" href="<?php echo SHIKSHA_HOME; ?>/messageBoard/MsgBoard/postQuestionFromCafeForm/1~mustread<!-- #widgettracker --><!-- widgettracker# -->"><img border="0" width="160" vspace="0" align="right" hspace="0" height="300" src="<?php echo SHIKSHA_HOME."/public/images/mailerad.jpg"; ?>" /></a></td>
	    <td></td>
	    </tr>
	    </tbody></table>
</td>
</tr>
</table>

