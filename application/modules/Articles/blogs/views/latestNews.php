<?php

$totalRecords = count($articleWidgetsData);

if($articleWidgetsData['imageName'] != ""){
$imgSrc = $articleWidgetsData['imageName'];
$totalRecords = $totalRecords - 1;
} else { // Show the default image..
$imgSrc = "/public/images/category-latest-news.jpg";
}
?>
<div class="latestNewsBlock">
<div class="content-wrap">
<h4>Related News</h4>
<ul>
<?php for($i=0;$i<count($articleWidgetsData) && $i<4;$i++){?>
	<?php if(!empty($articleWidgetsData[$i]['articleTitle'])){?>
		<li>
                        
		<a href="<?php echo $articleWidgetsData[$i]['articleURL']; ?>" title="<?=$articleWidgetsData[$i]['articleTitle']?>" uniqueattr="article_detail_page_latestnewwidget"><?php 
		if($i==0){$len=75;}else{$len=60;}
			echo formatArticleTitle($articleWidgetsData[$i]['articleTitle'], $len);
		?></a>
		<?php if($articleWidgetsData[$i]['comments']>1) $comment = "comments";else $comment = "comment";?>
			<p style="margin-top:1px;font-size:11px;color:gray;"><?php if($articleWidgetsData[$i]['comments'] != "") echo $articleWidgetsData[$i]['comments']." $comment";   ?></p>
		</li>
	<?php }?>
<?php } ?>
</ul>
<div class="clearFix"></div>
</div>
<div class="clearFix"></div>
</div>

<div class="clearFix"></div>
