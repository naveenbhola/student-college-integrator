<?php 
if($showAcademicUnitSection)
{
?>
<tr id="compareRow3">
	<td><div class="cmpre-head"><label>College / Dept.</label></div></td>
	<?php 
	foreach ($compareData['academicUnit'] as $courseId => $academicUnitName) {
	?>
		<td><div class="cmpre-head"><p class="affliatn-txt"><?php echo $academicUnitName;?></p></div></td>
	<?php
	}
	?>
</tr>
<?php 
}
?>