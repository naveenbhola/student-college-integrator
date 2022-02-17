<?php

$totalRecords = count($articleWidgetsData);

if($articleWidgetsData['imageName'] != ""){
    $imgSrc = $articleWidgetsData['imageName'];
    $totalRecords = $totalRecords - 1;
} else { // Show the default image..
    $imgSrc = "/public/images/news-avatar.jpg";
}
?>

<div class="nav-widgets">
	<h5 style="margin-bottom:5px">News & Features</h5>
	<div class="nav-widget-wrap">
		<h5 style="margin-bottom:8px"><a href="<?php echo $articleWidgetsData[0]['articleURL']; ?>"><?=formatArticleTitle($articleWidgetsData[0]['articleTitle'],60)?></a></h5>
		<div class="news-figure"><img src="<?=$imgSrc?>" alt="<?=$articleWidgetsData[0]['articleTitle']?>" /></div>
		<div class="details">
			<?=formatArticleTitle($articleWidgetsData[0]['summary'],150)?>
			<div class="spacer5"></div>

			<a href="<?php echo $articleWidgetsData[0]['articleURL']; ?>" class="knw-more">Know more</a>
		</div>
		
		<div class="spacer10 clearFix"></div>
		<ul>
				<?php
                for($i=1; $i < $totalRecords; $i++) {
                ?>
                <li><a href="<?php echo $articleWidgetsData[$i]['articleURL']; ?>" title="<?=$articleWidgetsData[$i]['articleTitle']?>"><?php echo formatArticleTitle($articleWidgetsData[$i]['articleTitle'], 60);?></a></li>
                <?php
                }
                ?>
		</ul>
		<div class="see-all-news">
			<a href="<?=SHIKSHA_HOME?>/blogs/shikshaBlog/showArticlesList?category=<?=$personalizedArray['category']->getParentId()?>&country=<?=$personalizedArray['countryId'];?>">See All</a>
			<span class="seeAllArr"></span>
		</div>
	</div>
</div>