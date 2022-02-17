<script type="text/javascript">
$j("#showCollegeRanks").mouseenter(function() {
      $j("#college-rank-layer").show();
      $j("#college-rank-layer").css("top", $j(this).offset().top+20);
      $j("#college-rank-layer").css("left", $j(this).offset().left);
}).mouseleave(function() {
      $j("#college-rank-layer").hide();
});
</script>
<?php
$exam = $courseObj->getEligibility(array('general'));
foreach ($exam['general'] as $result){
	
$name = $result->getExamName();
$marks = $result->getValue();
if(empty($marks))
   $examAndcutoff[] = $name;
else
   $examAndcutoff[] = $name."(".$marks.")"; 
}
$examAndcutoff = implode(", ",$examAndcutoff);
$courseFees 	 = $courseObj->getFees();
$courseFeesValue = is_object($courseFees) ? $courseFees->getFeesValue() :'';
$courseId = $courseObj->getId();  // used in myshortlist view

$layerHeaderText = "Download E-Brochure";
if($userInfo != "false")
{
      $layerHeaderText = "Please select the course to download the brochure";
}
?>

                            <div class="clear-width" style="padding-bottom:20px;" name="up">
							
                                <div class="institute-heading"><a href="<?php echo $courseObj->getURL();?>"><?php echo $courseObj->getInstituteName(); ?></a>&nbsp;&nbsp;<span class="alumini-count-color"><?php echo $total_alumni;?> Alumni</span>
				<p class="font-12">
				<?php
				if(!empty($courseFeesValue))
				{
			        ?>
                                <span>Total Fees:  <span style="color:#333"><?php echo getIndianDisplableAmt($courseFeesValue);//echo $courseFees; ?></span></span>
			        <?php
				}
				?>
				<?php if(!empty($examAndcutoff)){
			              if(!empty($courseFeesValue))
				      {
			        ?>
							<span style="margin:0 5px;">|</span>
			        <?php
				      }
			        ?>
				
				<span>Exams & Cut-Off: <span style="color:#333"><?php echo $examAndcutoff; ?></span></span> 
				<?php } ?>
				<?php if(!empty($courseObj->course_ranking)) { ?>
					<span style="margin:0 5px; ">|</span>
					<a href="javascript:void(0);" id="showCollegeRanks" style = "display: inline-block">View College Rank</a></p>
				<?php } ?>
				<div class="college-rank-layer" id="college-rank-layer" style="display:none;">
                                	<?php //} ?><i class="common-sprite rank-layer-pointer"></i>
                                    <ul>
			                <?php foreach($courseObj->course_ranking as $source => $rank) { ?>
			                <li>							
                                       		<label><?php echo $source; ?> :</label> 
                                            <span class="rank-tag"><?php echo $rank; ?></span>
                                        </li>
					<?php } ?>
                                    </ul>
                                </div>

                            </div>
			      <!--myshortlist view	-->
			      <?php
				if($categoryPage_SubCat == 23){
					if(in_array($courseId, $shortlistedCoursesOfUser)) {
						$courseShortlistedStatus = 1;
					}
				$onclick = "var customParam = {'shortlistCallback':'shortlistCallbackForCtpgTuple', 'shortlistCallbackParam':{}, 'trackingKeyId':'".DESKTOP_NL_NAUKRITOOL_SHORTLIST."', 'pageType':'ND_NaukriTool'}; myShortlistObj.checkCourseForShortlist('".$courseId."', customParam);";
				?>
				<a style="padding:4px 12px 6px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;" isLastViewed = "<?=$lastViewedCourse == $courseId ? 'true' : 'false' ?>" onmouseover="showShortlistInfoBox(this);" onmouseout="hideShortlistInfoBox();"  href="javascript:void(0);" class="shrtlist-btn <?="shrt".$courseId?>" cta-type="shortlist" onclick="ajaxDownloadEBrochure(this,<?php echo $courseObj->getInstituteId();?>,'<?php echo $courseObj->getInstituteType();;?>','<?php echo addslashes(htmlentities($courseObj->getInstituteName()));?>','ND_NaukriTool','<?php echo DESKTOP_NL_NAUKRITOOL_SHORTLIST;?>','','','','')" customCallBack="listingShortlistCallback" customActionType="ND_NaukriTool"><i class="common-sprite shrtlist-star-icon"></i><span class="btn-label">Shortlist</span></a>    
				<br/> 
				<a target="_blank" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="<?="shrt-view-link".$courseId?>" style="font-size:12px; margin:8px 0 0 0; display:<?=$courseShortlistedStatus == 1 ? 'block' : 'none' ?>; float:right">View my shortlist</a>
			<?php
			$courseShortlistedStatus = 0;
				}
			?>
				
				
                            </div>
                           <?php if(!empty($chart) && $chart != 'null') { ?>
			    <div class="annual-job-functions" style="padding-left:0;">
			         <div style="text-align:center;"><h3 class="tac mt8">Annual salary (INR) of alumni in different job functions</h3></div>
                            <div class="graph-box" style="margin-left:0px; float:left; background:#FAFAFA; width:657px;">
				<div id="salary-data-chart" class="alumni-graph" style="width:520px;float:left;background:rgb(247, 245, 250))" ></div>
			    <div class="alumini-salary">
							<p>Average Salary of all Alumni :</p>
							<p style="font-size:16px;"><?=$salary = number_format($instituteNaukriSalaryData['ctc50'], 1, '.', '')." L";?></p>
			    </div>
			    </div>
			    
                           </div>
			    <?php } ?>
                           
                           <div class="naukri-tool-widget clear-width ins-tool-widget" style="overflow:hidden;">
			       <h3 class="clear-width" style="text-align:center; font-size:13px; font-weight:normal; margin:10px 0;"> Alumni Employment Data of this college</h3>
                           		<ul>
                                	<li class="svg-width" style="position: relative;">
                                    	<h3 class="job-alumini-title">Job functions of alumni <span id="specializationChart4"></span></h3>
					<div id="piechart4" style="width:280px !important"></div>
					<p id="innerText4" style="position: absolute; color: rgb(102, 102, 102); text-align: center; top: 132px; font-size: 12px; left: 120px;">Select<br> Job Function</p>
                                    </li>
                                    <li class="naukri-lastItem" style="position: relative;">
                                    	<h3 class="job-alumini-title">Companies <span id="specializationChart5">where alumni are working </span></h3>
					  <div id="piechart5"></div>
					 <p id="innerText5" style="position: absolute; color: rgb(102, 102, 102); text-align: center; top: 133px; font-size: 12px; left: 128px;">Select<br> Company</p>
                                    </li>
                                </ul>
                           </div>
                          
                           <div class="clear-width">
                            <p class="flLt job-width">Interested in this college to get your <br/>dream job:</p>
                           	<div class="button-area flRt" name="down" style="width:363px; text-align: right;">
				    <!--<a href="#" class="apply-nw-button"><i class="common-sprite apply-nw-icon"></i>Apply Now</a>-->
		
				
					<a class="apply-nw-button course_layer<?=$courseObj->getId()?>" href="javascript:void(0);" onclick="<?=(!empty($extraClass)?'return false; ':'')?>responseForm.showResponseForm('<?php echo $courseObj->getInstituteId() ;?>','ND_CareerCompass_Ebrochure','institute',{'trackingKeyId': '202','callbackObj':'','callbackFunction': 'showEbdownloadMsg','callbackFunctionParams': {'courseList':'<?php echo $courseObj->getInstituteId();?>'}},{});"><i class="common-sprite bro-sml-icon"></i>Get Brochure</a>

				    
				    <!--myshortlist view-->
			      <?php
				if($categoryPage_SubCat == 23){
					if(in_array($courseId, $shortlistedCoursesOfUser)) {
						$courseShortlistedStatus = 1;
					}
				$onclick = "var customParam = {'shortlistCallback':'shortlistCallbackForCtpgTuple', 'shortlistCallbackParam':{}, 'trackingKeyId':'".DESKTOP_NL_NAUKRITOOL_BOTTOM_SHORTLIST."', 'pageType':'ND_NaukriTool'}; myShortlistObj.checkCourseForShortlist('".$courseId."', customParam);";
				?>
				<a id="shrtLst" style="padding:4px 12px 6px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;" isLastViewed = "<?=$lastViewedCourse == $courseId ? 'true' : 'false' ?>" onmouseover="showShortlistInfoBox(this);" onmouseout="hideShortlistInfoBox();"  href="javascript:void(0);" class="shrtlist-btn <?="shrt".$courseId?>" cta-type="shortlist" onclick="ajaxDownloadEBrochure(this,<?php echo $courseObj->getInstituteId();?>,'<?php echo $courseObj->getInstituteType();;?>','<?php echo addslashes(htmlentities($courseObj->getInstituteName()));?>','ND_NaukriTool','<?php echo DESKTOP_NL_NAUKRITOOL_BOTTOM_SHORTLIST;?>','','','','')" customCallBack="listingShortlistCallback" customActionType="ND_NaukriTool"><i class="common-sprite shrtlist-star-icon"></i><strong class="btn-label" style="font-weight:normal;">Shortlist</strong></a>    
				<br/>
				<span id="ebmsg" style="display: none; margin:8px 0 0;width:250px; color: #52935c; font-size:12px; position:relative; right:24px;"><i class="common-sprite dwnld-mark-icon-2"></i>E-Brochure successfully mailed.</span>
				<a target="_blank" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="<?="shrt-view-link".$courseId?>" style="font-size:12px; width:100px;margin:8px 0 0 0; display:<?=$courseShortlistedStatus == 1 ? 'block' : 'none' ?>; float:right">View my shortlist</a>
			<?php
			$courseShortlistedStatus = 0;
				}
			?> 
                            </div>
				
                            <div class="clearFix"></div>
                        </div>
