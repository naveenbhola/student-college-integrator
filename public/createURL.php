<?php
if( isset($_REQUEST['email']) && isset($_REQUEST['id']) ){
    $email = $_REQUEST['email'];
    $id = $_REQUEST['id'];
    $parameter = base64_encode('email~'.base64_encode($email).'_reviewerId~'.base64_encode($id));
    $url = "http://www.shiksha.com/college-review-rating-form/".$parameter;
    echo $url;
}
?>
