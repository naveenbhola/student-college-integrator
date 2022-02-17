<?php 
  $countriesToDisplay = 6; 
  $index=0;
  $keyValue = 2;
  if($allLazyLoad) {
    $keyValue = 0;
  }
?>
<div class="fluid__div">
    <h1 class="fnt__30 clr__3 txt__cntr">Study Abroad Scholarships</h1>
    <div class="fix__div">
      <div id="find__sclr__abroad" class="c-cont">
        <div class="slider-box">
          <?php if(count($scholarshipStatistics)>6){ ?>
          <a class="buttons prev ui-arrow lft-arrw scholarshipCountryLeft"><i class="prv"></i></a>
          <?php }?>
          <div class="viewport">
            <ul class="clearfix find__schlr scholarshipCountry-list" >
                <?php foreach ($scholarshipStatistics as $key=>$value) {
                    $countryNameForURL = htmlentities(str_replace(' ', '-', strtolower($countryMapping[$value['countryId']])));
                    $imgURL = IMGURL_SECURE.'/public/images/scholarshipHomepage/'.$countryNameForURL.'.jpg';
                    if($key > $keyValue)
                    {
                      $lazyClass = 'class="lazy"';
                      $dataSrc = 'data-original="'.$imgURL.'"';
                    }else{
                      $lazyClass = '';
                      $dataSrc = 'src="'.$imgURL.'"';
                    }
                  ?>
                  <?php if($index%$countriesToDisplay == 0){ $sliderCount++; ?>
                        <li class="clearfix">
                  <?php  } ?>
                  <div class="img__placeholder">
                     <a href="<?php echo $scholarshipCountryCategoryPageURL[$value['countryId']]; ?>">
                     <img <?php echo $lazyClass.' '.$dataSrc; ?> alt="<?php echo $countryNameForURL; ?>">
                     <div class="img__mask"></div>
                     <h3 class="fnt__24 clr__white"><?php echo $value['totalScholarships'];?> Scholarships in <br/> <?php echo $countryMapping[$value['countryId']]; ?></h3>
                     <div class="more__about">
                        <h4 class="fnt__14 clr__white"><?php 
                        if($value['totalAmount'] != '')
                          echo $value['totalAmount'];
                        else
                          echo 'View all Scholarships in '.$countryMapping[$value['countryId']];

                        ?></h4>
                     </div>
                     </a>
                  </div>
                  <?php if(($index%$countriesToDisplay == $countriesToDisplay-1) || ($index==count($scholarshipStatistics)-1)){?>
                          </li>
                <?php } $index++; }?>
            </ul>
          </div>
          <?php if(count($scholarshipStatistics)>6){ ?>
          <a class="buttons next ui-arrow rgt-arrw scholarshipCountryRight"><i class="nxt"></i></a>
          <?php }?>
        </div>
        <div class="slider-indicator scholarshipCountrySliderInd">
            <ul class="">
                <?php for($i=0;$i<$sliderCount;$i++){ ?> 
                    <li><a class="bullet dot-pt <?php echo ($i==0?"active":""); ?>"></a></li>
                <?php } ?>
            </ul>
        </div>
      </div>

    </div>
</div>