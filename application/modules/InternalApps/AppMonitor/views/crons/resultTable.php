<?php
        echo "<table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
            <tr style='background: none repeat scroll 0 0 #f7f7f7;'><th width='30'>#</th><th width='200'>Cron</th><th width='440'>Error</th><th width='100'>File</th><th width='50'>Line</th><th width='80'>Team</th><th width='70'>Time</th></tr>";
            
    if(count($result) > 0) {
        $j = 1;
        
        foreach ($result as $row) {
            
            $qid = $row['id'];
            $background_color = "";
            if($j % 2 == 0){
            	$background_color = "background-color:#F3FAF2";
            }

            echo "<tr style='".$background_color.";'>";
            echo "<td valign='top'><div style='word-wrap:break-word; width:30px; line-height:140%;'>".$j."</div></td>";
            echo "<td valign='top'><div style='word-wrap:break-word; width:200px;'>".$row['cron']."</div></td>";
            
            echo "<td valign='top'><div style='word-wrap:break-word; width:440px; line-height:140%;'>";
            
            if(strlen($row['error']) > 200) {
?>	
                <a href='#' onclick="$('#elps<?php echo $j; ?>').hide(); $('#fullq<?php echo $j; ?>').show(); return false;" style="color:#333; text-decoration:none;">
<?php				
            }
            echo substr($row['error'],0,200);
            if(strlen($row['error']) > 200) {
                echo "</a>";
                echo "<span id='elps".$j."'>...</span>";
                echo "<span id='fullq".$j."' style='display:none;'>".(substr($row['error'],200))."</span>";
            }
        
            echo "</div>";
            
            echo "</td>";
            //echo "<td valign='top'>".$row['error']."</td>";
         	echo "<td valign='top'>";
            if(strpos($row['file'],"Filename: /var/www/html/app/releases/") === 0) {
                echo substr($row['file'],37);    
            }
            else if(strpos($row['file'],"/var/www/html/app/releases/") === 0) {
                echo substr($row['file'],27);    
            }
            echo "</td>";
		
            echo "<td valign='top'><div style='word-wrap:break-word; width:50px; line-height:140%;'>";
            $lineParts = explode(':',$row['line']);
            if(count($lineParts) > 1) {
                echo trim($lineParts[1]);
            }
            else {
                echo trim($lineParts[0]);
            }
            echo "</div></td>";
            
			echo "<td valign='top'><div style='word-wrap:break-word; width:80px; line-height:140%;'>".$row['team']."</div></td>";
			echo "<td valign='top'><div style='word-wrap:break-word; width:70px; line-height:140%;'>";
			if($detailedReport) {
				echo date('Y-m-d H:i:s', strtotime($row['time']));
			}
			else {
				echo date('H:i:s', strtotime($row['time']));
			}
		    echo "</div></td>";
			
            echo "</tr>";
            
            //echo "<tr><td></td><td colspan='4' style='background:#fafafa; color:#777'>This is fixed by Vikas. Required some code changes.</td></tr>";
            
            $j++;
        }
        echo "</table>";
    }
    else {
        echo "<tr><td colspan='7' style='font-size:16px;color:green;font-weight:bold; padding:20px 0' align='center' >No results found.</td></tr>";
    }