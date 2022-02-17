<?php
if(empty($tableData)){ ?>
	<div class="spacer20 clearFix"></div>
	<h1>No data available.</h1>
<?php
} else {
?>

<div class="div-table">
	<table class="table" width="100%">
		<thead>
			<tr>
					<th>Position</th>
					<th>Article Id</th>
					<th>Article Title</th>
					<th>Start date</th>
					<th>End date</th>
					<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			foreach($tableData as $d) {
				$count++;
				$httpPresent = false;
				?>
				<tr class="grey">
					<td><?php echo $d['position'];?></td>
					<td><?php echo $d['article_id'];?></td>
					<td><?php echo $d['articleTitle']; ?></td>
					<td><?php echo $d['start_date'];?></td>
					<td><?php echo $d['end_date'];?></td>
					<td>
						<a href="/home/HomePageCMS/index/<?php echo $pageType;?>/<?php echo $d['id'];?>" style="text-decoration:none;"><input type="button" class="table-edit-btn" tabindex="5" value="Edit" ></a>
						<input type="button" class="table-remove-btn" tabindex="5" value="Remove" onclick="deleteFeaturedArticle('<?php echo $d['id'];?>','article');">
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<!--cms-tabel-ends-->
</div>
<?php
}
?>