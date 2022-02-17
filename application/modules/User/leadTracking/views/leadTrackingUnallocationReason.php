<table class="table table-striped">
    <thead>

<?php if($matching_exclusion){?>	
    <tr>
        <th>User Pick Time</th>
        <th>Unallocation Reason</th>
        <th>Stream</th>
        <th>Sub Stream</th>
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($unallocation_reason as $reason) { //_P($reason);
			?>
			<tr>
		        <td class="filterable-cell"><?php echo str_replace('T', ' ', $reason['_source']['user_pick_time'])?></td>
		        <td class="filterable-cell"><?php echo $reasonMappingArray[$reason['matched_queries'][0]]?></td>
		        <!-- <th style ="text-align:center"><?php //echo json_decode($reason['_source']['profile'],true)['streamId'];?></th>
		        <th style ="text-align:center"><?php //echo json_decode($reason['_source']['profile'],true)['subStreamId'];?></th> -->

		        <td class="filterable-cell"><?php echo $reason['_source']['stream_id'];?></td>
		        <td class="filterable-cell"><?php echo $reason['_source']['substream_id'];?></td>

		    </tr>
		<?php }?>
   
<?php }?>

	<?php if($picked_exclusion ){?>	
		<tr>
			<!-- <tr style="background-color:#909090;"> -->
	        <!-- <td  style ="text-align:center;"><span>Reason Flag</span></td>
	        <td style ="text-align:center;"><span>Reason Value</span></td> -->
	         <th>User Pick Time</th>
        	<th>Unallocation Reason</th>
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
		        <th class="filterable-cell <?php if($update_color_flag){echo 'text-danger';}?>"><?php echo $key; ?></td>
		        <th class="filterable-cell <?php if($update_color_flag){echo 'text-danger';}?>"><?php echo $reason_value; ?></td>
		    </tr>
		<?php }
		}
	?>

    </tbody>
    
</table>

<style type="text/css">
	table {
            width: 100%;
        }

    tbody {
            height: 320px;
            overflow-y: auto;
        }

        thead th {
            height: 30px;

            //text-align: left;
        }

    tr:after {
            content: ' ';
            display: block;
            visibility: hidden;
            clear: both;
        }
        thead, tbody, tr, td, th { display: block; text-align: center;}

        tbody td, thead th {
            width: 24.2%;
            float: left;
        }

</script>