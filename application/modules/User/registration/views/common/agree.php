<?php
$text = 'submit';
if(!empty($buttonText)) {
	$text = strtolower($buttonText);
}
?>
By clicking <?=$text?> button, I agree to the <a href="javascript:void(0);" onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition');">terms of services</a> and <a href="javascript:void(0);" onclick="return popitup('https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');">privacy policy</a>.
