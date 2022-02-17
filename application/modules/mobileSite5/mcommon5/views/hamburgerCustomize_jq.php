<?php
	$mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
        $screenWidth = $mobile_details['resolution_width'];
	$screenHeight = $mobile_details['resolution_height'];
?>
<!-- <div class="new-nav"></div> -->
<nav id="mypanel" data-position="left" data-display="overlay" data-role="panel" data-position-fixed="true" style="overflow: auto;z-index:99999;"></nav>
<?php global $isHamburgerMenu;
$isHamburgerMenu = true; ?>