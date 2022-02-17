<?php if(!empty($articleInfo)){
        $GA_Tap_On_Article = 'ARTICLE_TUPLE';
        $GA_Tap_On_View_All = 'VIEW_ALL_ARTICLES';
  ?>
<div class="new-row">
 <div class="group-card gap no__pad clear listingTuple" id="articles">
   <h2 class="head-1 gap">Articles <?php if($totalArticles > 3){?><span class="para-6">(showing <?=count($articleInfo)?> of <?=$totalArticles?> articles)</span><?php } ?></h2> 
   
    <div class="new-row1 equalblockheight" >
    <?php foreach($articleInfo as $key=>$article){
      $articleURL = addingDomainNameToUrl(array('url' => $article['url'] , 'domainName' =>SHIKSHA_HOME));
      ?>
    <div class="col-md-4 block card-clickable" ga-attr="<?=$GA_Tap_On_Article?>"">
      <div class="group-card  card-fixed" >
          <div class="group-head">
            <h3 class="head-1 h-fix"><?=substr(htmlentities($article['blogTitle']),0,80)?><?php if(strlen(htmlentities($article['blogTitle']))>80){echo '...'; } ?></h3>
          </div>  
        <p class="para-2"><?=substr(htmlentities($article['summary']),0,170)?><?php if(strlen(htmlentities($article['summary']))>170){
          echo '...'; } ?> 
          <br>  
          <a href="<?=$articleURL;?>" class='redirectLink' target='_blank' > Read More</a>
        </p>
    </div>
    </div>
    <?php } ?>
    <p class="clr"></p>
  </div>
  <?php if($totalArticles > 3){?>
  <div class="alter-div align-center">
    <a class="button button--secondary arw_link" href="<?=$all_article_url?>" target="_blank" ga-attr="<?=$GA_Tap_On_View_All?>" id="article_count">View All <?=$totalArticles?> Articles</a>
  </div>
  <?php } ?>
 </div>
</div>
<?php } ?> 
