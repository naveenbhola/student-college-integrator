<h2 class="comp-title">Comparing Institutes</h2>
<table cellpadding="0" cellspacing="0" border="1" class="compare-table2" style="display:none;" id="comparePageTop">
	<tr>
		<td width="165" align="center" valign="middle">
            <div class="compare-items">
                <a href="#" onclick="emailMeCompareLayer(); return false;"><img src="public/images/email-comparison.gif" alt="Email me this comparison"/></a>
            </div>
        </td>
		<?php
		$j = 0;$isSAComparePage = 0;
		if($request->getCountryId()!=2)
		{
			$isSAComparePage = 1;
		}
		foreach($institutes as $institute){
			$j++;
			$course = $institute->getFlagshipCourse();
			$course->setCurrentLocations($request);
			if(strlen($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()) > 35){
				$instStr  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()),0,31));
				$instStr .= "...";
			}else{
				$instStr = htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());
			}
			if($_COOKIE["applied_".$course->getId()] == 1){
				$class = "requested-e-bro";
			}else{
				$class = "";
			}
			$courseLocations = $course->getCurrentLocations();
			$displayLocation = $course->getCurrentMainLocation();
			if(count($course->getLocations()) > 1){
				if($request->getCityId() > 1){
					$additionalURLParams = "?city=".$displayLocation->getCity()->getId();
					if($request->getLocalityId()){
						$additionalURLParams .= "&locality=".$request->getLocalityId();
					}
				}
				$course->setAdditionalURLParams($additionalURLParams);
			}
		?>
		<td valign="top" width="165" class="<?=$class?>">
			<div class="compare-items">
				<p class="remove-inst"><a href="#" onclick="$j('#close<?=$j?>').trigger('click'); return false;">remove institute<span>&nbsp;</span></a></p>
                <div class="small-figure">
					<a href="<?=$course->getURL()?>" target="_blank">
						<img title="<?php echo htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?>" alt="<?php echo htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?>" border="0"  width="40" height="40" src="<?php echo $institute->getMainHeaderImage()->getThumbURL()?$institute->getMainHeaderImage()->getThumbURL():'/public/images/recommendation-default-image.jpg'; ?>">
					</a>
				</div>
                <div class="compare-title"><a target="_blank" href="<?=$course->getURL()?>" title="<?php echo htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?>"><?=$instStr?></a></div><div class="clearFix spacer10"></div>
				<?php
				if($class == "requested-e-bro"){
				?>
					<div class="clearFix"></div><p class="req-bro-txt2">E-brochure Requested</p>
				<?php
				}else{
				?>
					<input type="button" onclick="ApplyNowCourse('<?php echo $institute->getId(); ?>','<?php echo base64_encode(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()));?>','<?php echo $course->getId(); ?>','<?php echo base64_encode(htmlspecialchars($course->getName())); ?>','<?=$course->getURL()?>');" title="Download E-brochure" value="Download E-brochure" class="orange-button"/>
				<?php
				}
				?>
			</div>
		</td>
		<?php } 
		if($j < 4){
			while($j < 4){
				$j++;
		?>
		<td width="165" valign="top">
			<div class="compare-items"><a href="#" onclick="openNormalSlide(); return false;" style="text-decoration:none">
				<p class="add-inst">add an institute<span>&nbsp;</span></p>
				<p class="compare-hints">To compare another institute, add here</p></a>
			</div>
        </td>
		<?php
			}
		}
		?>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="1" class="compare-table">
	<tr>
		<td width="165" align="center" valign="middle">
            <div class="compare-items" id="email-compare">
                <a href="#" onclick="emailMeCompareLayer(); return false;"><img src="public/images/email-comparison.gif" alt="Email me this comparison"/></a>
            </div>
        </td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$j++;
			$course = $institute->getFlagshipCourse();
			$course->setCurrentLocations($request);
			if(strlen($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()) > 35){
				$instStr  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()),0,31));
				$instStr .= "...";
			}else{
				$instStr = htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());
			}
			$course = $institute->getFlagshipCourse();
			if($_COOKIE["applied_".$course->getId()] == 1){
				$class = "requested-e-bro";
			}else{
				$class = "";
			}
			$courseLocations = $course->getCurrentLocations();
			$displayLocation = $course->getCurrentMainLocation();
			if(count($course->getLocations()) > 1){
				if($request->getCityId() > 1){
					$additionalURLParams = "?city=".$displayLocation->getCity()->getId();
					if($request->getLocalityId()){
						$additionalURLParams .= "&locality=".$request->getLocalityId();
					}
				}
				$course->setAdditionalURLParams($additionalURLParams);
			}
		?>
                
		<td valign="top" width="165" class="<?=$class?>">
		<input type="hidden" id="course_location_required_city_<?php echo $course->getId();?>" value="<?php echo $course->getCurrentMainLocation()->getCity()->getId();?>"/>
                                        <input type="hidden" id="course_location_required_locality_<?php echo $course->getId();?>" value="<?php  echo $course->getCurrentMainLocation()->getLocality()->getId();?>"/>
			<div class="compare-items">
				<p class="remove-inst"><a href="#" onclick="$j('#close<?=$j?>').trigger('click'); return false;">remove institute<span>&nbsp;</span></a></p>
				<strong class="compare-inst-title"><a target="_blank" href="<?=$course->getURL()?>" title="<?php echo htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?>"><?=$instStr?></a></strong>
				<div class="figure">
					<a href="<?=$course->getURL()?>" target="_blank">
						<img title="<?php echo htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?>" alt="<?php echo htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?>" border="0"  width="124" height="104" src="<?php echo $institute->getMainHeaderImage()->getThumbURL()?$institute->getMainHeaderImage()->getThumbURL():'/public/images/recommendation-default-image.jpg'; ?>">
					</a>
				</div>
				<?php
				if($class == "requested-e-bro"){
				?>
					<p class="req-bro-txt">E-brochure Requested</p>
				<?php
				}else{
				?>
					<input type="button" onclick="ApplyNowCourse('<?php echo $institute->getId(); ?>','<?php echo base64_encode(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()));?>','<?php echo $course->getId(); ?>','<?php echo base64_encode(htmlspecialchars($course->getName())); ?>','<?=$course->getURL()?>');" title="Download E-brochure" value="Download E-brochure" class="orange-button"/>
				<?php
				}
				?>
			</div>
		</td>
		<?php } 
		if($j < 4){
			while($j < 4){
				$j++;
		?>
		<td width="165" valign="top">
			<div class="compare-items">
				<a href="#" onclick="openNormalSlide(); return false;" style="text-decoration:none"><p class="add-inst">add an institute<span>&nbsp;</span></p>
				<div class="inst-numb"><?=$j?></div>
				<p class="compare-hints">To compare another institute, add here</p></a>
			</div>
        </td>
		<?php
			}
		}
		?>
	</tr>
	<tr>
		<td valign="top"><div class="compare-items"><strong>Course Name</strong></div></td>
		<?php
		$position = 0;
		foreach($institutes as $i){
			$course = $i->getFlagshipCourse();
		?>
		<td valign="top" id="dropdown">
			<div class="compare-items">
				<div onmouseover="$('ul<?=$course->getId()?>').style.display = 'block';" onmouseout="$('ul<?=$course->getId()?>').style.display = 'none';">
					<?php if(count($instituteList[$position]['courseList']) > 1){ ?>
					<div>
						<?=$course->getName()?>&nbsp;<span class="orangeColor">&#9660;</span>
					</div>
					<ul id="ul<?=$course->getId()?>" class="compare_course">
						<?php
							foreach($instituteList[$position]['courseList'] as $c){
								if($c->getName() && $c->getId() != $course->getId()){
						?>
							<li ><a href="#" onclick="updateCompareLayer('<?=$position?>', '<?=$c->getId()?>','<?=$i->getId()?>'); return false;"><?=htmlspecialchars($c->getName())?></a></li>
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
		<td valign="top"><div class="compare-items"><strong>AIIMA Rating</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			if($institute->getAIMARating()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><span class="ratingBox"><?=$institute->getAIMARating()?></span></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_1"/></td>';
		}
		?>
	</tr>
	<tr id="row2">
		<td valign="top"><div class="compare-items"><strong>Alumni Rating</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			if($institute->getAlumniRating()){
				$j++;
		?>
		<td valign="top">
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
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_2"/></td>';
		}
		?>
	</tr>
	<tr id="row17">
		<td valign="top"><div class="compare-items"><strong>Course Duration</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->getDuration() && $course->getDuration()!=""){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><?=$course->getDuration()?></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_17"/></td>';
		}
		?>
	</tr>
	<tr id="row18">
		<td valign="top"><div class="compare-items"><strong>Mode of Study</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->getCourseType()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><?=$course->getCourseType()?></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_18"/></td>';
		}
		?>
	</tr>
	<tr id="row19">
		<td valign="top"><div class="compare-items"><strong>Affiliated To</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			$affiliations = $course->getAffiliations();
			if(count($affiliations) > 0){
				$j++;
		?>
		<td valign="top">
		<div class="compare-items">
		<?php
		foreach($affiliations as $affiliation) {
			echo '<div class="co_dot">'.langStr('affiliation_'.$affiliation[0],$affiliation[1])."</div>";
		}
		?>
		</div>
		</td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_19"/></td>';
		}
		?>
	</tr>
	<tr id="row3">
		<td valign="top"><div class="compare-items"><strong>Average Salary (p.a.)</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->getAverageSalary()){
			$j++;
		?>
		<?php $salArray = $course->getSalary();?>
		<td valign="top"><div class="compare-items"><?php echo  $salArray['currency']." ".number_format($course->getAverageSalary()/100000,2)." Lacs "; ?></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_3"/></td>';
		}
		?>
	</tr>
	<tr id="row4">
		<td valign="top"><div class="compare-items"><strong>Top Recruiting Companies</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if(($recruitingCompanies = $course->getRecruitingCompanies())&&
			   (!(count($recruitingCompanies) == 1 && !$recruitingCompanies[0]->getName()))){
				$j++;
		?>
		<td valign="top">
			<div class="compare-items">
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
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_4"/></td>';
		}
		?>
	</tr>

	<tr id="row5">
		<td valign="top"><div class="compare-items"><strong>AICTE Approved</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->isAICTEApproved()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_5"/></td>';
		}
		?>         
	</tr>
	<tr id="row6">
		<td valign="top"><div class="compare-items"><strong>UGC Recognised</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->isUGCRecognised()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_6"/></td>';
		}
		?>     
	</tr>
	<tr id="row20">
		<td valign="top"><div class="compare-items"><strong>DEC Approved</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->isDECApproved()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_20"/></td>';
		}
		?>     
	</tr>
	<tr id="row7">
		<td valign="top"><div class="compare-items"><strong>Fees</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->getFees()->getValue()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><?=$course->getFees()?></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_7"/></td>';
		}
		?>
	</tr>
	<tr id="row8">
		<td valign="top"><div class="compare-items"><strong>Eligibility</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			$exams = $course->getEligibilityExams();
			if(count($exams) > 0){
				$j++;
		?>
		<td valign="top" class="compare-items">
		<?php
		foreach($exams as $exam) {
			$marks= $exam->getMarks() > 0 ? " : ".$exam->getMarks() :'';
			if($marks && $exam->getMarksType()){
				$mark_type = $exam->getMarksType();
				$mark_type = str_replace("_"," ",$mark_type);
			}
			else{
				$mark_type = '';
			}
			echo '<div class="co_dot">'.$exam->getAcronym().$marks." ".$mark_type.'</div>';
		}
		if($course->getOtherEligibilityCriteria()!=''){
			echo '<div class="co_dot">'.$course->getOtherEligibilityCriteria().'</div>';
		}
		?>
		</td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_8"/></td>';
		}
		?>
	</tr>
	<tr id="row9">
		<td valign="top"><div class="compare-items"><strong>Dual Degree</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($sf = $course->offersDualDegree()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_9"/></td>';
		}
		?> 
	</tr>
<?php if($isSAComparePage == 0){ ?>	
	<tr id="row10">
		<td valign="top"><div class="compare-items"><strong>Foreign Travel</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($sf = $course->offersForeignTravel()){
				$j++;
		?>
		<td valign="top"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></td>
		<?php }elseif($sf = $course->offersForeignExchange()){
			$j++;
		?>
		<td valign="top"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_10"/></td>';
		}
		?>
	</tr>
	<tr id="row11">
		<td valign="top"><div class="compare-items"><strong>Free Laptop</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->providesFreeLaptop()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_11"/></td>';
		}
		?>  
	</tr>
<?php } // End of if($isSAComparePage==0). ?>
	<tr id="row12">
		<td valign="top"><div class="compare-items"><strong>In-Campus Hostel </strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->providesHostelAccomodation()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
		if($j == 0){
			echo '<td id="hidetr_12"/></td>';
		}
		?> 
	</tr>
<?php if($isSAComparePage==0){ ?>
	<tr id="row13">
		<td valign="top"><div class="compare-items"><strong>Transport Facility</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->providesTransportFacility()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_13"/></td>';
		}
		?> 
	</tr>
	<tr id="row14">
		<td valign="top"><div class="compare-items"><strong>Wifi Campus</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->hasWifiCampus()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_14"/></td>';
		}
		?>
	</tr>
	<tr id="row15">
		<td valign="top"><div class="compare-items"><strong>AC Campus</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($course->hasACCampus()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><img border="0" src="/public/images/cn_chk.gif"></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_15"/></td>';
		}
		?> 
	</tr>
	<tr id="row16">
		<td valign="top"><div class="compare-items"><strong>Free Training</strong></div></td>
		<?php
		$j = 0;
		foreach($institutes as $institute){
			$course = $institute->getFlagshipCourse();
			if($sf = $course->getFreeTrainingProgram()){
				$j++;
		?>
		<td valign="top"><div class="compare-items"><?=langStr('feature_'.$sf->getName().'_'.$sf->getValue())?></div></td>
		<?php }else{ ?>
		<td valign="top">&nbsp;</td>
		<?php } }
			if($j == 0){
			echo '<td id="hidetr_16"/></td>';
		}
		?> 
	</tr>
<?php } // End of if($isSAComparePage==0).

	if($isSAComparePage == 1){ 
?>
	<tr id="row21">
		<td valign="top"><div class="compare-items"><strong>Starts in</strong></div></td>
			<?php
				$j = 0;
				foreach($institutes as $institute){
					$course = $institute->getFlagshipCourse();
					$sf = $course->getDateOfCommencement();
					if($sf){
						$j++;
			?>
			<td valign="top"><div class="compare-items"><?=date('F, Y',strtotime($sf))?></div></td>
			<?php }else{ ?>
						<td valign="top">&nbsp;</td>
					<?php } 
				}
				if($j == 0){
					echo '<td id="hidetr_21"/></td>';
				}
			?> 
	</tr>
<?php }?>
</table>
<?php
$validity['isSAComparePage']=$isSAComparePage; 
$this->load->view('categoryList/categoryPageComparePageWidget',$validity);
?>
