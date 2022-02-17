<html>
<head></head>
<body>
<table border='1' style="border-collapse:collapse">
<tr><th>Course Id </th>
<th>Course Name </th>
<th>action_type</th>
<th>updated_on </th>
<th>updated_by </th>
<th>Field Value</th>
<th>Old Value </th>
<th>New Value </th>
</tr>

<?php foreach($courseWiseData as $course_id => $courseData){ 
		foreach ($courseData as $updated_on => $data) {
		$printCourse = true;
			foreach ($data  as $widgetData) { ?>
			<tr>
			<?php if($printCourse){ $printCourse =false; ?>
				<td rowspan="<?php echo count($data); ?>"><?php  echo $widgetData['listing_id'];?></td>
				<td rowspan="<?php echo count($data); ?>"><?php echo $widgetData['listing_name'] ?></td>
				<td rowspan="<?php echo count($data); ?>"><?php echo $widgetData['action_type']; ?></td>
				<td rowspan="<?php echo count($data); ?>"><?php echo $widgetData['updated_on']?></td>
				<td rowspan="<?php echo count($data); ?>"><?php echo $widgetData['user_id'].'('.$widgetData['user_name'].')'?></td>
			<?php } ?>
			<td><?php echo $widgetData['field']; ?></td>
			<td>
			<?php  if($widgetData['field'] == 'locations' ){ 
				$locationData = explode('::',$widgetData['old_value']);
				foreach($locationData as $key => $locationId){  
					 echo $locationName[$locationId]; ?></br>
			<?php	}
				} 
				else if($widgetData['field'] == 'Hierarchy'){
					$hiearchyData = explode('::',$widgetData['old_value']);
					echo '<b>stream:</b>'.$streamName[$hiearchyData[0]].'</br><b>substream:</b>'.$substreamName[$hiearchyData[1]].'</br><b>specialization:</b>'.$specialisationName[$hiearchyData[2]];
				} else if($widgetData['field'] == 'primaryInstituteName' || $widgetData['field'] == 'courseName'){
					echo $widgetData['old_value'];
				}
			 ?>
			 </td>
			<td>
				<?php  if($widgetData['field'] == 'locations'){ 
					$locationData = explode('::',$widgetData['new_value']);
					foreach($locationData as $key => $locationId){  
						 echo $locationName[$locationId]; ?></br>
				<?php	}
					} 
					else if($widgetData['field'] == 'Hierarchy'){
						$hiearchyData = explode('::',$widgetData['new_value']); 
						echo '<b>stream:</b>'.$streamName[$hiearchyData[0]].'</br><b>substream:</b>'.$substreamName[$hiearchyData[1]].'</br><b>specialization:</b>'.$specialisationName[$hiearchyData[2]];	
					} else if($widgetData['field'] == 'courseName' || $widgetData['field'] == 'primaryInstituteName'){
						echo $widgetData['new_value'];
					}
			 ?>
			 </td>
			</tr>
<?php	
		}
	}
}
	?>
</table>
</body>
</html>