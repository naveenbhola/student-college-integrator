<?php
$countOfStaticLink=0;
if(!empty($rankingUrl)) $countOfStaticLink++;
if(!empty($questionsUrl) && $questionsUrl['count']!=0) $countOfStaticLink++;
if(!empty($newsArticlesUrl) && $newsArticlesUrl['count']!=0) $countOfStaticLink++;
if(!($countOfStaticLink == 0 && empty($cityLinks)))
{
?>

<div class="panel-pad">
     <div class="search-widget">
      <h3 class="col-heading s-hide">Learn more about...</h3>
        <div class="search__block">

           <div class="find-que ">
              <div class="slider-col-tab">
                <ul class="slide-col-ul">
                  <li>
                    <div class="data-cols">
                      <?php if($countOfStaticLink!=0) { ?>
                         <div class="main-divs">
                              <div class="cs-section">
                                 <p class="click__txt"><?php echo ucfirst($widgetHeading); ?></p>
                              </div>    
                         </div>
                        <!--border class-->
                          <div class="max__height">
                            <div class="border-class">
                              <ul class="widget-li">
                                 <?php if(!empty($rankingUrl)) { ?>
                                   <li class="<?php if($countOfStaticLink==1) echo "w100"; ?>"><a ga-attr="Stream_Cart" ga-optlabel="Mobile_Stream_Cart_CollegeRanking" href=<?php echo $rankingUrl[0]; ?> > Top Ranked Colleges</a></li>
                                 <?php } ?>   
                                <?php if(!empty($questionsUrl) && $questionsUrl['count']!=0) { ?>
                                   <li class="<?php if($countOfStaticLink==1) echo "w100"; ?>"><a ga-attr="Stream_Cart" ga-optlabel="Mobile_Stream_Cart_Questions" href=<?php echo $questionsUrl['url']; ?> ><strong><?php echo $questionsUrl['count'] ?></strong> Answered Questions</a></li>
                                 <?php } ?> 

                                <?php if(!empty($newsArticlesUrl) && $newsArticlesUrl['count']!=0) { ?>
                                  <li class="<?php if($countOfStaticLink==1) echo "w100"; ?>"><a ga-attr="Stream_Cart" ga-optlabel="Mobile_Stream_Cart_NewsnArticles"  href=<?php echo $newsArticlesUrl['url']; ?> ><strong><?php echo $newsArticlesUrl['count'] ?></strong> News & Articles</a></li>
                                <?php } ?> 
                              </ul>

                             </div>
                          </div>  
                      <?php } ?>
                      <!--most viewed collegs-->
                      <div class="most-viewd">
                  <h3 class="most-txt"><?php echo ucfirst($widgetHeading); ?> colleges in</h3>
                       <ul class="loc-widget">
                          <?php foreach($cityLinks as $cityCtpData)
                          { ?>
                            <li>
                              <a ga-attr="Stream_Cart" ga-optlabel="Mobile_Stream_Cart_CollegeLocations" href="<?php echo $cityCtpData['url'];?>"><?php echo ucfirst($cityCtpData['name']); ?></a>
                            </li>   
                         <?php } ?>
                       </ul>
                        <a class="link-col" ga-attr="Stream_Cart" ga-optlabel="Mobile_Stream_Cart_ViewAllColleges" href="  <?php echo $allIndiaLink['url'] ?>">View All Colleges ></a>
                       </div>
                  </div>
                  </li>
                </ul>
              </div>
           </div>
        </div>
    </div>
</div>
<?php } ?>