<?php foreach($articles as $article) { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474a4b;">
  <tr>
    <td align="center">
      <div style="width:100%"><img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/mnews/Default-Image.jpg'; ?>" border="0" vspace="0" hspace="0" align="absmiddle" style="max-width:500px; width:inherit;" /></div>
    </td>
  </tr>
  <tr>
    <td height="14"></td>
  </tr>
  <tr>
    <td valign="baseline">
      <table border="0" cellspacing="0" align="left" cellpadding="0" style="max-width:359px; text-align:left; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474a4b;">
        <tr>
          <td style="padding-bottom:6px;">
	    <a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~MNewsFeaturedArticleList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:20px; color:#0065e8; text-decoration:none;">
	      <?php
		$articleTitle = trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle'];
		if(strlen($articleTitle) > 100) {
		  $articleTitle = substr($articleTitle,0,100).'...';
	      }
	      echo $articleTitle;
	      ?>
	    </a>
	  </td>
          <td></td>
        </tr>
	<tr>
	  <td width="346">
	    <?php
	      $articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
	      if(strlen($articleSummary) > 200) {
		  $articleSummary = substr($articleSummary,0,200).'...';
	      }
	      echo $articleSummary;
	    ?>
	  </td>
          <td width="13"></td>
        </tr>
      </table>
      <table width="136" align="left" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
	<tbody>
	  <tr><td height="15"></td></tr>
	  <tr>
	    <td width="136" bgcolor="#ffda3e" align="center" height="41" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:#4b4b4b;display:block;font-size:14px;line-height:39px" title="Read more" href="<?php echo SHIKSHA_HOME.$article['url']; ?>~MNewsFeaturedArticleList<!-- #widgettracker --><!-- widgettracker# -->"><b>Read more</b></a></td>
	  </tr>
	</tbody>
      </table>
    </td>
  </tr>
</table>
<?php } ?>