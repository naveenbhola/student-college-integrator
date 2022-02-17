	<?php if(isset($instituteInfo[0]['instituteInfo'][0]['institute_name'])):
			if(array_key_exists('alt_image_header', $config_array[$courseId])) {
				$alt_image_header = $config_array[$courseId]['alt_image_header'];	
			}
		?>
        <div class="appsFormHeaderChild">
	    <div class="appsDetails2">
        <div class="appsLogo2" style="height:70px!important">
            <?php 
               $imageLogo = ($instituteList != '' && is_object($instituteList[$institute_id])) ? $instituteList[$institute_id]->getLogoUrl() : '';
                if($imageLogo == '') {
                    $imageLogo = '/public/images/recommendation-default-image.jpg';
                }
            ?>
        <img style="width:100%;height:100%" src="<?php echo $imageLogo; ?>" alt="<?php echo $alt_image_header;?>" />
        </div>
	    <div class="collegeDetails">
        <h1><?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name']; ?> Application Form</h1>
		<?php
			$date = strtotime($instituteInfo[0]['instituteInfo'][0]['last_date']);
			$last_date = date('d-m-Y',$date);
		?>
        <?php if($instituteInfo[0]['instituteInfo'][0]['institute_id']!='35413' && $instituteInfo[0]['instituteInfo'][0]['institute_id']!='35407'){ ?>
		<div class="formNumb2" style="margin-top:9px;">
		    Last Date to Apply:<br /><strong><?php echo $last_date;?></strong>
		</div>
        <?php } ?>
	    </div>
        </div>
        </div>
	<?php endif; ?>
