<div id="rmConfirmationContainer" style="padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px; min-height: 500px;">
    <div class="orangeColor fontSize_14p bld" style="width: 950px; font-size: 22px;">
        <span><b>Confirm Response Migration</b></span><br>
    </div>
    <div style='background:#fafafa; padding:5px 0 20px 0; margin-top: 10px;'>
        <div style='width:900px; margin:0 auto;'>
            <table class='rmTable' cellpadding='0' cellspacing='0'>
                <tr>
                    <th width='120'>Listing Type</th>
                    <th width='150'>Old Listing ID<br/>(Already deleted)</th>
                    <th width='150'>New Listing ID</th>
                    <th width='100'>Is Valid</th>
                    <th width='400'>Error</th>
                </tr>
                <?php for($i=0;$i<10;$i++) { ?>
                <?php //$bgStyle = "style='background:".($dataValidation[$i]['isValid'] ? '#DCF5DC' : '#F5DCDC')."'"; ?>
                <tr>
                    <td><?php echo $dataValidation[$i]['listingType']; ?></td>
                    <td><?php echo $dataValidation[$i]['oldListingId']; ?></td>
                    <td><?php echo $dataValidation[$i]['newListingId']; ?></td>
                    <td <?php echo $bgStyle; ?>>
                    <?php if($dataValidation[$i]['isValid'] != 'Blank') { ?>
                        <img src='/public/images/<?php echo $dataValidation[$i]['isValid'] == 'Yes' ? 'check.png' : 'close-icn.png'; ?>' />
                    <?php } ?>
                    </td>
                    
                    <td style='color:red; font-size: 12px;'><?php echo implode('<br />',$dataValidation[$i]['errors']); ?></td>
                </tr>
                <?php } ?>
            </table>
            <br />
            <input type="button" value="Confirm Migrate Responses" title="Migrate Responses" class="orange-button flLt" style='margin-left:200px; margin-top: 10px;' onclick="confirmMigration()">
            <div style='float:left; font-size:16px; margin:16px 0 0 30px;'>
            <a href='javascript:void(); return false;' onclick="cancelAndEdit();">Cancel and edit migration data</a>
            </div>
            <div style='clear: both;'></div>
            <div style='clear: both;'></div>
            <br />
        </div>
    </div>
</div>
<script>
    function cancelAndEdit()
    {
        document.getElementById('rmConfirmationContainer').style.display = 'none';
        document.getElementById('rmFormContainer').style.display = 'block';
    }
    function confirmMigration()
    {
        document.getElementById('migrationConfirmed').value = 'yes';
        document.getElementById('rmForm').submit();
    }
</script>