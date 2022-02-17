<?php
$countOfStaticLink=0;
if(!empty($rankingUrl)) $countOfStaticLink++;
if(!empty($questionsUrl) && $questionsUrl['count']!=0) $countOfStaticLink++;
if(!empty($newsArticlesUrl) && $newsArticlesUrl['count']!=0) $countOfStaticLink++;
?>
<section>
    <div class=" color-1">
        <h2 class="color-1 f16 heading-gap font-w6">Learn more about</h2>
        <div class="color-w f16 art-crd">
            <h3 class="f14 color-1 <?php if($countOfStaticLink==0) echo 'm-5btm';?>"><?php echo ucfirst($widgetHeading); ?></h3>
            <?php if($countOfStaticLink!=0) { ?>
            <div class="max__height">
                <div class="border-class">
                    <ul class="widget-li">
                         <?php if(!empty($rankingUrl)) { ?>
                           <li class="<?php if($countOfStaticLink==1) echo "w100"; ?>" ><a class="ga-analytic" data-vars-event-name="CollegeRanking" data-vars-event-cat-name ="Amp_Stream_Cart"  href=<?php echo $rankingUrl[0]; ?> > Top Ranked Colleges</a></li>
                         <?php } ?>   
                        <?php if(!empty($questionsUrl) && $questionsUrl['count']!=0) { ?>
                           <li class="<?php if($countOfStaticLink==1) echo "w100"; ?>"><a class="ga-analytic" data-vars-event-name="Questions" data-vars-event-cat-name ="Amp_Stream_Cart" href=<?php echo $questionsUrl['url']; ?> ><strong><?php echo $questionsUrl['count'] ?></strong> Answered Questions</a></li>
                         <?php } ?> 

                        <?php if(!empty($newsArticlesUrl) && $newsArticlesUrl['count']!=0) { ?>
                          <li class="<?php if($countOfStaticLink==1) echo "w100"; ?>"><a class="ga-analytic" data-vars-event-name="NewsnArticles" data-vars-event-cat-name ="Amp_Stream_Cart" href=<?php echo $newsArticlesUrl['url']; ?> ><strong><?php echo $newsArticlesUrl['count'] ?></strong> News &amp; Articles</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php }?>
            <div class="most-viewd">
                <h3 class="f14 color-1 font-w7"><?php echo ucfirst($widgetHeading); ?> colleges in</h3>
                <ul class="loc-widget">
                     <?php foreach($cityLinks as $cityCtpData)
                     { ?>
                       <li>
                         <a class='ga-analytic' data-vars-event-name="CollegeLocations" data-vars-event-cat-name="Amp_Stream_Cart"  href="<?php echo $cityCtpData['url'];?>"><?php echo ucfirst($cityCtpData['name']); ?></a>
                       </li>   
                    <?php } ?>
                </ul>
                <a class="ga-analytic link-col" data-vars-event-name="ViewAllColleges" data-vars-event-cat-name="Amp_Stream_Cart" href="<?php echo $allIndiaLink['url'] ?>">View All Colleges &gt;</a>
            </div>
        </div>
    </div>    
</section>
