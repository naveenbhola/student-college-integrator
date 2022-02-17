<section>
  <div class="data-card">
      <h2 class="color-3 f16 heading-gap font-w6">Featured Institute</h2>
      <amp-carousel class="carosel__height"  height="150" layout="fixed-height" type="carousel">
      <?php foreach ($info as $key=>$data){ ?>
            <?php
                if(empty($data['courseName']) || empty($data['insName'])){
         continue;
      }
      if(strlen($data['insName']) > 50)
      {
        $data['insName'] = substr($data['insName'],0,47).'...';
      }
      if(strlen($data['courseName']) > 50 )
      {
        $data['courseName'] = substr($data['courseName'],0,47).'...';
      }

      if(!empty($data['CTAText']) && !empty($data['redirectUrl'])){
            $findString = '.shiksha.com';
            $checkShikshaUrl = strpos($data['redirectUrl'], $findString);
            if($checkShikshaUrl === false){
              $addNoFollow = 'rel="nofollow"';
            }else{
              $addNoFollow = '';
            }
            ?>
        <figure class="color-w pad-10 wd3 wh-sp n-mg v-top ga-analytic" data-vars-event-name="FEATURED_INSTITUTE">
          <a href="<?php echo $data['redirectUrl'];?>" <?=$addNoFollow?>>
           <div class="t-cntr m-5btm f-lt"><span class="block"><amp-img class="f-im" src="<?php echo $data['insImage'];?>" width="72" height="53" layout=responsive alt="<?php echo htmlentities($data['insName']);?>"></amp-img></span></div>
           <div class="s-add f-det">
               <figcaption class="caption color-6 f12 font-w7">
                   <span class="block font-w6 f14 color-3 l-14"><?php echo htmlentities($data['insName']);?></span>
                   <span class="block color-6 f12 font-w4"><?php echo htmlentities($data['courseName']);?></span>
                   <span class="block font-w6 f14 color-b l-14"><?php echo htmlentities($data['CTAText']);?></span>
               </figcaption>
           </div>
          </a>
       </figure>
       <?php }else{?>
        <figure class="color-w pad-10 wd3 wh-sp n-mg v-top ga-analytic" data-vars-event-name="FEATURED_INSTITUTE">
           <div class="t-cntr m-5btm f-lt"><a href="<?php echo $data['insUrl'];?>" class="block"><amp-img class="f-im" src="<?php echo $data['insImage'];?>" width="72" height="53" layout=responsive alt="<?php echo htmlentities($data['insName']);?>"></amp-img></a></div>
           <div class="s-add f-det">
               <figcaption class="caption color-6 f12 font-w7">
                   <a class="block font-w6 f14 color-3 l-14" href="<?php echo $data['insUrl'];?>"><?php echo htmlentities($data['insName']);?></a>
                   <a class="block color-6 f12 font-w4" href="<?php echo $data['courseUrl'];?>"><?php echo htmlentities($data['courseName']);?></a>
               </figcaption>
           </div>
       </figure>
      <?php }} ?>
      </amp-carousel>
    </div>
</section>
