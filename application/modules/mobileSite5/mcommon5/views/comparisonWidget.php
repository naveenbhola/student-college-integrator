<div class="flexslider">
  <ul class="slides">

        <?php foreach ($comparisonList as $compareItem){ ?>
        <li>
            <div class="slide-main-wrap">
                <div class="slide-prev"><i class="msprite prv-icn"></i></div>
                <div class="slider-wrap">
                    <ul>
                        <li style="cursor: pointer;" onClick="trackEventByGAMobile('MOBILE_COMPARISON_LINK_FROM_HOMEPAGE'); window.location='<?=SHIKSHA_HOME.'/compare-colleges-'.$compareItem['courseId_1'].'-'.$compareItem['courseId_2']?>'">
                            <div class="slider-row tac"><?=$compareItem['instituteName_1']?></div>
                            <div class="comp-divider"><p class="comp-vs tac">Vs</p></div>
                            <div class="slider-row tac"><?=$compareItem['instituteName_2']?></span></div>
                        </li>
                    </ul>
                </div>
                <div class="slide-next"><i class="msprite nxt-icn"></i></div>
            </div>
        </li>
        <?php } ?>
        
    </ul>
</div>
    
<div class="comp-opt-block">
    <div class="orTxt">
      <span>OR</span>
    </div>
    <div class="comp-btn-block">
      <a href="<?=SHIKSHA_HOME."/compare-colleges"?>" class="btn btn-default flLt" onClick="trackEventByGAMobile('MOBILE_COMPARISON_HOME_FROM_HOMEPAGE');" >choose colleges to compare</a>
    </div>
</div>

