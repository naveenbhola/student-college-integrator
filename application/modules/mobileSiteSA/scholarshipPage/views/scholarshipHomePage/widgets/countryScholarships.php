<?php
  $keyValue = 2;
  if($allLazyLoad) {
    $keyValue = 0;
  }
?>
<section class="cmn__pad bg__white">
  <h1 class="fnt16_bold clr__3">Study Abroad Scholarships</h1>
  <div class="cl">
      <?php foreach ($scholarshipStatistics as $key=>$value) {  
        $countryNameForURL = htmlentities(str_replace(' ', '-', strtolower($countryMapping[$value['countryId']])));
        $imgURL = IMGURL_SECURE.'/public/mobileSA/images/scholarshipHomePage/'.$countryNameForURL.'.jpg';
        if($key > $keyValue){
          $lazyClass = 'class="lazy"';
          $dataSrc = 'data-src="'.$imgURL.'"';
        }else{
          $lazyClass = '';
          $dataSrc = 'src="'.$imgURL.'"';
        }
      ?>
      <section class="d_img sch_grp">
        <a href="<?php echo $scholarshipCountryCategoryPageURL[$value['countryId']]?>" class="block">
         <article class="cntry_img">
         <img <?php echo $lazyClass.' '.$dataSrc; ?> alt="<?php echo $countryNameForURL; ?>">
         </article>
         <article class="sch_brf">
           <p class="fnt__14__bold clr__3"><?php echo $value['totalScholarships'];?> Scholarships in <?php echo $countryMapping[$value['countryId']]; ?></p>
           <p class="mt__5 fnt__12__semi clr__6">
            <?php if($value['totalAmount'] != ''){
                echo $value['totalAmount'];
              }else{
                echo 'View all Scholarships in '.$countryMapping[$value['countryId']];
              }
            ?>
           </p>
         </article>
        </a>
      </section>
      <?php } ?>
  </div>
</section>