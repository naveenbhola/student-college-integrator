<?php 
if($appliedFilters['sortBy']['field'] == 'title' && $appliedFilters['sortBy']['order'] == 'desc'){
	$titleSorterClass = 'down';
	$dateSorterClass = 'updown';
}else if($appliedFilters['sortBy']['field'] == 'title' && $appliedFilters['sortBy']['order'] == 'asc'){
	$titleSorterClass = 'up';
	$dateSorterClass = 'updown';
}if($appliedFilters['sortBy']['field'] == 'createdOn' && $appliedFilters['sortBy']['order'] == 'desc'){
	$titleSorterClass = 'updown';
	$dateSorterClass = 'down';
}else if($appliedFilters['sortBy']['field'] == 'createdOn' && $appliedFilters['sortBy']['order'] == 'asc'){
	$titleSorterClass = 'updown';
	$dateSorterClass = 'up';
}
?>
<table cellpadding="4" cellspacing="0" border="1" width="100%" class="vcms-list">
	<tr>
		<th><?php echo $vcmsType == 'layer' ? 'Select' : 'S.No.' ?></th>
		<th width="30%">Title<i title="Sort" sortField="title" sortOrder="<?=$titleSorterClass?>" class="vcms-sort <?=$titleSorterClass?>"></i></th>
		<th width="15%">Tags</th>
		<th width="25%">Description</th>
		<th>Created On<i title="Sort" sortField="createdOn" sortOrder="<?=$dateSorterClass?>" class="vcms-sort <?=$dateSorterClass?>"></i></th>
		<th>Action</th>
	</tr>
	<?php 
	if(count($videoList) == 0){
	?>
		<td colspan="10" align="center"><p>No video available.</p></td>
	<?php 
	}else{
		foreach ($videoList as $key => $video) {
			$videoTags = $allTags = array();
			foreach ($video['tags'] as $tag) {
				$allTags[] = $tag['name'];
				if(count($videoTags) < 4){
					$videoTags[] = $tag['name'];
				}
			}
			$videoTitle = strlen($video['title']) > 34 ? substr($video['title'], 0, 31).'...' : $video['title'];
			$videoDesc = strlen($video['description']) > 40 ? substr($video['description'], 0, 37).'...' : $video['description'];
			$tagEllipsis = count($video['tags']) > 4 ? '...' : '';
		?>
			<tr>
				<td align="center" valign="middle">
					<?php 
					if($vcmsType == 'layer'){
					?>
						<label for="selectedVideos<?=$key?>"></label>
						<input type="checkbox" svid="<?php echo $video['id'] ?>" value="<?php echo $video['ytVideoId'] ?>" name="vcmsVideo[]" id="selectedVideos<?=$key?>" />
					<?php 
					}else{
						echo ($key+1)+(($currentPage-1)*$pageSize);
					}
					?>
				</td>
				<td valign="middle">
					<div class="v-img-ttl">
						<img src="https://i.ytimg.com/vi/<?=$video['ytVideoId']?>/mqdefault.jpg" width="80" height="45" />
						<span><?php echo $video['title']; ?></span>
					</div>
				</td>
				<?php 
				if(!empty($videoTags)){
				?>
					<td title="<?=implode(', ', $allTags)?>"><?php echo implode(', ', $videoTags).$tagEllipsis; ?></td>
				<?php 
				}else{
				?>
					<td align="center">-</td>
				<?php 
				}
				?>
				<td title="<?php echo $video['description']; ?>"><?php echo $videoDesc; ?></td>
				<td align="center" title="<?=date('d-m-Y H:i:s', strtotime($video['createdOn']))?>"><?php echo date('d-m-Y', strtotime($video['createdOn'])); ?></td>
				<td align="center">
				<?php 
				if($vcmsType == 'layer'){
				?>
					<a href="javascript:;" class="embedOne" ytVideoId="<?=$video['ytVideoId']?>" svid="<?=$video['id']?>">Embed</a>
				<?php 
				}else{
				?>
					<a href="/videoCMS/VideoCMS/addEditVideoContent/<?php echo $video['id']; ?>">Edit</a><!-- / <a href="javascript:;">Delete</a> -->
				<?php 
				}
				?>
				</td>
			</tr>
		<?php 
		}
	}
	?>
</table>
<input id = "methodName"  type = "hidden" value = "vcmsPaginationClick">
<input id = "countOffset" type = "hidden" value = "<?=$pageSize?>"/>
<input id = "startOffSet" type = "hidden" value = "<?=($currentPage-1)*$pageSize?>"/>
<input id = "currentPage" type = "hidden" value = "<?=$currentPage?>"/>
<input id = "totalVideoCount" type = "hidden" value = "<?=$totalVideoCount?>"/>
<script type="text/javascript">
var pageName = 'vcms-list';
</script>