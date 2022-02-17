<?php

function getParametersForApply($validateuser,$course,$city = '',$locality = '')
{
    $user_info = $validateuser[0];
    list($email,$cookie_val) = explode('|',$user_info['cookiestr']);
            
    $json = '{"'.$course->getId().'":["url","'.$course->getName().'","course"]}';
    $localityJSON = "localityJSON=".'{"'.$course->getId().'":["'.$city.'","'.$locality.'"]}';
    $params = "reqInfofirstName=".$user_info['firstname'].
              "|reqInfolastName=".$user_info['lastname'].
              "|reqInfoPhNumber=".$user_info['mobile'].
              "|reqInfoEmail=".$email.
              "|jSON=".$json.
              "|resolution=1".
              "|coordinates=1".
              "|loginproductname=1".
              "|referer=1".
              "|$localityJSON";
    
    return $params; 
}

function printRating($rating,$starFilledClass = 'rating-star-filled', $starEmptyClass = 'rating-star',$sprite = 'shortlist-sprite') {
    $rating = round($rating);
    $remainingStars = 5 - $rating;
    $html = '';
    for($i = 1;$i<=$rating;$i++) {
        $html .= '<span class="'.$sprite.' '.$starFilledClass.'"></span>';
    }
    for($i = 1;$i<=$remainingStars;$i++) {
        $html .= '<span class="'.$sprite.' '.$starEmptyClass.'"></span>';
    }
    return $html;
}

function printRatingText($rating) {
    $rating = round($rating);
    $text = 'Poor';
    switch($rating) {
        case '1' : $text = 'Poor';
            break;
        case '2' : $text = 'Below Average';
            break;
        case '3' : $text = 'Average';
            break;
        case '4' : $text = 'Above Average';
            break;
        case '5' : $text = 'Excellent';
            break;
    }
    return $text;
}

function checkForValidUrl($text){
   // The Regular Expression filter
    $reg_exUrl = "/(((http|https):\/\/[^\s]+)|((www.)[^\s]+))/i";
    if(preg_match($reg_exUrl,$text)){
      return 'success';
    }else{
      return 'fail';
    }
}