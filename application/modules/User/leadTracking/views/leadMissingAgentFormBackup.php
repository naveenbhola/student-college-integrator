
<div class ='scrollit' style="overflow-x: hidden;">
	<table border="1" style="border:1px solid #ccc;border-collapse: collapse;" align="center" width="500">

	<?php if($matching_exclusion){?>	
	<thead style="border-collapse: collapse;">
		<tr style="background-color:#909090;display: block;position: relative;">
	        <th  style ="text-align:center;"><span>User Pick Time</span></th>
	        <th style ="text-align:center;"><span>Unallocation Reason</span></th>
	        <th style ="text-align:center;"><span>Stream</span></th>
	        <th style ="text-align:center;"><span>Sub stream</span></th>
	    </tr>
	</thead>	
	<tbody style="display: block;width: 100%;height: 200px;overflow-y: scroll;overflow-x: hidden;">
	<?php 
		foreach ($unallocation_reason as $reason) { //_P($reason);
			?>
			<tr>
		        <td style ="text-align:center;"><?php echo str_replace('T', ' ', $reason['_source']['user_pick_time'])?></td>
		        <td style ="text-align:center;"><?php echo $reasonMappingArray[$reason['matched_queries'][0]]?></td>
		        <!-- <th style ="text-align:center"><?php //echo json_decode($reason['_source']['profile'],true)['streamId'];?></th>
		        <th style ="text-align:center"><?php //echo json_decode($reason['_source']['profile'],true)['subStreamId'];?></th> -->

		        <td style ="text-align:center;"><?php echo $reason['_source']['stream_id'];?></td>
		        <td style ="text-align:center;"><?php echo $reason['_source']['substream_id'];?></td>

		    </tr>
		<?php }
		}
	?>

	<?php if($picked_exclusion ){?>	
		<tr style="background-color:#909090;">
	        <td  style ="text-align:center;"><span>Reason Flag</span></td>
	        <td style ="text-align:center;"><span>Reason Value</span></td>
	    </tr>
		
	<?php 

		foreach ($unpicked_reasons as $key => $reason_value) { 
			$update_color_flag = false;
			
			if( ($key == 'exclusion_id' || $key == 'hardbounce' || $key == 'ownershipchallenged'|| $key == 'abused'|| $key == 'softbounce') && $reason_value>0 ){
				$update_color_flag = true;
			}

			if(($key == 'is_processed' || $key == 'isLDBUser') && strtoupper($reason_value) == 'NO'){
				$update_color_flag = true;
			}

			if(($key == 'mobileverified' ) && $reason_value <1 ){
				$update_color_flag = true;
			}

			if(($key == 'isTestUser' ) && strtoupper($reason_value) == 'YES'){
				$update_color_flag = true;
			}

			if(($key == 'TimeOfStart' ) && $reason_value > (date('Y-m-d', strtotime('+1 year'))) ){
				$update_color_flag = true;
			}


			?>
			<tr>
		        <td style ="text-align:center; <?php if($update_color_flag){echo 'color:red';} ?>"><?php echo $key; ?></td>
		        <td style ="text-align:center; <?php if($update_color_flag){echo 'color:red';} ?>"><?php echo $reason_value; ?></td>
		    </tr>
		<?php }
		}
	?>
</tbody>

</table>

</div>


<style type="text/css">
	.scrollit table thead th:nth-child(1), .scrollit table tbody td:nth-child(1){
         min-width: 200px;
	}
	.scrollit table thead th:nth-child(2), .scrollit table tbody td:nth-child(2){
         min-width: 200px;
	}
	.scrollit table thead th:nth-child(3), .scrollit table tbody td:nth-child(3){
         min-width: 200px;
	}
	.scrollit table thead th:nth-child(4), .scrollit table tbody td:nth-child(4){
         min-width: 200px;
	}
	
</style>
