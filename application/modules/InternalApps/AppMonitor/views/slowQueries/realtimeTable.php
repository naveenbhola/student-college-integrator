<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
    <tr style='background: none repeat scroll 0 0 #f7f7f7;'>
        <th width='50'>#</th>
        <th>Query</th>
        <th width='120'>Server</th>
        <th width='100'>Source</th>
        <th width='80'>Time</th>
        <th width='80'>Lock</th>
        <th width='100'> Sent</th>
        <th width='120'>Examined</th>
        <th width='130'>At</th>
    </tr>    
<?php
if(count($result) > 0) {
    $j = 1;
    foreach ($result as $row) {
        
        $qid = $row['id'];
        $background_color = "";
        if($j % 2 == 0){
            $background_color = "background-color:#F3FAF2";
        }
    
        echo "<tr style='".$background_color.";'>";
        echo "<td valign='top'>".$j."</td>";
        echo "<td><div id='cm".$qid."' style='width:580px;  word-wrap:break-word; font-size:11px; line-height:140%; font-family:monospace; color:#333;'>";
        
        if(strlen($row['query']) > 200) {
    ?>	
            <a href='#' onclick="$('#elps<?php echo $j; ?>').hide(); $('#fullq<?php echo $j; ?>').show(); return false;" style="color:#333; text-decoration:none;">
    <?php				
        }
        echo substr($row['query'],0,200);
        if(strlen($row['query']) > 200) {
            echo "</a>";
            echo "<span id='elps".$j."'>...</span>";
            echo "<span id='fullq".$j."' style='display:none;'>".(htmlentities(substr($row['query'],200)))."</span>";
        }
    
        echo "</div>";
        
        echo "</td>";
        echo "<td valign='top'>".$row['host']."</td>";
        echo "<td valign='top'>".end(explode('.',$row['source']))."</td>";
        echo "<td valign='top'>".$row['query_time']."</td>";
        echo "<td valign='top'>".$row['lock_time']."</td>";
        echo "<td valign='top'>".$row['rows_sent']."</td>";
        echo "<td valign='top'>".$row['rows_examined']."</td>";
        echo "<td valign='top'>".date('H:i:s',strtotime($row['record_time']))."</td>";
        echo "</tr>";
        $j++;
    }
    echo "</table>";
}
else {
    echo "<tr><td colspan='9' style='font-size:16px;color:green;font-weight:bold; padding:20px 0;' align='center' >No results found.</td></tr>";
}