<table class="exceptionErrorTable" width='1170' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
    <tr>
        <th width="5%">S No.</th>
        <th width="20%">Solr Url</th>
        <th width="20%">Solr Error Msg</th>
        <th width="15%">Module/Controller</th>
        <th width="5%">Team</th>
        <th width="12%">URL</th>
        <th width="5%">Mobile</th>
        <th width="7%">Time</th>
        <th width="8%">Complete Response</th>
    </tr>
    <?php
        if(empty($todaysTopExceptions)){
            echo "<tr><td colspan=9><i>No Rows Found !!!</i></td></tr>";
        }
        foreach($todaysTopExceptions as $key=>$todaysTopExceptionsRow){
    ?>
        <tr>
        <td valign='top'><?php echo ($key+1);?>.</td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['solrURL'];?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['msg'];?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['module_name']." / ".$todaysTopExceptionsRow['controller_name']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['team']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['pageURL']?></td>
        <td valign='top'><?php echo $todaysTopExceptionsRow['isMobile'] ? 'Yes' : 'No'?></td>
        <td valign='top'><?php echo date('H:i:s', strtotime($todaysTopExceptionsRow['time']))?></td>
        <td valign='top'><a href="javascript:void(0);" onclick="showDetails('/AppMonitor/SolrErrors/getErrorResponseDetails', <?php echo $todaysTopExceptionsRow['id'];?>);return false;">More Details</a></td>
    </tr>	
    <?php
        }
    ?>
</table>