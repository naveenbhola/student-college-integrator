<?php $overLayDetails = $examSettingsArray['overDetails'];  
?>
<table border="1" cellpadding="3" cellspacing="0" style="width: 100%; border-collapse: collapse; ">
        <tbody>
                <tr style="height: 38.75pt; background: #e3e2e2;">
                        <td style="width:50%" valign="top">
                            <p><strong><?php echo $overLayDetails['markHeading'];?></strong></p>
                        </td>
                        <td style="width:50%" valign="top">
                            <p><strong><?php echo $overLayDetails['rankHeading'];?></strong></p>
                        </td>
                </tr>
                <?php foreach ($overLayDetails['mapping'] as $mappingMarks => $mappingRank):?>
                <tr>
                        <td valign="top" >
                            <p><?php echo $mappingMarks; ?></p>
                        </td>
                        <td valign="top">
                            <p><?php echo $mappingRank;?></p>
                        </td>
                </tr>
                
                <?php endforeach;?>
        </tbody>
</table>

<?php if(!empty($overLayDetails['notice'])):?>
<span style="margin-top: 10px;font-size: 0.8em;"><?php echo $overLayDetails['notice'];?></span>
<?php endif;?>

