<div style="width: <?=$width?>px; margin: 0pt auto;">
		<div class="blkRound" id="pleaseWaitCompare">
				<div class="loaderBg"><img src="/public/images/loader.gif" align="middle">&nbsp;&nbsp;Loading...</div>
		</div>
        <div class="blkRound">
            <div class="bluRound">
            	<span class="float_R"><img class="pointer" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();" src="/public/images/fbArw.gif" border="0"/></span>
                <span class="title">Compare Institutes</span>
                <div class="clear_B"></div>
            </div>            
            <div style="padding: 0pt;" class="whtRound">
                    <div style="overflow: hidden; width:100%;">
                	<table width="100%" cellspacing="1" cellpadding="7" border="0" bgcolor="#efefef" id="comparOverlay">
                    	<tr>
                        	<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php
							foreach($institutes as $institute){
								if(strlen($institute->getName().", ".$institute->getMainLocation()->getCity()->getName()) > 40){
									$instStr  = substr(htmlspecialchars($institute->getName().", ".$institute->getMainLocation()->getCity()->getName()),0,36);
									$instStr .= "...";
								}else{
									$instStr = htmlspecialchars($institute->getName().", ".$institute->getMainLocation()->getCity()->getName());
								}
								$course = $institute->getFlagshipCourse();
							?>
                            <td valign="top" bgcolor="#ffffff" align="left">
                            	<div class="word-wrap w-160">
                            	<a style="display: block; height: 30px; overflow: visible;" target="_blank" href="<?=$course->getURL()?>" title="<?php echo htmlspecialchars($institute->getName().", ".$institute->getMainLocation()->getCity()->getName());?>"><strong><?=$instStr?></strong></a>
                                <div style="margin-top: 14px;margin-bottom:10px;"><a href="<?=$course->getURL()?>" target="_blank"><img title="<?php echo htmlspecialchars($institute->getName().", ".$institute->getMainLocation()->getCity()->getName());?>" alt="<?php echo htmlspecialchars($institute->getName().", ".$institute->getMainLocation()->getCity()->getName());?>" border="0"  width="124" height="104" src="<?php echo $institute->getMainHeaderImage()->getThumbURL()?$institute->getMainHeaderImage()->getThumbURL():'/public/images/recommendation-default-image.jpg'; ?>"></a></div>
								<input type="button" onclick="ApplyNowCourse('<?php echo $course->getURL(); ?>');" title="Request E-brochure" value="Request E-brochure" class="orangeButtonStyle" />
							</div>
							</td>
							<?php } ?>
                        </tr>
						<tr>
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Course Name</strong></div></td>
                            <?php
							$position = 0;
							foreach($institutes as $i){
								$course = $i->getFlagshipCourse();
							?>
							<td valign="top" bgcolor="#ffffff" align="left" id="dropdown">
                            	<div class="word-wrap w-160">
										<div onmouseover="$('ul<?=$course->getId()?>').style.display = 'block';" onmouseout="$('ul<?=$course->getId()?>').style.display = 'none';">
										<?php if(count($instituteList[$position]['courseList']) > 2){ ?>
										<div>
											<?=$course->getName()?>&nbsp;<span class="orangeColor">&#9660;</span>
										</div>
										<ul id="ul<?=$course->getId()?>" class="compare_course">
											<?php
												foreach($instituteList[$position]['courseList'] as $c){
													$course1 = explode(">>",$c);
													if($course1[1] && $course1[0] != $course->getId()){
											?>
												<li ><a href="#" onclick="updateCompareLayer('<?=$position?>', '<?=$course1[0];?>'); return false;"><?=$course1[1]?></a></li>
											<?php
													}
												}
											?>
										</ul>
										<?php }else{ ?>
										<div><?=$course->getName()?>&nbsp;</div>
										<div id="ul<?=$course->getId()?>"></div>
										<?php } ?>
										</div>
                                        </div>
							</td>
							
							<?php $position++;}
							?>
                        </tr>
                        <tr id="row1">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>AIIMA Rating</strong></div></td>
                            <?php
							$j = 0;
							foreach($institutes as $institute){
								if($institute->getAIMARating()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><?=$institute->getAIMARating()?></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_1"/></td>';
							}
							?>
                        </tr>
                        <tr id="row2">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Alumni Rating</strong></div></td>
                            <?php
							$j = 0;
							foreach($institutes as $institute){
								if($institute->getAlumniRating()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left">
								<span>
									<?php
									$i = 1;
									while($i <= $institute->getAlumniRating()){
									?>
										<img border="0" src="/public/images/nlt_str_full.gif">
									<?php
										$i++;
									}
									?>
								</span>
								<span class="rateNum">&nbsp;<?=$institute->getAlumniRating()?>/5</span>
							</td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_2"/></td>';
							}
							?>
                        </tr>
						<tr id="row17">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Course Duration</strong></div></td>
                            <?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->getDuration()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><?=$course->getDuration()?></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_17"/></td>';
							}
							?>
						</tr>
						<tr id="row18">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Mode of Study</strong></div></td>
                            <?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->getCourseType()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><?=$course->getCourseType()?></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_18"/></td>';
							}
							?>
						</tr>
						<tr id="row19">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Affiliated To</strong></div></td>
                            <?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								$affiliations = $course->getAffiliations();
								if(count($affiliations) > 0){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left">
                            <div class="word-wrap w-160">
							<?php
							foreach($affiliations as $affiliation) {
								echo '<div class="co_dot">'.langStr('affiliation_'.$affiliation[0],$affiliation[1])."</div>";
							}
							?>
                            </div>
							</td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_19"/></td>';
							}
							?>
						</tr>
                        <tr id="row3">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Average Salary (p.a.)</strong></div></td>
                            <?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->getAverageSalary()){
								$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><?php echo number_format($course->getAverageSalary()/100000,2)." Lacs"; ?></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_3"/></td>';
							}
							?>
                        </tr>
                        <tr id="row4">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Top Recruiting Companies</strong></div></td>
                          	<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if(($recruitingCompanies = $course->getRecruitingCompanies())&&
								   (!(count($recruitingCompanies) == 1 && !$recruitingCompanies[0]->getName()))){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left">
                            	<div class="word-wrap w-160">
								<?php
									$cCount = 0;
									foreach($recruitingCompanies as $company){
										$cCount++;
										if($cCount>3) break;
								?>
                          		<div class="co_dot"><?=$company->getName();?></div>
								<?php }
								if(count($recruitingCompanies) > 3){ ?>
								<div><?php echo (count($recruitingCompanies)-3);?> more recruiters</div>
								<?php
								}
								?>
								</div>
                          	</td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_4"/></td>';
							}
							?>
                        </tr>

                        <tr id="row5">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>AICTE Approved</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->isAICTEApproved()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_5"/></td>';
							}
							?>         
                        </tr>
                        <tr id="row6">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>UGC Recognised</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->isUGCRecognised()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_6"/></td>';
							}
							?>     
                        </tr>
						<tr id="row20">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>DEC Approved</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->isDECApproved()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_20"/></td>';
							}
							?>     
                        </tr>
                        <tr id="row7">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Fees</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->getFees()->getValue()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><?=$course->getFees()?></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_7"/></td>';
							}
							?>
                        </tr>
                        <tr id="row8">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Eligibility</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								$exams = $course->getEligibilityExams();
								if(count($exams) > 0){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left">
							<?php
							foreach($exams as $exam) {
								echo '<div class="co_dot">'.$exam->getAcronym().'</div>';
							}
							?>
							</td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_8"/></td>';
							}
							?>
                        </tr>
                        <tr id="row9">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Dual Degree</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($sf = $course->offersDualDegree()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_9"/></td>';
							}
							?> 
                        </tr>
                        <tr id="row10">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Foreign Travel</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($sf = $course->offersForeignTravel()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></td>
							<?php }elseif($sf = $course->offersForeignExchange()){
								$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_10"/></td>';
							}
							?>
                        </tr>
                        <tr id="row11">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Free Laptop</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->providesFreeLaptop()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_11"/></td>';
							}
							?>  
                        </tr>
                        <tr id="row12">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>In-Campus Hostel </strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->providesHostelAccomodation()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
							if($j == 0){
								echo '<td id="hidetr_12"/></td>';
							}
							?> 
                        </tr>
                        <tr id="row13">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Transport Facility</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->providesTransportFacility()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
								if($j == 0){
								echo '<td id="hidetr_13"/></td>';
							}
							?> 
                        </tr>
                        <tr id="row14">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Wifi Campus</strong></div></td>
                          	<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->hasWifiCampus()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
								if($j == 0){
								echo '<td id="hidetr_14"/></td>';
							}
							?>
							</tr>
                        <tr id="row15">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>AC Campus</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($course->hasACCampus()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><img border="0" src="/public/images/cn_chk.gif"></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
								if($j == 0){
								echo '<td id="hidetr_15"/></td>';
							}
							?> 
                        </tr>
                        <tr id="row16">
                        	<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><strong>Free Training</strong></div></td>
							<?php
							$j = 0;
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								if($sf = $course->getFreeTrainingProgram()){
									$j++;
							?>
							<td valign="top" bgcolor="#ffffff" align="left"><div class="word-wrap w-160"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div></td>
							<?php }else{ ?>
							<td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php } }
								if($j == 0){
								echo '<td id="hidetr_16"/></td>';
							}
							?> 
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
							<?php
							$courseIDs = array();
							foreach($institutes as $institute){
								$course = $institute->getFlagshipCourse();
								$courseIDs[] = $course->getId();
							?>
                            <td valign="top" bgcolor="#ffffff" align="left">
                                <div class="word-wrap w-160" style="margin: 12px 0pt 5px;">
										<input type="button" onclick="ApplyNowCourse('<?php echo $course->getURL(); ?>');" title="Request E-brochure" value="Request E-brochure" class="orangeButtonStyle" />
								</div>
							</td>
							<?php } ?>
                        </tr>
                    </table>
                    </div>                                                     
            </div>
        </div>
    </div>
<script>
for(var i=0; i<21 ; i++){
	if(document.getElementById("hidetr_"+i)){
		$('row'+i).style.display = 'none';
	}
}
currentCompareCourses = <?php echo json_encode($courseIDs); ?>;
</script>
