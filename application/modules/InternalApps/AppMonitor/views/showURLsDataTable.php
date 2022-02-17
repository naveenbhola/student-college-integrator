<style>
.truncate {
  width: 350px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.topurls-table tr td{padding : 2px;}
</style>
<h2 style='font-size:24px;'>
<?php if($dashboardType == ENT_DASHBOARD_TYPE_JS_ERROR) { echo $msg;} else echo $controller.'/'.($method == 'All' ? $module : $method); ?>
<div style='margin-top:10px; color:#777; font-size:16px;'>
<?php echo date("M d, Y",strtotime($selecteddate));?>
<?php if($todate && $todate != $selecteddate) { echo ' - '.date("M d, Y",strtotime($todate)); }?>
</div>
</h2>
<div style='height:430px; overflow: auto; margin-top:20px;'>
<table class="exceptionErrorTable" width='870' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
	<tr>
		<th width="30">#</th>
		<?php
			if(in_array($dashboardType, array(ENT_DASHBOARD_TYPE_EXCEPTION, ENT_DASHBOARD_TYPE_DB_ERROR,ENT_DASHBOARD_TYPE_JS_ERROR)) )
				echo "<th width='400'>URLs</th>";
			else		
				echo "<th width='600'>URLs</th>";
		?>
		
		<?php 
			if($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES)
				echo "<th width='100'>Time Taken</th>";
			else if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY) 
				echo "<th width='100'>Memory</th>";
			else if($dashboardType == ENT_DASHBOARD_TYPE_CACHE) 
				echo "<th width='100'>Cache</th>";	
			else if(in_array($dashboardType, array(ENT_DASHBOARD_TYPE_EXCEPTION,ENT_DASHBOARD_TYPE_DB_ERROR)) )
				echo "<th width='300'>Referrer</th>";
			else if($dashboardType == ENT_DASHBOARD_TYPE_JS_ERROR) 
				echo "<th width='100'>userAgent</th>";	
		?>
		<th width="100">Mobile</th>
	</tr>
	<?php 
		foreach ($urlData as  $key=>$value) {
	?>
		<tr>
			<td><?php echo $key+1;?></td>
			<td><p title="<?php echo $value['url'];?>"><?php echo $value['url'];?></p></td>
			
			<td><?php if($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES){echo ceil($value['time_taken'])." ms";}
					  else if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY){echo number_format($value['memory_occupied']/(1024 * 1024), 2)." MB";}
					  else if($dashboardType == ENT_DASHBOARD_TYPE_CACHE){echo number_format($value['cache_size']/(1024 * 1024), 2)." Mb";}
					  else if(in_array($dashboardType, array( ENT_DASHBOARD_TYPE_EXCEPTION,ENT_DASHBOARD_TYPE_DB_ERROR))) {echo $value['referrer'];}
					  else if($dashboardType == ENT_DASHBOARD_TYPE_JS_ERROR) echo $value['userAgent'];
					  ?></td>
			<td><?php if($dashboardType == ENT_DASHBOARD_TYPE_JS_ERROR) { echo $value['is_mobile'];} else echo $value['is_mobile'] ? "Yes" : "No"; ?></td>
		</tr>
	<?php
		}
	?>
</table>
</div>