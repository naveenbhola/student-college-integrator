<div class="sitemap-block">
  <div class="sitemap-container">
    <div class="row brdr-btm"><div class="col-lg-12"><h1 class="main-title">Shiksha Sitemap</h1></div></div>
    
    <div class="row">
      <div class="col-lg-12">
        <h2 class="sub-title"><?php echo $courseHeading;?></h2>
      </div>
    </div>
      <?php
      
      if (empty($substreams)) {
          $substreams = array();
      }
      if (empty($specializations)) {
          $specializations = array();
      }
      $specializationList = array_merge($substreams, $specializations);
      if(count($specializationList) > 0){ ?>

      <div class="row brdr-btm">
          <div class="col-lg-12">
              <h3 class="sub-title"><?php echo $specializationHeading; ?></h3>
          </div>
          <div class="col-lg-12"
               style="-moz-column-count: 3;-webkit-column-count: 3;column-count: 3;-moz-column-fill:balance;-ms-column-count:3; ">
              <ul>
                  <?php
                  foreach ($specializationList as $specialization) { ?>
                      <li><a href="<?php echo $specialization['link']; ?>"><?php echo $specialization['text']; ?></a>
                      </li>
                  <?php } ?>
              </ul>
          </div>
      </div>
      <?php } ?>

    <div class="row">
      <div class="col-lg-12">
        <h2 class="sub-title"><?php echo $heading;?></h2>
      </div>
    </div>
  <?php  if(count($stateList) > 0){ ?>
  <div class="row brdr-btm">
      <div class="col-lg-12">
      <h3 class="sub-title2">STATES</h3>
      </div>
      
      <div class="col-lg-12" style="-moz-column-count: 3;-webkit-column-count: 3;column-count: 3;-moz-column-fill:balance;-ms-column-count:3; ">
          <ul>
              <?php foreach ($stateList as $stateName => $oneUrl) { ?>
                  <li><a href="<?php echo $oneUrl; ?>"><?php echo $stateName; ?></a></li>
              <?php } ?>
          </ul>
      </div>
    </div>
   <?php }?>
   <?php  if(count($metroAndPopularCities) > 0){ ?>
    <div class="row brdr-btm">
      <div class="col-lg-12">
      <h3 class="sub-title2">POPULAR LOCATIONS</h3>
      </div>
   
        <div class="col-lg-12" style="-moz-column-count: 2;-webkit-column-count: 2;column-count: 2;-moz-column-fill:balance;-ms-column-count:2; ">
        <ul>
        <?php foreach ($metroAndPopularCities as $cityName => $oneUrl) { ?>
              <li><a href="<?php echo $oneUrl; ?>"><?php echo $cityName; ?></a></li>
      <?php } ?>
          </ul>
      </div>
    </div>
      <?php }?>
       <?php  if(count($alphabetWiseCities) > 0){ ?>
    <div class="row last-row" id="citiesList">
      <div class="col-lg-12">
      <h3 class="sub-title2 flLt">ALL CITIES</h3>
      <div class="clear"></div>
      <?php if($alphabetWiseCities) {?>
      <!-- <div class="search-layer-field1">
        <i class="msprite1 search-sml-icn1"></i>
        <input type="text" class="search-clg-field1" placeholder="Search a city" autocomplete="off" name="search" id="filterCities">
        <a id="keywordCross" style="display:none;" onclick="clearCollegeText(this);" class="clg-rmv">×</a>
      </div> -->
      <?php } ?>
      </div>
      <div class="col-lg-4 no-data-div hide"><i class='no-data'></i></div>
        <div class="col-lg-12" style="-moz-column-count: 3;-webkit-column-count: 3;column-count: 3;-moz-column-fill:balance;-ms-column-count:3; ">
          <ul>
      <?php foreach ($alphabetWiseCities as $cityName => $cityUrl) { ?>
              <li class='cityList'><a href="<?php echo $cityUrl;?>"><?php echo $cityName;?></a></li>
        <?php } ?>
            </ul>
        </div>
    </div>
     <?php }?>
</div>
</div>
<script>

 