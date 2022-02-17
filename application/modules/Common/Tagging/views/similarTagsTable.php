<?php
$tableHeads = array_keys($similarTags[0]);
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
<label>Input Tags</label>
<input type="text" name="tags" id="tags" placeholder="Comma separated tags" size="100" value="<?php echo $tags;?>">
<button onclick="submitform();">Show similar tags</button>
</form>
<script type="text/javascript">
	function submitform(){
		var tags = document.getElementById("tags").value;
		if(tags == ""){
			alert("Please provide a valid input");
			return false;
		}
		window.location.href = "/Tagging/TaggingController/tagsSuggestions/"+tags;
	}
</script>
<h1>Input Tag(s)</h1>
<table >
	<thead>
		<tr>
			<th>Tag Id</th>
			<th>Tag Name</th>
			<th>Tag Type</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($tagDetails as $value) {
		?>
		<tr>
			<td><?php echo $value['tagId'];?></td>
			<td><?php echo $value['tagName'];?></td>
			<td><?php echo $value['tagType'];?></td>
		</tr>
		<?php
			}
		?>
	</tbody>
</table>
<br/>

<h1>Input Tag(s) Shiksha Entities</h1>
<table >
	<thead>
		<tr>
			<th>Tag Id</th>
			<th>Entity Id</th>
			<th>Entity Type</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($tagEntities as $value) {
		?>
		<tr>
			<td><?php echo $value['tag_id'];?></td>
			<td><?php echo $value['entity_id'];?></td>
			<td><?php echo $value['entity_type'];?></td>
		</tr>
		<?php
			}
		?>
	</tbody>
</table>
<br/>

<h1>Similar Tag Details</h1>

<table >
	<thead>
		<tr>
	<?php 
		foreach ($tableHeads as $key=>$value) {
	?>
		<th><?php echo $value;?></th>
	<?php
		}
	?>		
		</tr>
	</thead>
	<tbody>
			<?php 
				foreach ($similarTags as $value) {
			?>
				<tr>
			<?php foreach ($value as $key=>$value1) { 

				if($key == 'tags'){
				?>
					<th><a href="/Tagging/TaggingController/tagsSuggestions/<?php echo $value['RecommendedTag'];?>"><?php echo $value1;?></a></th>
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
