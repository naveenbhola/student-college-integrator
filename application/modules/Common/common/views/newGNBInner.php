<?php
$this->load->library('category_list_client');
$categoryClient = new Category_list_client();
$featuredColleges = $categoryClient->getInstituteForTabs();
$this->load->library('cacheLib');
$cacheLib = new cacheLib();
$key = "featuredExams_GNB";
if($cacheLib->get($key)=='ERROR_READING_CACHE'){
    $this->load->model("examPages/exampagemodel_gnb");
    $featuredExams = $this->exampagemodel_gnb->getFeaturedExamsData();
    $cacheLib->store($key, $featuredExams , -1);
}
else{
    $featuredExams = $cacheLib->get($key);
}
if($featuredColleges[0]!='NO_DATA_FOUND'){
    foreach($featuredColleges as $institutes) {
        $key = '';
        if($institutes[course_type] == 'topmbacourses')
            $key = 'MBATop Colleges';
        elseif($institutes[course_type] == 'topgradcourses')
            $key = 'EngineeringTop Colleges';
        elseif($institutes[course_type] == 'toplawcourses')
            $key = 'LawTop Colleges';
        elseif($institutes[course_type] == 'topdesigncourses')
            $key = 'DesignTop Colleges';
        elseif($institutes[course_type] == 'tophospitalityandtravelcourses')
            $key = 'More CoursesHospitality & Travel';
        elseif($institutes[course_type] == 'topanimationcourses')
            $key = 'More CoursesAnimation';
        elseif($institutes[course_type] == 'topmasscommcourses')
            $key = 'More CoursesMass Communication & Media';
        elseif($institutes[course_type] == 'topbusinessmanagementcourses')
            $key = 'More CoursesBusiness & Management Studies';
        elseif($institutes[course_type] == 'topitandsoftwarecourses')
            $key = 'More CoursesIT & Software';
        elseif($institutes[course_type] == 'tophumanitiescourses')
            $key = 'More CoursesHumanities & Social Sciences';
        elseif($institutes[course_type] == 'topartscourses')
            $key = 'More CoursesArts (Fine/Visual/Performing)';
        elseif($institutes[course_type] == 'topsciencecourses')
            $key = 'More CoursesScience';
        elseif($institutes[course_type] == 'toparchitecturecourses')
            $key = 'More CoursesArchitecture & Planning';
        elseif($institutes[course_type] == 'topaccountingcourses')
            $key = 'More CoursesAccounting & Commerce';
        elseif($institutes[course_type] == 'topbankingcourses')
            $key = 'More CoursesBanking, Finance & Insurance';
        elseif($institutes[course_type] == 'topaviationcourses')
            $key = 'More CoursesAviation';
        elseif($institutes[course_type] == 'topteachingcourses')
            $key = 'More CoursesTeaching & Education';
        elseif($institutes[course_type] == 'topnursingcourses')
            $key = 'More CoursesNursing';
        elseif($institutes[course_type] == 'topmedicinecourses')
            $key = 'More CoursesMedicine & Health Sciences';
        elseif($institutes[course_type] == 'topbeautycourses')
            $key = 'More CoursesBeauty & Fitness';
        else
            $key = 'empty';

    $featuredCollegesGNB[$key][$institutes[position]] = $institutes;
    }
}

$currentPageURL = "http://".trim($_SERVER[HTTP_HOST],'/').'/'.trim($_SERVER[REQUEST_URI],'/');
global $newGNBconfigData;
?>

<div class="global-wrapper" id="_innerNav">
    <ul class="nav">
        <?php foreach($newGNBconfigData as $levelZeroKey=>$levelZeroData){ 
            $liLevelZeroClass ='';
            if($levelZeroKey == "More Courses")
                        $liLevelZeroClass = " nav-othercourses";
        ?>
        <li class="g_lev1<?=$liLevelZeroClass;?>"><a lang="<?=$levelZeroKey;?>"><?=$levelZeroKey;?><div class="spaceWrpr"></div><em class="g_pointer"></em></a>
            <div class="submenu">
                <ul class="g_lev2">
                <?php foreach ($levelZeroData as $levelFirstKey => $levelFirstData) { 
                    ?>
                    <li><a <?php if($levelFirstData['url'] != ''){ ?> href="<?php echo $levelFirstData['url']; ?>" class = "pntr <?php if($levelZeroKey == 'MBA' || $levelZeroKey == 'Counseling'){ echo "dontShowArrow"; } ?>" <?php } ?>><?=$levelFirstKey;?></a>
                        <div class="submenu2 <?php if($levelZeroKey=='Counseling'){ echo ' counsellingTab';}?>">
                            <table border="0">
                            <?php foreach ($levelFirstData as $levelSecondKey => $levelSecondData) {
                            ?>          
                                <tr>
                                <?php
                                $extraTR = '';
                                ?>
                                <?php foreach ($levelSecondData as $levelThirdKey => $levelThirdData) {
                                    $scrollClass = '';
                                    $extraTR .= '<td></td>';
                                    if($levelZeroKey == "More Courses" && (is_array($levelThirdData["Popular Courses"]) || is_array($levelThirdData["Popular Specializations"]))) {
                                        $scrollClass = " otherSclr";
                                    }
                                ?>
                                <?php
                                        if($levelFirstKey != 'College Predictors' && $levelFirstKey != 'Colleges by Location')
                                        {
                                                if(($levelZeroKey == "MBA" || $levelZeroKey == "Engineering" || $levelZeroKey == "Design" || $levelZeroKey == "Law") && $levelFirstKey != "Popular Specializations" && $levelThirdKey == "secondColumn" && !isset($featuredCollegesGNB[$levelZeroKey.$levelFirstKey]) && !isset($featuredExams[$levelZeroKey])) continue;
                                        } ?>
                                    <td>
                                    <?php $index = 0;
                                    $levelThirdDataCount = count($levelThirdData);
                                    foreach ($levelThirdData as $levelForthKey => $levelForthData) {
                                            if($levelForthKey == 'Popular Courses' || $levelForthKey == 'Popular Specializations'){?>
                                                    <div class="head_cours"><?=$levelForthKey?></div>
                                                    <?php 
                                                    continue;
                                            }  
                                            if($index == 0){?>
                                                <ul class="g_lev3<?=$scrollClass?>">

                                               <?php }   ?>

                                            <?php 
                                            $anchorClass='';$greaterThanTag = '';$onClick="";
                                            $gaTrackingPath = $levelZeroKey.':'.$levelFirstKey;
                                            $gaTrackingTrailPath1 = '';
                                            $gaTrackingTrailPath2 = '';
                                                if($levelForthData['type']=='heading'){
                                                    $class= "class='head_cours'";
                                                    $href= "";
                                                    $gaTrackingTrailPath1 = ':'.$levelForthKey;
                                                }elseif($levelForthData['type']=='sub-heading'){
                                                    $class= "class='head_cours-sub'";
                                                    $href= "";
                                                    $gaTrackingTrailPath1 .= ':'.$levelForthKey;
                                                }elseif($levelForthData['type']=='url'){
                                                    $class= "";
                                                    $href= "href='".$levelForthData['url']."'";
                                                    $gaTrackingTrailPath2 = ':'.$levelForthKey;
                                                }elseif($levelForthData['type']=='click'){
                                                    $class= "";
                                                    $href= "href='".$levelForthData['url']."'";
                                                    $onClick = $levelForthData['click'];
                                                    $gaTrackingTrailPath2 = ':'.$levelForthKey;
                                                }elseif($levelForthData['type']=='all'){
                                                    $class= "";
                                                    $anchorClass= "class='linkk'";
                                                    $href= "href='".$levelForthData['url']."'";
                                                    $onClick = $levelForthData['click'];
                                                    $greaterThanTag = '> ';
                                                    $gaTrackingTrailPath2 = ':'.$levelForthKey;
                                                }else{
                                                    $class= "";
                                                    $anchorClass= "class='linkk'";
                                                    $href= "href='".$levelForthData['url']."'";
                                                    $greaterThanTag = '> ';
                                                    $gaTrackingTrailPath2 = ':'.$levelForthKey;
                                                }
                                            ?>
                                            <?php if($levelForthKey == "Featured Colleges"){
                                                $count = 1;
                                                if(!isset($featuredCollegesGNB[$levelZeroKey.$levelFirstKey]))
                                                    continue;
                                                
                                                $trackAttr = (HOMEPAGE_GA_TRACKING) ? 'track="'.$gaTrackingPath.$gaTrackingTrailPath1.$gaTrackingTrailPath2.'"' : '';
                                                ?>
                                        <li class="head_cours">Featured Colleges</li>
                                        <?php foreach($featuredCollegesGNB[$levelZeroKey.$levelFirstKey] as $featuredCollegesKey => $featuredCollegesData){ ?>
                                            <li>
                                            <a  class="linkk" href="<?=$featuredCollegesData['detailurl'];?>" <?=$trackAttr;?>><?=$featuredCollegesData['instituteName'];?></a>
                                            </li>
                                        <?php $count++; if($count > 5) break;}} elseif($levelForthKey == "Featured Exams"){
                                            $count = 1;
                                            if(!isset($featuredExams[$levelZeroKey]))
                                                    continue;?>
                                    <li class="head_cours">Featured Exams</li>
                                    <?php foreach($featuredExams[$levelZeroKey] as $featuredExamsKey => $featuredExamsData){ ?>
                                            <li>
                                            <a  class="linkk" href="<?=$featuredExamsData['url'];?>" <?=$trackAttr?>><?=$featuredExamsData['exam_name'];?></a>
                                            </li>
                                        <?php $count++; if($count > 5) break; }} else{ ?>


                                            <li <?=$class;?>>
                                                <?php if($href!='' && $onClick=='' && !isset($levelForthData['hide'])){ ?>
                                                    <a <?=$href;?> <?=$trackAttr?> <?=$anchorClass;?>><?=$greaterThanTag.$levelForthKey;?></a>
                                                <?php }elseif($href!='' && $onClick!='' && !isset($levelForthData['hide'])){
                                                if($levelZeroKey == 'MBA' || $levelZeroKey == 'Engineering'){
                                                ?>
                                                <a <?=$anchorClass;?> <?=$href;?> <?=$trackAttr?> action="<?=$onClick;?>"><?=$greaterThanTag.$levelForthKey;?></a>
                                                    <?php } else{ ?>                                                   
                                                <a href="javascript:void(0);" <?=$trackAttr?> action="<?=$onClick;?>"><?=$levelForthKey;?></a>
                                                <?php }}elseif($href=='' && !isset($levelForthData['hide'])){ ?>
                                                    <?=$levelForthKey;?>
                                                <?php } ?>
                                            </li>
                                        <?php }$index++;
                                        if($index == $levelThirdDataCount){?>
                                            </ul>
                                       <?php }} ?>

                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                                <tr class="lastTrTd"><?=$extraTR?></tr>
                            </table>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
                <div class="gnb-nav-indctr">
                    <span class="disable"><i class="go-up"></i></span>
                    <span><i class="go-dwn"></i></span>
                </div>
            </div>
        </li>
        <?php } ?>
    </ul>
