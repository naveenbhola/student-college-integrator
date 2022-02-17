<?php
$footerComponents = array(
    'nonAsyncJSBundle' => 'sa-user-profile',
    'asyncJSBundle'    => 'async-sa-user-profile',
    );
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>
<script>
    $j(window).load(function(){
        <?php if($selfProfile === true){ ?>
        initializeViewProfile();
        <?php } ?>
    });
</script>