<?php

$homepagemiddlepanelcache = "HomePageRedesignCache/middlepanel.html";
if(file_exists($homepagemiddlepanelcache) && (time() - filemtime($homepagemiddlepanelcache))<=7200 && !$resetPage && !$_REQUEST['loadFromStatic']){
    echo file_get_contents($homepagemiddlepanelcache);
}else{
    ob_start();
    $this->load->view('home/homepageRedesign/homepage_v3/newHomepageMiddle');
    $pageContent = ob_get_contents();
    ob_end_clean();
    $pageContent = sanitize_output($pageContent);
    echo $pageContent;
    if(!$_REQUEST['loadFromStatic']) {
        $fp=fopen($homepagemiddlepanelcache,'w+');
        flock( $fp, LOCK_EX ); // exclusive lock
        fputs($fp,$pageContent);
        flock( $fp, LOCK_UN ); // release the lock
        fclose($fp);
    }
}
?>