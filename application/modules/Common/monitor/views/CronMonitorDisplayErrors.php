<div id='cronErrorBox' style='background:#f7f7f7; margin:20px 20px 30px 20px; box-shadow: 0 0 5px #ccc; padding:0px;'>
    <table cellpadding='0' cellspacing='0' width='100%'>
        <tr>
            <th width='200'>Cron</th>
            <th>Error</th>
            <th width='140' style='border-right:none;'>Time</th>
        </tr>
        <?php $j = 1; foreach($errors[$errorIndex] as $error) { $borderStyle = ($j++ == count($errors[$errorIndex]) ? "border-bottom:none" : ""); ?>
        <tr>
            <td style="<?php echo $borderStyle; ?>;">
            <div style='width:180px; word-wrap: break-word;'>
            <?php
                $cronParts = explode(" ",$error['cron']);
                foreach($cronParts as $cronPart) {
                    if(strpos($cronPart,"--run=") === 0) {
                        echo substr($cronPart,7);
                    }
                }
            ?>
            </div>
            </td>
            <td style="<?php echo $borderStyle; ?>;">
                <?php echo $error['error']; ?>
                <div style='margin-top:10px; color:#777; font-style:italic;'>
                    <?php echo (strpos($error['file'],'Filename:') === 0 ? substr($error['file'],10) : $error['file']).", "; ?>
                    <?php
                        $lineParts = explode('Line Number:',$error['line']);
                        if(count($lineParts) == 1) { echo 'Line Number: '; }
                        echo $error['line'];
                    ?>
                </div>
            </td>
            <td style='border-right:none; <?php echo $borderStyle; ?>'><?php echo $error['time']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>