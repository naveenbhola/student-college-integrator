<?php
$totalRecords = count($articleWidgetsData);
?>
 <table width="92%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
  
  <tr><td height="20"></td></tr>
  <tr>
    <td  style="border-top:1px solid #e2e2e2;">&nbsp;</td>
  </tr>
  <tr>
    <td height="39" style="padding-left: 0px" valign="top"><font face="Georgia, Times New Roman, Times, serif" color="#020001" style="font-size:25px;">Latest NEWS</font></td>
  </tr>
  <tr>
    <td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="font-family:Arial, Helvetica, sans-serif;">
  
			<?php
                for($i=0; $i < $totalRecords; $i++) {
                ?>
<tr>
    <td></td>
    <td valign="top" align="center" height="25" width="15"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2012/shiksha/prod20nov/images/bull1.gif" /></td>
    <td valign="top">
	<a style="font-size:14px; text-decoration:none; color:#3465e8;" href="<?php echo str_replace('https://www.shiksha.com',THIS_CLIENT_IP,$articleWidgetsData[$i]['url'])."~article"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="<?=$articleWidgetsData[$i]['blogTitle']?>"><?php echo formatArticleTitle($articleWidgetsData[$i]['blogTitle'], 500);?></a>
	<div style="margin:5px 0 7px 0; font-size:12px;"><?php if($articleWidgetsData[$i]['numComments'] != "") echo $articleWidgetsData[$i]['numComments']." comment(s)";   ?></div>
    </td>
  </tr>			               
                <?php
                }
                ?>
	</table>

    </td>
  </tr>
  <tr><td height="10"></td></tr>
  <tr>
          <td valign="top"><table cellspacing="0" cellpadding="0" border="0" width="150" style="font-family: Arial; font-size: 13px; color: rgb(71, 74, 75); display: block;">
    <tbody><tr>
      <td bgcolor="#ffda3e" width="150" height="37" align="center" style="border:1px solid #e8b363;border-radius:2px"><a href='https://www.shiksha.com/blogs/shikshaBlog/showArticlesList<?php if($categoryID > 0 && $countryID > 0) { ?>?category=<?php echo $categoryID; ?>&country=<?php echo $countryID."#"; ?><?php } ?>~article<!-- #widgettracker --><!-- widgettracker# -->' title="View more NEWS" style="text-decoration: none; color: rgb(75, 75, 75); display: block; font-size: 13px; line-height: 35px;" target="_blank"><b>View more NEWS</b> &gt;&gt;</a></td>
    </tr>
    </tbody></table>
	  </td>
          
        </tr>
  <tr><td height="10"></td></tr>
      </table>
