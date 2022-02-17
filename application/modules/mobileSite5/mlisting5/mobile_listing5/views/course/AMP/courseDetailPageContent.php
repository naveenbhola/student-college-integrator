<section class="s-container">
    <?php $this->load->view('mcommon5/AMP/dfpBannerView',array('bannerPosition' => 'leaderboard'));?>

         
        <?php  
          $this->load->view('course/AMP/courseDetailTopWidget');
          $displayedSectionCount = 0;
         foreach($navigationSection as $section){
                switch($section){
                        case 'Highlights': 
                                 if(!empty($highlights)) {
                                    $this->load->view('course/AMP/Widgets/highlightsWidget');
                                    $displayedSectionCount++;
                                 }
                                 if($dfpData['client'] == 1) {
                                      $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "client"));
                                    }
                                break;
                        case 'Eligibility':
                                if(!empty($eligibility) && $eligibility['showEligibilityWidget'])
                                {
                                    $this->load->view('mobile_listing5/course/AMP/Widgets/eligibilityCutOffWidget');
                                    $displayedSectionCount++;
                                }
                                break;
                        case 'Admissions':
                                if(!empty($admissions) || !empty($importantDatesData['importantDates']))
                                {
                                    $this->load->view('mobile_listing5/course/AMP/Widgets/admissionWidget');
                                    $displayedSectionCount++;
                                }
                                $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON"));
                                $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON1"));
                                break;
                        case 'Fees':
                                if(!empty($fees))
                                {
                                    $this->load->view('mobile_listing5/course/AMP/Widgets/courseFeesWidget');
                                    $displayedSectionCount++;
                                }
                                break;
                        case 'Gallery':
                                    $this->load->view('mobile_listing5/course/widgets/courseGalleryWidget');
                                    $displayedSectionCount++;
                                break;
                        case 'Seats':
                                    if(!empty($seats))
                                    {
                                        $this->load->view('mobile_listing5/course/AMP/Widgets/courseSeatWidget');
                                        $displayedSectionCount++;
                                    }
                                break;
                        case 'Placements':
                            if(!empty($placements) || !empty($placementsCompanies)){
                                $this->load->view('course/AMP/Widgets/coursePlacementWidgetAMP');
                                $displayedSectionCount++;
                            }
                        break;
                        case 'naukriSalary':
                              if(!empty($ifNaukriDataExists) && $ifNaukriDataExists != 0){ 
                                $this->load->view('course/AMP/Widgets/courseAlumniWidgetAMP');
                                $displayedSectionCount++;
                             }
                        break;
                        case 'Structure':
                                    if(!empty($courseStructure))
                                    {
                                        $this->load->view('mobile_listing5/course/AMP/Widgets/courseStructureWidget');
                                        $displayedSectionCount++;
                                    }
                                break;
                        case 'Contact':
                                echo $schemaContact;
                                echo $contactWidget;
                                $displayedSectionCount++;
                                break;
                        case 'Reco1':
                                echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$courseId,'course', 'alsoViewed',array(),true);
                                $displayedSectionCount++;
                                break;
                        case 'Reco2':
                                echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$courseId,'course', 'similar',array(),true);
                                $displayedSectionCount++;
                                break;
                        case 'CategoryPageLinks':                    
                                   if(!empty($interLinkingLinks)){
                                        $this->load->view('mobile_listing5/course/AMP/Widgets/courseCategoryPageLinkingWidget');
                                        $displayedSectionCount++;
                                    }
                                    break;
                        case 'Partner':
                                    if($partners)
                                    {
                                        $this->load->view('mobile_listing5/course/AMP/Widgets/coursePartnerWidget');
                                        $displayedSectionCount++;
                                    }
                                    break;
                        case 'ApplyNow':
                                    $this->load->view('mobile_listing5/course/AMP/Widgets/courseApplyNowWidget');
                                    $displayedSectionCount++;
                                    break;  
                        case 'Reviews':
                                    if(!empty($reviewWidget['html'])){
                                        echo $reviewWidget['html'];
                                        $displayedSectionCount++;
                                    }
                                    break;
                        case 'chpWidget':
                                    $this->load->view('mcommon5/chpInterLinking');
                                    break;
                        case 'AnA':
                                    if(trim($anaWidget['html'])) {
                                        echo $anaWidget['html'];
                                        $displayedSectionCount++;
                                    }
                                    echo modules::run('mobile_listing5/InstituteMobile/populateAnAProposition',$courseId,'course',true);
                                    break;

                }?>

                <?php
                if($displayedSectionCount==1)
                {
                    $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA"));
                    $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA1"));
                    $displayedSectionCount=5;
                }
            }

         
        
            if($isMultilocation){
                echo modules::run('mobile_listing5/InstituteMobile/getMultiLocationLayerForCourse',$courseObj,$currentLocationObj,true);
            }
         ?>
     </section>


 