<div class="activity-col">
    <span class="a-titl">ACTIVITY STATS</span>
      
        <?php 
          if(!$publicProfile){
            $privacyFields = "{0:'activitystats'}";
            $privacyFields = htmlspecialchars($privacyFields);

            $publicFlag = false;  
            if($privacyDetails['activitystats'] == 'public') {
              $publicFlag = true;
            }
        ?>
          <?php  $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag)); ?>
        <?php } ?>
      
</div>

<div id='slider1'>
	<a class="bck-ico prev" href="#">&#10094;</a>
	<div class="n-slider viewport">
		<ul class="act-ul overview" id="stats-section">
            <li>
                <a href="javascript:void(0);" id="AllActivity" label="All Activity" class="activity-a active">All </br> Activity<p></p></a>
            </li>

			<?php 

            for ($itr=0; $itr <$statCountSlider; $itr++) { 
               $this->load->view('profileActivityStats',array('stat'=>$stats[$itr]));

            }
                
			?>
		</ul>
    <p class="clr"></p>
	</div>
	<a class="frwd-ico next" href="#">&#10095;</a>
</div>

