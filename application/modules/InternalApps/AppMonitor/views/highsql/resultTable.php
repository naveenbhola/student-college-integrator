<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
    <tr>
        <th width="30">S No.</th>
        <th width="70">Team</th>
        <th width="100">Module</th>
        <th width="80">Controller</th>
        <th width="80">Method</th>
        <th width="220">URL</th>
        <th width="50">Mobile</th>
        <th width="45">Total Queries</th>
        <th width="100">Query Share</th>
        <th width="60">At</th>
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
            echo $realtimedatarow['total_queries'];
        ?>
        </td>
        <td><?php 
            global $DATABASE_DNS_NAME_MAPPING;
            $split = json_decode($realtimedatarow['queries_split'], true);
            foreach ($split as $key=>$value) {
                $dbName = $key;
                if(array_key_exists($key, $DATABASE_DNS_NAME_MAPPING))
                    $dbName = $DATABASE_DNS_NAME_MAPPING[$key];
                echo "<div><b>".$dbName."</b> : ".$value."</div>";
            }
        ?></td>
        <td><?php echo date("H:i:s",strtotime($realtimedatarow['log_time'])); ?></td>
        <?php if($dtype == ENT_DASHBOARD_TYPE_SLOWPAGES) {?>
        <?php } ?>

    </tr>	
    <?php
        }
    ?>
</table>