<div class="row" id="_searchResult">
	<h4 style="font-weight:bold;">Update Reputation Index</h4>
	<table class="table table-hover table-striped table-responsive" id="resultbl">
		<tr>
			<th>UserId</th>
			<th>UserName</th>
			<th>DisplayName</th>
			<th>Previous Points (RI)</th>
			<th>Current Points (RI)</th>
			<th>Action</th>
		</tr>
		<?php $this->load->view('anaInternal/newModeration/innerUserPointDetailPage');?>
	</table>
	<table align="center"><tr id="_loadMoreRow"><td colspan="7" style="background:#fff; padding-bottom:25px;"><a id="_loadMore" href="javascript:void(0);">Load More</a></td></tr></table>
</div>

<div id="backtop" class="back2top">
		<img src="<?=SHIKSHA_HOME?>/public/images/back2top.gif">
</div>
<?php $total_pages = ceil($totalPointUser/$item_per_page);?>
<script type="text/javascript">
var track_click = 1; //track user click on "load more" button
var total_pages = '<?php echo $total_pages; ?>';
</script>