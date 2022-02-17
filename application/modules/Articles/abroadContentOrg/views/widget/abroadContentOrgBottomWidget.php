<?php

$count=1;
foreach($widgetUrl as $key=>$value)
{
  if($key == $stageDetails['stageId'])
  {
     break; 			
  }
  $count++;
}
$presentReadingLeft = (($count -1)%4) * 191;
$contentOrgCurrentSlide = ($count >4)?1:0;
?>
<script>
var contentOrgCurrentSlide = <?= $contentOrgCurrentSlide?>;			 
</script>
<div class="study-plan-widget next-step-widget">
                    	<div class="article-widget-head clearwidth">
                    	<strong class="flLt font-14" style="margin-top:6px">Explore Your Next Step:</strong>
                        <div class="next-prev flRt" id="conOrgWidgetArrow">
            				<span class="flLt slider-caption">1 of 2</span>
           					<a class="prev-box" href="javaScript:void(0);" onclick="contentOrgBottomWidgetSlider(0)" style="outline: none !important;"><i class="common-sprite prev-icon"></i></a>
           					<a class="next-box" href="javaScript:void(0);" onclick="contentOrgBottomWidgetSlider(1)" style="outline: none !important;"><i class="common-sprite next-icon"></i></a>
        				</div>
                    </div>
	                
                    	<div class="planning-info next-step-info clearwidth" style="display:block; overflow: hidden; width: 751px;">
                            <ul id="conOrgWidgetContainer" style="display:block;width: 2000px; position: relative;">
                                <li style="width: 751px;">
			        <div class="reading-title clearwidth" style="<?= ($contentOrgCurrentSlide==1)?'display:none;':''?>left:<?= $presentReadingLeft;?>px">You are reading<i class="abroad-exam-sprite reading-arrow"></i></div>
                                <div class="flLt planning-col">
						<a <?= ($count==1)?'class="active"': 'href="'.$widgetUrl['COUNTRY'].'"'?>>	
						    <i class="abroad-exam-sprite tick-arrow"></i>
						    <i class="abroad-exam-sprite country-icon"></i>
						    <strong>Country</strong>
						    <p>Get help in deciding your study destination</p>
					       </a>
                                 </div>
				
				<div class="flLt planning-col">
						<a <?= ($count==2)?'class="active"':'href="'.$widgetUrl['COURSE'].'"'?>>
						    <i class="abroad-exam-sprite tick-arrow"></i>
						    <i class="abroad-exam-sprite course-icon"></i>
						    <strong>Course</strong>
						    <p>Select from suitable <br />courses on offer </p>
					       </a>
                                </div>
				
				<div class="flLt planning-col">
				<a <?= ($count==3)?'class="active"':'href="'.$widgetUrl['EXAM'].'"'?>>
				    <i class="abroad-exam-sprite tick-arrow"></i>
                                    <i class="abroad-exam-sprite exam-plan-icon"></i>
                                    <strong>Exam</strong>
                                    <p>Know which exam you should take</p>
			       </a>
                                </div>
				
				<div class="flLt last planning-col">
				<a <?= ($count==4)?'class="active"':'href="'.$widgetUrl['COLLEGE'].'"'?>>
			            <i class="abroad-exam-sprite tick-arrow"></i>
                                    <i class="abroad-exam-sprite college-plan-icon"></i>
                                    <strong>College</strong>
                                    <p>Shortlist colleges <br />based on your preference </p>
			        </a>
                                </div>
                                </li>
				<li style="width: 751px;">
				<div class="reading-title clearwidth" style="<?= ($contentOrgCurrentSlide==0)?'display:none;':''?>left:<?= $presentReadingLeft;?>px">You are reading<i class="abroad-exam-sprite reading-arrow"></i></div>		
                                <div class="flLt planning-col">
				<a <?= ($count==5)?'class="active"':'href="'.$widgetUrl['APPLICATION_PROCESS'].'"'?>>				
			            <i class="abroad-exam-sprite tick-arrow"></i>
                                    <i class="abroad-exam-sprite process-icon"></i>
                                    <strong>Application Process</strong>
                                    <p>Understand how to apply to a college</p>
				 </a> 
				</div>
				 <div class="flLt planning-col <?= ($count==6)?"active":''?>">
				 <a <?= ($count==6)?'class="active"':'href="'.$widgetUrl['SCHOLARSHIP_FUNDS'].'"'?>>
                                    <i class="abroad-exam-sprite tick-arrow"></i>
				    <i class="abroad-exam-sprite loan-icon"></i>
                                    <strong>Scholarship & Loan</strong>
                                    <p>Find out financing options available to you</p>
				 </a>   
                                </div>
				<div class="flLt planning-col">
				<a <?= ($count==7)?'class="active"':'href="'.$widgetUrl['VISA_DEPARTURE'].'"'?>>
                                    <i class="abroad-exam-sprite tick-arrow"></i>
				    <i class="abroad-exam-sprite visa-icon"></i>
                                    <strong>Visa & Departure</strong>
                                    <p>Know about Student Visa & pre-departure checklist</p>
                                </a>
				</div>
                                <div class="flLt last planning-col">
				<a <?= ($count==8)?'class="active"':'href="'.$widgetUrl['STUDENT_LIFE'].'"'?>>				
                                    <i class="abroad-exam-sprite tick-arrow"></i>
				    <i class="abroad-exam-sprite stu-life-icon"></i>
                                    <strong>Student Life</strong>
                                    <p>Get insights & tips to adapt and excel in a foreign land </p>
                                </a>
				</div>
                                </li>
                            </ul>
           				</div>
                        <div class="clearwidth">
                            <ol class="carausel-bullets" id="conOrgWidgetbullet">
                            <li style="cursor:pointer;" class="active" onclick="contentOrgBottomWidgetSlider(0)"></li>
                            <li style="cursor:pointer;" onclick="contentOrgBottomWidgetSlider(1)"></li>
                            </ol>
                        </div>
                    </div>