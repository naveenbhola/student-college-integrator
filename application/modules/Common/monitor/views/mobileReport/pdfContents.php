<html>
    <head>
        <style>
            .reportList{list-style: none; padding: 10px}
            .reportList li{margin-bottom:10px; padding-bottom:10px; border-bottom: 1px dashed #ccc;}
            .reportBlock {}
            .graphHeading{font-weight: normal; font-size:18px; color: #666; margin-bottom: 10px;}
            .graphImage{width: 710px; padding:5px; background: #eee;}
            .graphImage img{width: 700px; height: 220px;}
            .graphTableData{width: 100%;}
            .graphTableData table{border:1px solid #ccc; margin-top:7px; width:710px; font-size: 12px; color: #333;}
            .graphTableData table tr th{background: #eee;font-weight: bold;}
            .graphTableData table tr th, .graphTableData table tr td {padding: 4px; border: 1px solid #ccc; text-align: center}
        </style>
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("ebrochureAbroad"); ?>" type="text/css" rel="stylesheet" />
    </head>
    <body>

        <ul class="reportList">
            <?php
            $count =0;
            foreach($pdfData as $pageName=>$values){
                $count++;
                ?>
            <li>
                <div class="reportBlock">
                    <div class="graphHeading">
                        <span style="font-size: small;">
                            <b><?= $count;?>. <?= $pageName;?></b>
                        </span>
                    </div>
                    <div class="graphImage"><img src="<?php echo MEDIADATA_INTERNAL_DOMAIN.$values['imgPath'];?>"/></div>
                    
                    <div class="graphTableData">
                        <table>
                            <tr>
                                <th>Page Views</th>
                                <!--<th>Avg. Page Load Time</th>
                                <th>Avg. Head Recieved</th>-->
                                <th>Avg. Server Processing Time</th>
                            <tr>
                                <td><?= $values['View Count'];?></td>
                                <!--<td><?= '';//$values['Avg. Page Load Time'];?></td>
                                <td><?= '';//$values['Avg. Head Load Time'];?></td>-->
                                <td><?= $values['Avg. Server Processing Time'];?></td>
                            </tr>
                        </table>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </li>
            <?php } ?>
        </ul>
    </body>
</html>