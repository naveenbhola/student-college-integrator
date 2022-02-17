<title>Add Courses To Groups</title>
<?php
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
        $this->load->view('enterprise/headerCMS', $headerComponents);
        $this->load->view('enterprise/cmsTabs');
        ?>

        <?php
        $groups=array();
        foreach($groupList as $group)
        {
        	$groups[$group['groupId']]= $group['groupId'];
        }
        ?>

<div
	class="mar_full_10p">
<div class="lineSpace_10">&nbsp;</div>
<!--div class="fontSize_14p bld">Create Groups &amp; Add Courses</div-->
<div class="lineSpace_10">&nbsp;</div>
<div>
<table width="600" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="200" class="lineSpace_32" valign="top"><?php if($groupId!=''){?>
		<div><a href="/enterprise/shikshaDB/addCoursesToGroups">Ungrouped</a></div>
		<?php }  else {?>
		<div>Ungrouped</div>
		<?php } ?> <?php
		foreach($groups as $group)
		{
			if($groupId!=$group){
				?>
		<div><a
			href='/enterprise/shikshaDB/addCoursesToGroups/<?php echo $group?>'>Group
			<?php echo $group?></a></div>

			<?php }  else {?>
		<div>Group <?php echo $group?></div>
		<?php } ?> <?php } ?></td>
		<td width="400" valign="top"><?php if(count($courseGroupMapping)>0 && count($groups)>0){?>

		<form name="input"
			action="<?php if($groupId!=''){?>/enterprise/shikshaDB/removeCoursesFromGroup<?php }else{?>/enterprise/shikshaDB/addCoursesToGroup<?php } ?>"
			method="post">
		<div class="lineSpace_10">&nbsp;</div>
		<?php
		$nameList=array();
		$courseReach='';
		$scope = '';
		foreach($courseGroupMapping as $group) {
			if(strtolower($group['scope']) == "india") {
				continue;	
			}
			?> <?php
			if(!array_key_exists($group['name'],$nameList))
			{
				$nameList[$group['name']]=1;
				echo  '<div class="lineSpace_10">&nbsp;</div>';
				// echo '<div><b>'.$group['name'].'</b></div>';
				// echo  '<div class="lineSpace_10">&nbsp;</div>';
				if($group['CourseReach']=='national')
				{
					echo '<div><b>'.$group['name'].' Degree ('.$group['scope'].')</b></div>';
				}
				else
				{
					echo '<div><b>'.$group['name'].' Courses ('.$group['scope'].')</b></div>';
				}
				$courseReach=$group['CourseReach'];
				$scope=$group['scope'];
				echo  '<div class="lineSpace_10">&nbsp;</div>';
			}
			if(($courseReach!=$group['CourseReach']) || ($scope!=$group['scope']))
			{
				$courseReach=$group['CourseReach'];
				$scope = $group['scope'];
				echo  '<div class="lineSpace_10">&nbsp;</div>';
				if($group['CourseReach']=='national')
				{
					echo '<div><b>'.$group['name'].' Degree ('.$group['scope'].')</b></div>';
				}
				else
				{
					echo '<div><b>'.$group['name'].' Courses ('.$group['scope'].')</b></div>';
				}
				echo  '<div class="lineSpace_10">&nbsp;</div>';

			}
			?>
		<div><input type="checkbox" name="courseId[]"
			value="<?php echo $group['SpecializationId']?>"/ > <?php echo $group['CourseName'];?>
		</div>
		<?php } ?>
		<?php } ?>
		<!-- TEST PREP START -->
		<!-- TEST PREP END -->
		<div class="lineSpace_10">&nbsp;</div>
		<div><?php if ($groupId!=''){?> Remove From: <?php } else {?> Move to:
		<?php } ?> <select id="groupId" name="groupId">
		<?php if ($groupId!=''){?>
			<option value="<?php echo $groupId?>">Group <?php echo $groupId?></option>
			<?php } else {?>
			<?php foreach($groups as $group) {?>
			<option value="<?php echo $group?>">Group <?php echo $group?></option>
			<?php } ?>
			<?php } ?>
		</select></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div><input type="submit" value="Submit" /></div>
		</form>
		</td>
	</tr>
</table>
</div>
</div>
