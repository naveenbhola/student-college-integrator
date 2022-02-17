<?php
 // echo "Total Recs: ".count($articleWidgetsData)."<pre>";print_r($articleWidgetsData); echo "</pre>"; die;
/*
function formatArticleTitle($content, $charToDisplay) {
        if(strlen($content) <= $charToDisplay)
            return($content);
        else
            return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))."...") ;
}
*/

$totalRecords = count($articleWidgetsData);

if($articleWidgetsData['imageName'] != ""){
    $imgSrc = $articleWidgetsData['imageName'];
    $totalRecords = $totalRecords - 1;
} else { // Show the default image..
    $imgSrc = "/public/images/category-latest-news.jpg";
}
?>
 <div class="latestNewsBlock" uniqueattr="CategoryPage/LatestNews" id="latestNewsBlockDiv">
        	<strong>Latest Articles on <?php echo $categoryName;?></strong>
			<ul>
            	<li class="firstNews">
                    <div class="latestNewsFigure"><a href="<?php echo $articleWidgetsData[0]['articleURL']; ?>"><img src="<?=$imgSrc?>" alt="" height="63" width="112" border="0" /></a></div>
                    <div class="latestNewsFirstContent">                    	
                        <a href="<?php echo $articleWidgetsData[0]['articleURL']; ?>" title="<?=$articleWidgetsData[0]['articleTitle']?>"><?php 
                        // $finalContent = formatArticleTitle($articleWidgetsData[0]['articleTitle'], 75);
                        // echo $finalContent = wordwrap($finalContent, 22, '<br />', true);
                        echo formatArticleTitle($articleWidgetsData[0]['articleTitle'], 75);
                          ?></a>
                    	<p style="margin-top:5px;"><?php if($articleWidgetsData[0]['comments'] != "") echo $articleWidgetsData[0]['comments']." comment(s)";   ?></p>
                    </div>
                </li>
                <?php
                for($i=1; $i < $totalRecords; $i++) {
                ?>
                <li>
                    <div class="latestNewsContent">
                    	<a href="<?php echo $articleWidgetsData[$i]['articleURL']; ?>" title="<?=$articleWidgetsData[$i]['articleTitle']?>"><?php echo formatArticleTitle($articleWidgetsData[$i]['articleTitle'], 60);?></a>
                        <div class="spacer5 clearFix"></div>
                    	<p><?php if($articleWidgetsData[$i]['comments'] != "") echo $articleWidgetsData[$i]['comments']." comment(s)";   ?></p>
                    </div>
                </li>
                <?php
                }
                ?>
            </ul>
            <div class="seeAllNews">
		<a href="<?=SHIKSHA_HOME?>/blogs/shikshaBlog/showArticlesList?category=<?=$categoryID?>&country=<?=$countryID?>">See All</a> <span class="seeAllPointer"></span>
            </div>
            <div class="clearFix"></div>
        </div>
