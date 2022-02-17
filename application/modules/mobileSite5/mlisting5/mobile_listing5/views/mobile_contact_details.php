<?php 
		if($pageType=='course'){
				$locations = $course->getLocations();
				$location = $locations[$currentLocation->getLocationId()];
				if($location){
						$contactDetail = $location->getContactDetail();
				}
				if(!$contactDetail){
						$locations = $institute->getLocations();
						$location = $locations[$currentLocation->getLocationId()];
						$contactDetail = $location->getContactDetail();
				}
		}
		else{
				$locations = $institute->getLocations();
				$location = $locations[$currentLocation->getLocationId()];
				if($location){
						$contactDetail = $location->getContactDetail();
				}
		}
	
		if($contactDetail){ ?>
		<dt class="inst-details">
				<h2 class="ques-title">
				    <p>Contact Details</p>
				</h2>
		</dt>
		<dd class="contact-details" id itemscope itemtype="http://schema.org/CollegeOrUniversity">
		<?php
				if($contactDetail->getContactPerson()){
						echo "<p>Name of the Person: <span>".$contactDetail->getContactPerson()."</span></p>";
				}
				$email = $contactDetail->getContactEmail();
				if(!empty($email)){
						echo "<p>Email: <a href=mailto:'$email'>$email</a></p>";
				}
				$website = $contactDetail->getContactWebsite();
				if(!empty($website)){
						echo "<p>Website: <a href='http://$website' target='_blank' rel='nofollow' >$website</a></p>";
				}
				$address = $currentLocation->getAddress();
				if($address){ 
					$city= $currentLocation->getCity()->getName()!=''? $currentLocation->getCity()->getName():"";
					$state=$currentLocation->getState()->getName()!=''?$currentLocation->getState()->getName():"";
					$country=$currentLocation->getCountry()->getName()!=''?$currentLocation->getCountry()->getName():"";
					$geoParams1=$institute->getName()." ".$currentLocation->getCity()->getName()." ".$currentLocation->getCountry()->getName();			
					$geoParams1=urlencode($geoParams1);
					echo "<p>Address: <span><a href='http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=$geoParams1'>$address</a></span></p>";
				}

				if($contactDetail->getContactNumbers()){
					$contactNumbers = explode(',',$contactDetail->getContactNumbers());
					if(count($contactNumbers)<=1){ ?>
						<a id='listing_contact_widget' href='tel:<?=$contactNumbers[0]?>' onClick="logCallActivity('<?=$contactNumbers[0]?>');" class='button blue big'><i class='icon-mobile'></i> Call</a>
					<?php }
					else{ $flag='multiple';
						?>
						<p><a id="listing_contact_widget" href="#popupMenu" data-rel="popup" data-inline="true" data-transition="slideup" data-icon="gear" data-theme="e" class="button blue big"><i class="icon-mobile"></i> Call</a></p>
						<div data-role="popup" id="popupMenu" data-theme="d">						
							<ul data-role="listview" data-inset="true" style="min-width:200px; background: #fff;" data-theme="d">						
							    <li data-role="divider" data-theme="e">Call</li>
								<?php $i=0;
								foreach($contactNumbers as $no){ ?>
									<li ><a onclick='logCallActivity("<?=$no?>"); gaTrackForMultiplePhoneNumbers();'  href='tel:<?=$no?>'><?=$no?></a></li>
								        <?php $i++;
								}
								?>
							</ul>						
						</div>						
						<?php
					}
				}
				$this->load->view('requestEbrochureCoursePage_contactDetails');
				?>
		</dd>
		
	    <?php
	    }
	    ?>

<script>
var flagCall = 'false';
$(document).ready(function(){
    $("#listing_contact_widget").click(function(){
        if(flagCall!='true'){
    	<?php
    	if($institute_id && $pageType=='institute')
    	{
    		?>
                flagCall = 'true';
    		var listing_id  = "<?php echo $institute_id; ?>";
    	        processContactCount('institute',listing_id);
	<?php
    	}
    	else
    	{
    		?>
		flagCall='true';
    		var listing_id  = "<?php echo $course->getId(); ?>";
		 processContactCount('course',listing_id);
    		<?php
    	}
    	?>
        <?php  if($flag!='multiple'){ ?> 
              _gaq.push(['_trackEvent', 'Actual Call', 'click', listing_id]);
        <?php } ?>
     }
    });
   } );

     function gaTrackForMultiplePhoneNumbers(){
    	<?php
    	if($institute_id && $pageType=='institute')
    	{
    		?>
    		var listing_id  = "<?php echo $institute_id; ?>";
    		<?php
    	}
    	else
    	{
    		?>
    		var listing_id  = "<?php echo $course->getId(); ?>";
    		<?php
    	}
    	?>
        _gaq.push(['_trackEvent', 'Actual Call', 'click', listing_id]);
    }

    function processContactCount(listing_type,listing_id){
        
        var tracking_field='View_Contact_Mobile5';
        var listing_type = listing_type;
        var listing_id =listing_id;
        jQuery.ajax(
			{ 
                        url: '/mobile_listing5/Listing_mobile/increaseContactCount/'+listing_id+'/'+listing_type+'/'+tracking_field,
                        dataType: 'text',
                        success: function(){
                                return ;                        },
                        error: function() {
                                return;
                        }       
                });
    } 

        function logCallActivity(numberToCall){
            <?php
            if($institute_id && $pageType=='institute'){ ?>
                    var listing_id  = "<?php echo $institute_id; ?>";
                    var listing_type = "institute";
                    <?php
            }
            else{
            ?>
                    var listing_id  = "<?php echo $course->getId(); ?>";
                    var listing_type = "course";
            <?php
            }
            ?>
            
            userId = logged_in_userid;
            $.ajax(
                            {
                            url: '/mobile_listing5/Listing_mobile/logCallActivity',
                            type: "POST",
                            data: {'listingType':listing_type, 'listingId':listing_id, 'numberToCall':numberToCall, 'userId':userId },
                            success: function(){ return ; },
                            error: function() { return; }
                    });

        }

</script>
