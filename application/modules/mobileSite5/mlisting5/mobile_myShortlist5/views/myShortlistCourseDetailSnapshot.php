 <div  data-role="content">
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
        <div data-enhance="false">
<?php 

$examArray     = $courseDetails->getEligibilityExams();
$courseId      = $courseDetails->getId();
$instituteId   = $courseDetails->getInstId();
$courseName    = $courseDetails->getName();
$courseFeeObj  = $courseDetails->getFees($courseDetails->getMainLocation());
$courseFee     = moneyFormatIndia($courseFeeObj->getValue());       
$instituteName = $courseDetails->getInstituteName();


    $examNameArray = array();
        foreach($examArray as $res){
            $marksType  = $res->getMarksType();
            $marks  = $res->getMarks();
            if($marksType == 'percentile' && $marks)
            $examNameArray[] = $res->getAcronym().":".$marks."%tile";
            else
            $examNameArray[] = $res->getAcronym();
            
        }
        $examList = implode(", ", $examNameArray);
        $examList = $examList ? $examList : 'N/A';

        $rank     = $courseRanks[$courseId] ? $courseRanks[$courseId] : "Not in top 100";


        $isNaukriSalaryDataAvailable = false;
        $instituteNaukriSalaryData   = $naukriData[$instituteId];
        if($instituteNaukriSalaryData)
        {
            $isNaukriSalaryDataAvailable = true;       
            $salaryValue                 = intVal($instituteNaukriSalaryData['ctc50']*100000);
            $salary                      = $listingCommonLibObj->formatMoneyAmount($salaryValue, 1, 0);
        }else{
            $salary                      = 'N/A';
        }

           
if($instituteNaukriData)
{
 $salary                      = number_format($instituteNaukriData['ctc50'], 1, '.', '')." L";
 $isNaukriSalaryDataAvailable = true;
}

        $param['userId']         = ($userStatus != 'false') ? $userStatus[0]['userid'] : 0;
        $param['courseId']       = $courseId;
        $param['scope']          = 'national';

        $courseShortlistedStatus = $shortlistListingLib->checkIfCourseAlreadyShortlisted($param);

        $courseShortlistedStatus = ($courseShortlistedStatus)?'true':'false'; 
        if($courseShortlistedStatus == 'true'){
            $shortlistedClass           = 'shortlisted-star';
            $shortlistedText            = 'Shortlisted';
            $shortlistedBtnText         = 'Shortlisted';
            $shortlistedStatus          = true;
            $shortlistedInlineCSS       = "style='left:28px;'";

        }else{
            $shortlistedClass           = 'shortlist-star';
            $shortlistedText            = 'Shortlist';
            $shortlistedBtnText         = 'Shortlist this college';
            $shortlistedStatus          = false;
            $shortlistedInlineCSS       = "";

        }


?>
     
            <section class="content-section">

    <div id="courseSnapshotMain">  
<section class="content-section">
            
            <section class="listing-tupple">
                <a class="mys-shortlst-btn"><i id="shortlistedStar<?=$courseId?>" class="sprite <?=$shortlistedClass?>" <?=$shortlistedInlineCSS?>  ></i><b><?=$shortlistedText?></b></a>
                 <div class="courseInfoToPass" style="display:none;">
                    <courseId><?=$courseId;?></courseId>
                </div>
                <aside class="listing-cont mrgnrght55" style="padding-bottom: 0px;">
                    <strong><?=html_escape($instituteName)?> 
                <span><?=$courseDetails->getMainLocation()->getCity()->getName()?></span></strong>
                <p class="course-name"><?=html_escape($courseName)?></p>
                    <ul class="shortlist-details">
                        <li>
                              <label>Exam</label><b class="dot">:</b>
                              <p><?=html_escape($examList)?></p>
                        </li>
                        <li>
                            <label>Rank</label><b class="dot">:</b>
                            <p><?=$rank?></p>
                        </li>
                        <li>
                            <label>Total Fees</label><b class="dot">:</b>
                            <?php if(!empty($courseFee)) {?>
                            <p>Rs <?=$courseFee?></p>
                            <?php } else { ?>
                            <p>N/A</p>
                            <?php } ?>
                        </li>
                        <li>
                        <label>Alumni Salary</label><b class="dot">:</b>
                        <?php 
                        if($isNaukriSalaryDataAvailable == false){?>
                        <p>N/A</p>
                        <?php }else{ ?>
                        <p>Rs <?=$salary?></p>
                         <?php } ?>

                    </li>
                        
                    </ul>
                    
                </aside>
                <ul class="shortlist-details" style="border-top: 1px solid #cccccc; padding-top: 10px; margin: 0px 10px;">
                        
                        <li>
                            <label>Affiliation</label><b class="dot">:</b>
                            <?php if(!empty($affiliationData)){ ?>
                            <p><?=$affiliationData?></p>
                            <?php }else{?>
                            <p>N/A</p>
                            <?php }?>

                        </li>
                        
                    </ul>
                <div class="mys-placemnt">
                    <label style="display:block;width:100px;float:left;">Average Course Rating</label>
                    <b class="dot" style="font-weight:normal;position:relative; top:2px;float:left;margin-right: 10px;">:</b>
                    <?php if(!empty($courseReviewsOverallRating)){ ?>
                    <div class="ranking-bg"><?=$courseReviewsOverallRating?><sub>/5</sub></div>
                    <?php }else{?>
                    <div class="ranking-withoutbg">N/A</div>
                    <?php }?>
                    <p class="clr"></p>
                    
                    <div class="plcmnt-sec-head">
                    <?php if($isNaukriSalaryDataAvailable == true){ ?>
                        <div class="clearfix"></div>
                        <strong style="text-align:center">Alumni Employment Stats</strong>
                        <p>Data based on resumes from <i class="msprite naukri-sml-logo"></i>, India's No 1 job site</p>
                        <div class="accordian-sub-title" style="padding-top:10px">Average annual salary (INR) of alumni of this course is  <b>Rs <?=$salary?> for 2-5 years work</b> experience</div>
                        <?php } ?>
                    </div>
                </div>
                <article class="lising-nav-details mys-lstngNav">
                <?php if(isset($questionData['data']) && !empty($questionData['data'])){ ?>

                    <p class="title">Question and answers     :</p>
                    <div class="prev-q"><a href="#" class="txtColorBlck">Previously asked questions (<?=$questionData['total'];?>)</a></div>
                    
                    <ol class="ques-display">
                        <?php foreach ($questionData['data'] as $key => $value) { ?>
                            <li><?=$value['msgTxt'];?> 
                            <?php if(!empty($value['msgCount'])){ 
                                if($value['msgCount'] > 1){
                                    echo "(".$value['msgCount']." answers)";
                                }else{
                                    echo "(".$value['msgCount']." answer)";
                                }
                            }
                                ?>
                            </li>
                        <?php }?>
                    </ol>
                      <?php }?>
                    <a class="btn btn-default btn-med" courseidonsnapshot ="<?=$courseId?>" shortlisted="<?=$shortlistedStatus;?>" <?php if($shortlistedStatus == true){?> style='background:#8c8c8c;' <?php } ?> id="courseSnapshotShortlistBtn">
                        <i class="msprite blue-shortlist-star"></i><?=$shortlistedBtnText?>
                    </a>
                </article>
            </section>
        </section>
        </div>
 </section>
  <?php $this->load->view('mcommon5/footerLinks'); ?>
        </div>
    </div>
    <a href="javascript:void(0)"  courseidonsnapshot ="<?=$courseId?>" shortlisted="<?=$shortlistedStatus;?>" <?php if($shortlistedStatus == true){?> style='background:#8c8c8c;' <?php } ?> class="btn btn-default btn-med sticky" id="courseSnapshotShortlistStickyBtn" ><i class="msprite blue-shortlist-star"></i><?=$shortlistedBtnText?></a>
