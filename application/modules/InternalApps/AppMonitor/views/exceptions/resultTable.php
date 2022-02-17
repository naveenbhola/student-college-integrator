<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
    <tr>
        <th width="3%">S No.</th>
        <th width="20%"><?php echo $titleText;?> Msg</th>
        <th width="10%">Source File</th>
        <th width="4%">Line No.</th>
        <th width="15%">URL</th>
        <th width="10%">Referrer</th>
        <th width="5%">Mobile</th>
    <th width="7%">Server</th>
        <th width="6%">Time</th>
    <th width="5%">Stack Trace</th>
    </tr>
    <?php
        if(empty($todaysTopExceptions)){
            echo "<tr><td colspan=7><i>No Rows Found !!!</i></td></tr>";
        }
        foreach($todaysTopExceptions as $key=>$todaysTopExceptionsRow){
    ?>
        <tr>
        <td valign='top'><?php echo ($key+1);?>.</td>
        <td valign='top'><?php echo html_escape($todaysTopExceptionsRow['msg']);?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['file']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['line_num']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['url']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['referrer']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['isMobile'] ? 'Yes' : 'No'?></td>
    <td valign='top'><?php echo $todaysTopExceptionsRow['server']?></td>
        <td valign='top'><?php echo date('H:i:s', strtotime($todaysTopExceptionsRow['addition_date']))?></td>
    <td valign='top'><a href='javascript:void(0);' onclick="showErrorStackTrace('exception', <?php echo $todaysTopExceptionsRow['id'];?>)">View</a></td>
    </tr>   
    <?php
        }
    ?>
</table>

<script>
function showErrorStackTrace(type, errorid)
{
    $("body").addClass("noscroll");
    $("#voverlay").show();
    $("#vdialog_inner").css('top', $('body').scrollTop()+20);
    $("#vdialog").show();
    $("#vdialog_content").html("<div style='margin:50px; text-align:center;'><img src='/public/images/appmonitor/loader.gif' /></div>");
    $.ajax({
            data: { "id" : errorid, 'type' : type},
            url : "/AppMonitor/Exceptions/showStackTrace",
            cache : false,
            method : "POST",
            beforeSend : function(){
            }
        }).done(function(res){
            $("#vdialog_content").html(res);
        });
}
</script>
