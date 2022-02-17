<!--home section-->
       <section class="bg__img">
         <div class="bg__opc">
           <p class="fnt16_bold cntr_align clr__white">The most comprehensive study abroad scholarships</p>
           <p class="sub__text fnt__12__semi clr__white"><?php echo $fsTotalScholarshipCount;?> scholarships. 11 countries. <?php echo $fsTotalScholarshipAmount;?> in total amount</p>
           <div class="home__col">
              <form class="" method="post">
                 <ul class="optn__list">
                   <li>
                     <div class="custom-dropdown" id="courseLevelContainer">

                       <select class="universal-select" name="scholarshipCourseLevel" id="scholarshipCourseLevel">
                          <option value="">Course Level</option>
                          <option value="bachelors">Bachelors</option>
                          <option value="masters">Masters</option>
                       </select>
                          <div class="err_hint" id="err_hint">
                         <div class="up_arow"></div>
                         <div class="helper_text">Choose course level before proceeding</div>
                       </div>
                     </div>
                   </li>
                   <li>
                     <div class="drop__col" id="categoryContainer">
                       <div id="categoryChooser" class="custom-dropdown">
                           <div class="drop-overlay"></div>
                           <select class="universal-select" name="courseStream" id="courseStream">
                               <option value="">Course Stream</option>
                           </select>
                       </div>
                       <div class="customDropLayer customInputs" id="categoryList" style="display:none; z-index: 2;">
                          <strong class="drop-title">You can select multiple streams here</strong>
                          <div class="drop-layer-cont">
                              <ul>
                                  <?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){ ?>
                                  <li>
                                     <div class="">
                                        <input type="checkbox" label="<?php echo $parentCategoryDetails['name']; ?>" class="streamSelect" id="s<?php echo $parentCategoryDetails['id']; ?>" name="course_stream[]"  value="<?php echo $parentCategoryDetails['id']; ?>" >
                                        <label for="s<?php echo $parentCategoryDetails['id']; ?>">
                                              <span class="sprite flLt"></span>
                                              <p><?php echo $parentCategoryDetails['name']; ?></p>
                                          </label>
                                     </div>
                                  </li>
                                  <?php } ?>
                              </ul>
                       </div>
                     </div>
                     </div>
                   </li>
                   <li>
                     <div class="drop__col" id="countryContainer">
                       <div id="countryChooser" class="custom-dropdown">
                           <div class="drop-overlay"></div>
                           <select class="universal-select" name="countryMapping[]" id="countryMapping">
                               <option value="">Study Destination</option>
                           </select>
                       </div>
                       <div class="customDropLayer customInputs" id="countryList" style="display:none; z-index: 1;">
                          <strong class="drop-title">You can select multiple countries here</strong>
                          <div class="drop-layer-cont">
                              <ul>
                                  <?php foreach($countryMapping as $id => $name){ ?>
                                  <li>
                                     <div class="">
                                        <input type="checkbox" label="<?php echo $name;?>" class="countrySelect" id="c<?php echo $id;?>" name="country_mapping[]"  value="<?php echo $id;?>" >
                                        <label for="c<?php echo $id;?>">
                                              <span class="sprite flLt"></span>
                                              <p><?php echo $name;?></p>
                                          </label>
                                     </div>
                                  </li>
                                  <?php } ?>
                              </ul>
                       </div>
                     </div>
                     </div>
                   </li>
                 </ul>
                 <a href="javascript:void(0);" id="findScholarships" trackingkeyid="<?php echo $findScholarshipTrackingId; ?>" class="btn btn-primary btn-full ui-link">Find Scholarships <i class="sprite l-arr"></i></a>
                 <p class="fnt__10 clr__white mt__10 txt__cntr"><a class="msng-schlr" href="<?php echo SUGGEST_MISSING_SCHOLARSHIP_LINK; ?>">Suggest a missing scholarship, win Rs 500!</a></p>
              </form>
           </div>
         </div>
         <!-- <div class="mask__layer"></div> -->
       </section>