<?php
if(isset($result)) {
    
    if(count($result) > 0) {
        echo "<h1 style='margin:20px 0;'>".$clauseValue.($extraHeading ? " (".$extraHeading.")" : "")."</h1>";
        $j = $offset+1;
        echo "<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc; margin-bottom:20px;' width='1180'>";
        echo "<tr style='background: none repeat scroll 0 0 #f7f7f7;'>";
        
        echo "<th width='50'>#</th><th width='200'>Time</th><th width='200'>Method</th><th width='900'>URL</th></tr>";
        
        echo "</tr>"; 
            
        foreach ($result as $row) {
            
            $qid = $row['id'];
            $background_color = "";
            if($j % 2 == 0){
                $background_color = "background-color:#F3FAF2";
            }

            echo "<tr style='".$background_color.";'>";
            echo "<td valign='top'>".$j."</td>";
            
            echo "<td>".$row['datetime']."</td>";
            echo "<td>".$row['method']."</td>";
            echo "<td>".$row['url']."</td>";
        
            echo "</tr>";
            $j++;
        }
        echo "</table>";
        if($nextPageNumber) {
            echo "<div style='float:right;'><a href='#'  onclick=\"TrafficRequests('".$clauseType."', '".$clauseValue."', '".$nextPageNumber."')\">Next</a></div>";
        }
        if($previousPageNumber) {
            echo "<div style='float:right; margin-right:20px;'><a href='#' onclick=\"TrafficRequests('".$clauseType."', '".$clauseValue."', '".$previousPageNumber."')\">Previous</a></div>";
        }
        echo "<div style='clear:both;'></div>";
    }
    else {
        echo "No results found";
    }
}
else {
    echo "<div style='text-align:center; font-size:24px; color:#bbb; margin-top:100px;'>Please select filters.</div>";
}
?>
