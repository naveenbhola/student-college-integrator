<table class="jsErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
    <tr>
        <th width="5%">S No.</th>
        <th width="15%"><?php echo $titleText;?> Msg</th>
        <th width="15%">JS File Path</th>
        <th width="5%">Line No.</th>
        <th width="5%">Column No.</th>
        <th width="20%">URL</th>
        <th width="12%">Exception</th>
        <th width="10%">userAgent</th>
        <th width="5%">Mobile</th>
        <th width="8%">Time</th>

    </tr>
    <?php
        if(empty($todaysTopJSErrors)){
            echo "<tr><td colspan=7><i>No Rows Found !!!</i></td></tr>";
        }
        foreach($todaysTopJSErrors as $key=>$todaysTopJSErrorsRow){
    ?>
        <tr>
        <td valign='top'><?php echo ($key+1);?>.</td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['msg'];?></td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['file']?></td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['line_num']?></td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['col_num']?></td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['url']?></td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['exception']?></td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['userAgent']?></td>
        <td valign='top'><?php echo $todaysTopJSErrorsRow['isMobile'];?></td>
        <td valign='top'><?php echo date('H:i:s', strtotime($todaysTopJSErrorsRow['addition_date']))?></td>
    </tr>	
    <?php
        }
    ?>
</table>