<?php
if(isset($result)) {
    $sortClause = '';
    if($sorter == 'occurrence') {
    $sortClause = 'ORDER BY `count` DESC';
    }   
    else if($sorter == 'numrows') {
        $sortClause = 'ORDER BY `avgRows` DESC';
    }

    
    if(count($result) > 0) {
        $j = 1;
        
        echo "<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
    <tr style='background: none repeat scroll 0 0 #f7f7f7;'><th width='50'>#</th><th width='600'>Query</th><th width='80'>Server</th><th width='60'>Count</th><th width='120'>Time (Avg/Total)</th><th width='140'>Rows (Avg/Total)</th></tr>";
        foreach ($result as $row) {
            
            $qid = $row['id'];
            $background_color = "";
            if($j % 2 == 0){
                $background_color = "background-color:#F3FAF2";
            }

            echo "<tr style='".$background_color.";'>";
            echo "<td valign='top'>".$j."</td>";
            echo "<td><div id='cm".$qid."' style='width:680px;  word-wrap:break-word; font-size:11px; line-height:140%; font-family:monospace; color:#333;'>".$row['query']."</div>";
            //echo "<div id='qcomment".$qid."' style='background:#FAFF9E; padding:8px; margin:15px 0 10px 0; color:#444; font-size:12px; ".($row['comment'] ? "" : "display:none;")."'>".$row['comment']."</div>";    
            echo "</td>";
            echo "<td valign='top'>".$row['server']."</td>";
            echo "<td valign='top'>".$row['count']."</td>";
            echo "<td valign='top'>".$row['avgTime']." / ".$row['totalTime']."</td>";
            //echo "<td valign='top'>".$result['totalTime']."</td>";
            echo "<td valign='top'>".$row['avgRows']." / ".$row['totalRows']."</td>";
            //echo "<td valign='top'><div style='opacity:0.5'><a href='javascript:void(0);' onclick='showOverlay(".$qid.")'><img src='/public/images/performance/comment.png'/></a></div></td>";
            echo "</tr>";
            
            //echo "<tr><td></td><td colspan='4' style='background:#fafafa; color:#777'>This is fixed by Vikas. Required some code changes.</td></tr>";
            
            $j++;
        }
        echo "</table>";
    }
    else {
        echo "No results found";
    }
}
else {
    echo "<div style='text-align:center; font-size:24px; color:#bbb; margin-top:100px;'>Please select filters.</div>";
}
?>