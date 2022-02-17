<?php
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:'';
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	if($userId != '')
		$loggedIn = 1;
	else
		$loggedIn = 0;

?>
<div style="margin-left:0px;">			
	<!--BlogNavigation-->
	<div id="powerUserContainer" style="display:block;">
							<?php 

							$this->load->view('enterprise/powerUserListing',array('userAbuse'=>$userAbuse,'abuseDetails'=>$abuseDetails)); 
							?>
	</div>
	<!--BlogNavigation-->
</div>

<!--End_Mid_Panel-->
<br clear="all" />
<!--End_Center-->
