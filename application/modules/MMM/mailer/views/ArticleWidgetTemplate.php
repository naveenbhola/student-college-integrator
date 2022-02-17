<?php
$totalRecords = count($articleWidgetsData);

?>
 <div class="latestNewsBlock" uniqueattr="CategoryPage/LatestNews">
        	<h4>Catch the latest news</h4>
			<ul>
            	<li class="firstNews">
                    <div class="latestNewsFirstContent">                    	
                        <a href="<?php echo $articleWidgetsData[0]['articleURL']; ?><!-- #tracker --><!-- tracker# -->" title="<?=$articleWidgetsData[0]['articleTitle']?>"><?php 
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
                    	<a href="<?php echo $articleWidgetsData[$i]['articleURL']; ?><!-- #tracker --><!-- tracker# -->" title="<?=$articleWidgetsData[$i]['articleTitle']?>"><?php echo formatArticleTitle($articleWidgetsData[$i]['articleTitle'], 60);?></a>
                        <div class="spacer5 clearFix"></div>
                    	<p><?php if($articleWidgetsData[$i]['comments'] != "") echo $articleWidgetsData[$i]['comments']." comment(s)";   ?></p>
                    </div>
                </li>
                <?php
                }
                ?>
            </ul>
            <div class="seeAllNews" >
		<a href="<?=SHIKSHA_HOME?>/blogs/shikshaBlog/showArticlesList?category=<?=$categoryID?>&country=<?=$countryID?><!-- #tracker --><!-- tracker# -->">See All</a> <span class="seeAllPointer"></span>
            </div>
            <div class="clearFix"></div>
        </div>
