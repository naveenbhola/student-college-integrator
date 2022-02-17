<?php ?>
<p> Hi, </p>
<p> Please check following listings for errors :</p>
<?php 
$coulmnHeader = array_keys($errorEntries[0]); ?>
<table style="width:auto;border:1px solid black;border-collapse:collapse;">
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $column) {?>
<th style="border:1px solid black;border-collapse:collapse;padding:5px;"><?=$column; ?></th>
<?php }?>
</tr>
<?php foreach($errorEntries as $rowData) {?>
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $coulmn) {?>
<td style="border:1px solid black;border-collapse:collapse;padding:5px;"><?= is_array($rowData[$coulmn]) ? _p($rowData[$coulmn]): $rowData[$coulmn] ?></td>
<?php }?>
</tr>
<?php }?>
</table>
<p> --Listing Error Reporting. </p>