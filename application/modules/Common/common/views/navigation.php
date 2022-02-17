<?php

    $this->load->library('common/Personalizer');
    
    $personalizedArray = $this->personalizer->isPersonalized();
    if($personalizedArray['categoryId']){
        if($personalizedArray['isPersonalized'] == 0){
            $personalized = 'nonpersonalized';
        }else{
            $personalized = 'personalized';
        }
    
    }
    
    if($personalizedArray['isPersonalized'] == 0){
        $personalizedArray = false;
    }
    
    if($personalizedArray){
       $this->load->view('common/personalizedGnb',array('personalizedArray' => $personalizedArray)); 
    }else{
        $this->load->view('common/newGNB');
    }    
?>
<script>
personalized = '<?=$personalized?>';
</script>
    
    