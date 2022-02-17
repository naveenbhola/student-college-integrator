<table class="layer-table1" style="margin:40px 0 8px 0">
        <tbody>
                <tr>
                        <th>
                            <?php echo $overDetails['markHeading'];?>
                        </th>
                        <th>
                            <?php echo $overDetails['rankHeading'];?>
                        </th>
                </tr>
		<?php foreach($overDetails['mapping'] as $key=>$value){?>
                <tr>
                        <td>
                            <?php echo $key;?>
                        </td>
                        <td>
                            <?php echo $value;?>
                        </td>
                </tr>
		<?php } ?>
        </tbody>
</table>

<?php if(!empty($overDetails['notice'])){ ?><span style="margin-top: 10px;font-size: 0.8em;"> <?php echo $overDetails['notice'];?></span><?php } ?>
