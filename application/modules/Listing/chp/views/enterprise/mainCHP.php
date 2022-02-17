<div class="abroad-cms-content" id="view">
     <div class="abroad-cms-rt-box">

        <div class="abroad-cms-head" style="width: 99%;">
      <?php

        if($tabName=='addCHP'){
                $this->load->view('enterprise/addCHP');
        }else if($tabName=='seoCHP'){
                $this->load->view('enterprise/seoCHP');
        }else if($tabName=='editCHP'){
                $this->load->view('enterprise/contentCHP');
        }else{
                $this->load->view('enterprise/listAllCHP');
        }
        $this->load->view('common/footerNew');
        $this->load->view('examPages/cms/footer');
      ?>
<script type="text/javascript">
    var allowed      = /^[0-9a-zA-Z\s/()&.,#+]+$/;
    var tabName = '<?php echo $tabName;?>';
    var chpType = '<?php echo $chpType;?>';
    var sectionOrder = <?php echo json_encode($sectionOrder);?>;
    var errorMsg   = '<?php echo $errorMsg;?>';
    $j(function(){
        if(tabName == 'addCHP'){
          initAddChp();
        }else if(tabName == 'seoCHP'){
          initSeoChp();
        }else if(tabName == 'editCHP'){
          initContent();
        }
        if(errorMsg){
          alert(errorMsg);
        }
    });
    
</script>

