<div class="content-wrap clearfix">
        <div class="abroad-compare-clg-title">
            <div class="clearfix" style="margin-bottom:8px;"><a href="<?php echo $referrerPageURL; ?>">< Go back to <?php echo $referrerPageTitle; ?></a></div>
            <div class="flLt">
                <h1>Compare colleges</h1>
                <p class="compare-count">You can compare up to 3 colleges</p>
            </div>
            <!-- <div class="flRt"><a href="#" class="button-style" style="background:#f1a536"><strong>Email me this comparision</strong></a></div> -->
            <div class="clearfix"></div>
        </div>
        <?php if(!empty($courseDataObjs)) { ?>
      <div class="compare-college-section">

        <?php $this->load->view('compareCourses/widgets/compareCoursesRecommendationSection');?>
           <!--******************************** about course data *************************************-->
        <?php $this->load->view('compareCourses/widgets/compareCoursesAboutCoursesSection');?>
            <!--****************************** 1st year fee details *************************************-->
        <?php $this->load->view('compareCourses/widgets/compareCoursesFeesDetailsSection');?>
            <!--****************************************Courses entry requirements details********************-->
        <?php $this->load->view('compareCourses/widgets/compareCoursesEligibilityRequirementsSection');?>
            <!--**************************************application process data**********************************-->
        <?php $this->load->view('compareCourses/widgets/compareCoursesApplicationProcessSection');?>
            <!--**************************************about university data**********************************-->
        <?php $this->load->view('compareCourses/widgets/compareCoursesUniversityInfoSection');?>
           <!--**************************************miscellaneous data**********************************-->
        <?php $this->load->view('compareCourses/widgets/compareCoursesMiscellaneousInfoSection');?>
        <!--************************************ Download Brochure and RMC button************************-->
        <?php //$this->load->view('compareCourses/widgets/compareCoursesCTA');?>
         </div>
         
         <!--************************************ sticky************************-->
                 
         <div class="sticky-compare-header clearwidth" id="compHeaderSticky" style="display:none;">
           <table border="0" cellpadding="0" cellspacing="0"  class="sticky-compare-header-table">
               <tr>
                   <th colspan="4">
                       <div class="flLt country-head"><a href="<?php echo $referrerPageURL; ?>">< Go back to <?php echo $referrerPageTitle; ?></a></div>
                           <!-- <div class="flRt" style="font-weight:normal">
                               Get this comparision on your email
                               <a href="#" class="button-style" style="background:#f1a536"><strong>Email me this comparision</strong></a>
                           </div> -->
                           <div class="clearfix"></div>
                       </th>
               </tr>
           </table>
            <table border="0" cellpadding="0" cellspacing="0" class="compare-head-table">
                <tr>
                    <th width="25%" ><div class="compare-detail-content"><strong>University name</strong></div></th>                    
                    <?php foreach ($courseDataObjs as $courseObj) { 
                         $univId            = $courseObj->getUniversityId();
                         $universityObject  = $univDataObjs[$univId];?>
                         <th width="25%">
                         <a href="<?php echo $universityObject->getURL();?>" class="compared-univ-name"><?php echo htmlentities($courseObj->getUniversityName());?></a>
                         </th>
                    <?php } ?>                    
                    <?php if($coursesCount == 1){?>
                     <th width="25%"></th>
                    <?php } ?>
                    <?php if($coursesCount < 3){?>
                    <th width="25%" style="border-bottom:none; padding-bottom:0;" rowspan="2">
                        <?php if(count($recommendedCourses)>0){ ?>
                        <h2 class="comp-similar-text">Compare with similar colleges</h2>
                        <ul class="compare-suggestion-list">
                            <?php $this->load->view('compareCourses/widgets/recommendedCoursesForCompare'); ?>
                        </ul>
                        <?php } ?>
                    </th>
                    <?php } ?>
                </tr>
                <tr>
                    <td><div class="compare-detail-content"><strong>Course name</strong></div></td>
                    <?php foreach ($courseDataObjs as $courseObj) { ?>
                    <td><a href="<?php echo $courseObj->getURL(); ?>"><?php echo htmlentities($courseObj->getName());?></a></td>
                    <?php } ?>                    
                    <?php if($coursesCount == 1){?>
                    <td></td>
                    <?php } ?>
                </tr>
            </table>
         </div>
    </div>
    <?php } ?>
