 <div class="userpage-container">
        <div class="page-heading  header-fix">
            <a href="#" class="p-title">Add/Edit Education background</a>
            <a href="#" class="cls-head flRt" data-rel="back">&times;</a>
        </div>
        <div class="workexp-dtls">
        
            <form id="registrationForm_<?=$regFormId?>">
              <div id="sectionsDiv">
                    <?php $this->load->view('userEducationSections'); ?>
              </div>
              <input type="hidden" value="educationalBackgroundSection" name="sectionType" id="sectionType_<?php echo $regFormId; ?>" >
              <input type='hidden' name='isStudyAbroad' id="isStudyAbroadFlag_<?php echo $regFormId; ?>" value='<?php echo $isStudyAbroadFlag;?>' />
              <input type='hidden' name='abroadSpecialization' id="abroadSpecialization_<?php echo $regFormId; ?>" value='<?php echo $abroadSpecializationFlag;?>' />
            </form>

            <?php //if($isAddMore != 'YES'){ ?>
                <section class="workexp-cont clearfix" id="educationBlockAddMore">
                   <article class="workexp-box">
                       <div class="dtls">
                           <a class="add-sec" href="javascript:void(0);"><i class="profile-sprite addplus-icon flLt"></i>Add Education Background</a>
                       </div>
                   </article>

               </section>
           <?php //} ?>
           
        </div>
      <input type="button" class="common-btn bottom-fix saveButton" value="save"  />
    </div>