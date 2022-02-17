<div class="clg-nav">
        <div class="clg-navList">
            <ul>
                <?php foreach ($navigationSection as $key => $section) {
                    switch ($section) {
                        case 'Eligibility':
                            if(!empty($eligibility) && $eligibility['showEligibilityWidget'])
                            echo '<li><a href="javascript:void(0)" elementtofocus="eligibility" ga-attr="ElIGIBILITY_NAVIGATION_COURSEDETAIL_MOBILE">Eligibility</a></li>';
                            
                            break;
                        case 'Highlights':
                            if(!empty($highlights))
                                echo '<li><a href="javascript:void(0)" elementtofocus="highlights" ga-attr="HIGHLIGHTS_NAVIGATION_COURSEDETAIL_MOBILE">Highlights</a></li>';
                            break;
                        case 'Fees':
                            if(!empty($fees))
                                echo '<li><a href="javascript:void(0)" elementtofocus="fees" ga-attr="FEES_NAVIGATION_COURSEDETAIL_MOBILE">Fees</a></li>';
                            break;
                        case 'Gallery':
                            $galleryWidget = trim($galleryWidget);
                            if(!empty($galleryWidget))
                                echo '<li><a href="javascript:void(0)" elementtofocus="gallery" ga-attr="GALLERY_NAVIGATION_COURSEDETAIL_MOBILE">Gallery<span></span></a></li>';
                            break;
                        case 'Structure':
                            if(!empty($courseStructure))
                                echo '<li><a href="javascript:void(0)" elementtofocus="structure" ga-attr="STRUCTURE_NAVIGATION_COURSEDETAIL_MOBILE">Structure</a></li>';
                            break;
                        case 'Admissions':
                            if(!empty($admissions) || !empty($importantDatesData['importantDates'])){
                                if(empty($admissions)){
                                    echo '<li><a href="javascript:void(0)" elementtofocus="admissions" ga-attr="DATES_NAVIGATION_COURSEDETAIL_MOBILE">Dates</a></li>';
                                }
                                else{
                                    echo '<li><a href="javascript:void(0)" elementtofocus="admissions" ga-attr="ADMISSION_NAVIGATION_COURSEDETAIL_MOBILE">Admissions</a></li>';
                                }
                            }
                            break;
                        case 'Placements':
                            if(!empty($placements) || !empty($placementsCompanies) || !empty($internships) && $internships->getReportUrl()){
                                echo '<li><a href="javascript:void(0)" elementtofocus="placements" ga-attr="PLACEMENTS_NAVIGATION_COURSEDETAIL_MOBILE">Placements</a></li>';
                            }
                            break;
                        case 'Seats':
                            if(!empty($seats))
                                echo '<li><a href="javascript:void(0)" elementtofocus="seats" ga-attr="SEATS_NAVIGATION_COURSEDETAIL_MOBILE">Seats Info</a></li>';
                            break;
                        case 'AnA':
                            $anaWidgetHtml = trim($anaWidget['html']);
                            if(!empty($anaWidgetHtml))
                                echo '<li><a href="javascript:void(0)" elementtofocus="qna" ga-attr="QA_NAVIGATION_COURSEDETAIL_MOBILE">Q&A <span></span></a></li>';
                            break;
                        case 'Partner':
                            if(!empty($partners))
                                echo '<li><a href="javascript:void(0)" elementtofocus="partner" ga-attr="PARTNER_NAVIGATION_COURSEDETAIL_MOBILE">Partner</a></li>';
                            break;
                        case 'Reviews':
                            $reviewWidgetHtml = trim($reviewWidget['html']);
                            if(!empty($reviewWidgetHtml)){
                                echo '<li><a href="javascript:void(0)" elementtofocus="reviews" ga-attr="REVIEWS_NAVIGATION_COURSEDETAIL_MOBILE">Reviews <span></span></a></li>';
                            }
                        break;
                        case 'Contact':
                            $contactWidget = trim($contactWidget);
                            if(!empty($contactWidget)){
                                echo '<li><a href="javascript:void(0)" elementtofocus="contact" ga-attr="CONTACT_NAVIGATION_COURSEDETAIL_MOBILE">Contact <span></span></a></li>';
                            }
                        break;
                        default:
                            # code...
                            break;
                    }
                }?>
            </ul>
        </div>
    </div>