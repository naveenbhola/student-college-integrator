<div style ="text-align:center;padding: 10px 0 10px;color:#003d4d;"><span><b>Search Agent Details</b></span></div>	
<div class =''>
	<table border="1" style="border:1px solid #ccc;border-collapse: collapse;" align="center" width="500">

	<?php 
		foreach ($searchAgentDetails as $key => $value) { ?>
			<tr>
		        <th style ="text-align:center"><?php echo $key;?></th>
		        <th style ="text-align:center"><?php echo $value;?></th>
		    </tr>
		<?php }
		
	?>
	</table>

</div>


<div style ="text-align:center;padding: 20px 0 5px;color:#003d4d;"><span style ="text-align:center"><b>Search Agent Criteria</b></span>	</div>

<div class ='scrollit'>

	<table border="1" style="border:1px solid #ccc;border-collapse: collapse;" align="center" width="500">
		
	<?php 
		foreach ($searchAgentCriteria as $criteria) { ?>
			<tr>
		        <th style ="text-align:center"><?php echo $criteria['keyname']?></th>
		        <th style ="text-align:center"><?php echo $criteria['value']?></th>
		    </tr>
		<?php }
		
	?>

</table>
	
</div>