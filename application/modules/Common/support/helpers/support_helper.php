<?php

function setSupportMessage($message,$type='success')
{
    
    $_SESSION['supportMessage'] = array('message' => $message,'type' => $type);
}

function displaySupportMessage()
{
    
    $supportMessage = $_SESSION['supportMessage'];
    if($supportMessage && is_array($supportMessage) && $supportMessage['message'] && $supportMessage['type']) {
        echo '<div class="support_message_'.$supportMessage['type'].'">';
        echo $supportMessage['message'];
        echo '</div>';
        unset($_SESSION['supportMessage']);
    }
}