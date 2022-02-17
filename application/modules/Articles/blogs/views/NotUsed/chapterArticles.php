<div class="lineSpace_10">&nbsp;</div>
<div class="highlightColorfont1 mar_left_10p">
	Articles from the same chapter
</div>
<div class="grayLine mar_left_5p mar_right_10p lineSpace_5">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<div style="float:left; width:100%; padding-left:0px;">
	<?php 
		foreach($chapterArticles as $chapterArticle){
			if($blogInfo[0]['blogId'] == $chapterArticle['blogId']) continue;
	?>
	<div class="row mar_full_10p">
		<img src="/public/images/bullets.gif" align="absmiddle"/><a class="fontSize_12p" href="<?php echo $chapterArticle['url'];?>"><?php echo $chapterArticle['blogTitle'] ?></a>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<?php
		}
	?>
</div>
<div class="clear_L"></div>
