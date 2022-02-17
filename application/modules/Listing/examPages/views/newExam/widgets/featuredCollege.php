 <div class="card__right mt__15 ps__rl global-box-shadow">
  <h3 class="f16__clr3 fix__sec fnt__sb">Featured Institute</h3>
  <?php if(count($info)>1){ ?>
  <p class="new__slide"><a id="prev" class="slide__prev disabled"><i></i></a> <a id="next" class="slide__next active"><i></i></a></p>
  <?php } ?>
 <div class="mtop__10 slider_main" style="width: 100%">
    <ul class="slider-list">
    <?php 
    $i = 1;
    foreach($info as $key=>$data){

      if(empty($data['courseName']) || empty($data['insName'])){
        continue;
      }
      if(strlen($data['insName']) > 70)
      {
        $data['insName'] = substr($data['insName'],0,67).'...';
      }
      if(strlen($data['courseName']) > 80)
      {
        $data['courseName'] = substr($data['courseName'],0,77).'...';
      }
      
      $activeLiClass = ($i==1)?'activeLi':'';
      $i++;
    ?>
      
      <?php if(!empty($data['CTAText']) && !empty($data['redirectUrl'])){
        $findString = '.shiksha.com';
        $checkShikshaUrl = strpos($data['redirectUrl'], $findString);
        if($checkShikshaUrl === false){
          $addNoFollow = 'rel="nofollow"';
        }else{
          $addNoFollow = '';
        }

        ?>
         <li class="mySlides" ga-attr="FEATURED_INSTITUTE">
          <a class="_block f-card <?=$activeLiClass?>" href="<?php echo $data['redirectUrl'];?>" target="_blank" <?=$addNoFollow?>>
            <div class="main-divs">
                 <!--img col-->
                 <div class="slide__img">
                    <span>
                      <img src="<?php echo $data['insImage'];?>" alt="<?php echo htmlentities($data['insName']);?>" style="width: 60px;height: 60px;">
                    </span>
                 </div>
                 <!--college inf-->
                 <div class="slide__text">
                    <p class="f14__clr3 fnt__sb"><span class="f14__clr3 fnt__sb"><?php echo htmlentities($data['insName']);?></span></p>
                    <p><span class="f12__clr3"><?php echo htmlentities($data['courseName']);?></span></p>
                    <span class="f14__clrb" ><?php echo htmlentities($data['CTAText']);?></span>
                  </div>
                  
             </div>
           </a>
        </li>               
      <?php }else{?>
        <li class="mySlides" ga-attr="FEATURED_INSTITUTE">
          <div class="main-divs f-card">
               <!--img col-->
               <div class="slide__img">
                  <a class="<?=$activeLiClass?>" href="<?php echo $data['insUrl'];?>" target="_blank">
                    <img src="<?php echo $data['insImage'];?>" alt="<?php echo htmlentities($data['insName']);?>" style="width: 60px;height: 60px;">
                  </a>
               </div>
               <!--college inf-->
               <div class="slide__text">
                  <p class="f14__clr3 fnt__sb"><a class="f14__clr3 fnt__sb" href="<?php echo htmlentities($data['insUrl']);?>" target="_blank"><?php echo htmlentities($data['insName']);?></a></p>
                  <p><a class="f12__clr3" href="<?php echo $data['courseUrl'];?>" target="_blank"><?php echo htmlentities($data['courseName']);?></a></p>
                </div>

             </div>
        </li>
      <?php }} ?>
    </ul>
 </div>
</div>
<script>
window.onload = function(){initializeCounselorSlider();}
function initializeCounselorSlider()
{
	bindSliderButtonsCounselorWidget();
}

/*
 * function to bind click event handler for slider buttons
 */
function bindSliderButtonsCounselorWidget()
{
  $j('#prev').bind("click",function(e){ 
    leftSlideEventHandlerCounselorWidget();
  });
  $j('#next').bind("click",function(e){ 
    rightSlideEventHandlerCounselorWidget();
  });
}


function leftSlideEventHandlerCounselorWidget()
{
    var sizeOfSlideMovement = (typeof arguments[0]== 'undefined'?348:arguments[0]*348);
    var nextActiveIndex =arguments[1];
    var currActive = $j(".slider_main").find('li a.activeLi');
    var nextActiveLI = typeof nextActiveIndex != 'undefined'?currActive.closest('ul').find('li').eq(nextActiveIndex):currActive.closest('li').prev();
    if (nextActiveLI.length == 0) {
        return false;
    }
    $j(".slider-list").animate( {left: "+="+sizeOfSlideMovement}, 500);
    currActive.removeClass('activeLi');
    nextActiveLI.find('a:first').addClass('activeLi');
    if (nextActiveLI.prev().length == 0) {
        $j(".slide__prev").removeClass('active');
        $j(".slide__prev").addClass('disabled');
    }
    $j(".slide__next").addClass('active');
    $j(".slide__next").removeClass('disabled');
}


function rightSlideEventHandlerCounselorWidget()
{

    var sizeOfSlideMovement = (typeof arguments[0]== 'undefined'?348:arguments[0]*348);
    var nextActiveIndex =arguments[1];
    var currActive = $j(".slider_main").find('li a.activeLi');
    var nextActiveLI = typeof nextActiveIndex != 'undefined'?currActive.closest('ul').find('li').eq(nextActiveIndex):currActive.closest('li').next();
    if (nextActiveLI.length == 0) {
        return false;
    }
    $j(".slider-list").animate( {left: "-="+sizeOfSlideMovement}, 500);
    currActive.removeClass('activeLi');
    nextActiveLI.find('a:first').addClass('activeLi');
    if (nextActiveLI.next().length == 0) {
        $j(".slide__next").removeClass('active');
        $j(".slide__next").addClass('disabled');
    }
    $j(".slide__prev").addClass('active');
    $j(".slide__prev").removeClass('disabled');
}
</script>
