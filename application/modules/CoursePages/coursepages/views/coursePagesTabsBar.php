<?php
if($courseHomePageId == 1) {
	 $shortlistedCourseCount =  Modules::run('myShortlist/MyShortlist/getShortlistedCoursesCount');
	 $data =  Modules::run('myShortlist/MyShortlist/fetchUserNotifications');
	 $notificationData = $data['resultdata'];
	 $isAllNotificationSeen = $data['isAllNotificationSeen'];
	 
 }

if(!isset($selectedTab) || $selectedTab == "") {
	$selectedTab = "Home";
}
if(!(isset($isFloatingBar) && $isFloatingBar)) {
	$nav_main_class = "";
	if ($indentCPGSHeader===TRUE)
	{
		 $nav_main_class =  "class='nav-position'";
		 if($articleDetailPage===TRUE) {
			$nav_main_class .= " style='margin-top:-20px;'";
		 }
	}
?>
<!--Removing sub-navigation bar from all pages (LF-3300)-->
<div style="display:none">
	<div id="course-nav-main" <?php echo $nav_main_class; ?>>    
	<?php
		if($selectedTab == "Home") {
	?>
		<h1 class="course-pages-title"><span><?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?></span> in India</h1>
	<?php
		} else {
		  if($exampageExamNameLabel != '')
		  {
			   $exampageExamNameLabel = '&nbsp;';
		  ?>
			<!--h1 class="course-pages-title"><span><?=$exampageExamNameLabel?></span></h1-->
			<div class="course-pages-title"><span><?=$exampageExamNameLabel?></span></div>
		  <?php } else {
	?>
		<div class="course-pages-title"><span><?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?></span> in India</div>
	<?php
		  }
		}
	global $isNewExamPage;
	if(($selectedTab == "Exams" && isset($isNewExamPage) && $isNewExamPage) || $selectedTab == "MyShortlist" || $selectedTab == "CollegePredictor") {
	   $this->load->view ( 'common/shikshaHomeNavigation' );	
	}	
	 
	$ulId = "cpgs_tabs_bar_container";
	$helpTextDivId = 'id="cpgs_help_text_div"';	
} else {
	$ulId = "cpgs_floating_tabs_bar_container";
	$helpTextDivId = 'id="cpgs_floating_help_text_div"';
	$backLinkDivId = "floating_back_link_div";
	echo "<div><div class='course-pages-title'>".$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name']." in India</div> <!--a href='#' class='follow-update'>Follow updates on this page</a--></div>";
}

if(count($coursePagesSeoDetails) < 5) {
	$ulClass = 'class="course-nav course-nav2"';
} else {
	$ulClass = 'class="course-nav"';
}
$examTabExamPageLinks = array();


?>
		    <ul id="<?=$ulId?>" <?=$ulClass?>><?php
			foreach($coursePagesSeoDetails as $tab => $tabSeoDetails) {
				//echo $tab."<br/>";
				if($tab == $selectedTab) {
					$class = ' class="active"';
				} else {
					$class = ' ';
				}	
			?>
  			<?php if($coursePagesSeoDetails[$tab]['NAME'] == 'Tools'):?>
			
			   <?php if($courseHomePageId == 1) : ?>			   
						 			
						 <li 
							<?php if($isFloatingBar): ?>
								 onmouseenter="$('student-tools-sticky').style.display = 'block' ; $('student-tools').style.display = 'none';$j('#tools-border_div_sticky').show();" onmouseleave=" $('student-tools-sticky').style.display='none' ;$('student-tools').style.display = 'none'; $('tools-border_div_sticky').style.display='none'"
							<?php else:?>
								 onmouseenter=" $('student-tools').style.display = 'block';$('tools-border_div').style.display = 'block'" onmouseleave="$('student-tools').style.display = 'none';$('tools-border_div').style.display = 'none'"
						 <?php endif;?>
						 >
							 <a style="position:relative" uniqueattr="CPGS_SESSION_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME']?>" href="javascript:void(0);" onmouseover="$('student-tools').style.display = 'block'" <?=$class?>>
								   <i class="icon-CollegePredictor"></i><?=$coursePagesSeoDetails[$tab]['NAME']?><div class="help-tool-tip" style="display: none;" id="cpgs_<?php echo $tab.$prefix_to_append;?>"><div class="contents"><?php echo str_replace("<>", $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'], $coursePagesSeoDetails[$tab]['HELP_TXT']);?><i class="pointer"></i></div></div>								 
								   <i class="examRslt-icon"></i>
								   <?php if(!$isFloatingBar): ?>
									  <div  id="tools-border_div"  style="display:none; background: #FFFFFF;bottom: -6px;height: 1px;left: 0;position: absolute;width: 100%;z-index: 99; overflow:hidden"></div>
								   <?php else: ?>
									  <div  id="tools-border_div_sticky" style="display:none; background: #FFFFFF;bottom: -6px;height: 1px;left: 0;position: absolute;width: 100%;z-index: 99; overflow:hidden"></div>
								   <?php endif;?>
							 </a>
			 
							 <div id="<?php if($isFloatingBar) { echo 'student-tools-sticky'; } else { echo 'student-tools';} ?>" class="student-tools" style="display: none;">	
								 <p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', 'College Reviews');" href="<?php echo base_url()?><?= MBA_COLLEGE_REVIEW ?>" style="padding:0 !important <?php echo $featuredStyle;?>">College Reviews</a></p>
								 <p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', 'Campus Connect');" href="<?php echo base_url() ?>mba/resources/ask-current-mba-students" style="padding:0 !important <?php echo $featuredStyle;?>">Campus Connect</a></p>
								 <p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$examName?>');" href="<?=SHIKSHA_HOME.'/mba/resources/mba-alumni-data'?>" style="padding:0 !important <?php echo $featuredStyle;?>">Career Compass</a></p>
								 <p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', 'Exam Calendar');" href="<?php echo SHIKSHA_MBA_CALENDAR;?>" style="padding:0 !important <?php echo $featuredStyle;?>">Exam Calendar</a></p>
							 </div>				
			 
						 </li>			

			
			   <?php else: ?>
			   
			   <li id="cptab"
					<?php if($isFloatingBar): ?>
					onmouseenter="showCPLayer('FLOATING');" onmouseleave="hideCPLayer('FLOATING');"
					<?php else:?>
					onmouseenter=" showCPLayer('NOTFLOATING');" onmouseleave=" hideCPLayer('NOTFLOATING');"
					<?php endif;?>
					>
						<a style="position:relative" uniqueattr="CPGS_SESSION_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME']?>" href="javascript:void(0);" onmouseover="$j('#cp-result').show();" <?=$class?>><i class="icon-<?=$tab?>"></i><?=$coursePagesSeoDetails[$tab]['NAME']?>
							   <div class="help-tool-tip" style="display: none;" id="cpgs_<?php echo $tab.$prefix_to_append;?>">
											<div class="contents"><?php echo str_replace("<>", $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'], $coursePagesSeoDetails[$tab]['HELP_TXT']);?>
											<i class="pointer"></i>
											</div>
								</div>
								<i class="examRslt-icon"></i>
								<?php if(!$isFloatingBar): ?>
								<div  id="cp_border_div"  style="display:none; background: #FFFFFF;bottom: -6px;height: 1px;left: 0;position: absolute;width: 100%;z-index: 99; overflow:hidden"></div>
								<?php else: ?>
								<div  id="cp_border_div_sticky" style="display:none; background: #FFFFFF;bottom: -6px;height: 1px;left: 0;position: absolute;width: 100%;z-index: 99; overflow:hidden"></div>
								<?php endif;?>
						</a>
						<?php if($isFloatingBar):?>
							<div id="cp-result-sticky" class="exam-result" style="display: none;width:272px;">
								 <?php $this->load->view('coursepages/toolsTab'); ?>
							</div>					
						<?php else:?>
							<div id="cp-result" class="exam-result" style="display: none;width:271px;">
								 <?php $this->load->view('coursepages/toolsTab'); ?>
							</div>								
						<?php endif;?>
					</li>
			   
			   <?php endif;?>
			   
			<?php elseif($coursePagesSeoDetails[$tab]['NAME'] == 'Exams'):?>
			<li 
			<?php if($isFloatingBar): ?>
			onmouseenter="$('exam-result-sticky').style.display = 'block' ; $('exam-result').style.display = 'none';$j('#border_div_sticky').show();" onmouseleave=" $('exam-result-sticky').style.display='none' ;$('exam-result').style.display = 'none'; $('border_div_sticky').style.display='none'"
			<?php else:?>
			onmouseenter=" $('exam-result').style.display = 'block';$('border_div').style.display = 'block'" onmouseleave="$('exam-result').style.display = 'none';$('border_div').style.display = 'none'"
			<?php endif;?>
			>
				<a style="position:relative" uniqueattr="CPGS_SESSION_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME']?>" href="javascript:void(0);" onmouseover="$('exam-result').style.display = 'block'" <?=$class?>><i class="icon-<?=$tab?>"></i><?=$coursePagesSeoDetails[$tab]['NAME']?>
			           <div class="help-tool-tip" style="display: none;" id="cpgs_<?php echo $tab.$prefix_to_append;?>">
                                    <div class="contents"><?php echo str_replace("<>", $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'], $coursePagesSeoDetails[$tab]['HELP_TXT']);?>
                                    <i class="pointer"></i>
                                    </div>
                        </div>
                        <i class="examRslt-icon"></i>
                        <?php if(!$isFloatingBar): ?>
                        <div  id="border_div"  style="display:none; background: #FFFFFF;bottom: -6px;height: 1px;left: 0;position: absolute;width: 100%;z-index: 99; overflow:hidden"></div>
                        <?php else: ?>
                        <div  id="border_div_sticky" style="display:none; background: #FFFFFF;bottom: -6px;height: 1px;left: 0;position: absolute;width: 100%;z-index: 99; overflow:hidden"></div>
                        <?php endif;?>
				</a>
				<?php /* if($courseHomePageId != 23) { if($isFloatingBar):?>
				<div id="exam-result-sticky" class="exam-result" style="right: 0;display: none;">
					<?php $count = count($examTabExamPageLinks);?>
					<?php foreach ($examTabExamPageLinks as $grade => $data):?>
								<?php if($k%2==0):?>
								<p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$data['name']?>');" href="<?php echo $data['url'];?>" style="padding:0 !important"><?php echo $data['name'];?></a></p>
								<?php else:?>
								<p class="flRt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$data['name']?>');" href="<?php echo $data['url'];?>" style="padding:0 !important"><?php echo $data['name'];?> </a></p>							
								<?php endif; $k++; ?>
					<?php endforeach;?>
				</div>					
				<?php else:?>
				<div id="exam-result" class="exam-result" style="right: 0;display: none;">
					<?php $count = count($examTabExamPageLinks);?>
					<?php foreach ($examTabExamPageLinks as $grade => $data):?>
							<?php if($j%2==0):?>
								<p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$data['name']?>');" href="<?php echo $data['url'];?>" style="padding:0 !important"><?php echo $data['name'];?></a></p>
								<?php else:?>
								<p class="flRt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$data['name']?>');" href="<?php echo $data['url'];?>" style="padding:0 !important"><?php echo $data['name'];?> </a></p>							
								<?php endif; $j++; ?>
					
					<?php endforeach;?>
				</div>					
				
				<?php endif; } */?>
				
				<?php if($courseHomePageId == 1 || $courseHomePageId == 6) { if($isFloatingBar):?> 
                               
				<div id="exam-result-sticky" class="exam-result" style="display: none;<?=$courseHomePageId == 6 ? 'right:0;': '' ?> top: 47px;">
					<?php $count = count($examTabExamPageLinks);?>
					<?php foreach ($examTabExamPageLinks as $examName => $data):?>
                                            <?php 
                                            $exam = $examName;
                                            $featuredStyle = '';
                                            if($data['is_featured'] == 1) {
                                                $featuredStyle = ";text-decoration:underline !important;";
                                                $exam = "<strong>$examName</strong>";
                                            }
                                            if($k%2==0):?>
								<p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$examName?>');" href="<?php echo $data['url'];?>" style="padding:0 !important <?php echo $featuredStyle;?>"><?php echo $exam;?></a></p>
								<?php else:?>
								<p class="flRt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$examName?>');" href="<?php echo $data['url'];?>" style="padding:0 !important <?php echo $featuredStyle;?>"><?php echo $exam;?> </a></p>							
								<?php endif; $k++; ?>
					<?php endforeach;?>
				</div>				
				<?php else:?>
	 				<div id="exam-result" class="exam-result" style="display: none;<?=$courseHomePageId == 6 ? 'right:0;': '' ?> top: 47px;">
					<?php $count = count($examTabExamPageLinks);?>
					<?php foreach ($examTabExamPageLinks as $examName => $data):?>
                                            <?php 
                                            $exam = $examName;
                                            $featuredStyle = '';
                                            if($data['is_featured'] == 1) {
                                                $featuredStyle = ";text-decoration:underline !important;";
                                                $exam = "<strong>$examName</strong>";
                                            }
                                            if($j%2==0):?>
								<p class="flLt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$examName?>');" href="<?php echo $data['url'];?>" style="padding:0 !important <?php echo $featuredStyle;?>"><?php echo $exam;?></a></p>
								<?php else:?>
								<p class="flRt"><a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE', 'top_navigation_bar_exam_tab', '<?=$examName?>');" href="<?php echo $data['url'];?>" style="padding:0 !important <?php echo $featuredStyle;?>"><?php echo $exam;?> </a></p>							
								<?php endif; $j++; ?>
					<?php endforeach;?>
				</div>					
				
				<?php endif; }?>
			</li>
						
			<?php elseif($coursePagesSeoDetails[$tab]['NAME'] == 'MyShortlist'):?>
							<li  
			<?php if($isFloatingBar): ?>
			onmouseenter="$j('.notificationLayer').show() ;" onmouseleave=" $j('.notificationLayer').hide();"
			<?php else:?>
			onmouseenter="$j('.notificationLayer').show() ;" onmouseleave="$j('.notificationLayer').hide();"
			<?php endif;?>
			class="shortlist-tab" 
			<?php if(count($notificationData) < 1 ||  $notificationData == "") {?>
			onmouseover="showCpgsTip(this,'cpgs_<?php echo $tab.$prefix_to_append;?>','CPGS_HOVER_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME'].$prefix_to_append?>');" onmouseout="hideCpgsTip('cpgs_<?php echo $tab.$prefix_to_append;?>');"
			<?php }?>
			>
				
				
				<a  <?=$selectedTab != 'MyShortlist' ? "target='_blank'" : "" ?> style="color:#fff !important;" uniqueattr="CPGS_SESSION_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME']?>" href="<?=$coursePagesSeoDetails[$tab]['URL']?>" <?=$class?>> 
				<i class="common-sprite new-badge"></i><i class="common-sprite icon-shortlist"><span id="myShortlist<?=$prefix_to_append ?>" class="count-number"><?=$shortlistedCourseCount; ?></span></i><span>My Shortlist</span></a>
				 <i class="common-sprite <?= $isAllNotificationSeen ? '' : 'red-'?><?=isset($isAllNotificationSeen) && $isAllNotificationSeen !== "" ?'bell-icon' :'' ?>"></i>
					           <div class="help-tool-tip" style="display: none; left: 0px !important; top: 55px !important;" id="cpgs_<?php echo $tab.$prefix_to_append;?>">
                                    <div class="contents"><?php echo str_replace("<>", $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'], $coursePagesSeoDetails[$tab]['HELP_TXT']);?>
                                    <i class="pointer"></i>
                                    </div>

                                    
                                    
                        </div>
				</a>	
				
				                            <?php if(isset($notificationData) && count($notificationData) > 0 && $notificationData!=="") {?>				                           				                                  
                                        <div id="shortlistNotification<?=$isFloatingBar ? 'Sticky' : ''?>"  class=" shortlist-notification-layer notificationLayer" style="width:380px; display:none; padding:10px 10px 10px 0">
                                    	<i class="common-sprite shortlist-tab-pointer"></i>
                                    	<div class="scrollbar1 shortlist-info-list">
                                 			<div class="scrollbar">
                								<div class="track">
                    							<div class="thumb"></div>
                    							</div>
                							</div>
                						 <div class="viewport" style="height:350px;overflow:hidden;">
                						 <div class="overview" style="width:370px">	
                                    	<?php $i =1; foreach ($notificationData as $data) {?>
                                    		<div class="<?= count($notificationData) <=$i  ? 'last' : ''?> shortlist-info-detail <?= $data['is_seen'] == "" || empty($data['is_seen']) ? 'unseen' :'seen' ?>" notificationId="<?=$data['id'] ?>" >
												<?= $data['body']?>
	                                            <div class="review-duration" style="margin-top:10px">
	                                                 <i class="common-sprite duration-icn"></i> <?=$data['timeText'] ?>
	                                             </div>
                                    		</div>
                                    		<?php $i++;}?>
<!--                                     		<div class="shortlist-info-detail"> -->
<!--                                     			<strong>Luxury Connect Business School responded </strong>to your questions -->
<!-- 	                                            <div class="review-duration"> -->
<!-- 	                                                 <i class="common-sprite duration-icn"></i> 1 hr ago -->
<!-- 	                                             </div> -->
<!--                                     		</div> -->
<!--                                     		<div class="shortlist-info-detail last"> -->
<!--                                     			<strong>Management Development Institute</strong> gets new alumni review -->
<!-- 	                                            <div class="review-duration"> -->
<!-- 	                                                 <i class="common-sprite duration-icn"></i> 6 hrs ago -->
<!-- 	                                             </div> -->
<!--                                     		</div> -->
                                    	</div>
                                    	</div>
                                    	</div>
                                    	</div>
                                    	<?php }?>
                                    	</li>
			
			<?php elseif($coursePagesSeoDetails[$tab]['NAME'] == 'Rankings' && !empty($tabData['ranking'])):?>
							<li <?php if($isFloatingBar): ?>
			onmouseenter="$('ranking-result-sticky').style.display = 'block' ; $('ranking-result').style.display = 'none';$j('#border_div_sticky').show();" onmouseleave=" $('ranking-result-sticky').style.display='none' ;$('ranking-result').style.display = 'none'; $('border_div_sticky').style.display='none'"
			<?php else:?>
			onmouseenter=" $('ranking-result').style.display = 'block';$('border_div').style.display = 'block'" onmouseleave="$('ranking-result').style.display = 'none';$('border_div').style.display = 'none'"
			<?php endif;?>
			>
				<a uniqueattr="CPGS_SESSION_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME']?>" href="<?=$coursePagesSeoDetails[$tab]['URL']?>" <?=$class?>><i class="icon-<?=$tab?>"></i><?=$coursePagesSeoDetails[$tab]['NAME']?>
			           <div class="help-tool-tip" style="display: none;" id="cpgs_<?php echo $tab.$prefix_to_append;?>">
                                    <div class="contents"><?php echo str_replace("<>", $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'], $coursePagesSeoDetails[$tab]['HELP_TXT']);?>
                                    <i class="pointer"></i>
                                    </div>
                        </div>
			<i class="examRslt-icon"></i>
				</a>		
				
				
				<div id="ranking-result" class="exam-result" style="width:435px;display: none;<?=$courseHomePageId == 6 ? 'right:0;': '' ?> top: 45px;">
					<?php
					foreach($tabData['ranking'] as $rankingRow)
					{
				        ?>
					     <p style="width:94%;line-height:20px;" ><a href="<?php echo SHIKSHA_HOME.$rankingRow['url'];?>" style="padding:0 !important"><?php echo $rankingRow['title']?></a></p>
					<?php
					}
					?>
				</div>
				<?php if($isFloatingBar): ?>
				<div id="ranking-result-sticky" class="exam-result" style="width:435px;display: none;<?=$courseHomePageId == 6 ? 'right:0;': '' ?> top: 47px;">
					<?php
					foreach($tabData['ranking'] as $rankingRow)
					{
				        ?>
					     <p style="width:94%;line-height:20px;"><a href="<?php echo SHIKSHA_HOME.$rankingRow['url'];?>" style="padding:0 !important"><?php echo $rankingRow['title']?></a></p>
					<?php
					}
					?>
				</div>
				
				<?php endif;?>
				
				
			</li>

			<?php elseif($coursePagesSeoDetails[$tab]['NAME'] == 'Institutes' && isset($tabData['category']) && !empty($tabData['category'])):?>
				<li onmouseenter=" $('category-result').style.display = 'block';$('border_div').style.display = 'block'" onmouseleave="$('category-result').style.display = 'none';$('border_div').style.display = 'none'" >
					<a uniqueattr="CPGS_SESSION_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME']?>" href="<?=$coursePagesSeoDetails[$tab]['URL']?>" <?=$class?>><i class="icon-<?=$tab?>"></i><?=$coursePagesSeoDetails[$tab]['NAME']?>
			           <div class="help-tool-tip" style="display: none;" id="cpgs_<?php echo $tab.$prefix_to_append;?>">
                                    <div class="contents"><?php echo str_replace("<>", $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'], $coursePagesSeoDetails[$tab]['HELP_TXT']);?>
                                    <i class="pointer"></i>
                                    </div>
                        </div>
						<i class="examRslt-icon"></i>
					</a>
					<div id="category-result" class="exam-result" style="width:435px;display: none;top: 45px;">
					<?php
					$totalCategoryRelatedLinks = count($tabData['category']);
					foreach($tabData['category'] as $key=>$categoryRow)
					{
				        ?>
					     <p style="width:94%;line-height:20px;" >
					     <?php if($key == ($totalCategoryRelatedLinks-1)){?>
					     	<a href="<?php echo $categoryRow['url'];?>" onclick="removeCategoryPageCookiesToShowLocationOverlay();" style="padding:0 !important"><?php echo $categoryRow['urlTitle']?></a>
					     	<?php } else {?>
					     	<a href="<?php echo $categoryRow['url'];?>" style="padding:0 !important"><?php echo $categoryRow['urlTitle']?></a>
					     	<?php } ?>
					     </p>
					<?php
					}
					?>
					</div>
			</li>
			
			
			<?php else:
			if( !empty($coursePagesSeoDetails[$tab]['NAME'] )) { ?>
				<li onmouseover="showCpgsTip(this,'cpgs_<?php echo $tab . $prefix_to_append; ?>','CPGS_HOVER_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME'] . $prefix_to_append ?>');"
					onmouseout="hideCpgsTip('cpgs_<?php echo $tab . $prefix_to_append; ?>');">
					<a uniqueattr="CPGS_SESSION_tabs/<?php echo $coursePagesSeoDetails[$tab]['NAME'] ?>"
					   href="<?= $coursePagesSeoDetails[$tab]['URL'] ?>" <?= $class ?>><i
							class="icon-<?= $tab ?>"></i><?= $coursePagesSeoDetails[$tab]['NAME'] ?>
						<div class="help-tool-tip" style="display: none;"
							 id="cpgs_<?php echo $tab . $prefix_to_append; ?>">
							<div
								class="contents"><?php echo str_replace("<>", $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'], $coursePagesSeoDetails[$tab]['HELP_TXT']); ?>
								<i class="pointer"></i>
							</div>
						</div>
					</a>
				</li>
				<?php
			}
			endif;
			}
			?>
		    </ul>
			    
		<?php if(is_array($backLinkArray) && $backLinkArray['MESSAGE'] != "" && isset($isFloatingBar) && $isFloatingBar) {	?>
			<script>var is_back_link_div_visible = 1; </script>
			<p style="margin: 5px 0 0 0; padding: 0; line-height: normal; font-size: 13px" id="<?=$backLinkDivId?>"><a href="<?=$backLinkArray['LANDING_URL']?>"><span style="font-size: 18px">&laquo;</span> <?=$backLinkArray['MESSAGE']?></a></p>
		<?php } else { ?>			
			<script>var is_back_link_div_visible = 0; </script>
		<?php }
		
		if($ifGutterHelpTextRequired) {	?>
		<div class="navigate-info" <?=$helpTextDivId?>><i class="info-pointer"></i><p>Navigate about <?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?> from here</p></div>
<?php		}
		
 if(!(isset($isFloatingBar) && $isFloatingBar)) {	?>
	</div>
<?php } ?>
</div>

<script>
var subCatId = <?php echo $courseHomePageId; ?>;

var TRACK_INTO_GA = true;
var tab_track_index = 0;
var tab_track_index_float = 0;
var tab_track_array_float = [];
var tab_track_array = [];
function showCpgsTip(div,id,uniqueAttribute) {
	if(typeof($j) == 'undefined') {
		return false;	
	}
	
	$j(div).mouseenter(function() {


	});
	
    var left_offset = $j('#'+id).prev().position();
    var width = $j('#'+id).parent().parent().width();
    var calculated_left = (left_offset.left);   
    
    $j('#'+id).css({left:calculated_left,top:(left_offset.top+42)});

    if(id == "cpgs_MyShortlist" || id == "cpgs_MyShortlistfloat") {
           	 $j('#'+id).css({left:0,top:(55)});
    }
        
    $j('#'+id).show();

	if(tab_track_array_float.length >0 && tab_track_array.length>0) {
		return false;
	}
        
        return false;
	if(TRACK_INTO_GA && typeof(pageTracker) != 'undefined' && typeof(uniqueAttribute) !='undefined' && (tab_track_array.length == 0 || tab_track_array_float.length == 0)) {	
		
		//console.log(tab_track_array.length+"___"+tab_track_array_float.length);		
		if(uniqueAttribute.indexOf('float') >0) {

			if(tab_track_array_float.length == 0){
				tab_track_index_float++;
				tab_track_array_float[tab_track_index_float] = uniqueAttribute;
				pageTracker._setCustomVar(2, "GATrackingVariableSession", uniqueAttribute,2);
				pageTracker._trackPageview();
			}
						
		} else {

			if(tab_track_array.length == 0) {
				tab_track_index++;
				tab_track_array[tab_track_index] = uniqueAttribute;
				pageTracker._setCustomVar(2, "GATrackingVariableSession", uniqueAttribute,2);
				pageTracker._trackPageview();
			}
			
		}
		
	   	
	}
}

function hideCpgsTip(id) {
	
	if(typeof($j) == 'undefined') {
		return false;	
	}
	
	$j('#'+id).hide();
}
function showCPLayer(type){
	if(type=='FLOATING'){
		$j('#cp-result-sticky').show() ;
		$j('#cp-result').hide();
		$j('#cp_border_div_sticky').show();
	}else{
		$j('#cp-result').show();
		$j('#cp_border_div').show();
	}
	var leftPostion = obtainPostitionFromLeft('cptab');
	$j('#cp-result').css({'left':leftPostion});
	$j('#cp-result-sticky').css({'left':leftPostion+2});
}

function hideCPLayer(type){
	if(type=="FLOATING"){
		$j('#cp-result-sticky').hide() ;
		$j('#cp-result').hide();
		$j('#cp_border_div_sticky').hide();	
	}else{
		$j('#cp-result').hide();
		$j('#cp_border_div').hide();
	}
}

function obtainPostitionFromLeft(id) {
   var offsets = $j('#'+id).position();
   var left = offsets.left;
   return left;
}
</script>
