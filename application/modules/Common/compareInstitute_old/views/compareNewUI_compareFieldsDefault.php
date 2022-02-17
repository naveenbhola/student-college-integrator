<?php $isSAComparePage = 0; ?>
<!--tr>
	<td><div class="cmpre-head"><label>AIIMA Rating</label></div></td>
	<?php
	$j = 0;$k = 0;
	foreach($institutes as $institute){
		$k++;
		if($institute->getAIMARating()){
			$j++;
			?>
			<td>
				<div class="cmpre-head"><p class="affliatn-txt"><?=$institute->getAIMARating()?></p></div>
			</td>
			<?php 
		}else{
		?>
			<td>-</td>
		<?php 
		}
	}
	?>
</tr-->
<!--tr>
	<td><div class="cmpre-head"><label>Alumni Rating</label></div></td>
	<?php
	$j = 0;$k = 0;
	foreach($institutes as $institute){
		$k++;
		$alumniRating = $institute->getAlumniRating();
		if($alumniRating){
			$j++;
			?>
			<td>
				<div class="cmpre-head">
					<?php
					$i = 1;
					while($i <= $alumniRating){
					?>
						<img border="0" src="/public/images/nlt_str_full.gif">
					<?php
						$i++;
					}
					?>
					<!--p class="affliatn-txt"><?=$alumniRating?></p->
				</div>
			</td>
			<?php 
		}else{
		?>
			<td>-</td>
		<?php 
		}
	}
	?>
</tr-->
<!--tr>
	<td><div class="cmpre-head"><label>Course Duration</label></div></td>
	<?php
	$j = 0;$k = 0;
	foreach($institutes as $institute){
		$k++;
		$course = $institute->getFlagshipCourse();
		if($course->getDuration() && $course->getDuration()!=""){
			$j++;
			?>
			<td>
				<div class="cmpre-head"><p class="affliatn-txt"><?=$course->getDuration()?></p></div>
			</td>
			<?php 
		}else{
		?>
			<td>-</td>
		<?php 
		}
	}
	?>
</tr-->
<!--tr>
	<td><div class="cmpre-head"><label>Mode of Study</label></div></td>
	<?php
	$j = 0;$k = 0;
	foreach($institutes as $institute){
		$k++;
		$course = $institute->getFlagshipCourse();
		if($course->getCourseType()){
			$j++;
			?>
			<td>
				<div class="cmpre-head"><p class="affliatn-txt"><?=$course->getCourseType()?></p></div>
			</td>
			<?php 
		}else{
		?>
			<td>-</td>
		<?php 
		}
	}
	?>
</tr-->
<!--tr>
	<td><div class="cmpre-head"><label>Affiliated To</label></div></td>
	<?php
	$j = 0;$k = 0;
	foreach($institutes as $institute){
		$k++;
		$course = $institute->getFlagshipCourse();
		$affiliations = $course->getAffiliations();
		if(count($affiliations) > 0){
			$j++;
			?>
			<td>
				<div class="cmpre-head">
					<?php
					foreach($affiliations as $affiliation) {
						echo '<p class="affliatn-txt">'.langStr('affiliation_'.$affiliation[0],$affiliation[1])."</p>";
					}
					?>
				</div>
			</td>
			<?php 
		}else{
		?>
			<td>-</td>
		<?php 
		}
	}
	?>
</tr-->
<!--tr>
	<td><div class="cmpre-head"><label>Average Salary (p.a.)</label></div></td>
	<?php
	$j = 0;$k = 0;
	foreach($institutes as $institute){
		$k++;
		$course = $institute->getFlagshipCourse();
		if($course->getAverageSalary()){
			$j++;
			$salArray = $course->getSalary();
			?>
			<td>
				<div class="cmpre-head"><p class="affliatn-txt"><?php echo  $salArray['currency']." ".number_format($course->getAverageSalary()/100000,2)." Lacs "; ?></p></div>
			</td>
			<?php 
		}else{
		?>
			<td>-</td>
		<?php 
		}
	}
	?>
</tr-->
<!--tr>
	<td><div class="cmpre-head"><label>Top Recruiting Companies</label></div></td>
	<?php
	$j = 0;$k = 0;
	foreach($institutes as $institute){
		$k++;
		$course = $institute->getFlagshipCourse();
		if(($recruitingCompanies = $course->getRecruitingCompanies())&&
			   (!(count($recruitingCompanies) == 1 && !$recruitingCompanies[0]->getName()))){
			$j++;
			$salArray = $course->getSalary();
			?>
			<td>
				<div class="cmpre-head">
					<?php
					$cCount = 0;
					foreach($recruitingCompanies as $company){
						if($cCount>=10) break;
						if($cCount == 5){
						    echo "<div id='viewMoreLink".$course->getId()."'><a href='javascript:void(0)' onClick='unhideCompanies(".$course->getId().");'>+ View More</a></div>";
						    echo "<div id='companyNames".$course->getId()."' style='display:none;'>";
						}
					?>
					<div style="background:url(/public/images/co_dot.jpg) left 6px no-repeat;padding-left:8px;margin-bottom:2px"><?=$company->getName();?></div>
					<?php
						$cCount++;
						}
						if($cCount>=5){ echo "</div>";}
					?>
				</div>
			</td>
			<?php 
		}else{
		?>
			<td>-</td>
		<?php 
		}
	}
	?>
</tr-->