<div class="cms-form-wrapper clear-width">
	<div id="video_top_section">
		<?php $this->load->view('videoCMS/videoListFilters'); ?>
	</div>
	<div id="video_list_section">
		<?php $this->load->view('videoCMS/videoListTable'); ?>
	</div>
	<?php 
	if(count($videoList) > 0){
	?>
	<div id="video_bottom_section">
		<?php 
		if($vcmsType == 'layer'){
		?>
			<div id="video_button_section">
				<input type="button" id="embedVideos" value="Embed" class="button button--orange" />
				<a href="/videoCMS/VideoCMS/addEditVideoContent" class="button button--secondary" target="blank">+ Add New Video</a>
			</div>
		<?php 
		}
		?>
		<div id="video_pagination_section" class="pagingID">
			<div id="video_pagination_div"></div>
			<div id="video_count"><?=$totalVideoCount?> Videos</div>
		</div>
	</div>
	<?php 
	}
	?>
</div>
<script type="text/javascript">
isMobileSearch = 'true';
</script>