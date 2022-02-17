<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
    <tr>
        <th width="5%">S No.</th>
        <th width="25%">Query</th>
        <th width="10%"><?php echo $titleText;?> Msg</th>
        <th width="10%">Source File</th>
        <th width="5%">Line No.</th>
        <th width="15%">URL</th>
        <th width="10%">Referrer</th>
        <th width="5%">Mobile</th>
        <th width="8%">Time</th>
    </tr>
    <?php
        if(empty($todaysTopExceptions)){
            echo "<tr><td colspan=7><i>No Rows Found !!!</i></td></tr>";
        }
        foreach($todaysTopExceptions as $key=>$todaysTopExceptionsRow){
    ?>
        <tr>
        <td valign='top'><?php echo ($key+1);?>.</td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['query'];?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['msg'];?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['file']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['line_num']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['url']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['referrer']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['isMobile'] ? 'Yes' : 'No'?></td>
        <td valign='top'><?php echo date('H:i:s', strtotime($todaysTopExceptionsRow['addition_date']))?></td>
    </tr>	
    <?php
        }
    ?>
</table>