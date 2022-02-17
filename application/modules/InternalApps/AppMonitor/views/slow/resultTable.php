<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
    <tr>
        <th width="50">S No.</th>
        <th width="70">Team</th>
        <th width="100">Module</th>
        <th width="100">Controller</th>
        <th width="90">Method</th>
        <th width="220">URL</th>
        <th width="50">Mobile</th>
        <th width="80">
        <?php
        if($dtype == ENT_DASHBOARD_TYPE_SLOWPAGES) {
            echo "Time (ms)";
        }
        else if($dtype == ENT_DASHBOARD_TYPE_CACHE) {
            echo "Cache (Mb)";
        }
        else if($dtype == ENT_DASHBOARD_TYPE_MEMORYPAGES) {
            echo "Memory (MB)";
        }
        ?></th>
        <th width="60">At</th>
        <?php if($dtype == ENT_DASHBOARD_TYPE_SLOWPAGES) {?>
        <th width="60">Log</th>
        <?php }?>
    </tr>
    <?php 
        if(empty($realtimedata)){
            echo "<tr><td colspan=8><i>No Rows Found !!!</i></td></tr>";
        }
        foreach($realtimedata as $key=>$realtimedatarow){
    ?>
        <tr>
        <td><?php echo ($key+1);?></td>
        <td><?php echo $realtimedatarow['team']; ?></td>
        <td><?php echo $realtimedatarow['module_name'] ? $realtimedatarow['module_name'] : "-"?></td>
        <td><?php echo $realtimedatarow['controller_name']?></td>
        <td><?php echo $realtimedatarow['method_name']?></td>
        <td><div style='width:220px; word-wrap:break-word;'><?php echo $realtimedatarow['url']?></div></td>
        <td><?php echo $realtimedatarow['is_mobile']?></td>
        <td>
        <?php
        if($dtype == ENT_DASHBOARD_TYPE_SLOWPAGES) {
            echo ceil($realtimedatarow['time_taken']);
        }
        else if($dtype == ENT_DASHBOARD_TYPE_CACHE) {
            echo number_format($realtimedatarow['cachesize']/(1024*1024), 2);
        }
        else {
            echo number_format($realtimedatarow['memory_consumed']/(1024*1024), 2);
        }
        ?>
        </td>
        <td><?php echo date("H:i:s",strtotime($realtimedatarow['log_time'])); ?></td>
        <?php if($dtype == ENT_DASHBOARD_TYPE_SLOWPAGES) {?>
        <td>
            <?php if(!empty($realtimedatarow['log_details'])){?>
                <a href="#" onclick="showSlowPageLog('<?php echo base64_encode($realtimedatarow['log_details'])?>');return false;">View</a>
            <?php } else { ?>
                N/A
            <?php }?>
        </td>
        <?php } ?>

    </tr>	
    <?php
        }
    ?>
</table>