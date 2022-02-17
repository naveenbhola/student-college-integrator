<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 15/3/18
 * Time: 12:43 PM
 */
$gaParams = '';
$gaParamsFourthCard = '';
if($listingType == 'course')
{
    $gaParams = 'ABROAD_COURSE_PAGE,userProfileWidget,viewProfile';
    $gaParamsFourthCard = 'ABROAD_COURSE_PAGE,userProfileWidget,applyHome';
}
elseif($listingType == 'university')
{
    $gaParams = 'ABROAD_UNIVERSITY_PAGE,userProfileWidget,viewProfile';
    $gaParamsFourthCard = 'ABROAD_UNIVERSITY_PAGE,userProfileWidget,applyHome';
}
?>
<div class="clearfix"></div>
<div class="slider_section">
    <h2 class="n-title">Students who got admitted through Shiksha Counseling Service</h2>
    <div id="slider_card" class="slider_wrap slider2">
        <a href="javascript:void(0);" class="css_right nextArrow next"><span class="css_arrow"></span></a>
        <div class="viewport" >
            <ul class="clear_max overview">
                <?php
                $count=1;
                foreach ($usersData as $userID =>$userData)
                {
                    if(empty($userData['education']['educationLevel'])||($userData['education']['educationLevel']==10))
                    {
                        $graduationOrXthHeading ='Class X<br>Board';
                        $graduationOrXthContent =empty($userData['education']['board'])?'None':$userData['education']['board'];
                        $workOrXthScoreHeading = empty($userData['education']['educationMarksType'])?'CGPA':$userData['education']['educationMarksType'];
                        $workOrXthScoreContent = empty($userData['education']['educationMarks'])?'None':$userData['education']['educationMarks'];

                    }
                    else
                    {
                        $graduationOrXthHeading = 'Graduation<br>Percentage';
                        $graduationOrXthContent = empty($userData['education']['educationMarks'])?'None':$userData['education']['educationMarks'];
                        $workOrXthScoreHeading = 'Work<br>Experience';
                        $workOrXthScoreContent = empty($userData['education']['workex'])?'None':$userData['education']['workex'];
                    }
                    $examScore = (empty($userData['exam']) ||empty($userData['exam']['examName']))?'None': (empty($userData['exam']['educationMarks'])
                        ?$userData['exam']['examName'].': None':$userData['exam']['examName'].': '.$userData['exam']['educationMarks']);

                    ?>
                    <li style="width: 300px;">
                        <div class="card_profile">
                            <div class="align-cntr fix_h p-top p-btm">
                                <a class="inner-img" style="background-image: url('<?php echo $userData['image'];?>')"></a>
                                <div class="dls_block">
                                    <h3 class="fontw-6 fn-14 wrap_margin"><?php echo empty($userData['name'])?'-':$userData['name'];?></h3>
                                    <p class="fontw-4 fn-12 m1-b">Admitted to <?php echo empty($userData['admissionData']['courseName'])?'-':
                                            ((strlen($userData['admissionData']['courseName'])>52)?substr($userData['admissionData']['courseName'],0,52).'...':$userData['admissionData']['courseName']);?></p>
                                    <p class="fn-14"><?php echo empty($userData['admissionData']['univName'])?'-':
                                            ((strlen($userData['admissionData']['univName'])>52)?substr($userData['admissionData']['univName'],0,52).'...':$userData['admissionData']['univName']);?></p>
                                </div>
                            </div>
                            <div class="score_block">
                                <div>
                                    <p class="name_lable">Exam <br> Score</p>
                                    <strong class="score_label fontw-6"><?php echo $examScore;?></strong>
                                </div>
                                <div>
                                    <p class="name_lable"><?php echo $graduationOrXthHeading;?></p>
                                    <strong class="score_label fontw-6"><?php echo $graduationOrXthContent;?></strong>
                                </div>
                                <div>
                                    <p class="name_lable"><?php echo $workOrXthScoreHeading;?></p>
                                    <strong class="score_label fontw-6"><?php echo $workOrXthScoreContent;?></strong>
                                </div>
                            </div>
                            <div class="fix_at_btm">
                                <a class="gaTrack" gaparams="<?php echo $gaParams; ?>" href="<?php echo empty($userData['profileLink'])?'#':$userData['profileLink']?>">View Profile <span class="css_arrow"></span></a>
                            </div>
                        </div>
                    </li>
                    <?php
                    if($count == 1 && !empty($ratingData))
                    {
                        ?>
                        <li style="width: 300px;">
                            <div class="card_profile top_bar">
                                <div class="around_space">
                                    <h3 class="info-title">Overseas Admission Counselling <span>by Shiksha.com</span></h3>
                                    <div>
                                      <div class="review_rate_tab">
                                        <strong><?php echo $ratingData['overallRating'];?></strong>
                                        <div class="starBlock">
                                            <div class="starFullBlock" style="width: <?php echo $starRatingWidth;?>;">
                                            </div>
                                        </div>
                                        <a href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/apply/reviews" class="reviewCount">
                                            <?php echo $ratingData['ratingCount']." review".($ratingData['ratingCount']>1?'s':'');?>
                                        </a>
                                      </div>
                                      <div class="CounselingBox">
                                          <div class="CounselingInnerBox">
                                              <i class="counslngIcon"></i>
                                              <span>Student Centric Process</span>
                                          </div>
                                          <div class="CounselingInnerBox">
                                              <i class="chatIcon"></i>
                                              <span>Instant chat Availability</span>
                                          </div>
                                          <div class="CounselingInnerBox">
                                              <i class="univIcon"></i>
                                              <span>Wide Choice of Universities</span>
                                          </div>
                                          <div class="CounselingInnerBox">
                                              <i class="personalisedIcon"></i>
                                              <span>100% Free & Personalised</span>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="cntr_txt">
                                        <a class="gaTrack inline-btn" gaparams="<?php echo $gaParamsFourthCards; ?>" href="<?php echo SHIKSHA_STUDYABROAD_HOME.'/apply'?>">Know More</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                    $count++;
                }
                ?>
            </ul>
        </div>
        <a href="javascript:void(0);" class="css_left prevArrow disable prev"><span class="css_arrow"></span></a>
    </div>
</div>
<script>
    $j('#slider_card').tinycarousel({ infinite: false,animationTime : 350 });
</script>
