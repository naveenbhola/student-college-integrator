<a class="left-slide" style="display:none;"><i></i></a>

<ul class="head-ul crse-ul nav-bar-list">
 <?php
  foreach ($navigationSection as $key => $section) {
    switch ($section) {
        case 'Eligibility':
            if(!empty($eligibility) && $eligibility['showEligibilityWidget'])
            echo '<li><a elementtofocus="eligibility" ga-track="ElIGIBILITY_NAVIGATION_COURSEDETAIL_DESKTOP">Eligibility</a></li>';
            break;
        case 'Reviews':
             $reviewWidgetHtml  = trim($reviewWidget['html']);
             if(!empty($reviewWidgetHtml)){
                 echo '<li><a elementtofocus="review" ga-track="REVIEWS_NAVIGATION_COURSEDETAIL_DESKTOP">Reviews <sub class="sub-cl"></sub></a></li>';
             }
         break;    
        case 'Fees':
            if(!empty($fees))
                echo '<li><a elementtofocus="fees" ga-track="FEES_NAVIGATION_COURSEDETAIL_DESKTOP">Fees</a></li>';
            break;
        case 'Highlights':
            if(!empty($highlights))
                echo '<li><a elementtofocus="highlights" ga-track="HIGHLIGHTS_NAVIGATION_COURSEDETAIL_DESKTOP">Highlights</a></li>';
            break;
        case 'Gallery':
            if(!empty($galleryWidget))
                echo '<li><a elementtofocus="gallery" ga-track="GALLERY_NAVIGATION_COURSEDETAIL_DESKTOP">Gallery <sub class="sub-cl">'.$galleryWidget['totalCount'].'</sub></a></li>';
            break;
        case 'Structure':
            if(!empty($courseStructure))
                echo '<li><a elementtofocus="structure" ga-track="STRUCTURE_NAVIGATION_COURSEDETAIL_DESKTOP">Structure</a></li>';
            break;
        case 'Admissions':
            if(!empty($admissions) || !empty($importantDatesData['importantDates'])){
                if(empty($admissions)){
                    echo '<li><a elementtofocus="admissions" ga-track="DATES_NAVIGATION_COURSEDETAIL_DESKTOP">Dates</a></li>';
                }
                else{
                    echo '<li><a elementtofocus="admissions" ga-track="ADMISSION_NAVIGATION_COURSEDETAIL_DESKTOP">Admissions</a></li>';
                }
            }
            break;
        case 'Placements':
            if(!empty($placements) || !empty($placementsCompanies) || !empty($internships) && $internships->getReportUrl()){
                echo '<li><a elementtofocus="placements" ga-track="PLACEMENTS_NAVIGATION_COURSEDETAIL_DESKTOP">Placements</a></li>';
            }
            break;
        case 'Seats':
            if(!empty($seats))
                echo '<li><a elementtofocus="seats" ga-track="SEATS_NAVIGATION_COURSEDETAIL_DESKTOP">Seats Info</a></li>';
            break;
        case 'AnA':
            $anaWidgetHtml = trim($anaWidget['html']);
            if(!empty($anaWidgetHtml)) {
                echo '<li><a elementtofocus="ana" ga-track="QA_NAVIGATION_COURSEDETAIL_DESKTOP">Q&A ';
                if($anaWidget['count'] > 1) {
                    echo '<sub class="sub-cl"></sub>';
                }
                echo '</a></li>';
            }
            break;
        case 'Partner':
            if(!empty($partners))
                echo '<li><a elementtofocus="partner" ga-track="PARTNER_NAVIGATION_COURSEDETAIL_DESKTOP">Partner</a></li>';
            break;
        case 'Contact':
            $contactWidget  = trim($contactWidget);
            if(!empty($contactWidget)){
                echo '<li><a elementtofocus="contact" ga-track="CONTACT_NAVIGATION_COURSEDETAIL_DESKTOP">Contact <span></span></a></li>';
            }
        break;
        default:
            # code...
            break;
    }
}?>
</ul>
<a class="right-slide" style="display:none;"><i></i></a>