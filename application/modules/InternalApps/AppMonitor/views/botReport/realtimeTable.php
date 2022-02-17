<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
    <tr style='background: none repeat scroll 0 0 #f7f7f7;'>
        <th width='50'>#</th>
        <th width='120'>IP</th>
        <th>User Agent</th>
        <th width='120'>Status</th>
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
        echo "<td valign='top'>".$row['ip']."</td>";
        echo "<td valign='top'><div style='width:600px; word-wrap:break-word;'>".$row['useragent']."</div></td>";
        echo "<td valign='top'>".$statuses[$row['status']]."</td>";
        echo "<td valign='top'>".date('H:i:s',strtotime($row['created']))."</td>";
        echo "</tr>";
        $j++;
    }
    echo "</table>";
}
else {
    echo "<tr><td colspan='9' style='font-size:16px;color:green;font-weight:bold; padding:20px 0;' align='center' >No results found.</td></tr>";
}
