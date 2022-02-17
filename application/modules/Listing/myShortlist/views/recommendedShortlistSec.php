<?php

$totalCourses   = count($courseObject);
$tracking_keyid = empty($tracking_keyid) ? 0 : $tracking_keyid;
$numberOfSlides =  ceil($totalCourses/4);
$counter        = 1;
$slideCounter   = 1;
$nextArrowClass = ($totalCourses < 5)?'next-arrw':'next-arrw-a';
$sliderFlag = '';
                            ?>
               
               <?php if(isset($isRecommendationsFlag) && $isRecommendationsFlag)
               {
                $sliderFlag = 'Recommendation';
                ?>
                <h3 class="recommended-title">Shortlist from recommended colleges</h3>
                <?php
               }
               else
               {
                echo "<br/>";
               }
               if(empty($courseObject)){ ?>
                <div class="caraousal-section"  >
                    <div class="" style="overflow: hidden;text-align:center;font-size: 12px;">
                        <div>No similar colleges found!</div>
                    </div>
                    <div class="clearFix"></div>
                </div>
               <?php }else{ ?>
                <div class="caraousal-section"  >
                    <div class="prev-arrow-box flLt"><a href="javascript:void(0)" title="Previous" onclick="enterpriseSlideLeftC<?php echo $sliderFlag?>();" id="prevButtonC<?php echo $sliderFlag?>" class="shortlist-sprite prev-arrw"></a></div>
                    <div class="caraousal-content" style="overflow: hidden;">
                        <ul id="slideContainerC<?php echo $sliderFlag?>" style="width: <?php echo ($numberOfSlides*900)?>px; position: relative; left: 0px;">
                            <li id="slideC<?php echo $slideCounter?>" style="float: left; width:839px;">
                            <?php 
                            
                            foreach($courseObject as $course){
                                
                                if(($counter%4) == 0 || $counter == $totalCourses){
                                    $lastClass ="last";
                                }else{
                                    $lastClass ="";
                                }
                                $this->load->view('myShortlist/courseInfoBox',array('courseDetails'=>$course,'lastClass'=>$lastClass,'naukriData'=>$naukriData,'tracking_keyid'=>$tracking_keyid)); 
                                if(($counter%4) == 0){
                                    $slideCounter++;
                                ?>  
                                </li>
                                <li id="slideC<?php echo $slideCounter?>" style="float: left; width:839px;">
                                <?php 
                                }
                                $counter++;
                                 }
                          ?>
                          </li>
                        </ul>
                    </div>
                    <div class="next-arrow-box flRt"><a href="javascript:void(0)" class="shortlist-sprite <?php echo $nextArrowClass?>" title="Next" onclick="enterpriseSlideRightC<?php echo $sliderFlag?>();" id="nextButtonC<?php echo $sliderFlag?>"></a></div>
                    <div class="clearFix"></div>
                </div>
              <?php }
               ?>
                 <script>
var slideWidthC<?php echo $sliderFlag?> = 839;
var numOfSlides<?php echo $sliderFlag?> = <?php echo ($numberOfSlides-1)?>;
var currentSlideC<?php echo $sliderFlag?> = 0;
var intervalPeriodC<?php echo $sliderFlag?> = 5000;

var intervalC<?php echo $sliderFlag?>;
//interval = setInterval(function(){changeSlider();},intervalPeriod);

</script>