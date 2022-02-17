<?php
//SEO title , meta tags & description for listings
if(($details['status'] == 'live') || isset($cmsData))
{ 
    if($listing_type == "institute" || $listing_type == "course"){
        $pageTitle = $details['title'];
        $locations = array();
        $optionalArgs = array();
        for($i = 0; $i < count($details['locations']); $i++){
            $optionalArgs['location'][$i] = $details['locations'][$i]['city_name']."-".$details['locations'][$i]['country_name'];
        }
        $pageTitle .=" - ".implode("-",$optionalArgs['location']);
        if($listing_type == "course"){
            $metaDescription = "Find detailed information of courses and admissions in $pageTitle ,".$details['institute_name']." institute . View various courses offered by ".$details['institute_name']." institute  ".implode("-",$optionalArgs['location'])." and discuss on the community forum on Shiksha.com";
            $metaKeywords = "Shiksha, $pageTitle ".$details['institute_name'].", graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships ";
            $pageTitle = "Shiksha.com ".$details['title']." , ".$details['institute_name']."  institute ".implode("-",$optionalArgs['location'])." Course Information, Syllabus, Duration, Eligibility, Qualification ";
        }else{
            $metaDescription = "Find detailed information of courses and admissions in $pageTitle . View various courses offered by $pageTitle and discuss on the community forum on Shiksha.com ";
            $metaKeywords = "Shiksha, $pageTitle , graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships.";
            $pageTitle = "Shiksha.com $pageTitle Courses Information, Admissions, Scholarships, Contact, Address";
        }

    }
    else{
        $pageTitle = $details['title'];
        $instituteList = "";
        $locList = "";
        foreach($details['instituteArr'] as $insti){
            $instituteList .=" ".$insti['institute_name'];
            foreach($insti['locations'] as $loc){
                $locList .= " ".$loc['city_name'] ." ".$loc['country_name'];
            }
        }

        if($listing_type=="scholarship"){
            $pageTitle = "Shiksha.com - Scholarships - $pageTitle - $instituteList - Institute University Scholarship";
            $metaKeywords = "Shiksha $pageTitle , $instituteList , Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships";
            $metaDescription = "Information on $pageTitle $instituteList , qualification, application process, selection process, amount of scholarship now on Shiksha.com. Find Scholarship details, admission information and examination / test results of various institutes and universities in India and abroad";
        }else{
            $date = "";
            $app_end_date = "";
            $app_start_date = "";
            $end_date = "";
            $exam_date = "";

            if(isset($details['application_brochure_start_date']) && ($details['application_brochure_start_date'] != "0000-00-00 00:00:00")){
                $app_start_date =  date('j F',strtotime($details['application_brochure_start_date']));
            }
            if(isset($details['application_brochure_end_date']) && ($details['application_brochure_end_date'] != "0000-00-00 00:00:00")){
                $app_end_date = date('j F',strtotime($details['application_brochure_end_date']));
            }
            if(isset($details['application_end_date']) && ($details['application_end_date'] != "0000-00-00 00:00:00")){
                $end_date =  date('j F',strtotime($details['application_end_date']));
            }
            if($details['entrance_exam'] == "yes") {
                if(isset($details['exam_info'][0]['exam_date']) && ($details['exam_info'][0]['exam_date'] != "0000-00-00 00:00:00")){
                    $exam_date =  " exam on ".date('j F',strtotime($details['exam_info'][0]['exam_date']));
                }
            }
            $pageTitle = "Shiksha.com - ".$pageTitle." – $locList - $app_start_date - $app_end_date - $end_date - $exam_date Education Events – Admissions – Scholarships – Results – Career Fairs";
            $metaKeywords = " $pageTitle – $locList - $app_start_date - $app_end_date - $end_date  - $exam_date  , Event Details, date, Organiser. Find now on Shiksha.com. Find Scholarship details, admission information and examination / test results of various institutes and universities in India and abroad.";
            $metaDescription = "Shiksha,  $pageTitle – $locList - $app_start_date - $app_end_date - $end_date  - $exam_date  Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships";
        }
    }
}
else{
    $pageTitle = "Listing does not exist";
}

$headerComponents = array(
                          'css'   => array(
                                            'header',
                                          'mainStyle',
                                          'raised_all',
                                          'footer',
                                          //'cal_style'
                                          ),
                          'js'    => array(
                                          'common',
                                          //'alerts',
                                          //'prototype',
                                          //'discussion',
                                          'cityList'
                                           ),
                          'title' => $pageTitle,
                          'tabName' => 'Listing', 
                          'taburl' => $thisUrl, 
                          'product' => 'home', 
                          'bannerProperties' => array('pageId'=>'LISTINGS', 'pageZone'=>'HEADER'),
                          'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                          'callShiksha'=>1,
                          'metaKeywords'  =>"$metaKeywords",
                          'metaDescription'  =>"$metaDescription"
                         );
//$this->load->view('common/header', $headerComponents);
$this->load->view('common/homepage', $headerComponents);
$this->load->view('common/overlay');

if(isset($validateuser[0]['cookiestr']))
{
    $userEmailArray=explode("|",$validateuser[0]['cookiestr']);
    $userEmail=$userEmailArray[0];
}
else
{
    $userEmail="";
}
if(isset($validateuser[0]['mobile']))
{
    $usermobile=$validateuser[0]['mobile'];
}
else
{
    $usermobile="";
}
$overlayComponents = array(
        'userEmailAddress'=>$userEmail,
        'userMobileNumber'=>$usermobile
        );

if(isset($validateuser[0]['cookiestr'])){
    $this->load->view('search/searchRequestInfo',$overlayComponents);
}
?>

    <script>
        var SITE_URL = '<?php echo base_url() ."/";?>';
        var BASE_URL = SITE_URL;
	</script>



<!--Start_Center-->
<div style="width:99.5%">
	<!--End_Right_Panel-->
	<div>
	</div>
	<!--End_Right_Panel-->
	
	
	<!--Start_Left_Panel-->
    <!-- left_panel -->
	<div id="">
    </div>
	<!--End_Left_Panel-->
<!-- start mid panel -->
<?php 

if(($details['status'] == 'live') || isset($cmsData))
{ 
    $this->load->view("listing/".$listing_type."Details");
}
else{
    $this->load->view("listing/listingNotFound"); 
}

   ?>
<!-- end mid panel -->
<!-- beacon code start-->
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<script>
       var img = document.getElementById('beacon_img');
       var randNum = Math.floor(Math.random()*Math.pow(10,16));
       img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?php echo $type_id; ?>+<?php echo $listing_type; ?>';
       fillProfaneWordsBag() ;
</script>

<?php

if(!isset($cmsData)){
    echo '<script>var globalAdsContainer = "rightpanelads";</script>';
    $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'SIDE');
    $this->load->view('common/banner',$bannerProperties);
}
$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
$this->load->view('common/footer',$bannerProperties);
?>
