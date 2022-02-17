<?php 
switch ($productType) {
  case 'rankingPage':
    $track = 'Ranking_Desktop_IIM_Call_Predictor';
    break;  
  case 'categoryPage':
    $track = 'Category_Page_Desktop_IIM_Call_Predictor';
    break;  
  case 'courseListingPage':
    $track = 'Course_Detail_IIM_Call_Predictor';
    break;
  case 'instituteListingPage':
    $track = 'Institute_Detail_IIM_Call_Predictor';
    break;  
}
?>
<div class="banner-img-col">
  
    <div class="banner-left">
        <p>
          Wondering if you'll get an 
          <?php 
          if($productType == 'rankingPage'){
            ?>
            <span class="banner-bold">IIM interview call ?</span>
            <?php    
          }
          ?>
        </p>
        <?php 
        if($productType != 'rankingPage'){
          ?>
          <span class="banner-bold">IIM interview call ?</span>
          <?php
        }
        ?>
    </div>
    
     <div class="banner-right">
        <h2 class="banner-h1">IIM Call Predictor<span class="banner-hlp-txt">can help you find</span></h2>
           <ul class="banner-ul">
              <li><p>IIMs you are eligible for</p></li>
              <li><p>CAT %ile at which you can expect a call from IIMs</p></li>
           </ul>
           <a class='findOutBtn' ga-attr="IIMPredictorBanner" ga-page="<?php echo $track;?>" ga-optlabel="link" href="<?php echo SHIKSHA_HOME;?>/mba/resources/iim-call-predictor" target='_blank'>Find Out Now</a>
    </div>
  
</div>
