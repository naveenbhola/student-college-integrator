<?php
echo "<h1></h1>";
foreach($reports as $reportType => $reportData) {
    echo "<h2>Top ".$reportMap[$reportType]['heading']."</h2>";
    $j = 1;        
    
    if($reportType == 'IP') {
        echo "<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc; margin-bottom:20px;' width='860'>
        <tr style='background: none repeat scroll 0 0 #f7f7f7;'><th width='50'>#</th><th width='200'>IP</th><th width='200'>ISP</th><th width='200'>Organization</th><th width='160'>Count</th></tr>";
        foreach ($reportData as $val => $cols) {
            
            $qid = $row['id'];
            $background_color = "";
            if($j % 2 == 0){
                $background_color = "background-color:#F3FAF2";
            }

            echo "<tr style='".$background_color.";'>";
            echo "<td valign='top'>".$j."</td>";
            echo "<td>".$val."</td>";
            echo "<td>".$cols['ISP']."</td>";
            echo "<td>".$cols['organization']."</td>";
            echo "<td>".$cols['count']."</td>";
            echo "</tr>";
            $j++;
        }
        echo "</table>";
    }
    else {
        echo "<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc; margin-bottom:20px;' width='860'>
        <tr style='background: none repeat scroll 0 0 #f7f7f7;'><th width='50'>#</th><th width='500'>".$reportMap[$reportType]['column']."</th><th width='260'>Count</th></tr>";
        foreach ($reportData as $val => $count) {
            
            $qid = $row['id'];
            $background_color = "";
            if($j % 2 == 0){
                $background_color = "background-color:#F3FAF2";
            }

            echo "<tr style='".$background_color.";'>";
            echo "<td valign='top'>".$j."</td>";
            echo "<td>".$val."";
            echo "</td>";
            echo "<td valign='top'>".$count."</td>";
            echo "</tr>";
            $j++;
        }
        echo "</table>";
    }
}

