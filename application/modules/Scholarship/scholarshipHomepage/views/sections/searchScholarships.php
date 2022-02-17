<div class="page__image">
  <div class="img__mask"></div>
  <div class="filters_">
    <!-- <h1 class="fnt__30__bold clr__white">Find Scholarships for you</h1>#}-->
    <div class="fnt__32__semi clr__white banner-head">The most comprehensive study abroad scholarships</div>
    <p class="fnt__18__semi clr__white lh__24 mb__5 banner-caption"><?php echo $fsTotalScholarshipCount;?> Scholarships, 11 Countries, <?php echo $fsTotalScholarshipAmount;?> in total amount</p>
      <div class="filters__data clearfix">
          <div class="filters__optns clearfix flt__lft">
            <div class="optn__1  flt__lft drop_option">
              <i class="custm__ico" ></i>
               <p class="fnt__14 clr__3 selected_val" value="Course Level">Course Level</p>
               <div class="optns__block searchScholarshipOptions__1">
                  <ul>
                    <li class="fnt__14 clr__3" courseLevel="bachelors" >Bachelors</li>
                    <li class="fnt__14 clr__3" courseLevel="masters" >Masters</li>
                  </ul>
               </div>
               <input type="hidden" name="scholarshipCourseLevel" id="scholarshipCourseLevel">
               <div class="input-helper" >
                      <div class="up-arrow"></div>
                      <div class="helper-text">Choose course level before proceeding</div>
               </div>

            </div>
            <div class="optn__2  flt__lft drop_option">
              <i class="custm__ico" ></i>
               <p  class="fnt__14 clr__3 selected_val" >Course Stream</p>
               <div class="optns__block searchScholarshipOptions__2">
                  <ul>
                  <?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){ ?>
                    <li class="fnt__14 clr__3">
                       <input type="checkbox" name="course_stream[]" id="stream__<?php echo $parentCategoryId;?>" value="<?php echo $parentCategoryId;?>">
                       <label for="stream__<?php echo $parentCategoryId;?>" class="block course_stream"><span class="common-sprite"></span><?php echo $parentCategoryDetails['name'];?></label>
                   </li>
                   <?php } ?>
                   </ul>
               </div>
            </div>
            <div class="optn__3  flt__lft drop_option">
              <i class="custm__ico" ></i>
               <p  class="fnt__14 clr__3 selected_val" >Study Destination</p>
               <div class="optns__block searchScholarshipOptions__3">
                  <ul>
                  <?php foreach($countryMapping as $id => $name){ ?>
                    <li class="fnt__14 clr__3">
                       <input type="checkbox" name="country_mapping[]" id="abroad__<?php echo $id;?>" value="<?php echo $id;?>">
                       <label for="abroad__<?php echo $id;?>" class="block destination"><span class="common-sprite"></span><?php echo $name;?></label>
                   </li>
                   <?php } ?>
                  </ul>
               </div>
            </div>
          </div>
          <div class="filtes__get flt__right">
             <a class="fnt__14__bold btns__new prime__btn findScholarships" trackingkeyid="<?php echo $findScholarshipTrackingId; ?>">Find Scholarships</a>
          </div>
        </div>

  </div>
     <p class="fnt__14 clr__white txt__down"><a href="<?php echo SUGGEST_MISSING_SCHOLARSHIP_LINK; ?>">Suggest a missing scholarship, win Rs 500!</a></p>
</div>