<div class ='scrollit'>
	<table border="1" style="border:1px solid #ccc;border-collapse: collapse;" align="center" width="500">

		<tr style="background-color:#909090;">
	        <th  style ="text-align:center"><span>Search Agent Id</span></th>
	        <th style ="text-align:center"><span>Allocation Time</span></th>
	    </tr>
		
	<?php 
		foreach ($searchAgentData as $searchAgent) { ?>
			<tr>
		        <th style ="text-align:center"><?php echo $searchAgent['agentid']?></th>
		        <th style ="text-align:center"><?php echo $searchAgent['allocationtime']?></th>
		    </tr>
		<?php }
		
	?>

</table>

</div>