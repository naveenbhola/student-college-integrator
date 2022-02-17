<table cellpadding='0' cellspacing='0' width='<?php echo $smaller ? 810 : 840; ?>' <?php if($smaller) echo "style='background:#fff;'"; ?>>
    <tr>
        <th colspan='3' align='center'>
            <?php echo $tableName; ?>
            <div class='subth'><?php echo $tableMeta[$tableName]['desc']; ?></div>
        </th>
    </tr>
<?php    
$i = 1;
$columns = $tables[$tableName];
foreach($columns as $columnName => $columnData) {
?>        
    <tr>
        <td width='250' style='background:#f8f8f8; <?php if($tableMeta[$tableName]['fkeys'][$columnName] && !$smaller) echo "color:blue; font-weight:bold;"; else echo "font-weight:normal;"; ?> <?php if($i == count($columns)) echo "border-bottom:none;"; ?>'>
            <div style='word-wrap:break-word; width:240px;' id='<?php echo $tableName; ?>_<?php echo $columnName; ?>'>
            <?php if($columnData['key'] == 'PRI') { ?>
                <span style='text-decoration: underline; font-weight:bold; font-style:italic;'><?php echo $columnName; ?></span>
            <?php } else { ?>
            <?php echo $columnName; ?>
            <?php } ?>
            <?php if($tableMeta[$tableName]['fkeys'][$columnName] && !$smaller) { ?>
                <br /><a href='javascript:void(0); return false;' onclick="showTable('<?php echo $tableMeta[$tableName]['fkeys'][$columnName]; ?>','<?php echo $tableName; ?>_<?php echo $columnName; ?>')" onmousfeout="hideTable('<?php echo $tableMeta[$tableName]['fkeys'][$columnName]; ?>','<?php echo $tableName; ?>_<?php echo $columnName; ?>')" style='color:#6F89A3; font-size:11px; font-weight:normal;'>
                <?php echo $tableMeta[$tableName]['fkeys'][$columnName]; ?>
                </a>
            <?php } ?>
            </div>
        </td>
        
        <td width='150' style='color:#555; <?php if($i == count($columns)) echo "border-bottom:none;"; ?>'>
            <div style='word-wrap:break-word; width:140px;'>
                <?php echo $columnData['type']; ?>
            </div>
        </td>
        
        <td style='color:#555; border-right:none; <?php if($i == count($columns)) echo "border-bottom:none;"; ?>'>
            <span style='color:#777; font-size:12px; font-weight:normal;'>
                <?php echo $tableMeta[$tableName]['fieldDesc'][$columnName]; ?>
            </span>
        </td>
    </tr>
<?php    
        $i++;
    }
?>
</table>