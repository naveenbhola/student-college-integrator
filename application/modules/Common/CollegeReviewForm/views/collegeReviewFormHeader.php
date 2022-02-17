<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;

$formData                = array();
$formData['title']       = "Share your College experiences with others – Shiksha.com";

$formData['description'] = $header_description;
if(isset($validateuser[0]['cookiestr'])) {
    $cookieStr             = $validateuser[0]['cookiestr'];
    $cookieArray           = explode('|',$cookieStr);
    $formData['email']     = $cookieArray[0];
    $formData['firstname'] = htmlspecialchars($validateuser[0]["firstname"]);
    $formData['lastname']  = htmlspecialchars($validateuser[0]["lastname"]);
    $formData['mobile']    = htmlspecialchars($validateuser[0]["mobile"]);
}else {
    $formData['email']     = "";
    $formData['firstname'] = "";
    $formData['lastname']  = "";
    $formData['mobile']    = "";
}



		$this->load->view('CollegeReviewForm/reviewFormHeader',$formData);
?>