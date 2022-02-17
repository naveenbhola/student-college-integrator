<?php foreach($articles as $article) { ?>
<table width="262" align="left" border="0" cellspacing="0" cellpadding="0">
    <tr>
	<td width="252">
	    <table width="252" border="0" cellspacing="0" cellpadding="0" align="left" style="border:1px solid #e5e5e5;">
		<tr>
		    <td width="10"></td>
		    <td width="232" height="10"></td>
		    <td width="10"></td>
		</tr>
		<tr>
		    <td></td>
		    <td valign="top">
			<table width="228" border="0" cellspacing="0" cellpadding="0" align="center">
			    <tr>
				<td height="162">
				    <img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/mnews/Default-Image.jpg'; ?>" border="0" vspace="0" hspace="0" align="left" width="228" height="162" />
				</td>
			    </tr>
			    <tr>
				<td height="15"></td>
			    </tr>
			    <tr>
				<td height="70" valign="top">
				    <a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~MNewsRegularArticleList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:17px; color:#0065e8; text-decoration:none;">
					<?php
					    $articleTitle = trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle'];
					    if(strlen($articleTitle) > 60) {
						$articleTitle = substr($articleTitle,0,60).'...';
					    }
					    echo $articleTitle;
					?>
				    </a>
				</td>
			    </tr>
			    <tr>
				<td height="10"></td>
			    </tr>
			    <tr>
				<td height="70" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474a4b;">
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
				<td height="13"></td>
			    </tr>
			    <tr>
				<td valign="top">
				    <table width="136" align="left" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
					<tbody>
					    <tr>
						<td width="136" bgcolor="#ffda3e" align="center" height="41" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:#4b4b4b;display:block;font-size:14px;line-height:39px" title="Read more" href="<?php echo SHIKSHA_HOME.$article['url']; ?>~MNewsRegularArticleList<!-- #widgettracker --><!-- widgettracker# -->"><b>Read more</b></a></td>
					    </tr>
					</tbody>
				    </table>
				</td>
			    </tr>
			    <tr>
				<td height="13"></td>
			    </tr>
			</table>
		    </td>
		</tr>
	    </table>
	</td>
	<td width="10"></td>
    </tr>
    <tr>
	<td height="10"></td>
	<td></td>
    </tr>
</table>
<?php } ?>
