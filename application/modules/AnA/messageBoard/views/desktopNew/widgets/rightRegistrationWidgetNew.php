<?php
                        $cacheLib = $this->load->library('cacheLib');
                        $cntKey = md5('nationalHomepageCounters_json');
                        $hpCounterResult = $cacheLib->get($cntKey);
                        if($hpCounterResult != 'ERROR_READING_CACHE'){
                                $hpCounterResult = json_decode($hpCounterResult, true);
                                $collegeCount = formatNumber($hpCounterResult['national']['instCount']);
				$reviewCount = formatNumber($hpCounterResult['national']['reviewsCount']);
				$answerCount = formatNumber($hpCounterResult['national']['questionsAnsweredCount']);
				$careerCount = formatNumber($hpCounterResult['national']['careerCount']);
				$examCount = formatNumber($hpCounterResult['national']['examCount']);
                        }
?>
       <div class="search-block txt-cntr">
           <p class="signup-h3">Taking an Exam? Selecting a College?</p>
           <p class="inf-txts">Get authentic answers from experts, students and alumni that you <strong>won't</strong> find anywhere else</p>
           <a class="nw-btn" ga-attr="<?=$GA_Tap_On_Right_Reg_CTA?>" id="qnaRegstr" data-trackingKey="<?=$regRightTrackingPageKeyId?>">Sign Up on Shiksha </a>
	   <p class="background-brdr"><span>On Shiksha, get access to</span></p>
           <ul class="inf-li">
             <li><strong><?=$collegeCount?></strong> Colleges</li>
               <li><strong><?=$examCount?></strong> Exams</li>
               <li><strong><?=$reviewCount?></strong> Reviews</li>
               <li><strong><?=$answerCount?></strong> Answers</li>
            </ul>
       </div>
