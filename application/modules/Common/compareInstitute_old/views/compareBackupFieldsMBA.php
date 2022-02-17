		<tr id="row11">
			<td width="165" valign="top">
			    <div class="compare-items" style="position:relative"><label>Course Duration</label>
			    </div>
			</td>
			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();
				if(($course->getDuration() && $course->getDuration()!="") || $course->getCourseType()){
					$j++;
			?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <div class="compare-items">
				<p class="percentile"><?=$course->getDuration()?> <?=$course->getCourseType()?></p>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php } }
			if($j == 0){
				echo '<td id="hidetr_11"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k+1; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>

		<tr id="row12">
			<td width="165" valign="top">
			    <div class="compare-items" style="position:relative"><label>Form Submission Date</label>
			    </div>
			</td>
			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();
				if($course->getDateOfFormSubmission() && $course->getDateOfFormSubmission()!="0000-00-00 00:00:00" ){
					$j++;
			?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <div class="compare-items">
				<p class="percentile"><?=date("jS F, Y",strtotime($course->getDateOfFormSubmission()))?></p>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php } }
			if($j == 0){
				echo '<td id="hidetr_12"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k+1; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>

		<tr id="row13">
			<td width="165" valign="top">
			    <div class="compare-items" style="position:relative"><label>Course Commencement Date </label>
			    </div>
			</td>
			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();
				if($course->getDateOfCourseComencement() && $course->getDateOfCourseComencement()!="0000-00-00 00:00:00" ){
					$j++;
			?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <div class="compare-items">
				<p class="percentile"><?=date("jS F, Y",strtotime($course->getDateOfCourseComencement()))?></p>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($k==4){echo "class='l	ast'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php } }
			if($j == 0){
				echo '<td id="hidetr_13"/></td>';
			}
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k+1; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					echo '>&nbsp;</td>';
				}
			}
			?>
		</tr>
               