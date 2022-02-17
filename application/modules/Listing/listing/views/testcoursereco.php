<?php
?>
<style type="text/css">
	table {
  border-collapse: collapse;
  width: 90%;
}
tr {
  border-bottom: 1px solid #ccc;
}
th{
	background-color: #ddd;
}
th, td {
  text-align: left;
  padding: 4px;
}
</style>

<form onsubmit="return false;">
<label>Input Course</label>
<input type="text" name="tags" id="tags" placeholder="Course Id" size="100" value="<?php echo $courseId;?>">
<button onclick="submitform();">Show similar course</button>
</form>
<script type="text/javascript">
	function submitform(){
		var tags = document.getElementById("tags").value;
		if(tags == ""){
			alert("Please provide a valid input");
			return false;
		}
		window.location.href = "/listing/AbroadCourseDemo/checkCourseReco/"+tags;
	}
</script>
<h1>Input Course</h1>
<table >
	<thead>
		<tr>
			<th>Course Id</th>
			<th>Course Name</th>
			<th>Course URL</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($inputCourseDetails as $value) {
		?>
		<tr>
			<td><?php echo $value['listing_type_id'];?></td>
			<td><?php echo $value['listing_title'];?></td>
			<td><?php echo $value['listing_seo_url'];?></td>
		</tr>
		<?php
			}
		?>
	</tbody>
</table>
<br/>

<div style="width: 48%;display: inline-block;">
<h1>New Similar Courses</h1>

<table>
	<thead>
		<tr>
			<th>Course Id</th>
			<th>Course Name</th>
			<th>Course URL</th>
		</tr>
	</thead>
	<tbody>
			<?php 
				foreach ($collabReco as $value) {
			?>
				<tr>
			<?php foreach ($value as $key=>$value1) { 

				if($key == 'listing_type_id'){
				?>
					<th><a href="/listing/AbroadCourseDemo/checkCourseReco/<?php echo $value1;?>"><?php echo $value1;?></a></th>
				<?php
				}else{
			?>
					<td><?php echo $value1;?></td>
			<?php } 
				 } 
				?>
				</tr>
			<?php
				}
			?>		
	</tbody>
</table>
</div>

<div style="width: 48%;display: inline-block;">
<h1>Old Similar Courses</h1>

<table>
	<thead>
		<tr>
			<th>Course Id</th>
			<th>Course Name</th>
			<th>Course URL</th>
		</tr>
	</thead>
	<tbody>
			<?php 
				foreach ($oldReco as $value) {
			?>
				<tr>
			<?php foreach ($value as $key=>$value1) { 

				if($key == 'listing_type_id'){
				?>
					<th><a href="/listing/AbroadCourseDemo/checkCourseReco/<?php echo $value1;?>"><?php echo $value1;?></a></th>
				<?php
				}else{
			?>
					<td><?php echo $value1;?></td>
			<?php } 
				 } 
				?>
				</tr>
			<?php
				}
			?>		
	</tbody>
</table>
</div>