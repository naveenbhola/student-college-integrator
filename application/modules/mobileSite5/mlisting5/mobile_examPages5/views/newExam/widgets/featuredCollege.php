<section>
    <div class="data-card f-col">
        <h2 class="color-3 f16 heading-gap font-w6">Featured Institute</h2>
        <div class="f14 color-3 ana-slider" style="overflow:hidden">
            <ul style="width:1200px; overflow-x:scroll">
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
                <li ga-attr="FEATURED_INSTITUTE">
                  <a class="_block f-card" href="<?php echo $data['redirectUrl'];?>" <?=$addNoFollow?>>
                    <div class="color-w pad-10">
                        <span class="f-img f-lt"><img class="lazy" data-original="<?php echo $data['insImage'];?>" alt="<?php echo htmlentities($data['insName']);?>" width="79" height="60"></span>
                        <div class="f-det">
                            <span class="font-w6 f12 color-3 block l-14"><?php echo htmlentities($data['insName']);?></span>
                            <span class="f11 color-3 font-w4 l-14 block m-5top"><?php echo htmlentities($data['courseName']);?></span>
                            <span class="f12 font-w6 color-b l-14 block m-5top"><?php echo htmlentities($data['CTAText']);?></span>
                        </div>
                    </div>
                  </a>
                </li>
            <?php }else{?>
               <li ga-attr="FEATURED_INSTITUTE">
                    <div class="color-w pad-10 f-card">
                        <a href="<?php echo htmlentities($data['insUrl']);?>" class="f-img f-lt"><img  class="lazy" data-original="<?php echo $data['insImage'];?>" alt="<?php echo htmlentities($data['insName']);?>" width="79" height="60"></a>
                        <div class="f-det">
                            <a href="<?php echo htmlentities($data['insUrl']);?>" class="font-w6 f12 color-3 block l-14"><?php echo htmlentities($data['insName']);?></a>
                            <a href="<?php echo htmlentities($data['courseUrl']);?>" class="f11 color-3 font-w4 l-14 block m-5top"><?php echo htmlentities($data['courseName']);?></a>
                        </div>
                    </div>
                </li>

            <?php }} ?>
            </ul>
        </div>
    </div>
</section>
