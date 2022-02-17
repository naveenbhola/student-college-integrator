
    <div class="aside col-lg-3 pL0 pLR0 sidebar">
        <div class="left_nav">
            <label class="nav_main_head">Filter your search </label>
            <?php 
                if($isTwoStepClosedSearch && empty($singleStreamClosedSearch)){
                    ?>
                    <ul class="menu accordion backFilter">
                        <li id='list'>
                            <a href="<?=$backToClosedSearch['url']?>"><i class="icons ic_left-gry"></i>All Streams</a>
                            <ul class="submenu">
                                <li>
                                    <div>
                                        <p><?=$backToClosedSearch['stream']?></p>
                                    </div>
                                </li>                            
                            </ul>
                       </li>
                    </ul>
                    <?php
                }
            ?>
            <ul class="menu">
                <?php foreach ($filters as $filterType => $filter) {
                    switch ($filterType) {
                        case 'stream':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/streams');
                            }
                            break;

                        case 'offered_by_college':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/colleges');
                            }
                            break;

                        case 'location':
                            $this->load->view('nationalCategoryList/filters/locations');
                            break;
                        
                        case 'exam':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/exams');
                            }
                            break;

                        case 'sub_spec':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/substreamSpecs');
                            }
                            break;
                        
                        case 'specialization':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/specializations');
                            }
                            break;

                        case 'base_course':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/baseCourses');
                            }
                            break;

                        case 'popular_group':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/popularGroups');
                            }
                            break;

                        case 'certificate_provider':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/certificateProviders');
                            }
                            break;

                        case 'course_level':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/courseLevels');
                            }
                            break;

                        case 'et_dm':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/educationDelivery');
                            }
                            break;

                        case 'credential':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/credentials');
                            }
                            break;

                        case 'level_credential':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/levelCredential');
                            }
                            break;

                        case 'accreditation':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/accreditation');
                            }
                            break;

                        case 'college_ownership':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/ownership');
                            }
                            break;

                        case 'approvals':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/approvals');
                            }
                            break;

                        case 'fees':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/fees');
                            }
                            break;
                        case 'review':
                            if(!empty($filter) && $product == "Category") {
                                $this->load->view('nationalCategoryList/filters/reviews');
                            }
                            break;    

                        case 'facilities':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/facilities');
                            }
                            break;

                        case 'course_status':
                            if(!empty($filter)) {
                                $this->load->view('nationalCategoryList/filters/courseStatus');
                            }
                            break;
                    }
                }?>
            </ul>
        </div>
        <?php if($product == "AllCoursesPage" && ($instituteObj->getType() == "university" || $isReviewExist || $isAnAExist || $isArticleExist)){
            ?>
            <div class="related-link-sec">
                <h2>Related <?php
                    if($instituteObj->getType() == "institute"){
                        echo "college";
                    }else{
                        echo $instituteObj->getType();    
                    }
                    
                 ?> links</h2>
                <ul>
                    <?php if($instituteObj->getType() == "university"){
                                $listingId = $instituteObj->getId();
                                $type = 'all_content_pages';
                                $optionalArgs['typeOfListing'] = $instituteObj->getType();
                                $optionalArgs['typeOfPage'] = 'admission';
                                $admissionUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $admissionUrl;?>" target="_blank">
                                   Admission Process
                                </a>
                            </li>
                            <?php
                    }?>
                    
                    <?php if($isReviewExist){
                                $listingId = $instituteObj->getId();
                                $type = 'all_content_pages';
                                $optionalArgs['typeOfListing'] = $instituteObj->getType();
                                $optionalArgs['typeOfPage'] = 'reviews';
                                $reviewUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $reviewUrl;?>" target="_blank">
                                   Student Reviews
                                </a>
                            </li>
                            <?php
                    }?>

                    <?php if($isAnAExist){
                            $listingId = $instituteObj->getId();
                            $type = 'all_content_pages';
                            $optionalArgs['typeOfListing'] = $instituteObj->getType();
                            $optionalArgs['typeOfPage'] = 'questions';
                            $anaUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $anaUrl;?>" target="_blank">
                                   Questions &amp; Discussions
                                </a>
                            </li>
                            <?php
                    }?>

                    <?php if($isArticleExist){
                              $listingId = $instituteObj->getId();
                            $type = 'all_content_pages';
                            $optionalArgs['typeOfListing'] = $instituteObj->getType();
                            $optionalArgs['typeOfPage'] = 'articles';
                            $articlesUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $articlesUrl;?>" target="_blank">
                                    News &amp; Articles
                                </a>
                            </li>
                            <?php
                    }?>

                    <?php if($isScholarshipExist){
                            $scholarshipUrl =  $instituteObj->getAllContentPageUrl('scholarships');
                            ?>
                            <li>
                                <a href="<?php echo $scholarshipUrl;?>" target="_blank">
                                   Scholarships
                                </a>
                            </li>
                            <?php
                    }?>
                </ul>
            </div>
            <?php
        }
        ?>
        
    </div>
