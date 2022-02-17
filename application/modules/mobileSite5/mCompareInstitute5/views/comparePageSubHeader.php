
<!--div class="head-group" data-enhance="false">
<h1>
    <div style="font-size:1.1em; margin-left: 12px; text-align: left;">
	College Compare
    </div>
  </h1>
</div-->
<?php 
if(count($courseIdArr) > 0){
?>
<div id="comparePageStickySubHeader" style="display:none;">
	<div class="sticky-header">
	   <table class="compare-table">
	   	<tbody>
	   		<tr>
	   			<?php 
	   			$iteration = 0;
	   			foreach ($courseIdArr as $courseId){
	   				$iteration++;
	   				
	   				if($showAcademicUnitSection){
						$instituteId = $academicUnitRawData[$courseId]['userSelectedInstitute'];
					}else{
						$instituteId = $instIdArr[$courseId];
					}
	   				
	   				$institute   = $instituteObjs[$instituteId];
	   				$instNameDisplay = ($institute->getShortName() != '') ? $institute->getShortName() : $institute->getName();
	   				if(strlen($instNameDisplay) > 30){
						$instName  = substr(html_escape($instNameDisplay), 0, 28);
						$instName .= "...";
					}else{
						$instName = html_escape($instNameDisplay);
					}
					//$course = $institute->getFlagshipCourse();
					//$course->setCurrentLocations($request);
					$course = $courseObjs[$courseId];
	   			?>
	   			<th class="border-new" lang="en">
	   				<p class="clg-name"><?php echo $instName?></p>
	   				<p class="location-colg"><?php echo $institute->getMainLocation()?$institute->getMainLocation()->getCityName():'';?></p>
	   			</th>
	   			<?php 
	   			}
	   			if($iteration == 1)
	   				echo '<th class="border-new">&nbsp;</th>';
	   			?>
	   		</tr>
	   	</tbody>
	   </table>
	</div>
</div>
<?php 
}
?>