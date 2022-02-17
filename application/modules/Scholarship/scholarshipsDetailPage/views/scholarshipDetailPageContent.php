<?php $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageTitleSection'); ?>
<div class="dtls-wrap">
    <div class="dtls-content">
        <?php $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageLeftStickySection'); ?>

        <div class="dtls-lftbar">

            <?php 
                    $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageOverviewSection');
                    //Study Areas of scholarship
                    $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageStudyAreaSection');
                    //Scholarship amount details 
                    $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageAmountSection');
                    //Student Awards
                    if($scholarshipObj->getDeadline()->getNumAwards() > 0 || $scholarshipObj->getDeadline()->getNumAwards() == -1) {
                        $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageStudentAwardsSection');
                    }
                    //Scholarship Eligibility
                    $data['specialRestrictionObj'] = $scholarshipObj->getSpecialRestrictions();
                    $data['restrictions'] = $data['specialRestrictionObj']->getRestrictions();
                    $data['restrictionsDescription'] = $data['specialRestrictionObj']->getDescription();
                    $data['eligibilityObj'] = $scholarshipObj->getEligibility();
                    $data['exams'] = $data['eligibilityObj']->getExams();
                    $data['education'] = $data['eligibilityObj']->getEducation();
                    $data['workEx'] = $data['eligibilityObj']->workXPRequired();
                    $data['interview'] = $data['eligibilityObj']->interviewRequired();
                    $data['desc'] = $data['eligibilityObj']->getDescription();
                    $data['pref'] = $data['eligibilityObj']->getPreference();
                    $data['workExYears'] = $data['eligibilityObj']->getWorkXP();
                    if(!empty($data['restrictions']) || 
                        !empty($data['exams']) || 
                        !empty($data['education']) ||
                        $data['workEx'] > 0 || 
                        $data['interview'] > 0 || 
                        $data['desc'] != '' || 
                        $data['pref'] != '' ||
                        !empty($applicableNationalities)){ 
                            $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageEligibilitySection',$data);
                    }
                    $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageBellyCTA');
                    //Application Process for this scholarship
                    if($scholarshipObj->getDeadline()->getApplicationEndDate() != '' || 
                        $scholarshipObj->getDeadline()->getApplicationStartDate() != '' || 
                        !empty($finalImpDateData) || 
                        $scholarshipObj->getDeadline()->getApplicationStartDateDescription() != '' || 
                        $scholarshipObj->getDeadline()->getApplicationEndDateDescription() != '' || 
                        !empty($docsRequired) || 
                        $scholarshipObj->getDeadline()->getAdditionalInfo() != '') {
                            $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageApplicationProcessSection'); 
                        }
                    $this->load->view('scholarshipsDetailPage/sections/scholarshipDetailPageBottomCTA');
            ?>
        </div>
<!--end of rightsidebar-->

       </div>
    <?php 
       $this->load->view('widget/similarScholarships'); 
       if(count($scholarshipStatistics)>0){
        $this->load->view('scholarshipHomepage/sections/countryScholarships');
    }
    ?>
   </div>
