<tr>
<td width="20"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
<td width="524"><font face="Georgia, Times New Roman, Times, serif" style="font-size:18px; color:#444648;">News tailored for you</font></td>
<td width="20"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
</tr>
<tr>
<td></td>
<td valign="top">
	
<?php foreach($articles as $article) { ?>	
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:block;">
  <tr>
    <td height="12" colspan="2"></td>
  </tr>
  <tr>
    <td width="107" valign="top">
		<!--img src="< ?php echo $article['blogImageURL'] ? $article['blogImageURL'] : SHIKSHA_HOME.'/public/images/newsletter/article_default2.gif'; ?>" width="97" height="73" vspace="0" hspace="0" align="left" style="border:1px solid #a8a8a8" /-->
		<img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/newsletter/article_default2.gif'; ?>" vspace="0" hspace="0" align="left" style="border:1px solid #a8a8a8" />
	</td>
    <td width="417"><table border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:13px; color:#474a4b;padding-left:10px;" width="100%" >
        <tr>
          <td><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~NewsletterArticleList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Georgia, 'Times New Roman', Times, serif; color:#0065e8; font-size:20px; text-decoration:none;"><font color="#0065e8"><?php echo trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle']; ?></font></a></td>
        </tr>
        <tr>
          <td>
		<?php
			$articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
			if(strlen($articleSummary) > 200) {
				$articleSummary = substr($articleSummary,0,200).'...';
			}
			echo $articleSummary;
		?>
		  <br />
<!--font color="#474a4b"><b><?php echo date('F d, Y',strtotime($article['lastModifiedDate'])); ?></b>.</font-->
 </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="15" colspan="2" style="border-bottom:1px solid #e4e4e4;"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/spacer.gif" width="1" height="8" vspace="0" hspace="0" align="left" /></td>
  </tr>
</table>

<?php } ?>
			
</td>
<td></td>
</tr>
<tr>
<td colspan="3" height="15"></td>
</tr>
<tr>
<td></td>
<td><div style="width:100%" align="center"><a href='<?php echo SHIKSHA_HOME; ?>/careers~NewsletterCareerCentralBanner<!-- #widgettracker --><!-- widgettracker# -->'><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/ccbanner.gif" vspace="0" hspace="0" align="absmiddle" style="max-width:468px; width:inherit; max-height:60px;" /></a></div></td>
<td></td>
</tr>

<tr>
<td colspan="3" height="18"></td>
</tr>
