<div class ='scrollit'>
	<table border="1" style="border:1px solid #ccc;border-collapse: collapse;" align="center"  width="500">

	<?php 
		foreach ($leadDetails as $text => $value) { ?>
			<tr>
		        <th style="background-color:#909090; text-align:center"><?php echo $text?></th>
		        <th style ="text-align:center"><?php echo $value?></th>
		    </tr>
		<?php }
		
	?>

</table>

</div>