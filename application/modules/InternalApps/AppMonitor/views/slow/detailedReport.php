<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
    <tr>
        <th width="30">#</th>
        <th width="90">Team</th>
        <th width="120">Module</th>
        <th width="150">Controller</th>
        <th width="150">Method</th>
        <th width="80">Total Hits</th>
        <th width="100">
		<?php
        if($dashboard == ENT_DASHBOARD_TYPE_SLOWPAGES) {
            echo "Avg Time (ms)";
        }
        else if($dashboard == ENT_DASHBOARD_TYPE_CACHE) {
            echo "Avg Cache (Mb)";
        }
        else if($dashboard == ENT_DASHBOARD_TYPE_MEMORY) {
            echo "Avg Memory (MB)";
        }
        ?>
		</th>
        <th width="100">&gt; Threshold</th>
        <th width="60">URL List</th>
    </tr>
    <?php 
        if(empty($data)){
            echo "<tr><td colspan=9><i>No Rows Found !!!</i></td></tr>";
        }
        $j = 1;
        foreach($data as $key => $row){
            list($team, $module, $controller, $method) = explode('~', $key);
            if($team == 'All') {
        ?>
            <tr style='background:#E6FFD6;'>
            <td>0</td>
            <td colspan='4'>All</td>
        <?php        
        }
        else {
        ?>
        <tr>
        <td><?php echo ($j++);?></td>
        <td><?php echo $team; ?></td>
        <td><?php echo $module ? $module : "-"?></td>
        <td><?php echo $controller?></td>
        <td><?php echo $method?></td>
        <?php } ?>
        
        <td><?php echo number_format($row['hits'])?></td>
        <td>
        <?php
        if($dashboard == ENT_DASHBOARD_TYPE_SLOWPAGES) {
            echo number_format(ceil($row['avg']));
        }
		else if($dashboard == ENT_DASHBOARD_TYPE_CACHE) {
            echo number_format($row['avg']/(1024*1024),2);
        }
        else {
			echo number_format($row['avg']/(1024*1024),2);
        }
        ?>
        </td>
        <td><?php echo number_format($row['above_threshold'])?></td>
        <td><a href='#' onclick="showURLList('<?php echo $module; ?>', '<?php echo $controller; ?>', '<?php echo $method; ?>', '<?php echo $filters['fromdate']; ?>', '<?php echo $filters['todate']; ?>', '<?php echo $selectedModule; ?>'); return false;">View</a></td>
    </tr>	
    <?php
        }
    ?>
</table>
<script>
function showURLList(module, controller, method, fromdate, todate, module)
{
    $("body").addClass("noscroll");
	$("#voverlay").show();
	$("#vdialog_inner").css('top', $('body').scrollTop()+20);
	$("#vdialog").show();
	$("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
    chartTypeData = controller+"___"+method;
    $.ajax({
	    	data: { "charttype" : chartTypeData , "date" : fromdate, "todate" : todate, "module" : module,"dashboardType" : "<?php echo $dashboard;?>"},
	    	url : "/AppMonitor/Dashboard/showTopUrls",
	    	cache : false,
	    	method : "POST",
	    	beforeSend : function(){
	    	}
	    }).done(function(res){
			$("#vdialog_content").html(res);
	    });
}
</script>
