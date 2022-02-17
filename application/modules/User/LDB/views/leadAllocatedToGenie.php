<div class ='scrollit'>
	<table border="1" style="border:1px solid #ccc;border-collapse: collapse;" align="center" width="500">

		<tr style="background-color:#909090;">
	        <th  style ="text-align:center"><span>User Id</span></th>
	        <th style ="text-align:center"><span>Allocation Time</span></th>
	    </tr>
		
	<?php //_P($matchingLog);
		foreach ($allocatedLeads as $lead) { ?>
			<tr>
		        <th style ="text-align:center"><?php echo $lead['userid'];?></th>
		        <th style ="text-align:center"><?php echo $lead['allocationtime'];?></th>
		    </tr>
		<?php }
		
	?>

</table>

</div>