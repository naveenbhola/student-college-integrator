	<?php if(isset($instituteInfo[0]['instituteInfo'][0]['institute_name'])):
			if(array_key_exists('alt_image_header', $config_array[$courseId])) {
				$alt_image_header = $config_array[$courseId]['alt_image_header'];	
			}
		?>
        <div class="appsFormHeaderChild">
	    <div class="appsDetails2">
        <div class="appsLogo2"><img src="<?php echo $instituteInfo[0]['instituteInfo'][0]['logo_link']; ?>" alt="<?php echo $alt_image_header;?>" /></div>
	    <div class="collegeDetails">
        <?php
            /*$str = $instituteInfo[0]['instituteInfo'][0]['sessionYear'];
            if($courseId=='12873'){
                    $str = 'PGDM (C) 2015-2017';
            }*/
         ?>
        <h1><?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name']; 
        	if ($instituteInfo[0]['instituteInfo'][0]['courseId'] == 7544){
        		echo "<br>"." MBA Admissions 2019 -";
        	}
        	?> 
    		Application Form</h1>
                <?php $date = strtotime($instituteInfo[0]['instituteInfo'][0]['last_date']);
		      $last_date = date('d-m-Y',$date);
		?>
        <?php if($instituteInfo[0]['instituteInfo'][0]['institute_id']!='35413' && $instituteInfo[0]['instituteInfo'][0]['institute_id']!='35407'){ ?>
		<div class="formNumb2">
		    Last Date to Apply:<br /><strong><?php echo $last_date;?></strong>
		</div>
        <?php } ?>
	    </div>
        </div>
        </div>
	<?php endif; ?>
