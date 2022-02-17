<?php
  
    if($personalizedArray){
        $this->load->view('common/personalizedCategorySearchOverlay.php');
    }else{
        $this->load->view('common/categorySearchOverlay.php');
    }
 
?>