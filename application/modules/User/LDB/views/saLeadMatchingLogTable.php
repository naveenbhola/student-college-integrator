<div  style ="text-align:center">
	<span><p><b>Total  no of entries : <?php echo count($matchingLog);?></b></p></span>
</div>
<div class ='scrollit'>
	<table border="1" style="border:1px solid #ccc;border-collapse: collapse;" align="center" width="500">

		<tr style="background-color:#909090;">
	        <th  style ="text-align:center"><span>Search Agent Id</span></th>
	        <th style ="text-align:center"><span>Client Id</span></th>
	        <th style ="text-align:center"><span>Matching Time</span></th>
	    </tr>
		
	<?php 
		foreach ($matchingLog as $logObj) { ?>
			<tr>
		        <th style ="text-align:center"><?php echo $logObj->agentid;?></th>
		        <th style ="text-align:center"><?php echo $logObj->clientid;?></th>
		        <th style ="text-align:center"><?php echo $matchingTime;?></th>
		    </tr>
		<?php }
		
	?>

</table>

</div>