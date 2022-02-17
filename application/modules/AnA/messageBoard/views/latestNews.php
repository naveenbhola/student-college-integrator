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
<h4>Latest Article about <?php echo $selectedSubCategoryName;?> Education</h4>
<ul>
<?php for($i=0;$i<count($articleWidgetsData) && $i<4;$i++){?>
<?php if(!empty($articleWidgetsData[$i]['articleTitle'])){?>
<li <?php if($i==0){ ?> class="firstNews" <?php } ?>>
<?php if($i==0){ ?>
<div class="latestNewsFigure"><a href="<?php echo $articleWidgetsData[$i]['articleURL']; ?>"><img src="<?=$imgSrc?>" alt="" height="63" width="112" border="0" /></a></div>
<div class="latestNewsFirstContent">
<?php }?>
                        
<a href="<?php echo $articleWidgetsData[$i]['articleURL']; ?>" title="<?=$articleWidgetsData[$i]['articleTitle']?>" ><?php 
if($i==0){$len=75;}else{$len=60;}
echo formatArticleTitle($articleWidgetsData[$i]['articleTitle'], $len);
?></a>
<?php if($articleWidgetsData[$i]['comments']>1) $comment = "comments";else $comment = "comment";?>
<p style="margin-top:5px;"><?php if($articleWidgetsData[$i]['comments'] != "") echo $articleWidgetsData[$i]['comments']." $comment";   ?></p>
<?php if($i==0){ ?>
</div>
<?php }?>
</li>
<?php }?>
<?php } ?>
</ul>
<div class="seeAllNews">
<a href="<?=SHIKSHA_HOME?>/blogs/shikshaBlog/showArticlesList?category=<?=$CategoryList[0]?>&country=<?=$catCountArray[0][countryId];?>" >See All</a> <span class="seeAllPointer"></span>
</div>
<div class="clearFix"></div>
</div>
<div class="clearFix"></div>
</div>
<div class="clearFix spacer15"></div>
