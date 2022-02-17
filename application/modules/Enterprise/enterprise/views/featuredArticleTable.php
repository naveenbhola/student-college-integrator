<div id="udpateTablle"> 
	<table class="cms-table-structure" cellspacing="0" cellpadding="0" border="1" style="margin-top:15px;width:90%;margin-left: 20px;">
				<tr>
	                <th width="5%" align="center"><span style="margin-top:6px;" class="flLt">S.No.</span></th>
	                <th width="30%"><span style="margin-top:6px;" class="flLt">From Date</span></th>
	                <th width="30%"><span style="margin-top:6px;" class="flLt">To Date</span></th>
	                <th width="15%"><span style="margin-top:6px;" class="flLt">Article ID</span></th>
	                <th width="15%"><span style="margin-top:6px;" class="flLt">Action</span></th>
		        </tr>

		        <?php
		        	$counter = 1;
		        	if(empty($featuredArticlesData))
		        		echo "<tr><td colspan='5'><i>No Data Found !!!</i></td></tr>";
		        	else{
		        		foreach ($featuredArticlesData as $dataRow) {
		        ?>
		        		<tr>
		        			<td><?php echo $counter++; ?></td>
			                <td><?php echo date("d-m-Y",strtotime($dataRow['from_date']));?></td>
			                <td><?php echo date("d-m-Y",strtotime($dataRow['to_date']));?></td>
			                <td><?php echo $dataRow['article_id'];?></td>
			                <td><a href="javascript:void(0);" onclick="deleteFeaturedArticle(this, <?php echo $dataRow['id'];?>, <?php echo $dataRow['courseHomePageId'];?>);">Delete</a></td>
				        </tr>
		        <?php
		        		}
		        	}
		        ?>
	</table>
</div>
<script>
var exitingTimePeriods = new Array();
<?php
	foreach ($featuredArticlesData as $dataRow) {
?>
		exitingTimePeriods.push(new Array('<?php echo $dataRow['from_date']; ?>', '<?php echo $dataRow['to_date']; ?>'));
<?php
	}
?>
</script>