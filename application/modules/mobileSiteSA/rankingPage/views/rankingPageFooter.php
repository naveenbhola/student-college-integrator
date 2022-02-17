<?php
$footerComponents = array(
    'js'  =>array('rankingPage'),
    'pages'=>array('commonModule/layers/brochureWithRequestCallback'),
    'trackingPageKeyIdForReg' => 674,
    'commonJSV2'=>true,
    'loadLazyJSFile'=>false,
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
    var img = document.getElementById('beacon_img');
    var randNum = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011007/<?=$contentType?>+<?=$contentId?>';
</script>
<noscript>
    <?php if(!empty($relNext)){ ?>
        <a href="<?php echo $relNext ;?>">Next</a>
    <?php } 
    if(!empty($relPrev)){ ?>
        <a href="<?php echo $relPrev; ?>">Previous</a>
    <?php } ?>
</noscript>