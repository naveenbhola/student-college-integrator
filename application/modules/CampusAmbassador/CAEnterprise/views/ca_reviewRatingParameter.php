<tr>
	<td colspan="4">
	<table style="margin-left:180px">
	<tr>
	<td align="right" ></td>
	<td><strong>Ratings</strong></td>
	<td></td>
	</tr>
	
	<?php foreach ($ratingValue['ratingParam'] as $key => $value) {?>
	<tr>
	  <td align="right" ></td>
	  <td width="200px"><?php echo $value['description'];?>:</td>
	  <td><?php echo $value['rating'];?>/5</td>
	</tr>
	
	<?php }?>

	</table>
	</td>
</tr>