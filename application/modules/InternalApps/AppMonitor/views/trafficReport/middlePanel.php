<?php
if(isset($result)) {
    
    if(count($result) > 0) {
        $j = 1;
        echo "<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>";
        echo "<tr style='background: none repeat scroll 0 0 #f7f7f7;'>";
        
        if($reportType == 'IP') {
            echo "<th width='50'>#</th><th width='200'>IP</th><th width='300'>ISP</th><th width='300'>Organization</th><th width='100'>Count</th><th width='100'>Drill down</th><th width='100'>Requests</th></tr>";
        }    
        else if($reportType == 'userAgent') {
            echo "<th width='30'>#</th><th width='800'>User Agent</th><th width='100'>Count</th><th width='100'>Drill down</th><th width='100'>Requests</th></tr>";
        }
        else if($reportType == 'ISP') {
            echo "<th width='30'>#</th><th width='800'>ISP</th><th width='100'>Count</th><th width='100'>Drill down</th><th width='100'>Requests</th></tr>";
        }
        else if($reportType == 'organization') {
            echo "<th width='30'>#</th><th width='800'>Organization</th><th width='100'>Count</th><th width='100'>Drill down</th><th width='100'>Requests</th></tr>";
        }
        echo "</tr>"; 
            
        foreach ($result as $row) {
            
            $qid = $row['id'];
            $background_color = "";
            if($j % 2 == 0){
                $background_color = "background-color:#F3FAF2";
            }

            echo "<tr style='".$background_color.";'>";
            echo "<td valign='top'>".$j."</td>";
            
            if($reportType == 'IP') {
                echo "<td>".$row['ip']."";
                echo "<td>".$row['ISP']."";
                echo "<td>".$row['organization']."";
            }
            else if($reportType == 'userAgent') {
                echo "<td>".$row['userAgent']."";
            }
            else if($reportType == 'ISP') {
                echo "<td>".$row['ISP']."";
            }
            else if($reportType == 'organization') {
                echo "<td>".$row['organization']."";
            }
            
            echo "</td>";
            echo "<td valign='top'>".$row['count']."</td>";
            
            if($reportType == 'IP') {
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"drilldownTrafficReport('IP', '".$row['ip']."', '".$fromDate."', '".$toDate."')\">View</a></div></td>";
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"TrafficRequests('IP', '".$row['ip']."', 1)\">View</a></div></td>";
            }
            else if($reportType == 'userAgent') {
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"drilldownTrafficReport('userAgent', '".$row['userAgent']."', '".$fromDate."', '".$toDate."')\">View</a></div></td>";
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"TrafficRequests('userAgent', '".$row['userAgent']."', 1)\">View</a></div></td>";
            }
            else if($reportType == 'ISP') {
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"drilldownTrafficReport('ISP', '".$row['ISP']."', '".$fromDate."', '".$toDate."')\">View</a></div></td>";
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"TrafficRequests('ISP', '".$row['ISP']."', 1)\">View</a></div></td>";
            }
            else if($reportType == 'organization') {
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"drilldownTrafficReport('organization', '".$row['organization']."', '".$fromDate."', '".$toDate."')\">View</a></div></td>";
                echo "<td valign='top'><a href='javascript:void(0);' onclick=\"TrafficRequests('organization', '".$row['organization']."', 1)\">View</a></div></td>";
            }
            echo "</tr>";
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
