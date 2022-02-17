<?php 
  $scholarshipsToDisplay = 5; 
  $totalScholarships = count($popularScholarships);
  $sliderCount = ($totalScholarships%$scholarshipsToDisplay==0) ? ($totalScholarships/$scholarshipsToDisplay) : (($totalScholarships/$scholarshipsToDisplay)+1);
  $showSliderArrows = count($popularScholarships)>$scholarshipsToDisplay ? 1 : 0;
  $index = 0;
?>
<div class="fluid__div">
      <h2 class="fnt__30 clr__3 txt__cntr">Popular Scholarships to Study Abroad</h2>
      <div class="max__container">
        <div  id="popular__ul" class="c-cont">
            <div class="slider-box">
                <?php if($showSliderArrows){ ?>
                  <a class="buttons prev ui-arrow lft-arrw popularScholarshipLeft"><i class="prv"></i></a>
                <?php }?>
                  <div class="viewport">
                     <ul class="popular__ul popularScholarship-list">
                        <?php foreach ($popularScholarships as $key=>$value) {  ?>
                        <?php if($index%$scholarshipsToDisplay == 0){ ?>
                         <li class="clearfix">
                        <?php  } ?>
                           <div class="block new__div">
                              <div class="schlr__title">
                                <a href="<?php echo $value['seoUrl'];?>" class="fnt__14__bold clr__blue"> <?php echo formatArticleTitle(htmlentities($value['saScholarshipName']),55);?> </a>
                              </div>
                              <div class="schrl__dtls">
                                <div class="height__fix mb__10">
                                  <p class="fnt__12 clr__6">Scholarship amount <strong class="block"><?php echo ($value['saScholarshipAmount']>0) ? 'Rs '.moneyFormatIndia($value['saScholarshipAmount']).'/-' : 'Amount not available';?></strong> </p>
                                  <?php if($value['saScholarshipAwardsCount']>0){?>
                                  <p class="fnt__12 clr__9">(<?php echo $value['saScholarshipAwardsCount']. " student ". ($value['saScholarshipAwardsCount']==1 ? 'award' : 'awards') ?>)</p>
                                  <?php } ?>
                                </div>
                                <p class="fnt__12 clr__6 mb__10">Scholarship type<strong class="block"><?php echo ucfirst($value['saScholarshipType']);?> Based</strong></p>
                                <p class="fnt__12 clr__6">Applicability <strong class="ib__block"><?php echo $value['saScholarshipUniversity']; if($value['countryCount']>=3){echo " + ".($value['countryCount']-2)." more";}  ?></strong></p>
                              </div>
                              <a href="<?php echo $value['seoUrl'];?>" class="btns__new prime__btn fnt__12__bold gaTrack" gaParams="ScholarshipHomePage,ViewAndApply">View & Apply</a>
                           </div>
                        <?php if(($index%$scholarshipsToDisplay == $scholarshipsToDisplay-1) || ($index==count($popularScholarships)-1)){?>
                          </li>
                        <?php } $index++; }?>
                     </ul>
                  </div>
                <?php if($showSliderArrows){ ?>
                    <a class="buttons next ui-arrow rgt-arrw popularScholarshipRight"><i class="nxt"></i></a>
                <?php }?>
            </div>
            <div class="slider-indicator popularScholarshipSliderInd">
              <ul class="">
                  <?php for($i=0;$i<$sliderCount;$i++){ ?> 
                      <li><a class="bullet dot-pt <?php echo ($i==0?"active":""); ?>"></a></li>
                  <?php } ?>
              </ul>
            </div>
        </div>
      </div>
</div>
